<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MTDetail extends Model
{
    use HasFactory;
    protected $table = 'master_tarif_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'master_tarif_id',
        'master_item_id',
        'master_item_name',
        'tarif',
        'count_by',
    ];

    public function MItem()
    {
        return $this->belongsTo(MItem::class, 'master_item_id', 'id');
    }
}
