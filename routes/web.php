<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\BayplanImportController;
use App\Http\Controllers\DischargeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DoOnlineController;
use App\Http\Controllers\CoparnsController;
use App\Http\Controllers\ExportInvoice;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SppsController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\AndroidController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\YardrotController;
use App\Http\Controllers\DischargeView;
use App\Http\Controllers\Stripping;
use App\Http\Controllers\Gati;
use App\Http\Controllers\Gato;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\EdiController;
use App\Http\Controllers\ProfileControllers;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportCont;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\CoparnController;
use App\Http\Controllers\ReportController;


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

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// });  

Route::get('/master/port', function () {
  return view('master.port');
});

//Route::get('/invoice', function () {
//    return view('invoice.dashboard');
//});


Route::post('/set-session/{key}/{value}', [SessionsController::class, 'setSession'])->name('set-session');
Route::post('/unset-session/{key}', [SessionsController::class, 'unsetSession'])->name('unset-session');



Route::prefix('invoice')->group(function () {
  Route::get('/', [InvoiceController::class, 'index']);
  Route::get('/menu', [InvoiceController::class, 'menuindex']);
  Route::get('/test', [InvoiceController::class, 'test']);
  Route::get('/delivery', [InvoiceController::class, 'deliveryForm']);
  Route::post('/export', [InvoiceController::class, 'exportToExcel']);
  Route::prefix('add')->group(function () {
    Route::prefix('/extend')->group(function () {
      Route::get('/', [InvoiceController::class, 'extendIndex']);
      Route::get('/step1', [InvoiceController::class, 'addDataExtendStep1']);
      Route::get('/update_step1', [InvoiceController::class, 'updateDataExtendStep1']);
      Route::get('/step2', [InvoiceController::class, 'addDataExtendStep2']);
      Route::post('/storestep1', [InvoiceController::class, 'storeDataExtendStep1']);
      Route::post('/storeupdatestep1', [InvoiceController::class, 'storeUpdateDataExtendStep1']);
      Route::post('/storestep2', [InvoiceController::class, 'storeDataExtendStep2']);
    });
    Route::get('/step1', [InvoiceController::class, 'addDataStep1']);
    Route::get('/update_step1', [InvoiceController::class, 'updateDataStep1']);
    Route::get('/step2', [InvoiceController::class, 'addDataStep2']);
    Route::post('/storestep1', [InvoiceController::class, 'storeDataStep1']);
    Route::post('/storeupdatestep1', [InvoiceController::class, 'storeUpdateDataStep1']);
    Route::post('/storestep2', [InvoiceController::class, 'storeDataStep2']);
  });
  Route::get('/pranota', [InvoiceController::class, 'Pranota']);
  Route::get('/paidinvoice', [InvoiceController::class, 'PaidInvoice']);
  Route::get('/job', [InvoiceController::class, 'jobPage']);

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
  Route::prefix('singleData')->group(function () {
    Route::post('/invoiceForm', [InvoiceController::class, 'singleInvoiceForm']);
    Route::post('/verifyPayment', [InvoiceController::class, 'VerifyPayment']);
    Route::post('/verifyPiutang', [InvoiceController::class, 'VerifyPiutang']);
    Route::post('/mastertarif', [InvoiceController::class, 'singleMasterTarif']);
    Route::post('/updateMasterTarif', [InvoiceController::class, 'updateMasterTarif']);
    Route::post('/createMasterTarif', [InvoiceController::class, 'createMasterTarif']);
    Route::post('/findContainer', [InvoiceController::class, 'findContainer']);
    Route::post('/findSingleCustomer', [InvoiceController::class, 'findSingleCustomer']);
    Route::post('/findContainerBooking', [InvoiceController::class, 'findContainerBooking']);
  });
  Route::prefix('mastertarif')->group(function () {
    Route::get('/', [InvoiceController::class, 'masterTarif']);
  });

  Route::prefix('payment')->group(function () {
    Route::get('/', [InvoiceController::class, 'paymentMethod']);
    Route::prefix('singleData')->group(function () {
      Route::post('/paymentmethod', [InvoiceController::class, 'singlePaymentMethod']);
      Route::post('/updatePaymentMethod', [InvoiceController::class, 'updatePaymentMethod']);
      Route::post('/createPaymentMethod', [InvoiceController::class, 'storePaymentMethod']);
    });
  });
});

