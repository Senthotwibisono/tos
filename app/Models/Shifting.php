<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shifting extends Model
{
    use HasFactory;
    protected $table = 'shifting';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'ves_id',
        'ves_code',
        'ves_name',
        'voy_no',
        'ves_name',
        'container_key',
        'container_no',
        'ctr_size',
        'ctr_type',
        'ctr_status',
        'landing',
        'crane_d_k',
        'id_alat',
        'alat',
        'id_operator',
        'operator',
        'bay_from',
        'row_from',
        'tier_from',
        'bay_to',
        'row_to',
        'tier_to',
    ];
}
