<?php

namespace App\Http\Controllers\Lapangan\Vessel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VesselController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function voyageIndex()
    {
        $data['title'] = 'Vessel Schedule';

        return view('lapangan.planning.voyage.index');
    }
}
