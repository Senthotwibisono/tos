<?php

namespace App\Http\Controllers\customer\import;

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Http\Request;

use App\Models\InvoiceImport as Import;
use App\Models\OrderService as OS;
use App\Models\InvoiceForm as Form;
use App\Models\ImportDetail as Detail; 
use App\Models\ContainerInvoice as Container;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\MasterUserInvoice as MUI;
use App\Models\Customer;
use App\Models\DOonline;
use App\Models\Item;

use App\Models\JobImport;
use App\Models\VVoyage;

use DataTables;

use Auth;
use Carbon\Carbon;
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

    public function formStoreFirst(Request $request)
    {
        try {
            $mui = MUI::where('user_id', $this->userId)->get();
            if ($mui->isEmpty()) {
                return redirect()->back()->with('error', 'Anda belum memiliki list customer, hubungi admin!!!');
            }

            $oldForm = Form::where('i_e', 'I')
            ->where('user_id', $this->userId)
            ->where('done', 'N')
            ->whereNull('expired_date')
            ->whereNull('os_id')
            ->whereNull('cust_id')
            ->whereNull('do_id')
            ->whereNull('ves_id')
            ->whereNull('disc_date')
            ->first();
            if ($oldForm) {
                return redirect('/customer-import/formFirstStepId='.$oldForm->id)->with('success', 'Continue Your Step');
            }
            $form = Form::create([
                'i_e' => 'I',
                'user_id' => Auth::user()->id,
                'done' => 'N',
            ]);

            // dd($form);
            return redirect('/customer-import/formFirstStepId='.$form->id)->with('success', 'Continue Your Step');
        } catch (\Throwable $th) {
            return redirect( )->back()->with('error', 'Something Wrong!! '. $th->getMessage());
        }
    }

    public function firstStepIndex($id)
    {
        $data['title'] = 'Form Import';
        $data['form'] = Form::find($id);
        $data['orderService'] = OS::where('ie', '=', 'I')->get();
        $data['do_online'] = DOonline::where('active', 'Y')->get();
        $data['ves'] = VVoyage::where('deparature_date', '>=', Carbon::now())->get();
        $data['containerInvoice'] = Container::where('form_id', $id)->get();
        
        $mui = MUI::where('user_id', $this->userId)->get();
        $customerId = $mui->pluck('customer_id')->toArray();
        $data['customer'] = Customer::whereIn('id', $customerId)->get();
        // dd($mui, $customerId, $data['customer']);
        

        return view('customer.import.form.firstStep', $data);
    }
}
