<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\VVoyage;
use App\Models\VMaster;
use App\Models\VSeq;
use App\Models\VService;
use App\Models\Berth;
use App\Models\User;
use App\Models\Item;
use Carbon\Carbon;


class VesselController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = 'Vessel Schedule';
        $vessel_voyage = VVoyage::orderBy('deparature_date', 'desc')->get();
        return view('planning.vessel.main', compact('vessel_voyage', 'title'));
    }

    public function create()
    {
        $title = 'Add Schedule';
       $users = User::all();
       $vessel_master = VMaster::all();
       $vessel_seq = VSeq::all();
       $vessel_service = VService::select('service')->distinct()->get();
       $berth = Berth::all();
       $currentDateTime = Carbon::now();
       $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
       $item = Item::select('ves_id');
        return view('planning.vessel.create', compact('users', 'vessel_master', 'vessel_seq', 'vessel_service', 'berth', 'currentDateTimeString', 'item', 'title'));
    }

    public function getvessel(request $request)
    {
        $ves_code = $request->ves_code;
        $ves_name = VMaster::where('ves_code', $ves_code)->get();
        foreach ($ves_name as $name) {
            echo "<option value='$name->ves_name'>$name->ves_name</option>";
        }
       
    }
    public function getvessel_agent(request $request)
    {
        $ves_code = $request->ves_code;
        $agents = VMaster::where('ves_code', $ves_code)->get();
        foreach ($agents as $agent) {
            echo "<option value='$agent->agent'>$agent->agent</option>";
        }
       
    }
    public function getvessel_liner(request $request)
    {
        $ves_code = $request->ves_code;
        $liners = VMaster::where('ves_code', $ves_code)->get();
        foreach ($liners as $liner) {
            echo "<option value='$liner->liner'>$liner->liner</option>";
        }
       
    }
    public function reg_flag(request $request)
    {
        $ves_code = $request->ves_code;
        $reg_flag = VMaster::where('ves_code', $ves_code)->get();
        foreach ($reg_flag as $reg) {
            echo "<option value='$reg->reg_flag'>$reg->reg_flag</option>";
        }
       
    }
    public function length(request $request)
    {
        $ves_code = $request->ves_code;
        $length = VMaster::where('ves_code', $ves_code)->get();
        foreach ($length as $panjang) {
            echo "<option value='$panjang->ves_length'>$panjang->ves_length</option>";
        }
       
    }
    public function bcode(request $request)
    {
        $berth_no = $request->berth_no;
        $bcode = Berth::where('berth_no', $berth_no)->get();
        foreach ($bcode as $code) {
            echo "<option value='$code->berth_code'>$code->berth_code</option>";
        }
       
    }
    public function from(request $request)
    {
        $berth_no = $request->berth_no;
        $from = Berth::where('berth_no', $berth_no)->get();
        foreach ($from as $fmetre) {
            echo "<option value='$fmetre->from_length'>$fmetre->from_length</option>";
        }
       
    }
    public function tlength(request $request)
    {
        $berth_no = $request->berth_no;
        $tlength = Berth::where('berth_no', $berth_no)->get();
        foreach ($tlength as $t) {
            echo "<option value='$t->to_length'>$t->to_length</option>";
        }
       
    }
    public function origin(request $request)
    {
        $service = $request->service;
        $origin = VService::where('service', $service)->get();
        foreach ($origin as $or) {
            echo "<option value='$or->disch_port'>$or->disch_port</option>";
        }
       
    }
    public function next(request $request)
    {
        $service = $request->service;
        $next = VService::where('service', $service)->get();
        foreach ($next as $nt) {
            echo "<option value='$nt->disch_port'>$nt->disch_port</option>";
        }
       
    }
    public function dest(request $request)
    {
        $service = $request->service;
        $dest = VService::where('service', $service)->get();
        foreach ($dest as $dst) {
            echo "<option value='$dst->disch_port'>$dst->disch_port</option>";
        }
       
    }
    public function last(request $request)
    {
        $service = $request->service;
        $last = VService::where('service', $service)->get();
        foreach ($last as $lt) {
            echo "<option value='$lt->disch_port'>$lt->disch_port</option>";
        }
       
    }

    public function schedule_store(request $request){
        $request->validate([

            'ves_code' => 'required|max:4',
            

            'voy_in' => 'required|max:7',
            'voy_out' => 'required|max:7',
            'berth_no' => 'required',
            'berth_grid' => 'required|max:5',
            'est_anchorage_date' => 'required',
            'est_pilot_date' =>'required',
            'est_start_work_date' =>'required',
            'est_end_work_date' =>'required',
            'eta_date' =>'required',
            'etd_date' =>'required',
            'est_berthing_date' =>'required',
            'voyage_owner'=>'required|max:4'
        ],
        [
            'ves_code.max' => 'vessel code  tidak boleh lebih dari 4 karakter.', 
            'voy_in.max' => 'Kolom Voy In tidak boleh lebih dari 7 karakter.',
            'voy_out.max' => 'Kolom Voy Out tidak boleh lebih dari 7 karakter.',
            'berth_grid.max' => 'Kolom Berth Grid tidak boleh lebih dari 5 karakter.',
            'cy_code.max' => 'Kolom Cy Code tidak boleh lebih dari 1 karakter.',
            'no_ook.max' => 'Kolom No.PPK tidak boleh lebih dari 20 karakter.',
        ]    
    );

        $vessel_voyage = VVoyage::create([
           'ves_code' => $request->ves_code,
           'voy_in' => $request->voy_in,
           'voy_out' => $request->voy_out,
           'ves_name' => $request->ves_name,
           'agent' => $request->agent,
           'liner' => $request->liner,
           'voyage_owner'=> $request->voyage_owner ,
           'import_yn' => $request->import_yn,
           'export_yn' => $request->export_yn,
           'ves_length' => $request->ves_length,
           'reg_flag' => $request->reg_flag,
           'ocean_interisland' => $request->ocean_interisland,
           'berth_no' => $request->berth_no,
           'berth_fr_metre' => $request->berth_fr_metre,
           'berth_to_metre' => $request->berth_to_metre,
           'btoa_side' => $request->btoa_side,
           'berth_grid' => $request->berth_grid,
           'est_anchorage_date' => $request->est_anchorage_date,
           'act_anchorage_date'=> $request->est_anchorage_date,
           'est_pilot_date' => $request->est_pilot_date,
           'act_pilot_date' => $request->est_pilot_date,
           'est_start_work_date' => $request->est_start_work_date,
           'act_start_work_date' => $request->est_start_work_date,
           'est_end_work_date' => $request->est_end_work_date,
           'act_end_work_date' => $request->est_end_work_date,
           'eta_date' => $request->eta_date,
           'arrival_date' => $request->eta_date,
           'etd_date' => $request->etd_date,
           'deparature_date' => $request->etd_date,
           'est_berthing_date' => $request->est_berthing_date,
           'berthing_date' => $request->est_berthing_date,
           'discharge_date' => $request->discharge_date,
           'loading_date' => $request->loading_date,
           'exit_date' => $request->exit_date,
           'clossing_date' => $request->clossing_date,
           'doc_clossing_date' => $request->doc_clossing_date,
           'recv_cargo_cutoff_date' => $request->recv_cargo_cutoff_date,
           'non_oper_time' => $request->non_oper_date,
           'idle_time' => $request->idle_time,
           'effective_time' => $request->effective_time,
           'year_made' => $request->year_name,
           'country_made' => $request->country_made,
           'last_port' => $request->last_port,
           'next_port' => $request->next_port,
           'origin_port' =>$request->origin_port,
           'dest_port' =>$request->dest_port,
           'liner_tramp' =>$request->liner_tramp,
           'feeder_direct' =>$request->feeder_direct,
           'export_booking' =>$request->export_booking,
           'export_counter' =>$request->export_counter,
           'import_booking' =>$request->import_booking,
           'import_counter' =>$request->import_counter,
           'vessel_service' =>$request->vessel_service,
           'remarks' =>$request->remarks,
           'update_time' =>$request->update_time,
           'user_id' =>$request->user_id,
           'vessel_history' =>$request->vessel_history,
           'berth_code' =>$request->berth_code,
           'cy_code' =>$request->cy_code,
           'no_bc11' =>$request->no_bc11,
           'no_ppk' => $request->no_ppk,
           'open_stack_date'=>$request->open_stack_date,
        ]);

        return redirect('/planning/vessel-schedule')->with('success', "Jadwal Berhasil Dibuat");
    }

    public function edit_schedule($ves_id){
        $title = 'Edit Schedule';
        $bongkar_import = Item::where('ves_id', $ves_id)->where('ctr_i_e_t', '=', 'I')->where('ctr_intern_status','!=', '01' )->count();
        $users = User::all();
        $vessel_voyage = VVoyage::where('ves_id', $ves_id)->first();
        $currentDateTime = Carbon::now();
        $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
       
        return view('planning.vessel.edit', compact('vessel_voyage', 'currentDateTimeString', 'title', 'bongkar_import',));
    }

    public function update_schedule(Request $request, $ves_id)
    {

        $request->validate([
            'act_anchorage_date' => 'required',
            'act_pilot_date' =>'required',
            'act_start_work_date' =>'required',
            'act_end_work_date' =>'required',
            'arrival_date' =>'required',
            'deparature_date' =>'required',
            'berthing_date' =>'required',

          
        ],
        [


        ]    
    );


        VVoyage::where('ves_id', $ves_id)->update([

       
        'act_anchorage_date'=> $request->act_anchorage_date,     
        'act_pilot_date' => $request->act_pilot_date,       
        'act_start_work_date' => $request->act_start_work_date,        
        'act_end_work_date' => $request->act_end_work_date,        
        'arrival_date' => $request->arrival_date,        
        'deparature_date' => $request->deparature_date,        
        'berthing_date' => $request->berthing_date,
        'export_booking' =>$request->export_booking,
        'export_counter' =>$request->export_counter,
        'import_booking' =>$request->import_booking,
        'import_counter' =>$request->import_counter,
        'clossing_date' => $request->clossing_date,
        'doc_clossing_date' => $request->doc_clossing_date,
        'loading_date' => $request->loading_date,
        'no_bc11' => $request->no_bc11,
        'open_stack_date' =>$request->open_stack_date,
        ]);

        
        return redirect('/planning/vessel-schedule')->with('success', "Jadwal Berhasil Diperbarui");
    }


    public function delete_schedule($ves_id)
    {
        
        VVoyage::where('ves_id',$ves_id)->delete();
        return back()->with('success', "Berhasil Dihapus");
    }

    public function refreshCounter(Request $request)
    {
        $ves_id = $request->input('ves_id');
        $bongkar_import = Item::where('ves_id', $ves_id)
            ->where('ctr_i_e_t', 'I')
            ->where('ctr_intern_status', '!=', '01')
            ->count();

        $bongkar_export = Item::where('ves_id', $ves_id)
            ->where('ctr_i_e_t', 'E')
            ->where('ctr_intern_status', '!=', '01')
            ->count();
        
     
        
        return response()->json([
            'import_counter' => $bongkar_import,
            'export_counter' => $bongkar_export,
     
        ]);
    }
}
