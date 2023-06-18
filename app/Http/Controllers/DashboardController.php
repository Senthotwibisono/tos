<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $user = Auth::user();
        $hideSidebar = $user->role === 'android';

        $data = [];
        $data["active"] = "Dashboard";
        $data["subactive"] = "";

        return view('dashboard', compact('hideSidebar'), $data);
    }
}
