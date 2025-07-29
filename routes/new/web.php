<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Lapangan\Vessel\PlanningController;
use App\Http\Controllers\Lapangan\GetDataController;


Route::prefix('/planning')->name('planning.')->controller(PlanningController::class)->group(function() {

    Route::middleware('permission:Vessel Schedule')->group(function() {
        Route::prefix('/voyage')->name('voyage.')->group(function() {
            Route::get('/index', 'voyageIndex')->name('index');
            Route::get('/data', 'voyageData')->name('data');
            Route::post('/post', 'voyagePost')->name('post');
        });
    });
});

Route::prefix('/getData')->name('getData.')->controller(GetDataController::class)->group(function() {
    Route::post('/vessel', 'getVessel')->name('vessel');
    Route::post('/berth', 'getBerth')->name('berth');
    Route::post('/voyage', 'getVoyage')->name('voyage');
});