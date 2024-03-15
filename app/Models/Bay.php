<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bay extends Model
{
    use HasFactory;

    protected $table = 'profile_bay'; 
     protected $primaryKey = 'id';

    protected $fillable = [
        'VES_CODE',
        'BAY1',
        'SIZE1',
        'BAY2',
        'SIZE2',
        'JOINSLOT',
        'WEIGHT_BALANCE_ON',
        'WEIGHT_BALANCE_UNDER',
        'START_ROW',
        'START_ROW_UNDER',
        'TIER',
        'TIER_UNDER',
        'MAX_ROW',
        'MAX_ROW_UNDER',
        'START_TIER',
        'START_TIER_UNDER',
    ];
    public $timestamps = false;
}
