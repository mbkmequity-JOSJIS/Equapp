<?php

use App\Http\Controllers\Api\BmkgProxyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('welcome');

Route::get('/modul/', [DashboardController::class, 'index'])->name('home');

Route::get('/modul/lokasi', [LocationController::class, 'index'])->name('lokasi');
Route::get('/modul/perangkat', [LocationController::class, 'index'])->name('perangkat');
Route::get('/modul/lokasi/{id}', [LocationController::class, 'show'])->name('location.detail');

Route::get('/modul/api/bmkg/forecast', BmkgProxyController::class)->name('api.bmkg.forecast');
