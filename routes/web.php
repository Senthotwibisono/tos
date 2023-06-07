<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\BayplanImportController;
use App\Http\Controllers\DischargeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\SessionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// });  

Route::get('/master/port', function () {
    return view('master.port');
});

// Route::get('/invoice', function () {
//     return view('invoice.dashboard');
// });

Route::post('/set-session/{key}/{value}', [SessionsController::class, 'setSession'])->name('set-session');
Route::post('/unset-session/{key}', [SessionsController::class, 'unsetSession'])->name('unset-session');



Route::prefix('invoice')->group(function () {
    Route::get('/', [InvoiceController::class, 'index']);
    Route::get('/main', [InvoiceController::class, 'test']);
    Route::prefix('add')->group(function () {
        Route::get('/step1', [InvoiceController::class, 'addDataStep1']);
        Route::get('/step2', [InvoiceController::class, 'addDataStep2']);
        Route::get('/step3', [InvoiceController::class, 'addDataStep3']);
        Route::post('/storestep1', [InvoiceController::class, 'storeDataStep1']);
        Route::post('/storestep2', [InvoiceController::class, 'storeDataStep2']);
        Route::post('/storestep3', [InvoiceController::class, 'storeDataStep3']);
    });
    Route::get('/pranota', [InvoiceController::class, 'Pranota']);
    Route::get('/paidinvoice', [InvoiceController::class, 'PaidInvoice']);
    Route::prefix('customer')->group(function () {
        Route::get('/', [InvoiceController::class, 'customerDashboard']);
        Route::get('/add', [InvoiceController::class, 'addDataCustomer']);
        Route::post('/store', [InvoiceController::class, 'storeDataCustomer'])->name('customer.store');
    });
    Route::prefix('container')->group(function () {
        Route::get('/', [InvoiceController::class, 'containerDashboard']);
        Route::get('/add', [InvoiceController::class, 'addDataContainer']);
        Route::post('/store', [InvoiceController::class, 'storeDataContainer']);
    });
});




Auth::routes();

Route::get('/system/user', [SystemController::class, 'user'])->name('system.user.main');
Route::get('/system/role', [SystemController::class, 'role'])->name('system.role.main');
Route::get('/system/role/addrole', [SystemController::class, 'createrole'])->name('system.role.cretae');
Route::post('/system/role/rolestore', [SystemController::class, 'rolestore']);
Route::get('/system/edit_role={id}', [SystemController::class, 'edit_role'])->name('system.role.edit');
Route::patch('/system/role_update={id}', [SystemController::class, 'update_role']);
Route::delete('/system/delete_role={id}', [SystemController::class, 'delete_role']);

Route::get('/system/user/create_user', [SystemController::class, 'create_user'])->name('system.user.cretae');
Route::post('/system/user_store', [SystemController::class, 'user_store']);
Route::get('/system/edit_user={id}', [SystemController::class, 'edit_user'])->name('system.user.edit');
Route::patch('/system/user_update={id}', [SystemController::class, 'update_user']);
Route::delete('/system/delete_user={id}', [SystemController::class, 'delete_user']);

Route::get('/planning/vessel-schedule', [VesselController::class, 'index']);
Route::get('/planning/create-schedule', [VesselController::class, 'create']);
Route::post('/getvessel', [VesselController::class, 'getvessel'])->name('getvessel');
Route::post('/getvessel_agent', [VesselController::class, 'getvessel_agent'])->name('getvessel_agent');
Route::post('/getvessel_liner', [VesselController::class, 'getvessel_liner'])->name('getvessel_linert');
Route::post('/reg_flag', [VesselController::class, 'reg_flag'])->name('reg_flag');
Route::post('/length', [VesselController::class, 'length'])->name('length');
Route::post('/bcode', [VesselController::class, 'bcode'])->name('bcode');
Route::post('/from', [VesselController::class, 'from'])->name('from');
Route::post('/tlength', [VesselController::class, 'tlength'])->name('tlength');
Route::post('/origin', [VesselController::class, 'origin'])->name('origin');
Route::post('/next', [VesselController::class, 'next'])->name('next');
Route::post('/dest', [VesselController::class, 'dest'])->name('dest');
Route::post('/last', [VesselController::class, 'last'])->name('last');
Route::post('/planning/vessel_schedule_store', [VesselController::class, 'schedule_store'])->name('/planning/vessel_schedule_store');
Route::get('/planning/schedule_schedule={ves_id}', [VesselController::class, 'edit_schedule']);
Route::patch('/planning/schedule_update={ves_id}', [VesselController::class, 'update_schedule']);
Route::delete('/planning/delete_schedule={ves_id}', [VesselController::class, 'delete_schedule']);

Route::get('/planning/bayplan_import', [BayplanImportController::class, 'index']);
Route::post('/getsize', [BayplanImportController::class, 'size']);
Route::post('/gettype', [BayplanImportController::class, 'type']);
Route::post('/getcode', [BayplanImportController::class, 'code']);
Route::post('/getname', [BayplanImportController::class, 'name']);
Route::post('/getvoy', [BayplanImportController::class, 'voy']);
Route::post('/getagent', [BayplanImportController::class, 'agent']);
Route::post('/planning/bayplan_import', [BayplanImportController::class, 'store']);
Route::get('/planning/edit_bayplanimport_{container_key}', [BayplanImportController::class, 'edit']);
Route::post('/getsize_edit', [BayplanImportController::class, 'size_edit']);
Route::post('/gettype_edit', [BayplanImportController::class, 'type_edit']);
Route::post('/get-iso-type', [BayplanImportController::class, 'get_iso_type']);
Route::post('/get-iso-size', [BayplanImportController::class, 'get_iso_size']);
Route::post('/get-ves-name', [BayplanImportController::class, 'get_ves_name']);
Route::post('/planning/update_bayplanimport', [BayplanImportController::class, 'update_bayplanimport']);
Route::delete('/planning/delete_item={container_key}', [BayplanImportController::class, 'delete_item']);

Route::get('/disch/confrim_disch', [DischargeController::class, 'index']);
Route::post('/search-container', [DischargeController::class, 'container']);
Route::post('/get-container-key', [DischargeController::class, 'get_key']);
Route::post('/confirm', [DischargeController::class, 'confirm']);

Route::get('/yard/placement', [PlacementController::class, 'index']);
Route::post('/placement', [PlacementController::class, 'place']);
Route::post('/get-tipe', [PlacementController::class, 'get_tipe']);
// Route::post('/confirm', [DischargeController::class, 'confirm']);

Route::middleware('role:admin')->get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
