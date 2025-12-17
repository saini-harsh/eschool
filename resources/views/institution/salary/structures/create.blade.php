@extends('layouts.institution')
@section('title', 'Create Salary Structure')
@section('content')
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Create Salary Structure</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('institution.salary.structures.index') }}">Salary Structures</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <form id="salaryStructureForm">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Basic Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Structure Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g., Standard Teacher Salary" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Optional description"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Salary Preview -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Salary Preview</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Test Base Salary</label>
                                <input type="number" id="previewBaseSalary" class="form-control" placeholder="Enter base salary to preview" value="25000">
                            </div>
                            <hr>
                            <div id="salaryPreview">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Base Salary:</span>
                                    <strong id="previewBase">₹0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>+ Earnings:</span>
                                    <strong id="previewEarnings">₹0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-danger">
                                    <span>- Deductions:</span>
                                    <strong id="previewDeductions">₹0</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Net Salary:</span>
                                    <strong id="previewNet" class="text-primary fs-5">₹0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Earnings -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-success"><i class="ti ti-plus me-1"></i>Earnings</h6>
                            <button type="button" class="btn btn-sm btn-success add-component" data-type="earning">
                                <i class="ti ti-plus me-1"></i> Add Earning
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="earningsContainer">
                                <!-- Earning components will be added here -->
                            </div>
                            <p class="text-muted small mb-0" id="noEarnings">No earnings added. Click "Add Earning" to add components.</p>
                        </div>
                    </div>

                    <!-- Deductions -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-danger"><i class="ti ti-minus me-1"></i>Deductions</h6>
                            <button type="button" class="btn btn-sm btn-danger add-component" data-type="deduction">
                                <i class="ti ti-plus me-1"></i> Add Deduction
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="deductionsContainer">
                                <!-- Deduction components will be added here -->
                            </div>
                            <p class="text-muted small mb-0" id="noDeductions">No deductions added. Click "Add Deduction" to add components.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> Create Salary Structure
                        </button>
                        <a href="{{ route('institution.salary.structures.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Component Template -->
    <template id="componentTemplate">
        <div class="component-row mb-3 p-3 border rounded">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Component Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control component-name" placeholder="e.g., HRA, PF, etc." required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Amount/Percentage <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control component-amount" placeholder="0.00" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select class="form-select component-percentage">
                        <option value="0">Fixed Amount (₹)</option>
                        <option value="1">Percentage (%)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-component">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let componentIndex = 0;

    // Add component
    $('.add-component').on('click', function() {
        const type = $(this).data('type');
        const container = type === 'earning' ? '#earningsContainer' : '#deductionsContainer';
        const noMessage = type === 'earning' ? '#noEarnings' : '#noDeductions';
        
        const template = document.getElementById('componentTemplate').content.cloneNode(true);
        const row = $(template).find('.component-row');
        row.attr('data-type', type);
        row.attr('data-index', componentIndex++);
        
        $(container).append(row);
        $(noMessage).hide();
        updatePreview();
    });

    // Remove component
    $(document).on('click', '.remove-component', function() {
        const row = $(this).closest('.component-row');
        const type = row.data('type');
        row.remove();
        
        const container = type === 'earning' ? '#earningsContainer' : '#deductionsContainer';
        const noMessage = type === 'earning' ? '#noEarnings' : '#noDeductions';
        
        if ($(container).children().length === 0) {
            $(noMessage).show();
        }
        updatePreview();
    });

    // Update preview on input change
    $(document).on('input', '.component-amount, .component-percentage, #previewBaseSalary', function() {
        updatePreview();
    });

    function updatePreview() {
        const baseSalary = parseFloat($('#previewBaseSalary').val()) || 0;
        let totalEarnings = 0;
        let totalDeductions = 0;

        $('#earningsContainer .component-row').each(function() {
            const amount = parseFloat($(this).find('.component-amount').val()) || 0;
            const isPercentage = $(this).find('.component-percentage').val() === '1';
            totalEarnings += isPercentage ? (baseSalary * amount / 100) : amount;
        });

        $('#deductionsContainer .component-row').each(function() {
            const amount = parseFloat($(this).find('.component-amount').val()) || 0;
            const isPercentage = $(this).find('.component-percentage').val() === '1';
            totalDeductions += isPercentage ? (baseSalary * amount / 100) : amount;
        });

        const netSalary = baseSalary + totalEarnings - totalDeductions;

        $('#previewBase').text('₹' + baseSalary.toLocaleString('en-IN'));
        $('#previewEarnings').text('₹' + totalEarnings.toLocaleString('en-IN', { maximumFractionDigits: 2 }));
        $('#previewDeductions').text('₹' + totalDeductions.toLocaleString('en-IN', { maximumFractionDigits: 2 }));
        $('#previewNet').text('₹' + netSalary.toLocaleString('en-IN', { maximumFractionDigits: 2 }));
    }

    // Form submission
    $('#salaryStructureForm').on('submit', function(e) {
        e.preventDefault();
        
        const components = [];
        
        $('.component-row').each(function() {
            components.push({
                name: $(this).find('.component-name').val(),
                type: $(this).data('type'),
                amount: parseFloat($(this).find('.component-amount').val()),
                is_percentage: $(this).find('.component-percentage').val() === '1'
            });
        });

        if (components.length === 0) {
            toastr.error('Please add at least one component');
            return;
        }

        const data = {
            name: $('input[name="name"]').val(),
            description: $('textarea[name="description"]').val(),
            components: components,
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            url: '{{ route("institution.salary.structures.store") }}',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.href = response.redirect_url;
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.values(errors).forEach(err => toastr.error(err[0]));
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Failed to create salary structure');
                }
            }
        });
    });

    // Initialize preview
    updatePreview();
});
</script>
@endpush
