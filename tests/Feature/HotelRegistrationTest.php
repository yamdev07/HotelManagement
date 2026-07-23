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

    public function test_signup_shows_spam_notice_with_email(): void
    {
        Mail::fake();

        $this->post('/inscription', [
            'company_name' => 'Hotel Spam',
            'plan'         => 'starter',
            'admin_name'   => 'X',
            'admin_email'  => 'spam@notice.test',
        ])->assertSessionHas('credentials_email', 'spam@notice.test');

        // Le bandeau "vérifiez vos spams" s'affiche sur l'onboarding
        $this->get(route('onboarding.show'))
            ->assertOk()
            ->assertSee('spam@notice.test')
            ->assertSee('courrier indésirable');
    }

    public function test_signup_rejects_emoji_in_company_name(): void
    {
        Mail::fake();

        $this->post('/inscription', [
            'company_name' => 'Hotel 🏨 Cactus 😀',
            'admin_name'   => 'X',
            'admin_email'  => 'emoji@test.test',
        ])->assertSessionHasErrors('company_name');

        $this->assertDatabaseMissing('hotels', ['name' => 'Hotel 🏨 Cactus 😀']);
    }

    public function test_signup_accepts_accented_and_punctuated_name(): void
    {
        Mail::fake();

        $this->post('/inscription', [
            'company_name' => "Résidence l'Océan & Fils (2024)",
            'admin_name'   => 'André Éboué',
            'admin_email'  => 'accent@test.test',
        ]);

        $this->assertDatabaseHas('hotels', ['name' => "Résidence l'Océan & Fils (2024)"]);
    }

    public function test_country_sets_currency_and_adjusts_price(): void
    {
        Mail::fake();

        $this->post('/inscription', [
            'company_name' => 'Hotel Abidjan',
            'plan'         => 'pro',
            'country'      => 'CI',
            'admin_name'   => 'X',
            'admin_email'  => 'x@ci.test',
        ]);

        $hotel = \App\Models\Hotel::where('name', 'Hotel Abidjan')->first();
        $this->assertEquals('CI', $hotel->country);
        $this->assertEquals('XOF', $hotel->currency);
        // pro = 45000 base × 1.20 (Côte d'Ivoire)
        $this->assertEquals(54000, $hotel->monthlyPrice());
        // Bénin reste à 45000
        $this->assertEquals(45000, \App\Models\Hotel::priceFor('pro', 'BJ'));
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

    public function test_signup_accepts_large_logo_up_to_4mb(): void
    {
        Mail::fake();
        Storage::fake('public');

        // ~3 Mo : refusé avant (limite 2 Mo), accepté maintenant (4 Mo)
        $this->post('/inscription', [
            'company_name' => 'Hotel Gros Logo',
            'admin_name'   => 'Boss',
            'admin_email'  => 'boss@gros.test',
            'logo'         => UploadedFile::fake()->image('logo.png')->size(3000),
        ])->assertSessionDoesntHaveErrors('logo');

        $this->assertNotNull(Hotel::where('name', 'Hotel Gros Logo')->first()?->logo);
    }

    public function test_signup_accepts_svg_logo(): void
    {
        Mail::fake();
        Storage::fake('public');

        // SVG : refusé avant par la règle 'image' (getimagesize), accepté maintenant
        $this->post('/inscription', [
            'company_name' => 'Hotel SVG',
            'admin_name'   => 'Boss',
            'admin_email'  => 'boss@svg.test',
            'logo'         => UploadedFile::fake()->create('logo.svg', 40, 'image/svg+xml'),
        ])->assertSessionDoesntHaveErrors('logo');

        $this->assertNotNull(Hotel::where('name', 'Hotel SVG')->first());
    }

    public function test_signup_rejects_non_image_logo(): void
    {
        Mail::fake();
        Storage::fake('public');

        $this->post('/inscription', [
            'company_name' => 'Hotel Bad Logo',
            'admin_name'   => 'X',
            'admin_email'  => 'bad@logo.test',
            'logo'         => UploadedFile::fake()->create('doc.pdf', 20, 'application/pdf'),
        ])->assertSessionHasErrors('logo');

        $this->assertDatabaseMissing('hotels', ['name' => 'Hotel Bad Logo']);
    }

    public function test_existing_email_auto_logs_in_instead_of_error(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'taken@test.test']);

        $response = $this->post('/inscription', [
            'company_name' => 'Hotel Dup',
            'admin_name'   => 'X',
            'admin_email'  => 'taken@test.test',
        ]);

        $response->assertRedirectToRoute('onboarding.show');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('hotels', ['name' => 'Hotel Dup']);
    }
}
