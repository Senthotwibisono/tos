<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        ini_set('memory_limit', '2048M');
        $invoice = InvoiceImport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'I');
        })->whereNot('form_id', '=', '')->orderBy('order_at', 'desc');
        
        if ($request->has('type')) {
            if ($request->type == 'unpaid') {
                $invoice = InvoiceImport::whereHas('service', function ($query) {
                    $query->where('ie', '=', 'I');
                })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'desc');
            }

            if ($request->type == 'piutang') {
                $invoice = InvoiceImport::whereHas('service', function ($query) {
                    $query->where('ie', '=', 'I');
                })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'desc');
            }
        }

        if ($request->has('os_id')) {
            $invoice = InvoiceImport::whereNot('form_id', '=', '')->where('os_id', $request->os_id)->orderBy('order_at', 'desc')->orderBy('lunas', 'asc');
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
            if (in_array($inv->lunas, ['N', 'P'])) {
                return '<button type="button" id="pay" data-type="import" data-id="'.$inv->id.'" class="btn btn-sm btn-success" onClick="searchToPay(this)"><i class="fa fa-cogs"></i></button>';
            }elseif ($inv->lunas == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }else{
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
            if ($inv->lunas != 'C') {
                return '<button type="button" data-type="import" data-id="'.$inv->form_id.'" class="btn btn-sm btn-danger" onClick="cancelInvoice(this)"><i class="fa fa-trash"></i></button>';
            }else {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('viewPhoto', function($inv){
            $herf = '/bukti_bayar/import/'; 
            return '<a href="javascript:void(0)" onclick="openWindow(\''.$herf.$inv->id.'\')" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>';
        })
        ->addColumn('editInvoice', function($inv){
            return '<a href="/invoice/import/edit-'.$inv->form_id.'" target="_blank" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>';
        })
        ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'delete', 'payFlag', 'viewPhoto', 'editInvoice'])
        ->make(true);
    }


    public function deliveryMenu()
    {
        $data['title'] = "Delivery Menu";
        return view('billingSystem.import.form.main', $data);
    }

    public function formImportData(Request $request)
    {
        $form = Form::where('i_e', 'I')->where('done', '=', 'N')->get();

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
            return '<a href="/billing/import/delivery-editForm/'.$form->id.'" class="btn btn-outline-warning">Edit</a>';
        })
        ->rawColumns(['edit'])
        ->make(true);
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
        $expDate = Carbon::parse($request->exp_date);
        $expDate->addDay(2);
        $interval = $discDate->diff($expDate);
        $jumlahHari = $interval->days;
        // dd($disc, $discDate, $jumlahHari);
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


    public function doManual(Request $request)
    {   

        $do_no = $request->doNo;
        $do = DOonline::where('do_no', $do_no)->first();

        if (empty($request->customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih Customer Dahulu !!',
            ]);
        }
        
        $customer = Customer::find($request->customerId);
        if ($do->customer_code != $customer->code) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor DO tidak sesuai dengan Customer yang anda pilih !!',
            ]);
        }

        if ($do->expired <= Carbon::now()) {
            return response()->json([
                'success' => false,
                'message' => 'DO kadaluarsa, harap perbarui expired DO !!',
            ]);
        }

        // var_dump($do);

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

        $discDate = $cont->min('disc_date');
        $discDateFormat = Carbon::parse($discDate)->format('Y-m-d');

        if ($cont->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Container Belum Dapat Di Pilih !!',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
            'data'    => $do,
            'cont'    => $cont,
            'discDate' => $discDateFormat,
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
    return DB::transaction(function () {
        $latest = InvoiceImport::where('inv_type', 'DSK')->lockForUpdate()->orderBy('inv_no', 'desc')->first();

        if (!$latest) {
            return 'DSK0000001';
        }

        $lastNumber = (int)substr($latest->inv_no, 3);
        $nextNumber = $lastNumber + 1;

        return 'DSK' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    });
}

    private function getNextInvoiceDS()
    {
        return DB::transaction(function () {
            $latest = InvoiceImport::where('inv_type', 'DS')->lockForUpdate()->orderBy('inv_no', 'desc')->first();
        
            if (!$latest) {
                return 'DS0000001';
            }
        
            $lastNumber = (int)substr($latest->inv_no, 2);
            $nextNumber = $lastNumber + 1;
        
            return 'DS' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
        });
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
    try {
        // Retrieve all invoices related to the form ID
        $invoices = InvoiceImport::where('form_id', $id)->get();
    
        // Check if any invoice is already paid (not 'N' in 'lunas')
        $paidInvoice = $invoices->first(function ($invoice) {
            return $invoice->lunas !== 'N';
        });

        // If there's a paid invoice, return an error response
        if ($paidInvoice) {
            return response()->json(['message' => 'Data tidak bisa dihapus, ada invoice yang sudah lunas.', 'status' => 'error']);
        }

        // Delete the invoices
        foreach ($invoices as $invoice) {
            $invoice->delete();
        }

        // Delete related details
        $invoiceDetails = Detail::where('form_id', $id)->get();
        foreach ($invoiceDetails as $detail) {
            $detail->delete();
        }

        // Delete container invoices and reset related items
        $containerInvoices = Container::where('form_id', $id)->get();
        foreach ($containerInvoices as $container) {
            $item = Item::where('container_key', $container->container_key)->first();
            if ($item) {
                $item->update([
                    'selected_do' => 'N',
                    'os_id' => null,
                ]);
            }
            $container->delete();
        }

        // Delete the form entry if it exists
        $form = Form::find($id);
        if ($form) {
            $form->delete();
        }

        // Return a success response
        return response()->json(['message' => 'Data berhasil dihapus.', 'status' => 'success']);
    } catch (\Throwable $th) {
        // Return a more precise error response
        return response()->json(['message' => 'An error occurred: ' . $th->getMessage(), 'status' => 'error']);
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
        $data['formattedDate'] = Carbon::now()->format('l, d-m-Y');
        $data['job'] = JobImport::with(['Kapal', 'Service', 'Item', 'Invoice'])->where('inv_id', $id)->paginate(10);
        $data['cont'] = Item::whereIn('container_key', $data['job']->pluck('container_key'))->get()->keyBy('container_key');

        $qrcodes = [];
        foreach ($data['job'] as $jb) {
                $qrcodes[$jb->id] = QrCode::size(100)->generate($jb->container_no);
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
    } else {
        $invDate = $invoice->invoice_date;
    }

    $invoiceNo = $invoice->inv_no ?? ($invoice->inv_type == 'DSK' ? $this->getNextInvoiceDSK() : $this->getNextInvoiceDS());

    // Process containers for main invoice
    $this->processContainersForInvoice($invoice, $invoiceNo);

    if ($request->couple == 'on') {
        $form = Form::find($invoice->form_id);
        $coupleInvoice = InvoiceImport::where('form_id', $form->id)->where('id', '!=', $invoice->id)->first();

        if ($coupleInvoice && $coupleInvoice->lunas != 'Y') {
            $invDateCouple = $coupleInvoice->lunas == 'N' ? Carbon::now() : $coupleInvoice->invoice_date;
            $coupleInvoiceNo = $coupleInvoice->inv_no ?? ($coupleInvoice->inv_type == 'DSK' ? $this->getNextInvoiceDSK() : $this->getNextInvoiceDS());

            // Process containers for couple invoice
            $this->processContainersForInvoice($coupleInvoice, $coupleInvoiceNo, $invDateCouple);
        }
    }

    $invoice->update([
        'lunas' => 'Y',
        'inv_no' => $invoiceNo,
        'lunas_at' => Carbon::now(),
        'invoice_date' => $invDate,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'data'    => $invoice,
    ]);
}

/**
 * Process containers for the given invoice.
 *
 * @param InvoiceImport $invoice
 * @param string $invoiceNo
 * @param Carbon|null $invDate
 */
    private function processContainersForInvoice($invoice, $invoiceNo, $invDate = null)
    {
        $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        $bigOS = OS::where('id', $invoice->os_id)->first();
    
        foreach ($containerInvoice as $cont) {
            $lastJobNo = JobImport::orderBy('id', 'desc')->value('job_no');
            $jobNo = $this->getNextJob($lastJobNo);
        
            $job = JobImport::where('inv_id', $invoice->id)->where('container_key', $cont->container_key)->first();
            $item = Item::where('container_key', $cont->container_key)->first();
        
            if (!$job) {
                $discDate = Carbon::parse($item->disc_date);
                $expiryDateFormatted = $discDate->addDays(4)->format('Y-m-d');
            
                $job = JobImport::create([
                    'inv_id' => $invoice->id,
                    'job_no' => $jobNo,
                    'os_id' => $invoice->os_id,
                    'os_name' => $invoice->os_name,
                    'cust_id' => $invoice->cust_id,
                    'active_to' => $invoice->expired_date,
                    'container_key' => $cont->container_key,
                    'container_no' => $cont->container_no,
                    'ves_id' => $cont->ves_id,
                ]);
            }
        
            $item->update([
                'invoice_no' => $invoiceNo,
                'job_no' => $job->job_no,
                'order_service' => $bigOS->order,
            ]);
        }
    
        $details = Detail::where('inv_id', $invoice->id)->get();
        foreach ($details as $detail) {
            $detail->update([
                'lunas' => 'Y',
                'inv_no' => $invoiceNo,
            ]);
        }
    
        $invoice->update([
            'lunas' => 'Y',
            'inv_no' => $invoiceNo,
            'lunas_at' => Carbon::now(),
            'invoice_date' => $invDate ?? Carbon::now(),
        ]);
    }

    private function processContainersForInvoicePiutang($invoice, $invoiceNo, $invDate = null)
    {
        $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        $bigOS = OS::where('id', $invoice->os_id)->first();

        foreach ($containerInvoice as $cont) {
            $lastJobNo = JobImport::orderBy('id', 'desc')->value('job_no');
            $jobNo = $this->getNextJob($lastJobNo);

            $job = JobImport::where('inv_id', $invoice->id)->where('container_key', $cont->container_key)->first();
            $item = Item::where('container_key', $cont->container_key)->first();

            if (!$job) {
                $discDate = Carbon::parse($item->disc_date);
                $expiryDateFormatted = $discDate->addDays(4)->format('Y-m-d');

                $job = JobImport::create([
                    'inv_id' => $invoice->id,
                    'job_no' => $jobNo,
                    'os_id' => $invoice->os_id,
                    'os_name' => $invoice->os_name,
                    'cust_id' => $invoice->cust_id,
                    'active_to' => $invoice->expired_date,
                    'container_key' => $cont->container_key,
                    'container_no' => $cont->container_no,
                    'ves_id' => $cont->ves_id,
                ]);
            }

            $item->update([
                'invoice_no' => $invoiceNo,
                'job_no' => $job->job_no,
                'order_service' => $bigOS->order,
            ]);
        }

        $details = Detail::where('inv_id', $invoice->id)->get();
        foreach ($details as $detail) {
            $detail->update([
                'lunas' => 'P',
                'inv_no' => $invoiceNo,
            ]);
        }

        $invoice->update([
            'lunas' => 'P',
            'inv_no' => $invoiceNo,
            'lunas_at' => Carbon::now(),
            'invoice_date' => $invDate ?? Carbon::now(),
        ]);
    }


    public function piutangImport(Request $request)
    {
        $id = $request->inv_id;

        $invoice = InvoiceImport::where('id', $id)->first();
        if ($invoice->lunas == 'N') {
            $invDate = Carbon::now();
        } else {
            $invDate = $invoice->invoice_date;
        }

        $invoiceNo = $invoice->inv_no ?? ($invoice->inv_type == 'DSK' ? $this->getNextInvoiceDSK() : $this->getNextInvoiceDS());

        // Process containers for main invoice
        $this->processContainersForInvoicePiutang($invoice, $invoiceNo);

        if ($request->couple == 'on') {
            $form = Form::find($invoice->form_id);
            $coupleInvoice = InvoiceImport::where('form_id', $form->id)->where('id', '!=', $invoice->id)->first();

            if ($coupleInvoice && $coupleInvoice->lunas != 'Y') {
                $invDateCouple = $coupleInvoice->lunas == 'N' ? Carbon::now() : $coupleInvoice->invoice_date;
                $coupleInvoiceNo = $coupleInvoice->inv_no ?? ($coupleInvoice->inv_type == 'DSK' ? $this->getNextInvoiceDSK() : $this->getNextInvoiceDS());

                // Process containers for couple invoice
                $this->processContainersForInvoicePiutang($coupleInvoice, $coupleInvoiceNo, $invDateCouple);
            }
        }

        $invoice->update([
            'lunas' => 'P',
            'inv_no' => $invoiceNo,
            'lunas_at' => Carbon::now(),
            'invoice_date' => $invDate,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data'    => $invoice,
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
            
        ]);

        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'C',
            ]);
        }

        $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        foreach ($containerInvoice as $cont) {
            $item = Item::where('container_key', $cont->container_key)->first();
            if ($item->invoice_no == $invoice->inv_no) {
                # code...
                $item->update([
                    'selected_do'=>'N',
                    'os_id'=>null,
                    'job_no'=>null,
                    'invoice_no'=>null,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Invoice Berhasil di Cancel!',
        ]);
    }

    public function editInvoice($form_id)
    {
        $form = Form::find($form_id);

        $data['title'] = 'Edit Invoice Import';
        
        $invoice = InvoiceImport::where('form_id', $form->id)->get();

        $data['singleInvoice'] = $invoice->first();

        $data['customers'] = Customer::get();
        $data['vesels'] = VVoyage::get();
        $data['form'] = $form;

        $data['expired'] = Carbon::parse($data['singleInvoice']->disc_Date)->addDays(4);

        $data['services'] = OS::where('ie', 'I')->get();
        $data['flagDSK'] = 'N';
        $data['flagDS'] = 'N';

        $dsk = $invoice->where('inv_type', 'DSK')->first();
        if ($dsk) {
            $data['flagDSK'] = 'Y';
            $data['dsk'] = $dsk;
        }
        $ds = $invoice->where('inv_type', 'DS')->first();
        if ($ds) {
            $data['flagDS'] = 'Y';
            $data['ds'] = $ds;
        }
        // dd($invoice);

        return view('billingSystem.import.billing.edit.index', $data);
    }

    public function updateInvoice(Request $request)
    {
        // dd($request->all());

        try {
            //code...
            $form = Form::find($request->form_id);
            $form->update([
                'os_id' => $request->os_id,
                'ves_id' => $request->ves_id,
                'cust_id' => $request->cust_id,
            ]);
    
            $customer = Customer::find($request->cust_id);
            $headers = InvoiceImport::where('form_id', $form->id)->get();
            foreach ($headers as $header) {
                $header->update([
                    'os_id' => $form->os_id ?? null,
                    'cust_id' => $form->cust_id ?? null,
                    'cust_name' => $customer->name ?? null, 
                    'fax' => $request->fax ?? null, 
                    'npwp' => $request->npwp ?? null, 
                    'alamat' => $request->alamat ?? null, 
                ]);
            }
            // ([
            //     'os_id' => $form->os_id ?? null,
            //     'cust_id' => $form->cust_id ?? null,
            //     'cust_name' => $customer->name ?? null, 
            //     'fax' => $request->fax ?? null, 
            //     'npwp' => $request->npwp ?? null, 
            //     'alamat' => $request->alamat ?? null, 
            // ]);

            $details = Detail::where('form_id', $form->id)->get();
            foreach ($details as $detil) {
                $detil->update([
                    'cust_id' => $request->cust_id,
                    'cust_name' => $customer->name,
                ]);
            }
    
            // $detil = Detail::where('form_id', $form->id)->update([
            //     'cust_id' => $request->cust_id,
            //     'cust_name' => $customer->name,
            // ]);
    
            $dsk = InvoiceImport::where('form_id', $form->id)->where('inv_type', 'DSK')->first();
            if ($dsk) {
                $dsk->update([
                    'total' => $request->totalDSK, 
                    'admin' => $request->adminDSK, 
                    'discount' => $request->discountDSK, 
                    'pajak' => $request->pajakDSK, 
                    'grand_total' => $request->grand_totalDSK, 
                    'order_at' => $request->order_atDSK,
                    'piutang_at' => $request->piutang_atDSK, 
                    'lunas_at' => $request->lunas_atDSK, 
                    'invoice_date' => $request->invoice_dateDSK, 
                ]);
            }
    
            $ds = InvoiceImport::where('form_id', $form->id)->where('inv_type', 'DS')->first();
            if ($ds) {
                $ds->update([
                    'total' => $request->totalDS, 
                    'admin' => $request->adminDS, 
                    'discount' => $request->discountDS, 
                    'pajak' => $request->pajakDS, 
                    'grand_total' => $request->grand_totalDS, 
                    'order_at' => $request->order_atDS,
                    'piutang_at' => $request->piutang_atDS, 
                    'lunas_at' => $request->lunas_atDS, 
                    'invoice_date' => $request->invoice_dateDS, 
                ]);
            }
            return redirect()->back()->with('success', 'Data Berhasil di perbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something Wrong In : ' . $th->getMessage());
            //throw $th;
        }
    }

    public function Preinvoice($id)
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

        return view('billingSystem.import.form.new.preinvoice', $data)->with('success', 'Seilahkan lanjutkan ke tahap berikut nya');
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

            return redirect('/invoice/import/delivery-detail/unpaid')->with('success', 'Invoice berhasil di buat');
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
        return InvoiceImport::create([
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
            'discount' => $request->input("discount$type"),
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
        if ($detail->MItem->count_by == 'O') {
            $jumlahCont = 1;
        };
        
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
            'jumlah_hari' => ($detail->MItem->count_by == 'T') ? $jumlahHari : 0,
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
