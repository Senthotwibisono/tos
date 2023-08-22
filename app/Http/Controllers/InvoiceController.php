<?php

namespace App\Http\Controllers;

use Auth;
use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use App\Http\Controllers\DataExport;
use App\Models\TpsSppbPib; // check doc number import
use App\Models\TpsSppbBc; // check doc import bc
use App\Models\TpsDokPabean; // check doc lainya
use App\Models\TpsSppbPibCont; // check container
use App\Models\TpsSppbBcCont; // check container bc
use App\Models\TpsDokPabeanCont; // check container pabean

// cari doc number, type & date
// 1. check TpsSppbPib (NO_SPPB)
// 2. kalo ga nemu ke yg TpsSppbBc (NO_SPPB)
// 3. kalo masi ga nemu ke yg TpsDokPabean (NO_DOC_INOUT)

// cari container
// 1. check TpsSppbPibCont (_CONT)
// 2. kalo ga nemu ke yg TpsSppbBcCont (_CONT)
// 3. kalo masi ga nemu ke yg TpsDokPabeanCont (_CONT)




class InvoiceController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function menuindex()
  {
    $data = [];
    $data["title"] = "Menu Billing System";

    return view('invoice.menu', $data);
  }
  public function index()
  {
    // dd("Masuk");
    $client = new Client();

    $data = [];

    // GET ALL INVOICE
    // $url_invoice = getenv('API_URL') . '/customer-service/customerAll';
    $url_invoice = getenv('API_URL') . '/delivery-service/invoice/all';
    $req_invoice = $client->get($url_invoice);
    $response_invoice = $req_invoice->getBody()->getContents();
    $result_invoice = json_decode($response_invoice);
    // dd($result_invoice);

    $data["invoices"] = $result_invoice->data;
    $data["title"] = "Billing System";
    return view('invoice.dashboard', $data);
  }

  public function extendIndex()
  {
    // dd("Masuk");
    $client = new Client();

    $data = [];

    // GET ALL INVOICE
    // $url_invoice = getenv('API_URL') . '/customer-service/customerAll';
    $url_invoice = getenv('API_URL') . '/delivery-service/invoice/all';
    $req_invoice = $client->get($url_invoice);
    $response_invoice = $req_invoice->getBody()->getContents();
    $result_invoice = json_decode($response_invoice);
    // dd($result_invoice);

    $data["invoices"] = $result_invoice->data;
    $data["title"] = "Billing System";
    return view('invoice/delivery_form/extend/dashboard', $data);
  }

  public function deliveryForm()
  {
    $data = [];
    $client = new Client();

    // GET ALL FORM
    $url_delivery = getenv('API_URL') . '/delivery-service/form/all';
    $req_delivery = $client->get($url_delivery);
    $response_delivery = $req_delivery->getBody()->getContents();
    $result_delivery = json_decode($response_delivery);
    // dd($result_delivery);

    $data["deliveries"] = $result_delivery->data;
    $data["title"] = "Delivery Form Data";
    return view('invoice/delivery_form/dashboard', $data);
  }

  public function masterTarif()
  {
    $data = [];
    $client = new Client();

    // GET ALL TARIF SP2
    $url_master_tarif = getenv('API_URL') . '/delivery-service/mastertarif/sp2/all';
    $req_master_tarif = $client->get($url_master_tarif);
    $response_master_tarif = $req_master_tarif->getBody()->getContents();
    $result_master_tarif = json_decode($response_master_tarif);


    $data["mastertarif"] = $result_master_tarif->data;
    $data["title"] = "Master Tarif Data";
    return view('invoice/master_tarif/dashboard', $data);
  }

  public function addMasterTarif()
  {
    $data = [];
    $client = new Client();

    $data["title"] = "Tambah Master Tarif Data";
    return view('invoice/master_tarif/add', $data);
  }

  public function editMasterTarif(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $url_form = getenv('API_URL') . '/delivery-service/mastertarif/sp2/single/' . $id;
    $req_form = $client->get($url_form);
    $response_form = $req_form->getBody()->getContents();
    $result_master_tarif = json_decode($response_form);

    $data["mastertarif"] = $result_master_tarif->data;
    $data["title"] = "Edit Master Tarif Data";

    return view('invoice/master_tarif/edit', $data);
  }

  public function storeEditMasterTarif(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $lokasi_sandar = $request->lokasi_sandar;
    $type = $request->type;
    $size = $request->size;
    $status = $request->status;
    $masa1 = $request->masa1;
    $masa2 = $request->masa2;
    $masa3 = $request->masa3;
    $masa4 = $request->masa4;
    $lift_on = $request->lift_on;
    $lift_off = $request->lift_off;
    $pass_truck = $request->pass_truck;
    $gate_pass_admin = $request->gate_pass_admin;
    $cost_recovery = $request->cost_recovery;
    $surcharge = $request->surcharge;
    $packet_plp = $request->packet_plp;
    $behandle = $request->behandle;
    $recooling = $request->recooling;
    $monitoring = $request->monitoring;
    $administrasi = $request->administrasi;
    $fields =
      [
        "lokasi_sandar" => $lokasi_sandar,
        "type" => $type,
        "size" => $size,
        "status" => $status,
        "masa1" => $masa1,
        "masa2" => $masa2,
        "masa3" => $masa3,
        "masa4" => $masa4,
        "lift_on" => $lift_on,
        "lift_off" => $lift_off,
        "pass_truck" => $pass_truck,
        "gate_pass_admin" => $gate_pass_admin,
        "cost_recovery" => $cost_recovery,
        "surcharge" => $surcharge,
        "packet_plp" => $packet_plp,
        "behandle" => $behandle,
        "recooling" => $recooling,
        "monitoring" => $monitoring,
        "administrasi" => $administrasi,
      ];
    $url = getenv('API_URL') . '/delivery-service/mastertarif/sp2/update/' . $id;
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    // dd($response);
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/invoice/mastertarif')->with('success', 'Data berhasil disimpan!');
    } else {
      return redirect('/invoice/mastertarif')->with('success', 'Data gagal disimpan!');
    }
  }

  public function storeCreateMasterTarif(Request $request)
  {
    $client = new Client();

    // $id = $request->id;
    // var_dump($id);
    // die();
    $lokasi_sandar = $request->lokasi_sandar;
    $type = $request->type;
    $size = $request->size;
    $status = $request->status;
    $masa1 = $request->masa1;
    $masa2 = $request->masa2;
    $masa3 = $request->masa3;
    $masa4 = $request->masa4;
    $lift_on = $request->lift_on;
    $lift_off = $request->lift_off;
    $pass_truck = $request->pass_truck;
    $gate_pass_admin = $request->gate_pass_admin;
    $cost_recovery = $request->cost_recovery;
    $surcharge = $request->surcharge;
    $packet_plp = $request->packet_plp;
    $behandle = $request->behandle;
    $recooling = $request->recooling;
    $monitoring = $request->monitoring;
    $administrasi = $request->administrasi;
    $fields =
      [
        "lokasi_sandar" => $lokasi_sandar,
        "type" => $type,
        "size" => $size,
        "status" => $status,
        "masa1" => $masa1,
        "masa2" => $masa2,
        "masa3" => $masa3,
        "masa4" => $masa4,
        "lift_on" => $lift_on,
        "lift_off" => $lift_off,
        "pass_truck" => $pass_truck,
        "gate_pass_admin" => $gate_pass_admin,
        "cost_recovery" => $cost_recovery,
        "surcharge" => $surcharge,
        "packet_plp" => $packet_plp,
        "behandle" => $behandle,
        "recooling" => $recooling,
        "monitoring" => $monitoring,
        "administrasi" => $administrasi,
      ];
    // dd($fields);
    $url = getenv('API_URL') . '/delivery-service/mastertarif/sp2/create';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/invoice/mastertarif')->with('success', 'Data berhasil disimpan!');
    } else {
      return redirect('/invoice/mastertarif')->with('success', 'Data gagal disimpan!');
    }
  }


  public function Pranota(Request $request)
  {
    $data = [];
    $client = new Client();

    $id_invoice = $request->id;
    // dd($id_invoice);

    // GET SINGLE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/invoice/single/' . $id_invoice;
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
    $data["title"] = "Pranota Data";
    return view('invoice/pranota/index', $data);
  }

  public function PaidInvoice(Request $request)
  {
    $data = [];
    $client = new Client();

    $id_invoice = $request->id;
    // dd($id_invoice);
    //commited
    // GET SINGLE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/invoice/single/' . $id_invoice;
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
    $data["title"] = "Invoice " . $result_single_invoice->data->invoice->invoiceNumber;
    return view('invoice/paid_invoice/index', $data);
  }

  public function jobPage(Request $request)
  {
    $client = new Client();

    $id_invoice = $request->id;
    // dd($id_invoice);
    //commited
    // GET SINGLE INVOICE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/invoice/single/' . $id_invoice;
    $req_single_invoice = $client->get($url_single_invoice);
    $response_single_invoice = $req_single_invoice->getBody()->getContents();
    $result_single_invoice = json_decode($response_single_invoice);
    // dd($result_single_invoice);

    $id_delivery = $result_single_invoice->data->invoice->data6->deliveryid;

    // GET SINGLE DELIVERY FORM
    $url_single_form = getenv('API_URL') . '/delivery-service/form/container/' . $id_delivery;
    $req_single_form = $client->get($url_single_form);
    $response_single_form = $req_single_form->getBody()->getContents();
    $result_single_form = json_decode($response_single_form);
    // dd($result_single_form);
    $container = $result_single_form->data->containers;
    // dd($container);
    $container_arr = [];
    foreach ($container as $data) {
      array_push($container_arr, [
        // "banner_url" => $banner_url_file_1,
        $data->container_no
      ]);
    }
    // dd($container_arr);
    $jobNumber = $result_single_invoice->data->invoice->jobNumber;
    // dd($jobNumber);
    $fields = [
      "containers" => $container_arr,
      "jobNumber" => $jobNumber,
    ];
    // dd($fields);

    $url = getenv('API_URL') . '/delivery-service/job/conkey';
    $req = $client->get(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);
    // dd($result);
    $data = [];
    $jobData = $result->data->jobData;
    // dd($jobData);
    $qrcodes = [];
    foreach ($jobData as $value) {
      array_push(
        $qrcodes,
        QrCode::size(100)->generate($value->container_no)
      );
    }
    // dd($qrcodes);
    $data["jobs"] = $jobData;
    $data["invoice"] = $result_single_invoice->data;
    $data["delivery"] = $result_single_form->data;
    $data["title"] = "Job Page";
    return view('invoice/jobPage/index', $data, compact('qrcodes'));
  }

  public function test()
  {
    dd("CONFIRM");
  }
  public function addDataStep1()
  {
    $data = [];
    $data["title"] = "Step 1 | Devivery Form Input Data";

    $client = new Client();
    // GET ALL CUSTOMER
    // $url_customer = getenv('API_URL') . '/customer-service/customerAll';
    $url_customer = getenv('API_URL') . '/delivery-service/customer/all';
    $req_customer = $client->get($url_customer);
    $response_customer = $req_customer->getBody()->getContents();
    $result_customer = json_decode($response_customer);
    // dd($result_customer);

    // GET ALL CONTAINER
    // $url_container = getenv('API_URL') . '/container-service/all';
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
    return view('invoice/delivery_form/add_step_1', $data);
  }

  public function updateDataStep1(Request $request)
  {
    $data = [];
    $data["title"] = "Update Data Step 1 | Devivery Form Update Data";

    $client = new Client();

    $id_param = $request->id;
    // dd($id_param);

    // GET SINGLE FORM
    $url_form = getenv('API_URL') . '/delivery-service/form/single/' . $id_param;
    $req_form = $client->get($url_form);
    $response_form = $req_form->getBody()->getContents();
    $result_form = json_decode($response_form);
    // dd($result_form);

    // GET ALL CUSTOMER
    // $url_customer = getenv('API_URL') . '/customer-service/customerAll';
    $url_customer = getenv('API_URL') . '/delivery-service/customer/all';
    $req_customer = $client->get($url_customer);
    $response_customer = $req_customer->getBody()->getContents();
    $result_customer = json_decode($response_customer);
    // dd($result_customer);

    // GET ALL CONTAINER
    // $url_container = getenv('API_URL') . '/container-service/all';
    $url_container = getenv('API_URL') . '/delivery-service/container/all';
    $req_container = $client->get($url_container);
    $response_container = $req_container->getBody()->getContents();
    $result_container = json_decode($response_container);
    // dd($result_container);

    $data["form"] = $result_form->data;
    $data["customer"] = $result_customer->data;
    $data["container"] = $result_container->data;

    return view('invoice/delivery_form/update_step_1', $data);
  }

  public function storeDataStep1(Request $request)
  {
    $data = [];
    $client = new Client();

    $exp_date = $request->exp_date;
    $exp_time = $request->exp_time;
    $customer = $request->customer;
    $do_number = $request->do_number ?? $request->do_number_auto;
    $do_exp_date = $request->do_exp_date;
    $boln = $request->boln;
    $container = $request->container;
    $order_service = $request->order_service;
    $fields = [
      "exp_date" => $exp_date,
      "time" => $exp_time,
      "customer_id" => $customer,
      "do_number" => $do_number,
      "do_exp_date" => $do_exp_date,
      "boln" => $boln,
      "container" => $container,
      "orderService" => $order_service,
    ];
    // dd($fields);
    // Commit changes

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
      return redirect('/invoice/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
    } else {
      return redirect('/invoice')->with('success', 'Data gagal disimpan!');
    }
  }

  public function storeUpdateDataStep1(Request $request)
  {
    $data = [];
    $client = new Client();

    $id_param = $request->id;
    // dd($id_param);

    $exp_date = $request->exp_date;
    $exp_time = $request->exp_time;
    $customer = $request->customer;
    $do_number = $request->do_number;
    $do_exp_date = $request->do_exp_date;
    $boln = $request->boln;
    $container = $request->container;
    $fields = [
      "exp_date" => $exp_date,
      "time" => $exp_time,
      "customer" => $customer,
      "do_number" => $do_number,
      "do_exp_date" => $do_exp_date,
      "boln" => $boln,
      "container" => $container,
      "orderService" => "sp2",

    ];
    // dd($fields);
    // Commit changes

    $url = getenv('API_URL') . '/delivery-service/form/update/' . $id_param;
    // dd($url);
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    // var_dumpa
    $result = json_decode($response);
    // dd($result);
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/invoice/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan! Silahkan preview sebelum melakukan perhitungan!');
    } else {
      return redirect('/invoice')->with('success', 'Data gagal disimpan!');
    }
  }

  public function addDataStep2(Request $request)
  {

    $client = new Client();

    $id_form = $request->id;
    // dd($id_form);

    // GET SINGLE FORM
    $url_single_form = getenv('API_URL') . '/delivery-service/form/container/' . $id_form;
    $req_single_form = $client->get($url_single_form);
    $response_single_form = $req_single_form->getBody()->getContents();
    $result_single_form = json_decode($response_single_form);
    // dd($result_single_form);
    $container = $result_single_form->data->containers;
    // dd($container);
    $container_arr = [];
    foreach ($container as $data) {
      array_push($container_arr, [
        // "banner_url" => $banner_url_file_1,
        $data->container_no
      ]);
    }

    $fields = [
      "id" => $id_form,
      "containers" => $container_arr,
    ];
    // dd($fields);
    if ($result_single_form->data->orderService == "sp2") {
      $url = getenv('API_URL') . '/delivery-service/form/calculate/sp2';
    } else {
      $url = getenv('API_URL') . '/delivery-service/form/calculate/spps';
    }

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

    $isExtended = $result->data->deliveryForm->isExtended;
    $diffInDays = $result->data->diffInDays[0];
    $data["ccdelivery"] = $result->data;
    if ($result->data->deliveryForm->isExtended != 1) {
      if ($result->data->deliveryForm->orderService != "spps") {
        // $data["menuinv"] = [$isExtended != 1 ? "Lift On" : "", $isExtended != 1 ? "Pass Truck" : "", $diffInDays->masa1 > 0 ? "Penumpukan Masa 1" : "", $diffInDays->masa2 > 0 ? "Penumpukan Masa 2" : "", $diffInDays->masa3 > 0 ? "Penumpukan Masa 3" : ""];
        if ($diffInDays->masa1 != 0 && $diffInDays->masa2 != 0 && $diffInDays->masa3 != 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa1 != 0 && $diffInDays->masa2 != 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 2"];
        } else if ($diffInDays->masa1 != 0 && $diffInDays->masa3 != 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa2 != 0 && $diffInDays->masa3 != 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 2", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa1 == 0 && $diffInDays->masa2 != 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 2"];
        } else if ($diffInDays->masa1 == 0 && $diffInDays->masa3 != 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa2 == 0 && $diffInDays->masa1 != 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 1"];
        } else if ($diffInDays->masa3 == 0 && $diffInDays->masa2 != 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 2"];
        } else if ($diffInDays->masa1 == 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 2", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa2 == 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa3 == 0) {
          $data["menuinv"] = ["Lift On", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 2"];
        }
      } else {
        // $data["menuinv"] = [$isExtended != 1 ? "Paket Stripping" : "", $isExtended != 1 ? "Pass Truck" : "", $diffInDays->masa1 > 0 ? "Penumpukan Masa 1" : "Penumpukan Masa 1", $diffInDays->masa2 > 0 ? "Penumpukan Masa 2" : "Penumpukan Masa 2", $diffInDays->masa3 > 0 ? "Penumpukan Masa 3" : "Penumpukan Masa 3"];
        if ($diffInDays->masa1 != 0 && $diffInDays->masa2 != 0 && $diffInDays->masa3 != 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa1 != 0 && $diffInDays->masa2 != 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 2"];
        } else if ($diffInDays->masa1 != 0 && $diffInDays->masa3 != 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa2 != 0 && $diffInDays->masa3 != 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 2", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa1 == 0 && $diffInDays->masa2 != 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 2"];
        } else if ($diffInDays->masa1 == 0 && $diffInDays->masa3 != 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa2 == 0 && $diffInDays->masa1 != 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 1"];
        } else if ($diffInDays->masa3 == 0 && $diffInDays->masa2 != 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 2"];
        } else if ($diffInDays->masa1 == 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 2", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa2 == 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 3"];
        } else if ($diffInDays->masa3 == 0) {
          $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 2"];
        }
      }
    } else if ($diffInDays->masa1 != 0 && $diffInDays->masa2 != 0 && $diffInDays->masa3 != 0) {
      $data["menuinv"] = ["Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3"];
    } else if ($diffInDays->masa1 != 0 && $diffInDays->masa2 != 0) {
      $data["menuinv"] = ["Penumpukan Masa 1", "Penumpukan Masa 2"];
    } else if ($diffInDays->masa1 != 0 && $diffInDays->masa3 != 0) {
      $data["menuinv"] = ["Penumpukan Masa 1", "Penumpukan Masa 3"];
    } else if ($diffInDays->masa2 != 0 && $diffInDays->masa3 != 0) {
      $data["menuinv"] = ["Penumpukan Masa 2", "Penumpukan Masa 3"];
    } else if ($diffInDays->masa1 == 0 && $diffInDays->masa2 != 0) {
      $data["menuinv"] = ["Penumpukan Masa 2"];
    } else if ($diffInDays->masa1 == 0 && $diffInDays->masa3 != 0) {
      $data["menuinv"] = ["Penumpukan Masa 3"];
    } else if ($diffInDays->masa2 == 0 && $diffInDays->masa1 != 0) {
      $data["menuinv"] = ["Penumpukan Masa 1"];
    } else if ($diffInDays->masa3 == 0 && $diffInDays->masa2 != 0) {
      $data["menuinv"] = ["Penumpukan Masa 2"];
    } else if ($diffInDays->masa1 == 0) {
      $data["menuinv"] = ["Penumpukan Masa 2", "Penumpukan Masa 3"];
    } else if ($diffInDays->masa2 == 0) {
      $data["menuinv"] = ["Penumpukan Masa 1", "Penumpukan Masa 3"];
    } else if ($diffInDays->masa3 == 0) {
      $data["menuinv"] = ["Penumpukan Masa 1", "Penumpukan Masa 2"];
    }
    // dd($data["menuinv"]);
    $data["isExtended"] = $isExtended;
    $data["diffInDays"] = $diffInDays;
    $data["title"] = "Step 2 | Delivery Pranota";
    return view('invoice/delivery_form/add_step_2', $data);
  }

  public function storeDataStep2(Request $request)
  {
    $data = [];
    $client = new Client();


    $data1 = $request->input('data1');
    $data2 = $request->input('data2');
    $data3 = $request->input('data3');
    $data4 = $request->input('data4');
    $data5 = $request->input('data5');
    $data6 = $request->input('data6');
    $isExtended = $request->input('isExtended');
    $active_to = $request->input('active_to');
    // $data7 = $request->input('data7');
    $orderService = $request->input('orderService');
    $fields = [
      "data1" => $data1,
      "data2" => $data2,
      "data3" => $data3,
      "data4" => $data4,
      "data5" => $data5,
      "data6" => $data6,
      "isExtended" => $isExtended,
      // "data7" => $data7,
      "orderService" => $orderService,
      "active_to" => $active_to,
    ];
    // dd($fields);

    $url = getenv('API_URL') . '/delivery-service/invoice/create';
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
      if ($isExtended == "1") {
        return redirect('/invoice/add/extend')->with('success', 'Invoice berhasil dibuat & disimpan!');
      } else {
        if ($orderService == "export") {
          return redirect('/export')->with('success', 'Invoice berhasil dibuat & disimpan!');
        } else {
          return redirect('/invoice')->with('success', 'Invoice berhasil dibuat & disimpan!');
        }
      }
    } else {
      return redirect('/invoice')->with('error', 'Data gagal disimpan! kode error : #st2del');
    }
  }

  public function addDataExtendStep1()
  {
    $data = [];
    $data["title"] = "Step 1 | Devivery Extend Form Input Data";
    // dd("MASUKK");
    $client = new Client();

    // GET ALL CUSTOMER
    // $url_delivery = getenv('API_URL') . '/customer-service/customerAll';
    $url_invoice = getenv('API_URL') . '/delivery-service/invoice/all';
    $req_invoice = $client->get($url_invoice);
    $response_invoice = $req_invoice->getBody()->getContents();
    $result_invoice = json_decode($response_invoice);
    // dd($result_invoice);

    $data["invoices"] = $result_invoice->data;

    return view('invoice/delivery_form/extend/add_step_1', $data);
  }

  public function storeDataExtendStep1(Request $request)
  {
    $data = [];
    $client = new Client();

    $extended_exp_date = $request->extended_exp_date;
    $deliveryid = $request->form;
    $fields = [
      "extended_exp_date" => $extended_exp_date,
      "isExtended" => "1",
    ];
    // dd($fields);
    // Commit changes

    $url = getenv('API_URL') . '/delivery-service/form/extend/expupdate/' . $deliveryid;
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
      return redirect('/invoice/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
    } else {
      return redirect('/invoice')->with('success', 'Data gagal disimpan!');
    }
  }

  public function customerDashboard()
  {
    $data = [];
    $client = new Client();

    // $url_test = getenv('API_URL').'/delivery-service/form/all';
    // $req_test = $client->get($url_test);
    // $response_test = $req_test->getBody()->getContents();
    // $result_test = json_decode($response_test);
    // dd($result_test->data[0]->container[1]);

    // GET ALL CUSTOMER
    // $url_customer = getenv('API_URL') . '/customer-service/customerAll';
    $url_customer = getenv('API_URL') . '/delivery-service/customer/all';
    $req_customer = $client->get($url_customer);
    $response_customer = $req_customer->getBody()->getContents();
    $result_customer = json_decode($response_customer);
    // dd($result_customer);

    $data["customer"] = $result_customer->data;
    $data["title"] = "Dashboard | Data Customer";
    return view('invoice/customer/dashboard', $data);
  }

  public function addDataCustomer()
  {
    $data = [];
    $client = new Client();

    $data["title"] = "Add Customer Data | Data Customer";
    return view('invoice/customer/customeradd', $data);
  }

  public function storeDataCustomer(Request $request)
  {
    $data = [];
    $client = new Client();

    $cust_name = $request->cust_name;
    $cust_no = $request->cust_code;
    $cust_phone = $request->cust_phone;
    $cust_fax = $request->cust_fax;
    $cust_npwp = $request->cust_npwp;
    $cust_address = $request->cust_address;

    $fields = [
      "customer_name" => $cust_name,
      "customer_no" => $cust_no,
      "phone" => $cust_phone,
      "fax" => $cust_fax,
      "npwp" => $cust_npwp,
      "address" => $cust_address,
    ];
    // dd($fields);

    $url = getenv('API_URL') . '/delivery-service/customer/create';
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
      return redirect('/invoice/customer')->with('success', 'Data berhasil disimpan!');
    } else {
      return redirect('/invoice/customer')->with('success', 'Data gagal disimpan!');
    }
  }

  public function containerDashboard()
  {
    $data = [];
    $client = new Client();

    // GET ALL CONTAINER
    // $url_container = getenv('API_URL') . '/container-service/containerAll';
    $url_container = getenv('API_URL') . '/delivery-service/container/all';
    $req_container = $client->get($url_container);
    $response_container = $req_container->getBody()->getContents();
    $result_container = json_decode($response_container);
    // dd($result_container);

    $data["container"] = $result_container->data;
    $data["title"] = "Dashboard | Data Container";
    return view('invoice/container/dashboard', $data);
  }

  public function addDataContainer()
  {
    $data = [];
    $client = new Client();

    $data["title"] = "Add Container Data | Data Container";
    return view('invoice/container/containeradd', $data);
  }

  public function storeDataContainer(Request $request)
  {
    $data = [];
    $client = new Client();

    $container_name = $request->container_name;
    $container_no = $request->container_no;
    $ctr_status = $request->ctr_status;
    $ctr_intern_status = $request->ctr_intern_status;
    $type = $request->type;
    $size = $request->size;
    $gross = $request->gross;

    $fields = [
      "container_name" => $container_name,
      "container_no" => $container_no,
      "ctr_status" => $ctr_status,
      "ctr_intern_status" => $ctr_intern_status,
      "type" => $type,
      "size" => $size,
      "gross" => $gross,
    ];
    // dd($fields);

    $url = getenv('API_URL') . '/delivery-service/container/create';
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
      return redirect('/invoice/container')->with('success', 'Data berhasil disimpan!');
    } else {
      return redirect('/invoice/container')->with('success', 'Data gagal disimpan!');
    }
  }

  public function singleInvoiceForm(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $url_form = getenv('API_URL') . '/delivery-service/invoice/single/' . $id;
    $req_form = $client->get($url_form);
    $response_form = $req_form->getBody()->getContents();
    // var_dump($response_form);
    // die();

    echo $response_form;
  }

  public function singleMasterTarif(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $url_form = getenv('API_URL') . '/delivery-service/mastertarif/sp2/single/' . $id;
    $req_form = $client->get($url_form);
    $response_form = $req_form->getBody()->getContents();
    // var_dump($response_form);
    // die();

    echo $response_form;
  }

  public function updateMasterTarif(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $lokasi_sandar = $request->lokasi_sandar;
    $type = $request->type;
    $size = $request->size;
    $status = $request->status;
    $masa1 = $request->masa1;
    $masa2 = $request->masa2;
    $masa3 = $request->masa3;
    $masa4 = $request->masa4;
    $lift_on = $request->lift_on;
    $lift_off = $request->lift_off;
    $pass_truck = $request->pass_truck;
    $gate_pass_admin = $request->gate_pass_admin;
    $cost_recovery = $request->cost_recovery;
    $surcharge = $request->surcharge;
    $packet_plp = $request->packet_plp;
    $behandle = $request->behandle;
    $recooling = $request->recooling;
    $monitoring = $request->monitoring;
    $administrasi = $request->administrasi;
    $fields =
      [
        "lokasi_sandar" => $lokasi_sandar,
        "type" => $type,
        "size" => $size,
        "status" => $status,
        "masa1" => $masa1,
        "masa2" => $masa2,
        "masa3" => $masa3,
        "masa4" => $masa4,
        "lift_on" => $lift_on,
        "lift_off" => $lift_off,
        "pass_truck" => $pass_truck,
        "gate_pass_admin" => $gate_pass_admin,
        "cost_recovery" => $cost_recovery,
        "surcharge" => $surcharge,
        "packet_plp" => $packet_plp,
        "behandle" => $behandle,
        "recooling" => $recooling,
        "monitoring" => $monitoring,
        "administrasi" => $administrasi,
      ];
    $url = getenv('API_URL') . '/delivery-service/mastertarif/sp2/update/' . $id;
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

  public function createMasterTarif(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $lokasi_sandar = $request->lokasi_sandar;
    $type = $request->type;
    $size = $request->size;
    $status = $request->status;
    $masa1 = $request->masa1;
    $masa2 = $request->masa2;
    $masa3 = $request->masa3;
    $masa4 = $request->masa4;
    $lift_on = $request->lift_on;
    $lift_off = $request->lift_off;
    $pass_truck = $request->pass_truck;
    $gate_pass_admin = $request->gate_pass_admin;
    $cost_recovery = $request->cost_recovery;
    $surcharge = $request->surcharge;
    $packet_plp = $request->packet_plp;
    $behandle = $request->behandle;
    $recooling = $request->recooling;
    $monitoring = $request->monitoring;
    $administrasi = $request->administrasi;
    $fields =
      [
        "lokasi_sandar" => $lokasi_sandar,
        "type" => $type,
        "size" => $size,
        "status" => $status,
        "masa1" => $masa1,
        "masa2" => $masa2,
        "masa3" => $masa3,
        "masa4" => $masa4,
        "lift_on" => $lift_on,
        "lift_off" => $lift_off,
        "pass_truck" => $pass_truck,
        "gate_pass_admin" => $gate_pass_admin,
        "cost_recovery" => $cost_recovery,
        "surcharge" => $surcharge,
        "packet_plp" => $packet_plp,
        "behandle" => $behandle,
        "recooling" => $recooling,
        "monitoring" => $monitoring,
        "administrasi" => $administrasi,
      ];
    $url = getenv('API_URL') . '/delivery-service/mastertarif/sp2/create';
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

  public function VerifyPayment(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $fields =
      [
        "isPaid" => 1,
      ];
    $url = getenv('API_URL') . '/delivery-service/invoice/setPaid/' . $id;
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

  public function VerifyPayment2(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $fields =
      [
        "isPaid" => 1,
      ];
    $url = getenv('API_URL') . '/delivery-service/invoice/setPaid2/' . $id;
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
        "isPiutang" => 1,
      ];
    $url = getenv('API_URL') . '/delivery-service/invoice/setPiutang/' . $id;
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

  public function paymentMethod()
  {
    $data = [];
    $client = new Client();

    // GET ALL PAYMENT METHOD
    $url_payment = getenv('API_URL') . '/delivery-service/payment/all';
    $req_payment = $client->get($url_payment);
    $response_payment = $req_payment->getBody()->getContents();
    $result_payment = json_decode($response_payment);
    // dd($result_payment);

    $data["payments"] = $result_payment->data;
    $data["title"] = "Master Tarif Data";
    return view('invoice/payment/dashboard', $data);
  }

  public function singlePaymentMethod(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $url_form = getenv('API_URL') . '/delivery-service/payment/single/' . $id;
    $req_form = $client->get($url_form);
    $response_form = $req_form->getBody()->getContents();
    // var_dump($response_form);
    // die();

    echo $response_form;
  }

  public function updatePaymentMethod(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    $name = $request->name;
    $bankCode = $request->bankCode;
    $bankNumber = $request->bankNumber;
    $bankName = $request->bankName;
    // $isActive = $request->isActive;
    // var_dump($bankName);
    // die();
    $fields =
      [
        "name" => $name,
        "bankCode" => $bankCode,
        "bankNumber" => $bankNumber,
        "bank" => $bankName,
        // "isActive" => $isActive,

      ];
    // var_dump($fields);
    // die();
    $url = getenv('API_URL') . '/delivery-service/payment/update/' . $id;
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

  public function storePaymentMethod(Request $request)
  {
    $client = new Client();

    // $id = $request->id;
    // var_dump($id);
    // die();
    $name = $request->name;
    $bankCode = $request->bankCode;
    $bankNumber = $request->bankNumber;
    $bankName = $request->bankName;
    // $isActive = $request->isActive;
    $fields =
      [
        "name" => $name,
        "bankCode" => $bankCode,
        "bankNumber" => $bankNumber,
        "bank" => $bankName,
        // "isActive" => $isActive,

      ];
    // var_dump($fields);
    // die();

    $url = getenv('API_URL') . '/delivery-service/payment/create';
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

  public function exportToExcel(Request $request)
  {
    $client = new Client();

    $startDate = $request->input('start');
    $endDate = $request->input('end');

    $fields = [
      "startdate" => $startDate,
      "enddate" => $endDate
    ];

    $url = getenv('API_URL') . '/delivery-service/invoice/activeto';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );

    $response = $req->getBody()->getContents();
    $jsonData = json_decode($response, true)['data'];

    // Manually extract the desired data fields for export
    $exportData = collect($jsonData)->map(function ($item) {
      return [
        'Performa ID' => $item['performaId'],
        'Invoice Number' => $item['invoiceNumber'],
        'Order Service' => $item['orderService'],
        'Job Number' => $item['jobNumber'],
        'Is Paid' => $item['isPaid'],
        'Active To' => $item['active_to'],
        // 'Is Active' => $item['isActive'],
      ];
    });

    // Define the column headings for the Excel sheet
    $headings = [
      'Performa ID',
      'Invoice Number',
      'Order Service',
      'Job Number',
      'Is Paid',
      'Active To',
      // 'Is Active',
    ];

    // Export the data to Excel
    return Excel::download(new DataExport($exportData, $headings), 'data.xlsx');
  }

  public function findContainer(Request $request)
  {
    $client = new Client();
    $do_no = $request->do_no;

    $url = getenv('API_URL') . '/delivery-service/do/groupsingle/' . $do_no;
    $req = $client->get(
      $url
    );
    $response = $req->getBody()->getContents();
    // var_dump($response);
    // die();
    echo $response;
  }

  public function findSingleCustomer(Request $request)
  {
    $client = new Client();
    $id = $request->id;

    $url = getenv('API_URL') . '/delivery-service/customer/single/' . $id;
    $req = $client->get(
      $url
    );
    $response = $req->getBody()->getContents();
    // var_dump($response);
    // die();
    echo $response;
  }

  public function findContainerBooking(Request $request)
  {
    $client = new Client();
    $id = $request->booking;

    $url = getenv('API_URL') . '/delivery-service/container/booking/' . $id;
    $req = $client->get(
      $url
    );
    $response = $req->getBody()->getContents();
    // var_dump($response);
    // die();
    echo $response;
  }

  public function allContainerImport()
  {
    $client = new Client();

    // GET ALL CONTAINER
    // $url_container = getenv('API_URL') . '/container-service/all';
    $url_container = getenv('API_URL') . '/delivery-service/container/all';
    $req_container = $client->get($url_container);
    $response_container = $req_container->getBody()->getContents();
    $result_container = json_decode($response_container);
    // dd($result_container);
    echo $response_container;
  }
}
