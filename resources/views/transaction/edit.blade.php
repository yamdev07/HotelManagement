@extends('template.master')
@section('title', 'Modifier Réservation')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>
                <i class="fas fa-edit me-2"></i>Modifier Réservation #{{ $transaction->id }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaction.index') }}">Réservations</a></li>
                    <li class="breadcrumb-item active">Modifier #{{ $transaction->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-pencil-alt me-2"></i>Informations de la Réservation
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h5>Veuillez corriger les erreurs suivantes :</h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('transaction.update', $transaction->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Client -->
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Client *</label>
                            <select id="customer_id" name="customer_id" class="form-control select2" required>
                                <option value="">-- Sélectionner un client --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ old('customer_id', $transaction->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email ?? 'Pas d\'email' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Chambre -->
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Chambre *</label>
                            <select id="room_id" name="room_id" class="form-control select2" required>
                                <option value="">-- Sélectionner une chambre --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                            data-price="{{ $room->price }}"
                                            {{ old('room_id', $transaction->room_id) == $room->id ? 'selected' : '' }}>
                                        Chambre {{ $room->number }} - {{ $room->type->name }} 
                                        ({{ Helper::formatCFA($room->price) }} / nuit)
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <strong>Prix actuel :</strong> 
                                <span id="current-price">{{ Helper::formatCFA($transaction->room->price) }}</span> / nuit
                            </div>
                        </div>
                        
                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in" class="form-label">Date d'Arrivée *</label>
                                <input type="date" class="form-control" id="check_in" name="check_in"
                                       value="{{ old('check_in', \Carbon\Carbon::parse($transaction->check_in)->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="check_out" class="form-label">Date de Départ *</label>
                                <input type="date" class="form-control" id="check_out" name="check_out"
                                       value="{{ old('check_out', \Carbon\Carbon::parse($transaction->check_out)->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                        </div>
                        
                        <!-- Calcul du prix -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h6>Calcul du séjour</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1">Nombre de nuits : <span id="nights-count">0</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">Total estimé : <span id="estimated-total">0 FCFA</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('transaction.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Select2 si présent
    if ($.fn.select2) {
        $('.select2').select2({
            placeholder: "Sélectionner une option",
            allowClear: true
        });
    }
    
    // Calculer le nombre de nuits et le total
    function calculateStay() {
        const checkIn = document.getElementById('check_in').value;
        const checkOut = document.getElementById('check_out').value;
        const roomSelect = document.getElementById('room_id');
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const pricePerNight = selectedOption ? selectedOption.getAttribute('data-price') : 0;
        
        if (checkIn && checkOut && pricePerNight) {
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const timeDiff = checkOutDate - checkInDate;
            const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            
            if (nights > 0) {
                const total = nights * parseFloat(pricePerNight);
                document.getElementById('nights-count').textContent = nights;
                document.getElementById('estimated-total').textContent = 
                    new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
            } else {
                document.getElementById('nights-count').textContent = '0';
                document.getElementById('estimated-total').textContent = '0 FCFA';
            }
        }
    }
    
    // Événements pour recalculer
    document.getElementById('check_in').addEventListener('change', calculateStay);
    document.getElementById('check_out').addEventListener('change', calculateStay);
    document.getElementById('room_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption) {
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('current-price').textContent = 
                new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
        }
        calculateStay();
    });
    
    // Calcul initial
    calculateStay();
});
</script>
@endsection