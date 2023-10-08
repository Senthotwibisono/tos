<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

    public function redirectToRole()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return '/';
            } elseif ($user->hasRole('android'))
                return redirect('/android-dashboard');
            elseif ($user->hasRole('gate')) {
                return redirect('/android-gate');
            } elseif ($user->hasRole('yard')) {
                return redirect('/android-yard');
            } elseif ($user->hasRole('cc')) {
                return '/android-cc';
            }
        }

        // public function showDashboardView()
        // {
        //     // Get the authenticated user's role
        //     $user = Auth::user();
        //     $role = $user->getRoleNames()->first(); // Assuming you're using Spatie/Permission

        //     return view('android-dashboard', ['role' => $role]);
        // }
    }
}
