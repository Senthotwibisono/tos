<?php

namespace App\Http\Controllers\Api\InvoiceService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VVoyage;
use App\Models\Item;
use App\Models\DOonline;

class TrackingInvoice extends Controller
{
    public function searchByDo(Request $request)
    {
        // var_dump($request->all());
        // die();

        $vessel = VVoyage::where('ves_code', $request->ves_code)->where('voy_in', $request->voy_in)->where('voy_out', $request->voy_out)->first();
        if (!$vessel) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Kapal Tidak Di Temukan',
            ]);
        }

        $do = DOonline::where('do_no', $request->do_no)->first();
        // var_dump($do);
        if (!$do) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor DO Tidak ditemukan',
            ]);
        }

        $doCont = json_decode($do->container_no, true);
        // var_dump($vessel->ves_id, $doCont);
        
        $items = Item::where('ves_id', $vessel->ves_id)->where('ctr_i_e_t', 'I')->whereIn('container_no', $doCont)->get();
        $containersItem = $items->pluck('container_key');
        
        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' =>'Data null atau tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data ditemukan',
            'data' => [
                'items' => $containersItem,
            ],
        ]);
    }
}
