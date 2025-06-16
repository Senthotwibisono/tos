<?php

namespace App\Http\Controllers\Api\CustomerService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\VVoyage;
use App\Models\Item;
use App\Models\MasterUserInvoice as MUI;
use Auth;

class GetDataServcie extends Controller
{
    public function customer(Request $request)
    {
        $customer = Customer::find($request->cust_id);
        if ($customer) {
            return response()->json([
                'success' => true, 
                'data' => $customer, 
            ]);
        }else {
            return response()->json([
                'success' => false,
            ]);
        }
        // var_dump($request->all());
    }

    public function vessel(Request $request)
    {
        $vessel = VVoyage::find($request->ves_id);
        if ($vessel) {
            return response()->json([
                'success' => true, 
                'data' => $vessel, 
            ]);
        }else {
            return response()->json([
                'success' => false,
            ]);
        }
        // var_dump($request->all());
    }

    public function bookingNo(Request $request)
    {
        // var_dump($request->all());
        $items = Item::where('booking_no', $request->booking_no)->where('ctr_intern_status', 49)->where('selected_do', 'N')->where('ctr_i_e_t', 'E')->get();
        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada container yang dapat di pilih',
            ]);
        }

        $singleItem = $items->first();
        $customer = Customer::where('code', $singleItem->customer_code)->first();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Something wrong in customer field, call admin',
            ]);
        }
        // var_dump(Auth::user()->id);
        // die();

        $mui = MUI::where('user_id', $request->userId)->where('customer_id', $customer->id)->get();
        if ($mui->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking No ini tidak untuk customer anda!!!',
            ]);
        }

        $vessel = VVoyage::find($singleItem->ves_id);
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'customer' => $customer,
                    'vessel' => $vessel,
                    'items' => $items,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }


    public function getBookingGlobal(Request $request)
    {
        // var_dump($request->all());
        $items = Item::where('booking_no', $request->booking_no)->where('ctr_intern_status', 49)->where('selected_do', 'N')->where('ctr_i_e_t', 'E')->get();
        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada container yang dapat di pilih',
            ]);
        }
        $singleItem = $items->first();
        $vessel = VVoyage::find($singleItem->ves_id);
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'vessel' => $vessel,
                    'items' => $items,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
