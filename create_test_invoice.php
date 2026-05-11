<?php

require_once 'vendor/autoload.php';

use App\Models\Customer;
use App\Models\Room;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderItem;
use App\Models\Payment;

$c = Customer::first();
$r = Room::first();
$m = Menu::first();

if (!$c || !$r || !$m) {
    echo "Données manquantes : Customer, Room ou Menu\n";
    exit(1);
}

$transaction = Transaction::create([
    'user_id' => 1,
    'customer_id' => $c->id,
    'room_id' => $r->id,
    'check_in' => now(),
    'check_out' => now()->addDay(),
    'status' => 'active',
    'person_count' => 1,
    'total_price' => $r->price,
]);

$order = RestaurantOrder::create([
    'customer_id' => $c->id,
    'room_id' => $r->id,
    'transaction_id' => $transaction->id,
    'total' => $m->price * 2,
    'status' => 'paid',
]);

RestaurantOrderItem::create([
    'order_id' => $order->id,
    'menu_id' => $m->id,
    'quantity' => 2,
    'price' => $m->price,
]);

$payment = Payment::create([
    'user_id' => 1,
    'created_by' => 1,
    'transaction_id' => $transaction->id,
    'amount' => $transaction->getTotalPrice(),
    'status' => 'completed',
    'payment_method' => 'cash',
    'reference' => 'TEST-FACTURE',
]);

echo "Transaction ID: {$transaction->id}, Payment ID: {$payment->id}\n";
echo "URLs de test :\n";
echo "- Transaction: /transaction/{$transaction->id}/invoice\n";
echo "- Payment: /payment/{$payment->id}/invoice\n";