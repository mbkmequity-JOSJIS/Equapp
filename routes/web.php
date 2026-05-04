<?php

use App\Http\Controllers\Api\BmkgProxyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::get('/lokasi', [LocationController::class, 'index'])->name('lokasi');
Route::get('/perangkat', [LocationController::class, 'index'])->name('perangkat');
Route::get('/lokasi/{id}', [LocationController::class, 'show'])->name('location.detail');

Route::get('/api/bmkg/forecast', BmkgProxyController::class)->name('api.bmkg.forecast');
