<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceImport;
use App\Models\ImportDetail;
use App\Models\ExportDetail;
use App\Models\ExtendDetail;
use App\Models\InvoiceHeaderStevadooring as Steva;
use App\Exports\ImportZahir;
use App\Exports\ZahirSteva;
use Maatwebsite\Excel\Facades\Excel;


class ZahirController extends Controller
{
   public function Import(Request $request)
   {
    $startDate = $request->start;
    $endDate = $request->end;

    $data = InvoiceImport::whereDate('order_at', '>=', $startDate)->whereDate('order_at', '<=', $endDate)->orderBy('id', 'asc')->get();

    $header = ['TGL TRANS', ' NO. REFERENSI/INV', 'NAMA PELANGGAN', 'NAMA GUDANG', 'NAMA DEPT', 'ID JOB', 'KETERANGAN',
                'NAMA SALESMAN', 'ISTUNAI', 'BIAYALAIN', 'DISKONFINAL', 'UANGMUKA', 'PLU/KODE BARANG', 'QTY', 'SATUAN', 
                'HARGA', 'DISKON (%)', 'KODE PAJAK', 'TGL JATUH TEMPO', 'AKUN BANK', 'NAMA DEPT DETAIL', 'IDJ JOB DETAIL',
                'NOTE DETA', 'NO DOKUMEN', 'MATA UANG', 'NILAI TUKAR', 'NOMOR SERI', 'NOMOR SO'];

    $csv = [];
    $csv[] = $header;

    foreach ($data as $item) {
        if ($item->lunas = 'Y') {
            $isTunai = 'T';
        }else {
            $isTunai = 'F';
        }
        
        // 20
        if ($item->ctr_20 != null) {
        
        }
        if (!empty($csvRow)) {
            $csv[] = $csvRow;
        }
    }
    $file = fopen('export.csv', 'w');

    // Write CSV data to file
    foreach ($csv as $csvRow) {
        fputcsv($file, $csvRow);
    }

    // Close the file handle
    fclose($file);

    // Return a response or redirect as needed
    return response()->download('export.csv')->deleteFileAfterSend(true);
   }

   public function ZahirImport(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
     $data = ImportDetail::whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate)->orderBy('inv_id', 'asc')->get();
       $fileName = 'ReportZahirImport-'. $startDate . $endDate .'.csv';
     return Excel::download(new ImportZahir($data), $fileName);
   }

   public function ZahirExport(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
     $data = ExportDetail::whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate)->orderBy('inv_id', 'asc')->get();
       $fileName = 'ReportZahirExport-'. $startDate . $endDate .'.csv';
     return Excel::download(new ImportZahir($data), $fileName);
   }

   public function ZahirExtend(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
     $data = ExtendDetail::whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate)->orderBy('inv_id', 'asc')->get();
       $fileName = 'ReportZahirExtend-'. $startDate . $endDate .'.csv';
     return Excel::download(new ImportZahir($data), $fileName);
   }

   public function ZahirSteva(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
     $data = Steva::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->orderBy('id', 'asc')->get();
       $fileName = 'ReportZahirStevadooring-'. $startDate . $endDate .'.xlsx';
     return Excel::download(new ZahirSteva($data), $fileName);
   }
}