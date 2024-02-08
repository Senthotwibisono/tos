<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\VVoyage;
use App\Models\Isocode;
use App\Models\Item;
use App\Models\Imocode;
use App\Models\Port;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Coparn;



use Auth;
use Carbon\Carbon;

class CoparnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $data['title'] = "Coparn";
        $data['container'] = Item::where('ctr_intern_status', '49')->whereNotNull('booking_no')->get();
        return view('billingSystem.coparn.main', $data);
    }

    public function uploadView()
    {
        $data['title'] = "Upload Coparn";
        $data['vessel'] = VVoyage::where('clossing_date','>=', Carbon::now())->get();

        return view('billingSystem.coparn.uploadFile', $data);
    }

    public function getVesselData(Request $request)
    {
        $id = $request->id;
        $ves = VVoyage::where('ves_id', $id)->first();

        if ($ves) {
            return response()->json([
                'success' => true,
                'message' => 'Nomor Do Expired !!',
                'data'=>$ves,
            ]);
        }
    }

    public function storeData(Request $request)
    {
        $path = $request->file('file');
        $vesselVoyage = VVoyage::where('ves_id', $request->ves_id)->first();
        $ves_name = $vesselVoyage->ves_name;
        $voy_no = $vesselVoyage->voy_out;
        $ves_code = $vesselVoyage->ves_code;
        $ves_id = $request->ves_id;
        // dd($ves_code, $voy_no, $ves_code, $ves_name, $ves_id);
        $import = new Coparn(
            $ves_name,
            $voy_no,
            $ves_code,
            $ves_id, 
            
        );
    
    
            Excel::import($import, $path->getRealPath());

        return redirect('/billing/coparn')->with('success', 'Data berhasil diimpor.');
    }
}
