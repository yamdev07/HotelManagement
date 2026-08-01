<?php

namespace Tests\Unit;

use App\Models\PromoCode;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Logique métier des codes promo : validité et calcul de la remise.
 */
class PromoCodeTest extends TestCase
{
    public function test_percent_discount(): void
    {
        $c = new PromoCode(['type' => 'percent', 'value' => 10]);
        $this->assertSame(10000, $c->discountOn(100000)); // 10 % de 100 000
    }

    public function test_fixed_discount_is_capped_to_total(): void
    {
        $c = new PromoCode(['type' => 'fixed', 'value' => 5000]);
        $this->assertSame(5000, $c->discountOn(100000));
        $this->assertSame(3000, $c->discountOn(3000)); // jamais plus que le total
    }

    public function test_inactive_code_is_rejected(): void
    {
        $c = new PromoCode(['type' => 'percent', 'value' => 10, 'is_active' => false, 'min_nights' => 1]);
        $this->assertFalse($c->validateFor(2)['ok']);
    }

    public function test_min_nights_enforced(): void
    {
        $c = new PromoCode(['type' => 'percent', 'value' => 10, 'is_active' => true, 'min_nights' => 3]);
        $this->assertFalse($c->validateFor(2)['ok']);
        $this->assertTrue($c->validateFor(3)['ok']);
    }

    public function test_expired_code_is_rejected(): void
    {
        $c = new PromoCode([
            'type' => 'percent', 'value' => 10, 'is_active' => true, 'min_nights' => 1,
            'ends_at' => Carbon::today()->subDay(),
        ]);
        $this->assertFalse($c->validateFor(1)['ok']);
    }

    public function test_max_uses_exhausted_is_rejected(): void
    {
        $c = new PromoCode(['type' => 'percent', 'value' => 10, 'is_active' => true, 'min_nights' => 1, 'max_uses' => 5, 'used_count' => 5]);
        $this->assertFalse($c->validateFor(1)['ok']);
    }
}
