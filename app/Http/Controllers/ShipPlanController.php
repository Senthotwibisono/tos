<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VVoyage;
use App\Models\Item;
use Auth;

class ShipPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = "Ship Planning";
        $vessel_voyage = VVoyage::all();
  

        return view('planning.ship.main', compact('vessel_voyage', 'title'));
    }

    public function plan(Request $request)
    {
        $ves_id = $request->ves_id;
        $item = Item::where('ves_id', $ves_id)->get();

        return response()->json([
            'success' => 200,
            'message' => 'Detail Data Post',
            'data'    => $item
        ]);
    }
}
