<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\VMaster;
use App\Models\VVoyage;
use App\Models\Item;
use App\Models\InvoiceImport;
use App\Models\JobImport;
use App\Models\Extend;
use App\Models\JobExtend;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
class ServiceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function indexImport()
    {
        $data['title'] = 'Seraching History Invoice';

        $data['vessels'] = VMaster::get();
        return view('billingSystem.service.tracking.index', $data);
    }

    public function contInvoiceData(Request $request)
    {
        // var_dump($request->items);
        $items = Item::whereIn('container_key', $request->items)->get();
        // var_dump($items);
        return dataTables::of($items)
        ->make(true);
    }

    public function contInvoiceDataDetil(Request $request)
    {
        // var_dump($request->container_key);
        // die();
        $containers = Container::where('container_key', $request->container_key)->get();
        $formImport = Form::where('i_e', 'I')->where('done', 'Y')->whereIn('id', $containers->pluck('form_id'))->get();
        $import = InvoiceImport::whereIn('form_id', $formImport->pluck('id'))->whereIn('lunas', ['P', 'Y'])->get();
        $jobImport = JobImport::whereIn('inv_id', $import->pluck('id'))->where('container_key', $request->container_key)->get();
        
        $formExted = Form::where('i_e', 'X')->where('done', 'Y')->whereIn('id', $containers->pluck('form_id'))->get();
        $extend = Extend::whereIn('form_id', $formExted->pluck('id'))->whereIn('lunas', ['P', 'Y'])->get();
        $jobExtend = JobExtend::whereIn('inv_id', $extend->pluck('id'))->where('container_key', $request->container_key)->get();

        $combinedJobs = $jobImport->concat($jobExtend)->values();

        return dataTables::of($combinedJobs)
        ->addColumn('invoice_no', function($combinedJobs){
            return $combinedJobs->Invoice->inv_no;
        })
        ->addColumn('pranota', function($combinedJobs){
            return $combinedJobs->Invoice->proforma_no;
        })
        ->addColumn('job', function($combinedJobs){
            return $combinedJobs->job_no;
        })
        ->make(true);

    }

    public function jadwalKapal()
    {
        $data['vessels'] = VVoyage::where('clossing_date','>=', Carbon::now())->get();
        return view('billingSystem.service.customer.jadwalKapal', $data);
    }
}
