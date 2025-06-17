<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Support\Facades\DB;

use Auth;
use Carbon\Carbon;
use DataTables;


use App\Models\Customer;
use App\Models\MasterUserInvoice as MUI;

use App\Models\InvoiceExport as Export;
use App\Models\ExportDetail;
use App\Models\InvoiceHeaderStevadooring;
use App\Models\JobExport;
use App\Models\InvoiceImport as Import;
use App\Models\ImportDetail;
use App\Models\JobImport;
use App\Models\Extend;
use App\Models\ExtendDetail;
use App\Models\JobExtend;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use App\Models\Item;
use App\Models\OrderService as OS;

use App\Models\Payment\RefNumber as VA;
use App\Models\Payment\RefDetail;

class TransactionController extends Controller
{
    
    public function indexList()
    {
        $data['title'] = 'List log virtual account';

        return view('customer.payment.list', $data);
    }

    public function dataList(Request $request)
    {
        $va = VA::where('user_id', Auth::user()->id)->orderByRaw("FIELD('status', 'N', 'Y', 'C', 'E')")->orderBy('created_at', 'desc');
        $data = $va->get();

        return DataTables::Of($data)
        ->addColumn('status', function($data){
            if ($data->status == 'N') {
                return '<span class="badge bg-info text-white">Not Paid</span>';
            }
            if ($data->status == 'C') {
                return '<span class="badge bg-danger text-white">Cancel</span>';
            }
            if ($data->status == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }
            if ($data->status == 'E') {
                return '<span class="badge bg-warning text-white">Expired</span>';
            }
        })
        ->addColumn('cancel', function($data){
            if ($data->status == 'N') {
                return '<button type="button" data-id="'.$data->id.'" class="btn btn-danger" onClick="cancelVA(this)">Cancel</button>';
            }
            return '-';
        })
        ->addColumn('rePay', function($data){
            if ($data->status == 'N') {
                $herf = '/pembayaran/virtual_account-' . $data->id;
                return '<a href="javascript:void(0)" onclick="openWindow(\'' . $herf . '\')" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>';
            }

            return '-';
        })
        ->rawColumns(['status', 'cancel', 'rePay'])
        ->make(true);
    }

    public function indexVA($id)
    {
        $va = VA::find($id);
        $data['title'] = 'Virtual Account Pembayaran Billing ' . $va->invoice_type;
        $data['va'] = $va;
        $data['details'] = RefDetail::where('va_id', $id)->get();

        return view('customer.payment.virtual_account', $data);
    }

