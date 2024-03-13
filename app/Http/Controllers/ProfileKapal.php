<?php

namespace App\Http\Controllers;

use Iluminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\VMaster;
use App\Models\Bay;
use App\Models\ProfileTier;

class ProfileKapal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vessel_master = VMaster::orderBy('ves_code', 'desc')->get();;
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

        $bayPlan = $model;
            //under
            $rowUnder = $bayPlan->MAX_ROW_UNDER - $bayPlan->START_ROW_UNDER;
            for ($i = $bayPlan->START_ROW_UNDER; $i <= $rowUnder; $i++) {
                $tierUnder = $bayPlan->TIER_UNDER;
                for ($r = $bayPlan->START_TIER_UNDER; $r <= $tierUnder + 1; $r++) { // Increment $r by 2
                    if ($r % 2 == 0) {
                        $ship = ProfileTier::create([
                            'on_under'=>'U',
                            'ves_code' => $request->ves_code,
                            'voy_no' => $request->voy_out,
                            'bay_slot' => $bayPlan->BAY1,
                            'bay_row' => str_pad($i, 2, '0', STR_PAD_LEFT), // Pad $i with leading zeros
                            'bay_tier' => str_pad($r, 2, '0', STR_PAD_LEFT), // Pad $r with leading zeros
                            'active'=>'N',
                        ]);
                    }
                }
            }

            
            $rowOnDeck = $bayPlan->MAX_ROW - $bayPlan->START_ROW;
            for ($i = $bayPlan->START_ROW; $i <= $rowOnDeck; $i++) {
                $tierOnDeck = $bayPlan->START_TIER + $bayPlan->TIER;
                for ($r = $bayPlan->START_TIER; $r <= $tierOnDeck - 1; $r++) { // Increment $r by 2
                    if ($r % 2 == 0) {
                        $shipOnDeck = ProfileTier::create([
                            'on_under'=>'O',
                            'ves_code' => $request->ves_code,
                            'voy_no' => $request->voy_out,
                            'bay_slot' => $bayPlan->BAY1,
                            'bay_row' => str_pad($i, 2, '0', STR_PAD_LEFT), // Pad $i with leading zeros
                            'bay_tier' => $r, // Pad $r with leading zeros
                            'active'=>'N',
                        ]);
                    }
                }
            }
        

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
        $gridBoxData = Bay::where('VES_CODE', $ves_code)->get(); 
        $rowBoxData = Bay::where('VES_CODE','BAY1', $ves_code)->get(); // Data Row
        return view('planning.profile.grid', compact('gridBoxData', 'ves_code'));
    }

    public function bayProfile($bay, $ves)
    {
        $data['bay'] = Bay::where('VES_CODE', $bay)->where('BAY1', $ves)->first();
        $data['kapal'] = VMaster::where('ves_code', $bay)->first();
        $data['onDeck'] = ProfileTier::where('ves_code', $bay)->where('on_under', '=', 'O')->where('bay_slot', $ves)->get();
        $data['underDeck'] = ProfileTier::where('ves_code', $bay)->where('on_under', '=', 'U')->where('bay_slot', $ves)->get();

        return view('planning.profile.bay-edit', $data);
    }

    public function updateTier(Request $request)
    {
        $checkedBoxes = $request->input('checkbox_input');
        $bay = $request->bay_slot;
        $ves = $request->ves_code;
    
        // First, set all rows for this bay and ves to "N"
        ProfileTier::where('ves_code', $ves)
                   ->where('bay_slot', $bay)
                   ->update(['active' => 'N']);
    
        // Now, loop through the checked checkboxes and update them to "Y"
        foreach ($checkedBoxes as $checkBox) {
            $parts = explode('-', $checkBox);
            $row = $parts[0]; // Extract row number
            $tier = $parts[1]; // Extract tier number
    
            ProfileTier::where('ves_code', $ves)
                       ->where('bay_slot', $bay)
                       ->where('bay_row', $row)
                       ->where('bay_tier', $tier)
                       ->update(['active' => 'Y']);
        }
    
        // Redirect back to the profile-kapal page
        $url = '/profile-kapal/get/bay-' . $ves . '-' . $bay;
        return redirect($url)->with('success', 'Profile Tier has been updated!');
    }

    public function cetakProfile($ves)
    {
        $kapal = VMaster::where('ves_code', $ves)->first();
        $data['kapal'] = $kapal;
        $data['title'] = "Profile Kapal". $kapal->ves_name;
        $data['baySlots'] = ProfileTier::select('bay_slot')->where('ves_code', $ves)->groupBy('bay_slot')->orderBy('bay_slot', 'asc')->get();
        $data['onDeck'] = ProfileTier::where('ves_code', $ves)->where('on_under', '=', 'O')->orderBy('bay_slot', 'asc')->get();
        $data['underDeck'] = ProfileTier::where('ves_code', $ves)->where('on_under', '=', 'U')->orderBy('bay_slot', 'asc')->get();

        return view('planning.profile.cetak-profile', $data);
    }
}



