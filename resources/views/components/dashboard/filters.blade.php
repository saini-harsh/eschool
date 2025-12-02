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
                    <form action="" method="GET">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}" placeholder="Search by title, code" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label">Institution</label>
                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                            class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                            Select
                        </a>
                        <ul class="dropdown-menu dropdown-menu w-100">
                            @if (isset($institutions) && !empty($institutions))
                                @foreach ($institutions as $institution)
                                    <li>
                                        <label
                                            class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                            <input class="form-check-input m-0 me-2" type="checkbox" name="institution_ids[]" value="{{ $institution->id }}" {{ in_array($institution->id, (array) request()->input('institution_ids', [])) ? 'checked' : '' }}>
                                            {{ $institution->name }}
                                        </label>
                                    </li>
                                @endforeach
                            @endif
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
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                        Select
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu w-100">
                                        <li>
                                            <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                <input class="form-check-input m-0 me-2" type="checkbox" name="status[]" value="1" {{ in_array('1', (array) request()->input('status', [])) ? 'checked' : '' }}>
                                                Active
                                            </label>
                                        </li>
                                        <li>
                                            <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                <input class="form-check-input m-0 me-2" type="checkbox" name="status[]" value="0" {{ in_array('0', (array) request()->input('status', [])) ? 'checked' : '' }}>
                                                Inactive
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-outline-white me-2" id="close-filter">Close</button>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
