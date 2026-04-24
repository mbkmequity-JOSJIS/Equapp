<?php

use App\Http\Controllers\Api\BmkgProxyController;
use App\Http\Controllers\DeveloperProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

// Web Landing Page
Route::get('/', function () {
    return view('index');
})->name('landing');


// Rute Modul IOT
Route::get('/modul', [HomeController::class, 'index'])->name('home');

Route::get('/modul/lokasi-dan-perangkat', [LocationController::class, 'index'])->name('locations.index');
Route::get('/modul/lokasi/{id}', [LocationController::class, 'show'])->name('location.detail');

Route::get('/modul/profil-pengembang', DeveloperProfileController::class)->name('developer.profile');

Route::get('/modul/api/bmkg/forecast', BmkgProxyController::class)->name('api.bmkg.forecast');
