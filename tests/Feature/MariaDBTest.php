<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class MariaDBTest extends TestCase
{
    public function test_maria_db_connection(): void
    {
        try {
            $pdo = DB::connection()->getPdo();
            $driver = DB::connection()->getDriverName();
            
            $this->assertEquals('mysql', $driver);
            
            dump('✅ Connexion MariaDB réussie !');
            dump('📊 Base de données : ' . DB::connection()->getDatabaseName());
            
        } catch (\Exception $e) {
            dump('❌ Erreur : ' . $e->getMessage());
            $this->fail('Connexion à MariaDB échouée');
        }
    }
}