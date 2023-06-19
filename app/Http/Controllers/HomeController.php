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
        $vessel_voyage = VVoyage::orderBy('etd_date', 'desc')->take(3)->get();
        $history_container = HistoryContainer::orderBy('update_time', 'desc')->take(3)->get();

        $countNotNull = Yard::whereNotNull('container_key')->count();
        $countNull = Yard::whereNull('container_key')->count();
        return view('dashboard', compact('title', 'vessel_voyage', 'history_container'), [
            'countNotNull' => $countNotNull,
            'countNull' => $countNull,
        ]);
    }

}
