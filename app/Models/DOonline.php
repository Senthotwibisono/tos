<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DOonline extends Model
{
    use HasFactory;
    protected $table = 'do_online';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'do_no',
        'container_no',
        'bl_no',
        'expired',
        'selected_cont',
        'created_at',
        'created_by',
        'active',
    ];
}
