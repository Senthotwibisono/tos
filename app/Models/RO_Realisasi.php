<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RO_Realisasi extends Model
{
    use HasFactory;
    protected $table = 'ro_realisasi';
    protected $primaryKey = 'ro_realisasi_id';
    public $timestamps = false;

    protected $fillable = [
        'ro_no',
        'container_no',
        'container_key',
        'truck_no',
        'truck_id',
        'status',
    ];
}
