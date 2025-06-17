<?php

namespace App\Http\Controllers;

use App\Models\Item; // load model
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\HistoryContainer;
use App\Models\Job;
use Illuminate\Pagination\Paginator;


use Auth;
use Carbon\Carbon;
class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index(): View
    {
        $items = Item::whereNot('container_no', '=', '')->get();
        return view('reports.hist.index', compact('items'));
    }

    public function get_cont(Request $request)
    {
        $container_key = $request->id;
        // $lt_cont = Item::where('container_key','=',$container_key);
        $item = Item::findOrFail($container_key);
        $histcontnrs = HistoryContainer::where('container_key', '=', $container_key)->get();
        $anncectrjobs = Job::where('container_key', '=', $container_key)->get([
            'CTR_STATUS',
            'CTPS_YN',
            'COMMODITY_CODE',
            'COMMODITY_NAME',
            'STACK_DATE'
        ]);
        // return response()->view('reports.hist.display_', compact('lw_cont'));
        $view = view('reports.hist.display_', compact('item'))->render();
        return response()->json([
            'cont_disp' => $view,
            'cont_no' => $item->container_no,
            'ves_id' => $item->ves_code
        ]);

        exit();
    }
    public function get_cont_hist(Request $request)
    {
        $container_key = $request->id;
        $histcontnrs = HistoryContainer::where('container_key', '=', $container_key)->get();
        return response()->json(['data' => $histcontnrs]);
    }
    public function get_cont_job(Request $request)
    {
        $container_key = $request->id;
        $anncectrjobs = Job::where('container_key', '=', $container_key)->get([
            'ctr_status',
            'ctps_yn',
            'commodity_code',
            'commodity_name',
            'stack_date'
        ]);
        return response()->json(['data' => $anncectrjobs]);
    }


    public function postHistoryContainer($dataHistory)
    {
        // dd($dataHistory);
        $history = HistoryContainer::create([
            'container_key' => $dataHistory['container_key'],
            'container_no' => $dataHistory['container_no'],
            'operation_name' => $dataHistory['operation_name'],
            'ves_id' => $dataHistory['ves_id'],
            'ves_code' => $dataHistory['ves_code'],
            'voy_no' => $dataHistory['voy_no'],
            'ctr_i_e_t' => $dataHistory['ctr_i_e_t'],
            'ctr_active_yn' => $dataHistory['ctr_active_yn'],
            'ctr_size' => $dataHistory['ctr_size'],
            'ctr_type' => $dataHistory['ctr_type'],
            'ctr_status' => $dataHistory['ctr_status'],
            'ctr_intern_status' => $dataHistory['ctr_intern_status'],
            'yard_blok' => $dataHistory['yard_blok'],
            'yard_slot' => $dataHistory['yard_slot'],
            'yard_row' => $dataHistory['yard_row'],
            'yard_tier' => $dataHistory['yard_tier'],
            'truck_no' => $dataHistory['truck_no'],
            'truck_in_date' => $dataHistory['truck_in_date'],
            'truck_out_date' => $dataHistory['truck_out_date'],
            'oper_name' => $dataHistory['oper_name'],
            'iso_code' => $dataHistory['iso_code'],
        ]);
    }
}
