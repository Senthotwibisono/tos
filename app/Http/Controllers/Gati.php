<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\Job;
use App\Models\Isocode;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;


class Gati extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = 'Gate In Delivery';
        $confirmed = Item::where('ctr_intern_status', '=', 10,)->orderBy('truck_in_date', 'desc')->get();
        $formattedData = [];
        $data = [];

        foreach ($confirmed as $tem) {
            $now = Carbon::now();
            $updatedAt = Carbon::parse($tem->truck_in_date);

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
                'truck_in_date' => $diff . ' yang lalu',
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', '03')
            ->whereNotNull('job_no')
            ->pluck('container_no', 'container_key');
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');

        $client = new Client();
        // GET ALL JOB_CONTAINER
        $url_jobContainer = getenv('API_URL') . '/delivery-service/job/all';
        $req_jobContainer = $client->get($url_jobContainer);
        $response_jobContainer = $req_jobContainer->getBody()->getContents();
        $result_jobContainer = json_decode($response_jobContainer);
        // dd($result_jobContainer);
        // dd($containerKeys);

        $data["active"] = "delivery";
        $data["subactive"] = "gatein";
        $data["jobContainers"] = $result_jobContainer->data;
        return view('gate.delivery.main', $data, compact('confirmed', 'formattedData', 'title', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'), $data);
    }
    public function android()
    {
        $title = 'Gate In Delivery';
        $confirmed = Item::where('ctr_intern_status', '=', 10,)->orderBy('truck_in_date', 'desc')->get();
        $formattedData = [];

        foreach ($confirmed as $tem) {
            $now = Carbon::now();
            $updatedAt = Carbon::parse($tem->truck_in_date);

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
                'truck_in_date' => $diff . ' yang lalu',
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', '03')
            ->whereNotNull('job_no')
            ->pluck('container_no', 'container_key');
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');

         $client = new Client();
        // GET ALL JOB_CONTAINER
        $url_jobContainer = getenv('API_URL') . '/delivery-service/job/all';
        $req_jobContainer = $client->get($url_jobContainer);
        $response_jobContainer = $req_jobContainer->getBody()->getContents();
        $result_jobContainer = json_decode($response_jobContainer);
        // dd($result_jobContainer);
        // dd($containerKeys);

        $data["active"] = "delivery";
        $data["subactive"] = "gatein";
        $data["jobContainers"] = $result_jobContainer->data;
        return view('gate.delivery.android_in', compact('confirmed', 'formattedData', 'title', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'),$data);
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
        // $container_key = $request->container_key;
        // $name = Item::where('container_key', $container_key)->first();

        // if ($name) {
        //     return response()->json(['container_no' => $name->container_no, 'job' => $name->job_no, 'invoice' => $name->invoice_no]);
        // }
        // return response()->json(['container_no' => 'data tidak ditemukan', 'job' => 'data tidak ditemukan', 'invoice' => 'data tidak ditemukan']);
        $client = new Client();

        $fields = [
            "container_key" => $request->container_key,
        ];
        // dd($fields, $item->getAttributes());

        $url = getenv('API_URL') . '/delivery-service/job/containerbykey';
        $req = $client->post(
            $url,
            [
                "json" => $fields
            ]
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

    public function gati_del(Request $request)
    {


        $container_key = $request->container_key;
        // var_dump($request->job_no);
        // die();
        $item = Item::where('container_key', $container_key)->first();
        $cek_expired = Job::where('container_key', $container_key)->where('ACTIVE_TO', '<=', $request->truck_in_date)->exists();

        if ($cek_expired) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah melewati expired !!',
            ]);
        }

        $request->validate([
            'container_no' => 'required',
            'truck_no' => 'required',
        ], [
            'container_no.required' => 'Container Number is required.',
            'truck_no.required' => 'Truck Number is required.',
        ]);
        $item->update([
            'ctr_intern_status' => 10,
            'truck_no' => $request->truck_no,
            'truck_in_date' => $request->truck_in_date,
            'job_no' => $request->job_no,
            'invoice_no' => $request->invoice_no,
        ]);
        // var_dump($item);
        // die();
        $client = new Client();

        $fields = [
            "container_key" => $request->container_key,
            "ctr_intern_status" => "10",
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
            return back();
        }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Updated successfully!',
        //     'item' => $item,
        // ]);
    }

    //Reciving
    public function index_rec()
    {
        $title = 'Gate In Delivery';
        $confirmed = Item::where('ctr_intern_status', '=', 50,)->orderBy('truck_in_date', 'desc')->get();
        $formattedData = [];
        $data = [];

        foreach ($confirmed as $tem) {
            $now = Carbon::now();
            $updatedAt = Carbon::parse($tem->truck_in_date);

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
                'truck_in_date' => $diff . ' yang lalu',
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', '49')
            ->whereNotNull('job_no')
            ->pluck('container_no', 'container_key');
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $isocode = Isocode::all();

       

      
        return view('gate.recive.main', $data, compact('confirmed', 'formattedData', 'title', 'users','isocode', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'), $data);
    }

    public function data_container_rec(Request $request)
    {
        $container_key = $request->container_key;
        $name = Item::where('container_key', $container_key)->first();

        if ($name) {
            return response()->json(['container_no' => $name->container_no, 'gross' => $name->gross, 'iso_code' => $name->iso_code, 'seal_no' => $name->seal_no, 'bl_no' => $name->bl_no, 'size' => $name->ctr_size, 'type' => $name->ctr_type, 'stat' => $name->ctr_status,
            'vessel'=>$name->ves_name,
            'voy'=>$name->voy_no,
            'imo'=>$name->imo_code,
            'gross'=>$name->gross,
            'class'=>$name->gross_class,
            'pod'=>$name->disch_port,
            'seal'=>$name->seal_no,
            'oh'=>$name->over_height,
            'ow'=>$name->over_weight,
            'ol'=>$name->over_length
        ]);
        }
        return response()->json(['container_no' => 'data tidak ditemukan', 'gross' => 'data tidak ditemukan', 'iso_code' => 'data tidak ditemukan', 'bl_no' => 'data tidak ditemukan']);
       
    }
    public function gati_iso_rec(Request $request)
    {
        $iso_code = $request->iso_code;
        $type = Isocode::where('iso_code', $iso_code)->first();

        if ($type) {
            return response()->json(['type' => $type->iso_type, 'size' => $type->iso_size]);
        }
        return response()->json(['size' => 'data tidak ditemukan', 'type' => 'data tidak ditemukan']);
    }

    public function gati_rec(Request $request)
    {


        $container_key = $request->container_key;
        // var_dump($request->job_no);
        // die();
        $item = Item::where('container_key', $container_key)->first();
        
        $request->validate([
            'container_no' => 'required',
            'truck_no' => 'required',
        ], [
            'container_no.required' => 'Container Number is required.',
            'truck_no.required' => 'Truck Number is required.',
        ]);
        $item->update([
            'container_no' => $request->container_no,
            'ctr_intern_status' => 50,
            'truck_no' => $request->truck_no,
            'truck_in_date' => $request->truck_in_date,
            'gross' => $request->gross,
            'iso_code' => $request->iso_code,
            'bl_no' => $request->bl_no,
            'seal_no' => $request->seal_no,
            'ctr_type' => $request->ctr_type,
            'ctr_size' => $request->ctr_size,
            'ctr_status' => $request->ctr_status,
        ]);
        // var_dump($item);
        // die();
       

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'item' => $item,
        ]);
    }
}
