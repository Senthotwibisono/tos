<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileTier extends Model
{
    use HasFactory;
    protected $table = 'profile_tier';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'ves_code',
        'bay_slot',
        'bay_row',
        'bay_tier',
        'on_under',
        'active',
    ];
}
