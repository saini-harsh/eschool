/**
 * Students Management JavaScript
 * Handles cascading dropdowns for Institution -> Classes -> Sections -> Teachers
 */

$(document).ready(function () {
    console.log("Students.js loaded successfully");

    // Check if jQuery is available
    if (typeof $ === "undefined") {
        console.error("jQuery is not loaded!");
        return;
    }

    // Check if we're on a student page
    if ($("#institution_id").length > 0) {
        console.log("Student form detected, initializing...");
        // Initialize students functionality
        initStudentsForm();
    } else {
        console.log("Student form not found on this page");
    }

    // Always initialize status updates if we're on a page with status selects
    if ($(".status-select").length > 0) {
        console.log("Status selects found, initializing status updates...");
        initStudentStatusUpdates();
    }

    // Initialize edit page functionality
    initStudentEditPage();

    // Initialize class cards functionality
    initClassCards();

    // Initialize export functionality
    initExportFunctionality();
});

function initStudentsForm() {
    console.log("Initializing student form...");

    // Load classes and teachers when institution is selected
    $("#institution_id").on("change", function () {
        var institutionId = $(this).val();
        console.log("Institution changed to:", institutionId);
        loadClassesByInstitution(institutionId);
        loadTeachersByInstitution(institutionId);
        generateInstitutionCode(institutionId);
    });

    // Load sections when class is selected
    $("#class_id").on("change", function () {
        var classId = $(this).val();
        console.log("Class changed to:", classId);
        loadSectionsByClass(classId);
    });

    // Check if we're in edit mode and pre-populate form
    var isEditMode =
        $("#institution_id").val() &&
        ($("#current_class_id").val() ||
            $("#current_section_id").val() ||
            $("#current_teacher_id").val());
    if (isEditMode) {
        console.log("Edit mode detected, pre-populating form...");
        prePopulateEditForm();
    } else if ($("#institution_id").val()) {
        console.log("Pre-selected institution found, triggering change...");
        $("#institution_id").trigger("change");
    }

    // Initialize status update functionality for students list
    initStudentStatusUpdates();
}

function prePopulateEditForm() {
    var institutionId = $("#institution_id").val();
    var classId = $("#current_class_id").val() || $("#class_id").val();
    var sectionId = $("#current_section_id").val() || $("#section_id").val();
    var teacherId = $("#current_teacher_id").val() || $("#teacher_id").val();

    console.log("Pre-populating with:", {
        institutionId,
        classId,
        sectionId,
        teacherId,
    });

    // First load classes for the institution
    loadClassesByInstitution(institutionId, function () {
        // After classes are loaded, set the class
        if (classId) {
            $("#class_id").val(classId);

            // Then load sections for the class
            loadSectionsByClass(classId, function () {
                // After sections are loaded, set the section
                if (sectionId) {
                    $("#section_id").val(sectionId);
                }
            });
        }
    });

    // Load teachers for the institution
    loadTeachersByInstitution(institutionId, function () {
        // After teachers are loaded, set the teacher
        if (teacherId) {
            $("#teacher_id").val(teacherId);
        }
    });

    // Generate institution code
    generateInstitutionCode(institutionId);
}

/**
 * Generate institution code based on selected institution
 */
function generateInstitutionCode(institutionId) {
    if (institutionId) {
        var code = "INS" + institutionId.toString().padStart(3, "0");
        $('input[name="institution_code"]').val(code);
    } else {
        $('input[name="institution_code"]').val("");
    }
}

/**
 * Load classes for selected institution
 */
