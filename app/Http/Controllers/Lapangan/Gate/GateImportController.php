<?php

namespace App\Http\Controllers\Lapangan\Gate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\Item;
use App\Models\JobImport;
use App\Models\JobExtend;

use App\Http\Controllers\HistoryController;

class GateImportController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->history = app(HistoryController::class);
    }

    public function indexIn()
    {
        $data['title'] = 'Gate Bongkaran';

        return view('lapangan.gate.import.gate-in', $data);
    }

    public function dataIn(Request $request)
    {
        $items = Item::whereIn('ctr_intern_status', ['10', '50'])->get();
        
        return DataTables::of($items)
        ->addColumn('cancel', function($items){
            return '<button type="button" class="btn btn-danger" data-tipe="in" data-id="'.$items->container_key.'" onClick="cancelGateIn(this)">Cancel</button>';
        })
        ->addColumn('iet', function($items){
            return (in_array($items->ctr_intern_status, ['10', '04'])) ? 'Bongkar' : 'Muat';
        })
        ->rawColumns(['cancel'])
        ->make(true);
    }

    public function postIn(Request $request)
    {
        // var_dump($request->all());
        // die();

        $item = Item::where('container_key', $request->container_key)->first();
        try {
            DB::transaction(function() use($item, $request) {
                if ($item->ctr_intern_status == '03') {
                    $internStatus = '10';
                    $operationName = 'GATI';
                }elseif ($item->ctr_intern_status == '04') {
                    $internStatus = '04';
                    $operationName = 'GATI-STR';
                }elseif ($item->ctr_intern_status == '49') {
                    $internStatus = '50';
                    $operationName = 'GATI-REC';
                }

                $item->update([
                    'ctr_intern_status' => $internStatus,
                    'truck_no' => $request->truck_no,
                    'truck_in_date' => $request->truck_in_date,
                ]);

                $dataHistory = [
                  'container_key' => $item->container_key,
                  'container_no' => $item->container_no,
                  'operation_name' => $operationName,
                  'ves_id' => $item->ves_id,
                  'ves_code' => $item->ves_code,
                  'voy_no' => $item->voy_no,
                  'ctr_i_e_t' => $item->ctr_i_e_t,
                  'ctr_active_yn' => $item->ctr_active_yn,
                  'ctr_size' => $item->ctr_size,
                  'ctr_type' => $item->ctr_type,
                  'ctr_status' => $item->ctr_status,
                  'ctr_intern_status' => $item->ctr_intern_status,
                  'yard_blok' => $item->yard_blok,
                  'yard_slot' => $item->yard_slot,
                  'yard_row' => $item->yard_row,
                  'yard_tier' => $item->yard_tier,
                  'truck_no' => $item->truck_no,
                  'truck_in_date' => $item->truck_in_date ? Carbon::parse($item->truck_in_date)->format('Y-m-d') : null,
                  'truck_out_date' => $item->truck_out_date ? Carbon::parse($item->truck_out_date)->format('Y-m-d') : null,
                  'oper_name' => Auth::user()->name,
                  'iso_code' => $item->iso_code,
                ];
        
                $historyContainer = $this->history->postHistoryContainer($dataHistory);
                
            });
            return response()->json([
                'success' => true,
                'message' => 'Aksi Berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function indexOut()
    {
        $data['title'] = 'Gate Bongkaran';

        return view('lapangan.gate.import.gate-out', $data);
    }

    public function dataOut(Request $request)
    {
        $items = Item::whereIn('ctr_intern_status', ['09', '50'])
        ->selectRaw('MAX(container_key) as container_key, container_no, MAX(truck_no) as truck_no, MAX(truck_out_date) as truck_out_date, MAX(ctr_intern_status) as ctr_intern_status, MAX(iso_code) as iso_code, MAX(ctr_size) as ctr_size, MAX(ctr_type) as ctr_type, MAX(job_no) as job_no, MAX(voy_no) as voy_no')
        ->groupBy('container_no')
        ->get();
        return DataTables::of($items)
        ->addColumn('cancel', function($items){
            return '<button type="button" class="btn btn-danger" data-tipe="out" data-id="'.$items->container_key.'" onClick="cancelGateIn(this)">Cancel</button>';
        })
        ->addColumn('iet', function($items){
            return (in_array($items->ctr_intern_status, ['09', '04'])) ? 'Bongkar' : 'Muat';
        })
        ->rawColumns(['cancel'])
        ->make(true);
    }

    public function postOut(Request $request)
    {
        // var_dump($request->all());
        // die();

        $item = Item::where('container_key', $request->container_key)->first();
        try {
            DB::transaction(function() use($item, $request) {
                if (in_array ($item->ctr_intern_status, ['10', '01', '02', '03'])) {
                    $internStatus = '09';
                    $operationName = 'GATO';
                }elseif ($item->ctr_intern_status == '04') {
                    $internStatus = '04';
                    $operationName = 'GATO-STR';
                }elseif (in_array($item->ctr_intern_status, ['49', '50', '51', '53', '54', '55', '56'])) {
                    $internStatus = $item->ctr_intern_status;
                    $operationName = 'GATO-REC';
                }

                $item->update([
                    'ctr_intern_status' => $internStatus,
                    'truck_no' => $request->truck_no,
                    'truck_out_date' => $request->truck_in_date,
                ]);

                $dataHistory = [
                  'container_key' => $item->container_key,
                  'container_no' => $item->container_no,
                  'operation_name' => $operationName,
                  'ves_id' => $item->ves_id,
                  'ves_code' => $item->ves_code,
                  'voy_no' => $item->voy_no,
                  'ctr_i_e_t' => $item->ctr_i_e_t,
                  'ctr_active_yn' => $item->ctr_active_yn,
                  'ctr_size' => $item->ctr_size,
                  'ctr_type' => $item->ctr_type,
                  'ctr_status' => $item->ctr_status,
                  'ctr_intern_status' => $item->ctr_intern_status,
                  'yard_blok' => $item->yard_blok,
                  'yard_slot' => $item->yard_slot,
                  'yard_row' => $item->yard_row,
                  'yard_tier' => $item->yard_tier,
                  'truck_no' => $item->truck_no,
                  'truck_in_date' => $item->truck_in_date ? Carbon::parse($item->truck_in_date)->format('Y-m-d') : null,
                  'truck_out_date' => $item->truck_out_date ? Carbon::parse($item->truck_out_date)->format('Y-m-d') : null,
                  'oper_name' => Auth::user()->name,
                  'iso_code' => $item->iso_code,
                ];
        
                $historyContainer = $this->history->postHistoryContainer($dataHistory);
                
            });
            return response()->json([
                'success' => true,
                'message' => 'Aksi Berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function cancelGate(Request $request)
    {
        $item = Item::where('container_key', $request->container_key)->first();
        try {
            DB::transaction(function() use($item, $request) {
                if ($request->tipe == 'in') {
                    if ($item->ctr_intern_status == '10') {
                        $internStatus = '03';
                    }elseif ($item->ctr_intern_status == '04') {
                        $internStatus = '04';
                    }elseif ($item->ctr_intern_status == '50') {
                        $internStatus = '49';
                    }
                    $data = [
                        'truck_in_date' => null,
                        'truck_no' => null,
                        'ctr_intern_status' => $internStatus
                    ];

                    $item->update(
                        $data
                    );
                }

                if ($request->tipe == 'out') {
                    if ($item->ctr_intern_status == '09') {
                        $internStatus = '10';
                    }elseif ($item->ctr_intern_status == '04') {
                        $internStatus = '04';
                    }else{
                        $internStatus = $item->ctr_intern_status;
                    }
                    $data = [
                        'truck_out_date' => null,
                        'truck_no' => null,
                        'ctr_intern_status' => $internStatus
                    ];

                    $item->update(
                        $data
                    );
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Aksi Berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }


    public function indexBalikMt(Request $request)
    {
        $data['title'] = 'Gate Bongkaran';

        return view('lapangan.gate.import.balik-mt', $data);
    }

    public function dataBalikMt(Request $request)
    {
        $items = Item::where('ctr_intern_status', '04')->whereNotNull('depo_mty')->get();

        return DataTables::of($items)
        ->addColumn('cancel', function($items){
            return '<button type="button" class="btn btn-danger" data-tipe="in" data-id="'.$items->container_key.'" onClick="cancelGateIn(this)">Cancel</button>';
        })
        ->rawColumns(['cancel'])
        ->make(true);
    }
    
    public function postBalikMt(Request $request)
    {
        // var_dump($request->all());
        // die;

        $item = Item::where('container_key', $request->container_key)->first();
        try {
            DB::transaction(function() use($item, $request) {
                $item->update([
                    'depo_mty' => $request->depo_mty,
                    'truck_no_mty' => $request->truck_no_mty,
                    'mty_date' => $request->mty_date,
                    'ctr_intern_status' => '04'
                ]); 

                $dataHistory = [
                  'container_key' => $item->container_key,
                  'container_no' => $item->container_no,
                  'operation_name' => 'BCK-MTY',
                  'ves_id' => $item->ves_id,
                  'ves_code' => $item->ves_code,
                  'voy_no' => $item->voy_no,
                  'ctr_i_e_t' => $item->ctr_i_e_t,
                  'ctr_active_yn' => $item->ctr_active_yn,
                  'ctr_size' => $item->ctr_size,
                  'ctr_type' => $item->ctr_type,
                  'ctr_status' => $item->ctr_status,
                  'ctr_intern_status' => '04',
                  'yard_blok' => $item->yard_blok,
                  'yard_slot' => $item->yard_slot,
                  'yard_row' => $item->yard_row,
                  'yard_tier' => $item->yard_tier,
                  'truck_no' => $item->truck_no,
                  'truck_in_date' => $item->truck_in_date ? Carbon::parse($item->truck_in_date)->format('Y-m-d') : null,
                  'truck_out_date' => $item->truck_out_date ? Carbon::parse($item->truck_out_date)->format('Y-m-d') : null,
                  'mty_date' => $item->mty_date ? Carbon::parse($item->mty_date)->format('Y-m-d') : null,
                  'truck_no_mty' => $item->truck_no_mty,
                  'depo_mty' => $item->depo_mty,
                  'oper_name' => Auth::user()->name,
                  'iso_code' => $item->iso_code,
                ];
                $historyContainer = $this->history->postHistoryContainer($dataHistory);
            });
            return response()->json([
                'success' => true,
                'message' => 'Aksi Berhasil'
            ]); 
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]); 
        }
    }

    public function cancelBalikMt(Request $request)
    {
        $item = Item::where('container_key', $request->container_key)->first();
        try {
            DB::transaction(function() use($item){
                $item->update([
                    'ctr_intern_status' => '09',
                    'depo_mty' => null,
                    'truck_no_mty' => null,
                    'mty_date' => null,
                ]);
            });
            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function indexPelindoImport(Request $request)
    {
        $data['title'] = 'Gate Pelindo Import';

        return view('lapangan.gate.import.pelindo-import', $data);
    }

    public function dataPelindoImport(Request $request)
    {
        $items = Item::where('ctr_intern_status', '09')->where('relokasi_flag', 'Y')->get();

        return DataTables::of($items)
        ->addColumn('cancel', function($items){
            return '<button type="button" class="btn btn-danger" data-tipe="in" data-id="'.$items->container_key.'" onClick="cancelGateIn(this)">Cancel</button>';
        })
        ->rawColumns(['cancel'])
        ->make(true);
    }

    public function cancelPelindoImport(Request $request)
    {
        $item = Item::where('container_key', $request->container_key)->first();
        try {
            DB::transaction(function() use($item){
                $item->update([
                    'ctr_intern_status' => '03',
                    'truck_no' => null,
                    'truck_out_date' => null,
                ]);
            });
            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function indexAmbilMt(Request $request)
    {
         $data['title'] = 'Gate Pelindo Import';

        return view('lapangan.gate.import.ambil-mty', $data);
    }

    public function dataAmbilMt(Request $request)
    {
        $items = Item::where('ctr_intern_status', '09')->whereNotNull('out_mty_date')->get();

        return DataTables::of($items)
        ->addColumn('cancel', function($items){
            return '<button type="button" class="btn btn-danger" data-tipe="in" data-id="'.$items->container_key.'" onClick="cancelGateIn(this)">Cancel</button>';
        })
        ->rawColumns(['cancel'])
        ->make(true);
    }

    public function postAmbilMt(Request $request)
    {
        // var_dump($request->all());
        // die;

        $item = Item::where('container_key', $request->container_key)->first();
        try {
            DB::transaction(function() use($item, $request) {
                $item->update([
                    'out_mty_truck' => $request->out_mty_truck,
                    'out_mty_date' => $request->out_mty_date,
                    'ctr_intern_status' => '09'
                ]); 

                $dataHistory = [
                  'container_key' => $item->container_key,
                  'container_no' => $item->container_no,
                  'operation_name' => 'BCK-MTY',
                  'ves_id' => $item->ves_id,
                  'ves_code' => $item->ves_code,
                  'voy_no' => $item->voy_no,
                  'ctr_i_e_t' => $item->ctr_i_e_t,
                  'ctr_active_yn' => $item->ctr_active_yn,
                  'ctr_size' => $item->ctr_size,
                  'ctr_type' => $item->ctr_type,
                  'ctr_status' => $item->ctr_status,
                  'ctr_intern_status' => '04',
                  'yard_blok' => $item->yard_blok,
                  'yard_slot' => $item->yard_slot,
                  'yard_row' => $item->yard_row,
                  'yard_tier' => $item->yard_tier,
                  'truck_no' => $item->truck_no,
                  'truck_in_date' => $item->truck_in_date ? Carbon::parse($item->truck_in_date)->format('Y-m-d') : null,
                  'truck_out_date' => $item->truck_out_date ? Carbon::parse($item->truck_out_date)->format('Y-m-d') : null,
                  'mty_date' => $item->mty_date ? Carbon::parse($item->mty_date)->format('Y-m-d') : null,
                  'truck_no_mty' => $item->truck_no_mty,
                  'depo_mty' => $item->depo_mty,
                  'oper_name' => Auth::user()->name,
                  'iso_code' => $item->iso_code,
                  'out_mty_truck' => $item->out_mty_truck,
                  'out_mty_date' => $item->out_mty_date,
                ];
                $historyContainer = $this->history->postHistoryContainer($dataHistory);
            });
            return response()->json([
                'success' => true,
                'message' => 'Aksi Berhasil'
            ]); 
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]); 
        }
    }

    public function cancelAmbilMt(Request $request)
    {
        $item = Item::where('container_key', $request->container_key)->first();
        try {
            DB::transaction(function() use($item){
                $item->update([
                    'ctr_intern_status' => '04',
                    'out_mty__date' => null,
                    'out_mty_truck' => null,
                ]);
            });
            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    
}
