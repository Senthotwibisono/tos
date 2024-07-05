<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\BayplanImportController;
use App\Http\Controllers\DischargeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BillingImportController;
use App\Http\Controllers\BillingExportController;
use App\Http\Controllers\DoOnlineController;
use App\Http\Controllers\CoparnsController;
use App\Http\Controllers\ExportInvoice;
use App\Http\Controllers\BeaCukaiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\StuffingController;
use App\Http\Controllers\SppsController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\AndroidController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\YardrotController;
use App\Http\Controllers\DischargeView;
use App\Http\Controllers\Stripping;
use App\Http\Controllers\Stuffing;
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
use App\Http\Controllers\ShipPlanController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\BeaController;
use App\Http\Controllers\SoapController;
use App\Http\Controllers\ProfileKapal;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\GateRelokasiController;
use App\Http\Controllers\TruckingController;
use App\Http\Controllers\BCGatterController;
use App\Http\Controllers\MasterTarifController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InvoiceExportController;
use App\Http\Controllers\BayplanDesignController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\BatalMuatController;
use App\Http\Controllers\InvoiceExtend;
use App\Http\Controllers\StevadooringController;
use App\Http\Controllers\ShiftingController;
use App\Http\Controllers\ZahirController;
use App\Http\Controllers\MasterInvoiceController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\RealisasiMuatController;
use App\Http\Controllers\GateCheckingController;
use App\Http\Controllers\GateMTcontroller;
use App\Http\Controllers\PluggingController;
use App\Exports\ContainersReport;

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


Route::post('/findContainerArray', [InvoiceController::class, 'findContainerArray']);

Route::post('/beacukaiImportCheck', [BeacukaiController::class, 'beacukaiImportCheck']);
Route::post('/beacukaiExportCheck', [BeacukaiController::class, 'beacukaiExportCheck']);
Route::post('/allContainerImport', [InvoiceController::class, 'allContainerImport']);
Route::post('/findContainerArray', [InvoiceController::class, 'findContainerArray']);
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
    Route::post('/verifyPayment2', [InvoiceController::class, 'VerifyPayment2']);
    Route::post('/verifyPiutang', [InvoiceController::class, 'VerifyPiutang']);
    Route::post('/mastertarif', [InvoiceController::class, 'singleMasterTarif']);
    Route::post('/updateMasterTarif', [InvoiceController::class, 'updateMasterTarif']);
    Route::post('/createMasterTarif', [InvoiceController::class, 'createMasterTarif']);
    Route::post('/findContainer', [InvoiceController::class, 'findContainer']);
    Route::post('/findSingleCustomer', [InvoiceController::class, 'findSingleCustomer']);
    Route::post('/findContainerBooking', [InvoiceController::class, 'findContainerBooking']);
    Route::post('/findContainerRo', [InvoiceController::class, 'findContainerRo']);
  });
  Route::prefix('mastertarif')->group(function () {
    Route::get('/', [InvoiceController::class, 'masterTarif']);
    Route::get('/add', [InvoiceController::class, 'addMasterTarif']);
    Route::get('/edit', [InvoiceController::class, 'editMasterTarif']);
    Route::post('/store', [InvoiceController::class, 'storeCreateMasterTarif']);
    Route::post('/storeEdit', [InvoiceController::class, 'storeEditMasterTarif']);
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
  Route::prefix('/stuffingold')->group(function () {
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
    Route::get('/pranota1', [ExportController::class, 'Pranota1']);
    Route::get('/pranota2', [ExportController::class, 'Pranota2']);
    Route::get('/paidinvoice1', [ExportController::class, 'PaidInvoice1']);
    Route::get('/paidinvoice2', [ExportController::class, 'PaidInvoice2']);
    Route::get('/job', [ExportController::class, 'jobPage']);
  });
  Route::prefix('/stuffing-in')->group(function () {
    Route::get('/', [StuffingController::class, 'index']);
    Route::get('/form', [StuffingController::class, 'formStuffing']);
    Route::get('/previewJobContainer', [StuffingController::class, 'previewDataJob']);
    Route::prefix('/add')->group(function () {
      Route::get('/step1', [StuffingController::class, 'addDataStep1']);
      Route::get('/updatestep1', [StuffingController::class, 'updateDataStep1']);
      Route::get('/step2', [StuffingController::class, 'addDataStep2']);
      Route::post('/storestep1', [StuffingController::class, 'storeDataStep1']);
      Route::post('/updatestep1', [StuffingController::class, 'updateDataStep1']);
      Route::post('/storestep2', [StuffingController::class, 'storeDataStep2']);
    });
    Route::prefix('/invoice')->group(function () {
      Route::get('/', [StuffingController::class, 'invoiceIndex']);
      Route::get('/form', [StuffingController::class, 'invoiceForm']);
      Route::prefix('/add')->group(function () {
        Route::get('/step1', [StuffingController::class, 'invoiceStep1']);
        Route::get('/updatestep1', [StuffingController::class, 'invoiceUpdateStep1']);
        Route::get('/step2', [StuffingController::class, 'invoiceStep2']);
        Route::post('/storestep1', [StuffingController::class, 'invoiceStoreStep1']);
        Route::post('/updatestep1', [StuffingController::class, 'invoiceUpdateStep1']);
        Route::post('/storestep2', [StuffingController::class, 'invoiceStoreStep2']);
      });
    });
    Route::prefix('/generate')->group(function () {
      Route::get('/', [StuffingController::class, 'generateIndex']);
      Route::get('/step1', [StuffingController::class, 'generateStep1']);
      Route::get('/step2', [StuffingController::class, 'generateStep2']);
      Route::post('/storestep1', [StuffingController::class, 'generateStoreStep1']);
      Route::post('/storestep2', [StuffingController::class, 'generateStoreStep2']);
    });
    Route::get('/pranota', [StuffingController::class, 'pranotaStuffing']);
    Route::get('/finalinvoice', [StuffingController::class, 'invoiceStuffing']);
    Route::get('/jobPage', [StuffingController::class, 'jobPageStuffing']);
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
    Route::get('/add', [SppsController::class, 'addMasterTarif']);
    Route::get('/edit', [SppsController::class, 'editMasterTarif']);
    Route::post('/store', [SppsController::class, 'storeCreateMasterTarif']);
    Route::post('/storeEdit', [SppsController::class, 'storeEditMasterTarif']);
  });
});

