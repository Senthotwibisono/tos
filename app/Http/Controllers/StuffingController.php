<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// use Auth;

use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use App\Http\Controllers\DataExport;
use App\Models\VVoyage;

class StuffingController extends Controller
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

    // GET ALL INVOICE
    // $url_invoice = getenv('API_URL') . '/customer-service/customerAll';
    $url_invoice = getenv('API_URL') . '/delivery-service/invoice/all';
    $req_invoice = $client->get($url_invoice);
    $response_invoice = $req_invoice->getBody()->getContents();
    $result_invoice = json_decode($response_invoice);
    // dd($result_invoice);

    $data["invoices"] = $result_invoice->data;
    $data["title"] = "Export Stuffing Dalam Billing System";
    return view('exportStuffingIn.dashboard', $data);
  }

  public function formStuffing()
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
    $data["title"] = "Export Stuffing Dalam Form Data";
    return view('exportStuffingIn.delivery_form.dashboard', $data);
  }

  public function addDataStep1()
  {
    $user = Auth::user();
    // dd($user->id);
    $data = [];
    $data["title"] = "Step 1 | Export Stuffing Dalam Form Input Data";

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

    // GET ALL BOOKING
    $url_ro = getenv('API_URL') . '/delivery-service/container/ro/all';
    $req_ro = $client->get($url_ro);
    $response_ro = $req_ro->getBody()->getContents();
    $result_ro = json_decode($response_ro);
    // dd($result_ro);
    $data["ro"] = $result_ro->data;


    $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();
    $data["vessel"] = $vessel_voyage;


    $data["customer"] = $result_customer->data;
    $data["container"] = $result_container->data;
    $data["do"] = $result_do->data;
    $data["user"] = $user->id;
    return view('exportStuffingIn/delivery_form/add_step_1', $data);
  }

  public function storeDataStep1(Request $request)
  {
    $data = [];
    $client = new Client();

    $departure = $request->departure;
    $exp_time = $request->exp_time;
    $customer = $request->customer;
    $closingtime = $request->closingtime;
    $do_number = $request->do_number ?? $request->do_number_auto;
    $do_exp_date = $request->do_exp_date;
    $boln = $request->boln;
    $container = $request->container;
    $roNumber = $request->roNumber;
    $ctr = $request->ctr;
    $pod = $request->pod;
    $fpod = $request->fpod;
    $documentNumber = $request->documentNumber;
    $documentType = $request->documentType;
    $documentDate = $request->documentDate;
    // $order_service = $request->order_service;
    $fields = [
      "exp_date" => $departure,
      // "time" => "",
      "customer_id" => $customer,
      "closingtime" => $closingtime,
      "do_number" => "",
      // "do_exp_date" => "",
      "boln" => "",
      "container" => $container,
      "roNumber" => $roNumber,
      // "ctr" => $ctr,
      // "pod" => $pod,
      // "fpod" => $fpod,
      "orderService" => "stuffingIn",
      "documentNumber" => $documentNumber,
      "documentType" => $documentType,
      "documentDate" => $documentDate,

    ];
    // dd($fields, json_encode($fields));
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
      return redirect('/export/stuffing-in/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
      // return redirect('/export/stuffing-in')->with('success', 'Form berhasil disimpan!');
    } else {
      return redirect('/export/stuffing-in')->with('success', 'Data gagal disimpan!');
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
    // dd($fields, json_encode($fields));

    $url = getenv('API_URL') . '/delivery-service/form/calculate/instuffingfirst';
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
    $data["menuinv"] = ["Pass Truck IN", "Pass Truck OUT"];

    // dd($data["menuinv"]);
    $data["title"] = "Step 2 | Export Stuffing Dalam Job Confirm";
    return view('exportStuffingIn/delivery_form/add_step_2', $data);
  }

  public function previewDataJob(Request $request)
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

    $url = getenv('API_URL') . '/delivery-service/form/calculate/instuffingfirst';
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
    $data["menuinv"] = ["Pass Truck IN", "Pass Truck OUT"];

    // dd($data["menuinv"]);
    $data["title"] = "Preview Data | Export Stuffing Dalam Job ";
    return view('exportStuffingIn/delivery_form/preview_step_2', $data);
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
    $orderService = "stuffingIn";
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
      return redirect('/export/stuffing-in')->with('success', 'Invoice berhasil dibuat & disimpan!');
    } else {
      return redirect('/export/stuffing-in')->with('error', 'Data gagal disimpan! kode error : #st2del');
    }
  }

  public function generateIndex()
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
    $data["title"] = "Export Stuffing Dalam Billing System";
    return view('exportStuffingIn.generate.dashboard', $data);
  }

  public function generateStep1()
  {
    $user = Auth::user();
    // dd($user->id);
    $data = [];
    $data["title"] = "Step 1 | Export Stuffing Dalam Form Input Data";

    $client = new Client();
    // GET ALL CUSTOMER
    // $url_customer = getenv('API_URL') . '/customer-service/customerAll';
    $url_customer = getenv('API_URL') . '/delivery-service/customer/all';
    $req_customer = $client->get($url_customer);
    $response_customer = $req_customer->getBody()->getContents();
    $result_customer = json_decode($response_customer);
    // dd($result_customer);

    // GET ALL CONTAINER BY RO
    // $url_container = getenv('API_URL') . '/container-service/all';
    $url_container = getenv('API_URL') . '/delivery-service/container/stuffingIn/all';
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

    // GET ALL RO
    $url_ro = getenv('API_URL') . '/delivery-service/container/ro/all';
    $req_ro = $client->get($url_ro);
    $response_ro = $req_ro->getBody()->getContents();
    $result_ro = json_decode($response_ro);
    // dd($result_ro);
    $data["ro"] = $result_ro->data;



    $vessel_voyage = VVoyage::where('ves_id', '=', $result_container->data[0]->ves_id)->get();
    $data["vessel"] = $vessel_voyage[0];
    // dd($vessel_voyage[0]->clossing_date);


    $data["customer"] = $result_customer->data;
    $data["container"] = $result_container->data;
    $data["do"] = $result_do->data;
    $data["user"] = $user->id;
    return view('exportStuffingIn/generate/delivery_form/add_step_1', $data);
  }

  public function generateStoreStep1(Request $request)
  {
    $data = [];
    $client = new Client();

    $departure = $request->departure;
    $exp_time = $request->exp_time;
    $customer = $request->customer;
    $closingtime = $request->closingtime;
    $do_number = $request->do_number ?? $request->do_number_auto;
    $do_exp_date = $request->do_exp_date;
    $boln = $request->boln;
    $container = $request->container;
    $roNumber = $request->roNumber;
    $ctr = $request->ctr;
    $pod = $request->pod;
    $fpod = $request->fpod;
    $documentNumber = $request->documentNumber;
    $documentType = $request->documentType;
    $documentDate = $request->documentDate;
    // $order_service = $request->order_service;
    $fields = [
      "exp_date" => $departure,
      // "time" => "",
      "customer_id" => $customer,
      "closingtime" => $closingtime,
      "do_number" => "",
      // "do_exp_date" => "",
      "boln" => "",
      "container" => $container,
      "roNumber" => $roNumber,
      // "ctr" => $ctr,
      // "pod" => $pod,
      // "fpod" => $fpod,
      "orderService" => "stuffingInAll",
      "documentNumber" => $documentNumber,
      "documentType" => $documentType,
      "documentDate" => $documentDate,

    ];
    // dd($fields, json_encode($fields));
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
      return redirect('/export/stuffing-in/generate/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
      // return redirect('/export/stuffing-in')->with('success', 'Form berhasil disimpan!');
    } else {
      return redirect('/export/stuffing-in/generate')->with('success', 'Data gagal disimpan!');
    }
  }

  public function generateStep2(Request $request)
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
    // dd($fields, json_encode($fields));

    $url = getenv('API_URL') . '/delivery-service/form/calculate/stuffinginall';
    $req = $client->post(
      $url,
      [
        "json" => $fields
      ]
    );
    $response = $req->getBody()->getContents();
    $result = json_decode($response);
    $data = [];
    // dd($result, $req->getStatusCode());

    if ($result->status == 200 || $result->status == 201) {
      $data["ccdelivery"] = $result->data;
      $data["menuinv"] = ["Lift On Empty", "Lift Off Full", "Penumpukan Masa 1", "Penumpukan Masa 2", "Penumpukan Masa 3",];

      // dd($data["menuinv"]);
      $data["title"] = "Step 2 | Export Stuffing Dalam Job Confirm";
      return view('exportStuffingIn/generate/delivery_form/add_step_2', $data);
    } else {
      return redirect('/export/stuffing-in/generate')->with('success', 'Data gagal disimpan!');
    }
  }

  public function generateStoreStep2(Request $request)
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
    $orderService = "stuffingInAll";
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
      return redirect('/export/stuffing-in/generate')->with('success', 'Invoice berhasil dibuat & disimpan!');
    } else {
      return redirect('/export/stuffing-in/generate')->with('error', 'Data gagal disimpan! kode error : #st2del');
    }
  }
}
