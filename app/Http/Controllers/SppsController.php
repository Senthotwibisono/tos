<?php

namespace App\Http\Controllers;

use Auth;
use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SppsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    // dd("Masuk");
    $client = new Client();

    $data = [];

    // // GET ALL INVOICE
    // // $url_invoice = getenv('API_URL') . '/customer-service/customerAll';
    $url_invoice = getenv('API_URL') . '/delivery-service/spps/all';
    $req_invoice = $client->get($url_invoice);
    $response_invoice = $req_invoice->getBody()->getContents();
    $result_invoice = json_decode($response_invoice);
    // dd($result_invoice->data);

    $data["invoices"] = $result_invoice->data;
    $data["title"] = "Spps Dashboard Page";
    return view('spps.dashboard', $data);
  }

  public function deliveryForm()
  {
    $data = [];
    $client = new Client();

    //commited changes

    // GET ALL FORM
    $url_delivery = getenv('API_URL') . '/delivery-service/form/all';
    $req_delivery = $client->get($url_delivery);
    $response_delivery = $req_delivery->getBody()->getContents();
    $result_delivery = json_decode($response_delivery);
    // dd($result_delivery);

    $data["deliveries"] = $result_delivery->data;
    $data["title"] = "Delivery Form Data";
    return view('spps/delivery_form/dashboard', $data);
  }

  public function masterTarif()
  {
    $data = [];
    $client = new Client();

    // GET ALL FORM
    $url_master_tarif = getenv('API_URL') . '/delivery-service/mastertarif/spps/all';
    $req_master_tarif = $client->get($url_master_tarif);
    $response_master_tarif = $req_master_tarif->getBody()->getContents();
    $result_master_tarif = json_decode($response_master_tarif);
    // dd($result_master_tarif);

    $data["mastertarif"] = $result_master_tarif->data;
    $data["title"] = "SPPS Master Tarif Data";
    return view('spps/master_tarif/dashboard', $data);
  }


  public function addMasterTarif()
  {
    $data = [];
    $client = new Client();

    $data["title"] = "Tambah Master Tarif Data";
    return view('spps/master_tarif/add', $data);
  }

  public function editMasterTarif(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $url_form = getenv('API_URL') . '/delivery-service/mastertarif/spps/single/' . $id;
    $req_form = $client->get($url_form);
    $response_form = $req_form->getBody()->getContents();
    $result_master_tarif = json_decode($response_form);

    $data["mastertarif"] = $result_master_tarif->data;
    $data["title"] = "Edit Master Tarif Data";

    return view('spps/master_tarif/edit', $data);
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
    $paket_stripping = $request->paket_stripping;
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
        "paket_stripping" => $paket_stripping,
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
    $url = getenv('API_URL') . '/delivery-service/mastertarif/spps/update/' . $id;
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    // dd($response);
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/spps/mastertarif')->with('success', 'Data berhasil disimpan!');
    } else {
      return redirect('/spps/mastertarif')->with('success', 'Data gagal disimpan!');
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
    $paket_stripping = $request->paket_stripping;
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
        "paket_stripping" => $paket_stripping,
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
    $url = getenv('API_URL') . '/delivery-service/mastertarif/spps/create';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
      return redirect('/spps/mastertarif')->with('success', 'Data berhasil disimpan!');
    } else {
      return redirect('/spps/mastertarif')->with('success', 'Data gagal disimpan!');
    }
  }

  public function Pranota(Request $request)
  {
    $data = [];
    $client = new Client();

    $id_invoice = $request->id;
    // dd($id_invoice);

    // GET SINGLE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/spps/single/' . $id_invoice;
    $req_single_invoice = $client->get($url_single_invoice);
    $response_single_invoice = $req_single_invoice->getBody()->getContents();
    $result_single_invoice = json_decode($response_single_invoice);
    // dd($result_single_invoice);

    $data["invoices"] = $result_single_invoice->data;
    $data["title"] = "Pranota Data";
    return view('spps/pranota/index', $data);
  }

  public function PaidInvoice(Request $request)
  {
    $data = [];
    $client = new Client();

    $id_invoice = $request->id;
    // dd($id_invoice);
    //commited
    // GET SINGLE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/spps/single/' . $id_invoice;
    $req_single_invoice = $client->get($url_single_invoice);
    $response_single_invoice = $req_single_invoice->getBody()->getContents();
    $result_single_invoice = json_decode($response_single_invoice);
    // dd($result_single_invoice);

    $data["invoices"] = $result_single_invoice->data;
    $data["title"] = "Invoice " . $result_single_invoice->data->invoice->invoiceNumber;
    return view('spps/spps/index', $data);
  }

  public function jobPage(Request $request)
  {
    $client = new Client();

    $id_invoice = $request->id;
    // dd($id_invoice);
    //commited
    // GET SINGLE INVOICE FORM
    $url_single_invoice = getenv('API_URL') . '/delivery-service/spps/single/' . $id_invoice;
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
    return view('spps/jobPage/index', $data, compact('qrcodes'));
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

    $data["customer"] = $result_customer->data;
    $data["container"] = $result_container->data;

    return view('spps/delivery_form/add_step_1', $data);
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

    return view('spps/delivery_form/update_step_1', $data);
  }

  public function storeDataStep1(Request $request)
  {
    $data = [];
    $client = new Client();

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
      "customer_id" => $customer,
      "do_number" => $do_number,
      "do_exp_date" => $do_exp_date,
      "boln" => $boln,
      "container" => $container,
      "orderService" => "spps",

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
      return redirect('/spps/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
    } else {
      return redirect('/spps')->with('success', 'Data gagal disimpan!');
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
      "orderService" => "spps",

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
      return redirect('/spps/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan! Silahkan preview sebelum melakukan perhitungan!');
    } else {
      return redirect('/spps')->with('success', 'Data gagal disimpan!');
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

    $url = getenv('API_URL') . '/delivery-service/form/calculate/spps';
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
    $data["menuinv"] = ["Paket Stripping", "Pass Truck", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3"];
    $data["title"] = "Step 2 | Delivery Pranota";
    return view('spps/delivery_form/add_step_2', $data);
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
    // $data7 = $request->input('data7');

    $fields = [
      "data1" => $data1,
      "data2" => $data2,
      "data3" => $data3,
      "data4" => $data4,
      "data5" => $data5,
      "data6" => $data6,
      // "data7" => $data7,
      "orderService" => "spps",
    ];
    // dd($fields);

    $url = getenv('API_URL') . '/delivery-service/spps/create';
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
      return redirect('/spps')->with('success', 'spps berhasil dibuat & disimpan!');
    } else {
      return redirect('/spps')->with('error', 'Data gagal disimpan! kode error : #st2del');
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
    return view('spps/customer/dashboard', $data);
  }

  public function addDataCustomer()
  {
    $data = [];
    $client = new Client();

    $data["title"] = "Add Customer Data | Data Customer";
    return view('spps/customer/customeradd', $data);
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
      return redirect('/spps/customer')->with('success', 'Data berhasil disimpan!');
    } else {
      return redirect('/spps/customer')->with('success', 'Data gagal disimpan!');
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
    return view('spps/container/dashboard', $data);
  }

  public function addDataContainer()
  {
    $data = [];
    $client = new Client();

    $data["title"] = "Add Container Data | Data Container";
    return view('spps/container/containeradd', $data);
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
      return redirect('/spps/container')->with('success', 'Data berhasil disimpan!');
    } else {
      return redirect('/spps/container')->with('success', 'Data gagal disimpan!');
    }
  }

  public function singleInvoiceForm(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $url_form = getenv('API_URL') . '/delivery-service/spps/single/' . $id;
    $req_form = $client->get($url_form);
    $response_form = $req_form->getBody()->getContents();
    // var_dump($response_form);
    // die();

    echo $response_form;
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
    $url = getenv('API_URL') . '/delivery-service/spps/setPaid/' . $id;
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
    $url = getenv('API_URL') . '/delivery-service/spps/setPiutang/' . $id;
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

  public function singleMasterTarif(Request $request)
  {
    $client = new Client();

    $id = $request->id;
    // var_dump($id);
    // die();
    $url_form = getenv('API_URL') . '/delivery-service/mastertarif/spps/single/' . $id;
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
    $url = getenv('API_URL') . '/delivery-service/mastertarif/spps/update/' . $id;
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
    $url = getenv('API_URL') . '/delivery-service/mastertarif/spps/create';
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
}
