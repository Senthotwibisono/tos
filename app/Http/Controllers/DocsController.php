<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Yard;
use App\Models\RO_Gate;
use App\Models\RO_Realisasi;
use App\Models\RO;
use App\Models\VVoyage;
use Auth;
use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index_ro()
    {
        $title = 'Dokumen R.O';

        $ro = RO::orderBy('created_at', 'desc')->get();

        return view('docs.dokumen.ro', compact('title', 'ro'));
    }

    public function index_items()
    {
        $title = 'Inventory Items';

        $items = Item::whereNotIn('ctr_intern_status', ['09', '56'])->get();

        return view('docs.inventory.container', compact('title', 'items'));
    }
    
    public function container_ro(Request $request)
    {
        $id = $request->ro_no;
        $detail_cont = RO_Realisasi::where('ro_no', $id)->get();

        if ($detail_cont) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $detail_cont,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!!',
            ]);
        }
    }
}
