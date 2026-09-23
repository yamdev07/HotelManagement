<?php

namespace App\Mail;

use App\Models\Hotel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email automatique du cycle d'essai (J+1, J+3, J+7, J+11).
 * Une seule classe : le contenu dépend de l'étape ($stage).
 */
class TrialLifecycleMail extends Mailable
{
    use Queueable, SerializesModels;

    /** Jour d'essai (depuis l'inscription) => clé d'étape. */
    public const SCHEDULE = [
        1  => 'welcome',
        3  => 'tips',
        7  => 'usage',
        11 => 'ending',
        14 => 'trial_end',
        30 => 'review',
    ];

    public function __construct(
        public Hotel $hotel,
        public string $stage
    ) {}

    public function build()
    {
        $c = $this->content()[$this->stage] ?? $this->content()['welcome'];

        return $this->subject($c['subject'])
            ->view('emails.trial-lifecycle')
            ->with([
                'hotelName' => $this->hotel->name,
                'heading'   => $c['heading'],
                'lines'     => $c['lines'],
                'ctaLabel'  => $c['cta'],
                'ctaUrl'    => $c['url'],
            ]);
    }

    /** Contenu par étape (français). */
    private function content(): array
    {
        $dashboard = route('login.index');

        return [
            'welcome' => [
                'subject' => 'Bienvenue chez checkinHub · on vous accompagne',
                'heading' => 'Bienvenue à bord !',
                'lines' => [
                    'Votre essai gratuit est lancé. En 5 minutes, vous pouvez déjà gérer une vraie réservation.',
                    'Pour bien démarrer : ajoutez vos chambres, invitez un réceptionniste, puis créez votre première réservation.',
                    'Tout est guidé depuis votre tableau de bord grâce à la checklist de configuration.',
                ],
                'cta' => 'Ouvrir mon tableau de bord',
                'url' => $dashboard,
            ],
            'tips' => [
                'subject' => '3 conseils pour bien démarrer avec checkinHub',
                'heading' => '3 conseils pour aller plus vite',
                'lines' => [
                    '1. Créez vos types de chambres et fixez vos tarifs.',
                    '2. Invitez votre équipe : chacun son compte, sans partager votre mot de passe.',
                    '3. Publiez votre mini-site pour recevoir des réservations en ligne.',
                ],
                'cta' => 'Continuer la configuration',
                'url' => $dashboard,
            ],
            'usage' => [
                'subject' => 'Votre première semaine avec checkinHub',
                'heading' => 'Déjà une semaine !',
                'lines' => [
                    'Comment se passe la prise en main ? La plupart des hôteliers gèrent leur premier check-in dès les premiers jours.',
                    'Si un point bloque, écrivez-nous sur WhatsApp : on vous débloque rapidement.',
                    'Pensez à compléter votre configuration pour profiter de toutes les fonctionnalités.',
                ],
                'cta' => 'Voir mon tableau de bord',
                'url' => $dashboard,
            ],
            'ending' => [
                'subject' => 'Votre essai checkinHub se termine bientôt',
                'heading' => 'Votre essai se termine bientôt',
                'lines' => [
                    'Il vous reste quelques jours d\'essai. Pour ne rien perdre (réservations, clients, caisse), choisissez votre formule.',
                    'La formule s\'adapte à votre nombre de chambres, et le paiement se fait en ligne (Mobile Money & carte).',
                    'Une question avant de choisir ? Répondez à cet email ou écrivez-nous sur WhatsApp.',
                ],
                'cta' => 'Choisir mon abonnement',
                'url' => $dashboard,
            ],
            'trial_end' => [
                'subject' => 'Votre essai checkinHub est terminé',
                'heading' => 'Votre essai est terminé',
                'lines' => [
                    'Votre période d\'essai gratuite vient de se terminer. Vos données (réservations, clients, caisse) sont conservées.',
                    'Pour continuer à gérer votre hôtel sans interruption, choisissez votre formule et réglez en ligne (Mobile Money & carte).',
                    'Besoin d\'un délai ou d\'un conseil ? Répondez à cet email, on s\'arrange.',
                ],
                'cta' => 'Choisir mon abonnement',
                'url' => $dashboard,
            ],
            'review' => [
                'subject' => 'Votre bilan checkinHub à 30 jours',
                'heading' => 'Votre premier mois en chiffres',
                'lines' => [
                    'En un mois, vous avez traité '.$this->bookingsCount().' réservation(s) avec checkinHub. Bravo !',
                    'Pensez à explorer les fonctionnalités que vous n\'utilisez pas encore : housekeeping, rapports, restaurant, mini-site.',
                    'Un objectif pour le mois prochain ? Dites-le nous sur WhatsApp, on vous aide à en tirer le maximum.',
                ],
                'cta' => 'Voir mon tableau de bord',
                'url' => $dashboard,
            ],
        ];
    }

    /** Nombre de réservations de l'hôtel (sans le scope tenant). */
    private function bookingsCount(): int
    {
        try {
            return \App\Models\Transaction::withoutGlobalScopes()
                ->where('hotel_id', $this->hotel->id)->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
