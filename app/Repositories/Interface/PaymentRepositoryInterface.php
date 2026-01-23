<?php

namespace App\Repositories\Interface;

interface PaymentRepositoryInterface
{
    /**
     * Créer un paiement à partir d'une requête (ancienne méthode)
     */
    public function store($request, $transaction, string $status);
    
    /**
     * Créer un paiement à partir d'un tableau de données
     */
    public function create(array $data);
    
    /**
     * Créer un paiement avec paramètres simplifiés
     */
    public function createPayment($transactionId, $amount, $method = 'cash', $notes = null);
    
    /**
     * Récupérer les paiements d'une transaction
     */
    public function getByTransaction($transactionId);
    
    /**
     * Récupérer le total des paiements d'une transaction
     */
    public function getTotalByTransaction($transactionId);
    
    /**
     * Créer un remboursement
     */
    public function createRefund($transactionId, $amount, $reason = null);
    
    /**
     * Mettre à jour le statut d'un paiement
     */
    public function updateStatus($paymentId, $status, $notes = null);
    
    /**
     * Supprimer un paiement (annulation)
     */
    public function delete($paymentId);
    
    /**
     * Rechercher des paiements
     */
    public function search($request);
    
    /**
     * Paiements du jour
     */
    public function getTodayPayments();
    
    /**
     * Paiements par méthode
     */
    public function getPaymentsByMethod($startDate = null, $endDate = null);
}