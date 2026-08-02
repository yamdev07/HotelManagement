<?php

namespace Tests\Feature;

use App\Enums\RoomStatus;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Actions de l'assistant IA (function calling) : lecture directe, écriture
 * derrière confirmation, permissions et validation métier.
 */
class AiAssistantActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.groq.key' => 'test-key']);
        Carbon::setTestNow(Carbon::today()->setTime(13, 0)); // check-in autorisé (>= 12h, même jour)
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /** @return array{0:User,1:Room,2:Hotel} */
    private function make(): array
    {
        $hotel = Hotel::create([
            'name' => 'Act Hotel', 'slug' => 'act-'.Str::lower(Str::random(6)),
            'is_active' => true, 'onboarding_completed_at' => now(), 'subscription_ends_at' => now()->addMonth(),
        ]);
        app(TenantManager::class)->setHotelId($hotel->id);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        $hotel->update(['owner_user_id' => $admin->id]);
        $type = Type::firstOrCreate(['name' => 'Std'], ['capacity' => 2, 'information' => 'x']);
        $room = Room::create([
            'type_id' => $type->id, 'room_status_id' => RoomStatus::Available->value,
            'number' => '101', 'capacity' => 2, 'price' => 45000, 'view' => '',
        ]);
        app(TenantManager::class)->forget();

        return [$admin, $room, $hotel];
    }

    private function reservation(User $admin, Room $room): Transaction
    {
        app(TenantManager::class)->setHotelId($admin->hotel_id);
        $customer = Customer::create(['name' => 'Awa', 'email' => Str::random(6).'@x.test', 'phone' => '+22990', 'gender' => 'Other', 'user_id' => $admin->id]);
        $tx = Transaction::create([
            'user_id' => $admin->id, 'customer_id' => $customer->id, 'room_id' => $room->id,
            'check_in' => Carbon::today()->format('Y-m-d'), 'check_out' => Carbon::today()->addDays(2)->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => 1, 'total_price' => 90000,
        ]);
        app(TenantManager::class)->forget();

        return $tx;
    }

    private function toolCall(string $name, array $args = []): array
    {
        return ['choices' => [['message' => [
            'role' => 'assistant', 'content' => null,
            'tool_calls' => [['id' => 'call_1', 'type' => 'function', 'function' => [
                'name' => $name, 'arguments' => json_encode($args),
            ]]],
        ]]]];
    }

    private function finalText(string $text): array
    {
        return ['choices' => [['message' => ['role' => 'assistant', 'content' => $text]]]];
    }

    public function test_read_action_is_executed_and_answered(): void
    {
        [$admin] = $this->make();
        Http::fake(['api.groq.com/*' => Http::sequence()
            ->push($this->toolCall('list_available_rooms'))          // 1er appel : l'IA demande l'outil
            ->push($this->finalText('Il y a 1 chambre disponible : la 101.')), // 2e appel : réponse finale
        ]);

        $this->actingAs($admin)->postJson(route('assistant.chat'), [
            'messages' => [['role' => 'user', 'content' => 'Quelles chambres sont libres ?']],
        ])->assertOk()->assertJson(['ok' => true, 'reply' => 'Il y a 1 chambre disponible : la 101.']);
    }

    public function test_write_action_returns_pending_without_executing(): void
    {
        [$admin, $room] = $this->make();
        $tx = $this->reservation($admin, $room);

        Http::fake(['api.groq.com/*' => Http::response($this->toolCall('check_in_reservation', ['reservation_id' => $tx->id]))]);

        $res = $this->actingAs($admin)->postJson(route('assistant.chat'), [
            'messages' => [['role' => 'user', 'content' => 'Fais le check-in de la réservation '.$tx->id]],
        ])->assertOk();

        $res->assertJson(['ok' => true]);
        $this->assertSame('check_in_reservation', $res->json('pending.tool'));
        // Rien n'est exécuté tant que non confirmé.
        $this->assertSame('reservation', $tx->fresh()->status);
    }

    public function test_execute_performs_checkin_after_confirmation(): void
    {
        [$admin, $room] = $this->make();
        $tx = $this->reservation($admin, $room);

        $this->actingAs($admin)->postJson(route('assistant.execute'), [
            'tool' => 'check_in_reservation', 'args' => ['reservation_id' => $tx->id],
        ])->assertOk()->assertJson(['ok' => true]);

        $this->assertSame('active', $tx->fresh()->status);
    }

    public function test_execute_denies_action_without_permission(): void
    {
        [$admin, $room, $hotel] = $this->make();
        app(TenantManager::class)->setHotelId($hotel->id);
        $cashier = User::factory()->create(['role' => 'Cashier', 'hotel_id' => $hotel->id]);
        app(TenantManager::class)->forget();

        $res = $this->actingAs($cashier)->postJson(route('assistant.execute'), [
            'tool' => 'mark_room_clean', 'args' => ['room_number' => '101'],
        ])->assertOk();

        $res->assertJson(['ok' => false]);
        $this->assertStringContainsString('permission', Str::lower($res->json('message')));
    }

    public function test_execute_rejects_non_write_tool(): void
    {
        [$admin] = $this->make();

        $this->actingAs($admin)->postJson(route('assistant.execute'), [
            'tool' => 'list_available_rooms', 'args' => [],
        ])->assertStatus(422);
    }
}
