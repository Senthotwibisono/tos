<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VMaster extends Model
{
    use HasFactory;

    protected $table = 'vessel_master';
    public $timestamps = false;

    protected $fillable = [
               'ves_code',
               'ves_name',
               'agent' ,
               'liner' ,
               'liner_name',
               'reg_flag' ,
               'ocean_interisland' ,
               'g_r_t',
               'b_r_t',
               'l_o_a',
               'd_w_t',
               'n_r_t',
               'draft',
               'ves_lenght' ,
               'year_made' ,
               'country_made', 
               'call_sign' ,
               'lloyds_no',
               'isps_code',
               'Remark' ,
               'isps_date',
               'user_id' 
               
    ];
}
