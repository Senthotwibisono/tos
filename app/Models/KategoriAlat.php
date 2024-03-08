<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAlat extends Model
{
    use HasFactory;

    protected $table = 'master_alat_category';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
       'name',
    ];
}
