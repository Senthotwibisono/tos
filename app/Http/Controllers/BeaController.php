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

// Pabean
use App\Models\TpsDokPabean;
use App\Models\TpsDokPabeanCont;
use App\Models\TpsDokPabeanKms;

//PKBE
use App\Models\TpsDokPKBE;

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

    
        // Pabean
        $dok_pabean_import = TpsDokPabean::where('KD_DOK_INOUT', [41, 44])->pluck('NO_DOK_INOUT')->unique();
        $pabean_import = [];
        
        foreach ($dok_pabean_import as $bc) {
            $dok_pabean_imports = TpsDokPabean::where('NO_DOK_INOUT', $bc)->first();
            if ($dok_pabean_imports) {
                $pabean_import[] = $dok_pabean_imports;
            }
        }

        $dok_pabean_EXP = TpsDokPabean::where('KD_DOK_INOUT', '=', '56')->pluck('NO_DOK_INOUT')->unique();
        $pabean_EXP = [];
        
        foreach ($dok_pabean_EXP as $bc) {
            $dok_pabean_EXPS = TpsDokPabean::where('NO_DOK_INOUT', $bc)->first();
            if ($dok_pabean_EXPS) {
                $pabean_EXP[] = $dok_pabean_EXPS;
            }

        }

        $dok_lain = KodeDok::whereIn('kode', [
        4, 5, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
        31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 42, 43, 45, 47, 48, 49, 50, 51, 52, 59, 60, 61,
        62, 63, 64, 65, 66, 46, 53, 54, 55, 57, 58, 67])->get();

        // PKBE
        $dok_pkbe = TpsDokPKBE::pluck('NOPKBE')->unique();
        $details_pkbe = [];
            foreach ($dok_pkbe as $pkbeValue) {
                $detail_pkbe = TpsDokPKBE::where('NOPKBE', $pkbeValue)->first();
                if ($detail_pkbe) {
                    $details_pkbe[] = $detail_pkbe;
                }
            }
            $container_pkbe = TpsDokPKBE::whereIn('NOPKBE', $dok_pkbe)
                ->groupBy('NOPKBE')
                ->selectRaw('NOPKBE, count(distinct NO_CONT) as container_count')
                ->pluck('container_count', 'NOPKBE');

        return view('invoice.bc.req-bc-dok', compact('title', 'dok', 'sppb', 'dok_npe', 'details', 'container', 'container_SPPB', 'sppb_bc', 'pabean_import', 'pabean_EXP', 'dok_lain', 'details_pkbe', 'container_pkbe'));
    }

    public function detail(Request $request)
    {
        $car = $request->CAR;

        $cont = TpsSppbPibCont::where('CAR', '=', $car)->select('NO_CONT','SIZE', 'JNS_MUAT')->distinct('NO_CONT')->get();
        if ($cont->isEmpty()) {
            $cont = TpsSppbBcCont::where('CAR', '=', $car)->select('NO_CONT','SIZE', 'JNS_MUAT')->distinct('NO_CONT')->get();
        }
        if ($cont->isEmpty()) {
            $cont = TpsDokPabeanCont::where('CAR', '=', $car)->select('NO_CONT','SIZE', 'JNS_MUAT')->distinct('NO_CONT')->get();
        }
        if ($cont->isEmpty()) {
            $cont = TpsDokPKBE::where('CAR', '=', $car)->select('NO_CONT','SIZE')->distinct('NO_CONT')->get();
        }

        return response()->json([
            'success' => 200,
            'message' => 'Detail Data Post',
            'data'    => $cont,
        ]);
    }

    public function container_export(Request $request)
    {
        $NO_DAFTAR = $request->NO_DAFTAR;

        $data = TpsDokNpe::where('NO_DAFTAR', $NO_DAFTAR)->select('NO_CONT','SIZE','FL_SEGEL')->distinct('NO_CONT')->get();

        return response()->json([
            'success' => 200,
            'message' => 'Detail Data Post',
            'data'    => $data,
        ]);
    }
}
