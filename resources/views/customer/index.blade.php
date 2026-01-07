@extends('template.master')
@section('title', 'Customer')
@section('content')
    <style>
        .mybg {
            background-image: linear-gradient(#1975d1, #1975d1);
        }

        .numbering {
            width: 50px;
            height: 50px;
            align-items: center;
            justify-content: center;
            padding-top: 12px;
            text-align: center;
            border-bottom-right-radius: 30px;
            border-top-left-radius: 5px;
        }

        .icon {
            font-size: 1.5rem;
            margin-right: -10px;
            color: #212529;
        }

        .customer-card {
            min-height: 350px;
            transition: transform 0.3s ease;
        }

        .customer-card:hover {
            transform: translateY(-5px);
        }

        .customer-avatar {
            object-fit: cover;
            height: 350px;
            border-top-right-radius: 0.5rem;
            border-top-left-radius: 0.5rem;
        }

        .info-table td {
            padding: 2px 5px;
            vertical-align: top;
        }

        .info-table i {
            width: 20px;
            text-align: center;
        }

        .dropdown-toggle-custom {
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="row mt-2 mb-2">
                <div class="col-lg-6 mb-2">
                    <a href="{{ route('customer.create') }}" class="btn btn-sm shadow-sm myBtn border rounded" title="Add New Customer">
                        <svg width="25" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="black">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="ms-1">Add Customer</span>
                    </a>
                </div>
                <div class="col-lg-6 mb-2">
                    <form class="d-flex" method="GET" action="{{ route('customer.index') }}">
                        <input class="form-control me-2" type="search" placeholder="Search by name" aria-label="Search" 
                               id="search" name="search" value="{{ request()->input('search') }}">
                        <button class="btn btn-outline-dark" type="submit">Search</button>
                    </form>
                </div>
            </div>

            {{-- Messages de succès/erreur --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('failed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                @forelse ($customers as $customer)
                    <div class="col-lg-2 col-md-4 col-sm-6 my-1">
                        <div class="card shadow-sm customer-card p-0 rounded">
                            {{-- En-tête avec numéro et menu --}}
                            <div class="card-header p-0 border-0" style="position: relative;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title text-white numbering bg-dark m-0">
                                        {{ ($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1 }}
                                    </h5>
                                    <div class="dropdown mt-2 me-2">
                                        <a class="dropdown-toggle-custom" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                            <i class="fa fa-ellipsis-v icon"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('customer.show', $customer->id) }}">
                                                    <i class="fas fa-eye me-2"></i>Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('customer.edit', $customer->id) }}">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" 
                                                    action="{{ route('customer.destroy', $customer->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="dropdown-item text-danger"
                                                            onclick="return confirm('Delete {{ $customer->name }}?')">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Avatar --}}
                            <img src="{{ $customer->user->getAvatar() }}" 
                                 alt="{{ $customer->name }}" 
                                 class="customer-avatar"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=1975d1&color=fff&size=350'">

                            {{-- Informations --}}
                            <div class="card-body">
                                <h5 class="card-title mb-3">{{ $customer->name }}</h5>
                                <div class="customer-info">
                                    <table class="info-table w-100">
                                        <tr>
                                            <td><i class="fas fa-envelope text-primary"></i></td>
                                            <td>
                                                <span class="text-truncate d-block" title="{{ $customer->user->email }}">
                                                    {{ $customer->user->email }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-user-md text-success"></i></td>
                                            <td>
                                                <span>{{ $customer->job ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-map-marker-alt text-danger"></i></td>
                                            <td>
                                                <span class="text-truncate d-block" title="{{ $customer->address }}">
                                                    {{ $customer->address }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-phone text-info"></i></td>
                                            <td>
                                                <span>{{ $customer->phone ?? '+6281233808395' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-birthday-cake text-warning"></i></td>
                                            <td>
                                                <span>{{ $customer->birthdate ? \Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y') : 'N/A' }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h4>No customers found</h4>
                            <p class="text-muted">There are no customers in the database yet.</p>
                            <a href="{{ route('customer.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Customer
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($customers->hasPages())
                <div class="row justify-content-md-center mt-4">
                    <div class="col-sm-10 d-flex justify-content-md-center">
                        {{ $customers->onEachSide(2)->links('template.paginationlinks') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Gestion de la suppression
        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();
            
            const customerId = $(this).data('customer-id');
            const customerName = $(this).data('customer-name');
            const formId = '#delete-customer-form-' + customerId;
            
            Swal.fire({
                title: 'Are you sure?',
                text: `Customer "${customerName}" will be permanently deleted!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                backdrop: true,
                allowOutsideClick: false,
                allowEscapeKey: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Soumettre le formulaire
                    $(formId).submit();
                    
                    // Optionnel: Afficher un loader pendant la suppression
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        });

        // Ajouter un effet de survol sur les cartes
        $('.customer-card').hover(
            function() {
                $(this).css('box-shadow', '0 8px 16px rgba(0,0,0,0.2)');
            },
            function() {
                $(this).css('box-shadow', '0 4px 8px rgba(0,0,0,0.1)');
            }
        );

        // Auto-dismiss alerts après 5 secondes
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });

    // Fallback si pas de jQuery (optionnel)
    document.addEventListener('DOMContentLoaded', function() {
        // Vous pouvez ajouter du JavaScript vanilla ici si besoin
    });
</script>
@endsection