<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContainerInvoice as Container;
use App\Models\ContainerExtend as ContainerExtd;

class GateCheckingController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function Main(Request $request)
    {
        $data['title'] = "Checking Container Invoice";
        $data['containers'] = Container::orderBy('ves_id', 'desc')->get();


        return view('gate.checking.main', $data);
    }

    public function MainPerpanjangan(Request $request)
    {
        $data['title'] = "Checking Container Invoice";
        $data['containers'] = ContainerExtd::orderBy('ves_id', 'desc')->get();


        return view('gate.checking.main', $data);
    }
}
