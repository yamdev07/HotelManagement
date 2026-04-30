<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, LogsActivity, Notifiable;

    /**
     * Les attributs assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'avatar',
        'password',
        'random_key',
    ];

    /**
     * Les attributs à cacher dans les tableaux.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs à caster en types natifs.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at'  => 'datetime',
        'last_login_at'      => 'datetime',
        'last_login_attempt' => 'datetime',
        'is_active'          => 'boolean',
        'login_attempts'     => 'integer',
    ];

    public function getRoleEnumAttribute(): ?UserRole
    {
        return UserRole::tryFrom($this->attributes['role'] ?? '');
    }

    /**
     * Configuration du logging d'activité
     *
     * @return LogOptions
     */

    /**
     * Retourne le chemin complet de l'avatar de l'utilisateur.
     * Si aucun avatar n'est défini ou le fichier est manquant, retourne l'avatar par défaut.
     */
    public function getAvatar(): string
    {
        // Si aucun avatar défini, utiliser l'avatar par défaut
        if (! $this->avatar) {
            return asset('img/default/default-user.jpg');
        }

        // Le fichier est directement dans /public/img/user/
        $fullPath = 'img/user/'.trim($this->avatar, '/');

        if (file_exists(public_path($fullPath))) {
            return asset($fullPath);
        }

        return asset('img/default/default-user.jpg');
    }

    /**
     * Relation One-to-One avec Customer.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function isCustomer(): bool
    {
        return $this->roleEnum === UserRole::Customer;
    }

    public function isAdmin(): bool
    {
        return in_array($this->roleEnum, [UserRole::Admin, UserRole::Super]);
    }

    public function isSuper(): bool
    {
        return $this->roleEnum === UserRole::Super;
    }

    /**
     * Relation avec les sessions de caisse
     */
    public function cashierSessions()
    {
        return $this->hasMany(CashierSession::class);
    }

    /**
     * Retourne la session active de l'utilisateur
     */
    public function getActiveCashierSessionAttribute()
    {
        return $this->cashierSessions()
            ->where('status', 'active')
            ->first();
    }

    public function canStartSession(): bool
    {
        return ! $this->activeCashierSession && $this->roleEnum?->canProcessPayments();
    }

    public function isReceptionist(): bool
    {
        return $this->roleEnum === UserRole::Receptionist;
    }

    public function isCashier(): bool
    {
        return $this->roleEnum === UserRole::Cashier;
    }

    /**
     * Vérifie si l'utilisateur a une session active
     */
    public function hasActiveSession(): bool
    {
        return $this->activeCashierSession !== null;
    }

    public function getPermissionsAttribute(): array
    {
        return match (true) {
            $this->isSuper()                        => ['all'],
            $this->isAdmin()                        => ['manage_users', 'view_reports', 'manage_settings'],
            $this->isReceptionist() || $this->isCashier() => ['manage_bookings', 'process_payments', 'view_cashier_dashboard'],
            $this->isCustomer()                     => ['view_bookings', 'make_payments'],
            default                                 => [],
        };
    }

    /**
     * Relation avec les activités (logs) où l'utilisateur est l'auteur
     */
    public function activities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'causer_id')
            ->where('causer_type', self::class);
    }

    /**
     * Activités où cet utilisateur est la cible
     */
    public function targetedActivities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'subject_id')
            ->where('subject_type', self::class);
    }

    /**
     * Toutes les activités liées à cet utilisateur
     */
    public function allActivities()
    {
        return \Spatie\Activitylog\Models\Activity::where(function ($query) {
            $query->where('causer_type', self::class)
                ->where('causer_id', $this->id);
        })
            ->orWhere(function ($query) {
                $query->where('subject_type', self::class)
                    ->where('subject_id', $this->id);
            })
            ->orderBy('created_at', 'desc');
    }

    /**
     * Enregistrer la connexion de l'utilisateur
     */
    public function logLogin()
    {
        activity()
            ->causedBy($this)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'method' => 'web',
            ])
            ->log('Connexion réussie');

        $this->last_login_at = now();
        $this->save();
    }

    /**
     * Enregistrer la déconnexion de l'utilisateur
     */
    public function logLogout()
    {
        activity()
            ->causedBy($this)
            ->withProperties([
                'ip' => request()->ip(),
            ])
            ->log('Déconnexion');
    }

    /**
     * Enregistrer la consultation du profil
     */
    public function logProfileView($viewer = null)
    {
        activity()
            ->causedBy($viewer ?? $this)
            ->performedOn($this)
            ->withProperties([
                'ip' => request()->ip(),
                'url' => request()->fullUrl(),
            ])
            ->log('a consulté le profil');
    }

    /**
     * Scope pour les utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, UserRole|string $role): mixed
    {
        $value = $role instanceof UserRole ? $role->value : $role;
        return $query->where('role', $value);
    }

    public function scopeAdmins($query): mixed
    {
        return $query->whereIn('role', [UserRole::Admin->value, UserRole::Super->value]);
    }

    public function scopeStaff($query): mixed
    {
        return $query->whereIn('role', UserRole::staffValues());
    }

    public function getFormattedRoleAttribute(): string
    {
        return $this->roleEnum?->label() ?? (string) $this->role;
    }

    public function getRoleIconAttribute(): string
    {
        return $this->roleEnum?->icon() ?? 'fas fa-user';
    }

    public function getRoleColorAttribute(): string
    {
        return $this->roleEnum?->color() ?? 'secondary';
    }

    public function canEditUser(User $targetUser): bool
    {
        if ($this->id === $targetUser->id) return true;
        if ($this->isSuper()) return true;
        if ($this->isAdmin() && ! $targetUser->isSuper()) return true;

        return false;
    }

    public function canDeleteUser(User $targetUser): bool
    {
        if ($this->id === $targetUser->id) return false;

        return $this->isSuper() && ! $targetUser->isSuper();
    }

    /**
     * Obtenir les statistiques d'activité
     */
    public function getActivityStatsAttribute(): array
    {
        $totalActivities = $this->allActivities()->count();
        $actionsDone = $this->activities()->count();
        $modificationsReceived = $this->targetedActivities()->count();

        $lastActivity = $this->allActivities()->first();

        return [
            'total' => $totalActivities,
            'actions_done' => $actionsDone,
            'modifications_received' => $modificationsReceived,
            'last_activity' => $lastActivity ? $lastActivity->created_at : null,
            'last_activity_description' => $lastActivity ? $lastActivity->description : 'Aucune activité',
        ];
    }

    /**
     * Obtenir les activités récentes (pour le dashboard)
     */
    public function getRecentActivities($limit = 10)
    {
        return $this->allActivities()->limit($limit)->get();
    }

    /**
     * Générer un avatar aléatoire via UI Avatars
     */
    public function generateRandomAvatar(): string
    {
        $colors = ['4e73df', '1cc88a', '36b9cc', 'f6c23e', 'e74a3b', '858796'];
        $color = $colors[array_rand($colors)];

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).
               '&background='.$color.
               '&color=fff&size=150&bold=true';
    }

    /**
     * Créer un snapshot pour le journal d'activité
     * Cela évite "Objet supprimé" quand l'utilisateur est supprimé
     */
    public function getLogSnapshot(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'formatted_role' => $this->formatted_role,
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at?->format('d/m/Y H:i'),
        ];
    }

    /**
     * Récupérer l'activité même si l'utilisateur est supprimé
     * (Override pour utiliser le snapshot)
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => 'a créé l\'utilisateur :name',
                    'updated' => 'a modifié l\'utilisateur :name',
                    'deleted' => 'a supprimé l\'utilisateur :name (:email)',
                    'restored' => 'a restauré l\'utilisateur :name',
                    default => "a {$eventName} l'utilisateur :name",
                };
            });
    }

    public function hasDefaultAvatar(): bool
    {
        return ! $this->avatar || str_contains($this->avatar, 'default-user.jpg');
    }

    /**
     * Renvoie la valeur string du rôle (compatible avec les comparaisons dans les vues).
     */
    public function getRoleValueAttribute(): string
    {
        return (string) $this->role;
    }
}
