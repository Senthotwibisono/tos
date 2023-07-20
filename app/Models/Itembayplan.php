<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Http\Models\VVoyage;

class item extends Model
{
    use HasFactory;
    protected $table = 'itembayplan';
    public $timestamps = false;
    protected $primaryKey = 'container_key';
    
    protected $fillable = [
    'container_no',
    'isocode',
    'ctr_size',
    'ctr_type',
    'ctr_status',
    'ctr_intern_status'=> '01',
    'bay_slot',
    'bay_row',
    'bay_tier',
    'load_port',
    'disch_port',
    'fdisch_port',   
    'ctr_opr',                           
    'user_id' 
    ];
}
