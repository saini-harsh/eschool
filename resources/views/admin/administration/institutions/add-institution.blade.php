@extends('layouts.admin')
@section('title', 'Admin | Add Institution')
@section('content')
    <!-- Start Content -->
    <div class="content">

        <!-- start row -->
        <div class="row">

            <div class="col-lg-10 mx-auto">
                <div>
                <!-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif -->
                    <h6 class="mb-3 fs-14"><a href="{{ route('admin.institutions') }}"><i class="ti ti-arrow-left me-1"></i>Institutions</a></h6>
                    <form action="{{ route('admin.store-institution') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card rounded-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Basic Details</h6>
                            </div> <!-- end card header -->
                            <div class="card-body">

                                <div>
                                    <label class="form-label">Logo</label>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-xxl border border-dashed bg-light me-3 flex-shrink-0">
                                            <i class="ti ti-photo text-primary"></i>
                                        </div>
                                        <div class="d-inline-flex flex-column align-items-start">
                                            <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                                <i class="ti ti-photo me-1"></i>Upload Logo
                                                <input type="file" name="logo" class="form-control image-sign" accept="image/*">
                                            </div>
                                            <span class="text-dark fs-12">JPG or PNG format, not exceeding 5MB.</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- start row -->
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Institution Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                    </div> <!-- end col -->

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                    </div> <!-- end col -->

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" maxlength="20" required>
                                        </div>
                                    </div> <!-- end col -->

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Established Date <span class="text-danger">*</span></label>
                                            <div class="input-group w-auto input-group-flat">
                                                <input type="text" name="established_date" class="form-control" data-provider="flatpickr"
                                                    data-date-format="d M, Y" placeholder="dd/mm/yyyy" required>
                                                <span class="input-group-text">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Board <span class="text-danger">*</span></label>
                                            <input type="text" name="board" class="form-control" required>
                                        </div>
                                    </div> <!-- end col -->

                                    <div class="col-md-4">
                                        <div>
                                            <label class="form-label">Website</label>
                                            <input type="text" name="website" class="form-control">
                                        </div>
                                    </div> <!-- end col -->

                                </div>
                                <!-- end row -->

                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card rounded-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Address Details</h6>
                            </div> <!-- end card header -->
                            <div class="card-body pb-0">

                                <!-- start row -->
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control" required>
                                        </div>
                                    </div> <!-- end col -->

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Postal Code</label>
                                            <input type="text" name="pincode" maxlength="10" class="form-control" required>
                                        </div>
                                    </div> <!-- end col -->

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control" required>
                                        </div>
                                    </div> <!-- end col -->

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">District</label>
                                            <input type="text" name="district" class="form-control" required>
                                        </div>
                                    </div> <!-- end col -->

                                </div>
                                <!-- end row -->

                            </div> <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="card rounded-0">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Login Credentials</h6>
                            </div> <!-- end card header -->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-light me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Institution</button>
                        </div>

                    </form> <!-- end form -->

                </div>
            </div> <!-- end col -->

        </div>
        <!-- end row -->

    </div>
<!-- End Content -->
@endsection
