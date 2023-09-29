<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VMaster;
use Illuminate\Support\Str;

class VVoyage extends Model
{
    use HasFactory;

    protected $table = 'vessel_voyage';

    public $timestamps = false;
   
    public function VMaster()
{
    return $this->belongsTo(VMaster::class, 'vess_code');
}


    protected $fillable = [
        'ves_code',
        'voy_in',
        'voy_out',
        'ves_name',
        'agent',
        'liner',
        'voyage_owner',
        'import_yn',
        'export_yn',
        'ves_length',
        'reg_flag',
        'ocean_interisland',
        'berth_no',
        'berth_fr_metre',
        'berth_to_metre',
        'btoa_side',
        'berth_grid',
        'est_anchorage_date',
        'act_anchorage_date',
        'est_pilot_date',
        'act_pilot_date',
        'est_start_work_date',
        'act_start_work_date',
        'est_end_work_date',
        'act_end_work_date',
        'eta_date',
        'arrival_date',
        'etd_date',
        'deparature_date',
        'est_berthing_date',
        'berthing_date',
        'discharge_date',
        'loading_date',
        'exit_date',
        'clossing_date',
        'doc_clossing_date',
        'recv_cargo_cutoff_date',
        'non_oper_time',
        'idle_time',
        'effective_time',
        'year_made',
        'country_made',
        'last_port',
        'next_port',
        'origin_port',
        'dest_port',
        'liner_tramp',
        'feeder_direct',
        'export_booking',
        'export_counter',
        'import_booking',
        'import_counter',
        'vessel_service',
        'billing_complate',
        'remarks',
        'update_time',
        'user_id',
        'vessel_history',
        'berth_code',
        'cy_code',
        'no_bc11',
        'no_ppk',
        'open_stack_date',
    ];
}
