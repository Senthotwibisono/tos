<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;


use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Bay;
use App\Models\Ship;
use App\Models\VVoyage;

use PDF;
use TCPDF;
use Dompdf\Dompdf;
use Dompdf\Options;

use Carbon\Carbon;

class DischargeView extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($vessel = null, $bay = null)
    {
        $title = 'Vessel View';
        $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->get(['ves_id', 'ves_name', 'voy_no']);
        $now = Carbon::now();
        $data['ves'] = VVoyage::where('deparature_date', '>=', $now )->get();

        $vessel = $vessel;
        if ($vessel != null) {
            $data['selectedVes'] = VVoyage::where('ves_id', $vessel)->first();
            $data['optionBay'] = Bay::where('VES_CODE', $data['selectedVes']->ves_code)->get();

            $bay  = $bay;
            if ($bay != null) {
                $data['onDeck'] = Ship::where('ves_id', $vessel)->where('on_under', '=', 'O')->where('bay_slot', $bay)->get();
                $data['underDeck'] = Ship::where('ves_id', $vessel)->where('on_under', '=', 'U')->where('bay_slot', $bay)->get();
            }else {
                $data['onDeck'] = null; 
                $data['underDeck'] = null; 
            }
           
        }else {
            $data['selectedVes'] = null; 
            $data['optionBay'] = null; 
            $data['onDeck'] = null; 
            $data['underDeck'] = null; 
        }
       
        return view('disch.view', compact('item', 'title', 'vessel', 'bay'), $data);
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
        $kapal = VVoyage::where('ves_id', $ves_id)->first();
        $bay = Bay::where('VES_CODE', $kapal->ves_code)->get();
        foreach ($bay as $slot) {
            echo "<option value='$slot->BAY1'>$slot->BAY1</option>";
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

    public function cetakBay(Request $request)
    {
        $vessel = $request->ves_id;
        $bay_slot = $request->bay_slot;

        $data['title'] = "Bay";

        $data['onDeck'] = Ship::where('ves_id', $vessel)->where('on_under', '=', 'O')->where('bay_slot', $bay_slot)->get();
        $data['underDeck'] = Ship::where('ves_id', $vessel)->where('on_under', '=', 'U')->where('bay_slot', $bay_slot)->get();
        $data['vesSelect'] = VVoyage::where('ves_id', $vessel)->first();
        $data['baySelect'] = $bay_slot;
        $data['totalCont'] = Ship::where('ves_id', $vessel)->where('ctr_i_e_t', '=', 'I')->where('bay_slot', $bay_slot)->count();

       return view('disch.cetakKapal', $data);
    }

    public function cetakKapal(Request $request)
    {
        $vessel = $request->ves_id; 
        $data['title'] = "Bay";

        $data['baySlots'] = Ship::select('bay_slot')->where('ves_id', $vessel)->groupBy('bay_slot')->orderBy('bay_slot', 'asc')->get();
        $data['onDeck'] = Ship::where('ves_id', $vessel)->where('on_under', '=', 'O')->orderBy('bay_slot', 'asc')->get();
        $data['underDeck'] = Ship::where('ves_id', $vessel)->where('on_under', '=', 'U')->orderBy('bay_slot', 'asc')->get();
        $data['vesSelect'] = VVoyage::where('ves_id', $vessel)->first();
        $data['totalCont'] = Ship::where('ves_id', $vessel)->where('ctr_i_e_t', '=', 'I')->count();
        return view('disch.cetakFull', $data);
    }
}
