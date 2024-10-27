<?php

namespace App\Http\Controllers\customer\import;

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Http\Request;

use App\Models\InvoiceImport as Import;
use DataTables;
class CustomerImportController extends CustomerMainController
{
    public function indexUnpaid()
    {
        $data['title'] = 'Import Unpaid List';

        return view('customer.import.detil.unpaid', $data);
    }

    public function dataUnpaid(Request $request)
    {
        $unpaids = $this->import->with(['customer', 'service', 'form'])->where('lunas', 'N'); // Removed `query()`
        return DataTables::of($unpaids)->make(true);
    }

    public function indexPiutang()
    {
        $data['title'] = 'Import Piutang List';

        return view('customer.import.detil.piutang', $data);
    }

    public function dataPiutang(Request $request)
    {
        $unpaids = $this->import->with(['customer', 'service', 'form'])->where('lunas', 'P'); // Removed `query()`
        return DataTables::of($unpaids)->make(true);
    }
}
