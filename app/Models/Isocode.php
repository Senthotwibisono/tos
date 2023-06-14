<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Isocode extends Model
{
    use HasFactory;
    protected $table = 'isocode';
    public $timestamps = false;

    protected $fillable = [
        'iso_code',
        'iso_size',
        'iso_type',
        'iso_weight',
        'iso_height',
        'descr',
        'user_id'
    ];

}
