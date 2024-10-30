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
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use App\Models\OSDetail;
use App\Models\MTDetail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport; // Assuming you create an export class
use App\Exports\ReportInvoice; // Assuming you create an export class
use App\Models\ImportDetail as Detail; // Assuming you create an export class

use DataTables;


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
      $this->middleware('role:invoice|admin|user')->only(['billingMain', 'detilUnpaid', 'detilPiutang', 'deliveryMenu',
    'deliveryEdit', 'deliveryForm']); 
    }

    public function billingMain()
    {
        $data['title'] = "Delivery Billing System";
        $data['invoice'] = InvoiceImport::whereNot('form_id', '=', '')->orderBy('order_at', 'asc')->orderBy('lunas', 'asc')->get();
        $data['service'] = OS::where('ie', '=' , 'I')->orderBy('id', 'asc')->get();
        $data['unPaids'] = InvoiceImport::whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'asc')->get();
        $data['piutangs'] = InvoiceImport::whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'asc')->get();

        $data['countUnpaids'] = $data['unPaids']->count();
        $data['totalUnpaids'] = $data['unPaids']->sum('total');
        $data['grandTotalUnpaids'] = $data['unPaids']->sum('grand_total');

        $data['countPiutangs'] = $data['piutangs']->count();
        $data['totalPiutangs'] = $data['piutangs']->sum('total');
        $data['grandTotalPiutangs'] = $data['piutangs']->sum('grand_total');
        return view('billingSystem.import.billing.main', $data);
    }
    public function detilUnpaid()
    {
        $data['title'] = "Delivery Billing System (Unpaid Invoice)";
        return view('billingSystem.import.billing.detil.unPaid', $data);
    }

    public function dataUnpaid(Request $request)
    {
        $unpaids = InvoiceImport::whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'asc'); // Removed `query()`
        return DataTables::of($unpaids)->make(true);
    }
   
    public function detilPiutang()
    {
        $data['title'] = "Delivery Billing System (Piutang Invoice)";
        return view('billingSystem.import.billing.detil.piutang', $data);
    }

    public function dataPiutang(Request $request)
    {
        $unpaids = InvoiceImport::whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'asc'); // Removed `query()`
        return DataTables::of($unpaids)->make(true);
    }
    public function detilInvoice(Request $request)
    {
        $data['os'] = OS::find($request->id);
        $data['title'] = "Delivery Billing System " . $data['os']->name;
        $data['osId'] = $request->id;

        // Retrieve the 'OS' model

        // Retrieve 'InvoiceImport' records with eager loading (replace 'relatedModel' with actual relationships)

        // Return the view with the retrieved data
        return view('billingSystem.import.billing.detil.detil', $data);
    }

    public function dataService(Request $request)
    {
        $unpaids = InvoiceImport::whereNotNull('form_id')
        ->where('os_id', $request->osId)
        ->orderBy('order_at', 'asc')
        ->orderBy('lunas', 'asc');
        return DataTables::of($unpaids)->make(true);
    }


    public function deliveryMenu()
    {
        $data['title'] = "Delivery Menu";
        $data['formInvoiceImport'] = Form::where('i_e', 'I')->where('done', '=', 'N')->get();

        return view('billingSystem.import.form.main', $data);
    }

    public function deliveryEdit($id)
    {
        $user = Auth::user();
        $data['title'] = "Delivery Form";
        $data["user"] = $user->id;

        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'I')->get();
        $data['do_online'] = DOonline::where('active', 'Y')->get();
        $data['ves'] = VVoyage::where('arrival_date', '<=', Carbon::now())->get();
        $data['form'] = Form::where('id', $id)->first();
        $data['containerInvoice'] = Container::where('form_id', $id)->get();
        // dd($data['form']);

        return view('billingSystem.import.form.edit', $data);
    }

    public function updateFormImport(Request $request)
    {
        $form = Form::where('id', $request->form_id)->first();

        $newContainer = $request->container;
        $disc = Item::whereIn('container_key', $newContainer)->orderBy('disc_date', 'asc')->get();
        $singleCont = $disc->first();
        $discDate = Carbon::parse($singleCont->disc_date);
        // dd($disc, $discDate);
        $expDate = Carbon::parse($request->exp_date);
        $expDate->addDay(2);
        $interval = $discDate->diff($expDate);
        $jumlahHari = $interval->days;
        // dd($jumlahHari);
        if($jumlahHari >= 6 ){
            return redirect()->back()->with('error', 'Melebihi Kuota Massa 1 !!');
        }

        $oldCont = Container::where('form_id', $form->id)->get();
        foreach ($oldCont as $cont) {
            $cont->delete();
        }

        $form->update([
            'expired_date'=>$request->exp_date,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'do_id'=>$request->do_number_auto,
            'ves_id'=> $request->ves_id,
            'i_e'=>'I',
            'disc_date'=>$singleCont->disc_date,
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
                'ves_id'=>$item->ves_id,
                'ves_name'=>$item->ves_name,
                'ctr_type'=>$item->ctr_type,
                'ctr_intern_status'=>$item->ctr_intern_status,
                'gross'=>$item->gross,
            ]);
        }

        return redirect()->route('formInvoice', ['id' => $form->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
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

        if (!$do) {
            return response()->json([
                'success' => false,
                'message' => 'DO not found!',
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

        $doCont = json_decode($do->container_no, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($doCont) || empty($doCont)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid container data!',
            ]);
        }

        if ($os != 4 && $os != 5) {
            $cont = Item::whereIn('container_no', $doCont)
                ->whereIn('ctr_intern_status', ['03', '04', '10', '09', '14'])
                ->where('selected_do', '=', 'N')
                ->where('ves_id', $ves)
                ->get();
        } else {
            $cont = Item::whereIn('container_no', $doCont)
                ->where('ctr_intern_status', '=', '15')
                ->where('selected_do', '=', 'N')
                ->where('ves_id', 'PELINDO')
                ->get();
        }

        if ($cont->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Container Belum Dapat Di Pilih !!',
            ]);
        }

        $singleCont = $cont->first();
        $discDate = Carbon::parse($singleCont->disc_date);
        $expiryDate = $discDate->addDays(4);
        $expiryDateFormatted = $expiryDate->format('Y-m-d');

        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
            'data'    => $do,
            'cont'    => $cont,
            'expired' => $expiryDateFormatted,
        ]);
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

   public function formInvoice($id)
    {
        $data['title'] = 'Invoice Import';
        $form = Form::where('id', $id)->first();
        $bigOS = OS::where('id', $form->os_id)->first();
        $data['customer'] = Customer::where('id', $form->cust_id)->first();
        $data['expired'] = $form->expired_date;
        $data['discDate'] = $form->disc_date;
        $data['doOnline'] = DOonline::where('id', $form->do_id)->first();
        $data['service'] = OS::where('id', $form->os_id)->first();
        $data['selectCont'] = Container::where('form_id', $id)->get();
        $containerInvoice = Container::where('form_id', $id)->get();
        $discDate = Carbon::parse($form->disc_date);
        $expDate = Carbon::parse($form->expired_date);
        $expDate->addDay(2);
        $interval = $discDate->diff($expDate);
        $jumlahHari = $interval->days;
        $osDSK = OSDetail::where('os_id', $form->os_id)->where('type', '=', 'DSK')->get();
        $data['dsk'] = $osDSK->isNotEmpty() ? 'Y' : 'N';
        $osDS = OSDetail::where('os_id', $form->os_id)->where('type', '=', 'DS')->get();
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
                                    'jumlahHari' => $jumlahHari,
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
                                    'jumlahHari' => $jumlahHari,
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
            $data['discountDS'] = $form->discount_ds ;
            $data['pajakDS'] = (($data['totalDS'] + $data['adminDS']) - $data['discountDS']) * 11 / 100;
            $data['grandTotalDS'] = (($data['totalDS'] + $data['adminDS']) - $data['discountDS']) + $data['pajakDS'];
        }

        // dd($osDSK, $service, $resultsDSK, $resultsDS);
        return view('billingSystem.import.form.pre-invoice', compact('form'), $data);
    }

    public function beforeCreate(Request $request)
    {
        $contSelect = $request->container;
        $disc = Item::whereIn('container_key', $contSelect)->orderBy('disc_date', 'asc')->get();
        $singleCont = $disc->first();
        $discDate = Carbon::parse($singleCont->disc_date);
        // dd($disc, $discDate);
        $expDate = Carbon::parse($request->exp_date);
        $expDate->addDay(2);
        $interval = $discDate->diff($expDate);
        $jumlahHari = $interval->days;
        // dd($jumlahHari);
        // if($jumlahHari >= 6 ){
        //     return redirect()->back()->with('error', 'Melebihi Kuota Massa 1 !!');
        // }
        $invoice = Form::create([
            'expired_date'=>$request->exp_date,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'do_id'=>$request->do_number_auto,
            'ves_id'=> $request->ves_id,
            'i_e'=>'I',
            'disc_date'=>$singleCont->disc_date,
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
                'ves_id'=>$item->ves_id,
                'ves_name'=>$item->ves_name,
                'ctr_type'=>$item->ctr_type,
                'ctr_intern_status'=>$item->ctr_intern_status,
                'gross'=>$item->gross,
            ]);
        }
        // dd($cont);

        return redirect()->route('formInvoice', ['id' => $invoice->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }

    public function deliveryDelete($id)
    {
        $form = Form::find($id);
        // var_dump($form, $id);
        // die();
        if ($form) {
            $container = Container::where('form_id', $id)->get();
            if ($container) {
                foreach ($container as $cont) {
                    $cont->delete();
                }
            }

        $form->delete();
        return response()->json(['message' => 'Data berhasil dihapus.']);
        }else {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }
       
    }

    public function InvoiceImport(Request $request)
    {
        $form = Form::where('id', $request->formId)->first();
        $bigOS = OS::where('id', $form->os_id)->first();
        $now = Carbon::now();
        $discDate = Carbon::parse($form->disc_date);
        $expDate = Carbon::parse($form->expired_date);
        $expDate->addDay(2);
        $interval = $discDate->diff($expDate);
        $sumInterval = $interval->days;
        $jumlahHari = $sumInterval - 1;
        $cont = Container::where('form_id', $form->id)->get();
        $groupedBySize = $cont->groupBy('ctr_size');
        $ctrGroup = $groupedBySize->map(function ($sizeGroup) {
            return $sizeGroup->groupBy('ctr_status');
        });
        $osDSK = OSDetail::where('os_id', $form->os_id)->where('type', 'DSK')->get();
        $dsk = $osDSK->isEmpty() ? 'N' : 'Y';
        
        $osDS = OSDetail::where('os_id', $form->os_id)->where('type', 'DS')->get();
        $ds = $osDS->isEmpty() ? 'N' : 'Y';

       if ($dsk == 'Y') {
        $nextProformaNumber = $this->getNextProformaNumber();
        $invoiceNo = $this->getNextInvoiceDSK();
        $invoiceDSK = InvoiceImport::create([
            'inv_type'=>'DSK',
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
            'last_expired_date'=>$form->expired_date,
            'disc_date' => $form->disc_date,
            'do_no'=>$form->doOnline->do_no,
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
                             'inv_no'=>$invoiceDSK->inv_no,
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
                             'inv_no'=>$invoiceDSK->inv_no,
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
        $invoiceDS = InvoiceImport::create([
            'inv_type'=>'DS',
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
            'do_no'=>$form->doOnline->do_no,
            'total'=>$request->totalDS,
            'admin'=>$request->adminDS,
            'discount'=>$request->discountDS,
            'pajak'=>$request->pajakDS,
            'grand_total'=>$request->grandTotalDS,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
           
            'last_expired_date'=>$form->expired_date,
            
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
                             'inv_no'=>$invoiceDS->inv_no,
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
                             'inv_no'=>$invoiceDS->inv_no,
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
                        'inv_no'=>$invoiceDS->inv_no,
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
       return redirect()->route('billinImportgMain')->with('success', 'Menunggu Pembayaran');

    }


    

    private function getNextProformaNumber()
{
    // Mendapatkan nomor proforma terakhir
    $latestProforma = InvoiceImport::orderBy('proforma_no', 'desc')->first();

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
    $latest = InvoiceImport::where('inv_type', 'DSK')->orderBy('inv_no', 'desc')->first();
    
    // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
    if (!$latest) {
        return 'DSK0000001';
    }

    // Mendapatkan nomor urut proforma terakhir
    $lastInvoice = $latest->inv_no;


    // Mengekstrak angka dari nomor proforma terakhir
    $lastNumber = (int)substr($lastInvoice, 3);

    // Menambahkan 1 ke nomor proforma terakhir
    $nextNumber = $lastNumber + 1;
    // Menghasilkan nomor proforma berikutnya dengan format yang benar
    return 'DSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
}

private function getNextInvoiceDS()
{
    // Mendapatkan nomor proforma terakhir
    $latest = InvoiceImport::where('inv_type', 'DS')->orderBy('inv_no', 'desc')->first();

    // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
    if (!$latest) {
        return 'DS0000001';
    }

    // Mendapatkan nomor urut proforma terakhir
    $lastInvoice = $latest->inv_no;

    // Mengekstrak angka dari nomor proforma terakhir
    $lastNumber = (int)substr($lastInvoice, 3);

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


        return view('billingSystem.import.pranota.dsk', $data);
    }
    public function PranotaImportDS($id)
    {

        $data['title'] = "Pranota";

        $data['invoice'] = InvoiceImport::where('id', $id)->first();
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

        return view('billingSystem.import.pranota.ds', $data);
    }
    
    public function deliveryInvoiceDelete($id)
{
    // Retrieve the invoice data for the specified form ID
    try {
        $invoice = InvoiceImport::where('form_id', $id)->get();
    
        // Check if any invoice is already paid (not 'N' in 'lunas')
        $paid = $invoice->first(function ($inv) {
            return $inv->lunas !== 'N';
        });

        // If there's a paid invoice, return an error message
        if ($paid) {
            return response()->json(['message' => 'Data tidak bisa dihapus, ada invoice yang sudah lunas.', 'status' => 'error']);
        }

        // Delete the invoices
        foreach ($invoice as $inv) {
            $inv->delete();
        }

        // Delete the related details
        $invoiceDetail = Detail::where('form_id', $id)->get();
        foreach ($invoiceDetail as $detail) {
            $detail->delete();
        }

        // Delete the container invoices and update the associated items
        $containerInvoice = Container::where('form_id', $id)->get();
        foreach ($containerInvoice as $cont) {
            $item = Item::where('container_key', $cont->container_key)->first();
            if ($item) {
                $item->update([
                    'selected_do' => 'N',
                    'os_id' => null,
                ]);
            }
            $cont->delete();
        }

        // Delete the form entry
        $form = Form::where('id', $id)->first();
        if ($form) {
            $form->delete();
        }

        // Return a success response
        return response()->json(['message' => 'Data berhasil dihapus.', 'status' => 'success']);
    } catch (\Throwable $th) {
        return response()->json(['message' => 'Oopss Something Wrong: ' . $th->getMessage(), 'status' => 'success']);
        //throw $th;
    }
}


    // invoice
    public function InvoiceImportDSK($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceImport::where('id', $id)->first();
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

        return view('billingSystem.import.invoice.dsk', $data);
    }
    public function InvoiceImportDS($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceImport::where('id', $id)->first();
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

        return view('billingSystem.import.invoice.ds', $data);
    }


    // Job
    public function JobInvoice($id)
    {
        $data['title'] = 'Job Number';
        $data['inv'] = InvoiceImport::with('form')->where('id', $id)->first();
        $data['form'] = Form::where('id', $data['inv']->form_id)->first();
        date_default_timezone_set('Asia/Jakarta');
        $data['now'] = Carbon::now();
        $data['formattedDate'] = $data['now']->format('l, d-m-Y');
        if ($data['inv']->extend == 'Y') {
            return back()->with('error', 'Job Telah Di Perbarui, Silahkan Cek Menu Extend');
        }
        $data['job'] = JobImport::where('inv_id', $id)->paginate(10);
        $data['cont'] = Item::whereIn('container_key', $data['job']->pluck('container_key'))->get()->keyBy('container_key');

        foreach ($data['job'] as $jb) {
            if ($data['cont']->has($jb->container_key)) {
                $ct = $data['cont']->get($jb->container_key);
                $qrcodes[$jb->id] = QrCode::size(100)->generate($ct->container_no);
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
        if ($invoice->lunas == 'N') {
            $invDate = Carbon::now();
        }else {
            $invDate = $invoice->invoice_date;
        }
       if ($invoice->inv_no == null) {
            if ($invoice->inv_type == 'DSK' ) {
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
            $lastJobNo = JobImport::orderBy('id', 'desc')->value('job_no');
            $jobNo = $this->getNextJob($lastJobNo);
            $job = JobImport::where('inv_id', $invoice->id)->where('container_key', $cont->container_key)->first();
            $item = Item::where('container_key', $cont->container_key)->first();
            if (!$job) {
                $discDate = Carbon::parse($item->disc_date);
                $expiryDate = $discDate->addDays(4);
                $expired = Carbon::now()->greaterThan($expiryDate);
                
                $expiryDateFormatted = $expiryDate->format('Y-m-d');
                $job = JobImport::create([
                    'inv_id'=>$invoice->id,
                    'job_no'=>$jobNo,
                    'os_id'=>$invoice->os_id,
                    'os_name'=>$invoice->os_name,
                    'cust_id'=>$invoice->cust_id,
                    'active_to'=>$expiryDateFormatted,
                    'container_key'=>$cont->container_key,
                    'container_no'=>$cont->container_no,
                    'ves_id'=>$cont->ves_id,
                ]);
            }
           
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
            $coupleInvoice = InvoiceImport::where('form_id', $form->id)->whereNot('id', $invoice->id)->first();
            // var_dump($coupleInvoice->form_id);
            // die();
            if ($coupleInvoice && $coupleInvoice->lunas != 'Y') {
                if ($coupleInvoice->lunas == 'N') {
                    $invDate = Carbon::now();
                }else {
                    $invDate = $coupleInvoice->invoice_date;
                }
                if ($coupleInvoice->inv_no == null) {
                    if ($coupleInvoice->inv_type == 'DSK' ) {
                        $coupleInvoiceNo = $this->getNextInvoiceDSK();
                    } else {
                        $coupleInvoiceNo = $this->getNextInvoiceDS();
                    }
                } else {
                    $coupleInvoiceNo = $coupleInvoice->inv_no;
                }
                
                $containerInvoice = Container::where('form_id', $form->form_id)->get();
                $bigOS = OS::where('id', $form->os_id)->first();
                foreach ($containerInvoice as $cont) {
                    $lastJobNo = JobImport::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
                    $job = JobImport::where('inv_id', $coupleInvoice->id)->where('container_key', $cont->container_key)->first();
                    $item = Item::where('container_key', $cont->container_key)->first();
                    if (!$job) {
                        $discDate = Carbon::parse($item->disc_date);
                        $expiryDate = $discDate->addDays(4);
                        $expired = Carbon::now()->greaterThan($expiryDate);
                        
                        $expiryDateFormatted = $expiryDate->format('Y-m-d');
                        $job = JobImport::create([
                            'inv_id'=>$coupleInvoice->id,
                            'job_no'=>$jobNo,
                            'os_id'=>$coupleInvoice->os_id,
                            'os_name'=>$coupleInvoice->os_name,
                            'cust_id'=>$coupleInvoice->cust_id,
                            'active_to'=>$expiryDateFormatted,
                            'container_key'=>$cont->container_key,
                            'container_no'=>$cont->container_no,
                            'ves_id'=>$cont->ves_id,
                        ]);
                    }
                   
                    $item->update([
                        'invoice_no'=>$coupleInvoice,
                        'job_no' => $job->job_no,
                        'order_service' => $bigOS->order,
                    ]);
                }
        
                $details = Detail::where('inv_id', $id)->get();
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
                    'invoice_date'=> $invDate,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
            'data'    => $item,
        ]);
    }

    public function piutangImport(Request $request)
    {
        $id = $request->inv_id;

        // var_dump($request->couple);
        // die;

        $invoice = InvoiceImport::where('id', $id)->first();
        if ($invoice->inv_no == null) {
            if ($invoice->inv_type == 'DSK' ) {
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
            $lastJobNo = JobImport::orderBy('id', 'desc')->value('job_no');
            $jobNo = $this->getNextJob($lastJobNo);
            $item = Item::where('container_key', $cont->container_key)->first();
            $discDate = Carbon::parse($item->disc_date);
            $expiryDate = $discDate->addDays(4);
            $expired = Carbon::now()->greaterThan($expiryDate);
            
            $expiryDateFormatted = $expiryDate->format('Y-m-d');
            $job = JobImport::create([
                'inv_id'=>$invoice->id,
                'job_no'=>$jobNo,
                'os_id'=>$invoice->os_id,
                'os_name'=>$invoice->os_name,
                'cust_id'=>$invoice->cust_id,
                'active_to'=>$expiryDateFormatted,
                'container_key'=>$cont->container_key,
                'container_no'=>$cont->container_no,
                'ves_id'=>$cont->ves_id,
            ]);
            
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
            $coupleInvoice = InvoiceImport::where('form_id', $form->id)->whereNot('id', $invoice->id)->first();
            if ($coupleInvoice && $coupleInvoice->lunas != 'Y') {
                if ($coupleInvoice->inv_no == null) {
                    if ($coupleInvoice->inv_type == 'DSK' ) {
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
                    $lastJobNo = JobImport::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
                    $item = Item::where('container_key', $cont->container_key)->first();
                    $discDate = Carbon::parse($item->disc_date);
                    $expiryDate = $discDate->addDays(4);
                    $expired = Carbon::now()->greaterThan($expiryDate);
                    
                    $expiryDateFormatted = $expiryDate->format('Y-m-d');
                    $job = JobImport::create([
                        'inv_id'=>$coupleInvoice->id,
                        'job_no'=>$jobNo,
                        'os_id'=>$coupleInvoice->os_id,
                        'os_name'=>$coupleInvoice->os_name,
                        'cust_id'=>$coupleInvoice->cust_id,
                        'active_to'=>$expiryDateFormatted,
                        'container_key'=>$cont->container_key,
                        'container_no'=>$cont->container_no,
                        'ves_id'=>$cont->ves_id,
                    ]);
                    
                    $item->update([
                        'invoice_no'=>$coupleInvoiceNo,
                        'job_no' => $job->job_no,
                        'order_service' => $bigOS->order,
                    ]);
                }
        
                $details = Detail::where('inv_id', $id)->get();
                foreach ($details as $detail) {
                    $detail->update([
                    'lunas'=>'P',
                    'inv_no'=>$coupleInvoiceNo,
                    ]);
                }
        
                $coupleInvoice->update([
                    'lunas' => 'P',
                    'inv_no'=>$coupleInvoiceNo,
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
    

    // Report
    public function ReportExcel(Request $request)
    {
        $os = $request->os_id;
        $startDate = $request->start;
        $endDate = $request->end;
        $invoiceQuery = Detail::where('os_id', $os)
            ->whereDate('order_date', '>=', $startDate)
            ->whereDate('order_date', '<=', $endDate);
    
        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->orderBy('order_date', 'asc')->get();
    
        $fileName = 'ReportInvoiceImport-' . $os . '-' . $startDate . '-' . $endDate . '.xlsx';

      return Excel::download(new InvoicesExport($invoice), $fileName);
    }

    public function unpaidReport(Request $request)
    {
        $startDate = $request->start;
        $endDate = $request->end;
        $invoiceQuery = InvoiceImport::whereBetween('order_at', [$startDate, $endDate])->where('lunas', '=', 'N');
    
        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->orderBy('order_at', 'asc')->get();
    
        $fileName = 'ReportInvoiceImportUnoaid-'. '-' . $startDate . '-' . $endDate . '.xlsx';

      return Excel::download(new ReportInvoice($invoice), $fileName);
    }
    public function piutangReport(Request $request)
    {
        $startDate = $request->start;
        $endDate = $request->end;
        $invoiceQuery = InvoiceImport::whereBetween('order_at', [$startDate, $endDate])->where('lunas', '=', 'P');
    
        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->orderBy('order_at', 'asc')->get();
    
        $fileName = 'ReportInvoiceImportPiutang-'. '-' . $startDate . '-' . $endDate . '.xlsx';

      return Excel::download(new ReportInvoice($invoice), $fileName);
    }

    public function ReportImpiortAll(Request $request)
    {
        $startDate = $request->start;
        $endDate = $request->end;
        $invoiceQuery = InvoiceImport::whereDate('invoice_date', '>=', $startDate)
            ->whereDate('invoice_date', '<=', $endDate);
    
        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->whereNot('lunas', '=', 'N')->orderBy('inv_no', 'asc')->get();
    
        $fileName = 'ReportInvoiceImport-' . $startDate . '-' . $endDate . '.xlsx';

      return Excel::download(new ReportInvoice($invoice), $fileName);
    }


    public function deliveryInvoiceCancel(Request $request)
    {
        $id = $request->inv_id;
        $invoice = InvoiceImport::where('id', $id)->first();
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
