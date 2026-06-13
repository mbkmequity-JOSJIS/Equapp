<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;
    
class FirebaseService
{
    protected Database $database;
    protected Auth $auth;


    public function __construct()
    {

        $this->database = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->withDatabaseUri('https://equapp-5718f-default-rtdb.firebaseio.com')
            ->createDatabase();

        $this->auth = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
            ->createAuth();
    }

    public function auth()
    {
        return $this->auth;
    }


    public function getDataAquaviska()
    {
        return $this->database->getReference('water_quality')->getValue();
    }
}
