<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;

use App\Models\VMaster;
class ServiceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function indexImport()
    {
        $data['title'] = 'Seraching History Invoice';

        $data['vessels'] = VMaster::get();
        return view('billingSystem.service.tracking.index', $data);
    }
}
