<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'currency',
        'country',
        'timezone',
        'logo',
        'primary_color',
        'secondary_color',
        'theme_mode',
        'tagline',
        'description',
        'cover_image',
        'show_rooms',
        'show_restaurant',
        'show_services',
        'show_contact',
        'show_reviews',
        'reviews_moderation',
        'services',
        'socials',
        'about_title',
        'about_text',
        'contact_email',
        'contact_phone',
        'address',
        'is_active',
        'suspension_reason',
        'subscription_ends_at',
        'plan',
        'pending_plan',
        'pending_plan_effective_at',
        'room_limit',
        'onboarding_completed_at',
        'owner_user_id',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_rooms' => 'boolean',
        'show_restaurant' => 'boolean',
        'show_services' => 'boolean',
        'show_contact' => 'boolean',
        'show_reviews' => 'boolean',
        'reviews_moderation' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'pending_plan_effective_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
        'room_limit' => 'integer',
        'services' => 'array',
        'socials' => 'array',
        'metadata' => 'array',
    ];

    /** Services par défaut si l'hôtelier n'en a pas défini. */
    public const DEFAULT_SERVICES = [
        ['icon' => 'fa-wifi', 'title' => 'Wi-Fi gratuit', 'description' => "Connexion haut débit partout dans l'établissement."],
        ['icon' => 'fa-bell-concierge', 'title' => 'Conciergerie 24/7', 'description' => 'Une équipe dévouée à votre service jour et nuit.'],
        ['icon' => 'fa-mug-saucer', 'title' => 'Petit-déjeuner', 'description' => 'Une table généreuse pour bien commencer la journée.'],
        ['icon' => 'fa-car', 'title' => 'Voiturier & parking', 'description' => 'Stationnement sécurisé et service voiturier.'],
        ['icon' => 'fa-spa', 'title' => 'Bien-être', 'description' => 'Des moments de détente pensés pour vous.'],
        ['icon' => 'fa-location-dot', 'title' => 'Emplacement', 'description' => "Au cœur des points d'intérêt incontournables."],
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

    /** Libellés des modules premium (pour les messages d'upgrade). */
    public const MODULE_LABELS = [
        'restaurant' => 'Restaurant',
        'housekeeping' => 'Housekeeping',
        'reports' => 'Rapports avancés',
    ];

    /**
     * L'offre de l'hôtel inclut-elle ce module premium ?
     * (le socle réservations/check-in/caisse est toujours disponible)
     */
    public function hasModule(string $key): bool
    {
        $plan = $this->plan ?: config('plans.default', 'starter');

        return in_array($key, config('plans.tiers.'.$plan.'.modules', []), true);
    }

    public static function moduleLabel(string $key): string
    {
        return self::MODULE_LABELS[$key] ?? ucfirst($key);
    }

    /**
     * Prix mensuel d'un plan pour un pays donné (coût de la vie appliqué),
     * arrondi à la centaine.
     */
    public static function priceFor(string $plan, ?string $country = null): int
    {
        $tiers = config('plans.tiers');
        $base = $tiers[$plan]['price'] ?? $tiers[config('plans.default', 'starter')]['price'];

        $code = $country ?: config('plans.default_country', 'BJ');
        $conf = config('plans.countries.'.$code, config('plans.countries.'.config('plans.default_country', 'BJ')));
        $coef = $conf['coef'] ?? 1.0;
        $round = $conf['round'] ?? 100;

        return (int) (round(($base * $coef) / $round) * $round);
    }

    /** Configuration du pays de l'hôtel. */
    public function countryConfig(): array
    {
        $c = config('plans.countries');

        return $c[$this->country] ?? $c[config('plans.default_country', 'BJ')];
    }

    public function countryName(): string
    {
        return $this->countryConfig()['name'];
    }

    /** Devise d'affichage (celle du pays). */
    public function displayCurrency(): string
    {
        return $this->currency ?: $this->countryConfig()['currency'];
    }

    /** Prix mensuel du plan courant, ajusté au pays. */
    public function monthlyPrice(): int
    {
        return self::priceFor(
            $this->plan ?: config('plans.default', 'starter'),
            $this->country ?: config('plans.default_country', 'BJ')
        );
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
     * Historique des abonnements souscrits par l'hôtel.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->latest('starts_at');
    }

    /** Total réellement encaissé (hors périodes d'essai gratuit). */
    public function totalPaid(): float
    {
        return (float) $this->subscriptions()->sum('amount');
    }

    /** Nombre de renouvellements (réabonnements). */
    public function renewalsCount(): int
    {
        return $this->subscriptions()->where('is_renewal', true)->count();
    }

    /** L'hôtel s'est-il déjà réabonné ? */
    public function hasRenewed(): bool
    {
        return $this->renewalsCount() > 0;
    }

    /**
     * Enregistre une période d'abonnement dans l'historique.
     */
    public function recordSubscription(array $attrs = []): Subscription
    {
        return $this->subscriptions()->create(array_merge([
            'plan' => $this->plan ?: config('plans.default', 'starter'),
            'amount' => 0,
            'currency' => $this->currency ?: 'CFA',
            'status' => 'active',
            'is_renewal' => false,
            'starts_at' => now(),
            'ends_at' => $this->subscription_ends_at,
            'created_by' => auth()->id(),
        ], $attrs));
    }

    // ───────────────────────── Changement de formule ─────────────────────────

    /** Nombre de jours restants sur l'abonnement en cours (0 si expiré). */
    public function remainingDays(): int
    {
        if (! $this->subscription_ends_at || $this->subscription_ends_at->isPast()) {
            return 0;
        }

        return (int) ceil(now()->floatDiffInDays($this->subscription_ends_at));
    }

    /**
     * Valeur (monétaire) des jours non consommés du plan actuel, au tarif
     * journalier du plan courant. Sert d'avoir lors d'une montée en gamme.
     */
    public function prorationCredit(): int
    {
        if (! $this->hasActiveAccess()) {
            return 0;
        }

        $dailyRate = $this->monthlyPrice() / 30;

        return (int) round($this->remainingDays() * $dailyRate);
    }

    /**
     * Nature d'un passage vers $newPlan :
     *  - reactivation : abonnement expiré/suspendu (paiement plein)
     *  - renewal      : même formule, actif (prolongation)
     *  - upgrade      : formule plus chère, actif (immédiat, au prorata)
     *  - downgrade    : formule moins chère, actif (programmé en fin de cycle)
     */
    public function changeType(string $newPlan): string
    {
        if (! $this->hasActiveAccess()) {
            return 'reactivation';
        }
        if ($newPlan === $this->plan) {
            return 'renewal';
        }

        $newPrice = self::priceFor($newPlan, $this->country);
        $curPrice = $this->monthlyPrice();

        return $newPrice > $curPrice ? 'upgrade' : 'downgrade';
    }

    /** Programme une descente en gamme à la fin du cycle payé (sans paiement). */
    public function scheduleDowngrade(string $newPlan): void
    {
        $this->update([
            'pending_plan' => $newPlan,
            'pending_plan_effective_at' => $this->subscription_ends_at,
        ]);
    }

    /** Annule un changement de formule programmé. */
    public function cancelScheduledChange(): void
    {
        $this->update(['pending_plan' => null, 'pending_plan_effective_at' => null]);
    }

    /** Applique un downgrade programmé si sa date d'effet est atteinte. */
    public function applyDuePendingPlan(): void
    {
        if (! $this->pending_plan) {
            return;
        }
        if ($this->pending_plan_effective_at && $this->pending_plan_effective_at->isFuture()) {
            return;
        }
        if (! array_key_exists($this->pending_plan, config('plans.tiers'))) {
            $this->cancelScheduledChange();

            return;
        }

        $this->update([
            'plan' => $this->pending_plan,
            'room_limit' => config('plans.tiers.'.$this->pending_plan.'.room_limit'),
            'pending_plan' => null,
            'pending_plan_effective_at' => null,
        ]);
    }

    /**
     * Prolonge l'abonnement de N mois et enregistre la période dans l'historique.
     * Repart de la date de fin si elle est future (renouvellement), ou de maintenant
     * si $resetFromNow (montée en gamme au prorata : nouveau cycle immédiat).
     * Réactive l'accès (is_active) et met éventuellement à jour le palier.
     */
    public function applyRenewal(int $months, float $amount, ?string $plan = null, array $extra = [], bool $resetFromNow = false): Subscription
    {
        $months = max(1, $months);

        $start = (! $resetFromNow && $this->subscription_ends_at && $this->subscription_ends_at->isFuture())
            ? $this->subscription_ends_at->copy()
            : now();
        $newEnd = $start->copy()->addMonths($months);

        $updates = [
            'subscription_ends_at' => $newEnd,
            'is_active' => true,
            'suspension_reason' => null,
            // Tout paiement effectif annule un changement de formule programmé.
            'pending_plan' => null,
            'pending_plan_effective_at' => null,
        ];
        if ($plan && array_key_exists($plan, config('plans.tiers'))) {
            $updates['plan'] = $plan;
            $updates['room_limit'] = config('plans.tiers.'.$plan.'.room_limit');
        }
        $this->update($updates);

        return $this->recordSubscription(array_merge([
            'plan' => $plan ?: $this->plan,
            'status' => 'active',
            'is_renewal' => true,
            'amount' => $amount,
            'starts_at' => $start,
            'ends_at' => $newEnd,
        ], $extra));
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

    /** Thème par défaut de l'établissement : light | dark | system. */
    public function themeMode(): string
    {
        return in_array($this->theme_mode, ['light', 'dark', 'system'], true) ? $this->theme_mode : 'light';
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
