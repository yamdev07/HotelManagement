<?php

namespace Tests\Feature;

use App\Mail\HotelCredentialsMail;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HotelRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_form_is_reachable(): void
    {
        $this->get('/inscription')->assertOk()->assertSee('Créez votre établissement');
    }

    public function test_visitor_can_register_and_is_logged_in_and_emailed(): void
    {
        Mail::fake();

        $response = $this->post('/inscription', [
            'company_name' => 'Nouvel Hotel',
            'plan'         => 'pro',
            'contact_phone' => '+229 00 00 00 00',
            'admin_name'   => 'Patron',
            'admin_email'  => 'patron@nouvel.test',
        ]);

        $response->assertRedirectToRoute('onboarding.show');

        $hotel = Hotel::where('name', 'Nouvel Hotel')->first();
        $this->assertNotNull($hotel);
        $this->assertTrue($hotel->is_active);
        $this->assertEquals('pro', $hotel->plan);
        $this->assertEquals(20, $hotel->room_limit);
        $this->assertNotNull($hotel->subscription_ends_at);
        // Essai ~14 jours
        $this->assertTrue($hotel->subscription_ends_at->isFuture());

        $admin = User::where('email', 'patron@nouvel.test')->first();
        $this->assertEquals('Admin', $admin->role);
        $this->assertEquals($hotel->id, $admin->hotel_id);
        $this->assertEquals($admin->id, $hotel->owner_user_id);

        $this->assertAuthenticatedAs($admin);

        Mail::assertSent(HotelCredentialsMail::class, fn ($mail) => $mail->hasTo('patron@nouvel.test'));
    }

    public function test_plan_defaults_to_starter_when_absent(): void
    {
        Mail::fake();

        $this->post('/inscription', [
            'company_name' => 'Hotel Defaut',
            'admin_name'   => 'X',
            'admin_email'  => 'x@defaut.test',
        ]);

        $hotel = Hotel::where('name', 'Hotel Defaut')->first();
        $this->assertEquals('starter', $hotel->plan);
        $this->assertEquals(10, $hotel->room_limit);
    }

    public function test_signup_accepts_logo_upload(): void
    {
        Mail::fake();
        Storage::fake('public');

        $this->post('/inscription', [
            'company_name' => 'Hotel Logo',
            'plan'         => 'starter',
            'admin_name'   => 'Boss',
            'admin_email'  => 'boss@logo.test',
            'logo'         => UploadedFile::fake()->image('logo.png'),
        ]);

        $hotel = Hotel::where('name', 'Hotel Logo')->first();
        $this->assertNotNull($hotel->logo);
        Storage::disk('public')->assertExists($hotel->logo);
    }

    public function test_email_must_be_unique(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'taken@test.test']);

        $this->post('/inscription', [
            'company_name' => 'Hotel Dup',
            'admin_name'   => 'X',
            'admin_email'  => 'taken@test.test',
        ])->assertSessionHasErrors('admin_email');

        $this->assertDatabaseMissing('hotels', ['name' => 'Hotel Dup']);
    }
}