Route::prefix('do')->group(function () {
  route::get('/', [DoOnlineController::class, 'index']);
  route::get('/create', [DoOnlineController::class, 'create']);
  route::post('/store', [DoOnlineController::class, 'store']);
});

Route::prefix('coparn')->group(function () {
  route::get('/', [CoparnsController::class, 'index']);
  route::get('/create', [CoparnsController::class, 'create']);
  route::get('/singlecreate', [CoparnsController::class, 'singleCreate']);
  route::post('/store', [CoparnsController::class, 'store']);
  route::post('/singlestore', [CoparnsController::class, 'singlestore']);
  Route::post('/findSingleVessel', [CoparnsController::class, 'findSingleVessel']);
});

Route::prefix('export')->group(function () {
  route::get('/', [ExportController::class, 'index']);
  Route::get('/delivery', [ExportController::class, 'deliveryForm']);
  Route::prefix('add')->group(function () {
    Route::get('/step1', [ExportController::class, 'addDataStep1']);
    Route::get('/update_step1', [ExportController::class, 'updateDataStep1']);
    Route::get('/step2', [ExportController::class, 'addDataStep2']);
    Route::post('/storestep1', [ExportController::class, 'storeDataStep1']);
    Route::post('/storeupdatestep1', [ExportController::class, 'storeUpdateDataStep1']);
    Route::post('/storestep2', [ExportController::class, 'storeDataStep2']);
  });
  Route::prefix('/stuffing')->group(function () {
    route::get('/', [ExportController::class, 'indexStuffing']);
    Route::get('/delivery', [ExportController::class, 'deliveryFormStuffing']);
    Route::prefix('add')->group(function () {
      Route::get('/step1', [ExportController::class, 'addDataStepStuffing1']);
      Route::get('/update_step1', [ExportController::class, 'updateDataStepStuffing1']);
      Route::get('/step2', [ExportController::class, 'addDataStepStuffing2']);
      Route::post('/storestep1', [ExportController::class, 'storeDataStepStuffing1']);
      Route::post('/storeupdatestep1', [ExportController::class, 'storeUpdateDataStepStuffing1']);
      Route::post('/storestep2', [ExportController::class, 'storeDataStepStuffing2']);
    });
  });
});

