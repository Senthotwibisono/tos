<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpsDokNPE extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tps_doknpexml';
    protected $primaryKey = 'TPS_DOKNPE_PK';
    public $timestamps = false;

}