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
        $data['oldInv'] = InvoiceImport::where('lunas', '=', 'Y')->get();
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
        $data['oldInv'] = InvoiceImport::where('lunas', '=', 'Y')->get();
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
        $inv = InvoiceImport::where('id', $id)->first();

        if ($inv) {
           
                $cont = Container::where('form_id', $inv->form_id)->get();
                // var_dump($invCont, $cont);
                // die;       
           
            if (!$cont->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'updated successfully!',
                    'data'    => $inv,
                    'cont' => $cont,
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
        $oldInv = InvoiceImport::where('id', $request->inv_id)->first();
        $oldExpired = $oldInv->last_expired_date;
        // dd($oldInv, $oldExpired);
        $expired = $request->exp_date;

        if ($oldExpired >= $expired) {
            return back()->with('error', 'Expired date tidak lebih besar dari expired date sebelumnya');
        }

        $contSelect = $request->container_key;
        $cont = Item::whereIn('container_key', $contSelect)->orderBy('disc_date', 'asc')->get();
        $singleCont = $cont->first();
        // dd($singleCont);
        $invoice = Form::create([
            'expired_date'=>$request->exp_date,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'do_id'=>$request->inv_id,
            'ves_id'=> $singleCont->ves_id,
            'i_e'=>'X',
            'disc_date'=>$oldExpired,
            'done'=>'N',
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
        $oldInv = InvoiceImport::where('id', $request->inv_id)->first();
        $oldExpired = $oldInv->last_expired_date;
        // dd($oldInv, $oldExpired);
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
            'do_id'=>$request->inv_id,
            'ves_id'=> $singleCont->ves_id,
            'i_e'=>'X',
            'disc_date'=>$oldExpired,
            'done'=>'N',
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
        $oldInv = InvoiceImport::where('id', $form->do_id)->first();


        if ($oldInv->massa3 == null) {
            if ($oldInv->massa2 == null) {
                if ($jumlahHari > 5) {
                    $m2 = 5;
                    $m3 = $jumlahHari - 5;
                } else {
                    $m2 = $jumlahHari;
                    $m3 = 0;
                }
            } elseif ($oldInv->massa2 < 5) {
                if ($jumlahHari > 5) {
                    $m2 = min(5 - $oldInv->massa2, $jumlahHari);
                    $m3 = $jumlahHari - $m2;
                } else {
                    $m2 = min(5 - $oldInv->massa2, $jumlahHari);
                    $m3 = 0;
                }
            } else {
                $m2 = 0;
                $m3 = $jumlahHari;
            }
        }else {
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
        $pajak = ($total * 11) / 100;
        $grandTotal = $total + $pajak;

        $data['total'] = $total;
        $data['pajak'] = $pajak;
        $data['grandTotal'] = $grandTotal;
        $data['results'] = $results;
        // dd($results);
        
        return view('billingSystem.extend.form.pre-invoice', compact('form'), $data)->with('success', 'Silahkan Melanjutkan Proses');
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

        $oldInv = InvoiceImport::where('id', $form->do_id)->first();

        if ($oldInv->massa3 == null) {
            if ($oldInv->massa2 == null) {
                if ($jumlahHari > 5) {
                    $m2 = 5;
                    $m3 = $jumlahHari - 5;
                } else {
                    $m2 = $jumlahHari;
                    $m3 = 0;
                }
            } elseif ($oldInv->massa2 < 5) {
                if ($jumlahHari > 5) {
                    $m2 = min(5 - $oldInv->massa2, $jumlahHari);
                    $m3 = $jumlahHari - $m2;
                } else {
                    $m2 = min(5 - $oldInv->massa2, $jumlahHari);
                    $m3 = 0;
                }
            } else {
                $m2 = 0;
                $m3 = $jumlahHari;
            }
        }else {
            $m2 = 0;
            $m3 = $jumlahHari;
        }

        $massa2 = $m2;
        $massa3 = $m3; 

       
        $cust = Customer::where('id', $request->cust_id)->first();
        $invoiceNo = 'DS-' . $this->getNextInvoiceExtend();
        $extend = Extend::create([
            'form_id'=>$form->id,
            'm2' => $massa2,
            'm3' => $massa3,
            'proforma_no'=>$oldInv->proforma_no,
            'inv_id'=>$oldInv->id,
            'inv_no'=>$invoiceNo,
            'cust_id'=>$form->cust_id,
            'cust_name'=>$form->customer->name,
            'fax'=>$form->customer->fax,
            'npwp'=>$form->customer->npwp,
            'alamat'=>$form->customer->alamat,
            'os_id'=>$form->os_id,
            'os_name'=>$form->service->name,
            'admin'=>$request->admin,
            'total'=>$request->total,
            'pajak'=>$request->pajak,
            'grand_total'=>$request->grand_total,
            'order_by'=>$request->order_by,
            'lunas'=> "N",
            'expired_date'=>$request->expired_date,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
        ]);
        
        $oldInv->update([
            'last_expired_date'=>$request->expired_date,
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
                                    'inv_no'=>$extend->inv_no,
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
                                    'kode'=>$detail->kode,
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
                        'inv_no'=>$extend->inv_no,
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
    public function Oldpost(Request $request)
    {
        $oldInv = InvoiceImport::where('id', $request->inv_id)->first();
        $cont = "["."". $request->contKey_Selected . "" ."]";
        $cust = Customer::where('id', $request->cust_id)->first();
        $invoiceNo = 'DS-' . $this->getNextInvoiceExtend();
        $itemtArray = json_decode($cont);
        $item = Item::where('container_key', $itemtArray)->get();
        $extend = Extend::create([
            'proforma_no'=>$oldInv->proforma_no,
            'inv_id'=>$oldInv->id,
            'inv_no'=>$invoiceNo,
            'cust_id'=>$cust->id,
            'cust_name'=>$cust->name,
            'fax'=>$cust->fax,
            'npwp'=>$cust->npwp,
            'alamat'=>$cust->alamat,
            'os_id'=>$oldInv->os_id,
            'os_name'=>$oldInv->os_name,
            'container_key'=>"["."". $request->contKey_Selected . "" ."]",
            'm1'=>$request->m1,
            'm2'=>$request->m2,
            'm3'=>$request->m3,
            'ctr_20'=>$request->ctr_20,
            'ctr_40'=>$request->ctr_40,
            'ctr_21'=>$request->ctr_21,
            'ctr_42'=>$request->ctr_42,
            'm1_20'=>$request->m1_20,
            'm2_20'=>$request->m2_20,
            'm3_20'=>$request->m3_20,
            'm1_21'=>$request->m1_21,
            'm2_21'=>$request->m2_21,
            'm3_21'=>$request->m3_21,
            'm1_40'=>$request->m1_40,
            'm2_40'=>$request->m2_40,
            'm3_40'=>$request->m3_40,
            'm1_42'=>$request->m1_42,
            'm2_42'=>$request->m2_42,
            'm3_42'=>$request->m3_42,
            'admin'=>$request->admin,
            'total'=>$request->total,
            'pajak'=>$request->pajak,
            'grand_total'=>$request->grand_total,
            'order_by'=>$request->order_by,
            'lunas'=> "N",
            'expired_date'=>$request->expired_date,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
        ]);

        if ($extend->os_id = '1' || $extend->os_id = '2' || $extend->os_id = '5' || $extend->os_id = '16')  {
            $kode = "PPSP2-";
        }else {
            $kode = "PPSPS-";
        }
        if ($extend->ctr_20 != null) {
            $detail20 = Detail::create([
                'inv_id'=>$extend->id,
                'inv_no'=>$extend->inv_no,
                'inv_type'=>'XTD',
                'keterangan'=>'Invoice Extend',
                'detail'=> $kode.'20',
                'ukuran'=>'20',
                'jumlah'=>$extend->ctr_20,
                'satuan'=>'unit',
                'harga'=>$extend->lolo_mty_20,
                'expired_date'=>$extend->expired_date,
                'order_date'=>$extend->order_at,
                'lunas'=>$extend->lunas,
                'cust_id'=>$extend->cust_name,
                'cust_name'=>$extend->cust_id
            ]);
        }
        if ($extend->ctr_21 != null) {
            $detail21 = Detail::create([
                'inv_id'=>$extend->id,
                'inv_no'=>$extend->inv_no,
                'inv_type'=>'XTD',
                'keterangan'=>'Invoice Extend',
                'detail'=> $kode.'21',
                'ukuran'=>'21',
                'jumlah'=>$extend->ctr_21,
                'satuan'=>'unit',
                'harga'=>$extend->lolo_mty_21,
                'expired_date'=>$extend->expired_date,
                'order_date'=>$extend->order_at,
                'lunas'=>$extend->lunas,
                'cust_id'=>$extend->cust_name,
                'cust_name'=>$extend->cust_id
            ]);
        }
        if ($extend->ctr_40 != null) {
            $detail40 = Detail::create([
                'inv_id'=>$extend->id,
                'inv_no'=>$extend->inv_no,
                'inv_type'=>'XTD',
                'keterangan'=>'Invoice Extend',
                'detail'=> $kode.'40',
                'ukuran'=>'40',
                'jumlah'=>$extend->ctr_40,
                'satuan'=>'unit',
                'harga'=>$extend->lolo_mty_40,
                'expired_date'=>$extend->expired_date,
                'order_date'=>$extend->order_at,
                'lunas'=>$extend->lunas,
                'cust_id'=>$extend->cust_name,
                'cust_name'=>$extend->cust_id
            ]);
        }
        if ($extend->ctr_42 != null) {
            $detail42 = Detail::create([
                'inv_id'=>$extend->id,
                'inv_no'=>$extend->inv_no,
                'inv_type'=>'XTD',
                'keterangan'=>'Invoice Extend',
                'detail'=> $kode.'42',
                'ukuran'=>'42',
                'jumlah'=>$extend->ctr_42,
                'satuan'=>'unit',
                'harga'=>$extend->lolo_mty_42,
                'expired_date'=>$extend->expired_date,
                'order_date'=>$extend->order_at,
                'lunas'=>$extend->lunas,
                'cust_id'=>$extend->cust_name,
                'cust_name'=>$extend->cust_id
            ]);
        }
        // $contArray = explode(',', $cont[0]);
        // dd($contArray, $cont);
        $contArray = json_decode($cont);
            foreach ($contArray as $idCont) {
                $selectCont = Item::where('container_key', $idCont)->get();
                
                foreach ($selectCont as $item) {
                    $item->update([
                        'selected_do' => 'Y'
                    ]);

                    $lastJobNo = JobExtend::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
                        $job = JobExtend::create([
                            'inv_id'=>$extend->id,
                            'job_no'=>$jobNo,
                            'os_id'=>$extend->os_id,
                            'os_name'=>$extend->os_name,
                            'cust_id'=>$extend->cust_id,
                            'active_to'=>$extend->expired_date,
                            'container_key'=>$item->container_key,
                            'container_no'=>$item->container_no,
                            'ves_id'=>$item->ves_id,
                        ]);
                }
            }
        return redirect()->route('index-extend')->with('success', 'Invoice Berhasil Di Buat');
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
                'invoice_no'=>$invoice->inv_no,
                'job_no' => $job->job_no,
                'order_service' => $bigOS->order,
            ]);
        }

        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'Y'
            ]);
        }

        $invoice->update([
            'lunas' => 'Y',
            'lunas_at'=> Carbon::now(),
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
                'invoice_no'=>$invoice->inv_no,
                'job_no' => $job->job_no,
                'order_service' => $bigOS->order,
            ]);
        }

        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'P'
            ]);
        }

        $invoice->update([
            'lunas' => 'P',
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
        $data['inv'] = Extend::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['inv']->form_id)->first();
        date_default_timezone_set('Asia/Jakarta');
        $data['now'] = Carbon::now();
        $data['formattedDate'] = $data['now']->format('l, d-m-Y');
        $data['job'] = JobExtend::where('inv_id', $id)->get();
        $data['cont'] = Item::get();
        foreach ($data['job'] as $jb) {
            foreach ($data['cont'] as $ct) {
                if ($ct->container_key == $jb->container_key) {
                    $qrcodes[$jb->id] = QrCode::size(100)->generate($ct->container_no);
                    break;
                }
            }
        }
        return view('billingSystem.import.job.main',compact('qrcodes'), $data);
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


private function getNextInvoiceExtend()
{
    // Mendapatkan nomor proforma terakhir
    $latest = Extend::orderBy('order_at', 'desc')->first();

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
            $item->update([
                'selected_do'=>'N',
            ]);
            $cont->delete();
        }

        $form = Form::where('id', $id)->first();
        $form->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}