Route::prefix('delivery')->group(function () {
  Route::prefix('billing')->group(function () {
    Route::get('/', [BillingImportController::class, 'billingIndex']);
    Route::get('/pranota', [BillingImportController::class, 'pranotaIndex']);
    Route::get('/invoice', [BillingImportController::class, 'invoiceIndex']);
    Route::get('/job', [BillingImportController::class, 'jobIndex']);
    Route::prefix('extend')->group(function () {
      Route::get('/', [BillingImportController::class, 'billingExtendIndex']);
      // Route::get('/')
    });
  });
  Route::prefix('form')->group(function () {
    Route::get('/', [BillingImportController::class, 'formIndex']);
    Route::get('/create', [BillingImportController::class, 'createIndex']);
    Route::get('/review', [BillingImportController::class, 'reviewIndex']);
    Route::post('/storeForm', [BillingImportController::class, 'storeForm']);
    Route::post('/storeBilling', [BillingImportController::class, 'storeBilling']);
    Route::prefix('extend')->group(function () {
      Route::get('/', [BillingImportController::class, 'formExtendIndex']);
      Route::get('/create', [BillingImportController::class, 'createExtendIndex']);
      Route::get('/review', [BillingImportController::class, 'reviewExtendIndex']);
      Route::post('/storeForm', [BillingImportController::class, 'storeFormExtend']);
      Route::post('/storeBilling', [BillingImportController::class, 'storeBillingExtend']);
    });
  });
  Route::prefix('mastertarif')->group(function () {
    Route::get('/', [BillingImportController::class, 'masterTarifIndex']);
    Route::get('/detail', [BillingImportController::class, 'masterTarifDetail']);
    Route::get('/create', [BillingImportController::class, 'masterTarifCreate']);
    Route::post('/update', [BillingImportController::class, 'masterTarifUpdate']);
    Route::post('/store', [BillingImportController::class, 'masterTarifStore']);
  });
  Route::prefix('ajx')->group(function () {
    Route::post('/singleInvoice', [BillingImportController::class, 'singleInvoice']);
    Route::post('/verifyPayment ', [BillingImportController::class, 'verifyPayment']);
    Route::post('/verifyPiutang', [BillingImportController::class, 'verifyPiutang']);
    Route::post('/singleInvoice', [BillingImportController::class, 'singleInvoice']);
    Route::post('/allContainer', [BillingImportController::class, 'allContainer']);
  });
});

