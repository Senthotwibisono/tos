<?php

namespace App\Http\Controllers\Lapangan\Vessel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use DataTables;
use Carbon\Carbon;

use App\Models\VVoyage;
use App\Models\VMaster;
use App\Models\VSeq;
use App\Models\VService;
use App\Models\Berth;
use App\Models\ProfileTier;
use App\Models\Ship;

class PlanningController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function voyageIndex()
    {
        $data['title'] = 'Vessel Schedule';
        $data['vessels'] = VMaster::get();
        $data['seqs'] = VSeq::get();
        $data['services'] = VService::select('service')->distinct()->get();
        $data['berths'] = Berth::get();
        // dd($data);
        return view('lapangan.planning.voyage.index', $data);
    }

    public function voyageData(Request $request)
    {
        $data = VVoyage::orderBy('etd_date', 'desc')->get();
        return DataTables::of($data)
        ->addColumn('edit', function($data){
            return '<button class="btn btn-warning" data-id="'.$data->ves_id.'" onClick="editVoyage(this)"><i class="fas fa-pencil"></i></button>';
        })
        ->addColumn('delete', function($data){
            return '<button class="btn btn-danger" data-id="'.$data->ves_id.'" onClick="deleteVoyage(this)"><i class="fas fa-trash"></i></button>';
        })
        ->rawColumns(['edit', 'delete'])
        ->make(true);
    }

    public function voyagePost(Request $request)
    {
        $data = $request->all();
        try {
            DB::transaction( function() use($request) {
                $vessel = VVoyage::updateOrCreate(
                    ['ves_id' => $request->ves_id],
                    [
                        'ves_code' => $request->ves_code, 
                        'ves_name' => $request->ves_name, 
                        'agent' => $request->agent, 
                        'liner' => $request->liner, 
                        'voyage_owner' => $request->owner, 
                        'ves_length' => $request->ves_length, 
                        'voy_in' => $request->voy_in, 
                        'voy_out' => $request->voy_out, 
                        'reg_flag' => $request->reg_flag, 
                        'vessel_service' => $request->ves_service, 
                        'origin_port' => $request->origin_port, 
                        'next_port' => $request->next_port, 
                        'dest_port' => $request->dest_port, 
                        'last_port' => $request->last_port, 
                        'berth_no' => $request->berthNo, 
                        'cy_code' => $request->cyCode, 
                        'btoa_side' => $request->btoaSide, 
                        'berth_grid' => $request->berthGrid, 
                        'import_booking' => $request->booking_import, 
                        'export_booking' => $request->booking_export, 
                        'eta_date' => $request->eta_date, 
                        'arrival_date' => $request->arrival_date, 
                        'est_berthing_date' => $request->est_berthing_date, 
                        'berthing_date' => $request->berthing_date, 
                        'est_start_work_date' => $request->est_start_work_date, 
                        'act_start_work_date' => $request->act_start_work_date, 
                        'est_end_work_date' => $request->est_end_work_date, 
                        'act_end_work_date' => $request->act_end_work_date, 
                        'etd_date' => $request->etd_date, 
                        'deparature_date' => $request->deparature_date, 
                        'open_stack_date' => $request->open_stack_date, 
                        'doc_clossing_date' => $request->doc_clossing_date, 
                        'clossing_date' => $request->clossing_date, 
                        'user_id' => Auth::user()->id,
                    ]
                );

                $bay = ProfileTier::where('ves_code', $request->ves_code)->get();
                if (!empty($bay)) {
                    foreach ($bay as $bayPlan) {
                        //under
                        if ($bayPlan->active == 'Y') {
                            $oldShip = Ship::where('ves_id', $vessel->ves_id)->where('voy_no', $vessel->voy_out)->where('bay_slot', $bayPlan->bay_slot)->where('bay_row', $bayPlan->bay_row)->where('bay_tier', $bayPlan->bay_tier)->first();
                            if (!$oldShip) {
                                $ship = Ship::create([
                                    'on_under'=>$bayPlan->on_under,
                                    'ves_id' => $vessel->ves_id,
                                    'ves_code' => $request->ves_code,
                                    'voy_no' => $request->voy_out,
                                    'bay_slot' => $bayPlan->bay_slot,
                                    'bay_row' =>  $bayPlan->bay_row,
                                    'bay_tier' =>  $bayPlan->bay_tier,
                                ]);
                            }
                        }
                    }
                }
            });

            return response()->json([
                'success'  => true,
                'message' => 'Aksi Berhasail'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false, 
                'message' => $th->getMessage()
            ]);
        }
    }
}
