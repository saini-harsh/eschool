@extends('layouts.institution')
@section('title', 'Institution | Edit Boarding Student')
@section('content')
    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Edit Boarding Student</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('institution.boarding.index') }}">Boarding</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('institution.boarding.update', $boarding->id) }}" method="POST">
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

                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                    <option value="{{ $boarding->student->id }}" selected>
                                        {{ $boarding->student->first_name }} {{ $boarding->student->middle_name }} {{ $boarding->student->last_name }}
                                        @if($boarding->student->admission_number) - {{ $boarding->student->admission_number }} @endif
                                        @if($boarding->student->schoolClass) ({{ $boarding->student->schoolClass->name }}) @endif
                                    </option>
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Note: Changing the student will replace the current boarding entry.</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('institution.boarding.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-x me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i>Update Boarding
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Student Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if ($boarding->student->photo)
                                <img src="{{ asset($boarding->student->photo) }}" alt="Photo"
                                    class="avatar avatar-xl rounded-circle">
                            @else
                                <div class="avatar avatar-xl bg-primary text-white rounded-circle mx-auto">
                                    {{ strtoupper(substr($boarding->student->first_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h6 class="text-center mb-3">
                            {{ $boarding->student->first_name }} {{ $boarding->student->middle_name }} {{ $boarding->student->last_name }}
                        </h6>
                        <div class="mb-2">
                            <strong>Admission No.:</strong> {{ $boarding->student->admission_number ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Roll No.:</strong> {{ $boarding->student->roll_number ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Class:</strong> 
                            @if($boarding->student->schoolClass)
                                <span class="badge badge-soft-primary">{{ $boarding->student->schoolClass->name }}</span>
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="mb-2">
                            <strong>Section:</strong> 
                            @if($boarding->student->section)
                                <span class="badge badge-soft-info">{{ $boarding->student->section->name }}</span>
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> {{ $boarding->student->email ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Phone:</strong> {{ $boarding->student->phone ?? 'N/A' }}
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('institution.students.show', $boarding->student->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="ti ti-eye me-1"></i>View Full Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Content -->
@endsection

