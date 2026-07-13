@extends('platform.layout')
@section('title', $hotel->name)
@section('content')
    <a href="{{ route('platform.hotels.index') }}" class="btn btn-outline-secondary btn-sm mb-3">← Retour</a>
    <h3 class="fw-bold">{{ $hotel->name }}</h3>
    <p class="text-muted">{{ $hotel->planName() }} · {{ $hotel->subscriptions->count() }} période(s) d'abonnement · {{ number_format($hotel->totalPaid(), 0, ',', ' ') }} CFA encaissés</p>
@endsection
