<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use Auth;
use Illuminate\Http\Request;

class Stripping extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
public function index()
{
    $title = 'STR';
    $confirmed = Item::where('ctr_intern_status', '=', 04,)->orderBy('update_time', 'desc')->get();
    $formattedData = [];

    foreach ($confirmed as $tem) {
        $now = Carbon::now();
        $updatedAt = Carbon::parse($tem->update_time);

        // Perhitungan selisih waktu
        $diff = $updatedAt->diffForHumans($now);

        // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
        if ($updatedAt->diffInDays($now) < 1) {
            $diff = $updatedAt->diffForHumans($now, true);
            $diff = str_replace(['hours', 'hour','minutes', 'minutes', 'seconds', 'seconds'], ['jam', 'jam','menit', 'menit', 'detik', 'detik'], $diff);
        } else {
            // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
            $diff = $updatedAt->diffForHumans($now, true);
            $diff = str_replace(['days', 'day', 'hours', 'hour','minutes', 'minutes', 'seconds', 'seconds'], ['hari', 'hari', 'jam', 'jam','menit', 'menit', 'detik', 'detik'], $diff);
        }

        $formattedData[] = [
            'container_no' => $tem->container_no,
            'ctr_type'=>$tem->ctr_type,
            'yard_block'=>$tem->yard_block,
            'yard_slot'=>$tem->yard_slot,
            'yard_row'=>$tem->yard_row,
            'yard_tier'=>$tem->yard_tier,
            'update_time' => $diff . ' yang lalu',
            'container_key' => $tem->container_key
        ];
      
    }
    $containerKeys = Item::where('ctr_intern_status', '=', '03')
    ->whereHas('job', function ($query) {
        $query->where('order_service_code', 'SPPS');
    })
    ->pluck('container_no', 'container_key')
    ->map(function ($value, $key) {
        return ['value' => $key, 'text' => $value];
    })
    ->values()
    ->all();
    $items = Item::where('ctr_intern_status', 03)->get();
    $users = User::all();
    $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
    $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
    $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
    $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
    $currentDateTime = Carbon::now();
    $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
    return view('stripping.main', compact('confirmed','formattedData','title', 'items','users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'));
}
public function android()
{
    $title = 'STR';
    $confirmed = Item::where('ctr_intern_status', '=', 04,)->orderBy('update_time', 'desc')->get();
    $formattedData = [];

    foreach ($confirmed as $tem) {
        $now = Carbon::now();
        $updatedAt = Carbon::parse($tem->update_time);

        // Perhitungan selisih waktu
        $diff = $updatedAt->diffForHumans($now);

        // Jika selisih waktu kurang dari 1 hari, maka tampilkan format jam
        if ($updatedAt->diffInDays($now) < 1) {
            $diff = $updatedAt->diffForHumans($now, true);
            $diff = str_replace(['hours', 'hour','minutes', 'minutes', 'seconds', 'seconds'], ['jam', 'jam','menit', 'menit', 'detik', 'detik'], $diff);
        } else {
            // Jika selisih waktu lebih dari 1 hari, maka tampilkan format hari dan jam
            $diff = $updatedAt->diffForHumans($now, true);
            $diff = str_replace(['days', 'day', 'hours', 'hour','minutes', 'minutes', 'seconds', 'seconds'], ['hari', 'hari', 'jam', 'jam','menit', 'menit', 'detik', 'detik'], $diff);
        }

        $formattedData[] = [
            'container_no' => $tem->container_no,
            'ctr_type'=>$tem->ctr_type,
            'yard_block'=>$tem->yard_block,
            'yard_slot'=>$tem->yard_slot,
            'yard_row'=>$tem->yard_row,
            'yard_tier'=>$tem->yard_tier,
            'update_time' => $diff . ' yang lalu',
            'container_key' => $tem->container_key
        ];
      
    }
    $containerKeys = Item::where('ctr_intern_status', '=', '03')
    ->whereHas('job', function ($query) {
        $query->where('order_service_code', 'SPPS');
    })
    ->pluck('container_no', 'container_key')
    ->map(function ($value, $key) {
        return ['value' => $key, 'text' => $value];
    })
    ->values()
    ->all();
    $items = Item::where('ctr_intern_status', 03)->get();
    $users = User::all();
    $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
    $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
    $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
    $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');
    $currentDateTime = Carbon::now();
    $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
    return view('stripping.android', compact('confirmed','formattedData','title', 'items','users', 'currentDateTimeString', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'containerKeys'));
}

     public function get_stripping(Request $request)
    {
        $container_key = $request->container_key;
        $name = Item::where('container_key', $container_key)->first();
       
        if ($name) {
            return response()->json(['container_no' => $name->container_no, 'tipe'=>$name->ctr_type, 'oldblock'=>$name->yard_block, 'oldslot'=>$name->yard_slot, 'oldrow'=>$name->yard_row, 'oldtier'=>$name->yard_tier, 'invoice'=>$name->invoice_no]);
        }
        return response()->json(['container_no' => 'data tidak ditemukan', 'tipe' => 'data tidak ditemukan', 'coname' => 'data tidak ditemukan', 'oldblock' => 'data tidak ditemukan', 'oldslot' => 'data tidak ditemukan', 'oldrow' => 'data tidak ditemukan', 'oldtier' => 'data tidak ditemukan']);
    }
    
    public function stripping_place(Request $request)
    {


        $container_key = $request->container_key;
    $item = Item::where('container_key', $container_key)->first();
    
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
            'ctr_intern_status' => '04',
            'wharf_yard_oa' => $request->wharf_yard_oa,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'item' => $item,
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Yard sudah terisi oleh container -'. $yard_rowtier->container_no. '!!',
        ]);
    }
    }
    
}