@extends('template.master')
@section('title', __('my-reservations.page_title'))
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>
                <i class="fas fa-calendar-alt me-2"></i>
                @if($isCustomer)
                    {{ __('my-reservations.my_reservations') }}
                @else
                    {{ __('my-reservations.all_reservations') }}
                @endif
            </h2>
        </div>
    </div>

    <!-- Si client, montrer seulement ses réservations -->
    @if($isCustomer)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        {{ __('my-reservations.customer_info') }}
    </div>
    @endif

    <!-- Liste des réservations actives -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3">
                <i class="fas fa-clock me-2"></i>{{ __('my-reservations.active_reservations') }}
                <span class="badge bg-primary">{{ $transactions->count() }}</span>
            </h5>
            
            @if($transactions->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('my-reservations.room') }}</th>
                                    <th>{{ __('my-reservations.arrival') }}</th>
                                    <th>{{ __('my-reservations.departure') }}</th>
                                    <th>{{ __('my-reservations.nights') }}</th>
                                    <th>{{ __('my-reservations.total') }}</th>
                                    <th>{{ __('my-reservations.paid') }}</th>
                                    <th>{{ __('my-reservations.status') }}</th>
                                    <th>{{ __('my-reservations.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->room->number }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_in) }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_out) }}</td>
                                    <td>{{ $transaction->getDateDifferenceWithPlural() }}</td>
                                    <td>{{ Helper::formatCFA($transaction->getTotalPrice()) }}</td>
                                    <td>{{ Helper::formatCFA($transaction->getTotalPayment()) }}</td>
                                    <td>
                                        @if($transaction->getTotalPrice() - $transaction->getTotalPayment() <= 0)
                                            <span class="badge bg-success">{{ __('my-reservations.status_paid') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('my-reservations.status_pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('transaction.show.public', $transaction->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ __('my-reservations.no_active_reservations') }}
            </div>
            @endif
        </div>
    </div>

    <!-- Historique des réservations -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3">
                <i class="fas fa-history me-2"></i>{{ __('my-reservations.past_reservations') }}
                <span class="badge bg-secondary">{{ $transactionsExpired->count() }}</span>
            </h5>
            
            @if($transactionsExpired->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('my-reservations.room') }}</th>
                                    <th>{{ __('my-reservations.arrival') }}</th>
                                    <th>{{ __('my-reservations.departure') }}</th>
                                    <th>{{ __('my-reservations.nights') }}</th>
                                    <th>{{ __('my-reservations.total') }}</th>
                                    <th>{{ __('my-reservations.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactionsExpired as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->room->number }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_in) }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_out) }}</td>
                                    <td>{{ $transaction->getDateDifferenceWithPlural() }}</td>
                                    <td>{{ Helper::formatCFA($transaction->getTotalPrice()) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ __('my-reservations.status_completed') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ __('my-reservations.no_past_reservations') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection