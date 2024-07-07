<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\Customer;
use App\Models\Item;
use App\Models\KodeDok;
use App\Models\RO;
use App\Models\VVoyage;
use App\Models\InvoiceExport;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\ExportDetail as Detail;
use App\Models\JobExport;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Exports\ReportInvoice;

class RentalController extends Controller
{
    //
}
