<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTarif extends Model
{
    use HasFactory;
    protected $table = 'master_tarif';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'os_id',
        'os_name',
        'ctr_size',
        'm1',
        'm2',
        'm3',
        'lolo_full',
        'lolo_empty',
        'pass_truck_masuk',
        'pass_truck_keluar',
        'admin',
        'pajak',
        'paket_stripping',
        'pemindahan_petikemas',
        'create_by',
        'update_by',
        'created_at',
        'update_at',
        // Export
        'jpb_extruck',
        'sewa_crane',
        'cargo_dooring',
        'paket_stuffing',
    ];
}
