<?php

namespace App\Http\Controllers\customer\extend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\InvoiceImport as Import;
use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\InvoiceForm as Form;
use App\Models\ImportDetail as Detail; 
use App\Models\ContainerInvoice as Container;
use App\Models\MasterUserInvoice as MUI;
use App\Models\Customer;
use App\Models\DOonline;
use App\Models\Item;

use App\Models\JobImport;
use App\Models\VVoyage;

use DataTables;

use Auth;
use Carbon\Carbon;

class CustomerExtendController extends CustomerMainController
{
    public function formList()
    {
        $data['title'] = "Form Management || Perpanjangan";

        return view('customer.extend.form.listIndex', $data);
    }

    public function formData(Request $request)
    {
        $form = $this->form->with(['customer', 'Kapal', 'doOnline', 'service'])->where('i_e', 'X')->where('done', '=', 'N');
        return DataTables::of($form)->make(true);
    }

    public function formStoreFirst(Request $request)
    {
        try {
            $mui = MUI::where('user_id', $this->userId)->get();
            if ($mui->isEmpty()) {
                return redirect()->back()->with('error', 'Anda belum memiliki list customer, hubungi admin!!!');
            }

            $oldForm = Form::where('i_e', 'X')
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
                return redirect('/customer-extend/formFirstStepId='.$oldForm->id)->with('success', 'Continue Your Step');
            }
            $form = Form::create([
                'i_e' => 'X',
                'user_id' => Auth::user()->id,
                'done' => 'N',
            ]);

            // dd($form);
            return redirect('/customer-extend/formFirstStepId='.$form->id)->with('success', 'Continue Your Step');
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

        $data['expired'] = Carbon::now()->addDays(4)->format('Y-m-d');
        // dd($data['expired']);
        // dd($mui, $customerId, $data['customer']);
        

        return view('customer.extend.form.firstStep', $data);
    }

    public function getOldInvoice(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = 5; // Jumlah item per halaman

        $query = $this->import
            ->where('lunas', 'Y')
            ->where('inv_type', 'DS')
            ->select('inv_no', 'id', 'form_id')
            ->groupBy('inv_no', 'id', 'form_id'); // Hindari distinct() dengan groupBy

        if ($search) {
            $query->where('inv_no', 'like', "%{$search}%");
        }

        // Hitung total data untuk pagination
        $totalCount = $query->count();
        $oldInvoice = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        return response()->json([
            'data' => $oldInvoice,
            'more' => ($page * $perPage) < $totalCount, // Cek apakah ada halaman berikutnya
        ]);
    }

    public function oldInvoiceData(Request $request)
    {
        var_dump($request->all());
    }

}
