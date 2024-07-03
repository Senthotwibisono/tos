<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceExport extends Model
{
    use HasFactory;
    protected $table = 'invoice_export';
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
        'jpb_extruck_20',
        'sewa_crane_20',
        'cargo_dooring_20',
        'paket_stuffing_20',
        // 21
        'm1_21',
        'm2_21',
        'm3_21',
        'lolo_full_21',
        'lolo_empty_21',
        'pass_truck_masuk_21',
        'pass_truck_keluar_21',
        'jpb_extruck_21',
        'sewa_crane_21',
        'cargo_dooring_21',
        'paket_stuffing_21',
        //  40
        'm1_40',
        'm2_40',
        'm3_40',
        'lolo_full_40',
        'lolo_empty_40',
        'pass_truck_masuk_40',
        'pass_truck_keluar_40',
        'jpb_extruck_40',
        'sewa_crane_40',
        'cargo_dooring_40',
        'paket_stuffing_40',
        // 42
        'm1_42',
        'm2_42',
        'm3_42',
        'lolo_full_42',
        'lolo_empty_42',
        'pass_truck_masuk_42',
        'pass_truck_keluar_42',
        'jpb_extruck_42',
        'sewa_crane_42',
        'cargo_dooring_42',
        'paket_stuffing_42',
        'extend',
        'total',
        'admin',
        'discount',
        'pajak',
        'grand_total',
        'order_by',
        'order_at',
        'etd',
        'piutang_at',
        'piutang',
        'lunas',
        'lunas_at',
        'expired_date',
        'booking_no',
        'invoice_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'id');
    }
}
