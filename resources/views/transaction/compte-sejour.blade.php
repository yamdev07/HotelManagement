@extends('template.master')
@section('title', __('compte-sejour.page_title', ['room' => $transaction->room->number]))

@section('content')
<style>
/* ── Compte séjour : style épuré + couleur de marque (remplace le Bootstrap brut) ── */
.folio-page .card { border: 1px solid var(--s200, #e5e7eb) !important; box-shadow: 0 1px 2px rgba(20,40,30,.04) !important; border-radius: 14px !important; overflow: hidden; }
.folio-page .card-header { font-weight: 700 !important; border-bottom: 1px solid var(--s100, #eef0ee) !important; background: var(--white, #fff) !important; color: var(--s800, #1f2937) !important; }
.folio-page .card-header i { color: var(--g600) !important; }
/* En-tête chambre : accent marque plein */
.folio-page .card-header.bg-primary { background: var(--g600) !important; color: #fff !important; border-bottom: 0 !important; }
.folio-page .card-header.bg-primary i, .folio-page .card-header.bg-primary .badge { color: #fff !important; }
/* Restaurant / Extras / Ajouter : en-têtes clairs avec liseré marque (fini le jaune/cyan/dark) */
.folio-page .card-header.bg-warning, .folio-page .card-header.bg-info, .folio-page .card-header.bg-dark { background: var(--tint, #f4f7f5) !important; color: var(--s800, #1f2937) !important; border-bottom: 2px solid var(--g500) !important; }
.folio-page .card-header.bg-warning i, .folio-page .card-header.bg-info i, .folio-page .card-header.bg-dark i { color: var(--g600) !important; }
.folio-page .card-header .badge.bg-light, .folio-page .card-header .badge { background: rgba(0,0,0,.08) !important; color: inherit !important; }
.folio-page .card-header.bg-light { background: var(--surface, #f8faf9) !important; color: var(--s800) !important; }
/* Accents texte/boutons -> marque */
.folio-page .text-primary, .folio-page .text-warning, .folio-page .text-info, .folio-page .text-success { color: var(--g600) !important; }
.folio-page .btn-success { background: var(--g600) !important; border-color: var(--g600) !important; }
.folio-page .btn-success:hover { background: var(--g700) !important; border-color: var(--g700) !important; }
.folio-page .btn-outline-secondary { color: var(--s600); border-color: var(--s300); }
</style>
<div class="folio-page">
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">
            <i class="fas fa-receipt me-2 text-success"></i>
            {{ __('compte-sejour.page_heading', ['room' => $transaction->room->number]) }}
        </h3>
        <small class="text-muted">
            {{ $transaction->customer->name }} &bull;
            {{ $transaction->check_in->format('d/m/Y') }} → {{ $transaction->check_out->format('d/m/Y') }}
            &bull; <span class="badge bg-{{ $transaction->status_color }}">{{ $transaction->status_label }}</span>
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transaction.show', $transaction) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> {{ __('compte-sejour.back') }}
        </a>
        @if($transaction->payments()->exists())
        <a href="{{ route('transaction.invoice', $transaction) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-invoice me-1"></i> {{ __('compte-sejour.final_invoice') }}
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
                <div class="text-muted small">{{ __('compte-sejour.room') }}</div>
                <div class="fw-bold fs-5">{{ number_format($roomSubtotal, 0, ',', ' ') }} CFA</div>
                <small class="text-muted">{{ __('compte-sejour.nights_count', ['count' => $transaction->nights]) }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-utensils fa-2x text-warning mb-2"></i>
                <div class="text-muted small">{{ __('compte-sejour.restaurant') }}</div>
                <div class="fw-bold fs-5">{{ number_format($restaurantTotal, 0, ',', ' ') }} CFA</div>
                <small class="text-muted">{{ __('compte-sejour.orders_count', ['count' => $transaction->restaurantOrders->whereNotIn('status', ['paid','cancelled'])->count()]) }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="fas fa-concierge-bell fa-2x text-info mb-2"></i>
                <div class="text-muted small">{{ __('compte-sejour.extras') }}</div>
                <div class="fw-bold fs-5">{{ number_format($extrasTotal, 0, ',', ' ') }} CFA</div>
                <small class="text-muted">{{ __('compte-sejour.articles_count', ['count' => $transaction->extras->count()]) }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
            <div class="card-body text-center">
                <i class="fas fa-receipt fa-2x text-success mb-2"></i>
                <div class="text-muted small">{{ __('compte-sejour.total_to_pay') }}</div>
                <div class="fw-bold fs-4 text-success">{{ number_format($grandTotal, 0, ',', ' ') }} CFA</div>
                <small class="{{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                    @if($remaining > 0)
                        {{ __('compte-sejour.remaining', ['amount' => number_format($remaining, 0, ',', ' ')]) }}
                    @else
                        <i class="fas fa-check-circle"></i> {{ __('compte-sejour.paid') }}
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
                <strong>{{ __('compte-sejour.room_section_title', ['room' => $transaction->room->number]) }}</strong>
                <span class="ms-auto badge bg-white text-primary">{{ $transaction->room->type->name ?? 'Standard' }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td>{{ __('compte-sejour.night_x_count', ['count' => $transaction->nights]) }}</td>
                            <td class="text-end">{{ __('compte-sejour.price_per_night', ['price' => number_format($transaction->room->price, 0, ',', ' ')]) }}</td>
                            <td class="text-end fw-bold">{{ number_format($roomSubtotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @if($transaction->late_checkout && $transaction->late_checkout_fee > 0)
                        <tr class="table-warning">
                            <td colspan="2">{{ __('compte-sejour.late_checkout') }}</td>
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
                <strong>{{ __('compte-sejour.restaurant_orders') }}</strong>
                <span class="ms-auto badge bg-white text-warning">{{ number_format($restaurantTotal, 0, ',', ' ') }} CFA</span>
            </div>
            <div class="card-body p-0">
                @forelse($transaction->restaurantOrders->whereNotIn('status', ['paid','cancelled']) as $order)
                <div class="border-bottom px-3 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            {{ __('compte-sejour.order_number', ['number' => str_pad($order->id, 5, '0', STR_PAD_LEFT)]) }}
                            &bull; {{ $order->created_at->format('d/m H:i') }}
                        </small>
                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : 'secondary' }}">{{ $order->status }}</span>
                    </div>
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between small ps-2 mt-1">
                        <span>{{ $item->menu->name ?? '·' }} × {{ $item->quantity }}</span>
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
                    {{ __('compte-sejour.no_restaurant_orders') }}
                </div>
                @endforelse
            </div>
        </div>

        {{-- Section Extras --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="fas fa-concierge-bell me-2"></i>
                <strong>{{ __('compte-sejour.extras_title') }}</strong>
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
                                onclick="return confirm('{{ __('compte-sejour.delete_extra_confirm') }}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-concierge-bell mb-1 d-block"></i>
                    {{ __('compte-sejour.no_extras') }}
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
                            <td class="text-muted">{{ __('compte-sejour.room') }}</td>
                            <td class="text-end">{{ number_format($roomSubtotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('compte-sejour.restaurant') }}</td>
                            <td class="text-end">{{ number_format($restaurantTotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('compte-sejour.extras') }}</td>
                            <td class="text-end">{{ number_format($extrasTotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @if($transaction->late_checkout && $transaction->late_checkout_fee > 0)
                        <tr>
                            <td class="text-muted">{{ __('compte-sejour.late_checkout') }}</td>
                            <td class="text-end">{{ number_format($transaction->late_checkout_fee, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @endif
                        <tr class="border-top">
                            <td class="fw-bold fs-5">{{ __('compte-sejour.total_invoice') }}</td>
                            <td class="text-end fw-bold fs-5 text-success">{{ number_format($grandTotal, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr class="text-muted">
                            <td>{{ __('compte-sejour.already_paid') }}</td>
                            <td class="text-end">− {{ number_format($totalPaid, 0, ',', ' ') }} CFA</td>
                        </tr>
                        <tr>
                            <td class="fw-bold {{ $remaining > 0 ? 'text-danger' : 'text-success' }}">{{ __('compte-sejour.remaining_to_pay') }}</td>
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
                <i class="fas fa-plus me-2"></i><strong>{{ __('compte-sejour.add_extra') }}</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transaction.extras.store', $transaction) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label form-label-sm">{{ __('compte-sejour.category') }}</label>
                        <select name="category" class="form-select form-select-sm" required>
                            @foreach($extraCategories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label form-label-sm">{{ __('compte-sejour.description') }}</label>
                        <input type="text" name="description" class="form-control form-control-sm"
                            placeholder="{{ __('compte-sejour.description_placeholder') }}" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-7">
                            <label class="form-label form-label-sm">{{ __('compte-sejour.unit_price') }}</label>
                            <input type="number" name="amount" class="form-control form-control-sm"
                                min="0" step="50" required>
                        </div>
                        <div class="col-5">
                            <label class="form-label form-label-sm">{{ __('compte-sejour.quantity') }}</label>
                            <input type="number" name="quantity" class="form-control form-control-sm"
                                min="1" value="1" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-plus me-1"></i> {{ __('compte-sejour.add_to_invoice') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Informations client --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <i class="fas fa-user me-2"></i><strong>{{ __('compte-sejour.customer') }}</strong>
            </div>
            <div class="card-body small">
                <div><strong>{{ $transaction->customer->name }}</strong></div>
                <div class="text-muted">{{ $transaction->customer->phone ?? '·' }}</div>
                <div class="text-muted">{{ $transaction->customer->email ?? '·' }}</div>
                <div class="text-muted">{{ $transaction->customer->nationality ?? '' }}</div>
                @if($transaction->person_count > 1)
                <div class="mt-1"><i class="fas fa-users text-muted me-1"></i>{{ __('compte-sejour.persons_count', ['count' => $transaction->person_count]) }}</div>
                @endif
            </div>
        </div>

        {{-- Paiement rapide --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <i class="fas fa-cash-register me-2"></i><strong>{{ __('compte-sejour.payment') }}</strong>
            </div>
            <div class="card-body text-center">
                @if($remaining > 0)
                <p class="text-danger mb-2 small">{{ __('compte-sejour.remaining_balance', ['amount' => number_format($remaining, 0, ',', ' ')]) }}</p>
                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-success btn-sm w-100 mb-2">
                    <i class="fas fa-credit-card me-1"></i> {{ __('compte-sejour.record_payment') }}
                </a>
                @else
                <p class="text-success mb-2 small"><i class="fas fa-check-circle me-1"></i> {{ __('compte-sejour.account_paid') }}</p>
                @endif
                @if($transaction->payments()->exists())
                <a href="{{ route('transaction.invoice', $transaction) }}" class="btn btn-outline-success btn-sm w-100">
                    <i class="fas fa-file-pdf me-1"></i> {{ __('compte-sejour.view_invoice') }}
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
</div>{{-- /.folio-page --}}
@endsection
