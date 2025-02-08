<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\VVoyage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterAlat;
use App\Models\ActAlat;
use App\Models\Operator;
use App\Models\Ship;
use App\Models\ActOper;
use GuzzleHttp\Client;

use Auth;
use Illuminate\Http\Request;

use DataTables;

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
    $items = Item::where('ctr_i_e_t', '=', 'E')
    ->where(function ($query) {
        $query->where('ctr_intern_status', '=', 51)
            ->orWhere('ctr_intern_status', '=', 53);
    })->orWhere(function ($query) {
        $query->where('ctr_intern_status', '=', '08')
            ->where(function ($query) {
                $query->where('mty_type', '=', '01')
                    ->orWhere('mty_type', '=', '02');
            });
    })->orWhere(function ($query) {
        $query->where('ctr_intern_status', '=', '49')
            ->whereHas('service', function ($query) {
                $query->where('order', '=', 'MTI');
            });
    })->orWhere(function ($query) {
      $query->where('ctr_intern_status', '=', '14');
  })->orWhere(function ($query) {
    $query->whereIn('ctr_intern_status', ['03', '04'])->where('ctr_status', '=', 'MTY');
})->get();

    $users = User::all();
    $data["active"] = "discharge";
    $data["subactive"] = "confirm";
    $currentDateTime = Carbon::now();
    $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
    $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();
    $alat = MasterAlat::where('category', '=', 'Bay')->get();
    $data['operator'] = Operator::where('role', '=', 'cc')->get();

    return view('load.main', compact('title', 'items', 'users', 'currentDateTimeString', 'vessel_voyage', 'alat'), $data);
  }


  public function dataTable(Request $request)
  {
      $cont = Item::where('ctr_i_e_t', '=', 'E')
                  ->where('ctr_intern_status', '=', '56');
  
      return DataTables::of($cont)
          ->addColumn('veseel', function($cont) {
              // Gabungkan nama kapal dan nomor voyage
              return $cont->ves_name . ' || ' . $cont->voy_no ?? '-';
          })
          ->addColumn('container', function($cont) {
              // Pastikan nomor container tidak null
              return $cont->container_no ?? '-';
          })
          ->addColumn('slot', function($cont) {
              // Gabungkan slot, row, dan tier
              $slot = $cont->bay_slot ?? '-';
              $row = $cont->bay_row ?? '-';
              $tier = $cont->tier ?? '-';
  
              return "{$slot} || {$row} || {$tier}";
          })
          ->addColumn('alat', function($cont) {
              // Pastikan alat tidak null
              return $cont->cc_tt_no ?? '-';
          })
          ->addColumn('operator', function($cont) {
              // Pastikan operator tidak null
              return $cont->cc_tt_oper ?? '-';
          })
          ->addColumn('laod_date', function($cont) {
              // Format tanggal jika diperlukan
              return $cont->load_date ?? '-';
          })
          ->addColumn('action1', function($cont){
            return '<button class="btn btn-outline-info EditBay" data-id="'.$cont->container_key.'">Edit Bay Plan</button>';
          })
          ->addColumn('action2', function($cont){
            return '<button class="btn btn-outline-danger CancelBay" data-id="'.$cont->container_key.'">Cancel</button>';
          })
          ->rawColumns(['slot', 'action1', 'action2']) // Jika ada kolom yang memerlukan rendering HTML
          ->filter(function ($query) use ($request) {
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('ves_name', 'like', "%{$search}%")
                      ->orWhere('voy_no', 'like', "%{$search}%")
                      ->orWhere('container_no', 'like', "%{$search}%");
                });
            }
        })
          ->make(true);
  }
  
  //android
  public function android()
  {
    $title = 'Confirm Load';
    $subtitle = 'Discharge Load';
    $formattedData = [];
    $data = [];
    $items = Item::where('ctr_i_e_t', '=', 'E')
    ->where(function ($query) {
        $query->where('ctr_intern_status', '=', 51)
            ->orWhere('ctr_intern_status', '=', 53);
    })->orWhere(function ($query) {
        $query->where('ctr_intern_status', '=', '08')
            ->where(function ($query) {
                $query->where('mty_type', '=', '01')
                    ->orWhere('mty_type', '=', '02');
            });
    })->orWhere(function ($query) {
        $query->where('ctr_intern_status', '=', '49')
            ->whereHas('service', function ($query) {
                $query->where('order', '=', 'MTI');
            });
    })->orWhere(function ($query) {
    $query->where('ctr_intern_status', '=', '14');
})->orWhere(function ($query) {
  $query->whereIn('ctr_intern_status', ['03', '04'])->where('ctr_status', '=', 'MTY');
})->get();

    $users = User::all();
    $data["active"] = "discharge";
    $data["subactive"] = "confirm";
    $currentDateTime = Carbon::now();
    $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
    $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->orderBy('deparature_date', 'desc')->get();
    $alat = MasterAlat::where('category', '=', 'Bay')->get();
    $data['operator'] = Operator::where('role', '=', 'cc')->get();

    return view('load.android', compact('title', 'items', 'users', 'currentDateTimeString', 'vessel_voyage', 'alat'), $data);
  }

  public function get_cont(Request $request)
{
    $ves_id = $request->ves_id;

    $container_key = Item::where(function ($query) use ($ves_id) {
        // When ctr_i_e_t is 'E', filter by ves_id and ctr_intern_status
        $query->where('ctr_i_e_t', 'E')
              ->where('ves_id', $ves_id)
              ->whereIn('ctr_intern_status', ['50', '51', '53']);
    })
    ->orWhere(function ($query) {
        // For MTY containers, check only ctr_status and ctr_intern_status
        $query->where('ctr_status', 'MTY')
              ->whereIn('ctr_intern_status', ['14', '04', '03']);
    })
    ->orWhere(function ($query) {
        // For MTY containers, check only ctr_status and ctr_intern_status
        $query->where('ves_code', 'NTG')
              ->whereIn('ctr_intern_status', ['50', '51', '53']);
    })
    ->get();

    $option = []; // Initialize $option as an empty array

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
    $kapal = VVoyage::where('ves_id', $request->ves_id)->first();
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
    $ship = Ship::where('ves_id', $kapal->ves_id)->where('bay_slot', $request->bay_slot)->where('bay_row', $request->bay_row)->where('bay_tier', $request->bay_tier)->first();
    if ($ship) {
      if ($ship->container_key == null) {
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
            'ves_id'=>$kapal->ves_id,
            'ves_name'=>$kapal->ves_name,
            'voy_no'=>$kapal->voy_no,
            'activity' =>'LOAD',
        ]);
        $oldItem = Item::where('container_no', $item->container_no)->where('ctr_intern_status', '=', '04')->first();
        if ($oldItem) {
            $oldItem->update([
                'ctr_intern_status'=>'09',
                'ctr_active_yn'=> 'N',
                'os_id'=>null,
            ]);
        }
        Item::where('container_key', $container_key)->update([
          'bay_slot' => $request->bay_slot,
          'bay_row' => $request->bay_row,
          'bay_tier' => $request->bay_tier,
          'load_date' => $request->load_date,
          'cc_tt_no'  => $alat->name,
          'cc_tt_oper'  => $opr->name,
          'ctr_intern_status' => '56',
          'ctr_active_yn' => 'N',
          'ctr_i_e_t' => 'E',
          'ves_id'=>$kapal->ves_id,
          'ves_code'=>$kapal->ves_code,
          'ves_name'=>$kapal->ves_name,
          'voy_no'=>$kapal->voy_out,
        ]);
        $ship->update([
          'container_no'=>$item->container_no,
          'container_key'=>$item->container_key,
          'ctr_size'=>$item->ctr_size,
          'ctr_type'=>$item->ctr_type,
          'dangerous_yn'=>$item->dangerous_yn,
          'ctr_i_e_t'=> "E",
      ]);
    
      return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'item' => $item,
      ]);
    }else {
      return response()->json([
        'success' => false,
        'message' => 'Bay Sudah Terisi, Silahkan pilih Bay Lain !!',
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
   
  }

  public function cancelBay($id)
  {
    $cont = Item::find($id);
    if ($cont) {
      $ship = $cont->ves_id;
      $bay = Ship::where('ves_id', $ship)->where('container_key', $cont->container_key)->where('ctr_i_e_t', '=',  'E')->first();
      // dd($cont, $bay);
      if ($bay) {
        # code...
        $bay->update([
            'container_no'=>null,
            'container_key'=>null,
            'ctr_size'=>null,
            'ctr_type'=>null,
            'dangerous_yn'=>null,
            'ctr_i_e_t'=> null,
        ]);
      }
      $cont->update([
        'bay_slot' => null,
        'bay_row' => null,
        'bay_tier' => null,
        'ctr_intern_status' => 50,
     ]);
     return back()->with('success', 'Bay Berhasil di Update');
    }else {
      return back()->with('error', 'Bay Tidak Ditemukan');
    }
  }

  public function bay_edit($id)
  {
    
    $item = Item::where('container_key', $id)->first();
    // var_dump($item->container_no);
    // die;
    if ($item) {
      $ship = $item->ves_id;
      $bay = Ship::where('ves_id', $ship)->where('container_key', $item->container_key)->where('ctr_i_e_t', '=',  'E')->first();
      //  var_dump($bay);
      //  die;
      
          return response()->json([
            'success' => true,
            'data'    => $item,
            'bay' => $bay,
          ]);
     
          return response()->json([
            'success' => false,
           ]);

    }else {
          return response()->json([
            'success' => false,
           ]);
    }
  }

  public function bay_update(Request $request)
  {
    $item = Item::where('container_key', $request->container_key)->first();
    $ves = $request->ves_id;
    $ship = Ship::where('ves_id', $request->ves_id)->where('bay_slot', $request->bay_slot)->where('bay_row', $request->bay_row)->where('bay_tier', $request->bay_tier)->first();
    if ($ship) {
      if ($ship->container_key == $item->container_key) {
        return back()->with('error', 'Tidak ada Perubahan, Mohon Cek Kembali!!');
      }elseif ($ship->container_key != null) {
        $oldCont = $ship->container_no;
        $message = 'Bay Sudah Terisi Dengan Container '.$oldCont.' !!';
        return back()->with('error', $message);
      }else {
       $oldShip = Ship::where('ves_id', $item->ves_id)->where('bay_slot', $item->bay_slot)->where('bay_row', $item->bay_row)->where('bay_tier', $item->bay_tier)->first();
         $ship->update([
          'container_no'=>$item->container_no,
          'container_key'=>$item->container_key,
          'ctr_size'=>$item->ctr_size,
          'ctr_type'=>$item->ctr_type,
          'dangerous_yn'=>$item->dangerous_yn,
          'ctr_i_e_t'=> "E",
         ]);
         $oldShip->update([
          'container_no'=>null,
          'container_key'=>null,
          'ctr_size'=>null,
          'ctr_type'=>null,
          'dangerous_yn'=>null,
          'ctr_i_e_t'=> null,
         ]);
         $item->update([
            'bay_slot' => $ship->bay_slot,
            'bay_row' => $ship->bay_row,
            'bay_tier' => $ship->bay_tier,
         ]);
         return back()->with('success', 'Bay Berhasil di Update');
      }
    }else {
      return back()->with('error', 'Bay Tidak Ditemukan');
    }
  }
}
