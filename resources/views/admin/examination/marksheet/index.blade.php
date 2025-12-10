@extends('layouts.admin')
@section('title', 'Admin | Student Marksheet')
@section('content')

    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold mb-0">Generate Student Marksheet</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.exam-management.marksheet.search') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="institution_id" class="form-label">Institution</label>
                                        <select class="form-select @error('institution_id') is-invalid @enderror"
                                            id="institution_id" name="institution_id" required>
                                            <option value="">Select Institution</option>
                                            @foreach ($institutions as $institution)
                                                <option value="{{ $institution->id }}"
                                                    {{ (isset($request) && $request->institution_id == $institution->id) || request('institution_id') == $institution->id ? 'selected' : '' }}>
                                                    {{ $institution->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('institution_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="exam_type_id" class="form-label">Exam Type</label>
                                        <select class="form-select @error('exam_type_id') is-invalid @enderror"
                                            id="exam_type_id" name="exam_type_id">
                                            <option value="">Select Exam Type</option>
                                            @foreach ($examTypes as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ (isset($request) && $request->exam_type_id == $type->id) || request('exam_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('exam_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="month" class="form-label">Month</label>
                                        <select class="form-select @error('month') is-invalid @enderror" id="month"
                                            name="month">
                                            <option value="">Select Month</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                @php
                                                    $monthName = date('F', mktime(0, 0, 0, $m, 10));
                                                @endphp
                                                <option value="{{ $m }}"
                                                    {{ (isset($request) && (int) $request->month === $m) || (int) request('month') === $m ? 'selected' : '' }}>
                                                    {{ $monthName }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('month')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
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
                                <div class="col-md-2">
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
                                <div class="col-md-12 d-flex align-items-end">
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Search</button>
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
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                <div>
                                    <h5 class="fw-bold mb-1 text-primary">
                                        Student List
                                    </h5>
                                    @php
                                        $selectedInstitutionId = request('institution_id');
                                        $selectedExamTypeId = request('exam_type_id');
                                        $selectedMonth = request('month');
                                        $selectedClassId = request('class_id');
                                        $selectedSectionId = request('section_id');

                                        $selectedInstitution = isset($institutions)
                                            ? $institutions->firstWhere('id', $selectedInstitutionId)
                                            : null;
                                        $selectedExamType = isset($examTypes)
                                            ? $examTypes->firstWhere('id', $selectedExamTypeId)
                                            : null;
                                        $selectedClass = isset($class)
                                            ? $class
                                            : (isset($classes)
                                                ? $classes->firstWhere('id', $selectedClassId)
                                                : null);
                                        $selectedSection = isset($section) ? $section : null;
                                        $selectedMonthName = $selectedMonth
                                            ? DateTime::createFromFormat('!m', (int) $selectedMonth)->format('F')
                                            : null;
                                    @endphp
                                    <div class="small text-muted">
                                        @if ($selectedInstitution)
                                            <span><i
                                                    class="ti ti-building me-1"></i>{{ $selectedInstitution->name }}</span>
                                            <span class="mx-2">|</span>
                                        @endif
                                        @if ($selectedClass)
                                            <span><i class="ti ti-school me-1"></i>Class: {{ $selectedClass->name }}</span>
                                            <span class="mx-2">|</span>
                                        @endif
                                        @if ($selectedSection)
                                            <span><i class="ti ti-category me-1"></i>Section:
                                                {{ $selectedSection->name }}</span>
                                            <span class="mx-2">|</span>
                                        @endif
                                        @if ($selectedExamType)
                                            <span><i
                                                    class="ti ti-certificate me-1"></i>{{ $selectedExamType->title }}</span>
                                            <span class="mx-2">|</span>
                                        @endif
                                        @if ($selectedMonthName)
                                            <span><i class="ti ti-calendar-event me-1"></i>{{ $selectedMonthName }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
                                                    @if (isset($exam))
                                                        <a href="{{ route('admin.exam-management.marksheet.generate', ['studentId' => $student->id, 'examId' => $exam->id]) }}"
                                                            class="btn btn-sm btn-danger" target="_blank">
                                                            <i class="fas fa-file-pdf"></i> Generate PDF
                                                        </a>
                                                    @endif
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
            const reloadWithFilters = () => {
                const institutionId = $('#institution_id').val();
                const examTypeId = $('#exam_type_id').val();
                const month = $('#month').val();

                const params = new URLSearchParams();
                if (institutionId) params.set('institution_id', institutionId);
                if (examTypeId) params.set('exam_type_id', examTypeId);
                if (month) params.set('month', month);

                window.location.href = "{{ route('admin.exam-management.marksheet.index') }}" + (params
                    .toString() ?
                    '?' + params.toString() : '');
            };

            // Reload to fetch filtered exams/classes when filters change
            $('#institution_id, #exam_type_id, #month').on('change', function() {
                reloadWithFilters();
            });

            // Sections Load
            $('select[name="class_id"]').on('change', function() {
                var class_id = $(this).val();
                var institution_id = $('#institution_id').val();
                if (class_id) {
                    $.ajax({
                        url: "{{ url('admin/students/sections') }}/" + institution_id + "/" +
                            class_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            const $select = $('select[name="section_id"]');
                            $select.empty().append('<option value="">Select Section</option>');

                            if (data.sections && data.sections.length > 0) {
                                // Deduplicate by id in case backend returns duplicates
                                const seen = new Set();
                                data.sections.forEach(section => {
                                    if (seen.has(section.id)) return;
                                    seen.add(section.id);
                                    $select.append(
                                        '<option value="' + section.id + '">' +
                                        section.name + '</option>'
                                    );
                                });
                            }
                        },
                        error: function() {
                            const $select = $('select[name="section_id"]');
                            $select.empty();
                            $select.append('<option value="">Select Section</option>');
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
