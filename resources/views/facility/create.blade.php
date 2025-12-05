@extends('template.master')
@section('title', 'Add Facility')
@section('content')

<div class="container mt-4">
    <h2>Add New Facility</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        // Liste des icônes FontAwesome disponibles
        $icons = [
            'fas fa-wifi' => 'WiFi',
            'fas fa-swimming-pool' => 'Pool',
            'fas fa-dumbbell' => 'Gym',
            'fas fa-concierge-bell' => 'Service Bell',
            'fas fa-parking' => 'Parking',
            'fas fa-utensils' => 'Restaurant',
            'fas fa-spa' => 'Spa',
            'fas fa-tv' => 'TV',
            'fas fa-shuttle-van' => 'Shuttle',
            'fas fa-cocktail' => 'Bar',
        ];
    @endphp

    <form action="{{ route('facility.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Facility Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="detail" class="form-label">Detail</label>
            <textarea name="detail" class="form-control" required>{{ old('detail') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="icon" class="form-label">Icon (select from list)</label>
            <select name="icon" class="form-select">
                <option value="">-- No Icon --</option>
                @foreach($icons as $class => $label)
                    <option value="{{ $class }}" {{ old('icon') == $class ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save Facility</button>
        <a href="{{ route('facility.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
