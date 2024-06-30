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

    public function uploadSingle()
    {
        $data['title'] = "Upload Coparn Single";
        $data['vessel'] = VVoyage::where('clossing_date','>=', Carbon::now())->get();
        $data['isoCode'] = Isocode::get();
        $data['ports'] = Port::get();

        return view('billingSystem.coparn.uploadSingle', $data);
    }

    public function storeDataSingle(Request $request)
    {
        $isoCodeData = Isocode::where('iso_code', $request->iso_code)->first();
        if ($request->ves_id == 'PELINDO') {
            $ves = (object)[ // Cast the array to an object for consistent access
                'ves_name' => 'Pelindo',
                'ves_code' => 'Pelindo',
                'voy_no' => null, // Assign null or any default value you prefer
                'voy_out' => null,
                'clossing_date' => null,
                'eta_date' => null,
                'etd_date' => null,
            ];
        }else {
            $ves = VVoyage::where('ves_id', $request->ves_id)->first();
        }

        $coparn = Item::create([
            'ves_id'=>$request->ves_id,
            'ves_code'=>$ves->ves_code,
            'ves_name'=>$ves->ves_name,
            'voy_no'=>$ves->voy_no,
            'disch_port'=>$request->disch_port,
            'load_port'=>$request->load_port,
            'container_no'=>$request->container_no,
            'iso_code'=>$request->iso_code,
            'ctr_size'=>$isoCodeData->iso_size,
            'ctr_type'=>$isoCodeData->iso_type,
            'ctr_opr'=>$request->ctr_opr,
            'ctr_status'=>$request->ctr_status,
            'gross'=>$request->gross,
            'ctr_intern_status'=>'49',
            'ctr_i_e_t'=>'E',
            'disc_load_trans_shift'=>'LOAD',
            'user_id'=>Auth::user()->name,
            'ctr_active_yn'=>'N',
            'selected_do'=>'N',
            'booking_no'=>$request->booking_no,
        ]);
     
        return redirect('/billing/coparn')->with('success', 'Data berhasil diimpor.');
    }

    public function getVesselData(Request $request)
    {
        $id = $request->id;
        if ($id == 'PELINDO') {
            $ves = [
                'ves_name' => 'Pelindo',
                'ves_code' => 'Pelindo',
                'voy_out' =>null,
                'clossing_date' =>null,
                'eta_date' =>null,
                'etd_date' =>null,
            ];
        }else {
            $ves = VVoyage::where('ves_id', $id)->first();
        }

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
        $file = $request->file('file');
    // Determine the file extension
    $extension = $file->getClientOriginalExtension();
    // Get the real path of the uploaded file
    $path = $file->getRealPath();
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
    
    
        if (in_array($extension, ['xls', 'xlsx'])) {
            Excel::import($import, $path, null, ucfirst($extension));
        } else {
            return redirect()->back()->with('error', 'Invalid file type.');
        }

        return redirect('/billing/coparn')->with('success', 'Data berhasil diimpor.');
    }

    public function editCoparn($id)
    {
        $item = Item::where('container_key', $id)->first();
        $data['title'] = "Booking Number ". $item->booking_no;
        $data['cont'] = $item;
        $data['vessel'] = VVoyage::orderBy('clossing_date','desc')->get();
        $data['isoCode'] = Isocode::get();
        $data['ports'] = Port::get();

        return view('billingSystem.coparn.edit', $data);
    }

    public function updateCoparn(Request $request)
    {
        $item = Item::where('container_key', $request->container_key)->first();
        $all = $request->all;
        $isoCodeData = Isocode::where('iso_code', $request->iso_code)->first();

        if ($request->ves_id == 'PELINDO') {
            $ves = (object)[ // Cast the array to an object for consistent access
                'ves_name' => 'Pelindo',
                'ves_code' => 'Pelindo',
                'voy_no' => null, // Assign null or any default value you prefer
                'voy_out' => null,
                'clossing_date' => null,
                'eta_date' => null,
                'etd_date' => null,
            ];
        }else {
            $ves = VVoyage::where('ves_id', $request->ves_id)->first();
        }
        if ($all) {
            $coparns = Item::where('booking_no', $item->booking_no)->get();
            foreach ($coparns as $coparn) {
                $coparn->update([
                    'ves_id'=>$request->ves_id,
                    'ves_code'=>$ves->ves_code,
                    'ves_name'=>$ves->ves_name,
                    'voy_no'=>$ves->voy_no,
                    'disch_port'=>$request->disch_port,
                    'load_port'=>$request->load_port,
                ]);
            }
        }
        
        $item->update([
            'ves_id'=>$request->ves_id,
            'ves_code'=>$ves->ves_code,
            'ves_name'=>$ves->ves_name,
            'voy_no'=>$ves->voy_no,
            'disch_port'=>$request->disch_port,
            'load_port'=>$request->load_port,
            'container_no'=>$request->container_no,
            'iso_code'=>$request->iso_code,
            'ctr_size'=>$isoCodeData->iso_size,
            'ctr_type'=>$isoCodeData->iso_type,
            'ctr_opr'=>$request->ctr_opr,
            'ctr_status'=>$request->ctr_status,
            'gross'=>$request->gross,
            'ctr_intern_status'=>'49',
            'ctr_i_e_t'=>'E',
            'disc_load_trans_shift'=>'LOAD',
            'user_id'=>Auth::user()->name,
            'ctr_active_yn'=>'N',
            'selected_do'=>'N',
            'booking_no'=>$request->booking_no,
        ]);

        return redirect('/billing/coparn')->with('success', 'Data berhasil diUpdate.');

    }
}
