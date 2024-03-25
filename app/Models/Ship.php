<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;
    protected $table = 'profile_bay_det';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'ves_id',
        'ves_code',
        'voy_no',
        'bay_slot',
        'bay_row',
        'bay_tier',
        'container_no',
        'container_key',
        'ctr_size',
        'ctr_type',
        'dangerous_yn',
        'ctr_i_e_t',
        'on_under',
    ];

    public function cont()
    {
        return $this->belongsTo(Item::class, 'container_key', 'container_key');
    }
}