    public function cancelVA(Request $request)
    {
        $va = VA::find($request->id);
        if ($va) {
            try {
                $va->update([
                    'status' => 'C',
                    'lunas_time' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data has been update',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data not Found',
            ]);
        }
    }

    public function paymentSuccess($va)
    {
        // var_dump($va->invoice_type);
        // die();
        if (in_array($va->invoice_type, ['Export', 'EXPORT', 'export'])) {
            // var_dump($va->invoice_type);
            // die();
            try {
                $this->payExport($va);
            } catch (\Throwable $th) {
                \Log::error('Payment failed for VA ID ' . $va->id . ': ' . $th->getMessage());
                return false;
            }
        } elseif (in_array($va->invoice_type, ['Import', 'IMPORT', 'import'])) {
            try {
                $this->payImport($va);
            } catch (\Throwable $th) {
                \Log::error('Payment failed for VA ID ' . $va->id . ': ' . $th->getMessage());
                return false;
            }
        } elseif (in_array($va->invoice_type, ['EXTEND', 'Extend', 'extend'])) {
            try {
                $this->payExtend($va);
            } catch (\Throwable $th) {
                \Log::error('Payment failed for VA ID ' . $va->id . ': ' . $th->getMessage());
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    private function payImport($va)
    {
        $detils = RefDetail::where('va_id', $va->id)->get();
        $details = $detils->pluck('inv_id');
        $action = 'lunas';
        $response = $this->processImport($details, $action);
        // var_dump($details, $response);
        // die();
    }

    private function payExport($va)
    {
        $detils = RefDetail::where('va_id', $va->id)->get();
        $details = $detils->pluck('inv_id');
        $action = 'lunas';
        $response = $this->processExport($details, $action);
        // var_dump(json_encode($detils));
        // die();
        
        // return $va;
    }

    private function payExtend($va)
    {
        $detils = RefDetail::where('va_id', $va->id)->get();
        $details = $detils->pluck('inv_id');
        $action = 'lunas';
        $response = $this->processExtend($details, $action);
    }

    private function getInvoiceNoOS()
    {
        $latest = Export::where('inv_type', 'OS')->orderBy('inv_no', 'desc')->first();
        if (!$latest) {
            return 'OS0000001';
        }
        $lastInvoice = $latest->inv_no;
        $lastNumber = (int)substr($lastInvoice, 3);
        $nextNumber = $lastNumber + 1;
        return 'OS' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    private function getInvoiceNoOSK()
    {
        $latest = Export::where('inv_type', 'OSK')->orderBy('inv_no', 'desc')->first();
        $latestStev = InvoiceHeaderStevadooring::orderBy('invoice_no', 'desc')->first();
        if (!$latest && !$latestStev) {
            return 'OSK0000001';
        }
        $lastExportInvoice = $latest ? $latest->inv_no : null;
        $lastStevInvoice = $latestStev ? $latestStev->invoice_no : null;

        $lastExportNumber = $lastExportInvoice ? (int)substr($lastExportInvoice, 3) : 0;
        $lastStevNumber = $lastStevInvoice ? (int)substr($lastStevInvoice, 3) : 0;

        $lastNumber = max($lastExportNumber, $lastStevNumber);
        $nextNumber = $lastNumber + 1;
        return 'OSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    private function getInvoiceNoDSK()
    {
        return DB::transaction(function () {
            $latest = Import::where('inv_type', 'DSK')->lockForUpdate()->orderBy('inv_no', 'desc')->first();
            if (!$latest) {
                return 'DSK0000001';
            }
            $lastNumber = (int)substr($latest->inv_no, 3);
            $nextNumber = $lastNumber + 1;

            return 'DSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
        });
    }

    private function getInvoiceNoDS()
    {
        return DB::transaction(function () {
            $latest = Import::where('inv_type', 'DS')->lockForUpdate()->orderBy('inv_no', 'desc')->first();
        
            if (!$latest) {
                return 'DS0000001';
            }
        
            $lastNumber = (int)substr($latest->inv_no, 2);
            $nextNumber = $lastNumber + 1;
        
            return 'DS' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
        });
    }

    private function getNextJob($lastJobNo)
    {
        if (!$lastJobNo) {
            return 'JOB0000001';
        }
        $lastNumber = (int)substr($lastJobNo, 3);
        $nextNumber = $lastNumber + 1;
        return 'JOB' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    private function getNextJobPerpanjangan($lastJobNo)
    {
        if (!$lastJobNo) {
            return 'JOBP0000001';
        }
        $lastNumber = (int)substr($lastJobNo, 4);
        $nextNumber = $lastNumber + 1;

        // Menghasilkan nomor pekerjaan berikutnya dengan format yang benar
        return 'JOBP' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    private function getNextInvoiceExtend()
    {
        $latest = Extend::orderBy('inv_no', 'desc')->first();
        if (!$latest) {
            return 'P0000001';
        }
        $lastInvoice = $latest->inv_no;
        $lastNumber = (int)substr($lastInvoice, 5);
        $nextNumber = $lastNumber + 1;
        return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    // Manual Payment
    public function manualImport($data)
    {
        $valueData = $data['data']['data'];
        $action = $data['data']['action'];
        $singleInvoice = Import::find($valueData['id']);
        $details = Import::where('id', $valueData['id'])->pluck('id');
        if ($valueData['couple'] == 'Y') {
            $details = Import::where('form_id', $singleInvoice->form_id)->pluck('id');
        }
        // var_dump($details);
        // die();
        $response = $this->processImport($details, $action);
        return $response;
        
    }

    private function processImport($details, $action)
    {
        try {
            $lunas = ($action == 'lunas') ? 'Y' : 'P';
            foreach ($details as $detil) {
                $import = Import::find($detil);
                if ($import->inv_type == 'DSK') {
                    $noInvoice = ($import->inv_no) ? $import->inv_no : $this->getInvoiceNoDSK();
                } elseif ($import->inv_type == 'DS') {
                    $noInvoice = ($import->inv_no) ? $import->inv_no : $this->getInvoiceNoDS();
                } else {
                    return false;
                }

                $piutangAt = ($lunas == 'P') ? ($import->piutang_at ? $import->piutang_at : Carbon::now()) : $import->piutang_at ;
                $lunasAt = ($lunas == 'Y') ? ($import->lunas_at ? $import->lunas_at : Carbon::now()) : $import->lunas_at;
                $invoiceDate = ($lunas == 'Y') ? $lunasAt : $piutangAt;
    
                $containerInvoice = Container::where('form_id', $import->form_id)->get();
                $bigOS = OS::where('id', $import->os_id)->first();
                foreach ($containerInvoice as $cont) {
                    $lastJobNo = JobImport::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
                    $job = JobImport::where('inv_id', $import->id)->where('container_key', $cont->container_key)->first();
                    if (!$job) {
                        $expired = Carbon::parse($cont->SingleCont->disc_date)->addDays(4);
                        $job = DB::transaction(function() use($import, $jobNo, $cont, $expired){
                            return JobImport::create([
                                'inv_id'=>$import->id,
                                'job_no'=>$jobNo,
                                'os_id'=>$import->os_id,
                                'os_name'=>$import->os_name,
                                'cust_id'=>$import->cust_id,
                                'active_to'=>$expired,
                                'container_key'=>$cont->container_key,
                                'container_no'=>$cont->container_no,
                                'ves_id'=>$cont->ves_id,
                            ]);
                        });
                    }
                    $item = Item::where('container_key', $cont->container_key)->first();
                    DB::transaction(function() use($item, $job, $bigOS, $noInvoice){
                        $item->update([
                            'invoice_no'=>$noInvoice,
                            'job_no' => $job->job_no,
                            'order_service' => $bigOS->order,
                            'os_id' => $job->os_id, 
                        ]);
                    });
                }
    
                $importDetils = ImportDetail::where('inv_id', $import->id)->get();
                foreach ($importDetils as $detilI) {
                    DB::transaction(function() use($detilI, $noInvoice, $lunas) {
                        $detilI->update([
                            'lunas' => $lunas,
                            'inv_no'=>$noInvoice,
                        ]);
                    });
                }
                
                DB::transaction(function() use($import, $noInvoice, $lunas, $lunasAt, $piutangAt, $invoiceDate){
                    $import->update([
                        'lunas' => $lunas,
                        'inv_no'=>$noInvoice,
                        'lunas_at' => $lunasAt,
                        'piutang_at' => $piutangAt,
                        'invoice_date'=> $invoiceDate,
                    ]);
                });
            }
            return response()->json([
                'success' => true,
                'message' => 'Data udpated'
            ]);
        } catch (\Throwable $th) {
           return response()->json([
                'success' => false,
                'message' => 'Something wrong in :' . $th->getMessage(),
            ]);
        }
    }

    public function manualExtend($data)
    {
        $valueData = $data['data']['data'];
        $action = $data['data']['action'];
        $singleInvoice = Extend::find($valueData['id']);
        $details = Extend::where('id', $valueData['id'])->pluck('id');
        if ($valueData['couple'] == 'Y') {
            $details = Extend::where('form_id', $singleInvoice->form_id)->pluck('id');
        }
        // var_dump($details);
        // die();
        $response = $this->processExtend($details, $action);
        return $response;
    }

    private function processExtend($details, $action)
    {
        try {
            foreach ($details as $detil) {
                $lunas = ($action == 'lunas') ? 'Y' : 'P';
                $extend = Extend::find($detil);
                if ($extend->Form->tipe == 'P') {
                    $query = Extend::find($extend->Form->do_id);
                    $jobQuery = JobExtend::where('inv_id', $extend->Form->do_id);
                }elseif ($extend->Form->tipe == 'I') {
                    $query = Import::find($extend->Form->do_id);
                    $jobQuery = JobImport::where('inv_id', $extend->Form->do_id);
                }

                $piutangAt = ($lunas == 'P') ? ($extend->piutang_at ? $extend->piutang_at : Carbon::now()) : $extend->piutang_at ;
                $lunasAt = ($lunas == 'Y') ? ($extend->lunas_at ? $extend->lunas_at : Carbon::now()) : $extend->lunas_at;
                $invoiceDate = ($lunas == 'Y') ? $lunasAt : $piutangAt;


                $noInvoice = ($extend->inv_no) ? $extend->inv_no : 'DS-'.$this->getNextInvoiceExtend();
                $containerInvoice = Container::where('form_id', $extend->form_id)->get();
                $bigOS = OS::where('id', $extend->os_id)->first();
                foreach ($containerInvoice as $cont) {
                    $lastJobNo = JobExtend::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJobPerpanjangan($lastJobNo);
                    $job = JobExtend::where('inv_id', $extend->id)->where('container_key', $cont->container_key)->first();
                    if (!$job) {
                        $job = DB::transaction(function() use($extend, $jobNo, $cont){
                            return JobExtend::create([
                                'inv_id'=>$extend->id,
                                'job_no'=>$jobNo,
                                'os_id'=>$extend->os_id,
                                'os_name'=>$extend->os_name,
                                'cust_id'=>$extend->cust_id,
                                'active_to'=>$extend->expired_date,
                                'container_key'=>$cont->container_key,
                                'container_no'=>$cont->container_no,
                                'ves_id'=>$cont->ves_id,
                            ]);
                        });
                    }

                    $oldJob = $jobQuery->where('container_key', $cont->container_key)->update(['extend_flag' => 'Y']);

                    $item = Item::where('container_key', $cont->container_key)->first();
                    DB::transaction(function() use($item, $job, $bigOS, $noInvoice){
                        $item->update([
                            'invoice_no'=>$noInvoice,
                            'job_no' => $job->job_no,
                            'order_service' => $bigOS->order,
                        ]);
                    });
                }
    
                $extendDetils = ExtendDetail::where('inv_id', $extend->id)->get();
                foreach ($extendDetils as $detilE) {
                    DB::transaction(function() use($detilE, $noInvoice, $lunas) {
                        $detilE->update([
                            'lunas' => $lunas,
                            'inv_no'=>$noInvoice,
                        ]);
                    });
                }
                
                DB::transaction(function() use($extend, $noInvoice, $lunas, $lunasAt, $piutangAt, $invoiceDate){
                    $extend->update([
                        'lunas' => $lunas,
                        'inv_no'=>$noInvoice,
                        'lunas_at' => $lunasAt,
                        'piutang_at' => $piutangAt,
                        'invoice_date'=> $invoiceDate,
                    ]);
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'Data udpated'
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return false;

        }
    }

    public function manualExport($data)
    {
        $valueData = $data['data']['data'];
        $action = $data['data']['action'];
        $singleInvoice = Export::find($valueData['id']);
        $details = Export::where('id', $valueData['id'])->pluck('id');
        if ($valueData['couple'] == 'Y') {
            $details = Export::where('form_id', $singleInvoice->form_id)->pluck('id');
        }
        // var_dump($details);
        // die();
        $response = $this->processExport($details, $action);
        return $response;
        
    }

    private function processExport($details, $action)
    {
        try {
            // var_dump($details, $action);
            // die();
            foreach ($details as $detil) {
                $lunas = ($action == 'lunas') ? 'Y' : 'P';
                $export = Export::find($detil);
                // var_dump($export->inv_type);
                // die();
                if ($export->inv_type == 'OSK') {
                    $noInvoice = $this->getInvoiceNoOSK();
                } elseif ($export->inv_type == 'OS') {
                    $noInvoice = $this->getInvoiceNoOS();
                } else {
                    return false;
                }
    
                $containerInvoice = Container::where('form_id', $export->form_id)->get();
                $bigOS = OS::where('id', $export->os_id)->first();
                foreach ($containerInvoice as $cont) {
                    $lastJobNo = JobExport::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
                    $job = JobExport::where('inv_id', $export->id)->where('container_key', $cont->container_key)->first();
                    if (!$job) {
                        $job = DB::transaction(function() use($export, $jobNo, $cont){
                            return JobExport::create([
                                'inv_id'=>$export->id,
                                'job_no'=>$jobNo,
                                'os_id'=>$export->os_id,
                                'os_name'=>$export->os_name,
                                'cust_id'=>$export->cust_id,
                                'active_to'=>$export->expired_date,
                                'container_key'=>$cont->container_key,
                                'container_no'=>$cont->container_no,
                                'ves_id'=>$cont->ves_id,
                            ]);
                        });
                    }
                    $item = Item::where('container_key', $cont->container_key)->first();
                    DB::transaction(function() use($item, $job, $bigOS, $noInvoice){
                        $item->update([
                            'invoice_no'=>$noInvoice,
                            'job_no' => $job->job_no,
                            'order_service' => $bigOS->order,
                        ]);
                    });
                }
    
                $exportDetils = ExportDetail::where('inv_id', $export->id)->get();
                foreach ($exportDetils as $detilE) {
                    DB::transaction(function() use($detilE, $noInvoice, $lunas) {
                        $detilE->update([
                            'lunas' => $lunas,
                            'inv_no'=>$noInvoice,
                        ]);
                    });
                }
                
                DB::transaction(function() use($export, $noInvoice, $lunas){
                    $export->update([
                        'lunas' => $lunas,
                        'inv_no'=>$noInvoice,
                        'lunas_at'=> Carbon::now(),
                        'invoice_date'=> Carbon::now(),
                    ]);
                });
            }
            return response()->json([
                'success' => true,
                'message' => 'Data udpated'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong In ' . $th->getMessage(),
            ]);

        }
    }
}
