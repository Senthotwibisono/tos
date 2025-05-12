<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceImport;
use App\Models\InvoiceExport;
use App\Models\Extend;
use App\Models\ImportDetail;
use App\Models\ExportDetail;
use App\Models\ExtendDetail;
use App\Models\InvoiceHeaderStevadooring as Steva;
use App\Exports\ImportZahir;
use App\Exports\ZahirSteva;
use App\Exports\ZahirOthers;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;


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

    // dd($request->all());
     $startDate = $request->start;
     $endDate = $request->end;
    //  $data = ImportDetail::whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate)->orderBy('inv_id', 'asc')->get();
     $exportInv = InvoiceImport::whereHas('service', function ($query) {
      $query->where('ie', '=', 'I');
      })
      ->whereNot('lunas', '=', 'C')
      ->whereDate('invoice_date', '>=', $startDate)
      ->whereDate('invoice_date', '<=', $endDate)
      ->get();
    
      // Get the ids of the fetched InvoiceExport records
      $exportInvIds = $exportInv->pluck('id')->toArray();
    
      // Fetch ExportDetail records that match the InvoiceExport ids
      $data = ImportDetail::whereIn('inv_id', $exportInvIds)->get();
      $fileName = 'ReportZahirImport-'. $startDate . $endDate .'.csv';
      try {
        csvExcel($fileName, $data);
        return back()->with('success', 'Success');
      } catch (\Throwable $th) {
        return back()->with('error', 'Somethings wrong in : ' . $th->getMessage());
      }
   }

   private function csvExcel($fileName, $data)
   {
     return Excel::download(new ImportZahir($data), $fileName);
   }

   public function newImport(Request $request)
   {
    $import = InvoiceImport::whereHas('service', function($query) {
      $query->where('ie', 'I');
    })->whereNot('lunas', 'C')->whereIn('inv_type', $request->inv_type)->whereBetween('invoice_date', [Carbon::parse($request->start), Carbon::parse($request->end)])->get();

    $detil = ImportDetail::whereIn('inv_id', $import->pluck('id'))->get();
    if ($detil->isEmpty()) {
      return response()->json([
        'success' => false,
        'message' => 'Tidak ada data yang bisa di export',
      ]);
    }
    $fileName = 'ReportZahirImport-'. Carbon::parse($request->start) .'-'. Carbon::parse($request->end) .'.csv';

    $invId = $import->pluck('id');
    return response()->json([
      'success' => true,
      'message' => 'Data tersedia, siap di export',
      'data' => [
        'inv_id' => $invId,
        'fileName' => $fileName,
      ],
    ]);
   }

   public function csvImport(Request $request)
   {
    // var_dump($request->all());
    // die;
    // dd($data);
    try {
      $data = ImportDetail::whereIn('inv_id', json_decode($request->inv_id))->get();
       return Excel::download(new ImportZahir($data), $request->fileName.'.csv');
    } catch (\Throwable $th) {
      return response()->json([
        'success' => false,
        'message' => $th->getMessage(),
      ]);
    }
   }



   public function ZahirExport(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
     $exportInv = InvoiceExport::whereHas('service', function ($query) {
      $query->where('ie', '=', 'E');
      })
      ->whereNot('lunas', '=', 'C')
      ->whereDate('invoice_date', '>=', $startDate)
      ->whereDate('invoice_date', '<=', $endDate)
      ->get();
    
      // Get the ids of the fetched InvoiceExport records
      $exportInvIds = $exportInv->pluck('id')->toArray();
    
      // Fetch ExportDetail records that match the InvoiceExport ids
      $data = ExportDetail::whereIn('inv_id', $exportInvIds)->get();
       $fileName = 'ReportZahirExport-'. $startDate . $endDate .'.csv';
     return Excel::download(new ImportZahir($data), $fileName);
   }

   public function newExport(Request $request)
   {
    $import = InvoiceExport::whereHas('service', function($query) {
      $query->where('ie', 'E');
    })->whereNot('lunas', 'C')->whereIn('inv_type', $request->inv_type)->whereBetween('invoice_date', [Carbon::parse($request->start), Carbon::parse($request->end)])->get();

    $detil = ExportDetail::whereIn('inv_id', $import->pluck('id'))->get();
    if ($detil->isEmpty()) {
      return response()->json([
        'success' => false,
        'message' => 'Tidak ada data yang bisa di export',
      ]);
    }
    $fileName = 'ReportZahirExport-'. Carbon::parse($request->start) .'-'. Carbon::parse($request->end) .'.csv';

    $invId = $import->pluck('id');
    return response()->json([
      'success' => true,
      'message' => 'Data tersedia, siap di export',
      'data' => [
        'inv_id' => $invId,
        'fileName' => $fileName,
      ],
    ]);
   }

   public function csvExport(Request $request)
   {
    // var_dump($request->all());
    // die;
    // dd($data);
    try {
      $data = ExportDetail::whereIn('inv_id', json_decode($request->inv_id))->get();
       return Excel::download(new ImportZahir($data), $request->fileName.'.csv');
    } catch (\Throwable $th) {
      return response()->json([
        'success' => false,
        'message' => $th->getMessage(),
      ]);
    }
   }

   public function ZahirPlugging(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
     $exportInv = InvoiceExport::whereHas('service', function ($query) {
      $query->where('ie', '=', 'P');
      })
      ->whereNot('lunas', '=', 'C')
      ->whereDate('invoice_date', '>=', $startDate)
      ->whereDate('invoice_date', '<=', $endDate)
      ->get();
    
      // Get the ids of the fetched InvoiceExport records
      $exportInvIds = $exportInv->pluck('id')->toArray();
    
      // Fetch ExportDetail records that match the InvoiceExport ids
      $data = ExportDetail::whereIn('inv_id', $exportInvIds)->get();
       $fileName = 'ReportZahirPlugging-'. $startDate . $endDate .'.csv';
     return Excel::download(new ImportZahir($data), $fileName);
   }

   public function ZahirExtend(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
    //  $data = ExtendDetail::whereNot('total', null)->whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate)->orderBy('inv_id', 'asc')->get();
     $exportInv = Extend::whereHas('service', function ($query) {
      $query->where('ie', '=', 'X');
      })
      ->whereNot('lunas', '=', 'C')
      ->whereDate('invoice_date', '>=', $startDate)
      ->whereDate('invoice_date', '<=', $endDate)
      ->get();
      // dd($exportInv);
    
      // Get the ids of the fetched InvoiceExport records
      $exportInvIds = $exportInv->pluck('id')->toArray();
    
      // Fetch ExportDetail records that match the InvoiceExport ids
      $data = ExtendDetail::whereIn('inv_id', $exportInvIds)->whereNot('total', '=', 0)->get();
       $fileName = 'ReportZahirExtend-'. $startDate . $endDate .'.csv';
     return Excel::download(new ImportZahir($data), $fileName);
   }

   public function ZahirSteva(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
     $data = Steva::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->whereNotIn('lunas', ['C', 'N'])->orderBy('id', 'asc')->get();
       $fileName = 'ReportZahirStevadooring-'. $startDate . $endDate .'.csv';
     return Excel::download(new ZahirSteva($data), $fileName);
   }

   public function ZahirOthers(Request $request)
   {
     $startDate = $request->start;
     $endDate = $request->end;
     $data = InvoiceExport::whereHas('service', function ($query) {
      $query->where('ie', '=', 'R');
      })
      ->whereNot('lunas', '=', 'C')
      ->whereDate('invoice_date', '>=', $startDate)
      ->whereDate('invoice_date', '<=', $endDate)
      ->get();
    
       $fileName = 'ReportZahirPlugging-'. $startDate . $endDate .'.csv';
     return Excel::download(new ZahirOthers($data), $fileName);
   }
}
