<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
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
        $data['invoice'] = Extend::orderBy('order_at', 'asc')->get();
        $data['unPaids'] = Extend::whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'asc')->get();
        $data['piutangs'] = Extend::whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'asc')->get();
        return view('billingSystem.extend.billing.main', $data);
    }

    public function ListForm()
    {
        $data['title'] = "Extend Form";
        $data['forms'] = Form::where('done', '=', 'N')->where('i_e', '=', 'X')->get();
        
        return view('billingSystem.extend.form.listForm', $data);
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
        $data['now'] = Carbon::now();
        $data['OrderService'] = OS::where('ie', '=', 'X')->get();
        return view('billingSystem.extend.form.createForm', $data);
    }

    public function EditForm($id)
    {
        $data['title'] = 'Extend Form';
        $user = Auth::user();
        $data["user"] = $user->id;

        $tumpuk = ImportDetail::where('count_by', 'T')->get();
        $invIds = $tumpuk->pluck('inv_id');
        $invoiceImport = InvoiceImport::whereNot('form_id',  '=' , '')->whereNot('lunas', '=', 'N')->get();
    
        // Retrieve invoices from Extend where 'lunas' is 'Y'
        $extendInv = Extend::whereNot('lunas', '=', 'N')->get();
    
        // Merge the collections
        $mergedInvoices = $invoiceImport->merge($extendInv);
    
        // Pass the merged collection to the view or further processing
        $data['oldInv'] = $mergedInvoices;
        $data['customer'] = Customer::get();
        $data['now'] = Carbon::now();
        $data['OrderService'] = OS::where('ie', '=', 'X')->get();
        $data['containerInvoice'] = Container::where('form_id', $id)->get();
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
        $discount = ($total * $form->discount_ds) / 100;
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
                'order_service' => $bigOS->order,
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
                'order_service' => $bigOS->order,
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
        $data['job'] = JobExtend::where('inv_id', $id)->paginate(10);

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
  $invoice = Detail::where('lunas', '=', 'N')->whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate)->orderBy('order_date', 'asc')->get();
    $fileName = 'ReportInvoiceExtendUnpaid'.'-'. $startDate . $endDate .'.xlsx';
  return Excel::download(new ReportExtend($invoice), $fileName);
}
public function ReportExcelPiutang(Request $request)
{
  $startDate = $request->start;
  $endDate = $request->end;
  $invoice = Detail::where('lunas', '=', 'P')->whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate)->orderBy('order_date', 'asc')->get();
    $fileName = 'ReportInvoiceExtendPiutang'.'-'. $startDate . $endDate .'.xlsx';
  return Excel::download(new ReportExtend($invoice), $fileName);
}

    public function extendInvoiceDelete($id)    
    {
        $invoice = Extend::where('form_id', $id)->get();
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

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function extendInvoiceCancel(Request $request)    
    {
        $id = $request->inv_id;

        $invoice = Extend::where('id', $id)->first();
        
            $invoice->update([
                'lunas' => 'C',
                'total'=> 0,
                'discount'=> 0,
                'pajak'=> 0,
                'grand_total'=> 0,
            ]);
       

        $invoiceDetail = Detail::where('form_id', $id)->get();
        foreach ($invoiceDetail as $detail) {
            $detail->update([
                'lunas'=>'C',
                'jumlah'=>0,
                'jumlah_hari'=> 0,
                'tarif'=>0,
                'total'=>0,
            ]);
        }

        $containerInvoice = Container::where('form_id', $id)->get();
        
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
}

