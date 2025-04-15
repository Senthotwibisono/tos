<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenMandiri extends Model
{
    use HasFactory;
    protected $table = 'payment_token';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
       'user_id',
       'key',
       'token',
       'expired',
    ];
}
