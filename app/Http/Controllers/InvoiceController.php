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
        $url_customer = getenv('API_URL') . '/customer-service/customerAll';
        $req_customer = $client->get($url_customer);
        $response_customer = $req_customer->getBody()->getContents();
        $result_customer = json_decode($response_customer);
        // dd($result_customer);

        $data["customer"] = $result_customer->data;

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
        $url_customer = getenv('API_URL') . '/customer-service/customerAll';
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


        $data["title"] = "Add Customer Data | Data Customer";
        return view('invoice/customer/customeradd', $data);
    }
}
