<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\BatalMuat;
use App\Models\VVoyage;
use Auth;
use Carbon\Carbon;

class BatalMuatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title']= 'Batal Muat';
        $data['item'] = Item::where('ctr_i_e_t', '=', 'E')->whereNot('ctr_intern_status', '=', '56')->get();
        $data['canceled'] = BatalMuat::where('ctr_action', null)->whereIn('container_key', function($query) {
            $query->select('container_key')->from('item');
        })->get();
        $data['vessel'] = VVoyage::where('deparature_date','>=', Carbon::now())->get();

        return view('batal-muat.main', $data);
    }

    public function store(Request $request)
    {
        $cont = $request->container_key;
        $user = Auth::user()->id;
        $now = Carbon::now();
        // dd($user, $now);
        foreach ($cont as $ctr) {
            $item = Item::where('container_key', $ctr)->first();

            $batalMuat = BatalMuat::create([
                'container_key' =>$item->container_key,
                'container_no' =>$item->container_no,
                'old_ves_id' =>$item->ves_id,
                'old_ves_name' =>$item->ves_name,
                'old_voy_no' =>$item->voy_no,
                'alasan_batal_muat' =>$request->alasan_batal_muat,
                'tanggal_batal_muat' =>$now,
                'user_id' =>$user,
            ]);

            $item->update([
                'ctr_i_e_t' => 'T',
                'ves_id'=>null,
                'ves_code'=>null,
                'ves_name'=>null,
                'voy_no'=>null,
            ]);

        }
       
        return redirect('/batal-muat')->with('success', 'Container Berhasil Ditambahkan pada Daftar Batal Muat');
    }

    public function action(Request $request)
    {
        $id = $request->id;
        $batalMuat = BatalMuat::where('id', $id)->first();
        if ($batalMuat) {
            $cont = Item::where('container_key', $batalMuat->container_key)->first();

            return response()->json([
                'success' => true,
                'data' => $batalMuat,
                'cont' => $cont
            ]);
        }
    }

    public function update(Request $request)
    {
        $batalMuat = BatalMuat::where('id', $request->id)->first();
        $cont = Item::where('container_key', $request->key)->first();
        $user = Auth::user()->id;
        $now = Carbon::now();
        $act = $request->ctr_action;
        if ($act != "OUT") {
           $ves = VVoyage::where('ves_id', $request->ves_id)->first();

           $batalMuat->update([
            'new_ves_id'=>$ves->ves_id,
            'new_ves_name'=>$ves->ves_name,
            'new_voy_no'=>$ves->voy_out,
            'ctr_action'=>$request->ctr_action,
            'tanggal_action'=>$now,
            'user_id'=>$user,
           ]);

           $cont->update([
            'ves_id'=> $ves->ves_id,
            'ves_code'=> $ves->ves_code,
            'ves_name'=> $ves->ves_name,
            'voy_no'=> $ves->voy_out,
            'ctr_i_e_t' => 'E',
           ]);
        }else {
            $batalMuat->update([
                'ctr_action'=>$request->ctr_action,
                'tanggal_action'=>$now,
                'user_id'=>$user,
               ]);

            $cont->update([
                'ctr_intern_status' => '03',
            ]);
        }
        return redirect('/batal-muat')->with('success', 'Container Berhasil Diperbarui');
    }

    public function indexReport()
    {
        $data['title'] = 'Report Batal Muat';
        $data['batalMuat'] = BatalMuat::whereIn('container_key', function($query) {
            $query->select('container_key')->from('item');
        })->orderBy('last_update', 'desc')->get();

        return view('reports.batal-muat.index', $data);
    }


    public function get_data_batalMuat(Request $request)
    {
        $request->validate([
            'created_at' => 'required',
        ]);
        $created_at = $request->created_at;
        $date_range = explode(' to ', $created_at);

        if (count($date_range) >= 2) {
            $start_date = date('Y-m-d', strtotime($date_range[0]));
            $end_date = date('Y-m-d', strtotime($date_range[1]));
        } else {
            $start_date = date('Y-m-d', strtotime($date_range[0]));
            $end_date = $start_date;
        }

        $data = BatalMuat::whereDate('last_update', '>=', $start_date)->whereDate('last_update', '<=', $end_date)->get();
        if ($data->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data pada rentang tanggal tersebut!',
            ]);
        }

        
    }

    public function ReportPrint(Request $request)
    {
        $data['title'] = 'Laporan Produktivitas Operator';
        $created_at = $request->created_at;
        $reqid = $request->id;

        $created_at_range = $request->created_at;
    
         // Memecah nilai 'created_at_range' menjadi tanggal awal dan akhir
         $date_range = explode(' to ', $created_at_range);

         // Mengkonversi tanggal awal dan akhir menjadi format yang sesuai (misalnya, Y-m-d)
         if (count($date_range) >= 2) {
            $start_date = date('Y-m-d', strtotime($date_range[0]));
            $end_date = date('Y-m-d', strtotime($date_range[1]));
        } else {
            $start_date = date('Y-m-d', strtotime($date_range[0]));
            $end_date = $start_date;
        }
         
        $data['batalMuat'] = BatalMuat::whereDate('last_update', '>=', $start_date)->whereDate('last_update', '<=', $end_date)->orderBy('old_ves_id', 'desc')->get();
        $data['ves'] = $data['batalMuat'] ->groupBy('old_ves_id')
        ->map(function ($group) {
            return [
                'old_ves_id' => $group->first()->old_ves_id,
                'total_containers' => $group->count(),
            ];
        });
        

        return view('reports.batal-muat.laporan-batalMuat', $data);
    }


    public function addCont()
    {
        $data['title']= 'Batal Muat || Add Container Form';
        $data['item'] = Item::where('ctr_i_e_t', '=', 'E')->whereNot('ctr_intern_status', '=', '56')->get();
        
        return view('batal-muat.add-cont', $data);
    }
}
