<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\AlasanSegel;
use App\Models\Segel;
use Illuminate\Support\Facades\Storage;

class BCGatterController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
        $title = 'BC-Dahsboard';
        $item  = Item::where('bc_flag','=', 'HOLD')->whereNot('jenis_dok','=', 'SPPB2.0');
        $item_to_hold  = Item::where('jenis_dok','=', 'SPPB2.3')->where('bc_flag','=', 'RELEASE');

        return view('bc.gatter.index', compact('title', 'item'));
    }

    public function hold_index()
    {
        $title = 'BC-Hold';
        $item  = Item::where('bc_flag','=', 'HOLD')->whereNot('jenis_dok','=', 'SPPB2.0')->get();
      
        return view('bc.gatter.view.holding', compact('title', 'item'));
    }

    public function release_cont(Request $request)
    {
        $id = $request->container_key;

        $item = Item::where('container_key', '=', $id);

        if ($item) {
            $item->update([
                'bc_flag' => 'RELEASE',
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $item,
            ]);
        }
    }

    public function holdingp2_index()
    {
        $title = 'BC-Dahsboard';
        $item = Item::where('jenis_dok','=', 'SPPB2.0')->where('bc_flag','=', 'RELEASE')->where('ctr_active_yn','=','Y')->get();

        $alseg = AlasanSegel::get();
        return view('bc.gatter.view.hold-p2', compact('title', 'item', 'alseg'));
    }

    public function holdingp2_cont(Request $request)
    {
        $id = $request->container_key;
        $item = Item::where('container_key', '=', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'data' => $item,
        ]);
    }

    public function holding_cont(Request $request)
    {
        $id = $request->container_key;

        $item = Item::where('container_key', $id)->first();

        if ($item) {
            $item->update([
                'bc_flag' => 'HOLD',
            ]);

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('segel', $fileName);
            
            $segel = Segel::create([
                'container_key'=>$request->container_key,
                'container_no'=>$item->container_no,
                'alasan_segel'=>$request->alasan_segel,
                'keterangan'=>$request->keterangan,
                'file'=>$fileName,
                'no_dok'=>'TEST112',
                'jenis_dok'=>$item->jenis_dok,
                'status'=>'HOLD',
            ]) ;
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $item,
            ]);
        }
    }

    public function proses_releaseP2()
    {
        $title = 'Proses Release P2';
        $segel = Segel::where('status', '=', 'HOLD')->get();
        return view('bc.gatter.view.realeasep2', compact('segel', 'title'));
    }

    public function showDocument(Request $request)
    {
        $id = $request->file;
        $tema = Segel::where('file',$id)->first();
    
        // Jika tema ditemukan, tampilkan file PDF
        if ($tema) {
            $filePath = storage_path('app/segel/' . $tema->file);
            
            // Periksa apakah file PDF ada
            if (file_exists($filePath)) {
                return response()->file($filePath, ['Content-Type' => 'application/pdf']);
            } else {
                return response()->json(['error' => 'File PDF tidak ditemukan'], 404);
            }
        } else {
            return response()->json(['error' => 'Tema tidak ditemukan'], 404);
        }
    }

    public function release_p2(Request $request)
    {
        $id = $request->container_key;

        $item = Item::where('container_key', '=', $id);
        $segel = Segel::where('container_key', '=', $id);

        if ($item) {
            $item->update([
                'bc_flag' => 'RELEASE',
            ]);

            $segel->update([
                'status' => 'RELEASE',
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $item,
            ]);
        }
    }

}
