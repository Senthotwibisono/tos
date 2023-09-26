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
}
