<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TpsDokPKBE extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    
    protected $table = 'tps_dokpkbe';
    protected $primaryKey = 'TPS_DOKPKBE_PK';
    public $timestamps = false;
}
