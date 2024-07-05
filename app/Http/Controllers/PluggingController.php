<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\Customer;
use App\Models\Item;
use App\Models\KodeDok;
use App\Models\RO;
use App\Models\VVoyage;
use App\Models\InvoiceExport;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\ExportDetail as Detail;
use App\Models\JobExport;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Exports\ReportInvoice;


use Auth;
use Carbon\Carbon;

class PluggingController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function billingMain()
    {
        $data['title'] = "Plugging Billing System";
        $data['invoice'] = InvoiceExport::orderBy('order_at', 'asc')->orderBy('lunas', 'asc')->get();

        $data['service'] = OS::where('ie', '=' , 'P')->orderBy('id', 'asc')->get();
        $data['unPaids'] = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'P');
        })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'asc')->get();
        $data['piutangs'] = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'P');
        })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'asc')->get();

        return view('billingSystem.plugging.billing.main', $data);
    }

    public function deliveryMenu()
    {
        $data['title'] = "Plugging Form Menu";
        $data['formInvoiceImport'] = Form::where('i_e', 'P')->where('done', '=', 'N')->get();

        return view('billingSystem.plugging.form.main', $data);
    }

    public function FormIndex()
    {
        $user = Auth::user();
        $data['title'] = "Plugging Form";
        $data["user"] = $user->id;

        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'P')->get();
        $data['vessel'] = VVoyage::get();

        return view('billingSystem.plugging.form.create', $data);
    }

    public function FormEdit($id)
    {
        $form = Form::where('id', $id)->first();
        $data['form'] = $form;
        $data['discDate'] = Carbon::parse($form->disc_date)->format('d-m-Y h:i K');
        $data['expDate'] = Carbon::parse($form->expired_date)->format('d-m-Y h:i K');
        // dd($data['discDate'], $data['expDate']);
        $user = Auth::user();
        $data['title'] = "Plugging Form";
        $data["user"] = $user->id;
        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'P')->get();
        $data['vessel'] = VVoyage::get();
        $data['containerInvoice'] = Container::where('form_id', $id)->get();
        return view('billingSystem.plugging.form.edit', $data);
    }

    public function getContainer(Request $request)
    {
        $kapal = $request->kapal;
       
       $cont = Item::where('ves_id', $kapal)->whereNot('container_no', '=', '')->where('ctr_type', '=', 'RFR')->get();
       if ($cont->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada container yang dapat digunakan !!',
            ]);
         } else {
             return response()->json([
                 'success' => true,
                 'message' => 'Success !!',
                 'data' => $cont,
             ]);
         }
    }

    public function FormStore(Request $request)
    {
        $contSelect = $request->container;
        $expiredDate = Carbon::createFromFormat('d-m-Y h:i A', $request->expired_date);
        $discDate = Carbon::createFromFormat('d-m-Y h:i A', $request->disc_date);
        
        $invoice = Form::create([
            'expired_date'=>$expiredDate,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'ves_id'=> $request->ves_id,
            'i_e'=>'P',
            'disc_date'=>$discDate,
            'done'=>'N',
            'discount_ds'=>$request->discount_ds,
            'discount_dsk'=>$request->discount_dsk,
        ]);

      
        foreach ($contSelect as $cont) {
            $item = Item::where('container_key', $cont)->first();
            $contInvoice = Container::create([
                'container_key'=>$item->container_key,
                'container_no'=>$item->container_no,
                'ctr_size'=>$item->ctr_size,
                'ctr_status'=>$item->ctr_status,
                'form_id'=>$invoice->id,
                'ves_id'=>$item->ves_id,
                'ves_name'=>$item->ves_name,
                'ctr_type'=>$item->ctr_type,
                'ctr_intern_status'=>$item->ctr_intern_status,
                'gross'=>$item->gross,
            ]);
        }


        return redirect()->route('plugging-preinvoice', ['id' => $invoice->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }

    public function FormUpdate(Request $request)
    {
       $invoice = Form::where('id', $request->form_id)->first();
       $expiredDate = Carbon::createFromFormat('d-m-Y h:i A', $request->expired_date);
       $discDate = Carbon::createFromFormat('d-m-Y h:i A', $request->disc_date);
       
       $oldCont = Container::where('form_id', $invoice->id)->get();
        foreach ($oldCont as $cont) {
            $cont->delete();
        }

        $newContainer = $request->container;
        foreach ($newContainer as $cont) {
            $item = Item::where('container_key', $cont)->first();
            $contInvoice = Container::create([
                'container_key'=>$item->container_key,
                'container_no'=>$item->container_no,
                'ctr_size'=>$item->ctr_size,
                'ctr_status'=>$item->ctr_status,
                'form_id'=>$invoice->id,
                'ves_id'=>$item->ves_id,
                'ves_name'=>$item->ves_name,
                'ctr_type'=>$item->ctr_type,
                'ctr_intern_status'=>$item->ctr_intern_status,
                'gross'=>$item->gross,
            ]);
        }

        $invoice->update([
            'expired_date'=>$expiredDate,
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'ves_id'=> $request->ves_id,
            'i_e'=>'P',
            'disc_date'=>$discDate,
            'done'=>'N',
            'discount_ds'=>$request->discount_ds,
            'discount_dsk'=>$request->discount_dsk,
        ]);

        return redirect()->route('plugging-preinvoice', ['id' => $invoice->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }

    public function preinvoice($id)
    {
        $data['title'] = 'Invoice Plugging';
        $form = Form::where('id', $id)->first();
        $bigOS = OS::where('id', $form->os_id)->first();
        $data['customer'] = Customer::where('id', $form->cust_id)->first();
        $data['discDate'] = Carbon::parse($form->disc_date)->format('d-m-Y h:i K');
        $data['expDate'] = Carbon::parse($form->expired_date)->format('d-m-Y h:i K');

        $data['service'] = OS::where('id', $form->os_id)->first();
        $data['selectCont'] = Container::where('form_id', $id)->get();
        $containerInvoice = Container::where('form_id', $id)->get();
        $groupedBySize = $containerInvoice->groupBy('ctr_size');
        $ctrGroup = $groupedBySize->map(function ($sizeGroup) {
            return $sizeGroup->groupBy('ctr_status');
        });
        $data['ctrGroup'] = $ctrGroup;
        $discDateCarbon = Carbon::parse($form->disc_date);
        $expDateCarbon = Carbon::parse($form->expired_date);
        $secondsDifference = $discDateCarbon->diffInSeconds($expDateCarbon);
        $shiftCount = ceil($secondsDifference / 28800);
        // dd($shiftCount);
        $osDS = OSDetail::where('os_id', $form->os_id)->where('type', '=', 'OS')->get();

      
            $data['adminDS'] = 0;
            $resultsDS = collect(); // Use a collection to store the results
            $service = $osDS;
            foreach ($service as $svc) {
                foreach ($ctrGroup as $size => $statusGroup) {
                    foreach ($statusGroup as $status => $containers) {
                        $containerCount = $containers->count();
                        $tarif = MT::where('os_id', $bigOS->id)
                            ->where('ctr_size', $size)
                            ->where('ctr_status', $status)
                            ->first();
                        $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
                            ->where('master_item_id', $svc->master_item_id)
                            ->first();
                        if ($tarifDetail) {
                            if ($tarifDetail->count_by == 'H') {
                                $hargaC = $tarifDetail->tarif * $containerCount * $shiftCount;
                                $resultsDS->push([
                                    'ctr_size' => $size,
                                    'ctr_status' => $status,
                                    'count_by' => 'C',
                                    'tarif' => $tarifDetail->tarif,
                                    'jumlahHari' => $shiftCount,
                                    'containerCount' => $containerCount,
                                    'keterangan' => $tarifDetail->master_item_name,
                                    'harga' => $hargaC,
                                ]);
                            }
                        }
                    }
                }
                $singleTarif = MT::where('os_id', $bigOS->id)->first();
                $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                    ->where('master_item_id', $svc->master_item_id)
                    ->where('count_by', 'O')
                    ->first();
                if ($singleTarifDetail) {
                    $data['adminDS'] = $singleTarifDetail->tarif;
                }
            }
            $data['totalDS'] = $resultsDS->sum('harga');
            $data['resultsDS'] = $resultsDS;
            $data['discountDS'] = ($data['totalDS'] + $data['adminDS']) * $form->discount_ds / 100;
            $data['pajakDS'] = (($data['totalDS'] + $data['adminDS']) - $data['discountDS']) * 11 / 100;
            $data['grandTotalDS'] = (($data['totalDS'] + $data['adminDS']) - $data['discountDS']) + $data['pajakDS'];
     

        return view('billingSystem.plugging.form.pre-invoice', compact('form'), $data);
    }

    public function SubmitInvoice(Request $request)
    {
        $form = Form::where('id', $request->formId)->first();
        $bigOS = OS::where('id', $form->os_id)->first();
        $data['customer'] = Customer::where('id', $form->cust_id)->first();
        $data['discDate'] = Carbon::parse($form->disc_date)->format('d-m-Y h:i K');
        $data['expDate'] = Carbon::parse($form->expired_date)->format('d-m-Y h:i K');

        $data['service'] = OS::where('id', $form->os_id)->first();
        $data['selectCont'] = Container::where('form_id', $request->formId)->get();
        $containerInvoice = Container::where('form_id', $request->formId)->get();
        $groupedBySize = $containerInvoice->groupBy('ctr_size');
        $ctrGroup = $groupedBySize->map(function ($sizeGroup) {
            return $sizeGroup->groupBy('ctr_status');
        });
        $data['ctrGroup'] = $ctrGroup;
        $discDateCarbon = Carbon::parse($form->disc_date);
        $expDateCarbon = Carbon::parse($form->expired_date);
        $secondsDifference = $discDateCarbon->diffInSeconds($expDateCarbon);
        $shiftCount = ceil($secondsDifference / 28800);
        // dd($shiftCount);
        $osDS = OSDetail::where('os_id', $form->os_id)->where('type', '=', 'OS')->get();
        $ds = $osDS->isEmpty() ? 'N' : 'Y';

        // dd($ds, $dsk);

      

     
        $nextProformaNumber = $this->getNextProformaNumber();
        $invoiceNo = $this->getNextInvoiceDS();
        $invoiceDS = InvoiceExport::create([
            'inv_type'=>'OS',
            'form_id'=>$form->id,
           
            'proforma_no'=>$nextProformaNumber,
            'cust_id'=>$form->cust_id,
            'cust_name'=>$form->customer->name,
            'fax'=>$form->customer->fax,
            'npwp'=>$form->customer->npwp,
            'alamat'=>$form->customer->alamat,
            'os_id'=>$form->os_id,
            'os_name'=>$form->service->name,
           
            'lunas'=>'N',
            'expired_date'=>$form->expired_date,
            'disc_date' => $form->disc_date,
            'booking_no'=>$form->do_id,
            'total'=>$request->totalDS,
            'admin'=>$request->adminDS,
            'discount'=>$request->discountDS,
            'pajak'=>$request->pajakDS,
            'grand_total'=>$request->grandTotalDS,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
          
            
        ]);
        $admin = 0;
        foreach ($osDS as $service) {
            foreach ($ctrGroup as $size => $statusGroup) {
                foreach ($statusGroup as $status => $containers) {
                    $containerCount = $containers->count();
                    $tarif = MT::where('os_id', $bigOS->id)
                            ->where('ctr_size', $size)
                            ->where('ctr_status', $status)
                            ->first();
                    $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
                        ->where('master_item_id', $service->master_item_id)
                        ->first();
                    if ($tarifDetail) {
                        if ($service->kode != 'PASSTRUCK') {
                            $kode = $service->kode . $size;
                        }else {
                            $kode = 'PASSTRUCK';
                        }
                       
                        if ($tarifDetail->count_by == 'H') {
                            $hargaC = $tarifDetail->tarif * $containerCount * $shiftCount;
                            $detailImport = Detail::create([
                             'inv_id'=>$invoiceDS->id,
                            
                             'inv_type'=>$invoiceDS->inv_type,
                             'keterangan'=>$form->service->name,
                             'ukuran'=>$size,
                             'jumlah'=>$containerCount,
                             'satuan'=>'unit',
                             'expired_date'=>$form->expired_date,
                             'order_date'=>$invoiceDS->order_at,
                             'lunas'=>'N',
                             'cust_id'=>$form->cust_id,
                             'cust_name'=>$form->customer->name,
                             'os_id'=>$form->os_id,
                             'jumlah_hari' => $shiftCount,
                             'master_item_id'=>$service->master_item_id,
                             'master_item_name'=>$service->master_item_name,
                             'kode'=>$kode,
                             'tarif'=>$tarifDetail->tarif,
                             'total'=>$hargaC,
                             'form_id'=>$form->id,
                             'count_by'=>'H',
                            ]);

                        }
                    }    
                }
            }
            $singleTarif = MT::where('os_id', $bigOS->id)->first();
                $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                    ->where('master_item_id', $service->master_item_id)
                    ->where('count_by', 'O')
                    ->first();
                if ($singleTarifDetail) {
                    $detailImport = Detail::create([
                        'inv_id'=>$invoiceDS->id,

                        'inv_type'=>$invoiceDS->inv_type,
                        'keterangan'=>$form->service->name,
                        'ukuran'=> '0',
                        'jumlah'=> 1,
                        'satuan'=>'unit',
                        'expired_date'=>$form->expired_date,
                        'order_date'=>$invoiceDS->order_at,
                        'lunas'=>'N',
                        'cust_id'=>$form->cust_id,
                        'cust_name'=>$form->customer->name,
                        'os_id'=>$form->os_id,
                        'jumlah_hari'=>'0',
                        'master_item_id'=>$service->master_item_id,
                        'master_item_name'=>$service->master_item_name,
                        'kode'=>$service->kode,
                        'tarif'=>$tarifDetail->tarif,
                        'total'=>$tarifDetail->tarif,
                        'form_id'=>$form->id,
                        'count_by'=>'O',
                       ]);
                }
        }
       

       $form->update([
        'done'=>'Y',
       ]);
       
       return redirect()->route('plugging-main')->with('success', 'Menunggu Pembayaran');

       
    }

    private function getNextProformaNumber()
    {
        // Mendapatkan nomor proforma terakhir
        $latestProforma = InvoiceExport::orderBy('proforma_no', 'desc')->first();
    
        // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
        if (!$latestProforma) {
            return 'P0000001';
        }
    
        // Mendapatkan nomor urut proforma terakhir
        $lastProformaNumber = $latestProforma->proforma_no;
    
        // Mengekstrak angka dari nomor proforma terakhir
        $lastNumber = (int)substr($lastProformaNumber, 1);
    
        // Menambahkan 1 ke nomor proforma terakhir
        $nextNumber = $lastNumber + 1;
    
        // Menghasilkan nomor proforma berikutnya dengan format yang benar
        return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    private function getNextInvoiceDS()
    {
        // Mendapatkan nomor proforma terakhir
        $latest = InvoiceExport::where('inv_type', 'OS')->orderBy('inv_no', 'desc')->first();
    
        // Jika tidak ada proforma sebelumnya, kembalikan nomor proforma awal
        if (!$latest) {
            return 'OS0000001';
        }
    
        // Mendapatkan nomor urut proforma terakhir
        $lastInvoice = $latest->inv_no;
    
        // Mengekstrak angka dari nomor proforma terakhir
        $lastNumber = (int)substr($lastInvoice, 3);
    
        // Menambahkan 1 ke nomor proforma terakhir
        $nextNumber = $lastNumber + 1;
    
        // Menghasilkan nomor proforma berikutnya dengan format yang benar
        return 'OS' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
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

    public function Pranota($id)
    {

        $data['title'] = "Pranota";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDS = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDS) {
            $data['admin'] = $adminDS->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.plugging.pranota.ds', $data);
    }

    public function Invoice($id)
    {

        $data['title'] = "Invoice";

        $data['invoice'] = InvoiceExport::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDS = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDS) {
            $data['admin'] = $adminDS->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.plugging.invoice.ds', $data);
    }

    public function ReportExcel(Request $request)
    {
        $startDate = $request->start;
        $endDate = $request->end;
        $invoiceQuery = InvoiceExport::whereDate('invoice_date', '>=', $startDate)
            ->whereDate('invoice_date', '<=', $endDate);
    
        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->whereHas('service', function ($query) {
            $query->where('ie', '=', 'P');
        })->whereNot('lunas', '=', 'N')->orderBy('inv_no', 'asc')->get();
    
        $fileName = 'ReportInvoiceExport-' . $startDate . '-' . $endDate . '.xlsx';

      return Excel::download(new ReportInvoice($invoice), $fileName);
    }

    public function ReportExcelOnce(Request $request)
    {
      $os = $request->os_id;
      $startDate = $request->start;
      $endDate = $request->end;
      $invoiceQuery = Detail::whereHas('service', function ($query) {
        $query->where('ie', '=', 'P');
    })->where('os_id', $os)
      ->whereDate('order_date', '>=', $startDate)
      ->whereDate('order_date', '<=', $endDate);

        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->orderBy('order_date', 'asc')->get();        $fileName = 'ReportInvoiceExport-'.$os.'-'. $startDate . $endDate .'.xlsx';
      return Excel::download(new ReportExport($invoice), $fileName);
    }

}
