<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Protection anti-force-brute : le login est limité (throttle) à 10 tentatives
 * par minute et par IP.
 */
class LoginThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited_after_too_many_attempts(): void
    {
        $payload = ['email' => 'brute@x.test', 'password' => 'wrong-password'];

        // 10 tentatives autorisées (identifiants invalides -> redirection).
        for ($i = 0; $i < 10; $i++) {
            $this->post('/login', $payload);
        }

        // La 11ᵉ est bloquée par le throttle.
        $this->post('/login', $payload)->assertStatus(429);
    }
}
