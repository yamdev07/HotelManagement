@extends('template.master')
@section('title', 'Compte Séjour – Chambre ' . $transaction->room->number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">
            <i class="fas fa-receipt me-2 text-success"></i>
            Compte Séjour — Chambre {{ $transaction->room->number }}
        </h3>
        <small class="text-muted">
            {{ $transaction->customer->name }} &bull;
            {{ $transaction->check_in->format('d/m/Y') }} → {{ $transaction->check_out->format('d/m/Y') }}
            &bull; <span class="badge bg-{{ $transaction->status_color }}">{{ $transaction->status_label }}</span>
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transaction.show', $transaction) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
        @if($transaction->payments()->exists())
        <a href="{{ route('transaction.invoice', $transaction) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-invoice me-1"></i> Facture finale
        </a>
        @endif
    </div>
</div>

{{-- Résumé en temps réel --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-bed fa-2x text-primary mb-2"></i>
                <div class="text-muted small">Chambre</div>
                <div class="fw-bold fs-5">{{ number_format($roomSubtotal, 0, ',', ' ') }} CFA</div>
                <small class="text-muted">{{ $transaction->nights }} nuit(s)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-utensils fa-2x text-warning mb-2"></i>
                <div class="text-muted small">Restaurant</div>
                <div class="fw-bold fs-5">{{ number_format($restaurantTotal, 0, ',', ' ') }} CFA</div>
                <small class="text-muted">{{ $transaction->restaurantOrders->whereNotIn('status', ['paid','cancelled'])->count() }} commande(s)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-concierge-bell fa-2x text-info mb-2"></i>
                <div class="text-muted small">Extras</div>
                <div class="fw-bold fs-5">{{ number_format($extrasTotal, 0, ',', ' ') }} CFA</div>
                <small class="text-muted">{{ $transaction->extras->count() }} article(s)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
            <div class="card-body text-center">
                <i class="fas fa-receipt fa-2x text-success mb-2"></i>
                <div class="text-muted small">Total à payer</div>
                <div class="fw-bold fs-4 text-success">{{ number_format($grandTotal, 0, ',', ' ') }} CFA</div>
                <small class="{{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                    @if($remaining > 0)
                        Reste : {{ number_format($remaining, 0, ',', ' ') }} CFA
                    @else
                        <i class="fas fa-check-circle"></i> Soldé
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Colonne gauche : détails --}}
    <div class="col-lg-8">

        {{-- Section Chambre --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-bed me-2"></i>
                <strong>Chambre {{ $transaction->room->number }}</strong>
                <span class="ms-auto badge bg-white text-primary">{{ $transaction->room->type->name ?? 'Standard' }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td>Nuitée × {{ $transaction->nights }}</td>
                            <td class="text-end">{{ number_format($transaction->room->price, 0, ',', ' ') }} CFA / nuit</td>
                            <td class="text-end fw-bold">{{ number_format($roomSubtotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @if($transaction->late_checkout && $transaction->late_checkout_fee > 0)
                        <tr class="table-warning">
                            <td colspan="2">Late checkout</td>
                            <td class="text-end fw-bold">+ {{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Section Restaurant --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-warning text-dark d-flex align-items-center">
                <i class="fas fa-utensils me-2"></i>
                <strong>Commandes Restaurant</strong>
                <span class="ms-auto badge bg-white text-warning">{{ number_format($restaurantTotal, 0, ',', ' ') }} CFA</span>
            </div>
            <div class="card-body p-0">
                @forelse($transaction->restaurantOrders->whereNotIn('status', ['paid','cancelled']) as $order)
                <div class="border-bottom px-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Commande #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            &bull; {{ $order->created_at->format('d/m H:i') }}
                        </small>
                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : 'secondary' }}">{{ $order->status }}</span>
                    </div>
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between small ps-2 mt-1">
                        <span>{{ $item->menu->name ?? '—' }} × {{ $item->quantity }}</span>
                        <span>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} CFA</span>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-end mt-1">
                        <strong class="small">{{ number_format($order->total, 0, ',', ' ') }} CFA</strong>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-utensils mb-1 d-block"></i>
                    Aucune commande restaurant sur ce séjour
                </div>
                @endforelse
            </div>
        </div>

        {{-- Section Extras --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="fas fa-concierge-bell me-2"></i>
                <strong>Extras (minibar, lessive, services…)</strong>
                <span class="ms-auto badge bg-white text-info">{{ number_format($extrasTotal, 0, ',', ' ') }} CFA</span>
            </div>
            <div class="card-body p-0">
                @forelse($transaction->extras as $extra)
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                    <div>
                        <i class="fas {{ $extra->category_icon }} text-muted me-2"></i>
                        <span>{{ $extra->description }}</span>
                        <small class="text-muted ms-1">({{ $extra->category_label }})</small>
                        @if($extra->quantity > 1)
                            <small class="text-muted ms-1">× {{ $extra->quantity }}</small>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <strong>{{ number_format($extra->subtotal, 0, ',', ' ') }} CFA</strong>
                        @can('checkrole:Super,Admin,Receptionist')
                        <form method="POST" action="{{ route('transaction.extras.destroy', [$transaction, $extra]) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger py-0"
                                onclick="return confirm('Supprimer cet extra ?')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-concierge-bell mb-1 d-block"></i>
                    Aucun extra enregistré
                </div>
                @endforelse
            </div>
        </div>

        {{-- Récapitulatif total --}}
        <div class="card border-0 shadow-sm border-start border-4 border-success">
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted">Chambre</td>
                            <td class="text-end">{{ number_format($roomSubtotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Restaurant</td>
                            <td class="text-end">{{ number_format($restaurantTotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Extras</td>
                            <td class="text-end">{{ number_format($extrasTotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @if($transaction->late_checkout && $transaction->late_checkout_fee > 0)
                        <tr>
                            <td class="text-muted">Late checkout</td>
                            <td class="text-end">{{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @endif
                        <tr class="border-top">
                            <td class="fw-bold fs-5">TOTAL FACTURE</td>
                            <td class="text-end fw-bold fs-5 text-success">{{ number_format($grandTotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr class="text-muted">
                            <td>Déjà payé</td>
                            <td class="text-end">− {{ number_format($totalPaid, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr>
                            <td class="fw-bold {{ $remaining > 0 ? 'text-danger' : 'text-success' }}">Reste à payer</td>
                            <td class="text-end fw-bold {{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($remaining, 0, ',', ' ') }} CFA
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Colonne droite : actions --}}
    <div class="col-lg-4">

        {{-- Ajouter un extra --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-plus me-2"></i><strong>Ajouter un extra</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transaction.extras.store', $transaction) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label form-label-sm">Catégorie</label>
                        <select name="category" class="form-select form-select-sm" required>
                            @foreach($extraCategories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label form-label-sm">Description</label>
                        <input type="text" name="description" class="form-control form-control-sm"
                            placeholder="Ex: Coca-Cola, Lessive chemise…" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-7">
                            <label class="form-label form-label-sm">Prix unitaire (CFA)</label>
                            <input type="number" name="amount" class="form-control form-control-sm"
                                min="0" step="50" required>
                        </div>
                        <div class="col-5">
                            <label class="form-label form-label-sm">Qté</label>
                            <input type="number" name="quantity" class="form-control form-control-sm"
                                min="1" value="1" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-plus me-1"></i> Ajouter à la facture
                    </button>
                </form>
            </div>
        </div>

        {{-- Informations client --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <i class="fas fa-user me-2"></i><strong>Client</strong>
            </div>
            <div class="card-body small">
                <div><strong>{{ $transaction->customer->name }}</strong></div>
                <div class="text-muted">{{ $transaction->customer->phone ?? '—' }}</div>
                <div class="text-muted">{{ $transaction->customer->email ?? '—' }}</div>
                <div class="text-muted">{{ $transaction->customer->nationality ?? '' }}</div>
                @if($transaction->person_count > 1)
                <div class="mt-1"><i class="fas fa-users text-muted me-1"></i>{{ $transaction->person_count }} personnes</div>
                @endif
            </div>
        </div>

        {{-- Paiement rapide --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <i class="fas fa-cash-register me-2"></i><strong>Paiement</strong>
            </div>
            <div class="card-body text-center">
                @if($remaining > 0)
                <p class="text-danger mb-2 small">Solde restant : <strong>{{ number_format($remaining, 0, ',', ' ') }} CFA</strong></p>
                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-success btn-sm w-100 mb-2">
                    <i class="fas fa-credit-card me-1"></i> Enregistrer un paiement
                </a>
                @else
                <p class="text-success mb-2 small"><i class="fas fa-check-circle me-1"></i> Compte soldé</p>
                @endif
                @if($transaction->payments()->exists())
                <a href="{{ route('transaction.invoice', $transaction) }}" class="btn btn-outline-success btn-sm w-100">
                    <i class="fas fa-file-pdf me-1"></i> Voir la facture
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif
@endsection
