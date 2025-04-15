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
use App\Models\JobExport;
use App\Models\InvoiceImport as Import;
use App\Models\Extend;
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
        if (in_array($va->invoice_type, ['Export', 'EXPORT'])) {
            // var_dump($va->invoice_type);
            // die();
            try {
                $this->payExport($va);
            } catch (\Throwable $th) {
                \Log::error('Payment failed for VA ID ' . $va->id . ': ' . $th->getMessage());
                return false;
            }
        }
        return true;
    }

    private function payExport($va)
    {
        $detils = RefDetail::where('va_id', $va->id)->get();
        // var_dump(json_encode($detils));
        // die();
        foreach ($detils as $detil) {
            $export = Export::find($detil->inv_id);
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
            
            DB::transaction(function() use($export, $noInvoice){
                $export->update([
                    'lunas' => 'Y',
                    'inv_no'=>$noInvoice,
                    'lunas_at'=> Carbon::now(),
                    'invoice_date'=> Carbon::now(),
                ]);
            });
        }
        // return $va;
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
        if (!$latest) {
            return 'OSK0000001';
        }
        $lastInvoice = $latest->inv_no;
        $lastNumber = (int)substr($lastInvoice, 3);
        $nextNumber = $lastNumber + 1;
        return 'OSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
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
}
