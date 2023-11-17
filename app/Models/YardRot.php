<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YardRot extends Model
{
    use HasFactory;
    protected $table = 'yard_rowtier';
    public $timestamps = false;
//     protected $primaryKey = 'YARD_BLOCK	';
    
//     public function VVoyage()
//     {
//         return $this->belongsTo(VVoyage::class, 'ves_id');
//     }
    
    protected $fillable = [
        'YARD_BLOCK', 
        'YARD_SLOT', 
        'YARD_ROW', 
        'YARD_TIER', 
        'STATUS', 
        'CONTAINER_KEY', 
        'CONTAINER_NUMBER', 
        'UPDATE_TIME', 
        'PCH'
        ];
}
