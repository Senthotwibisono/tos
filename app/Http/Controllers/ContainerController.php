<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\HistoryContainer as History;
use App\Models\VVoyage as Kapal;
use App\Models\Isocode;
use App\Models\YardRot as Yard;
use App\Exports\ContainersReport;
use App\Exports\ContainersReportAll;
use Maatwebsite\Excel\Facades\Excel;


class ContainerController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function indexHistory()
    {
        $data['title'] = 'History Container';
        $data['kapal'] = Kapal::orderBy('ves_id', 'asc')->get();
        $data['jumlahKapal'] = Kapal::count();

        Carbon::setLocale('id');
        $now = Carbon::now();
        $data['bulan'] = $now->translatedFormat('F');

        $data['kapalBulan'] = Kapal::whereYear('eta_date', $now->year)->where('eta_date', $now->month)->count();
        $data['container'] = Item::count();
        $data['import'] = Item::where('ctr_i_e_t', '=', 'I')->count();
        $data['export'] = Item::where('ctr_i_e_t', '=', 'E')->count();
        
        return view('container.main', $data);
    }

    public function indexSortVes($id, Request $request)
    {
      $kapal = Kapal::where('ves_id', $id)->first();
      $data['id'] = $id;
      $data['title'] = "Report Container by Vessel " . $kapal->ves_name . ' ' . $kapal->voy_out;

      $query = Item::where('ves_id', $id)->orderBy('ctr_i_e_t', 'desc')->orderBy('container_key', 'asc');
      $data['intern'] = Item::where('ves_id', $id)->distinct('ctr_intern_status')->pluck('ctr_intern_status');
 

      // Apply filters
      if ($request->ctr_i_e_t) {
          $query->where('ctr_i_e_t', $request->ctr_i_e_t);
      }
  
      if ($request->ctr_active_yn) {
          $query->where('ctr_active_yn', $request->ctr_active_yn);
      }

      if ($request->is_damage) {
        $query->where('is_damage', $request->is_damage);
      }

      if ($request->ctr_opr) {
        $query->where('ctr_opr', 'like', '%' . $request->ctr_opr . '%');
      }

      if ($request->ctr_type) {
        $query->where('ctr_type', 'like', '%' . $request->ctr_type . '%');
      }

      if ($request->ctr_size) {
        $query->where('ctr_size', $request->ctr_size);
      }

      if ($request->ctr_status) {
        $query->where('ctr_status', $request->ctr_status);
      }

      if ($request->ctr_intern_status) {
        $query->where('ctr_intern_status', $request->ctr_intern_status);
      }
  
      $data['containers'] = $query->get();

      $totalQuery = clone $query;
      $importQuery = clone $query;
      $exportQuery = clone $query;

      $data['containerTotal'] = $totalQuery->count();
      $data['import'] = $importQuery->where('ctr_i_e_t', 'I')->count();
      $data['export'] = $exportQuery->where('ctr_i_e_t', 'E')->count();

      return view('container.sortByVessel', $data);
    }

    public function history($id)
    {
      $cont = Item::where('container_key', $id)->first();
      $data['title'] = 'History Container ' . $cont->container_no;
      
      $data['cont'] = $cont;
      $data['history'] = History::where('container_key', $id)->orderBy('ctr_intern_status', 'asc')->get();
      return view('container.detail.history', $data);
    }

    public function edit($id)
    {
      $cont = Item::where('container_key', $id)->first();
      $data['title'] = 'Edit Container ' . $cont->container_no;
      $data['cont'] = $cont;
      $data['isoCode'] = Isocode::all();

      return view('container.detail.edit', $data);
    }

    public function update(Request $request)
    {
      $item = Item::where('container_key', $request->container_key)->first();
      $iso = Isocode::where('iso_code', $request->iso_code)->first();

      if ($item) {
        $item->update([
          'ctr_active_yn'=>$request->ctr_active_yn,
          'is_damage'=>$request->is_damage,
          'ctr_opr'=>$request->ctr_opr,
          'iso_code'=>$request->iso_code,
          'ctr_code'=>$iso->iso_code,
          'ctr_size'=>$iso->iso_size,
          'ctr_type'=>$iso->iso_type,
        ]);
        return redirect()->back()->with('success', 'Data Berhasil di Update');
      }else {
        return redirect()->back()->with('false', 'Data Tidak di Temukan');
      }
    }

    public function Export(Request $request, $id)
    {
      $filters = $request->query();
      $id = $id;
      $kapal = Kapal::where('ves_id', $id)->first();
      $name = 'ReportContainers-'.$kapal->ves_code.$kapal->voy_out.'.xlsx';
      // dd($filters);

      return excel::download(new ContainersReport($filters, $id), $name);
    }

    public function indexContainerAll(Request $request)
    {
      $data['title'] = "Report Container";

      $query = Item::orderBy('ctr_i_e_t', 'desc')->orderBy('container_key', 'asc');
      $data['intern'] = Item::distinct('ctr_intern_status')->pluck('ctr_intern_status');
      $data['kapal'] = Kapal::all();
      $data['yards'] = Yard::distinct('YARD_BLOCK')->pluck('YARD_BLOCK');
 

      // Apply filters
      if ($request->ctr_i_e_t) {
          $query->where('ctr_i_e_t', $request->ctr_i_e_t);
      }
  
      if ($request->ctr_active_yn) {
          $query->where('ctr_active_yn', $request->ctr_active_yn);
      }

      if ($request->is_damage) {
        $query->where('is_damage', $request->is_damage);
      }

      if ($request->ctr_opr) {
        $query->where('ctr_opr', 'like', '%' . $request->ctr_opr . '%');
      }

      if ($request->ctr_type) {
        $query->where('ctr_type', 'like', '%' . $request->ctr_type . '%');
      }

      if ($request->ctr_size) {
        $query->where('ctr_size', $request->ctr_size);
      }

      if ($request->ctr_status) {
        $query->where('ctr_status', $request->ctr_status);
      }

      if ($request->ctr_intern_status) {
        $query->where('ctr_intern_status', $request->ctr_intern_status);
      }
      if ($request->ves_id) {
        $query->where('ves_id', $request->ves_id);
      }
      if ($request->yard_block) {
        $query->where('yard_block', $request->yard_block);
      }
  
      $data['containers'] = $query->get();

      $totalQuery = clone $query;
      $importQuery = clone $query;
      $exportQuery = clone $query;

      $data['containerTotal'] = $totalQuery->count();
      $data['import'] = $importQuery->where('ctr_i_e_t', 'I')->count();
      $data['export'] = $exportQuery->where('ctr_i_e_t', 'E')->count();
      $data['damage'] = $exportQuery->where('is_damage', 'Y')->count();

      return view('container.sortByAll', $data);
    }

    public function ExportAll(Request $request)
    {
      $filters = $request->query();
      
      
      $name = 'ReportContainers.xlsx';
      // dd($filters);

      return excel::download(new ContainersReportAll($filters), $name);
    }
}
