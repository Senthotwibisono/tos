<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VService extends Model
{
    use HasFactory;

    protected $table = 'vessel_service';
    public $timestamps = false;

    protected $fillable = [
        'service' ,
        'disch_port' ,
        'user_id'
];          
    
}
