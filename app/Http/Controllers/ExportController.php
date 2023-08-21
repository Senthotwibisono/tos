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
use App\Models\VVoyage;



class ExportController extends Controller
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
        $data["title"] = "Receiving | Billing System";
        return view('export.dashboard', $data);
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
        $data["title"] = "Receiving Form Data";
        return view('export/delivery_form/dashboard', $data);
    }

    public function addDataStep1()
    {
        $data = [];
        $data["title"] = "Step 1 | Export Delivery Form Input Data";

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

        // GET ALL VESSEL
        // $url_vessel = getenv('API_URL') . '/delivery-service/vessel/all';
        // $req_vessel = $client->get($url_vessel);
        // $response_vessel = $req_vessel->getBody()->getContents();
        // $result_vessel = json_decode($response_vessel);

        $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();

        // dd($result_vessel);

        // GET ALL BOOKING
        $url_booking = getenv('API_URL') . '/delivery-service/container/booking/all';
        $req_booking = $client->get($url_booking);
        $response_booking = $req_booking->getBody()->getContents();
        $result_booking = json_decode($response_booking);
        // dd($result_booking);
        $data["booking"] = $result_booking->data;

        $data["customer"] = $result_customer->data;
        $data["vessel"] = $vessel_voyage;
        $data["container"] = $result_container->data;
        $data["do"] = $result_do->data;
        return view('export/delivery_form/add_step_1', $data);
    }

    public function updateDataStep1(Request $request)
    {
        $data = [];
        $data["title"] = "Update Data Step 1 | Receiving Form Update Data";

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

        return view('export/delivery_form/update_step_1', $data);
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
        $bookingno = $request->booking;
        $ctr = $request->ctr;
        $pod = $request->pod;
        $fpod = $request->fpod;
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
            "booking_no" => $bookingno,
            // "ctr" => $ctr,
            // "pod" => $pod,
            // "fpod" => $fpod,
            "orderService" => "export",
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
            return redirect('/export/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
        } else {
            return redirect('/export')->with('success', 'Data gagal disimpan!');
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
            return redirect('/export/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan! Silahkan preview sebelum melakukan perhitungan!');
        } else {
            return redirect('/export')->with('success', 'Data gagal disimpan!');
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

        $url = getenv('API_URL') . '/delivery-service/form/calculate/export';
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
            if ($result->data->deliveryForm->orderService == "sp2") {
                $data["menuinv"] = [$isExtended != 1 ? "Lift On" : "", $isExtended != 1 ? "Pass Truck" : "", $diffInDays->masa1 > 0 ? "Penumpukan Masa 1" : "", $diffInDays->masa2 > 0 ? "Penumpukan Masa 2" : "", $diffInDays->masa3 > 0 ? "Penumpukan Masa 3" : ""];
            } else if ($result->data->deliveryForm->orderService == "spps") {
                $data["menuinv"] = [$isExtended != 1 ? "Paket Stripping" : "", $isExtended != 1 ? "Pass Truck" : "", $diffInDays->masa1 > 0 ? "Penumpukan Masa 1" : "Penumpukan Masa 1", $diffInDays->masa2 > 0 ? "Penumpukan Masa 2" : "Penumpukan Masa 2", $diffInDays->masa3 > 0 ? "Penumpukan Masa 3" : "Penumpukan Masa 3"];
            } else if ($result->data->deliveryForm->orderService == "export") {
                $data["menuinv"] = [$isExtended != 1 ? "Lift Off" : "", $isExtended != 1 ? "Pass Truck" : "", $diffInDays->masa1 > 0 ? "Penumpukan Masa 1" : "Penumpukan Masa 1", $diffInDays->masa2 > 0 ? "Penumpukan Masa 2" : "Penumpukan Masa 2", $diffInDays->masa3 > 0 ? "Penumpukan Masa 3" : "Penumpukan Masa 3"];
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
        $data["title"] = "Step 2 | Receiving Pranota";
        return view('export/delivery_form/add_step_2', $data);
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
            return redirect('/export')->with('success', 'Invoice berhasil dibuat & disimpan!');
        } else {
            return redirect('/export')->with('error', 'Data gagal disimpan! kode error : #st2del');
        }
    }

    public function indexStuffing()
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
        $data["title"] = "Export Stuffing | Billing System";
        return view('export.dashboardStuffing', $data);
    }

    public function deliveryFormStuffing()
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
        $data["title"] = "Export Stuffing Form Data";
        return view('export/stuffing/dashboard', $data);
    }

    public function addDataStepStuffing1()
    {
        $data = [];
        $data["title"] = "Step 1 | Export Stuffing Form Input Data";

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
        // $url_do = getenv('API_URL') . '/delivery-service/do/groupall';
        // $req_do = $client->get($url_do);
        // $response_do = $req_do->getBody()->getContents();
        // $result_do = json_decode($response_do);
        // dd($result_do);

        // GET ALL VESSEL
        // $url_vessel = getenv('API_URL') . '/delivery-service/vessel/all';
        // $req_vessel = $client->get($url_vessel);
        // $response_vessel = $req_vessel->getBody()->getContents();
        // $result_vessel = json_decode($response_vessel);
        // dd($result_vessel);
        $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();


        // GET ALL BOOKING
        $url_booking = getenv('API_URL') . '/delivery-service/container/booking/all';
        $req_booking = $client->get($url_booking);
        $response_booking = $req_booking->getBody()->getContents();
        $result_booking = json_decode($response_booking);
        // dd($result_booking);

        $data["booking"] = $result_booking->data;
        $data["customer"] = $result_customer->data;
        $data["vessel"] = $vessel_voyage;
        $data["container"] = $result_container->data;
        // $data["do"] = $result_do->data;
        return view('export/stuffing/add_step_1', $data);
    }

    public function storeDataStepStuffing1(Request $request)
    {
        $data = [];
        $client = new Client();

        $departure = $request->departure;
        $exp_time = $request->exp_time;
        $closingtime = $request->closingtime;
        $customer = $request->customer;
        $do_number = $request->do_number ?? $request->do_number_auto;
        $do_exp_date = $request->do_exp_date;
        $boln = $request->boln;
        $container = $request->container;
        // $bookingno = $request->booking;
        // $ctr = $request->ctr;
        // $pod = $request->pod;
        // $fpod = $request->fpod;
        // $order_service = $request->order_service;
        $fields = [
            "exp_date" => $departure,
            // "time" => "",
            "closingtime" => $closingtime,
            "customer_id" => $customer,
            "do_number" => "",
            // "do_exp_date" => "",
            "boln" => "",
            "container" => $container,
            // "booking_no" => $bookingno,
            // "ctr" => $ctr,
            // "pod" => $pod,
            // "fpod" => $fpod,
            "orderService" => "stuffing",
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
            return redirect('/export/stuffing/add/step2?id=' . $result->data->id)->with('success', 'Form berhasil disimpan!');
        } else {
            return redirect('/export/stuffing')->with('success', 'Data gagal disimpan!');
        }
    }

    public function addDataStepStuffing2(Request $request)
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

        $url = getenv('API_URL') . '/delivery-service/form/calculate/stuffing';
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
        $data["menuinv"] = ["Lift On Empty", ""];
        $data["menuinv2"] = ["Lift Off Full", "Pass Truck", "Penumpukan Masa 1"];
        $data["isExtended"] = $isExtended;
        $data["diffInDays"] = $diffInDays;
        $data["title"] = "Step 2 | Stuffing Pranota";
        return view('export/stuffing/add_step_2', $data);
    }
    public function storeDataStepStuffing2(Request $request)
    {
        $data = [];
        $client = new Client();


        $data1 = $request->input('data1');
        $data2 = $request->input('data2');
        $data3 = $request->input('data3');
        $data4 = $request->input('data4');
        $data5 = $request->input('data5');
        $data6 = $request->input('data6');
        $data7 = $request->input('data7');
        $data8 = $request->input('data8');
        $isExtended = $request->input('isExtended');
        $active_to = $request->input('active_to');
        $orderService = $request->input('orderService');
        $fields = [
            "data1" => $data1,
            "data2" => $data2,
            "data3" => $data3,
            "data4" => $data4,
            "data5" => $data5,
            "data6" => $data6,
            "data7" => $data7,
            "data8" => $data8,
            "isExtended" => $isExtended,
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
            return redirect('/export/stuffing')->with('success', 'Invoice berhasil dibuat & disimpan!');
        } else {
            return redirect('/export/stuffing')->with('error', 'Data gagal disimpan! kode error : #st2del');
        }
    }

    public function Pranota1(Request $request)
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
        return view('/export/stuffing/pranota/index', $data);
    }

    public function PaidInvoice1(Request $request)
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
        return view('export/stuffing/paid_invoice/index1', $data);
    }

    public function PaidInvoice2(Request $request)
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
        $data["title"] = "Invoice " . $result_single_invoice->data->invoice->invoiceNumber2;
        return view('export/stuffing/paid_invoice/index2', $data);
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
}
