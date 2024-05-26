<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\OrderService as OS;
use App\Models\OSDetail;
use App\Models\MasterTarif as MT;
use App\Models\MTDetail;
use App\Models\Customer;
use App\Models\DOonline;
use App\Models\Item;
use App\Models\Extend;
use App\Models\KodeDok;
use App\Models\InvoiceImport;
use App\Models\ImportDetail;
use App\Models\JobImport;
use App\Models\VVoyage;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\JobExtend;
use App\Models\ExtendDetail as Detail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExtend;

class InvoiceExtend extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }


    public function index()
    {
        $data['title'] = 'Delivery Extend';
        $data['service'] = OS::where('ie', '=' , 'I')->orderBy('id', 'asc')->get();
        $data['invoice'] = Extend::orderBy('order_at', 'asc')->get();
        return view('billingSystem.extend.billing.main', $data);
    }

    public function form()
    {
        $data['title'] = 'Extend Form';
        $user = Auth::user();
        $data["user"] = $user->id;

        $tumpuk = ImportDetail::where('count_by', 'T')->get();
        $invIds = $tumpuk->pluck('inv_id');
        $data['oldInv'] = InvoiceImport::whereIn('id', $invIds)->where('lunas', '=', 'Y')->get();
        $data['customer'] = Customer::get();
        $data['now'] = Carbon::now();
        return view('billingSystem.extend.form.createForm', $data);
    }

    public function contData(Request $request)
    {
        $id = $request->id;
        $inv = InvoiceImport::where('id', $id)->first();

        if ($inv) {
           
                $cont = Container::where('form_id', $inv->form_id)->get();
                // var_dump($invCont, $cont);
                // die;       
           
            if (!$cont->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'updated successfully!',
                    'data'    => $inv,
                    'cont' => $cont,
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
    }

    public function preinvoice(Request $request)
    {
        $data['title'] = 'Pre Invoice Exrtend';
        $data['customer'] = Customer::where('id', $request->customer)->first();
        $oldInv = InvoiceImport::where('id', $request->inv_id)->first();
        $oldService = OS::where('id', $oldInv->os_id)->first();
        // dd($oldService);
        if ($oldService->order == 'SP2') {
            $newOS = OS::where('ie', '=', 'X')->where('name', '=', 'Perpanjangan Penumpukan SP2')->first();
        }else {
            $newOS = OS::where('ie', '=', 'X')->where('name', '=', 'Perpanjangan Penumpukan SPPS')->first();
        }
        $tarif20 = MT::where('os_id', $newOS->id)->where('ctr_size', '=', '20')->first();
        $tarifCont20 = MTDetail::where('master_tarif_id', $tarif20->id)->first();
        
        // dd($tarif);
        $oldExpired = $oldInv->expired_date;
        $expired = $request->exp_date;

        if ($oldExpired >= $expired) {
            return back()->with('error', 'Expired date tidak lebih besar dari expired date sebelumnya');
        }
        else {
        //    $cont = str_replace(['[', ']', '"'], '', $oldInv->container_key);
        //    $contItem = explode(",", $cont);
        $data['selectedCont'] = $request->container_key;
           $data['item'] = Item::whereIn('container_key', $request->container_key)->orderBy('ctr_size', 'asc')->get();
            
           $oldExp = Carbon::parse($oldExpired);
           $expDate = Carbon::parse($expired);
           $interval = $oldExp->diff($expDate);
           $jumlahHari = $interval->days;

           $oldm1 = $oldInv->massa1;
           $oldm2 = $oldInv->massa2;
           $oldm3 = $oldInv->massa3;

           if ($oldm1 >= '5') {
            $data['newM1'] = '0';
            $sisaHari1 = $jumlahHari - $data['newM1'];
           }else {
            $data['newM1'] = 5 - $oldm1;
            $sisaHari1 = $jumlahHari - $data['newM1'];
           }

           if ($sisaHari1 >= '5') {
            if ($oldm2 >= '5') {
                $data['newM2'] = '0';
                $sisaHari2 = $jumlahHari - $data['newM2'];
            }elseif ($oldm2 == '0') {
                $data['newM2'] = '5';
                $sisaHari2 = $jumlahHari - $data['newM2'];
            }else {
                $data['newM2'] = 5 - $oldm2;
                $sisaHari2 = $jumlahHari - $data['newM2'];
            }
           }else {
            if ($oldm2 == '0') {
                $data['newM2'] = $sisaHari1;
                $sisaHari2 = '0';
            }elseif ($oldm2 >= '0') {
                $maxM2 = 5 - $oldm2;
                if ($sisaHari1 <= $maxM2) {
                    $data['newM2'] = $sisaHari1;
                    $sisaHari2 = '0';
                }else {
                    $data['newM2'] = $maxM2;
                    $sisaHari2 = $sisaHari1 - $maxM2;
                }
            }
           }

           if ($sisaHari2 > 0) {
            $data['newM3'] = $sisaHari2;
           }else {
            $data['newM3'] = '0';
           }

        //    jumlah Cont
        $ctr_20  = $data['item']->where('ctr_size', '20')->count();
        if ($ctr_20) {
            $data['tarif20'] = MT::where('os_id', $oldInv->os_id)->where('ctr_size', '20')->first();
            // dd($data['tarif20']);
              // tarif 20
            if ($data['newM1'] == '0') {
                $m1_20 = '0';
            }else {
                $m1_20 = 0 * $ctr_20 * $data['newM1'];
            }

            $m2_20 = 54000 * $ctr_20  * $data['newM2'];
            // dd($ctr_20, $data['newM2']);
            $m3_20 = 81000 * $ctr_20  * $data['newM3'];
        }else {
            $m1_20 = '0';
            $m2_20 = '0';
            $m3_20 = '0';
        }

        $ctr_21  = $data['item']->where('ctr_size', '21')->count();
        if ($ctr_21) {
            $data['tarif21'] = MT::where('os_id', $oldInv->os_id)->where('ctr_size', '21')->first();
             // tarif 21
        if ($data['newM1'] == '0') {
            $m1_21 = '0';
        }else {
            $m1_21 = 0 * $ctr_21 * $data['newM1'];
        }

        $m2_21 = 124000 * $ctr_21 * $data['newM2'];
        $m3_21 = 186000 * $ctr_21 * $data['newM3'];
        }else {
            $m1_21 = '0';
            $m2_21 = '0';
            $m3_21 = '0';
        }
        
        $ctr_40  =$data['item']->where('ctr_size', '40')->count();
        if ($ctr_40) {
            $data['tarif40'] = MT::where('os_id', $oldInv->os_id)->where('ctr_size', '40')->first();
            if ($data['newM1'] == '0') {
                $m1_40 = '0';
            }else {
                $m1_40 = 0 * $ctr_40 * $data['newM1']; 
            }
    
            $m2_40 = 108000 * $ctr_40 * $data['newM2'];
            $m3_40 = 162000 * $ctr_40 * $data['newM3'];
        }else {
            $m1_40 = '0';
            $m2_40 = '0';
            $m3_40 = '0';
        }
        $ctr_42  = $data['item']->where('ctr_size', '42')->count();
        if ($ctr_42) {
            # code...
            $data['tarif42'] = MT::where('os_id', $oldInv->os_id)->where('ctr_size', '42')->first();
               // tarif 42
             if ($data['newM1'] == '0') {
                $m1_42 = '0';
            }else {
                $m1_42 = 0 * $ctr_42 * $data['newM1'];
            }
        
            $m2_42 = 248000 * $ctr_42 * $data['newM2'];
            $m3_42 = 248000 * $ctr_42 * $data['newM3'];
        }else {
            $m1_42 = '0';
            $m2_42 = '0';
            $m3_42 = '0';
        }
           
        $tarif = MT::where('os_id', $oldInv->os_id)->first();
        $tarifAdmin = MTDetail::where('master_tarif_id', $tarif->id)->where('count_by', '=', 'O')->first();
        $admin = $tarifAdmin->tarif;
        $pajak = $tarif->pajak;
        $total = $m1_20 + $m2_20 + $m3_20 + $m1_21 + $m2_21 + $m3_21 + $m1_40 + $m2_40 + $m3_40 + $m1_42 + $m2_42 + $m3_42;
        
        $sum = $total + $admin;
        $pajakTot = ($sum * $pajak)/100;
        $grandTotal = $sum + $pajakTot;
        return view('billingSystem.extend.form.pre-invoice', compact('pajakTot', 'admin', 'total', 'grandTotal','m1_20', 'm2_20', 'm3_20', 'm1_21', 'm2_21', 'm3_21', 'm1_40', 'm2_40', 'm3_40', 'm1_42', 'm2_42', 'm3_42', 'ctr_20', 'ctr_21', 'ctr_40', 'ctr_42', 'expired', 'oldInv', 'jumlahHari'), $data)->with('success', 'Silahkan Melanjutkan Proses');

        }
    }

    public function post(Request $request)
    {
        $oldInv = InvoiceImport::where('id', $request->inv_id)->first();
        $cont = "["."". $request->contKey_Selected . "" ."]";
        $cust = Customer::where('id', $request->cust_id)->first();
        $invoiceNo = $oldInv->inv_type . '-' . $this->getNextInvoiceExtend();
        $itemtArray = json_decode($cont);
        $item = Item::where('container_key', $itemtArray)->get();
        $extend = Extend::create([
            'proforma_no'=>$oldInv->proforma_no,
            'inv_id'=>$oldInv->id,
            'inv_no'=>$invoiceNo,
            'cust_id'=>$cust->id,
            'cust_name'=>$cust->name,
            'fax'=>$cust->fax,
            'npwp'=>$cust->npwp,
            'alamat'=>$cust->alamat,
            'os_id'=>$oldInv->os_id,
            'os_name'=>$oldInv->os_name,
            'container_key'=>"["."". $request->contKey_Selected . "" ."]",
            'm1'=>$request->m1,
            'm2'=>$request->m2,
            'm3'=>$request->m3,
            'ctr_20'=>$request->ctr_20,
            'ctr_40'=>$request->ctr_40,
            'ctr_21'=>$request->ctr_21,
            'ctr_42'=>$request->ctr_42,
            'm1_20'=>$request->m1_20,
            'm2_20'=>$request->m2_20,
            'm3_20'=>$request->m3_20,
            'm1_21'=>$request->m1_21,
            'm2_21'=>$request->m2_21,
            'm3_21'=>$request->m3_21,
            'm1_40'=>$request->m1_40,
            'm2_40'=>$request->m2_40,
            'm3_40'=>$request->m3_40,
            'm1_42'=>$request->m1_42,
            'm2_42'=>$request->m2_42,
            'm3_42'=>$request->m3_42,
            'admin'=>$request->admin,
            'total'=>$request->total,
            'pajak'=>$request->pajak,
            'grand_total'=>$request->grand_total,
            'order_by'=>$request->order_by,
            'lunas'=> "N",
            'expired_date'=>$request->expired_date,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
        ]);

        if ($extend->os_id = '1' || $extend->os_id = '2' || $extend->os_id = '5' || $extend->os_id = '16')  {
            $kode = "PPSP2-";
        }else {
            $kode = "PPSPS-";
        }
        if ($extend->ctr_20 != null) {
            $detail20 = Detail::create([
                'inv_id'=>$extend->id,
                'inv_no'=>$extend->inv_no,
                'inv_type'=>'XTD',
                'keterangan'=>'Invoice Extend',
                'detail'=> $kode.'20',
                'ukuran'=>'20',
                'jumlah'=>$extend->ctr_20,
                'satuan'=>'unit',
                'harga'=>$extend->lolo_mty_20,
                'expired_date'=>$extend->expired_date,
                'order_date'=>$extend->order_at,
                'lunas'=>$extend->lunas,
                'cust_id'=>$extend->cust_name,
                'cust_name'=>$extend->cust_id
            ]);
        }
        if ($extend->ctr_21 != null) {
            $detail21 = Detail::create([
                'inv_id'=>$extend->id,
                'inv_no'=>$extend->inv_no,
                'inv_type'=>'XTD',
                'keterangan'=>'Invoice Extend',
                'detail'=> $kode.'21',
                'ukuran'=>'21',
                'jumlah'=>$extend->ctr_21,
                'satuan'=>'unit',
                'harga'=>$extend->lolo_mty_21,
                'expired_date'=>$extend->expired_date,
                'order_date'=>$extend->order_at,
                'lunas'=>$extend->lunas,
                'cust_id'=>$extend->cust_name,
                'cust_name'=>$extend->cust_id
            ]);
        }
        if ($extend->ctr_40 != null) {
            $detail40 = Detail::create([
                'inv_id'=>$extend->id,
                'inv_no'=>$extend->inv_no,
                'inv_type'=>'XTD',
                'keterangan'=>'Invoice Extend',
                'detail'=> $kode.'40',
                'ukuran'=>'40',
                'jumlah'=>$extend->ctr_40,
                'satuan'=>'unit',
                'harga'=>$extend->lolo_mty_40,
                'expired_date'=>$extend->expired_date,
                'order_date'=>$extend->order_at,
                'lunas'=>$extend->lunas,
                'cust_id'=>$extend->cust_name,
                'cust_name'=>$extend->cust_id
            ]);
        }
        if ($extend->ctr_42 != null) {
            $detail42 = Detail::create([
                'inv_id'=>$extend->id,
                'inv_no'=>$extend->inv_no,
                'inv_type'=>'XTD',
                'keterangan'=>'Invoice Extend',
                'detail'=> $kode.'42',
                'ukuran'=>'42',
                'jumlah'=>$extend->ctr_42,
                'satuan'=>'unit',
                'harga'=>$extend->lolo_mty_42,
                'expired_date'=>$extend->expired_date,
                'order_date'=>$extend->order_at,
                'lunas'=>$extend->lunas,
                'cust_id'=>$extend->cust_name,
                'cust_name'=>$extend->cust_id
            ]);
        }
        // $contArray = explode(',', $cont[0]);
        // dd($contArray, $cont);
        $contArray = json_decode($cont);
            foreach ($contArray as $idCont) {
                $selectCont = Item::where('container_key', $idCont)->get();
                
                foreach ($selectCont as $item) {
                    $item->update([
                        'selected_do' => 'Y'
                    ]);

                    $lastJobNo = JobExtend::orderBy('id', 'desc')->value('job_no');
                    $jobNo = $this->getNextJob($lastJobNo);
                        $job = JobExtend::create([
                            'inv_id'=>$extend->id,
                            'job_no'=>$jobNo,
                            'os_id'=>$extend->os_id,
                            'os_name'=>$extend->os_name,
                            'cust_id'=>$extend->cust_id,
                            'active_to'=>$extend->expired_date,
                            'container_key'=>$item->container_key,
                            'container_no'=>$item->container_no,
                            'ves_id'=>$item->ves_id,
                        ]);
                }
            }
        return redirect()->route('index-extend')->with('success', 'Invoice Berhasil Di Buat');
    }

    public function payExtend($id)
    {
        $invoice = Extend::where('id', $id)->first();
        if ($invoice) {
            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $invoice,
            ]);
        }
    }

    public function payFull(Request $request)
    {
        $id = $request->inv_id;

        $invoice = Extend::where('id', $id)->first();
        $cont = $invoice->container_key;
        $contArray = json_decode($invoice->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();
        $details = Detail::where('inv_id', $id)->get();

        if ($invoice) {
            $job = JobExtend::where('inv_id', $id)->get();
            // var_dump($job);
            // die;
            foreach ($job as $jobp) {
                $contUpdate = Item::where('container_key', $jobp->container_key)->update([
                    'invoice_no'=>$invoice->inv_no,
                    'job_no' => $jobp->job_no,
                ]);
            }
            foreach ($details as $detail) {
                $detail->update([
                    'lunas' => 'Y'
                ]);
            }


            $invoice->update([
                'lunas' => 'Y',
                'lunas_at'=> Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $items,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
    }

    public function piutang(Request $request)
    {
        $id = $request->inv_id;

        $invoice = Extend::where('id', $id)->first();
       
        $cont = $invoice->container_key;
        $contArray = json_decode($invoice->container_key);
        $container_key_string = $contArray[0];
        $container_keys = explode(",", $container_key_string);
        $items = Item::whereIn('container_key', $container_keys)->get();

        if ($invoice) {
            $job = JobExtend::where('inv_id', $id)->get();
            // var_dump($job);
            // die;
            foreach ($job as $jobp) {
                $contUpdate = Item::where('container_key', $jobp->container_key)->update([
                    'invoice_no'=>$invoice->inv_no,
                    'job_no' => $jobp->job_no,
                ]);
            }

            $invoice->update([
                'lunas' => 'P',
                'lunas_at'=> Carbon::now(), 
            ]);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully!',
                'data'    => $items,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!',
            ]);
        }
    }


    public function PranotaExtend($id)
    {
        $data['title'] = "Pranota";
        $data['invoice'] = Extend::where('id', $id)->first();
        $cont = str_replace(['[', ']', '"'], '', $data['invoice']->container_key);
        $contItem = explode(",", $cont);
        $data['item'] = Item::whereIn('container_key', $contItem)->orderBy('ctr_size', 'asc')->get();
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.extend.pranota.main', $data);
    }

    public function InvoiceExtend($id)
    {
        $data['title'] = "Invoice";
        $data['invoice'] = Extend::where('id', $id)->first();
        $cont = str_replace(['[', ']', '"'], '', $data['invoice']->container_key);
        $contItem = explode(",", $cont);
        $data['item'] = Item::whereIn('container_key', $contItem)->orderBy('ctr_size', 'asc')->get();
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.extend.invoice.main', $data);
    }


    public function JobExtend($id)
    {
        $data['title'] = 'Job Number';
        $data['inv'] = Extend::where('id', $id)->first();
        $data['job'] = JobExtend::where('inv_id', $id)->get();
        $data['cont'] = Item::get();
        foreach ($data['job'] as $jb) {
            foreach ($data['cont'] as $ct) {
                if ($ct->container_key == $jb->container_key) {
                    $qrcodes[$jb->id] = QrCode::size(100)->generate($ct->container_no);
                    break;
                }
            }
        }
        return view('billingSystem.import.job.main',compact('qrcodes'), $data);
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


private function getNextInvoiceExtend()
{
    // Mendapatkan nomor proforma terakhir
    $latest = Extend::orderBy('order_at', 'desc')->first();

    // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
    if (!$latest) {
        return 'P0000001';
    }

    // Mendapatkan nomor urut proforma terakhir
    $lastInvoice = $latest->inv_no;

    // Mengekstrak angka dari nomor proforma terakhir
    $lastNumber = (int)substr($lastInvoice, 5);

    // Menambahkan 1 ke nomor proforma terakhir
    $nextNumber = $lastNumber + 1;

    // Menghasilkan nomor proforma berikutnya dengan format yang benar
    return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
}

private function getNextJob($lastJobNo)
{
    // Jika tidak ada nomor pekerjaan sebelumnya, kembalikan nomor pekerjaan awal
    if (!$lastJobNo) {
        return 'JOBP0000001';
    }

    // Mengekstrak angka dari nomor pekerjaan terakhir
    $lastNumber = (int)substr($lastJobNo, 4);

    // Menambahkan 1 ke nomor pekerjaan terakhir
    $nextNumber = $lastNumber + 1;

    // Menghasilkan nomor pekerjaan berikutnya dengan format yang benar
    return 'JOBP' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
}

public function ReportExcel(Request $request)
{
  $os = $request->os_id;
  $startDate = $request->start;
  $endDate = $request->end;
  $invoice = Extend::where('os_id', $os)->whereDate('order_at', '>=', $startDate)->whereDate('order_at', '<=', $endDate)->orderBy('order_at', 'asc')->get();
    $fileName = 'ReportInvoiceExtend-'.$os.'-'. $startDate . $endDate .'.xlsx';
  return Excel::download(new ReportExtend($invoice), $fileName);
}
}

