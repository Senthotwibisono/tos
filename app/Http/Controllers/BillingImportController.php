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

class BillingImportController extends Controller
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

    // if ($result_invoice->data->deliveryForm->orderService == "sp2iks") {
    //   $data["orderService"] = "SP2 Kapal Sandar Icon (MT Balik IKS / MKB)";
    // } else if ($result_invoice->data->deliveryForm->orderService == "sp2pelindo") {
    //   $data["orderService"] = "SP2 Kapal Sandar icon (MT Balik Pelindo)";
    // } else if ($result_invoice->data->deliveryForm->orderService == "spps") {
    //   $data["orderService"] = "SPPS";
    // } else if ($result_invoice->data->deliveryForm->orderService == "sppsrelokasipelindo") {
    //   $data["orderService"] = "SPPS (Relokasi Pelindo - ICON)";
    // } else if ($result_invoice->data->deliveryForm->orderService == "sp2icon") {
    //   $data["orderService"] = "SP2 (MT Balik ICON / MKB)";
    // }

    $data["title"] = "Delivery Billing System";
    $data["invoices"] = $result_invoice->data;
    return view('billing.import.billingIndex', $data);
  }

  public function formIndex()
  {
    $client = new Client();
    $data = [];

    // GET ALL FORM
    $url_delivery = getenv('API_URL') . '/delivery-service/form/all';
    $req_delivery = $client->get($url_delivery);
    $response_delivery = $req_delivery->getBody()->getContents();
    $result_delivery = json_decode($response_delivery);
    // dd($result_delivery);

    $data["deliveries"] = $result_delivery->data;
    $data["title"] = "Delivery Form";
    return view('billing.import.form.formIndex', $data);
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

    // GET ALL DO NUMBER
    $url_do = getenv('API_URL') . '/delivery-service/do/groupall';
    $req_do = $client->get($url_do);
    $response_do = $req_do->getBody()->getContents();
    $result_do = json_decode($response_do);
    // dd($result_do);

    $data["customer"] = $result_customer->data;
    $data["container"] = $result_container->data;
    $data["do"] = $result_do->data;
    $data["user"] = $user->id;
    $data["title"] = "Delivery Create Form | Delivery Billing System";
    return view('billing.import.form.create', $data);
  }

  public function storeForm(Request $request)
  {
    $data = [];
    $client = new Client();

    $exp_date = $request->exp_date;
    // $exp_time = $request->exp_time;
    $customer = $request->customer;
    $do_number = $request->do_number ?? $request->do_number_auto;
    $do_exp_date = $request->do_exp_date;
    $boln = $request->boln;
    $container = $request->container;
    $order_service = $request->order_service;
    $documentNumber = $request->documentNumber;
    $documentType = $request->documentType;
    $documentDate = $request->documentDate;
    $fields = [
      "exp_date" => $exp_date,
      "customer_id" => $customer,
      "do_number" => $do_number,
      "do_exp_date" => $do_exp_date,
      "boln" => $boln,
      "container" => $container,
      "orderService" => $order_service,
      "documentNumber" => $documentNumber,
      "documentType" => $documentType,
      "documentDate" => $documentDate,

    ];
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
      return redirect('/delivery/form/review?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
    } else {
      return redirect('/delivery')->with('success', 'Data gagal disimpan!');
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
    $url = getenv('API_URL') . '/delivery-service/form/delivery/calculate';

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
    if ($result->data->deliveryForm->orderService == "sp2iks") {
      $data["orderService"] = "SP2 Kapal Sandar Icon (MT Balik IKS )";
    } else if ($result->data->deliveryForm->orderService == "sp2mkb") {
      $data["orderService"] = "SP2 Kapal Sandar icon (MKB)";
    } else if ($result->data->deliveryForm->orderService == "sp2pelindo") {
      $data["orderService"] = "SP2 Kapal Sandar icon (MT Balik Pelindo)";
    } else if ($result->data->deliveryForm->orderService == "spps") {
      $data["orderService"] = "SPPS";
    } else if ($result->data->deliveryForm->orderService == "sppsrelokasipelindo") {
      $data["orderService"] = "SPPS (Relokasi Pelindo - ICON)";
    } else if ($result->data->deliveryForm->orderService == "sp2icon") {
      $data["orderService"] = "SP2 (Relokasi Pelindo - ICON)";
    }


    $data["title"] = "Review Form & Calculation Pranota | Delivery Billing System";
    return view('billing.import.form.review', $data);
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
    $url = getenv('API_URL') . '/delivery-service/form/delivery/calculate';

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
    if ($orderService == "sp2iks") {
      $orderServiceName = "SP2 Kapal Sandar Icon (MT Balik IKS)";
    } else if ($orderService == "sp2mkb") {
      $orderServiceName = "SP2 Kapal Sandar Icon (MKB)";
    } else if ($orderService == "sp2pelindo") {
      $orderServiceName = "SP2 Kapal Sandar icon (MT Balik Pelindo)";
    } else if ($orderService == "spps") {
      $orderServiceName = "SPPS";
    } else if ($orderService == "sppsrelokasipelindo") {
      $orderServiceName = "SPPS (Relokasi Pelindo - ICON)";
    } else if ($orderService == "sp2icon") {
      $orderServiceName = "SP2 (Relokasi Pelindo - ICON)";
    }

    $delivery = $result->data;
    $billingTotal = $result->data->billingTotal;
    $deliveryForm = $result->data->deliveryForm;
    $customer = $result->data->deliveryForm->customer;
    $containers = $result->data->deliveryForm->containers;
    $grandTotal = $result->data->grandTotal;
    // dd($billingTotal[0]);
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
      // dd($container);
      // dd($value);
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
        foreach ($bill == "DSK" ? $value->table->titleTableDSK : $value->table->titleTableDS as $table) {
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
          } else if ($table == "Pass Truck Keluar") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->pass_truck;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->passTruckOut;
          } else if ($table == "Pass Truck Masuk") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->pass_truck;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->passTruckIn;
          } else if ($table == "Pass Truck") {
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = $tarif->pass_truck;
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = $value->passTruck ?? $value->passTruckIn;
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
          } else {
            $obj[$contNo]["billing_detail"][$bill][$table]["Hari"] = "Not Found!";
            $obj[$contNo]["billing_detail"][$bill][$table]["Tarif Satuan"] = "Not Found!";
            $obj[$contNo]["billing_detail"][$bill][$table]["Amount"] = "Not Found!";
          }
          // dd($deliveryForm->orderService);
          $obj[$contNo]["Summary"][$bill]["Total Amount"] = $bill == "DSK" ? $value->initialDSK : $value->initialDS;
          $obj[$contNo]["Summary"][$bill]["Tax"] = $bill == "DSK" ? $value->taxDSK : $value->taxDS;
          $obj[$contNo]["Summary"][$bill]["Grand Total"] = $bill == "DSK" ? $delivery->grandTotal[$i]->totalDSK : $delivery->grandTotal[$i]->totalDS;

          $obj[$contNo]["container_detail"]["ContainerID"] = $contID;
          $obj[$contNo]["container_detail"]["Container Number"] = $contNo;
          $obj[$contNo]["container_detail"]["Container Type"] = $ctrType;
          $obj[$contNo]["container_detail"]["Container Size"] = $ctrSize;
          $obj[$contNo]["container_detail"]["Container Status"] = $ctrStatus;

          // $orderService[$bill][$ctrSize]["Order Service"] = $delivery;
        }
      }
      $i++;
      // $arrCont[$value->billingName->container->container_no] = $value->billingName->container;
    }
    // dd($arrCont);
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
      return redirect('/delivery/billing')->with('success', 'Invoice berhasil dibuat & disimpan!');
    } else {
      return redirect('/delivery/billing')->with('success', 'Data gagal disimpan! kode error : #st2del');
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
    return view('billing.import.pranota', $data);
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
    return view('billing.import.invoice', $data);
  }

  public function singleInvoice(Request $request)
  {
    $client = new Client();

    $id_invoice = $request->id;
    // GET SINGLE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/invoice/v2/single/' . $id_invoice;
    $req_single_invoice = $client->get($url_single_invoice);
    $response_single_invoice = $req_single_invoice->getBody()->getContents();
    $result_single_invoice = json_decode($response_single_invoice);
    // dd($result_single_invoice);

    echo $response_single_invoice;
  }

  public function VerifyPayment(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $fields =
      [
        "id" => $id,
        "isPaid" => 1,
      ];
    $url = getenv('API_URL') . '/delivery-service/invoice/v2/setVerify';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    // var_dump($response);
    // die();

    echo $response;
  }

  public function VerifyPiutang(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $fields =
      [
        "id" => $id,
        "isPiutang" => 1,
      ];
    $url = getenv('API_URL') . '/delivery-service/invoice/v2/setVerify';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    // var_dump($response);
    // die();

    echo $response;
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
    return view('billing.import.job', $data, compact('qrcodes'));
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

    $data["title"] = "Master Tarif Data Dashboard | Icon Sarana";
    $data["mastertarif"] = $result_mastertarif->data;

    return view('billing.import.mastertarif.index', $data);
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

    return view('billing.import.mastertarif.detail', $data);
  }
}
