<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\KategoriAlat;
use App\Models\MasterAlat;
use App\Models\ActAlat;

class EquipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Master Alat';

        $category = KategoriAlat::orderBy('id', 'asc')->get();
        $alat = MasterAlat::orderBy('category', 'asc')->get();
        return view ('master.alat', compact('title', 'category', 'alat'));
    }

    public function addCategory(Request $request)
    {
        $category = KategoriAlat::create([
            'name'=>$request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'item' => $category,
        ]);
    }

    public function addAlat(Request $request)
    {
        $alat = MasterAlat::create([
            'category'=>$request->category,
            'name'=>$request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'item' => $alat,
        ]);
    }

    public function report()
    {
        $title = 'Equipment Reports';
        $alat = ActAlat::orderBy('category', 'asc')->get();
        $master_alat = MasterAlat::get();
        $kategori = KategoriAlat::get();
        return view('reports.equipment.index', compact('title', 'alat', 'master_alat', 'kategori')); 
    }

    public function get_alat(Request $request)
    {
        $kategori = $request->name;
        if ($kategori === 'all') {
            $alat = MasterAlat::get();
        
        $option = []; // Inisialisasi variabel $option sebagai array kosong
        
        foreach ($alat as $kode) {
            $option[] = [
                'value' => $kode->id,
                'text' => $kode->name,
            ];
        }
        return response()->json($option);
        }else {
            $alat = MasterAlat::whereIn('category', $kategori)->get();
        
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
    public function get_data_alat(Request $request)
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

    public function laporan_alat(Request $request)
    {
        $title = 'Laporan Produktivitas Alat';
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
            $alat = MasterAlat::get();
            $id_alat = $alat->pluck('id')->toArray();
            $jumlah = ActAlat::whereIn('id_alat', $id_alat)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->groupBy('id_alat')
            ->selectRaw('id_alat, count(id_alat) as jumlah')
            ->pluck('jumlah', 'id_alat');

             $activity = ActAlat::whereIn('id_alat', $id_alat)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->orderBy('category', 'asc')->orderBy('nama_alat', 'asc')->orderBy('created_at', 'asc')->get();
        }else {
            $id = explode(',', $reqid);
            $alat = MasterAlat::whereIn('id', $id)->get();
            $id_alat = $alat->pluck('id')->toArray();
            $jumlah = ActAlat::whereIn('id_alat', $id_alat)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->groupBy('id_alat')
            ->selectRaw('id_alat, count(id_alat) as jumlah')
            ->pluck('jumlah', 'id_alat');
            $activity = ActAlat::whereIn('id_alat', $id_alat)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->orderBy('category', 'asc')->orderBy('nama_alat', 'asc')->orderBy('created_at', 'asc')->get();
        }
        

        return view('reports.equipment.laporan-alat', compact('title', 'alat', 'jumlah', 'activity'));
    }
}
