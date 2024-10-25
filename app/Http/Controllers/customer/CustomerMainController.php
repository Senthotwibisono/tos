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


class CustomerMainController extends Controller
{
    protected $userId;
    protected $import;
    protected $export;
    protected $extend;
   
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer'); 
        $this->middleware(function ($request, $next) {
            $this->userId = Auth::user()->id; // Ambil user ID di sini
            $this->import = Import::where('user_id', Auth::user()->id)->get();
            $this->export = Export::where('user_id', Auth::user()->id)->get();
            $this->extend = Extend::where('user_id', Auth::user()->id)->get();
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
            $data['dsk'] = $this->import->where('inv_type', '=', 'DSK');
            $dskPaid = $this->import->where('inv_type', '=', 'DSK')->where('lunas', '=', 'Y')->where('lunas', '=', 'C');
            $data['dskPaid'] = $dskPaid->count();
            $data['dskPaidAmount'] = $dskPaid->sum('grand_total');
            $dskUnpaid = $this->import->where('inv_type', '=', 'DSK')->where('lunas', '=', 'N')->where('lunas', '=', 'C');
            $data['dskUnpaid'] = $dskUnpaid->count();
            $data['dskUnpaidAmount'] = $dskUnpaid->sum('grand_total');
            $data['dskTotal'] = $data['dskPaid'] + $data['dskUnpaid'];
            $data['dskTotalAmount'] = $data['dskPaidAmount'] + $data['dskUnpaidAmount'];
            // DS
            $data['ds'] = $this->import->where('inv_type', '=', 'DS');
            $dsPaid = $this->import->where('inv_type', '=', 'DS')->where('lunas', '=', 'Y')->where('lunas', '=', 'C');
            $data['dsPaid'] = $dsPaid->count();
            $data['dsPaidAmount'] = $dsPaid->sum('grand_total');
            $dsUnpaid = $this->import->where('inv_type', '=', 'DS')->where('lunas', '=', 'N')->where('lunas', '=', 'C');
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
            $data['osk'] = $this->export->where('inv_type', '=', 'OSK');
            $oskPaid = $this->export->where('inv_type', '=', 'OSK')->where('lunas', '=', 'Y')->where('lunas', '=', 'C');
            $data['oskPaid'] = $oskPaid->count();
            $data['oskPaidAmount'] = $oskPaid->sum('grand_total');
            $oskUnpaid = $this->export->where('inv_type', '=', 'OSK')->where('lunas', '=', 'N')->where('lunas', '=', 'C');
            $data['oskUnpaid'] = $oskUnpaid->count();
            $data['oskUnpaidAmount'] = $oskUnpaid->sum('grand_total');
            $data['oskTotal'] = $data['oskPaid'] + $data['oskUnpaid'];
            $data['oskTotalAmount'] = $data['oskPaidAmount'] + $data['oskUnpaidAmount'];
            // DS
            $data['os'] = $this->export->where('inv_type', '=', 'OS');
            $osPaid = $this->export->where('inv_type', '=', 'OS')->where('lunas', '=', 'Y')->where('lunas', '=', 'C');
            $data['osPaid'] = $osPaid->count();
            $data['osPaidAmount'] = $osPaid->sum('grand_total');
            $osUnpaid = $this->export->where('inv_type', '=', 'OS')->where('lunas', '=', 'N')->where('lunas', '=', 'C');
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
        $extend = $this->extend->where('lunas', '!=', 'C');
        $data['extend'] = $extend;
        $extendPaid = $extend->where('lunas', '=', 'Y');
        $data['extendPaid'] = $extendPaid->count();
        $data['extendPaidAmount'] = $extendPaid->sum('grand_total');
        $extendUnpaid = $extend->where('lunas', '=', 'N');
        $data['extendUnpaid'] = $extendUnpaid->count();
        $data['extendUnpaidAmount'] = $extendUnpaid->sum('grand_total');

        
        return view('customer.main', $data);
    }

    public function Import()
    {
        $data['title'] = 'Invoice Muat';
        $data['orderService'] = OS::where('ie', '=' , 'I')->orderBy('id', 'asc')->get();

        return view('customer.import.index', $data);
    }
}
