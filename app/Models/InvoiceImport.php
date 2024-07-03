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
        'massa1',
        'massa2',
        'massa3',
        // Tarif
        'ctr_20',
        'ctr_40',
        'ctr_21',
        'ctr_42',
        // 20
        'm1_20',
        'm2_20',
        'm3_20',
        'lolo_full_20',
        'lolo_empty_20',
        'pass_truck_masuk_20',
        'pass_truck_keluar_20',
        'paket_stripping_20',
        'pemindahan_petikemas_20',

        // 21
        'm1_21',
        'm2_21',
        'm3_21',
        'lolo_full_21',
        'lolo_empty_21',
        'pass_truck_masuk_21',
        'pass_truck_keluar_21',
        'paket_stripping_21',
        'pemindahan_petikemas_21',

        // 40
        'm1_40',
        'm2_40',
        'm3_40',
        'lolo_full_40',
        'lolo_empty_40',
        'pass_truck_masuk_40',
        'pass_truck_keluar_40',
        'paket_stripping_40',
        'pemindahan_petikemas_40',

        // 42
        'm1_42',
        'm2_42',
        'm3_42',
        'lolo_full_42',
        'lolo_empty_42',
        'pass_truck_masuk_42',
        'pass_truck_keluar_42',
        'paket_stripping_42',
        'pemindahan_petikemas_42',

        'extend',
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
