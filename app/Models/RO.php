<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RO extends Model
{
    use HasFactory;
    protected $table = 'ro_dok';
    protected $primaryKey = 'ro_id';
    public $timestamps = false;

    protected $fillable = [
        'ro_no',
        'stuffing_service',
        'jmlh_cont',
    ];
}
