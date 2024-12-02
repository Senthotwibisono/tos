<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceForm extends Model
{
    use HasFactory;
    protected $table = 'invoice_form';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'expired_date',
        'os_id',
        'cust_id',
        'do_id',
        'ves_id',
        'i_e',
        'disc_date',
        'done',
        'massa2',
        'massa3',
        'tipe',
        'discount_ds',
        'discount_dsk',
        'tarif',
        'palka',
        'keterangan',
        'user_id',
    ];

    public function doOnline()
    {
        return $this->belongsTo(DOonline::class, 'do_id', 'id');
    }

    public function oldInv()
    {
        if ($this->tipe === 'P') {
            // If 'tipe' is 'P', use the Extend class
            return $this->belongsTo(Extend::class, 'do_id', 'id');
        } else {
            // Otherwise, use the InvoiceImport class
            return $this->belongsTo(InvoiceImport::class, 'do_id', 'id');
        }
    }

    public function Kapal()
    {
        return $this->belongsTo(VVoyage::class, 'ves_id', 'ves_id');
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
