<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\InvoiceService\TrackingInvoice;
use App\Http\Controllers\Api\CustomerService\GetDataServcie;
use App\Http\Controllers\Api\Payment\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/trackingInvoice')->controller(TrackingInvoice::class)->group(function(){
    Route::post('/searchByDo', 'searchByDo');
});


Route::prefix('/getData')->controller(GetDataServcie::class)->group(function(){
    Route::post('/customer', 'customer')->name('api.customer.GetData-customer');
    Route::post('/vessel', 'vessel')->name('api.customer.GetData-vessel');
    Route::post('/bookingNo', 'bookingNo')->name('api.customer.GetData-booking');
});

Route::controller(PaymentController::class)->group(function(){
    Route::prefix('/payment')->group(function(){
        Route::post('/billPayment', 'billPaymentRequest');
    });
});
