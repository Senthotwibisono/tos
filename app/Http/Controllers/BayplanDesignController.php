<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BayplanDesignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title'] = "Bay PLan";

        return view('load.bayplan.main', $data);
    }
}
