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
use GuzzleHttp\Client;
use Illuminate\Http\Request;



class PlacementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'PLC';
        $confirmed = Item::whereIn('ctr_intern_status',  [03, 04, 51])->orderBy('update_time', 'desc')->get();
        $formattedData = [];
        $data = [];

        foreach ($confirmed as $tem) {
            $now = Carbon::now();
            $updatedAt = Carbon::parse($tem->update_time);

            // Perhitungan selisih waktu
            $diff = $updatedAt->diffForHumans($now);

            // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
            if ($updatedAt->diffInDays($now) < 1) {
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
            } else {
                // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['days', 'day', 'hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['hari', 'hari', 'jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
            }

            $formattedData[] = [
                'container_key' => $tem->container_key,
                'container_no' => $tem->container_no,
                'ctr_type' => $tem->ctr_type,
                'ctr_status' => $tem->ctr_status,
                'yard_block' => $tem->yard_block,
                'yard_slot' => $tem->yard_slot,
                'yard_row' => $tem->yard_row,
                'yard_tier' => $tem->yard_tier,
                'update_time' => $diff . ' yang lalu',
                'container_key' => $tem->container_key,
                'ctr_intern_status' => $tem->ctr_intern_status,
                'order_service' => $tem->order_service,

            ];
        }
        $items = Item::whereIn('ctr_intern_status', [02, 03, 04, 12, 50, 51, 13, 14])->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $data["active"] = "yard";
        $data["subactive"] = "placement";
        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        $vessel = VVoyage::whereDate('etd_date', '>=', now())->get();
        return view('yard.place.main', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'alat', 'vessel'), $data);
    }

    public function android()
    {
        $title = 'PLC';
        $confirmed = Item::whereIn('ctr_intern_status',  [03, 04, 51])->orderBy('update_time', 'desc')->get();
        $formattedData = [];
        $data = [];

        foreach ($confirmed as $tem) {
            $now = Carbon::now();
            $updatedAt = Carbon::parse($tem->update_time);

            // Perhitungan selisih waktu
            $diff = $updatedAt->diffForHumans($now);

            // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
            if ($updatedAt->diffInDays($now) < 1) {
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
            } else {
                // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
                $diff = $updatedAt->diffForHumans($now, true);
                $diff = str_replace(['days', 'day', 'hours', 'hour', 'minutes', 'minutes', 'seconds', 'seconds'], ['hari', 'hari', 'jam', 'jam', 'menit', 'menit', 'detik', 'detik'], $diff);
            }

            $formattedData[] = [
                'container_no' => $tem->container_no,
                'ctr_type' => $tem->ctr_type,
                'yard_block' => $tem->yard_block,
                'yard_slot' => $tem->yard_slot,
                'yard_row' => $tem->yard_row,
                'yard_tier' => $tem->yard_tier,
                'update_time' => $diff . ' yang lalu',
                'container_key' => $tem->container_key,
                'ctr_intern_status' => $tem->ctr_intern_status,
                'order_service' => $tem->order_service,

            ];
        }
        $items = Item::whereIn('ctr_intern_status', [02, 03, 04, 50, 51, 13])->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $data["active"] = "yard";
        $data["subactive"] = "placement";
        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        return view('yard.place.android-yard', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'alat'), $data);
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

        if (is_null($yard_rowtier->container_key)) {
            $id_alat = $request->alat;
            $alat = MasterAlat::where('id', $id_alat)->first();

            if ($item->ctr_intern_status === '13' || $item->ctr_intern_status === '12' || $item->ctr_intern_status === '14') {
                $item->update([
                    'yard_block' => $request->yard_block,
                    'yard_slot' => $request->yard_slot,
                    'yard_row' => $request->yard_row,
                    'yard_tier' => $request->yard_tier,
                    'ctr_intern_status' => ($item->ctr_intern_status === '12' || $item->ctr_intern_status === '13') ? '03' : (($item->ctr_intern_status === '14') ? '04' :  $item->ctr_intern_status),
                    'wharf_yard_oa' => $request->wharf_yard_oa,
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
    
    
                $client = new Client();
    
                $fields = [
                    "container_key" => $request->container_key,
                    "ctr_intern_status" => ($item->ctr_intern_status === '12' || $item->ctr_intern_status === '13') ? '03' : (($item->ctr_intern_status === '14') ? '04' :  $item->ctr_intern_status),
                    'yard_block' => $request->yard_block,
                    'yard_slot' => $request->yard_slot,
                    'yard_row' => $request->yard_row,
                    'yard_tier' => $request->yard_tier,
                ];
                // dd($fields, $item->getAttributes());
    
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
                    $item->save();
    
                    return response()->json([
                        'success' => 400,
                        'message' => 'updated successfully!',
                        'data'    => $item,
                    ]);
                } else {
                    return back();
                }
            } else {
                $item->update([
                    'yard_block' => $request->yard_block,
                    'yard_slot' => $request->yard_slot,
                    'yard_row' => $request->yard_row,
                    'yard_tier' => $request->yard_tier,
                    'ctr_intern_status' => ($item->ctr_intern_status === '02' || $item->ctr_intern_status === '03') ? '03' : (($item->ctr_intern_status === '50') ? '51' : $item->ctr_intern_status),
                    'wharf_yard_oa' => $request->wharf_yard_oa,
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
    
    
                $client = new Client();
    
                $fields = [
                    "container_key" => $request->container_key,
                    "ctr_intern_status" => ($item->ctr_intern_status === '02' || $item->ctr_intern_status === '03') ? '03' : (($item->ctr_intern_status === '50') ? '51' : $item->ctr_intern_status),
                    'yard_block' => $request->yard_block,
                    'yard_slot' => $request->yard_slot,
                    'yard_row' => $request->yard_row,
                    'yard_tier' => $request->yard_tier,
                ];
                // dd($fields, $item->getAttributes());
    
                $url = getenv('API_URL') . '/delivery-service/container/confirmPlacement';
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
    
                    return response()->json([
                        'success' => 400,
                        'message' => 'updated successfully!',
                        'data'    => $item,
                    ]);
                } else {
                    return back();
                }
            }
           
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Yard sudah terisi!',
            ]);
        }
    }

    public function change(Request $request)
    {
        $id = $request->container_key;
        $item = Item::where('container_key', '=', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ]);
    }

    public function place_mty(Request $request)
    {
        $key = $request->container_key;
        $item = Item::where('container_key', $key);

        if ($item) {

            $mty = $request->mty_type;
            if ($mty === '03') {
                $item->update([
                    'ctr_intern_status' => '08',
                    'mty_type' => $request->mty_type,
                    'ves_id' => null,
                    'ves_name' => null,
                    'ves_code' => null,
                    'voy_no' => null,
                ]);
                $client = new Client();
        
                $fields = [
                    "container_key" => $request->container_key,
                    "ctr_intern_status" => "08",
                    "mty_type" => $request->mty_type,
                    "ves_id" => null,
                    "ves_name" => null,
                    "ves_code" => null,
                    "voy_no" => null,
                ];
            }else {
                $item->update([
                    'ctr_intern_status' => '08',
                    'mty_type' => $request->mty_type,
                    'ves_id' => $request->ves_id,
                    'ves_name' => $request->ves_name,
                    'ves_code' => $request->ves_code,
                    'voy_no' => $request->voy_no,
                ]);

                $ves_id = $request->ves_id;
                $kapal = VVoyage::where('ves_id', $ves_id)->first();

                $client = new Client();
        
                $fields = [
                    "container_key" => $request->container_key,
                    "ctr_intern_status" => "08",
                    "mty_type" => $request->mty_type,
                    "ves_id" => $request->ves_id,
                    "ves_name" => $request->ves_name,
                    "ves_code" => $request->ves_code,
                    "voy_no" => $request->voy_no,
                    "departure_date" => $kapal->deparature_date,
                    "closing_date" => $kapal->clossing_date,
                    "arrival_date" => $kapal->arrival_date,
                ];
            }
           
            // dd($fields, $item->getAttributes());

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
    }
}
