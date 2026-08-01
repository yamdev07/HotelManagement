<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Review;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Système d'avis : dépôt public (modéré ou non), affichage vitrine,
 * modération back-office et isolation multi-tenant.
 */
class ReviewSystemTest extends TestCase
{
    use RefreshDatabase;

    private function hotel(array $attrs = []): Hotel
    {
        return Hotel::create(array_merge([
            'name' => 'Rev '.Str::random(4),
            'slug' => 'rev-'.Str::lower(Str::random(6)),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'show_reviews' => true,
            'reviews_moderation' => true,
        ], $attrs));
    }

    private function review(Hotel $hotel, array $attrs = []): Review
    {
        app(TenantManager::class)->setHotelId($hotel->id);
        $r = Review::create(array_merge([
            'hotel_id' => $hotel->id, 'author_name' => 'Awa', 'author_city' => 'Cotonou',
            'rating' => 5, 'comment' => 'Séjour vraiment parfait, merci !', 'status' => Review::STATUS_PENDING,
        ], $attrs));
        app(TenantManager::class)->forget();

        return $r;
    }

    public function test_public_can_submit_review_pending_when_moderated(): void
    {
        $hotel = $this->hotel(['reviews_moderation' => true]);

        $this->post(route('public.hotel.review.store', $hotel->slug), [
            'author_name' => 'Marc', 'author_city' => 'Paris',
            'rating' => 4, 'comment' => 'Très bon accueil et chambre impeccable.',
        ])->assertRedirect();

        app(TenantManager::class)->setHotelId($hotel->id);
        $this->assertDatabaseHas('reviews', [
            'hotel_id' => $hotel->id, 'author_name' => 'Marc', 'rating' => 4, 'status' => 'pending',
        ]);
        app(TenantManager::class)->forget();
    }

    public function test_public_review_published_directly_when_moderation_off(): void
    {
        $hotel = $this->hotel(['reviews_moderation' => false]);

        $this->post(route('public.hotel.review.store', $hotel->slug), [
            'author_name' => 'Sarah', 'rating' => 5, 'comment' => 'Une expérience à refaire absolument.',
        ])->assertRedirect();

        app(TenantManager::class)->setHotelId($hotel->id);
        $this->assertDatabaseHas('reviews', ['author_name' => 'Sarah', 'status' => 'approved']);
        app(TenantManager::class)->forget();
    }

    public function test_review_requires_valid_fields(): void
    {
        $hotel = $this->hotel();

        $this->post(route('public.hotel.review.store', $hotel->slug), [
            'author_name' => '', 'rating' => 9, 'comment' => 'court',
        ])->assertSessionHasErrors(['author_name', 'rating', 'comment']);
    }

    public function test_only_approved_reviews_show_on_vitrine(): void
    {
        $hotel = $this->hotel();
        $this->review($hotel, ['comment' => 'AVIS-APPROUVE-XYZ', 'status' => Review::STATUS_APPROVED, 'approved_at' => now()]);
        $this->review($hotel, ['comment' => 'AVIS-ENATTENTE-XYZ', 'status' => Review::STATUS_PENDING]);

        $html = $this->get('/h/'.$hotel->slug)->assertOk()->getContent();

        $this->assertStringContainsString('AVIS-APPROUVE-XYZ', $html);
        $this->assertStringNotContainsString('AVIS-ENATTENTE-XYZ', $html);
    }

    public function test_admin_can_approve_and_reply(): void
    {
        $hotel = $this->hotel();
        $review = $this->review($hotel);
        $admin = $this->hotelAdmin($hotel);

        $this->actingAs($admin)->post(route('reviews.approve', $review))->assertRedirect();
        $this->assertSame('approved', $review->fresh()->status);
        $this->assertNotNull($review->fresh()->approved_at);

        $this->actingAs($admin)->post(route('reviews.reply', $review), ['reply' => 'Merci pour votre visite !'])
            ->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('Merci pour votre visite !', $review->fresh()->reply);
    }

    public function test_reviews_are_isolated_per_hotel(): void
    {
        $hotelA = $this->hotel();
        $hotelB = $this->hotel();
        $reviewB = $this->review($hotelB);
        $adminA = $this->hotelAdmin($hotelA);

        // L'admin de A ne peut pas modérer un avis de B (scope -> 404).
        $this->actingAs($adminA)->post(route('reviews.approve', $reviewB))->assertNotFound();
        $this->assertSame('pending', $reviewB->fresh()->status);
    }

    private function hotelAdmin(Hotel $hotel): User
    {
        app(TenantManager::class)->setHotelId($hotel->id);
        $admin = User::factory()->create(['role' => 'Admin', 'hotel_id' => $hotel->id]);
        app(TenantManager::class)->forget();

        return $admin;
    }
}
