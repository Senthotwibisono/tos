<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\InvoiceExport;
use App\Models\Extend;
use App\Models\InvoiceImport;
class RefDetail extends Model
{
    use HasFactory;
    protected $table = 'payment_va_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'va_id',
        'inv_id',
        'invoice_ie',
        'proforma_no',
        'invoice_no',
        'invoice_type',
        'amount',
    ];

    public function Invoice()
    {   
        if ($this->invoice_ie == 'E') {
            return $this->belongsTo(InvoiceExport::class, 'inv_id', 'id');
        }

        if ($this->invoice_ie == 'X') {
            return $this->belongsTo(Extend::class, 'inv_id', 'id');
        }

        if ($this->invoice_ie == 'I') {
            return $this->belongsTo(InvoiceImport::class, 'inv_id', 'id');
        }
        
    }

    public function VA()
    {
        return $this->belongsTo(RefNumber::class, 'va_id', 'id');
    }
}
