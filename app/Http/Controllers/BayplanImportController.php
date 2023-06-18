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

        $item = Item::where('ctr_intern_status', '=', '01')->orderBy('container_key', 'desc')->get();
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
        $request->validate([]);

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
            'ctr_intern_status' => $request->ctr_intern_status,
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

        // var_dump($item);
        // die();
        // $response = response()->json(['message' => 'Item created successfully', 'item' => $item]);
        // dd($item->getAttributes()->ves_code);
        $client = new Client();

        $fields = [
            "container_key" => $item->container_key,
            "ves_id" => $item->ves_id,
            "voy_no" => $item->voy_no,
            "vessel_name" => $item->ves_name,
            "container_no" => $item->container_no,
            "ctr_status" => $item->ctr_status,
            "ctr_intern_status" => $item->ctr_intern_status,
            "ctr_type" => $item->ctr_type,
            "ctr_opr" => $item->ctr_opr,
            "ctr_size" => $item->ctr_size,
            "disc_load_trans_shift" => $item->disc_load_trans_shift,
            "load_port" => $item->load_port,
            "disch_port" => $item->disch_port,
            "fdisch_port" => "",
            "bay_slot" => $item->bay_slot,
            "bay_row" => $item->bay_row,
            "bay_tier" => $item->bay_tier,
            "gross" => $item->gross,
            "iso_code" => $item->iso_code,
        ];
        // dd($fields, $item->getAttributes());

        $url = 'localhost:3013/delivery-service/container/create';
        $req = $client->post(
            $url,
            [
                "json" => $fields
            ]
        );
        $response = $req->getBody()->getContents();
        $result = json_decode($response);
        // dd($result);
        if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
            $item->save();
            return back();
        } else {
            return back();
        }


        // return back();
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

        $client = new Client();

        $fields = [
            "container_key" => $request->container_key,
            "ves_id" => $request->ves_id,
            "voy_no" => $request->voy_no,
            "vessel_name" => $request->ves_name,
            "container_no" => $request->container_no,
            "ctr_status" => $request->ctr_status,
            "ctr_intern_status" => "01",
            "ctr_type" => $request->ctr_type,
            "ctr_opr" => $request->ctr_opr,
            "ctr_size" => $request->ctr_size,
            "disc_load_trans_shift" => $request->disc_load_trans_shift,
            "load_port" => $request->load_port,
            "disch_port" => $request->disch_port,
            "fdisch_port" => "",
            "bay_slot" => $request->bay_slot,
            "bay_row" => $request->bay_row,
            "bay_tier" => $request->bay_tier,
            "gross" => $request->gross,
            "iso_code" => $request->iso_code,
        ];
        // var_dump($fields, $item);
        // die();

        $url = 'localhost:3013/delivery-service/container/update';
        $req = $client->post(
            $url,
            [
                "json" => $fields
            ]
        );
        $response = $req->getBody()->getContents();
        $result = json_decode($response);
        // var_dump($result);
        // die();
        if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
            return response()->json([
                'success' => 400,
                'message' => 'updated successfully!',
                'data'    => $item,
                // 'history' => $history_container,
            ]);
        } else {
            return back()->with('success', 'Data gagal disimpan!');
        }
    }

    public function delete_item($container_key)
    {

        Item::where('container_key', $container_key)->delete();
        return back();
    }
}
