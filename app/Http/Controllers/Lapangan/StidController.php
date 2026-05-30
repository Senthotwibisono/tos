<?php

namespace App\Http\Controllers\Lapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\MasterStid;

class StidController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title'] = 'Master STID';

        return view('lapangan.master.stid', $data);
    }

    public function data(Request $request)
    {
        $datas = MasterStid::with(['uid', 'lastupdate'])->get();
        
        return DataTables::of($datas)
        ->addColumn('delete', function($datas){
            return '<button type="button" class="btn btn-danger" data-id="'.$datas->id.'" onClick="deleteStid(this)"><i class="fas fa-trash"></i></button>';
        })
        ->addColumn('edit', function($datas){
            return '<button type="button" class="btn btn-warning" data-id="'.$datas->id.'" onClick="editStid(this)"><i class="fas fa-pencil"></i></button>';
        })
        ->rawColumns(['delete', 'edit'])
        ->make(true);
    }

    public function post(Request $request)
    {
        try {
            DB::transaction(function() use($request){
                MasterStid::updateOrCreate(
                    ['id' => $request->id],
                    [
                        'company' => $request->company,
                        'card_number' => $request->card_number,
                        'stid' => $request->stid,
                        'truck_no' => $request->truck_no,
                        'vehicle_type' => $request->vehicle_type,
                        'merk' => $request->merk,
                        'status' => $request->status,
                        'uid' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                        'uid_updated' => Auth::user()->id,
                    ]
                );
            });
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di muat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        $data = MasterStid::find($request->id);
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data 
            ]);
        }else{
            return response()->jsom([
                'success'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::transaction(function() use($request){
                MasterStid::where('id', $request->id)->delete();
            });
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di muat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
