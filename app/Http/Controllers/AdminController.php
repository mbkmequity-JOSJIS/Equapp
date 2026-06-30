<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class AdminController extends Controller
{
    public function index()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->withDatabaseUri('https://equapp-5718f-default-rtdb.firebaseio.com');

        $database = $factory->createDatabase();
        
        $waterQualityDevices = $database->getReference('water_quality/devices')->getValue();

        return view('admin.index', [
            'waterQualityDevices' => $waterQualityDevices
        ]);
    }
    public function updateFirebaseDeviceName(Request $request)
    {
        $node = $request->input('node');
        $name = $request->input('name');

        if ($node && $name) {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
                ->withDatabaseUri('https://equapp-5718f-default-rtdb.firebaseio.com');

            $database = $factory->createDatabase();
            $database->getReference('water_quality/devices/' . $node . '/device_name')->set($name);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 400);
    }
}
