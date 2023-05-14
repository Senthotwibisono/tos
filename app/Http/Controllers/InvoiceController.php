<?php

namespace App\Http\Controllers;

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
        $data["title"] = "Dashboard | Data Customer";
        return view('invoice/customer/dashboard', $data);
    }
}
