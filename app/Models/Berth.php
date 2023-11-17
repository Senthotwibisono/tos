<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berth extends Model
{
    use HasFactory;

    protected $table = 'berth';
    public $timestamps = false;
    protected $fillable = [
               'berth_no', 
               'berth_code',
               'consturct_type',
               'from_length' ,
               'to_length',
               'ocean_interisland' ,
               'user_id' 
    ];          
}
