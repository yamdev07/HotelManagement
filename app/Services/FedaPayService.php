<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Intégration FedaPay pour le paiement des abonnements SaaS.
 *
 * On utilise l'API REST v1 de FedaPay via le client HTTP de Laravel :
 *   1. création d'une transaction (montant + client + URL de retour)
 *   2. génération d'un token → URL de paiement hébergée (redirection)
 *   3. vérification du statut au retour ('approved' = payé)
 *
 * Aucun SDK à installer (déploiement FTP) : tout passe par Http.
 */
class FedaPayService
{
    /** Base de l'API selon l'environnement (sandbox/live). */
    public function baseUrl(): string
    {
        return $this->env() === 'live'
            ? 'https://api.fedapay.com/v1'
            : 'https://sandbox-api.fedapay.com/v1';
    }

    public function env(): string
    {
        return config('services.fedapay.env', 'sandbox');
    }

    /** Le paiement en ligne est-il configuré ? */
    public function isConfigured(): bool
    {
        return ! empty(config('services.fedapay.secret'));
    }

    protected function client(): PendingRequest
    {
        return Http::withToken((string) config('services.fedapay.secret'))
            ->acceptJson()
            ->asJson()
            ->timeout(30);
    }

    /**
     * Crée une transaction FedaPay et renvoie l'URL de paiement hébergée.
     *
     * @return array{transaction_id:int, url:string}
     *
     * @throws \RuntimeException si l'API échoue.
     */
    public function createCheckout(Hotel $hotel, string $plan, int $amount, string $currency, string $description, string $callbackUrl, string $customerEmail, string $customerName): array
    {
        [$firstname, $lastname] = $this->splitName($customerName);

        $create = $this->client()->post($this->baseUrl().'/transactions', [
            'description' => $description,
            'amount' => $amount,
            'currency' => ['iso' => $this->normalizeCurrency($currency)],
            'callback_url' => $callbackUrl,
            'customer' => [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $customerEmail,
            ],
        ]);

        if (! $create->successful()) {
            throw new \RuntimeException('FedaPay: création de la transaction échouée ('.$create->status().') '.$create->body());
        }

        $transaction = $create->json('v1/transaction') ?? $create->json('transaction') ?? $create->json();
        $transactionId = $transaction['id'] ?? null;

        if (! $transactionId) {
            throw new \RuntimeException('FedaPay: identifiant de transaction manquant dans la réponse.');
        }

        $token = $this->client()->post($this->baseUrl().'/transactions/'.$transactionId.'/token');

        if (! $token->successful()) {
            throw new \RuntimeException('FedaPay: génération du token échouée ('.$token->status().') '.$token->body());
        }

        $url = $token->json('url');
        if (! $url) {
            // Repli : URL de checkout construite à partir du token.
            $sub = $this->env() === 'live' ? 'checkout' : 'sandbox-checkout';
            $url = 'https://'.$sub.'.fedapay.com/'.$token->json('token');
        }

        return [
            'transaction_id' => (int) $transactionId,
            'url' => $url,
        ];
    }

    /**
     * Récupère le statut d'une transaction ('approved', 'declined', 'pending', ...).
     */
    public function transactionStatus(int $transactionId): ?string
    {
        $res = $this->client()->get($this->baseUrl().'/transactions/'.$transactionId);

        if (! $res->successful()) {
            return null;
        }

        $transaction = $res->json('v1/transaction') ?? $res->json('transaction') ?? $res->json();

        return $transaction['status'] ?? null;
    }

    public function isApproved(int $transactionId): bool
    {
        return $this->transactionStatus($transactionId) === 'approved';
    }

    /**
     * Normalise la devise vers un code ISO accepté par FedaPay.
     * L'app stocke souvent "CFA"/"FCFA" ; FedaPay attend "XOF" (BCEAO) ou "XAF" (BEAC).
     */
    protected function normalizeCurrency(string $currency): string
    {
        $c = strtoupper(trim($currency));

        return match ($c) {
            'CFA', 'FCFA', 'F CFA', 'FRANC CFA' => 'XOF',
            default => $c,
        };
    }

    /** @return array{0:string,1:string} [prénom, nom] */
    protected function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $firstname = $parts[0] ?? 'Client';
        $lastname = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : $firstname;

        return [$firstname, $lastname];
    }
}
