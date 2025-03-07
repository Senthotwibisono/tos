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
use DataTables;

class RentalController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function billingMain()
    {
        $data['title'] = "Rental & Repair Billing System";
        $data['invoice'] = InvoiceExport::orderBy('order_at', 'asc')->orderBy('lunas', 'asc')->get();

        $data['service'] = OS::where('ie', '=', 'R')->orderBy('id', 'asc')->get();
        $data['unPaids'] = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'R');
        })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'asc')->get();
        $data['piutangs'] = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'R');
        })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'asc')->get();

        return view('billingSystem.rental-repair.billing.main', $data);
    }

    public function dataTable(Request $request)
    {
        $invoice = InvoiceExport::whereHas('service', function ($query) {
            $query->where('ie', '=', 'R');
        })->whereNot('form_id', '=', '')->orderBy('order_at', 'desc');
        
        if ($request->has('type')) {
            if ($request->type == 'unpaid') {
                $invoice = InvoiceExport::whereHas('service', function ($query) {
                    $query->where('ie', '=', 'R');
                })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'desc');
            }

            if ($request->type == 'piutang') {
                $invoice = InvoiceExport::whereHas('service', function ($query) {
                    $query->where('ie', '=', 'R');
                })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'desc');
            }
        }

        if ($request->has('os_id')) {
            $invoice = InvoiceExport::whereNot('form_id', '=', '')->where('os_id', $request->os_id)->orderBy('order_at', 'desc')->orderBy('lunas', 'asc');
        }

        $inv = $invoice->get();
        return DataTables::of($inv)
        ->addColumn('proforma', function($inv) {
            return $inv->proforma_no ?? '-';
        })
        ->addColumn('customer', function($inv){
            return $inv->cust_name ?? '-';
        })
        ->addColumn('service', function($inv){
            return $inv->os_name ?? '-';
        })
        ->addColumn('type', function($inv){
            return $inv->inv_type ?? '-';
        })
        ->addColumn('orderAt', function($inv){
            return $inv->order_at ?? '-';
        })
        ->addColumn('status', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-danger text-white">Not Paid</span>';
            }elseif ($inv->lunas == 'P') {
                return '<span class="badge bg-warning text-white">Piutang</span>';
            }elseif ($inv->lunas == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('pranota', function($inv){
            return '<a type="button" href="/renta&repair-pranota-'.$inv->id.'" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>';
        })
        ->addColumn('invoice', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/renta&repair-invoice-'.$inv->id.'" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>';
            }
        })
        ->addColumn('job', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/job/export-'.$inv->id.'" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>';
            }
        })
        ->addColumn('action', function($inv){
            return '<button type="button" id="pay" data-id="'.$inv->id.'" class="btn btn-sm btn-success pay"><i class="fa fa-cogs"></i></button>';
        })
        ->addColumn('delete', function($inv){
            if ($inv->lunas == 'N') {
                return '<button type="button" data-id="'.$inv->form_id.'" class="btn btn-sm btn-danger Delete"><i class="fa fa-trash"></i></button>';
            }else {
                return '-';
            }
        })
        ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'delete'])
        ->make(true);
    }

    public function deliveryMenu()
    {
        $data['title'] = "Rental & Repair Form Menu";
        $data['formInvoiceImport'] = Form::where('i_e', 'R')->where('done', '=', 'N')->get();

        return view('billingSystem.rental-repair.form.main', $data);
    }

    public function FormIndex()
    {
        $user = Auth::user();
        $data['title'] = "Rental & Repair Form";
        $data["user"] = $user->id;

        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'R')->get();
        $data['vessel'] = VVoyage::get();

        return view('billingSystem.rental-repair.form.create', $data);
    }

    public function FormEdit($id)
    {
        $form = Form::where('id', $id)->first();
        $data['form'] = $form;
        $data['discDate'] = Carbon::parse($form->disc_date)->format('d-m-Y h:i K');
        $data['expDate'] = Carbon::parse($form->expired_date)->format('d-m-Y h:i K');
        // dd($data['discDate'], $data['expDate']);
        $user = Auth::user();
        $data['title'] = "Rental & Repair Form";
        $data["user"] = $user->id;
        $data['customer'] = Customer::get();
        $data['orderService'] = OS::where('ie', '=', 'R')->get();
        $data['vessel'] = VVoyage::get();
        // $data['containerInvoice'] = Container::where('form_id', $id)->get();
        return view('billingSystem.rental-repair.form.edit', $data);
    }

    public function getContainer(Request $request)
    {
        $kapal = $request->kapal;
       
       $cont = Item::where('ves_id', $kapal)->whereNot('container_no', '=', '')->get();
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

    public function getOrder(Request $request)
    {
        $os = OS::where('id', $request->id)->first();
        return response()->json([
            'success' => true,
            'message' => 'Tidak ada container yang dapat digunakan !!',
            'data' => $os
        ]);
    }

    public function FormStore(Request $request)
    {
        // $contSelect = $request->container;
       
        
        $invoice = Form::create([
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'ves_id'=> $request->ves_id,
            'i_e'=>'R',
            'done'=>'N',
            'discount_ds'=>$request->discount_ds,
            'discount_dsk'=>$request->discount_dsk,
            'tarif'=>$request->tarif,
            'palka'=>$request->palka,
            'keterangan' => $request->keterangan,
        ]);

    //   $service = OS::where('id', $invoice->os_id)->first();
    //   if ($service->order != 'P') {
    //     foreach ($contSelect as $cont) {
    //         $item = Item::where('container_key', $cont)->first();
    //         $tarif = $request->input("tarif-$cont");
    //         $contInvoice = Container::create([
    //             'container_key'=>$item->container_key,
    //             'container_no'=>$item->container_no,
    //             'ctr_size'=>$item->ctr_size,
    //             'ctr_status'=>$item->ctr_status,
    //             'form_id'=>$invoice->id,
    //             'ves_id'=>$item->ves_id,
    //             'ves_name'=>$item->ves_name,
    //             'ctr_type'=>$item->ctr_type,
    //             'ctr_intern_status'=>$item->ctr_intern_status,
    //             'gross'=>$item->gross,
    //         ]);
    //     }
    //   }
       


        return redirect()->route('rental-repair-preinvoice', ['id' => $invoice->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }

    public function FormUpdate(Request $request)
    {
       $invoice = Form::where('id', $request->form_id)->first();
      
       $service = OS::where('id', $request->order_service)->first();
    //     if ($service->order != 'P') {
    //      $oldCont = Container::where('form_id', $invoice->id)->get();
    //      foreach ($oldCont as $cont) {
    //          $cont->delete();
    //      }   

    //      $newContainer = $request->container;
    //      foreach ($newContainer as $cont) {
    //          $item = Item::where('container_key', $cont)->first();
    //          $tarif = $request->input("tarif-$cont");
    //          $contInvoice = Container::create([
    //              'container_key'=>$item->container_key,
    //              'container_no'=>$item->container_no,
    //              'ctr_size'=>$item->ctr_size,
    //              'ctr_status'=>$item->ctr_status,
    //              'form_id'=>$invoice->id,
    //              'ves_id'=>$item->ves_id,
    //              'ves_name'=>$item->ves_name,
    //              'ctr_type'=>$item->ctr_type,
    //              'ctr_intern_status'=>$item->ctr_intern_status,
    //              'gross'=>$item->gross,
    //              'palka'=>$request->palka,
    //          ]);
    //      }

    //    }
       
       
        $invoice->update([
            'os_id'=>$request->order_service,
            'cust_id'=>$request->customer,
            'ves_id'=> $request->ves_id,
            'i_e'=>'R',
            'done'=>'N',
            'discount_ds'=>$request->discount_ds,
            'discount_dsk'=>$request->discount_dsk,
            'tarif'=>$request->tarif,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('rental-repair-preinvoice', ['id' => $invoice->id])->with('success', 'Silahkan Lanjut ke Tahap Selanjutnya');
    }

    public function preinvoice($id)
    {
        $form = Form::where('id', $id)->first();
        $data['title'] = 'Invoice '.$form->service->name;
        $bigOS = OS::where('id', $form->os_id)->first();
        $data['customer'] = Customer::where('id', $form->cust_id)->first();
        $data['discDate'] = Carbon::parse($form->disc_date)->format('d-m-Y h:i K');
        $data['expDate'] = Carbon::parse($form->expired_date)->format('d-m-Y h:i K');

        $data['service'] = OS::where('id', $form->os_id)->first();
        // $data['selectCont'] = Container::where('form_id', $id)->get();
        $containerInvoice = Container::where('form_id', $id)->get();
        // $data['jumlahCont'] = $containerInvoice->count();
        $groupedBySize = $containerInvoice->groupBy('ctr_size');
        $ctrGroup = $groupedBySize->map(function ($sizeGroup) {
            return $sizeGroup->groupBy('ctr_status');
        });
        $data['ctrGroup'] = $ctrGroup;
        // $discDateCarbon = Carbon::parse($form->disc_date);
        // $expDateCarbon = Carbon::parse($form->expired_date);
        // $secondsDifference = $discDateCarbon->diffInSeconds($expDateCarbon);
        // $shiftCount = ceil($secondsDifference / 28800);
        // dd($shiftCount);
        $osDS = OSDetail::where('os_id', $form->os_id)->where('type', '=', 'OS')->get();

      
            $data['adminDS'] = 0;
            $resultsDS = collect(); // Use a collection to store the results
            $service = $osDS;
            $singleTarif = MT::where('os_id', $bigOS->id)->first();
            $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
                ->where('count_by', 'O')
                ->first();
            if ($singleTarifDetail) {
                $data['adminDS'] = 0;
            }
            $data['totalDS'] = $form->tarif;
            $data['discountDS'] = $form->discount_ds;
            $data['pajakDS'] = (($data['totalDS'] + $data['adminDS']) - $data['discountDS']) * 11 / 100;
            $data['grandTotalDS'] = (($data['totalDS'] + $data['adminDS']) - $data['discountDS']) + $data['pajakDS'];
     

        return view('billingSystem.rental-repair.form.pre-invoice', compact('form'), $data);
    }

    public function SubmitInvoice(Request $request)
    {
        $form = Form::where('id', $request->formId)->first();
        $bigOS = OS::where('id', $form->os_id)->first();
        $data['customer'] = Customer::where('id', $form->cust_id)->first();
        $data['discDate'] = Carbon::parse($form->disc_date)->format('d-m-Y h:i K');
        $data['expDate'] = Carbon::parse($form->expired_date)->format('d-m-Y h:i K');

        $data['service'] = OS::where('id', $form->os_id)->first();
        // $data['selectCont'] = Container::where('form_id', $request->formId)->get();
        // $containerInvoice = Container::where('form_id', $request->formId)->get();
        // $groupedBySize = $containerInvoice->groupBy('ctr_size');
        // $ctrGroup = $groupedBySize->map(function ($sizeGroup) {
        //     return $sizeGroup->groupBy('ctr_status');
        // });
        // $data['ctrGroup'] = $ctrGroup;
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
           
            'total'=>$request->totalDS,
            'admin'=>$request->adminDS,
            'discount'=>$request->discountDS,
            'pajak'=>$request->pajakDS,
            'grand_total'=>$request->grandTotalDS,
            'order_by'=> Auth::user()->name,
            'order_at'=> Carbon::now(),
          
            
        ]);
        $admin = 0;
        // foreach ($osDS as $service) {
        //     foreach ($containerInvoice as $cont) {
        //         $tarif = MT::where('os_id', $bigOS->id)->first();
        //         $tarifDetail = MTDetail::where('master_tarif_id', $tarif->id)
        //             ->where('master_item_id', $service->master_item_id)
        //             ->first();
        //         if ($tarifDetail) {
        //             if ($tarifDetail->count_by != 'O') {
        //                 $detailImport = Detail::create([
        //                  'inv_id'=>$invoiceDS->id,
        //                  'inv_type'=>$invoiceDS->inv_type,
        //                  'keterangan'=>$form->service->name,
        //                  'ukuran'=>$cont->ctr_size,
        //                  'jumlah'=>'1',
        //                  'satuan'=>'unit',
        //                  'expired_date'=>$form->expired_date,
        //                  'order_date'=>$invoiceDS->order_at,
        //                  'lunas'=>'N',
        //                  'cust_id'=>$form->cust_id,
        //                  'cust_name'=>$form->customer->name,
        //                  'os_id'=>$form->os_id,
        //                  'jumlah_hari' => $shiftCount,
        //                  'master_item_id'=>$service->master_item_id,
        //                  'master_item_name'=>$service->master_item_name,
        //                  'kode'=>$service->kode,
        //                  'tarif'=>$form->tarif,
        //                  'total'=>$form->tarif,
        //                  'form_id'=>$form->id,
        //                  'count_by'=>$tarifDetail->count_by,
        //                 ]);
        //             }
        //         }    
        //     }
        //     $singleTarif = MT::where('os_id', $bigOS->id)->first();
        //         $singleTarifDetail = MTDetail::where('master_tarif_id', $singleTarif->id)
        //             ->where('master_item_id', $service->master_item_id)
        //             ->where('count_by', 'O')
        //             ->first();
        //         if ($singleTarifDetail) {
        //             $detailImport = Detail::create([
        //                 'inv_id'=>$invoiceDS->id,
        //                 'inv_type'=>$invoiceDS->inv_type,
        //                 'keterangan'=>$form->service->name,
        //                 'ukuran'=> '0',
        //                 'jumlah'=> 1,
        //                 'satuan'=>'unit',
        //                 'expired_date'=>$form->expired_date,
        //                 'order_date'=>$invoiceDS->order_at,
        //                 'lunas'=>'N',
        //                 'cust_id'=>$form->cust_id,
        //                 'cust_name'=>$form->customer->name,
        //                 'os_id'=>$form->os_id,
        //                 'jumlah_hari'=>'0',
        //                 'master_item_id'=>$service->master_item_id,
        //                 'master_item_name'=>$service->master_item_name,
        //                 'kode'=>$service->kode,
        //                 'tarif'=>$singleTarifDetail->tarif,
        //                 'total'=>$singleTarifDetail->tarif,
        //                 'form_id'=>$form->id,
        //                 'count_by'=>'O',
        //                ]);
        //         }
        // }
       

       $form->update([
        'done'=>'Y',
       ]);
       
       return redirect()->route('rental-repair-main')->with('success', 'Menunggu Pembayaran');

       
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
        // $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDS = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDS) {
            $data['admin'] = $adminDS->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.rental-repair.pranota.ds', $data);
    }

    public function Invoice($id)
    {

        
        $data['invoice'] = InvoiceExport::where('id', $id)->first();
        $data['form'] = Form::where('id', $data['invoice']->form_id)->first();
        
        $data['title'] = "Invoice " .$data['form']->service->name;
        
        // $data['contInvoice'] = Container::where('form_id', $data['invoice']->form_id)->orderBy('ctr_size', 'asc')->get();
        $invDetail = Detail::where('inv_id', $id)->whereNot('count_by', '=', 'O')->orderBy('count_by', 'asc')->orderBy('kode', 'asc')->get();
        $data['invGroup'] = $invDetail->groupBy('ukuran');

        $data['admin'] = 0;
        $adminDS = Detail::where('inv_id', $id)->where('count_by', '=', 'O')->first();
        if ($adminDS) {
            $data['admin'] = $adminDS->total;
        }
        $data['terbilang'] = $this->terbilang($data['invoice']->grand_total);

        return view('billingSystem.rental-repair.invoice.ds', $data);
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
            $query->where('ie', '=', 'R');
        })->whereNot('lunas', '=', 'N')->orderBy('inv_no', 'asc')->get();
    
        $fileName = 'ReportInvoiceExport-' . $startDate . '-' . $endDate . '.xlsx';

      return Excel::download(new ReportInvoice($invoice), $fileName);
    }

    public function ReportExcelOnce(Request $request)
    {
      $os = $request->os_id;
      $startDate = $request->start;
      $endDate = $request->end;
      $invoiceQuery = InvoiceExport::whereHas('service', function ($query) {
        $query->where('ie', '=', 'R');
    })->where('os_id', $os)
      ->whereDate('invoice_date', '>=', $startDate)
      ->whereDate('invoice_date', '<=', $endDate);

        // Cek apakah checkbox 'inv_type' ada dalam request dan tidak kosong
        if ($request->has('inv_type') && !empty($request->inv_type)) {
            // Tambahkan filter berdasarkan 'inv_type'
            $invoiceQuery->whereIn('inv_type', $request->inv_type);
        }
    
        $invoice = $invoiceQuery->orderBy('invoice_date', 'asc')->get();        $fileName = 'ReportInvoiceExport-'.$os.'-'. $startDate . $endDate .'.xlsx';
      return Excel::download(new ReportInvoice($invoice), $fileName);
    }

    public function Paid(Request $request)
    {
        $id = $request->inv_id;

        $invoice = InvoiceExport::where('id', $id)->first();
        if ($invoice->lunas == 'N') {
            $invDate = Carbon::now();
        }else {
            $invDate = $invoice->invoice_date;
        }
        if ($invoice->inv_no == null) {
            if ($invoice->inv_type == 'OSK' ) {
                $invoiceNo = $this->getNextInvoiceDSK();
            }else {
                $invoiceNo = $this->getNextInvoiceDS();
            }
       }else {
         $invoiceNo = $invoice->inv_no;
       }
        // $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        $bigOS = OS::where('id', $invoice->os_id)->first();


        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'Y',
            'inv_no'=>$invoiceNo,
            ]);
        }

        $invoice->update([
            'lunas' => 'Y',
            'inv_no'=>$invoiceNo,
            'lunas_at'=> Carbon::now(),
            'invoice_date'=> $invDate,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
        ]);
        
    }

    public function Piutang(Request $request)
    {
        $id = $request->inv_id;

        $invoice = InvoiceExport::where('id', $id)->first();
        if ($invoice->inv_no == null) {
            if ($invoice->inv_type == 'OSK' ) {
                $invoiceNo = $this->getNextInvoiceDSK();
            }else {
                $invoiceNo = $this->getNextInvoiceDS();
            }
       }else {
         $invoiceNo = $invoice->inv_no;
       }
        // $containerInvoice = Container::where('form_id', $invoice->form_id)->get();
        $bigOS = OS::where('id', $invoice->os_id)->first();
        

        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'P',
            'inv_no'=>$invoiceNo,
            ]);
        }

        $invoice->update([
            'lunas' => 'P',
            'inv_no'=>$invoiceNo,
            'piutang_at'=> Carbon::now(),
            'invoice_date'=> Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'updated successfully!',
        ]);
    }

    public function Cancel(Request $request)
    {
        $id = $request->inv_id;
        $invoice = InvoiceExport::where('id', $id)->first();
        // var_dump($invoice);
        // die;
        $invoice->update([
            'lunas' => 'C',
            'total'=> 0,
            'discount'=> 0,
            'pajak'=> 0,
            'grand_total'=> 0,
            
        ]);

        $details = Detail::where('inv_id', $id)->get();
        foreach ($details as $detail) {
            $detail->update([
            'lunas'=>'C',
            'jumlah'=>0,
            'jumlah_hari'=> 0,
            'tarif'=>0,
            'total'=>0,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invoice Berhasil di Cancel!',
        ]);
    }
}
