@extends('template.master')
@section('title', 'Edit Facility')
@section('content')

<div class="container mt-4">
    <h2>Edit Facility</h2>

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

    <form action="{{ route('facility.update', $facility) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Facility Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $facility->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="detail" class="form-label">Detail</label>
            <textarea name="detail" class="form-control" required>{{ old('detail', $facility->detail) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="icon" class="form-label">Icon (select from list)</label>
            <select name="icon" class="form-select">
                <option value="">-- No Icon --</option>
                @foreach($icons as $class => $label)
                    <option value="{{ $class }}" {{ old('icon', $facility->icon) == $class ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Facility</button>
        <a href="{{ route('facility.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
