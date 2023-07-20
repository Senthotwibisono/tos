<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class DischargeView extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = 'Vessel View';
        $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->get(['ves_id', 'ves_name', 'voy_no']);
        return view('disch.view', compact('item', 'title'));
    }

    public function get_ves(Request $request)
    {
        $ves_id = $request->ves_id;
        $name = Item::where('ves_id', $ves_id)->first();
       
        if ($name) {
            return response()->json(['name' => $name->ves_name, 'code'=>$name->ves_code]);
        }
        return response()->json(['name' => 'data tidak ditemukan', 'code' => 'data tidak ditemukan']);
    
    }

    public function get_bay(request $request)
    {
        $ves_id = $request->ves_id;
        $bay = Item::where('ves_id', $ves_id)->distinct()->get('bay_slot');
        foreach ($bay as $slot) {
            echo "<option value='$slot->bay_slot'>$slot->bay_slot</option>";
        }
    }

    public function get_container(Request $request)
    {
        $ves_id = $request->ves_id;
        $bay_slot = $request->bay_slot;

        $item = Item::where('ves_id', $ves_id)
                    ->where('bay_slot', $bay_slot)
                    ->where('ctr_intern_status',01 )
                    ->get();

        return response()->json(['item' => $item]);
    }
}
