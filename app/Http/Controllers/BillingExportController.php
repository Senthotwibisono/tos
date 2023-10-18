<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\DataExport;
use App\Models\VVoyage;


class BillingExportController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function billingIndex()
  {
    $client = new Client();
    $data = [];

    // GET ALL INVOICE
    $url_invoice = getenv('API_URL') . '/delivery-service/invoice/v2/all';
    $req_invoice = $client->get($url_invoice);
    $response_invoice = $req_invoice->getBody()->getContents();
    $result_invoice = json_decode($response_invoice);
    // dd($result_invoice);

    $data["title"] = "Receiving Billing System";
    $data["invoices"] = $result_invoice->data;
    return view('billing.export.billingIndex', $data);
  }

  public function formIndex()
  {
    $client = new Client();
    $data = [];

    // GET ALL FORM
    $url_receiving = getenv('API_URL') . '/delivery-service/form/all';
    $req_receiving = $client->get($url_receiving);
    $response_receiving = $req_receiving->getBody()->getContents();
    $result_receiving = json_decode($response_receiving);
    // dd($result_receiving);

    $data["receivings"] = $result_receiving->data;
    $data["title"] = "Receiving Form";
    return view('billing.export.form.formIndex', $data);
  }
  public function  createIndex()
  {
    $client = new Client();
    $data = [];

    // GET USER DATA
    $user = Auth::user();

    // GET ALL CUSTOMER
    $url_customer = getenv('API_URL') . '/delivery-service/customer/all';
    $req_customer = $client->get($url_customer);
    $response_customer = $req_customer->getBody()->getContents();
    $result_customer = json_decode($response_customer);
    // dd($result_customer);

    // GET ALL CONTAINER
    $url_container = getenv('API_URL') . '/delivery-service/container/all';
    $req_container = $client->get($url_container);
    $response_container = $req_container->getBody()->getContents();
    $result_container = json_decode($response_container);
    // dd($result_container);

    // GET ALL VOYAGE BY DEPARATURE DATE
    $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();
    // dd($result_vessel);

    // GET ALL BOOKING
    $url_booking = getenv('API_URL') . '/delivery-service/container/booking/all';
    $req_booking = $client->get($url_booking);
    $response_booking = $req_booking->getBody()->getContents();
    $result_booking = json_decode($response_booking);
    // dd($result_booking);

    // GET ALL RO
    $url_ro = getenv('API_URL') . '/delivery-service/container/ro/all';
    $req_ro = $client->get($url_ro);
    $response_ro = $req_ro->getBody()->getContents();
    $result_ro = json_decode($response_ro);
    // dd($result_ro);
    $data["ro"] = $result_ro->data;

    $data["booking"] = $result_booking->data;
    $data["vessel"] = $vessel_voyage;
    $data["customer"] = $result_customer->data;
    $data["container"] = $result_container->data;
    $data["user"] = $user->id;
    $data["title"] = "Receiving Create Form | Receiving Billing System";
    return view('billing.export.form.create', $data);
  }
  public function storeForm(Request $request)
  {
    $data = [];
    $client = new Client();

    // $exp_date = $request->exp_date;
    $departure = $request->departure;
    // $exp_time = $request->exp_time;
    $customer = $request->customer;
    // $do_number = $request->do_number ?? $request->do_number_auto;
    // $do_exp_date = $request->do_exp_date;
    // $boln = $request->boln;
    $container = $request->container;
    $order_service = $request->order_service;
    $documentNumber = $request->documentNumber;
    $documentType = $request->documentType;
    $documentDate = $request->documentDate;
    $bookingno = $request->booking;
    $rono = $request->roNumber;

    $fields = [
      "exp_date" => $departure,
      "customer_id" => $customer,
      "container" => $container,
      "orderService" => $order_service,
      "documentNumber" => $documentNumber,
      "documentType" => $documentType,
      "documentDate" => $documentDate,

    ];
    if ($bookingno != null) {
      $fields["booking_no"] = $bookingno;
    } else {
      $fields["roNumber"] = $rono;
    }

    // dd($fields);

    $url = getenv('API_URL') . '/delivery-service/form/create';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);
    // dd($result);
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/receiving/form/review?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
    } else {
      return redirect('/receiving')->with('success', 'Data gagal disimpan!');
    }
  }

  public function reviewIndex(Request $request)
  {
    $client = new Client();

    $id_form = $request->id;
    // dd($id_form);

    $fields = [
      "id" => $id_form,
    ];
    // dd($fields);
    $url = getenv('API_URL') . '/delivery-service/form/receiving/calculate';

    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);
    // dd($result);
    $data = [];

    $data["ccdelivery"] = $result->data;
    // $data["tableBilling"] = $result->data->tableBilling;
    $data["grandTotal"] = $result->data->grandTotal;
    $data["billingTotal"] = $result->data->billingTotal;
    // $data["differentDays"] = $result->data->differentDays;
    // $data["findContainer"] = $result->data->findContainer;
    $data["deliveryForm"] = $result->data->deliveryForm;
    $data["customer"] = $result->data->deliveryForm->customer;
    $data["containers"] = $result->data->deliveryForm->containers;
    // $data["tarifCheckResults"] = $result->data->tarifCheckResults;
    if ($result->data->deliveryForm->orderService == "lolofull") {
      $data["orderService"] = "LOLO FULL KAPAL SANDAR ICON (2 Invoice)";
    } else if ($result->data->deliveryForm->orderService == "lolofull1inv") {
      $data["orderService"] = "LOLO FULL KAPAL SANDAR ICON (1 Invoice)";
    } else if ($result->data->deliveryForm->orderService == "lolomt") {
      $data["orderService"] = "LOLO MT (1 Invoice)";
    } else if ($result->data->deliveryForm->orderService == "jpbicon") {
      $data["orderService"] = "JPB EX-TRUCK/ STUFFING MUATAN KAPAL ICON";
    } else if ($result->data->deliveryForm->orderService == "jpbluar") {
      $data["orderService"] = "JPB EX-TRUCK/ STUFFING MUATAN KAPAL LUAR";
    } else if ($result->data->deliveryForm->orderService == "ernahandling1inv") {
      $data["orderService"] = "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ICL) 1 INVOICE";
    } else if ($result->data->deliveryForm->orderService == "ernahandling2inv") {
      $data["orderService"] = "HANDLING CHARGE ERNA VIA KAPAL ICON (INVOICE ERNA) 2 INVOICE";
    } else if ($result->data->deliveryForm->orderService == "ernahandlingluar") {
      $data["orderService"] = "HANDLING CHARGE ERNA VIA KAPAL LUAR (INVOICE ERNA) 2 INVOICE";
    } else if ($result->data->deliveryForm->orderService == "sp2dry") {
      $data["orderService"] = "MUAT DRY SP2";
    } else if ($result->data->deliveryForm->orderService == "sppsdry") {
      $data["orderService"] = "MUAT DRY SPPS";
    }


    $data["title"] = "Review Form & Calculation Pranota | Receiving Billing System";
    return view('billing.export.form.review', $data);
  }

  public function storeBilling(Request $request)
  {

    $client = new Client();
    $id = $request->deliveryFormId;
    // dd($idDev);
    $fields = [
      "id" => $id,
    ];
    // dd($fields);
    $url = getenv('API_URL') . '/delivery-service/form/receiving/calculate';

    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);

    $orderService = $result->data->deliveryForm->orderService;
    // dd($orderService);

    $delivery = $result->data;
    $billingTotal = $result->data->billingTotal;
    $deliveryForm = $result->data->deliveryForm;
    $customer = $result->data->deliveryForm->customer;
    $containers = $result->data->deliveryForm->containers;
    $grandTotal = $result->data->grandTotal;
    // dd($billingTotal);
    // dd($billingTotal[0]->billingName->name[0]);
    $i = 0;
    foreach ($billingTotal as $value) {
      $container = $value->billingName->container;
      $ctrSize = $container->ctr_size;
      $ctrType = $container->ctr_type;
      $ctrStatus = $container->ctr_status;
      $contNo = $container->container_no;
      $contID = $container->id;
      $hari = $value->differentDays;
      $tarif = $value->tarif;
      // dd($tarif->lift_empty);
      // $test["Billing Name"] = $value->billingName->name;
      // dd($value->billingName);
      // foreach ($value->billingName->name as $bill) {
      //   foreach ($bill == "DSK" ? $value->table->titleTableDSK : $value->table->titleTableDS as $table) {
      //     foreach ($value->table->titleTableDSK as $table) {
      //       $obj[$contNo][$ctrSize][$table]["Tarif"] = $contNo;
      //     }
      //   }
      // }
      foreach ($value->billingName->name as $bill) {
        // dd($bill);
        foreach ($bill == "OSK" || $bill == "OSK(OSK246)" || $bill == "OSK(OSK48)" || $bill == "OSK(OSK212)" ? $value->table->titleTableOSK : $value->table->titleTableOS as $table) {
          // $obj[$contNo][$ctrSize][$table]["Nomor Container"] = $contNo;
          // $obj[$contNo][$ctrSize][$table]["Ukuran"] = $ctrSize;
          // $obj[$contNo][$ctrSize][$table]["Type"] = $ctrType;
          // $obj[$contNo][$ctrSize][$table]["Status"] = $ctrStatus;
          if ($table == "Lift On / Off Full") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->lift_full;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->liftFull;
          } else if ($table == "Lift On / Off Empty") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->lift_empty;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->liftEmpty;
          } else if ($table == "Lift Off MT") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->lift_off_mt;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->liftOffMT;
          } else if ($table == "Pass Truck Out") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->pass_truck;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->passTruckOut;
          } else if ($table == "Pass Truck In") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->pass_truck;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->passTruckIn;
          } else if ($table == "Pass Truck") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->pass_truck;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->passTruck ?? $tarif->passTruckIn;
          } else if ($table == "Administrasi") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->administrasi;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->administration;
          } else if ($table == "Pemindahan Petikemas Antar Blok") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->pemindahan;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->pemindahan;
          } else if ($table == "Paket Stripping") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->paket_stripping;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->paketStripping;
          } else if ($table == "Penumpukan Masa 1") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Hari"] = $hari[$i]->masa1;
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->masa1;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->penumpukanMasa1;
          } else if ($table == "Penumpukan Masa 2") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Hari"] = $hari[$i]->masa2;
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->masa2;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->penumpukanMasa2;
          } else if ($table == "Penumpukan Masa 3") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Hari"] = $hari[$i]->masa3;
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->masa3;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->penumpukanMasa3;
          } else if ($table == "JPB Extruck") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->jpbExtruck;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->jpbExtruck;
          } else if ($table == "Handling Charge") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->handlingCharge;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->handlingCharge;
          } else if ($table == "Paket Stuffing") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->paketStuffing;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->paketStuffing;
          } else if ($table == "Cargo Dooring") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->cargoDooring;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->cargoDooring;
          } else if ($table == "Sewa Crane") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->sewaCrane;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->sewaCrane;
          } else {
            $obj[$contNo]["billing_detail"][$bill][$table]["Hari"] = "Not Found!";
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = "Not Found!";
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = "Not Found!";
          }
          // dd($deliveryForm->orderService);
          $obj[$contNo]["Summary"][$bill]["Total Amount"] = $bill == "OSK" || $bill == "OSK(OSK246)" || $bill == "OSK(OSK48)" || $bill == "OSK(OSK212)" ? $value->initialOSK : $value->initialOS;
          $obj[$contNo]["Summary"][$bill]["Tax"] = $bill == "OSK" || $bill == "OSK(OSK246)" || $bill == "OSK(OSK48)" || $bill == "OSK(OSK212)" ? $value->taxOSK : $value->taxOS;
          $obj[$contNo]["Summary"][$bill]["Grand Total"] = $bill == "OSK" || $bill == "OSK(OSK246)" || $bill == "OSK(OSK48)" || $bill == "OSK(OSK212)" ? $delivery->grandTotal[$i]->totalOSK : $delivery->grandTotal[$i]->totalOS;

          $obj[$contNo]["container_detail"]["ContainerID"] = $contID;
          $obj[$contNo]["container_detail"]["Container Number"] = $contNo;
          $obj[$contNo]["container_detail"]["Container Type"] = $ctrType;
          $obj[$contNo]["container_detail"]["Container Size"] = $ctrSize;
          $obj[$contNo]["container_detail"]["Container Status"] = $ctrStatus;

          // $orderService[$bill][$ctrSize]["Order Service"] = $delivery;
        }
      }
      $i++;
    }
    // dd($orderService);
    $billingDetail =  $obj;

    $form["delivery_form_id"] = $deliveryForm->id;
    $form["order_service"] = $orderService;

    $fields = [
      "billingDetail" => $billingDetail,
      "formDetail" => $form,
    ];
    // dd($fields, json_encode($fields));
    $url = getenv('API_URL') . '/delivery-service/invoice/v2/store';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);
    // dd($result);

    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/receiving/billing')->with('success', 'Invoice berhasil dibuat & disimpan!');
    } else {
      return redirect('/receiving/billing')->with('success', 'Data gagal disimpan! kode error : #st2del');
    }
  }
  public function pranotaIndex(Request $request)
  {
    $data = [];

    $client = new Client();

    $id_invoice = $request->id;

    // GET SINGLE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/invoice/v2/single/' . $id_invoice;
    $req_single_invoice = $client->get($url_single_invoice);
    $response_single_invoice = $req_single_invoice->getBody()->getContents();
    $result_single_invoice = json_decode($response_single_invoice);
    // dd($result_single_invoice);

    // GET SINGLE PAYMENT METHODS
    $url_single_payment = getenv('API_URL') . '/delivery-service/payment/single/e5b8b2e5-2445-4798-8c77-b85d24eeae2b';
    $req_single_payment = $client->get($url_single_payment);
    $response_single_payment = $req_single_payment->getBody()->getContents();
    $result_single_payment = json_decode($response_single_payment);
    // dd($result_single_payment);
    $data["payments"] = $result_single_payment->data;
    $data["invoices"] = $result_single_invoice->data;
    $data["title"] = "Pranota ";
    return view('billing.export.pranota', $data);
  }
  public function invoiceIndex(Request $request)
  {
    $data = [];

    $client = new Client();

    $id_invoice = $request->id;

    // GET SINGLE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/invoice/v2/single/' . $id_invoice;
    $req_single_invoice = $client->get($url_single_invoice);
    $response_single_invoice = $req_single_invoice->getBody()->getContents();
    $result_single_invoice = json_decode($response_single_invoice);
    // dd($result_single_invoice);

    // GET SINGLE PAYMENT METHODS
    $url_single_payment = getenv('API_URL') . '/delivery-service/payment/single/e5b8b2e5-2445-4798-8c77-b85d24eeae2b';
    $req_single_payment = $client->get($url_single_payment);
    $response_single_payment = $req_single_payment->getBody()->getContents();
    $result_single_payment = json_decode($response_single_payment);
    // dd($result_single_payment);
    $data["payments"] = $result_single_payment->data;
    $data["invoices"] = $result_single_invoice->data;
    $data["title"] = "Invoice ";
    return view('billing.export.invoice', $data);
  }
  public function jobIndex(Request $request)
  {
    $client = new Client();

    $id_invoice = $request->id;
    // dd($id_invoice);
    //commited
    // GET SINGLE INVOICE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/invoice/v2/single/' . $id_invoice;
    $req_single_invoice = $client->get($url_single_invoice);
    $response_single_invoice = $req_single_invoice->getBody()->getContents();
    $result_single_invoice = json_decode($response_single_invoice);
    // dd($result_single_invoice);

    // GET SINGLE JOB CONTAINER
    $url_single_job = getenv('API_URL') . '/delivery-service/job/single/' . $result_single_invoice->data->jobID;
    $req_single_job = $client->get($url_single_job);
    $response_single_job = $req_single_job->getBody()->getContents();
    $result_single_job = json_decode($response_single_job);
    // dd($result_single_job);


    $jobData = $result_single_job->data;
    // dd($jobData);
    $qrcodes = QrCode::size(100)->generate($jobData->containers[0]->jobContainer->container_no);
    // dd($qrcodes);
    $data["job"] = $jobData->containers[0]->jobContainer;
    $data["invoice"] = $result_single_invoice->data;
    $data["delivery"] = $result_single_invoice->data->deliveryForm;
    $data["title"] = "Job Page | Icon Sarana";
    return view('billing.export.job', $data, compact('qrcodes'));
  }

  public function masterTarifIndex()
  {
    $client = new Client();

    $data = [];

    // GET ALL MASTER TARIF
    $url_mastertarif = getenv('API_URL') . '/delivery-service/mastertarif/all';
    $req_mastertarif = $client->get($url_mastertarif);
    $response_mastertarif = $req_mastertarif->getBody()->getContents();
    $result_mastertarif = json_decode($response_mastertarif);
    // dd($result_mastertarif);

    $data["title"] = "Master Tarif Receiving Data Dashboard | Icon Sarana";
    $data["mastertarif"] = $result_mastertarif->data;

    return view('billing.export.mastertarif.index', $data);
  }

  public function masterTarifDetail(Request $request)
  {
    $client = new Client();
    $id = $request->id;
    // dd($id);
    $data = [];

    // GET SINGLE MASTER TARIF
    $url_mastertarif = getenv('API_URL') . '/delivery-service/mastertarif/single/' . $id;
    $req_mastertarif = $client->get($url_mastertarif);
    $response_mastertarif = $req_mastertarif->getBody()->getContents();
    $result_mastertarif = json_decode($response_mastertarif);
    // dd($result_mastertarif);

    $data["title"] = "Master Tarif Detail | Icon Sarana";
    $data["mastertarif"] = $result_mastertarif->data;

    return view('billing.export.mastertarif.detail', $data);
  }

  public function masterTarifUpdate(Request $request)
  {
    $client = new Client();
    $input = $request->input();
    // dd($input);
    $fields = [
      "masa1" => str_replace(".", "", $request["masa1"]),
      "masa2" => str_replace(".", "", $request["masa2"]),
      "masa3" => str_replace(".", "", $request["masa3"]),
      "masa4" => str_replace(".", "", $request["masa4"]),
      "lift_on" => str_replace(".", "", $request["lift_on"]),
      "lift_off" => str_replace(".", "", $request["lift_off"]),
      "lift_empty" => str_replace(".", "", $request["lift_empty"]),
      "lift_full" => str_replace(".", "", $request["lift_full"]),
      "pass_truck" => str_replace(".", "", $request["pass_truck"]),
      "gate_pass_admin" => str_replace(".", "", $request["gate_pass_admin"]),
      "cost_recovery" => str_replace(".", "", $request["cost_recovery"]),
      "surcharge" => str_replace(".", "", $request["surcharge"]),
      "packet_plp" => str_replace(".", "", $request["packet_plp"]),
      "behandle" => str_replace(".", "", $request["behandle"]),
      "recooling" => str_replace(".", "", $request["recooling"]),
      "monitoring" => str_replace(".", "", $request["monitoring"]),
      "administrasi" => str_replace(".", "", $request["administrasi"]),
      "orderservice" => str_replace(".", "", $request["orderservice"]),
      "pemindahan" => str_replace(".", "", $request["pemindahan"]),
      "lift_off_mt" => str_replace(".", "", $request["lift_off_mt"]),
      "paket_stripping" => str_replace(".", "", $request["paket_stripping"]),
      "cargoDooring" => str_replace(".", "", $request["cargoDooring"]),
      "sewaCrane" => str_replace(".", "", $request["sewaCrane"]),
      "paketStuffing" => str_replace(".", "", $request["paketStuffing"]),
      "handlingCharge" => str_replace(".", "", $request["handlingCharge"]),
      "jpbExtruck" => str_replace(".", "", $request["jpbExtruck"]),

    ];
    // dd($fields);
    // dd($id);
    // $data = [];
    $url = getenv('API_URL') . '/delivery-service/mastertarif/update/' . $request["id"];
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);
    // dd($result);
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/receiving/mastertarif')->with('success', 'Master Tarif berhasil di edit & disimpan!');
    } else {
      return redirect('/receiving/mastertarif')->with('success', 'Data gagal disimpan! kode error : #st2del');
    }
  }

  public function masterTarifCreate()
  {
    $client = new Client();
    $data = [];
    $data["title"] = "Master Tarif Create";
    return view('billing.export.mastertarif.create', $data);
  }

  public function masterTarifStore(Request $request)
  {
    $client = new Client();
    $input = $request->input();
    // dd($input);
    $fields = [
      "type" => $request["type"],
      "size" => $request["size"],
      "status" => $request["status"],
      "masa1" => str_replace(".", "", $request["masa1"]),
      "masa2" => str_replace(".", "", $request["masa2"]),
      "masa3" => str_replace(".", "", $request["masa3"]),
      "masa4" => str_replace(".", "", $request["masa4"]),
      "lift_on" => str_replace(".", "", $request["lift_on"]),
      "lift_off" => str_replace(".", "", $request["lift_off"]),
      "lift_empty" => str_replace(".", "", $request["lift_empty"]),
      "lift_full" => str_replace(".", "", $request["lift_full"]),
      "pass_truck" => str_replace(".", "", $request["pass_truck"]),
      "gate_pass_admin" => str_replace(".", "", $request["gate_pass_admin"]),
      "cost_recovery" => str_replace(".", "", $request["cost_recovery"]),
      "surcharge" => str_replace(".", "", $request["surcharge"]),
      "packet_plp" => str_replace(".", "", $request["packet_plp"]),
      "behandle" => str_replace(".", "", $request["behandle"]),
      "recooling" => str_replace(".", "", $request["recooling"]),
      "monitoring" => str_replace(".", "", $request["monitoring"]),
      "administrasi" => str_replace(".", "", $request["administrasi"]),
      "orderservice" => str_replace(".", "", $request["orderservice"]),
      "pemindahan" => str_replace(".", "", $request["pemindahan"]),
      "lift_off_mt" => str_replace(".", "", $request["lift_off_mt"]),
      "paket_stripping" => str_replace(".", "", $request["paket_stripping"]),
      "cargoDooring" => str_replace(".", "", $request["cargoDooring"]),
      "sewaCrane" => str_replace(".", "", $request["sewaCrane"]),
      "paketStuffing" => str_replace(".", "", $request["paketStuffing"]),
      "handlingCharge" => str_replace(".", "", $request["handlingCharge"]),
      "jpbExtruck" => str_replace(".", "", $request["jpbExtruck"]),

    ];
    // dd($fields);
    // dd($id);
    // $data = [];
    $url = getenv('API_URL') . '/delivery-service/mastertarif/create';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);
    // dd($result);
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/receiving/mastertarif')->with('success', 'Master Tarif berhasil ditambah & disimpan!');
    } else {
      return redirect('/receiving/mastertarif')->with('success', 'Data gagal disimpan! kode error : #st2del');
    }
  }
}
