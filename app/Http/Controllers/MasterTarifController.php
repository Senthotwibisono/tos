<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderService as OS;
use App\Models\OSDetail;
use App\Models\MasterTarif as MT;
use App\Models\Customer;
use App\Models\DOonline;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\uploadDO;


class MasterTarifController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function import()
    {
        $data['title'] = "Mater Tarif Import";
        $data ['orderService'] = OS::where('ie', '=', 'I')->get();
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

        return view('billingSystem.do.main', $data);
    }
    
    public function doUpload(Request $request)
    {
        $path = $request->file('storedo');
        Excel::import(new uploadDO, $path->getRealPath(), null, 'Xls');

        return redirect('/billing/dock-DO')->with('success', 'Data berhasil diimpor.');
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
