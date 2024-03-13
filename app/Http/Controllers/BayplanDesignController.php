<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Models\VVoyage;
use App\Models\Item;
use App\Models\Ship;

class BayplanDesignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title'] = "Bay PLan Load";

        $now = Carbon::now();
        
        $ves = VVoyage::orderBy('ves_id', 'desc')->get(); 
        
        // Fetching data for each vessel
        foreach ($ves as $vessel) {
            $vessel->containersInProgress = Item::where('ves_id', $vessel->ves_id)
                ->where('ctr_i_e_t', 'E')
                ->where('ctr_intern_status', '<>', 56)
                ->count();
            
            $vessel->containersLoaded = Item::where('ves_id', $vessel->ves_id)
                ->where('ctr_intern_status', 56)
                ->count();
        }

        $data['ves'] = $ves;

        return view('load.bayplan.main', $data);
    }

    public function cetakLoad($ves)
    {
        $kapal = VVoyage::where('ves_id', $ves)->first();
        $data['title'] = "Bayplan Load" . $kapal->ves_name;

        $data['baySlots'] = Ship::select('bay_slot')->where('ves_id', $ves)->groupBy('bay_slot')->orderBy('bay_slot', 'asc')->get();
        $data['onDeck'] = Ship::where('ves_id', $ves)->where('on_under', '=', 'O')->orderBy('bay_slot', 'asc')->get();
        $data['underDeck'] = Ship::where('ves_id', $ves)->where('on_under', '=', 'U')->orderBy('bay_slot', 'asc')->get();
        $data['vesSelect'] = VVoyage::where('ves_id', $ves)->first();
        $data['totalCont'] = Ship::where('ves_id', $ves)->where('ctr_i_e_t', '=', 'E')->count();
        return view('load.bayplan.cetakFull', $data);
    }
}
