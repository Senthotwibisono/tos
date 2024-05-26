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
        'ctr_status',
        'pajak',
    ];
}
