<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yard extends Model
{
    use HasFactory;
    protected $table = 'yard_rowtier';
    protected $primaryKey = false;
    public $timestamps = false;

    protected $fillable = [
        'status',
        'container_key',
        'container_no',
        'pch',
        'update_time',
        'user_id',
    ];

}
