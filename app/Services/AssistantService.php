<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Assistant IA de la vitrine, propulsé par Groq (API compatible OpenAI).
 *
 * L'assistant ne connaît QUE les données réelles de l'hôtel (injectées dans le
 * prompt système) : il ne peut donc pas inventer de prix ou de prestations.
 * Non configuré (clé absente) => isConfigured() renvoie false et l'assistant
 * n'est pas affiché.
 */
class AssistantService
{
    public function isConfigured(): bool
    {
        return ! empty(config('services.groq.key'));
    }

    /**
     * Répond au dernier message du visiteur.
     *
     * @param  array<int,array{role:string,content:string}>  $history  Historique court (visiteur/assistant).
     * @return array{ok:bool,reply:string}
     */
    public function reply(Hotel $hotel, array $history): array
    {
        if (! $this->isConfigured()) {
            return ['ok' => false, 'reply' => "L'assistant n'est pas disponible pour le moment."];
        }

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->systemPrompt($hotel)]],
            $this->sanitizeHistory($history),
        );

        try {
            $response = Http::withToken(config('services.groq.key'))
                ->timeout(20)
                ->acceptJson()
                ->post(rtrim(config('services.groq.base_url'), '/').'/chat/completions', [
                    'model' => config('services.groq.model'),
                    'messages' => $messages,
                    'temperature' => 0.3,
                    'max_tokens' => 500,
                ]);

            if (! $response->successful()) {
                Log::warning('Groq assistant: réponse non OK', ['status' => $response->status(), 'body' => $response->body()]);

                return ['ok' => false, 'reply' => "Désolé, je n'ai pas pu répondre. Réessayez dans un instant."];
            }

            $text = trim((string) data_get($response->json(), 'choices.0.message.content', ''));

            if ($text === '') {
                return ['ok' => false, 'reply' => "Désolé, je n'ai pas de réponse. Réessayez dans un instant."];
            }

            return ['ok' => true, 'reply' => $text];
        } catch (\Throwable $e) {
            Log::error('Groq assistant: exception', ['error' => $e->getMessage()]);

            return ['ok' => false, 'reply' => "Désolé, une erreur est survenue. Réessayez dans un instant."];
        }
    }

    /** Contexte injecté : identité, prestations et chambres réelles de l'hôtel. */
    private function systemPrompt(Hotel $hotel): string
    {
        $rooms = Room::with('type')
            ->where('room_status_id', '!=', 0)
            ->orderBy('price')
            ->get()
            ->map(function (Room $r) {
                $type = $r->type->name ?? 'Chambre';
                $price = number_format((float) $r->price, 0, ',', ' ');

                return "- {$type} (n°{$r->number}) : {$price} FCFA/nuit, {$r->capacity} pers. max";
            })
            ->implode("\n");

        $services = collect($hotel->siteServices())
            ->map(fn ($s) => is_array($s) ? ($s['title'] ?? null) : $s)
            ->filter()
            ->implode(', ');

        $lines = [
            "Tu es l'assistant virtuel de l'hôtel « {$hotel->name} ». Tu réponds aux visiteurs de son site web.",
            '',
            'RÈGLES :',
            "- Réponds UNIQUEMENT à propos de cet hôtel, à partir des informations ci-dessous. N'invente jamais de prix, de chambre ou de service.",
            "- Si tu ne sais pas, dis-le et invite à utiliser le formulaire de contact ou à réserver en ligne.",
            "- Réponds dans la langue du visiteur (français ou anglais), de façon chaleureuse, concise et professionnelle.",
            "- Pour réserver, invite à cliquer sur « Réserver » sur le site. Ne prétends pas enregistrer une réservation toi-même.",
            "- Les prix sont en FCFA.",
            '',
            "INFORMATIONS SUR L'HÔTEL :",
            "Nom : {$hotel->name}",
        ];

        if ($hotel->address) {
            $lines[] = "Adresse : {$hotel->address}";
        }
        if ($hotel->contact_phone) {
            $lines[] = "Téléphone : {$hotel->contact_phone}";
        }
        if ($hotel->contact_email) {
            $lines[] = "Email : {$hotel->contact_email}";
        }
        if ($hotel->about_text) {
            $lines[] = "À propos : {$hotel->about_text}";
        }
        if ($services !== '') {
            $lines[] = "Services : {$services}";
        }
        $lines[] = '';
        $lines[] = 'CHAMBRES DISPONIBLES :';
        $lines[] = $rooms !== '' ? $rooms : '(aucune chambre renseignée)';
        $lines[] = '';
        $lines[] = 'Check-in à partir de 12h, check-out avant 12h (indicatif).';

        return implode("\n", $lines);
    }

    /**
     * Ne garde que les rôles user/assistant, limite la longueur et l'historique.
     *
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
