<?php

namespace App\Http\Controllers;

use App\Models\Bay;
use Illuminate\Http\Request;

class GridController extends Controller
{
    public function index(Request $request)
    {
        $ves_code = $request->ves_code;
        // Fetch data from the database based on the VES_CODE
        $gridBoxData = Bay::where('VES_CODE', $ves_code)->get(); // Replace 'VES_CODE' with the actual column name
        // dd($gridBoxData);
        return view('planning.profile.grid', compact('gridBoxData'));
    }
}
