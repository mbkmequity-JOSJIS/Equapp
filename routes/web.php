<?php


use App\Http\Controllers\Api\BmkgProxyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DeviceController;
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



Route::middleware(['guest'])->group(function () {
    Route::get('/modul/devices', [DeviceController::class, 'index'])->name('devices');
    Route::get('/modul/devices/{device}', [DeviceController::class, 'devices'])->name('device.list');
    Route::get('/modul/devices/{device}/{id}', [DeviceController::class, 'show'])->name('device.detail');
    Route::get('/modul/api/devices/{device}/{id}', [DeviceController::class, 'getDetail'])->name('api.device.detail');
    Route::post('/modul/api/devices/{device}/{id}/calibration', [DeviceController::class, 'storeCalibration'])->name('api.device.calibration.store');
    Route::get('/modul/locations', [LocationController::class, 'index'])->name('locations');
    Route::get('/modul/locations/{id}', [LocationController::class, 'show'])->name('location.detail');
    Route::get('/modul/api/sensor-data', [LocationController::class, 'getSensorData'])->name('api.sensor.data');
    Route::get('/modul/api/location/{id}', [LocationController::class, 'getLocationDetail'])->name('api.location.detail');
    Route::get('/modul/{module}', [DashboardController::class, 'index'])->name('module');
    Route::get('/modul/', [DashboardController::class, 'index'])->name('home');
});
Route::get('/modul/api/bmkg/forecast', BmkgProxyController::class)->name('api.bmkg.forecast');
