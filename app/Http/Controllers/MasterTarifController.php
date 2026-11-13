<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderService as OS;
use App\Models\OSDetail;
use App\Models\MasterTarif as MT;
use App\Models\Customer;
use App\Models\Item;
use App\Models\VVoyage;
use App\Models\DOonline;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\uploadDO;
use App\Models\MasterUserInvoice as MUI;

use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\File;
use DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;


use Symfony\Component\Process\Process;


class MasterTarifController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function import()
    {
        $data['title'] = "Mater Tarif Import";
        $data ['orderService'] = OS::whereIn('ie', '=', ['I', 'X'])->get();
        $data ['masterTarif'] = MT::get();
        return view('billingSystem.import.master-tarif.main', $data);
    }

    public function orderService(Request $request)
    {
       

        $os = OS::create([
            'name'=>$request->name,
            'ie'=>$request->ie,
            'order'=>$request->order,
            'return_yn' => $request->return_yn,
            'depo_return' => $request->depo_return,
        ]);

        return redirect()->back()->with('success', 'Service ordered successfully.');
    }

    public function tarif($os)
    {
        $data['orderService'] = OS::where('id', $os)->first();
        $data['title'] = "Add Master Tarif " . $data['orderService']->name;
        
        return view('billingSystem.import.master-tarif.create', $data);
    }

    public function storeMT(Request $request)
    {
        $osId = $request->os_id;
        $os = OS::where('id', $osId)->first();

        if ($os) {
            $mt = MT::create([
                'os_id'=>$request->os_id,
                'os_name'=>$request->os_name,
                'ctr_size'=>$request->ctr_size,
                'm1'=>$request->m1,
                'm2'=>$request->m2,
                'm3'=>$request->m3,
                'lolo_full'=>$request->lolo_full,
                'lolo_empty'=>$request->lolo_empty,
                'pass_truck_masuk'=>$request->pass_truck_masuk,
                'pass_truck_keluar'=>$request->pass_truck_keluar,
                'admin'=>$request->admin,
                'pajak'=>$request->pajak,
                'paket_stripping'=>$request->paket_stripping,
                'pemindahan_petikemas'=>$request->pemindahan_petikemas,
                'jpb_extruck'=>$request->jpb_extruck,
                'sewa_crane'=>$request->sewa_crane,
                'cargo_dooring'=>$request->cargo_dooring,
                'paket_stuffing'=>$request->paket_stuffing,
                'create_by'=> Auth::user()->name,
                'created_at'=>Carbon::now(),
                
            ]);
            return redirect('/billing/import/master-tarif')->with('success', 'Tarif added successfully.');

        }else {
            return redirect()->back()->with('error', 'Something Wrong, Call the Admin.');
        }

    }

    public function EditImport($id)
    {
        $data['masterTarif'] = MT::where('id', $id)->first();
        $data['title'] = "Add Master Tarif " . $data['masterTarif']->os_name;
        
        return view('billingSystem.import.master-tarif.edit', $data);
    }

    public function updateMT(Request $request)
    {
        $id = $request->id;
        $mt = MT::where('id', $id)->first();

        if ($mt) {
            $mt->update([
                'os_id'=>$request->os_id,
                'os_name'=>$request->os_name,
                'ctr_size'=>$request->ctr_size,
                'm1'=>$request->m1,
                'm2'=>$request->m2,
                'm3'=>$request->m3,
                'lolo_full'=>$request->lolo_full,
                'lolo_empty'=>$request->lolo_empty,
                'pass_truck_masuk'=>$request->pass_truck_masuk,
                'pass_truck_keluar'=>$request->pass_truck_keluar,
                'admin'=>$request->admin,
                'pajak'=>$request->pajak,
                'paket_stripping'=>$request->paket_stripping,
                'pemindahan_petikemas'=>$request->pemindahan_petikemas,
                'jpb_extruck'=>$request->jpb_extruck,
                'sewa_crane'=>$request->sewa_crane,
                'cargo_dooring'=>$request->cargo_dooring,
                'paket_stuffing'=>$request->paket_stuffing,
                'update_by'=> Auth::user()->name,
            ]);
            return redirect('/billing/import/master-tarif')->with('success', 'Tarif added successfully.');

        }else {
            return redirect()->back()->with('error', 'Something Wrong, Call the Admin.');
        }
    }

    public function customer()
    {
        $data['title'] = "Customer";
        $data ['customer'] = Customer::get();
        return view('billingSystem.customer.main', $data);
    }

    public function addCust()
    {
        $data['title'] = "Customer Add";
        return view('billingSystem.customer.create', $data);


    }

    public function storeCust(Request $request)
    {
        $cust = Customer::create([
            'name'=>$request->name,
            'code'=>$request->code,
            'alamat'=>$request->alamat,
            'npwp'=>$request->npwp,
            'email'=>$request->email,
            'fax'=>$request->fax,
            'phone'=>$request->phone,
            'mapping_zahir'=>$request->mapping_zahir,
        ]);

        return redirect('/billing/customer')->with('success', 'Customer added successfully.');

    }

    public function editCust($id)
    {
        $data['cust'] = Customer::where('id', $id)->first();
        $data['title'] = "Customer Edit";


        return view('billingSystem.customer.edit', $data);
    }

    public function updateCust(Request $request)
    {
        $cust = Customer::where('id', $request->id)->first();
        if ($cust) {
                $cust->update([
                    'name'=>$request->name,
                    'code'=>$request->code,
                    'alamat'=>$request->alamat,
                    'npwp'=>$request->npwp,
                    'email'=>$request->email,
                    'fax'=>$request->fax,
                    'phone'=>$request->phone,
                    'mapping_zahir'=>$request->mapping_zahir,
                ]);

                return redirect('/billing/customer')->with('success', 'Customer update successfully.');

        }
        return redirect()->back()->with('error', 'Something Wrong, Call the Admin.');

    }

    public function doMain()
    {
        $data['title'] = "DO Online Check";
        $data['doOnline'] = DOonline::get();

        $data['vessels'] = VVoyage::orderBy('deparature_date', 'desc')->get();

        // dd($data['vessels']);
        return view('billingSystem.do.main', $data);
    }

    public function doData()
    {
        $doData = DOonline::orderBy('created_at', 'desc')->get();  

        return datatables()->of($doData)
            ->addIndexColumn()  
            ->addColumn('actions', function ($do) {
                $pdfDirectory = storage_path('app/public/do/' . $do->id);           

                $hasPdf = \Illuminate\Support\Facades\File::exists($pdfDirectory)
                    && count(\Illuminate\Support\Facades\File::files($pdfDirectory)) > 0;           

                $pdfButton = $hasPdf
                    ? '<a href="' . route('doFiles', $do->id) . '"
                          class="btn btn-info" target="_blank_">
                          File PDF
                       </a>'
                    : '<a href="' . route('doFiles', $do->id) . '"
                          class="btn btn-outline-info" target="_blank_">
                          File PDF (0)
                       </a>';           

                return '
                    <div class="d-flex flex-wrap gap-1">
                        <a href="/edit/doOnline/' . $do->id . '"
                           class="btn btn-warning">Edit</a>         

                        ' . $pdfButton . '          

                        <form id="deleteForm-' . $do->id . '"
                              action="' . route('deleteDo') . '"
                              method="post">
                            ' . csrf_field() . '
                            <input type="hidden" name="id" value="' . $do->id . '">
                            <button type="button"
                                    class="btn btn-outline-danger delete-btn"
                                    data-id="' . $do->id . '">
                                Delete
                            </button>
                        </form>
                    </div>';
            })
            ->addColumn('container_no', function ($do) {
                $doArray = json_decode($do->container_no, true);    

                return is_array($doArray)
                    ? implode(', ', $doArray)
                    : $do->container_no;
            })  
            ->addColumn('approve', function($do){
                if ($do->status == 'N') {
                    return '<button class="btn btn-success" data-id="'.$do->id.'" data-action="approve" onClick="approveButton(this)">Approve</button>';
                }else {
                    return '<button class="btn btn-danger" data-id="'.$do->id.'" data-action="unapprove" onClick="approveButton(this)">Batal Approve</button>';
                }
            })
            ->rawColumns(['actions', 'container_no', 'approve'])
            ->make(true);
    }
    

    
    public function doUpload(Request $request)
    {
        $path = $request->file('storedo');
        Excel::import(new uploadDO, $path->getRealPath(), null, 'Xls');
        if (Auth::user()->hasRole('customer')) {
            $url = '/customer-import/doOnline/index';
        }else {
            $url = '/billing/dock-DO';
        }
        return redirect($url)->with('success', 'Data berhasil diimpor.');
    }

    public function deleteDo(Request $request)
    {
        $do = DOonline::where('id', $request->id)->first();
        if ($do) {
            $do->delete();
            return redirect()->back()->with('success', 'Data Berhasil di Hapus.');
        }else {
            return redirect()->back()->with('error', 'Something Wrong, Call the Admin.');
        }
    }

    public function doEdit($id)
    {
        $do = DOonline::where('id', $id)->first();
        $data['title'] = "Edit DO ".$do->do_no;
        $data['do'] = $do;
        return view('billingSystem.do.edit', $data);
    }

    public function doUpdate(Request $request)
    {
        $do = DOonline::find($request->id); 

        if (!$do) {
            return redirect()->back()->with('error', 'Data DO tidak ditemukan.');
        }   

        try {
            DB::transaction(function () use ($request, $do) {
                $do->update([
                    'do_no'         => $request->do_no,
                    'container_no'  => trim($request->container_no),
                    'bl_no'         => $request->bl_no,
                    'expired'       => $request->expired,
                    'customer_code' => $request->customer_code,
                ]); 

                if ($request->hasFile('do_pdf')) {
                    $file = $request->file('do_pdf');   

                    if (!$file->isValid()) {
                        throw new \RuntimeException(
                            'File PDF gagal diunggah: ' . $file->getErrorMessage()
                        );
                    }   

                    $fileName = preg_replace(
                        '/[^A-Za-z0-9._-]/',
                        '_',
                        $file->getClientOriginalName()
                    );  

                    // Nama unik agar file lama tidak tertimpa.
                    $fileName = pathinfo($fileName, PATHINFO_FILENAME)
                        . '_' . now()->format('YmdHis')
                        . '.pdf';   

                    $path = $file->storeAs(
                        'do/' . $do->id,
                        $fileName,
                        'public'
                    );  

                    if (!$path) {
                        throw new \RuntimeException(
                            'PDF gagal disimpan ke storage.'
                        );
                    }
                }
            }); 

            return redirect()->back()->with(
                'success',
                'Data DO berhasil diperbarui.'
            );
        } catch (\Throwable $th) {
            report($th);    

            return redirect()->back()->with(
                'error',
                'Gagal memperbarui DO: ' . $th->getMessage()
            );
        }
    }

    public function createDoManual(Request $request)
    {
        $idKapal = $request->id_kapal;
        // dd($idKapal);
        $data['title'] = 'Create DO Online Manual';
        $data['items'] = Item::where('ves_id', $idKapal)->whereIn('ctr_intern_status', ['01', '02', '03'])->where('selected_do', 'N')->get();
        if (Auth::user()->hasRole('customer')) {
            $mui = MUI::where('user_id', Auth::user()->id)->get();
            $data['customers'] = Customer::whereIn('id', $mui->pluck('customer_id'))->get();
        }else {
            $data['customers'] = Customer::get();
        }

        // dd($request->all());

        return view('billingSystem.do.createManual', $data);
    }
    
    // public function parseDoPdf(Request $request)
    // {
    //     $request->validate([
    //         'do_pdf' => 'required|file|max:10240',
    //     ]); 

    //     $file = $request->file('do_pdf');   

    //     if (!$file || !$file->isValid()) {
    //         return response()->json([
    //             'message' => 'File PDF gagal diunggah atau tidak valid.',
    //         ], 422);
    //     }   

    //     // Validasi ekstensi dan signature PDF.
    //     if (
    //         strtolower($file->getClientOriginalExtension()) !== 'pdf' ||
    //         file_get_contents($file->getRealPath(), false, null, 0, 5) !== '%PDF-'
    //     ) {
    //         return response()->json([
    //             'message' => 'File harus berupa PDF yang valid.',
    //         ], 422);
    //     }   

    //     try {
    //         // 1. Ekstrak teks langsung dari PDF.
    //         $parser = new Parser();
    //         $pdf = $parser->parseFile($file->getRealPath());
    //         $text = $pdf->getText();    

    //         $text = str_replace("\0", '', $text);
    //         $text = preg_replace('/[ \t]+/', ' ', $text);   

    //         // 2. OCR jika teks PDF kosong atau informasi penting tidak lengkap.
    //         $upperText = strtoupper($text); 

    //         $hasDoOrBl = preg_match(
    //             '/(?:DO-[A-Z0-9-]+|DELIVERY\s+ORDER|\bNO[.,]?\s*BL\s*:|\bNOMOR\s+BL\s*:|\bB\s*\/?\s*L\s*NO\.?\s*:)/i',
    //             $text
    //         );  

    //         $hasContainer = preg_match(
    //             '/[A-Z]{4,5}\s*\d{7}/i',
    //             $upperText
    //         );  

    //         if (trim($text) === '' || !$hasDoOrBl || !$hasContainer) {
    //             $text = $this->extractTextWithOcr($file->getRealPath());
    //             $text = str_replace("\0", '', $text);
    //             $text = preg_replace('/[ \t]+/', ' ', $text);
    //         }   

    //         // Normalisasi huruf untuk pencocokan.
    //         $upperText = strtoupper($text); 

    //         // 3. Ambil nomor DO.
    //         $doNo = null;
    //         $blNo = null;   
    //         // var_dump($text);
    //         // Format Indo Container Lines: No. DO-SBYPTK96950.
    //         if (preg_match('/\bNo\.\s*(DO-[A-Z0-9-]+)/i', $text, $m)) {
    //             $doNo = trim($m[1]);
    //         }   

    //         // Format Lintas Kumala: DELIVERY ORDER (DO), lalu nomor DO.
    //         if (
    //             !$doNo &&
    //             preg_match(
    //                 '/DELIVERY\s+ORDER\s*\(DO\)\s*[\r\n]+\s*(DO[A-Z0-9-]+)/i',
    //                 $text,
    //                 $m
    //             )
    //         ) {
    //             $doNo = trim($m[1]);
    //         }   

    //         // Format scan dengan nomor DO berupa angka.
    //         if (
    //             !$doNo &&
    //             preg_match('/^\s*(\d{5,})\s*$/m', $text, $m)
    //         ) {
    //             $doNo = trim($m[1]);
    //         }   

    //         // 4. Ambil nomor BL.
    //         if (preg_match('/\bNo[.,]?\s*BL\s*[:,]?\s*([A-Z0-9-]+)/i', $text, $m)) {
    //             $blNo = trim($m[1]);
    //         }           

    //         // Format Indo Container Lines: JKTPTK96521Nomor BL :
    //         if (!$blNo && preg_match('/([A-Z0-9-]+)\s*Nomor\s*BL\s*:/i', $text, $m)) {
    //             $blNo = trim($m[1]);
    //         }           

    //         // Format Samudera: B/L No., BIL No.: atau B/L No.:.
    //         if (!$blNo && preg_match('/\bB\s*[\/I|]?\s*L\s*No\.?\s*[:,]?\s*([A-Z0-9-]+)/i', $text, $m)) {
    //             $blNo = trim($m[1]);
    //         }           

    //         // Format alternatif: Nomor BL : SUBPNKJ5762601.
    //         if (!$blNo && preg_match('/Nomor\s*BL\s*:\s*([A-Z0-9-]+)/i', $text, $m)) {
    //             $blNo = trim($m[1]);
    //         }           

    //         // Jika nomor DO tidak tersedia, gunakan nomor BL.
    //         if (!$doNo && $blNo) {
    //             $doNo = $blNo;
    //         }   

    //         // Format alternatif: Nomor BL : SUBPNKJ5762601.
    //         if (
    //             !$blNo &&
    //             preg_match(
    //                 '/Nomor\s*BL\s*:\s*([A-Z0-9-]+)/i',
    //                 $text,
    //                 $m
    //             )
    //         ) {
    //             $blNo = trim($m[1]);
    //         }   

    //         // Jika nomor DO tidak ada, gunakan nomor BL.
    //         if (!$doNo && $blNo) {
    //             $doNo = $blNo;
    //         }   

    //         // 5. Ambil nomor container.
    //         preg_match_all(
    //             '/([A-Z]{4,5})\s*(\d{7})/i',
    //             $upperText,
    //             $containerMatches,
    //             PREG_SET_ORDER
    //         );  

    //         $containers = [];   

    //         foreach ($containerMatches as $match) {
    //             $prefix = strtoupper($match[1]);
    //             $number = $match[2];    

    //             // Koreksi kesalahan OCR yang diketahui.
    //             if ($prefix === 'IRFSU') {
    //                 $prefix = 'RFSU';
    //             } elseif ($prefix === 'NYIKU') {
    //                 $prefix = 'NYKU';
    //             }
                
    //             if ($prefix === 'TANK') {
    //                 continue;
    //             }
                
    //             if ($prefix === 'CUBE') {
    //                 continue;
    //             }

    //             $container = $prefix . $number; 

    //             // Nomor container standar: 4 huruf + 7 angka.
    //             if (preg_match('/^[A-Z]{4}\d{7}$/', $container)) {
    //                 $containers[] = $container;
    //             }
    //         }   

    //         $containers = array_values(array_unique($containers));  

    //         // 6. Ambil tanggal expired.
    //         $expired = null;    

    //         // Format Lintas Kumala:
    //         // Storage Masa Berlaku : 22.10.2026 s/d 26.10.2026.
    //         if (
    //             preg_match(
    //                 '/Storage\s+Masa\s+Berlaku\s*:?\s*(\d{2}[.\/-]\d{2}[.\/-]\d{4})\s*s\s*\/?\s*d\s*(\d{2}[.\/-]\d{2}[.\/-]\d{4})/i',
    //                 $text,
    //                 $m
    //             )
    //         ) {
    //             $date = str_replace(['.', '/'], '-', $m[2]);
    //             $parsedDate = \DateTime::createFromFormat('!d-m-Y', $date); 

    //             if ($parsedDate && $parsedDate->format('d-m-Y') === $date) {
    //                 $expired = $parsedDate->format('Y-m-d');
    //             }
    //         }   

    //         // Format Samudera: DO BERLAKU S/D: 21-10-2026.
    //         if (
    //             $expired === null &&
    //             preg_match(
    //                 '/DO\s+BERLAKU\s+S.{0,3}D\s*:?\s*(\d{2}[\/-]\d{2}[\/-]\d{4})/i',
    //                 $text,
    //                 $m
    //             )
    //         ) {
    //             $date = str_replace('/', '-', $m[1]);
    //             $parsedDate = \DateTime::createFromFormat('!d-m-Y', $date); 

    //             if ($parsedDate && $parsedDate->format('d-m-Y') === $date) {
    //                 $expired = $parsedDate->format('Y-m-d');
    //             }
    //         }   

    //         // Format Indo Container Lines: Batas Storage di tabel.
    //         if (
    //             $expired === null &&
    //             preg_match(
    //                 '/Batas\s+Storage[\s\S]{0,100}?(\d{2}[\/-]\d{2}[\/-]\d{4})/i',
    //                 $text,
    //                 $m
    //             )
    //         ) {
    //             $date = str_replace('-', '/', $m[1]);
    //             $parsedDate = \DateTime::createFromFormat('!d/m/Y', $date); 

    //             if ($parsedDate && $parsedDate->format('d/m/Y') === $date) {
    //                 $expired = $parsedDate->format('Y-m-d');
    //             }
    //         }   

    //         // 7. Validasi hasil ekstraksi.
    //         if (!$doNo || !$blNo) {
    //             \Log::warning('Nomor DO/BL tidak ditemukan', [
    //                 'file' => $file->getClientOriginalName(),
    //                 'do_no' => $doNo,
    //                 'bl_no' => $blNo,
    //                 'text' => $text,
    //             ]); 

    //             return response()->json([
    //                 'message' => 'Nomor DO atau BL tidak ditemukan. Periksa kualitas scan atau format PDF.',
    //             ], 422);
    //         }   

    //         if (empty($containers)) {
    //             \Log::warning('Nomor container tidak ditemukan', [
    //                 'file' => $file->getClientOriginalName(),
    //                 'text' => $text,
    //             ]); 

    //             return response()->json([
    //                 'message' => 'Nomor container tidak ditemukan pada PDF.',
    //             ], 422);
    //         }   

    //         // 8. Response.
    //         return response()->json([
    //             'do_no' => $doNo,
    //             'bl_no' => $blNo,
    //             'expired' => $expired,
    //             'containers' => $containers,
    //         ]);
    //     } catch (\Throwable $e) {
    //         report($e); 

    //         return response()->json([
    //             'message' => 'PDF gagal dibaca. Pastikan PDF tidak rusak dan OCR sudah terpasang jika file berupa scan.',
    //         ], 422);
    //     }
    // }


    // public function parseDoPdf(Request $request)    
    // {   
    //     $request->validate([    
    //         'do_pdf' => 'required|file|max:10240',  
    //     ]); 

    //     $file = $request->file('do_pdf');   

    //     if (!$file || !$file->isValid()) {  
    //         return response()->json([   
    //             'message' => 'File PDF gagal diunggah atau tidak valid.',   
    //         ], 422);    
    //     }   

    //     // Validasi ekstensi dan signature PDF. 
    //     if (    
    //         strtolower($file->getClientOriginalExtension()) !== 'pdf' ||    
    //         file_get_contents($file->getRealPath(), false, null, 0, 5) !== '%PDF-'  
    //     ) { 
    //         return response()->json([   
    //             'message' => 'File harus berupa PDF yang valid.',   
    //         ], 422);    
    //     }   

    //     try {   
    //         // 1. Ekstrak teks langsung dari PDF.   
    //         $parser = new Parser(); 
    //         $pdf = $parser->parseFile($file->getRealPath());    
    //         $text = $pdf->getText();    

    //         $text = str_replace("\0", '', $text);   
    //         $text = preg_replace('/[ \t]+/', ' ', $text);   

    //         // 2. OCR jika teks PDF kosong atau informasi penting tidak lengkap.    
    //         $upperText = strtoupper($text); 

    //         $hasDoOrBl = preg_match(    
    //             '/(?:DO-[A-Z0-9-]+|DELIVERY\s+ORDER|\bNO[.,]?\s*BL\s*:|\bNOMOR\s+BL\s*:|\bB\s*\/?\s*L\s*NO\.?\s*:)/i',  
    //             $text   
    //         );  

    //         $hasContainer = preg_match( 
    //             '/[A-Z]{4,5}\s*\d{7}/i',    
    //             $upperText  
    //         );  

    //         if (trim($text) === '' || !$hasDoOrBl || !$hasContainer) {  
    //             $text = $this->extractTextWithOcr($file->getRealPath());    
    //             $text = str_replace("\0", '', $text);   
    //             $text = preg_replace('/[ \t]+/', ' ', $text);   
    //         }   

    //         // Normalisasi huruf untuk pencocokan.  
    //         $upperText = strtoupper($text); 

    //         // 3. Ambil nomor DO dan BL.    
    //         $doNo = null;   
    //         $blNo = null;   

    //         // Normalisasi untuk pencocokan.    
    //         $normalizedText = strtoupper($text);    
            
    //         // A. Ambil nomor DO setelah DELIVERY ORDER (DO).   
    //         if (preg_match( 
    //             '/DELIVERY\s+ORDER\s*\(?DO\)?[\r\n:]\s*(DO[A-Z0-9-]+)/i',   
    //             $text,  
    //             $m  
    //         )) {    
    //             $doNo = trim($m[1]);    
    //         }   

    //         // Fallback: cari nomor DO yang berdiri sendiri.    
    //         if (!$doNo && preg_match(   
    //             '/\b(DO[A-Z0-9-]{5,})\b/i', 
    //             $text,  
    //             $m  
    //         )) {    
    //             $doNo = strtoupper(trim($m[1]));    
    //         }   

    //         // A2. Format DO tanpa awalan DO.   
    //         // Contoh:  
    //         // DELIVERY ORDER (DO)  
    //         // ...  
    //         // ICLK063AJKTPNK2606269    
    //         if (!$doNo && preg_match(   
    //             '/\bB\s*\/\s*L\s*(?:No\.?|Number)?\s*[:.]?\s*([A-Z0-9-]{5,})/i', 
    //             $text,  
    //             $m  
    //         )) {    
    //             $candidate = strtoupper(trim($m[1]));   

    //             if (!preg_match(    
    //                 '/^(DO|NOMOR|KONTAINER|BARANG|HARAP|DENGAN)$/i',    
    //                 $candidate  
    //             )) {    
    //                 $doNo = $candidate; 
    //             }   
    //         }   

    //         // B. Format normal: No. BL : SUBPNKIDL352602   
    //         if (preg_match( 
    //             '/\bNo\.?\s*BL\s*[:.]?\s*([A-Z0-9-]{5,})/i',    
    //             $text,  
    //             $m  
    //         )) {    
    //             $blNo = strtoupper(trim($m[1]));    
    //         }   

    //         // C. Format OCR terbalik:
    //         // :SUBPNKIDL352602No. BL
    //         if (!$blNo && preg_match(
    //             '/([A-Z]{3,}[A-Z0-9-]*\d{4,})\s*No\.?\s*BL\b/i',
    //             $text,
    //             $m
    //         )) {
    //             $blNo = strtoupper(trim($m[1]));
    //         }           

    //         // C2. Format Indo Container Lines:
    //         // JKTPTK93724Nomor BL :
    //         if (!$blNo && preg_match(
    //             '/([A-Z0-9-]{5,})\s*Nomor\s+BL\s*[:.]?/i',
    //             $text,
    //             $m
    //         )) {
    //             $blCandidate = strtoupper(trim($m[1]));         

    //             // Jika nomor BL merupakan nomor DO tanpa prefix DO-,
    //             // samakan nomor BL dengan nomor DO.
    //             if ($doNo && $blCandidate === preg_replace('/^DO-?/i', '', $doNo)) {
    //                 $blNo = $doNo;
    //             } else {
    //                 $blNo = $blCandidate;
    //             }
    //         }           

    //         // D. Format alternatif: Nomor BL : SUBPNKIDL352602
    //         if (!$blNo && preg_match(
    //             '/Nomor\s*BL\s*[:.]?\s*([A-Z0-9-]{5,})/i',
    //             $text,
    //             $m
    //         )) {
    //             $blNo = strtoupper(trim($m[1]));
    //         }

    //         // F. Format Samudera:  
    //         // B/L No.: ICLK063AJKTPNK2606269   
    //         if (!$blNo && preg_match(   
    //             '/\bB\s*\/\s*L\s*(?:No\.?|Number)?\s*[:.]?\s*([A-Z0-9-]{5,})/i',    
    //             $text,  
    //             $m  
    //         )) {    
    //             $blNo = strtoupper(trim($m[1]));    
    //         }   

    //         // E. Jika nomor DO tidak ditemukan, gunakan BL.    
    //         if (!$doNo && $blNo) {  
    //             $doNo = $blNo;  
    //         }   

    //         // Jika nomor BL tidak ditemukan tetapi DO tersedia,    
    //         // pertahankan fallback yang sama.  
    //         // Tidak mengisi BL dengan DO agar validasi tetap akurat.   

    //         // 5. Ambil nomor container.    
    //         preg_match_all( 
    //             '/([A-Z]{4,5})\s*(\d{7})/i',    
    //             $upperText, 
    //             $containerMatches,  
    //             PREG_SET_ORDER  
    //         );  

    //         $containers = [];   

    //         foreach ($containerMatches as $match) { 
    //             $prefix = strtoupper($match[1]);    
    //             $number = $match[2];    

    //             // Koreksi kesalahan OCR yang diketahui.    
    //             if ($prefix === 'IRFSU') {  
    //                 $prefix = 'RFSU';   
    //             } elseif ($prefix === 'NYIKU') {    
    //                 $prefix = 'NYKU';   
    //             }   

    //             if ($prefix === 'TANK') {   
    //                 continue;   
    //             }   

    //             if ($prefix === 'CUBE') {   
    //                 continue;   
    //             }   

    //             $container = $prefix . $number; 

    //             // Nomor container standar: 4 huruf + 7 angka.  
    //             if (preg_match('/^[A-Z]{4}\d{7}$/', $container)) {  
    //                 $containers[] = $container; 
    //             }   
    //         }   

    //         $containers = array_values(array_unique($containers));  

    //         // 6. Ambil tanggal expired.    
    //         $expired = null;    

    //         // Format Lintas Kumala:    
    //         // Storage Masa Berlaku : 22.10.2026 s/d 26.10.2026.    
    //         if (    
    //             preg_match( 
    //                 '/Storage\s+Masa\s+Berlaku\s*:?\s*(\d{2}[.\/-]\d{2}[.\/-]\d{4})\s*s\s*\/?\s*d\s*(\d{2}[.\/-]\d{2}[.\/-]\d{4})/i',   
    //                 $text,  
    //                 $m  
    //             )   
    //         ) { 
    //             $date = str_replace(['.', '/'], '-', $m[2]);    
    //             $parsedDate = \DateTime::createFromFormat('!d-m-Y', $date); 

    //             if ($parsedDate && $parsedDate->format('d-m-Y') === $date) {    
    //                 $expired = $parsedDate->format('Y-m-d');    
    //             }   
    //         }   

    //         // Format Samudera: DO BERLAKU S/D: 21-10-2026. 
    //         if (    
    //             $expired === null &&    
    //             preg_match( 
    //                 '/DO\s+BERLAKU\s+S.{0,3}D\s*:?\s*(\d{2}[\/-]\d{2}[\/-]\d{4})/i',    
    //                 $text,  
    //                 $m  
    //             )   
    //         ) { 
    //             $date = str_replace('/', '-', $m[1]);   
    //             $parsedDate = \DateTime::createFromFormat('!d-m-Y', $date); 

    //             if ($parsedDate && $parsedDate->format('d-m-Y') === $date) {    
    //                 $expired = $parsedDate->format('Y-m-d');    
    //             }   
    //         }   

    //         // Format Indo Container Lines: Batas Storage di tabel. 
    //         if (    
    //             $expired === null &&    
    //             preg_match( 
    //                 '/Batas\s+Storage[\s\S]{0,100}?(\d{2}[\/-]\d{2}[\/-]\d{4})/i',  
    //                 $text,  
    //                 $m  
    //             )   
    //         ) { 
    //             $date = str_replace('-', '/', $m[1]);   
    //             $parsedDate = \DateTime::createFromFormat('!d/m/Y', $date); 

    //             if ($parsedDate && $parsedDate->format('d/m/Y') === $date) {    
    //                 $expired = $parsedDate->format('Y-m-d');    
    //             }   
    //         }   

    //         // 7. Validasi hasil ekstraksi. 
    //         if (!$doNo || !$blNo) { 
    //             \Log::warning('Nomor DO/BL tidak ditemukan', [  
    //                 'file' => $file->getClientOriginalName(),   
    //                 'do_no' => $doNo,   
    //                 'bl_no' => $blNo,   
    //                 'text' => $text,    
    //             ]); 

    //             return response()->json([   
    //                 'message' => 'Nomor DO atau BL tidak ditemukan. Periksa kualitas scan atau format PDF.',    
    //             ], 422);    
    //         }   

    //         if (empty($containers)) {   
    //             \Log::warning('Nomor container tidak ditemukan', [  
    //                 'file' => $file->getClientOriginalName(),   
    //                 'text' => $text,    
    //             ]); 

    //             return response()->json([   
    //                 'message' => 'Nomor container tidak ditemukan pada PDF.',   
    //             ], 422);    
    //         }   

    //         // 8. Response. 
    //         return response()->json([   
    //             'do_no' => $doNo,   
    //             'bl_no' => $blNo,   
    //             'expired' => $expired,  
    //             'containers' => $containers,    
    //         ]); 

    //     } catch (\Throwable $e) {   
    //         report($e); 

    //         return response()->json([   
    //             'message' => 'PDF gagal dibaca. Pastikan PDF tidak rusak dan OCR sudah terpasang jika file berupa scan.',   
    //         ], 422);    
    //     }   
    // }   

    
    public function parseDoPdf(Request $request)    
    {   
        $request->validate([    
            'do_pdf' => 'required|file|max:10240',  
        ]); 
        
        $file = $request->file('do_pdf');   
        
        if (!$file || !$file->isValid()) {  
            return response()->json([   
                'message' => 'File PDF gagal diunggah atau tidak valid.',   
            ], 422);    
        }   
        
        // Validasi ekstensi dan signature PDF. 
        if (    
            strtolower($file->getClientOriginalExtension()) !== 'pdf' ||    
            file_get_contents($file->getRealPath(), false, null, 0, 5) !== '%PDF-'  
        ) { 
            return response()->json([   
                'message' => 'File harus berupa PDF yang valid.',   
            ], 422);    
        }   
        
        try {   
            // 1. Ekstrak teks langsung dari PDF.   
            $parser = new Parser(); 
            $pdf = $parser->parseFile($file->getRealPath());    
            $text = $pdf->getText();    
        
            $text = str_replace("\0", '', $text);   
            $text = preg_replace('/[ \t]+/', ' ', $text);   
        
            // 2. OCR jika teks PDF kosong atau informasi penting tidak lengkap.    
            $upperText = strtoupper($text); 
        
            $hasDoOrBl = preg_match(    
                '/(?:DO-[A-Z0-9-]+|DELIVERY\s+ORDER|\b(?:B[\/I.]?\s*L|BIL)\s*(?:NO\.?|NUMBER)\s*:|\bNOMOR\s+BL\s*:)/i', 
                $text   
            );  
        
            $hasContainer = preg_match( 
                '/[A-Z]{3,4}\s*\d{7}/i',    
                $upperText  
            );  
        
            if (trim($text) === '' || !$hasDoOrBl || !$hasContainer) {  
                $ocrText = $this->extractTextWithOcr($file->getRealPath()); 
        
                $ocrText = str_replace("\0", '', $ocrText); 
                $ocrText = preg_replace('/[ \t]+/', ' ', $ocrText); 
        
                // Gabungkan teks asli dan OCR agar informasi tidak hilang. 
                $text = $text . "\n" . $ocrText;    
            }   
        
            // Normalisasi teks.    
            $upperText = strtoupper($text); 
        
            $normalizedText = preg_replace('/\s+/', ' ', $upperText);   
        
            // Normalisasi karakter OCR yang sering tertukar.   
            // Tidak melakukan penggantian global agar nomor tidak berubah. 
        
            // 3. Ambil nomor DO dan BL.    
            $doNo = null;   
            $blNo = null;   
        
            // A. Nomor DO dengan awalan DO.    
            if (preg_match( 
                '/\b(DO[-\s]?[A-Z0-9-]{5,})\b/i',   
                $normalizedText,    
                $m  
            )) {    
                $doNo = strtoupper(preg_replace('/\s+/', '', $m[1]));   
            }   
        
            // B. Nomor DO berupa angka.    
            // Contoh Samudera: 0046091.    
            if (!$doNo) {   
                if (preg_match( 
                    '/^\s*(\d{7,10})\s*$/m',    
                    $text,  
                    $m  
                )) {    
                    $doNo = trim($m[1]);    
                }   
            }   
        
            // C. Nomor BL format Samudera. 
            // B/L No.: ICLK063AJKTPNK2606271   
            // BIL No,: ICLKO63AJKTPNK260627 1  
            if (preg_match( 
                '/\bB\s*[\/I|.]?\s*L\s*(?:NO\.?|NUMBER)\s*[:.,]?\s*([A-Z0-9][A-Z0-9\s-]{8,})/i',    
                $normalizedText,    
                $m  
            )) {    
                $candidate = strtoupper($m[1]); 
        
                // Hapus spasi dan karakter pemisah.    
                $candidate = preg_replace('/[\s\-:.,]+/', '', $candidate);  
        
                // Ambil kandidat nomor BL. 
                if (preg_match( 
                    '/^([A-Z0-9]{10,30})/', 
                    $candidate, 
                    $blMatch    
                )) {    
                    $blNo = $blMatch[1];    
                }   
            }   
        
            // D. Format normal: No. BL : SUBPNKIDL352602   
            if (!$blNo && preg_match(   
                '/\bNO\.?\s*BL\s*[:.]?\s*([A-Z0-9][A-Z0-9\s-]{5,})/i',  
                $normalizedText,    
                $m  
            )) {    
                $candidate = preg_replace('/[\s\-:.,]+/', '', $m[1]);   
        
                if (preg_match('/^([A-Z0-9]{5,30})/', $candidate, $blMatch)) {  
                    $blNo = $blMatch[1];    
                }   
            }   
        
            // E. Format OCR terbalik.  
            // :SUBPNKIDL352602 No. BL  
            if (!$blNo && preg_match(   
                '/([A-Z]{3,}[A-Z0-9-]*\d{4,})\s*NO\.?\s*BL\b/i',    
                $normalizedText,    
                $m  
            )) {    
                $blNo = strtoupper( 
                    preg_replace('/[\s\-]+/', '', $m[1])    
                );  
            }   
        
            // F. Format Nomor BL.  
            if (!$blNo && preg_match(   
                '/NOMOR\s+BL\s*[:.]?\s*([A-Z0-9-]{5,})/i',  
                $normalizedText,    
                $m  
            )) {    
                $blNo = strtoupper(trim($m[1]));    
            }   
        
            // G. Fallback nomor BL sebagai nomor DO.   
            if (!$doNo && $blNo) {  
                $doNo = $blNo;  
            }   
        
            // 5. Ambil nomor container.    
            $containers = [];   
        
            // Cari nomor container standar.    
            preg_match_all( 
                '/\b([A-Z]{4})\s*(\d{7})\b/i',  
                $upperText, 
                $containerMatches,  
                PREG_SET_ORDER  
            );  
        
            foreach ($containerMatches as $match) { 
                $prefix = strtoupper($match[1]);    
                $number = $match[2];    
        
                // Koreksi kesalahan OCR yang diketahui.    
                if ($prefix === 'IRFSU') {  
                    $prefix = 'RFSU';   
                } elseif ($prefix === 'NYIKU') {    
                    $prefix = 'NYKU';   
                }   
        
                if ($prefix === 'TANK' || $prefix === 'CUBE') { 
                    continue;   
                }   
        
                $container = $prefix . $number; 
        
                if (preg_match('/^[A-Z]{4}\d{7}$/', $container)) {  
                    $containers[] = $container; 
                }   
            }   
        
            // Tambahan: OCR terkadang menghilangkan huruf pertama. 
            // Contoh TCLU3904123 terbaca CLU3904123.   
            if (preg_match_all( 
                '/\bCLU\s*(\d{7})\b/i', 
                $upperText, 
                $partialMatches,    
                PREG_SET_ORDER  
            )) {    
                foreach ($partialMatches as $match) {   
                    $containers[] = 'TCLU' . $match[1]; 
                }   
            }   
        
            $containers = array_values(array_unique($containers));  
        
            // 6. Ambil tanggal expired.    
            $expired = null;    
        
            // Format Lintas Kumala.    
            if (    
                preg_match( 
                    '/Storage\s+Masa\s+Berlaku\s*:?\s*(\d{2}[.\/-]\d{2}[.\/-]\d{4})\s*s\s*\/?\s*d\s*(\d{2}[.\/-]\d{2}[.\/-]\d{4})/i',   
                    $text,  
                    $m  
                )   
            ) { 
                $date = str_replace(['.', '/'], '-', $m[2]);    
        
                $parsedDate = \DateTime::createFromFormat('!d-m-Y', $date); 
        
                if ($parsedDate && $parsedDate->format('d-m-Y') === $date) {    
                    $expired = $parsedDate->format('Y-m-d');    
                }   
            }   
        
            // Format Samudera. 
            // DO BERLAKU S/D: 22-11-2026   
            if (    
                $expired === null &&    
                preg_match( 
                    '/DO\s+BERLAKU\s+S.{0,3}D\s*:?\s*(\d{2}[-\/]\d{2}[-\/]\d{4})/i',    
                    $text,  
                    $m  
                )   
            ) { 
                $date = str_replace('/', '-', $m[1]);   
        
                $parsedDate = \DateTime::createFromFormat('!d-m-Y', $date); 
        
                if ($parsedDate && $parsedDate->format('d-m-Y') === $date) {    
                    $expired = $parsedDate->format('Y-m-d');    
                }   
            }   
        
            // Format Indo Container Lines. 
            if (    
                $expired === null &&    
                preg_match( 
                    '/Batas\s+Storage[\s\S]{0,100}?(\d{2}[\/-]\d{2}[\/-]\d{4})/i',  
                    $text,  
                    $m  
                )   
            ) { 
                $date = str_replace('-', '/', $m[1]);   
        
                $parsedDate = \DateTime::createFromFormat('!d/m/Y', $date); 
        
                if ($parsedDate && $parsedDate->format('d/m/Y') === $date) {    
                    $expired = $parsedDate->format('Y-m-d');    
                }   
            }   
        
            // 7. Validasi hasil ekstraksi. 
            if (!$doNo || !$blNo) { 
                \Log::warning('Nomor DO/BL tidak ditemukan', [  
                    'file' => $file->getClientOriginalName(),   
                    'do_no' => $doNo,   
                    'bl_no' => $blNo,   
                    'text' => $text,    
                ]); 
        
                return response()->json([   
                    'message' => 'Nomor DO atau BL tidak ditemukan. Periksa kualitas scan atau format PDF.',    
                ], 422);    
            }   
        
            if (empty($containers)) {   
                \Log::warning('Nomor container tidak ditemukan', [  
                    'file' => $file->getClientOriginalName(),   
                    'text' => $text,    
                ]); 
        
                return response()->json([   
                    'message' => 'Nomor container tidak ditemukan pada PDF.',   
                ], 422);    
            }   
        
            // 8. Response. 
            return response()->json([   
                'do_no' => $doNo,   
                'bl_no' => $blNo,   
                'expired' => $expired,  
                'containers' => $containers,    
            ]); 
        
        } catch (\Throwable $e) {   
            report($e); 
        
            return response()->json([   
                'message' => 'PDF gagal dibaca. Pastikan PDF tidak rusak dan OCR sudah terpasang jika file berupa scan.',   
            ], 422);    
        }   
    }   




    
    private function extractTextWithOcr(string $pdfPath): string
    {
        $tempDir = storage_path('app/temp/do-ocr-' . uniqid('', true)); 

        if (!mkdir($tempDir, 0755, true) && !is_dir($tempDir)) {
            throw new \RuntimeException('Gagal membuat folder sementara OCR.');
        }   

        try {
            $prefix = $tempDir . '/page';   

            // 1. Konversi PDF menjadi gambar dengan resolusi tinggi.
            $convert = new Process([
                'pdftoppm',
                '-r', '300',
                '-png',
                $pdfPath,
                $prefix,
            ]); 

            $convert->setTimeout(180);
            $convert->mustRun();    

            $images = glob($prefix . '-*.png') ?: [];
            sort($images, SORT_NATURAL);    

            if (empty($images)) {
                throw new \RuntimeException(
                    'Gambar halaman PDF tidak berhasil dibuat.'
                );
            }   

            $text = ''; 

            foreach ($images as $image) {
                $ocrImage = $image; 

                // 2. Preprocessing gambar jika ImageMagick tersedia.
                // Jika gagal, gunakan gambar asli.
                $processedImage = $tempDir . '/processed-' . basename($image);  

                try {
                    $preprocess = new Process([
                        'magick',
                        $image,
                        '-colorspace', 'Gray',
                        '-normalize',
                        '-sharpen', '0x1',
                        $processedImage,
                    ]); 

                    $preprocess->setTimeout(60);
                    $preprocess->mustRun(); 

                    if (is_file($processedImage) && filesize($processedImage) > 0) {
                        $ocrImage = $processedImage;
                    }
                } catch (\Throwable $e) {
                    // ImageMagick tidak tersedia atau gagal.
                    // Lanjutkan OCR menggunakan gambar asli.
                }   

                // 3. OCR dengan beberapa mode segmentasi halaman.
                $results = [];  

                foreach ([6, 11] as $psm) {
                    $ocr = new Process([
                        'tesseract',
                        $ocrImage,
                        'stdout',
                        '-l', 'eng',
                        '--psm', (string) $psm,
                    ]); 

                    $ocr->setTimeout(180);
                    $ocr->mustRun();    

                    $results[$psm] = trim($ocr->getOutput());
                }   

                // PSM 6 lebih cocok sebagai hasil utama untuk dokumen
                // dengan susunan tabel yang relatif teratur.
                $pageText = $results[6] ?? '';  

                // Tambahkan baris dari PSM 11 jika belum terbaca
                // oleh PSM 6, khususnya nomor container.
                $lines = preg_split('/\R/', $pageText) ?: [];
                $knownLines = [];   

                foreach ($lines as $line) {
                    $line = trim($line);    

                    if ($line !== '') {
                        $knownLines[] = preg_replace('/\s+/', '', strtoupper($line));
                    }
                }   

                $extraLines = preg_split('/\R/', $results[11] ?? '') ?: []; 

                foreach ($extraLines as $line) {
                    $line = trim($line);    

                    if ($line === '') {
                        continue;
                    }   

                    $normalizedLine = preg_replace('/\s+/', '', strtoupper($line)); 

                    // Hindari menambahkan baris yang sama dua kali.
                    if (!in_array($normalizedLine, $knownLines, true)) {
                        $pageText .= "\n" . $line;
                        $knownLines[] = $normalizedLine;
                    }
                }   

                $text .= "\n" . $pageText;
            }   

            return trim($text);
        } finally {
            // 4. Bersihkan seluruh file sementara.
            foreach (glob($tempDir . '/*') ?: [] as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }   

            @rmdir($tempDir);
        }
    }




    public function postManual(Request $request)
    {
        // dd($request->all());

        $oldDo = DOonline::where('do_no', $request->do_no)->first();
        if ($oldDo) {
            return redirect()->back()->with('error', 'Nomor Do telah digunakan');
        }

        try {
            $containers = $request->container;

            // Pastikan nilai container tidak kosong dan dalam bentuk array
            if (empty($containers) || !is_array($containers)) {
                return redirect()->back()->with('error', 'Pilih minimal satu container.');
            }
            
            if ($request->expired <= Carbon::now()) {
                return redirect()->back()->with('error', 'Ooops, Tanggal Expired tidak boleh lebih kecil dari hari ini');
            }

            $containersJson = json_encode($containers);

            $status = Auth::user()->hasRole('customer') ? 'Y' : 'Y';

            $do = DOonline::create([
                'do_no' => $request->do_no,
                'bl_no' => $request->bl_no,
                'expired' => $request->expired,
                'customer_code' => $request->customer,
                'created_at' => Carbon::now(),
                'created_by' => Auth::user()->name,
                'active' => 'Y',
                'container_no'=>$containersJson,
                'status' => $status
            ]);

            if ($request->hasFile('do_pdf')) {
                $file = $request->file('do_pdf');

                $fileName = preg_replace(
                    '/[^A-Za-z0-9._-]/',
                    '_',
                    $file->getClientOriginalName()
                );

                $file->storeAs(
                    'do/' . $do->id,
                    $fileName,
                    'public'
                );
            }

            if (Auth::user()->hasRole('customer')) {
                $url = '/customer-import/doOnline/index';
            }else {
                $url = '/billing/dock-DO';
            }
            return redirect($url)->with('success', 'Data Berhasil di Buat');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something Wrong : ' . $th->getMessage());
        }
    }

    
    public function approved(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'action' => 'required|in:approve,unapprove',
        ]); 

        try {
            $do = DOonline::findOrFail($request->id);   

            $do->update([
                'status' => $request->action === 'approve' ? 'Y' : 'N',
            ]); 

            return response()->json([
                'success' => true,
                'message' => $request->action === 'approve'
                    ? 'DO berhasil disetujui.'
                    : 'Persetujuan DO berhasil dibatalkan.',
            ]);
        } catch (\Throwable $th) {
            report($th);    

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status DO.',
            ], 500);
        }
    }

    public function doFiles($id)
    {
        $do = DOonline::findOrFail($id);
        $data['title'] = 'File DO';   

        $directory = 'do/' . $do->id;
        $files = Storage::disk('public')->files($directory);    

        return view('billingSystem.do.do-files', $data, compact('do', 'files'));
    }   

    public function deleteDoFile(Request $request, $id)
    {
        $request->validate([
            'filename' => 'required|string|max:255',
        ]); 

        $do = DOonline::findOrFail($id);    

        // Batasi penghapusan hanya ke file di folder DO ini.
        $filename = basename($request->filename);
        $path = 'do/' . $do->id . '/' . $filename;  

        $disk = Storage::disk('public');    

        if (!$disk->exists($path)) {
            return redirect()->back()
                ->with('error', 'File tidak ditemukan.');
        }   

        $disk->delete($path);   

        return redirect()->route('doFiles', $do->id)
            ->with('success', 'File PDF berhasil dihapus.');
    }


    // Master Tarif  

    public function export()
    {
        $data['title'] = "Mater Tarif Export";
        $data ['orderService'] = OS::where('ie', '=', 'E')->get();
        $data ['masterTarif'] = MT::get();
        return view('billingSystem.export.master-tarif.main', $data);
    }

    public function tarifExport($os)
    {
        $data['orderService'] = OS::where('id', $os)->first();
        $data['title'] = "Add Master Tarif " . $data['orderService']->name;
        
        return view('billingSystem.export.master-tarif.create', $data);
    }

    public function storeMTexport(Request $request)
    {
        $osId = $request->os_id;
        $os = OS::where('id', $osId)->first();

        if ($os) {
            $mt = MT::create([
                'os_id'=>$request->os_id,
                'os_name'=>$request->os_name,
                'ctr_size'=>$request->ctr_size,
                'm1'=>$request->m1,
                'm2'=>$request->m2,
                'm3'=>$request->m3,
                'lolo_full'=>$request->lolo_full,
                'lolo_empty'=>$request->lolo_empty,
                'pass_truck_masuk'=>$request->pass_truck_masuk,
                'pass_truck_keluar'=>$request->pass_truck_keluar,
                'admin'=>$request->admin,
                'pajak'=>$request->pajak,
                'paket_stripping'=>$request->paket_stripping,
                'pemindahan_petikemas'=>$request->pemindahan_petikemas,
                'create_by'=> Auth::user()->name,
                'created_at'=>Carbon::now(),
                'jpb_extruck'=>$request->jpb_extruck,
                'sewa_crane'=>$request->sewa_crane,
                'cargo_dooring'=>$request->cargo_dooring,
                'paket_stuffing'=>$request->paket_stuffing,
            ]);
            return redirect('/billing/export/master-tarif')->with('success', 'Tarif added successfully.');

        }else {
            return redirect()->back()->with('error', 'Something Wrong, Call the Admin.');
        }

    }

    public function EditExport($id)
    {
        $data['masterTarif'] = MT::where('id', $id)->first();
        $data['title'] = "Add Master Tarif " . $data['masterTarif']->os_name;
        
        return view('billingSystem.export.master-tarif.edit', $data);
    }

    public function updateMTexport(Request $request)
    {
        $id = $request->id;
        $mt = MT::where('id', $id)->first();

        if ($mt) {
            $mt->update([
                'os_id'=>$request->os_id,
                'os_name'=>$request->os_name,
                'ctr_size'=>$request->ctr_size,
                'm1'=>$request->m1,
                'm2'=>$request->m2,
                'm3'=>$request->m3,
                'lolo_full'=>$request->lolo_full,
                'lolo_empty'=>$request->lolo_empty,
                'pass_truck_masuk'=>$request->pass_truck_masuk,
                'pass_truck_keluar'=>$request->pass_truck_keluar,
                'admin'=>$request->admin,
                'pajak'=>$request->pajak,
                'paket_stripping'=>$request->paket_stripping,
                'pemindahan_petikemas'=>$request->pemindahan_petikemas,
                'update_by'=> Auth::user()->name,
                'jpb_extruck'=>$request->jpb_extruck,
                'sewa_crane'=>$request->sewa_crane,
                'cargo_dooring'=>$request->cargo_dooring,
                'paket_stuffing'=>$request->paket_stuffing,
            ]);
            return redirect('/billing/export/master-tarif')->with('success', 'Tarif added successfully.');

        }else {
            return redirect()->back()->with('error', 'Something Wrong, Call the Admin.');
        }
    }

}
