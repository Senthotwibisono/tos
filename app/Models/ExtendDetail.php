<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtendDetail extends Model
{
    use HasFactory;
    protected $table = 'extend_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'inv_id',
        'inv_no',
        'inv_type',
        'keterangan',
        'ukuran',
        'jumlah',
        'satuan',
        'expired_date',
        'order_date',
        'lunas',
        'cust_id',
        'cust_name',
        'os_id',
        'jumlah_hari',
        'master_item_id',
        'master_item_name',
        'kode',
        'tarif',
        'total',
        'form_id',
        'count_by'

    ];

    public function Form()
    {
        return $this->belongsTo(InvoiceForm::class, 'form_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(OrderService::class, 'os_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'id');
    }
}
