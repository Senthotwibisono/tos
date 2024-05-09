<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTongkakDetail extends Model
{
    use HasFactory;
    protected $table = 'invoice_tambat_tongkak_detail';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'inv_id',
        'name',
        'tarif',
        'jumlah',
        'total',
        'detail'
    ];
}
