<?php

namespace App\Http\Controllers\customer\extend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerMainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\InvoiceImport as Import;
use App\Models\Extend as Extend;
use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\OSDetail;
use App\Models\MTDetail;
use App\Models\InvoiceForm as Form;
use App\Models\ExtendDetail as Detail; 
use App\Models\ContainerInvoice as Container;
use App\Models\MasterUserInvoice as MUI;
use App\Models\Customer;
use App\Models\DOonline;
use App\Models\Item;

use App\Models\Payment\RefNumber as VA;
use App\Models\Payment\RefDetail;

use App\Models\JobImport;
use App\Models\JobExtend;
use App\Models\VVoyage;

use DataTables;

use Auth;
use Carbon\Carbon;

class CustomerExtendController extends CustomerMainController
{
    // public function indexUnpaid()
    // {
    //     $data['title'] = 'Perpanjangan Unpaid List';

    //     return view('customer.extend.detil.unpaid', $data);
    // }

    // public function indexPiutang()
    // {
    //     $data['title'] = 'Perpanjangan Piutang List';

    //     return view('customer.extend.detil.piutang', $data);
    // }

    // public function indexService(Request $request)
    // {
    //     $os = OS::find($request->id);
    //     $data['title'] = 'Perpanjangan '.$os->name.' List'; 

    //     $data['osId'] = $os->id;
    //     return view('customer.extend.detil.service', $data);
    // }

    // public function dataService(Request $request)
    // {
    //     $invoice = $this->extend->whereHas('service', function ($query) {
    //         $query->where('ie', '=', 'X');
    //     })->whereNot('form_id', '=', '')->orderBy('order_at', 'desc');
        
    //     if ($request->has('type')) {
    //         if ($request->type == 'unpaid') {
    //             $invoice = $this->extend->whereHas('service', function ($query) {
    //                 $query->where('ie', '=', 'X');
    //             })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'desc');
    //         }

    //         if ($request->type == 'piutang') {
    //             $invoice = $this->extend->whereHas('service', function ($query) {
    //                 $query->where('ie', '=', 'X');
    //             })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'desc');
    //         }
    //     }

    //     if ($request->has('os_id')) {
    //         $invoice = $this->extend->whereNot('form_id', '=', '')->where('os_id', $request->os_id)->orderBy('order_at', 'desc')->orderBy('lunas', 'asc');
    //     }

    //     $inv = $invoice->get();
    //     return DataTables::of($inv)
    //     ->addColumn('proforma', function($inv) {
    //         return $inv->proforma_no ?? '-';
    //     })
    //     ->addColumn('customer', function($inv){
    //         return $inv->cust_name ?? '-';
    //     })
    //     ->addColumn('service', function($inv){
    //         return $inv->os_name ?? '-';
    //     })
    //     ->addColumn('type', function($inv){
    //         return $inv->inv_type ?? '-';
    //     })
    //     ->addColumn('orderAt', function($inv){
    //         return $inv->order_at ?? '-';
    //     })
    //     ->addColumn('status', function($inv){
    //         if ($inv->lunas == 'N') {
    //             return '<span class="badge bg-danger text-white">Not Paid</span>';
    //         }elseif ($inv->lunas == 'P') {
    //             return '<span class="badge bg-warning text-white">Piutang</span>';
    //         }elseif ($inv->lunas == 'Y') {
    //             return '<span class="badge bg-success text-white">Paid</span>';
    //         }elseif ($inv->lunas == 'C') {
    //             return '<span class="badge bg-danger text-white">Canceled</span>';
    //         }
    //     })
    //     ->addColumn('pranota', function($inv){
    //         return '<a type="button" href="/pranota/extend-'.$inv->id.'" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>';
    //     })
    //     ->addColumn('invoice', function($inv){
    //         if ($inv->lunas == 'N') {
    //             return '<span class="badge bg-info text-white">Paid First!!</span>';
    //         }elseif ($inv->lunas == 'C') {
    //             return '<span class="badge bg-danger text-white">Canceled</span>';
    //         }else {
    //             return '<a type="button" href="/invoice/extend-'.$inv->id.'" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>';
    //         }
    //     })
    //     ->addColumn('job', function($inv){
    //         if ($inv->lunas == 'N') {
    //             return '<span class="badge bg-info text-white">Paid First!!</span>';
    //         }elseif ($inv->lunas == 'C') {
    //             return '<span class="badge bg-danger text-white">Canceled</span>';
    //         }else {
    //             return '<a type="button" href="/invoice/job/import-'.$inv->id.'" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>';
    //         }
    //     })
    //     ->addColumn('action', function($inv){
    //         if ($inv->lunas == 'N' || $inv->lunas == 'P') {
    //             return '<button type="button" id="pay" data-id="'.$inv->id.'" class="btn btn-sm btn-success pay"><i class="fa fa-cogs"></i></button>';
    //         }elseif ($inv->lunas == 'Y') {
    //             return '<span class="badge bg-success text-white">Paid</span>';
    //         }else {
    //             return '<span class="badge bg-danger text-white">Canceled</span>';
    //         }
    //     })
    //     ->addColumn('payFlag', function($inv){
    //         if ($inv->lunas == 'N') {
    //             if ($inv->pay_flag == 'Y') {
    //                 return '<div class="spinner-border text-primary" role="status">
                            
