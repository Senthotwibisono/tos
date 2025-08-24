<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Lapangan\Vessel\PlanningController;
use App\Http\Controllers\Lapangan\Gate\GateImportController;
use App\Http\Controllers\Lapangan\GetDataController;


Route::prefix('/planning')->name('planning.')->controller(PlanningController::class)->group(function() {

    Route::middleware('permission:Vessel Schedule')->group(function() {
        Route::prefix('/voyage')->name('voyage.')->group(function() {
            Route::get('/index', 'voyageIndex')->name('index');
            Route::get('/data', 'voyageData')->name('data');
            Route::post('/post', 'voyagePost')->name('post');
        });
    });

    Route::middleware('permission:Baplei')->group(function() {
        Route::prefix('/baplei')->name('baplei.')->group(function() {
            Route::get('/index', 'bapleiIndex')->name('index');
            Route::get('/data', 'bapleiData')->name('data');
            Route::post('/post', 'bapleiPost')->name('post');
        });
    });
});

Route::prefix('/gate')->name('gate.')->group(function () {
    Route::controller(GateImportController::class)->group(function() {
        Route::prefix('/import')->name('import.')->group(function() {
            Route::get('/index/in', 'indexIn')->name('indexIn');
            Route::get('/data/in', 'dataIn')->name('dataIn');
            Route::post('/post/in', 'postIn')->name('postIn');
    
            Route::post('/cancel/in', 'cancelGate')->name('cancelIn');
    
            Route::get('/index/out', 'indexOut')->name('indexOut');
            Route::get('/data/out', 'dataOut')->name('dataOut');
            Route::post('/post/out', 'postOut')->name('postOut');
        });
        
        Route::prefix('/balik-mt')->name('balikMt.')->group(function(){
            Route::get('/index', 'indexBalikMt')->name('index');
            Route::get('/data', 'dataBalikMt')->name('data');
            Route::post('/post', 'postBalikMt')->name('post');
            Route::post('/cancel', 'cancelBalikMt')->name('cancel');
        });
        
        Route::prefix('/relokasi-pelindo')->name('pelindoImport.')->group(function(){
            Route::get('/index', 'indexPelindoImport')->name('index');
            Route::get('/data', 'dataPelindoImport')->name('data');
            Route::post('/cancel', 'cancelPelindoImport')->name('cancel');
        });
        
        Route::prefix('/ambil-mty')->name('ambilMt.')->group(function() {
            Route::get('index', 'indexAmbilMt')->name('index');
            Route::get('data', 'dataAmbilMt')->name('data');
            Route::post('post', 'postAmbilMt')->name('post');
            Route::post('/cancel', 'cancelAmbilMt')->name('cancel');
            
        });
    });
});

Route::prefix('/getData')->name('getData.')->controller(GetDataController::class)->group(function() {
    Route::post('/vessel', 'getVessel')->name('vessel');
    Route::post('/berth', 'getBerth')->name('berth');
    Route::post('/voyage', 'getVoyage')->name('voyage');
    Route::post('/container', 'getContainer')->name('container');
    Route::get('/containerSelect', 'getContainerSelect')->name('containerSelect');
    Route::post('/iso', 'getIso')->name('iso');
    Route::get('/jobImport', 'getJobImport')->name('jobImport');
    Route::post('/jobImportDetil', 'getJobImportDetil')->name('jobImportDetil');
    Route::post('/jobImportDetilOut', 'getJobImportDetilOut')->name('jobImportDetilOut');
});