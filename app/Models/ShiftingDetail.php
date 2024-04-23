<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftingDetail extends Model
{
    use HasFactory;
    protected $table = 'invoice_shifting_detail';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'inv_id',
        'ctr_size',
        'ctr_status',
        'crane',
        'landing',
        'tarif',
        'jumlah',
        'total'
    ];
}
