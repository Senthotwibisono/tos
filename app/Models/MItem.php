<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MItem extends Model
{
    use HasFactory;
    protected $table = 'master_invoice';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'kode',
        'count_by',
        'size',
        'massa',
    ];
}
