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

use DataTables;


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

        $data['vessels'] = VVoyage::where('deparature_date', '>=', Carbon::now())->orderBy('ves_id', 'desc')->get();

        // dd($data['vessels']);
        return view('billingSystem.do.main', $data);
    }

    public function doData()
    {
        $doData = DOonline::all(); // Adjust with your actual model and query logic
        return datatables()->of($doData)
            ->addIndexColumn() // This adds DT_RowIndex to each row
            ->addColumn('actions', function($do) {
                return '
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <a href="/edit/doOnline/'.$do->id.'" class="btn btn-warning">Edit</a>
                        </div>
                        <div class="col-sm-6">
                            <form id="deleteForm-'.$do->id.'" action="'.route('deleteDo').'" method="post">
                                '.csrf_field().'
                                <input type="hidden" name="id" value="'.$do->id.'">
                                <button type="button" class="btn btn-outline-danger delete-btn" data-id="'.$do->id.'">Delete</button>
                            </form>
                        </div>
                    </div>';
            })
            ->addColumn('container_no', function($do) {
                $doArray = json_decode($do->container_no, true);
                if (is_array($doArray)) {
                    return implode(', ', $doArray);
                } else {
                    return $do->container_no;
                }
            })
            ->rawColumns(['actions', 'container_no'])
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
        $do = DOonline::where('id', $request->id)->first();
        if ($do) {
            $do->update([
                'do_no'=>$request->do_no,
                'container_no'=>$request->container_no,
                'bl_no'=>$request->bl_no,
                'expired'=>$request->expired,
                'customer_code'=>$request->customer_code,
            ]);
            return redirect()->back()->with('success', 'Data Berhasil di Update.');
        }else {
            return redirect()->back()->with('error', 'Something Wrong, Call the Admin.');
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

            $do = DOonline::create([
                'do_no' => $request->do_no,
                'bl_no' => $request->bl_no,
                'expired' => $request->expired,
                'customer_code' => $request->customer,
                'created_at' => Carbon::now(),
                'created_by' => Auth::user()->name,
                'active' => 'Y',
                'container_no'=>$containersJson,
            ]);

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
