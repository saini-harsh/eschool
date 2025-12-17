@extends('layouts.admin')
@section('title', 'Admin | Salary Structures')
@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Salary Structures</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Salary Structures</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.salary.structures.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Add Structure
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.salary.structures.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Institution</label>
                    <select name="institution_id" class="form-select">
                        <option value="">All Institutions</option>
                        @foreach($institutions as $institution)
                            <option value="{{ $institution->id }}" {{ request('institution_id') == $institution->id ? 'selected' : '' }}>
                                {{ $institution->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="ti ti-filter"></i></button>
                    <a href="{{ route('admin.salary.structures.index') }}" class="btn btn-outline-secondary"><i class="ti ti-x"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Structures Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($structures->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Institution</th>
                                <th>Name</th>
                                <th>Components</th>
                                <th>Earnings</th>
                                <th>Deductions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($structures as $index => $structure)
                                <tr>
                                    <td>{{ $structures->firstItem() + $index }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $structure->institution->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $structure->name }}</strong>
                                        @if($structure->description)
                                            <br><small class="text-muted">{{ Str::limit($structure->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $structure->components->count() }}</td>
                                    <td>
                                        @if($structure->earnings->count() > 0)
                                            <span class="text-success">{{ $structure->earnings->count() }} items</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($structure->deductions->count() > 0)
                                            <span class="text-danger">{{ $structure->deductions->count() }} items</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                data-id="{{ $structure->id }}" 
                                                {{ $structure->status ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.salary.structures.edit', $structure->id) }}" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger delete-btn" 
                                                    data-id="{{ $structure->id }}" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $structures->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ti ti-file-text fs-1 text-muted"></i>
                    <p class="mt-3 mb-0 text-muted">No salary structures found</p>
                    <a href="{{ route('admin.salary.structures.create') }}" class="btn btn-primary mt-3">
                        <i class="ti ti-plus me-1"></i> Create First Structure
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Status toggle
    $('.status-toggle').on('change', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/salary/structures") }}/' + id + '/status',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Failed to update status');
                $(this).prop('checked', !$(this).prop('checked'));
            }
        });
    });

    // Delete
    $('.delete-btn').on('click', function() {
        if (confirm('Are you sure you want to delete this salary structure?')) {
            const id = $(this).data('id');
            $('#deleteForm').attr('action', '{{ url("admin/salary/structures") }}/' + id + '/delete').submit();
        }
    });
});
</script>
@endpush
