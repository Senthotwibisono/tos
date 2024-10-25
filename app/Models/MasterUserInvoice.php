<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterUserInvoice extends Model
{
    use HasFactory;
    protected $table = 'master_user_invoice';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'customer_id',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
