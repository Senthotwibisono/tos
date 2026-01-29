<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extend extends Model
{
    use HasFactory;
    protected $table = 'invoice_extend';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'form_id',
        'proforma_no',
        'inv_id',
        'inv_no',
        'cust_id',
        'cust_name',
        'fax',
        'npwp',
        'alamat',
        'os_id',
        'os_name',
        'container_key',
        'admin',
        'total',
        'pajak',
        'grand_total',
        'order_by',
        'order_at',
        'piutang_at',
        'lunas',
        'lunas_at',
        'expired_date',
        'discount',
        'invoice_date',
        'user_id',
        'pay_flag',
        'va',
        'materai_id',
        'sn',
        'image_materai'
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'id');
    }

    public function Form()
    {
        return $this->belongsTo(InvoiceForm::class, 'form_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(OrderService::class, 'os_id', 'id');
    }
}
