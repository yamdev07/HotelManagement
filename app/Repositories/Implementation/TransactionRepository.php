<?php

namespace App\Repositories\Implementation;

use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function store($request, Customer $customer, Room $room)
    {
        return Transaction::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'status' => 'reservation', // Nouveau statut par défaut
            'person_count' => $request->person_count ?? 1,
        ]);
    }

    /**
     * Récupérer les transactions ACTIVES (pas encore terminées ou annulées)
     */
    public function getTransaction($request)
    {
        return Transaction::with(['user', 'room', 'room.type', 'customer', 'payments'])
            ->where(function($query) {
                // Seulement les statuts actifs : réservation et actif
                $query->whereIn('status', ['reservation', 'active'])
                      ->orWhere(function($q) {
                          // Ou les transactions avec check_out futur
                          $q->where('check_out', '>=', Carbon::now())
                            ->whereNotIn('status', ['cancelled', 'no_show', 'completed']);
                      });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('transactions.id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('customers.name', 'like', "%{$search}%")
                          ->orWhere('customers.email', 'like', "%{$search}%")
                          ->orWhere('customers.phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('room', function ($r) use ($search) {
                        $r->where('rooms.number', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->where('check_in', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->where('check_out', '<=', $request->date_to);
            })
            ->orderBy('check_in', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(20)
            ->appends($request->all());
    }

    /**
     * Récupérer les transactions ANCIENNES (terminées, annulées, no show, ou expirées)
     */
    public function getTransactionExpired($request)
    {
        return Transaction::with(['user', 'room', 'room.type', 'customer', 'payments'])
            ->where(function($query) {
                // Transactions avec statuts terminaux
                $query->whereIn('status', ['completed', 'cancelled', 'no_show'])
                      ->orWhere('check_out', '<', Carbon::now()); // Ou dates passées
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('transactions.id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('customers.name', 'like', "%{$search}%")
                          ->orWhere('customers.email', 'like', "%{$search}%")
                          ->orWhere('customers.phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('room', function ($r) use ($search) {
                        $r->where('rooms.number', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('check_in', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('check_out', '<=', $request->date_to);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                if ($request->type === 'cancelled') {
                    $query->where('status', 'cancelled');
                } elseif ($request->type === 'expired') {
                    $query->where('check_out', '<', Carbon::now())
                          ->whereNotIn('status', ['cancelled', 'no_show', 'completed']);
                } elseif ($request->type === 'completed') {
                    $query->where('status', 'completed');
                } elseif ($request->type === 'no_show') {
                    $query->where('status', 'no_show');
                }
            })
            ->orderBy('check_out', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(20)
            ->appends($request->all());
    }

    /**
     * Récupérer les transactions annulées spécifiquement
     */
    public function getCancelledTransactions($request = null)
    {
        return Transaction::with(['user', 'room', 'customer', 'payments'])
            ->where('status', 'cancelled')
            ->when($request && $request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('transactions.id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('customers.name', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('cancelled_at', 'DESC')
            ->paginate(20);
    }

    /**
     * Récupérer les réservations d'un client spécifique
     */
    public function getCustomerTransactions($customerId, $request = null)
    {
        return Transaction::with(['room', 'room.type', 'payments'])
            ->where('customer_id', $customerId)
            ->when($request && $request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('check_in', 'DESC')
            ->paginate(10);
    }

    /**
     * Statistiques des transactions
     */
    public function getStatistics()
    {
        $now = Carbon::now();
        
        return [
            'total' => Transaction::count(),
            'active' => Transaction::whereIn('status', ['reservation', 'active'])
                ->where('check_out', '>=', $now)
                ->count(),
            'reservation' => Transaction::where('status', 'reservation')
                ->where('check_in', '>=', $now)
                ->count(),
            'in_hotel' => Transaction::where('status', 'active')
                ->where('check_in', '<=', $now)
                ->where('check_out', '>=', $now)
                ->count(),
            'cancelled' => Transaction::where('status', 'cancelled')->count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'no_show' => Transaction::where('status', 'no_show')->count(),
            'expired' => Transaction::where('check_out', '<', $now)
                ->whereNotIn('status', ['cancelled', 'no_show', 'completed'])
                ->count(),
            'today_checkins' => Transaction::whereDate('check_in', $now->toDateString())
                ->whereIn('status', ['reservation', 'active'])
                ->count(),
            'today_checkouts' => Transaction::whereDate('check_out', $now->toDateString())
                ->whereIn('status', ['active', 'reservation'])
                ->count(),
        ];
    }

    /**
     * Transactions à venir (pour dashboard)
     */
    public function getUpcomingTransactions($limit = 10)
    {
        return Transaction::with(['customer', 'room'])
            ->whereIn('status', ['reservation', 'active'])
            ->where('check_in', '>=', Carbon::now())
            ->orderBy('check_in', 'ASC')
            ->limit($limit)
            ->get();
    }

    /**
     * Transactions en cours (clients dans l'hôtel)
     */
    public function getCurrentGuests()
    {
        $now = Carbon::now();
        
        return Transaction::with(['customer', 'room', 'room.type'])
            ->where('status', 'active')
            ->where('check_in', '<=', $now)
            ->where('check_out', '>=', $now)
            ->orderBy('check_out', 'ASC')
            ->get();
    }

    /**
     * Transactions à vérifier (check-in/check-out aujourd'hui)
     */
    public function getTodayTransactions()
    {
        $today = Carbon::today();
        
        return [
            'checkins' => Transaction::with(['customer', 'room'])
                ->whereDate('check_in', $today)
                ->where('status', 'reservation')
                ->orderBy('check_in', 'ASC')
                ->get(),
            'checkouts' => Transaction::with(['customer', 'room'])
                ->whereDate('check_out', $today)
                ->where('status', 'active')
                ->orderBy('check_out', 'ASC')
                ->get(),
        ];
    }

    /**
     * Vérifier la disponibilité d'une chambre
     */
    public function checkRoomAvailability($roomId, $checkIn, $checkOut, $excludeTransactionId = null)
    {
        $query = Transaction::where('room_id', $roomId)
            ->whereIn('status', ['reservation', 'active']) // Seulement les réservations actives
            ->where(function($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut])
                  ->orWhere(function($q2) use ($checkIn, $checkOut) {
                      $q2->where('check_in', '<', $checkIn)
                         ->where('check_out', '>', $checkOut);
                  });
            });
        
        if ($excludeTransactionId) {
            $query->where('id', '!=', $excludeTransactionId);
        }
        
        return $query->exists();
    }
}