<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Models\VVoyage;
use App\Models\Item;

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
}
