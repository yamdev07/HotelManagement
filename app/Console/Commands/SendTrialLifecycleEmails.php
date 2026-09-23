<?php

namespace App\Console\Commands;

use App\Mail\TrialLifecycleMail;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Envoie les emails automatiques du cycle d'essai (J+1, J+3, J+7, J+11).
 * Idempotent : chaque étape n'est envoyée qu'une fois par hôtel (table trial_email_logs).
 * Ne cible que le jour exact depuis l'inscription -> aucun envoi rétroactif massif.
 */
class SendTrialLifecycleEmails extends Command
{
    protected $signature = 'trial:send-lifecycle {--dry : Affiche sans envoyer}';

    protected $description = 'Envoie les emails du cycle d\'essai (J+1, J+3, J+7, J+11).';

    public function handle(): int
    {
        $today = now()->startOfDay();
        $sent = 0;

        Hotel::query()
            ->whereNotNull('created_at')
            ->where('created_at', '>=', now()->subDays(15))
            ->chunkById(100, function ($hotels) use ($today, &$sent) {
                foreach ($hotels as $hotel) {
                    $days = Carbon::parse($hotel->created_at)->startOfDay()->diffInDays($today);
                    $stage = TrialLifecycleMail::SCHEDULE[$days] ?? null;
                    if (! $stage) {
                        continue;
                    }

                    // L'étape "ending" ne concerne que les essais réellement en cours de fin.
                    if ($stage === 'ending' && ! $this->trialEndingSoon($hotel)) {
                        continue;
                    }

                    // Déjà envoyée ? (idempotence)
                    $already = DB::table('trial_email_logs')
                        ->where('hotel_id', $hotel->id)->where('stage', $stage)->exists();
                    if ($already) {
                        continue;
                    }

                    $email = $this->recipient($hotel);
                    if (! $email) {
                        continue;
                    }

                    if ($this->option('dry')) {
                        $this->line("[dry] hôtel #{$hotel->id} ({$hotel->name}) -> {$stage} -> {$email}");
                        $sent++;

                        continue;
                    }

                    try {
                        Mail::to($email)->send(new TrialLifecycleMail($hotel, $stage));
                        DB::table('trial_email_logs')->insert([
                            'hotel_id' => $hotel->id,
                            'stage' => $stage,
                            'sent_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $sent++;
                    } catch (\Throwable $e) {
                        Log::warning('Email cycle essai échoué (hôtel '.$hotel->id.', '.$stage.') : '.$e->getMessage());
                    }
                }
            });

        $this->info("Cycle d'essai : {$sent} email(s) traité(s).");

        return self::SUCCESS;
    }

    private function trialEndingSoon(Hotel $hotel): bool
    {
        return $hotel->subscription_ends_at
            && $hotel->subscription_ends_at->isFuture()
            && $hotel->subscription_ends_at->lessThan(now()->addDays(6));
    }

    private function recipient(Hotel $hotel): ?string
    {
        if ($hotel->owner_user_id) {
            $owner = User::find($hotel->owner_user_id);
            if ($owner && $owner->email) {
                return $owner->email;
            }
        }

        return $hotel->contact_email ?: null;
    }
}
