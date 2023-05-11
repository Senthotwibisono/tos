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
}
