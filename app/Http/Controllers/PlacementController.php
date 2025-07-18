<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\VVoyage;
use Auth;
use App\Models\MasterAlat;
use App\Models\ActAlat;
use App\Models\Operator;
use App\Models\ActOper;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\HistoryController;

class PlacementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->history = app(HistoryController::class);
    }

    public function index()
    {
        $title = 'PLC';
        $confirmed = Item::whereIn('ctr_intern_status',  [03, 04, 51])->orderBy('update_time', 'desc')->get();
        $formattedData = [];
        $data = [];

        $data['containerMT'] = Item::whereIn('ctr_intern_status', [04, 12, 13, 14])->get();
        // dd($data['containerMT']['container_no']);
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');

        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        $data['truck'] = MasterAlat::where('category', '=', 'Truck')->get();
        $data['operator'] = Operator::where('role', '=', 'yard')->get();
        $data['supir'] = Operator::where('role', '=', 'truck')->get();
        $vessel = VVoyage::whereDate('etd_date', '>=', now())->get();
        $vessel_voyage = VVoyage::orderBy('deparature_date', 'desc')->get();
        return view('yard.place.main', compact('confirmed', 'formattedData', 'title',  'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'alat', 'vessel', 'vessel_voyage'), $data);
    }

    public function android()
    {
        $title = 'PLC';
        $confirmed = Item::whereIn('ctr_intern_status',  [03, 04, 51])->orderBy('update_time', 'desc')->get();
        $formattedData = [];
        $data = [];

        $data['containerMT'] = Item::whereIn('ctr_intern_status', ['04', '12', '13', '14'])->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');

        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        $data['truck'] = MasterAlat::where('category', '=', 'Truck')->get();
        $data['operator'] = Operator::where('role', '=', 'yard')->get();
        $data['supir'] = Operator::where('role', '=', 'truck')->get();
        $vessel = VVoyage::whereDate('etd_date', '>=', now())->get();
        $vessel_voyage = VVoyage::orderBy('deparature_date', 'desc')->get();
        return view('yard.place.android', compact('confirmed', 'formattedData', 'title',  'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'alat', 'vessel', 'vessel_voyage'), $data);
    }


    // public function get_tipe(Request $request)
    // {
    //     $container_no = $request->container_no;
    //     $name = Item::where('container_no', $container_no)->get();

    //     if ($name) {
    //         return response()->json(['key' => $name->container_key, 'tipe'=>$name->ctr_type,'coname'=>$name->commodity_name]);
    //     }
    //     return response()->json(['key' => 'data tidak ditemukan', 'tipe' => 'data tidak ditemukan', 'coname' => 'data tidak ditemukan']);
    // }

    public function tipe_container(Request $request)
    {
        $container_key = $request->container_key;
        $name = Item::where('container_key', $container_key)->first();

        if ($name) {
            return response()->json(['container_key' => $name->container_key, 'container_no' => $name->container_no, 'tipe' => $name->ctr_type, 'size' => $name->ctr_size, 'coname' => $name->commodity_name]);
        }
        return response()->json(['container_no' => 'data tidak ditemukan', 'tipe' => 'data tidak ditemukan', 'coname' => 'data tidak ditemukan']);
    }

    public function place(Request $request)
    {
        $container_key = $request->container_key;
        // var_dump($container_key);
        // die();
        $item = Item::where('container_key', $container_key)->first();
        $request->validate([
            'container_no' => 'required',
            'yard_block'  => 'required',
            'yard_slot'  => 'required',
            'yard_row'  => 'required',
            'yard_tier' => 'required',

        ], [
            'container_no.required' => 'Container Number is required.',
            'yard_block.required' => 'Block is required.',
            'yard_slot.required' => 'Slot Alat Number is required.',
            'yard_row.required' => 'Row Alat Number is required.',
            'yard_tier.required' => 'Tier Alat Number is required.',
        ]);

        $yard_rowtier = Yard::where('yard_block', $request->yard_block)
            ->where('yard_slot', $request->yard_slot)
            ->where('yard_row', $request->yard_row)
            ->where('yard_tier', $request->yard_tier)
            ->first();
        // var_dump($yard_rowtier, $request->yard_block, $request->yard_slot, $request->yard_tier, $request->yard_row);
        // die();
        if (empty($yard_rowtier->container_key)) {
            $id_alat = $request->alat;
            $alat = MasterAlat::where('id', $id_alat)->first();
            $opr = Operator::where('id', $request->operator)->first();
            // var_dump($opr);
            // die;
            $truck = MasterAlat::where('id', $request->truck)->first();
            $supir = Operator::where('id', $request->supir)->first();

            if ($item->ctr_intern_status === '13' || $item->ctr_intern_status === '12' || $item->ctr_intern_status === '14') {
                $item->update([
                    'yard_block' => $request->yard_block,
                    'yard_slot' => $request->yard_slot,
                    'yard_row' => $request->yard_row,
                    'yard_tier' => $request->yard_tier,
                    'ctr_intern_status' => ($item->ctr_intern_status === '12' || $item->ctr_intern_status === '13') ? '03' : (($item->ctr_intern_status === '14') ? '04' :  $item->ctr_intern_status),
                    'wharf_yard_oa' =>  $opr->name,
                    'alat_yard' => $alat->name,
                    'ht_no' => $truck->name ?? $item->ht_no ?? null,
                    'ht_driver' => $supir->name ?? $item->ht_driver ?? null,

                ]);

                $act_alat = ActAlat::create([
                    'id_alat' =>  $request->alat,
                    'category' => $alat->category,
                    'nama_alat' => $alat->name,
                    'operator_id'=>$request->operator,
                    'operator' => $opr->name,
                    'container_key' => $request->container_key,
                    'container_no' => $request->container_no,
                    'activity' => 'PLC',
                ]);

                $actOper = ActOper::create([
                    'alat_id' =>$request->alat,
                    'alat_category' =>$alat->category,
                    'alat_name'  =>$alat->name,
                    'operator_id'=>$request->operator,
                    'operator_name'=>$opr->name,
                    'container_key'=>$item->container_key,
                    'container_no'=>$item->container_no,
                    'ves_id'=>$item->ves_id,
                    'ves_name'=>$item->ves_name,
                    'voy_no'=>$item->voy_no,
                    'activity' =>'PLC',
                ]);

                if (!empty($request->truck)) {
                    $actTruck = ActAlat::create([
                        'id_alat' =>  $request->truck,
                        'category' => $truck->category,
                        'nama_alat' => $truck->name,
                        'operator_id'=>$request->supir,
                        'operator' => $supir->name,
                        'container_key' => $request->container_key,
                        'container_no' => $request->container_no,
                        'activity' => 'RELOKASI TO PLC',
                    ]);
    
                   
    
                    $actSupir = ActOper::create([
                        'alat_id' =>$request->truck,
                        'alat_category' =>$truck->category,
                        'alat_name'  => $truck->name,
                        'operator_id'=>$request->supir,
                        'operator_name'=>$supir->name,
                        'container_key'=>$item->container_key,
                        'container_no'=>$item->container_no,
                        'ves_id'=>$item->ves_id,
                        'ves_name'=>$item->ves_name,
                        'voy_no'=>$item->voy_no,
                        'activity' =>'RELOKASI TO PLC',
                    ]);
    
                }
               
               if ($item->ctr_intern_status == '03') {
                    $dataHistory = [
                      'container_key' => $item->container_key,
                      'container_no' => $item->container_no,
                      'operation_name' => 'PLC',
                      'ves_id' => $item->ves_id,
                      'ves_code' => $item->ves_code,
                      'voy_no' => $item->voy_no,
                      'ctr_i_e_t' => $item->ctr_i_e_t,
                      'ctr_active_yn' => $item->ctr_active_yn,
                      'ctr_size' => $item->ctr_size,
                      'ctr_type' => $item->ctr_type,
                      'ctr_status' => $item->ctr_status,
                      'ctr_intern_status' => $item->ctr_intern_status,
                      'yard_blok' => $item->yard_blok,
                      'yard_slot' => $item->yard_slot,
                      'yard_row' => $item->yard_row,
                      'yard_tier' => $item->yard_tier,
                      'truck_no' => $item->truck_no,
                      'truck_in_date' => $item->truck_in_date ? Carbon::parse($item->truck_in_date)->format('Y-m-d') : null,
                      'truck_out_date' => $item->truck_out_date ? Carbon::parse($item->truck_out_date)->format('Y-m-d') : null,
                      'oper_name' => Auth::user()->name,
                      'iso_code' => $item->iso_code,
                    ];
                
                    $historyContainer = $this->history->postHistoryContainer($dataHistory);
                }
            

                    return response()->json([
                        'success' => true,
                        'message' => 'updated successfully!',
                        'data'    => $item,
                    ]);
               
            } else {
                $item->update([
                    'yard_block' => $request->yard_block,
                    'yard_slot' => $request->yard_slot,
                    'yard_row' => $request->yard_row,
                    'yard_tier' => $request->yard_tier,
                    'ctr_intern_status' => ($item->ctr_intern_status === '02' || $item->ctr_intern_status === '03') ? '03' : (($item->ctr_intern_status === '50') ? '51' : $item->ctr_intern_status),
                    'wharf_yard_oa' =>  $opr->name,
                    'alat_yard' => $alat->name,
                    'ht_no' => $truck->name ?? $item->ht_no ?? null,
                    'ht_driver' => $supir->name ?? $item->ht_driver ?? null,
                    
                ]);

                $act_alat = ActAlat::create([
                    'id_alat' =>  $request->alat,
                    'category' => $alat->category,
                    'nama_alat' => $alat->name,
                    'operator_id'=>$request->operator,
                    'operator' => $opr->name,
                    'container_key' => $request->container_key,
                    'container_no' => $request->container_no,
                    'activity' => 'PLC',
                ]);
                if ($item->ctr_intern_status == '03') {
                   $ket = "DISCH TO PLC";
                }else {
                    $ket = "PLC TO LOAD";
                }

                $actOper = ActOper::create([
                    'alat_id' =>$request->alat,
                    'alat_category' =>$alat->category,
                    'alat_name'  =>$alat->name,
                    'operator_id'=>$request->operator,
                    'operator_name'=>$opr->name,
                    'container_key'=>$item->container_key,
                    'container_no'=>$item->container_no,
                    'ves_id'=>$item->ves_id,
                    'ves_name'=>$item->ves_name,
                    'voy_no'=>$item->voy_no,
                    'activity' =>'PLC',
                ]);

                if (!empty($request->truck)) {
                    $actTruck = ActAlat::create([
                        'id_alat' =>  $request->truck,
                        'category' => $truck->category,
                        'nama_alat' => $truck->name,
                        'operator_id'=>$request->supir,
                        'operator' => $supir->name,
                        'container_key' => $request->container_key,
                        'container_no' => $request->container_no,
                        'activity' => $ket,
                    ]);
    
                   
    
                    $actSupir = ActOper::create([
                        'alat_id' =>$request->truck,
                        'alat_category' =>$truck->category,
                        'alat_name'  => $truck->name,
                        'operator_id'=>$request->supir,
                        'operator_name'=>$supir->name,
                        'container_key'=>$item->container_key,
                        'container_no'=>$item->container_no,
                        'ves_id'=>$item->ves_id,
                        'ves_name'=>$item->ves_name,
                        'voy_no'=>$item->voy_no,
                        'activity' =>$ket,
                    ]);
                }
                if (in_array($item->ctr_intern_status, ['03', '51'])) {
                    $dataHistory = [
                      'container_key' => $item->container_key,
                      'container_no' => $item->container_no,
                      'operation_name' => ($item->ctr_intern_status == '03') ? 'PLC' : 'PLC-REC',
                      'ves_id' => $item->ves_id,
                      'ves_code' => $item->ves_code,
                      'voy_no' => $item->voy_no,
                      'ctr_i_e_t' => $item->ctr_i_e_t,
                      'ctr_active_yn' => $item->ctr_active_yn,
                      'ctr_size' => $item->ctr_size,
                      'ctr_type' => $item->ctr_type,
                      'ctr_status' => $item->ctr_status,
                      'ctr_intern_status' => $item->ctr_intern_status,
                      'yard_blok' => $item->yard_blok,
                      'yard_slot' => $item->yard_slot,
                      'yard_row' => $item->yard_row,
                      'yard_tier' => $item->yard_tier,
                      'truck_no' => $item->truck_no,
                      'truck_in_date' => $item->truck_in_date ? Carbon::parse($item->truck_in_date)->format('Y-m-d') : null,
                      'truck_out_date' => $item->truck_out_date ? Carbon::parse($item->truck_out_date)->format('Y-m-d') : null,
                      'oper_name' => Auth::user()->name,
                      'iso_code' => $item->iso_code,
                    ];
                
                    $historyContainer = $this->history->postHistoryContainer($dataHistory);
                }
               

                    return response()->json([
                        'success' => 400,
                        'message' => 'updated successfully!',
                        'data'    => $item,
                    ]);
             
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Yard sudah terisi!',
            ]);
        }
    }

    public function placeRelokasiMT(Request $request)
{
    $container_key = $request->container_key;
    $item = Item::where('container_key', $container_key)->first();
   
    $yard_rowtier = Yard::where('yard_block', $request->yard_block)
        ->where('yard_slot', $request->yard_slot)
        ->where('yard_row', $request->yard_row)
        ->where('yard_tier', $request->yard_tier)
        ->first();

        if ($yard_rowtier->container_key != null) {
            return redirect()->back()->with('error', 'Yard Sudah Terisi!');
        }
    
    if (empty($yard_rowtier->container_key)) {
        $id_alat = $request->alat;
        $alat = MasterAlat::where('id', $id_alat)->first();
        $opr = Operator::where('id', $request->operator)->first();
        $truck = MasterAlat::where('id', $request->truck)->first();
        $supir = Operator::where('id', $request->supir)->first();

        if ($item->ctr_intern_status == '14') {
            $status = '04';
            $keterangan = 'MT Balik';
        }else {
            $status = '03';
            $keterangan = 'RELOKASI TO PLC';
        }
       

        $item->update([
            'yard_block' => $request->yard_block,
            'yard_slot' => $request->yard_slot,
            'yard_row' => $request->yard_row,
            'yard_tier' => $request->yard_tier,
            'ctr_intern_status' => $status,
            'wharf_yard_oa' => $opr->name,
            'alat_yard' => $alat->name,
            'ht_no' => $truck->name ?? $item->ht_no ?? null,
            'ht_driver' => $supir->name ?? $item->ht_driver ?? null,
        ]);

        ActAlat::create([
            'id_alat' => $request->alat,
            'category' => $alat->category,
            'nama_alat' => $alat->name,
            'operator_id' => $request->operator,
            'operator' => $opr->name,
            'container_key' => $request->container_key,
            'container_no' => $item->container_no,
            'activity' => 'PLC',
        ]);

        ActOper::create([
            'alat_id' => $request->alat,
            'alat_category' => $alat->category,
            'alat_name' => $alat->name,
            'operator_id' => $request->operator,
            'operator_name' => $opr->name,
            'container_key' => $item->container_key,
            'container_no' => $item->container_no,
            'ves_id' => $item->ves_id,
            'ves_name' => $item->ves_name,
            'voy_no' => $item->voy_no,
            'activity' => 'PLC',
        ]);

        if (!empty($request->truck)) {
            ActAlat::create([
                'id_alat' => $request->truck,
                'category' => $truck->category,
                'nama_alat' => $truck->name,
                'operator_id' => $request->supir,
                'operator' => $supir->name,
                'container_key' => $request->container_key,
                'container_no' => $item->container_no,
                'activity' => $keterangan,
            ]);

            ActOper::create([
                'alat_id' => $request->truck,
                'alat_category' => $truck->category,
                'alat_name' => $truck->name,
                'operator_id' => $request->supir,
                'operator_name' => $supir->name,
                'container_key' => $item->container_key,
                'container_no' => $item->container_no,
                'ves_id' => $item->ves_id,
                'ves_name' => $item->ves_name,
                'voy_no' => $item->voy_no,
                'activity' => $keterangan,
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil di update!');
    } else {
        return redirect()->back()->with('error', 'Terjadi kesalahan!');
    }
}


    public function change(Request $request)
    {
         // return response()->json(['container_no' => 'data tidak ditemukan', 'job' => 'data tidak ditemukan', 'invoice' => 'data tidak ditemukan']);
         $client = new Client();

         $fields = [
             "container_key" => $request->container_key,
         ];
         // dd($fields, $item->getAttributes());
 
         $url = getenv('API_URL') . '/delivery-service/job/single/'. $request->container_key;
         $req = $client->get(
             $url
             
             
             
         );
         $response = $req->getBody()->getContents();
         $result = json_decode($response);
         // var_dump($response);
         // die();
         // dd($result);
         if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
             // $item->save();
 
             echo $response;
         } else {
             return response()->json(['container_no' => 'data tidak ditemukan', 'job' => 'data tidak ditemukan', 'invoice' => 'data tidak ditemukan']);
         }

       
    }

    public function place_mty(Request $request)
    {
        $key = $request->container_key;
        $item = Item::where('container_key', $key);

        if ($item) {

            $mty = $request->order_service;
            if ($mty === 'mtiks') {
             
                $item->update([
                    'ctr_intern_status' => '10',
                    'order_service' => $request->order_service,
                    'ves_id' => null,
                    'ves_name' => null,
                    'ves_code' => null,
                    'voy_no' => null,
                ]);
              
                $client = new Client();

                $fields = [
                    "container_key" => $request->container_key,
                    "jobId" => $request->id,
                    "ctr_intern_status" => "10",
                 
                ];

                $url = getenv('API_URL') . '/delivery-service/container/confirmGateIn';
                $req = $client->post(
                    $url,
                    [
                        "json" => $fields
                    ]
                );
                $response = $req->getBody()->getContents();
                $result = json_decode($response);
    
                // dd($result); 
                var_dump($result);
                die();
                if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
    
    
                    return response()->json([
                        'success' => 400,
                        'message' => 'updated successfully!',
                        'data'    => $item,
                    ]);
                } else {
                    return back();
                }

            } else {
                $id_alat = $request->alat;
                $alat = MasterAlat::where('id', $id_alat)->first();
                $item->update([
                    'ctr_intern_status' => '51',
                    'mty_type' => $request->mty_type,
                    'ves_id' => $request->ves_id,
                    'ves_name' => $request->ves_name,
                    'ves_code' => $request->ves_code,
                    'voy_no' => $request->voy_no,
                    'yard_block' => $request->yard_block,
                    'yard_slot' => $request->yard_slot,
                    'yard_row' => $request->yard_row,
                    'yard_tier' => $request->yard_tier,
                ]);

                $act_alat = ActAlat::create([
                    'id_alat' =>  $request->alat,
                    'category' => $alat->category,
                    'nama_alat' => $alat->name,
                    'operator' => $request->operator,
                    'container_key' => $request->container_key,
                    'container_no' => $request->container_no,
                    'activity' => 'PLC',
                ]);

                $ves_id = $request->ves_id;
                $kapal = VVoyage::where('ves_id', $ves_id)->first();

                $client = new Client();

                $fields = [
                    "container_key" => $request->container_key,
                    "jobId" => $request->id,
                    "ctr_intern_status" => "51",
                    "ves_id" => $request->ves_id,
                    "ves_name" => $request->ves_name,
                    "ves_code" => $request->ves_code,
                    "voy_no" => $request->voy_no,
                    "departure_date" => $kapal->deparature_date,
                    "closing_date" => $kapal->clossing_date,
                    "arrival_date" => $kapal->arrival_date,
                    'yard_block' => $request->yard_block,
                    'yard_slot' => $request->yard_slot,
                    'yard_row' => $request->yard_row,
                    'yard_tier' => $request->yard_tier,
                ];

                $url = getenv('API_URL') . '/delivery-service/container/confirmGateIn';
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
                        'success' => 400,
                        'message' => 'updated successfully!',
                        'data'    => $item,
                    ]);
                } else {
                    return back();
                }
            }

            // dd($fields, $item->getAttributes());

          
        }
    }

    public function get_cont(request $request)
    {
      $ves_id = $request->ves_id;
      $container_key = Item::where('ves_id', $ves_id)->whereNot('container_no', '')->whereIn('ctr_intern_status', [02, 03, 04, 12, 50, 51, 13, 14])->get();

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
}
