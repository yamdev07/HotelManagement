<?php

namespace Tests\Feature;

use App\Mail\TrialLifecycleMail;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TrialLifecycleEmailTest extends TestCase
{
    use RefreshDatabase;

    private function makeTrialHotel(string $email = 'owner@trial.test'): Hotel
    {
        $hotel = Hotel::create([
            'name' => 'Hotel Essai',
            'slug' => 'hotel-essai-'.uniqid(),
            'country' => 'BJ',
            'currency' => 'XOF',
            'plan' => 'pro',
            'room_limit' => 20,
            'is_active' => true,
            'contact_email' => $email,
            'subscription_ends_at' => now()->addDays(14),
        ]);

        $owner = User::create([
            'hotel_id' => $hotel->id,
            'name' => 'Proprio',
            'email' => $email,
            'role' => 'Admin',
            'password' => bcrypt('secret-Pass1'),
            'random_key' => 'k'.uniqid(),
        ]);
        $hotel->update(['owner_user_id' => $owner->id]);

        return $hotel;
    }

    public function test_day_1_sends_welcome_once_and_is_idempotent(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-01-01 09:00:00');
        $this->makeTrialHotel();

        // On avance d'un jour : J+1
        Carbon::setTestNow('2026-01-02 09:00:00');
        $this->artisan('trial:send-lifecycle')->assertSuccessful();

        Mail::assertSent(TrialLifecycleMail::class, fn ($m) => $m->stage === 'welcome' && $m->hasTo('owner@trial.test'));
        Mail::assertSent(TrialLifecycleMail::class, 1);

        // Relance le même jour : aucun nouvel envoi (idempotent)
        $this->artisan('trial:send-lifecycle')->assertSuccessful();
        Mail::assertSent(TrialLifecycleMail::class, 1);

        Carbon::setTestNow();
    }

    public function test_non_scheduled_day_sends_nothing(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-01-01 09:00:00');
        $this->makeTrialHotel();

        // J+2 n'est pas une étape planifiée
        Carbon::setTestNow('2026-01-03 09:00:00');
        $this->artisan('trial:send-lifecycle')->assertSuccessful();

        Mail::assertNothingSent();
        Carbon::setTestNow();
    }

    public function test_day_3_sends_tips(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-01-01 09:00:00');
        $this->makeTrialHotel();

        Carbon::setTestNow('2026-01-04 09:00:00');
        $this->artisan('trial:send-lifecycle')->assertSuccessful();

        Mail::assertSent(TrialLifecycleMail::class, fn ($m) => $m->stage === 'tips');
        Carbon::setTestNow();
    }

    public function test_ending_not_sent_when_trial_not_ending_soon(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-01-01 09:00:00');
        // Abonnement payé : fin dans longtemps -> pas d'email "fin d'essai"
        $hotel = $this->makeTrialHotel('paid@trial.test');
        $hotel->update(['subscription_ends_at' => now()->addMonths(6)]);

        Carbon::setTestNow('2026-01-12 09:00:00'); // J+11
        $this->artisan('trial:send-lifecycle')->assertSuccessful();

        Mail::assertNotSent(TrialLifecycleMail::class);
        Carbon::setTestNow();
    }

    public function test_day_14_sends_trial_end_when_trial_over(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-01-01 09:00:00');
        $this->makeTrialHotel(); // essai jusqu'au 2026-01-15

        Carbon::setTestNow('2026-01-15 09:00:00'); // J+14, essai qui se termine
        $this->artisan('trial:send-lifecycle')->assertSuccessful();

        Mail::assertSent(TrialLifecycleMail::class, fn ($m) => $m->stage === 'trial_end');
        Carbon::setTestNow();
    }

    public function test_day_30_sends_review(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-01-01 09:00:00');
        $this->makeTrialHotel();

        Carbon::setTestNow('2026-01-31 09:00:00'); // J+30
        $this->artisan('trial:send-lifecycle')->assertSuccessful();

        Mail::assertSent(TrialLifecycleMail::class, fn ($m) => $m->stage === 'review');
        Carbon::setTestNow();
    }
}
