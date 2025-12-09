@extends('template.master')
@section('title', 'Facilities')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Facilities</h3>
    <a href="{{ route('facility.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add Facility
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facilities as $facility)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $facility->name }}</td>
                        <td>
                            @if($facility->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('facility.edit', $facility->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-sm btn-danger delete" 
                                    data-id="{{ $facility->id }}" 
                                    data-name="{{ $facility->name }}">
                                <i class="fas fa-trash"></i> Delete
                            </button>

                            <form id="delete-facility-form-{{ $facility->id }}" 
                                  action="{{ route('facility.destroy', $facility->id) }}" 
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No facilities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('footer')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.delete').click(function() {
            var facility_id = $(this).data('id');
            var facility_name = $(this).data('name');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "Facility \"" + facility_name + "\" will be deleted. You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var form_id = "#delete-facility-form-" + facility_id;
                    $(form_id).submit();
                }
            });
        });
    });
</script>
@endsection
