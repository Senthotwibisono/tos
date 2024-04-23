<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Shifting;
use App\Models\Ship;
use App\Models\MasterAlat;
use App\Models\ActAlat;
use App\Models\Operator;
use App\Models\ActOper;
use App\Models\VVoyage;

use Auth;
use Carbon\Carbon;


class ShiftingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title'] = 'Shifting';

        $data['cont'] = Item::whereIn('ctr_intern_status', ['01', '02', '56'])->get();
        $data['alat'] = MasterAlat::where('category', '=', 'Bay')->get();
        $data['operator'] = Operator::where('role', '=', 'cc')->get();
        $data['shifted'] = Shifting::orderBy('ves_id', 'desc')->orderBy('shifting_time', 'desc')->get();
        $data['vessel_voyage'] = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();


        return view('shifting.index', $data);
    }

    public function android()
    {
        $data['title'] = 'Shifting';

        $data['cont'] = Item::whereIn('ctr_intern_status', ['01', '02', '56'])->get();
        $data['alat'] = MasterAlat::where('category', '=', 'Bay')->get();
        $data['operator'] = Operator::where('role', '=', 'cc')->get();
        $data['shifted'] = Shifting::orderBy('ves_id', 'desc')->orderBy('shifting_time', 'desc')->get();
        $data['vessel_voyage'] = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();


        return view('shifting.android', $data);
    }

    public function get_cont(request $request)
    {
      $ves_id = $request->ves_id;
      $container_key = Item::where('ves_id', $ves_id)->whereNot('container_no', '')->whereIn('ctr_intern_status', ['01', '02', '56'])->get();
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

    public function shifting(Request $request)
    {
      $item = Item::where('container_key', $request->container_key)->first();
      $oldShip = Ship::where('ctr_i_e_t', '=', 'I')->where('container_key', $item->container_key)->first();
      $now = Carbon::now();
      $alat = MasterAlat::where('id', $request->id_alat)->first();
      $opr = Operator::where('id', $request->id_operator)->first();
      $ship = Ship::where('ves_id', $request->ves_id)->where('bay_slot', $request->bay_to)->where('bay_row', $request->row_to)->where('bay_tier', $request->tier_to)->first();
      if ($ship) {
        if ($ship->container_key == null) {
            $act_alat = ActAlat::create([
              'id_alat' =>  $request->id_alat,
              'category' => 'Bay',
              'nama_alat' => $alat->name,
              'operator_id'=>$request->id_operator,
              'operator' => $opr->name,
              'container_key' => $request->container_key,
              'container_no' => $item->container_no,
              'activity' => 'DISCH',
            ]);
            $actOper = ActOper::create([
              'alat_id' => $request->id_alat,
              'alat_category' =>$alat->category,
              'alat_name'  =>$alat->name,
              'operator_id'=>$request->id_operator,
              'operator_name'=>$opr->name,
              'container_key'=>$item->container_key,
              'container_no'=>$item->container_no,
              'ves_id'=>$item->ves_id,
              'ves_name'=>$item->ves_name,
              'voy_no'=>$item->voy_no,
              'activity' =>'SHIFT',
            ]);

            $shift = Shifting::create([
              'ves_id'=>$item->ves_id,
              'ves_code'=>$item->ves_code,
              'ves_name'=>$item->ves_name,
              'voy_no'=>$item->voy_no,
              'ves_name'=>$item->ves_name,
              'container_key'=>$item->container_key,
              'container_no'=>$item->container_no,
              'ctr_size'=>$item->ctr_size,
              'ctr_type'=>$item->ctr_type,
              'ctr_status'=>$item->ctr_status,
              'landing'=>$request->landing,
              'crane_d_k'=>$request->crane_d_k,
              'id_alat'=>$request->id_alat,
              'alat'=>$alat->name,
              'id_operator'=>$request->id_operator,
              'operator'=>$opr->name,
              'bay_from'=>$item->bay_slot,
              'row_from'=>$item->bay_row,
              'tier_from'=>$item->bay_tier,
              'bay_to'=>$request->bay_to,
              'row_to'=>$request->row_to,
              'tier_to'=>$request->tier_to,
            ]);
            $oldShip->update([
              'container_no'=>null,
              'container_key'=>null,
              'ctr_size'=>null,
              'ctr_type'=>null,
              'dangerous_yn'=>null,
              'ctr_i_e_t'=> null,
            ]);

            $ship->update([
              'container_no'=>$item->container_no,
              'container_key'=>$item->container_key,
              'ctr_size'=>$item->ctr_size,
              'ctr_type'=>$item->ctr_type,
              'dangerous_yn'=>$item->dangerous_yn,
              'ctr_i_e_t'=> "I",
          ]);

          $item->update([
            'bay_slot' => $request->bay_to,
            'bay_row' => $request->row_to,
            'bay_tier' => $request->tier_to,
        ]);

        return redirect()->back()->with('succsess', 'Berhasil');
        }else {
          return redirect()->back()->with('error', 'Bay Sudah Terisi');

        }
      }else {
        return redirect()->back()->with('error', 'Data Tidak Ditemukan');
      }

    }
}
