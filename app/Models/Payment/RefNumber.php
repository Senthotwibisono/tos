<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Customer;

class RefNumber extends Model
{
    use HasFactory;
    protected $table = 'payment_va';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'virtual_account',
        'expired_va',
        'invoice_type',
        'customer_name',
        'customer_id',
        'description',
        'billing_amount',
        'payment_amount',
        'type_trx',
        'status',
        'bank_id',
        'merchant_type',
        'terminal_id',
        'trx_id',
        'inquiry_time',
        'lunas_time',
        'user_id',
        'created_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
