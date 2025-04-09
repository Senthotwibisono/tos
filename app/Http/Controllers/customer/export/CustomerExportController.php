<?php

namespace App\Http\Controllers\customer\export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Support\Facades\DB;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\VVoyage;
use App\Models\Item;
use App\Models\Isocode;
use App\Models\Port;

use App\Models\Customer;
use App\Models\MasterUserInvoice as MUI;

use App\Models\InvoiceExport as Export;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\ExportDetail as Detail;
use App\Models\JobExport;

class CustomerExportController extends  CustomerMainController
{
    public function indexDetail()
    {

        $data['title'] = 'Invoice Muat';

        return view('customer.export.detil', $data);
    }

    public function dataDetail(Request $request)
    {
        $inv = $this->export->with(['customer', 'service', 'form']);
        if ($request->has('status')) {
            $inv = ($request->status == 'N') ? $this->export->with(['customer', 'service', 'form'])->where('lunas', 'N') : $this->export->with(['customer', 'service', 'form'])->where('lunas', 'P');
        }

        $inv = $inv->get();

        return DataTables::of($inv)
        ->addColumn('proforma', function($inv) {
            return $inv->proforma_no ?? '-';
        })
        ->addColumn('customer', function($inv){
            return $inv->cust_name ?? '-';
        })
        ->addColumn('service', function($inv){
            return $inv->os_name ?? '-';
        })
        ->addColumn('type', function($inv){
            return $inv->inv_type ?? '-';
        })
        ->addColumn('orderAt', function($inv){
            return $inv->order_at ?? '-';
        })
        ->addColumn('status', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-danger text-white">Not Paid</span>';
            }elseif ($inv->lunas == 'P') {
                return '<span class="badge bg-warning text-white">Piutang</span>';
            }elseif ($inv->lunas == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('pranota', function($inv){
            return '<a type="button" href="/pranota/export-'.$inv->inv_type.$inv->id.'" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>';
        })
        ->addColumn('invoice', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/export-'.$inv->inv_type.$inv->id.'" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>';
            }
        })
        ->addColumn('job', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/job/export-'.$inv->id.'" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>';
            }
        })
        ->addColumn('action', function($inv){
            if ($inv->lunas == 'N' || $inv->lunas == 'P') {
                return '<button type="button" id="pay" data-id="'.$inv->id.'" class="btn btn-sm btn-success pay"><i class="fa fa-cogs"></i></button>';
            }elseif ($inv->lunas == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }else {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('payFlag', function($inv){
            if ($inv->lunas == 'N') {
                if ($inv->pay_flag == 'Y') {
                    return '<div class="spinner-border text-primary" role="status">
                            
                        </div> <span class="">Waiting Approved</span>';
                }elseif ($inv->pay_flag == 'C') {
                    return '<span class="badge bg-danger text-white">Di Tolak</span>';
                }else {
                    return '-';
                }
            }else {
                return '-';
            }
        })
        ->addColumn('delete', function($inv){
            if ($inv->lunas == 'N') {
                return '<button type="button" data-id="'.$inv->form_id.'" class="btn btn-sm btn-danger Delete"><i class="fa fa-trash"></i></button>';
            }else {
                return '-';
            }
        })
        ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'delete', 'payFlag'])
        ->make(true);
    }

    public function indexForm()
    {
        $data['title'] = "Form Index";

        return view('customer.export.form.index', $data);
    }

    public function dataForm(Request $request)
    {
        $form = Form::where('i_e', 'E')->where('done', '=', 'N')->get();
        
        return DataTables::of($form)
        ->addColumn('customer', function($form){
            return $form->customer->name ?? '-';
        })
        ->addColumn('service', function($form){
            return $form->service->name ?? '-';
        })
        ->addColumn('edit', function($form){
            return '<a href="" class="btn btn-warning"><i class="fas fa-pencil"></i></a>';
        })
        ->addColumn('delete', function($form){
            return '<button type="button" class="btn btn-danger" onClick="deleteButton(event)" data-id="'.$form->id.'"><i class="fas fa-trash"></i></button>';
        })
        ->rawColumns(['edit', 'delete'])
        ->make(true);
    }
    
}
