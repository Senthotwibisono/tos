<?php

namespace App\Http\Controllers\customer\import;

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Http\Request;

use App\Models\InvoiceImport as Import;
use App\Models\OrderService as OS;
use App\Models\InvoiceForm as Form;
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
        // var_dump($request->osId);
        // die();
        $unpaids = $this->import->with(['customer', 'service', 'form'])->where('lunas', 'P'); // Removed `query()`
        return DataTables::of($unpaids)->make(true);
    }
    
    public function indexService(Request $request)
    {
        $os = OS::find($request->id);
        $data['title'] = 'Import '.$os->name.' List'; 

        $data['osId'] = $os->id;
        return view('customer.import.detil.service', $data);
    }

    public function dataService(Request $request)
    {
        $unpaids = $this->import->with(['customer', 'service', 'form'])->where('os_id', $request->osId)->where('lunas', 'P'); // Removed `query()`
        return DataTables::of($unpaids)->make(true);
    }

    public function formList()
    {
        $data['title'] = "Form Management || Import";

        return view('customer.import.form.listIndex', $data);
    }

    public function formData()
    {
        $form = Form::with(['customer', 'Kapal', 'doOnline', 'service'])->where('i_e', 'I')->where('done', '=', 'N');
        return DataTables::of($form)->make(true);
    }
}
