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
        'extend',
        'total',
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
        'do_no,'
    ];
}
