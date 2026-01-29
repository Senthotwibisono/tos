<?php

namespace App\Http\Controllers\customer\export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Support\Facades\DB;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\VVoyage;
use App\Models\Item;
use App\Models\Isocode;
use App\Models\Port;

use App\Models\Customer;
use App\Models\MasterUserInvoice as MUI;

use App\Models\InvoiceExport as Export;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\ExportDetail as Detail;
use App\Models\JobExport;
use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;

use App\Models\Payment\RefNumber as VA;
use App\Models\Payment\RefDetail;

class CustomerExportController extends  CustomerMainController
{
    public function indexDetail()
    {

        $data['title'] = 'Invoice Muat';

        return view('customer.export.detil', $data);
    }

    public function dataDetail(Request $request)
    {
        $inv = $this->export->with(['customer', 'service', 'form']);
        if ($request->has('status')) {
            if ($request->status == 'N') {
                $inv = $this->export->with(['customer', 'service', 'form'])->where('lunas', 'N');
            } elseif ($request->status == 'C') {
                $inv = $this->export->with(['customer', 'service', 'form'])->where('lunas', 'C');
            }
        }

        $inv = $inv->get();

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
            return '<a type="button" href="/pranota/export-'.$inv->inv_type.$inv->id.'" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>';
        })
        ->addColumn('invoice', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/export-'.$inv->inv_type.$inv->id.'" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>';
            }
        })
        ->addColumn('job', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/job/export-'.$inv->id.'" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>';
            }
        })
        ->addColumn('action', function($inv){
            if ($inv->lunas == 'N' || $inv->lunas == 'P') {
                if ($inv->inv_type === 'OSK') {
                    return '<button type="button" id="pay" data-id="'.$inv->id.'" class="btn btn-sm btn-success pay" onClick="payButton(this)"><i class="fa fa-cogs"></i></button>';
                }else {
                    return 'Pembayaran melalui kasir!';
                }
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
        ->addColumn('delete', function($inv){
            if ($inv->lunas == 'N') {
                return '<button type="button" class="btn btn-danger" onClick="deleteButton(this)" data-id="'.$inv->form_id.'">Cancel</button>';
            }else {
                return '-';
            }
        })
        ->addColumn('materai', function($inv){
            if (($inv->grand_total >= 5000000) && ($inv->lunas === 'Y')) {
                # code...
                return '<button class="btn btn-danger" data-type="E" data-id="'.$inv->id.'" onClick="materai(this)">Materai</button>';
            }else{
                return '-';
            }
        })
        ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'delete', 'payFlag', 'materai'])
        ->make(true);
    }

    public function indexForm()
    {
        $data['title'] = "Form Index";

        return view('customer.export.form.index', $data);
    }

    public function dataForm(Request $request)
    {
        $form = $this->form->where('i_e', 'E')->where('done', '=', 'N')->get();
        
        return DataTables::of($form)
        ->addColumn('customer', function($form){
            return $form->customer->name ?? '-';
        })
        ->addColumn('service', function($form){
            return $form->service->name ?? '-';
        })
        ->addColumn('edit', function($form){
            return '<a href="/customer-export/form/firstStepIndex-'.$form->id.'" class="btn btn-warning"><i class="fas fa-pencil"></i></a>';
        })
        ->addColumn('delete', function($form){
            return '<button type="button" class="btn btn-danger" onClick="deleteButton(this)" data-id="'.$form->id.'"><i class="fas fa-trash"></i></button>';
        })
        ->rawColumns(['edit', 'delete'])
        ->make(true);
    }
    

    public function createForm()
    {
        $mui = MUI::where('user_id', $this->userId)->get();
        if ($mui->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki list customer, hubungi admin!!!',
            ]);
        }
        
        $oldForm = Form::where('i_e', 'E')
        ->where('user_id', $this->userId)
        ->where('done', 'N')
        ->whereNull('expired_date')
        ->whereNull('os_id')
        ->whereNull('cust_id')
        ->whereNull('ves_id')
        ->first();
        if ($oldForm) {
            return response()->json([
                'success' => true,
                'message' => 'data ditemukan',
                'id' => $oldForm->id,
            ]);
        }
        try {
            $form = Form::create([
                'i_e' => 'E',
                'user_id' => Auth::user()->id,
                'done' => 'N',
            ]);
            return response()->json([
                'success' => true,
                'message' => 'data ditemukan',
                'id' => $form->id,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => true,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function deleteForm(Request $request)
    {
        // var_dump($request->all());
        $form = Form::find($request->id);
        $checkInvoice = Export::where('form_id', $form->id)->get();
        // var_dump($checkInvoice);
        // die();
        if ($checkInvoice->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus form, Pranota sudah terbit',
            ]);
        }

        $formContainer = Container::where('form_id', $form->id)->get();
        if ($formContainer->isNotEmpty()) {
            $items = Item::whereIn('container_key', $formContainer->pluck('container_key'))->get();
            if ($items->isNotEmpty()) {
                foreach ($items as $item) {
                    $item->update([
                        'selected_do' => 'N',
                    ]);
                };
            }
        }

        try {
            DB::transaction(function () use ($form) {
                $conts = Container::where('form_id', $form->id)->delete();
                $form->delete();
            });
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil di Hapus',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function firstStepIndex($id)
    {
        $data['form'] = Form::find($id);
        $data['title'] = 'Form Invoice Export - Step 1';
        $data['usersMaster'] = MUI::where('user_id', Auth::user()->id)->get();
        $data['customers'] = Customer::whereIn('id', $data['usersMaster']->pluck('customer_id'))->get();
        $data['vessels'] = VVoyage::where('clossing_date','>=', Carbon::now())->get();
        $data['orderService'] = OS::where('ie', '=', 'E')->get();
        $data['containers'] = Container::where('form_id', $id)->get();

        return view('customer.export.form.step1', $data);
    }

    public function firstStepPost(Request $request)
    {
        // var_dump($request->all());
        if ($request->container_key == null) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada container yg anda pilih',
            ]);
        }
        $form = Form::find($request->form_id);
        $vessel = VVoyage::find($request->ves_id);
        $customer = Customer::find($request->cust_id);
        $service = OS::find($request->os_id);
        $items = Item::whereIn('container_key', $request->container_key)->get();
        $oldCont = Container::where('form_id', $form->id)->get();
        $singleItem = $items->first();
        // var_dump($items, $oldCont);
        try {
            DB::transaction(function() use ($form, $vessel, $customer, $service, $items, $oldCont, $singleItem) {
                if ($oldCont->isNotEmpty()) {
                    foreach ($oldCont as $cont) {
                        $item = Item::find($cont->container_key)->updateOrFail([
                            'selected_do' => 'N'
                        ]);
                        $cont->delete();
                    }
                }

                $form->update([
                    'expired_date'=>$vessel->clossing_date,
                    'os_id'=>$service->id,
                    'cust_id'=> $customer->id,
                    'do_id'=>$singleItem->booking_no,
                    'ves_id'=> $vessel->ves_id,
                    'i_e'=>'E',
                    'disc_date'=>Carbon::now(),
                    'done'=>'N',
                ]);

                foreach ($items as $item) {
                    $item->update([
                        'ves_id'=>$vessel->ves_id,
                        'ves_code'=>$vessel->ves_code,
                        'ves_name'=>$vessel->ves_name,
                        'voy_no'=>$vessel->voy_out,
                        'selected_do' => 'Y',
                    ]);

                    $contInvoice = Container::create([
                        'container_key'=>$item->container_key,
                        'container_no'=>$item->container_no,
                        'ctr_size'=>$item->ctr_size,
                        'ctr_status'=>$item->ctr_status,
                        'form_id'=>$form->id,
                        'ves_id'=>$vessel->ves_id,
                        'ves_name'=>$vessel->ves_name,
                        'ctr_type'=>$item->ctr_type,
                        'ctr_intern_status'=>$item->ctr_intern_status,
                        'gross'=>$item->gross,
                    ]);
                }

            });
            return response()->json([
                'success' => true,
                'message' => 'Berhasil di simpan',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function preinvoice($id)
    {
        $data['title'] = 'Preinvoice Export';
        $data['form'] = Form::find($id);
        $data['service'] = OS::find($data['form']->os_id);
        $data['serviceDetails'] = OSDetail::where('os_id', $data['service']->id)->get();
        $data['osk'] = ($data['serviceDetails']->where('type', 'OSK')->isNotEmpty()) ? 'Y' : 'N';
        $data['os'] = ($data['serviceDetails']->where('type', 'OS')->isNotEmpty()) ? 'Y' : 'N';

        $data['containers'] = Container::where('form_id', $id)->get();
        $data['groupedConts'] = $data['containers']->groupBy([
            fn ($item) => $item->ctr_size,
            fn ($item) => $item->ctr_status,
        ]);
        $groupKeys = [];

        foreach ($data['groupedConts'] as $size => $statusGroup) {
            foreach ($statusGroup as $status => $group) {
                $groupKeys[] = [
                    'size' => $size,
                    'status' => $status,
                    'count' => $group->count(),
                ];
            }
        }       

        $data['groupSummary'] = $groupKeys;
        $defaultTarif = MT::where('os_id', $data['service']->id)->get();
        foreach ($groupKeys as $group) {
            $tarifCheck = $defaultTarif->where('ctr_size', $group['size'])->where('ctr_status', $group['status'])->first();
            // dd($tarifCheck);
            if (!$tarifCheck) {
                return back()->with('error', 'Master Tarif Belum Dibuat untuk kondisi size : ' . $group['size'] . ' status : ' . $group['status']);
            }
        }
        $data['tarifs'] = $defaultTarif;
        $data['tarifDetails'] = MTDetail::whereIn('master_tarif_id', $defaultTarif->pluck('id'))->get();

        // return $data;
        $data['serviceOSK'] = $data['serviceDetails']->where('type', 'OSK');
        $data['serviceOS'] = $data['serviceDetails']->where('type', 'OS');

        // return $data;
        return view('customer.export.form.preinvoice', $data)->with('success');
    }

    public function createInvoice(Request $request)
    {
        $form = Form::find($request->formId);
        // dd($request->all());
        try {
            DB::transaction(function() use($form, $request){
                if ($request->has('itemOSK')) {
                    $detailsOSK = json_decode($request->itemOSK);
                    $grandTotal = $request->grandTotalOSK;
                    if ($grandTotal >= 5000000) {
                        $grandTotal += 10000;
                    }
                    $dataOSK =[
                        'total' => $request->totalAmountOSK,
                        'admin' => $request->adminOSK,
                        'discount' => 0,
                        'pajak' => $request->ppnOSK,
                        'grand_total' => $grandTotal,
                    ];
                  
                    $this->createOSK($form, $detailsOSK, $dataOSK);
                }
                
                if ($request->has('itemOS')) {
                    $detailsOS = json_decode($request->itemOS);
                    $grandTotal = $request->grandTotalOS;
                    if ($grandTotal >= 5000000) {
                        $grandTotal += 10000;
                    }
                    $dataOS =[
                        'total' => $request->totalAmountOS,
                        'admin' => $request->adminOS,
                        'discount' => 0,
                        'pajak' => $request->ppnOS,
                        'grand_total' => $grandTotal,
                    ];
                    $this->createOS($form, $detailsOS, $dataOS);
                }

                $form->update([
                    'done' => 'Y',
                ]);
            });
        } catch (\Throwable $th) {
           return back()->with('error', $th->getMessage());
        }
        
        return redirect()->route('customer.export.indexDetail')->with('success', 'Invoice anda berhasil di buat');
    }

    private function createOSK($form, $detailsOSK, $dataOSK){
        // dd($form, $detailsOSK);
        $invoiceOSK = Export::create([
            'inv_type'=>'OSK',
            'form_id'=>$form->id,
            'proforma_no'=> $this->getNextProformaNumber(),
            'cust_id'=>$form->cust_id,
            'cust_name'=>$form->customer->name,
            'fax'=>$form->customer->fax,
            'npwp'=>$form->customer->npwp,
            'alamat'=>$form->customer->alamat,
            'os_id'=>$form->os_id,
            'os_name'=>$form->service->name,
            'massa1'=> 1,
            'lunas'=>'N',
            'expired_date'=>$form->expired_date,
            'disc_date' => $form->disc_date,
            'booking_no'=>$form->do_id,
            'total'=> $dataOSK['total'],
            'admin'=> $dataOSK['admin'],
            'discount'=>$dataOSK['discount'],
            'pajak'=>$dataOSK['pajak'],
            'grand_total'=> $dataOSK['grand_total'],
            'order_by' => Auth::user()->name,
            'order_at' => Carbon::now(),
            'user_id' => Auth::user()->id, 
        ]);
        // dd($detailsOSK);
        foreach ($detailsOSK as $detil) {
            // dd($detil);
            if ($detil->total === 2000) {
                Detail::create([
                    'inv_id'=>$invoiceOSK->id,
                    'inv_type' => $detil->inv_type,
                    'keterangan' => $detil->keterangan,
                    'ukuran' => $detil->ukuran,
                    'jumlah' => $detil->jumlah,
                    'satuan' => $detil->satuan,
                    'expired_date' => $detil->expired_date,
                    'lunas' => $detil->lunas,
                    'cust_id' => $detil->cust_id,
                    'cust_name' => $detil->cust_name,
                    'os_id' => $detil->os_id,
                    'jumlah_hari' => $detil->jumlah_hari,
                    'master_item_id' => 19,
                    'master_item_name' => 'Administrasi (K)',
                    'kode' => 'ADMINISTRASI (K)',
                    'tarif' => $detil->tarif,
                    'total' => $detil->total,
                    'form_id' => $detil->form_id,
                    'count_by' => 'O',
                    'order_date'=>$invoiceOSK->order_at,
                ]);
            }else {
                Detail::create([
                    'inv_id'=>$invoiceOSK->id,
                    'inv_type' => $detil->inv_type,
                    'keterangan' => $detil->keterangan,
                    'ukuran' => $detil->ukuran,
                    'jumlah' => $detil->jumlah,
                    'satuan' => $detil->satuan,
                    'expired_date' => $detil->expired_date,
                    'lunas' => $detil->lunas,
                    'cust_id' => $detil->cust_id,
                    'cust_name' => $detil->cust_name,
                    'os_id' => $detil->os_id,
                    'jumlah_hari' => $detil->jumlah_hari,
                    'master_item_id' => $detil->master_item_id,
                    'master_item_name' => $detil->master_item_name,
                    'kode' => $detil->kode,
                    'tarif' => $detil->tarif,
                    'total' => $detil->total,
                    'form_id' => $detil->form_id,
                    'count_by' => $detil->count_by,
                    'order_date'=>$invoiceOSK->order_at,
                ]);
            }
        }

        return $invoiceOSK;
    }

    private function createOS($form, $detailsOS, $dataOS){
        // dd($form, $detailsOS);
        $invoiceOS = Export::create([
            'inv_type'=>'OS',
            'form_id'=>$form->id,
            'proforma_no'=> $this->getNextProformaNumber(),
            'cust_id'=>$form->cust_id,
            'cust_name'=>$form->customer->name,
            'fax'=>$form->customer->fax,
            'npwp'=>$form->customer->npwp,
            'alamat'=>$form->customer->alamat,
            'os_id'=>$form->os_id,
            'os_name'=>$form->service->name,
            'massa1'=> 1,
            'lunas'=>'N',
            'expired_date'=>$form->expired_date,
            'disc_date' => $form->disc_date,
            'booking_no'=>$form->do_id,
            'total'=> $dataOS['total'],
            'admin'=> $dataOS['admin'],
            'discount'=>$dataOS['discount'],
            'pajak'=>$dataOS['pajak'],
            'grand_total'=> $dataOS['grand_total'],
            'order_by' => Auth::user()->name,
            'order_at' => Carbon::now(),
            'user_id' => Auth::user()->id, 
        ]);

        foreach ($detailsOS as $detil) {
            // dd($detil);
            if ($detil->total === 2000) {
                Detail::create([
                    'inv_id'=>$invoiceOS->id,
                    'inv_type' => $detil->inv_type,
                    'keterangan' => $detil->keterangan,
                    'ukuran' => $detil->ukuran,
                    'jumlah' => $detil->jumlah,
                    'satuan' => $detil->satuan,
                    'expired_date' => $detil->expired_date,
                    'lunas' => $detil->lunas,
                    'cust_id' => $detil->cust_id,
                    'cust_name' => $detil->cust_name,
                    'os_id' => $detil->os_id,
                    'jumlah_hari' => $detil->jumlah_hari,
                    'master_item_id' => 19,
                    'master_item_name' => 'Administrasi (NK)',
                    'kode' => 'ADMINISTRASI (NK)',
                    'tarif' => $detil->tarif,
                    'total' => $detil->total,
                    'form_id' => $detil->form_id,
                    'count_by' => 'O',
                    'order_date'=>$invoiceOS->order_at,
                ]);
            } else {

                Detail::create([
                    'inv_id'=>$invoiceOS->id,
                    'inv_type' => $detil->inv_type,
                    'keterangan' => $detil->keterangan,
                    'ukuran' => $detil->ukuran,
                    'jumlah' => $detil->jumlah,
                    'satuan' => $detil->satuan,
                    'expired_date' => $detil->expired_date,
                    'lunas' => $detil->lunas,
                    'cust_id' => $detil->cust_id,
                    'cust_name' => $detil->cust_name,
                    'os_id' => $detil->os_id,
                    'jumlah_hari' => $detil->jumlah_hari,
                    'master_item_id' => $detil->master_item_id,
                    'master_item_name' => $detil->master_item_name,
                    'kode' => $detil->kode,
                    'tarif' => $detil->tarif,
                    'total' => $detil->total,
                    'form_id' => $detil->form_id,
                    'count_by' => $detil->count_by,
                    'order_date'=>$invoiceOS->order_at,
                ]);
            }
        }

        return $invoiceOS;
    }

    private function getNextProformaNumber()
    {
        // Mendapatkan nomor proforma terakhir
        $latestProforma = Export::orderBy('proforma_no', 'desc')->first();
        if (!$latestProforma) {
            return 'P0000001';
        }
        $lastProformaNumber = $latestProforma->proforma_no;
        $lastNumber = (int)substr($lastProformaNumber, 1);
        $nextNumber = $lastNumber + 1;

        return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    public function cancelInvoice(Request $request)
    {
        $form = Form::find($request->id);
        try {
            $formContainer = Container::where('form_id', $form->id)->get();
            if ($formContainer->isNotEmpty()) {
                $items = Item::whereIn('container_key', $formContainer->pluck('container_key'))->get();
                if ($items->isNotEmpty()) {
                    foreach ($items as $item) {
                        $item->update([
                            'selected_do' => 'N',
                        ]);
                    };
                }
            }

            $invoices = Export::where('form_id', $form->id)->get();
            foreach ($invoices as $invoice) {
                $invoice->update([
                    'lunas' => 'C',
                ]);

                $va = VA::where('virtual_account', $invoice->va)->first();
                if ($va) {
                    $va->update([
                        'status' => 'C',
                        'lunas_time' => Carbon::now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Invoice canceled',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function searchToPay($id)
    {
        $export = Export::find($id);
        $anotherInvoice = Export::where('form_id', $export->form_id)->whereNot('id', $export->id)->where('lunas', 'N')->first();
        if ($export) {
            return response()->json([
                'success' => true,
                'data' => $export,
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

        $export = Export::find($request->id);
        $billingAmount = $export->grand_total;
        if ($request->couple == 'Y') {
            $otheExport = Export::where('form_id', $export->form_id)->whereNot('id', $export->id)->first();
            $billingAmount += $otheExport->grand_total;
        }
        
        try {
            $newVa = DB::transaction(function() use($billingAmount, $export){
                return VA::create([
                    'virtual_account' => $this->virtualAccount(),
                    'expired_va' => Carbon::now()->addHours(3),
                    'invoice_type' => 'Export',
                    'customer_name' => $export->cust_name,
                    'customer_id' => $export->cust_id,
                    'description' => $export->os_name,
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
        $export = Export::find($request->id);
        DB::transaction(function() use($newVa, $export){
            RefDetail::create([
                'va_id' => $newVa->id,
                'inv_id' => $export->id,
                'invoice_ie' => 'E',
                'proforma_no' => $export->proforma_no,
                'invoice_type' => $export->inv_type,
                'amount' => $export->grand_total,
            ]);

            $export->update([
                'va' => $newVa->virtual_account,
            ]);
        });

        if ($request->couple == 'Y') {
            $otheExport = Export::where('form_id', $export->form_id)->whereNot('id', $export->id)->first();
            if ($otheExport) {
                DB::transaction(function() use($newVa, $otheExport){
                    RefDetail::create([
                        'va_id' => $newVa->id,
                        'inv_id' => $otheExport->id,
                        'invoice_ie' => 'E',
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
        $export = Export::find($request->id);
        $oldVa = VA::where('virtual_account', $export->va)->first();
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
           $otheExport = Export::where('form_id', $export->form_id)->whereNot('id', $export->id)->first();
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
