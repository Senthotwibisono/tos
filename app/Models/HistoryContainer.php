<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryContainer extends Model
{
    use HasFactory;
    protected $table = 'history_container';
    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = false;

    protected $fillable = [
        'container_key',
        'container_no',
        'operation_name',
        'ves_id',
        'ves_code',
        'voy_no',
        'ctr_i_e_t',
        'ctr_active_yn',
        'ctr_size',
        'ctr_type',
        'ctr_status',
        'ctr_intern_status',
        'yard_blok',
        'yard_slot',
        'yard_row',
        'yard_tier',
        'truck_no',
        'truck_in_date',
        'truck_out_date',
        'oper_name',
        'update_time',
        'iso_code',
    ];
}
