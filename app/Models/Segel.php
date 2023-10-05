<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segel extends Model
{
    use HasFactory;

    protected $table = 'daftar_segel';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'container_key',
        'container_no',
        'alasan_segel',
        'keterangan',
        'file',
        'no_dok',
        'jenis_dok',
        'status',
    ];
}
