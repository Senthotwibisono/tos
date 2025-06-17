<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\MasterAlat;
use App\Models\ActAlat;
use App\Models\Operator;
use App\Models\ActOper;
use Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\HistoryController;

class Stripping extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->history = app(HistoryController::class);
    }
    public function index()
    {
        $title = 'STR';
        $confirmed = Item::where('ctr_intern_status', '=', 04,)->orderBy('update_time', 'desc')->get();
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
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', '=', [03, 04])
            ->whereHas('job', function ($query) {
                $query->where('order_service_code', 'SPPS');
            })
            ->pluck('container_no', 'container_key')
            ->map(function ($value, $key) {
                return ['value' => $key, 'text' => $value];
            })
            ->values()
            ->all();

            $data['containerStr'] = Item::whereIn('ctr_intern_status', ['03', '04'])->where('order_service', 'SPPS')->whereNot('order_service', null)->whereNotNull('job_no')->get();
        $items = Item::where('ctr_intern_status', 03)->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
        $data['operator'] = Operator::where('role', '=', 'yard')->get();

        // GET ALL JOB_CONTAINER

      

        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        return view('stripping.main', $data, compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys', 'alat'));
    }
    public function android()
    {
        $title = 'STR';
        $confirmed = Item::where('ctr_intern_status', '=', 04,)->orderBy('update_time', 'desc')->get();
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
                'container_key' => $tem->container_key
            ];
        }
        $containerKeys = Item::where('ctr_intern_status', '=', [03, 04])
            ->whereHas('job', function ($query) {
                $query->where('order_service_code', 'SPPS');
            })
            ->pluck('container_no', 'container_key')
            ->map(function ($value, $key) {
                return ['value' => $key, 'text' => $value];
            })
            ->values()
            ->all();

            $data['containerStr'] = Item::whereIn('ctr_intern_status', ['03', '04'])->where('order_service', 'SPPS')->whereNot('order_service', null)->whereNotNull('job_no')->get();

        $items = Item::where('ctr_intern_status', 03)->get();
        $users = User::all();
        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');

        // GET ALL JOB_CONTAINER

      


        $alat = MasterAlat::where('category', '=', 'Yard')->get();
        return view('stripping.android', $data, compact('confirmed', 'formattedData', 'title', 'items', 'users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys', 'alat'));
    }

    public function get_stripping(Request $request)
    {
        $container_key = $request->container_key;
        $name = Item::where('container_key', $container_key)->first();


      

        if ($name) {
            return response()->json(['container_key' => $name->container_key, 'container_no' => $name->container_no, 'tipe' => $name->ctr_type, 'oldblock' => $name->yard_block, 'oldslot' => $name->yard_slot, 'oldrow' => $name->yard_row, 'oldtier' => $name->yard_tier, 'invoice' => $name->invoice_no]);
        }
        return response()->json(['container_no' => 'data tidak ditemukan', 'tipe' => 'data tidak ditemukan', 'coname' => 'data tidak ditemukan', 'oldblock' => 'data tidak ditemukan', 'oldslot' => 'data tidak ditemukan', 'oldrow' => 'data tidak ditemukan', 'oldtier' => 'data tidak ditemukan']);
    }

    public function stripping_place(Request $request)
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

        if ($request->id_alat == null) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memilih alat !!',
            ]);
        }


        $yard_rowtier = Yard::where('yard_block', $request->yard_block)
            ->where('yard_slot', $request->yard_slot)
            ->where('yard_row', $request->yard_row)
            ->where('yard_tier', $request->yard_tier)
            ->first();

            if ($yard_rowtier->container_key == null || $yard_rowtier->container_key == ' ') { 
            $id_alat = $request->id_alat;
            $alat = MasterAlat::where('id', $id_alat)->first();
            $opr = Operator::where('id', $request->operator)->first();

            $item->update([
                'yard_block' => $request->yard_block,
                'yard_slot' => $request->yard_slot,
                'yard_row' => $request->yard_row,
                'yard_tier' => $request->yard_tier,
                'ctr_intern_status' => '04',
                'wharf_yard_oa' => $request->wharf_yard_oa,
                'gross' => null,
                'gross_class' => null,
                'commodity_code' => null,
                'commodity_name' => null,
                'agent' => null,
                'ctr_status' => 'MTY',
            ]);

                $dataHistory = [
                  'container_key' => $item->container_key,
                  'container_no' => $item->container_no,
                  'operation_name' => 'str',
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

            $act_alat = ActAlat::create([
                'id_alat' =>  $request->id_alat,
                'category' => $alat->category,
                'nama_alat' => $alat->name,
                'operator_id'=>$request->operator,
                'operator' => $opr->name,
                'container_key' => $request->container_key,
                'container_no' => $request->container_no,
                'activity' => 'STR',
            ]);
            $actOper = ActOper::create([
                'alat_id' =>$request->id_alat,
                'alat_category' =>$alat->category,
                'alat_name'  =>$alat->name,
                'operator_id'=>$request->operator,
                'operator_name'=>$opr->name,
                'container_key'=>$item->container_key,
                'container_no'=>$item->container_no,
                'ves_id'=>$item->ves_id,
                'ves_name'=>$item->ves_name,
                'voy_no'=>$item->voy_no,
                'activity' =>'STR',
            ]);

         
           
                return response()->json([
                    'success' => true,
                    'message' => 'Updated successfully!',
                    'item' => $item,
                ]);
          
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Yard sudah terisi oleh container -' . $yard_rowtier->container_no . '!!',
            ]);
        }
    }
}
