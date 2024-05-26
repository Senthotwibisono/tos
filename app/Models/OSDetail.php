<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OSDetail extends Model
{
    use HasFactory;
    protected $table = 'order_service_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'os_id',
        'os_name',
        'type',
        'master_item_id',
        'master_item_name',
        'kode',
    ];
}
