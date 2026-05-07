<?php


use App\Http\Controllers\Api\BmkgProxyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Factory;


Route::get('/test-firebase', function () {
    $factory = (new Factory)
        ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
        ->withDatabaseUri('https://equapp-5718f-default-rtdb.firebaseio.com');

    $database = $factory->createDatabase();

    $data = $database->getReference('KualitasAir')->getValue();

    return response()->json($data);
});

Route::get('/', function () {
    return view('index');
})->name('welcome');


// Auth Routes
Route::get('/login', [AuthController::class, 'index'])->name('login.view');

// Admin Routes
Route::get('/admin', [AdminController::class, 'index'])->name('admin.home');


// Modul Routes 
Route::get('/modul/', [DashboardController::class, 'index'])->name('home.modul');
Route::get('/modul/lokasi', [ModulController::class, 'index'])->name('lokasi.modul');
Route::get('/modul/perangkat', [ModulController::class, 'index'])->name('perangkat.modul');
Route::get('/modul/lokasi/{id}', [ModulController::class, 'show'])->name('location.detail.modul');
Route::get('/modul/api/bmkg/forecast', BmkgProxyController::class)->name('api.bmkg.forecast');
