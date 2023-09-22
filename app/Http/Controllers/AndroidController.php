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
    public function gate_android()
    {
        $title = 'Gate Dashboard';
        return view('android.gate', compact('title'));
    }
    public function yard_android()
    {
        $title = 'Yard Dashboard';
        return view('android.yard', compact('title'));
    }

    public function cc_android()
    {
        $title = 'CC Dashboard';
        return view('android.cc', compact('title'));
    }
    

    
}
