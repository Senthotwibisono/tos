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
        'm1',
        'm2',
        'm3',
        // Tarif
        'ctr_20',
        'ctr_40',
        'ctr_21',
        'ctr_42',
        // 20
        'm1_20',
        'm2_20',
        'm3_20',
        // 21
        'm1_21',
        'm2_21',
        'm3_21',
        // 40
        'm1_40',
        'm2_40',
        'm3_40',
        // 42
        'm1_42',
        'm2_42',
        'm3_42',
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
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'id');
    }
}
