<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bay; // Assuming you have a Bay model

class BayController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'VES_CODE' => 'required', // Add validation rules for other attributes
            'BAY1' => 'required',
            'SIZE1' => 'required|integer|min:0',
            'BAY2' => 'required',
            'SIZE2' => 'required|integer|min:0',
            'JOINSLOT' => 'required',
            'WEIGHT_BALANCE_ON' => 'required|integer|min:0',
            'WEIGHT_BALANCE_UNDER' => 'required|integer|min:0',
            'START_ROW' => 'required',
            'START_ROW_UNDER' => 'required',
            'TIER' => 'required|integer|min:0',
            'TIER_UNDER' => 'required|integer|min:0',
            'MAX_ROW' => 'required|integer|min:0',
            'MAX_ROW_UNDER' => 'required|integer|min:0',
            'START_TIER' => 'required|integer|min:0',
            'START_TIER_UNDER' => 'required|integer|min:0',
        ]);

        // Create a new Bay record
        Bay::create($validatedData);

        // Redirect to a success page or do something else
        return redirect('/planning/profile/profil-kapal');

    }
}
