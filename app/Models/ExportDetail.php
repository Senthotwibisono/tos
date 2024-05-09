<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportDetail extends Model
{
    use HasFactory;
    protected $table = 'export_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'inv_id',
        'inv_no',
        'inv_type',
        'keterangan',
        'detail',
        'ukuran',
        'jumlah',
        'satuan',
        'harga',
        'expired_date',
        'order_date',
        'lunas',
        'cust_id',
        'cust_name'
    ];
}
