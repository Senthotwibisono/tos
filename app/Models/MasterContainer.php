<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterContainer extends Model
{
    use HasFactory;
    protected $table = 'master_container';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'container_no',
        'ctr_size',
        'ctr_type',
        'status',
        'location',
    ];
}
