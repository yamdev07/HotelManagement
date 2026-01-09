@extends('template.master')
@section('title', 'Room Management')
@section('content')
    <style>
        .add-room-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .add-room-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
        }
        .btn-view { color: #4299e1; background: #ebf8ff; }
        .btn-edit { color: #48bb78; background: #f0fff4; }
        .btn-delete { color: #f56565; background: #fff5f5; }
        .btn-delete:hover { background: #fed7d7; }
        
        .badge {
            padding: 0.5em 1em;
            font-size: 0.85em;
        }
        
        .price-cfa {
            font-weight: 600;
            color: #2d3748;
        }
    </style>

    <div class="container-fluid">
        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Bouton Add - LIEN DIRECT SIMPLE -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('room.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add New Room
                </a>
            </div>
        </div>

        <!-- Table des chambres -->
        <div class="card shadow">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="fas fa-bed me-2"></i>Room Management</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Room #</th>
                                <th>Type</th>
                                <th>Capacity</th>
                                <th>Price (CFA)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                            <tr>
                                <td><strong>{{ $room->number }}</strong></td>
                                <td>{{ $room->type->name ?? 'N/A' }}</td>
                                <td>{{ $room->capacity }} persons</td>
                                <td class="price-cfa">
                                    <!-- Format CFA avec séparateurs de milliers -->
                                    {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                    @if($room->price > 0)
                                        <small class="text-muted d-block">
                                            ≈ {{ number_format($room->price / 655, 2, ',', ' ') }} €
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $room->roomStatus->color ?? 'info' }}">
                                        {{ $room->roomStatus->name ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- View -->
                                        <a href="{{ route('room.show', $room->id) }}" 
                                           class="btn-action btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Edit -->
                                        <a href="{{ route('room.edit', $room->id) }}" 
                                           class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Delete -->
                                        <form method="POST" 
                                              action="{{ route('room.destroy', $room->id) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete room {{ $room->number }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn-action btn-delete"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-bed fa-2x text-muted mb-3"></i>
                                    <h5>No Rooms Found</h5>
                                    <p class="text-muted">Add your first room to get started</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($rooms->hasPages())
                    <div class="mt-3">
                        {{ $rooms->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
// Auto-hide alerts après 5 secondes
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endsection