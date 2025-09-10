$(document).ready(function() {
    // Prevent multiple initializations
    if (window.routinesInitialized) {
        return;
    }
    window.routinesInitialized = true;

    // Global variables
    let currentInstitutionId = null;
    let currentClassId = null;
    let currentSectionId = null;
    let subjects = [];
    let teachers = [];

    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // No auto-loading - user must select institution manually

    // Institution change handler - unbind existing events first
    $('#institution_id').off('change.routines').on('change.routines', function() {
        const institutionId = $(this).val();
        currentInstitutionId = institutionId;
        
        if (institutionId) {
            loadClassesByInstitution(institutionId);
            loadTeachersByInstitution(institutionId);
        } else {
            resetDependentDropdowns(['class_id', 'section_id', 'subject_id', 'teacher_id']);
        }
    });

    // Class change handler - unbind existing events first
    $('#class_id').off('change.routines').on('change.routines', function() {
        const classId = $(this).val();
        currentClassId = classId;
        
        if (classId && currentInstitutionId) {
            loadSectionsByClass(classId);
            loadSubjectsByInstitutionClass(currentInstitutionId, classId);
        } else {
            resetDependentDropdowns(['section_id', 'subject_id']);
        }
    });

    // Section change handler - unbind existing events first
    $('#section_id').off('change.routines').on('change.routines', function() {
        currentSectionId = $(this).val();
    });

    // Search existing routines - unbind existing events first
    $('#search_existing').off('click.routines').on('click.routines', function() {
        if (!currentClassId || !currentSectionId) {
            showAlert('Please select both class and section', 'warning');
            return;
        }
        loadExistingRoutines(currentClassId, currentSectionId);
    });

    // Routine form submission - using event delegation to prevent multiple bindings
    $(document).on('submit', '.routine-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const day = form.data('day');
        
        // Prevent multiple submissions
        if (form.data('submitting')) {
            return false;
        }
        
        if (!currentInstitutionId || !currentClassId || !currentSectionId) {
            showAlert('Please select institution, class and section first', 'warning');
            return;
        }

        // Mark form as submitting
        form.data('submitting', true);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="ti ti-loader me-1"></i> Adding...');

        const formData = new FormData(this);
        formData.append('institution_id', currentInstitutionId);
        formData.append('class_id', currentClassId);
        formData.append('section_id', currentSectionId);
        formData.append('day', day);

        $.ajax({
            url: '/institution/routines',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    form[0].reset();
                    loadExistingRoutines(currentClassId, currentSectionId);
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = 'Validation failed:\n';
                    Object.keys(response.errors).forEach(key => {
                        errorMessage += response.errors[key][0] + '\n';
                    });
                    showAlert(errorMessage, 'error');
                } else {
                    showAlert('Error creating routine', 'error');
                }
            },
            complete: function() {
                // Reset form state
                form.data('submitting', false);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Delete routine
    $(document).on('click', '.delete-routine', function() {
        const routineId = $(this).data('routine-id');
        const row = $(this).closest('tr');
        
        if (confirm('Are you sure you want to delete this routine?')) {
            $.ajax({
                url: `/institution/routines/${routineId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
                        row.remove();
                    } else {
                        showAlert(response.message, 'error');
                    }
                },
                error: function() {
                    showAlert('Error deleting routine', 'error');
                }
            });
        }
    });

    // Institution filter change handler for report - unbind existing events first
    $('#institution_filter').off('change.routines').on('change.routines', function() {
        const institutionId = $(this).val();
        if (institutionId) {
            loadClassesForReport(institutionId);
            $('#class_filter').prop('disabled', false);
        } else {
            $('#class_filter').html('<option value="">Select Class</option>').prop('disabled', true);
            $('#section_filter').html('<option value="">Select Section</option>').prop('disabled', true);
            $('#search_routine').prop('disabled', true);
        }
    });

    // Class filter change handler for report - unbind existing events first
    $('#class_filter').off('change.routines').on('change.routines', function() {
        const classId = $(this).val();
        if (classId) {
            loadSectionsForReport(classId);
            $('#section_filter').prop('disabled', false);
        } else {
            $('#section_filter').html('<option value="">Select Section</option>').prop('disabled', true);
            $('#search_routine').prop('disabled', true);
        }
    });

    // Section filter change handler for report - unbind existing events first
    $('#section_filter').off('change.routines').on('change.routines', function() {
        const sectionId = $(this).val();
        if (sectionId) {
            $('#search_routine').prop('disabled', false);
        } else {
            $('#search_routine').prop('disabled', true);
        }
    });

    // Search routine for report - unbind existing events first
    $('#search_routine').off('click.routines').on('click.routines', function() {
        const classId = $('#class_filter').val();
        const sectionId = $('#section_filter').val();
        
        if (!classId || !sectionId) {
            showAlert('Please select both class and section', 'warning');
            return;
        }
        
        loadRoutineReport(classId, sectionId);
    });

    // Print routine - unbind existing events first
    $('#print_routine').off('click.routines').on('click.routines', function() {
        window.print();
    });

    // Helper Functions
    function formatTimeTo12Hour(time24) {
        if (!time24) return '';
        
        const [hours, minutes] = time24.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        
        return `${hour12}:${minutes} ${ampm}`;
    }

    function loadClassesForReport(institutionId) {
        console.log('Loading classes for report, institution:', institutionId);
        $.ajax({
            url: `/institution/routines/classes/${institutionId}`,
            type: 'GET',
            success: function(response) {
                console.log('Classes loaded successfully for report:', response);
                if (response.success) {
                    const classSelect = $('#class_filter');
                    classSelect.html('<option value="">Select Class</option>');
                    response.data.forEach(function(cls) {
                        classSelect.append(`<option value="${cls.id}">${cls.name}</option>`);
                    });
                }
            },
            error: function() {
                console.error('Error loading classes for report');
                showAlert('Error loading classes', 'error');
            }
        });
    }

    function loadClassesByInstitution(institutionId) {
        console.log('Loading classes for institution:', institutionId);
        $.ajax({
            url: `/institution/routines/classes/${institutionId}`,
            type: 'GET',
            success: function(response) {
                console.log('Classes loaded successfully:', response);
                if (response.success) {
                    const classSelect = $('#class_id');
                    classSelect.html('<option value="">Select Class</option>');
                    response.data.forEach(function(cls) {
                        classSelect.append(`<option value="${cls.id}">${cls.name}</option>`);
                    });
                }
            },
            error: function() {
                console.error('Error loading classes');
                showAlert('Error loading classes', 'error');
            }
        });
    }

    function loadSectionsByClass(classId) {
        $.ajax({
            url: `/institution/routines/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const sectionSelect = $('#section_id');
                    sectionSelect.html('<option value="">Select Section</option>');
                    response.data.forEach(function(section) {
                        sectionSelect.append(`<option value="${section.id}">${section.name}</option>`);
                    });
                }
            },
            error: function() {
                showAlert('Error loading sections', 'error');
            }
        });
    }

    function loadSubjectsByInstitutionClass(institutionId, classId) {
        $.ajax({
            url: `/institution/routines/subjects/${institutionId}/${classId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    subjects = response.data;
                    $('.subject-select').html('<option value="">Select Subject</option>');
                    subjects.forEach(function(subject) {
                        $('.subject-select').append(`<option value="${subject.id}">${subject.name} (${subject.code})</option>`);
                    });
                }
            },
            error: function() {
                showAlert('Error loading subjects', 'error');
            }
        });
    }

    function loadTeachersByInstitution(institutionId) {
        console.log('Loading teachers for institution:', institutionId);
        $.ajax({
            url: `/institution/routines/teachers/${institutionId}`,
            type: 'GET',
            success: function(response) {
                console.log('Teachers loaded successfully:', response);
                if (response.success) {
                    teachers = response.data;
                    $('.teacher-select').html('<option value="">Select Teacher</option>');
                    teachers.forEach(function(teacher) {
                        $('.teacher-select').append(`<option value="${teacher.id}">${teacher.first_name} ${teacher.last_name}</option>`);
                    });
                }
            },
            error: function() {
                console.error('Error loading teachers');
                showAlert('Error loading teachers', 'error');
            }
        });
    }

    function loadExistingRoutines(classId, sectionId) {
        $.ajax({
            url: '/institution/routines/report',
            type: 'GET',
            data: {
                class_id: classId,
                section_id: sectionId
            },
            success: function(response) {
                if (response.success) {
                    displayExistingRoutines(response.data);
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('Error loading existing routines', 'error');
            }
        });
    }

    function displayExistingRoutines(routines) {
        // Clear existing routine rows
        $('[id$="-routine-tbody"]').empty();
        
        // Group routines by day
        Object.keys(routines).forEach(function(day) {
            const dayRoutines = routines[day];
            const tbody = $(`#${day}-routine-tbody`);
            
            dayRoutines.forEach(function(routine) {
                const row = createRoutineRow(routine);
                tbody.append(row);
            });
        });
    }

    function createRoutineRow(routine) {
        const template = $('#routine-row-template').html();
        return template
            .replace('__ROUTINE_ID__', routine.id)
            .replace('__SUBJECT_NAME__', routine.subject.name)
            .replace('__TEACHER_NAME__', `${routine.teacher.first_name} ${routine.teacher.last_name}`)
            .replace('__START_TIME__', formatTimeTo12Hour(routine.start_time))
            .replace('__END_TIME__', formatTimeTo12Hour(routine.end_time))
            .replace('__IS_BREAK_BADGE__', routine.is_break ? 'bg-warning' : 'bg-light text-dark')
            .replace('__IS_BREAK_TEXT__', routine.is_break ? 'Yes' : 'No')
            .replace('__IS_OTHER_DAY_BADGE__', routine.is_other_day ? 'bg-info' : 'bg-light text-dark')
            .replace('__IS_OTHER_DAY_TEXT__', routine.is_other_day ? 'Yes' : 'No')
            .replace('__CLASS_ROOM__', routine.class_room ? routine.class_room.room_no : 'N/A');
    }

    function loadRoutineReport(classId, sectionId) {
        $.ajax({
            url: '/institution/routines/report',
            type: 'GET',
            data: {
                class_id: classId,
                section_id: sectionId
            },
            success: function(response) {
                if (response.success) {
                    displayRoutineReport(response.data);
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('Error loading routine report', 'error');
            }
        });
    }

    function displayRoutineReport(routines) {
        const content = $('#routine_content');
        const template = $('#routine-template').html();
        
        if (Object.keys(routines).length === 0) {
            content.html(`
                <div class="text-center text-muted py-5">
                    <i class="ti ti-calendar-event fs-48 mb-3"></i>
                    <p>No routines found for the selected class and section</p>
                </div>
            `);
            return;
        }

        content.html(template);
        
        // Create time slots and populate routine data
        const timeSlots = generateTimeSlots(routines);
        const tbody = $('#routine-tbody');
        
        timeSlots.forEach(function(timeSlot) {
            const row = $('<tr></tr>');
            row.append(`<td class="text-center fw-bold">${formatTimeTo12Hour(timeSlot)}</td>`);
            
            ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'].forEach(function(day) {
                const cell = $('<td class="text-center"></td>');
                const dayRoutines = routines[day] || [];
                const routineAtTime = dayRoutines.find(r => r.start_time === timeSlot);
                
                if (routineAtTime) {
                    const cellTemplate = $('#routine-cell-template').html();
                    const cellContent = cellTemplate
                        .replace('__SUBJECT_NAME__', routineAtTime.subject.name)
                        .replace('__TEACHER_NAME__', `${routineAtTime.teacher.first_name} ${routineAtTime.teacher.last_name}`)
                        .replace('__CLASS_ROOM__', routineAtTime.class_room ? routineAtTime.class_room.room_no : 'N/A');
                    cell.html(cellContent);
                }
                
                row.append(cell);
            });
            
            tbody.append(row);
        });
    }

    function generateTimeSlots(routines) {
        const timeSlots = new Set();
        Object.values(routines).forEach(function(dayRoutines) {
            dayRoutines.forEach(function(routine) {
                timeSlots.add(routine.start_time);
            });
        });
        return Array.from(timeSlots).sort();
    }

    function loadSectionsForReport(classId) {
        $.ajax({
            url: `/institution/routines/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const sectionSelect = $('#section_filter');
                    sectionSelect.html('<option value="">Select Section</option>');
                    response.data.forEach(function(section) {
                        sectionSelect.append(`<option value="${section.id}">${section.name}</option>`);
                    });
                }
            },
            error: function() {
                showAlert('Error loading sections', 'error');
            }
        });
    }

    function resetDependentDropdowns(selectors) {
        selectors.forEach(function(selector) {
            $(`#${selector}`).html(`<option value="">Select ${selector.replace('_', ' ')}</option>`);
        });
    }

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-danger';
        
        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        
        $('.content').prepend(alert);
        
        setTimeout(function() {
            alert.alert('close');
        }, 5000);
    }

});
