<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RO extends Model
{
    use HasFactory;
    protected $table = 'ro_dok';
    protected $primaryKey = 'ro_id';
    public $timestamps = false;

    protected $fillable = [
        'ro_no',
        'stuffing_service',
        'jmlh_cont',
        'shipper',
        'ves_id',
        'ves_name',
        'ves_code',
        'voy_no',
        'pod',
        'ctr_20',
        'ctr_40',
        'file'
    ];
}