function loadClassesByInstitution(institutionId, callback) {
    var classSelect = $("#class_id");
    var sectionSelect = $("#section_id");

    // Reset class and section selections
    classSelect.html('<option value="">-- None --</option>');
    sectionSelect.html('<option value="">-- None --</option>');

    if (institutionId) {
        // Show loading state
        classSelect.html('<option value="">Loading classes...</option>');

        // Fetch classes for selected institution via AJAX
        console.log("Loading classes for institution:", institutionId);
        $.ajax({
            url: "/institution/students/classes/" + institutionId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                console.log("Classes loaded successfully:", response);
                classSelect.html('<option value="">-- None --</option>');

                if (response.classes && response.classes.length > 0) {
                    response.classes.forEach(function (classItem) {
                        classSelect.append(
                            '<option value="' +
                                classItem.id +
                                '">' +
                                classItem.name +
                                "</option>"
                        );
                    });
                } else {
                    classSelect.append(
                        '<option value="">No classes available</option>'
                    );
                }

                // Execute callback if provided
                if (typeof callback === "function") {
                    callback();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error loading classes:", xhr.responseText);
                classSelect.append(
                    '<option value="">Error loading classes</option>'
                );

                // Execute callback even on error
                if (typeof callback === "function") {
                    callback();
                }
            },
        });
    } else {
        // Execute callback if no institution selected
        if (typeof callback === "function") {
            callback();
        }
    }
}

/**
 * Load teachers for selected institution
 */
function loadTeachersByInstitution(institutionId, callback) {
    var teacherSelect = $("#teacher_id");

    // Reset teacher selection
    teacherSelect.html('<option value="">-- None --</option>');

    if (institutionId) {
        // Show loading state
        teacherSelect.html('<option value="">Loading teachers...</option>');

        // Fetch teachers for selected institution via AJAX
        $.ajax({
            url: "/institution/students/teachers/" + institutionId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                teacherSelect.html('<option value="">-- None --</option>');

                if (response.teachers && response.teachers.length > 0) {
                    response.teachers.forEach(function (teacher) {
                        teacherSelect.append(
                            '<option value="' +
                                teacher.id +
                                '">' +
                                teacher.first_name +
                                " " +
                                teacher.last_name +
                                "</option>"
                        );
                    });
                } else {
                    teacherSelect.append(
                        '<option value="">No teachers available</option>'
                    );
                }

                // Execute callback if provided
                if (typeof callback === "function") {
                    callback();
                }
            },
            error: function () {
                teacherSelect.append(
                    '<option value="">Error loading teachers</option>'
                );

                // Execute callback even on error
                if (typeof callback === "function") {
                    callback();
                }
            },
        });
    } else {
        // Execute callback if no institution selected
        if (typeof callback === "function") {
            callback();
        }
    }
}

/**
 * Load sections for selected class
 */
function loadSectionsByClass(classId, callback) {
    var sectionSelect = $("#section_id");

    // Reset section selection
    sectionSelect.html('<option value="">-- None --</option>');

    if (classId) {
        // Show loading state
        sectionSelect.html('<option value="">Loading sections...</option>');

        // Fetch sections for selected class via AJAX
        console.log("Loading sections for class:", classId);
        $.ajax({
            url: "/institution/students/sections/" + classId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                console.log("Sections loaded successfully:", response);
                sectionSelect.html('<option value="">-- None --</option>');

                if (response.sections && response.sections.length > 0) {
                    response.sections.forEach(function (section) {
                        sectionSelect.append(
                            '<option value="' +
                                section.id +
                                '">' +
                                section.name +
                                "</option>"
                        );
                    });
                } else {
                    sectionSelect.append(
                        '<option value="">No sections available</option>'
                    );
                }

                // Execute callback if provided
                if (typeof callback === "function") {
                    callback();
                }
            },
            error: function (xhr, status, error) {
                sectionSelect.append(
                    '<option value="">Error loading sections</option>'
                );
                console.error("Error loading sections:", xhr.responseText);
                console.error("Status:", status);
                console.error("Error:", error);

                // Execute callback even on error
                if (typeof callback === "function") {
                    callback();
                }
            },
        });
    } else {
        // Execute callback if no class selected
        if (typeof callback === "function") {
            callback();
        }
    }
}

