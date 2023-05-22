<?php

namespace App\Http\Controllers;

use Auth;
use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function setSession(Request $request, $key, $value)
    {
        $request->session()->put($key, $value);
        return response()->json(['success' => true]);
    }

    public function unsetSession(Request $request, $key)
    {
        $request->session()->forget($key);
        return response()->json(['success' => true]);
    }
}
