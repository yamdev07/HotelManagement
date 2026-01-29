<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'address',
        'email',
        'job',
        'birthdate',
        'user_id',
        'gender',
        'phone',
        'identification_type',
        'identification_number',
        'nationality',
        'company',
        'notes',
        'preferences',
        'is_active',
        'last_visit',
        'loyalty_points',
        'special_requests',
        'payment_preferences'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'last_visit' => 'datetime',
        'is_active' => 'boolean',
        'loyalty_points' => 'integer',
        'preferences' => 'array',
        'special_requests' => 'array',
        'payment_preferences' => 'array'
    ];

    protected $appends = [
        'age',
        'formatted_birthdate',
        'formatted_last_visit',
        'total_spent',
        'total_reservations',
        'active_reservations_count',
        'customer_type',
        'loyalty_tier',
        'avatar_url'
    ];

    /**
     * Configuration du logging d'activité
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => 'a créé un client',
                    'updated' => 'a modifié un client',
                    'deleted' => 'a supprimé un client',
                    'restored' => 'a restauré un client',
                    default => "a {$eventName} un client",
                };
            });
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Transaction actuelle (en cours)
     */
    public function currentTransaction()
    {
        return $this->hasOne(Transaction::class)
            ->where('status', 'active')
            ->where('check_in', '<=', now())
            ->where('check_out', '>=', now())
            ->latest();
    }

    /**
     * Transactions actives (en cours)
     */
    public function activeTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->where('status', 'active')
            ->where('check_out', '>=', now());
    }

    /**
     * Transactions à venir
     */
    public function upcomingTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->whereIn('status', ['reservation', 'active'])
            ->where('check_in', '>', now());
    }

    /**
     * Transactions passées (terminées)
     */
    public function pastTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->where('status', 'completed')
            ->orderBy('check_out', 'desc');
    }

    /**
     * Transactions annulées
     */
    public function cancelledTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->where('status', 'cancelled');
    }

    /**
     * Paiements du client
     */
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Transaction::class,
            'customer_id',
            'transaction_id',
            'id',
            'id'
        );
    }

    /**
     * Factures du client
     */
    public function invoices()
    {
        return $this->hasManyThrough(
            Invoice::class,
            Transaction::class,
            'customer_id',
            'transaction_id',
            'id',
            'id'
        );
    }

    /**
     * Accesseur: Âge du client
     */
    public function getAgeAttribute()
    {
        if (!$this->birthdate) {
            return null;
        }
        
        return Carbon::parse($this->birthdate)->age;
    }

    /**
     * Accesseur: Date de naissance formatée
     */
    public function getFormattedBirthdateAttribute()
    {
        if (!$this->birthdate) {
            return 'Non renseignée';
        }
        
        return $this->birthdate->format('d/m/Y');
    }

    /**
     * Accesseur: Dernière visite formatée
     */
    public function getFormattedLastVisitAttribute()
    {
        if (!$this->last_visit) {
            return 'Jamais';
        }
        
        return $this->last_visit->format('d/m/Y H:i');
    }

    /**
     * Accesseur: Total dépensé
     */
    public function getTotalSpentAttribute()
    {
        return $this->transactions()
            ->whereIn('status', ['completed', 'active'])
            ->sum('total_price');
    }

    /**
     * Accesseur: Nombre total de réservations
     */
    public function getTotalReservationsAttribute()
    {
        return $this->transactions()->count();
    }

    /**
     * Accesseur: Nombre de réservations actives
     */
    public function getActiveReservationsCountAttribute()
    {
        return $this->activeTransactions()->count();
    }

    /**
     * Accesseur: Type de client
     */
    public function getCustomerTypeAttribute()
    {
        $totalSpent = $this->total_spent;
        $totalReservations = $this->total_reservations;
        
        if ($totalReservations == 0) {
            return 'Nouveau';
        }
        
        if ($totalSpent > 1000000) {
            return 'VIP';
        } elseif ($totalSpent > 500000) {
            return 'Fidèle';
        } elseif ($totalReservations > 5) {
            return 'Régulier';
        } else {
            return 'Occasionnel';
        }
    }

    /**
     * Accesseur: Niveau de fidélité
     */
    public function getLoyaltyTierAttribute()
    {
        $points = $this->loyalty_points ?? 0;
        
        if ($points >= 10000) {
            return 'Platine';
        } elseif ($points >= 5000) {
            return 'Or';
        } elseif ($points >= 1000) {
            return 'Argent';
        } else {
            return 'Bronze';
        }
    }

    /**
     * Accesseur: Couleur du niveau de fidélité
     */
    public function getLoyaltyTierColorAttribute()
    {
        $tier = $this->loyalty_tier;
        
        return match($tier) {
            'Platine' => 'info',
            'Or' => 'warning',
            'Argent' => 'secondary',
            'Bronze' => 'danger',
            default => 'light'
        };
    }

    /**
     * Accesseur: URL de l'avatar
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->user && $this->user->avatar) {
            return $this->user->getAvatar();
        }
        
        // Générer un avatar basé sur le nom
        $name = urlencode($this->name);
        $colors = ['4ecdc4', '45b7d1', '96c93d', 'a363d9', 'e74a3b'];
        $color = $colors[array_rand($colors)];
        
        return "https://ui-avatars.com/api/?name={$name}&background={$color}&color=fff&size=150&bold=true";
    }

    /**
     * Scope: Clients actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Clients inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope: Clients VIP
     */
    public function scopeVip($query)
    {
        return $query->whereHas('transactions', function($q) {
            $q->whereIn('status', ['completed', 'active'])
              ->groupBy('customer_id')
              ->havingRaw('SUM(total_price) > ?', [1000000]);
        });
    }

    /**
     * Scope: Clients réguliers
     */
    public function scopeRegular($query)
    {
        return $query->whereHas('transactions', function($q) {
            $q->whereIn('status', ['completed', 'active'])
              ->groupBy('customer_id')
              ->havingRaw('COUNT(*) > ?', [5]);
        });
    }

    /**
     * Scope: Clients avec réservation actuelle
     */
    public function scopeWithActiveBooking($query)
    {
        return $query->whereHas('transactions', function($q) {
            $q->where('status', 'active')
              ->where('check_in', '<=', now())
              ->where('check_out', '>=', now());
        });
    }

    /**
     * Scope: Clients par type de client
     */
    public function scopeByType($query, $type)
    {
        return match($type) {
            'vip' => $query->vip(),
            'regular' => $query->regular(),
            'new' => $query->whereDoesntHave('transactions'),
            default => $query
        };
    }

    /**
     * Vérifier si le client est VIP
     */
    public function isVip()
    {
        return $this->customer_type === 'VIP';
    }

    /**
     * Vérifier si le client est régulier
     */
    public function isRegular()
    {
        return $this->customer_type === 'Régulier';
    }

    /**
     * Enregistrer une visite
     */
    public function recordVisit()
    {
        $this->last_visit = now();
        $this->saveQuietly();
        
        // Logger la visite
        activity()
            ->performedOn($this)
            ->withProperties([
                'visit_date' => now(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('a visité son profil');
    }

    /**
     * Ajouter des points de fidélité
     */
    public function addLoyaltyPoints($points, $reason = null)
    {
        $this->loyalty_points += $points;
        $this->saveQuietly();
        
        // Logger l'ajout de points
        activity()
            ->performedOn($this)
            ->withProperties([
                'points_added' => $points,
                'total_points' => $this->loyalty_points,
                'reason' => $reason,
            ])
            ->log('a reçu des points de fidélité');
        
        return $this;
    }

    /**
     * Déduire des points de fidélité
     */
    public function deductLoyaltyPoints($points, $reason = null)
    {
        $this->loyalty_points = max(0, $this->loyalty_points - $points);
        $this->saveQuietly();
        
        // Logger la déduction
        activity()
            ->performedOn($this)
            ->withProperties([
                'points_deducted' => $points,
                'total_points' => $this->loyalty_points,
                'reason' => $reason,
            ])
            ->log('a utilisé des points de fidélité');
        
        return $this;
    }

    /**
     * Obtenir le taux d'occupation du client
     */
    public function getOccupancyRate()
    {
        $totalNights = 0;
        $totalPossibleNights = 0;
        
        foreach ($this->transactions()->where('status', 'completed')->get() as $transaction) {
            $totalNights += $transaction->check_in->diffInDays($transaction->check_out);
        }
        
        // Calcul approximatif - on considère qu'un client idéal occupe 30 nuits par an
        $yearsAsCustomer = $this->created_at->diffInYears(now()) ?: 1;
        $totalPossibleNights = $yearsAsCustomer * 30;
        
        return $totalPossibleNights > 0 ? round(($totalNights / $totalPossibleNights) * 100, 1) : 0;
    }

    /**
     * Obtenir les préférences du client
     */
    public function getPreference($key, $default = null)
    {
        $preferences = $this->preferences ?? [];
        return $preferences[$key] ?? $default;
    }

    /**
     * Définir une préférence
     */
    public function setPreference($key, $value)
    {
        $preferences = $this->preferences ?? [];
        $preferences[$key] = $value;
        $this->preferences = $preferences;
        $this->save();
        
        return $this;
    }

    /**
     * Obtenir les demandes spéciales
     */
    public function getSpecialRequests()
    {
        return $this->special_requests ?? [];
    }

    /**
     * Ajouter une demande spéciale
     */
    public function addSpecialRequest($request)
    {
        $requests = $this->special_requests ?? [];
        $requests[] = [
            'request' => $request,
            'date' => now()->format('Y-m-d H:i:s'),
        ];
        $this->special_requests = $requests;
        $this->save();
        
        return $this;
    }

    /**
     * Obtenir les statistiques du client
     */
    public function getStatsAttribute()
    {
        $transactions = $this->transactions()->get();
        $completedTransactions = $transactions->where('status', 'completed');
        
        $stats = [
            'total_transactions' => $transactions->count(),
            'completed_transactions' => $completedTransactions->count(),
            'active_transactions' => $transactions->where('status', 'active')->count(),
            'cancelled_transactions' => $transactions->where('status', 'cancelled')->count(),
            'total_nights' => $completedTransactions->sum(function($t) {
                return $t->check_in->diffInDays($t->check_out);
            }),
            'total_spent' => $completedTransactions->sum('total_price'),
            'average_spend_per_night' => $completedTransactions->avg(function($t) {
                $nights = $t->check_in->diffInDays($t->check_out);
                return $nights > 0 ? $t->total_price / $nights : 0;
            }),
            'last_transaction' => $transactions->sortByDesc('check_in')->first(),
            'favorite_room_type' => $transactions->groupBy('room.type_id')->sortDesc()->keys()->first(),
        ];
        
        return $stats;
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (!$customer->is_active) {
                $customer->is_active = true;
            }
        });

        static::created(function ($customer) {
            // Logger la création
            activity()
                ->causedBy(auth()->user())
                ->performedOn($customer)
                ->withProperties([
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone ?? 'Non renseigné',
                ])
                ->log('a créé un nouveau client');
        });

        static::updating(function ($customer) {
            // Vérifier les changements importants
            if ($customer->isDirty('email')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($customer)
                    ->withProperties([
                        'old_email' => $customer->getOriginal('email'),
                        'new_email' => $customer->email,
                    ])
                    ->log('a changé l\'email du client');
            }
            
            if ($customer->isDirty('is_active')) {
                $action = $customer->is_active ? 'activé' : 'désactivé';
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($customer)
                    ->withProperties([
                        'old_status' => $customer->getOriginal('is_active'),
                        'new_status' => $customer->is_active,
                    ])
                    ->log("a {$action} le client");
            }
        });
    }

    /**
     * Obtenir les activités du client
     */
    public function customerActivities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'subject_id')
            ->where('subject_type', self::class);
    }

    /**
     * Obtenir l'historique complet du client
     */
    public function getHistoryAttribute()
    {
        $activities = $this->customerActivities()->orderBy('created_at', 'desc')->get();
        $transactions = $this->transactions()->with('room', 'payments')->orderBy('check_in', 'desc')->get();
        $payments = $this->payments()->with('transaction')->orderBy('payment_date', 'desc')->get();
        
        return [
            'activities' => $activities,
            'transactions' => $transactions,
            'payments' => $payments,
            'stats' => $this->stats,
        ];
    }

    /**
     * Sauvegarde sans déclencher d'événements
     */
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    /**
     * Obtenir le résumé du client
     */
    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?? 'Non renseigné',
            'customer_type' => $this->customer_type,
            'loyalty_tier' => $this->loyalty_tier,
            'total_spent' => number_format($this->total_spent, 0, ',', ' ') . ' CFA',
            'total_reservations' => $this->total_reservations,
            'active_reservations' => $this->active_reservations_count,
            'last_visit' => $this->formatted_last_visit,
            'occupancy_rate' => $this->getOccupancyRate() . '%',
            'is_active' => $this->is_active ? 'Actif' : 'Inactif',
        ];
    }
}