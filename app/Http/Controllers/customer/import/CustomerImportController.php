<?php

namespace App\Http\Controllers\customer\import;

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\InvoiceImport as Import;
use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\InvoiceForm as Form;
use App\Models\ImportDetail as Detail; 
use App\Models\ContainerInvoice as Container;
use App\Models\MasterUserInvoice as MUI;
use App\Models\Customer;
use App\Models\DOonline;
use App\Models\Item;

use App\Models\Payment\RefNumber as VA;
use App\Models\Payment\RefDetail;

use App\Models\JobImport;
use App\Models\VVoyage;

use DataTables;

use Auth;
use Carbon\Carbon;
class CustomerImportController extends CustomerMainController
{
    // public function indexUnpaid()
    // {
    //     $data['title'] = 'Import Unpaid List';

    //     return view('customer.import.detil.unpaid', $data);
    // }

    // public function dataUnpaid(Request $request)
    // {
    //     $unpaids = $this->import->with(['customer', 'service', 'form'])->where('lunas', 'N'); // Removed `query()`
    //     return DataTables::of($unpaids)->make(true);
    // }

    // public function indexPiutang()
    // {
    //     $data['title'] = 'Import Piutang List';

    //     return view('customer.import.detil.piutang', $data);
    // }

    // public function dataPiutang(Request $request)
    // {
    //     // var_dump($request->osId);
    //     // die();
    //     $unpaids = $this->import->with(['customer', 'service', 'form'])->where('lunas', 'P'); // Removed `query()`
    //     return DataTables::of($unpaids)->make(true);
    // }
    
    // public function indexService(Request $request)
    // {
    //     $os = OS::find($request->id);
    //     $data['title'] = 'Import '.$os->name.' List'; 

    //     $data['osId'] = $os->id;
    //     return view('customer.import.detil.service', $data);
    // }

    // public function dataService(Request $request)
    // {
    //     $invoice = $this->import->whereHas('service', function ($query) {
    //         $query->where('ie', '=', 'I');
    //     })->whereNot('form_id', '=', '')->orderBy('order_at', 'desc');
        
    //     if ($request->has('type')) {
    //         if ($request->type == 'unpaid') {
    //             $invoice = $this->import->whereHas('service', function ($query) {
    //                 $query->where('ie', '=', 'I');
    //             })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'desc');
    //         }

    //         if ($request->type == 'piutang') {
    //             $invoice = $this->import->whereHas('service', function ($query) {
    //                 $query->where('ie', '=', 'I');
    //             })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'desc');
    //         }
    //     }

    //     if ($request->has('os_id')) {
    //         $invoice = $this->import->whereNot('form_id', '=', '')->where('os_id', $request->os_id)->orderBy('order_at', 'desc')->orderBy('lunas', 'asc');
    //     }

    //     $inv = $invoice->get();
    //     return DataTables::of($inv)
    //     ->addColumn('proforma', function($inv) {
    //         return $inv->proforma_no ?? '-';
    //     })
    //     ->addColumn('customer', function($inv){
    //         return $inv->cust_name ?? '-';
    //     })
    //     ->addColumn('service', function($inv){
    //         return $inv->os_name ?? '-';
    //     })
    //     ->addColumn('type', function($inv){
    //         return $inv->inv_type ?? '-';
    //     })
    //     ->addColumn('orderAt', function($inv){
    //         return $inv->order_at ?? '-';
    //     })
    //     ->addColumn('status', function($inv){
    //         if ($inv->lunas == 'N') {
    //             return '<span class="badge bg-danger text-white">Not Paid</span>';
    //         }elseif ($inv->lunas == 'P') {
    //             return '<span class="badge bg-warning text-white">Piutang</span>';
    //         }elseif ($inv->lunas == 'Y') {
    //             return '<span class="badge bg-success text-white">Paid</span>';
    //         }elseif ($inv->lunas == 'C') {
    //             return '<span class="badge bg-danger text-white">Canceled</span>';
    //         }
    //     })
    //     ->addColumn('pranota', function($inv){
    //         return '<a type="button" href="/pranota/import-'.$inv->inv_type.$inv->id.'" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>';
    //     })
    //     ->addColumn('invoice', function($inv){
    //         if ($inv->lunas == 'N') {
    //             return '<span class="badge bg-info text-white">Paid First!!</span>';
    //         }elseif ($inv->lunas == 'C') {
    //             return '<span class="badge bg-danger text-white">Canceled</span>';
    //         }else {
    //             return '<a type="button" href="/invoice/import-'.$inv->inv_type.$inv->id.'" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>';
    //         }
    //     })
    //     ->addColumn('job', function($inv){
    //         if ($inv->lunas == 'N') {
    //             return '<span class="badge bg-info text-white">Paid First!!</span>';
    //         }elseif ($inv->lunas == 'C') {
    //             return '<span class="badge bg-danger text-white">Canceled</span>';
    //         }else {
    //             return '<a type="button" href="/invoice/job/import-'.$inv->id.'" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>';
    //         }
    //     })
    //     ->addColumn('action', function($inv){
    //         if ($inv->lunas == 'N' || $inv->lunas == 'P') {
    //             return '<button type="button" id="pay" data-id="'.$inv->id.'" class="btn btn-sm btn-success pay"><i class="fa fa-cogs"></i></button>';
    //         }elseif ($inv->lunas == 'Y') {
    //             return '<span class="badge bg-success text-white">Paid</span>';
    //         }else {
    //             return '<span class="badge bg-danger text-white">Canceled</span>';
    //         }
    //     })
    //     ->addColumn('payFlag', function($inv){
    //         if ($inv->lunas == 'N') {
    //             if ($inv->pay_flag == 'Y') {
    //                 return '<div class="spinner-border text-primary" role="status">
                            
