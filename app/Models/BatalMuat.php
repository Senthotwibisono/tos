<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatalMuat extends Model
{
    use HasFactory;
    protected $table = 'batal_muat';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'container_key',
        'container_no',
        'old_ves_id',
        'old_ves_name',
        'old_voy_no',
        'new_ves_id',
        'new_ves_name',
        'new_voy_no',
        'alasan_batal_muat',
        'tanggal_batal_muat',
        'ctr_action',
        'tanggal_action',
        'user_id',
        'last_update',

    ];

    public function cont()
    {
        return $this->belongsTo(Item::class, 'container_key', 'container_key');
    }
}
