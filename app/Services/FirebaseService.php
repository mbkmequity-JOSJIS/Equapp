<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;

class FirebaseService
{
    protected Database $database;

    public function __construct()
    {
        $this->database = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->withDatabaseUri('https://equapp-5718f-default-rtdb.firebaseio.com')
            ->createDatabase();
    }

    public function getDataAquaviska()
    {
        return $this->database->getReference('water_quality')->getValue();
    }
}