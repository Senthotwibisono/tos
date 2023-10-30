<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\VVoyage;
use PDF;
use TCPDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
{
    $title = 'Discharged Repport';
    $confirmed = Item::where('ctr_intern_status', '=', 02)->orderBy('update_time', 'desc')->get();
    $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->get(['ves_id', 'ves_name', 'voy_no']);
   $vesCodes = []; // Membuat array kosong untuk menampung ves_codes        

        return view('reports.report.disch-reports', compact('item', 'vesCodes', 'title'));
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
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', 02)
                ->orderBy('bay_slot', 'asc')->orderBy('bay_row', 'asc')->orderBy('bay_tier', 'asc')
                ->where('ctr_intern_status','=', '02' )
                ->get();
                

    return response()->json( $item);
}

public function generateREPT_disch(Request $request)
{
    
    $ves_id = $request->query('ves_id');
    $voy_nos = Item::where('ves_id', $ves_id)->distinct('voy_no')->pluck('voy_no');
    $ves_names = Item::where('ves_id', $ves_id)->distinct('ves_name')->pluck('ves_name');
    $ves_codes = Item::where('ves_id', $ves_id)->distinct('ves_code')->pluck('ves_code');
    $ves_name = implode(", ", $ves_names->toArray());
    $voy_no = implode(", ", $voy_nos->toArray());
    $ves_code = implode(", ", $ves_codes->toArray());
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '02' )
                ->orderBy('bay_slot', 'asc')->orderBy('bay_row', 'asc')->orderBy('bay_tier', 'asc')
                
                ->get();
        
    $total = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '02' )->count();   
    $belum_bongkar = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '02' )->count(); 

        // Lakukan tindakan yang sesuai dengan nilai ves_id
        // Misalnya, ambil data berdasarkan ves_id dan hasilkan PDF
        
        // Contoh: Membuat objek PDF menggunakan library seperti Dompdf atau TCPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);

        $pdf = new Dompdf($options);
        $pageNumber = $pdf->getCanvas()->get_page_number();
        $pageCount = $pdf->getCanvas()->get_page_count();
        $pdf->loadHtml(view('reports.report.pdf.disch', ['item'=>$item, 'ves_id'=>$ves_id, 'voy_no'=>$voy_no, 'ves_name'=>$ves_name, 'ves_code'=>$ves_code, 'total'=>$total, 'belum_bongkar'=>$belum_bongkar, 'pageNumber'=>$pageNumber, 'pageCount'=>$pageCount]));
        $pdf->render();

        // Menggunakan response untuk mengirim PDF ke browser
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="data.pdf"'
        ]);
}

//PLC
public function index_plc()
{
    $title = 'Placement Repport';
    $confirmed = Item::where('ctr_intern_status', '=', 03)->orderBy('update_time', 'desc')->get();
    $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->get(['ves_id', 'ves_name', 'voy_no']);
   $vesCodes = []; // Membuat array kosong untuk menampung ves_codes        

        return view('reports.report.plc', compact('item', 'vesCodes', 'title'));
}
public function get_container_plc(Request $request)
{
    $ves_id = $request->ves_id;
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', 03)
                ->orderBy('yard_block', 'asc')->orderBy('yard_slot', 'asc')->orderBy('yard_row', 'asc')->orderBy('yard_tier', 'asc')
                ->where('ctr_intern_status','=', '03' )
                ->get();
                

    return response()->json( $item);
}

public function generateREPT_plc(Request $request)
{
    
    $ves_id = $request->query('ves_id');
    $voy_nos = Item::where('ves_id', $ves_id)->distinct('voy_no')->pluck('voy_no');
    $ves_names = Item::where('ves_id', $ves_id)->distinct('ves_name')->pluck('ves_name');
    $ves_codes = Item::where('ves_id', $ves_id)->distinct('ves_code')->pluck('ves_code');
    $ves_name = implode(", ", $ves_names->toArray());
    $voy_no = implode(", ", $voy_nos->toArray());
    $ves_code = implode(", ", $ves_codes->toArray());
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '03' )
                ->orderBy('yard_block', 'asc')->orderBy('yard_slot', 'asc')->orderBy('yard_row', 'asc')->orderBy('yard_tier', 'asc')
                
                ->get();
        
    $total = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '03' )->count();   
    $belum_bongkar = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '03' )->count(); 

        // Lakukan tindakan yang sesuai dengan nilai ves_id
        // Misalnya, ambil data berdasarkan ves_id dan hasilkan PDF
        
        // Contoh: Membuat objek PDF menggunakan library seperti Dompdf atau TCPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);

        $pdf = new Dompdf($options);
        $pageNumber = $pdf->getCanvas()->get_page_number();
        $pageCount = $pdf->getCanvas()->get_page_count();
        $pdf->loadHtml(view('reports.report.pdf.plc-pdf', ['item'=>$item, 'ves_id'=>$ves_id, 'voy_no'=>$voy_no, 'ves_name'=>$ves_name, 'ves_code'=>$ves_code, 'total'=>$total, 'belum_bongkar'=>$belum_bongkar, 'pageNumber'=>$pageNumber, 'pageCount'=>$pageCount]));
        $pdf->render();

        // Menggunakan response untuk mengirim PDF ke browser
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="data.pdf"'
        ]);
}

