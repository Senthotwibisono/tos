<?php

namespace App\Http\Controllers\customer\export;

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\VVoyage;
use App\Models\Item;
use App\Models\Isocode;
use App\Models\Port;

use App\Models\Customer;
use App\Models\MasterUserInvoice as MUI;

use App\Exports\Customer\CoparnUpload;

class CoparnCustomerController extends CustomerMainController
{
    public function index()
    {
        $data['title'] = 'List Coparn';
        $data['vessels'] = VVoyage::where('clossing_date','>=', Carbon::now())->get();
        $data['isoCode'] = Isocode::get();
        $data['ports'] = Port::get();
        $data['usersMaster'] = MUI::where('user_id', Auth::user()->id)->get();
        $data['customers'] = Customer::whereIn('id', $data['usersMaster']->pluck('customer_id'))->get();

        return view('customer.export.coparn.index', $data);
    }

    public function dataCoparn(Request $request)
    {
        $items = Item::where('ctr_intern_status', '49')->whereNotNull('booking_no')->where('created_by', Auth::user()->id)->get();

        return DataTables::of($items)
        ->addColumn('edit', function($items){
            return '<button type="button" id="editButton" data-id="'.$items->container_key.'" class="btn btn-warning" onclick="editItem(event)"><i class="fas fa-pencil"></i></button>';
        })
        ->addColumn('delete', function($items){
            return '<button type="button" class="btn btn-danger btn-delete-coparn" data-id="'.$items->container_key.'" data-no="'.$items->container_no.'"><i class="fas fa-trash"></i></button>';
        })
        ->addColumn('uid', function($items){
            return $items->craeted->name ?? '-';
        })
        ->rawColumns(['edit', 'delete'])
        ->make(true);
    }

    public function storeSingle(Request $request)
    {
        // dd($request->all());
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

        try {
            $coparn = Item::updateOrCreate(
                ['container_key' => $request->container_key], 
                [
                'ves_id'=>$request->ves_id,
                'ves_code'=>$ves->ves_code,
                'ves_name'=>$ves->ves_name,
                'voy_no'=>$ves->voy_out,
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
                'created_by' => Auth::user()->id,
                'customer_code' => $request->customer_id,
            ]);
    
            return redirect()->back()->with('success', 'Data uploaded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function editCoparn($id)
    {
        $item = Item::find($id);
        if ($item) {
            return response()->json([
                'success' => true,
                'data' => $item,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ]);
        }
    }

    public function deleteCoparn(Request $request)
    {
        try {
            $item = Item::find($request->container_key)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di hapus',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function storeFile(Request $request)
    {
        $coparns = $request->dataResult;
        foreach ($coparns as $data) {
            $oldItem = Item::where('container_no', $data[1])->where('ctr_intern_status', "49")->where('booking_no', $data[0])->first();
            if (!$oldItem) {
                $isoCodeData = Isocode::where('iso_code', $data[2])->first();
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
    
                $status = ($data[9] == 'EMPTY') ? 'MTY' : 'FCL';
                $item = Item::create([
                    'ves_id'=>$request->ves_id,
                    'ves_code'=>$ves->ves_code,
                    'ves_name'=>$ves->ves_name,
                    'voy_no'=>$ves->voy_no,
                    'disch_port'=>$data[4],
                    'load_port'=>$data[5],
                    'container_no'=>$data[1],
                    'iso_code'=>$data[2],
                    'ctr_size'=>$isoCodeData->iso_size,
                    'ctr_type'=>$isoCodeData->iso_type,
                    'ctr_opr'=>$data[6],
                    'ctr_status'=>$status,
                    'gross'=>$data[10],
                    'ctr_intern_status'=>'49',
                    'ctr_i_e_t'=>'E',
                    'disc_load_trans_shift'=>'LOAD',
                    'user_id'=>Auth::user()->name,
                    'ctr_active_yn'=>'N',
                    'selected_do'=>'N',
                    'booking_no'=>$data[0],
                    'created_by' => Auth::user()->id,
                    'customer_code' => $request->customer,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil disimpan',
        ]);
    }
}