/**
 * Initialize student status updates
 */
function initStudentStatusUpdates() {
    // Check if CSRF token is available
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    if (!csrfToken) {
        console.error("CSRF token not found!");
        return;
    }

    console.log("Initializing student status updates...");

    // Check if status select elements exist
    var statusSelects = $(".status-select");
    console.log("Found", statusSelects.length, "status select elements");

    // Handle status updates for students list
    $(document).on("change", ".status-select", function () {
        var studentId = $(this).data("student-id");
        var newStatus = $(this).val();
        var selectElement = $(this);
        var originalValue =
            selectElement.find("option[selected]").val() || selectElement.val();

        console.log("Student status changed:", {
            studentId,
            newStatus,
            originalValue,
        });
        console.log("Select element:", selectElement);
        console.log("Data attributes:", selectElement.data());

        if (!studentId) {
            console.error("No student ID found in data attributes");
            return;
        }

        var url = "/institution/students/status/" + studentId;
        console.log("Sending AJAX request to:", url);
        console.log("Request data:", {
            status: newStatus,
            _token: csrfToken,
        });

        $.ajax({
            url: url,
            type: "POST",
            data: {
                status: newStatus,
                _token: csrfToken,
            },
            dataType: "json",
            success: function (response) {
                console.log("Success response:", response);
                if (response.success) {
                    showToast("Status updated successfully!", "success");
                    // Update the selected attribute
                    selectElement.find("option").removeAttr("selected");
                    selectElement
                        .find('option[value="' + newStatus + '"]')
                        .attr("selected", "selected");
                } else {
                    showToast(
                        response.message || "Error updating status!",
                        "error"
                    );
                    // Revert the selection on error
                    selectElement.val(originalValue);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error updating student status:", {
                    xhr,
                    status,
                    error,
                });
                console.error("Response text:", xhr.responseText);
                console.error("Status code:", xhr.status);

                // Try to parse error response
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    console.error("Parsed error response:", errorResponse);
                    showToast(
                        errorResponse.message || "Error updating status!",
                        "error"
                    );
                } catch (e) {
                    showToast("Error updating status!", "error");
                }

                // Revert the selection on error
                selectElement.val(originalValue);
            },
        });
    });

    // Handle delete confirmations
    $(document).on("click", ".delete-student", function (e) {
        e.preventDefault();
        var deleteUrl = $(this).data("delete-url");
        var studentName = $(this).data("student-name");

        showDeleteConfirmation(deleteUrl, studentName);
    });
}

/**
 * Show delete confirmation
 */
function showDeleteConfirmation(deleteUrl, studentName) {
    if (
        confirm(`Are you sure you want to delete the student "${studentName}"?`)
    ) {
        // Create a form and submit it
        var form = $("<form>", {
            method: "POST",
            action: deleteUrl,
        });

        form.append(
            $("<input>", {
                type: "hidden",
                name: "_token",
                value: $('meta[name="csrf-token"]').attr("content"),
            })
        );

        $("body").append(form);
        form.submit();
    }
}

/**
 * Show toast notification
 */
function showToast(message, type) {
    // Use toastr if available, otherwise fallback to custom toast
    if (typeof toastr !== "undefined") {
        if (type === "success") {
            toastr.success(message);
        } else {
            toastr.error(message);
        }
    } else {
        // Fallback to custom toast
        var toastHtml = `
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <div class="toast align-items-center text-bg-${
                    type === "success" ? "success" : "danger"
                } border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        `;

        // Add toast to page
        $("body").append(toastHtml);

        // Auto-hide after 3 seconds
        setTimeout(function () {
            $(".toast").remove();
        }, 3000);
    }
}

/**
 * Reset all form fields
 */
function resetStudentForm() {
    $("#institution_id").val("");
    $("#class_id").html('<option value="">-- None --</option>');
    $("#section_id").html('<option value="">-- None --</option>');
    $("#teacher_id").html('<option value="">-- None --</option>');
    $('input[name="institution_code"]').val("");
}