//STRIPPING
public function index_str()
{
    $title = 'Stripping Repport';
    $confirmed = Item::where('ctr_intern_status', '=', 04)->orderBy('update_time', 'desc')->get();
    $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->get(['ves_id', 'ves_name', 'voy_no']);
   $vesCodes = []; // Membuat array kosong untuk menampung ves_codes        

        return view('reports.report.str', compact('item', 'vesCodes', 'title'));
}
public function get_container_str(Request $request)
{
    $ves_id = $request->ves_id;
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', 04)
                ->orderBy('yard_block', 'asc')->orderBy('yard_slot', 'asc')->orderBy('yard_row', 'asc')->orderBy('yard_tier', 'asc')
                ->where('ctr_intern_status','=', '04' )
                ->get();
                

    return response()->json( $item);
}
public function generateREPT_str(Request $request)
{
    
    $ves_id = $request->query('ves_id');
    $voy_nos = Item::where('ves_id', $ves_id)->distinct('voy_no')->pluck('voy_no');
    $ves_names = Item::where('ves_id', $ves_id)->distinct('ves_name')->pluck('ves_name');
    $ves_codes = Item::where('ves_id', $ves_id)->distinct('ves_code')->pluck('ves_code');
    $ves_name = implode(", ", $ves_names->toArray());
    $voy_no = implode(", ", $voy_nos->toArray());
    $ves_code = implode(", ", $ves_codes->toArray());
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '04' )
                ->orderBy('yard_block', 'asc')->orderBy('yard_slot', 'asc')->orderBy('yard_row', 'asc')->orderBy('yard_tier', 'asc')
                
                ->get();
        
    $total = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '04' )->count();   
    $belum_bongkar = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '04' )->count(); 

        // Lakukan tindakan yang sesuai dengan nilai ves_id
        // Misalnya, ambil data berdasarkan ves_id dan hasilkan PDF
        
        // Contoh: Membuat objek PDF menggunakan library seperti Dompdf atau TCPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);

        $pdf = new Dompdf($options);
        $pageNumber = $pdf->getCanvas()->get_page_number();
        $pageCount = $pdf->getCanvas()->get_page_count();
        $pdf->loadHtml(view('reports.report.pdf.str-pdf', ['item'=>$item, 'ves_id'=>$ves_id, 'voy_no'=>$voy_no, 'ves_name'=>$ves_name, 'ves_code'=>$ves_code, 'total'=>$total, 'belum_bongkar'=>$belum_bongkar, 'pageNumber'=>$pageNumber, 'pageCount'=>$pageCount]));
        $pdf->render();

        // Menggunakan response untuk mengirim PDF ke browser
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="data.pdf"'
        ]);
}
//GATI-DEL
public function index_gati_del()
{
    $title = 'Gate In Delivery Repport';
    $confirmed = Item::where('ctr_intern_status', '=', 10)->orderBy('update_time', 'desc')->get();
    $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->get(['ves_id', 'ves_name', 'voy_no']);
   $vesCodes = []; // Membuat array kosong untuk menampung ves_codes        

        return view('reports.report.gati-del', compact('item', 'vesCodes', 'title'));
}
public function get_container_gati_del(Request $request)
{
    $ves_id = $request->ves_id;
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', 10)
                ->orderBy('invoice_no', 'asc')->orderBy('job_no', 'asc')->orderBy('truck_in_date', 'asc')
                ->where('ctr_intern_status','=', '10' )
                ->get();
                

    return response()->json( $item);
}
public function generateREPT_gati_del(Request $request)
{
    
    $ves_id = $request->query('ves_id');
    $voy_nos = Item::where('ves_id', $ves_id)->distinct('voy_no')->pluck('voy_no');
    $ves_names = Item::where('ves_id', $ves_id)->distinct('ves_name')->pluck('ves_name');
    $ves_codes = Item::where('ves_id', $ves_id)->distinct('ves_code')->pluck('ves_code');
    $ves_name = implode(", ", $ves_names->toArray());
    $voy_no = implode(", ", $voy_nos->toArray());
    $ves_code = implode(", ", $ves_codes->toArray());
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '10' )
                ->orderBy('invoice_no', 'asc')->orderBy('job_no', 'asc')->orderBy('truck_in_date', 'asc')->orderBy('truck_no', 'asc')
                
                ->get();
        
    $total = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '10' )->count();   
    $belum_bongkar = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '10' )->count(); 

        // Lakukan tindakan yang sesuai dengan nilai ves_id
        // Misalnya, ambil data berdasarkan ves_id dan hasilkan PDF
        
        // Contoh: Membuat objek PDF menggunakan library seperti Dompdf atau TCPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);

        $pdf = new Dompdf($options);
        $pageNumber = $pdf->getCanvas()->get_page_number();
        $pageCount = $pdf->getCanvas()->get_page_count();
        $pdf->loadHtml(view('reports.report.pdf.gati-del-pdf', ['item'=>$item, 'ves_id'=>$ves_id, 'voy_no'=>$voy_no, 'ves_name'=>$ves_name, 'ves_code'=>$ves_code, 'total'=>$total, 'belum_bongkar'=>$belum_bongkar, 'pageNumber'=>$pageNumber, 'pageCount'=>$pageCount]));
        $pdf->render();

        // Menggunakan response untuk mengirim PDF ke browser
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="data.pdf"'
        ]);
}
//GATO-DEL
public function index_gato_del()
{
    $title = 'Gate Out Delivery Repport';
    $confirmed = Item::where('ctr_intern_status', '=', '09')->orderBy('update_time', 'desc')->get();
    $item = Item::orderBy('ves_id', 'desc')->distinct('ves_id')->get(['ves_id', 'ves_name', 'voy_no']);
   $vesCodes = []; // Membuat array kosong untuk menampung ves_codes        

        return view('reports.report.gato-del', compact('item', 'vesCodes', 'title'));
}
public function get_container_gato_del(Request $request)
{
    $ves_id = $request->ves_id;
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', '09')
                ->orderBy('invoice_no', 'asc')->orderBy('job_no', 'asc')->orderBy('truck_in_date', 'asc')
                ->where('ctr_intern_status','=', '09' )
                ->get();
                

    return response()->json( $item);
}
public function generateREPT_gato_del(Request $request)
{
    
    $ves_id = $request->query('ves_id');
    $voy_nos = Item::where('ves_id', $ves_id)->distinct('voy_no')->pluck('voy_no');
    $ves_names = Item::where('ves_id', $ves_id)->distinct('ves_name')->pluck('ves_name');
    $ves_codes = Item::where('ves_id', $ves_id)->distinct('ves_code')->pluck('ves_code');
    $ves_name = implode(", ", $ves_names->toArray());
    $voy_no = implode(", ", $voy_nos->toArray());
    $ves_code = implode(", ", $ves_codes->toArray());
    $item = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '09' )
                ->orderBy('invoice_no', 'asc')->orderBy('job_no', 'asc')->orderBy('truck_in_date', 'asc')->orderBy('truck_no', 'asc')
                
                ->get();
        
    $total = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '09' )->count();   
    $belum_bongkar = Item::where('ves_id', $ves_id)->where('ctr_intern_status','=', '09' )->count(); 

        // Lakukan tindakan yang sesuai dengan nilai ves_id
        // Misalnya, ambil data berdasarkan ves_id dan hasilkan PDF
        
        // Contoh: Membuat objek PDF menggunakan library seperti Dompdf atau TCPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);

        $pdf = new Dompdf($options);
        $pageNumber = $pdf->getCanvas()->get_page_number();
        $pageCount = $pdf->getCanvas()->get_page_count();
        $pdf->loadHtml(view('reports.report.pdf.gato-del-pdf', ['item'=>$item, 'ves_id'=>$ves_id, 'voy_no'=>$voy_no, 'ves_name'=>$ves_name, 'ves_code'=>$ves_code, 'total'=>$total, 'belum_bongkar'=>$belum_bongkar, 'pageNumber'=>$pageNumber, 'pageCount'=>$pageCount]));
        $pdf->render();

        // Menggunakan response untuk mengirim PDF ke browser
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="data.pdf"'
        ]);
}


    public function index_xp()
    {
        $title ="Realisasi Export";
        $kapal = VVoyage::orderBy('deparature_date', 'desc')->get();

        $containerKeyCounts = [];

        foreach ($kapal as $kpl) {
            $count = Item::where('ctr_intern_status', '56')
                ->where('ves_id', $kpl->ves_id)
                ->count();
    
            // Menambahkan jumlah ke dalam array
            $containerKeyCounts[$kpl->ves_id] = $count;
        }
        return view('reports.report.exp.main', compact('title', 'kapal', 'containerKeyCounts'));
    }

    public function detail_cont(Request $request)
    {
        $id = $request->ves_id;
        $cont = Item::where('ves_id', $id)->where('ctr_intern_status', '56')->get();

        if ($cont) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $cont,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!!',
            ]);
        }
    }

    public function get_data_kapal(Request $request)
    {

        // Simpan data ke database jika diperlukan

        // Arahkan pengguna ke halaman baru dengan membawa data
        
        $ves_id = $request->ves_id;

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'item' => $ves_id,
        ]);
    }

    public function laporan_kapal(Request $request)
    {
        $title = 'Laporan Produktivitas Alat';
      
        $ves_id = $request->id;

        

        $kapal = VVoyage::where('ves_id', $ves_id)->first();
        $name = $kapal->ves_name;
        $voy = $kapal->voy_out;
        $port = $kapal->last_port;
        $flag = $kapal->reg_flag;
        $cont = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '56')->get();
    
        $total = $cont->count();

        return view('reports.report.exp.pdf', compact('title', 'kapal', 'cont', 'name', 'voy', 'total', 'port', 'flag'));
    }
}
