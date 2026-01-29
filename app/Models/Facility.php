<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Facility extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'detail',
        'icon',
        'category',
        'is_active',
        'sort_order',
        'description',
        'additional_info'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'additional_info' => 'array'
    ];

    protected $appends = [
        'formatted_detail',
        'icon_class',
        'category_label',
        'rooms_count',
        'is_popular'
    ];

    // Catégories d'équipements
    const CATEGORY_GENERAL = 'general';
    const CATEGORY_BATHROOM = 'bathroom';
    const CATEGORY_BEDROOM = 'bedroom';
    const CATEGORY_TECHNOLOGY = 'technology';
    const CATEGORY_KITCHEN = 'kitchen';
    const CATEGORY_ENTERTAINMENT = 'entertainment';
    const CATEGORY_ACCESSIBILITY = 'accessibility';
    const CATEGORY_SAFETY = 'safety';
    const CATEGORY_SERVICES = 'services';

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
                    'created' => 'a créé un équipement',
                    'updated' => 'a modifié un équipement',
                    'deleted' => 'a supprimé un équipement',
                    'restored' => 'a restauré un équipement',
                    default => "a {$eventName} un équipement",
                };
            });
    }

    /**
     * Relation avec les chambres
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'facility_room')
            ->withTimestamps()
            ->withPivot('quantity', 'notes')
            ->using(FacilityRoom::class);
    }

    /**
     * Relation avec les types de chambres
     */
    public function roomTypes()
    {
        return $this->belongsToMany(Type::class, 'facility_type')
            ->withTimestamps();
    }

    /**
     * Chambres actives (non supprimées)
     */
    public function activeRooms()
    {
        return $this->rooms()->where('is_active', true);
    }

    /**
     * Accesseur: Détail formaté
     */
    public function getFormattedDetailAttribute()
    {
        if (!$this->detail) {
            return $this->name;
        }
        
        return $this->detail;
    }

    /**
     * Accesseur: Classe CSS pour l'icône
     */
    public function getIconClassAttribute()
    {
        if (!$this->icon) {
            // Icône par défaut selon la catégorie
            return match($this->category) {
                self::CATEGORY_BATHROOM => 'fa-bath',
                self::CATEGORY_BEDROOM => 'fa-bed',
                self::CATEGORY_TECHNOLOGY => 'fa-wifi',
                self::CATEGORY_KITCHEN => 'fa-utensils',
                self::CATEGORY_ENTERTAINMENT => 'fa-tv',
                self::CATEGORY_ACCESSIBILITY => 'fa-wheelchair',
                self::CATEGORY_SAFETY => 'fa-shield-alt',
                self::CATEGORY_SERVICES => 'fa-concierge-bell',
                default => 'fa-check-circle'
            };
        }
        
        // Vérifier si c'est déjà une classe FontAwesome
        if (str_contains($this->icon, 'fa-')) {
            return $this->icon;
        }
        
        // Sinon, retourner l'icône telle quelle
        return $this->icon;
    }

    /**
     * Accesseur: Label de catégorie
     */
    public function getCategoryLabelAttribute()
    {
        $categories = self::getCategoryOptions();
        return $categories[$this->category] ?? 'Général';
    }

    /**
     * Accesseur: Nombre de chambres avec cet équipement
     */
    public function getRoomsCountAttribute()
    {
        return $this->rooms()->count();
    }

    /**
     * Accesseur: Est populaire (utilisé dans plus de 50% des chambres)
     */
    public function getIsPopularAttribute()
    {
        $totalRooms = Room::count();
        if ($totalRooms === 0) {
            return false;
        }
        
        $roomsWithFacility = $this->rooms()->count();
        return ($roomsWithFacility / $totalRooms) > 0.5;
    }

    /**
     * Obtenir les options de catégories
     */
    public static function getCategoryOptions()
    {
        return [
            self::CATEGORY_GENERAL => 'Général',
            self::CATEGORY_BATHROOM => 'Salle de bain',
            self::CATEGORY_BEDROOM => 'Chambre',
            self::CATEGORY_TECHNOLOGY => 'Technologie',
            self::CATEGORY_KITCHEN => 'Cuisine',
            self::CATEGORY_ENTERTAINMENT => 'Divertissement',
            self::CATEGORY_ACCESSIBILITY => 'Accessibilité',
            self::CATEGORY_SAFETY => 'Sécurité',
            self::CATEGORY_SERVICES => 'Services',
        ];
    }

    /**
     * Obtenir les icônes par catégorie
     */
    public static function getCategoryIcons()
    {
        return [
            self::CATEGORY_GENERAL => 'fa-check-circle',
            self::CATEGORY_BATHROOM => 'fa-bath',
            self::CATEGORY_BEDROOM => 'fa-bed',
            self::CATEGORY_TECHNOLOGY => 'fa-wifi',
            self::CATEGORY_KITCHEN => 'fa-utensils',
            self::CATEGORY_ENTERTAINMENT => 'fa-tv',
            self::CATEGORY_ACCESSIBILITY => 'fa-wheelchair',
            self::CATEGORY_SAFETY => 'fa-shield-alt',
            self::CATEGORY_SERVICES => 'fa-concierge-bell',
        ];
    }

    /**
     * Scope: Équipements actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Équipements par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope: Équipements populaires
     */
    public function scopePopular($query)
    {
        return $query->whereHas('rooms', function($q) {
            $q->havingRaw('COUNT(*) > ?', [Room::count() * 0.5]);
        });
    }

    /**
     * Scope: Équipements avec une chambre spécifique
     */
    public function scopeForRoom($query, $roomId)
    {
        return $query->whereHas('rooms', function($q) use ($roomId) {
            $q->where('rooms.id', $roomId);
        });
    }

    /**
     * Scope: Équipements sans chambre spécifique
     */
    public function scopeWithoutRoom($query, $roomId)
    {
        return $query->whereDoesntHave('rooms', function($q) use ($roomId) {
            $q->where('rooms.id', $roomId);
        });
    }

    /**
     * Vérifier si l'équipement est actif
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Activer l'équipement
     */
    public function activate()
    {
        $this->update(['is_active' => true]);
        
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this)
            ->log('a activé l\'équipement');
            
        return $this;
    }

    /**
     * Désactiver l'équipement
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
        
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this)
            ->log('a désactivé l\'équipement');
            
        return $this;
    }

    /**
     * Ajouter à une chambre
     */
    public function addToRoom($roomId, $quantity = 1, $notes = null)
    {
        $room = Room::findOrFail($roomId);
        
        // Vérifier si déjà associé
        if ($this->rooms()->where('room_id', $roomId)->exists()) {
            // Mettre à jour la quantité
            $this->rooms()->updateExistingPivot($roomId, [
                'quantity' => $quantity,
                'notes' => $notes
            ]);
            
            activity()
                ->causedBy(auth()->user())
                ->performedOn($this)
                ->withProperties([
                    'room_id' => $roomId,
                    'room_number' => $room->number,
                    'quantity' => $quantity,
                    'notes' => $notes,
                    'action' => 'updated'
                ])
                ->log('a mis à jour l\'équipement dans une chambre');
        } else {
            // Ajouter la relation
            $this->rooms()->attach($roomId, [
                'quantity' => $quantity,
                'notes' => $notes,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            activity()
                ->causedBy(auth()->user())
                ->performedOn($this)
                ->withProperties([
                    'room_id' => $roomId,
                    'room_number' => $room->number,
                    'quantity' => $quantity,
                    'notes' => $notes,
                    'action' => 'added'
                ])
                ->log('a ajouté l\'équipement à une chambre');
        }
        
        return $this;
    }

    /**
     * Retirer d'une chambre
     */
    public function removeFromRoom($roomId)
    {
        $room = Room::findOrFail($roomId);
        
        if ($this->rooms()->where('room_id', $roomId)->exists()) {
            $this->rooms()->detach($roomId);
            
            activity()
                ->causedBy(auth()->user())
                ->performedOn($this)
                ->withProperties([
                    'room_id' => $roomId,
                    'room_number' => $room->number,
                    'action' => 'removed'
                ])
                ->log('a retiré l\'équipement d\'une chambre');
        }
        
        return $this;
    }

    /**
     * Obtenir les statistiques de l'équipement
     */
    public function getStatsAttribute()
    {
        $rooms = $this->rooms()->get();
        $roomTypes = $this->roomTypes()->get();
        
        return [
            'total_rooms' => $rooms->count(),
            'total_room_types' => $roomTypes->count(),
            'average_quantity' => $rooms->avg('pivot.quantity') ?? 1,
            'rooms_by_type' => $rooms->groupBy('type_id')->map->count(),
            'popular_rooms' => $rooms->take(5)->pluck('number')->toArray(),
            'occupancy_rate' => Room::count() > 0 ? 
                round(($rooms->count() / Room::count()) * 100, 1) . '%' : '0%',
            'last_updated' => $this->updated_at->diffForHumans(),
        ];
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($facility) {
            if (!isset($facility->is_active)) {
                $facility->is_active = true;
            }
            
            if (!isset($facility->sort_order)) {
                $maxOrder = self::max('sort_order') ?? 0;
                $facility->sort_order = $maxOrder + 1;
            }
            
            if (!isset($facility->category)) {
                $facility->category = self::CATEGORY_GENERAL;
            }
        });

        static::created(function ($facility) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($facility)
                ->withProperties([
                    'name' => $facility->name,
                    'category' => $facility->category_label,
                    'icon' => $facility->icon,
                ])
                ->log('a créé un nouvel équipement');
        });

        static::updating(function ($facility) {
            // Vérifier les changements importants
            if ($facility->isDirty('name')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($facility)
                    ->withProperties([
                        'old_name' => $facility->getOriginal('name'),
                        'new_name' => $facility->name,
                    ])
                    ->log('a renommé l\'équipement');
            }
            
            if ($facility->isDirty('is_active')) {
                $action = $facility->is_active ? 'activé' : 'désactivé';
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($facility)
                    ->withProperties([
                        'old_status' => $facility->getOriginal('is_active'),
                        'new_status' => $facility->is_active,
                    ])
                    ->log("a {$action} l\'équipement");
            }
        });
    }

    /**
     * Obtenir les activités de l'équipement
     */
    public function facilityActivities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'subject_id')
            ->where('subject_type', self::class);
    }

    /**
     * Obtenir l'historique complet de l'équipement
     */
    public function getHistoryAttribute()
    {
        $activities = $this->facilityActivities()->orderBy('created_at', 'desc')->get();
        $rooms = $this->rooms()->with('type')->get();
        $roomTypes = $this->roomTypes()->get();
        
        return [
            'activities' => $activities,
            'rooms' => $rooms,
            'room_types' => $roomTypes,
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
     * Obtenir le résumé de l'équipement
     */
    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'detail' => $this->formatted_detail,
            'category' => $this->category_label,
            'icon' => $this->icon_class,
            'is_active' => $this->is_active ? 'Actif' : 'Inactif',
            'rooms_count' => $this->rooms_count,
            'is_popular' => $this->is_popular ? 'Oui' : 'Non',
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }

    /**
     * Reorganiser l'ordre des équipements
     */
    public static function reorder(array $order)
    {
        foreach ($order as $position => $id) {
            self::where('id', $id)->update(['sort_order' => $position + 1]);
        }
        
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['order' => $order])
            ->log('a réorganisé l\'ordre des équipements');
        
        return true;
    }
}

// Classe pour la table pivot si vous voulez plus de contrôle
class FacilityRoom extends \Illuminate\Database\Eloquent\Relations\Pivot
{
    protected $table = 'facility_room';
    
    protected $casts = [
        'quantity' => 'integer',
    ];
    
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}