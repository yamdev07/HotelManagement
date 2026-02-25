<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\Room;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ProcessWaitingReservations extends Command
{
    protected $signature = 'hotel:process-waiting-reservations';
    protected $description = 'Traite les réservations en attente de check-out';

    public function handle()
    {
        $this->info('Traitement des réservations en attente...');
        
        // Trouver les réservations en attente pour aujourd'hui
        $waitingReservations = Transaction::where('status', 'reserved_waiting')
            ->whereDate('check_in', Carbon::today())
            ->get();
        
        $count = 0;
        
        foreach ($waitingReservations as $reservation) {
            $room = $reservation->room;
            
            // Vérifier si la chambre est maintenant libre
            $currentOccupant = Transaction::where('room_id', $room->id)
                ->where('status', 'active')
                ->whereDate('check_out', Carbon::today())
                ->first();
            
            if (!$currentOccupant) {
                // Plus d'occupant, confirmer la réservation
                $reservation->confirmAfterCheckout();
                $count++;
                
                $this->info("Réservation #{$reservation->id} confirmée pour la chambre {$room->number}");
            } else {
                $this->line("En attente: Réservation #{$reservation->id} - Check-out à {$currentOccupant->check_out_time}");
            }
        }
        
        $this->info("Traitement terminé. $count réservation(s) confirmée(s).");
        
        return Command::SUCCESS;
    }
}