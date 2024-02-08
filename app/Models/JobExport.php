<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobExport extends Model
{
    use HasFactory;
    protected $table = 'job_export';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'inv_id',
        'job_no',
        'os_id',
        'os_name',
        'cust_id',
        'active_to',
        'container_key',
        'container_no',
        'ves_id',
    ];
}
