<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Item;
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
        $item = Item::whereIn('order_service', ['SP2IKS', 'SP2RELOKASI', 'SPPSRELOKASI'])->whereIn('ctr_intern_status',  ['09', '11', '15'])->get();
        $item_confirmed = Item::whereiN('ctr_intern_status',  ['12', '13', '14'])->get();

      
        return view('gate.relokasi.main', compact('item', 'title', 'item_confirmed'));
    }
    public function android()
    {
        $title = 'Gate Rlokasi';
        $item = Item::whereIn('order_service', ['SP2IKS', 'SP2RELOKASI', 'SPPSRELOKASI'])->whereIn('ctr_intern_status',  ['11', '15'])->get();
        $item_confirmed = Item::whereiN('ctr_intern_status',  ['12', '13'])->get();

        return view('gate.relokasi.android', compact('item', 'title', 'item_confirmed'));
    }

    public function data_container(Request $request)
    {
        // $container_key = $request->container_key;
        // $name = Item::where('container_key', $container_key)->first();

        // if ($name) {
        //     return response()->json(['container_no' => $name->container_no, 'job' => $name->job_no, 'invoice' => $name->invoice_no]);
        // }
        // return response()->json(['container_no' => 'data tidak ditemukan', 'job' => 'data tidak ditemukan', 'invoice' => 'data tidak ditemukan']);
       $key = $request->container_key;
       $item = Item::where('container_key', $key)->first();
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

        if ($item) {
            $service = $item->order_service;
            // SP2 BALIK IKS
            if ($service === 'SP2IKS') {
                $item->update([
                    'ctr_intern_status' => 14,
                    'ctr_status' => 'MTY',
                    'ctr_active_yn' => 'Y',
                    'truck_no' => $request->truck_no,
                ]);
                

                    return response()->json([
                        'success' => true,
                        'message' => 'Silahkan Menuju Bagian Placement',
                        'data'    => $item,
                    ]);
               

                // SPPS RELOKASI
            } elseif ($service === 'SPPSRELOKASI') {
                $item->update([
                    'ctr_intern_status' => 12,
                    'order_service' => $request->order_service,
                    'no_dok' => $request->no_dok,
                    'jenis_dok' => $request->jenis_dok,
                    'truck_no' => $request->truck_no,
                    'job_no' => $request->job_no,
                    'invoice_no' => $request->invoice_no,
                ]);
              

                    return response()->json([
                        'success' => true,
                        'message' => 'Silahkan Menuju Bagian Placement',
                        'data'    => $item,
                    ]);

                // SP2 RELOKASI
            } elseif ($service === 'SP2RELOKASI') {
                $item->update([
                    'ctr_intern_status' => 13,
                    'order_service' => $request->order_service,
                    'no_dok' => $request->no_dok,
                    'jenis_dok' => $request->jenis_dok,
                    'truck_no' => $request->truck_no,
                    'job_no' => $request->job_no,
                    'invoice_no' => $request->invoice_no,
                ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Silahkan Menuju Bagian Placement',
                        'data'    => $item,
                    ]);
              
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal, Terjadi Kesalahan',
            ]);
        }
    }
}
