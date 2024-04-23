<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TKapalDetail extends Model
{
    use HasFactory;
    protected $table = 'invoice_tambat_kapal_detail';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'inv_id',
        'gt_kapal',
        'etmal',
        'tarif',
        'total'
    ];
}
