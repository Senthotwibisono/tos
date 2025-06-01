<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Http\Controllers\customer\TransactionController;

use App\Models\DOonline;
use App\Models\VMaster;
use App\Models\VVoyage;
use App\Models\Item;
use App\Models\InvoiceImport;
use App\Models\ImportDetail;
use App\Models\JobImport;
use App\Models\Extend;
use App\Models\ExtendDetail;
use App\Models\JobExtend;
use App\Models\InvoiceForm as Form;
use App\Models\ContainerInvoice as Container;

use App\Models\Payment\RefNumber as VA;
use App\Models\Payment\RefDetail;


use App\Models\Customer;
class ServiceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function indexImport()
    {
        $data['title'] = 'Seraching History Invoice';

        $data['vessels'] = VMaster::get();
        return view('billingSystem.service.tracking.index', $data);
    }

    public function contInvoiceData(Request $request)
    {
        // var_dump($request->items);
        $items = Item::whereIn('container_key', $request->items)->get();
        // var_dump($items);
        return dataTables::of($items)
        ->make(true);
    }

    public function contInvoiceDataDetil(Request $request)
    {
        // var_dump($request->container_key);
        // die();
        $containers = Container::where('container_key', $request->container_key)->get();
        $formImport = Form::where('i_e', 'I')->where('done', 'Y')->whereIn('id', $containers->pluck('form_id'))->get();
        $import = InvoiceImport::whereIn('form_id', $formImport->pluck('id'))->whereIn('lunas', ['P', 'Y'])->get();
        $jobImport = JobImport::whereIn('inv_id', $import->pluck('id'))->where('container_key', $request->container_key)->get();
        
        $formExted = Form::where('i_e', 'X')->where('done', 'Y')->whereIn('id', $containers->pluck('form_id'))->get();
        $extend = Extend::whereIn('form_id', $formExted->pluck('id'))->whereIn('lunas', ['P', 'Y'])->get();
        $jobExtend = JobExtend::whereIn('inv_id', $extend->pluck('id'))->where('container_key', $request->container_key)->get();

        $combinedJobs = $jobImport->concat($jobExtend)->values();

        return dataTables::of($combinedJobs)
        ->addColumn('invoice_no', function($combinedJobs){
            return $combinedJobs->Invoice->inv_no;
        })
        ->addColumn('pranota', function($combinedJobs){
            return $combinedJobs->Invoice->proforma_no;
        })
        ->addColumn('job', function($combinedJobs){
            return $combinedJobs->job_no;
        })
        ->make(true);

    }

    public function jadwalKapal()
    {
        $data['vessels'] = VVoyage::where('clossing_date','>=', Carbon::now())->get();
        return view('billingSystem.service.customer.jadwalKapal', $data);
    }

    // Get Data

    public function getCustomer(Request $request)
    {
        $customer = Customer::find($request->customerId);
        if ($customer) {
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $customer,
            ]);
        } else {    
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function doList(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = 5; // Jumlah item per halaman

       $query = DOonline::query();

        // Tambahkan pencarian jika ada
        if ($search) {
            $query->where('do_no', 'like', "%{$search}%");
        }

        // Hitung total data untuk pagination
        $totalCount = $query->count();
        $oldInvoice = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        return response()->json([
            'data' => $oldInvoice,
            'more' => false // Cek apakah ada halaman berikutnya
        ]);
    }

    public function doData(Request $request)
    {
        $do = DOonline::find($request->doId);
        if (Carbon::parse($do->expired) < Carbon::now()) {
            return response()->json([
                'success' => false,
                'message' => 'DO Telah expired',
            ]);
        }
        $customer = Customer::where('code', $do->customer_code)->first();
        if ($do) {
            $items = Item::whereNotIn('ctr_intern_status', ['01', '02'])->where('ctr_i_e_t', 'I')->where('ves_id', $request->vesId)->where('selected_do', 'N')->whereIn('container_no', json_decode($do->container_no, true))->orderBy('disc_date', 'asc')->get();
            if ($items->isEmpty()) {
                return response()->json([
                    'success'=> false,
                    'message' => 'Tidak ada contianr yang dapat di pilih'
                ]);
            }

            $singleCont = $items->first();
            $dischDate = Carbon::parse($singleCont->disc_date);
            $expired = $dischDate->addDays(4);
            $expiredDate = $expired->format('Y-m-d');
            $flagCustomer = $customer->id ? (($customer->id == $request->customerId) ? 'Y' : 'N') : 'Y';
            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'doOnline' => $do,
                    'customer' => [
                        'flagTrue' => $flagCustomer,
                        'dataCustomer' => $customer
                    ]
                ],
            ]); 
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Do tidak ditemukan',
            ]);
        }

    }

    public function ImportPostForm(Request $request)
    {
        $validated = validator::make($request->all(), [    
            'orderService' => 'required',
            'customer' => 'required',
            'doId' => 'required',
            'vessel' => 'required',
            'container' => 'required|array',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()->all(),
            ]);
        }
      
        $items = Item::whereIn('container_key', $request->container)->get();
        $dischDate = $items->min('disc_date');
        $expiredDate = Carbon::parse($dischDate)->addDays(4)->format('Y-m-d');
        try {
            if ($request->input('formId') && $request->formId != null){
                $form = Form::find($request->formId);
                $form = DB::transaction(function() use($request, $dischDate, $expiredDate, $items, $form){
                    $form->update([
                        'expired_date' => $expiredDate,
                        'os_id' => $request->orderService,
                        'cust_id' => $request->customer,
                        'do_id' => $request->doId,
                        'ves_id' => $request->vessel,
                        'i_e' => 'I',
                        'tipe' => 'I',
                        'massa2' => 0,
                        'massa3' => 0,
                        'disc_date' => Carbon::parse($dischDate)->format('Y-m-d'),
                        'discount_ds' => $request->discountDS,
                        'discount_dsk' => $request->discountDSK,
                        'user_id' => Auth::user()->id,
                        'done' => 'N',
                    ]);
        
                    $this->containerStore($form, $items);
                    return $form;
                });
            }else {
                $form = DB::transaction(function() use($request, $dischDate, $expiredDate, $items){
                    $form = Form::create([
                        'expired_date' => $expiredDate,
                        'os_id' => $request->orderService,
                        'cust_id' => $request->customer,
                        'do_id' => $request->doId,
                        'ves_id' => $request->vessel,
                        'i_e' => 'I',
                        'tipe' => 'I',
                        'massa2' => 0,
                        'massa3' => 0,
                        'disc_date' => Carbon::parse($dischDate)->format('Y-m-d'),
                        'discount_ds' => $request->discountDS,
                        'discount_dsk' => $request->discountDSK,
                        'user_id' => Auth::user()->id,
                         'done' => 'N',
                    ]);
        
                    $this->containerStore($form, $items);
                    return $form;
                });
            }
             return response()->json([
                'success' => true,
                'data' => $form->id,
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function containerStore($form, $items)
    {
        try {
            $oldContianer = Container::where('form_id', $form->id)->get();
            if ($oldContianer->isNotEmpty()) {
                foreach ($oldContianer as $old) {
                    $old->delete();
                }
            }
            foreach ($items as $item) {
                Container::create([
                    'container_key' => $item->container_key,
                    'container_no' => $item->container_no,
                    'ctr_size' => $item->ctr_size,
                    'ctr_status' => $item->ctr_status,
                    'form_id' => $form->id,
                    'ves_id' => $item->ves_id,
                    'ves_name' => $item->ves_name,
                    'ctr_type' => $item->ctr_type,
                    'ctr_intern_status' => $item->ctr_intern_status,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }       
    }

    public function searchToPay(Request $request)
    {
        $type = $request->type;

        if ($type) {
            if ($type == 'import') {
                $data = $this->searchImport($request);
                // var_dump($data);
                // die();
                return response()->json([
                    'success' => true,
                    'data' => $data['data'],
                    'another' => $data['another'],
                    'anotherData' => $data['anotherData'],
                    'type' => $data['type'],
                ]);
            }
            if ($type == 'extend') {
                $data = $this->searchExtend($request);
                return response()->json([
                    'success' => true,
                    'data' => $data['data'],
                    'another' => false,
                    'type' => $data['type'],
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something wrong, call the admin!!',
            ]);
        }
    }

    private function searchImport($request)
    {
        $import = InvoiceImport::find($request->id);
        $anotherInvoice = false;
        if ($import->lunas == 'N') {
            $anotherInvoice = InvoiceImport::where('form_id', $import->form_id)->whereNot('id', $import->id)->where('lunas', 'N')->first();
        }
        if ($import->lunas == 'P') {
            $anotherInvoice = InvoiceImport::where('form_id', $import->form_id)->whereNot('id', $import->id)->where('lunas', 'P')->first();
        }
        $response = [
            'data' => $import,
            'another' => ($anotherInvoice) ? true : false,
            'anotherData' => ($anotherInvoice) ? $anotherInvoice : null,
            'type' => 'import',
        ];
        if ($import) {
            return $response;
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something wrong, call the admin!!',
            ]);
        }
    }

    private function searchExtend($request)
    {
        $extend = Extend::find($request->id);
        $anotherInvoice = false;
        $response = [
            'data' => $extend,
            'type' => 'extend',
        ];
        if ($extend) {
            return $response;
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something wrong, call the admin!!',
            ]);
        }
    }

    public function manualPayment(Request $request)
    {
        $action = $request->action;
        if ($action) {
            $data = [
                'data' => $request->all(),
            ];
            // var_dump($data);
            // die();
            if ($request->data['type'] == 'import') {
                $controller = new TransactionController;
                $response = $controller->manualImport($data);
                // var_dump($response);
                if ($response->getStatusCode() == 200) {
                    return response()->json(json_decode($response->getContent(), true));
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memproses di route import',
                        'status'  => $response->getStatusCode(),
                        'body'    => $response->getContent(),
                    ]);
                }
            }

            if ($request->data['type'] == 'extend') {
                $controller = new TransactionController;
                $response = $controller->manualExtend($data);
                // var_dump($response);
                if ($response->getStatusCode() == 200) {
                    return response()->json(json_decode($response->getContent(), true));
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memproses di route import',
                        'status'  => $response->getStatusCode(),
                        'body'    => $response->getContent(),
                    ]);
                }
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Action not definded, call admin soon',
            ]);
        }
    }

    public function cancelInvoice(Request $request)
    {
        $type = $request->type;
        if ($type) {
            if ($type == 'import') {
                $response = $this->cancelImport($request);
                return $response;
            }
            if ($type == 'extend') {
                $response = $this->cancelExtend($request);
                return $response;
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something wrong, call the admin'
            ]);
        }
    }

    private function cancelImport($request)
    {
        $imports = InvoiceImport::where('form_id', $request->id)->get();
        if ($imports->isNotEmpty()) {
            $detils = ImportDetail::where('form_id', $request->id)->get();
            $containers = Container::where('form_id', $request->id)->get();
            $form = Form::find($request->id);
            try {
                DB::transaction(function() use($request, $imports, $detils, $containers, $form) {
                    foreach ($imports as $import) {
                        $import->update([
                            'lunas' => 'C',
                            'invoice_date' => Carbon::now(),
                        ]);
                    }
                    
                    ImportDetail::where('form_id', $request->id)->update(['lunas' => 'C']);
                   
                    // var_dump($containers);
                    if ($containers->isNotEmpty()) {
                        foreach ($containers as $cont) {
                            $item = Item::find($cont->container_key);
                            $item->update([
                                'invoice_no' => null,
                                'job_no' => null,
                                'order_service' => null,
                                'os_id' => null,
                                'selected_do' => 'N',
                            ]);
                        }
                    }
                    
                    $form->update([
                        'done' => 'C'
                    ]);
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Data canceled'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something wrong in: ' . $th->getMessage(),
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    private function cancelExtend($request)
    {
        $extends = Extend::where('form_id', $request->id)->get();
        if ($extends->isNotEmpty()) {
            $detils = ExtendDetail::where('form_id', $request->id)->get();
            $containers = Container::where('form_id', $request->id)->get();
            $form = Form::find($request->id);
            if ($form->tipe == 'I') {
                $query = InvoiceImport::find($form->do_id);
                $jobQuery = JobImport::where('inv_id', $query->id);
            }elseif ($form->tipe == 'P') {
                $query = Extend::find($form->do_id);
                $jobQuery = JobExtend::where('inv_id', $query->id);
            }
            try {
                DB::transaction(function() use($request, $extends, $detils, $containers, $form, $jobQuery) {
                    foreach ($extends as $extend) {
                        $extend->update([
                            'lunas' => 'C',
                            'invoice_date' => Carbon::now(),
                        ]);
                    }
                    
                    ExtendDetail::where('form_id', $request->id)->update(['lunas' => 'C']);
                   
                    // var_dump($containers);
                    if ($containers->isNotEmpty()) {
                        foreach ($containers as $cont) {
                            $item = Item::find($cont->container_key);
                            $item->update([
                                'invoice_no' => null,
                                'job_no' => null,
                                'order_service' => null,
                                'os_id' => null,
                                'selected_do' => 'N',
                            ]);

                            $jobQuery->where('container_key', $cont->container_key)->update(['extend_flag' => 'N']);
                        }
                    }
                    
                    $form->update([
                        'done' => 'C'
                    ]);
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Data canceled'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something wrong in: ' . $th->getMessage(),
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function createVA(Request $request)
    {
        $data = $request->data;
        if ($data['type'] == 'import') {
            $query = new InvoiceImport;
        }elseif ($data['type'] == 'extend') {
             $query = new Extend;
        }elseif (in_array($data['type'], ['others', 'plugging', 'export'])) {
            # code...
        }elseif (condition) {
            # code...
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something get wrong when checking type of transaction, call the admin',
            ]);
        }
        $response = $this->makeVirtualAccount($data, $query);
        return $response;
    }

    private function makeVirtualAccount($data, $query)
    {
        $checking = $this->checkingOldVA($data, $query);
        if ($checking) {
            return $checking;
        }
        $dataInvoice = $query->find($data['id']);
        $billingAmount = $dataInvoice->grand_total;
        if ($data['couple'] == 'Y') {
            $dataInvoiceOther = $query->where('form_id', $dataInvoice->form_id)->whereNot('id', $dataInvoice->id)->first();
            $billingAmount += $dataInvoiceOther->grand_total;
        }
        
        try {
            $newVa = DB::transaction(function() use($billingAmount, $dataInvoice, $data){
                return VA::create([
                    'virtual_account' => $this->virtualAccount(),
                    'expired_va' => Carbon::now()->addHours(6),
                    'invoice_type' => $data['type'],
                    'customer_name' => $dataInvoice->cust_name,
                    'customer_id' => $dataInvoice->cust_id,
                    'description' => $dataInvoice->os_name,
                    'billing_amount' => $billingAmount,
                    'status' => 'N',
                    'user_id' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            });
            $this->createVaDetail($newVa, $data, $query);

            return response()->json([
                'success' => true,
                'message' => 'VA Berhasil dibuat',
                'data' => $newVa,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong in : ' . $th->getMessage(),
            ]);
        }   
    }

    private function virtualAccount()
    {
        $prefix = '56732'; // kode perusahaan/bank Mandiri
        $year = date('y'); // Tahun 2 digit, misal 2025 => 25
        $month = date('m'); 
        do {
            // Generate 16 digit angka random
            $randomDigits = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $generateVa = $prefix . $year . $month . $randomDigits;
        } while (VA::where('virtual_account', $generateVa)->exists());

        return $generateVa;
    }

    private function createVaDetail($newVa, $data, $query)
    {
        $dataInvoice = $query->find($data['id']);
        DB::transaction(function() use($newVa, $dataInvoice){
            RefDetail::create([
                'va_id' => $newVa->id,
                'inv_id' => $dataInvoice->id,
                'invoice_ie' => $dataInvoice->Form->i_e,
                'proforma_no' => $dataInvoice->proforma_no,
                'invoice_type' => $dataInvoice->inv_type,
                'amount' => $dataInvoice->grand_total,
            ]);

            $dataInvoice->update([
                'va' => $newVa->virtual_account,
            ]);
        });

        if ($data['couple'] == 'Y') {
            $dataInvoiceOther = $query->where('form_id', $dataInvoice->form_id)->whereNot('id', $dataInvoice->id)->first();
            if ($dataInvoiceOther) {
                DB::transaction(function() use($newVa, $dataInvoiceOther){
                    RefDetail::create([
                        'va_id' => $newVa->id,
                        'inv_id' => $dataInvoiceOther->id,
                        'invoice_ie' => $dataInvoiceOther->Form->i_e,
                        'proforma_no' => $dataInvoiceOther->proforma_no,
                        'invoice_type' => $dataInvoiceOther->inv_type,
                        'amount' => $dataInvoiceOther->grand_total,
                    ]);
                    $dataInvoiceOther->update([
                        'va' => $newVa->virtual_account,
                    ]);
                });
            }
        }
    }

    private function checkingOldVA($data, $query)
    {
        $dataOld = $query->find($data['id']);
        $oldVa = VA::where('virtual_account', $dataOld->va)->first();
        if ($oldVa && !in_array($oldVa->status, ['C', 'Y'])) {
            if (Carbon::parse($oldVa->expired_va)->greaterThan(Carbon::now())) {
                return response()->json([
                    'success' => false,
                    'message' => 'VA untuk invoice yg anda pilih masih aktif, dengan nomor VA : ' . $oldVa->virtual_account,
                    'status' => 30,
                    'data' => $oldVa,
                ]);
            }
            
            $oldVa->update([
                'status' => 'E',
            ]);
        }

        if ($data['couple'] == 'Y') {
           $dataOther = $query->where('form_id', $dataOld->form_id)->whereNot('id', $dataOld->id)->first();
           $otherOldVa = VA::where('virtual_account', $dataOther->va)->first();
           if ($otherOldVa && !in_array($otherOldVa->status, ['C', 'Y'])) {
                if (Carbon::parse($otherOldVa->expired_va)->greaterThan(Carbon::now())) {
                    return response()->json([
                        'success' => false,
                        'message' => 'VA untuk invoice yg anda pilih masih aktif, dengan nomor VA : ' . $otherOldVa->virtual_account,
                        'status' => 30,
                        'data' => $otherOldVa,
                    ]);
                }

                $otherOldVa->update([
                    'status' => 'E',
                ]);
            }
        }

        return null;
    }

    // Extend
    public function OldInvoiceList(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = 5;   

        // Query untuk import
        $importQuery = InvoiceImport::whereIn('lunas', ['Y', 'P'])
            ->where('inv_type', 'DS')
            ->select('inv_no', 'id', 'form_id')
            ->groupBy('inv_no', 'id', 'form_id');   

        // Query untuk extend
        $extendQuery = Extend::whereIn('lunas', ['Y', 'P'])
            ->select('inv_no', 'id', 'form_id')
            ->groupBy('inv_no', 'id', 'form_id');   

        // Tambahkan pencarian jika ada
        if ($search) {
            $importQuery->where('inv_no', 'like', "%{$search}%");
            $extendQuery->where('inv_no', 'like', "%{$search}%");
        }   

        // Gabungkan query
        $unionQuery = $importQuery->union($extendQuery);    

        // Bungkus union ke dalam subquery agar bisa dipaginate
        $wrappedQuery = DB::query()->fromSub($unionQuery, 'old_invoices');  

        // Hitung total dan ambil data dengan pagination
        $totalCount = $wrappedQuery->count();
        $oldInvoice = $wrappedQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();    

        return response()->json([
            'data' => $oldInvoice,
            'more' => false,
        ]);
    }

    public function OldInvoiceData(Request $request)
    {
        $code = $request->id;
        preg_match('/[A-Za-z\-]+/', $code, $letterPart);
        preg_match('/\d+/', $code, $numberPart);

        $prefix = $letterPart[0]; // Hasil: "DS-P"
        $number = $numberPart[0]; // Hasil: "00001"

       if ($prefix == 'DS-P') {
        $query = Extend::where('inv_no', $request->id)->first();
        $queryJob = new JobExtend;
       } else {
        $query = InvoiceImport::where('inv_no', $request->id)->first();
        $queryJob = new JobImport;
       }
       if (!$query) {
        return response()->json([
            'success' => false,
            'message' => 'Something wrong in reading invoice, call the admin',
        ]);
       }

       $form = Form::find($query->form_id);
       $oldExpired = Carbon::parse($form->expired_date)->format('Y-m-d');
       $order = $form->service->order;
       $job = $queryJob->where('inv_id', $query->id)->get();
       return response()->json([
        'success' => true,
        'data' => [
            'oldExpired' => $oldExpired,
            'oldForm' => $form,
            'containers' => $job,
            'order' => $order
        ],
       ]);
       
    }

    public function ExtendPostForm(Request $request)
    {
        $validated = validator::make($request->all(), [    
            'orderService' => 'required',
            'customer' => 'required',
            'oldInvoice' => 'required',
            'expired' => 'required',
            'container' => 'required|array',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()->all(),
            ]);
        }

        $code = $request->oldInvoice;
        preg_match('/[A-Za-z\-]+/', $code, $letterPart);
        preg_match('/\d+/', $code, $numberPart);

        $prefix = $letterPart[0]; // Hasil: "DS-P"
        $number = $numberPart[0]; // Hasil: "00001"

       if ($prefix == 'DS-P') {
        $query = Extend::where('inv_no', $request->oldInvoice)->first();
        $queryJob = new JobExtend;
        $tipe = 'P';
       } else {
        $query = InvoiceImport::where('inv_no', $request->oldInvoice)->first();
        $queryJob = new JobImport;
        $tipe = 'I';
       }
        if (!$query) {
        return response()->json([
            'success' => false,
            'message' => 'Something wrong in reading invoice, call the admin',
        ]);
       }

        $jobs = $queryJob->where('inv_id', $query->id)->whereIn('id', $request->container)->get();
        $grouped = $jobs->groupBy('active_to');
        $mostCommonActiveTo = $grouped->sortByDesc(function ($group) {
            return $group->count();
        })->keys()->first();        
        $differentContainers = $jobs->filter(function ($item) use ($mostCommonActiveTo) {
            return $item->active_to != $mostCommonActiveTo;
        })->pluck('container_no');      

        if ($differentContainers->isNotEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Beberapa container memiliki active_to yang berbeda dari mayoritas, ' . $differentContainers->values(),
                'containers' => $differentContainers->values()
            ]);
        }

        $singleJob = $jobs->first();
        $discDate = Carbon::parse($singleJob->active_to);
        $expiredForm = Carbon::parse($request->expired);
        $oldForm = Form::find($query->form_id);
        
        $items = Item::whereIn('container_key', $jobs->pluck('container_key'))->get();

        $massa = $this->hitungMassa($discDate, $expiredForm, $oldForm);
        try {
            if ($request->has('formId')) {
                $form = Form::find($request->formId);
                $form = DB::transaction(function() use($query, $tipe, $form, $request, $massa, $discDate, $expiredForm, $oldForm, $items){
                    $form->update([
                        'expired_date' => $request->expired,
                        'os_id' => $request->orderService,
                        'cust_id' => $request->customer,
                        'do_id' => $query->id,
                        'ves_id' => $oldForm->ves_id,
                        'disc_date' => $discDate,
                        'tipe' => $tipe,
                        'massa2' => $massa['massa2'],
                        'massa3' => $massa['massa3'],
                        'i_e' => 'X',
                        'user_id' => Auth::user()->id,
                        'done' => 'N',
                        'discount_ds' => $request->discount,
                    ]);
                    $this->containerStore($form, $items);
                    return $form;
                });
            }else {
                $form = DB::transaction(function() use($query, $tipe, $request, $massa, $discDate, $expiredForm, $oldForm, $items){
                    $form = Form::create([
                        'expired_date' => $request->expired,
                        'os_id' => $request->orderService,
                        'cust_id' => $request->customer,
                        'do_id' => $query->id,
                        'ves_id' => $oldForm->ves_id,
                        'disc_date' => $discDate,
                        'tipe' => $tipe,
                        'massa2' => $massa['massa2'],
                        'massa3' => $massa['massa3'],
                        'i_e' => 'X',
                        'user_id' => Auth::user()->id,
                        'done' => 'N',
                        'discount_ds' => $request->discount,
                    ]);
                    $this->containerStore($form, $items);
                    return $form;
                });
            }
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di simpan',
                'data' => $form->id,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }

    }

    private function hitungMassa($discDate, $expiredForm, $oldForm)
    {
        $interval = $discDate->startOfDay()->diff($expiredForm->startOfDay());
        $jumlahHari = ($interval->days);
        // var_dump($jumlahHari, $discDate, $expiredForm);
        // die();
        $massa1 = 0;
        $massa2 = 0;
        $massa3 = 0;
        
        if ($oldForm->massa3 == null) {
            if ($oldForm->massa2 == null) {
                if ($jumlahHari >= 6) {
                    $massa2 = 5;
                    $massa3 = $jumlahHari - 5;
                } else {
                    $massa2 = $jumlahHari;
                    $massa3 = 0;
                }
            } elseif ($oldForm->massa2 < 5) {
                $remainingMassa2Days = 5 - $oldForm->massa2;
                if ($jumlahHari > $remainingMassa2Days) {
                    $massa2 = $remainingMassa2Days;
                    $massa3 = $jumlahHari - $remainingMassa2Days;
                } else {
                    $massa2 = $jumlahHari;
                    $massa3 = 0;
                }
            } else {
                $massa2 = 0;
                $massa3 = $jumlahHari;
            }
        } else {
            $massa2 = 0;
            $massa3 = $jumlahHari;
        }

        $massa = [
            'jumlahHari' => $jumlahHari,
            'massa1' => $massa1,
            'massa2' => $massa2,
            'massa3' => $massa3,
        ];

        return $massa;
    }

}
