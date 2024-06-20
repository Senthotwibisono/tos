<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class GateMTcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function IndexIn()
    {
        $data['title'] = "Gate In Empty";
        $data['containers'] = Item::where('ctr_intern_status', '=', '49')->whereNot('job_no', null)->whereHas('service', function($query) {
            $query->where('order', '=', 'MTK');
        })->get();

        $data['confirmed'] = Item::where('ctr_intern_status', '=', '45')->get();

        return view('gate.empty.indexIn', $data);
    }

    public function ConfirmIn(Request $request)
    {
        $item = Item::where('container_key', $request->container_key)->first();
        if ($item) {
            $item->update([
                'ctr_intern_status'=>'45',
                'truck_in_date'=>$request->truck_in_date,
                'truck_no'=>$request->truck_no,
            ]);

            return redirect()->back()->with('success', 'Silahkan Masuk');
        }else {
            return redirect()->back()->with('error', 'Terjadi Kesalahan');
        }
    }

    public function IndexOut()
    {
        $data['title'] = "Gate Out Empty";
        $data['containers'] = Item::where('ctr_intern_status', '=', '45')->get();

        $data['confirmed'] = Item::where('ctr_intern_status', '=', '09')->whereHas('service', function($query) {
            $query->where('order', '=', 'MTK');
        })->get();

        return view('gate.empty.indexOut', $data);
    }

    public function ConfirmOut(Request $request)
    {
        $item = Item::where('container_key', $request->container_key)->first();
        if ($item) {
            $oldItem = Item::where('container_no', $item->container_no)->where('ctr_intern_status', '=', '04')->first();
            if ($oldItem) {
                $oldItem->update([
                    'ctr_intern_status'=>'09',
                    'ctr_active_yn'=> 'N',
                    'os_id'=>null,
                ]);
            }
            $item->update([
                'ctr_intern_status'=>'09',
                'truck_out_date'=>$request->truck_out_date,
                'truck_no'=>$request->truck_no,
            ]);

            return redirect()->back()->with('success', 'Silahkan Masuk');
        }else {
            return redirect()->back()->with('error', 'Terjadi Kesalahan');
        }
    }
}