/**
 * Pre-populate form with existing values (for edit mode)
 */
function populateStudentForm(studentData) {
    if (studentData.institution_id) {
        $("#institution_id").val(studentData.institution_id);
        // Trigger change to load classes and teachers
        $("#institution_id").trigger("change");

        // Wait for classes to load, then set class
        setTimeout(function () {
            if (studentData.class_id) {
                $("#class_id").val(studentData.class_id);
                // Trigger change to load sections
                $("#class_id").trigger("change");

                // Wait for sections to load, then set section
                setTimeout(function () {
                    if (studentData.section_id) {
                        $("#section_id").val(studentData.section_id);
                    }
                }, 500);
            }
        }, 500);
    }

    if (studentData.teacher_id) {
        // Wait for teachers to load, then set teacher
        setTimeout(function () {
            $("#teacher_id").val(studentData.teacher_id);
        }, 500);
    }
}

/**
 * Initialize student edit page functionality
 */
function initStudentEditPage() {
    console.log("Initializing student edit page...");

    // Auto-hide toasts after 5 seconds and load data
    document.addEventListener("DOMContentLoaded", function () {
        const toasts = document.querySelectorAll(".toast");
        toasts.forEach((toast) => {
            setTimeout(() => {
                const bsToast = new bootstrap.Toast(toast);
                bsToast.hide();
            }, 5000);
        });

        // Load classes and teachers automatically if we're on edit page
        if (typeof window.institutionId !== "undefined") {
            loadClasses(window.institutionId);
            loadTeachers(window.institutionId);
        }
    });

    // Initialize create page functionality
    initStudentCreatePage();
}

/**
 * Initialize student create page functionality
 */
function initStudentCreatePage() {
    console.log("Initializing student create page...");

    // Load classes and teachers automatically on page load for create page
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof window.institutionId !== "undefined") {
            loadClasses(window.institutionId);
        }
    });
}

/**
 * Load classes by institution (for create and edit pages)
 */
function loadClasses(institutionId) {
    if (institutionId) {
        fetch(`/institution/students/classes/${institutionId}`)
            .then((response) => response.json())
            .then((data) => {
                const classSelect = document.getElementById("class_id");
                if (classSelect) {
                    classSelect.innerHTML = '<option value="">Class</option>';
                    data.classes.forEach((classItem) => {
                        const option = document.createElement("option");
                        option.value = classItem.id;
                        option.textContent = classItem.name;
                        classSelect.appendChild(option);
                    });
                }
            });

        // Also load teachers for create page
        loadTeachers(institutionId);
    }
}

/**
 * Load sections by class (for create and edit pages)
 */
function loadSections(classId) {
    if (classId) {
        fetch(`/institution/students/sections/${classId}`)
            .then((response) => response.json())
            .then((data) => {
                const sectionSelect = document.getElementById("section_id");
                if (sectionSelect) {
                    sectionSelect.innerHTML =
                        '<option value="">Section</option>';
                    data.sections.forEach((section) => {
                        const option = document.createElement("option");
                        option.value = section.id;
                        option.textContent = section.name;
                        sectionSelect.appendChild(option);
                    });
                }
            });
    }
}

/**
 * Load teachers by institution (for create and edit pages)
 */
function loadTeachers(institutionId) {
    if (institutionId) {
        fetch(`/institution/students/teachers/${institutionId}`)
            .then((response) => response.json())
            .then((data) => {
                const teacherSelect = document.getElementById("teacher_id");
                if (teacherSelect) {
                    teacherSelect.innerHTML =
                        '<option value="">Select Teacher</option>';
                    data.teachers.forEach((teacher) => {
                        const option = document.createElement("option");
                        option.value = teacher.id;
                        option.textContent =
                            teacher.first_name + " " + teacher.last_name;
                        teacherSelect.appendChild(option);
                    });
                }
            });
    }
}

