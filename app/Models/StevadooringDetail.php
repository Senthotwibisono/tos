<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StevadooringDetail extends Model
{
    use HasFactory;
    protected $table = 'invoice_stevadooring_detail';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'inv_id',
        'ctr_size',
        'ctr_status',
        'tarif',
        'jumlah',
        'total',
        'detail'
    ];
}
