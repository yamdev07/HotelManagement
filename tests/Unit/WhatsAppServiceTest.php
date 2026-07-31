<?php

namespace Tests\Unit;

use App\Services\WhatsAppService;
use Tests\TestCase;

/**
 * Normalisation des numéros au format Cloud API (international, sans « + »).
 * L'indicatif par défaut (229) vient de config('services.whatsapp.default_country').
 */
class WhatsAppServiceTest extends TestCase
{
    private WhatsAppService $wa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wa = new WhatsAppService;
    }

    public function test_international_number_is_kept(): void
    {
        $this->assertSame('22990112233', $this->wa->normalize('+229 90 11 22 33'));
    }

    public function test_local_number_gets_default_country_code(): void
    {
        $this->assertSame('22990112233', $this->wa->normalize('90 11 22 33'));
    }

    public function test_double_zero_prefix_is_treated_as_international(): void
    {
        $this->assertSame('22990112233', $this->wa->normalize('0022990112233'));
    }

    public function test_leading_zero_local_number_is_cleaned(): void
    {
        $this->assertSame('22990112233', $this->wa->normalize('090 11 22 33'));
    }

    public function test_empty_or_garbage_returns_null(): void
    {
        $this->assertNull($this->wa->normalize(null));
        $this->assertNull($this->wa->normalize(''));
        $this->assertNull($this->wa->normalize('abc'));
        $this->assertNull($this->wa->normalize('12')); // trop court
    }

    public function test_not_configured_send_is_a_noop(): void
    {
        config(['services.whatsapp.token' => null, 'services.whatsapp.phone_id' => null]);
        $this->assertFalse($this->wa->isConfigured());
        $this->assertFalse($this->wa->sendText('+22990112233', 'test'));
    }
}