    //                     </div> <span class="">Waiting Approved</span>';
    //             }elseif ($inv->pay_flag == 'C') {
    //                 return '<span class="badge bg-danger text-white">Di Tolak</span>';
    //             }else {
    //                 return '-';
    //             }
    //         }else {
    //             return '-';
    //         }
    //     })
    //     ->addColumn('delete', function($inv){
    //         if ($inv->lunas == 'N') {
    //             return '<button type="button" data-id="'.$inv->form_id.'" class="btn btn-sm btn-danger Delete"><i class="fa fa-trash"></i></button>';
    //         }else {
    //             return '-';
    //         }
    //     })
    //     ->addColumn('viewPhoto', function($inv){
    //         $herf = '/bukti_bayar/extend/'; 
    //         return '<a href="javascript:void(0)" onclick="openWindow(\''.$herf.$inv->id.'\')" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>';
    //     })
    //     ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'delete', 'payFlag', 'viewPhoto'])
    //     ->make(true);
    // }

    public function listIndex()
    {
        $data['title'] = "List Invoice Extend (Perpanjangan)";

        return view('customer.extend.detil.listIndex', $data);
    }

    public function listData(Request $request)
    {
        $invoice = $this->extend->whereHas('service', function ($query) {
            $query->where('ie', '=', 'X');
        })->whereNot('form_id', '=', '')->orderBy('order_at', 'desc');
        
        if ($request->has('type')) {
            if ($request->type == 'unpaid') {
                $invoice = $this->extend->whereHas('service', function ($query) {
                    $query->where('ie', '=', 'X');
                })->whereNot('form_id', '=', '')->where('lunas', '=', 'N')->orderBy('order_at', 'desc');
            }
        
            if ($request->type == 'piutang') {
                $invoice = $this->extend->whereHas('service', function ($query) {
                    $query->where('ie', '=', 'X');
                })->whereNot('form_id', '=', '')->where('lunas', '=', 'P')->orderBy('order_at', 'desc');
            }
        }
        
        if ($request->has('os_id')) {
            $invoice = $this->extend->whereNot('form_id', '=', '')->where('os_id', $request->os_id)->orderBy('order_at', 'desc')->orderBy('lunas', 'asc');
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
            return '<a type="button" href="/pranota/extend-'.$inv->id.'" target="_blank" class="btn btn-sm btn-warning text-white"><i class="fa fa-file"></i></a>';
        })
        ->addColumn('invoice', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/extend-'.$inv->id.'" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fa fa-dollar"></i></a>';
            }
        })
        ->addColumn('job', function($inv){
            if ($inv->lunas == 'N') {
                return '<span class="badge bg-info text-white">Paid First!!</span>';
            }elseif ($inv->lunas == 'C') {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }else {
                return '<a type="button" href="/invoice/job/import-'.$inv->id.'" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-ship"></i></a>';
            }
        })
        ->addColumn('action', function($inv){
            if ($inv->lunas == 'N' || $inv->lunas == 'P') {
                 if ($inv->inv_type == 'DS') {
                    return '<span class="badge text-white" style="background-color: orange;">Pembayaran Manual</span>';
                }else {
                    return '<button type="button" id="pay" data-id="'.$inv->id.'" class="btn btn-sm btn-success pay" onClick="payExtend(this)"><i class="fa fa-cogs"></i></button>';
                }
            }elseif ($inv->lunas == 'Y') {
                return '<span class="badge bg-success text-white">Paid</span>';
            }else {
                return '<span class="badge bg-danger text-white">Canceled</span>';
            }
        })
        ->addColumn('payFlag', function($inv){
            if ($inv->lunas == 'N') {
                if ($inv->pay_flag == 'Y') {
                    return '<div class="spinner-border text-primary" role="status">
                            
                        </div> <span class="">Waiting Approved</span>';
                }elseif ($inv->pay_flag == 'C') {
                    return '<span class="badge bg-danger text-white">Di Tolak</span>';
                }else {
                    return '-';
                }
            }else {
                return '-';
            }
        })
        ->addColumn('delete', function($inv){
            if ($inv->lunas == 'N') {
                return '<button type="button" data-id="'.$inv->form_id.'" class="btn btn-sm btn-danger" onClick="cancelInvoice(this)">Cacnel</i></button>';
            }else {
                return '-';
            }
        })
        ->addColumn('viewPhoto', function($inv){
            $herf = '/bukti_bayar/extend/'; 
            return '<a href="javascript:void(0)" onclick="openWindow(\''.$herf.$inv->id.'\')" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>';
        })
        ->rawColumns(['status', 'pranota', 'invoice', 'job', 'action', 'delete', 'payFlag', 'viewPhoto'])
        ->make(true);
    }

    public function formList()
    {
        $data['title'] = "Form Management || Perpanjangan";

        return view('customer.extend.form.listIndex', $data);
    }

    public function formData(Request $request)
    {
        $form = $this->form->with(['customer', 'Kapal', 'doOnline', 'service'])->where('i_e', 'X')->where('done', '=', 'N');
        return DataTables::of($form)
        ->addColumn('edit', function($form){
            return '<a href="/customer-extend/formFirstStepId='.$form->id.'" class="btn btn-warning"><i class="fas fa-pencil"></i></a>';
        })
        ->addColumn('delete', function($form){
            return '<button class="btn btn-danger" data-id="'.$form->id.'" onClick="deleteForm(this)">Delete</button>';
        })
        ->rawColumns(['edit', 'delete'])
        ->make(true);
    }

    public function formStoreFirst(Request $request)
    {
        try {
            $mui = MUI::where('user_id', $this->userId)->get();
            if ($mui->isEmpty()) {
                return redirect()->back()->with('error', 'Anda belum memiliki list customer, hubungi admin!!!');
            }

            $oldForm = Form::where('i_e', 'X')
            ->where('user_id', $this->userId)
            ->where('done', 'N')
            ->whereNull('expired_date')
            ->whereNull('os_id')
            ->whereNull('cust_id')
            ->whereNull('do_id')
            ->whereNull('ves_id')
            ->whereNull('disc_date')
            ->first();
            if ($oldForm) {
                return redirect('/customer-extend/formFirstStepId='.$oldForm->id)->with('success', 'Continue Your Step');
            }
            $form = Form::create([
                'i_e' => 'X',
                'user_id' => Auth::user()->id,
                'done' => 'N',
            ]);

            // dd($form);
            return redirect('/customer-extend/formFirstStepId='.$form->id)->with('success', 'Continue Your Step');
        } catch (\Throwable $th) {
            return redirect( )->back()->with('error', 'Something Wrong!! '. $th->getMessage());
        }
    }

    public function firstStepIndex($id)
    {
        $data['title'] = 'Form Import';
        $data['form'] = Form::find($id);
        $data['orderService'] = OS::where('ie', '=', 'X')->get();
        $data['do_online'] = DOonline::where('active', 'Y')->get();
        $data['ves'] = VVoyage::where('deparature_date', '>=', Carbon::now())->get();
        $data['containerInvoice'] = Container::where('form_id', $id)->get();
        
        $mui = MUI::where('user_id', $this->userId)->get();
        $customerId = $mui->pluck('customer_id')->toArray();
        $data['customer'] = Customer::whereIn('id', $customerId)->get();
        // dd($data['expired']);
        // dd($mui, $customerId, $data['customer']);
        

        return view('customer.extend.form.firstStep', $data);
    }

    public function getOldInvoice(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $perPage = 5; // Jumlah item per halaman

        // Query untuk import
        $importQuery = $this->import
            ->where('lunas', 'Y')
            ->where('inv_type', 'DS')
            ->select('inv_no', 'id', 'form_id')
            ->groupBy('inv_no', 'id', 'form_id');

        // Query untuk export
        $exportQuery = $this->extend
            ->where('lunas', 'Y')
            ->select('inv_no', 'id', 'form_id')
            ->groupBy('inv_no', 'id', 'form_id');

        // Gabungkan kedua query dengan UNION
        $query = $importQuery->union($exportQuery);

        // Tambahkan pencarian jika ada
        if ($search) {
            $query->where('inv_no', 'like', "%{$search}%");
        }

        // Hitung total data untuk pagination
        $totalCount = $query->count();
        $oldInvoice = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        return response()->json([
            'data' => $oldInvoice,
            'more' => ($page * $perPage) < $totalCount, // Cek apakah ada halaman berikutnya
        ]);
    }

    public function getOldInvoiceById(Request $request)
    {
        $form = Form::find($request->id);
        // dd($request->all(), $form);
        switch ($form->tipe) {
            case 'P':
                $invoice = Extend::find($form->do_id);
                break;
            case 'I':
                $invoice = Import::find($form->do_id);
                break;
            default:
                # code...
                break;
        }

        if ($invoice) {
            return response()->json([
                'id' => $invoice->id,
                'inv_no' => $invoice->inv_no,
                'form_id' => $invoice->form_id
            ]);
        }

        return response()->json([], 404);
    }

    public function oldInvoiceData(Request $request)
    {
        $oldForm = Form::find($request->formId);
        // var_dump($request->all(), $oldForm);
        if ($oldForm) {
            switch ($oldForm->i_e) {
                case 'I':
                    $oldInvoice = Import::find($request->oldId);
                    $allInvoice = Import::where('form_id', $oldForm->id)->get();
                    $oldJob = JobImport::whereIn('inv_id', $allInvoice->pluck('id'))->get();
                    $tipe = 'I';
                    break;
                    case 'X':
                        $oldInvoice = Extend::find($request->oldId);
                        $allInvoice = Extend::where('form_id', $oldForm->id)->get();
                        $oldJob = JobExtend::whereIn('inv_id', $allInvoice->pluck('id'))->get();
                        $tipe = 'P';
                    break;
                default:
                    # code...
                    break;
            }
            // var_dump($oldInvoice);
            if ($oldForm->service->order == 'SP2') {
                $newOs = OS::where('ie', 'X')->where('order', 'SP2')->first();
            }elseif ($oldForm->service->order == 'SPPS') {
                $newOs = OS::where('ie', 'X')->where('order', 'SPPS')->first();
            }

            $vessel = VVoyage::find($oldForm->ves_id);

            $discDate = Carbon::parse($oldInvoice->expired_date)->format('Y-m-d');

            $items = Item::whereIn('container_key', $oldJob->pluck('container_key'))->whereIn('job_no', $oldJob->pluck('job_no'))->get();
            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada Container yang dapat di pilih',
                ]);
            }  
            return response()->json([
                'success' => true,
                'data' => $oldInvoice,
                'service' => $newOs,
                'vessel' => $vessel,
                'discDate' => $discDate,
                'cont' => $items,
                'tipe' => $tipe,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
     
    }

    public function storeFormStep1(Request $request)
    {
        
        $form = Form::find($request->form_id);
        if ($request->exp_date == null) {
            return redirect()->back()->with('error', 'Anda Belum Mengisi Tanggal Rencana Perpanjangan');
        }
        
        if ($request->disc_date >= $request->exp_date) {
            return redirect()->back()->with('error', 'Tanggal Rencana Perpanjangan harus lebih besar');
        }
        if ($form) {
            $oldForm = Form::find($request->oldFormId);
            if (!$oldForm) {
                return redirect()->back()->with('error', 'Oopss Somthing wrong!! : Old Form Tidak Ditemukan');
            }

            $disc = Carbon::parse($request->disc_date);
            $expired = Carbon::parse($request->exp_date);

            $interval = $disc->diff($expired);
            $jumlahHari = ($interval->days);

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
                    // Calculate remaining days needed to reach 5 days for massa2
                    $remainingMassa2Days = 5 - $oldForm->massa2;
                    
                    // If there are more days than needed to reach 5 days in massa2
                    if ($jumlahHari > $remainingMassa2Days) {
                        $massa2 = $remainingMassa2Days;
                        $massa3 = $jumlahHari - $remainingMassa2Days;
                    } else {
                        $massa2 = $jumlahHari;
                        $massa3 = 0;
                    }
                } else {
                    // If massa2 already has 5 or more days
                    $massa2 = 0;
                    $massa3 = $jumlahHari;
                }
            } else {
                $massa2 = 0;
                $massa3 = $jumlahHari;
            }
            $selctedCont = Item::whereIn('container_key', $request->container)->get();
            // dd($oldForm->massa2, $oldForm->massa3, $jumlahHari, $massa2, $massa3, $request->all(), $selctedCont);

            try {
                $form->update([
                    'expired_date' => $request->exp_date,
                    'os_id' => $request->order_service,
                    'cust_id' => $request->customer,
                    'do_id' => $request->oldInvoice,
                    'ves_id' => $oldForm->ves_id,
                    'disc_date' => $request->disc_date,
                    'tipe' => $request->tipe,
                    'massa2' => $massa2,
                    'massa3' => $massa3,
                ]);

                $oldContainer = Container::where('form_id', $form->id)->delete();

                foreach ($selctedCont as $item) {
                    $newCont = Container::create([
                        'container_key'=>$item->container_key,
                        'container_no'=>$item->container_no,
                        'ctr_size'=>$item->ctr_size,
                        'ctr_status'=>$item->ctr_status,
                        'form_id'=>$form->id,
                        'ves_id'=>$item->ves_id,
                        'ves_name'=>$item->ves_name,
                        'ctr_type'=>$item->ctr_type,
                        'ctr_intern_status'=>$item->ctr_intern_status,
                        'gross'=>$item->gross,
                    ]);
                }

                return redirect('/customer-extend/preinvoice/'.$form->id)->with('success', 'Silahkan Melanjutkan Step');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'something wrong : ' .$th->getMessage());
            }
            
        }
    }

    public function preinvoice($id)
    {
        $form = Form::find($id);
        $data['title'] = "Preinvoice Perpanjangan| " . $form->Service->name;
        $data['form'] = $form;

        $containers = Container::where('form_id', $form->id)->get();
        $data['listContainers'] = $containers;

        $service = OS::find($form->os_id);
        $serviceDetail = OSDetail::where('os_id', $service->id)->orderBy('master_item_name', 'asc')->get();

        $serviceDSK = $serviceDetail->where('type', 'DSK');
        $data['serviceDSK'] = $serviceDSK;
        $data['dsk'] = $serviceDSK->isNotEmpty() ? 'Y' : 'N'; 

        $serviceDS = $serviceDetail->where('type', 'XTD');
        $data['serviceDS'] = $serviceDS;
        $data['ds'] = $serviceDS->isNotEmpty() ? 'Y' : 'N';

        // dd($serviceDetail, $serviceDS, $serviceDSK);

        $data['size'] = $containers->pluck('ctr_size')->unique();
        $data['status'] = $containers->pluck('ctr_status')->unique();

        $tarif = MT::where('os_id', $service->id)->select('ctr_size', 'ctr_status')->get();
        $checkTarif = $tarif->toArray();
        $invalidContainer = $containers->filter(function($container) use ($checkTarif){
            return !in_array(['ctr_size'=>$container->ctr_size, 'ctr_status'=>$container->ctr_status], $checkTarif);
        });
        if ($invalidContainer->isNotEmpty()) {
            $invalidContainerNumber = $invalidContainer->pluck('container_no')->implode(', ');
            return redirect()->back()->with('error', 'Hubungi Admin, terdapat container yang belum memiliki master tarif :' . $invalidContainerNumber);
        }
        $groupContainer = $containers->unique('ctr_size', 'ctr_status')->pluck('ctr_size', 'ctr_status');
        $data['sizes'] = $containers->pluck('ctr_size')->unique();
        $data['statuses'] = $containers->pluck('ctr_status')->unique();
        $data['groupContainer'] = $groupContainer;

        $data['tarif'] = MT::where('os_id', $service->id)->get();
        $data['tarifDetail'] = MTDetail::whereIn('master_tarif_id', $data['tarif']->pluck('id'))->get();
        // dd($service, $data['dsk'], $data['size'], $data['status'], $checkTarif, $invalidContainer);

        return view('customer.extend.form.preinvoice', $data)->with('success', 'Seilahkan lanjutkan ke tahap berikut nya');
    }

    public function deleteInvoice(Request $request)
    {
        $formId = $request->formId;
        try {
            $form = Form::find($formId);
            $headerLunas = Extend::where('form_id', $form->id)->get();
            // var_dump($headerLunas);
            // die();
            if ($headerLunas->isNotEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Opsss sudah ada Pranota yang terbit, handa hanya dapat melakukan cancel invoice',
                ]);
            }
    
            Container::where('form_id')->delete();
    
            $form->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong : '.$th->getMessage(),
            ]);
            
        }
        
    }

    public function createInvoice(Request $request)
    {
        // dd($request->all());

        $form = Form::find($request->formId);
        $service = OS::find($form->os_id);
        $containers = Container::where('form_id', $form->id)->get();
        $groupContainer = $containers->unique('ctr_size', 'ctr_status')->pluck('ctr_size', 'ctr_status');
        $sizes = $containers->pluck('ctr_size')->unique();
        $statuses = $containers->pluck('ctr_status')->unique();

        $serviceDetail = OSDetail::where('os_id', $service->id)->get();
        $tarif = MT::where('os_id', $service->id)->get();
        $tarifDetail = MTDetail::whereIn('master_tarif_id', $tarif->pluck('id'))->get();
        try {
            if ($serviceDetail->where('type', 'DSK')->isNotEmpty()) {
                $this->processInvoice($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetail->where('type', 'DSK'), $tarif, $tarifDetail, 'DSK');
            }
    
            if ($serviceDetail->where('type', 'XTD')->isNotEmpty()) {
                $this->processInvoice($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetail->where('type', 'XTD'), $tarif, $tarifDetail, 'DS');
            }

            $form->update(['done'=>'Y']);
            foreach ($containers as $cont) {
                $item = Item::find($cont->container_key);
                $item->selected_do = 'Y';
                $item->os_id = $form->os_id;
                $item->order_service = $service->order;
                $item->save();
            }

            return redirect('/customer-extend/unpaid')->with('success', 'Invoice berhasil di buat');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something Wrong : '.$th->getMessage());
        }
    }

    private function processInvoice($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetails, $tarif, $tarifDetail, $type)
    {
        DB::transaction(function () use ($request, $form, $service, $containers, $groupContainer, $sizes, $statuses, $serviceDetails, $tarif, $tarifDetail, $type) {
            $header = $this->createInvoiceHeader($request, $form, $service, $type);

            foreach ($sizes as $size) {
                foreach ($statuses as $status) {
                    $jumlahCont = $containers->where('ctr_size', $size)->where('ctr_status', $status)->count();
                    if ($jumlahCont > 0) {
                        foreach ($serviceDetails as $detail) {
                            if ($detail->MItem->count_by != 'O') {
                                $this->createInvoiceDetail($header, $form, $detail, $tarif, $tarifDetail, $size, $status, $jumlahCont);
                            }
                        }
                    }
                }
            }

            foreach ($serviceDetails as $detail) {
                if ($detail->MItem->count_by == 'O') {
                    $size = null;
                    $status = null;
                    $this->createInvoiceDetail($header, $form, $detail, $tarif, $tarifDetail, $size, $status, 1);
                }
            }
        });
    }

    private function createInvoiceHeader($request, $form, $service, $type)
    {
        return Extend::create([
            'form_id' => $form->id,
            'inv_id' => $form->do_id,
            'proforma_no' => $this->getNextProformaNumber(),
            'cust_id' => $form->customer->id,
            'cust_name' => $form->customer->name,
            'fax' => $form->customer->fax,
            'npwp' => $form->customer->npwp,
            'alamat' => $form->customer->alamat,
            'os_id' => $service->id,
            'os_name' => $service->name,
            'total' => $request->input("total$type"),
            'admin' => $request->input("admin$type"),
            'pajak' => $request->input("ppn$type"),
            'grand_total' => $request->input("grandTotal$type"),
            'order_by' => Auth::user()->name,
            'order_at' => Carbon::now(),
            'disc_date' => $form->disc_date,
            'expired_date' => $form->expired_date,
            'user_id' => Auth::user()->id,
            'lunas' => 'N',
        ]);
    }

    private function createInvoiceDetail($header, $form, $detail, $tarif, $tarifDetail, $size, $status, $jumlahCont)
    {
        $selectedTarif = $tarif->where('ctr_size', $size)->where('ctr_status', $status)->first();
        if ($detail->MItem->count_by == 'O') {
            $selectedTarif = $tarif->first();
        }
        $tarifDSK = $tarifDetail->where('master_tarif_id', $selectedTarif->id)->where('master_item_id', $detail->master_item_id)->first();

        $jumlahHari = $this->calculateDays($form, $detail);
        $kode = ($detail->kode != 'PASSTRUCK') ? $detail->kode . $size : 'PASSTRUCK';
        $harga = $jumlahCont * $jumlahHari * $tarifDSK->tarif;
        // dd($detail);
        Detail::create([
            'inv_id' => $header->id,
            'inv_type' => 'XTD',
            'keterangan' => $form->service->name,
            'ukuran' => ($detail->MItem->count_by != 'O') ? $size : '0',
            'ctr_status' => ($detail->MItem->count_by != 'O') ? $status : '-',
            'jumlah' => ($detail->MItem->count_by != 'O') ? $jumlahCont : 1,
            'satuan' => 'unit',
            'expired_date' => $form->expired_date,
            'order_date' => $header->order_at,
            'lunas' => 'N',
            'cust_id' => $form->cust_id,
            'cust_name' => $form->customer->name,
            'os_id' => $form->os_id,
            'jumlah_hari' => $jumlahHari,
            'master_item_id' => $detail->master_item_id,
            'master_item_name' => $detail->master_item_name,
            'kode' => $kode,
            'tarif' => $tarifDSK->tarif,
            'total' => $harga,
            'form_id' => $form->id,
            'count_by' => $detail->MItem->count_by,
        ]);
    }

    private function calculateDays($form, $detail)
    {
        if ($detail->MItem->count_by == 'T') {
            return ($detail->MItem->massa == 3) ? $form->massa3 : (($detail->MItem->massa == 2) ? $form->massa2 : 1);
        }
        return 1;
    }


    private function getNextProformaNumber()
    {
        $latestProforma = Extend::orderBy('proforma_no', 'desc')->first();

        if (!$latestProforma) {
            return 'P0000001';
        }

        $lastProformaNumber = $latestProforma->proforma_no;
        $lastNumber = (int)substr($lastProformaNumber, 1);
        $nextNumber = $lastNumber + 1;
        return 'P' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    public function payButton($id)
    {
        // var_dump($id);
        // die();
        try {
            //code...
            $import = Extend::find($id);
    
            return response()->json([
                'success' => true,
                'data' => $import,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Oopss something wrong : ' . $th->getMessage(),
            ]);
        }
    }

    public function payImportFromCust(Request $request)
    {
        // Hapus `dd($request->all());` setelah debugging
        if (!$request->hasFile('bukti_bayar')) {
            return back()->with('error', 'Anda Wajib Memasukkan Bukti Pembayaran');
        }

        $import = Extend::find($request->id);
        if (!$import) {
            return back()->with('error', 'Data Import tidak ditemukan');
        }

        foreach ($request->file('bukti_bayar') as $photo) {
            $fileName = time() . '_' . $photo->getClientOriginalName(); // Tambahkan timestamp untuk menghindari nama duplikat
            $filePath = 'bukti_bayar/extend/' . $import->id;

            // Simpan ke storage
            $photo->storeAs($filePath, $fileName, 'public');

            // Simpan ke database
           $import->update([
            'pay_flag' => 'Y',
           ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah');
    }

    // public function cancelInvoice(Request $request)
    // {
    //     $headers = Extend::where('form_id', $request->formId)->get();
    //     if ($headers->isNotEmpty()) {
            
    //     } 
    // }


    public function searchToPay(Request $request)
    {
        $extend = Extend::find($request->id);
        if ($extend) {
            return response()->json([
                'success' => true,
                'data' => $extend,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data not Found',
            ]);
        }
    }

    public function createVA(Request $request)
    {
        // Checking VA
        $checking = $this->checkingOldVA($request);
        if ($checking) {
            return $checking;
        }

        $extend = Extend::find($request->id);
        $billingAmount = $extend->grand_total;
        
        try {
            $newVa = DB::transaction(function() use($billingAmount, $extend){
                return VA::create([
                    'virtual_account' => $this->virtualAccount(),
                    'expired_va' => Carbon::now()->addHours(3),
                    'invoice_type' => 'Extend',
                    'customer_name' => $extend->cust_name,
                    'customer_id' => $extend->cust_id,
                    'description' => $extend->os_name,
                    'billing_amount' => $billingAmount,
                    'status' => 'N',
                    'user_id' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            });
            $this->createVaDetail($newVa, $request);

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
        do {
            // Generate 16 digit angka random
            $randomDigits = str_pad(mt_rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
            $generateVa = $prefix . $randomDigits;
        } while (VA::where('virtual_account', $generateVa)->exists());

        return $generateVa;
    }

    private function createVaDetail($newVa, $request)
    {
        $extend = Extend::find($request->id);
        DB::transaction(function() use($newVa, $extend){
            RefDetail::create([
                'va_id' => $newVa->id,
                'inv_id' => $extend->id,
                'invoice_ie' => 'X',
                'proforma_no' => $extend->proforma_no,
                'invoice_type' => $extend->inv_type,
                'amount' => $extend->grand_total,
            ]);

            $extend->update([
                'va' => $newVa->virtual_account,
            ]);
        });
    }

    private function checkingOldVA($request)
    {
        $extend = Extend::find($request->id);
        $oldVa = VA::where('virtual_account', $extend->va)->first();
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
        return null;
    }

}
