<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    use HasFactory;
    protected $table = 'import_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'inv_id',
        'inv_no',
        'inv_type',
        'keterangan',
        'ukuran',
        'ctr_status',
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

    public function master()
    {
        return $this->belongsTo(InvoiceImport::class, 'inv_id','id');
    }
}
