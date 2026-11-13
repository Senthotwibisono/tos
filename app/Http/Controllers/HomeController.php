<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VVoyage;
use App\Models\Yard;
use App\Models\HistoryContainer;
use App\Models\MasterContainer;

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

        $vessel_voyage = VVoyage::orderBy('arrival_date', 'desc')
            ->take(3)
            ->get();    

        $history_container = HistoryContainer::orderBy('update_time', 'desc')
            ->take(3)
            ->get();    

        $data = []; 

        $total = 1600;  

        $countNotNull = Yard::whereNotNull('container_key')->count();
        $countNull = $total - $countNotNull;    

        $data['active'] = 'Dashboard';
        $data['subactive'] = '';    

        // Total kapasitas yard dalam TEUS
        $data['kg'] = 2300;    

        // Total TEUS kontainer aktif
        $terisi = MasterContainer::where('status', 'active')
            ->whereNotNull('container_no')
            ->whereNotIn('container_no', ['', '0'])
            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN ctr_size IN ('20', '21', '22') THEN 1
                        WHEN ctr_size IN ('40', '41', '42', '45') THEN 2
                        ELSE 0
                    END
                ), 0) AS total_teus
            ")
            ->value('total_teus');  

        $kapasitas = $data['kg'];   

        // Kapasitas yang masih tersedia
        $tidakTerisi = max(0, $kapasitas - $terisi);    

        // Persentase kapasitas terpakai
        $data['persentaseTerisi'] = $kapasitas > 0
            ? ceil(($terisi / $kapasitas) * 100)
            : 0;    

        // Persentase kapasitas tersedia
        $data['persentaseTidakTerisi'] = $kapasitas > 0
            ? ceil(($tidakTerisi / $kapasitas) * 100)
            : 0;    

        // Total TEUS aktual
        $data['terisi'] = ceil($terisi);
        $data['tidakTerisi'] = ceil($tidakTerisi);    

        return view(
            'dashboard',
            compact('title', 'vessel_voyage', 'history_container', 'total'),
            $data
        );
    }
    }
