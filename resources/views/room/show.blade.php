@extends('template.master')
@section('title', 'Room Details')
@section('content')
    <style>
        .room-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
        }
        
        .customer-avatar {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }
        
        .room-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        
        .room-details-table tr td:first-child {
            width: 40px;
            vertical-align: top;
            color: #667eea;
        }
        
        .image-card {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .image-card:hover {
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .room-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            text-align: center;
        }
        
        .empty-state i {
            font-size: 48px;
            color: #cbd5e0;
            margin-bottom: 15px;
        }
    </style>

    <div class="container-fluid">
        <!-- En-tête avec le numéro de chambre -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-0">
                    <i class="fas fa-bed me-2"></i>Room Details: {{ $room->number }}
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('room.index') }}">Rooms</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $room->number }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row g-4">
            <!-- Colonne 1: Client actuel (si occupé) -->
            <div class="col-lg-3">
                @if (!empty($customer))
                <div class="card room-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Current Guest</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="text-center p-3">
                            <img class="customer-avatar" src="{{ $customer->user->getAvatar() }}" 
                                 alt="{{ $customer->name }}">
                        </div>
                        <div class="p-3">
                            <h4 class="mb-3">{{ $customer->name }}</h4>
                            <div class="room-details-table">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><i class="fas fa-envelope"></i></td>
                                        <td>{{ $customer->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-briefcase"></i></td>
                                        <td>{{ $customer->job ?? 'Not specified' }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-map-marker-alt"></i></td>
                                        <td>{{ $customer->address ?? 'Not specified' }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-phone"></i></td>
                                        <td>{{ $customer->phone ?? 'Not specified' }}</td>
                                    </tr>
                                    @if($customer->birthdate)
                                    <tr>
                                        <td><i class="fas fa-birthday-cake"></i></td>
                                        <td>{{ \Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y') }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card room-card h-100">
                    <div class="card-body empty-state">
                        <i class="fas fa-user-slash"></i>
                        <h5 class="text-muted">Room Available</h5>
                        <p class="text-muted mb-0">No guest currently staying in this room</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Colonne 2: Détails de la chambre -->
            <div class="col-lg-5">
                <div class="card room-card h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Room Information</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#imageUploadModal">
                            <i class="fas fa-upload me-1"></i>Upload Image
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Informations principales -->
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">Room Type</h6>
                                        <h4 class="card-title">{{ $room->type->name ?? 'N/A' }}</h4>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">Status</h6>
                                        <span class="room-badge bg-{{ $room->roomStatus->color ?? 'info' }}">
                                            {{ $room->roomStatus->name ?? 'Unknown' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Détails supplémentaires -->
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle p-3 me-3">
                                                        <i class="fas fa-users text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Capacity</h6>
                                                        <h4 class="mb-0">{{ $room->capacity }} persons</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success rounded-circle p-3 me-3">
                                                        <i class="fas fa-money-bill-wave text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Price</h6>
                                                        <h4 class="mb-0">{{ number_format($room->price, 0, ',', ' ') }} FCFA</h4>
                                                        <small class="text-muted">
                                                            ≈ {{ number_format($room->price / 655, 2, ',', ' ') }} €
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($room->view)
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6><i class="fas fa-mountain me-2"></i>View</h6>
                                                <p class="mb-0">{{ $room->view }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne 3: Images de la chambre -->
            <div class="col-lg-4">
                <div class="card room-card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Room Images</h5>
                    </div>
                    <div class="card-body">
                        @php
                            // S'assurer que nous avons toujours une collection
                            $images = $room->images ?? ($room->image ?? collect());
                        @endphp
                        
                        @if($images && $images->count() > 0)
                            @foreach ($images as $image)
                                <div class="card image-card mb-3">
                                    <img src="{{ asset('img/room/' . $room->number . '/' . $image->url) }}" 
                                        class="room-image" 
                                        alt="Room Image"
                                        onclick="openImageModal('{{ asset('img/room/' . $room->number . '/' . $image->url) }}')"
                                        style="cursor: pointer;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $image->created_at->format('d/m/Y H:i') }}
                                            </small>
                                            @if(auth()->user()->role === 'Super' || auth()->user()->role === 'Admin')
                                                <form action="{{ route('image.destroy', $image->id) }}" 
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this image?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-images"></i>
                                <h5 class="text-muted">No Images</h5>
                                <p class="text-muted mb-3">This room doesn't have any images yet</p>
                                @if(auth()->user()->role === 'Super' || auth()->user()->role === 'Admin')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#imageUploadModal">
                                        <i class="fas fa-upload me-1"></i>Upload First Image
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal pour upload d'image -->
            <div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageUploadModalLabel">
                                <i class="fas fa-upload me-2"></i>Upload Room Image
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('image.store', ['room' => $room->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="image" class="form-label">Select Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                        name="image" id="image" accept="image/*" required>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Supported formats: JPG, PNG, GIF. Max size: 2MB.
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i>Upload Image
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Modal pour voir l'image en grand -->
    <div class="modal fade" id="imageViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Room Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="fullSizeImage" src="" alt="Full Size Image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
// Fonction pour ouvrir l'image en grand
function openImageModal(imageUrl) {
    document.getElementById('fullSizeImage').src = imageUrl;
    const modal = new bootstrap.Modal(document.getElementById('imageViewModal'));
    modal.show();
}

// Auto-fermeture des alertes toast
@if(session('success'))
    toastr.success("{{ session('success') }}", "Success");
@endif

@if(session('failed'))
    toastr.error("{{ session('failed') }}", "Failed");
@endif

@error('image')
    toastr.error("{{ $message }}", "Upload Failed");
    // Ouvrir automatiquement le modal d'upload s'il y a une erreur
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('imageUploadModal'));
        modal.show();
    });
@enderror
</script>
@endsection