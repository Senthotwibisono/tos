<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\VVoyage;
use App\Models\Isocode;
use App\Models\Item;
use App\Models\Imocode;
use App\Models\Port;
use App\Models\User;
use App\Models\Ship;
use App\Models\HistoryContainer;
use Auth;

use GuzzleHttp\Client;


use Carbon\Carbon;

class BayplanImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = 'Bay Plan Import';

        $item = Item::whereIn('ctr_intern_status', ['01', '15'])->whereNot('container_no', '=', '')->orderBy('container_key', 'desc')->get();
        $formattedData = [];
        $data = [];

        foreach ($item as $tem) {
            $now = Carbon::now();
            $updatedAt = Carbon::parse($tem->update_time);

            // Perhitungan selisih waktu
            $diff = $updatedAt->diffForHumans($now);

            // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
            if ($updatedAt->diffInDays($now) < 1) {
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['hours', 'hour'], ['jam', 'jam'], $diff);
            } else {
                // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['days', 'day', 'hours', 'hour'], ['hari', 'hari', 'jam', 'jam'], $diff);
            }

            $formattedData[] = [
                'ves_id' => $tem->ves_id,
                'ves_name' => $tem->ves_name,
                'voy_no' => $tem->voy_no,
                'disc_load_seq' => $tem->disc_load_seq,
                'container_no' => $tem->container_no,
                'ctr_size' => $tem->ctr_size,
                'ctr_type' => $tem->ctr_type,
                'ctr_status' => $tem->ctr_status,
                'gross' => $tem->gross,
                'bay_slot' => $tem->bay_slot,
                'bay_row' => $tem->bay_row,
                'bay_tier' => $tem->bay_tier,
                'load_port' => $tem->load_port,
                'org_port' => $tem->org_port,
                'update_time' => $diff . ' yang lalu',
                'container_key' => $tem->container_key
            ];
        }
        $users = User::all();
        $isocode = Isocode::all();
        $today = date('Y-m-d H:i:s');
        $vessel_voyage = Vvoyage::all();
        $vessel_import = VVoyage::whereDate('eta_date', '>=', now())->get();
        // where('eta_date', '>', $today)->get();
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $imocode = Imocode::all();
        $port_master = Port::all();
        $data["active"] = "planning";
        $data["subactive"] = "bayplanimport";

        return view('planning.bayplan.main', compact('item', 'currentDateTimeString', 'isocode', 'vessel_voyage', 'title', 'imocode', 'users', 'vessel_import', 'port_master', 'formattedData'), $data);
    }

    public function size(request $request)
    {
        $iso_code = $request->iso_code;
        $size = Isocode::where('iso_code', $iso_code)->get();
        foreach ($size as $sz) {
            echo "<option value='$sz->iso_size'>$sz->iso_size</option>";
        }
    }
    public function type(request $request)
    {
        $iso_code = $request->iso_code;
        $type = Isocode::where('iso_code', $iso_code)->get();
        foreach ($type as $tp) {
            echo "<option value='$tp->iso_type'>$tp->iso_type</option>";
        }
    }
    public function code(request $request)
    {
        $ves_id = $request->ves_id;
        $code = VVoyage::where('ves_id', $ves_id)->get();
        foreach ($code as $kode) {
            echo "<option value='$kode->ves_code'>$kode->ves_code</option>";
        }
    }
    public function name(request $request)
    {
        $ves_id = $request->ves_id;
        $name = VVoyage::where('ves_id', $ves_id)->get();
        foreach ($name as $nam) {
            echo "<option value='$nam->ves_name'>$nam->ves_name</option>";
        }
    }
    public function voy(request $request)
    {
        $ves_id = $request->ves_id;
        $voy = VVoyage::where('ves_id', $ves_id)->get();
        foreach ($voy as $out) {
            echo "<option value='$out->voy_out'>$out->voy_out</option>";
        }
    }
    public function agent(request $request)
    {
        $ves_id = $request->ves_id;
        $agent = VVoyage::where('ves_id', $ves_id)->get();
        foreach ($agent as $ag) {
            echo "<option value='$ag->agent'>$ag->agent</option>";
        }
    }

    public function store(request $request)
    {

        $cont = $request->container_no;
        $cont_item = Item::where('container_no', $cont)->get();
    
        foreach ($cont_item as $item) {
            if ($item->ctr_intern_status !== '09' && $item->ctr_intern_status !== '56') {
                return redirect()->back()->with('error', 'Container sudah ada dan tidak memenuhi syarat untuk pembuatan baru.')->withInput();
            }
        }
    
        $request->validate([
            'container_no' => 'required|max:13',
            'ves_id' => 'required',
            'gross' => 'required',
            'gross_class' => 'required',
            'commodity_name' => 'required',
            'load_port' => 'required',
            'disch_port' => 'required',
            'disc_load_seq' => 'required',
            'bay_slot' => 'required',
            'bay_row' => 'required|max:2',
            'bay_tier' => 'required|max:2',
            'iso_code' => 'required',
            'ctr_opr' => 'required',
        ]);
    
        try {
            $item = Item::create([
                'container_no' => $request->container_no,
                'ves_id' => $request->ves_id,
                'ves_code' => $request->ves_code,
                'ves_name' => $request->ves_name,
                'voy_no' => $request->voy_no,
                'ctr_i_e_t' => $request->ctr_i_e_t,
                'ctr_size' => $request->ctr_size,
                'ctr_type' => $request->ctr_type,
                'ctr_status' => $request->ctr_status,
                'ctr_intern_status' => '01',
                'disc_load_trans_shift' => $request->disc_load_trans_shift,
                'gross' => $request->gross,
                'gross_class' => $request->gross_class,
                'over_height' => $request->over_height,
                'over_weight' => $request->over_weight,
                'over_length' => $request->over_length,
                'commodity_name' => $request->commodity_name,
                'load_port' => $request->load_port,
                'disch_port' => $request->disch_port,
                'agent' => $request->agent,
                'chilled_temp' => $request->chilled_temp,
                'imo_code' => $request->imo_code,
                'dangerous_yn' => $request->dangerous_yn,
                'dangerous_label_yn' => $request->dangerous_label_yn,
                'bl_no' => $request->bl_no,
                'seal_no' => $request->seal_no,
                'disc_load_seq' => $request->disc_load_seq,
                'bay_slot' => $request->bay_slot,
                'bay_row' => $request->bay_row,
                'bay_tier' => $request->bay_tier,
                'iso_code' => $request->iso_code,
                'ctr_opr' => $request->ctr_opr,
                'user_id' => $request->user_id,
                'selected_do' => 'N',

            ]);
            $ship = Ship::where('ves_id', $item->ves_id)->where('bay_slot', $item->bay_slot)->where('bay_row', $item->bay_row)->where('bay_tier', $item->bay_tier)->first();
            if ($ship) {
             if ($ship->container_key == null) {
                $ship->update([
                    'container_no'=>$item->container_no,
                    'container_key'=>$item->container_key,
                    'ctr_size'=>$item->ctr_size,
                    'ctr_type'=>$item->ctr_type,
                    'dangerous_yn'=>$item->dangerous_yn,
                    'ctr_i_e_t'=> "I",
                ]);
             }else {
                $item->delete();
                return redirect('/planning/bayplan_import')->with('error', "Bay Row Tier Sudah Terisi");
             }
            }else {
                $item->delete();
                return redirect('/planning/bayplan_import')->with('error', "Bay Row Tier Tidak Ada atau Tidak Sesuai");
            }
            return redirect('/planning/bayplan_import')->with('success', "Container Berhasil Dibuat");
        } catch (\Exception $e) {
            $item->delete();
            return redirect()->back()->with('error', 'Terjadi Kesalahan')->withInput();
        }
    }


    public function edit(Request $request)
    {
        $container_key = $request->container_key;
        $item = Item::where('container_key', $container_key)->first();

        return response()->json([
            'success' => 200,
            'message' => 'Detail Data Post',
            'data'    => $item
        ]);
    }
    public function size_edit(request $request)
    {
        $iso_code = $request->iso_code;
        $size = Isocode::where('iso_code', $iso_code)->get();
        foreach ($size as $sz) {
            echo "<option value='$sz->iso_size'>$sz->iso_size</option>";
        }
    }
    public function get_iso_type(Request $request)
    {
        $iso_code = $request->iso_code;
        $type = Isocode::where('iso_code', $iso_code)->first();

        if ($type) {
            return response()->json(['isotype_edit' => $type->iso_type, 'isosize_edit' => $type->iso_size]);
        }
        return response()->json(['isotype_edit' => 'data tidak ditemukan', 'isosize_edit' => 'data tidak ditemukan']);
    }

    public function get_ves_name(Request $request)
    {
        $ves_id = $request->ves_id;
        $name = VVoyage::where('ves_id', $ves_id)->first();

        if ($name) {
            return response()->json(['vesname_edit' => $name->ves_name, 'vescode_edit' => $name->ves_code, 'voy_edit' => $name->voy_out, 'agent_edit' => $name->agent]);
        }
        return response()->json(['vesname_edit' => 'data tidak ditemukan', 'vescode_edit' => 'data tidak ditemukan', 'voy_edit' => 'data tidak ditemukan', 'agent_edit' => 'data tidak ditemukan']);
    }

    public function update_bayplanimport(Request $request)
    {
        $container_key = $request->container_key;
        $item = Item::where('container_key', $container_key)->first();
        $request->validate([
            'container_no' => 'required|max:13',
            'ves_id' => 'required',
            'gross' => 'required',
            'gross_class' => 'required',
            'commodity_name' => 'required',
            'load_port' => 'required',
            'disch_port' => 'required',
            'disc_load_seq' => 'required',
            'bay_slot' => 'required',
            'bay_row' => 'required|max:2',
            'bay_tier' => 'required|max:2',
            'iso_code' => 'required',
            'ctr_opr' => 'required',


        ]);

        $item->update([
            'container_no' => $request->container_no,
            'ves_id' => $request->ves_id,
            'ves_code' => $request->ves_code,
            'ves_name' => $request->ves_name,
            'voy_no' => $request->voy_no,
            'ctr_size' => $request->ctr_size,
            'ctr_type' => $request->ctr_type,
            'ctr_status' => $request->ctr_status,
            'disc_load_trans_shift' => $request->disc_load_trans_shift,
            'gross' => $request->gross,
            'gross_class' => $request->gross_class,
            'over_height' => $request->over_height,
            'over_weight' => $request->over_weight,
            'over_length' => $request->over_length,
            'commodity_name' => $request->commodity_name,
            'load_port' => $request->load_port,
            'disch_port' => $request->disch_port,
            'agent' => $request->agent,
            'chilled_temp' => $request->chilled_temp,
            'imo_code' => $request->imo_code,
            'dangerous_yn' => $request->dangerous_yn,
            'dangerous_label_yn' => $request->dangerous_label_yn,
            'bl_no' => $request->bl_no,
            'seal_no' => $request->seal_no,
            'disc_load_seq' => $request->disc_load_seq,
            'bay_slot' => $request->bay_slot,
            'bay_row' => $request->bay_row,
            'bay_tier' => $request->bay_tier,
            'iso_code' => $request->iso_code,
            'ctr_opr' => $request->ctr_opr,
            'user_id' => $request->user_id,
        ]);

        $oldShip = Ship::where('ctr_i_e_t', '=', 'I')->where('container_key', $item->container_key)->first();
        $ship = Ship::where('ves_id', $item->ves_id)->where('bay_slot', $item->bay_slot)->where('bay_row', $item->bay_row)->where('bay_tier', $item->bay_tier)->first();
            if ($ship) {
                if ($ship->container_key == null || $ship->container_key == $item->container_key) {
                    if ($ship->container_key != $item->container_key) {
                        if ($oldShip) {
                            $oldShip->update([
                                'container_no'=>null,
                                'container_key'=>null,
                                'ctr_size'=>null,
                                'ctr_type'=>null,
                                'dangerous_yn'=>null,
                                'ctr_i_e_t'=> null,
                            ]);
                        }
                    }
                  
                    $ship->update([
                        'container_no'=>$item->container_no,
                        'container_key'=>$item->container_key,
                        'ctr_size'=>$item->ctr_size,
                        'ctr_type'=>$item->ctr_type,
                        'dangerous_yn'=>$item->dangerous_yn,
                        'ctr_i_e_t'=> "I",
                    ]);
                 }else {
                    $item->update([
                        'bay_slot' => $oldShip->bay_slot,
                        'bay_row' => $oldShip->bay_row,
                        'bay_tier' => $oldShip->bay_tier,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Bay Sudah Terisi!',
                        'data'    => $item,
                    ]);
                 }
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Bay Tidak Ditemukan!',
                    'data'    => $item,
                ]);
            }

        return response()->json([
            'success' => 400,
            'message' => 'updated successfully!',
            'data'    => $item,
        ]);
    }

    public function delete_item($container_key)
    {

        $item = Item::where('container_key', $container_key)->first();
        $ship = Ship::where('ves_id', $item->ves_id)->where('container_key', $item->container_key)->first();
        if ($ship) {
            $ship->update([
                    'container_no'=>null,
                    'container_key'=>null,
                    'ctr_size'=>null,
                    'ctr_type'=>null,
                    'dangerous_yn'=>null,
                    'ctr_i_e_t'=> null,
            ]);
        }
        $item->delete();
        return back();
    }


    public function pelindo(request $request)
    {

        $cont = $request->container_no;
        $cont_item = Item::where('container_no', $cont)->get();
    
        foreach ($cont_item as $item) {
            if ($item->ctr_intern_status !== '09' && $item->ctr_intern_status !== '56') {
                return redirect()->back()->with('error', 'Container sudah ada dan tidak memenuhi syarat untuk pembuatan baru.')->withInput();
            }
        }
    
        $request->validate([
            'container_no' => 'required|max:13',
            'gross' => 'required',
            'gross_class' => 'required',
            'commodity_name' => 'required',
            'load_port' => 'required',
            'disch_port' => 'required',
            'iso_code' => 'required',
            'ctr_opr' => 'required',
            'disc_date' => 'required',
        ]);
    
        try {
            $item = Item::create([
                'container_no' => $request->container_no,
                'ves_id' => 'PELINDO',
                'ves_code' => 'PELINDO',
                'ves_name' => 'PELINDO',
                'voy_no' => 'PELINDO',
                'ctr_i_e_t' => $request->ctr_i_e_t,
                'ctr_size' => $request->ctr_size,
                'ctr_type' => $request->ctr_type,
                'ctr_status' => $request->ctr_status,
                'ctr_intern_status' => '15',
                'disc_load_trans_shift' => $request->disc_load_trans_shift,
                'gross' => $request->gross,
                'gross_class' => $request->gross_class,
                'over_height' => $request->over_height,
                'over_weight' => $request->over_weight,
                'over_length' => $request->over_length,
                'commodity_name' => $request->commodity_name,
                'load_port' => $request->load_port,
                'disch_port' => $request->disch_port,
                'agent' => 'RELK',
                'chilled_temp' => $request->chilled_temp,
                'imo_code' => $request->imo_code,
                'dangerous_yn' => $request->dangerous_yn,
                'dangerous_label_yn' => $request->dangerous_label_yn,
                'bl_no' => $request->bl_no,
                'seal_no' => $request->seal_no,
                'disc_load_seq' => '--',
                'bay_slot' => '--',
                'bay_row' => '--',
                'bay_tier' => '--',
                'iso_code' => $request->iso_code,
                'ctr_opr' => $request->ctr_opr,
                'user_id' => $request->user_id,
                'disc_date' => $request->disc_date,
                'selected_do' => 'N',
            ]);

           
            return redirect('/planning/bayplan_import')->with('success', "Container Berhasil Dibuat");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi Kesalahan')->withInput();
        }
    }
}
