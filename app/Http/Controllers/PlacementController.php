<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use Auth;
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
                'container_no' => $tem->container_no,
                'ctr_type' => $tem->ctr_type,
                'yard_block' => $tem->yard_block,
                'yard_slot' => $tem->yard_slot,
                'yard_row' => $tem->yard_row,
                'yard_tier' => $tem->yard_tier,
                'update_time' => $diff . ' yang lalu',
                'container_key' => $tem->container_key,
                'ctr_intern_status' => $tem->ctr_intern_status
            ];
        }
        $items = Item::whereIn('ctr_intern_status', [02, 03, 04, 50, 51])->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $data["active"] = "yard";
        $data["subactive"] = "placement";
        return view('yard.place.main', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier'), $data);
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
                'ctr_intern_status' => $tem->ctr_intern_status
            ];
        }
        $items = Item::whereIn('ctr_intern_status', [02, 03, 04, 50, 51])->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $data["active"] = "yard";
        $data["subactive"] = "placement";
        return view('yard.place.android-yard', compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier'), $data);
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
            $item->update([
                'yard_block' => $request->yard_block,
                'yard_slot' => $request->yard_slot,
                'yard_row' => $request->yard_row,
                'yard_tier' => $request->yard_tier,
                'ctr_intern_status' => ($item->ctr_intern_status === '02' || $item->ctr_intern_status === '03') ? '03' : (($item->ctr_intern_status === '50') ? '51' : $item->ctr_intern_status),
                'wharf_yard_oa' => $request->wharf_yard_oa,
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
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Yard sudah terisi!',
            ]);
        }
    }
}
