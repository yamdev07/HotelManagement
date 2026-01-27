<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'email_verified_at' => 'datetime',
    ];

    /**
     * Retourne le chemin complet de l'avatar de l'utilisateur.
     * Si aucun avatar n'est défini ou le fichier est manquant, retourne l'avatar par défaut.
     *
     * @return string
     */
    public function getAvatar(): string
    {
        // Si aucun avatar défini, utiliser l'avatar par défaut
        if (! $this->avatar) {
            return asset('img/default/default-user.jpg');
        }

        // Le fichier est directement dans /public/img/user/
        $fullPath = 'img/user/' . trim($this->avatar, '/');

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

    /**
     * Vérifie si l'utilisateur est un client.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'Customer';
    }

    /**
     * Vérifie si l'utilisateur est un admin ou super-admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['Admin', 'Super']);
    }

    /**
     * Vérifie si l'utilisateur est super-admin.
     */
    public function isSuper(): bool
    {
        return $this->role === 'Super';
    }

    // Après la méthode isSuper(), ajoute :

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

    /**
     * Vérifie si l'utilisateur peut démarrer une session
     */
    public function canStartSession(): bool
    {
        return !$this->activeCashierSession && 
            in_array($this->role, ['Receptionist', 'Admin', 'Super', 'Cashier']);
    }

    /**
     * Vérifie si l'utilisateur est un réceptionniste
     */
    public function isReceptionist(): bool
    {
        return $this->role === 'Receptionist';
    }

    /**
     * Vérifie si l'utilisateur est un caissier
     */
    public function isCashier(): bool
    {
        return $this->role === 'Cashier';
    }

    /**
     * Vérifie si l'utilisateur a une session active
     */
    public function hasActiveSession(): bool
    {
        return $this->activeCashierSession !== null;
    }

    /**
     * Les permissions de l'utilisateur
     */
    public function getPermissionsAttribute(): array
    {
        $permissions = [];
        
        if ($this->isSuper()) {
            $permissions = ['all'];
        } elseif ($this->isAdmin()) {
            $permissions = ['manage_users', 'view_reports', 'manage_settings'];
        } elseif ($this->isReceptionist() || $this->isCashier()) {
            $permissions = ['manage_bookings', 'process_payments', 'view_cashier_dashboard'];
        } elseif ($this->isCustomer()) {
            $permissions = ['view_bookings', 'make_payments'];
        }
        
        return $permissions;
    }
}
