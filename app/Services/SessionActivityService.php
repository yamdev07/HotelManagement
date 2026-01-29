<?php

namespace App\Services;

use App\Models\SessionActivity;
use App\Models\CashierSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionActivityService
{
    /**
     * Enregistrer une nouvelle activité
     */
    public static function log(
        string $action,
        string $entityType = null,
        int $entityId = null,
        string $description,
        array $data = []
    ): SessionActivity
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        
        $session = $user->activeCashierSession ?? null;
        if (!$session) {
            return null;
        }
        
        $request = app('request');
        
        return SessionActivity::create([
            'cashier_session_id' => $session->id,
            'user_id' => $user->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'data' => $data,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
    
    /**
     * Loguer une activité de paiement
     */
    public static function logPayment($payment, $action)
    {
        $descriptions = [
            'created' => "Paiement #{$payment->id} créé - Montant: " . number_format($payment->amount, 0) . " FCFA",
            'updated' => "Paiement #{$payment->id} modifié",
            'deleted' => "Paiement #{$payment->id} supprimé",
            'completed' => "Paiement #{$payment->id} complété",
            'refunded' => "Paiement #{$payment->id} remboursé",
        ];
        
        return self::log(
            "payment_{$action}",
            'Payment',
            $payment->id,
            $descriptions[$action] ?? "Paiement #{$payment->id} {$action}",
            [
                'amount' => $payment->amount,
                'method' => $payment->payment_method,
                'status' => $payment->status,
                'reference' => $payment->reference,
                'customer' => $payment->transaction->booking->customer->name ?? 'N/A',
            ]
        );
    }
    
    /**
     * Loguer une activité de réservation
     */
    public static function logBooking($booking, $action)
    {
        $descriptions = [
            'created' => "Réservation #{$booking->id} créée - Chambre: {$booking->room->room_number}",
            'updated' => "Réservation #{$booking->id} modifiée",
            'deleted' => "Réservation #{$booking->id} supprimée",
            'checkin' => "Check-in pour réservation #{$booking->id} - Chambre: {$booking->room->room_number}",
            'checkout' => "Check-out pour réservation #{$booking->id} - Chambre: {$booking->room->room_number}",
        ];
        
        return self::log(
            $action === 'checkin' || $action === 'checkout' ? $action : "booking_{$action}",
            'Booking',
            $booking->id,
            $descriptions[$action] ?? "Réservation #{$booking->id} {$action}",
            [
                'room_number' => $booking->room->room_number ?? 'N/A',
                'check_in' => $booking->check_in?->format('d/m/Y'),
                'check_out' => $booking->check_out?->format('d/m/Y'),
                'customer' => $booking->customer->name ?? 'N/A',
                'total_amount' => $booking->total_amount ?? 0,
            ]
        );
    }
    
    /**
     * Loguer une activité de session
     */
    public static function logSession($session, $action)
    {
        $descriptions = [
            'started' => "Session #{$session->id} démarrée - Solde initial: " . number_format($session->initial_balance, 0) . " FCFA",
            'closed' => "Session #{$session->id} clôturée - Solde final: " . number_format($session->final_balance, 0) . " FCFA",
            'updated' => "Session #{$session->id} mise à jour",
        ];
        
        return self::log(
            "session_{$action}",
            'CashierSession',
            $session->id,
            $descriptions[$action] ?? "Session #{$session->id} {$action}",
            [
                'initial_balance' => $session->initial_balance,
                'final_balance' => $session->final_balance,
                'status' => $session->status,
            ]
        );
    }
}