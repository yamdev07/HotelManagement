<?php

namespace App\Repositories\Interface;

interface PaymentRepositoryInterface
{
    public function store($request, $transaction, string $status);

    public function create(array $data);

    public function createPayment($transactionId, $amount, $method = 'cash', $description = null);

    public function getByTransaction($transactionId);

    public function getCompletedByTransaction($transactionId);

    public function getTotalByTransaction($transactionId);

    public function createRefund($transactionId, $amount, $reason = null);

    public function markAsRefunded($paymentId, $userId, $reason = null);

    public function updateStatus($paymentId, $status, $description = null);

    public function cancel($paymentId, $userId, $reason = null);

    public function delete($paymentId);

    public function search($request);

    public function getTodayPayments();

    public function getTodayPaymentsAmount();

    public function getTodayPaymentsCount();

    public function getPaymentsByMethod($startDate = null, $endDate = null);

    public function getPaymentsByPeriod($startDate, $endDate);

    public function getPaymentStats($startDate = null, $endDate = null);

    public function getPaymentMethods();

    public function isTransactionFullyPaid($transactionId, $transactionTotal);

    public function getTransactionBalance($transactionId, $transactionTotal);

    public function getByCashierSession($sessionId);

    public function getTotalByCashierSession($sessionId);
}
