<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderService as OS;
use App\Models\MasterTarif as MT;
use App\Models\MItem;
use App\Models\MTDetail;
use App\Models\OSDetail;

class MasterInvoiceController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    // MItem
    public function indexMItem()
    {
        $data['title'] = 'Master Item';
        $data['items'] = MItem::get();

        return view('billingSystem.master.item', $data);
    }

    public function postItem(Request $request)
    {
        $item = MItem::create([
            'name'=>$request->name,
            'kode'=>$request->kode,
            'count_by'=>$request->count_by,
            'size'=>$request->size,
            'massa'=>$request->massa,
        ]);

        return redirect()->back()->with('success', 'Data Berhasil di Upload');
    }

    public function deleteItem(Request $request)
    {
        $item = MItem::where('id', $request->id)->first();
        if ($item) {
            $item->delete();
            return redirect()->back()->with('success', 'Data Berhasil di Hapus');
        }
    }

    public function editMItem(Request $request)
    {
        $item = MItem::where('id', $request->id)->first();
        if ($item) {
            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Ditemukan',
                'data'    => $item,  
            ]); 
        }
    }

    public function updateItem(Request $request)
    {
        $item = MItem::where('id', $request->id)->first();
        $item->update([
            'name'=>$request->name,
            'kode'=>$request->kode,
            'count_by'=>$request->count_by,
            'size'=>$request->size,
        ]);

        return redirect()->route('invoice-master-item')->with('success', 'Data Berhasil di Update');
    }
    // OrderService
    public function indexOS()
    {
        $data['title'] = 'Order Service';
        $data['os'] = OS::orderBy('ie', 'desc')->get();
        return view('billingSystem.master.orderService', $data);
    }

    public function deleteOS(Request $request)
    {
        $os = OS::where('id', $request->id)->first();
        if ($os) {
            $osd = OSDetail::where('os_id', $os->id)->get();
            foreach ($osd as $detail) {
                $detail->delete();
            }
            $os->delete();
            return redirect()->back()->with('success', 'Data Berhasil di Hapus');
        }
    }

    public function detailOS($id)
    {
        $data['orderService'] = OS::where('id', $id)->first();
        $data['title'] = $data['orderService']->name;
        $data['orderServiceDSK'] = OSDetail::where('os_id', $id)->where('type', '=', 'DSK')->get();
        $data['orderServiceDS'] = OSDetail::where('os_id', $id)->where('type', '=', 'DS')->get();
        $data['orderServiceXTD'] = OSDetail::where('os_id', $id)->where('type', '=', 'XTD')->get();
        $data['orderServiceOSK'] = OSDetail::where('os_id', $id)->where('type', '=', 'OSK')->get();
        $data['orderServiceOS'] = OSDetail::where('os_id', $id)->where('type', '=', 'OS')->get();

        $data['items'] = MItem::orderBy('name', 'asc')->get();

        return view('billingSystem.master.orderServiceDetail', $data);
    }

    public function updateOS(Request $request)
    {
        $os = OS::where('id', $request->id)->first();
        $os->update([
            'name'=>$request->name,
            'ie'=>$request->ie,
            'order'=>$request->order,
            'return_yn'=>$request->return_yn,
            'depo_return'=>$request->depo_return,
        ]);
        $osd = OSDetail::where('os_id', $os->id)->get();
        foreach ($osd as $detail) {
            $detail->update([
                'os_name'=>$request->name,
            ]);
        }

        return redirect()->back()->with('success', 'Data Berhasil di Perbaharui');
    }

    public function osDetailDSK(Request $request)
    {
        $mtIds = $request->master_item_id;

        foreach ($mtIds as $id) {
            $mItem = MItem::find($id);

            if (!$mItem) {
                return redirect()->back()->with('error', 'Master Item tidak ditemukan');
            }

            $newDSK = OSDetail::create([
                'os_id' => $request->os_id,
                'os_name' => $request->os_name,
                'type' => $request->type,
                'master_item_id' => $id,
                'master_item_name' => $mItem->name,
                'kode' => $mItem->kode,
                'massa' => $mItem->massa,
            ]);

            $masterTarifs = MT::where('os_id', $request->os_id)->get();

            foreach ($masterTarifs as $tarif) {
                $tarifDetil = MTDetail::where('master_tarif_id', $tarif->id)
                    ->where('master_item_id', $id)
                    ->exists(); // Use `exists()` to check existence efficiently

                if (!$tarifDetil) {
                    MTDetail::create([
                        'master_tarif_id' => $tarif->id,
                        'master_item_id' => $newDSK->master_item_id,
                        'master_item_name' => $newDSK->master_item_name,
                        'count_by' => $mItem->count_by ?? null, // Ensure 'count_by' exists
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data Berhasil diperbaharui');
    }


    public function osDetailDS(Request $request)
    {
        $mtId = $request->master_item_id;
        
        foreach ($mtId as $id) {
            $mItem = MItem::where('id', $id)->first();
            $newDSK = OSDetail::create([
                'os_id'=>$request->os_id,
                'os_name'=>$request->os_name,
                'type' => $request->type,
                'master_item_id'=>$id,
                'master_item_name'=>$mItem->name,
                'kode'=>$mItem->kode,
                'massa'=>$mItem->massa,
            ]);

            $masterTarifs = MT::where('os_id', $request->os_id)->get();

            foreach ($masterTarifs as $tarif) {
                $tarifDetil = MTDetail::where('master_tarif_id', $tarif->id)
                    ->where('master_item_id', $id)
                    ->exists(); // Use `exists()` to check existence efficiently

                if (!$tarifDetil) {
                    MTDetail::create([
                        'master_tarif_id' => $tarif->id,
                        'master_item_id' => $newDSK->master_item_id,
                        'master_item_name' => $newDSK->master_item_name,
                        'count_by' => $mItem->count_by ?? null, // Ensure 'count_by' exists
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'Data Berhasil di Perbaharui');
    }

    public function buangDetail(Request $request)
    {
        $detail = OSDetail::where('id', $request->id)->first();
        $masterTarifs = MT::where('os_id', $detail->os_id)->get();
        foreach ($masterTarifs as $tarif) {
            $tarifDetails = MTDetail::where('master_tarif_id', $tarif->id)->where('master_item_id', $detail->master_item_id)->delete();
        }
        $detail->delete();

        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }

    public function indexMTimport(Request $request)
    {
        $data['title'] = "Mater Tarif Import";
        $data ['orderService'] = OS::whereIn('ie', ['I', 'X'])->orderBy('ie', 'asc')->get();
        $data ['masterTarif'] = MT::get();
        return view('billingSystem.import.master-tarif.main', $data);
    }

    public function indexMTexport(Request $request)
    {
        $data['title'] = "Mater Tarif Export";
        $data ['orderService'] = OS::where('ie', '=', 'E')->orderBy('ie', 'asc')->get();
        $data ['masterTarif'] = MT::get();
        return view('billingSystem.export.master-tarif.main', $data);
    }

    public function modalMT(Request $request)
    {
        $os = OS::where('id', $request->id)->first();
        // var_dump($request->id);
        // die;
        if ($os) {
            return response()->json([
                'success' => true,
                'data' => $os,
            ]);
        }
    }

    public function indexMTimportDetail($id)
    {
        $masterTarif = MT::where('id', $id)->first();
        $data['title'] = 'Master Tarif '. $masterTarif->name;
        $data['MasterTarif'] = $masterTarif;
        $data['masterTarifDetail'] = MTDetail::where('master_tarif_id', $id)->get(); 
        return view('billingSystem.import.master-tarif.create', $data);
    }

    public function indexMTexportDetail($id)
    {
        $masterTarif = MT::where('id', $id)->first();
        $data['title'] = 'Master Tarif '. $masterTarif->name;
        $data['MasterTarif'] = $masterTarif;
        $data['masterTarifDetail'] = MTDetail::where('master_tarif_id', $id)->get(); 
        return view('billingSystem.export.master-tarif.create', $data);
    }

    public function indexMTpluggingDetail($id)
    {
        $masterTarif = MT::where('id', $id)->first();
        $data['title'] = 'Master Tarif '. $masterTarif->name;
        $data['MasterTarif'] = $masterTarif;
        $data['masterTarifDetail'] = MTDetail::where('master_tarif_id', $id)->get(); 
        return view('billingSystem.plugging.master-tarif.create', $data);
    }

    public function indexMTrentalDetail($id)
    {
        $masterTarif = MT::where('id', $id)->first();
        $data['title'] = 'Master Tarif '. $masterTarif->name;
        $data['MasterTarif'] = $masterTarif;
        $data['masterTarifDetail'] = MTDetail::where('master_tarif_id', $id)->get(); 
        return view('billingSystem.rental-repair.master-tarif.create', $data);
    }

    public function tarifFirst(Request $request)
    {
        $os = OS::where('id', $request->os_id)->first();
        $mt = MT::create([
            'os_id'=>$request->os_id,
            'os_name'=>$request->os_name,
            'ctr_size'=>$request->ctr_size,
            'ctr_status'=>$request->ctr_status,
        ]);

        $osDetail = OSDetail::where('os_id', $os->id)->get();
        foreach ($osDetail as $detail) {
            $item = MItem::where('id', $detail->master_item_id)->first();
            // dd($item);
            $mtDetail = MTDetail::create([
                'master_tarif_id'=>$mt->id,
                'master_item_id'=>$detail->master_item_id,
                'master_item_name'=>$detail->master_item_name,
                'count_by'=> $item->count_by,
            ]);
        }

        return redirect()->route('invoice-master-tarifImport-detail', ['id' => $mt->id])->with('success', 'Silahkan Lengkapi Tairf');
    }

    public function tarifFirstExport(Request $request)
    {
        $os = OS::where('id', $request->os_id)->first();
        $mt = MT::create([
            'os_id'=>$request->os_id,
            'os_name'=>$request->os_name,
            'ctr_size'=>$request->ctr_size,
            'ctr_status'=>$request->ctr_status,
        ]);

        $osDetail = OSDetail::where('os_id', $os->id)->get();
        foreach ($osDetail as $detail) {
            $item = MItem::where('id', $detail->master_item_id)->first();
            // dd($item);
            $mtDetail = MTDetail::create([
                'master_tarif_id'=>$mt->id,
                'master_item_id'=>$detail->master_item_id,
                'master_item_name'=>$detail->master_item_name,
                'count_by'=> $item->count_by,
            ]);
        }

        return redirect()->route('invoice-master-tarifExport-detail', ['id' => $mt->id])->with('success', 'Silahkan Lengkapi Tairf');
    }

    public function tarifFirstPlugging(Request $request)
    {
        $os = OS::where('id', $request->os_id)->first();
        $mt = MT::create([
            'os_id'=>$request->os_id,
            'os_name'=>$request->os_name,
            'ctr_size'=>$request->ctr_size,
            'ctr_status'=>$request->ctr_status,
        ]);

        $osDetail = OSDetail::where('os_id', $os->id)->get();
        foreach ($osDetail as $detail) {
            $item = MItem::where('id', $detail->master_item_id)->first();
            // dd($item);
            $mtDetail = MTDetail::create([
                'master_tarif_id'=>$mt->id,
                'master_item_id'=>$detail->master_item_id,
                'master_item_name'=>$detail->master_item_name,
                'count_by'=> $item->count_by,
            ]);
        }

        return redirect()->route('plugging-tarif-detail', ['id' => $mt->id])->with('success', 'Silahkan Lengkapi Tairf');
    }

    public function tarifFirstRental(Request $request)
    {
        $os = OS::where('id', $request->os_id)->first();
        $mt = MT::create([
            'os_id'=>$request->os_id,
            'os_name'=>$request->os_name,
            'ctr_size'=>$request->ctr_size,
            'ctr_status'=>$request->ctr_status,
        ]);

        $osDetail = OSDetail::where('os_id', $os->id)->get();
        foreach ($osDetail as $detail) {
            $item = MItem::where('id', $detail->master_item_id)->first();
            // dd($item);
            $mtDetail = MTDetail::create([
                'master_tarif_id'=>$mt->id,
                'master_item_id'=>$detail->master_item_id,
                'master_item_name'=>$detail->master_item_name,
                'count_by'=> $item->count_by,
            ]);
        }

        return redirect()->route('rental-repair-tarif-detail', ['id' => $mt->id])->with('success', 'Silahkan Lengkapi Tairf');
    }

    public function tarifDetail(Request $request)
    {
        $mt = MT::where('id', $request->tarif_id)->first();
        $mt->update([
            'pajak'=>$request->pajak,
        ]);
        $mtDetail = MTDetail::where('master_tarif_id', $request->tarif_id)->get();
        foreach ($mtDetail as $detail) {
            $detail->update([
                'tarif'=>$request->tarif[$detail->id],
            ]);
        }

        return redirect()->back()->with('success', 'Data Berhasil di Update');
    }

    public function tarifDelete(Request $request)
    {
        $mt = MT::where('id', $request->id)->first();
        // dd($request->id, $mt);
        
        $mtDetail = MTDetail::where('master_tarif_id', $request->id)->get();
        foreach ($mtDetail as $detail) {
           $detail->delete();
        }

        $mt->delete();
        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }

    public function indexMTplugging(Request $request)
    {
        $data['title'] = "Mater Tarif Export";
        $data ['orderService'] = OS::where('ie', '=', 'P')->orderBy('ie', 'asc')->get();
        $data ['masterTarif'] = MT::get();
        return view('billingSystem.plugging.master-tarif.main', $data);
    }

    public function indexMTrental(Request $request)
    {
        $data['title'] = "Mater Tarif Export";
        $data ['orderService'] = OS::where('ie', '=', 'R')->orderBy('ie', 'asc')->get();
        $data ['masterTarif'] = MT::get();
        return view('billingSystem.rental-repair.master-tarif.main', $data);
    }
}
