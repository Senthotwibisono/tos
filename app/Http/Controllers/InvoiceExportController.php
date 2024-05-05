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
use App\Models\JobExport;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

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

        return view('billingSystem.export.billing.main', $data);
    }

    public function deliveryMenuExport()
    {
        $data['title'] = "Reciving Menu";

        return view('billingSystem.export.form.main', $data);
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
        $data['contBooking'] = $cont->unique('booking_no')->pluck('booking_no');
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
    
       $kapal = VVoyage::where('ves_id', $singleCont->ves_id)->first();
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
        ->whereIn('ctr_intern_status', ['04', '06', '53'])
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

    public function beforeCreate(Request $request)
    {
        $data['title'] = "Preview Invoice";
        $cust = $request->customer;
        $data['customer'] = Customer::where('id', $cust)->first();
        $data['expired'] = $request->exp_date;
        $os = $request->order_service;
        $data['booking'] = $request->booking_no;
      
        $data['service'] = OS::where('id', $os)->first();
        $booking = $request->booking_no;
        $cont = $request->container;
        $data['contInvoice'] = implode(',', $cont);
        $items = Item::whereIn('container_key', $cont)->get();
        $data['selectCont'] =  Item::whereIn('container_key', $cont)->orderBy('ctr_size', 'asc')->get();
        $data['ves'] = $request->ves_id;        
        // dd($ves);
        $groupedContainers = [];

        foreach ($items as $item) {
            $containerKey = $item->container_key;
            $containerSize = $item->ctr_size;
        
            
            if (isset($groupedContainers[$containerSize])) {
                $groupedContainers[$containerSize][] = $containerKey;
            } else { 
                $groupedContainers[$containerSize] = [$containerKey];
            }
        }

        $jumlahContainerPerUkuran = [];

        // Hitung jumlah kontainer per ukuran
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $jumlahContainerPerUkuran[$ukuran] = count($containers);
        }
        $tarif = [];
        $loloFull = [];
        $ptMasuk = [];
        $ptKeluar = [];
        $pmassa1 = [];
        $loloEmpty = [];
        $cargo_dooring = [];
        $sewa_crane = [];
        $jpbTruck = [];
        $stuffing = [];

        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $tarif[$ukuran] = MT::where('os_id', $os)->where('ctr_size', $ukuran)->first();
            if (empty($tarif[$ukuran])) {
                return back()->with('error', 'Silahkan Membuat Master Tarif Terlebih Dahulu');
            }
            // OSK
            $loloFull[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full;
            $ptKeluar[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar;
            $pmassa1[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1;
            $cargo_dooring[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->cargo_dooring;
            $sewa_crane[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->sewa_crane;

        

            // OS  
            $loloEmpty[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty;
            $ptMasuk[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk;
            $stuffing[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stuffing;
            $jpbTruck[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->jpb_extruck;
   
            
        }

        // DSK
        if ($os == 6 || $os == 7 || $os == 14) {
            if ($os == 14) {
                $DSK = array_merge($loloFull, $ptKeluar, $ptKeluar, $pmassa1);
            }else {
                $DSK = array_merge($loloFull, $ptKeluar, $pmassa1);
            }
            
        }else {
            if ($os == 11 || $os == 13 ) {
                $DSK = array_merge($cargo_dooring, $sewa_crane);
            }else {
                $DSK = array_merge($ptMasuk);
            }

        };
        $DSKtot = array_sum($DSK);
        // dd($DSKtot);
       

        $adminMT = MT::where('os_id', $request->order_service)->first();
     
            $adminDSK = 0;
            $data['adminDSK'] = $adminDSK;
        
        $ppnDSK = (($DSKtot + $adminDSK) * 11) / 100;
       

        $data['grandDSK'] = $DSKtot + $ppnDSK + $adminDSK;
        $data['ppnDSK'] = $ppnDSK;
        $data['AmountDSK'] = $DSKtot;

        // DS
       
        if ($os == 6 || $os == 8 || $os == 14) {
            if ($os == 6 || $os == 8) {
                $DS = array_merge($loloEmpty, $ptKeluar,);
            }else {
                $DS = array_merge($loloEmpty);
            }

        }elseif ($os == 9 || $os == 10 || $os == 11 || $os == 12 || $os == 13  || $os == 15) {
            if ($os == 9) {
                $DS = array_merge($jpbTruck, $pmassa1, $ptKeluar);
            }elseif ($os == 10) {
                $DS = array_merge($jpbTruck, $pmassa1, $ptKeluar, $loloFull);
            }elseif ($os == 11 || $os == 12 || $os == 15) {
                $DS = array_merge($stuffing);
            }elseif ($os == 13) {
                $DS = array_merge($stuffing, $loloEmpty, $ptMasuk, $ptKeluar, $loloFull);
            }

        };


        if ($os != 7) {
            $DStot = array_sum($DS);
        // dd($adminMT);
        $adminDS = $adminMT->admin;
        $ppnDS = (($DStot + $adminDS) * 11) / 100;
        $data['adminDS'] = $adminDS;
        $data['grandDS'] = $DStot + $ppnDS + $adminDS;
        $data['ppnDS'] = $ppnDS;
        $data['AmountDS'] = $DStot;
        }
       

        // dd($data['booking']);
        

        return view('billingSystem.export.form.pre-invoice', compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data)->with('success', 'Silahkan Melanjutkan Proses');
        
    }


    public function invoiceExport(Request $request)
    {
        $os = $request->os_id;
        $cont = $request->container_key;
        $kapal = $request->ves_id;
        $item = Item::whereIn('container_key', $cont)->get();
        if ($kapal != null) {
            $ves = VVoyage::where('ves_id', $kapal)->first();
            $etd = $ves->etd_date;
        }else {
            $singleItem = Item::whereIn('container_key', $cont)->first();
            $ves = VVoyage::where('ves_id', $singleItem->ves_id)->first();
            $etd = $ves->etd_date;

        }
        
        
       

        if (!empty($item)) {
            if ($os == '6' || $os == '7' ||  $os == '9' ||  $os == '11' || $os == '13' || $os == '14' || $os == '15') {
                $nextProformaNumber = $this->getNextProformaNumber();
                $invoiceNo = $this->getNextInvoiceDSK();
              

                $dsk = InvoiceExport::create([
                    'inv_type'=>'OSK',
                    'inv_no' =>$invoiceNo,
                    'proforma_no'=>$nextProformaNumber,
                    'cust_id'=>$request->cust_id,
                    'cust_name'=>$request->cust_name,
                    'fax'=>$request->fax,
                    'npwp'=>$request->npwp,
                    'alamat'=>$request->alamat,
                    'os_id'=>$request->os_id,
                    'os_name'=>$request->os_name,
                    'container_key'=>json_encode($request->container_key),
                    'total'=>$request->totalDSK,
                    'pajak'=>$request->pajakDSK,
                    'grand_total'=>$request->grand_totalDSK,
                    'order_by'=> Auth::user()->name,
                    'order_at'=> Carbon::now(),
                    'lunas'=>'N',
                    'expired_date'=>$request->expired_date,                
                    'booking_no'=>$request->booking_no,
                    'etd'=> $etd,

                    'ctr_20'=>$request->ctr_20,
                    'ctr_40'=>$request->ctr_40,
                    'ctr_21'=>$request->ctr_21,
                    'ctr_42'=>$request->ctr_42,
                    'm1_20'=>$request->m1_20,
                    'm2_20'=>$request->m2_20,
                    'm3_20'=>$request->m3_20,
                    'lolo_full_20'=>$request->lolo_full_20,
                    'lolo_empty_20'=>$request->lolo_empty_20,
                    'pass_truck_masuk_20'=>$request->pass_truck_masuk_20,
                    'pass_truck_keluar_20'=>$request->pass_truck_keluar_20,
                    'jpb_extruck_20'=>$request->jpb_extruck_20,
                    'sewa_crane_20'=>$request->sewa_crane_20,
                    'cargo_dooring_20'=>$request->cargo_dooring_20,
                    'paket_stuffing_20'=>$request->paket_stuffing_20,
                    'm1_21'=>$request->m1_21,
                    'm2_21'=>$request->m2_21,
                    'm3_21'=>$request->m3_21,
                    'lolo_full_21'=>$request->lolo_full_21,
                    'lolo_empty_21'=>$request->lolo_empty_21,
                    'pass_truck_masuk_21'=>$request->pass_truck_masuk_21,
                    'pass_truck_keluar_21'=>$request->pass_truck_keluar_21,
                    'jpb_extruck_21'=>$request->jpb_extruck_21,
                    'sewa_crane_21'=>$request->sewa_crane_21,
                    'cargo_dooring_21'=>$request->cargo_dooring_21,
                    'paket_stuffing_21'=>$request->paket_stuffing_21,
                    'm1_40'=>$request->m1_40,
                    'm2_40'=>$request->m2_40,
                    'm3_40'=>$request->m3_40,
                    'lolo_full_40'=>$request->lolo_full_40,
                    'lolo_empty_40'=>$request->lolo_empty_40,
                    'pass_truck_masuk_40'=>$request->pass_truck_masuk_40,
                    'pass_truck_keluar_40'=>$request->pass_truck_keluar_40,
                    'jpb_extruck_40'=>$request->jpb_extruck_40,
                    'sewa_crane_40'=>$request->sewa_crane_40,
                    'cargo_dooring_40'=>$request->cargo_dooring_40,
                    'paket_stuffing_40'=>$request->paket_stuffing_40,
                    'm1_42'=>$request->m1_42,
                    'm2_42'=>$request->m2_42,
                    'm3_42'=>$request->m3_42,
                    'lolo_full_42'=>$request->lolo_full_42,
                    'lolo_empty_42'=>$request->lolo_empty_42,
                    'pass_truck_masuk_42'=>$request->pass_truck_masuk_42,
                    'pass_truck_keluar_42'=>$request->pass_truck_keluar_42,
                    'jpb_extruck_42'=>$request->jpb_extruck_42,
                    'sewa_crane_42'=>$request->sewa_crane_42,
                    'cargo_dooring_42'=>$request->cargo_dooring_42,
                    'paket_stuffing_42'=>$request->paket_stuffing_42,
                ]);
            }

            if ($os == '6' ||  $os == '9' ||  $os == '11' || $os == '13' || $os == '14' || $os == '15') {
                $proformaDS = $dsk->proforma_no;
            }else {
                $nextProformaNumberDS = $this->getNextProformaNumber();
                $proformaDS = $nextProformaNumberDS;
            }

            if ($os == '6' || $os == '8' ||  $os == '9' || $os == '10' ||  $os == '11' || $os == '12' || $os == '13' || $os == '14' || $os == '15') {
                     
                $invoiceNo = $this->getNextInvoiceDS();
         
                $ds = InvoiceExport::create([
                    'inv_type'=>'OS',
                    'inv_no'=>$invoiceNo,
                    'proforma_no'=>$proformaDS,
                    'cust_id'=>$request->cust_id,
                    'cust_name'=>$request->cust_name,
                    'fax'=>$request->fax,
                    'npwp'=>$request->npwp,
                    'alamat'=>$request->alamat,
                    'os_id'=>$request->os_id,
                    'os_name'=>$request->os_name,
                    'container_key'=>json_encode($request->container_key),
                    'total'=>$request->total,
                    'pajak'=>$request->pajak,
                    'grand_total'=>$request->grand_total,
                    'order_by'=> Auth::user()->name,
                    'order_at'=> Carbon::now(),
                    'lunas'=>'N',
                    'expired_date'=>$request->expired_date,
                    'booking_no'=>$request->booking_no,
                    'etd'=> $etd,
                    'ctr_20'=>$request->ctr_20,
                    'ctr_40'=>$request->ctr_40,
                    'ctr_21'=>$request->ctr_21,
                    'ctr_42'=>$request->ctr_42,
                    'm1_20'=>$request->m1_20,
                    'm2_20'=>$request->m2_20,
                    'm3_20'=>$request->m3_20,
                    'lolo_full_20'=>$request->lolo_full_20,
                    'lolo_empty_20'=>$request->lolo_empty_20,
                    'pass_truck_masuk_20'=>$request->pass_truck_masuk_20,
                    'pass_truck_keluar_20'=>$request->pass_truck_keluar_20,
                    'jpb_extruck_20'=>$request->jpb_extruck_20,
                    'sewa_crane_20'=>$request->sewa_crane_20,
                    'cargo_dooring_20'=>$request->cargo_dooring_20,
                    'paket_stuffing_20'=>$request->paket_stuffing_20,
                    'm1_21'=>$request->m1_21,
                    'm2_21'=>$request->m2_21,
                    'm3_21'=>$request->m3_21,
                    'lolo_full_21'=>$request->lolo_full_21,
                    'lolo_empty_21'=>$request->lolo_empty_21,
                    'pass_truck_masuk_21'=>$request->pass_truck_masuk_21,
                    'pass_truck_keluar_21'=>$request->pass_truck_keluar_21,
                    'jpb_extruck_21'=>$request->jpb_extruck_21,
                    'sewa_crane_21'=>$request->sewa_crane_21,
                    'cargo_dooring_21'=>$request->cargo_dooring_21,
                    'paket_stuffing_21'=>$request->paket_stuffing_21,
                    'm1_40'=>$request->m1_40,
                    'm2_40'=>$request->m2_40,
                    'm3_40'=>$request->m3_40,
                    'lolo_full_40'=>$request->lolo_full_40,
                    'lolo_empty_40'=>$request->lolo_empty_40,
                    'pass_truck_masuk_40'=>$request->pass_truck_masuk_40,
                    'pass_truck_keluar_40'=>$request->pass_truck_keluar_40,
                    'jpb_extruck_40'=>$request->jpb_extruck_40,
                    'sewa_crane_40'=>$request->sewa_crane_40,
                    'cargo_dooring_40'=>$request->cargo_dooring_40,
                    'paket_stuffing_40'=>$request->paket_stuffing_40,
                    'm1_42'=>$request->m1_42,
                    'm2_42'=>$request->m2_42,
                    'm3_42'=>$request->m3_42,
                    'lolo_full_42'=>$request->lolo_full_42,
                    'lolo_empty_42'=>$request->lolo_empty_42,
                    'pass_truck_masuk_42'=>$request->pass_truck_masuk_42,
                    'pass_truck_keluar_42'=>$request->pass_truck_keluar_42,
                    'jpb_extruck_42'=>$request->jpb_extruck_42,
                    'sewa_crane_42'=>$request->sewa_crane_42,
                    'cargo_dooring_42'=>$request->cargo_dooring_42,
                    'paket_stuffing_42'=>$request->paket_stuffing_42,
                ]);
            }

            $contArray = explode(',', $cont[0]);

            foreach ($contArray as $idCont) {
                $selectCont = Item::where('container_key', $idCont)->get();

                foreach ($selectCont as $item) {
                    if ($kapal != null) {
                        $ves = VVoyage::where('ves_id', $kapal)->first();
                        $item->update([
                            'selected_do' => 'Y',
                            'ves_id' => $ves->ves_id,
                            'ves_name' => $ves->ves_name,
                            'ves_code' => $ves->ves_code,
                            'voy_no' => $ves->voy_no,
                        ]);
                    }else {
                        $item->update([
                            'selected_do' => 'Y'
                        ]);
                    }
                   
                   

                    $lastJobNo = JobExport::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
            
                    if ($os == '6' || $os == '7' ||  $os == '9' ||  $os == '11' || $os == '13' || $os == '14' || $os == '15') {
                        $job = JobExport::create([
                            'inv_id'=>$dsk->id,
                            'job_no'=>$jobNo,
                            'os_id'=>$request->os_id,
                            'os_name'=>$request->os_name,
                            'cust_id'=>$request->cust_id,
                            'active_to'=>$dsk->expired_date,
                            'container_key'=>$item->container_key,
                            'container_no'=>$item->container_no,
                            'ves_id'=>$item->ves_id,
                        ]);
                    }
                    if ($os == '6' || $os == '8' ||  $os == '9' || $os == '10' ||  $os == '11'|| $os == '12' || $os == '13' || $os == '14' || $os == '15') {
                        $job = JobExport::create([
                            'inv_id'=>$ds->id,
                            'job_no'=>$jobNo,
                            'os_id'=>$request->os_id,
                            'os_name'=>$request->os_name,
                            'cust_id'=>$request->cust_id,
                            'active_to'=>$request->expired_date,
                            'container_key'=>$item->container_key,
                            'container_no'=>$item->container_no,
                            'ves_id'=>$item->ves_id,
                        ]);
                    }
                   
                }
            }
            return redirect()->route('billingExportMain')->with('success', 'Menunggu Pembayaran');

        } 

       
    }

    private function getNextProformaNumber()
    {
        // Mendapatkan nomor proforma terakhir
        $latestProforma = InvoiceExport::orderBy('order_at', 'desc')->first();
    
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
        // Mendapatkan nomor proforma terakhir
        $latest = InvoiceExport::where('inv_type', 'OSK')->orderBy('order_at', 'desc')->first();
    
        // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
        if (!$latest) {
            return 'OSK0000001';
        }
    
        // Mendapatkan nomor urut proforma terakhir
        $lastInvoice = $latest->inv_no;
    
        // Mengekstrak angka dari nomor proforma terakhir
        $lastNumber = (int)substr($lastInvoice, 3);
    
        // Menambahkan 1 ke nomor proforma terakhir
        $nextNumber = $lastNumber + 1;
    
        // Menghasilkan nomor proforma berikutnya dengan format yang benar
        return 'OSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }
    
    private function getNextInvoiceDS()
    {
        // Mendapatkan nomor proforma terakhir
        $latest = InvoiceExport::where('inv_type', 'OS')->orderBy('order_at', 'desc')->first();
    
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

    public function PranotaExportOSK($id)
    {

        $data['title'] = "Pranota";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();

        $cont = $data['invoice']->container_key;
        $contArray = json_decode($data['invoice']->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
        $data['contInvoce'] = Item::whereIn('container_key', $container_keys)->orderBy('ctr_size', 'asc')->get();
      
        $groupedContainers = [];

        foreach ($items as $item) {
            $containerKey = $item->container_key;
            $containerSize = $item->ctr_size;
        
            
            if (isset($groupedContainers[$containerSize])) {
                $groupedContainers[$containerSize][] = $containerKey;
            } else { 
                $groupedContainers[$containerSize] = [$containerKey];
            }
        }

        $jumlahContainerPerUkuran = [];

        // Hitung jumlah kontainer per ukuran
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $jumlahContainerPerUkuran[$ukuran] = count($containers);
        }

        $tarif = [];
        $loloFull = [];
        $ptMasuk = [];
        $ptKeluar = [];
        $pmassa1 = [];
        $pmassa2 = [];
        $pmassa3 = [];
        $loloEmpty = [];
        $stripping = [];
        $movePetikemas = [];
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $tarif[$ukuran] = MT::where('os_id', $data['invoice']->os_id)->where('ctr_size', $ukuran)->first();
            if (empty($tarif[$ukuran])) {
                return back()->with('error', 'Silahkan Membuat Master Tarif Terlebih Dahulu');
            }
            // DSK
            $loloFull[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full;
            $ptKeluar[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar;
            $pmassa1[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1;


            // DS  
            $loloEmpty[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty;
            $ptMasuk[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk;
            $stripping[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stripping;
            $movePetikemas[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pemindahan_petikemas;
   
            
        }
        
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);


        return view('billingSystem.export.pranota.dsk', compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data);
    }
    public function PranotaExportOS($id)
    {

        $data['title'] = "Pranota";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();

        $cont = $data['invoice']->container_key;
        $contArray = json_decode($data['invoice']->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
        $data['contInvoce'] = Item::whereIn('container_key', $container_keys)->orderBy('ctr_size', 'asc')->get();
      
        $groupedContainers = [];

        foreach ($items as $item) {
            $containerKey = $item->container_key;
            $containerSize = $item->ctr_size;
        
            
            if (isset($groupedContainers[$containerSize])) {
                $groupedContainers[$containerSize][] = $containerKey;
            } else { 
                $groupedContainers[$containerSize] = [$containerKey];
            }
        }

        $jumlahContainerPerUkuran = [];

        // Hitung jumlah kontainer per ukuran
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $jumlahContainerPerUkuran[$ukuran] = count($containers);
        }

        $tarif = [];
        $loloFull = [];
        $ptMasuk = [];
        $ptKeluar = [];
        $pmassa1 = [];
        $pmassa2 = [];
        $pmassa3 = [];
        $loloEmpty = [];
        $stripping = [];
        $movePetikemas = [];
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $tarif[$ukuran] = MT::where('os_id', $data['invoice']->os_id)->where('ctr_size', $ukuran)->first();
            if (empty($tarif[$ukuran])) {
                return back()->with('error', 'Silahkan Membuat Master Tarif Terlebih Dahulu');
            }
            // DSK
            $loloFull[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full;
            $ptKeluar[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar;
            $pmassa1[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1 * $data['invoice']->massa1;
            $pmassa2[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m2 * $data['invoice']->massa2;
            $pmassa3[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m3 * $data['invoice']->massa3;

            // DS  
            $loloEmpty[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty;
            $ptMasuk[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk;
            $stripping[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stripping;
            $movePetikemas[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pemindahan_petikemas;
   
            
        }

        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.export.pranota.ds',compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data);
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
        $cont = $invoice->container_key;
        $contArray = json_decode($invoice->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
        $service = $invoice->os_id;
        if ($service == 6 || $service == 7 || $service == 8 || $service == 8) {
            $os = "EXPORT";
        }else {
            $os = "STUFFING";
        }

        if ($invoice) {
            foreach ($items as $item) {
                $job = JobExport::where('container_key', $item->container_key)->first();
                $item->update([
                    'invoice_no'=>$invoice->inv_no,
                    'job_no' => $job->job_no,
                    'order_service' => $os,
                ]);
            }

            $invoice->update([
                'lunas' => 'Y',
                'lunas_at'=> Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $items,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
       

        
    }

    public function piutangExport(Request $request)
    {
        $id = $request->inv_id;

        $invoice = InvoiceExport::where('id', $id)->first();
        $cont = $invoice->container_key;
        $contArray = json_decode($invoice->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
        $service = $invoice->os_id;
        if ($service == 6 || $service == 7 || $service == 8 || $service == 8) {
            $os = "EXPORT";
        }else {
            $os = "STUFFING";
        }


        if ($invoice) {
            foreach ($items as $item) {
                $job = JobExport::where('container_key', $item->container_key)->first();
                $item->update([
                    'invoice_no'=>$invoice->inv_no,
                    'job_no' => $job->job_no,
                    'order_service' => $os,
                ]);
            }

            $invoice->update([
                'lunas' => 'P',
                'lunas_at'=> Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $items,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
       

        
    }



    public function InvoiceExportOSK($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();

        $cont = $data['invoice']->container_key;
        $contArray = json_decode($data['invoice']->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
        $data['contInvoce'] = Item::whereIn('container_key', $container_keys)->orderBy('ctr_size', 'asc')->get();
      
        $groupedContainers = [];

        foreach ($items as $item) {
            $containerKey = $item->container_key;
            $containerSize = $item->ctr_size;
        
            
            if (isset($groupedContainers[$containerSize])) {
                $groupedContainers[$containerSize][] = $containerKey;
            } else { 
                $groupedContainers[$containerSize] = [$containerKey];
            }
        }

        $jumlahContainerPerUkuran = [];

        // Hitung jumlah kontainer per ukuran
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $jumlahContainerPerUkuran[$ukuran] = count($containers);
        }

        $tarif = [];
        $loloFull = [];
        $ptMasuk = [];
        $ptKeluar = [];
        $pmassa1 = [];
        $loloEmpty = [];
        $cargo_dooring = [];
        $sewa_crane = [];
        $jpbTruck = [];
        $stuffing = [];
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $tarif[$ukuran] = MT::where('os_id', $data['invoice']->os_id)->where('ctr_size', $ukuran)->first();
            if (empty($tarif[$ukuran])) {
                return back()->with('error', 'Silahkan Membuat Master Tarif Terlebih Dahulu');
            }
            // DSK
            $loloFull[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full;
            $ptKeluar[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar;
            $pmassa1[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1;
            $cargo_dooring[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->cargo_dooring;
            $sewa_crane[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->sewa_crane;


            // DS  
            $loloEmpty[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty;
            $ptMasuk[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk;
            $stuffing[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stuffing;
            $jpbTruck[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->jpb_extruck;
   
            
        }
        
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);


        return view('billingSystem.export.invoice.dsk', compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data);
    }
    public function InvoiceExportOS($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();

        $cont = $data['invoice']->container_key;
        $contArray = json_decode($data['invoice']->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
        $data['contInvoce'] = Item::whereIn('container_key', $container_keys)->orderBy('ctr_size', 'asc')->get();
      
        $groupedContainers = [];

        foreach ($items as $item) {
            $containerKey = $item->container_key;
            $containerSize = $item->ctr_size;
        
            
            if (isset($groupedContainers[$containerSize])) {
                $groupedContainers[$containerSize][] = $containerKey;
            } else { 
                $groupedContainers[$containerSize] = [$containerKey];
            }
        }

        $jumlahContainerPerUkuran = [];

        // Hitung jumlah kontainer per ukuran
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $jumlahContainerPerUkuran[$ukuran] = count($containers);
        }

        $tarif = [];
        $loloFull = [];
        $ptMasuk = [];
        $ptKeluar = [];
        $pmassa1 = [];
        $loloEmpty = [];
        $cargo_dooring = [];
        $sewa_crane = [];
        $jpbTruck = [];
        $stuffing = [];
        foreach ($groupedContainers as $ukuran => $containers) {
            // Jumlah kontainer untuk ukuran saat ini adalah panjang array kontainer
            $tarif[$ukuran] = MT::where('os_id', $data['invoice']->os_id)->where('ctr_size', $ukuran)->first();
            if (empty($tarif[$ukuran])) {
                return back()->with('error', 'Silahkan Membuat Master Tarif Terlebih Dahulu');
            }
            // DSK
            $loloFull[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full;
            $ptKeluar[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar;
            $pmassa1[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1;
            $stuffing[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stuffing;
            $jpbTruck[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->jpb_extruck;

            // DS  
            $loloEmpty[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty;
            $ptMasuk[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk;
            $stuffing[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stuffing;
            $jpbTruck[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->jpb_extruck;
   
            
        }

        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.export.invoice.ds',compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data);
    }


    // Job
    public function JobInvoice($id)
    {
        $data['title'] = 'Job Number';
        $data['inv'] = InvoiceExport::where('id', $id)->first();
        $data['job'] = JobExport::where('inv_id', $id)->get();
        $singleJob =  JobExport::where('inv_id', $id)->first();
        $data['kapal'] = VVoyage::where('ves_id', $singleJob->ves_id)->first();
        $data['cont'] = Item::get();
        foreach ($data['job'] as $jb) {
            foreach ($data['cont'] as $ct) {
                if ($ct->container_key == $jb->container_key) {
                    $qrcodes[$jb->id] = QrCode::size(100)->generate($ct->container_no);
                    break;
                }
            }
        }
        return view('billingSystem.export.job.main',compact('qrcodes'), $data);
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
      $invoice = InvoiceExport::where('os_id', $os)->whereDate('order_at', '>=', $startDate)->whereDate('order_at', '<=', $endDate)->orderBy('order_at', 'asc')->get();
        $fileName = 'ReportInvoiceExport-'.$os.'-'. $startDate . $endDate .'.xlsx';
      return Excel::download(new ReportExport($invoice), $fileName);
    }
}
