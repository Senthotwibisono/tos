<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobImport extends Model
{
    use HasFactory;
    protected $table = 'job_import';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'inv_id',
        'job_no',
        'os_id',
        'os_name',
        'cust_id',
        'active_to',
        'container_key',
        'container_no',
        'ves_id',
        'extend_flag',
    ];

    public function Kapal()
    {
        return $this->belongsTo(VVoyage::class, 'ves_id');
    }
    
    public function Item()
    {
        return $this->belongsTo(Item::class, 'container_key', 'container_key');
    }
    
    public function Service()
    {
        return $this->belongsTo(OrderService::class, 'os_id', 'id');
    }
    
    public function Invoice()
    {
        return $this->belongsTo(InvoiceImport::class, 'inv_id', 'id');
    }
}
