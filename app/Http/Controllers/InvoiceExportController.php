<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\Customer;
use App\Models\Item;
use App\Models\KodeDok;
use App\Models\RO;
use App\Models\VVoyage;
use App\Models\InvoiceExport;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\ExportDetail as Detail;
use App\Models\JobExport;
use App\Models\InvoiceHeaderStevadooring;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Exports\ReportInvoice;
use App\Exports\ReportInvoiceAllExport;


use Auth;
use Carbon\Carbon;

class InvoiceExportController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function billingMain()
    {
        $data['title'] = "Reciving Billing System";
        $data['invoice'] = InvoiceExport::orderBy('order_at', 'asc')->orderBy('lunas', 'asc')->get();

        $data['service'] = OS::where('ie', '=' , 'E')->orderBy('id', 'asc')->get();
        $data['unPaids'] = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'E');
        })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'asc')->get();
        $data['piutangs'] = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'E');
        })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'asc')->get();
        $data['countUnpaids'] = $data['unPaids']->count();
        $data['totalUnpaids'] = $data['unPaids']->sum('total');
        $data['grandTotalUnpaids'] = $data['unPaids']->sum('grand_total');

        $data['countPiutangs'] = $data['piutangs']->count();
        $data['totalPiutangs'] = $data['piutangs']->sum('total');
        $data['grandTotalPiutangs'] = $data['piutangs']->sum('grand_total');
        return view('billingSystem.export.billing.main', $data);
    }
    public function detilUnpaid()
    {
        $data['title'] = "Reciving Billing System (Unpaid Invoice)";
        $data['unPaids'] = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'E');
        })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'asc')->get();
        return view('billingSystem.export.billing.detil.unPaid', $data);
    }
   
    public function detilPiutang()
    {
        $data['title'] = "Reciving Billing System (Piutang Invoice)";
        $data['piutangs'] = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'E');
        })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'asc')->get();
        return view('billingSystem.export.billing.detil.piutang', $data);
    }
    public function detilInvoice($id)
    {
        $data['title'] = "Reciving Billing System (Piutang Invoice)";
        $data['os'] = OS::find($id);
        $data['invoice'] = InvoiceExport::whereNot('form_id', '=', '')->where('os_id', $id)->orderBy('order_at', 'asc')->orderBy('lunas', 'asc')->get();
        return view('billingSystem.export.billing.detil.detil', $data);
    }

    public function deliveryMenuExport()
    {
        $data['title'] = "Reciving Menu";
        $data['formInvoiceImport'] = Form::where('i_e', 'E')->where('done', '=', 'N')->get();

        return view('billingSystem.export.form.main', $data);
    }

    public function deliveryEdit($id)
    {
        $user = Auth::user();
        $data['title'] = "Reciving Form";
        $data["user"] = $user->id;

        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'E')->get();
      
        $data['ves'] = VVoyage::where('arrival_date', '<=', Carbon::now())->get();
        $data['form'] = Form::where('id', $id)->first();
        $data['containerInvoice'] = Container::where('form_id', $id)->get();
        $cont = Item::where('ctr_intern_status', ['49'])->where('selected_do', 'N')->get();
        $data['contBooking'] = $cont->unique('booking_no')->pluck('booking_no');
        $data['roDok'] = RO::get();
        $data['kapalRO'] = VVoyage::where('clossing_date', '>=', Carbon::now())->get();
        return view('billingSystem.export.form.edit', $data);
    }

    public function deliveryFormExport()
    {
        $user = Auth::user();
        $data['title'] = "Reciving Form";
        $data["user"] = $user->id;

        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'E')->get();
        $data['dok_ro'] = RO::get();
        $cont = Item::where('ctr_intern_status', ['49'])->where('selected_do', 'N')->get();
        $data['contBooking'] = Item::where('ctr_intern_status', '49')
        ->where('selected_do', 'N')
        ->distinct()
        ->pluck('booking_no');
        $data['roDok'] = RO::get();
        $data['kapalRO'] = VVoyage::where('clossing_date', '>=', Carbon::now())->get();

        return view('billingSystem.export.form.create', $data);
    }

    public function getDOdataExport(Request $request)
    {
        $booking = $request->bookingNo;
       
        $os = $request->os;
        if (empty($os)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih Order Service Dahulu !!',
            ]);
        }
       $cont = Item::where('booking_no', $booking)->where('ctr_intern_status', ['49'])->where('selected_do', 'N')->get();
       $singleCont = Item::where('booking_no', $booking)->where('ctr_intern_status', ['49'])->where('selected_do', 'N')->first();
    
       if ($singleCont->ves_id == 'PELINDO') {
        $kapal =[
            'ves_name' => 'Pelindo',
            'ves_code' => 'Pelindo',
            'voy_out' =>null,
            'clossing_date' =>null,
            'eta_date' =>null,
            'etd_date' =>null,
        ];
       }else {
        $kapal = VVoyage::where('ves_id', $singleCont->ves_id)->first();
       }
       if (!empty($cont)) {
        return response()->json([
            'success' => true,
            'message' => 'Pilih Order Service Dahulu !!',
            'data' => $cont,
            'kapal' =>$kapal,
        ]);
       }else {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada container yang dapat digunakan !!',
        ]);
       }
    }

    public function getOrder(Request $request)
    {
        $os = OS::where('id', $request->id)->first();
        if ($os->order == 'SPPSD') {
            $cont = Item::where('ctr_status', '=', 'MTY')
                        ->whereIn('ctr_intern_status', ['04', '06', '53', '56'])
                        ->get();

            if ($cont->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada kontainer yang dapat digunakan !!',
                    'data' => $os,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => '',
                    'data' => $os,
                    'cont' => $cont,
                ]);
            }
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada container yang dapat digunakan !!',
                'data' => $os
            ]);
        }
        
    }

    public function getROdataExport(Request $request)
    {
        $RoNo = $request->RoNo;
      
        $os = $request->os;
        if (empty($os)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih Order Service Dahulu !!',
            ]);
        }
        $cont = Item::where('ro_no', $RoNo)
        ->whereIn('ctr_intern_status', ['04', '06', '53', '56'])
        ->where('selected_do', 'N')
        ->get();
            
        if ($cont->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada kontainer yang dapat digunakan !!',
        ]);
        } else {
        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $cont,
        ]);
        }
    }

    public function formInvoice($id)
    {
        $data['title'] = 'Invoice Export';
        $form = Form::where('id', $id)->first();
        $bigOS = OS::where('id', $form->os_id)->first();
        $data['customer'] = Customer::where('id', $form->cust_id)->first();
        $data['expired'] = $form->expired_date;
        $data['discDate'] = $form->disc_date;
        // $data['doOnline'] = DOonline::where('id', $form->do_id)->first();
        $data['service'] = OS::where('id', $form->os_id)->first();
        $data['selectCont'] = Container::where('form_id', $id)->get();
        $containerInvoice = Container::where('form_id', $id)->get();
        $discDate = Carbon::parse($form->disc_date);
        $expDate = Carbon::parse($form->expired_date);
        $expDate->addDay(2);
        $interval = $discDate->diff($expDate);
        $jumlahHari = $interval->days;
        $osDSK = OSDetail::where('os_id', $form->os_id)->where('type', '=', 'OSK')->get();
        $data['dsk'] = $osDSK->isNotEmpty() ? 'Y' : 'N';
        $osDS = OSDetail::where('os_id', $form->os_id)->where('type', '=', 'OS')->get();
        $data['ds'] = $osDS->isNotEmpty() ? 'Y' : 'N';

        $groupedBySize = $containerInvoice->groupBy('ctr_size');
        $ctrGroup = $groupedBySize->map(function ($sizeGroup) {
            return $sizeGroup->groupBy('ctr_status');
        });
        $data['ctrGroup'] = $ctrGroup;

        $resultsDSK = collect(); // Use a collection to store the results

        if ($data['dsk'] == 'Y') {
            $data['adminDSK'] = 0;
            $service = $osDSK;
            foreach ($service as $svc) {
                foreach ($ctrGroup as $size => $statusGroup) {
                    foreach ($statusGroup as $status => $containers) {
                        $containerCount = $containers->count();
                        $tarif = MT::where('os_id', $bigOS->id)
                            ->where('ctr_size', $size)
                            ->where('ctr_status', $status)
                            ->first();
                        $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
                            ->where('master_item_id', $svc->master_item_id)
                            ->first();
                        if ($tarifDetail) {
                            if ($tarifDetail->count_by == 'C') {
                                $hargaC = $tarifDetail->tarif * $containerCount;
                                $resultsDSK->push([
                                    'ctr_size' => $size,
                                    'ctr_status' => $status,
                                    'count_by' => 'C',
                                    'tarif' => $tarifDetail->tarif,
                                    'containerCount' => $containerCount,
                                    'jumlahHari' => 0,
                                    'keterangan' => $tarifDetail->master_item_name,
                                    'harga' => $hargaC,
                                ]);
                            } elseif ($tarifDetail->count_by == 'T') {
                                $hargaT = $tarifDetail->tarif * $containerCount;
                                $resultsDSK->push([
                                    'ctr_size' => $size,
                                    'ctr_status' => $status,
                                    'count_by' => 'T',
                                    'tarif' => $tarifDetail->tarif,
                                    'jumlahHari' => 1,
                                    'containerCount' => $containerCount,
                                    'keterangan' => $tarifDetail->master_item_name,
                                    'harga' => $hargaT,
                                ]);
                            }
                        }
                    }
                }
                $singleTarif = MT::where('os_id', $bigOS->id)->first();
                $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                    ->where('master_item_id', $svc->master_item_id)
                    ->where('count_by', 'O')
                    ->first();
                if ($singleTarifDetail) {
                    $data['adminDSK'] = $singleTarifDetail->tarif;
                } 
            }
            $data['totalDSK'] = $resultsDSK->sum('harga');
            $data['resultsDSK'] = $resultsDSK;
            $data['discountDSK'] = $form->discount_dsk;
            $data['pajakDSK'] = (($data['totalDSK'] + $data['adminDSK']) - $data['discountDSK']) * 11 / 100;
            $data['grandTotalDSK'] = (($data['totalDSK'] + $data['adminDSK']) - $data['discountDSK']) + $data['pajakDSK'];
        }

        if ($data['ds'] == 'Y') {
            $data['adminDS'] = 0;
            $resultsDS = collect(); // Use a collection to store the results
            $service = $osDS;
            foreach ($service as $svc) {
                foreach ($ctrGroup as $size => $statusGroup) {
                    foreach ($statusGroup as $status => $containers) {
                        $containerCount = $containers->count();
                        $tarif = MT::where('os_id', $bigOS->id)
                            ->where('ctr_size', $size)
                            ->where('ctr_status', $status)
                            ->first();
                        $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
                            ->where('master_item_id', $svc->master_item_id)
                            ->first();
                        if ($tarifDetail) {
                            if ($tarifDetail->count_by == 'C') {
                                $hargaC = $tarifDetail->tarif * $containerCount;
                                $resultsDS->push([
                                    'ctr_size' => $size,
                                    'ctr_status' => $status,
                                    'count_by' => 'C',
                                    'tarif' => $tarifDetail->tarif,
                                    'jumlahHari' => 0,
                                    'containerCount' => $containerCount,
                                    'keterangan' => $tarifDetail->master_item_name,
                                    'harga' => $hargaC,
                                ]);
                            } elseif ($tarifDetail->count_by == 'T') {
                                $hargaT = $tarifDetail->tarif * $containerCount;
                                $resultsDS->push([
                                    'ctr_size' => $size,
                                    'ctr_status' => $status,
                                    'count_by' => 'T',
                                    'tarif' => $tarifDetail->tarif,
                                    'jumlahHari' => 1,
                                    'containerCount' => $containerCount,
                                    'keterangan' => $tarifDetail->master_item_name,
                                    'harga' => $hargaT,
                                ]);
                            }
                        }
                    }
                }
                $singleTarif = MT::where('os_id', $bigOS->id)->first();
                $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                    ->where('master_item_id', $svc->master_item_id)
                    ->where('count_by', 'O')
                    ->first();
                if ($singleTarifDetail) {
                    $data['adminDS'] = $singleTarifDetail->tarif;
                }
            }
            $data['totalDS'] = $resultsDS->sum('harga');
            $data['resultsDS'] = $resultsDS;
            $data['discountDS'] = $form->discount_ds;
            $data['pajakDS'] = (($data['totalDS'] + $data['adminDS']) - $data['discountDS']) * 11 / 100;
            $data['grandTotalDS'] = (($data['totalDS'] + $data['adminDS']) - $data['discountDS']) + $data['pajakDS'];
        }

        // dd($osDSK, $service, $resultsDSK, $resultsDS);
        return view('billingSystem.export.form.pre-invoice', compact('form'), $data);
    }

    public function beforeCreate(Request $request)
    {
        $contSelect = $request->container;
        $singleCont = Item::whereIn('container_key', $contSelect)->first();
        $os = OS::where('id', $request->order_service)->first();
        if ($os->order == 'SPPS') {
            $kapal = $singleCont->ves_id;
            if ($kapal == 'PELINDO') {
               $ves = (object)[
                'ves_id'=>'PELINDO',
                'ves_name'=>'PELINDO',
                'clossing_date' => null,

               ];
            }else {
                $vessel = VVoyage::where('ves_id', $kapal)->first();
                $ves = (object)[
                    'ves_id'=>$vessel->ves_id,
                    'ves_name'=>$vessel->ves_name,
                    'clossing_date' => $vessel->clossing_date,

                   ];
            }
        }else {
            $kapal = $singleCont->ves_id;
            if ($kapal == 'PELINDO') {
               $ves = (object)[
                'ves_id'=>'PELINDO',
                'ves_name'=>'PELINDO',
                'clossing_date' => null,
               ];
            }else {
                $vessel = VVoyage::where('ves_id', $kapal)->first();
                $ves = (object)[
                    'ves_id'=>$vessel->ves_id,
                    'ves_name'=>$vessel->ves_name,
                    'clossing_date' => $vessel->clossing_date,
                   ];
            }
        }
        
        $invoice = Form::create([
            'expired_date'=>$ves->clossing_date,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'do_id'=>$request->booking_no,
            'ves_id'=> $ves->ves_id,
            'i_e'=>'E',
            'disc_date'=>Carbon::now(),
            'done'=>'N',
            'discount_ds'=>$request->discount_ds,
            'discount_dsk'=>$request->discount_dsk,
        ]);

       
        foreach ($contSelect as $cont) {
            $item = Item::where('container_key', $cont)->first();
            $contInvoice = Container::create([
                'container_key'=>$item->container_key,
                'container_no'=>$item->container_no,
                'ctr_size'=>$item->ctr_size,
                'ctr_status'=>$item->ctr_status,
                'form_id'=>$invoice->id,
                'ves_id'=>$ves->ves_id,
                'ves_name'=>$ves->ves_name,
                'ctr_type'=>$item->ctr_type,
                'ctr_intern_status'=>$item->ctr_intern_status,
                'gross'=>$item->gross,
            ]);
        }
        // dd($cont);

        return redirect()->route('formInvoiceExport', ['id' => $invoice->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }

    public function updateFormExport(Request $request)
    {
        $form = Form::where('id', $request->form_id)->first();

        $newContainer = $request->container;
        $contSelect = $request->container;
        $singleCont = Item::whereIn('container_key', $contSelect)->first();
        $os = OS::where('id', $request->order_service)->first();
        if ($os->order == 'SPPS') {
            $kapal = $request->ves_id;
            if ($kapal == 'PELINDO') {
               $ves = (object)[
                'ves_id'=>'PELINDO',
                'ves_name'=>'PELINDO',
                'clossing_date' => null,
               ];
            }else {
                $vessel = VVoyage::where('ves_id', $kapal)->first();
                $ves = (object)[
                    'ves_id'=>$vessel->ves_id,
                    'ves_name'=>$vessel->ves_name,
                    'clossing_date' => $vessel->clossing_date
                   ];
            }
        }else {
            $vessel = VVoyage::where('ves_id', $singleCont->ves_id)->first();
            $ves = (object)[
                'ves_id'=>$vessel->ves_id,
                'ves_name'=>$vessel->ves_name,
                'clossing_date' => $vessel->clossing_date,
               ];
        }

        $oldCont = Container::where('form_id', $form->id)->get();
        foreach ($oldCont as $cont) {
            $cont->delete();
        }

        $form->update([
            'expired_date'=>$ves->clossing_date,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'do_id'=>$request->booking_no,
            'ves_id'=> $ves->ves_id,
            'i_e'=>'E',
            'disc_date'=>Carbon::now(),
            'done'=>'N',
            'discount_ds'=>$request->discount_ds,
            'discount_dsk'=>$request->discount_dsk,
        ]);
        foreach ($newContainer as $cont) {
            $item = Item::where('container_key', $cont)->first();
            $contInvoice = Container::create([
                'container_key'=>$item->container_key,
                'container_no'=>$item->container_no,
                'ctr_size'=>$item->ctr_size,
                'ctr_status'=>$item->ctr_status,
                'form_id'=>$form->id,
                'ves_id'=>$ves->ves_id,
                'ves_name'=>$ves->ves_name,
                'ctr_type'=>$item->ctr_type,
                'ctr_intern_status'=>$item->ctr_intern_status,
                'gross'=>$item->gross,
            ]);
        }

        return redirect()->route('formInvoiceExport', ['id' => $form->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }


    public function invoiceExport(Request $request)
    {
        $form = Form::where('id', $request->formId)->first();
        $bigOS = OS::where('id', $form->os_id)->first();
        $now = Carbon::now();
        $discDate = Carbon::parse($form->disc_date);
        $expDate = Carbon::parse($form->expired_date);
        $expDate->addDay(2);
        $interval = $discDate->diff($expDate);
        $jumlahHari = $interval->days;
        $cont = Container::where('form_id', $form->id)->get();
        $groupedBySize = $cont->groupBy('ctr_size');
        $ctrGroup = $groupedBySize->map(function ($sizeGroup) {
            return $sizeGroup->groupBy('ctr_status');
        });
        $osDSK = OSDetail::where('os_id', $form->os_id)->where('type', 'OSK')->get();
        $dsk = $osDSK->isEmpty() ? 'N' : 'Y';
        
        $osDS = OSDetail::where('os_id', $form->os_id)->where('type', 'OS')->get();
        $ds = $osDS->isEmpty() ? 'N' : 'Y';

        // dd($ds, $dsk);

       if ($dsk == 'Y') {
        $nextProformaNumber = $this->getNextProformaNumber();
        $invoiceNo = $this->getNextInvoiceDSK();
        $invoiceDSK = InvoiceExport::create([
            'inv_type'=>'OSK',
            'form_id'=>$form->id,
            
            'proforma_no'=>$nextProformaNumber,
            'cust_id'=>$form->cust_id,
            'cust_name'=>$form->customer->name,
            'fax'=>$form->customer->fax,
            'npwp'=>$form->customer->npwp,
            'alamat'=>$form->customer->alamat,
            'os_id'=>$form->os_id,
            'os_name'=>$form->service->name,
            'massa1'=>$jumlahHari,
            'lunas'=>'N',
            'expired_date'=>$form->expired_date,
            'disc_date' => $form->disc_date,
            'booking_no'=>$form->do_id,
            'total'=>$request->totalDSK,
            'admin'=>$request->adminDSK,
            'discount'=>$request->discountDSK,
            'pajak'=>$request->pajakDSK,
            'grand_total'=>$request->grandTotalDSK,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
         
            
        ]);
        $admin = 0;
        foreach ($osDSK as $service) {
            foreach ($ctrGroup as $size => $statusGroup) {
                foreach ($statusGroup as $status => $containers) {
                    $containerCount = $containers->count();
                    $tarif = MT::where('os_id', $bigOS->id)
                            ->where('ctr_size', $size)
                            ->where('ctr_status', $status)
                            ->first();
                    $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
                        ->where('master_item_id', $service->master_item_id)
                        ->first();
                    if ($tarifDetail) {
                        if ($service->kode != 'PASSTRUCK') {
                            $kode = $service->kode . $size;
                        }else {
                            $kode = 'PASSTRUCK';
                        }
                        if ($tarifDetail->count_by == 'C') {
                            $hargaC = $tarifDetail->tarif * $containerCount;
                            $detailImport = Detail::create([
                             'inv_id'=>$invoiceDSK->id,
                            
                             'inv_type'=>$invoiceDSK->inv_type,
                             'keterangan'=>$form->service->name,
                             'ukuran'=>$size,
                             'jumlah'=>$containerCount,
                             'satuan'=>'unit',
                             'expired_date'=>$form->expired_date,
                             'order_date'=>$invoiceDSK->order_at,
                             'lunas'=>'N',
                             'cust_id'=>$form->cust_id,
                             'cust_name'=>$form->customer->name,
                             'os_id'=>$form->os_id,
                             'jumlah_hari'=> 0,
                             'master_item_id'=>$service->master_item_id,
                             'master_item_name'=>$service->master_item_name,
                             'kode'=>$kode,
                             'tarif'=>$tarifDetail->tarif,
                             'total'=>$hargaC,
                             'form_id'=>$form->id,
                             'count_by'=>'C',
                            ]);

                        }elseif ($tarifDetail->count_by == 'T') {
                            $hargaC = $tarifDetail->tarif * $containerCount;
                            $detailImport = Detail::create([
                             'inv_id'=>$invoiceDSK->id,
                            
                             'inv_type'=>$invoiceDSK->inv_type,
                             'keterangan'=>$form->service->name,
                             'ukuran'=>$size,
                             'jumlah'=>$containerCount,
                             'satuan'=>'unit',
                             'expired_date'=>$form->expired_date,
                             'order_date'=>$invoiceDSK->order_at,
                             'lunas'=>'N',
                             'cust_id'=>$form->cust_id,
                             'cust_name'=>$form->customer->name,
                             'os_id'=>$form->os_id,
                             'jumlah_hari'=>1,
                             'master_item_id'=>$service->master_item_id,
                             'master_item_name'=>$service->master_item_name,
                             'kode'=>$kode,
                             'tarif'=>$tarifDetail->tarif,
                             'total'=>$hargaC,
                             'form_id'=>$form->id,
                             'count_by'=>'T',
                            ]);
                        }
                    }    
                }
            }
            $singleTarif = MT::where('os_id', $bigOS->id)->first();
                $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                    ->where('master_item_id', $service->master_item_id)
                    ->where('count_by', 'O')
                    ->first();
                if ($singleTarifDetail) {
                    $detailImport = Detail::create([
                        'inv_id'=>$invoiceDSK->id,
                        'inv_no'=>$invoiceDSK->inv_no,
                        'inv_type'=>$invoiceDSK->inv_type,
                        'keterangan'=>$form->service->name,
                        'ukuran'=> '0',
                        'jumlah'=> 1,
                        'satuan'=>'unit',
                        'expired_date'=>$form->expired_date,
                        'order_date'=>$invoiceDSK->order_at,
                        'lunas'=>'N',
                        'cust_id'=>$form->cust_id,
                        'cust_name'=>$form->customer->name,
                        'os_id'=>$form->os_id,
                        'jumlah_hari'=>'0',
                        'master_item_id'=>$service->master_item_id,
                        'master_item_name'=>$service->master_item_name,
                        'kode'=>$service->kode,
                        'tarif'=>$tarifDetail->tarif,
                        'total'=>$tarifDetail->tarif,
                        'form_id'=>$form->id,
                        'count_by'=>'O',
                       ]);
                }
        }
       }

       if ($ds == 'Y') {
        $nextProformaNumber = $this->getNextProformaNumber();
        $invoiceNo = $this->getNextInvoiceDS();
        $invoiceDS = InvoiceExport::create([
            'inv_type'=>'OS',
            'form_id'=>$form->id,
       
            'proforma_no'=>$nextProformaNumber,
            'cust_id'=>$form->cust_id,
            'cust_name'=>$form->customer->name,
            'fax'=>$form->customer->fax,
            'npwp'=>$form->customer->npwp,
            'alamat'=>$form->customer->alamat,
            'os_id'=>$form->os_id,
            'os_name'=>$form->service->name,
            'massa1'=>$jumlahHari,
            'lunas'=>'N',
            'expired_date'=>$form->expired_date,
            'disc_date' => $form->disc_date,
            'booking_no'=>$form->do_id,
            'total'=>$request->totalDS,
            'admin'=>$request->adminDS,
            'discount'=>$request->discountDS,
            'pajak'=>$request->pajakDS,
            'grand_total'=>$request->grandTotalDS,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
          
            
        ]);
        $admin = 0;
        foreach ($osDS as $service) {
            foreach ($ctrGroup as $size => $statusGroup) {
                foreach ($statusGroup as $status => $containers) {
                    $containerCount = $containers->count();
                    $tarif = MT::where('os_id', $bigOS->id)
                            ->where('ctr_size', $size)
                            ->where('ctr_status', $status)
                            ->first();
                    $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
                        ->where('master_item_id', $service->master_item_id)
                        ->first();
                    if ($tarifDetail) {
                        if ($service->kode != 'PASSTRUCK') {
                            $kode = $service->kode . $size;
                        }else {
                            $kode = 'PASSTRUCK';
                        }
                       
                        if ($tarifDetail->count_by == 'C') {
                            $hargaC = $tarifDetail->tarif * $containerCount;
                            $detailImport = Detail::create([
                             'inv_id'=>$invoiceDS->id,
                     
                             'inv_type'=>$invoiceDS->inv_type,
                             'keterangan'=>$form->service->name,
                             'ukuran'=>$size,
                             'jumlah'=>$containerCount,
                             'satuan'=>'unit',
                             'expired_date'=>$form->expired_date,
                             'order_date'=>$invoiceDS->order_at,
                             'lunas'=>'N',
                             'cust_id'=>$form->cust_id,
                             'cust_name'=>$form->customer->name,
                             'os_id'=>$form->os_id,
                             'jumlah_hari'=> 0,
                             'master_item_id'=>$service->master_item_id,
                             'master_item_name'=>$service->master_item_name,
                             'kode'=>$kode,
                             'tarif'=>$tarifDetail->tarif,
                             'total'=>$hargaC,
                             'form_id'=>$form->id,
                             'count_by'=>'C',
                            ]);

                        }elseif ($tarifDetail->count_by == 'T') {
                            $hargaC = $tarifDetail->tarif * $containerCount;
                            $detailImport = Detail::create([
                             'inv_id'=>$invoiceDS->id,
      
                             'inv_type'=>$invoiceDS->inv_type,
                             'keterangan'=>$form->service->name,
                             'ukuran'=>$size,
                             'jumlah'=>$containerCount,
                             'satuan'=>'unit',
                             'expired_date'=>$form->expired_date,
                             'order_date'=>$invoiceDS->order_at,
                             'lunas'=>'N',
                             'cust_id'=>$form->cust_id,
                             'cust_name'=>$form->customer->name,
                             'os_id'=>$form->os_id,
                             'jumlah_hari'=>1,
                             'master_item_id'=>$service->master_item_id,
                             'master_item_name'=>$service->master_item_name,
                             'kode'=>$kode,
                             'tarif'=>$tarifDetail->tarif,
                             'total'=>$hargaC,
                             'form_id'=>$form->id,
                             'count_by'=>'T',
                            ]);
                        }
                    }    
                }
            }
            $singleTarif = MT::where('os_id', $bigOS->id)->first();
                $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                    ->where('master_item_id', $service->master_item_id)
                    ->where('count_by', 'O')
                    ->first();
                if ($singleTarifDetail) {
                    $detailImport = Detail::create([
                        'inv_id'=>$invoiceDS->id,
            
                        'inv_type'=>$invoiceDS->inv_type,
                        'keterangan'=>$form->service->name,
                        'ukuran'=> '0',
                        'jumlah'=> 1,
                        'satuan'=>'unit',
                        'expired_date'=>$form->expired_date,
                        'order_date'=>$invoiceDS->order_at,
                        'lunas'=>'N',
                        'cust_id'=>$form->cust_id,
                        'cust_name'=>$form->customer->name,
                        'os_id'=>$form->os_id,
                        'jumlah_hari'=>'0',
                        'master_item_id'=>$service->master_item_id,
                        'master_item_name'=>$service->master_item_name,
                        'kode'=>$service->kode,
                        'tarif'=>$tarifDetail->tarif,
                        'total'=>$tarifDetail->tarif,
                        'form_id'=>$form->id,
                        'count_by'=>'O',
                       ]);
                }
        }
       }

       $form->update([
        'done'=>'Y',
       ]);
       foreach ($cont as $contItem) {
        $item = Item::where('container_key', $contItem->container_key)->first();
        $item->update([
            'selected_do'=>'Y',
            'os_id'=>$form->os_id,
        ]);
       }
       return redirect()->route('billingExportMain')->with('success', 'Menunggu Pembayaran');

       
    }

    private function getNextProformaNumber()
    {
        // Mendapatkan nomor proforma terakhir
        $latestProforma = InvoiceExport::orderBy('proforma_no', 'desc')->first();
    
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
    
    private function getNextInvoiceDSK()
    {
        // Mendapatkan nomor invoice terakhir dari kedua tabel
        $latestExport = InvoiceExport::where('inv_type', 'OSK')->orderBy('inv_no', 'desc')->first();
        $latestStev = InvoiceHeaderStevadooring::orderBy('invoice_no', 'desc')->first();

        // Jika tidak ada invoice sebelumnya di kedua tabel, kembalikan nomor invoice awal
        if (!$latestExport && !$latestStev) {
            return 'OSK0000001';
        }

        // Mendapatkan nomor invoice terakhir dari kedua tabel
        $lastExportInvoice = $latestExport ? $latestExport->inv_no : null;
        $lastStevInvoice = $latestStev ? $latestStev->invoice_no : null;

        // Mengekstrak angka dari nomor invoice terakhir dari kedua tabel
        $lastExportNumber = $lastExportInvoice ? (int)substr($lastExportInvoice, 3) : 0;
        $lastStevNumber = $lastStevInvoice ? (int)substr($lastStevInvoice, 3) : 0;

        // Menentukan nomor invoice terbesar dari kedua tabel
        $lastNumber = max($lastExportNumber, $lastStevNumber);

        // Menambahkan 1 ke nomor invoice terakhir
        $nextNumber = $lastNumber + 1;

        // Menghasilkan nomor invoice berikutnya dengan format yang benar
        return 'OSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    
    private function getNextInvoiceDS()
    {
        // Mendapatkan nomor proforma terakhir
        $latest = InvoiceExport::where('inv_type', 'OS')->orderBy('inv_no', 'desc')->first();
    
        // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
        if (!$latest) {
            return 'OS0000001';
        }
    
        // Mendapatkan nomor urut proforma terakhir
        $lastInvoice = $latest->inv_no;
    
        // Mengekstrak angka dari nomor proforma terakhir
        $lastNumber = (int)substr($lastInvoice, 3);
    
        // Menambahkan 1 ke nomor proforma terakhir
        $nextNumber = $lastNumber + 1;
    
        // Menghasilkan nomor proforma berikutnya dengan format yang benar
        return 'OS' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }
    
    private function getNextJob($lastJobNo)
    {
        // Jika tidak ada nomor pekerjaan sebelumnya, kembalikan nomor pekerjaan awal
        if (!$lastJobNo) {
            return 'JOB0000001';
        }
    
        // Mengekstrak angka dari nomor pekerjaan terakhir
        $lastNumber = (int)substr($lastJobNo, 3);
    
        // Menambahkan 1 ke nomor pekerjaan terakhir
        $nextNumber = $lastNumber + 1;
    
        // Menghasilkan nomor pekerjaan berikutnya dengan format yang benar
        return 'JOB' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    public function recivingInvoiceDelete($id)
    {
        $invoice = InvoiceExport::where('form_id', $id)->get();
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
            $item->update([
                'selected_do'=>'N',
                'os_id'=>null,
            ]);
            $cont->delete();
        }

        $form = Form::where('id', $id)->first();
        $form->delete();

        return response()->json(['message' => 'Data berhasil dihapus.', 'status' => 'success']);
    }

    public function PranotaExportOSK($id)
    {

        $data['title'] = "Pranota";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDSK = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDSK) {
            $data['admin'] = $adminDSK->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);


        return view('billingSystem.export.pranota.dsk', $data);
    }
    public function PranotaExportOS($id)
    {

        $data['title'] = "Pranota";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDS = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDS) {
            $data['admin'] = $adminDS->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.export.pranota.ds', $data);
    }

    public function payExport($id)
    {
        $invoice = InvoiceExport::where('id', $id)->first();
        if ($invoice) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $invoice,
            ]);
        }
    }

    public function payFullExport(Request $request)
    {
        $id = $request->inv_id;

        $invoice = InvoiceExport::where('id', $id)->first();
        if ($invoice->lunas == 'N') {
            $invDate = Carbon::now();
        }else {
            $invDate = $invoice->invoice_date;
        }
        if ($invoice->inv_no == null) {
            if ($invoice->inv_type == 'OSK' ) {
                $invoiceNo = $this->getNextInvoiceDSK();
            }else {
                $invoiceNo = $this->getNextInvoiceDS();
            }
       }else {
         $invoiceNo = $invoice->inv_no;
       }
        $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        $bigOS = OS::where('id', $invoice->os_id)->first();
        foreach ($containerInvoice as $cont) {
            $lastJobNo = JobExport::orderBy('id', 'desc')->value('job_no');
            $jobNo = $this->getNextJob($lastJobNo);
            $job = JobExport::where('inv_id', $invoice->id)->where('container_key', $cont->container_key)->first();
            if (!$job) {
                $job = JobExport::create([
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

        if ($request->couple == 'on') {
            $form = Form::find($invoice->form_id);
            $coupleInvoice = InvoiceExport::where('form_id', $form->id)->whereNot('id', $invoice->id)->first();
            if ($coupleInvoice && $coupleInvoice->lunas != 'Y') {
                if ($coupleInvoice->lunas == 'N') {
                    $coupleDate = Carbon::now();
                }else {
                    $coupleDate = $coupleInvoice->invoice_date;
                }
                if ($coupleInvoice->inv_no == null) {
                    if ($coupleInvoice->inv_type == 'OSK' ) {
                        $coupleInvoiceNo = $this->getNextInvoiceDSK();
                    }else {
                        $coupleInvoiceNo = $this->getNextInvoiceDS();
                    }
               }else {
                 $coupleInvoiceNo = $coupleInvoice->inv_no;
               }
                $containerInvoice = Container::where('form_id', $coupleInvoice->form_id)->get();
                $bigOS = OS::where('id', $coupleInvoice->os_id)->first();
                foreach ($containerInvoice as $cont) {
                    $lastJobNo = JobExport::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
                    $job = JobExport::where('inv_id', $coupleInvoice->id)->where('container_key', $cont->container_key)->first();
                    if (!$job) {
                        $job = JobExport::create([
                            'inv_id'=>$coupleInvoice->id,
                            'job_no'=>$jobNo,
                            'os_id'=>$coupleInvoice->os_id,
                            'os_name'=>$coupleInvoice->os_name,
                            'cust_id'=>$coupleInvoice->cust_id,
                            'active_to'=>$coupleInvoice->expired_date,
                            'container_key'=>$cont->container_key,
                            'container_no'=>$cont->container_no,
                            'ves_id'=>$cont->ves_id,
                        ]);
                    }
                    $item = Item::where('container_key', $cont->container_key)->first();
                    $item->update([
                        'invoice_no'=>$coupleInvoiceNo,
                        'job_no' => $job->job_no,
                        'order_service' => $bigOS->order,
                    ]);
                }
        
                $details = Detail::where('inv_id', $coupleInvoice->id)->get();
                foreach ($details as $detail) {
                    $detail->update([
                    'lunas'=>'Y',
                    'inv_no'=>$coupleInvoiceNo,
                    ]);
                }
        
                $coupleInvoice->update([
                    'lunas' => 'Y',
                    'inv_no'=>$coupleInvoiceNo,
                    'lunas_at'=> Carbon::now(),
                    'invoice_date'=> $coupleDate,
                ]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
            'data'    => $item,
        ]);
        
    }

    public function piutangExport(Request $request)
    {
        $id = $request->inv_id;

        $invoice = InvoiceExport::where('id', $id)->first();
        if ($invoice->inv_no == null) {
            if ($invoice->inv_type == 'OSK' ) {
                $invoiceNo = $this->getNextInvoiceDSK();
            }else {
                $invoiceNo = $this->getNextInvoiceDS();
            }
       }else {
         $invoiceNo = $invoice->inv_no;
       }
        $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        $bigOS = OS::where('id', $invoice->os_id)->first();
        foreach ($containerInvoice as $cont) {
            $lastJobNo = JobExport::orderBy('id', 'desc')->value('job_no');
            $jobNo = $this->getNextJob($lastJobNo);
            $job = JobExport::create([
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

        if ($request->couple == 'on') {
            $form = Form::find($invoice->form_id);
            $coupleInvoice = InvoiceExport::where('form_id', $form->id)->whereNot('id', $invoice->id)->first();
            if ($coupleInvoice && $coupleInvoice->lunas != 'Y') {
                if ($coupleInvoice->inv_no == null) {
                    if ($coupleInvoice->inv_type == 'OSK' ) {
                        $coupleInvoiceNo = $this->getNextInvoiceDSK();
                    }else {
                        $coupleInvoiceNo = $this->getNextInvoiceDS();
                    }
               }else {
                 $coupleInvoiceNo = $coupleInvoice->inv_no;
               }
                $containerInvoice = Container::where('form_id', $coupleInvoice->form_id)->get();
                $bigOS = OS::where('id', $coupleInvoice->os_id)->first();
                foreach ($containerInvoice as $cont) {
                    $lastJobNo = JobExport::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
                    $job = JobExport::create([
                        'inv_id'=>$coupleInvoice->id,
                        'job_no'=>$jobNo,
                        'os_id'=>$coupleInvoice->os_id,
                        'os_name'=>$coupleInvoice->os_name,
                        'cust_id'=>$coupleInvoice->cust_id,
                        'active_to'=>$coupleInvoice->expired_date,
                        'container_key'=>$cont->container_key,
                        'container_no'=>$cont->container_no,
                        'ves_id'=>$cont->ves_id,
                    ]);
                    $item = Item::where('container_key', $cont->container_key)->first();
                    $item->update([
                        'invoice_no'=>$coupleInvoiceNo,
                        'job_no' => $job->job_no,
                        'order_service' => $bigOS->order,
                    ]);
                }
        
                $details = Detail::where('inv_id', $coupleInvoice->id)->get();
                foreach ($details as $detail) {
                    $detail->update([
                    'lunas'=>'P',
                    'inv_no'=>$coupleInvoice,
                    ]);
                }
        
                $invoice->update([
                    'lunas' => 'P',
                    'inv_no'=>$coupleInvoice,
                    'piutang_at'=> Carbon::now(),
                    'invoice_date'=> Carbon::now(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
            'data'    => $item,
        ]);
    }



    public function InvoiceExportOSK($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $data['singleCont'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->first();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');
        $data['admin'] = 0;
        $adminDSK = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDSK) {
            $data['admin'] = $adminDSK->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.export.invoice.dsk', $data);
    }
    public function InvoiceExportOS($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $data['singleCont'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->first();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDS = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDS) {
            $data['admin'] = $adminDS->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.export.invoice.ds', $data);
    }


    // Job
    public function JobInvoice($id)
    {
        $data['title'] = 'Job Number';

        // Ambil Invoice dan Form terkait
        $data['inv'] = InvoiceExport::with('form')->where('id', $id)->first();
        $data['form'] = $data['inv']->form;

        // Ambil Job terkait dengan inv_id, hanya ambil 1 untuk pagination
        $data['job'] = JobExport::where('inv_id', $id)->paginate(10);

        // Set timezone dan ambil waktu sekarang
        date_default_timezone_set('Asia/Jakarta');
        $data['now'] = Carbon::now();
        $data['formattedDate'] = $data['now']->format('l, d-m-Y');

        // Ambil single job untuk mendapatkan informasi kapal
        $singleJob = JobExport::where('inv_id', $id)->first();
        $kapal = VVoyage::where('ves_id', $singleJob->ves_id)->first();

        // Cek kondisi khusus untuk ves_id "PELINDO"
        if ($singleJob->ves_id == "PELINDO") {
            $data['kapal'] = (object)[
                'ves_name' => 'PELINDO',
                'voy_out' => 'PELINDO',
                'clossing_date' => 'PELINDO',
                'etd_date' => 'PELINDO',
            ];
        } else {
            $data['kapal'] = $kapal;
        }

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
        return view('billingSystem.export.job.main', compact('qrcodes'), $data);
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

    public function ReportExcel(Request $request)
    {
      $os = $request->os_id;
      $startDate = $request->start;
      $endDate = $request->end;
      $invoiceQuery = Detail::whereHas('service', function ($query) {
        $query->where('ie', '=', 'E');
    })->where('os_id', $os)
      ->whereDate('order_date', '>=', $startDate)
      ->whereDate('order_date', '<=', $endDate);

        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->orderBy('order_date', 'asc')->get();        $fileName = 'ReportInvoiceExport-'.$os.'-'. $startDate . $endDate .'.xlsx';
      return Excel::download(new ReportExport($invoice), $fileName);
    }
    public function unpaidReport(Request $request)
    {
      $startDate = $request->start;
      $endDate = $request->end;
      $invoiceQuery = InvoiceExport::whereHas('service', function ($query) {
        $query->where('ie', '=', 'E');
    })
      ->where('lunas', '=', 'N')
      ->whereDate('order_at', '>=', $startDate)
      ->whereDate('order_at', '<=', $endDate);

        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->orderBy('order_at', 'asc')->get();        $fileName = 'ReportInvoiceExportUnpaid'.'-'. $startDate . $endDate .'.xlsx';
      return Excel::download(new ReportInvoiceAllExport($invoice), $fileName);
    }
    public function piutangReport(Request $request)
    {
        $startDate = $request->start;
        $endDate = $request->end;
        $invoiceQuery = InvoiceExport::whereHas('service', function ($query) {
          $query->where('ie', '=', 'E');
      })
        ->where('lunas', '=', 'P')
        ->whereDate('order_at', '>=', $startDate)
        ->whereDate('order_at', '<=', $endDate);
  
          // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
          if ($request->has('inv_type') && !empty($request->inv_type)) {
              // Tambahkan filter berdasarkan 'inv_type'
              $invoiceQuery->whereIn('inv_type', $request->inv_type);
          }
      
          $invoice = $invoiceQuery->orderBy('order_at', 'asc')->get();        $fileName = 'ReportInvoiceExportPiutang'.'-'. $startDate . $endDate .'.xlsx';
        return Excel::download(new ReportInvoiceAllExport($invoice), $fileName);
    }

    public function ReportExcelAll(Request $request)
{
    $startDate = $request->start;
    $endDate = $request->end;

    // Initialize the query for InvoiceExport
    $invoiceQuery = InvoiceExport::whereDate('invoice_date', '>=', $startDate)
        ->whereDate('invoice_date', '<=', $endDate);

    // Check if the 'inv_type' checkbox is present and not empty in the request
    if ($request->has('inv_type') && !empty($request->inv_type)) {
        // Add filter based on 'inv_type'
        $invoiceQuery->whereIn('inv_type', $request->inv_type);
    }

    // Apply additional filters to the InvoiceExport query
    $invoiceQuery->whereHas('service', function ($query) {
        $query->where('ie', '=', 'E');
    })->whereNot('lunas', '=', 'N')
        ->orderBy('inv_no', 'asc');

    // Retrieve the invoices
    $invoices = $invoiceQuery->get();

    // Initialize the query for InvoiceHeaderStevadooring
    $stevadooringQuery = InvoiceHeaderStevadooring::whereDate('invoice_date', '>=', $startDate)
        ->whereDate('invoice_date', '<=', $endDate)
        ->whereNot('lunas', '=', 'N')
        ->orderBy('invoice_no', 'asc');

    // Retrieve the stevadooring invoices
    $stevadooringInvoices = $stevadooringQuery->get();

    // Merge both invoice collections
    $mergedInvoices = $invoices->map(function ($invoice) {
        return [
            'inv_no' => $invoice->inv_no,
            'invoice_date' => $invoice->invoice_date,
            'proforma_no' => $invoice->proforma_no,
            'inv_type' => $invoice->inv_type,
            'cust_name' => $invoice->customer->name,
            'os_name' => $invoice->os_name,
            'total' => $invoice->total,
            'admin' => $invoice->admin,
            'discount' => $invoice->discount,
            'pajak' => $invoice->pajak,
            'grand_total' => $invoice->grand_total,
            'lunas' => $invoice->lunas,
            'Form' => $invoice->Form
        ];
    })->merge($stevadooringInvoices->map(function ($invoice) {
        return [
            'inv_no' => $invoice->invoice_no,
            'invoice_date' => $invoice->invoice_date,
            'proforma_no' => $invoice->proforma_no ?? 'N/A',
            'inv_type' => 'N/A',
            'cust_name' => $invoice->customer->name,
            'os_name' => $invoice->os_name,
            'total' => $invoice->total,
            'admin' => $invoice->admin,
            'discount' => $invoice->discount,
            'pajak' => $invoice->pajak,
            'grand_total' => $invoice->grand_total,
            'lunas' => $invoice->lunas,
            'Form' => $invoice->Form
        ];
    }));

    // Sort the merged collection by 'inv_no'
    $invoice = $mergedInvoices->sortBy('inv_no')->values();

    // Generate the file name for the Excel file
    $fileName = 'ReportInvoiceExport-' . $startDate . '-' . $endDate . '.xlsx';

    // Download the Excel file with the merged and sorted invoices
    return Excel::download(new ReportInvoiceAllExport($invoice), $fileName);
}


    public function recivingInvoiceCancel(Request $request)
    {
        $id = $request->inv_id;
        $invoice = InvoiceExport::where('id', $id)->first();
        // var_dump($invoice);
        // die;
        $invoice->update([
            'lunas' => 'C',
            'total'=> 0,
            'discount'=> 0,
            'pajak'=> 0,
            'grand_total'=> 0,
            
        ]);

        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'C',
            'jumlah'=>0,
            'jumlah_hari'=> 0,
            'tarif'=>0,
            'total'=>0,
            ]);
        }

        $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        foreach ($containerInvoice as $cont) {
            $item = Item::where('container_key', $cont->container_key)->first();
            $item->update([
                'selected_do'=>'N',
                'os_id'=>null,
                'job_no'=>null,
                'invoice_no'=>null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invoice Berhasil di Cancel!',
        ]);
    }

    
}