/**
 * Initialize class cards functionality
 */
function initClassCards() {
    console.log("Initializing class cards...");

    // Handle class card clicks
    $(document).on("click", ".class-card", function () {
        var classId = $(this).data("class-id");
        var className = $(this).find(".card-title").text();

        console.log("Class card clicked:", { classId, className });

        if (classId) {
            loadStudentsByClass(classId, className);
        }
    });

    // Handle back to classes button
    $(document).on("click", "#back-to-classes", function () {
        showClassesSection();
    });

    // Handle student search
    $(document).on("input", "#student-search", function () {
        var searchTerm = $(this).val().toLowerCase();
        filterStudents(searchTerm);
    });

    // Handle section filter change
    $(document).on("change", "#section-filter", function () {
        var sectionId = $(this).val();
        loadStudentsBySection(sectionId);
    });

    // Handle status filter change
    $(document).on("change", "#status-filter", function () {
        filterStudentsByStatus();
    });
}

/**
 * Load students by class ID
 */
function loadStudentsByClass(classId, className) {
    console.log("Loading students for class:", classId);

    // Show loading state
    showStudentsSection();
    showLoadingState();

    // Update class name in header
    $("#selected-class-name").text(className + " - Students");

    // Store current class ID for filtering
    window.currentClassId = classId;

    // Fetch students via AJAX
    $.ajax({
        url: "/institution/students/class/" + classId,
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Students loaded successfully:", response);
            hideLoadingState();

            // Populate section filter dropdown
            populateSectionFilter(response.sections);

            if (response.students && response.students.length > 0) {
                displayStudents(response.students);
            } else {
                showEmptyState();
            }
        },
        error: function (xhr, status, error) {
            console.error("Error loading students:", xhr.responseText);
            hideLoadingState();
            showEmptyState();
            showToast("Error loading students!", "error");
        },
    });
}

/**
 * Display students in the table
 */
