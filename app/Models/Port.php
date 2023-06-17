<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;
    protected $table = 'port_master';
    public $timestamps = false;

    protected $fillable = [
        'port',
        'un_port',
        'un_country',
        'country_name',
        'descr',
        'user_id',
    ];
}
