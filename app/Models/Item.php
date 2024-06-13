<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class item extends Model
{
    use HasFactory;
    protected $table = 'item';
    public $timestamps = false;
    protected $primaryKey = 'container_key';

    protected $fillable = [
        'container_key',
        'container_no',
        'ves_id',
        'ves_code',
        'ves_name',
        'voy_no',
        'ctr_i_e_t',
        'ctps_yn',
        'ctr_active_yn',
        'ctr_size',
        'ctr_type',
        'ctr_status',
        'ctr_intern_status',
        'order_service',
        'disc_load_trans_shift',
        'land_ship_crane',
        'shift_by',
        'gross',
        'gross_class',
        'over_height',
        'over_weight',
        'over_length',
        'commodity_code',
        'commodity_name',
        'org_port',
        'load_port',
        'disch_port',
        'fdisch_port',
        'shipper',
        'agent',
        'consignee',
        'chilled_temp',
        'imo_code',
        'dangerous_yn',
        'dangerous_label_yn',
        'bl_no',
        'do_no',
        'seal_no',
        'peb_exp_no',
        'ctps_no',
        'pib_imp_no',
        'job_no',
        'invoice_no',
        'disc_load_seq',
        'bay_slot',
        'bay_row',
        'bay_tier',
        'yard_block',
        'yard_slot',
        'yard_row',
        'yard_tier',
        'sp2_ke_date',
        'ctps_to_peb_date',
        'disc_date',
        'load_date',
        'stack_date',
        'truck_no',
        'truck_in_date',
        'truck_out_date',
        'arrival_carrier',
        'departure_carrier',
        'crane_no',
        'crane_oper',
        'ship_oa',
        'wharf_oa',
        'ht_no',
        'ht_driver',
        'cc_tt_no',
        'cc_tt_oper',
        'wharf_yard_oa',
        'depot_warehouse_code',
        'container_dest',
        'remarks',
        'oper_name',
        'iso_code',
        'no_permohonan_ob',
        'bhd_date',
        'stripping_date',
        'stuffing_date',
        'ctr_opr',
        'no_pos_bl',
        'ctr_vip_yn',
        'dg_uncode',
        'kpbc',
        'consl_code',
        'f_d_key',
        'consl_f_d_key',
        'is_damage',
        'jenis_dok',
        'bc_flag',
        'user_id',
        'ro_no',
        'stuffing_procces',
        'mty_type',
        'selected_do',
        'booking_no',
        'alat_yard',
        'os_id',
    ];

    public function job()
    {
        return $this->hasMany(Job::class, 'container_key', );
    }

    public function PLC()
    {
        return $this->hasOne(ActAlat::class, 'container_key')
                ->where('activity', 'PLC')
                ->orderBy('created_at', 'desc');
    }
    public function service()
    {
        return $this->belongsTo(OrderService::class, 'os_id', 'id');
    }
    public function Kapal()
    {
        return $this->belongsTo(VVoyage::class, 'ves_id');
    }
    public function CtrInv()
    {
        return $this->hasOne(ContainerInvoice::class, 'container_key');
    }
}