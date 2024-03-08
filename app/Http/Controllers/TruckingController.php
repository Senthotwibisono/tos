<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\MasterAlat;
use App\Models\ActAlat;
use Auth;

class TruckingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Trucking';

        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');

        $item = Item::where('ctr_intern_status', '=', '10')->get();
        $alat = MasterAlat::where('category', '=', 'Yard')->get();


        return view('yard.trucking.main', compact('item', 'alat', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'title'));
    }
    public function android()
    {
        $title = 'Trucking';

        $yard_block = Yard::distinct('yard_block')->pluck('yard_block');
        $yard_slot = Yard::distinct('yard_slot')->pluck('yard_slot');
        $yard_row = Yard::distinct('yard_row')->pluck('yard_row');
        $yard_tier = Yard::distinct('yard_tier')->pluck('yard_tier');

        $item = Item::where('ctr_intern_status', '=', '10')->get();
        $alat = MasterAlat::where('category', '=', 'Yard')->get();


        return view('yard.trucking.android', compact('item', 'alat', 'yard_block', 'yard_slot', 'yard_row', 'yard_tier', 'title'));
    }

    public function get_truck(Request $request)
    {
        $container_key = $request->container_key;
        $name = Item::where('container_key', $container_key)->first();


        if ($name) {
            return response()->json(['truck_no' => $name->truck_no]);
        }
        return response()->json(['truck_no' => 'data tidak ditemukan']);
    }

    public function trucking(Request $request)
    {
        $request->validate([
            'container_key' => 'required',
            'id_alat'  => 'required',
        ], [
            
        ]);
        $id_alat = $request->id_alat;
        $alat = MasterAlat::where('id',$id_alat)->first();

        
        if ($alat) {
        $key = $request->container_key;
        $item = Item::where('container_key', '=', $key)->first();

        $act = ActAlat::create([
            'id_alat' =>  $request->id_alat,
            'category' => $alat->category,
            'nama_alat' => $alat->name,
            'container_key' => $request->container_key,
            'container_no' => $item->container_no,
            'activity' => 'Trucking',
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'item' => $act,
        ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'something wrong!!',
            ]);
        }
       
    }
}
