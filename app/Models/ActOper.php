<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActOper extends Model
{
    use HasFactory;
    protected $table = 'act_oper';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
       'alat_id',
       'alat_category',
       'alat_name',
       'operator_id',
       'operator_name',
       'container_key',
       'container_no',
       'ves_id',
       'ves_name',
       'voy_no',
       'activity',
    ];

    public function opRole()
    {
        return $this->belongsTo(Operator::class, 'operator_id', 'id');
    }
}
