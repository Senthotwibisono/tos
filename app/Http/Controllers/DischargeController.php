<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\VVoyage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterAlat;
use App\Models\ActAlat;
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
    $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();

    $alat = MasterAlat::where('category', '=', 'Bay')->get();
    return view('disch.main', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'vessel_voyage', 'alat'), $data);
  }
  
  //android
  public function android()
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
    $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('arrival_date', 'desc')->get();
    return view('disch.android', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'vessel_voyage'), $data);
  }

  public function get_cont(request $request)
  {
    $ves_id = $request->ves_id;
    $container_key = Item::where('ves_id', $ves_id)->whereIn('ctr_intern_status', ['01'])->get();
    
    $option = [];

    if ($container_key->isEmpty()) {
        // Return empty response when no containers are found
        return response()->json($option);
    }
    foreach ($container_key as $kode) {
      // echo "<option value='$kode->container_key'>$kode->container_no</option>";
      $option[] = [
        'value' => $kode->container_key,
        'text' => $kode->container_no,
      ];
    }
    return response()->json($option);
  }

  // public function get_cont(Request $request)
  // {
  //   $ves_id = $request->ves_id;
  //   $containerKey = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', 01)->get();


  //   $options = [];
  //   foreach ($containerKey as $container) {
  //     $options[] = [
  //       'id' => $container->container_key,
  //       'text' => $container->container_no
  //     ];
  //   }

  //   return response()->json($options);
  // }

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
    $now = Carbon::now();
    $container_key = $request->container_key;
    $item = Item::where('container_key', $container_key)->first();
    $id_alat = $request->cc_tt_no;
    $alat = MasterAlat::where('id', $id_alat )->first();
    $request->validate([
      'container_no' => 'required',
      'cc_tt_no' => 'required | max : 7',
      'cc_tt_oper' => 'required | max : 7',
    ], [
      'container_no.required' => 'Container Number is required.',
      'cc_tt_no.required' => 'Nomor Alat Number is required.',
      'cc_tt_oper.required' => 'Operator Alat Number is required.',
      'cc_tt_no.max' => 'Opss Data Terlalu Panjang.',
      'cc_tt_oper.max' => 'Opss Data Terlalu Panjang.',
    ]);
    if ($item) {

      $act_alat = ActAlat::create([
        'id_alat' =>  $request->cc_tt_no,
        'category' => 'Bay',
        'nama_alat' => $alat->name,
        'container_key' => $request->container_key,
        'container_no' => $request->container_no,
        'activity' => 'DISCH',
      ]);

      $item->update([
        'cc_tt_no' => $alat->name,
        'cc_tt_oper' => $request->cc_tt_oper,
        'disc_date' => $now,
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
        'ctr_active_yn'=>'Y',
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
       
  
        return response()->json([
          'success' => true,
          'message' => 'updated successfully!',
          'data'    => $item,
        ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Something Wrong!',
        ]);
      };
    }
    
  }
}
