<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RO_Gate extends Model
{
    use HasFactory;
    protected $table = 'ro_gati';
    protected $primaryKey = 'ro_id_gati';
    public $timestamps = false;

    protected $fillable = [
        'ro_no',
        'truck_no',
        'truck_in_date',
        'truck_out_date',
        'truck_in_date_after',
        'truck_out_date_after',
        'status',
    ];
}