Route::prefix('receiving')->group(function () {
  Route::prefix('billing')->group(function () {
    Route::get('/', [BillingExportController::class, 'billingIndex']);
    Route::get('/pranota', [BillingExportController::class, 'pranotaIndex']);
    Route::get('/invoice', [BillingExportController::class, 'invoiceIndex']);
    Route::get('/job', [BillingExportController::class, 'jobIndex']);
  });
  Route::prefix('form')->group(function () {
    Route::get('/', [BillingExportController::class, 'formIndex']);
    Route::get('/create', [BillingExportController::class, 'createIndex']);
    Route::get('/review', [BillingExportController::class, 'reviewIndex']);
    Route::post('/storeForm', [BillingExportController::class, 'storeForm']);
    Route::post('/storeBilling', [BillingExportController::class, 'storeBilling']);
  });
  Route::prefix('mastertarif')->group(function () {
    Route::get('/', [BillingExportController::class, 'masterTarifIndex']);
    Route::get('/detail', [BillingExportController::class, 'masterTarifDetail']);
    Route::get('/create', [BillingExportController::class, 'masterTarifCreate']);
    Route::post('/update', [BillingExportController::class, 'masterTarifUpdate']);
    Route::post('/store', [BillingExportController::class, 'masterTarifStore']);
  });
  Route::prefix('ajx')->group(function () {
    Route::post('/singleInvoice', [BillingExportController::class, 'singleInvoice']);
    Route::post('/verifyPayment ', [BillingExportController::class, 'verifyPayment']);
    Route::post('/verifyPiutang', [BillingExportController::class, 'verifyPiutang']);
    Route::post('/groupcontainerbyvesid', [BillingExportController::class, 'groupContainerByVesId']);
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
Route::post('/planning/bayplan_pelindo', [BayplanImportController::class, 'pelindo']);
Route::get('/planning/edit_bayplanimport_{container_key}', [BayplanImportController::class, 'edit']);
Route::post('/getsize_edit', [BayplanImportController::class, 'size_edit']);
Route::post('/gettype_edit', [BayplanImportController::class, 'type_edit']);
Route::post('/get-iso-type', [BayplanImportController::class, 'get_iso_type']);
Route::post('/get-iso-size', [BayplanImportController::class, 'get_iso_size']);
Route::post('/get-ves-name', [BayplanImportController::class, 'get_ves_name']);
Route::post('/planning/update_bayplanimport', [BayplanImportController::class, 'update_bayplanimport']);
Route::delete('/planning/delete_item={container_key}', [BayplanImportController::class, 'delete_item']);

//kotak-kotak
Route::get('/disch/view-vessel/{vessel?}/{bay?}', [DischargeView::class, 'index']);
Route::post('/get-ves', [DischargeView::class, 'get_ves']);
Route::post('/get-bay', [DischargeView::class, 'get_bay']);
Route::get('/get-container', [DischargeView::class, 'get_container']);
Route::get('/cetak-perKapal', [DischargeView::class, 'cetakKapal'])->name('dischCetakKapal');
Route::get('/cetak-perBay', [DischargeView::class, 'cetakBay'])->name('dischCetakBay');

// Android
Route::get('/android-dashboard', [AndroidController::class, 'index']);
Route::get('/disch/confirm_disch', [DischargeController::class, 'index']);
Route::post('/search-container', [DischargeController::class, 'container']);
Route::post('/get-container-key', [DischargeController::class, 'get_key']);
Route::post('/confirm', [DischargeController::class, 'confirm']);
Route::get('/redirect', [AndroidController::class, 'redirectToRole'])->name('redirect');


// Android user Gate
Route::get('/android-gate', [AndroidController::class, 'gate_android']);

// Android user yard
Route::get('/android-yard', [AndroidController::class, 'yard_android']);

// Android user cc
Route::get('/android-cc', [AndroidController::class, 'cc_android']);


//tampilan android
Route::get('/disch/android', [DischargeController::class, 'android']);
Route::get('/yard/android', [PlacementController::class, 'android']);
Route::get('/stripping/android', [Stripping::class, 'android']);
Route::get('/delivery/android-in', [Gati::class, 'android']);
Route::get('/delivery/android-out', [Gato::class, 'android']);
Route::get('/stuffing/android', [Stuffing::class, 'android']);

Route::get('/yard/placement', [PlacementController::class, 'index']);
Route::post('/placement', [PlacementController::class, 'place']);
Route::post('/placementMT-Relokasi', [PlacementController::class, 'placeRelokasiMT']);
Route::post('/dapet-tipe', [PlacementController::class, 'get_tipe']);
Route::post('/container-tipe', [PlacementController::class, 'tipe_container']);
Route::post('/placement/changedToMty-{container_key}', [PlacementController::class, 'change']);
Route::post('/placement/changed-status', [PlacementController::class, 'place_mty']);
Route::post('/get-con-plc', [PlacementController::class, 'get_cont']);



Route::get('/yard/yard-view/android', [YardrotController::class, 'Android']);

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
route::post('yards/rowtier/get_rowtier_android', [YardrotController::class, 'get_rowtierAndroid'])->name('rowtier.get_rowtierAndroid');
Route::post('/getSlot', [YardrotController::class, 'get_slot'])->name('get.slot');



Route::get('/planning/bayplan_import', [BayplanImportController::class, 'index']);


Route::get('/stripping', [Stripping::class, 'index']);
Route::post('/get-stripping', [Stripping::class, 'get_stripping']);
Route::post('/stripping-place', [Stripping::class, 'stripping_place']);

Route::get('/delivery/gate-in', [Gati::class, 'index']);
Route::post('/gati-data_container', [Gati::class, 'data_container']);
Route::post('/gati-del', [Gati::class, 'gati_del']);
Route::post('/gati-del/update-truck', [Gati::class, 'update_truck']);
Route::get('/gati-del/edit-{container_key}', [Gati::class, 'edit_truck']);
Route::get('/delivery/gate-out', [Gato::class, 'index']);
Route::post('/gato-data_container', [Gato::class, 'data_container']);
Route::post('/gato-del', [Gato::class, 'gato_del']);


// history
Route::get('/search-cont-job', [HistoryController::class, 'searchContJob'])->name('search_cont_job');
Route::get('/search-cont-hist', [HistoryController::class, 'searchContHist'])->name('search_cont_hist');


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
Route::get('/master/block/addSlot-{YARD_BLOCK}', [MasterController::class, 'slot']);
Route::post('/master/block/createSlot', [MasterController::class, 'create_slot']);

//role EDI Baplie recievr
Route::get('/edi/receiveedi', [EdiController::class, 'receiveedi']);
Route::post('/edi/receiveeditxt_store', [EdiController::class, 'receiveeditxt_store'])->name('/edi/receiveeditxt_store');
Route::delete('/edi/delete_itembayplan={container_key}', [EdiController::class, 'delete_itembayplan']);
Route::get('/edi/edit_itembayplan', [EdiController::class, 'edit_itembayplan']);



route::resource('yard/rowtier', YardrotController::class);
route::post('yards/rowtier/get_rowtier', [YardrotController::class, 'get_rowtier'])->name('rowtier.get_rowtier');

//Routes Spatie
Route::middleware('role:admin')->get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// Profil
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
Route::post('/get-con-gatiDel', [Gati::class, 'get_cont']);

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



//Export

//gate
Route::get('/reciving/gate-in', [Gati::class, 'index_rec']);
Route::get('/reciving/gate-in-android', [Gati::class, 'android_rec']);
Route::get('/gati-data_container-rec', [Gati::class, 'get_data_reciving']);
Route::post('/gati-rec', [Gati::class, 'gati_rec']);
Route::post('/gati-iso-rec', [Gati::class, 'gati_iso_rec']);

Route::get('/reciving/gate-out', [Gato::class, 'index_rec']);
Route::get('/reciving/gate-out-android', [Gato::class, 'android_rec']);
Route::post('/gato-data_container', [Gato::class, 'data_container']);
Route::post('/gato-rec', [Gato::class, 'gato_rec']);


//Ship Plan
Route::get('/planning/ship_planning', [ShipPlanController::class, 'index']);
Route::get('/planning/plan-ves-{ves_id}', [ShipPlanController::class, 'plan']);
Route::get('/planning/grid', [ProfileKapal::class, 'grid']);
Route::get('/profile-kapal/get/bay-{ves?}-{bay?}', [ProfileKapal::class, 'bayProfile']);
Route::post('/profile-tier/update', [ProfileKapal::class, 'updateTier'])->name('updateProfileTier');
Route::get('/profile-kapal/cetak-{ves?}', [ProfileKapal::class, 'cetakProfile']);
Route::get('/planning/edit/ves-bay', [ProfileKapal::class, 'editProfile']);
Route::post('/planning/update/ves-bay', [ProfileKapal::class, 'updateProfile']);
Route::post('/planning/delete/ves-bay', [ProfileKapal::class, 'deleteProfile'])->name('deleteProfileBay');


// Route for displaying the main profile page
Route::get('/planning/profile-kapal', [ProfileKapal::class, 'index'])->name('profile-kapal.index');

// Route for handling the form submission and storing data
Route::post('profile-kapal/stores', [ProfileKapal::class, 'stores']);



// Stuffing
Route::get('/stuffing/gate-in', [Gati::class, 'index_stuf']);
Route::post('/stuf-gate-in', [Gati::class, 'gati_stuf']);
Route::post('/stuf-gate-in-full', [Gati::class, 'gati_stuf_full']);
Route::post('/gati-stuf-data', [Gati::class, 'gati_stuffing_data']);


Route::get('/stuffing/gate-out', [Gato::class, 'index_stuf_out']);
Route::post('/stuf-gate-out', [Gato::class, 'gato_stuf']);

// Android
Route::get('/stuffing/gate-in-stuffing-android', [Gati::class, 'stuf_android']);
Route::get('/stuffing/gate-out-stuffing-android', [Gato::class, 'stuff_android_out']);


Route::get('/stuffing', [Stuffing::class, 'index']);
Route::get('/stuffing-android', [Stuffing::class, 'android']);
Route::post('/get-stuffing', [Stuffing::class, 'get_stuffing']);
Route::post('/get-vessel-in-stuffing', [Stuffing::class, 'get_vessel']);
Route::post('/stuffing-place', [Stuffing::class, 'stuffing_place']);
Route::post('/stuffing-confirm-out', [Stuffing::class, 'confirm_out']);
Route::get('/stuffing/stuffingDalam/modal-{ro_no}', [Stuffing::class, 'choose_container']);
Route::get('/stuffing/stuffingLuar/modal-{ro_id_gati}', [Stuffing::class, 'choose_container_luar']);
Route::get('/stuffing/detailCont-{ro_no}', [Stuffing::class, 'detail_cont']);
Route::get('/stuffing/detailContLuar-{ro_id_gati}', [Stuffing::class, 'detail_cont_luar']);
Route::get('/stuffing/luar/placeCont-{ro_id_gati}', [Stuffing::class, 'place_cont_luar']);
Route::get('/stuffing/viewCont-{container_key}', [Stuffing::class, 'view_cont']);

Route::post('/stuffing-confirm-out-placement-luar', [Stuffing::class, 'update_place_cont_luar']);

// Load
Route::get('/load/confirm_load', [LoadController::class, 'index']);
Route::get('/load/confirm_load-android', [LoadController::class, 'android']);
Route::post('/search-container', [LoadController::class, 'container']);
Route::post('/get-container-key-load', [LoadController::class, 'get_key']);
Route::post('/confirm-load', [LoadController::class, 'confirm']);
Route::post('/get-con-load', [LoadController::class, 'get_cont']);
Route::get('/get-con/bay-edit/{id?}', [LoadController::class, 'bay_edit']);
Route::post('/load/updateBay', [LoadController::class, 'bay_update'])->name('updateBayLoad');

//BeaCukai
Route::get('/bea/req-dok', [BeaController::class, 'index']);
Route::post('/download-sppb', [SoapController::class, 'GetImpor_SPPB']);
Route::post('/download-npe', [SoapController::class, 'GetEkspor_NPE']);
Route::post('/download-Pabean', [SoapController::class, 'GetDokumenPabean_OnDemand']);
Route::post('/download-PKBE', [SoapController::class, 'GetEkspor_PKBE']);
Route::post('/download-Dok-Manual', [SoapController::class, 'GetDokumenManual_OnDemand']);
Route::get('/bc/detail-container-{CAR}', [BeaController::class, 'detail']);
Route::get('/container/export-{NO_DAFTAR}', [BeaController::class, 'container_export']);

// Update Edi
Route::get('/edi/detail-container-{ves_id}', [EdiController::class, 'get_cont']);



// Docs
// ro
Route::get('/docs/dokumen/ro', [DocsController::class, 'index_ro']);
Route::get('/docs/dokumen/ro-android', [DocsController::class, 'Androidindex_ro']);
Route::get('/docs/dokumen/ro/detail-{ro_no}', [DocsController::class, 'container_ro']);
Route::post('/docs/ro-pdf', [DocsController::class, 'pdf_ro'])->name('pdf.ro');
Route::post('/docs/update-ro', [DocsController::class, 'update_ro']);
Route::get('/show-document-ro/{file}', [DocsController::class, 'showDocument'])->name('show-document-ro');
Route::get('/docs/ro/editBy-{ro_id}', [DocsController::class, 'edit_ro']);
// Inven
Route::get('docs/inventory/items', [DocsController::class, 'index_items'])->name('inventory.items');


// Alat
Route::get('/master/alat', [EquipmentController::class, 'index']);
Route::post('/kategori-alat', [EquipmentController::class, 'addCategory']);
Route::post('/add-alat', [EquipmentController::class, 'addAlat']);
Route::get('/reports/equipment', [EquipmentController::class, 'report']);
Route::post('/get-alat', [EquipmentController::class, 'get_alat']);
Route::post('/get-data-alat', [EquipmentController::class, 'get_data_alat']);
Route::get('/laporan-alat', [EquipmentController::class, 'laporan_alat'])->name('laporan-alat');

// BC Gatter
Route::get('/bea-cukai-sevice', [BCGatterController::class, 'index']);
Route::get('/bea-cukai-sevice/container-hold', [BCGatterController::class, 'hold_index']);
Route::post('/release-cont', [BCGatterController::class, 'release_cont']);
Route::post('/hold-cont', [BCGatterController::class, 'holding_cont']);
Route::get('/bea-cukai-sevice/container-hold-p2', [BCGatterController::class, 'holdingp2_index']);
Route::get('/container-hold-p2-{container_key}', [BCGatterController::class, 'holdingp2_cont']);
Route::get('/bea-cukai-sevice/container-proses-release-p2', [BCGatterController::class, 'proses_releaseP2']);
Route::get('/show-document/{file}', [BCGatterController::class, 'showDocument'])->name('show-document');
Route::get('/bea-cukai-sevice/dok-hold-p2', [BCGatterController::class, 'document']);
Route::post('/release-cont-p2', [BCGatterController::class, 'release_p2']);

// Gate Relokasi
Route::get('/delivery/balik-relokasi', [GateRelokasiController::class, 'index']);
Route::post('/gate-relokasi', [GateRelokasiController::class, 'permit']);
Route::get('/delivery/balik-relokasi-android', [GateRelokasiController::class, 'android']);
Route::post('/relokasi-data_container', [GateRelokasiController::class, 'data_container']);

// Trucking
Route::get('/yard/trucking', [TruckingController::class, 'index']);
Route::get('/yard/trucking-android', [TruckingController::class, 'android']);
Route::post('/trucking-get-truck', [TruckingController::class, 'get_truck']);
Route::post('/trucking', [TruckingController::class, 'trucking']);


// detail-cont Yard Row
Route::get('/yard/viewCont-{container_key}', [YardrotController::class, 'view_cont']);


// report Export
Route::get('/reports/export', [ReportController::class, 'index_xp']);
Route::get('/reports/detailCont-{ves_id}', [ReportController::class, 'detail_cont']);
Route::post('/get-data-kapal', [ReportController::class, 'get_data_kapal']);
Route::get('/laporan-kapal', [ReportController::class, 'laporan_kapal'])->name('laporan-kapal');



// New Invoice


// Import

// Master Tarif
Route::get('/billing/import/master-tarif', [MasterTarifController::class, 'import'])->name('mtImport');
Route::post('/billing/import/master-tarif/store-OS', [MasterTarifController::class, 'orderService'])->name('orderService');
Route::get('/billing/import/master-tarif-{os?}', [MasterTarifController::class, 'tarif']);
Route::post('/billing/import/master-tarif/store-MT', [MasterTarifController::class, 'storeMT'])->name('storeMT');
Route::get('/billing/import/master-tarif/edit-{id?}', [MasterTarifController::class, 'EditImport']);
Route::post('/billing/import/master-tarif/update-MT', [MasterTarifController::class, 'updateMT'])->name('updateMT');
// Customer ikut di Master Tarif
Route::get('/billing/customer', [MasterTarifController::class, 'customer'])->name('Customer');
Route::get('/billing/customer/add', [MasterTarifController::class, 'addCust'])->name('addCust');
Route::post('/billing/customer/store', [MasterTarifController::class, 'storeCust'])->name('storeCust');
Route::get('/billing/customer/edit-{id?}', [MasterTarifController::class, 'editCust']);
Route::post('/billing/customer/update', [MasterTarifController::class, 'updateCust'])->name('updateCust');

// DO ikut di Master Tarif

Route::get('/billing/dock-DO', [MasterTarifController::class, 'doMain'])->name('doMain');
Route::post('/billing/dock-DO/upload', [MasterTarifController::class, 'doUpload'])->name('doUpload');
Route::post('/billing/dock-DO/delete', [MasterTarifController::class, 'deleteDo'])->name('deleteDo');
Route::get('/edit/doOnline/{id?}', [MasterTarifController::class, 'doEdit'])->name('doEdit');
Route::post('/update/doOnline/{id?}', [MasterTarifController::class, 'doUpdate'])->name('doUpdate');

// invoiceImport
Route::get('/billing/import/delivey-system', [ImportController::class, 'billingMain'])->name('billinImportgMain');
Route::get('/billing/import/delivery-dashboard', [ImportController::class, 'deliveryMenu'])->name('deliveryMenu');
Route::get('/billing/import/delivery-editForm/{id?}', [ImportController::class, 'deliveryEdit'])->name('deliveryEdit');
Route::delete('/billing/import/delivery-deleteForm/{id?}', [ImportController::class, 'deliveryDelete'])->name('deliveryDelete');
Route::delete('/billing/import/delivery-deleteInvoice/{id?}', [ImportController::class, 'deliveryInvoiceDelete'])->name('deliveryInvoiceDelete');
Route::post('/billing/import/delivery-updateFormImport', [ImportController::class, 'updateFormImport'])->name('updateFormImport');
Route::get('/billing/import/delivery-form', [ImportController::class, 'deliveryForm'])->name('deliveryForm');
Route::get('/get-customer-data', [ImportController::class, 'getCust'])->name('getCust');
Route::get('/get-doOnline-data', [ImportController::class, 'getDOdata'])->name('getDOdata');
Route::get('/get-dokumenImport-data', [ImportController::class, 'getDokImport'])->name('getDokImport');

Route::post('/billing/import/delivey-system/beforeCreate', [ImportController::class, 'beforeCreate'])->name('beforeCreate');
Route::get('/billing/import/delivey-system/formInvoice/{id?}', [ImportController::class, 'formInvoice'])->name('formInvoice');
Route::post('/billing/import/delivey-system/store-import-invoice', [ImportController::class, 'invoiceImport'])->name('invoiceImport');

// Pranota
Route::get('/pranota/import-DSK{id?}', [ImportController::class, 'PranotaImportDSK'])->name('PranotaImportDSK');
Route::get('/pranota/import-DS{id?}', [ImportController::class, 'PranotaImportDS'])->name('PranotaImportDS');
// Invoice
Route::get('/invoice/import-DSK{id?}', [ImportController::class, 'InvoiceImportDSK'])->name('InvoiceImportDSK');
Route::get('/invoice/import-DS{id?}', [ImportController::class, 'InvoiceImportDS'])->name('InvoiceImportDS');
// JOB
Route::get('/invoice/job/import-{id?}', [ImportController::class, 'JobInvoice'])->name('JobInvoice');
Route::get('/import/pay-button{id?}', [ImportController::class, 'payImport'])->name('payImport');
Route::post('/invoice/import-payFull', [ImportController::class, 'payFullImport'])->name('payFullImport');
Route::post('/invoice/import-piutang', [ImportController::class, 'piutangImport'])->name('piutangImport');
Route::post('/invoice/import-cancel', [ImportController::class, 'deliveryInvoiceCancel'])->name('deliveryInvoiceCancel');



// Export
Route::get('/billing/export/master-tarif', [MasterTarifController::class, 'export'])->name('mtExport');
Route::get('/billing/export/master-tarif-{os?}', [MasterTarifController::class, 'tarifExport']);
Route::post('/billing/export/master-tarif/store-MT', [MasterTarifController::class, 'storeMTexport'])->name('storeMTexport');
Route::get('/billing/export/master-tarif/edit-{id?}', [MasterTarifController::class, 'EditExport']);
Route::post('/billing/import/master-tarif/update-MT', [MasterTarifController::class, 'updateMTexport'])->name('updateMTexport');


// coparn
Route::get('/billing/coparn', [CoparnController::class, 'index'])->name('coparnMain');
Route::get('/billing/coparn/upload-file', [CoparnController::class, 'uploadView'])->name('uploadView');
Route::get('/billing/coparn/upload-single', [CoparnController::class, 'uploadSingle'])->name('uploadSingle');
Route::get('/billing/coparn/get-vessel', [CoparnController::class, 'getVesselData'])->name('getVesselData');
Route::post('/billing/coparn/postFile', [CoparnController::class, 'storeData'])->name('storeData');
Route::post('/billing/coparn/postSingleCoparn', [CoparnController::class, 'storeDataSingle'])->name('storeDataSingle');
Route::get('/billing/coparn/edit-{id?}', [CoparnController::class, 'editCoparn'])->name('editCoparn');
Route::post('/billing/coparn/update', [CoparnController::class, 'updateCoparn'])->name('updateCoparn');


// invoice Export
Route::get('/billing/export/delivey-system', [InvoiceExportController::class, 'billingMain'])->name('billingExportMain');
Route::get('/billing/export/delivery-dashboard', [InvoiceExportController::class, 'deliveryMenuExport'])->name('deliveryMenuExport');
Route::get('/billing/export/delivery-form', [InvoiceExportController::class, 'deliveryFormExport'])->name('deliveryFormExport');
Route::get('/get-order-data', [InvoiceExportController::class, 'getOrder'])->name('getOrder');
Route::get('/get-BookingNo-data', [InvoiceExportController::class, 'getDOdataExport'])->name('getDOdataExport');
Route::get('/get-RoNumber-data', [InvoiceExportController::class, 'getROdataExport'])->name('getROdataExport');
Route::get('/billing/export/delivey-system/formInvoice/{id?}', [InvoiceExportController::class, 'formInvoice'])->name('formInvoiceExport');
Route::get('/billing/export/reciving-editForm/{id?}', [InvoiceExportController::class, 'deliveryEdit'])->name('deliveryEditExport');
Route::post('/billing/export/delivey-system/beforeCreate', [InvoiceExportController::class, 'beforeCreate'])->name('beforeCreateExport');
Route::post('/billing/export/delivey-system/store-export-invoice', [InvoiceExportController::class, 'invoiceExport'])->name('invoiceExport');
Route::post('/billing/export/reciving-updateFormExport', [InvoiceExportController::class, 'updateFormExport'])->name('updateFormExport');
Route::delete('/billing/export/reciving-deleteInvoice/{id?}', [InvoiceExportController::class, 'recivingInvoiceDelete'])->name('recivingInvoiceDelete');

Route::get('/pranota/export-OSK{id?}', [InvoiceExportController::class, 'PranotaExportOSK'])->name('PranotaExportOSK');
Route::get('/pranota/export-OS{id?}', [InvoiceExportController::class, 'PranotaExportOS'])->name('PranotaExportOS');


Route::get('/export/pay-button{id?}', [InvoiceExportController::class, 'payExport'])->name('payExport');
Route::post('/invoice/export-payFull', [InvoiceExportController::class, 'payFullExport'])->name('payFullExport');
Route::post('/invoice/export-piutang', [InvoiceExportController::class, 'piutangExport'])->name('piutangExport');
Route::post('/invoice/export-cancel', [InvoiceExportController::class, 'recivingInvoiceCancel'])->name('recivingInvoiceCancel');


Route::get('/invoice/export-OSK{id?}', [InvoiceExportController::class, 'InvoiceExportOSK'])->name('InvoiceExportOSK');
Route::get('/invoice/export-OS{id?}', [InvoiceExportController::class, 'InvoiceExportOS'])->name('InvoiceExportOS');
// JOB
Route::get('/invoice/job/export-{id?}', [InvoiceExportController::class, 'JobInvoice'])->name('JobInvoice');


// Bayplan Load
Route::get('/load/container_loading', [BayplanDesignController::class, 'index'])->name('Baypkan-Load');
Route::get('/load/container_loading-{ves?}', [BayplanDesignController::class, 'cetakLoad']);


// master operator
Route::get('/master/operator', [OperatorController::class, 'index'])->name('operator');
Route::post('/master/operator-post', [OperatorController::class, 'store'])->name('operator-post');
Route::delete('/master/operator-delete={id}', [OperatorController::class, 'delete']);
Route::get('/master/operator-edit-{id}', [OperatorController::class, 'edit']);
Route::post('/master/operator-patch', [OperatorController::class, 'update'])->name('operator-patch');

Route::get('/reports/operator', [OperatorController::class, 'report'])->name('op-rep');
Route::post('/get-op', [OperatorController::class, 'get_op']);
Route::post('/get-data-operator', [OperatorController::class, 'get_data_operator']);
Route::get('/laporan-operator', [OperatorController::class, 'ReportPrint'])->name('laporan-operator');


Route::get('/batal-muat', [BatalMuatController::class, 'index'])->name('index-batal-muat');
Route::get('/batal-muat/addCont-form', [BatalMuatController::class, 'addCont'])->name('addCont-batal-muat');
Route::post('/batal-muat/store', [BatalMuatController::class, 'store'])->name('post-batal-muat');
Route::get('/batal-muat-action', [BatalMuatController::class, 'action'])->name('action-batal-muat');
Route::post('/batal-muat/update', [BatalMuatController::class, 'update'])->name('update-batal-muat');


Route::get('/reports/batal-muat', [BatalMuatController::class, 'indexReport'])->name('indexReport-batal-muat');
Route::post('/get-data-batal-muat', [BatalMuatController::class, 'get_data_batalMuat']);
Route::get('/laporan-batalMuat', [BatalMuatController::class, 'ReportPrint'])->name('laporan-batalMuat');

// Report Invoice
Route::get('/invoice/report-import', [ImportController::class, 'ReportExcel'])->name('report-invoice-import');
Route::get('/invoice/report-importAll', [ImportController::class, 'ReportImpiortAll'])->name('report-invoice-import-All');
Route::get('/invoice/report-export', [InvoiceExportController::class, 'ReportExcel'])->name('report-invoice-export');

Route::get('/invoice/report-extend', [InvoiceExtend::class, 'ReportExcel'])->name('report-invoice-extend');
Route::get('/invoice/report-extend-all', [InvoiceExtend::class, 'ReportExcelAll'])->name('report-invoice-extend-All');
Route::get('/invoice/report-export-all', [InvoiceExportController::class, 'ReportExcelAll'])->name('report-invoice-export-All');


// Extend
Route::get('/billing/import/extendIndex', [InvoiceExtend::class, 'index'])->name('index-extend');
Route::get('/billing/import/extendIndexForm', [InvoiceExtend::class, 'ListForm'])->name('listForm-extend');
Route::get('/billing/import/extendForm', [InvoiceExtend::class, 'form'])->name('extendForm');
Route::get('/billing/import/extend-editForm/{id?}', [InvoiceExtend::class, 'EditForm']);
Route::post('/billing/import/extend-updateFormImport', [InvoiceExtend::class, 'updateFormImport'])->name('updateFormImportExtend');
Route::delete('/billing/import/extend-deleteInvoice/{id?}', [InvoiceExtend::class, 'extendInvoiceDelete'])->name('extendInvoiceDelete');

// 
Route::post('/billing/import/extendPost', [InvoiceExtend::class, 'postForm'])->name('extendPostForm');
// 
Route::get('/billing/import/extendPreinvoice/{id?}', [InvoiceExtend::class, 'preinvoice'])->name('extendPreinvoice');
Route::post('/billing/import/extendCreate', [InvoiceExtend::class, 'post'])->name('extendCreate');
Route::get('/extend/pay-button{id?}', [InvoiceExtend::class, 'payExtend'])->name('payExtend');
Route::post('/invoice/extend-payFullExtend', [InvoiceExtend::class, 'payFull'])->name('payFullExtend');
Route::post('/invoice/extend-piutangExtend', [InvoiceExtend::class, 'piutang'])->name('piutangFullExtend');
Route::post('/invoice/extend-cancelExtend', [InvoiceExtend::class, 'extendInvoiceCancel'])->name('extendInvoiceCancel');
Route::get('/pranota/extend-{id?}', [InvoiceExtend::class, 'PranotaExtend']);
Route::get('/invoice/extend-{id?}', [InvoiceExtend::class, 'InvoiceExtend']);
Route::get('/invoice/job/extend-{id?}', [InvoiceExtend::class, 'JobExtend']);
Route::get('/get-cont-extend-data', [InvoiceExtend::class, 'contData'])->name('getContToExtend');

// Steva Dooring
Route::get('/billing/stevadooring/billingMain', [StevadooringController::class, 'main'])->name('index-stevadooring');
Route::get('/billing/stevadooring/masterTarif', [StevadooringController::class, 'TarifIndex'])->name('index-stevadooring-Tarif');
Route::post('/billing/stevadooring/masterTarif-Update', [StevadooringController::class, 'TarifUpdate'])->name('update-stevadooring-Tarif');
  // RBM
Route::get('/billing/stevadooring/RBM', [StevadooringController::class, 'RBM_Index'])->name('index-stevadooring-RBM');
Route::get('/billing/stevadooring/RBM-create', [StevadooringController::class, 'RBM_Create'])->name('index-stevadooring-RBM_Create');
Route::post('/billing/stevadooring/RBM-post', [StevadooringController::class, 'RBM_Post'])->name('index-stevadooring-RBM_Post');
Route::get('/billing/stevadooring/RBM-detali/{id}', [StevadooringController::class, 'RBM_Detail'])->name('index-stevadooring-RBM_Detail');
Route::post('/billing/stevadooring/RBM-update', [StevadooringController::class, 'RBM_Update'])->name('index-stevadooring-RBM_Update');

// Form
Route::get('/billing/stevadooring/list-form', [StevadooringController::class, 'listForm'])->name('index-stevadooring-listForm');
Route::get('/billing/stevadooring/form', [StevadooringController::class, 'Form'])->name('stevadooringForm');
Route::post('/billing/stevadooring/FormPost', [StevadooringController::class, 'FormPost'])->name('stevadooringPost');
Route::get('/billing/stevadooring/PreInvoice/{id}', [StevadooringController::class, 'showInvoice'])->name('stevadooringPreInvoice');
Route::get('/billing/stevadooring/edit-invoice/{id}', [StevadooringController::class, 'editInvoice'])->name('stevadooringEditInvoice');
Route::post('/billing/stevadooring/FormUpdate', [StevadooringController::class, 'FormUpdate'])->name('stevadooringUpdate');
Route::post('/billing/stevadooring/DetailPost', [StevadooringController::class, 'stevadooringDetailPost'])->name('stevadooringDetailPost');

// Pranota
Route::get('/pranota/stevadooring-{id}', [StevadooringController::class, 'Pranota'])->name('index-stevadooring-Pranota');
// Pay
Route::get('/stevadooring/pay-button{id}', [StevadooringController::class, 'Pay'])->name('index-stevadooring-Pay');
Route::post('/invoice/stevadooring-payFull', [StevadooringController::class, 'payFullStevadooring'])->name('index-stevadooring-PayFull');
Route::post('/invoice/stevadooring-piutang', [StevadooringController::class, 'piutangStevadooring'])->name('index-stevadooring-Piutang');

// invoice
Route::get('/invoice/stevadooring-{id}', [StevadooringController::class, 'Invoice'])->name('index-stevadooring-Invoice');
Route::get('/invoice/report-stevadooring', [StevadooringController::class, 'ReportExcel'])->name('report-invoice-stevadooring');


// Shifting
Route::get('/planning/shifting/main', [ShiftingController::class, 'index'])->name('index-shifting');
Route::get('/planning/shifting/android', [ShiftingController::class, 'android'])->name('android-shifting');
Route::post('/get-con-shift', [ShiftingController::class, 'get_cont']);
Route::post('/post-shifting', [ShiftingController::class, 'shifting']);

// Zahir
Route::get('/invoice/zahir-import', [ZahirController::class, 'ZahirImport'])->name('zahir-invoice-import');
Route::get('/invoice/zahir-export', [ZahirController::class, 'ZahirExport'])->name('zahir-invoice-export');
Route::get('/invoice/zahir-export', [ZahirController::class, 'ZahirPlugging'])->name('zahir-invoice-plugging');
Route::get('/invoice/zahir-extend', [ZahirController::class, 'ZahirExtend'])->name('zahir-invoice-extend');
Route::get('/invoice/zahir-steva', [ZahirController::class, 'ZahirSteva'])->name('zahir-invoice-steva');

// Master Invoice
Route::get('/invoice/master/item', [MasterInvoiceController::class, 'indexMItem'])->name('invoice-master-item');
Route::post('/invoice/master/itemCreate', [MasterInvoiceController::class, 'postItem'])->name('invoice-master-itemCreate');
Route::post('/invoice/master/itemDelete', [MasterInvoiceController::class, 'deleteItem'])->name('invoice-master-itemDelete');
Route::get('/invoice/master/item-{id}', [MasterInvoiceController::class, 'editMItem'])->name('invoice-master-itemEdit');
Route::post('/invoice/master/itemUpdate', [MasterInvoiceController::class, 'updateItem'])->name('invoice-master-itemUpdate');

// os
Route::get('/invoice/master/os', [MasterInvoiceController::class, 'indexOS'])->name('invoice-master-os');
Route::post('/invoice/master/osDelete', [MasterInvoiceController::class, 'deleteOS'])->name('invoice-master-osDelete');
Route::get('/invoice/master/osDetail{id?}', [MasterInvoiceController::class, 'detailOS'])->name('invoice-master-osDetail');

Route::post('/invoice/master/osUpdate', [MasterInvoiceController::class, 'updateOS'])->name('invoice-master-osUpdate');
Route::post('/invoice/master/osDetailDSK', [MasterInvoiceController::class, 'osDetailDSK'])->name('invoice-master-osDetailDSK');
Route::post('/invoice/master/osDetailDS', [MasterInvoiceController::class, 'osDetailDS'])->name('invoice-master-osDetailDS');
Route::delete('/invoice/master/osDetailBuang={id}', [MasterInvoiceController::class, 'buangDetail'])->name('invoice-master-osDetailBuang');

Route::get('/invoice/master/tarif-import', [MasterInvoiceController::class, 'indexMTimport'])->name('invoice-master-tarifImport');
Route::get('/invoice/master/tarif-export', [MasterInvoiceController::class, 'indexMTexport'])->name('invoice-master-tarifExport');
Route::post('/invoice/master/tairfFirstStep', [MasterInvoiceController::class, 'tarifFirst'])->name('invoice-master-tarifFirst');
Route::post('/invoice/master/tairfFirstStepExport', [MasterInvoiceController::class, 'tarifFirstExport'])->name('invoice-master-tarifFirstExport');
Route::post('/invoice/master/tairfDelete', [MasterInvoiceController::class, 'tarifDelete'])->name('invoice-master-tarifDelete');
Route::get('/invoice/master/tarif-modalMT', [MasterInvoiceController::class, 'modalMT'])->name('invoice-master-modalMT');

Route::get('/invoice/master/tarif-import-detail-{id}', [MasterInvoiceController::class, 'indexMTimportDetail'])->name('invoice-master-tarifImport-detail');
Route::get('/invoice/master/tarif-export-detail-{id}', [MasterInvoiceController::class, 'indexMTexportDetail'])->name('invoice-master-tarifExport-detail');
Route::post('/invoice/master/tairfDetail', [MasterInvoiceController::class, 'tarifDetail'])->name('invoice-master-tarifDetail');

// newReport
Route::get('/container/indexReport', [ContainerController::class, 'indexHistory'])->name('container-report-main');
Route::get('/container/sortByVes/{id?}', [ContainerController::class, 'indexSortVes'])->name('container-report-vessel');
Route::get('/container/history/{id?}', [ContainerController::class, 'history'])->name('container-report-history');
Route::get('/container/edit-container/{id?}', [ContainerController::class, 'edit'])->name('container-edit');
Route::post('/container/update-container', [ContainerController::class, 'update'])->name('container-update');
Route::get('/container/sortByVes/export/{id?}', [ContainerController::class, 'Export']);
Route::get('/container/indexReportAll', [ContainerController::class, 'indexContainerAll'])->name('container-report-all');
Route::get('/container/sortByAll/export', [ContainerController::class, 'ExportAll'])->name('export-container-all');
  //Android 
  Route::get('/container/indexReportAndroid', [ContainerController::class, 'indexHistoryAndroid'])->name('container-report-main-android');
  Route::get('/container/indexReportAllAndroid', [ContainerController::class, 'indexContainerAllAndroid'])->name('container-report-all-android');
  Route::get('/container/sortByVes-android/{id?}', [ContainerController::class, 'indexSortVesAndroid'])->name('container-report-vessel-android');

// Realisasi Muat
Route::get('/planning/indexVessel', [RealisasiMuatController::class, 'index'])->name('realisasi-muat-main');
Route::get('/planning/detail/realisasiMuat/{id?}', [RealisasiMuatController::class, 'listMuat'])->name('realisasi-muat-listMuat');
Route::get('/planning/detail/realisasiMuat/excel/{id?}', [RealisasiMuatController::class, 'Excel'])->name('realisasi-muat-Excel');

Route::get('/gate/checking-', [GateCheckingController::class, 'Main']);
Route::get('/gate/checking-perpanjangan-', [GateCheckingController::class, 'MainPerpanjangan']);

// Gate MT
Route::get('/gate/gate-in/MT', [GateMTcontroller::class, 'IndexIn']);
Route::post('/gate/gate-in/MT-confirm', [GateMTcontroller::class, 'ConfirmIn'])->name('confirmInGateMT');
Route::get('/gate/gate-out/MT', [GateMTcontroller::class, 'IndexOut']);
Route::post('/gate/gate-out/MT-confirm', [GateMTcontroller::class, 'ConfirmOut'])->name('confirmOutGateMT');



// plugging
// main
Route::get('/plugging', [PluggingController::class, 'billingMain'])->name('plugging-main');
  // Tarif
Route::get('/plugging/master-tarif', [MasterInvoiceController::class, 'indexMTplugging'])->name('plugging-tarif-index');
Route::get('/plugging/master-tarifDetail-{id?}', [MasterInvoiceController::class, 'indexMTpluggingDetail'])->name('plugging-tarif-detail');
Route::post('/plugging/master-tarif-create-first', [MasterInvoiceController::class, 'tarifFirstPlugging'])->name('plugging-tarif-create-first');


// Form
  // list
  Route::get('/plugging-form', [PluggingController::class, 'deliveryMenu'])->name('plugging-form-index');
  Route::get('/plugging-create-index', [PluggingController::class, 'FormIndex'])->name('plugging-create-index');
  Route::get('/plugging-create-container', [PluggingController::class, 'getContainer'])->name('container-plugging');
  Route::post('/plugging-create-store', [PluggingController::class, 'FormStore'])->name('plugging-create-post');
  Route::get('/plugging-preinvoice/{id?}', [PluggingController::class, 'preinvoice'])->name('plugging-preinvoice');
  Route::get('/plugging-create-edit-{id?}', [PluggingController::class, 'FormEdit'])->name('plugging-edit-index');
  Route::post('/plugging-create-update', [PluggingController::class, 'FormUpdate'])->name('plugging-create-update');
  
  //Submit Invoice
  Route::post('/plugging-invoice-post', [PluggingController::class, 'SubmitInvoice'])->name('plugging-invoice-post');

  // Invoice
  Route::get('/plugging-pranota-{id?}', [PluggingController::class, 'Pranota'])->name('plugging-pranota');
  Route::get('/plugging-report', [PluggingController::class, 'ReportExcel'])->name('plugging-report');
  Route::get('/plugging-report-os', [PluggingController::class, 'ReportExcelOnce'])->name('plugging-report-os');
