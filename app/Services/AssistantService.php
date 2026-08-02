<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Assistant IA du back-office (app de gestion), propulsé par Groq
 * (API compatible OpenAI).
 *
 * Il aide l'hôtelier à utiliser l'application et répond sur l'état réel de son
 * établissement (chambres libres, arrivées du jour, encaissements…), injecté
 * dans le prompt système. Non configuré (clé absente) => l'assistant est masqué.
 */
class AssistantService
{
    public function __construct(private AssistantActions $actions) {}

    public function isConfigured(): bool
    {
        return ! empty(config('services.groq.key'));
    }

    /**
     * Répond à l'utilisateur du back-office. Peut déclencher des OUTILS :
     * - lecture (liste chambres/arrivées) : exécutée directement,
     * - écriture (check-in, ménage) : renvoyée en « pending » pour confirmation.
     *
     * @param  array<int,array{role:string,content:string}>  $history
     * @return array{ok:bool,reply:string,pending?:array{tool:string,args:array,summary:string}}
     */
    public function reply(User $user, array $history): array
    {
        if (! $this->isConfigured()) {
            return ['ok' => false, 'reply' => "L'assistant n'est pas disponible pour le moment."];
        }

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->systemPrompt($user)]],
            $this->sanitizeHistory($history),
        );
        $tools = $this->actions->definitions();

        // Boucle d'appels d'outils (bornée) : le modèle peut enchaîner des
        // lectures avant de répondre, ou proposer une écriture (-> confirmation).
        for ($i = 0; $i < 4; $i++) {
            $res = $this->call($messages, $tools);
            if (! $res['ok']) {
                return ['ok' => false, 'reply' => $res['reply']];
            }

            $message = $res['message'];
            $toolCalls = $message['tool_calls'] ?? [];

            if (empty($toolCalls)) {
                $text = trim((string) ($message['content'] ?? ''));

                return ['ok' => true, 'reply' => $text !== '' ? $text : "D'accord."];
            }

            // On rejoue le message de l'assistant (avec ses tool_calls).
            $messages[] = ['role' => 'assistant', 'content' => $message['content'] ?? '', 'tool_calls' => $toolCalls];

            foreach ($toolCalls as $tc) {
                $name = $tc['function']['name'] ?? '';
                $args = json_decode($tc['function']['arguments'] ?? '{}', true) ?: [];

                // Action d'écriture : on N'EXÉCUTE PAS, on demande confirmation.
                if ($this->actions->isWrite($name)) {
                    $summary = $this->actions->summary($name, $args);

                    return [
                        'ok' => true,
                        'reply' => trim((string) ($message['content'] ?? '')) ?: $summary,
                        'pending' => ['tool' => $name, 'args' => $args, 'summary' => $summary],
                    ];
                }

                // Action de lecture : exécutée, résultat renvoyé au modèle.
                $messages[] = [
                    'role' => 'tool',
                    'tool_call_id' => $tc['id'] ?? '',
                    'content' => $this->actions->runRead($user, $name, $args),
                ];
            }
        }

        return ['ok' => true, 'reply' => "Je n'ai pas pu finaliser la réponse. Reformulez ?"];
    }

    /**
     * Un appel à l'API Groq (chat completions).
     *
     * @return array{ok:bool,message?:array,reply?:string}
     */
    private function call(array $messages, array $tools): array
    {
        try {
            $payload = [
                'model' => config('services.groq.model'),
                'messages' => $messages,
                'temperature' => 0.3,
                'max_tokens' => 700,
            ];
            if (! empty($tools)) {
                $payload['tools'] = $tools;
                $payload['tool_choice'] = 'auto';
            }

            $response = Http::withToken(config('services.groq.key'))
                ->timeout(25)->acceptJson()
                ->post(rtrim(config('services.groq.base_url'), '/').'/chat/completions', $payload);

            if (! $response->successful()) {
                Log::warning('Groq assistant: réponse non OK', ['status' => $response->status(), 'body' => $response->body()]);

                return ['ok' => false, 'reply' => "Désolé, je n'ai pas pu répondre. Réessayez dans un instant."];
            }

            return ['ok' => true, 'message' => (array) data_get($response->json(), 'choices.0.message', [])];
        } catch (\Throwable $e) {
            Log::error('Groq assistant: exception', ['error' => $e->getMessage()]);

            return ['ok' => false, 'reply' => "Désolé, une erreur est survenue. Réessayez dans un instant."];
        }
    }

    /**
     * Transcrit un message vocal en texte via Groq Whisper.
     *
     * @return array{ok:bool,text:string}
     */
    public function transcribe(string $audioContents, string $filename = 'audio.webm'): array
    {
        if (! $this->isConfigured()) {
            return ['ok' => false, 'text' => ''];
        }

        try {
            $response = Http::withToken(config('services.groq.key'))
                ->timeout(30)
                ->asMultipart()
                ->post(rtrim(config('services.groq.base_url'), '/').'/audio/transcriptions', [
                    ['name' => 'file', 'contents' => $audioContents, 'filename' => $filename],
                    ['name' => 'model', 'contents' => config('services.groq.whisper_model')],
                    ['name' => 'response_format', 'contents' => 'json'],
                    ['name' => 'temperature', 'contents' => '0'],
                ]);

            if (! $response->successful()) {
                Log::warning('Groq Whisper: réponse non OK', ['status' => $response->status(), 'body' => $response->body()]);

                return ['ok' => false, 'text' => ''];
            }

            return ['ok' => true, 'text' => trim((string) data_get($response->json(), 'text', ''))];
        } catch (\Throwable $e) {
            Log::error('Groq Whisper: exception', ['error' => $e->getMessage()]);

            return ['ok' => false, 'text' => ''];
        }
    }

    /** Prompt système : rôle de l'assistant + état réel de l'établissement + repères de navigation. */
    private function systemPrompt(User $user): string
    {
        $hotel = $user->hotel;
        $hotelName = $hotel?->name ?? 'votre établissement';

        $lines = [
            "Tu es l'assistant de gestion de l'application hôtelière checkinHub.",
            "Tu aides {$user->name} ({$user->role}) à gérer l'hôtel « {$hotelName} ».",
            '',
            'RÈGLES :',
            "- Réponds de façon concise, professionnelle et actionnable, dans la langue de l'utilisateur (français ou anglais).",
            "- Utilise les CHIFFRES ci-dessous pour répondre aux questions sur l'état de l'hôtel. Ne les invente jamais.",
            "- Tu peux EXÉCUTER certaines actions via les outils disponibles (lister les chambres/arrivées, faire un check-in, marquer une chambre propre). Utilise-les quand l'utilisateur le demande.",
            "- Pour les actions qui modifient des données (check-in, ménage), une confirmation sera demandée automatiquement à l'utilisateur : appelle l'outil dès que la demande est claire, sans redemander toi-même.",
            "- Pour ce qui n'est pas couvert par un outil, indique le menu où aller (voir NAVIGATION).",
            "- Si une information n'est pas disponible, dis-le simplement.",
            '- Les montants sont en FCFA.',
            '',
            $this->liveSnapshot(),
            '',
            'NAVIGATION (où faire quoi dans l\'app) :',
            '- Réservations, check-in (arrivée) et check-out (départ) : menu « Réservations ».',
            '- Encaisser un paiement, ouvrir/clôturer la caisse : menu « Caisse ».',
            '- Ajouter ou modifier une chambre : menu « Chambres ».',
            '- Nettoyage des chambres : menu « Ménage ».',
            '- Modérer les avis clients : menu « Avis clients ».',
            '- Suivre les revenus et rapports : menu « Revenus ».',
            '- Codes promo : menu « Codes promo ».',
            '- Réglages de l\'hôtel et de la vitrine : « Mon établissement ».',
            '- Gérer le personnel : menu « Personnel ».',
        ];

        return implode("\n", $lines);
    }

    /** Instantané chiffré de l'établissement (scopé au tenant courant). */
    private function liveSnapshot(): string
    {
        $today = today();

        $roomsTotal = Room::count();
        $available  = Room::where('room_status_id', RoomStatus::Available->value)->count();
        $occupied   = Room::where('room_status_id', RoomStatus::Occupied->value)->count();
        $toClean    = Room::whereIn('room_status_id', [RoomStatus::Dirty->value, RoomStatus::Cleaning->value])->count();

        $arrivals   = Transaction::where('status', 'reservation')->whereDate('check_in', $today)->count();
        $departures = Transaction::where('status', 'active')->whereDate('check_out', $today)->count();
        $active     = Transaction::where('status', 'active')->count();

        $pendingReviews = Review::pending()->count();

        $revenueToday = (float) Payment::where('status', Payment::STATUS_COMPLETED)
            ->whereDate('payment_date', $today)->sum('amount');
        $revenueFmt = number_format($revenueToday, 0, ',', ' ');

        return implode("\n", [
            'ÉTAT ACTUEL DE L\'HÔTEL (aujourd\'hui '.$today->format('d/m/Y').') :',
            "- Chambres : {$roomsTotal} au total · {$available} disponibles · {$occupied} occupées · {$toClean} à nettoyer.",
            "- Arrivées prévues aujourd'hui : {$arrivals}.",
            "- Départs prévus aujourd'hui : {$departures}.",
            "- Séjours en cours : {$active}.",
            "- Avis en attente de modération : {$pendingReviews}.",
            "- Encaissé aujourd'hui : {$revenueFmt} FCFA.",
        ]);
    }

    /**
     * @param  array<int,mixed>  $history
     * @return array<int,array{role:string,content:string}>
     */
    private function sanitizeHistory(array $history): array
    {
        return collect($history)
            ->filter(fn ($m) => is_array($m) && in_array($m['role'] ?? null, ['user', 'assistant'], true) && ! empty($m['content']))
            ->map(fn ($m) => [
                'role' => $m['role'],
                'content' => mb_substr((string) $m['content'], 0, 1000),
            ])
            ->slice(-10)
            ->values()
            ->all();
    }
}
