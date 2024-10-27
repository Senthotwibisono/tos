<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AndroidController; 


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return '/';
            } elseif ($user->hasRole('android'))
                return '/android-dashboard';
            elseif ($user->hasRole('gate')) {
                return '/android-gate';
            } elseif ($user->hasRole('yard')) {
                return '/android-yard';
            } elseif ($user->hasRole('cc')) {
                return '/android-cc';
            }
            elseif ($user->hasRole('BeaCukai')) {
                return '/bea-cukai-sevice';
            } elseif ($user->hasRole('customer')) {
                return '/customer-dashboard';
            }
        }

        // Default fallback
        return '/default-page';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $reuest, $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard');
        } elseif ($user->hasRole('android')) {
            return redirect('/android-dashboard');
        } elseif ($user->hasRole('gate')) {
            return redirect('/android-gate');
        } elseif ($user->hasRole('yard')) {
            return redirect('/android-yard');
        } elseif ($user->hasRole('cc')) {
            return redirect('/android-cc');
        } elseif ($user->hasRole('BeaCukai')) {
            return redirect('/bea-cukai-sevice');
        } elseif ($user->hasRole('customer')) {
            return redirect('/customer-dashboard');
        } else {
            return redirect('/invoice/menu');
        }
    }
}