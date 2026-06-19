<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HotelRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_form_is_reachable(): void
    {
        $this->get('/inscription')->assertOk()->assertSee('Créez votre établissement');
    }

    public function test_visitor_can_register_a_hotel_and_is_logged_in(): void
    {
        $response = $this->post('/inscription', [
            'company_name'                => 'Nouvel Hotel',
            'primary_color'               => '#1e6b2e',
            'contact_phone'               => '+229 00 00 00 00',
            'admin_name'                  => 'Patron',
            'admin_email'                 => 'patron@nouvel.test',
            'admin_password'              => 'secret123',
            'admin_password_confirmation' => 'secret123',
        ]);

        $response->assertRedirectToRoute('hotel.settings.edit');

        $hotel = Hotel::where('name', 'Nouvel Hotel')->first();
        $this->assertNotNull($hotel);
        $this->assertTrue($hotel->is_active);
        $this->assertNotNull($hotel->subscription_ends_at);
        $this->assertEquals('#1e6b2e', $hotel->primary_color);

        $admin = User::where('email', 'patron@nouvel.test')->first();
        $this->assertEquals('Admin', $admin->role);
        $this->assertEquals($hotel->id, $admin->hotel_id);
        $this->assertEquals($admin->id, $hotel->owner_user_id);

        $this->assertAuthenticatedAs($admin);
    }

    public function test_signup_accepts_logo_upload(): void
    {
        Storage::fake('public');

        $this->post('/inscription', [
            'company_name'                => 'Hotel Logo',
            'admin_name'                  => 'Boss',
            'admin_email'                 => 'boss@logo.test',
            'admin_password'              => 'secret123',
            'admin_password_confirmation' => 'secret123',
            'logo'                        => UploadedFile::fake()->image('logo.png'),
        ]);

        $hotel = Hotel::where('name', 'Hotel Logo')->first();
        $this->assertNotNull($hotel->logo);
        Storage::disk('public')->assertExists($hotel->logo);
    }

    public function test_password_must_be_confirmed(): void
    {
        $this->post('/inscription', [
            'company_name'                => 'Hotel Bad',
            'admin_name'                  => 'X',
            'admin_email'                 => 'x@bad.test',
            'admin_password'              => 'secret123',
            'admin_password_confirmation' => 'different',
        ])->assertSessionHasErrors('admin_password');

        $this->assertDatabaseMissing('hotels', ['name' => 'Hotel Bad']);
    }
}
