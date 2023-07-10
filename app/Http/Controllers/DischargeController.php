<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\VVoyage;
use Carbon\Carbon;
use App\Models\User;
use GuzzleHttp\Client;

use Auth;
use Illuminate\Http\Request;

class DischargeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $title = 'Confirm Disch';
    $subtitle = 'Discharge Confirm';
    $confirmed = Item::where('ctr_intern_status', '=', 02,)->orderBy('update_time', 'desc')->get();
    $formattedData = [];
    $data = [];

    foreach ($confirmed as $tem) {
      $now = Carbon::now();
      $discAt = Carbon::parse($tem->disc_date);

      // Perhitungan selisih waktu
      $diff = $discAt->diffForHumans($now);

      // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
      if ($discAt->diffInDays($now) < 1) {
        $diff = $discAt->diffForHumans($now, true);
        $diff = str_replace(['hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
      } else {
        // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
        $diff = $discAt->diffForHumans($now, true);
        $diff = str_replace(['days', 'day', 'hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['hari', 'hari', 'jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
      }

      $formattedData[] = [
        'container_no' => $tem->container_no,
        'cc_tt_no' => $tem->cc_tt_no,
        'cc_tt_oper' => $tem->cc_tt_oper,
        'disc_date' => $diff . ' yang lalu',
        'container_key' => $tem->container_key
      ];
    }
    $items = Item::where('ctr_intern_status', '=', 01)->get();
    $users = User::all();
    $data["active"] = "discharge";
    $data["subactive"] = "confirm";
    $currentDateTime = Carbon::now();
    $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
    $vessel_voyage = VVoyage::whereDate('arrival_date', '<=', now())->orderBy('arrival_date', 'desc')->get();
    return view('disch.main', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'vessel_voyage'), $data);
  }
  //android
  public function android()
  {
    $title = 'Confirm Disch';
    $confirmed = Item::where('ctr_intern_status', '=', 02,)->orderBy('update_time', 'desc')->get();
    $formattedData = [];

    foreach ($confirmed as $tem) {
      $now = Carbon::now();
      $discAt = Carbon::parse($tem->disc_date);

      // Perhitungan selisih waktu
      $diff = $discAt->diffForHumans($now);

      // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
      if ($discAt->diffInDays($now) < 1) {
        $diff = $discAt->diffForHumans($now, true);
        $diff = str_replace(['hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
      } else {
        // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
        $diff = $discAt->diffForHumans($now, true);
        $diff = str_replace(['days', 'day', 'hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['hari', 'hari', 'jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
      }

      $formattedData[] = [
        'container_no' => $tem->container_no,
        'cc_tt_no' => $tem->cc_tt_no,
        'cc_tt_oper' => $tem->cc_tt_oper,
        'disc_date' => $diff . ' yang lalu',
        'container_key' => $tem->container_key
      ];
    }
    $items = Item::where('ctr_intern_status', '=', 01)->get();
    $users = User::all();
    $currentDateTime = Carbon::now();
    $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
    return view('disch.android', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString'));
  }

  public function get_cont(request $request)
  {
      $ves_id = $request->ves_id;
      $container_key = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', 01)->get();
      foreach ($container_key as $kode) {
          echo "<option value='$kode->container_key'>$kode->container_no</option>";
      }
  }

  public function get_key(Request $request)
  {
    $container_key = $request->container_key;
    $name = Item::where('container_key', $container_key)->first();

    if ($name) {
      return response()->json([
        'container_no' => $name->container_no, 'name' => $name->ves_name, 'slot' => $name->bay_slot, 'row' => $name->bay_row, 'tier' => $name->bay_tier,
        "container_key" => $name->container_key,
        "ves_id" => $name->ves_id,
        "voy_no" => $name->voy_no,
        "container_no" => $name->container_no,
        "ctr_status" => $name->ctr_status,
        "ctr_type" => $name->ctr_type,
        "ctr_opr" => $name->ctr_opr,
        "ctr_size" => $name->ctr_size,
        "disc_load_trans_shift" => $name->disc_load_trans_shift,
        "load_port" => $name->load_port,
        "disch_port" => $name->disch_port,
        "gross" => $name->gross,
        "iso_code" => $name->iso_code,

      ]);
    }
    return response()->json(['container_no' => 'data tidak ditemukan', 'name' => 'data tidak ditemukan', 'slot' => 'data tidak ditemukan', 'row' => 'data tidak ditemukan', 'tier' => 'data tidak ditemukan']);
  }


  public function confirm(Request $request)
  {
    $container_key = $request->container_key;
    $item = Item::where('container_key', $container_key)->first();
    $request->validate([
      'container_no' => 'required',
      'cc_tt_no' => 'required',
      'cc_tt_oper' => 'required',
    ], [
      'container_no.required' => 'Container Number is required.',
      'cc_tt_no.required' => 'Nomor Alat Number is required.',
      'cc_tt_oper.required' => 'Operator Alat Number is required.',
    ]);
    Item::where('container_key', $container_key)->update([
      'cc_tt_no' => $request->cc_tt_no,
      'cc_tt_oper' => $request->cc_tt_oper,
      'disc_date' => $request->disc_date,
      'ctr_intern_status' => '02',
      'wharf_yard_oa' => $request->wharf_yard_oa,
      'container_key' => $request->container_key,
      'ves_id' => $request->ves_id,
      'voy_no' => $request->voy_no,
      'ves_name' => $request->ves_name,
      'container_no' => $request->container_no,
      'ctr_status' => $request->ctr_status,
      'ctr_type' => $request->ctr_type,
      'ctr_opr' => $request->ctr_opr,
      'ctr_size' => $request->ctr_size,
      'disc_load_trans_shift' => $request->disc_load_trans_shift,
      'load_port' => $request->load_port,
      'disch_port' => $request->disch_port,
      'fdisch_port' => '',
      'bay_slot' => $request->bay_slot,
      'bay_row' => $request->bay_row,
      'bay_tier' => $request->bay_tier,
      'gross' => $request->gross,
      'iso_code' => $request->iso_code,

    ]);

    $client = new Client();

    $fields = [
      "container_key" => $request->container_key,
      "ctr_intern_status" => "02",
      "disc_date" => $request->disc_date,
      "ves_id" => $request->ves_id,
      "voy_no" => $request->voy_no,
      "vessel_name" => $request->ves_name,
      "container_no" => $request->container_no,
      "ctr_status" => $request->ctr_status,
      "ctr_type" => $request->ctr_type,
      "ctr_size" => $request->ctr_size,
      "ctr_opr" => $request->ctr_opr,
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
    // dd($fields, $item->getAttributes());

    $url = getenv('API_URL') . '/delivery-service/container/create';
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
      // $item->save();

      return response()->json([
        'success' => 400,
        'message' => 'updated successfully!',
        'data'    => $item,
      ]);
    } else {
      return back();
    }
  }
}

