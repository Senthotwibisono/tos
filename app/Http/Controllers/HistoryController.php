<?php

namespace App\Http\Controllers;

use App\Models\Item; // load model
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\HistoryContainer;
use App\Models\Job;
use Illuminate\Pagination\Paginator;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index(): View
    {
        $items = HistoryContainer::select('container_key', 'container_no', 'operation_name', 'ctr_intern_status')
            ->paginate(10); // Adjust the number based on your requirement

            $paginator = new Paginator($items, 10); // Use the same pagination number here


        return view('reports.hist.index', compact('items'));
    }

    public function get_cont(Request $request)
    {
        $container_key = $request->id;
        // $lt_cont = Item::where('container_key','=',$container_key);
        $item = Item::findOrFail($container_key);
        $histcontnrs = HistoryContainer::where('container_key', '=', $container_key)->get([
            'OPERATION_NAME',
            'CTR_STATUS',
            'CTR_INTERN_STATUS'
        ]);
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
        $histcontnrs = HistoryContainer::where('container_key', '=', $container_key)->get([
            'operation_name',
            'ctr_status',
            'ctr_intern_status'
        ]);
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
}
