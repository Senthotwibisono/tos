<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\VVoyage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterAlat;
use App\Models\ActAlat;
use App\Models\Operator;
use App\Models\ActOper;
use GuzzleHttp\Client;

use Auth;
use Illuminate\Http\Request;

class LoadController extends Controller
{
  //
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $title = 'Confirm Load';
    $subtitle = 'Discharge Load';
    $confirmed = Item::where('ctr_intern_status', '=', 56,)->orderBy('update_time', 'desc')->get();
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
        'ves_name' => $tem->ves_name,
        'voy_no' => $tem->voy_no,
        'bay_slot' => $tem->bay_slot,
        'bay_row' => $tem->bay_row,
        'bay_tier' => $tem->bay_tier,
      ];
    }
    $items = Item::where('ctr_i_e_t', '=', 'E' )->where(function ($query) {
          $query->where('ctr_intern_status', '=', 51)
              ->orWhere('ctr_intern_status', '=', 53);
        })->where(function ($query) {
            $query->where('ctr_intern_status', '=', '08')
                  ->where(function ($query) {
                      $query->where('mty_type', '=', '01')
                            ->orWhere('mty_type', '=', '02');
                  });
        })->get();
    $users = User::all();
    $data["active"] = "discharge";
    $data["subactive"] = "confirm";
    $currentDateTime = Carbon::now();
    $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
    $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();
    $alat = MasterAlat::where('category', '=', 'Bay')->get();
    $data['operator'] = Operator::where('role', '=', 'cc')->get();

    return view('load.main', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'vessel_voyage', 'alat'), $data);
  }

  //android
  public function android()
  {
    $title = 'Confirm Load';
    $subtitle = 'Discharge Load';
    $confirmed = Item::where('ctr_intern_status', '=', 56,)->orderBy('update_time', 'desc')->get();
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
        'ves_name' => $tem->ves_name,
        'voy_no' => $tem->voy_no,
        'bay_slot' => $tem->bay_slot,
        'bay_row' => $tem->bay_row,
        'bay_tier' => $tem->bay_tier,
      ];
    }
    $items = Item::where('ctr_intern_status', '=', [51, 53])->get();
    $users = User::all();
    $data["active"] = "discharge";
    $data["subactive"] = "confirm";
    $currentDateTime = Carbon::now();
    $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
    $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();
    $alat = MasterAlat::where('category', '=', 'Bay')->get();
    $data['operator'] = Operator::where('role', '=', 'cc')->get();

    return view('load.android', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'vessel_voyage', 'alat'), $data);
  }

  public function get_cont(Request $request)
  {
    $ves_id = $request->ves_id;
    $container_key = Item::where('ves_id', $ves_id)->whereIn('ctr_intern_status', ['50', '51', '53'])->get();

    $option = []; // Inisialisasi variabel $option sebagai array kosong

    foreach ($container_key as $kode) {
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
        'container_no' => $name->container_no, 'name' => $name->ves_name, "container_key" => $name->container_key,  "ves_id" => $name->ves_id, "voy_no" => $name->voy_no,
      ]);
    }
    return response()->json(['container_no' => 'data tidak ditemukan', 'name' => 'data tidak ditemukan', 'slot' => 'data tidak ditemukan', 'row' => 'data tidak ditemukan', 'tier' => 'data tidak ditemukan']);
  }


  public function confirm(Request $request)
  {
    $container_key = $request->container_key;
    $id_alat = $request->cc_tt_no;
    $alat = MasterAlat::where('id', $id_alat)->first();
    $item = Item::where('container_key', $container_key)->first();
    $opr = Operator::where('id', $request->operator)->first();
    $request->validate([
      'container_no' => 'required',
      'cc_tt_no' => 'required',
      'operator' => 'required',
      'bay_slot' => 'required',
      'bay_row' => 'required',
      'bay_tier' => 'required',

    ], [
      'container_no.required' => 'Container Number is required.',
      'cc_tt_no.required' => 'Nomor Alat Number is required.',
      'operator.required' => 'Operator Alat Number is required.',
    ]);
    $act_alat = ActAlat::create([
      'id_alat' =>  $request->cc_tt_no,
      'category' => 'Bay',
      'nama_alat' => $alat->name,
      'operator_id'=>$request->operator,
      'operator' => $opr->name,
      'container_key' => $request->container_key,
      'container_no' => $request->container_no,
      'activity' => 'LOAD',
    ]);
    $actOper = ActOper::create([
      'alat_id' => $request->cc_tt_no,
      'alat_category' =>$alat->category,
      'alat_name'  =>$alat->name,
      'operator_id'=>$request->operator,
      'operator_name'=>$opr->name,
      'container_key'=>$item->container_key,
      'container_no'=>$item->container_no,
      'ves_id'=>$item->ves_id,
      'ves_name'=>$item->ves_name,
      'voy_no'=>$item->voy_no,
      'activity' =>'LOAD',
  ]);
    Item::where('container_key', $container_key)->update([
      'bay_slot' => $request->bay_slot,
      'bay_row' => $request->bay_row,
      'bay_tier' => $request->bay_tier,
      'load_date' => $request->load_date,
      'cc_tt_no'  => $alat->name,
      'cc_tt_oper'  => $opr->name,
      'ctr_intern_status' => '56',
      'ctr_active_yn' => 'N',

    ]);
    
      return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'item' => $item,
      ]);
   
  }
}
