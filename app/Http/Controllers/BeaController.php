<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KodeDok;
// BC 20
use App\Models\TpsSppbPib;
use App\Models\TpsSppbPibCont;
use App\Models\TpsSppbPibKms;
// BC23
use App\Models\TpsSppbBc;
use App\Models\TpsSppbBcCont;
use App\Models\TpsSppbBcKms;
// NPE
use App\Models\TpsDokNPE;

class BeaController extends Controller
{
    //
    public function __construct()
  {
    $this->middleware('auth');
  }
    public function index()
    {

        $title = 'Bea Cukai Docs';

        $dok = KodeDok::all();

        // sppb 20
        $dok_sppb = TpsSppbPib::pluck('CAR')->unique();
        $sppb = [];
            foreach ($dok_sppb as $dsp) {
                $sppbs = TpsSppbPib::where('CAR', $dsp)->first();
                if ($sppbs) {
                    $sppb[] = $sppbs;
                }
            }
            $container_SPPB = TpsSppbPibCont::whereIn('CAR', $dok_sppb)
                ->groupBy('CAR')
                ->selectRaw('CAR, count(distinct NO_CONT) as container_count')
                ->pluck('container_count', 'CAR');
        
        $cont = TpsSppbPibCont::all();

        // bc23
        $dok_sppb_bc = TpsSppbBc::pluck('CAR')->unique();
        $sppb_bc = [];
            foreach ($dok_sppb_bc as $bc) {
                $sppb_bcs = TpsSppbBc::where('CAR', $bc)->first();
                if ($sppb_bcs) {
                    $sppb_bc[] = $sppb_bcs;
                }
            }

        // npe
        $dok_npe = TpsDokNpe::pluck('NONPE')->unique();
        $details = [];
            foreach ($dok_npe as $npeValue) {
                $detail = TpsDokNpe::where('NONPE', $npeValue)->first();
                if ($detail) {
                    $details[] = $detail;
                }
            }
            $container = TpsDokNpe::whereIn('NONPE', $dok_npe)
                ->groupBy('NONPE')
                ->selectRaw('NONPE, count(distinct NO_CONT) as container_count')
                ->pluck('container_count', 'NONPE');

        return view('invoice.bc.req-bc-dok', compact('title', 'dok', 'sppb', 'dok_npe', 'details', 'container', 'container_SPPB', 'sppb_bc'));
    }
}
