<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\RO_Gate;
use App\Models\RO_Realisasi;
use App\Models\RO;
use App\Models\Port;
use App\Models\VVoyage;
use Auth;
use Spatie\PdfToText\Pdf;
use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index_ro()
    {
        $title = 'Dokumen R.O';

        $ro = RO::orderBy('created_at', 'desc')->get();
        $vessel_voyage = VVoyage::orderBy('deparature_date', 'desc')->get();
        $port = Port::all();

        return view('docs.dokumen.ro', compact('title', 'ro', 'vessel_voyage', 'port'));
    }

    public function Androidindex_ro()
    {
        $title = 'Dokumen R.O';

        $ro = RO::orderBy('created_at', 'desc')->get();
        $vessel_voyage = VVoyage::whereDate('deparature_date', '>=', now())->get();
        $port = Port::all();

        return view('docs.dokumen.androidRo', compact('title', 'ro', 'vessel_voyage', 'port'));
    }

    public function pdf_ro(Request $request)
    {
        $file = $request->file('file');

        if ($file != null) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('pdfs', $fileName);
    
            $vessel = $request->ves_id;
            $ves = VVoyage::where('ves_id', $vessel)->first();
    
            $ctr20 = $request->ctr_20;
            $ctr40 = $request->ctr_40;
            $jmlh_cont = $ctr20 + $ctr40;
            $ro = RO::create([
                'ro_no' =>$request->ro_no,
                'stuffing_service' =>$request->stuffing_service,
                'jmlh_cont' => $jmlh_cont,
                'shipper' =>$request->shipper,
                'ves_id' => $request->ves_id,
                'ves_name' => $ves->ves_name,
                'ves_code' => $ves->ves_code,
                'voy_no' => $ves->voy_out,
                'pod' =>$request->pod,
                'ctr_20'=>$request->ctr_20,
                'ctr_40'=>$request->ctr_40,
                'file' => $fileName,
            ]) ;
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $ro,
            ]);
    
        }else {
            $vessel = $request->ves_id;
            $ves = VVoyage::where('ves_id', $vessel)->first();
    
            $ctr20 = $request->ctr_20;
            $ctr40 = $request->ctr_40;
            $jmlh_cont = $ctr20 + $ctr40;
            $ro = RO::create([
                'ro_no' =>$request->ro_no,
                'stuffing_service' =>$request->stuffing_service,
                'jmlh_cont' => $jmlh_cont,
                'shipper' =>$request->shipper,
                'ves_id' => $request->ves_id,
                'ves_name' => $ves->ves_name,
                'ves_code' => $ves->ves_code,
                'voy_no' => $ves->voy_out,
                'pod' =>$request->pod,
                'ctr_20'=>$request->ctr_20,
                'ctr_40'=>$request->ctr_40,
            ]) ;
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $ro,
            ]);
        }
       
       
    }


    public function update_ro(Request $request)
    {
            $id = $request->ro_id;
            $ro = RO::where('ro_id', $id)->first();
            if ($ro) {
                $file = $request->file('file');

                if ($file != null) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('pdfs', $fileName);
            
                    $vessel = $request->ves_id;
                    $ves = VVoyage::where('ves_id', $vessel)->first();
            
                    $ctr20 = $request->ctr_20;
                    $ctr40 = $request->ctr_40;
                    $jmlh_cont = $ctr20 + $ctr40;
                    $ro ->update([
                        'ro_no' =>$request->ro_no,
                        'stuffing_service' =>$request->stuffing_service,
                        'jmlh_cont' => $jmlh_cont,
                        'shipper' =>$request->shipper,
                        'ves_id' => $request->ves_id,
                        'ves_name' => $ves->ves_name,
                        'ves_code' => $ves->ves_code,
                        'voy_no' => $ves->voy_out,
                        'pod' =>$request->pod,
                        'ctr_20'=>$request->ctr_20,
                        'ctr_40'=>$request->ctr_40,
                        'file' => $fileName,
                    ]) ;
                    return response()->json([
                        'success' => true,
                        'message' => 'Updated successfully!',
                        'data' => $ro,
                    ]);
            
                }else {
                    $vessel = $request->ves_id;
                    $ves = VVoyage::where('ves_id', $vessel)->first();
            
                    $ctr20 = $request->ctr_20;
                    $ctr40 = $request->ctr_40;
                    $jmlh_cont = $ctr20 + $ctr40;
                    $ro ->update([
                        'ro_no' =>$request->ro_no,
                        'stuffing_service' =>$request->stuffing_service,
                        'jmlh_cont' => $jmlh_cont,
                        'shipper' =>$request->shipper,
                        'ves_id' => $request->ves_id,
                        'ves_name' => $ves->ves_name,
                        'ves_code' => $ves->ves_code,
                        'voy_no' => $ves->voy_out,
                        'pod' =>$request->pod,
                        'ctr_20'=>$request->ctr_20,
                        'ctr_40'=>$request->ctr_40,
                    ]) ;
                    return response()->json([
                        'success' => true,
                        'message' => 'Updated successfully!',
                        'data' => $ro,
                    ]);
                }
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'RO Not Found',
                ]);
            }   
    }
    
    public function showDocument(Request $request)
    {
        $id = $request->file;
        $tema = RO::where('file',$id)->first();
    
        // Jika tema ditemukan, tampilkan file PDF
        if ($tema) {
            $filePath = storage_path('app/pdfs/' . $tema->file);
            
            // Periksa apakah file PDF ada
            if (file_exists($filePath)) {
                return response()->file($filePath, ['Content-Type' => 'application/pdf']);
            } else {
                return response()->json(['error' => 'File PDF tidak ditemukan'], 404);
            }
        } else {
            return response()->json(['error' => 'Tema tidak ditemukan'], 404);
        }
    }

    public function edit_ro(Request $request)
    {
        $id = $request->ro_id;
        $ro = RO::where('ro_id', '=', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $ro,
        ]);
    }


    public function index_items()
    {
        $title = 'Inventory Items';

        $items = Item::whereNotIn('ctr_intern_status', ['09', '56'])->get();

        return view('docs.inventory.container', compact('title', 'items'));
    }
    
    public function container_ro(Request $request)
    {
        $id = $request->ro_no;
        $detail_cont = RO_Realisasi::where('ro_no', $id)->get();

        if ($detail_cont) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $detail_cont,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!!',
            ]);
        }
    }
}
