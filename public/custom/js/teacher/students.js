/**
 * Teacher Students Management JavaScript
 * Handles student viewing functionality for Teacher dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Teacher Students.js loaded successfully');
    
    // Initialize all functionality
    initTeacherStudentsPage();
});

function initTeacherStudentsPage() {
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const viewStudentsBtn = document.getElementById('viewStudentsBtn');
    const resetBtn = document.getElementById('resetBtn');
    const studentsTableContainer = document.getElementById('studentsTableContainer');
    
    if (!classSelect || !sectionSelect || !viewStudentsBtn || !resetBtn || !studentsTableContainer) {
        console.log('Required elements not found on this page');
        return;
    }
    
    // Function to check if both class and section are selected
    function checkFormValidity() {
        const classId = classSelect.value;
        const sectionId = sectionSelect.value;
        viewStudentsBtn.disabled = !(classId && sectionId);
    }
    
    // Function to load sections when class is selected
    function loadSections(classId) {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        
        if (classId) {
            // Show loading state
            sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
            sectionSelect.disabled = true;
            
            fetch(`/teacher/students/sections/${classId}`)
                .then(response => response.json())
                .then(data => {
                    sectionSelect.innerHTML = '<option value="">Select Section</option>';
                    sectionSelect.disabled = false;
                    
                    if (data.sections && data.sections.length > 0) {
                        data.sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.textContent = section.name;
                            sectionSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching sections:', error);
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                    sectionSelect.disabled = false;
                });
        }
    }
    
    // Function to load students via AJAX
    function loadStudents(classId, sectionId) {
        // Show loading state
        studentsTableContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading students...</p>
            </div>
        `;
        
        // Create form data
        const formData = new FormData();
        formData.append('class_id', classId);
        formData.append('section_id', sectionId);
        formData.append('_token', getCsrfToken());
        
        fetch('/teacher/students/get-by-class-section', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.students && data.students.length > 0) {
                renderStudentsTable(data.students);
                } else {
                studentsTableContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="ti ti-users fs-48 mb-3 d-block"></i>
                            <p class="mb-0">No students found for the selected class and section.</p>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching students:', error);
            studentsTableContainer.innerHTML = `
                <div class="text-center py-5">
                    <div class="text-danger">
                        <i class="ti ti-alert-circle fs-48 mb-3 d-block"></i>
                        <p class="mb-0">Error loading students. Please try again.</p>
                    </div>
                </div>
            `;
        });
    }
    
    // Function to render students table
    function renderStudentsTable(students) {
        let tableHTML = `
            <div class="table-responsive">
                <table class="table table-nowrap datatable">
                    <thead class="thead-ight">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th class="no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        students.forEach(student => {
            const photoUrl = student.photo ? `/admin/uploads/${student.photo}` : '';
            const photoHTML = student.photo ? 
                `<img src="${photoUrl}" alt="img">` : 
                `<i class="ti ti-user"></i>`;
            
            tableHTML += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <a href="/teacher/students/show/${student.id}" class="avatar avatar-sm avatar-rounded">
                                ${photoHTML}
                            </a>
                            <div class="ms-2">
                                <h6 class="fs-14 mb-0">
                                    <a href="/teacher/students/show/${student.id}">${student.first_name} ${student.last_name}</a>
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="ms-2">
                                <h6 class="fs-14 mb-0"><a href="javascript:void(0);">${student.email}</a></h6>
                            </div>
                        </div>
                    </td>
                    <td>${student.phone || 'N/A'}</td>
                    <td>
                        <span class="badge badge-soft-secondary">
                            ${student.school_class ? student.school_class.name : 'N/A'}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-soft-primary">
                            ${student.section ? student.section.name : 'N/A'}
                        </span>
                    </td>
                    <td>
                        <div class="d-inline-flex align-items-center">
                            <a href="/teacher/students/show/${student.id}" class="btn btn-icon btn-sm btn-outline-white border-0" title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="/teacher/students/download-pdf/${student.id}" class="btn btn-icon btn-sm btn-outline-success border-0" title="Download PDF">
                                <i class="ti ti-file-type-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tableHTML += `
                    </tbody>
                </table>
            </div>
        `;
        
        studentsTableContainer.innerHTML = tableHTML;
    }
    
    // Event listeners
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        loadSections(classId);
        checkFormValidity();
    });
    
    sectionSelect.addEventListener('change', function() {
        checkFormValidity();
    });
    
    viewStudentsBtn.addEventListener('click', function() {
        const classId = classSelect.value;
        const sectionId = sectionSelect.value;
        
        if (classId && sectionId) {
            loadStudents(classId, sectionId);
        }
    });
    
    resetBtn.addEventListener('click', function() {
        classSelect.value = '';
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        viewStudentsBtn.disabled = true;
        studentsTableContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="ti ti-filter fs-48 mb-3 d-block"></i>
                    <h5 class="mb-2">Select Class and Section</h5>
                    <p class="mb-0">Please select a class and section to view students.</p>
                </div>
            </div>
        `;
    });
    
    // Initial form validation
    checkFormValidity();
    
    // Initialize toast auto-hide functionality
    initToastAutoHide();
}

/**
 * Get CSRF token from meta tag
 */
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

/**
 * Initialize toast auto-hide functionality
 */
function initToastAutoHide() {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            setTimeout(() => {
                const bsToast = new bootstrap.Toast(toast);
                bsToast.hide();
            }, 5000);
        });
}