function displayStudents(students) {
    var tbody = $("#students-tbody");
    tbody.empty();

    students.forEach(function (student) {
        var row = `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <a href="/institution/students/show/${
                            student.id
                        }" class="avatar avatar-sm avatar-rounded">
                            <img src="${
                                student.photo
                                    ? "/" + student.photo
                                    : "/adminpanel/img/avatar-placeholder.jpg"
                            }" alt="img">
                        </a>
                        <div class="ms-2">
                            <h6 class="fs-14 mb-0">
                                <a href="/institution/students/show/${
                                    student.id
                                }">${student.first_name} ${
            student.last_name
        }</a>
                            </h6>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="ms-2">
                            <h6 class="fs-14 mb-0">
                                <a href="javascript:void(0);">${
                                    student.email
                                }</a>
                            </h6>
                        </div>
                    </div>
                </td>
                <td>${student.phone || "N/A"}</td>
                <td>
                    <span class="badge badge-soft-secondary">
                        ${
                            student.section
                                ? student.section.name
                                : "Not Assigned"
                        }
                    </span>
                </td>
                <td>
                    <span class="badge badge-soft-orange">
                        ${
                            student.teacher
                                ? student.teacher.first_name +
                                  " " +
                                  student.teacher.last_name
                                : "Not Assigned"
                        }
                    </span>
                </td>
                <td>
                    <div>
                        <select class="form-select form-select-sm status-select" data-student-id="${
                            student.id
                        }">
                            <option value="1" ${
                                student.status == 1 ? "selected" : ""
                            }>Active</option>
                            <option value="0" ${
                                student.status == 0 ? "selected" : ""
                            }>Inactive</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="d-inline-flex align-items-center">
                        <a href="/institution/students/show/${
                            student.id
                        }" class="btn btn-icon btn-sm btn-outline-white border-0" title="View Details">
                            <i class="ti ti-eye"></i>
                        </a>
                        <a href="/institution/students/edit/${
                            student.id
                        }" class="btn btn-icon btn-sm btn-outline-white border-0" title="Edit">
                            <i class="ti ti-edit"></i>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-student"
                           data-delete-url="/institution/students/delete/${
                               student.id
                           }"
                           data-student-name="${student.first_name} ${
            student.last_name
        }"
                           title="Delete">
                            <i class="ti ti-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(row);
    });

    // Initialize DataTable without search functionality
    initializeStudentsDataTable();

    // Hide empty state
    $("#students-empty").hide();
    $("#students-table").show();
}

/**
 * Show classes section and hide students section
 */
function showClassesSection() {
    $("#classes-section").show();
    $("#students-section").hide();
}

/**
 * Show students section and hide classes section
 */
function showStudentsSection() {
    $("#classes-section").hide();
    $("#students-section").show();
}

/**
 * Show loading state
 */
function showLoadingState() {
    $("#students-loading").show();
    $("#students-empty").hide();
    $("#students-table").hide();
}

/**
 * Hide loading state
 */
function hideLoadingState() {
    $("#students-loading").hide();
}

/**
 * Show empty state
 */
function showEmptyState() {
    $("#students-empty").show();
    $("#students-table").hide();
}

/**
 * Filter students based on search term
 */
function filterStudents(searchTerm) {
    var rows = $("#students-tbody tr");

    rows.each(function () {
        var row = $(this);
        var studentName = row.find("h6 a").text().toLowerCase();
        var studentEmail = row
            .find("td:nth-child(2) h6 a")
            .text()
            .toLowerCase();

        if (
            studentName.includes(searchTerm) ||
            studentEmail.includes(searchTerm)
        ) {
            row.show();
        } else {
            row.hide();
        }
    });

    // Apply other active filters
    applyAllFilters();
}

/**
 * Filter students by status
 */
function filterStudentsByStatus() {
    applyAllFilters();
}

/**
 * Apply all active filters
 */
function applyAllFilters() {
    var rows = $("#students-tbody tr");
    var searchTerm = $("#student-search").val().toLowerCase();
    var statusFilter = $("#status-filter").val();

    rows.each(function () {
        var row = $(this);
        var showRow = true;

        // Apply search filter
        if (searchTerm) {
            var studentName = row.find("h6 a").text().toLowerCase();
            var studentEmail = row
                .find("td:nth-child(2) h6 a")
                .text()
                .toLowerCase();

            if (
                !studentName.includes(searchTerm) &&
                !studentEmail.includes(searchTerm)
            ) {
                showRow = false;
            }
        }

        // Apply status filter
        if (statusFilter && showRow) {
            var statusSelect = row.find(".status-select");
            var currentStatus = statusSelect.val();

            if (currentStatus !== statusFilter) {
                showRow = false;
            }
        }

        // Show or hide row
        if (showRow) {
            row.show();
        } else {
            row.hide();
        }
    });
}

/**
 * Populate section filter dropdown
 */
function populateSectionFilter(sections) {
    var sectionFilter = $("#section-filter");
    sectionFilter.html('<option value="">All Sections</option>');

    if (sections && sections.length > 0) {
        sections.forEach(function (section) {
            sectionFilter.append(
                '<option value="' +
                    section.id +
                    '">' +
                    section.name +
                    "</option>"
            );
        });
    }
}

/**
 * Load students filtered by section
 */
function loadStudentsBySection(sectionId) {
    if (!window.currentClassId) {
        console.error("No current class ID found");
        return;
    }

    console.log(
        "Loading students for class:",
        window.currentClassId,
        "and section:",
        sectionId
    );

    // Show loading state
    showLoadingState();

    var url = sectionId
        ? "/institution/students/class/" +
          window.currentClassId +
          "/section/" +
          sectionId
        : "/institution/students/class/" + window.currentClassId;

    // Fetch students via AJAX
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Students loaded successfully:", response);
            hideLoadingState();

            // Reset other filters when loading new data
            $("#status-filter").val("");
            $("#student-search").val("");

            if (response.students && response.students.length > 0) {
                displayStudents(response.students);
            } else {
                showEmptyState();
            }
        },
        error: function (xhr, status, error) {
            console.error("Error loading students:", xhr.responseText);
            hideLoadingState();
            showEmptyState();
            showToast("Error loading students!", "error");
        },
    });
}

/**
 * Initialize DataTable for students table without search functionality
 */
function initializeStudentsDataTable() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable("#students-table")) {
        $("#students-table").DataTable().destroy();
    }

    // Initialize DataTable with search disabled
    $("#students-table").DataTable({
        bFilter: false, // Disable search functionality
        bInfo: true, // Keep info display
        bLengthChange: true, // Keep length change
        bPaginate: true, // Keep pagination
        ordering: true, // Keep sorting
        pageLength: 10, // Set default page length
        language: {
            sLengthMenu: "_MENU_",
            sLengthMenu: "Showing _MENU_ Results",
            info: "_START_ - _END_ of _TOTAL_ items",
            paginate: {
                next: "Next",
                previous: "Prev",
            },
        },
        scrollX: false,
        scrollCollapse: false,
        responsive: false,
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: 6 }, // Disable sorting on Action column
        ],
    });
}

/**
 * Initialize export functionality
 */
function initExportFunctionality() {
    console.log("Initializing export functionality...");

    // Handle export button click
    $(document).on("click", "#exportStudentsBtn", function () {
        exportStudents();
    });
}

/**
 * Export students data
 */
function exportStudents() {
    console.log("Exporting students...");

    // Check if we're in students section (class selected)
    if ($("#students-section").is(":visible") && window.currentClassId) {
        // Export current class students
        exportClassStudents(window.currentClassId);
    } else {
        // Export all students for the institution
        exportAllStudents();
    }
}

/**
 * Export students for a specific class
 */
function exportClassStudents(classId) {
    console.log("Exporting students for class:", classId);

    // Show loading state
    const exportBtn = $("#exportStudentsBtn");
    const originalText = exportBtn.html();
    exportBtn.html('<i class="ti ti-loader-2 me-1"></i>Exporting...');
    exportBtn.prop("disabled", true);

    // Create export URL
    const exportUrl = `/institution/students/export/class/${classId}`;

    // Create a temporary form to trigger download
    const form = $("<form>", {
        method: "GET",
        action: exportUrl,
        target: "_blank",
    });

    // Add CSRF token
    form.append(
        $("<input>", {
            type: "hidden",
            name: "_token",
            value: $('meta[name="csrf-token"]').attr("content"),
        })
    );

    // Submit form
    $("body").append(form);
    form.submit();
    form.remove();

    // Reset button state
    setTimeout(() => {
        exportBtn.html(originalText);
        exportBtn.prop("disabled", false);
    }, 1000);
}

/**
 * Export all students for the institution
 */
function exportAllStudents() {
    console.log("Exporting all students...");

    // Show loading state
    const exportBtn = $("#exportStudentsBtn");
    const originalText = exportBtn.html();
    exportBtn.html('<i class="ti ti-loader-2 me-1"></i>Exporting...');
    exportBtn.prop("disabled", true);

    // Create export URL
    const exportUrl = `/institution/students/export/all`;

    // Create a temporary form to trigger download
    const form = $("<form>", {
        method: "GET",
        action: exportUrl,
        target: "_blank",
    });

    // Add CSRF token
    form.append(
        $("<input>", {
            type: "hidden",
            name: "_token",
            value: $('meta[name="csrf-token"]').attr("content"),
        })
    );

    // Submit form
    $("body").append(form);
    form.submit();
    form.remove();

    // Reset button state
    setTimeout(() => {
        exportBtn.html(originalText);
        exportBtn.prop("disabled", false);
    }, 1000);
}
