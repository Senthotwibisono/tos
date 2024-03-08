<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use App\Models\Operator;
use App\Models\ActOper;


class OperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title'] = "Master Operator";
        $data['oper'] = Operator::orderBy('role', 'asc')->orderBy('name', 'asc')->get();
        return view('master.operator', $data);
    }

    public function store(Request $request)
    {
        $role = $request->role;
        if ($role != null) {
            $opr = Operator::create([
                'role'=>$request->role,
                'name'=>$request->name,
            ]);

            return redirect()->route('operator')->with('success', 'Data Berhasil Disimpan');
        }else {
            return redirect()->route('operator')->with('error', 'role belum di isi !!');
        }
    }
    
    public function delete($id)
    {
        $oper = Operator::where('id', $id)->first();
        if ($oper) {
            $oper->delete();
            return redirect()->route('operator')->with('success', 'Data Berhasil Dihapus');
        }else {
            return redirect()->route('operator')->with('error', 'role belum di isi !!');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $opr =  Operator::where('id', $id)->first();
        if ($opr) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $opr,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'updated successfully!',
            ]);
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $opr = Operator::where('id', $id)->first();
        if ($opr) {
            $opr->update([
                'role'=>$request->role,
                'name'=>$request->name,
            ]);
            return redirect()->route('operator')->with('success', 'Data Berhasil Diperbarui');
        }else {
            return redirect()->route('operator')->with('error', 'Data tidak ditemukan!!');
        }
    }

    public function report()
    {
        $data['title'] = 'Laporan Operator';
        $data['actOP'] = ActOper::orderBy('created_at')->get();
        return view('reports.operator.index', $data);
    }

    public function get_op(Request $request)
    {
        $kategori = $request->name;
        if ($kategori === 'all') {
            $alat = Operator::get();
        
        $option = []; // Inisialisasi variabel $option sebagai array kosong
        
        foreach ($alat as $kode) {
            $option[] = [
                'value' => $kode->id,
                'text' => $kode->name,
            ];
        }
        return response()->json($option);
        }else {
            $alat = Operator::whereIn('role', $kategori)->get();
        
        $option = []; // Inisialisasi variabel $option sebagai array kosong
        
        foreach ($alat as $kode) {
            $option[] = [
                'value' => $kode->id,
                'text' => $kode->name,
            ];
        }
        return response()->json($option);
        }
        
    }

    public function get_data_operator(Request $request)
    {
        $request->validate([
            'created_at' => 'required',
            'id' => 'required',
        ]);

        // Simpan data ke database jika diperlukan

        // Arahkan pengguna ke halaman baru dengan membawa data
        $created_at = $request->created_at;
        $id = $request->id;

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'item' => $id,
        ]);
    }

    public function ReportPrint(Request $request)
    {
        $title = 'Laporan Produktivitas Operator';
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
         
        if ($reqid === 'all') {
            $alat = Operator::get();
            $id_alat = $alat->pluck('id')->toArray();
            $jumlah = ActOper::whereIn('operator_id', $id_alat)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->groupBy('operator_id')
            ->selectRaw('operator_id, count(operator_id) as jumlah')
            ->pluck('jumlah', 'operator_id');

             $activity = ActOper::whereIn('operator_id', $id_alat)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->orderBy('operator_id', 'asc')->orderBy('operator_name', 'asc')->orderBy('created_at', 'asc')->get();
        }else {
            $id = explode(',', $reqid);
            $alat = Operator::whereIn('id', $id)->get();
            $id_alat = $alat->pluck('id')->toArray();
            $jumlah = ActOper::whereIn('operator_id', $id_alat)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->groupBy('id_alat')
            ->selectRaw('operator_id, count(operator_id) as jumlah')
            ->pluck('jumlah', 'operator_id');
            $activity = ActOper::whereIn('operator_id', $id_alat)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->orderBy('operator_id', 'asc')->orderBy('operator_name', 'asc')->orderBy('created_at', 'asc')->get();
        }
        

        return view('reports.operator.laporan-operator', compact('title', 'alat', 'jumlah', 'activity'));
    }
}
