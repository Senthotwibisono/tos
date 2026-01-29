<?php

namespace App\Http\Controllers\Api\Materai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;


use App\Models\InvoiceImport as Import;
use App\Models\ImportDetail;

use App\Models\Extend;
use App\Models\ExtendDetail;

use App\Models\InvoiceExport;
use App\Models\ExportDetail;

use App\Models\InvoiceHeaderStevadooring as Stevadooring;
use App\Models\StevadooringDetail as STV;
use App\Models\ShiftingDetail as SFT;
use App\Models\TKapalDetail as TK;
use App\Models\TTongkakDetail as TT;

class MateraiController extends Controller
{
    public function __construct() {
        // $this->user = "mitracomm0523_stgemet@yopmail.com";
        $this->user = "elvi@isarana.co.id";
        $this->password = "Emeterai123!";
    }

    public function first(Request $request)
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);   

        if ($request->type === 'I') {   

            $invoice = Import::findOrFail($request->id);    

            // ===== PATH & FILE NAME =====
            $signedFile = $invoice->inv_no . '.pdf';
            $signedDiskPath = 'signed/import/' . $signedFile; // untuk Storage
            $signedPublicUrl = asset('storage/' . $signedDiskPath); // untuk browser    

            // ✅ JIKA FILE SUDAH ADA → LANGSUNG RETURN
            if (Storage::disk('public')->exists($signedDiskPath)) {
                return response()->json([
                    'success' => true,
                    'signed'  => true,
                    'url'     => $signedPublicUrl,
                ]);
            }   

            // ===== GENERATE UNSIGNED PDF =====
            $fileName = $this->invoiceImportPdf($invoice->id);
            $file = storage_path('app/public/unsigned/import/' . $fileName);
        }   

        if ($request->type === 'X') {   

            $invoice = Extend::findOrFail($request->id);    

            // ===== PATH & FILE NAME =====
            $signedFile = $invoice->inv_no . '.pdf';
            $signedDiskPath = 'signed/extend/' . $signedFile; // untuk Storage
            $signedPublicUrl = asset('storage/' . $signedDiskPath); // untuk browser    

            // ✅ JIKA FILE SUDAH ADA → LANGSUNG RETURN
            if (Storage::disk('public')->exists($signedDiskPath)) {
                return response()->json([
                    'success' => true,
                    'signed'  => true,
                    'url'     => $signedPublicUrl,
                ]);
            }   

            // ===== GENERATE UNSIGNED PDF =====
            $fileName = $this->invoiceExtendPdf($invoice->id);
            $file = storage_path('app/public/unsigned/extend/' . $fileName);
        }   

        if ($request->type === 'E') {   

            $invoice = InvoiceExport::findOrFail($request->id);    

            // ===== PATH & FILE NAME =====
            $signedFile = $invoice->inv_no . '.pdf';
            $signedDiskPath = 'signed/export/' . $signedFile; // untuk Storage
            $signedPublicUrl = asset('storage/' . $signedDiskPath); // untuk browser    

            // ✅ JIKA FILE SUDAH ADA → LANGSUNG RETURN
            if (Storage::disk('public')->exists($signedDiskPath)) {
                return response()->json([
                    'success' => true,
                    'signed'  => true,
                    'url'     => $signedPublicUrl,
                ]);
            }   

            // ===== GENERATE UNSIGNED PDF =====
            $fileName = $this->invoiceExportPdf($invoice->id);
            $file = storage_path('app/public/unsigned/export/' . $fileName);
        }   

        if ($request->type === 'S') {   

            $invoice = Stevadooring::findOrFail($request->id);    

            // ===== PATH & FILE NAME =====
            $signedFile = $invoice->inv_no . '.pdf';
            $signedDiskPath = 'signed/stevadooring/' . $signedFile; // untuk Storage
            $signedPublicUrl = asset('storage/' . $signedDiskPath); // untuk browser    

            // ✅ JIKA FILE SUDAH ADA → LANGSUNG RETURN
            if (Storage::disk('public')->exists($signedDiskPath)) {
                return response()->json([
                    'success' => true,
                    'signed'  => true,
                    'url'     => $signedPublicUrl,
                ]);
            }   

            // ===== GENERATE UNSIGNED PDF =====
            $fileName = $this->invoiceStevadooringPdf($invoice->id);
            $file = storage_path('app/public/unsigned/stevadooring/' . $fileName);
        }   

        // ===== PROSES E-METERAI =====
        $token = $this->getToken(); 

        $send = $this->send($token, $file);
        // var_dump($send);
        // die();
        $invoice->update([
            'materai_id' => $send['id'],
        ]); 

        $serialNumber = $this->serialNumber($token, $fileName, $invoice);
        $invoice->update([
            'sn'            => $serialNumber['result']['sn'],
            'image_materai' => $serialNumber['result']['filenameQR'],
        ]); 

        $stamping = $this->stamping($invoice, $token);
        $urlFile = $stamping['urlFile'];    

        // ===== DOWNLOAD SIGNED PDF =====
        $this->download($invoice, $token, $urlFile, $request);    

        // ===== RETURN URL PDF =====
        return response()->json([
            'success' => true,
            'signed'  => true,
            'url'     => $signedPublicUrl,
        ]);
    }

    public function invoiceImportPdf($id)
    {
        $data['title'] = "Invoice Import"; 

        $data['invoice'] = Import::where('id', $id)->firstOrFail();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)
            ->orderBy('ctr_size', 'asc')
            ->get();    

        $invDetail = ImportDetail::where('inv_id', $id)
            ->whereNot('count_by', 'O')
            ->orderBy('count_by', 'asc')
            ->orderBy('kode', 'asc')
            ->get();    

        $data['invGroup'] = $invDetail->groupBy('ukuran');  

        $data['admin'] = 0;
        $adminDSK = ImportDetail::where('inv_id', $id)
            ->where('count_by', 'O')
            ->first();  

        if ($adminDSK) {
            $data['admin'] = $adminDSK->total;
        }   

        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);   

        // ⬇️ Generate PDF
        $pdf = Pdf::loadView('materai.import', $data)
            ->setPaper('A4', 'portrait');   

        // ⬇️ Nama file
        $filename = 'invoice_import_'.$data['invoice']->inv_no.'.pdf';   

        // ⬇️ Simpan ke storage/app/invoice
        Storage::put('public/unsigned/import/'.$filename, $pdf->output()); 

        return $filename;
    }

    public function invoiceExtendPdf($id)
    {
        $data['title'] = "Invoice";
        $data['invoice'] = Extend::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['item'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = ExtendDetail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDSK = ExtendDetail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDSK) {
            $data['admin'] = $adminDSK->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total); 

        // ⬇️ Generate PDF
        $pdf = Pdf::loadView('materai.extend', $data)
            ->setPaper('A4', 'portrait');   

        // ⬇️ Nama file
        $filename = 'invoice_extend_'.$data['invoice']->inv_no.'.pdf';   

        // ⬇️ Simpan ke storage/app/invoice
        Storage::put('public/unsigned/extend/'.$filename, $pdf->output()); 

        return $filename;
    }

    public function invoiceExportPdf($id)
    {
        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $data['singleCont'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->first();
        $invDetail = ExportDetail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDS = ExportDetail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDS) {
            $data['admin'] = $adminDS->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        // ⬇️ Generate PDF
        $pdf = Pdf::loadView('materai.export', $data)
            ->setPaper('A4', 'portrait');   

        // ⬇️ Nama file
        $filename = 'invoice_export_'.$data['invoice']->inv_no.'.pdf';   

        // ⬇️ Simpan ke storage/app/invoice
        Storage::put('public/unsigned/export/'.$filename, $pdf->output()); 

        return $filename;
    }

    public function invoiceStevadooringPdf($id)
    {
        $header = Stevadooring::where('id', $id)->first();
        switch ($header->rbm->tipe) {
            case 'I':
                $data['type'] = 'Import';
                break;
            case 'E':
                $data['type'] = 'Export';
                break;
            
            default:
                $data['type'] = ' ';
                break;
        }
        $data['title'] = 'Invoice Stevadooring ' . $header->ves_name . ' ' . $header->voy_out;
        $data['invoice'] = $header;
        if ($header->tambat_tongkak == 'Y') {
           $data['tongkak'] = TT::where('inv_id', $header->id)->get();
        //    dd($data['tongkak']);
        }
        if ($header->tambat_kapal == 'Y') {
            $data['tkapal'] = TK::where('inv_id', $header->id)->first();
        }
        if ($header->stevadooring == 'Y') {
            $data['stevadooring'] = STV::where('inv_id', $header->id)->whereNot('total', 0)->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
        }
        if ($header->shifting == 'Y') {
            $data['crane_dermaga'] = SFT::where('inv_id', $header->id)->where('crane', '=', 'D')->whereNot('total', 0)->orderBy('landing', 'asc')->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
            $data['crane_kapal'] = SFT::where('inv_id', $header->id)->where('crane', '=', 'K')->whereNot('total', 0)->orderBy('landing', 'asc')->orderBy('ctr_size', 'asc')->orderBy('ctr_status', 'asc')->get();
        }
        
        $data['terbilang'] = $this->terbilang($header->grand_total);

        // ⬇️ Generate PDF
        $pdf = Pdf::loadView('materai.stevadooring', $data)
            ->setPaper('A4', 'portrait');   

        // ⬇️ Nama file
        $filename = 'invoice_stevadooring_'.$data['invoice']->inv_no.'.pdf';   

        // ⬇️ Simpan ke storage/app/invoice
        Storage::put('public/unsigned/stevadooring/'.$filename, $pdf->output()); 

        return $filename;
    }

    public function getToken()
    {
        $payload = [
            'user' => $this->user,
            'password' => $this->password,
        ];
        // var_dump($payload);
        // die();
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                // 'Accept' => 'application/json',
            ])->post(
                // 'https://backendservicestg.e-meterai.co.id/api/users/login',
                'https://backendservice.e-meterai.co.id/api/users/login',
                $payload
            );
            // var_dump($response);
            // die;
            // Jika response gagal (HTTP != 2xx)
            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'status'  => $response->status(),
                    'message' => 'Request token gagal',
                    'response'=> $response->body(),
                ], $response->status());
            }

            // Ambil response JSON
            $result = $response->json();

            // return response()->json([
            //     'success' => true,
            //     'data'    => $result
            // ]);
            return $result['token'];

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function send($token, $file)
    {
        if (!file_exists($file)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ], 404);
        }   
        // var_dump($token, $file);
        // die();
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])
            ->attach(
                'file',                       // WAJIB nama: file
                fopen($file, 'r'),
                basename($file)
            )
            // ->post('https://fileuploadstg.e-meterai.co.id/uploaddoc2', [
            //     'token' => $token             // WAJIB ADA DI BODY
            ->post('https://fileupload.e-meterai.co.id/uploaddoc2', [
                'token' => $token             // WAJIB ADA DI BODY
            ]); 

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'status'  => $response->status(),
                    'message' => 'Upload gagal',
                    'response'=> $response->body(),
                ], $response->status());
            }   

            $result = $response->json();
            
            return $result;   

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function serialNumber($token, $fileName, $invoice)
    {
        $payload = [
            // 'idfile' => $invoice->materai_id,
            // "isUpload" => false,
            // "namadoc" => null,
            // "namafile" => (string) $fileName,
            // "nilaidoc" => "10000",
            // "namejidentitas" => null,
            // "noidentitas" => null,
            // "namedipungut" => null,
            // "snOnly" => false,
            // "nodoc" => "0",
            // "tgldoc" => Carbon::now('Asia/Jakarta')->format('Y-m-d')

            "idfile" => "$invoice->materai_id",
            "isUpload" => true,
            "namadoc" => "2",
            "namafile" => "$fileName",
            "nilaidoc" => "10000",
            "snOnly" => false,
            "nodoc" => "34",
            "tgldoc" => Carbon::now('Asia/Jakarta')->format('Y-m-d')
        ];
        // var_dump($payload, $token);
        // die();
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                // 'Accept' => 'application/json',
            ])->post(
                // 'https://stampv2stg.e-meterai.co.id/chanel/stampv2',$payload                
                'https://stampv2.e-meterai.co.id/chanel/stampv2',$payload                
            );
            // var_dump($response);
            // die;
            // Jika response gagal (HTTP != 2xx)
            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'status'  => $response->status(),
                    'response'=> $response->body(),
                ], $response->status());
            }

            // Ambil response JSON
            $result = $response->json();

            // return response()->json([
            //     'success' => true,
            //     'data'    => $result
            // ]);
            // $filename = '';   

        
            // Storage::put('public/unsigned/import/'.$filename, $pdf->output()); 
            return $result;

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function stamping($invoice, $token)
    {
        $payload = [
            'docId' => $invoice->materai_id,
            "certificatelevel" => "NOT_CERTIFIED",
            "dest" => "/sharefolder/final_$invoice->materai_id.pdf",
            "docpass" => "",
            "jwToken" => "$token", //JWT TOKEN
            "location" => "JAKARTA",
            "profileName" => "emeteraicertificateSigner",
            "reason" => "Akta Pejabat",
            "refToken" => "$invoice->sn", //SN NUMBER
            "spesimenPath" => "/sharefolder/$invoice->image_materai",
            "src" => "/sharefolder/doc_$invoice->materai_id.pdf",
            "visLLX" => 437,
            "visLLY" => 159,
            "visURX" => 537,
            "visURY" => 59,
            "visSignaturePage" => 1,
            "retryFlag" => "1"
        ];
        // var_dump($payload, $token);
        // die();
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                // 'Accept' => 'application/json',
            ])->post(
                // 'https://stampservicestg.e-meterai.co.id/keystamp/adapter/docSigningZ',$payload                
                'https://stampservice.e-meterai.co.id/keystamp/adapter/docSigningZ',$payload                
            );
            // var_dump($response);
            // die;
            // Jika response gagal (HTTP != 2xx)
            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'status'  => $response->status(),
                    'response'=> $response->body(),
                ], $response->status());
            }

            // Ambil response JSON
            $result = $response->json();

            // return response()->json([
            //     'success' => true,
            //     'data'    => $result
            // ]);
            // $filename = '';   

        
            // Storage::put('public/unsigned/import/'.$filename, $pdf->output()); 
            return $result;

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function download($invoice, $token, $urlFile, $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($urlFile);  

        if (!$response->successful()) {
            throw new \Exception('Gagal download file e-Meterai');
        }   

        // contoh nama file: INV-001_SIGNED.pdf
        $fileName = $invoice->inv_no.'.pdf';   

        if ($request->type === 'I') {
            Storage::disk('public')->put(
                'signed/import/'.$fileName,
                $response->body()
            );  
        }
        if ($request->type === 'X') {
            Storage::disk('public')->put(
                'signed/extend/'.$fileName,
                $response->body()
            );  
        }
        if ($request->type === 'E') {
            Storage::disk('public')->put(
                'signed/export/'.$fileName,
                $response->body()
            );  
        }
        if ($request->type === 'S') {
            Storage::disk('public')->put(
                'signed/stevadooring/'.$fileName,
                $response->body()
            );  
        }

        // optional: simpan ke DB
        // $invoice->update([
        //     'file_signed' => $fileName
        // ]); 

        return $fileName;
    }

    private function terbilang($number)
    {
        $x = abs($number);
        $angka = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

        $result = "";
        if ($x < 12) {
            $result = " " . $angka[$x];
        } elseif ($x < 20) {
            $result = $this->terbilang($x - 10) . " Belas";
        } elseif ($x < 100) {
            $result = $this->terbilang($x / 10) . " Puluh" . $this->terbilang($x % 10);
        } elseif ($x < 200) {
            $result = " Seratus" . $this->terbilang($x - 100);
        } elseif ($x < 1000) {
            $result = $this->terbilang($x / 100) . " Ratus" . $this->terbilang($x % 100);
        } elseif ($x < 2000) {
            $result = " Seribu" . $this->terbilang($x - 1000);
        } elseif ($x < 1000000) {
            $result = $this->terbilang($x / 1000) . " Ribu" . $this->terbilang($x % 1000);
        } elseif ($x < 1000000000) {
            $result = $this->terbilang($x / 1000000) . " Juta" . $this->terbilang($x % 1000000);
        } elseif ($x < 1000000000000) {
            $result = $this->terbilang($x / 1000000000) . " Milyar" . $this->terbilang(fmod($x, 1000000000));
        } elseif ($x < 1000000000000000) {
            $result = $this->terbilang($x / 1000000000000) . " Trilyun" . $this->terbilang(fmod($x, 1000000000000));
        }

        return $result;
    }
}
