<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ProcessWaitingReservations::class, // NOUVEAU
        \App\Console\Commands\UpdateTransactionStatuses::class, // Si vous l'avez
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Mise à jour des statuts tous les jours à 01:00
        $schedule->command('transactions:update-statuses')->dailyAt('01:00');
        
        // ✅ Traiter les réservations en attente toutes les 15 minutes
        $schedule->command('hotel:process-waiting-reservations')
                 ->everyFifteenMinutes()
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/waiting-reservations.log'));
        
        // ✅ Vérifier les no-show tous les jours à 02:00
        $schedule->command('hotel:process-no-shows')->dailyAt('02:00');
        
        // ✅ Nettoyer les vieux logs toutes les semaines
        $schedule->command('model:prune')->weekly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}