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
        
        .room-name {
            font-weight: 500;
            color: #4a5568;
        }
        
        .room-display-name {
            font-weight: 600;
            color: #2d3748;
        }
        
        .room-number {
            font-weight: 600;
            color: #2d3748;
            background: #f7fafc;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
        }
        
        .room-type-badge {
            font-size: 0.75rem;
            background: #e9ecef;
            color: #495057;
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
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-bed me-2"></i>Room Management</h4>
                <div class="text-muted small">
                    <span class="me-3">{{ $rooms->total() }} rooms total</span>
                    @if($rooms->total() > 0)
                        <span>Showing {{ $rooms->firstItem() }}-{{ $rooms->lastItem() }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Room #</th>
                                <th>Room Name</th>
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
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="room-number">{{ $room->number }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="room-display-name">
                                            {{ $room->display_name ?? $room->getNameOrNumber() }}
                                        </span>
                                        @if($room->name && $room->name !== $room->display_name)
                                            <small class="text-muted">
                                                <i class="fas fa-signature me-1"></i>{{ $room->name }}
                                            </small>
                                        @endif
                                        @if($room->view)
                                            <small class="text-muted mt-1">
                                                <i class="fas fa-mountain me-1"></i>{{ $room->view }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span>{{ $room->type->name ?? 'N/A' }}</span>
                                    @if($room->type && $room->type->base_price)
                                        <small class="d-block text-muted">
                                            Base: {{ number_format($room->type->base_price, 0, ',', ' ') }} FCFA
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        <span>{{ $room->capacity }} persons</span>
                                    </div>
                                </td>
                                <td class="price-cfa">
                                    <!-- Format CFA avec séparateurs de milliers -->
                                    <div class="d-flex flex-column">
                                        <span>{{ number_format($room->price, 0, ',', ' ') }} FCFA</span>
                                        @if($room->price > 0)
                                            <small class="text-muted">
                                                ≈ {{ number_format($room->price / 655, 2, ',', ' ') }} €
                                            </small>
                                            @if($room->type && $room->type->base_price && $room->price != $room->type->base_price)
                                                <small class="text-warning">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    Custom price
                                                </small>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-{{ $room->roomStatus->color ?? 'info' }} me-2">
                                            <i class="{{ $room->status_icon ?? 'fa-door-closed' }} me-1"></i>
                                            {{ $room->roomStatus->name ?? 'Unknown' }}
                                        </span>
                                        @if($room->is_available_today)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Available
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- View -->
                                        <a href="{{ route('room.show', $room->id) }}" 
                                           class="btn-action btn-view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Edit -->
                                        <a href="{{ route('room.edit', $room->id) }}" 
                                           class="btn-action btn-edit" title="Edit Room">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Delete -->
                                        @if(auth()->user()->role === 'Super' || auth()->user()->role === 'Admin')
                                            <form method="POST" 
                                                  action="{{ route('room.destroy', $room->id) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Delete room {{ $room->number }}? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn-action btn-delete"
                                                        title="Delete Room">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                                        <h5>No Rooms Found</h5>
                                        <p class="text-muted mb-4">You haven't added any rooms yet</p>
                                        <a href="{{ route('room.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add Your First Room
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($rooms->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $rooms->firstItem() }} to {{ $rooms->lastItem() }} of {{ $rooms->total() }} entries
                        </div>
                        <div>
                            {{ $rooms->links() }}
                        </div>
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

// Tooltip pour les boutons d'action
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection