<?php


use App\Http\Controllers\Api\BmkgProxyController;
use App\Http\Controllers\Api\IndicatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
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

Route::get('/modul/', [DashboardController::class, 'index'])->name('home');

Route::get('/modul/lokasi', [LocationController::class, 'index'])->name('lokasi');
Route::get('/modul/perangkat', [LocationController::class, 'index'])->name('perangkat');
Route::get('/modul/lokasi/{id}', [LocationController::class, 'show'])->name('location.detail');

Route::get('/modul/api/bmkg/forecast', BmkgProxyController::class)->name('api.bmkg.forecast');

// API Routes for Indicators
Route::prefix('/api/indicators')->group(function () {
    Route::get('/aquaviska/{area?}', [IndicatorController::class, 'aquaviska'])->name('api.indicators.aquaviska');
    Route::get('/location/{locationId}', [IndicatorController::class, 'location'])->name('api.indicators.location');
    Route::get('/iot-climate/{locationId?}', [IndicatorController::class, 'iotClimate'])->name('api.indicators.iotClimate');
});
