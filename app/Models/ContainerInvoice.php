<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerInvoice extends Model
{
    use HasFactory;
    protected $table = 'container_invoice';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'container_key',
        'container_no',
        'ctr_size',
        'ctr_status',
        'form_id',
        'ves_id',
        'ves_name',
        'ctr_type',
        'ctr_intern_status',
        'gross',
    ];

    public function Form()
    {
        return $this->belongsTo(InvoiceForm::class, 'form_id');
    }
    public function Kapal()
    {
        return $this->belongsTo(VVoyage::class, 'ves_id');
    }
}
