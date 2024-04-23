<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifStevadooring extends Model
{
    use HasFactory;

    protected $table = 'tarif_stevadooring';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
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
        'tambat_kapal',
        'pajak',
        'admin',
        'created_at',
        'created_by',
        'last_update_by',
    ];
}
