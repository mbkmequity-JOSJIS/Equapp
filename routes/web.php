<?php

use App\Http\Controllers\Api\BmkgProxyController;
use App\Http\Controllers\DeveloperProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/lokasi-dan-perangkat', [LocationController::class, 'index'])->name('locations.index');
Route::get('/lokasi/{id}', [LocationController::class, 'show'])->name('location.detail');

Route::get('/profil-pengembang', DeveloperProfileController::class)->name('developer.profile');

Route::get('/api/bmkg/forecast', BmkgProxyController::class)->name('api.bmkg.forecast');
