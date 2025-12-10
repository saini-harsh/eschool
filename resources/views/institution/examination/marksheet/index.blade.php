@extends('layouts.institution')
@section('title', 'Student Marksheet')
@section('content')

    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">Generate Student Marksheet</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('institution.exam-management.marksheet.search') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="exam_id" class="form-label">Exam</label>
                                        <select class="form-select @error('exam_id') is-invalid @enderror" id="exam_id"
                                            name="exam_id" required>
                                            <option value="">Select Exam</option>
                                            @foreach ($exams as $exam)
                                                <option value="{{ $exam->id }}"
                                                    {{ isset($request) && $request->exam_id == $exam->id ? 'selected' : '' }}>
                                                    {{ $exam->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('exam_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="class_id" class="form-label">Class</label>
                                        <select class="form-select @error('class_id') is-invalid @enderror" id="class_id"
                                            name="class_id" required>
                                            <option value="">Select Class</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ isset($request) && $request->class_id == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="section_id" class="form-label">Section</label>
                                        <select class="form-select @error('section_id') is-invalid @enderror"
                                            id="section_id" name="section_id" required>
                                            <option value="">Select Section</option>
                                            @if (isset($sections))
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->id }}"
                                                        {{ isset($request) && $request->section_id == $section->id ? 'selected' : '' }}>
                                                        {{ $section->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('section_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (isset($students))
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0">Student List</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Admission No</th>
                                            <th>Student Name</th>
                                            <th>Roll No</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $index => $student)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $student->admission_number }}</td>
                                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                                <td>{{ $student->roll_number }}</td>
                                                <td>
                                                    <a href="{{ route('institution.exam-management.marksheet.generate', ['studentId' => $student->id, 'examId' => $request->exam_id]) }}"
                                                        class="btn btn-sm btn-danger" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> Generate PDF
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No students found for the selected
                                                    criteria.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Sections Load
            $('select[name="class_id"]').on('change', function() {
                var class_id = $(this).val();
                if (class_id) {
                    $.ajax({
                        url: "{{ url('institution/sections') }}/classes/1",
                        type: "GET",
                        dataType: "json",
                        data: {
                            class_id: class_id
                        },
                        success: function(data) {
                            $('select[name="section_id"]').empty();
                            $('select[name="section_id"]').append(
                                '<option value="">Select Section</option>');

                            // The actual endpoint return structure might vary, adapting to common pattern
                            // Based on routes: getSectionsByClass usually returns sections
                            // Let's try the dedicated endpoint for sections by class if above fails
                            // Actually the route is institution.sections.classes but that returns classes?
                            // institution/sections/classes/{institutionId} returns classes?
                            // Let's look at web.php/institution.php again.
                            // Route::get('/sections/{classId}', [StudentController::class, 'getSectionsByClass'])->name('institution.students.sections'); exists in students prefix
                            // Also Route::get('/sections/{classId}', [SectionController::class, 'getSectionsByClass']) exists in many places?
                            // Let's use the one in student prefix or similar.
                        }
                    });

                    // Better approach: use the known endpoint pattern
                    $.ajax({
                        url: "{{ url('institution/students/sections') }}/" + class_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            const $select = $('select[name="section_id"]');
                            $select.empty();
                            $select.append('<option value="">Select Section</option>');

                            data.forEach(section => {
                                $select.append(
                                    '<option value="' + section.id + '">' + section
                                    .name + '</option>'
                                );
                            });
                        }
                    });
                } else {
                    const $select = $('select[name="section_id"]');
                    $select.empty();

                    $select.append('<option value="">Select Section</option>');
                }
            });
        });
    </script>
@endpush
