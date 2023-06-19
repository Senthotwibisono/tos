<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use App\Models\Item;
use PDF;
use TCPDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportCont extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

public function index()
{
    $title = 'Print Disch List';
    $confirmed = Item::where('ctr_intern_status', '=', 01,)->orderBy('update_time', 'desc')->get();
   $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->pluck('ves_id');
   $vesCodes = []; // Membuat array kosong untuk menampung ves_codes        

        return view('reports.report.review', compact('item', 'vesCodes', 'title'));
}

public function get_ves(Request $request)
{
    $ves_id = $request->ves_id;
    $name = Item::where('ves_id', $ves_id)->first();
   
    if ($name) {
        return response()->json(['name' => $name->ves_name, 'code'=>$name->ves_code]);
    }
    return response()->json(['name' => 'data tidak ditemukan', 'code' => 'data tidak ditemukan']);

}

public function get_bay(request $request)
{
    $ves_id = $request->ves_id;
    $bay = Item::where('ves_id', $ves_id)->distinct()->get('bay_slot');
    foreach ($bay as $slot) {
        echo "<option value='$slot->bay_slot'>$slot->bay_slot</option>";
    }
}

public function get_container(Request $request)
{
    $ves_id = $request->ves_id;
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', 01)
                ->orderBy('bay_slot', 'asc')->orderBy('bay_row', 'asc')->orderBy('bay_tier', 'asc')
                ->where('ctr_intern_status','=', '01' )
                ->get();
                

    return response()->json( $item);
}

public function generatePDF_disch(Request $request)
{
    
    $ves_id = $request->query('ves_id');
    $voy_nos = Item::where('ves_id', $ves_id)->distinct('voy_no')->pluck('voy_no');
    $ves_names = Item::where('ves_id', $ves_id)->distinct('ves_name')->pluck('ves_name');
    $ves_codes = Item::where('ves_id', $ves_id)->distinct('ves_code')->pluck('ves_code');
    $ves_name = implode(", ", $ves_names->toArray());
    $voy_no = implode(", ", $voy_nos->toArray());
    $ves_code = implode(", ", $ves_codes->toArray());
    $item = Item::where('ves_id', $ves_id)
                ->orderBy('bay_slot', 'asc')->orderBy('bay_row', 'asc')->orderBy('bay_tier', 'asc')
                ->where('ctr_intern_status','=', '01' )
                ->get();
        
    $total = Item::where('ves_id', $ves_id)->count();   
    $belum_bongkar = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '01' )->count(); 

        // Lakukan tindakan yang sesuai dengan nilai ves_id
        // Misalnya, ambil data berdasarkan ves_id dan hasilkan PDF
        
        // Contoh: Membuat objek PDF menggunakan library seperti Dompdf atau TCPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);

        $pdf = new Dompdf($options);
        $pageNumber = $pdf->getCanvas()->get_page_number();
        $pageCount = $pdf->getCanvas()->get_page_count();
        $pdf->loadHtml(view('reports.report.pdfcontves', ['item'=>$item, 'ves_id'=>$ves_id, 'voy_no'=>$voy_no, 'ves_name'=>$ves_name, 'ves_code'=>$ves_code, 'total'=>$total, 'belum_bongkar'=>$belum_bongkar, 'pageNumber'=>$pageNumber, 'pageCount'=>$pageCount]));
        $pdf->render();

        // Menggunakan response untuk mengirim PDF ke browser
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="data.pdf"'
        ]);
}


    // Realisasi Bongkar
    public function index_bongkar()
{
    $title = 'Realisasi Bongkar';
    $confirmed = Item::where('ctr_intern_status', '!=', 01,)->orderBy('update_time', 'desc')->get();
   $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->pluck('ves_id');
   $vesCodes = []; // Membuat array kosong untuk menampung ves_codes        

        return view('planning.print.realisasibongkar', compact('item', 'vesCodes', 'title'));
}

public function get_ves_bongkar(Request $request)
{
    $ves_id = $request->ves_id;
    $name = Item::where('ves_id', $ves_id)->first();
   
    if ($name) {
        return response()->json(['name' => $name->ves_name, 'code'=>$name->ves_code]);
    }
    return response()->json(['name' => 'data tidak ditemukan', 'code' => 'data tidak ditemukan']);

}

public function get_bay_bongkar(request $request)
{
    $ves_id = $request->ves_id;
    $bay = Item::where('ves_id', $ves_id)->distinct()->get('bay_slot');
    foreach ($bay as $slot) {
        echo "<option value='$slot->bay_slot'>$slot->bay_slot</option>";
    }
}

public function get_container_bongkar(Request $request)
{
    $ves_id = $request->ves_id;
    $item = Item::where('ves_id', $ves_id)
                ->orderBy('bay_slot', 'asc')->orderBy('bay_row', 'asc')->orderBy('bay_tier', 'asc')
                ->where('ctr_intern_status','!=', '01' )
                ->get();
                

    return response()->json( $item);
}

public function generatePDF_bongkar(Request $request)
{
    
    $ves_id = $request->query('ves_id');
    $voy_nos = Item::where('ves_id', $ves_id)->distinct('voy_no')->pluck('voy_no');
    $ves_names = Item::where('ves_id', $ves_id)->distinct('ves_name')->pluck('ves_name');
    $ves_codes = Item::where('ves_id', $ves_id)->distinct('ves_code')->pluck('ves_code');
    $ves_name = implode(", ", $ves_names->toArray());
    $ves_code = implode(", ", $ves_codes->toArray());
    $voy_no = implode(", ", $voy_nos->toArray());
    $items = Item::where('ves_id', $ves_id)
                ->where('ctr_intern_status','!=', '01' )
                ->orderBy('ctr_intern_status', 'asc')
                ->orderBy('yard_block', 'asc')
                ->orderBy('yard_slot', 'asc')
                ->orderBy('yard_row', 'asc')
                ->orderBy('yard_tier', 'asc')
                ->get();
    
    $total = Item::where('ves_id', $ves_id)->count();   
    $bongkar = Item::where('ves_id', $ves_id)->where('ctr_intern_status','!=', '01' )->count(); 
        // Lakukan tindakan yang sesuai dengan nilai ves_id
        // Misalnya, ambil data berdasarkan ves_id dan hasilkan PDF
        
        // Contoh: Membuat objek PDF menggunakan library seperti Dompdf atau TCPDF
        $pdf = new \Dompdf\Dompdf();
        $pdf->loadHtml(view('planning.print.pdfbongkar', ['items'=>$items, 'ves_id'=>$ves_id, 'voy_no'=>$voy_no, 'ves_name'=>$ves_name, 'ves_code'=>$ves_code, 'total'=>$total, 'bongkar'=>$bongkar]));
        $pdf->render();

        // Menggunakan response untuk mengirim PDF ke browser
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="data.pdf"'
        ]);
}
}
