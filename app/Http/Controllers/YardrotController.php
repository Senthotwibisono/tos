<?php
namespace App\Http\Controllers;

use App\Models\YardRot; // load model
use App\Models\Item; // load model
use Illuminate\Http\Request;

class YardrotController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {
        $title ='Yard View';
        $lt_block = YardRot::select('yard_block')->GroupBy('yard_block')->get();
        $lt_slot = YardRot::select('yard_slot')->GroupBy('yard_slot')->get();
        $lt_xy = $this->get_rtdisp($lt_block[0]->yard_block, $lt_slot[0]->yard_slot);
        $row = $this->get_row($lt_block[0]->yard_block, $lt_slot[0]->yard_slot);
        $tier = $this->get_tier($lt_block[0]->yard_block, $lt_slot[0]->yard_slot);
        $content = view('yards.rowtier.rowtier', compact('lt_xy', 'tier', 'row'))->render();
        return view('yards.rowtier.index', compact('lt_block', 'lt_slot', 'content', 'title', 'row', 'tier'));
    }

    public function get_rowtier(Request $request)
    {
        $block_no = $request->block_no;
        $slot_no = $request->slot_no;
       
        $row = $this->get_row($block_no, $slot_no);
        $tier = $this->get_tier($block_no, $slot_no);
       

        $lt_xy = $this->get_rtdisp($block_no, $slot_no);
        return response()->view('yards.rowtier.rowtier', compact('lt_xy', 'row', 'tier'));

        exit();
    }

    private function get_row($block_no, $slot_no)
    {
        $row_tot = YardRot::where('yard_block', $block_no)->where('yard_slot', $slot_no)->distinct('yard_row')->pluck('yard_row');
        $row = $row_tot->count();
        
        return $row;
    }

    private function get_tier($block_no, $slot_no)
    {
        $tier_tot = YardRot::where('yard_block', $block_no)->where('yard_slot', $slot_no)->distinct('yard_tier')->pluck('yard_tier');
        $tier = $tier_tot->count();
        
        return $tier;
    }

    /**
     */
    private function get_rtdisp($block_no, $slot_no)
    {
        $lt_rowtier = YardRot::select('yard_rowtier.yard_row', 'yard_rowtier.yard_tier', 'yard_rowtier.container_key', 'yard_rowtier.container_no', 'item.load_port', 'item.disch_port', 'item.iso_code', 'ctr_type', 'gross', 'gross_class')->join('item', function ($join) {
            $join->on('item.container_key', '=', 'yard_rowtier.container_key');
            $join->on('item.container_no', '=', 'yard_rowtier.container_no');
        })
            ->where('yard_rowtier.YARD_BLOCK', '=', $block_no)
            ->where('yard_rowtier.YARD_SLOT', '=', $slot_no)
            ->orderBy('yard_rowtier.YARD_TIER', 'asc')
            ->orderBy('yard_rowtier.YARD_ROW', 'asc')
            ->get();
        $lt_xy = array();
        foreach ($lt_rowtier as $lw_rowtier) {
            $disp = json_decode(json_encode([
                'fr' => $lw_rowtier->load_port,
                'to' => $lw_rowtier->disch_port,
                'cnt' => $lw_rowtier->container_no,
                'key' => $lw_rowtier->container_key,
                'typ' => $lw_rowtier->iso_code,
                'qty' => $lw_rowtier->gross,
                'iso' => $lw_rowtier->gross_class
            ]), FALSE);
            $lt_xy[$lw_rowtier->yard_tier - 1][$lw_rowtier->yard_row - 1] = $disp;
        }
        return $lt_xy;
    }

    public function view_cont(Request $request)
    {
        $id = $request->container_key;
        $view_cont = Item::where('container_key', $id)->first();

        if ($view_cont) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'data' => $view_cont,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong!!',
            ]);
        }
    }

    public function get_slot(request $request)
    {
        $block = $request->block_no;
        $slot = YardRot::where('yard_block', $block)->distinct('yard_slot')->pluck('yard_slot');
        foreach ($slot as $sl) {
            echo "<option value='$sl'>$sl</option>";
        }
       
    }
}
