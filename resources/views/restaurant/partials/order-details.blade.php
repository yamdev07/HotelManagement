<div class="order-details">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i> Informations client</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Nom:</th>
                            <td>{{ $order->customer_name ?? 'Non spécifié' }}</td>
                        </tr>
                        @if($order->customer_phone)
                        <tr>
                            <th>Téléphone:</th>
                            <td>{{ $order->customer_phone }}</td>
                        </tr>
                        @endif
                        @if($order->room_number)
                        <tr>
                            <th>Chambre:</th>
                            <td>
                                <span class="badge bg-info">Chambre {{ $order->room_number }}</span>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>Date commande:</th>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-file-invoice me-2"></i> Résumé de la commande</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Numéro:</th>
                            <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Statut:</th>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'preparing' => 'info',
                                        'delivered' => 'success',
                                        'paid' => 'primary',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'En attente',
                                        'preparing' => 'En préparation',
                                        'delivered' => 'Livré',
                                        'paid' => 'Payé',
                                        'cancelled' => 'Annulé'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td class="h5 text-primary">{{ number_format($order->total, 2) }} €</td>
                        </tr>
                        @if($order->payment_method)
                        <tr>
                            <th>Paiement:</th>
                            <td>
                                @php
                                    $paymentMethods = [
                                        'cash' => 'Espèces',
                                        'card' => 'Carte bancaire',
                                        'room_charge' => 'Frais de chambre',
                                        'online' => 'En ligne'
                                    ];
                                @endphp
                                {{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-list-alt me-2"></i> Détails des articles</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Menu</th>
                            <th class="text-center">Prix unitaire</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->menu->image)
                                    <img src="{{ asset('storage/' . $item->menu->image) }}" 
                                         alt="{{ $item->menu->name }}" 
                                         class="rounded me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <strong>{{ $item->menu->name }}</strong>
                                        @if($item->menu->description)
                                        <br><small class="text-muted">{{ Str::limit($item->menu->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ number_format($item->price, 2) }} €</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">{{ number_format($item->price * $item->quantity, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Sous-total:</th>
                            <th class="text-center">{{ number_format($order->items->sum(function($item) {
                                return $item->price * $item->quantity;
                            }), 2) }} €</th>
                        </tr>
                        @if($order->tax_rate > 0)
                        <tr>
                            <th colspan="3" class="text-end">Taxes ({{ $order->tax_rate }}%):</th>
                            <th class="text-center">{{ number_format($order->total * $order->tax_rate / 100, 2) }} €</th>
                        </tr>
                        @endif
                        @if($order->discount > 0)
                        <tr>
                            <th colspan="3" class="text-end">Réduction:</th>
                            <th class="text-center text-danger">-{{ number_format($order->discount, 2) }} €</th>
                        </tr>
                        @endif
                        <tr class="table-active">
                            <th colspan="3" class="text-end">Total:</th>
                            <th class="text-center h5">{{ number_format($order->total, 2) }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @if($order->notes)
    <div class="card mt-3">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i> Notes</h6>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $order->notes }}</p>
        </div>
    </div>
    @endif

    <div class="card mt-3">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-history me-2"></i> Historique</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Commande créée</span>
                    <span class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </li>
                @if($order->updated_at != $order->created_at)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Dernière modification</span>
                    <span class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<style>
.order-details .card {
    border: 1px solid #e9ecef;
    margin-bottom: 1rem;
}

.order-details .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 0.75rem 1.25rem;
}

.order-details .table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.order-details img.rounded {
    border: 1px solid #dee2e6;
}
</style>