<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

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

        return view('bc.gatter.view.hold-p2', compact('title', 'item'));
    }
}
