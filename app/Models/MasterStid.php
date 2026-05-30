<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterStid extends Model
{
    use HasFactory;
    protected $table = 'master_stid';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'company',
        'card_number',
        'stid',
        'truck_no',
        'vehicle_type',
        'merk',
        'status',
        'uid',
        'created_at',
        'uid_updated',
    ];

    public function uid()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function lastupdate()
    {
        return $this->belongsTo(User::class, 'uid_updated', 'id');
    }
}
