<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AndroidController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = 'Oprational Dashboard';
        return view('android.main', compact('title'));
    }

    
}
