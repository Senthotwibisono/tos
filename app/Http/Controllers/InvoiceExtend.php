<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\OrderService as OS;
use App\Models\OSDetail;
use App\Models\MasterTarif as MT;
use App\Models\MTDetail;
use App\Models\Customer;
use App\Models\DOonline;
use App\Models\Item;
use App\Models\Extend;
use App\Models\KodeDok;
use App\Models\InvoiceImport;
use App\Models\ImportDetail;
use App\Models\JobImport;
use App\Models\VVoyage;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\JobExtend;
use App\Models\ExtendDetail as Detail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExtend;
use App\Exports\ReportInvoice;

use DataTables;


class InvoiceExtend extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }


    public function index()
    {
        $data['title'] = 'Delivery Extend';
        $data['service'] = OS::where('ie', '=' , 'X')->orderBy('id', 'asc')->get();
        return view('billingSystem.extend.billing.main', $data);
    }

    public function dataIndex(Request $request)
    {
        $invoice = Extend::orderBy('order_at', 'asc');
        if ($request->has('type')) {
            if ($request->type == 'unpaid') {
                $invoice = Extend::whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'asc');
            }

            if ($request->type == 'piutang') {
                $invoice = Extend::whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'asc');
            }
        }

        if ($request->has('os_id')) {
            $invoice = Extend::where('os_id', $request->os_id)->orderBy('order_at', 'asc');
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
            return '<a type="button" href="/pranota/extend-'.$inv->id.'" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>';
        })
        ->addColumn('invoice', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/extend-'.$inv->id.'" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>';
            }
        })
        ->addColumn('job', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/job/extend-'.$inv->id.'" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>';
            }
        })
        ->addColumn('action', function($inv){
            if (in_array($inv->lunas, ['N', 'P'])) {
                return '<button type="button" id="pay" data-type="extend" data-id="'.$inv->id.'" class="btn btn-sm btn-success" onClick="searchToPay(this)"><i class="fa fa-cogs"></i></button>';
            }elseif ($inv->lunas == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }else{
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('delete', function($inv){
           if ($inv->lunas != 'C') {
                return '<button type="button" data-type="extend" data-id="'.$inv->form_id.'" class="btn btn-sm btn-danger" onClick="cancelInvoice(this)"><i class="fa fa-trash"></i></button>';
            }else {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('viewPhoto', function($inv){
            $herf = '/bukti_bayar/extend/'; 
            return '<a href="javascript:void(0)" onclick="openWindow(\''.$herf.$inv->id.'\')" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>';
        })
        ->addColumn('edit', function($inv){
            if ($inv->lunas == 'Y') {
                return '<a href="/billing/import/extenx-edit/'.$inv->id.'" class="btn btn-warning"><i class="fas fa-pencil"></i></a>';
            } elseif ($inv->lunas == 'P') {
                return '<a href="/billing/import/extenx-edit/'.$inv->id.'" class="btn btn-warning"><i class="fas fa-pencil"></i></a>';
            } else {
                return '';
            }
        })
        ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'delete', 'viewPhoto', 'edit'])
        ->make(true);
    }

    public function ListForm()
    {
        $data['title'] = "Extend Form";
        $data['forms'] = Form::where('done', '=', 'N')->where('i_e', '=', 'X')->get();
        
        return view('billingSystem.extend.form.listForm', $data);
    }

    public function extendDataForm(Request $request)
    {
        set_time_limit(0);
        $form = Form::where('done', '=', 'N')->where('i_e', '=', 'X')->get();

        return DataTables::of($form)
        ->addColumn('customer', function($form){
            return $form->customer->name ?? '-';
        })
        ->addColumn('doOnline', function($form){
            return $form->doOnline->do_no ?? '-';
        })
        ->addColumn('service', function($form){
            return $form->service->name ?? '-';
        })
        ->addColumn('expired', function($form){
            return $form->expired_date ?? '-';
        })
        ->addColumn('blNo', function($form){
            return $form->doOnline->bl_no ?? '-';
        })
        ->addColumn('edit', function($form){
            return '<a href="/billing/import/extend-editForm/'.$form->id.'" class="btn btn-outline-warning">Edit</a>';
        })
        ->rawColumns(['edit'])
        ->make(true);
    }


    public function form()
    {
        $data['title'] = 'Extend Form';
        $user = Auth::user();
        $data["user"] = $user->id;

        $tumpuk = ImportDetail::where('count_by', 'T')->get();
        $invIds = $tumpuk->pluck('inv_id');
        $invoiceImport = InvoiceImport::whereNot('form_id', '=' , '')->whereNot('lunas', '=', 'N')->get();
    
        // Retrieve invoices from Extend where 'lunas' is 'Y'
        $extendInv = Extend::whereNot('lunas', '=', 'N')->get();
    
        // Merge the collections
        $mergedInvoices = $invoiceImport->merge($extendInv);
    
        // Pass the merged collection to the view or further processing
        $data['oldInv'] = $mergedInvoices;
        $data['customer'] = Customer::get();
        $data['now'] = Carbon::now()->format('Y-m-d');
        $data['OrderService'] = OS::where('ie', '=', 'X')->get();
        return view('billingSystem.extend.form.createForm', $data);
    }

    public function EditForm($id)
    {
        $form = Form::find($id);
        $data['title'] = 'Extend Form';
        $user = Auth::user();
        $data["user"] = $user->id;
        $data['customer'] = Customer::get();
        $data['now'] = Carbon::now();
        $data['OrderService'] = OS::where('ie', '=', 'X')->get();
        $containers = Container::where('form_id', $id)->get();
        
        if ($form->tipe == 'I') {
            $query = InvoiceImport::find($form->do_id);
            $jobQuery = new JobImport;
        }elseif ($form->tipe == 'P') {
            $query = Extend::find($form->do_id);
            $jobQuery = new JobExtend;
        }

        $data['jobs'] = $jobQuery->where('inv_id', $query->id)->whereIn('container_key', $containers->pluck('container_key'))->get();

        $data['form'] = Form::where('id', $id)->first();
        return view('billingSystem.extend.form.editForm', $data);
    }
    public function contData(Request $request)
    {
        $id = $request->id;
        
        $inv = InvoiceImport::where('form_id', $id)->first();
        if (is_null($inv)) {
            $inv = Extend::where('form_id', $id)->first();
           $tipe = 'P';
        }else {
            $tipe = 'I';
        }

        // var_dump($inv, $id);
        // die();

        if ($inv) {
           
                $cont = Container::where('form_id', $inv->form_id)->get();
                  
           
            if (!$cont->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'updated successfully!',
                    'data'    => $inv,
                    'cont' => $cont,
                    'tipe' =>$tipe,
             
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
    }

    public function postForm(Request $request)
    {
        $oldInv = InvoiceImport::where('form_id', $request->inv_id)->first();
        if (is_null($oldInv)) {
            $oldInv = Extend::where('form_id', $request->inv_id)->first();
        }
        $oldForm = Form::where('id', $request->inv_id)->first();
        

        $contSelect = $request->container_key;
        $cont = Item::whereIn('container_key', $contSelect)->orderBy('disc_date', 'asc')->get();
        $singleCont = $cont->first();
        
        $firstJobActiveToDate = null;

        foreach ($cont as $item) {
            if ($oldForm->i_e == 'X') {
                $job = JobExtend::where('job_no', $item->job_no)->first();
            }elseif ($oldForm->i_e == 'I') {
                $job = JobImport::where('job_no', $item->job_no)->first();
            }else {
                return back()->with('error', 'Something Wromg');
            }

            $jobActiveToDate = Carbon::parse($job->active_to)->toDateString();

            if (!$firstJobActiveToDate) {
                $firstJobActiveToDate = $jobActiveToDate;
            } elseif ($firstJobActiveToDate != $jobActiveToDate) {
                return back()->with('error', 'Active to date differs between containers');
            }
        }

        $oldExpired = $firstJobActiveToDate;
        // dd($oldInv, $oldExpired);
        $expired = $request->exp_date;

        if ($oldExpired >= $expired) {
            return back()->with('error', 'Expired date tidak lebih besar dari expired date sebelumnya');
        }
        $invoice = Form::create([
            'expired_date'=>$request->exp_date,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'do_id'=>$oldInv->id,
            'ves_id'=> $singleCont->ves_id,
            'i_e'=>'X',
            'disc_date'=>$firstJobActiveToDate,
            'done'=>'N',
            'tipe'=>$request->tipe,
            'discount_ds'=>$request->discount_ds,
        ]);

       
        foreach ($contSelect as $cont) {
            $item = Item::where('container_key', $cont)->first();
            $contInvoice = Container::create([
                'container_key'=>$item->container_key,
                'container_no'=>$item->container_no,
                'ctr_size'=>$item->ctr_size,
                'ctr_status'=>$item->ctr_status,
                'form_id'=>$invoice->id,
                'ves_id'=>$item->ves_id,
                'ves_name'=>$item->ves_name,
                'ctr_type'=>$item->ctr_type,
                'ctr_intern_status'=>$item->ctr_intern_status,
                'gross'=>$item->gross,
            ]);
        }
        return redirect()->route('extendPreinvoice', ['id' => $invoice->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }

    public function updateFormImport(Request $request)
    {
        $form = Form::where('id', $request->form_id)->first();

        $newContainer = $request->container_key;
        $oldInv = InvoiceImport::where('form_id', $request->inv_id)->first();
        if (is_null($oldInv)) {
            $oldInv = Extend::where('form_id', $request->inv_id)->first();
        }
        $oldForm = Form::where('id', $request->inv_id)->first();
        $oldExpired = $oldForm->expired_date;
        $expired = $request->exp_date;

        if ($oldExpired >= $expired) {
            return back()->with('error', 'Expired date tidak lebih besar dari expired date sebelumnya');
        }

        $cont = Item::whereIn('container_key', $newContainer)->get();
        $singleCont = $cont->first();

        $oldCont = Container::where('form_id', $form->id)->get();
        foreach ($oldCont as $cont) {
            $cont->delete();
        }

        $form->update([
            'expired_date'=>$request->exp_date,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'do_id'=>$oldInv->id,
            'ves_id'=> $singleCont->ves_id,
            'i_e'=>'X',
            'disc_date'=>$oldExpired,
            'done'=>'N',
            'tipe'=>$request->tipe,
            'discount_ds'=>$request->discount_ds,
        ]);
        foreach ($newContainer as $cont) {
            $item = Item::where('container_key', $cont)->first();
            $contInvoice = Container::create([
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

        return redirect()->route('extendPreinvoice', ['id' => $form->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }

    public function preinvoice($id)
    {

        $data['title'] = "Pre Invoice Delivery Extend";
        $form = Form::where('id', $id)->first();
        $data['form'] = $form;
        $cont = Container::where('form_id', $form->id)->get();
        $data['container'] = $cont;

        $start = Carbon::parse($form->disc_date);
        $end = Carbon::parse($form->expired_date);
        $interval = $start->diff($end);
        $jumlahHari = $interval->days;
        // dd($jumlahHari);
        if ($form->tipe == 'I') {
            $oldInv = InvoiceImport::where('id', $form->do_id)->first();
        }else {
            $oldInv = Extend::where('id', $form->do_id)->first();
        }
        // dd($form, $oldInv);
        $oldForm = Form::where('id', $oldInv->form_id)->first();


        if ($oldForm->massa3 == null) {
            if ($oldForm->massa2 == null) {
                if ($jumlahHari >= 6) {
                    $m2 = 5;
                    $m3 = $jumlahHari - 5;
                } else {
                    $m2 = $jumlahHari;
                    $m3 = 0;
                }
            } elseif ($oldForm->massa2 < 5) {
                // Calculate remaining days needed to reach 5 days for massa2
                $remainingMassa2Days = 5 - $oldForm->massa2;
                
                // If there are more days than needed to reach 5 days in massa2
                if ($jumlahHari > $remainingMassa2Days) {
                    $m2 = $remainingMassa2Days;
                    $m3 = $jumlahHari - $remainingMassa2Days;
                } else {
                    $m2 = $jumlahHari;
                    $m3 = 0;
                }
            } else {
                // If massa2 already has 5 or more days
                $m2 = 0;
                $m3 = $jumlahHari;
            }
        } else {
            $m2 = 0;
            $m3 = $jumlahHari;
        }
        
       
        // dd($m2, $m3);

        $groupedBySize = $cont->groupBy('ctr_size');
        // dd($groupedBySize);
        $osd = OSDetail::where('os_id', $form->os_id)->get();
        // dd($osd);
        $results = collect();
        $data['ctrGroup'] = $groupedBySize;
        foreach ($osd as $detail) {
            foreach ($groupedBySize as $size => $containers) {
                $containerCount = $containers->count();
                // dd($containerCount);
                $tarif = MT::where('os_id', $detail->os_id)
                    ->where('ctr_size', $size)
                    ->first();
                $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
                    ->where('master_item_id', $detail->master_item_id)
                    ->first();
                 
                    if ($tarifDetail) {
                       if ($tarifDetail->count_by == 'T') {
                        if ($detail->massa == 2) {
                            $hari = $m2;
                        }else {
                            $hari = $m3;
                        }
                                $hargaT = $tarifDetail->tarif * $containerCount * $hari;
                                // dd($hari);
                                $results->push([
                                    'ctr_size' => $size,
                                    'ctr_status' => 'FCL',
                                    'count_by' => 'T',
                                    'tarif' => $tarifDetail->tarif,
                                    'jumlahHari' => $hari,
                                    'containerCount' => $containerCount,
                                    'keterangan' => $tarifDetail->master_item_name,
                                    'harga' => $hargaT,
                                    ]);
                            }
                    }
            }
            $singleTarif = MT::where('os_id', $detail->os_id)->first();
            $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                ->where('master_item_id', $detail->master_item_id)
                ->where('count_by', 'O')
                ->first();
            if ($singleTarifDetail) {
                $data['admin'] = $singleTarifDetail->tarif;
            } 
        }

        $totalKotor = $results->sum('harga');
        $total = $totalKotor + $data['admin'];
        $discount = $form->discount_ds;
        $pajak = (($total - $discount) * 11) / 100;
        $grandTotal = ($total - $discount) + $pajak;

        $data['total'] = $total;
        $data['discount'] = $discount;
        $data['pajak'] = $pajak;
        $data['grandTotal'] = $grandTotal;
        $data['results'] = $results;
        // dd($results);
        
        return view('billingSystem.extend.form.pre-invoice', compact('form', 'oldInv'), $data)->with('success', 'Silahkan Melanjutkan Proses');
    }

    public function post(Request $request)
    {
        $data['title'] = "Pre Invoice Delivery Extend";
        $form = Form::where('id', $request->form_id)->first();
        
        $cont = Container::where('form_id', $form->id)->get();
      

        $start = Carbon::parse($form->disc_date);
        $end = Carbon::parse($form->expired_date);
        $interval = $start->diff($end);
        $jumlahHari = $interval->days;

        if ($form->tipe == 'I') {
            $oldInv = InvoiceImport::where('id', $form->do_id)->first();
        }else {
            $oldInv = Extend::where('id', $form->do_id)->first();
        }
        $oldForm = Form::where('id', $oldInv->form_id)->first();

        if ($oldForm->massa3 == null) {
            if ($oldForm->massa2 == null) {
                if ($jumlahHari >= 6) {
                    $m2 = 5;
                    $m3 = $jumlahHari - 5;
                } else {
                    $m2 = $jumlahHari;
                    $m3 = 0;
                }
            } elseif ($oldForm->massa2 < 5) {
                // Calculate remaining days needed to reach 5 days for massa2
                $remainingMassa2Days = 5 - $oldForm->massa2;
                
                // If there are more days than needed to reach 5 days in massa2
                if ($jumlahHari > $remainingMassa2Days) {
                    $m2 = $remainingMassa2Days;
                    $m3 = $jumlahHari - $remainingMassa2Days;
                } else {
                    $m2 = $jumlahHari;
                    $m3 = 0;
                }
            } else {
                // If massa2 already has 5 or more days
                $m2 = 0;
                $m3 = $jumlahHari;
            }
        } else {
            $m2 = 0;
            $m3 = $jumlahHari;
        }
        

        $massa2 = $m2;
        $massa3 = $m3; 

       
        $cust = Customer::where('id', $request->cust_id)->first();
        $invoiceNo = 'DS-' . $this->getNextInvoiceExtend();
        $nextProformaNumber = $this->getNextProformaNumber();
        $extend = Extend::create([
            'form_id'=>$form->id,
            'm2' => $massa2,
            'm3' => $massa3,
            'proforma_no'=>$nextProformaNumber,
            'inv_id'=>$oldInv->id,
           
            'cust_id'=>$form->cust_id,
            'cust_name'=>$form->customer->name,
            'fax'=>$form->customer->fax,
            'npwp'=>$form->customer->npwp,
            'alamat'=>$form->customer->alamat,
            'os_id'=>$form->os_id,
            'os_name'=>$form->service->name,
            'admin'=>$request->admin, 
            'total'=>$request->total,
            'discount'=>$request->discount,
            'pajak'=>$request->pajak,
            'grand_total'=>$request->grand_total,
            'order_by'=>$request->order_by,
            'lunas'=> "N",
            'expired_date'=>$request->expired_date,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
        ]);
        
        $form->update([
            'massa2'=>$massa2,
            'massa3'=>$massa3,
        ]);
        

        $groupedBySize = $cont->groupBy('ctr_size');
        // dd($groupedBySize);
        $osd = OSDetail::where('os_id', $form->os_id)->get();
        // dd($osd);
        $results = collect();
      
        foreach ($osd as $detail) {
            foreach ($groupedBySize as $size => $containers) {
                $containerCount = $containers->count();
                // dd($containerCount);
                $tarif = MT::where('os_id', $detail->os_id)
                    ->where('ctr_size', $size)
                    ->first();
                $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
                    ->where('master_item_id', $detail->master_item_id)
                    ->first();
                 
                    if ($tarifDetail) {
                       if ($tarifDetail->count_by == 'T') {
                        if ($detail->massa == 2) {
                            $hari = $m2;
                        }else {
                            $hari = $m3;
                        }
                                $hargaT = $tarifDetail->tarif * $containerCount * $hari;
                                $results = Detail::create([
                                    'inv_id'=>$extend->id,
                                   
                                    'inv_type'=>'XTD',
                                    'keterangan'=>$form->service->name,
                                    'ukuran'=>$size,
                                    'jumlah'=>$containerCount,
                                    'satuan'=>'unit',
                                    'expired_date'=>$form->expired_date,
                                    'order_date'=>$extend->order_at,
                                    'lunas'=>'N',
                                    'cust_id'=>$form->cust_id,
                                    'cust_name'=>$form->customer->name,
                                    'os_id'=>$form->os_id,
                                    'jumlah_hari'=>$hari,
                                    'master_item_id'=>$detail->master_item_id,
                                    'master_item_name'=>$detail->master_item_name,
                                    'kode'=>$detail->kode.$size,
                                    'tarif'=>$tarifDetail->tarif,
                                    'total'=>$hargaT,
                                    'form_id'=>$form->id,
                                    'count_by'=>'T',
                                    ]);
                            }
                    }
            }
            $singleTarif = MT::where('os_id', $detail->os_id)->first();
            $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                ->where('master_item_id', $detail->master_item_id)
                ->where('count_by', 'O')
                ->first();
            if ($singleTarifDetail) {
                $single = Detail::create([
                        'inv_id'=>$extend->id,
                       
                        'inv_type'=>'XTD',
                        'keterangan'=>$form->service->name,
                        'ukuran'=> '0',
                        'jumlah'=> 1,
                        'satuan'=>'unit',
                        'expired_date'=>$form->expired_date,
                        'order_date'=>$extend->order_at,
                        'lunas'=>'N',
                        'cust_id'=>$form->cust_id,
                        'cust_name'=>$form->customer->name,
                        'os_id'=>$form->os_id,
                        'jumlah_hari'=>'0',
                        'master_item_id'=>$detail->master_item_id,
                        'master_item_name'=>$detail->master_item_name,
                        'kode'=>$detail->kode,
                        'tarif'=>$singleTarifDetail->tarif,
                        'total'=>$singleTarifDetail->tarif,
                        'form_id'=>$form->id,
                        'count_by'=>'O',
                ]);
            } 
        }
        $form->update([
            'done'=>'Y',
           ]);
        return redirect()->route('index-extend')->with('success', 'Data Berhasil Di Simpan');
    }

    public function payExtend($id)
    {
        $invoice = Extend::where('id', $id)->first();
        if ($invoice) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $invoice,
            ]);
        }
    }

    public function payFull(Request $request)
    {
        $id = $request->inv_id;

        $invoice = Extend::where('id', $id)->first();
        if ($invoice->lunas == 'N') {
            $invDate = Carbon::now();
        }else {
            $invDate = $invoice->invoice_date;
        }
        if ($invoice->inv_no == null) {
            $invoiceNo ='DS-' . $this->getNextInvoiceExtend();
        }else {
          $invoiceNo = $invoice->inv_no;
        }
        $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        $bigOS = OS::where('id', $invoice->os_id)->first();
        foreach ($containerInvoice as $cont) {
            $lastJobNo = JobExtend::orderBy('id', 'desc')->value('job_no');
            $jobNo = $this->getNextJob($lastJobNo);
            $job = JobExtend::where('inv_id', $invoice->id)->where('container_key', $cont->container_key)->first();
            if (!$job) {
                $job = JobExtend::create([
                    'inv_id'=>$invoice->id,
                    'job_no'=>$jobNo,
                    'os_id'=>$invoice->os_id,
                    'os_name'=>$invoice->os_name,
                    'cust_id'=>$invoice->cust_id,
                    'active_to'=>$invoice->expired_date,
                    'container_key'=>$cont->container_key,
                    'container_no'=>$cont->container_no,
                    'ves_id'=>$cont->ves_id,
                ]);
            }
            $item = Item::where('container_key', $cont->container_key)->first();
            $item->update([
                'invoice_no'=>$invoiceNo,
                'job_no' => $job->job_no,
            ]);
        }

        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'Y',
            'inv_no'=>$invoiceNo,
            ]);
        }

        $invoice->update([
            'lunas' => 'Y',
            'inv_no'=>$invoiceNo,
            'lunas_at'=> Carbon::now(),
            'invoice_date'=> $invDate,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
            'data'    => $item,
        ]);
    }

    public function piutang(Request $request)
    {
        $id = $request->inv_id;

        $invoice = Extend::where('id', $id)->first();
        if ($invoice->inv_no == null) {
                $invoiceNo = 'DS-' . $this->getNextInvoiceExtend();
       }else {
         $invoiceNo = $invoice->inv_no;
       }
        $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        $bigOS = OS::where('id', $invoice->os_id)->first();
        foreach ($containerInvoice as $cont) {
            $lastJobNo = JobExtend::orderBy('id', 'desc')->value('job_no');
            $jobNo = $this->getNextJob($lastJobNo);
            $job = JobExtend::where('inv_id', $invoice->id)->where('container_key', $cont->container_key)->first();
            if (!$job) {
                $job = JobExtend::create([
                    'inv_id'=>$invoice->id,
                    'job_no'=>$jobNo,
                    'os_id'=>$invoice->os_id,
                    'os_name'=>$invoice->os_name,
                    'cust_id'=>$invoice->cust_id,
                    'active_to'=>$invoice->expired_date,
                    'container_key'=>$cont->container_key,
                    'container_no'=>$cont->container_no,
                    'ves_id'=>$cont->ves_id,
                ]);
            }
            $item = Item::where('container_key', $cont->container_key)->first();
            $item->update([
                'invoice_no'=>$invoiceNo,
                'job_no' => $job->job_no,
            ]);
        }

        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'P',
            'inv_no'=>$invoiceNo,
            ]);
        }

        $invoice->update([
            'lunas' => 'P',
            'inv_no'=>$invoiceNo,
            'piutang_at'=> Carbon::now(),
            'invoice_date'=> Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
            'data'    => $item,
        ]);
    }


    public function PranotaExtend($id)
    {
        $data['title'] = "Pranota";

        $data['invoice'] = Extend::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['item'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDSK = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDSK) {
            $data['admin'] = $adminDSK->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.extend.pranota.main', $data);
    }

    public function InvoiceExtend($id)
    {
        $data['title'] = "Invoice";
        $data['invoice'] = Extend::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['item'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDSK = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDSK) {
            $data['admin'] = $adminDSK->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.extend.invoice.main', $data);
    }


    public function JobExtend($id)
    {
        $data['title'] = 'Job Number';

        // Ambil data Extend dan Form terkait
        $data['inv'] = Extend::with('form')->where('id', $id)->first();
        $data['form'] = $data['inv']->form;

        // Set timezone dan ambil waktu sekarang
        date_default_timezone_set('Asia/Jakarta');
        $data['now'] = Carbon::now();
        $data['formattedDate'] = $data['now']->format('l, d-m-Y');

        // Ambil job terkait dengan inv_id, lakukan pagination
        $data['job'] = JobExtend::with(['Kapal', 'Service', 'Item', 'Invoice'])->where('inv_id', $id)->paginate(10);

        // Ambil data container yang dibutuhkan
        $data['cont'] = Item::whereIn('container_key', $data['job']->pluck('container_key'))->get()->keyBy('container_key');

        // Inisialisasi variabel qrcodes
        $qrcodes = [];

        // Loop untuk menghasilkan QR Code untuk setiap job
        foreach ($data['job'] as $jb) {
            if ($data['cont']->has($jb->container_key)) {
                $ct = $data['cont']->get($jb->container_key);
                $qrcodes[$jb->id] = QrCode::size(100)->generate($ct->container_no);
            }
        }

        // Kembalikan view dengan data dan qrcodes yang dihasilkan
        return view('billingSystem.import.job.main', compact('qrcodes'), $data);
    }



    private function terbilang($number)
    {
        $x = abs($number);
        $angka = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

        $result = "";
        if ($x < 12) {
            $result = " " . $angka[$x];
        } elseif ($x < 20) {
            $result = $this->terbilang($x - 10) . " Belas";
        } elseif ($x < 100) {
            $result = $this->terbilang($x / 10) . " Puluh" . $this->terbilang($x % 10);
        } elseif ($x < 200) {
            $result = " Seratus" . $this->terbilang($x - 100);
        } elseif ($x < 1000) {
            $result = $this->terbilang($x / 100) . " Ratus" . $this->terbilang($x % 100);
        } elseif ($x < 2000) {
            $result = " Seribu" . $this->terbilang($x - 1000);
        } elseif ($x < 1000000) {
            $result = $this->terbilang($x / 1000) . " Ribu" . $this->terbilang($x % 1000);
        } elseif ($x < 1000000000) {
            $result = $this->terbilang($x / 1000000) . " Juta" . $this->terbilang($x % 1000000);
        } elseif ($x < 1000000000000) {
            $result = $this->terbilang($x / 1000000000) . " Milyar" . $this->terbilang(fmod($x, 1000000000));
        } elseif ($x < 1000000000000000) {
            $result = $this->terbilang($x / 1000000000000) . " Trilyun" . $this->terbilang(fmod($x, 1000000000000));
        }

        return $result;
    }

    private function getNextProformaNumber()
    {
        // Mendapatkan nomor proforma terakhir
        $latestProforma = Extend::orderBy('proforma_no', 'desc')->first();
    
        // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
        if (!$latestProforma) {
            return 'P0000001';
        }
    
        // Mendapatkan nomor urut proforma terakhir
        $lastProformaNumber = $latestProforma->proforma_no;
    
        // Mengekstrak angka dari nomor proforma terakhir
        $lastNumber = (int)substr($lastProformaNumber, 1);
    
        // Menambahkan 1 ke nomor proforma terakhir
        $nextNumber = $lastNumber + 1;
    
        // Menghasilkan nomor proforma berikutnya dengan format yang benar
        return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

private function getNextInvoiceExtend()
{
    // Mendapatkan nomor proforma terakhir
    $latest = Extend::orderBy('inv_no', 'desc')->first();

    // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
    if (!$latest) {
        return 'P0000001';
    }

    // Mendapatkan nomor urut proforma terakhir
    $lastInvoice = $latest->inv_no;

    // Mengekstrak angka dari nomor proforma terakhir
    $lastNumber = (int)substr($lastInvoice, 5);

    // Menambahkan 1 ke nomor proforma terakhir
    $nextNumber = $lastNumber + 1;

    // Menghasilkan nomor proforma berikutnya dengan format yang benar
    return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
}

private function getNextJob($lastJobNo)
{
    // Jika tidak ada nomor pekerjaan sebelumnya, kembalikan nomor pekerjaan awal
    if (!$lastJobNo) {
        return 'JOBP0000001';
    }

    // Mengekstrak angka dari nomor pekerjaan terakhir
    $lastNumber = (int)substr($lastJobNo, 4);

    // Menambahkan 1 ke nomor pekerjaan terakhir
    $nextNumber = $lastNumber + 1;

    // Menghasilkan nomor pekerjaan berikutnya dengan format yang benar
    return 'JOBP' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
}

public function ReportExcel(Request $request)
{
  $os = $request->os_id;
  $startDate = $request->start;
  $endDate = $request->end;
  $invoice = Detail::where('os_id', $os)->whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate)->orderBy('order_date', 'asc')->get();
    $fileName = 'ReportInvoiceExtend-'.$os.'-'. $startDate . $endDate .'.xlsx';
  return Excel::download(new ReportExtend($invoice), $fileName);
}
public function ReportExcelUnpaid(Request $request)
{
  $startDate = $request->start;
  $endDate = $request->end;
  $invoice = Extend::where('lunas', '=', 'N')->whereDate('order_at', '>=', $startDate)->whereDate('order_at', '<=', $endDate)->orderBy('order_at', 'asc')->get();
    $fileName = 'ReportInvoiceExtendUnpaid'.'-'. $startDate . $endDate .'.xlsx';
  return Excel::download(new ReportInvoice($invoice), $fileName);
}
public function ReportExcelPiutang(Request $request)
{
  $startDate = $request->start;
  $endDate = $request->end;
  $invoice = Extend::where('lunas', '=', 'P')->whereDate('order_at', '>=', $startDate)->whereDate('order_at', '<=', $endDate)->orderBy('order_at', 'asc')->get();
    $fileName = 'ReportInvoiceExtendPiutang'.'-'. $startDate . $endDate .'.xlsx';
  return Excel::download(new ReportInvoice($invoice), $fileName);
}

    public function extendInvoiceDelete($id)    
    {
        $invoice = Extend::where('form_id', $id)->get();
        $paid = $invoice->first(function ($inv) {
            return $inv->lunas !== 'N';
        });
    
    // If there's a paid invoice, return an error message
    if ($paid) {
        return response()->json(['message' => 'Data tidak bisa dihapus, ada invoice yang sudah lunas.', 'status' => 'error']);
    }
        foreach ($invoice as $inv) {
            $inv->delete();
        }

        $invoiceDetail = Detail::where('form_id', $id)->get();
        foreach ($invoiceDetail as $detail) {
            $detail->delete();
        }

        $containerInvoice = Container::where('form_id', $id)->get();
        foreach ($containerInvoice as $cont) {
            $item = Item::where('container_key', $cont->container_key)->first();
            $cont->delete();
        }

        $form = Form::where('id', $id)->first();
        $form->delete();

        return response()->json(['message' => 'Data berhasil dihapus.', 'status' => 'success']);
    }

    public function extendInvoiceCancel(Request $request)    
    {
        $id = $request->inv_id;
    
        // Retrieve invoice
        $invoice = Extend::where('id', $id)->first();
        if ($invoice) {
            $invoice->update([
                'lunas' => 'C',
                'total' => 0,
                'discount' => 0,
                'pajak' => 0,
                'grand_total' => 0,
            ]);
        }
    
        // Retrieve invoice details and update
        $invoiceDetails = Detail::where('inv_id', $id)->get();
        foreach ($invoiceDetails as $detail) {
            $detail->update([
                'lunas' => 'C',
                'jumlah' => 0,
                'jumlah_hari' => 0,
                'tarif' => 0,
                'total' => 0,
            ]);
        }
    
        // Retrieve container invoices and old invoice
        $containerInvoices = Container::where('form_id', $invoice->form_id)->get();
        $oldInv = InvoiceImport::where('form_id', $request->inv_id)->first();
    
        if (is_null($oldInv)) {
            $oldInv = Extend::where('form_id', $request->inv_id)->first();
        }
    
        if ($oldInv) {
            $job = is_null($oldInv->type) ? JobExtend::where('inv_id', $oldInv->id)->get() : JobImport::where('inv_id', $oldInv->id)->get();
            
            foreach ($containerInvoices as $cont) {
                $item = Item::where('container_key', $cont->container_key)->first();
                $singleJob = $job->where('container_key', $cont->container_key)->first();
            
                if ($item && $singleJob) {
                    $item->update([
                        'invoice_no' => $oldInv->inv_no,
                        'job_no' => $singleJob->job_no,
                    ]);
                }
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Invoice Berhasil di Cancel!',
        ]);
    }


    public function ReportExcelAll(Request $request)
    {
        $startDate = $request->start;
        $endDate = $request->end;
        $invoiceQuery = Extend::whereDate('invoice_date', '>=', $startDate)
            ->whereDate('invoice_date', '<=', $endDate);
    
        // if ($request->has('inv_type') && !empty($request->inv_type)) {
        //     $invoiceQuery->where('inv_type', $request->inv_type);
        // }
    
        $invoice = $invoiceQuery->whereNot('lunas', '=', 'N')->orderBy('inv_no', 'asc')->get();
    
        $fileName = 'ReportInvoiceExtend-' . $startDate . '-' . $endDate . '.xlsx';

      return Excel::download(new ReportInvoice($invoice), $fileName);
    }

    public function editInvoice($id)
    {
        $data['header'] = Extend::find($id);
        $data['detils'] = Detail::where('inv_id', $id)->get();
        $data['form'] = Form::find($data['header']->form_id);
        $data['containers'] = Container::where('form_id', $data['form']->id)->get();
        $data['title'] = 'Edit Invoice' . (($data['header']->inv_no) ? $data['header']->inv_no : $data['header']->proforma_no);

        $data['customers'] = Customer::get();
        $data['nomorContainers'] = $data['containers']->pluck('container_no')->implode(', ');
        // dd($data);
        return view('billingSystem.extend.invoice.edit', $data);
    }

    public function updateDetil(Request $request)
    {
        $detil = Detail::find($request->detilId);
        // dd($detil);
        if ($detil) {
            $detil->update([
                'jumlah_hari' => $request->jumlah_hari,
                'total' => $request->total,
            ]);
            return redirect()->back()->with('success', 'Data berhasil disimpan');
        }else {
            return redirect()->back()->with('error', 'Opss terjadi kesalahan');
        }
    }

    public function updateInvoiceHeader($id, Request $request)
    {
        $header = Extend::find($id);
        try {
            $customer = Customer::find($request->cust_id);
            // dd($customer);
            $header->update([
                'cust_id' => $request->cust_id,
                'cust_name' => $customer->name,
                'fax' => $customer->fax,
                'npwp' => $customer->npwp,
                'alamat' => $customer->alamat,
                'order_at' => $request->order_at,
                'piutang_at' => $request->piutang_at,
                'lunas_at' => $request->lunas_at,
                'expired_date' => $request->expired_date,
                'total' => $request->total,
                'admin' => $request->admin,
                'pajak' => $request->pajak,
                'discount' => $request->discount,
                'grand_total' => $request->grand_total,
            ]);

            $detil = Detail::where('inv_id', $id)->update([
                'cust_id' => $customer->id,
                'cust_name' =>$customer->name,
            ]);

            $job = JobExtend::where('inv_id', $id)->update([
                'active_to' => $request->expired_date,
            ]);

            return back()->with('success', 'Data berhasil disimpan');
            
        } catch (\Throwable $th) {
            return back()->with('error', 'Something Wrong in : ' . $th->getMessage());
        }
    }

    public function newPreinvoice($id)
    {
        $form = Form::find($id);
        $data['title'] = "Preinvoice Perpanjangan| " . $form->Service->name;
        $data['form'] = $form;

        $containers = Container::where('form_id', $form->id)->get();
        $data['listContainers'] = $containers;

        $service = OS::find($form->os_id);
        $serviceDetail = OSDetail::where('os_id', $service->id)->orderBy('master_item_name', 'asc')->get();

        $serviceDSK = $serviceDetail->where('type', 'DSK');
        $data['serviceDSK'] = $serviceDSK;
        $data['dsk'] = $serviceDSK->isNotEmpty() ? 'Y' : 'N'; 

        $serviceDS = $serviceDetail->where('type', 'XTD');
        $data['serviceDS'] = $serviceDS;
        $data['ds'] = $serviceDS->isNotEmpty() ? 'Y' : 'N';

        // dd($serviceDetail, $serviceDS, $serviceDSK);

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

        return view('billingSystem.extend.form.newPreinvoice.preinvoice', $data)->with('success', 'Seilahkan lanjutkan ke tahap berikut nya');
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
    
            if ($serviceDetail->where('type', 'XTD')->isNotEmpty()) {
                $this->processInvoice($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetail->where('type', 'XTD'), $tarif, $tarifDetail, 'DS');
            }

            $form->update(['done'=>'Y']);

            return redirect()->route('index-extend')->with('success', 'Invoice berhasil di buat');
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
                            if ($detail->MItem->count_by != 'O') {
                                $this->createInvoiceDetail($header, $form, $detail, $tarif, $tarifDetail, $size, $status, $jumlahCont);
                            }
                        }
                    }
                }
            }

            foreach ($serviceDetails as $detail) {
                if ($detail->MItem->count_by == 'O') {
                    $size = null;
                    $status = null;
                    $this->createInvoiceDetail($header, $form, $detail, $tarif, $tarifDetail, $size, $status, 1);
                }
            }
        });
    }

    private function createInvoiceHeader($request, $form, $service, $type)
    {
        return Extend::create([
            'form_id' => $form->id,
            'inv_id' => $form->do_id,
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
            'discount' => $request->input("discount$type"),
            'grand_total' => $request->input("grandTotal$type"),
            'order_by' => Auth::user()->name,
            'order_at' => Carbon::now(),
            'disc_date' => $form->disc_date,
            'expired_date' => $form->expired_date,
            'user_id' => Auth::user()->id,
            'lunas' => 'N',
        ]);
    }

    private function createInvoiceDetail($header, $form, $detail, $tarif, $tarifDetail, $size, $status, $jumlahCont)
    {
        $selectedTarif = $tarif->where('ctr_size', $size)->where('ctr_status', $status)->first();
        if ($detail->MItem->count_by == 'O') {
            $selectedTarif = $tarif->first();
        }
        $tarifDSK = $tarifDetail->where('master_tarif_id', $selectedTarif->id)->where('master_item_id', $detail->master_item_id)->first();

        $jumlahHari = $this->calculateDays($form, $detail);
        $kode = ($detail->kode != 'PASSTRUCK') ? $detail->kode . $size : 'PASSTRUCK';
        $harga = $jumlahCont * $jumlahHari * $tarifDSK->tarif;
        // dd($detail);
        Detail::create([
            'inv_id' => $header->id,
            'inv_type' => 'XTD',
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
            'jumlah_hari' => $jumlahHari,
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
}