Route::prefix('spps')->group(function () {
  Route::get('/', [SppsController::class, 'index']);
  Route::get('/test', [SppsController::class, 'test']);
  Route::get('/delivery', [SppsController::class, 'deliveryForm']);
  Route::prefix('add')->group(function () {
    Route::get('/step1', [SppsController::class, 'addDataStep1']);
    Route::get('/update_step1', [SppsController::class, 'updateDataStep1']);
    Route::get('/step2', [SppsController::class, 'addDataStep2']);
    Route::post('/storestep1', [SppsController::class, 'storeDataStep1']);
    Route::post('/storeupdatestep1', [SppsController::class, 'storeUpdateDataStep1']);
    Route::post('/storestep2', [SppsController::class, 'storeDataStep2']);
  });
  Route::get('/pranota', [SppsController::class, 'Pranota']);
  Route::get('/paidinvoice', [SppsController::class, 'PaidInvoice']);
  Route::get('/job', [SppsController::class, 'jobPage']);

  Route::prefix('customer')->group(function () {
    Route::get('/', [SppsController::class, 'customerDashboard']);
    Route::get('/add', [SppsController::class, 'addDataCustomer']);
    Route::post('/store', [SppsController::class, 'storeDataCustomer'])->name('customer.store');
  });
  Route::prefix('singleData')->group(function () {
    Route::post('/invoiceForm', [SppsController::class, 'singleInvoiceForm']);
    Route::post('/verifyPayment', [SppsController::class, 'VerifyPayment']);
    Route::post('/verifyPiutang', [SppsController::class, 'VerifyPiutang']);
    Route::post('/mastertarif', [SppsController::class, 'singleMasterTarif']);
    Route::post('/updateMasterTarif', [SppsController::class, 'updateMasterTarif']);
    Route::post('/createMasterTarif', [SppsController::class, 'createMasterTarif']);
  });
  Route::prefix('mastertarif')->group(function () {
    Route::get('/', [SppsController::class, 'masterTarif']);
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
Route::get('/refresh_counter', [VesselController::class, 'refreshCounter']);


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

//kotak-kotak
Route::get('/disch/view-vessel', [DischargeView::class, 'index']);
Route::post('/get-ves', [DischargeView::class, 'get_ves']);
Route::post('/get-bay', [DischargeView::class, 'get_bay']);
Route::get('/get-container', [DischargeView::class, 'get_container']);
// Android
Route::get('/android-dashboard', [AndroidController::class, 'index']);
Route::get('/disch/confirm_disch', [DischargeController::class, 'index']);
Route::post('/search-container', [DischargeController::class, 'container']);
Route::post('/get-container-key', [DischargeController::class, 'get_key']);
Route::post('/confirm', [DischargeController::class, 'confirm']);

//tampilan android
Route::get('/disch/android', [DischargeController::class, 'android']);
Route::get('/yard/android', [PlacementController::class, 'android']);
Route::get('/stripping/android', [Stripping::class, 'android']);
Route::get('/delivery/android-in', [Gati::class, 'android']);
Route::get('/delivery/android-out', [Gato::class, 'android']);

Route::get('/yard/placement', [PlacementController::class, 'index']);
Route::post('/placement', [PlacementController::class, 'place']);
Route::post('/dapet-tipe', [PlacementController::class, 'get_tipe']);
Route::post('/container-tipe', [PlacementController::class, 'tipe_container']);


//Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route::post('/get-slot', [PlacementController::class, 'get_slot']);
// Route::post('/confirm', [DischargeController::class, 'confirm']);


// //role master Port
// Route::get('/master/port', [MasterController::class, 'port']);
// Route::post('/master/port_store', [MasterController::class, 'port_store'])->name('/master/port_store');
// Route::post('/master/port_edit_store', [MasterController::class, 'port_edit_store'])->name('/master/port_edit_store');
// Route::delete('/master/delete_port={port}', [MasterController::class, 'delete_port']);
// Route::get('/master/edit_port', [MasterController::class, 'edit_port']);

// //role master Vessel
// Route::get('/master/vessel', [MasterController::class, 'vessel']);
// Route::post('/master/vessel_store', [MasterController::class, 'vessel_store'])->name('/master/vessel_store');
// Route::post('/master/vessel_edit_store', [MasterController::class, 'vessel_edit_store'])->name('/master/vessel_edit_store');
// Route::delete('/master/delete_vessel={vessel}', [MasterController::class, 'delete_vessel']);
// Route::get('/master/edit_vessel', [MasterController::class, 'edit_vessel']);


// //role master VesBerthsel
// Route::get('/master/berth', [MasterController::class, 'berth']);
// Route::post('/master/berth_store', [MasterController::class, 'berth_store'])->name('/master/berth_store');
// Route::post('/master/berth_edit_store', [MasterController::class, 'berth_edit_store'])->name('/master/berth_edit_store');
// Route::delete('/master/delete_berth={berth_no}', [MasterController::class, 'delete_berth']);
// Route::get('/master/edit_berth', [MasterController::class, 'edit_berth']);


// //role Vessel Servicel
// Route::get('/master/service', [MasterController::class, 'service']);
// Route::post('/master/service_store', [MasterController::class, 'service_store'])->name('/master/service_store');
// Route::post('/master/service_edit_store', [MasterController::class, 'service_edit_store'])->name('/master/service_edit_store');
// Route::delete('/master/delete_service={service_id}', [MasterController::class, 'delete_service']);
// Route::get('/master/edit_service', [MasterController::class, 'edit_service']);


// //role ISO Code
// Route::get('/master/isocode', [MasterController::class, 'isocode']);
// Route::post('/master/isocode_store', [MasterController::class, 'isocode_store'])->name('/master/isocode_store');
// Route::post('/master/isocode_edit_store', [MasterController::class, 'isocode_edit_store'])->name('/master/isocode_edit_store');
// Route::delete('/master/delete_isocode={iso_code}', [MasterController::class, 'delete_isocode']);
// Route::get('/master/edit_isocode', [MasterController::class, 'edit_isocode']);



// //role Yard Block
// Route::get('/master/block', [MasterController::class, 'block']);
// Route::post('/master/block_store', [MasterController::class, 'block_store'])->name('/master/block_store');
// Route::get('/master/edit_block', [MasterController::class, 'edit_block']);

// //role EDI Baplie recievr
// Route::get('/edi/receiveedi', [EdiController::class, 'receiveedi']);
// Route::post('/edi/receiveeditxt_store', [EdiController::class, 'receiveeditxt_store'])->name('/edi/receiveeditxt_store');
// Route::delete('/edi/delete_itembayplan={container_key}', [EdiController::class, 'delete_itembayplan']);
// Route::get('/edi/edit_itembayplan', [EdiController::class, 'edit_itembayplan']);



route::resource('yards/rowtier', YardrotController::class);
route::post('yards/rowtier/get_rowtier', [YardrotController::class, 'get_rowtier'])->name('rowtier.get_rowtier');




Route::get('/planning/bayplan_import', [BayplanImportController::class, 'index']);


Route::get('/stripping', [Stripping::class, 'index']);
Route::post('/get-stripping', [Stripping::class, 'get_stripping']);
Route::post('/stripping-place', [Stripping::class, 'stripping_place']);

Route::get('/delivery/gate-in', [Gati::class, 'index']);
Route::post('/gati-data_container', [Gati::class, 'data_container']);
Route::post('/gati-del', [Gati::class, 'gati_del']);

Route::get('/delivery/gate-out', [Gato::class, 'index']);
Route::post('/gato-data_container', [Gato::class, 'data_container']);
Route::post('/gato-del', [Gato::class, 'gato_del']);


// history
Route::group([
  'prefix' => 'reports',
  'as' => 'reports.'
], function () {
  Route::post('/hist/get_cont', [
    HistoryController::class,
    'get_cont'
  ])->name('hist.get_cont');
  Route::post('/hist/get_cont_hist', [
    HistoryController::class,
    'get_cont_hist'
  ])->name('hist.get_cont_hist');
  Route::post('/hist/get_cont_job', [
    HistoryController::class,
    'get_cont_job'
  ])->name('hist.get_cont_job');
  Route::get('/blank', function () {
    return view('reports.hist.blank');
  })->name('hist.blank');
  Route::resource('/hist', HistoryController::class);
});
//role master Port
Route::get('/master/port', [MasterController::class, 'port']);
Route::post('/master/port_store', [MasterController::class, 'port_store'])->name('/master/port_store');
Route::post('/master/port_edit_store', [MasterController::class, 'port_edit_store'])->name('/master/port_edit_store');
Route::delete('/master/delete_port={port}', [MasterController::class, 'delete_port']);
Route::get('/master/edit_port', [MasterController::class, 'edit_port']);

//role master Vessel
Route::get('/master/vessel', [MasterController::class, 'vessel']);
Route::post('/master/vessel_store', [MasterController::class, 'vessel_store'])->name('/master/vessel_store');
Route::post('/master/vessel_edit_store', [MasterController::class, 'vessel_edit_store'])->name('/master/vessel_edit_store');
Route::delete('/master/delete_vessel={vessel}', [MasterController::class, 'delete_vessel']);
Route::get('/master/edit_vessel', [MasterController::class, 'edit_vessel']);


//role master VesBerthsel
Route::get('/master/berth', [MasterController::class, 'berth']);
Route::post('/master/berth_store', [MasterController::class, 'berth_store'])->name('/master/berth_store');
Route::post('/master/berth_edit_store', [MasterController::class, 'berth_edit_store'])->name('/master/berth_edit_store');
Route::delete('/master/delete_berth={berth_no}', [MasterController::class, 'delete_berth']);
Route::get('/master/edit_berth', [MasterController::class, 'edit_berth']);


//role Vessel Servicel
Route::get('/master/service', [MasterController::class, 'service']);
Route::post('/master/service_store', [MasterController::class, 'service_store'])->name('/master/service_store');
Route::post('/master/service_edit_store', [MasterController::class, 'service_edit_store'])->name('/master/service_edit_store');
Route::delete('/master/delete_service={service_id}', [MasterController::class, 'delete_service']);
Route::get('/master/edit_service', [MasterController::class, 'edit_service']);


//role ISO Code
Route::get('/master/isocode', [MasterController::class, 'isocode']);
Route::post('/master/isocode_store', [MasterController::class, 'isocode_store'])->name('/master/isocode_store');
Route::post('/master/isocode_edit_store', [MasterController::class, 'isocode_edit_store'])->name('/master/isocode_edit_store');
Route::delete('/master/delete_isocode={iso_code}', [MasterController::class, 'delete_isocode']);
Route::get('/master/edit_isocode', [MasterController::class, 'edit_isocode']);



//role Yard Block
Route::get('/master/block', [MasterController::class, 'block']);
Route::post('/master/block_store', [MasterController::class, 'block_store'])->name('/master/block_store');
Route::get('/master/edit_block', [MasterController::class, 'edit_block']);

//role EDI Baplie recievr
Route::get('/edi/receiveedi', [EdiController::class, 'receiveedi']);
Route::post('/edi/receiveeditxt_store', [EdiController::class, 'receiveeditxt_store'])->name('/edi/receiveeditxt_store');
Route::delete('/edi/delete_itembayplan={container_key}', [EdiController::class, 'delete_itembayplan']);
Route::get('/edi/edit_itembayplan', [EdiController::class, 'edit_itembayplan']);



route::resource('yard/rowtier', YardrotController::class);
route::post('yards/rowtier/get_rowtier', [YardrotController::class, 'get_rowtier'])->name('rowtier.get_rowtier');

Route::middleware('role:admin')->get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::get('/profile', [ProfileControllers::class, 'index']);
Route::post('/update_profile_photo', [ProfileControllers::class, 'profil']);

Route::get('/planning/report', [ReportCont::class, 'index'])->name('report.index');
Route::post('/review-get-ves', [ReportCont::class, 'get_ves'])->name('report.get-ves');
Route::post('/review-get-bay', [ReportCont::class, 'get_bay'])->name('report.get-bay');
Route::get('/review-get-container', [ReportCont::class, 'get_container'])->name('report.get-container');
Route::get('/generate-pdf-disch', [ReportCont::class, 'generatePDF_disch'])->name('report.generate-pdf-disch');

// Routes untuk Realisasi Bongkar
Route::get('/planning/realisasi-bongkar', [ReportCont::class, 'index_bongkar'])->name('realisasi-bongkar.index');
Route::post('/realisasi-get-ves', [ReportCont::class, 'get_ves_bongkar'])->name('realisasi-bongkar.get-ves');
Route::post('/realisasi-get-bay', [ReportCont::class, 'get_bay_bongkar'])->name('realisasi-bongkar.get-bay');
Route::get('/realisasi-get-container', [ReportCont::class, 'get_container_bongkar'])->name('realisasi-bongkar.get-container');
Route::get('/generate-pdf-bongkar', [ReportCont::class, 'generatePDF_bongkar'])->name('realisasi-bongkar.generate-pdf-bongkar');


Route::post('/upload', [EdiController::class, 'upload'])->name('upload.submit');

Route::post('/get-con-disch', [DischargeController::class, 'get_cont']);

//Reports
// Disch
Route::get('/reports/disch', [ReportController::class, 'index'])->name('report.index');
Route::post('/review-get-ves-disch', [ReportController::class, 'get_ves'])->name('report.get-ves');
Route::post('/review-get-bay-disch', [ReportController::class, 'get_bay'])->name('report.get-bay');
Route::get('/review-get-container-disch', [ReportController::class, 'get_container'])->name('report.get-container');
Route::get('/generate-report-disch', [ReportController::class, 'generateREPT_disch'])->name('report.generate-report-disch');
//PLC
Route::get('/reports/plc', [ReportController::class, 'index_plc'])->name('report.index_plc');
Route::get('/review-get-container-plc', [ReportController::class, 'get_container_plc'])->name('report.get-container-plc');
Route::get('/generate-report-plc', [ReportController::class, 'generateREPT_plc'])->name('report.generate-report-plc');
//STRIPPING
Route::get('/reports/str', [ReportController::class, 'index_str'])->name('report.index_str');
Route::get('/review-get-container-str', [ReportController::class, 'get_container_str'])->name('report.get-container-str');
Route::get('/generate-report-str', [ReportController::class, 'generateREPT_str'])->name('report.generate-report-str');
//GATI-DEL
Route::get('/reports/gati-del', [ReportController::class, 'index_gati_del'])->name('report.index_gati_del');
Route::get('/review-get-container-gati-del', [ReportController::class, 'get_container_gati_del'])->name('report.get-container-gati-del');
Route::get('/generate-report-gati-del', [ReportController::class, 'generateREPT_gati_del'])->name('report.generate-report-gati-del');
// GATO-DEL
Route::get('/reports/gato-del', [ReportController::class, 'index_gato_del'])->name('report.index_gato-del');
Route::get('/review-get-container-gato-del', [ReportController::class, 'get_container_gato_del'])->name('report.get-container-gato-del');
Route::get('/generate-report-gato-del', [ReportController::class, 'generateREPT_gato_del'])->name('report.generate-report-gato-del');
//Export

Route::get('/edi/coparn', [CoparnController::class, 'index']);
