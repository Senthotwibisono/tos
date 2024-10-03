<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Item;
use App\Models\OrderService;
use Carbon\Carbon;
use App\Models\User;
use GuzzleHttp\Client;
use Auth;

class GateRelokasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Gate Rlokasi';
        $item = Item::whereIn('ctr_intern_status', ['11', '15'])
        ->orWhere(function($query) {
            $query->whereiN('ctr_intern_status', ['09', '49'])
                  ->whereHas('service', function($query) {
                      $query->where('return_yn', 'Y')
                            ->whereNotIn('order', ['MTI', 'MTK']);
                  });
        })->get();
            $item_confirmed = Item::whereIn('ctr_intern_status',  ['12', '13', '14'])->orderBy('truck_in_date', 'desc')->get();

      
        return view('gate.relokasi.main', compact('item', 'title', 'item_confirmed'));
    }
    public function android()
    {
        $title = 'Gate Rlokasi';
        $item = Item::whereIn('ctr_intern_status', ['11', '15'])
        ->orWhere(function($query) {
            $query->whereIn('ctr_intern_status', ['09', '49'])
                  ->whereHas('service', function($query) {
                      $query->where('return_yn', 'Y')
                            ->whereNotIn('order', ['MTI', 'MTK']);
                  });
        })->get();
            $item_confirmed = Item::whereIn('ctr_intern_status',  ['12', '13', '14'])->orderBy('truck_in_date', 'desc')->get();

        return view('gate.relokasi.android', compact('item', 'title', 'item_confirmed'));
    }

    public function data_container(Request $request)
    {
       $key = $request->container_key;
       $item = Item::with('service')->where('container_key', $key)->first();
       if ($item) {
        return response()->json([
            'success' => true,
            'message' => 'Silahkan Menuju Bagian Placement',
            'data'    => $item,
        ]);
       }else {
        return response()->json([
            'success' => false,
            'message' => 'Silahkan Menuju Bagian Placement',
        ]);
       }
    }

    public function permit(Request $request)
    {
        $container_key = $request->container_key;
        $item = Item::where('container_key', $container_key)->first();
        $now = Carbon::now();

        if ($item) {
            $service = $item->order_service;
            if ($item->ves_id == "PELINDO") {
                if ($service === 'SPPS') {
                    $item->update([
                        'ctr_intern_status' => '12',
                        'order_service' => $request->order_service,
                        'no_dok' => $request->no_dok,
                        'jenis_dok' => $request->jenis_dok,
                        'truck_no' => $request->truck_no,
                        'job_no' => $request->job_no,
                        'invoice_no' => $request->invoice_no,
                        'truck_in_date'=>$now,
                    ]);
                  
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Silahkan Menuju Bagian Placement',
                            'data'    => $item,
                        ]);
    
                    // SP2 RELOKASI
                } elseif ($service === 'SP2') {
                    $item->update([
                        'ctr_intern_status' => '13',
                        'order_service' => $request->order_service,
                        'no_dok' => $request->no_dok,
                        'jenis_dok' => $request->jenis_dok,
                        'truck_no' => $request->truck_no,
                        'job_no' => $request->job_no,
                        'invoice_no' => $request->invoice_no,
                        'truck_in_date'=>$now,
                    ]);
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Silahkan Menuju Bagian Placement',
                            'data'    => $item,
                        ]);
                  
                }
            }else {
                if ($request->depo_return != null) {
                    $item->update([
                        'ctr_intern_status' => '14',
                        'ctr_status' => 'MTY',
                        'ctr_active_yn' => 'Y',
                        'truck_no' => $request->truck_no,
                        'truck_in_date'=>$now,
                    ]);
                    
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Silahkan Menuju Bagian Placement',
                            'data'    => $item,
                        ]);
                   
    
                    // SPPS RELOKASI
                } 
            }
            
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal, Terjadi Kesalahan',
            ]);
        }
    }
}
