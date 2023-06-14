<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $table = 'yard_rowtier';
    public $timestamps = false;
    protected $fillable = [
               'yard_block', 
               'yard_slot',
               'yard_row',
               'yard_tier',
               'user_id' 
    ];          
}
