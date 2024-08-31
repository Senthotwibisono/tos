<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceHeaderStevadooring extends Model
{
    use HasFactory;

    protected $table = 'invoice_header_stevadooring';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'proforma_no',
        'invoice_no',
        'cust_id',
        'cust_name',
        'fax',
        'npwp',
        'alamat',
        'rbm_id',
        'ves_id',
        'ves_code',
        'voy_in',
        'voy_out',
        'ves_name',
        'arrival_date',
        'deparature_date',
        'open_stack_date',
        'clossing_date',
        'tambat_tongkak',
        'tambat_kapal',
        'stevadooring',
        'shifting',
        'tambat_tongkak_total',
        'tambat_kapal_total',
        'stevadooring_total',
        'shifting_total',
        'total',
        'pajak',
        'admin',
        'grand_total',
        'created_by',
        'created_at',
        'last_update_at',
        'last_update_by',
        'lunas_at',
        'piutang_at',
        'lunas',
        'status',
        'invoice_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'id');
    }
}
