<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'currency',
        'timezone',
        'logo',
        'primary_color',
        'secondary_color',
        'tagline',
        'description',
        'cover_image',
        'show_rooms',
        'show_restaurant',
        'show_services',
        'show_contact',
        'services',
        'socials',
        'about_title',
        'about_text',
        'contact_email',
        'contact_phone',
        'address',
        'is_active',
        'subscription_ends_at',
        'plan',
        'room_limit',
        'onboarding_completed_at',
        'owner_user_id',
        'metadata',
    ];

    protected $casts = [
        'is_active'            => 'boolean',
        'show_rooms'           => 'boolean',
        'show_restaurant'      => 'boolean',
        'show_services'        => 'boolean',
        'show_contact'         => 'boolean',
        'subscription_ends_at'    => 'datetime',
        'onboarding_completed_at' => 'datetime',
        'room_limit'              => 'integer',
        'services'                => 'array',
        'socials'                 => 'array',
        'metadata'                => 'array',
    ];

    /** Services par défaut si l'hôtelier n'en a pas défini. */
    public const DEFAULT_SERVICES = [
        ['icon' => 'fa-wifi', 'title' => 'Wi-Fi gratuit', 'description' => "Connexion haut débit partout dans l'établissement."],
        ['icon' => 'fa-bell-concierge', 'title' => 'Conciergerie 24/7', 'description' => 'Une équipe dévouée à votre service jour et nuit.'],
        ['icon' => 'fa-mug-saucer', 'title' => 'Petit-déjeuner', 'description' => 'Une table généreuse pour bien commencer la journée.'],
        ['icon' => 'fa-car', 'title' => 'Voiturier & parking', 'description' => 'Stationnement sécurisé et service voiturier.'],
        ['icon' => 'fa-spa', 'title' => 'Bien-être', 'description' => 'Des moments de détente pensés pour vous.'],
        ['icon' => 'fa-location-dot', 'title' => 'Emplacement', "description" => "Au cœur des points d'intérêt incontournables."],
    ];

    /** Liste des services de la vitrine (personnalisés ou défaut). */
    public function siteServices(): array
    {
        $custom = collect($this->services ?? [])
            ->filter(fn ($s) => ! empty($s['title']))
            ->values()
            ->all();

        return $custom ?: self::DEFAULT_SERVICES;
    }

    /** Réseaux sociaux renseignés (clé => url), vides exclus. */
    public function socialLinks(): array
    {
        return collect($this->socials ?? [])
            ->filter(fn ($url) => ! empty($url))
            ->all();
    }

    public function aboutTitle(): string
    {
        return $this->about_title ?: "Une expérience d'exception";
    }

    public function aboutText(): string
    {
        return $this->about_text
            ?: ($this->description ?: 'Niché dans un cadre raffiné, '.$this->name.' vous accueille pour un séjour inoubliable.');
    }

    /** L'hôtel doit-il encore passer par l'onboarding (personnalisation initiale) ? */
    public function needsOnboarding(): bool
    {
        return $this->onboarding_completed_at === null;
    }

    /**
     * Détermine le palier d'abonnement adapté à un nombre de chambres.
     */
    public static function planForRoomCount(int $rooms): string
    {
        foreach (config('plans.tiers') as $key => $tier) {
            $max = $tier['room_max'];
            if ($rooms >= $tier['room_min'] && ($max === null || $rooms <= $max)) {
                return $key;
            }
        }

        return config('plans.default', 'starter');
    }

    /** Configuration du palier courant. */
    public function planConfig(): array
    {
        $tiers = config('plans.tiers');

        return $tiers[$this->plan] ?? $tiers[config('plans.default', 'starter')];
    }

    public function planName(): string
    {
        return $this->planConfig()['name'];
    }

    public function monthlyPrice(): int
    {
        return $this->planConfig()['price'];
    }

    /** Limite de chambres du plan (null = illimité). */
    public function roomLimit(): ?int
    {
        return $this->room_limit ?? $this->planConfig()['room_limit'];
    }

    /**
     * Utilisateurs (staff) rattachés à cet hôtel.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Propriétaire / admin principal de l'hôtel.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * L'hôtel a-t-il actuellement accès à la plateforme ?
     * (actif ET abonnement non expiré)
     */
    public function hasActiveAccess(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->subscription_ends_at !== null && $this->subscription_ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function isSubscriptionExpired(): bool
    {
        return $this->subscription_ends_at !== null && $this->subscription_ends_at->isPast();
    }

    /**
     * Couleur principale (fallback sur la couleur par défaut de la plateforme).
     */
    public function primaryColor(): string
    {
        return $this->primary_color ?: '#4f46e5';
    }

    public function secondaryColor(): string
    {
        return $this->secondary_color ?: '#0f172a';
    }

    /**
     * URL du logo de l'hôtel, ou null si aucun logo n'est défini.
     */
    public function logoUrl(): ?string
    {
        return $this->logo ? asset('storage/'.$this->logo) : null;
    }

    /**
     * URL de l'image de couverture de la vitrine, ou null.
     */
    public function coverUrl(): ?string
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : null;
    }

    /**
     * URL publique de la vitrine de l'hôtel.
     */
    public function publicUrl(): string
    {
        return route('public.hotel', $this->slug);
    }

    /**
     * Image d'en-tête : celle de l'hôtel, ou une image premium par défaut.
     */
    public function coverOrDefault(): string
    {
        return $this->coverUrl() ?: config('vitrine.default_cover');
    }
}