    //                     </div> <span class="">Waiting Approved</span>';
    //             }elseif ($inv->pay_flag == 'C') {
    //                 return '<span class="badge bg-danger text-white">Di Tolak</span>';
    //             }else {
    //                 return '-';
    //             }
    //         }else {
    //             return '-';
    //         }
    //     })
    //     ->addColumn('delete', function($inv){
    //         if ($inv->lunas == 'N') {
    //             return '<button type="button" data-id="'.$inv->form_id.'" class="btn btn-sm btn-danger Delete"><i class="fa fa-trash"></i></button>';
    //         }else {
    //             return '-';
    //         }
    //     })
    //     ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'delete', 'payFlag'])
    //     ->make(true);
    // }

    public function indexData()
    {
        $data['title'] = 'List Data Invoice Import';

        return view('customer.import.detil.listInvoice', $data);
    }

    public function listData(Request $request)
    {
        $invoice = $this->import->whereHas('service', function ($query) {
            $query->where('ie', '=', 'I');
        })->whereNot('form_id', '=', '')->orderBy('order_at', 'desc');
        
        if ($request->has('type')) {
            if ($request->type == 'unpaid') {
                $invoice = $this->import->whereHas('service', function ($query) {
                    $query->where('ie', '=', 'I');
                })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'desc');
            }
        
            if ($request->type == 'piutang') {
                $invoice = $this->import->whereHas('service', function ($query) {
                    $query->where('ie', '=', 'I');
                })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'desc');
            }
        }
        
        if ($request->has('os_id')) {
            $invoice = $this->import->whereNot('form_id', '=', '')->where('os_id', $request->os_id)->orderBy('order_at', 'desc')->orderBy('lunas', 'asc');
        }
        
        $inv = $invoice->get();
        return DataTables::of($inv)
        ->addColumn('proforma', function($inv) {
            return $inv->proforma_no ?? '-';
        })
        ->addColumn('customer', function($inv){
            return $inv->cust_name ?? '-';
        })
        ->addColumn('service', function($inv){
            return $inv->os_name ?? '-';
        })
        ->addColumn('type', function($inv){
            return $inv->inv_type ?? '-';
        })
        ->addColumn('orderAt', function($inv){
            return $inv->order_at ?? '-';
        })
        ->addColumn('status', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-danger text-white">Not Paid</span>';
            }elseif ($inv->lunas == 'P') {
                return '<span class="badge bg-warning text-white">Piutang</span>';
            }elseif ($inv->lunas == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('pranota', function($inv){
            return '<a type="button" href="/pranota/import-'.$inv->inv_type.$inv->id.'" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>';
        })
        ->addColumn('invoice', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/import-'.$inv->inv_type.$inv->id.'" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>';
            }
        })
        ->addColumn('job', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/job/import-'.$inv->id.'" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>';
            }
        })
        ->addColumn('action', function($inv){
            if ($inv->lunas == 'N' || $inv->lunas == 'P') {
                return '<button type="button" id="pay" data-id="'.$inv->id.'" class="btn btn-sm btn-success" onClick="searchToPay(this)"><i class="fa fa-cogs"></i></button>';
            }elseif ($inv->lunas == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }else {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('payFlag', function($inv){
            if ($inv->lunas == 'N') {
                if ($inv->pay_flag == 'Y') {
                    return '<div class="spinner-border text-primary" role="status">
                            
                        </div> <span class="">Waiting Approved</span>';
                }elseif ($inv->pay_flag == 'C') {
                    return '<span class="badge bg-danger text-white">Di Tolak</span>';
                }else {
                    return '-';
                }
            }else {
                return '-';
            }
        })
        ->addColumn('cancel', function($inv){
            if ($inv->lunas == 'N') {
                return '<button type="button" data-id="'.$inv->form_id.'" class="btn btn-sm btn-danger" onClick="cancelInvoice(this)">Cancel</button>';
            } elseif (in_array($inv->lunas, ['Y', 'P'])) {
                return '<span class="badge bg-info text-white">Paid</span>';
            } else {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'cancel', 'payFlag'])
        ->make(true);
    }

    public function formList()
    {
        $data['title'] = "Form Management || Import";

        return view('customer.import.form.listIndex', $data);
    }

    public function formData()
    {
        $form = $this->form->with(['customer', 'Kapal', 'doOnline', 'service'])->where('i_e', 'I')->where('done', '=', 'N');
        return DataTables::of($form)->make(true);
    }

    public function formStoreFirst(Request $request)
    {
        try {
            $mui = MUI::where('user_id', $this->userId)->get();
            if ($mui->isEmpty()) {
                return redirect()->back()->with('error', 'Anda belum memiliki list customer, hubungi admin!!!');
            }

            $oldForm = Form::where('i_e', 'I')
            ->where('user_id', $this->userId)
            ->where('done', 'N')
            ->whereNull('expired_date')
            ->whereNull('os_id')
            ->whereNull('cust_id')
            ->whereNull('do_id')
            ->whereNull('ves_id')
            ->whereNull('disc_date')
            ->first();
            if ($oldForm) {
                return redirect('/customer-import/formFirstStepId='.$oldForm->id)->with('success', 'Continue Your Step');
            }
            $form = Form::create([
                'i_e' => 'I',
                'user_id' => Auth::user()->id,
                'done' => 'N',
            ]);

            // dd($form);
            return redirect('/customer-import/formFirstStepId='.$form->id)->with('success', 'Continue Your Step');
        } catch (\Throwable $th) {
            return redirect( )->back()->with('error', 'Something Wrong!! '. $th->getMessage());
        }
    }

    public function firstStepIndex($id)
    {
        $data['title'] = 'Form Import';
        $data['form'] = Form::find($id);
        $data['orderService'] = OS::where('ie', '=', 'I')->get();
        $data['do_online'] = DOonline::where('active', 'Y')->get();
        $data['ves'] = VVoyage::where('deparature_date', '>=', Carbon::now())->get();
        $data['containerInvoice'] = Container::where('form_id', $id)->get();
        
        $mui = MUI::where('user_id', $this->userId)->get();
        $customerId = $mui->pluck('customer_id')->toArray();
        $data['customer'] = Customer::whereIn('id', $customerId)->get();

        $data['expired'] = Carbon::now()->addDays(4)->format('Y-m-d');
        // dd($data['expired']);
        // dd($mui, $customerId, $data['customer']);
        

        return view('customer.import.form.firstStep', $data);
    }

    public function storeFormStep1(Request $request)
    {
        
        $selctedCont = Item::whereIn('container_key', $request->container)->get();
        
        $disc = Carbon::parse($request->disc_date);
        $expired = Carbon::parse($request->exp_date);

        if ($expired == null || $disc >= $expired) {
            return redirect()->back()->with('error', 'Rencana Keluar Harus Lebih Besar');
        }

        $interval = $disc->diff($expired);
        $jumlahHari = ($interval->days) + 1;

        if ($jumlahHari <= 4) {
            return redirect()->back()->with('error', 'Rencana Keluar Minimal 5 hari Setelah Disc Date');
        }
        $massa2 = null;
        $massa3 = null;

        if ($jumlahHari > 5) {
            $massa2 = min($jumlahHari -5, 5);

            if ($jumlahHari > 10) {
                $massa3 = $jumlahHari -10;
            }
        }
        // dd($request->all(), $disc, $expired, $interval, $jumlahHari, $massa2, $massa3);

        try {
            $doOnline = DOonline::find($request->do_number_auto);
            $cust = Customer::find($request->customer);

            if ($doOnline->customer_code != $cust->code) {
                return redirect()->back()->with('error', 'Nomor DO dengan Cutomer tidak singkron');
            }

            $form = Form::find($request->form_id);
            $form->update([
                'expired_date' => $request->exp_date,
                'os_id' => $request->order_service,
                'cust_id' => $request->customer,
                'do_id' => $request->do_number_auto,
                'ves_id' => $request->ves_id,
                'disc_date' => $request->disc_date,
                'massa2' => $massa2,
                'massa3' => $massa3,
            ]);

            $oldContainer = Container::where('form_id', $form->id)->delete();

            foreach ($selctedCont as $item) {
                $newCont = Container::create([
                    'container_key'=>$item->container_key,
                    'container_no'=>$item->container_no,
                    'ctr_size'=>$item->ctr_size,
                    'ctr_status'=>$item->ctr_status,
                    'form_id'=>$form->id,
                    'ves_id'=>$item->ves_id,
                    'ves_name'=>$item->ves_name,
                    'ctr_type'=>$item->ctr_type,
                    'ctr_intern_status'=>$item->ctr_intern_status,
                    'gross'=>$item->gross,
                ]);
            }

            return redirect('/customer-import/preinvoice/'.$form->id)->with('success', 'Silahkan lanjutkan ke tahap berikut nya');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Opsss somtheing wrong : ' . $th->getMessage());
        }
        
    }

    public function preinvoice($id)
    {
        $form = Form::find($id);
        $data['title'] = "Preinvoice Import| " . $form->Service->name;
        $data['form'] = $form;

        $containers = Container::where('form_id', $form->id)->get();
        $data['listContainers'] = $containers;

        $service = OS::find($form->os_id);
        $serviceDetail = OSDetail::where('os_id', $service->id)->orderBy('master_item_name', 'asc')->get();

        $serviceDSK = $serviceDetail->where('type', 'DSK');
        $data['serviceDSK'] = $serviceDSK;
        $data['dsk'] = $serviceDSK->isNotEmpty() ? 'Y' : 'N'; 

        $serviceDS = $serviceDetail->where('type', 'DS');
        $data['serviceDS'] = $serviceDS;
        $data['ds'] = $serviceDS->isNotEmpty() ? 'Y' : 'N';

        $data['size'] = $containers->pluck('ctr_size')->unique();
        $data['status'] = $containers->pluck('ctr_status')->unique();

        $tarif = MT::where('os_id', $service->id)->select('ctr_size', 'ctr_status')->get();
        $checkTarif = $tarif->toArray();
        $invalidContainer = $containers->filter(function($container) use ($checkTarif){
            return !in_array(['ctr_size'=>$container->ctr_size, 'ctr_status'=>$container->ctr_status], $checkTarif);
        });
        if ($invalidContainer->isNotEmpty()) {
            $invalidContainerNumber = $invalidContainer->pluck('container_no')->implode(', ');
            return redirect()->back()->with('error', 'Hubungi Admin, terdapat container yang belum memiliki master tarif :' . $invalidContainerNumber);
        }
        $groupContainer = $containers->unique('ctr_size', 'ctr_status')->pluck('ctr_size', 'ctr_status');
        $data['sizes'] = $containers->pluck('ctr_size')->unique();
        $data['statuses'] = $containers->pluck('ctr_status')->unique();
        $data['groupContainer'] = $groupContainer;

        $data['tarif'] = MT::where('os_id', $service->id)->get();
        $data['tarifDetail'] = MTDetail::whereIn('master_tarif_id', $data['tarif']->pluck('id'))->get();
        // dd($service, $data['dsk'], $data['size'], $data['status'], $checkTarif, $invalidContainer);

        return view('customer.import.form.preinvoice', $data)->with('success', 'Seilahkan lanjutkan ke tahap berikut nya');
    }

    public function createInvoice(Request $request)
    {
        // dd($request->all());

        $form = Form::find($request->formId);
        $service = OS::find($form->os_id);
        $containers = Container::where('form_id', $form->id)->get();
        $groupContainer = $containers->unique('ctr_size', 'ctr_status')->pluck('ctr_size', 'ctr_status');
        $sizes = $containers->pluck('ctr_size')->unique();
        $statuses = $containers->pluck('ctr_status')->unique();

        $serviceDetail = OSDetail::where('os_id', $service->id)->get();
        $tarif = MT::where('os_id', $service->id)->get();
        $tarifDetail = MTDetail::whereIn('master_tarif_id', $tarif->pluck('id'))->get();
        try {
            if ($serviceDetail->where('type', 'DSK')->isNotEmpty()) {
                $this->processInvoice($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetail->where('type', 'DSK'), $tarif, $tarifDetail, 'DSK');
            }
    
            if ($serviceDetail->where('type', 'DS')->isNotEmpty()) {
                $this->processInvoice($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetail->where('type', 'DS'), $tarif, $tarifDetail, 'DS');
            }

            $form->update(['done'=>'Y']);
            foreach ($containers as $cont) {
                $item = Item::find($cont->container_key);
                $item->selected_do = 'Y';
                $item->os_id = $form->os_id;
                $item->order_service = $service->order;
                $item->save();
            }

            return redirect('/customer-import/indexData')->with('success', 'Invoice berhasil di buat');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something Wrong : '.$th->getMessage());
        }
    }

    private function processInvoice($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetails, $tarif, $tarifDetail, $type)
    {
        DB::transaction(function () use ($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetails, $tarif, $tarifDetail, $type) {
            $header = $this->createInvoiceHeader($request, $form, $service, $type);

            foreach ($sizes as $size) {
                foreach ($statuses as $status) {
                    $jumlahCont = $containers->where('ctr_size', $size)->where('ctr_status', $status)->count();
                    if ($jumlahCont > 0) {
                        foreach ($serviceDetails as $detail) {
                            $this->createInvoiceDetail($header, $form, $detail, $tarif, $tarifDetail, $size, $status, $jumlahCont);
                        }
                    }
                }
            }
        });
    }

    private function createInvoiceHeader($request, $form, $service, $type)
    {
        return Import::create([
            'form_id' => $form->id,
            'inv_type' => $type,
            'proforma_no' => $this->getNextProformaNumber(),
            'cust_id' => $form->customer->id,
            'cust_name' => $form->customer->name,
            'fax' => $form->customer->fax,
            'npwp' => $form->customer->npwp,
            'alamat' => $form->customer->alamat,
            'os_id' => $service->id,
            'os_name' => $service->name,
            'total' => $request->input("total$type"),
            'admin' => $request->input("admin$type"),
            'pajak' => $request->input("ppn$type"),
            'grand_total' => $request->input("grandTotal$type"),
            'order_by' => Auth::user()->name,
            'order_at' => Carbon::now(),
            'disc_date' => $form->disc_date,
            'expired_date' => $form->expired_date,
            'do_no' => $form->doOnline->do_no,
            'user_id' => Auth::user()->id,
            'lunas' => 'N',
        ]);
    }

    private function createInvoiceDetail($header, $form, $detail, $tarif, $tarifDetail, $size, $status, $jumlahCont)
    {
        $selectedTarif = $tarif->where('ctr_size', $size)->where('ctr_status', $status)->first();
        $tarifDSK = $tarifDetail->where('master_tarif_id', $selectedTarif->id)->where('master_item_id', $detail->master_item_id)->first();

        $jumlahHari = $this->calculateDays($form, $detail);
        $kode = ($detail->kode != 'PASSTRUCK') ? $detail->kode . $size : 'PASSTRUCK';
        $harga = $jumlahCont * $jumlahHari * $tarifDSK->tarif;
        // dd($detail);
        Detail::create([
            'inv_id' => $header->id,
            'inv_type' => $header->inv_type,
            'keterangan' => $form->service->name,
            'ukuran' => ($detail->MItem->count_by != 'O') ? $size : '0',
            'ctr_status' => ($detail->MItem->count_by != 'O') ? $status : '-',
            'jumlah' => ($detail->MItem->count_by != 'O') ? $jumlahCont : 1,
            'satuan' => 'unit',
            'expired_date' => $form->expired_date,
            'order_date' => $header->order_at,
            'lunas' => 'N',
            'cust_id' => $form->cust_id,
            'cust_name' => $form->customer->name,
            'os_id' => $form->os_id,
            'jumlah_hari' => 0,
            'master_item_id' => $detail->master_item_id,
            'master_item_name' => $detail->master_item_name,
            'kode' => $kode,
            'tarif' => $tarifDSK->tarif,
            'total' => $harga,
            'form_id' => $form->id,
            'count_by' => $detail->MItem->count_by,
        ]);
    }

    private function calculateDays($form, $detail)
    {
        if ($detail->MItem->count_by == 'T') {
            return ($detail->MItem->massa == 3) ? $form->massa3 : (($detail->MItem->massa == 2) ? $form->massa2 : 1);
        }
        return 1;
    }


    private function getNextProformaNumber()
    {
        $latestProforma = Import::orderBy('proforma_no', 'desc')->first();

        if (!$latestProforma) {
            return 'P0000001';
        }

        $lastProformaNumber = $latestProforma->proforma_no;
        $lastNumber = (int)substr($lastProformaNumber, 1);
        $nextNumber = $lastNumber + 1;
        return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    public function deleteInvoice($formId)
    {
        try {
            $form = Form::find($formId);
            $headerLunas = Import::where('form_id', $form->id)->get();
            // var_dump($headerLunas);
            // die();
            if ($headerLunas->isNotEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pranota sudah terbit, tidak dapat hapus invoice',
                ]);
            }
           
    
            $containerInvoice = Container::where('form_id', $form->id)->get();

            $item = Item::whereIn('container_key', $containerInvoice->pluck('container_key'))->update([
                'selected_do' => 'N',
                'os_id' => null,
                'order_service' => null,
            ]);
    
            Container::where('form_id')->delete();
            $header = Import::where('form_id', $formId)->delete();
            $detil = Detail::where('form_Id', $formId)->delete();
    
            $form->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong : '.$th->getMessage(),
            ]);
            
        }
        
    }

    public function payButton($id)
    {
        // var_dump($id);
        // die();
        try {
            //code...
            $import = Import::find($id);
    
            return response()->json([
                'success' => true,
                'data' => $import,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Oopss something wrong : ' . $th->getMessage(),
            ]);
        }
    }

    public function payImportFromCust(Request $request)
    {
        // Hapus `dd($request->all());` setelah debugging
        if (!$request->hasFile('bukti_bayar')) {
            return back()->with('error', 'Anda Wajib Memasukkan Bukti Pembayaran');
        }

        $import = Import::find($request->id);
        if (!$import) {
            return back()->with('error', 'Data Import tidak ditemukan');
        }

        foreach ($request->file('bukti_bayar') as $photo) {
            $fileName = time() . '_' . $photo->getClientOriginalName(); // Tambahkan timestamp untuk menghindari nama duplikat
            $filePath = 'bukti_bayar/import/' . $import->id;

            // Simpan ke storage
            $photo->storeAs($filePath, $fileName, 'public');

            // Simpan ke database
           $import->update([
            'pay_flag' => 'Y',
           ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah');
    }

    public function cancelInvoice(Request $request)
    {
        // var_dump($request->all());
        // die();

        $headers = Import::where('form_id', $request->formId)->get();
        $checkLunas = $headers->whereIn('lunas', ['Y', 'P']);
        if ($checkLunas->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat melakukan pembatalan, sudah ada invoice yang lunas',
            ]);
        }
        try {
            foreach ($headers as $header) {
                $detils = Detail::where('inv_id', $header->id)->get();
                if ($detils->isNotEmpty()) {
                        foreach ($detils as $detil) {
                            $detil->update([
                                'lunas' => 'C',
                            ]);
                        }
                }
                $header->update([
                    'lunas' => 'C',
                ]);
                $va = VA::where('virtual_account', $header->va)->first();
                if ($va) {
                    $va->update([
                        'status' => 'C',
                        'lunas_time' => Carbon::now(),
                    ]);
                }
            }

            $containers = Container::where('form_id', $request->formId)->get();
            if ($containers->isNotEmpty()) {
                foreach ($containers as $container) {
                    $item = Item::find($container->container_key);
                    if ($item) {
                        $item->update([
                            'selected_do' => 'N',
                            'os_id' => null,
                            'order_service' => null,
                        ]);
                    }
                }
            }
        
            return response()->json([
                'success' => true,
                'message' => 'Data updated',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }

    }

    public function searchToPay(Request $request)
    {
        $import = Import::find($request->id);
        $anotherInvoice = Import::where('form_id', $import->form_id)->whereNot('id', $import->id)->where('lunas', 'N')->first();
        if ($import) {
            return response()->json([
                'success' => true,
                'data' => $import,
                'another' => ($anotherInvoice) ? true : false,
                'anotherData' => ($anotherInvoice) ? $anotherInvoice : null,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data not Found',
            ]);
        }
    }

    public function createVA(Request $request)
    {
        // Checking VA
        $checking = $this->checkingOldVA($request);
        if ($checking) {
            return $checking;
        }

        $import = Import::find($request->id);
        $billingAmount = $import->grand_total;
        if ($request->couple == 'Y') {
            $otheExport = Import::where('form_id', $import->form_id)->whereNot('id', $import->id)->first();
            $billingAmount += $otheExport->grand_total;
        }
        
        try {
            $newVa = DB::transaction(function() use($billingAmount, $import){
                return VA::create([
                    'virtual_account' => $this->virtualAccount(),
                    'expired_va' => Carbon::now()->addHours(3),
                    'invoice_type' => 'Import',
                    'customer_name' => $import->cust_name,
                    'customer_id' => $import->cust_id,
                    'description' => $import->os_name,
                    'billing_amount' => $billingAmount,
                    'status' => 'N',
                    'user_id' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            });
            $this->createVaDetail($newVa, $request);

            return response()->json([
                'success' => true,
                'message' => 'VA Berhasil dibuat',
                'data' => $newVa,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong in : ' . $th->getMessage(),
            ]);
        }   
    }

    private function virtualAccount()
    {
        $prefix = '56732'; // kode perusahaan/bank Mandiri
        do {
            // Generate 16 digit angka random
            $randomDigits = str_pad(mt_rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
            $generateVa = $prefix . $randomDigits;
        } while (VA::where('virtual_account', $generateVa)->exists());

        return $generateVa;
    }

    private function createVaDetail($newVa, $request)
    {
        $import = Import::find($request->id);
        DB::transaction(function() use($newVa, $import){
            RefDetail::create([
                'va_id' => $newVa->id,
                'inv_id' => $import->id,
                'invoice_ie' => 'I',
                'proforma_no' => $import->proforma_no,
                'invoice_type' => $import->inv_type,
                'amount' => $import->grand_total,
            ]);

            $import->update([
                'va' => $newVa->virtual_account,
            ]);
        });

        if ($request->couple == 'Y') {
            $otheExport = Import::where('form_id', $import->form_id)->whereNot('id', $import->id)->first();
            if ($otheExport) {
                DB::transaction(function() use($newVa, $otheExport){
                    RefDetail::create([
                        'va_id' => $newVa->id,
                        'inv_id' => $otheExport->id,
                        'invoice_ie' => 'I',
                        'proforma_no' => $otheExport->proforma_no,
                        'invoice_type' => $otheExport->inv_type,
                        'amount' => $otheExport->grand_total,
                    ]);
                    $otheExport->update([
                        'va' => $newVa->virtual_account,
                    ]);
                });
            }
        }
    }

    private function checkingOldVA($request)
    {
        $import = Import::find($request->id);
        $oldVa = VA::where('virtual_account', $import->va)->first();
        if ($oldVa && !in_array($oldVa->status, ['C', 'Y'])) {
            if (Carbon::parse($oldVa->expired_va)->greaterThan(Carbon::now())) {
                return response()->json([
                    'success' => false,
                    'message' => 'VA untuk invoice yg anda pilih masih aktif, dengan nomor VA : ' . $oldVa->virtual_account,
                    'status' => 30,
                    'data' => $oldVa,
                ]);
            }
            
            $oldVa->update([
                'status' => 'E',
            ]);
        }

        if ($request->couple == 'Y') {
           $otheExport = Import::where('form_id', $import->form_id)->whereNot('id', $import->id)->first();
           $otherOldVa = VA::where('virtual_account', $otheExport->va)->first();
           if ($otherOldVa && !in_array($otherOldVa->status, ['C', 'Y'])) {
                if (Carbon::parse($otherOldVa->expired_va)->greaterThan(Carbon::now())) {
                    return response()->json([
                        'success' => false,
                        'message' => 'VA untuk invoice yg anda pilih masih aktif, dengan nomor VA : ' . $otherOldVa->virtual_account,
                        'status' => 30,
                        'data' => $otherOldVa,
                    ]);
                }

                $otherOldVa->update([
                    'status' => 'E',
                ]);
            }
        }

        return null;
    }

}
