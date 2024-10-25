<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceImport extends Model
{
    use HasFactory;
    protected $table = 'invoice_import';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'form_id',
        'inv_type',
        'proforma_no',
        'inv_no',
        'job_no',
        'cust_id',
        'cust_name',
        'fax',
        'npwp',
        'alamat',
        'os_id',
        'os_name',
        'container_key',
        'total',
        'admin',
        'pajak',
        'grand_total',
        'order_by',
        'order_at',
        'disc_date',
        'piutang_at',
        'piutang',
        'lunas',
        'lunas_at',
        'expired_date',
        'do_no',
        'last_expired_date',
        'discount',
        'invoice_date',
        'user_id',
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
