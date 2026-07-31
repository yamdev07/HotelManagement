<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Transaction;
use App\Support\GuestMessages;
use Tests\TestCase;

/**
 * Messages WhatsApp pré-remplis + liens wa.me (parcours « en un tap »).
 */
class GuestMessagesTest extends TestCase
{
    private function fakeTransaction(): Transaction
    {
        $hotel = new Hotel(['name' => 'Test Hôtel', 'currency' => 'XOF']);
        $tx = new Transaction([
            'check_in' => '2026-08-10', 'check_out' => '2026-08-12',
            'total_price' => 100000, 'total_payment' => 15000,
        ]);
        $tx->id = 42;
        $tx->setRelation('customer', new Customer(['name' => 'Awa']));
        $tx->setRelation('room', new Room(['number' => '101']));
        $tx->setRelation('hotel', $hotel);

        return $tx;
    }

    public function test_confirmation_message_contains_key_details(): void
    {
        $tx = $this->fakeTransaction();
        $msg = GuestMessages::confirmation($tx->hotel, $tx);

        $this->assertStringContainsString('RES-00042', $msg);
        $this->assertStringContainsString('Test Hôtel', $msg);
        $this->assertStringContainsString('101', $msg);
        $this->assertStringContainsString('Awa', $msg);
    }

    public function test_link_builds_wa_me_url_from_phone(): void
    {
        $url = GuestMessages::link('+229 90 11 22 33', 'Bonjour');

        $this->assertSame('https://wa.me/22990112233?text=Bonjour', $url);
    }

    public function test_link_returns_null_for_unusable_phone(): void
    {
        $this->assertNull(GuestMessages::link(null, 'x'));
        $this->assertNull(GuestMessages::link('abc', 'x'));
    }
}
