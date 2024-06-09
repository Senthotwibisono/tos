<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\VVoyage as Kapal;
use App\Models\Item;
use App\Exports\RealisasiMuat;
use Maatwebsite\Excel\Facades\Excel;


class RealisasiMuatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title'] = "Realisasi Muat";
        $data['kapal'] = Kapal::all();

        return view('realisasiMuat.main', $data);
    }

    public function listMuat($id)
    {
        $kapal = Kapal::where('ves_id', $id)->first();
        $data['title'] = "Realisasi Muat Kapal ".$kapal->ves_name." - ".$kapal->voy_out;
        $data['kapal'] = $kapal;
        $data['jumlahCont'] = Item::where('ves_id', $id)->where('ctr_i_e_t', '=', 'E')->where('ctr_intern_status', '=', '56')->count();

        $data['containers'] = Item::where('ves_id', $id)->where('ctr_i_e_t', '=', 'E')->where('ctr_intern_status', '=', '56')->get();

        return view('realisasiMuat.detail', $data);
    }

    public function Excel($id)
    {
      $id = $id;
      $kapal = Kapal::where('ves_id', $id)->first();
      $name = 'Baplei-Muat-'.$kapal->ves_code.$kapal->voy_out.'.xlsx';
      // dd($filters);

      return excel::download(new RealisasiMuat($id), $name);
    }
}
