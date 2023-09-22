<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\RO;
use App\Models\RO_Gate;
use App\Models\RO_Realisasi;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;


class Gato extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = 'Gate Out Delivery';
        $confirmed = Item::where('ctr_intern_status', '=', '09',)->orderBy('update_time', 'desc')->get();
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
                'truck_no' => $tem->truck_no,
                'truck_out_date' => $diff . ' yang lalu',
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', '10')
            ->whereNotNull('truck_no')
            ->pluck('container_no', 'container_key');
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $data["active"] = "delivery";
        $data["subactive"] = "gateout";
        return view('gate.delivery.main_out', $data, compact('confirmed', 'title', 'formattedData', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'));
    }
    public function android()
    {
        $title = 'Gate Out Delivery';
        $confirmed = Item::where('ctr_intern_status', '=', '09',)->orderBy('update_time', 'desc')->get();
        $formattedData = [];

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
                'truck_no' => $tem->truck_no,
                'truck_out_date' => $diff . ' yang lalu',
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', '10')
            ->whereNotNull('job_no')
            ->pluck('container_no', 'container_key');
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        return view('gate.delivery.android_out', compact('confirmed', 'title', 'formattedData', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'));
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

    public function data_container(Request $request)
    {
        $container_key = $request->container_key;
        $name = Item::where('container_key', $container_key)->first();

        if ($name) {
            return response()->json(['container_no' => $name->container_no, 'job' => $name->job_no, 'invoice' => $name->invoice_no]);
        }
        return response()->json(['container_no' => 'data tidak ditemukan', 'job' => 'data tidak ditemukan', 'invoice' => 'data tidak ditemukan']);
    }

    public function gato_del(Request $request)
    {


        $container_key = $request->container_key;
        $item = Item::where('container_key', $container_key)->first();
        $request->validate([
            'container_no'=> 'required',
            'truck_no' => 'required',
        ], [
            'container_no.required' => 'Container Number is required.',
            'truck_no.required' => 'Truck Number is required.',
        ]);
        
        
        if ($item->truck_no === $request->truck_no) {
        $item->update([
            'ctr_intern_status' => '09',
            'truck_no' => $request->truck_no,
            'truck_out_date' => $request->truck_out_date,
        ]);
            $client = new Client();

            $fields = [
                "container_key" => $request->container_key,
                "ctr_intern_status" => "09",
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
            // var_dump($result);
            // die();
            if ($req->getStatusCode() == 200 || $req->getStatusCode() == 201) {
                // $item->save();

                return response()->json([
                    'success' => true,
                    'message' => 'updated successfully!',
                    'data'    => $item,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor Truck Berbeda Pada Saat Gate In !!',
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Nomor Truck Berbeda Pada Saat Gate In !!',
            ]);
        }
    }

    public function index_rec()
    {
        $title = 'Gate Out Reciving';
        $confirmed = Item::where('ctr_intern_status', '=', ['50', '51', '53'],)-> whereNotNull('truck_out_date')->whereNotNull('truck_no')->orderBy('update_time', 'desc')->get();
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
                'truck_no' => $tem->truck_no,
                'truck_out_date' => $diff . ' yang lalu',
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', ['50', '51', '53', '56'])
            ->whereNotNull('truck_in_date')->where('truck_out_date', '=', null)
            ->pluck('container_no', 'container_key');
        $users = User::all();
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $data["active"] = "delivery";
        $data["subactive"] = "gateout";
        return view('gate.recive.main_out', $data, compact('confirmed', 'title', 'formattedData', 'users', 'currentDateTimeString','containerKeys'));
    }
    public function android_rec()
    {
        $title = 'Gate Out Reciving';
        $confirmed = Item::where('ctr_intern_status', '=', '51',)-> whereNotNull('truck_out_date')->whereNotNull('truck_no')->orderBy('update_time', 'desc')->get();
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
                'truck_no' => $tem->truck_no,
                'truck_out_date' => $diff . ' yang lalu',
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', ['51', '53', '56'])
            ->whereNotNull('truck_in_date')->where('truck_out_date', '=', null)
            ->pluck('container_no', 'container_key');
        $users = User::all();
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $data["active"] = "delivery";
        $data["subactive"] = "gateout";
        return view('gate.recive.android_out', $data, compact('confirmed', 'title', 'formattedData', 'users', 'currentDateTimeString','containerKeys'));
    }

    public function data_container_rec(Request $request)
    {
        $container_key = $request->container_key;
        $name = Item::where('container_key', $container_key)->first();

        if ($name) {
            return response()->json(['container_no' => $name->container_no, 'job' => $name->job_no, 'invoice' => $name->invoice_no]);
        }
        return response()->json(['container_no' => 'data tidak ditemukan', 'job' => 'data tidak ditemukan', 'invoice' => 'data tidak ditemukan']);
    }

    public function gato_rec(Request $request)
    {


        $container_key = $request->container_key;
        $item = Item::where('container_key', $container_key)->first();
        $request->validate([
            'container_no'=> 'required',
            'truck_no' => 'required',
        ], [
            'container_no.required' => 'Container Number is required.',
            'truck_no.required' => 'Truck Number is required.',
        ]);
        
        
        if ($item->truck_no === $request->truck_no) {
        $item->update([
            'truck_no' => $request->truck_no,
            'truck_out_date' => $request->truck_out_date,
        ]);
            

                return response()->json([
                    'success' => true,
                    'message' => 'updated successfully!',
                    'data'    => $item,
                ]);
           
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Nomor Truck Berbeda Pada Saat Gate In !!',
            ]);
        }
    }

    public function index_stuf_out()
    {
        $title="Gate-out Stuffing";
        $ro = RO_Gate::whereIn('status', ['2', '5', '8'])->get();
        $ro_table = RO_Gate::where('truck_out_date', '!=', null)->orderBy('update_time', 'desc')->take(3)->get();
        return view('gate.stuffing.gate-out', compact('title'), compact('ro', 'ro_table'));
    }

    public function gato_stuf(Request $request)
    {
        $now = Carbon::now();
        $id = $request->ro_id_gati;
        $truck = RO_Gate::where('ro_id_gati', '=', $id)->first();

        if ($truck->status === '2') {
            $truck->update([
                'truck_out_date' => $now,
                'status' => '3',
            ]);
           
    
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Post',
                'data' => $truck,
                
            ]);
        } elseif ($truck->status === '5') {
            $truck->update([
                'truck_out_date' => $now,
                'status' => '6',
            ]);
           
    
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Post',
                'data' => $truck,
                
            ]);
        }elseif ($truck->status === '8') {
            $truck->update([
                'truck_out_date' => $now,
                'status' => '9',
            ]);
           
    
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Post',
                'data' => $truck,
                
            ]);
        }
         else {
            return response()->json([
                'success' => false,
                'message' => 'Detail Data Post',
                
                
            ]);
        }
        
    }

}
