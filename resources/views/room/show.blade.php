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
    .room-card:hover { transform: translateY(-5px); }

    .customer-avatar {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
    }

    .room-badge {
        font-size: 0.8rem;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-block;
        white-space: nowrap;
    }

    .image-card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
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
        gap: 12px;
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e0;
    }
</style>

<div class="container-fluid px-4">
    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-12 flex flex-col gap-2">
            <h2 class="flex items-center gap-2">
                <i class="fas fa-bed"></i> Room Details: {{ $room->number }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('room.index') }}">Rooms</a></li>
                    <li class="breadcrumb-item active">{{ $room->number }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        <!-- CURRENT GUEST -->
        <div class="col-lg-3">
            @if (!empty($customer))
            <div class="card room-card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 flex items-center gap-2">
                        <i class="fas fa-user"></i> Current Guest
                    </h5>
                </div>

                <div class="card-body p-0">
                    <img class="customer-avatar" src="{{ $customer->user->getAvatar() }}" alt="{{ $customer->name }}">

                    <div class="p-4 flex flex-col gap-3">
                        <h4 class="text-lg font-semibold break-words leading-tight">
                            {{ $customer->name }}
                        </h4>

                        <table class="table table-borderless w-full text-sm">
                            <tr>
                                <td class="w-8"><i class="fas fa-envelope"></i></td>
                                <td class="break-all">{{ $customer->user->email }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-briefcase"></i></td>
                                <td class="break-words">{{ $customer->job ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-map-marker-alt"></i></td>
                                <td class="break-words">{{ $customer->address ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-phone"></i></td>
                                <td class="whitespace-nowrap">{{ $customer->phone ?? 'Not specified' }}</td>
                            </tr>
                            @if($customer->birthdate)
                            <tr>
                                <td><i class="fas fa-birthday-cake"></i></td>
                                <td class="whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endif
                        </table>
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

        <!-- ROOM INFORMATION -->
        <div class="col-lg-5">
            <div class="card room-card h-100">
                <div class="card-header bg-white flex justify-between items-center">
                    <h5 class="mb-0 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Room Information
                    </h5>
                    <button class="btn btn-primary btn-sm flex items-center gap-1"
                            data-bs-toggle="modal" data-bs-target="#imageUploadModal">
                        <i class="fas fa-upload"></i> Upload Image
                    </button>
                </div>

                <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Room Type -->
                    <div class="card bg-light h-full">
                        <div class="card-body">
                            <h6 class="text-muted">Room Type</h6>
                            <h4 class="whitespace-nowrap">{{ $room->type->name ?? 'N/A' }}</h4>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="card bg-light h-full">
                        <div class="card-body">
                            <h6 class="text-muted">Status</h6>
                            <span class="room-badge bg-{{ $room->roomStatus->color ?? 'info' }}">
                                {{ $room->roomStatus->name ?? 'Unknown' }}
                            </span>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div class="card">
                        <div class="card-body flex items-center gap-4">
                            <div class="bg-primary rounded-circle p-2">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Capacity</h6>
                                <h4 class="text-lg font-semibold whitespace-nowrap">
                                    {{ $room->capacity }} persons
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="card">
                        <div class="card-body flex items-center gap-4">
                            <div class="bg-success rounded-circle p-2">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Price</h6>
                                <h4 class="text-lg font-semibold whitespace-nowrap">
                                    {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                </h4>
                                <small class="text-muted whitespace-nowrap">
                                    ≈ {{ number_format($room->price / 655, 2, ',', ' ') }} €
                                </small>
                            </div>
                        </div>
                    </div>

                    @if($room->view)
                    <div class="card md:col-span-2">
                        <div class="card-body">
                            <h6><i class="fas fa-mountain me-2"></i>View</h6>
                            <p class="mb-0 break-words">{{ $room->view }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ROOM IMAGES -->
        <div class="col-lg-4">
            <div class="card room-card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 flex items-center gap-2">
                        <i class="fas fa-images"></i> Room Images
                    </h5>
                </div>

                <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse ($room->images ?? [] as $image)
                        <div class="card image-card">
                            <img src="{{ $image->getRoomImage() }}"
                                 class="room-image cursor-pointer"
                                 onclick="openImageModal('{{ $image->getRoomImage() }}')">
                            <div class="card-body flex justify-between items-center">
                                <small class="text-muted whitespace-nowrap">
                                    {{ $image->created_at->format('d/m/Y H:i') }}
                                </small>
                                <form action="{{ route('image.destroy', $image->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state md:col-span-2">
                            <i class="fas fa-images"></i>
                            <h5 class="text-muted">No Images</h5>
                            <button class="btn btn-primary px-4 py-2 flex items-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#imageUploadModal">
                                <i class="fas fa-upload"></i> Upload First Image
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
