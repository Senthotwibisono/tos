<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlasanSegel extends Model
{
    use HasFactory;

    protected $table = 'alasan_segel';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
       'type',
    ];
}
