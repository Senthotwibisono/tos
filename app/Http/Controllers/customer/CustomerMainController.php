<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Customer;
use App\Models\MasterUserInvoice as MUI;

use App\Models\InvoiceImport as Import;
use App\Models\InvoiceExport as Export;
use App\Models\Extend as Extend;
use App\Models\OrderService as OS;
use App\Models\InvoiceForm as Form;


class CustomerMainController extends Controller
{
    protected $userId;
    protected $import;
    protected $export;
    protected $extend;
    protected $form;
   
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer'); 
        $this->middleware(function ($request, $next) {
            $this->userId = Auth::user()->id; // Ambil user ID di sini
            $this->import = Import::where('user_id', Auth::user()->id);
            $this->export = Export::where('user_id', Auth::user()->id);
            $this->extend = Extend::where('user_id', Auth::user()->id);
            $this->form = Form::where('user_id', Auth::user()->id);
            return $next($request);
        });

    }
    
    public function dashboardIndex()
    {
        
        $data['user'] = User::find($this->userId);
        $data['title'] = 'Dashboard' . Auth::user()->name;
        $mui = MUI::where('user_id', $this->userId)->get();
        // dd($this->import);
        $customerIds = $mui->pluck('customer_id'); // Mengambil array customer_id

        // Mengambil data customer berdasarkan customer_id dari MUI
        $data['customerList'] = Customer::whereIn('id', $customerIds)->get();

        // Import
            // DSK
            $data['dsk'] = (clone $this->import)->where('inv_type', '=', 'DSK')->get();
            $dskPaid = (clone $this->import)->where('inv_type', '=', 'DSK')->where('lunas', '=', 'Y');
            $data['dskPaid'] =  $dskPaid->count();
            $data['dskPaidAmount'] =  $dskPaid->sum('grand_total');
            $dskUnpaid = (clone $this->import)->where('inv_type', '=', 'DSK')->whereIn('lunas', ['N', 'P']);
            $data['dskUnpaid'] =  $dskUnpaid->count();
            $data['dskUnpaidAmount'] = $dskUnpaid->sum('grand_total');
            $data['dskTotal'] = $data['dskPaid'] + $data['dskUnpaid'];
            $data['dskTotalAmount'] = $data['dskPaidAmount'] + $data['dskUnpaidAmount'];
           

            // DS
            $data['ds'] = (clone $this->import)->where('inv_type', '=', 'DS')->get();
            $dsPaid = (clone $this->import)->where('inv_type', '=', 'DS')->where('lunas', '=', 'Y')->get();
            $data['dsPaid'] = $dsPaid->count();
            $data['dsPaidAmount'] = $dsPaid->sum('grand_total');
            $dsUnpaid = (clone $this->import)->where('inv_type', '=', 'DS')->where('lunas', '=', 'N')->get();
            $data['dsUnpaid'] = $dsUnpaid->count();
            $data['dsUnpaidAmount'] = $dsUnpaid->sum('grand_total');
            $data['dsTotal'] = $data['dsPaid'] + $data['dsUnpaid'];
            $data['dsTotalAmount'] = $data['dsPaidAmount'] + $data['dsUnpaidAmount'];

            // Total
            $data['importPaid'] = $data['dskPaid'] + $data['dsPaid'];
            $data['importPaidAmount'] = $data['dskPaidAmount'] + $data['dsPaidAmount'];
            $data['importUnpaid'] = $data['dskUnpaid'] + $data['dsUnpaid'];
            $data['importUnpaidAmount'] = $data['dskUnpaidAmount'] + $data['dsUnpaidAmount'];
            $data['importTotal'] = $data['dskTotal'] + $data['dsTotal'];
            $data['importTotalAmount'] = $data['dskTotalAmount'] + $data['dsTotalAmount'];

        // Eksport
            // OSK
            $data['osk'] = (clone $this->export)->where('inv_type', '=', 'OSK')->get();
            $oskPaid = (clone $this->export)->where('inv_type', '=', 'OSK')->where('lunas', '=', 'Y')->where('lunas', '=', 'C')->get();
            $data['oskPaid'] = $oskPaid->count();
            $data['oskPaidAmount'] = $oskPaid->sum('grand_total');
            $oskUnpaid = (clone $this->export)->where('inv_type', '=', 'OSK')->where('lunas', '=', 'N')->where('lunas', '=', 'C')->get();
            $data['oskUnpaid'] = $oskUnpaid->count();
            $data['oskUnpaidAmount'] = $oskUnpaid->sum('grand_total');
            $data['oskTotal'] = $data['oskPaid'] + $data['oskUnpaid'];
            $data['oskTotalAmount'] = $data['oskPaidAmount'] + $data['oskUnpaidAmount'];
            // DS
            $data['os'] = (clone $this->export)->where('inv_type', '=', 'OS')->get();
            $osPaid = (clone $this->export)->where('inv_type', '=', 'OS')->where('lunas', '=', 'Y')->where('lunas', '=', 'C')->get();
            $data['osPaid'] = $osPaid->count();
            $data['osPaidAmount'] = $osPaid->sum('grand_total');
            $osUnpaid = (clone $this->export)->where('inv_type', '=', 'OS')->where('lunas', '=', 'N')->where('lunas', '=', 'C')->get();
            $data['osUnpaid'] = $osUnpaid->count();
            $data['osUnpaidAmount'] = $osUnpaid->sum('grand_total');
            $data['osTotal'] = $data['osPaid'] + $data['osUnpaid'];
            $data['osTotalAmount'] = $data['osPaidAmount'] + $data['osUnpaidAmount'];
            // Total
            $data['exportPaid'] = $data['oskPaid'] + $data['osPaid'];
            $data['exportPaidAmount'] = $data['oskPaidAmount'] + $data['osPaidAmount'];
            $data['exportUnpaid'] = $data['oskUnpaid'] + $data['osUnpaid'];
            $data['exportUnpaidAmount'] = $data['oskUnpaidAmount'] + $data['osUnpaidAmount'];
            $data['exportTotal'] = $data['oskTotal'] + $data['osTotal'];
            $data['exportTotalAmount'] = $data['oskTotalAmount'] + $data['osTotalAmount'];

        // Extend
        $extend = (clone $this->extend)->where('lunas', '!=', 'C')->get();
        $data['extend'] = $extend;
        $extendPaid = $extend->where('lunas', '=', 'Y');
        $data['extendPaid'] = $extendPaid->count();
        $data['extendPaidAmount'] = $extendPaid->sum('grand_total');
        $extendUnpaid = $extend->where('lunas', '=', 'N');
        $data['extendUnpaid'] = $extendUnpaid->count();
        $data['extendUnpaidAmount'] = $extendUnpaid->sum('grand_total');
        $data['extendTotalAmount'] = $extend->sum('grand_total');

        
        return view('customer.main', $data);
    }

    public function Import()
    {
        $data['title'] = 'Invoice Bongkar, ' . Auth::user()->name;
        $data['orderService'] = OS::where('ie', '=' , 'I')->orderBy('id', 'asc')->get();

        $data['importTotal'] = (clone $this->import)->count();
        $data['importPaid'] = (clone $this->import)->where('lunas', '=', 'Y')->count();
        $data['importUnpaid'] = (clone $this->import)->whereNotIn('lunas', ['Y', 'C'])->count();
        $data['importCanceled'] = (clone $this->import)->where('lunas', '=', 'C')->count();
        $data['invoice'] = (clone $this->import);

        $data['importUnpaid'] = (clone $this->import)->where('lunas', '=', 'N')->count();
        $data['importUnpaidAmount'] = (clone $this->import)->where('lunas', '=', 'N')->sum('grand_total');
        
        $data['importPiutang'] = (clone $this->import)->where('lunas', '=', 'P')->count();
        $data['importPiutangAmount'] = (clone $this->import)->where('lunas', '=', 'P')->sum('grand_total');

        $data['importCanceled'] = (clone $this->import)->where('lunas', '=', 'C')->count();

        return view('customer.import.index', $data);
    }

    public function Export()
    {
        $data['title'] = 'Invoice Muat, ' . Auth::user()->name;
        $data['orderService'] = OS::where('ie', '=' , 'E')->orderBy('id', 'asc')->get();

        $data['exportTotal'] = (clone $this->export)->count();
        $data['exportPaid'] = (clone $this->export)->where('lunas', '=', 'Y')->count();
        $data['exportUnpaid'] = (clone $this->export)->whereNotIn('lunas', ['Y', 'C'])->count();
        $data['exportCanceled'] = (clone $this->export)->where('lunas', '=', 'C')->count();
        $data['invoice'] = (clone $this->export);

        $data['exportUnpaid'] = (clone $this->export)->where('lunas', '=', 'N')->count();
        $data['exportUnpaidAmount'] = (clone $this->export)->where('lunas', '=', 'N')->sum('grand_total');
        
        $data['exportPiutang'] = (clone $this->export)->where('lunas', '=', 'P')->count();
        $data['exportPiutangAmount'] = (clone $this->export)->where('lunas', '=', 'P')->sum('grand_total');

        $data['exportCanceled'] = (clone $this->export)->where('lunas', '=', 'C')->count();

        return view('customer.export.index', $data);
    }

    public function Extend()
    {
        $data['title'] = 'Invoice Perpanjangan, ' . Auth::user()->name;
        $data['orderService'] = OS::where('ie', '=' , 'X')->orderBy('id', 'asc')->get();

        $data['extendTotal'] = (clone $this->extend)->count();
        $data['extendPaid'] = (clone $this->extend)->where('lunas', '=', 'Y')->count();
        $data['extendUnpaid'] = (clone $this->extend)->whereNotIn('lunas', ['Y', 'C'])->count();
        $data['extendCanceled'] = (clone $this->extend)->where('lunas', '=', 'C')->count();
        $data['invoice'] = (clone $this->extend);

        $data['extendUnpaid'] = (clone $this->extend)->where('lunas', '=', 'N')->count();
        $data['extendUnpaidAmount'] = (clone $this->extend)->where('lunas', '=', 'N')->sum('grand_total');
        
        $data['extendPiutang'] = (clone $this->extend)->where('lunas', '=', 'P')->count();
        $data['extendPiutangAmount'] = (clone $this->extend)->where('lunas', '=', 'P')->sum('grand_total');

        $data['extendCanceled'] = (clone $this->extend)->where('lunas', '=', 'C')->count();

        return view('customer.extend.index', $data);
    }
}
