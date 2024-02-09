<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\Customer;
use App\Models\DOonline;
use App\Models\Item;
use App\Models\KodeDok;
use App\Models\InvoiceImport;
use App\Models\JobImport;
use App\Models\VVoyage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


// BC 20
use App\Models\TpsSppbPib as PIB;
use App\Models\TpsSppbPibCont as PIBCont;
use App\Models\TpsSppbPibKms as PIBKms;
// BC23
use App\Models\TpsSppbBc as BC;
use App\Models\TpsSppbBcCont as BCcont;
use App\Models\TpsSppbBcKms as BCkms;
// NPE
use App\Models\TpsDokNPE as NPE;

// Pabean
use App\Models\TpsDokPabean as Pabean;
use App\Models\TpsDokPabeanCont as PabeanCont;
use App\Models\TpsDokPabeanKms as PabeanKms;

//PKBE
use App\Models\TpsDokManual as Manual;
use App\Models\TpsDokManualCont as ManualCont;
use App\Models\TpsDokManualKms as ManualKms;



class ImportController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function billingMain()
    {
        $data['title'] = "Delivery Billing System";
        $data['invoice'] = InvoiceImport::orderBy('order_at', 'asc')->orderBy('lunas', 'asc')->get();
        $data['service'] = OS::where('ie', '=' , 'I')->orderBy('id', 'asc')->get();

        return view('billingSystem.import.billing.main', $data);
    }

    public function deliveryMenu()
    {
        $data['title'] = "Delivery Menu";

        return view('billingSystem.import.form.main', $data);
    }
    public function deliveryForm()
    {
        $user = Auth::user();
        $data['title'] = "Delivery Form";
        $data["user"] = $user->id;

        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'I')->get();
        $data['do_online'] = DOonline::where('active', 'Y')->get();
        $data['ves'] = VVoyage::where('arrival_date', '<=', Carbon::now())->get();

        return view('billingSystem.import.form.create', $data);
    }

    public function getCust(Request $request)
    {
        $id = $request->id;

        $cust = Customer::where('id', $id)->first();
        if ($cust) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $cust,
            ]);

        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
    }

    public function getDOdata(Request $request)
    {
        $id = $request->id;
        $do = DOonline::where('id', $id)->first();
        $now = Carbon::now();
        if ($now > $do->expired) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor Do Expired !!',
            ]);
        }

        $ves = $request->ves;
        if (empty($ves)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih Vessel Service Dahulu !!',
            ]);
        }
        $os = $request->os;
        if (empty($os)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih Order Service Dahulu !!',
            ]);
        }
        if ($do) {
            $doCont = json_decode($do->container_no);
            // var_dump($doCont);
            // die;
    
            if ($os != 4 && $os != 5) {
                $cont = Item::whereIn('container_no', $doCont)->where('ctr_intern_status', '=',  '03')->where('selected_do','=', 'N')->where('ves_id', $ves)->get();            
            }else {
                $cont = Item::whereIn('container_no', $doCont)->where('ctr_intern_status', '=',  '15')->where('selected_do','=', 'N')->where('ves_id', 'PELINDO')->get();            
            }
           
            if (!$cont->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'updated successfully!',
                    'data'    => $do,
                    'cont' => $cont,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Container Belum Dapat Di Pilih !!',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
    }

    public function getDokImport(Request $request)
    {
        $dok = $request->no_dok;
        $cont = $request->container_key;
        if (empty($cont)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih Do Terlebih Dahulu !!',
            ]);
        }
      
        
    
        $pib = PIB::where('NO_SPPB', $dok)->first();
        if ($pib) {
            $kdDok = KodeDok::where('id', '1')->first();
            $dateString = $pib->TGL_SPPB;
        
            $pibCont = PIBCont::where('TPS_SPPBXML_FK', $pib->TPS_SPPBXML_PK)->get();
            $item = Item::whereIn('container_key', $cont)->get();
        
            $matchingContainers = $pibCont->whereIn('NO_CONT', $item->pluck('container_no'));
        
            if ($matchingContainers->count() === $item->count()) {
                $formattedDate = Carbon::createFromFormat('m/d/Y', $dateString)->toDateString();
                return response()->json([
                    'success' => true,
                    'data' => $pib,
                    'dok' => $kdDok,
                    'tgl' => $formattedDate,
                ]);
            } else {
                // Jika ada kontainer yang tidak cocok
                return response()->json([
                    'success' => false,
                    'message' => 'Container Salah!',
                ]);
            }
        }
    
        $bc = BC::where('NO_SPPB', $dok)->first();
        if ($bc) {
            // Lakukan sesuatu jika BC ditemukan
            $kdDok = KodeDok::where('id', '2')->first();

            $dateString = $bc->TGL_SPPB;

            $bcCont = BCcont::whereIn('TPS_SPPBXML_FK', $bc->TPS_SPPBXML_PK)->get();
            $item = Item::whereIn('container_key', $cont)->get();
        
            $matchingContainers = $bcCont->whereIn('NO_CONT', $item->pluck('container_no'));

            if ($matchingContainers->count() === $item->count()) {
            $formattedDate = Carbon::createFromFormat('m/d/Y', $dateString)->toDateString();

            return response()->json([
                'success' => true,
                'data' => $bc,
                'dok' =>$kdDok,
                'tgl' =>$formattedDate,

            ]);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Container Salah!',
                ]);
            }
        }
    
        $manual = Manual::where('NO_DOK_INOUT', $dok)->first();
        if ($manual) {
            // Lakukan sesuatu jika manual ditemukan
            $kdDok = KodeDok::where('id', $manual->KD_DOK_INOUT)->first();

            $dateString = $manual->TGL_DOK_INOUT;

            $manualCont = ManualCont::whereIn('TPS_DOKMANUALXML_FK', $manual->TPS_DOKMANUALXML_PK)->get();
            $item = Item::whereIn('container_key', $cont)->get();
        
            $matchingContainers = $manualCont->whereIn('NO_CONT', $item->pluck('container_no'));
            if ($matchingContainers->count() === $item->count()) {
            $formattedDate = date('Y-m-d', strtotime(str_replace('/', '-', $dateString)));

            return response()->json([
                'success' => true,
                'data' => $manual,
                'dok' =>$kdDok,
                'tgl' =>$formattedDate,


            ]);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Container Salah!',
                ]);
            }
        }

        $pabean = Pabean::where('NO_DOK_INOUT', $dok)->first();
        if ($pabean) {
            // Lakukan sesuatu jika PKBE ditemukan
            $kdDok = KodeDok::where('id', '15')->first();
            $dateString = $pabean->TGL_DOK_INOUT;

            $pabeanCont = ManualCont::whereIn('TPS_DOKPABEANXML_FK', $pabean->TPS_DOKPABEANXML_PK)->get();
            $item = Item::whereIn('container_key', $cont)->get();
        
            $matchingContainers = $pabeanCont->whereIn('NO_CONT', $item->pluck('container_no'));
            if ($matchingContainers->count() === $item->count()) {
            $formattedDate = Carbon::createFromFormat('Ymd', $dateString)->toDateString();

            return response()->json([
                'success' => true,
                'data' => $pabean,
                'dok' =>$kdDok,
                'tgl' =>$formattedDate,

            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Container Salah!',
            ]);
        }
        }
    
        // Jika tidak ada yang ditemukan, kembalikan respons dokumen tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Dokumen Tidak Ditemukan!',
        ]);
    }

    public function beforeCreate(Request $request)
    {
        $data['title'] = "Preview Invoice";
        $cust = $request->customer;
        $data['customer'] = Customer::where('id', $cust)->first();
        $data['expired'] = $request->exp_date;
        $os = $request->order_service;
      
        $data['service'] = OS::where('id', $os)->first();
        $do = $request->do_number_auto;
        $data['doOnline'] = DOonline::where('id', $do)->first();
        $cont = $request->container;
        $data['contInvoice'] = implode(',', $cont);
        $items = Item::whereIn('container_key', $cont)->get();
        $data['selectCont'] =  Item::whereIn('container_key', $cont)->orderBy('ctr_size', 'asc')->get();

        $singleCont = Item::where('container_key', $cont)->first();
        $discDate = Carbon::parse($singleCont->disc_date);
        $expDate = Carbon::parse($request->exp_date);
        // dd($discDate);
        
        // Tambahkan 1 hari ke tanggal kedaluwarsa
        $expDate->addDay(2);
        
        $interval = $discDate->diff($expDate);
        $jumlahHari = $interval->days;

        if ($jumlahHari >=5) {
            $data['massa1seharusnya'] = 5;
        }else {
            $data['massa1seharusnya'] = $jumlahHari;
        }
        $data['massa1'] = 1;
        if ($jumlahHari - 5 >= 1) {
            if ($jumlahHari - 5 >= 5) {
                $data['massa2'] = 5;
            }else {
                $data['massa2'] = $jumlahHari - 5;
            }
        }else {
            $data['massa2'] = null;
        }

        if ($jumlahHari - 10 >= 1) {
            $data['massa3'] = $jumlahHari-10;
        }else {
            $data['massa3'] = null;
        }
        
        // dd($discDate, $expDate, $massa1seharusnya, $data['massa2'], $data['massa3']);

        // dd($jumlahHari, $discDate, $expDate, $massa2, $massa1, $massa3);
       

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
            $tarif[$ukuran] = MT::where('os_id', $os)->where('ctr_size', $ukuran)->first();
            if (empty($tarif[$ukuran])) {
                return back()->with('error', 'Silahkan Membuat Master Tarif Terlebih Dahulu');
            }
            // DSK
            $loloFull[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_full;
            $ptKeluar[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_keluar;
            $pmassa1[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m1 * $data['massa1'];
            $pmassa2[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m2 * $data['massa2'];
            $pmassa3[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->m3 * $data['massa3'];

            // DS  
            $loloEmpty[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->lolo_empty;
            $ptMasuk[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pass_truck_masuk;
            $stripping[$ukuran]= $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->paket_stripping;
            $movePetikemas[$ukuran] = $jumlahContainerPerUkuran[$ukuran] * $tarif[$ukuran]->pemindahan_petikemas;
   
            
        }

        // DSK
        if ($os != 3) {
            $DSK = array_merge($loloFull, $ptKeluar, $pmassa1, $pmassa2, $pmassa3);
        }else {
            $DSK = array_merge($ptKeluar);

        };
        $DSKtot = array_sum($DSK);
        // dd($DSKtot);
       

        $adminMT = MT::where('os_id', $os)->first();
        if ($os == 1 || $os == 3) {
            $adminDSK = 0;
            $data['adminDSK'] = $adminDSK;
        }else {
            $adminDSK = $adminMT->admin;
            $data['adminDSK'] = $adminDSK;
        }
        $ppnDSK = (($DSKtot + $adminDSK) * 11) / 100;
       

        $data['grandDSK'] = $DSKtot + $ppnDSK + $adminDSK;
        $data['ppnDSK'] = $ppnDSK;
        $data['AmountDSK'] = $DSKtot;

        // DS
        // DSK
        if ($os == 3 || $os == 4) {
            if ($os == 3) {
                $DS = array_merge($stripping, $movePetikemas, $pmassa1, $pmassa2, $pmassa3);
            }else {
                $DS = array_merge($stripping, $pmassa1, $pmassa2, $pmassa3);
            }

        }else {
            $DS = array_merge($loloEmpty, $ptMasuk);

        };

        $DStot = array_sum($DS);
        // dd($DStot);
        $adminDS = $adminMT->admin;
        $ppnDS = (($DStot + $adminDS) * 11) / 100;
        $data['adminDS'] = $adminDS;
        $data['grandDS'] = $DStot + $ppnDS + $adminDS;
        $data['ppnDS'] = $ppnDS;
        $data['AmountDS'] = $DStot;
        

        return view('billingSystem.import.form.pre-invoice', compact('groupedContainers', 'discDate', 'jumlahContainerPerUkuran', 'tarif'), $data)->with('success', 'Silahkan Melanjutkan Proses');
        
    }

    public function InvoiceImport(Request $request)
    {
        $os = $request->os_id;
        $cont = $request->container_key;
        $item = Item::whereIn('container_key', $cont)->get();
        $do = DOonline::where('id', $request->do_id)->first();
       

        if (!empty($item)) {
            if ($os == 1 || $os == 2 || $os == 3) {
                $nextProformaNumber = $this->getNextProformaNumber();
                $invoiceNo = $this->getNextInvoiceDSK();
              

                $dsk = InvoiceImport::create([
                    'inv_type'=>'DSK',
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
                    'massa1'=>$request->massa1,
                    'massa2'=>$request->massa2,
                    'massa3'=>$request->massa3,
                    'extend'=>$request->extend,
                    'total'=>$request->totalDSK,
                    'pajak'=>$request->pajakDSK,
                    'grand_total'=>$request->grand_totalDSK,
                    'order_by'=> Auth::user()->name,
                    'order_at'=> Carbon::now(),
                    'lunas'=>'N',
                    'expired_date'=>$request->expired_date,
                    'disc_date' => $request->discDate,
                    'do_no'=>$do->do_no,
                ]);
            }

            if ($os == 1 || $os == 3) {
                $proformaDS = $dsk->proforma_no;
            }else {
                $nextProformaNumberDS = $this->getNextProformaNumber();
                $proformaDS = $nextProformaNumberDS;
            }

            if ($os != 2) {
                if ($os != 1 || $os != 5) {
                    $massa1inv = $request->massa1;
                    $massa2inv = $request->massa2;
                    $massa3inv = $request->massa3;
                }else {
                    $massa1inv = null;
                    $massa2inv = null;
                    $massa3inv = null;
                }     
                $invoiceNo = $this->getNextInvoiceDS();
         
                $ds = InvoiceImport::create([
                    'inv_type'=>'DS',
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
                    'massa1'=>$request->massa1,
                    'massa2'=>$request->massa2,
                    'massa3'=>$request->massa3,
                    'extend'=>$request->extend,
                    'total'=>$request->total,
                    'pajak'=>$request->pajak,
                    'grand_total'=>$request->grand_total,
                    'order_by'=> Auth::user()->name,
                    'order_at'=> Carbon::now(),
                    'lunas'=>'N',
                    'expired_date'=>$request->expired_date,
                    'disc_date' => $request->discDate,
                    'do_no'=>$do->do_no,


                ]);
            }

            $contArray = explode(',', $cont[0]);

            foreach ($contArray as $idCont) {
                $selectCont = Item::where('container_key', $idCont)->get();

                foreach ($selectCont as $item) {
                    $item->update([
                        'selected_do' => 'Y'
                    ]);

                    $lastJobNo = JobImport::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
            
                    if ($os == 1 || $os == 2 || $os == 3) {
                        $job = JobImport::create([
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
                    if ($os != 2) {
                        $job = JobImport::create([
                            'inv_id'=>$ds->id,
                            'job_no'=>$jobNo,
                            'os_id'=>$request->os_id,
                            'os_name'=>$request->os_name,
                            'cust_id'=>$request->cust_id,
                            'active_to'=>$ds->expired_date,
                            'container_key'=>$item->container_key,
                            'container_no'=>$item->container_no,
                            'ves_id'=>$item->ves_id,
                        ]);
                    }
                   
                }
            }
            return redirect()->route('billinImportgMain')->with('success', 'Menunggu Pembayaran');

        } 

       
    }

    private function getNextProformaNumber()
{
    // Mendapatkan nomor proforma terakhir
    $latestProforma = InvoiceImport::orderBy('order_at', 'desc')->first();

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
    $latest = InvoiceImport::where('inv_type', 'DSK')->orderBy('order_at', 'desc')->first();

    // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
    if (!$latest) {
        return 'DSK0000001';
    }

    // Mendapatkan nomor urut proforma terakhir
    $lastInvoice = $latest->inv_no;

    // Mengekstrak angka dari nomor proforma terakhir
    $lastNumber = (int)substr($lastInvoice, 1);

    // Menambahkan 1 ke nomor proforma terakhir
    $nextNumber = $lastNumber + 1;

    // Menghasilkan nomor proforma berikutnya dengan format yang benar
    return 'DSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
}

private function getNextInvoiceDS()
{
    // Mendapatkan nomor proforma terakhir
    $latest = InvoiceImport::where('inv_type', 'DS')->orderBy('order_at', 'desc')->first();

    // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
    if (!$latest) {
        return 'DS0000001';
    }

    // Mendapatkan nomor urut proforma terakhir
    $lastInvoice = $latest->inv_no;

    // Mengekstrak angka dari nomor proforma terakhir
    $lastNumber = (int)substr($lastInvoice, 1);

    // Menambahkan 1 ke nomor proforma terakhir
    $nextNumber = $lastNumber + 1;

    // Menghasilkan nomor proforma berikutnya dengan format yang benar
    return 'DS' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
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



    // pRANOTA
    public function PranotaImportDSK($id)
    {

        $data['title'] = "Pranota";

        $data['invoice'] = InvoiceImport::where('id', $id)->first();

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


        return view('billingSystem.import.pranota.dsk', compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data);
    }
    public function PranotaImportDS($id)
    {

        $data['title'] = "Pranota";

        $data['invoice'] = InvoiceImport::where('id', $id)->first();

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

        return view('billingSystem.import.pranota.ds',compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data);
    }


    // invoice
    public function InvoiceImportDSK($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceImport::where('id', $id)->first();

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


        return view('billingSystem.import.invoice.dsk', compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data);
    }
    public function InvoiceImportDS($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceImport::where('id', $id)->first();

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

        return view('billingSystem.import.invoice.ds',compact('groupedContainers', 'jumlahContainerPerUkuran', 'tarif'), $data);
    }


    // Job
    public function JobInvoice($id)
    {
        $data['title'] = 'Job Number';
        $data['inv'] = InvoiceImport::where('id', $id)->first();
        $data['job'] = JobImport::where('inv_id', $id)->get();
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


    public function payImport($id)
    {
        $invoice = InvoiceImport::where('id', $id)->first();
        if ($invoice) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $invoice,
            ]);
        }
    }

    public function payFullImport(Request $request)
    {
        $id = $request->inv_id;

        $invoice = InvoiceImport::where('id', $id)->first();
        $cont = $invoice->container_key;
        $contArray = json_decode($invoice->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
      
        $service = $invoice->os_id;
        if ($service == 1 || $service == 2 || $service == 5) {
            if ($service == 5) {
                $os ="SP2RELOKASI";
            }elseif ($service == 1) {
                $os = "SP2IKS";
            }else {
                $os = "SP2";
            }
        }else {
            if ($service == 3 ) {     
                $os="SPPS";
            }else {
                $os="SPPSRELOKASI";
            }
        }

        if ($invoice) {
            foreach ($items as $item) {
              
                $job = JobImport::where('container_key', $item->container_key)->first();
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

    public function piutangImport(Request $request)
    {
        $id = $request->inv_id;

        $invoice = InvoiceImport::where('id', $id)->first();
        $cont = $invoice->container_key;
        $contArray = json_decode($invoice->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
        $service = $invoice->os_id;
        if ($service == 1 || $service == 2 || $service == 5) {
            if ($service == 5) {
                $os ="SP2RELOKASI";
            }elseif ($service == 1) {
                $os = "SP2IKS";
            }else {
                $os = "SP2";
            }
        }else {
            if ($service == 3 ) {     
                $os="SPPS";
            }else {
                $os="SPPSRELOKASI";
            }
        }

        if ($invoice) {
            foreach ($items as $item) {
                $job = JobImport::where('container_key', $item->container_key)->first();
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
    
}
