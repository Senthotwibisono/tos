<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TpsDokPKBE extends Model
{
    protected $table = 'tps_dokpkbe';
    protected $primaryKey = 'TPS_DOKPKBE_PK';
    public $timestamps = false;
    protected $fillable = [
        'CAR',
        'KD_KANTOR',
        'NOPKBE',
        'TGLPKBE',
        'NPWP_EKS',
        'NAMA_EKS',
        'NO_CONT',
        'SIZE', 
];      
}
