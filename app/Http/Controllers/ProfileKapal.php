<?php

namespace App\Http\Controllers;

use Iluminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\VMaster;
use App\Models\Bay;
use Illuminate\Support\Facades\Redirect as FacadesRedirect;

class ProfileKapal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vessel_master = VMaster::all();
        $ves_name = ''; // Initialize ves_name
        $ves_code = ''; // Initialize ves_code

        // Fetch the ves_name and ves_code from your data source (VMaster model, for example)
        if ($vessel_master->isNotEmpty()) {
            // Assuming you want to get the ves_name and ves_code from the first vessel in the collection
            $ves_name = $vessel_master->first()->ves_name;
            $ves_code = $vessel_master->first()->ves_code;
        }

        return view('planning.profile.main', compact('vessel_master', 'ves_name', 'ves_code'));
    }




    public function showProfileKapal()
    {
        // Fetch data from the VesselMaster model (adjust the query as needed)
        $vessel_master = VMaster::all();

        return view('planning.profile.main', compact('vessel_master'));
    }

    public function showSelectKapalModal(Request $request, $ves_name, $ves_code)
    {
        // Pass the $ves_name and $ves_code variables to the view
        return view('planning.profile.selectkapal', compact('ves_name', 'ves_code'));
    }

    public function store(Request $request, $ves_code)
    {
        // Validate the request data here if needed

        // Create a new instance of your model
        $model = new Bay();

        // Fill the model with the form data
        $model->VES_CODE = $ves_code;
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
        return redirect()->route('grid-box.index', ['ves_code' => $ves_code]);
    }
}
