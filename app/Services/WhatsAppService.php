<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Envoi de notifications WhatsApp via la Meta WhatsApp Cloud API (officielle).
 *
 * Principe (comme FedaPayService) : tout passe par le client HTTP de Laravel,
 * aucun SDK à installer (déploiement FTP). Le service est « gated » par la
 * config : si le token/phone_id ne sont pas renseignés, aucun envoi n'est
 * tenté (dégradation silencieuse) — le tunnel de réservation n'est jamais
 * bloqué par WhatsApp.
 *
 * Doc : https://developers.facebook.com/docs/whatsapp/cloud-api
 */
class WhatsAppService
{
    /** Le canal WhatsApp est-il configuré (donc actif) ? */
    public function isConfigured(): bool
    {
        return ! empty(config('services.whatsapp.token'))
            && ! empty(config('services.whatsapp.phone_id'));
    }

    protected function baseUrl(): string
    {
        $version = config('services.whatsapp.api_version', 'v21.0');
        $phoneId = config('services.whatsapp.phone_id');

        return "https://graph.facebook.com/{$version}/{$phoneId}/messages";
    }

    protected function client(): PendingRequest
    {
        return Http::withToken((string) config('services.whatsapp.token'))
            ->acceptJson()
            ->asJson()
            ->timeout(20);
    }

    /**
     * Envoie un message texte à un numéro. Renvoie true si accepté par l'API.
     * N'émet jamais d'exception vers l'appelant : loggue et renvoie false.
     */
    public function sendText(?string $rawPhone, string $message): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        $to = $this->normalize($rawPhone);
        if (! $to) {
            return false;
        }

        try {
            $response = $this->client()->post($this->baseUrl(), [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'text',
                'text' => ['preview_url' => false, 'body' => $message],
            ]);

            if (! $response->successful()) {
                Log::warning('WhatsApp: envoi échoué ('.$response->status().')', [
                    'to' => $to,
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('WhatsApp: exception à l\'envoi', ['to' => $to, 'error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Normalise un numéro au format international sans « + » ni séparateurs
     * (format attendu par la Cloud API, ex. « 22990000000 »).
     * Applique l'indicatif pays par défaut si le numéro est local.
     */
    public function normalize(?string $raw): ?string
    {
        if (! $raw) {
            return null;
        }

        $hadPlus = str_starts_with(trim($raw), '+');
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if ($digits === '') {
            return null;
        }

        // Retire un éventuel préfixe « 00 » international.
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
            $hadPlus = true;
        }

        // Numéro local (pas de « + », longueur d'un numéro national) => préfixe pays.
        $cc = (string) config('services.whatsapp.default_country', '229');
        if (! $hadPlus && strlen($digits) <= 10 && ! str_starts_with($digits, $cc)) {
            $digits = $cc.ltrim($digits, '0');
        }

        return strlen($digits) >= 8 ? $digits : null;
    }
}
