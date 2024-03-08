<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VVoyage;
use App\Models\Yard;
use App\Models\HistoryContainer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'Admin Dashboard';
        $vessel_voyage = VVoyage::orderBy('arrival_date', 'desc')->take(3)->get();
        $history_container = HistoryContainer::orderBy('update_time', 'desc')->take(3)->get();
        $data = [];

        $total = 1600;
        $countNotNull = Yard::whereNotNull('container_key')->count();
        $countNull = $total - $countNotNull;
        $data["active"] = "Dashboard";
        $data["subactive"] = "";
        // dd($data);
        return view('dashboard', compact('title', 'vessel_voyage', 'history_container', 'total'),  [
            'countNotNull' => $countNotNull,
            'countNull' => $countNull,
            'active' => "Dashboard",
            'subactive' => "",
        ]);
    }
}
