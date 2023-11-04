<?php

namespace App\Http\Controllers;

use Iluminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\VMaster;
use App\Models\Bay;

class ProfileKapal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vessel_master = VMaster::all();
        return view('planning.profile.main', compact('vessel_master'));
    }

    public function stores(Request $request) // store modal di grid
    {
        // Validate the request data here if needed

        // Create a new instance of your model
        $model = new Bay();

        // Fill the model with the form data
        $model->VES_CODE = $request->input('ves_code');
        $vesselMaster = VMaster::where('ves_code', $request->input('ves_code'))->first();
        // var_dump($vesselMaster->ves_name);
        // die();
        $model->BAY1 = $request->input('bay_name');
        $model->START_ROW = $request->input('start_row');
        $model->START_ROW_UNDER = $request->input('start_row_under');
        $model->TIER = $request->input('max_tier');
        $model->TIER_UNDER = $request->input('max_tier_under');
        $model->MAX_ROW = $request->input('max_row');
        $model->MAX_ROW_UNDER = $request->input('max_row_under');
        $model->START_TIER = $request->input('start_tier');
        $model->START_TIER_UNDER = $request->input('start_tier_under');

        // Save the model to the database
        $model->save();

        // Optionally, you can return a response to indicate success or failure
        // return redirect()->route('grid-box.index', ['ves_code' => $request->ves_code]);
        // return redirect()->route('grid-box.index', ['ves_code' => $request->ves_code]);
        return redirect('/planning/grid?ves_code=' . $request->ves_code . '&ves_name=' . $vesselMaster->ves_name);

        // return redirect()->route('grid-box.index');
    }

    public function grid(Request $request)
    {
        $ves_code = $request->ves_code;
        // Fetch data from the database based on the VES_CODE
        $gridBoxData = Bay::where('VES_CODE', $ves_code)->get(); // Replace 'VES_CODE' with the actual column name
        // dd($gridBoxData);
        return view('planning.profile.grid', compact('gridBoxData'));
    }
}



