<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActAlat extends Model
{
    use HasFactory;

    protected $table = 'activity_alat';
    public $timestamps = false;

    protected $fillable = [
       'id_alat',
       'category',
       'nama_alat',
       'operator',
       'container_key',
       'container_no',
       'activity',
    ];
}
