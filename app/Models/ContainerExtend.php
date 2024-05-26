<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerExtend extends Model
{
    use HasFactory;
    protected $table = 'container_extend';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'container_key',
        'container_no',
        'ctr_size',
        'ctr_status',
        'form_id',
        'ves_id',
        'ves_name',
        'ctr_type',
        'ctr_intern_status',
        'gross',
    ];
}
