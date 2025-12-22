@extends('layouts.institution')
@section('title', 'Institution | Add Student to Boarding')
@section('content')
    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Add Student to Boarding</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('institution.boarding.index') }}">Boarding</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('institution.boarding.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <form action="{{ route('institution.boarding.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="class_filter" class="form-label">Filter by Class</label>
                            <select class="form-select" id="class_filter" name="class_filter">
                                <option value="">All Classes</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">Select Student <span class="text-danger">*</span></label>
                            <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                <option value="">Choose Student</option>
                                @foreach ($availableStudents as $student)
                                    <option value="{{ $student->id }}" 
                                        data-class="{{ $student->class_id }}"
                                        data-section="{{ $student->section_id }}"
                                        data-class-name="{{ $student->schoolClass->name ?? '' }}"
                                        data-section-name="{{ $student->section->name ?? '' }}">
                                        {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                        @if($student->admission_number) - {{ $student->admission_number }} @endif
                                        @if($student->schoolClass) ({{ $student->schoolClass->name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Only students not already in boarding are shown.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('institution.boarding.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-x me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i>Add to Boarding
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- End Content -->
@endsection

@push('scripts')
    <script>
        // Filter students by class
        document.getElementById('class_filter')?.addEventListener('change', function() {
            const classId = this.value;
            const studentSelect = document.getElementById('student_id');
            const options = studentSelect.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                    return;
                }
                
                if (classId === '' || option.dataset.class === classId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Reset selection if current selection is hidden
            if (classId !== '' && studentSelect.value !== '') {
                const selectedOption = studentSelect.options[studentSelect.selectedIndex];
                if (selectedOption.style.display === 'none') {
                    studentSelect.value = '';
                }
            }
        });
    </script>
@endpush

