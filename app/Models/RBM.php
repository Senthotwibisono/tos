<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RBM extends Model
{
    use HasFactory;
    
    protected $table = 'realisasi_bongkar_muat';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'ves_id',
        'ves_code',
        'voy_in',
        'voy_out',
        'ves_name',
        'arrival_date',
        'deparature_date',
        'open_stack_date',
        'clossing_date',
        'ctr_20_fcl',
        'ctr_21_fcl',
        'ctr_40_fcl',
        'ctr_42_fcl',
        'ctr_20_mty',
        'ctr_21_mty',
        'ctr_40_mty',
        'ctr_42_mty',
        'shift_20_fcl_d_l',
        'shift_20_mty_d_l',
        'shift_21_fcl_d_l',
        'shift_21_mty_d_l',
        'shift_40_fcl_d_l',
        'shift_40_mty_d_l',
        'shift_42_fcl_d_l',
        'shift_42_mty_d_l',
        'shift_20_fcl_d',
        'shift_20_mty_d',
        'shift_21_fcl_d',
        'shift_21_mty_d',
        'shift_40_fcl_d',
        'shift_40_mty_d',
        'shift_42_fcl_d',
        'shift_42_mty_d',
        'shift_20_fcl_k_l',
        'shift_20_mty_k_l',
        'shift_21_fcl_k_l',
        'shift_21_mty_k_l',
        'shift_40_fcl_k_l',
        'shift_40_mty_k_l',
        'shift_42_fcl_k_l',
        'shift_42_mty_k_l',
        'shift_20_fcl_k',
        'shift_20_mty_k',
        'shift_21_fcl_k',
        'shift_21_mty_k',
        'shift_40_fcl_k',
        'shift_40_mty_k',
        'shift_42_fcl_k',
        'shift_42_mty_k',
        'loose_cargo',
        'ctr_tt',
        'gt_kapal',
        'etmal',
        'created_at',
        'created_by',
        'last_update_by',
        'last_update_at',
    ];
}
