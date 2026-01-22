<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateTransactionStatuses extends Command
{
    protected $signature = 'transactions:update-statuses';
    protected $description = 'Met à jour le statut de toutes les transactions selon leur date.';

    public function handle()
    {
        $this->info('Début de la mise à jour automatique des statuts...');

        // 1. Gérer les "no show" : Réservations où check_in est passé mais statut toujours "reservation"
        $noShowTransactions = Transaction::where('status', Transaction::STATUS_RESERVATION)
                                         ->whereDate('check_in', '<', Carbon::today())
                                         ->get();
        foreach ($noShowTransactions as $transaction) {
            $transaction->changeStatus(Transaction::STATUS_NO_SHOW);
            $this->line("Transaction #{$transaction->id} : 'reservation' → 'no_show' (no show).");
        }

        // 2. Activer les séjours : Réservations où check_in est aujourd'hui ou dans le passé, et check_out est dans le futur
        $transactionsToActivate = Transaction::where('status', Transaction::STATUS_RESERVATION)
                                             ->whereDate('check_in', '<=', Carbon::today())
                                             ->whereDate('check_out', '>=', Carbon::today())
                                             ->get();
        foreach ($transactionsToActivate as $transaction) {
            $transaction->changeStatus(Transaction::STATUS_ACTIVE);
            $this->line("Transaction #{$transaction->id} : 'reservation' → 'active' (check-in).");
        }

        // 3. Finaliser les séjours terminés : Statut "active" mais check_out passé
        $transactionsToComplete = Transaction::where('status', Transaction::STATUS_ACTIVE)
                                             ->whereDate('check_out', '<', Carbon::today())
                                             ->get();
        foreach ($transactionsToComplete as $transaction) {
            // VÉRIFICATION DE PAIEMENT AVANT DE COMPLÉTER
            if ($transaction->isFullyPaid()) {
                $transaction->changeStatus(Transaction::STATUS_COMPLETED);
                $this->line("Transaction #{$transaction->id} : 'active' → 'completed' (check-out).");
            } else {
                $this->warn("Transaction #{$transaction->id} : impossible de finaliser, solde impayé de {$transaction->getFormattedRemainingPaymentAttribute()}.");
            }
        }

        $this->info('Mise à jour terminée.');
        return 0;
    }
}