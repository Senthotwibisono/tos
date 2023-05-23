<?php

namespace App\Http\Controllers;

use Auth;
use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        // dd("Masuk");
        $data = [];
        $data["title"] = "Invoice Page";
        return view('invoice.dashboard', $data);
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
        $url_customer = 'localhost:3002/customer-service/customerAll';
        $req_customer = $client->get($url_customer);
        $response_customer = $req_customer->getBody()->getContents();
        $result_customer = json_decode($response_customer);
        // dd($result_customer);

        // GET ALL CONTAINER
        // $url_container = getenv('API_URL') . '/container-service/all';
        $url_container = 'localhost:3001/container-service/all';
        $req_container = $client->get($url_container);
        $response_container = $req_container->getBody()->getContents();
        $result_container = json_decode($response_container);
        // dd($result_container);

        $data["customer"] = $result_customer->data;
        $data["container"] = $result_container->data;

        return view('invoice/delivery_form/add_step_1', $data);
    }

    public function addDataStep2()
    {
        $data = [];
        $data["title"] = "Step 2 | Delivery Form Review Data";
        return view('invoice/delivery_form/add_step_2', $data);
    }

    public function addDataStep3()
    {
        $data = [];
        $data["title"] = "Step 3 | Delivery Pranota";
        return view('invoice/delivery_form/add_step_3', $data);
    }

    public function customerDashboard()
    {
        $data = [];
        $client = new Client();

        // GET ALL CUSTOMER
        // $url_customer = getenv('API_URL') . '/customer-service/customerAll';
        $url_customer = 'localhost:3002/customer-service/customerAll';
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
        $cust_code = $request->cust_code;
        $cust_phone = $request->cust_phone;
        $cust_fax = $request->cust_fax;
        $cust_npwp = $request->cust_npwp;
        $cust_address = $request->cust_address;

        $fields = [
            "customer_name" => $cust_name,
            "customer_code" => $cust_code,
            "phone" => $cust_phone,
            "fax" => $cust_fax,
            "npwp" => $cust_npwp,
            "address" => $cust_address,
        ];
        // dd($fields);

        $url = getenv('API_URL') . '/customer-service/storeCustomer';
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
        $url_container = 'localhost:3001/container-service/all';
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

        $url = getenv('API_URL') . '/container-service/create';
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
}
