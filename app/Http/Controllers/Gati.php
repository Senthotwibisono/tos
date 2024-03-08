<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\JobImport as Job;
use App\Models\Isocode;
use App\Models\RO;
use App\Models\RO_Gate;
use App\Models\RO_Realisasi;

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
        $confirmed = Item::where('ctr_intern_status', '=', '10',)->orWhere(function ($subquery) {
            $subquery->where('ctr_intern_status', '03')
                ->where(function ($subsubquery) {
                    $subsubquery->where('order_service', 'spps')
                        ->orWhere('order_service', 'sppsrelokasipelindo')
                        ->whereNotNull('truck_no');
                });
        })->orderBy('truck_in_date', 'desc')->get();
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
                'ctr_size' => $tem->ctr_size,
                'ctr_type' => $tem->ctr_type,
                'ves_name' => $tem->ves_name,
                'voy_no' => $tem->voy_no,
                'truck_no' => $tem->truck_no,
                'truck_in_date' => $tem->truck_in_date,
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

      
        $data['contGati'] = Item::whereIn('ctr_intern_status', ['03', '04', '11'])->whereNotNull('job_no')->get();

        $data["active"] = "delivery";
        $data["subactive"] = "gatein";
        return view('gate.delivery.main', $data, compact('confirmed', 'formattedData', 'title', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'), $data);
    }
    public function android()
    {
        $title = 'Gate In Delivery';
        $confirmed = Item::where('ctr_intern_status', '=', '10',)->orWhere(function ($subquery) {
            $subquery->where('ctr_intern_status', '03')
                ->where(function ($subsubquery) {
                    $subsubquery->where('order_service', 'spps')
                        ->orWhere('order_service', 'sppsrelokasipelindo')
                        ->whereNotNull('truck_no');
                });
        })->orderBy('truck_in_date', 'desc')->get();
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
                'ctr_size' => $tem->ctr_size,
                'ctr_type' => $tem->ctr_type,
                'ves_name' => $tem->ves_name,
                'voy_no' => $tem->voy_no,
                'truck_no' => $tem->truck_no,
                'truck_in_date' => $tem->truck_in_date,
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

        $data['contGati'] = Item::whereIn('ctr_intern_status', ['03', '04', '11'])->whereNotNull('job_no')->get();


        $data["active"] = "delivery";
        $data["subactive"] = "gatein";
        return view('gate.delivery.android_in', $data, compact('confirmed', 'formattedData', 'title', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'), $data);
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
        $item = Item::where('container_key', $request->container_key)->first();
        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $item,
            ]);
        }
    }

    public function gati_del(Request $request)
    {


        $container_key = $request->container_key;
        // var_dump($request->job_no);
        // die();
        $item = Item::where('container_key', $container_key)->first();
        $cek_expired = Job::where('job_no', $item->job_no)->where('active_to', '<=', $request->truck_in_date)->exists();

        if ($item->ctr_i_e_t == 'I') {
            if ($cek_expired) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sudah melewati expired !!',
                ]);
            }
        }
        
      

        $request->validate([
            'container_no' => 'required',
            'truck_no' => 'required',
        ], [
            'container_no.required' => 'Container Number is required.',
            'truck_no.required' => 'Truck Number is required.',
        ]);

        $order_service = $item->order_service;

        if (($order_service === 'SPPSRELOKASI') || ($order_service === 'SPPS')) {

            $item->update([
                'truck_no' => $request->truck_no,
                'truck_in_date' => $request->truck_in_date,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $item,
            ]);
        } else {
            $item->update([
                'ctr_intern_status' => 10,
                'truck_no' => $request->truck_no,
                'truck_in_date' => $request->truck_in_date,
                'no_dok' => $request->no_dok,
                'jenis_dok' => $request->jenis_dok,
            ]);
            // var_dump($item);
            // die();
         

                return response()->json([
                    'success' => true,
                    'message' => 'updated successfully!',
                    'data'    => $item,
                ]);
           
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
        $title = 'Gate In Recivng';
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
        $containerKeys = Item::where('ctr_intern_status', '49')->whereNotNull('job_no')->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $isocode = Isocode::all();

       
        return view('gate.recive.main', $data, compact('confirmed', 'formattedData', 'title', 'users', 'isocode', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'), $data);
    }


    public function android_rec()
    {
        $title = 'Gate In Receivng';
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
        $containerKeys = Item::where('ctr_intern_status', '49')->whereNotNull('job_no')->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $isocode = Isocode::all();

     
        return view('gate.recive.android_in', $data, compact('confirmed', 'formattedData', 'title', 'users', 'isocode', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'), $data);
    }

  

    public function get_data_reciving(Request $request)
    {
        $container_key = $request->id;
        // var_dump($container_key);
        // die();
        $name = Item::where('container_key', $container_key)->first();
        if ($name) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $name,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ]);
        }

        // if ($name) {
        //     return response()->json(['container_no' => $name->container_no, 'gross' => $name->gross, 'iso_code' => $name->iso_code, 'seal_no' => $name->seal_no, 'bl_no' => $name->bl_no, 'size' => $name->ctr_size, 'type' => $name->ctr_type, 'stat' => $name->ctr_status,
        //     'vessel'=>$name->ves_name,
        //     'voy'=>$name->voy_no,
        //     'imo'=>$name->imo_code,
        //     'gross'=>$name->gross,
        //     'class'=>$name->gross_class,
        //     'pod'=>$name->disch_port,
        //     'seal'=>$name->seal_no,
        //     'oh'=>$name->over_height,
        //     'ow'=>$name->over_weight,
        //     'ol'=>$name->over_length
        // ]);
        // }
        // return response()->json(['container_no' => 'data tidak ditemukan', 'gross' => 'data tidak ditemukan', 'iso_code' => 'data tidak ditemukan', 'bl_no' => 'data tidak ditemukan']);
     
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

        $container_key = $request->container_no;
        // var_dump($container_key);
        // die();
        $item = Item::where('container_key', $container_key)->first();
        if ($item) {
            if ($request->iso_code != null) {
                $iso_code = $request->iso_code;
            }else {
                $iso_code = $item->iso_code;

            }

            if ($request->ctr_type != null) {
                $ctr_type = $request->ctr_type;
            }else {
                $ctr_type = $item->ctr_type;
            }

            if ($request->ctr_size != null) {
                $ctr_size = $request->ctr_size;
            }else {
                $ctr_size = $item->ctr_size;
            }

            if ($request->ctr_status != null) {
                $ctr_status = $request->ctr_status;
            }else {
                $ctr_status = $item->ctr_status;

            }

            $item->update([
                'ctr_intern_status' => 50,
                'truck_no' => $request->truck_no,
                'truck_in_date' => $request->truck_in_date,
                'gross' => $request->gross,
                'iso_code' => $iso_code,
                'ctr_type' => $ctr_type,
                'ctr_size' => $ctr_size,
                'ctr_status' => $ctr_status,
                'user_id' => $request->user_id,
                'ctr_active_yn' => 'Y',
                'ctr_i_e_t' => 'E',
             
            ]);       
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $item,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'updated successfully!',
            ]);
        }
      
    }


    // Stuffing Gate
    public function index_stuf()
    {
        $title = "Gate-In Stuffing";
        $ro = RO_Gate::whereIn('status', ['1', '2', '4', '5', '7'])->get();
        $full = RO_Gate::where('status', '=', '6')->get();
        $ros = RO::all();
        $realisasis = RO_Realisasi::all();

        $rg = collect();
        $ro_Gate = [];

        foreach ($ros as $roItem) {
            $jmlhContInRoDok = $roItem->jmlh_cont;

            $realizationCount = $realisasis->where('ro_no', $roItem->ro_no)->count();

            if ($realizationCount < $jmlhContInRoDok) {
                $rg->push($roItem->ro_id);
            }

            $ro_Gate = RO::whereIn('ro_id', $rg)->get();
        }
        return view('gate.stuffing.gate-in', compact('title', 'ro', 'full', 'rg', 'ro_Gate'));
    }
    public function stuf_android()
    {
        $title = "Gate-In Stuffing";
        $ro = RO_Gate::whereIn('status', ['1', '2', '4', '5', '7'])->get();
        $full = RO_Gate::where('status', '=', '6')->get();
        $ros = RO::all();
        $realisasis = RO_Realisasi::all();

        $ro_Gate = [];
        $rg = collect();

        foreach ($ros as $roItem) {
            $jmlhContInRoDok = $roItem->jmlh_cont;

            $realizationCount = $realisasis->where('ro_no', $roItem->ro_no)->count();

            if ($realizationCount < $jmlhContInRoDok) {
                $rg->push($roItem->ro_id);
            }

            $ro_Gate = RO::whereIn('ro_id', $rg)->get();
        }
        return view('gate.stuffing.gate-in-android', compact('title', 'ro', 'full', 'rg', 'ro_Gate'));
    }

    public function gati_stuf(Request $request)
    {
        $now = Carbon::now();
        $ro_check = $request->ro_no;
        $ro_checked = RO::where('ro_no', '=', $ro_check)->get();

        $ro_gati = RO_Gate::create([
            'ro_no' => $request->ro_no,
            'truck_no' => $request->truck_no,
            'truck_in_date' => $now,
            'status' => '1',
        ]);


        return response()->json([
            'success' => 200,
            'message' => 'Detail Data Post',

        ]);
    }

    public function gati_stuf_full(Request $request)
    {
        $now = Carbon::now();
        $id = $request->ro_id_gati;
        $truck = RO_Gate::where('ro_id_gati', '=', $id)->first();

        if ($truck) {
            $truck->update([
                'truck_in_date_after' => $now,
                'status' => '7',
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Detail Data Post',
                'data' => $truck,

            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Detail Data Post',


            ]);
        }
    }

    public function gati_stuffing_data(Request $request)
    {
        $id = $request->ro_id;
        $data = RO::where('ro_id', $id)->first();

        if ($data) {
            return response()->json(['cont' => $data->jmlh_cont, 'service' => $data->stuffing_service, 'ro' => $data->ro_no]);
        }
        return response()->json(['service' => 'data tidak ditemukan', 'cont' => 'data tidak ditemukan']);
    }

    public function edit_truck(Request $request)
    {
        $id = $request->container_key;
        $truck = Item::where('container_key', '=', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $truck,
        ]);
    }

    public function update_truck(Request $request)
    {
        $key = $request->container_key;
        $item = Item::where('container_key', $key)->first();

        if ($item) {
            $item->update([
                'truck_no' => $request->truck,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $item,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!!',

            ]);
        }
    }
}
