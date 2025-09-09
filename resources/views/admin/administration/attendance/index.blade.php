@extends('layouts.admin')
@section('title', 'Admin Dashboard | Attendance')

@section('content')
    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Attendance</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href=""><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card mb-4 w-100">
            <div class="card-body">
                <form id="attendance-filter-form" class="row g-3 align-items-end">
                    <!-- Institution Dropdown -->
                    <div class="col-md-3">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            <option value="1">Nexa Core Solutions</option>
                            <option value="2">Byte Forge Technologies</option>
                        </select>
                    </div>
                    <!-- Type Dropdown -->
                    <div class="col-md-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" onchange="toggleStudentFields()">
                            <option value="">Select Type</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                            <option value="nonworkingstaff">Non Working Staff</option>
                        </select>
                    </div>
                    <!-- Class & Section (only for students) -->
                    <div class="col-md-3 d-none" id="class-section-fields">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select mb-2" id="class" name="class">
                            <option value="">Select Class</option>
                            <option value="1">Class 1</option>
                            <option value="2">Class 2</option>
                        </select>
                        <label for="section" class="form-label mt-2">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                    <!-- Date -->
                    <div class="col-md-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date"
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary ms-2" onclick="resetStudentFields()">Reset</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function toggleStudentFields() {
                var type = document.getElementById('type').value;
                var classSection = document.getElementById('class-section-fields');
                if (type === 'student') {
                    classSection.classList.remove('d-none');
                } else {
                    classSection.classList.add('d-none');
                }
            }

            function resetStudentFields() {
                document.getElementById('class-section-fields').classList.add('d-none');
            }
        </script>

        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="datatable-search">
                <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="ti ti-filter me-1"></i>Filter
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0" id="filter-dropdown">
                        <div class="card mb-0">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0">Filter</h6>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="link-danger text-decoration-underline">Clear
                                            All</a>
                                    </div>
                                </div>
                            </div>
                            <form action="#">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Name</label>
                                            <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <div class="dropdown">
                                            <a href="javascript:void(0);"
                                                class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                aria-expanded="true">
                                                Select
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu w-100">
                                                <li>
                                                    <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Nexa Core Solutions
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Byte Forge Technologies
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Code Pulse Innovations
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Quantum Stack Solutions
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Cognitix Technologies
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Status</label>
                                            <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <div class="dropdown">
                                            <a href="javascript:void(0);"
                                                class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                aria-expanded="true">
                                                Select
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu w-100">
                                                <li>
                                                    <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Active
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        Inactive
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-end">
                                    <button type="button" class="btn btn-outline-white me-2"
                                        id="close-filter">Close</button>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                            class="dropdown-toggle btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                            data-bs-toggle="dropdown">
                            <i class="ti ti-sort-descending-2 text-dark me-1"></i>Sort By : Newest
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-1">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Newest</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-nowrap datatable">
                <thead class="thead-ight">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="no-sort">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="company-details.html" class="avatar avatar-sm rounded-circle bg-light border">
                                    <img src="" class="w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0"><a href="company-details.html"></a></h6>
                                </div>
                            </div>
                        </td>
                        <td><a href="https://dleohr.dreamstechnologies.com/cdn-cgi/l/email-protection"
                                class="__cf_email__" data-cfemail="43202c2d37222037032d263b22202c31266d202c2e"></a>
                        </td>
                        <td></td>
                        <td>
                            <div>
                                <select class="select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="" class="btn btn-icon btn-sm btn-outline-white border-0"><i
                                        class="ti ti-edit"></i></a>
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0"
                                    data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="ti ti-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('custom/js/admin/attendance.js') }}"></script>
@endpush