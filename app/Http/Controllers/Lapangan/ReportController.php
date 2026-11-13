<?php

namespace App\Http\Controllers\Lapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\Item;
use App\Models\MasterContainer;

class ReportController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function indexOpname()
    {
        $data['title'] = 'Report Opname';

        return view('lapangan.report.opname', $data);
    }

    public function dataOpname(Request $request)
    {
        $data = MasterContainer::whereNotNull('container_no')
            ->whereNotIn('container_no', ['', '0'])
            ->orderByRaw("
                CASE
                    WHEN status = 'active' THEN 1
                    ELSE 2
                END ASC
            "); 

        // Kapasitas total yard dalam TEUS
        $kapasitasTeus = 2300;  

        // Hitung total TEUS container dengan status active
        $totalTeusActive = MasterContainer::where('status', 'active')
            ->whereNotNull('container_no')
            ->whereNotIn('container_no', ['', '0'])
            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN ctr_size IN ('20', '21', '22') THEN 1
                        WHEN ctr_size IN ('40', '41', '42', '45') THEN 2
                        ELSE 0
                    END
                ), 0) AS total_teus
            ")
            ->value('total_teus');  

        // Persentase YOR
        $yor = round(($totalTeusActive / $kapasitasTeus) * 100, 2); 

        $summary = [
            'total' => (clone $data)->count(),
            'active' => (clone $data)->where('status', 'active')->count(),
            'inactive' => (clone $data)->where('status', 'inactive')->count(),
            'YOR' => $yor,
            'IKS' => (clone $data)->where('location', 'IKS')->count(),
            'MKB' => (clone $data)->where('location', 'MKB')->count(),
        ];  

        return DataTables::of($data)
            ->filterColumn('status', function ($query, $keyword) {
                switch ($keyword) {
                    case 'active':
                        $query->where('status', 'active');
                        break;  

                    case 'inactive':
                        $query->where('status', 'inactive');
                        break;
                }
            })
            ->with('summary', $summary)
            ->addColumn('action', function ($data) {
                return '<button class="btn btn-warning" onclick="editContainer(this)" data-id="'.$data->id.'">
                            <i class="fas fa-pencil"></i>
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function updateMasterContianer(Request $request)
    {
        // var_dump($request->all());
        // die;
        try {
            db::transaction(function() use($request){
                MasterContainer::updateOrCreate(
                    ['id'=> $request->id],
                    [
                        // 'container_no' => $request->container_no,
                        'ctr_size' => $request->ctr_size,
                        'ctr_type' => $request->ctr_type,
                        'status' => $request->status,
                        'location' => $request->location,
                    ]
                );
            });

            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
