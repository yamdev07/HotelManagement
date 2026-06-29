<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Les tests n'ont pas besoin des assets compilés (Vite) : on neutralise
        // la directive @vite pour éviter l'erreur "manifest not found" en CI.
        $this->withoutVite();
    }
}
