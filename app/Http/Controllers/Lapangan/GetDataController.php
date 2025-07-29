<?php

namespace App\Http\Controllers\Lapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VVoyage;
use App\Models\Item;
use App\Models\VMaster;
use App\Models\VSeq;
use App\Models\VService;
use App\Models\Berth;

class GetDataController extends Controller
{
    

    public function getVessel(Request $request)
    {
        // var_dump($request->all());
        try {
            $vessel = VMaster::where('ves_code', $request->vesCode)->first();
            if ($vessel) {
                return response()->json([
                    'success' => true,
                    'data' => $vessel
                ]);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() 
            ]);
        }
    }

    public function getBerth(Request $request)
    {
        // var_dump($request->all());
        try {
            $berth = Berth::where('berth_no', $request->berthNo)->first();
            if ($berth) {
                return response()->json([
                    'success' => true,
                    'data' => $berth
                ]);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() 
            ]);
        }
    }

    public function getVoyage(Request $request)
    {
        $vessel = VVoyage::where('ves_id', $request->id)->first();
       
        $counterImport  = Item::where('ves_id', $vessel->ves_id)->where('ctr_i_e_t', 'I')->count();
        $counterExport  = Item::where('ves_id', $vessel->ves_id)->where('ctr_i_e_t', 'E')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'import' => $counterImport,
                'export' => $counterExport,
                'vessel' => $vessel
            ]
            ]);

    }
}
