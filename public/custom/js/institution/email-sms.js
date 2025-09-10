$(document).ready(function() {
    let selectedRecipients = [];
    let selectedRecipientType = 'individual';

    // Handle recipient type tab changes
    $('#recipient-tabs button').on('click', function() {
        const target = $(this).data('bs-target');
        selectedRecipientType = target.replace('#', '');
        
        // Clear previous selections when switching tabs
        selectedRecipients = [];
        updateRecipientsDisplay();
    });

    // Handle recipient checkbox changes
    $('.recipient-checkbox').on('change', function() {
        const value = $(this).val();
        if ($(this).is(':checked')) {
            if (!selectedRecipients.includes(value)) {
                selectedRecipients.push(value);
            }
        } else {
            selectedRecipients = selectedRecipients.filter(r => r !== value);
        }
        updateRecipientsDisplay();
    });

    // Update recipients display
    function updateRecipientsDisplay() {
        console.log('updateRecipientsDisplay() called');
        console.log('selectedRecipients array:', selectedRecipients);
        
        const container = $('#selected-recipients-display');
        container.empty();
        
        if (selectedRecipients.length === 0) {
            console.log('No recipients selected, showing "No recipients selected" message');
            container.html('<small class="text-muted">No recipients selected</small>');
            return;
        }
        
        // Group recipients by type for better display
        const groupedRecipients = {};
        selectedRecipients.forEach(recipient => {
            if (typeof recipient === 'string') {
                // Handle simple string recipients (from individual tab)
                if (!groupedRecipients['individual']) {
                    groupedRecipients['individual'] = [];
                }
                groupedRecipients['individual'].push(recipient);
            } else if (recipient.type) {
                // Handle object recipients (from group tab, class tab, etc.)
                if (!groupedRecipients[recipient.type]) {
                    groupedRecipients[recipient.type] = [];
                }
                groupedRecipients[recipient.type].push(recipient);
            }
        });
        
        // Debug: Log what's being processed
        console.log('Selected Recipients:', selectedRecipients);
        console.log('Grouped Recipients:', groupedRecipients);
        console.log('Container element:', container);
        
        // Display grouped recipients
        Object.keys(groupedRecipients).forEach(type => {
            const recipients = groupedRecipients[type];
            const count = recipients.length;
            console.log(`Processing type: ${type}, count: ${count}`);
            
            if (type === 'individual') {
                // Display individual recipients
                recipients.forEach(recipient => {
                    const iconElement = $('<div class="d-inline-block me-2 mb-2">')
                        .html(`<i class="ti ti-user text-success"></i><br><small>${recipient.name}<br><span class="text-muted">${recipient.email}</span></small>`);
                    container.append(iconElement);
                });
            } else if (type === 'class_student') {
                // Display class students as individual recipients
                recipients.forEach(recipient => {
                    const iconElement = $('<div class="d-inline-block me-2 mb-2">')
                        .html(`<i class="ti ti-user text-success"></i><br><small>${recipient.name}<br><span class="text-muted">${recipient.email}</span></small>`);
                    container.append(iconElement);
                });
            } else if (type === 'class_parent') {
                // Display class parents as individual recipients
                recipients.forEach(recipient => {
                    const iconElement = $('<div class="d-inline-block me-2 mb-2">')
                        .html(`<i class="ti ti-user-check text-warning"></i><br><small>${recipient.name}<br><span class="text-muted">${recipient.email}</span></small>`);
                    container.append(iconElement);
                });
            } else if (type.startsWith('all_')) {
                // Display group recipients as individual recipients (for Group tab)
                const displayName = type.replace('all_', '').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                
                // Check if this group contains student contacts (for parents)
                const hasStudentContacts = recipients.some(r => r.is_student_contact);
                let icon = 'ti ti-user text-primary';
                let tooltip = '';
                
                if (type === 'all_teachers') {
                    icon = 'ti ti-users text-primary';
                } else if (type === 'all_students') {
                    icon = 'ti ti-user text-success';
                } else if (type === 'all_parents') {
                    icon = 'ti ti-user-check text-warning';
                    tooltip = 'title="Using student contact information as parent contacts"';
                } else if (type === 'all_non_working_staff') {
                    icon = 'ti ti-user-star text-info';
                }
                
                console.log(`Displaying ${count} ${type} recipients with icon: ${icon}`);
                
                // Display each recipient individually
                recipients.forEach(recipient => {
                    const iconElement = $('<div class="d-inline-block me-2 mb-2">')
                    .attr("title", tooltip || "")
                        .html(`<i class="${icon}"></i><br><small>${recipient.name}<br><span class="text-muted">${recipient.email}</span></small>`);
                    container.append(iconElement);
                });
            } else {
                // Display other group recipients with count (fallback)
                const displayName = type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                
                const groupElement = $('<div class="d-inline-block me-2 mb-2">')
                    .html(`<i class="ti ti-users text-primary"></i><br><small>${displayName}<br><strong>${count}</strong></small>`);
                container.append(groupElement);
            }
        });
        
        console.log('Final container HTML:', container.html());
    }

    // Initialize group tab functionality
    initGroupTab();
    
    // Initialize individual tab functionality
    initIndividualTab();
    
    // Handle group recipient checkbox changes
    $(document).on('change', '.group-recipient-checkbox', function() {
        const value = $(this).val();
        const isChecked = $(this).is(':checked');
        
        console.log('Group checkbox changed:', value, isChecked);
        
        if (isChecked) {
            // Add to selected recipients if not already present
            if (!selectedRecipients.some(r => r.type === value)) {
                const institutionId = $('#group-institution-select').val();
                console.log('Loading recipients for institution:', institutionId, 'type:', value);
                if (institutionId) {
                    loadRecipientsByType(institutionId, value, true); // true = append mode
                }
            }
        } else {
            // Remove from selected recipients
            selectedRecipients = selectedRecipients.filter(r => r.type !== value);
            console.log('Removed recipients of type:', value, 'New total:', selectedRecipients.length);
            updateRecipientsDisplay();
            updateSelectedGroupsDisplay();
        }
        
        // Update select all checkbox state
        updateSelectAllCheckboxState();
    });

    // Initialize class tab functionality
    initClassTab();

    // Handle class selection
    $('#class-select').on('change', function() {
        const value = $(this).val();
        if (value) {
            // Load sections for the selected class
            loadSectionsByClass(value);
        } else {
            $('#section-container').hide();
            $('#recipients-container').hide();
            $('#class-selected-display').hide();
        }
    });

    // Handle section selection
    $('#section-select').on('change', function() {
        const sectionId = $(this).val();
        const classId = $('#class-select').val();
        
        if (sectionId && classId) {
            // Load students and parents for the selected class and section
            loadStudentsAndParentsByClassSection(classId, sectionId === 'all' ? null : sectionId);
        } else {
            $('#recipients-container').hide();
            $('#class-selected-display').hide();
        }
    });

    // Handle send message
    $('#send-message').on('click', function() {
        if (selectedRecipients.length === 0) {
            toastr.error('Please select at least one recipient');
            return;
        }

        const formData = {
            title: $('#message_title').val(),
            description: $('#message_description').val(),
            send_through: $('input[name="send_through"]:checked').val(),
            recipient_type: selectedRecipientType,
            recipients: selectedRecipients,
            _token: $('input[name="_token"]').val()
        };

        // Validate required fields
        if (!formData.title || !formData.description) {
            toastr.error('Please fill in all required fields');
            return;
        }

        // Show loading state
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.html('<i class="ti ti-loader ti-spin me-2"></i>SENDING...');
        $btn.prop('disabled', true);

        $.ajax({
            url: '/institution/email-sms/store',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Reset form
                    $('#email-sms-form')[0].reset();
                    selectedRecipients = [];
                    updateRecipientsDisplay();
                    // Reload page to show new message
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        toastr.error(errors[key][0]);
                    });
                } else {
                    toastr.error('An error occurred while sending the message');
                }
            },
            complete: function() {
                // Reset button state
                $btn.html(originalText);
                $btn.prop('disabled', false);
            }
        });
    });

    // Handle status updates
    $('.message-status-select').on('change', function() {
        const messageId = $(this).data('message-id');
        const status = $(this).val();

        $.ajax({
            url: `/institution/email-sms/${messageId}/status`,
            type: 'POST',
            data: {
                status: status,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while updating status');
            }
        });
    });

    // Handle delete message
    $('.delete-message').on('click', function() {
        const messageId = $(this).data('message-id');
        $('#deleteForm').attr('action', `/institution/email-sms/delete/${messageId}`);
        $('#delete_modal').modal('show');
    });

    // Handle edit message
    $('.edit-message').on('click', function() {
        const messageId = $(this).data('message-id');
        
        $.ajax({
            url: `/institution/email-sms/edit/${messageId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const message = response.data;
                    $('#message_id').val(message.id);
                    $('#message_title').val(message.title);
                    $('#message_description').val(message.description);
                    $(`input[name="send_through"][value="${message.send_through}"]`).prop('checked', true);
                    
                    // Set recipients
                    selectedRecipients = message.recipients;
                    updateRecipientsDisplay();
                    
                    // Switch to appropriate tab
                    $(`#${message.recipient_type}-tab`).tab('show');
                    
                    // Change button text
                    $('#send-message').html('<i class="ti ti-edit me-2"></i>UPDATE');
                }
            }
        });
    });

    // Handle form submission for delete
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#delete_modal').modal('hide');
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while deleting the message');
            }
        });
    });

    /**
     * Initialize Class Tab functionality
     */
    function initClassTab() {
        console.log('Initializing Class Tab functionality...');
        
        // Load institutions when class tab is clicked
        $('#class-tab').on('click', function() {
            loadInstitutionsForClass();
        });
        
        // Handle institution selection
        $('#class-institution-select').on('change', function() {
            const institutionId = $(this).val();
            if (institutionId) {
                $('#class-container').show();
                $('#section-container').hide();
                $('#recipients-container').hide();
                $('#class-selected-display').hide();
                // Reset class selection
                $('#class-select').val('');
                selectedRecipients = [];
                updateRecipientsDisplay();
                // Load classes for the selected institution
                loadClassesByInstitution(institutionId);
            } else {
                $('#class-container').hide();
                $('#section-container').hide();
                $('#recipients-container').hide();
                $('#class-selected-display').hide();
            }
        });
        
        // Handle Select All Students checkbox
        $('#select-all-students').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.class-student-checkbox').prop('checked', isChecked);
            
            if (isChecked) {
                // Select all students
                $('.class-student-checkbox').each(function() {
                    const recipientId = $(this).val();
                    const recipientName = $(this).data('name');
                    const recipientEmail = $(this).data('email');
                    const recipientPhone = $(this).data('phone');
                    
                    if (!selectedRecipients.some(r => r.id === recipientId)) {
                        selectedRecipients.push({
                            id: recipientId,
                            name: recipientName,
                            email: recipientEmail,
                            phone: recipientPhone,
                            type: 'class_student'
                        });
                    }
                });
            } else {
                // Deselect all students
                selectedRecipients = selectedRecipients.filter(r => r.type !== 'class_student');
            }
            
            updateRecipientsDisplay();
            updateSelectedClassRecipientsDisplay();
            updateSelectAllStudentsCheckboxState();
        });
        
        // Handle Select All Parents checkbox
        $('#select-all-parents').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.class-parent-checkbox').prop('checked', isChecked);
            
            if (isChecked) {
                // Select all parents
                $('.class-parent-checkbox').each(function() {
                    const recipientId = $(this).val();
                    const recipientName = $(this).data('name');
                    const recipientEmail = $(this).data('email');
                    const recipientPhone = $(this).data('phone');
                    
                    if (!selectedRecipients.some(r => r.id === recipientId)) {
                        selectedRecipients.push({
                            id: recipientId,
                            name: recipientName,
                            email: recipientEmail,
                            phone: recipientPhone,
                            type: 'class_parent'
                        });
                    }
                });
            } else {
                // Deselect all parents
                selectedRecipients = selectedRecipients.filter(r => r.type !== 'class_parent');
            }
            
            updateRecipientsDisplay();
            updateSelectedClassRecipientsDisplay();
            updateSelectAllParentsCheckboxState();
        });
        
        // Handle individual student checkbox changes
        $(document).on('change', '.class-student-checkbox', function() {
            const recipientId = $(this).val();
            const recipientName = $(this).data('name');
            const recipientEmail = $(this).data('email');
            const recipientPhone = $(this).data('phone');
            const isChecked = $(this).is(':checked');
            
            if (isChecked) {
                // Add to selected recipients
                if (!selectedRecipients.some(r => r.id === recipientId)) {
                    selectedRecipients.push({
                        id: recipientId,
                        name: recipientName,
                        email: recipientEmail,
                        phone: recipientPhone,
                        type: 'class_student'
                    });
                }
            } else {
                // Remove from selected recipients
                selectedRecipients = selectedRecipients.filter(r => r.id !== recipientId);
            }
            
            updateRecipientsDisplay();
            updateSelectedClassRecipientsDisplay();
            updateSelectAllStudentsCheckboxState();
        });
        
        // Handle individual parent checkbox changes
        $(document).on('change', '.class-parent-checkbox', function() {
            const recipientId = $(this).val();
            const recipientName = $(this).data('name');
            const recipientEmail = $(this).data('email');
            const recipientPhone = $(this).data('phone');
            const isChecked = $(this).is(':checked');
            
            if (isChecked) {
                // Add to selected recipients
                if (!selectedRecipients.some(r => r.id === recipientId)) {
                    selectedRecipients.push({
                        id: recipientId,
                        name: recipientName,
                        email: recipientEmail,
                        phone: recipientPhone,
                        type: 'class_parent'
                    });
                }
            } else {
                // Remove from selected recipients
                selectedRecipients = selectedRecipients.filter(r => r.id !== recipientId);
            }
            
            updateRecipientsDisplay();
            updateSelectedClassRecipientsDisplay();
            updateSelectAllParentsCheckboxState();
        });
    }
    
    /**
     * Load current institution for class selection
     */
    function loadInstitutionsForClass() {
        $.ajax({
            url: '/institution/email-sms/institutions',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#class-institution-select');
                    $select.find('option:not(:first)').remove();
                    
                    if (response.data && response.data.length > 0) {
                        // Auto-select the current institution
                        const currentInstitution = response.data[0];
                        $select.append(`<option value="${currentInstitution.id}" selected>${currentInstitution.name}</option>`);
                        
                        // Trigger change event to load classes
                        $select.trigger('change');
                    } else {
                        $select.append('<option value="" disabled>No institution available</option>');
                        toastr.warning('No institution found');
                    }
                } else {
                    toastr.error(response.message || 'Error loading institution');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading institution:', { xhr, status, error });
                
                let errorMessage = 'Error loading institution';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
                
                const $select = $('#class-institution-select');
                $select.find('option:not(:first)').remove();
                $select.append('<option value="" disabled>Error loading institution</option>');
            }
        });
    }
    
    /**
     * Load classes by institution
     */
    function loadClassesByInstitution(institutionId) {
        $.ajax({
            url: `/institution/email-sms/classes/${institutionId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#class-select');
                    $select.find('option:not(:first)').remove();
                    
                    if (response.data && response.data.length > 0) {
                    response.data.forEach(cls => {
                        $select.append(`<option value="${cls.id}">${cls.name}</option>`);
                    });
                    } else {
                        $select.append('<option value="" disabled>No classes available</option>');
                        toastr.warning('No classes found for this institution');
                    }
                } else {
                    toastr.error(response.message || 'Error loading classes');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading classes:', { xhr, status, error });
                
                let errorMessage = 'Error loading classes';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
                
                const $select = $('#class-select');
                $select.find('option:not(:first)').remove();
                $select.append('<option value="" disabled>Error loading classes</option>');
            }
        });
    }

    /**
     * Load sections by class
     */
    function loadSectionsByClass(classId) {
        $.ajax({
            url: `/institution/email-sms/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#section-select');
                    $select.find('option:not(:first)').remove();
                    $select.append('<option value="all">All Sections</option>');
                    
                    if (response.data && response.data.length > 0) {
                    response.data.forEach(section => {
                        $select.append(`<option value="${section.id}">${section.name}</option>`);
                    });
                    } else {
                        $select.append('<option value="" disabled>No sections available</option>');
                        toastr.warning('No sections found for this class');
                    }
                    
                    $('#section-container').show();
                } else {
                    toastr.error(response.message || 'Error loading sections');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading sections:', { xhr, status, error });
                
                let errorMessage = 'Error loading sections';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
                
                const $select = $('#section-select');
                $select.find('option:not(:first)').remove();
                $select.append('<option value="" disabled>Error loading sections</option>');
            }
        });
    }

    /**
     * Load students and parents by class and section
     */
    function loadStudentsAndParentsByClassSection(classId, sectionId = null) {
        let url = `/institution/email-sms/class-students-parents/${classId}`;
        if (sectionId) {
            url += `/${sectionId}`;
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const students = response.data.students || [];
                    const parents = response.data.parents || [];
                    
                    displayClassStudents(students);
                    displayClassParents(parents);
                    
                    $('#recipients-container').show();
                    $('#class-selected-display').show();
                    
                    // Show success message
                    let message = `Loaded ${students.length} students and ${parents.length} parents`;
                    if (sectionId) {
                        message += ` for the selected section`;
                    }
                    toastr.success(message);
                    
                } else {
                    toastr.error(response.message || 'Error loading students and parents');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading students and parents:', { xhr, status, error });
                
                let errorMessage = 'Error loading students and parents';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
            }
        });
    }
    
    /**
     * Display class students in the list
     */
    function displayClassStudents(students) {
        const container = $('#students-list');
        container.empty();
        
        if (students.length === 0) {
            container.html('<div class="alert alert-warning">No students found for this class.</div>');
            return;
        }
        
        students.forEach(student => {
            const checkboxDiv = $(`
                <div class="form-check">
                    <input class="form-check-input class-student-checkbox" type="checkbox" 
                           value="${student.id}" id="class_student_${student.id}"
                           data-name="${student.name}" data-email="${student.email}" data-phone="${student.phone}">
                    <label class="form-check-label" for="class_student_${student.id}">
                        ${student.name} (${student.email})
                    </label>
                </div>
            `);
            
            container.append(checkboxDiv);
        });
        
        // Reset select all checkbox
        $('#select-all-students').prop('checked', false);
    }
    
    /**
     * Display class parents in the list
     */
    function displayClassParents(parents) {
        const container = $('#parents-list');
        container.empty();
        
        if (parents.length === 0) {
            container.html('<div class="alert alert-warning">No parents found for this class.</div>');
            return;
        }
        
        parents.forEach(parent => {
            const checkboxDiv = $(`
                <div class="form-check">
                    <input class="form-check-input class-parent-checkbox" type="checkbox" 
                           value="${parent.id}" id="class_parent_${parent.id}"
                           data-name="${parent.name}" data-email="${parent.email}" data-phone="${parent.phone}" >
                    <label class="form-check-label" for="class_parent_${parent.id}">
                        ${parent.name} (${parent.email})
                    </label>
                </div>
            `);
            
            container.append(checkboxDiv);
        });
        
        // Reset select all checkbox
        $('#select-all-parents').prop('checked', false);
    }
    
    /**
     * Update the selected class recipients display
     */
    function updateSelectedClassRecipientsDisplay() {
        const container = $('#selected-class-recipients-display');
        container.empty();
        
        const classStudents = selectedRecipients.filter(r => r.type === 'class_student');
        const classParents = selectedRecipients.filter(r => r.type === 'class_parent');
        
        if (classStudents.length === 0 && classParents.length === 0) {
            $('#class-selected-display').hide();
            $('#selected-students-detail').hide();
            $('#selected-parents-detail').hide();
            return;
        }
        
        // Display summary badges
        if (classStudents.length > 0) {
            const studentsBadge = $(`<span class="badge badge-soft-primary me-2 mb-2">`)
                .html(`<i class="ti ti-user me-1"></i>Students: ${classStudents.length}`);
            container.append(studentsBadge);
        }
        
        if (classParents.length > 0) {
            const parentsBadge = $(`<span class="badge badge-soft-warning me-2 mb-2">`)
                .html(`<i class="ti ti-user-check me-1"></i>Parents: ${classParents.length}`);
            container.append(parentsBadge);
        }
        
        // Update detailed lists
        updateSelectedStudentsDetail(classStudents);
        updateSelectedParentsDetail(classParents);
        
        $('#class-selected-display').show();
    }
    
    /**
     * Update the selected students detail list
     */
    function updateSelectedStudentsDetail(students) {
        const container = $('#selected-students-list');
        container.empty();
        
        if (students.length === 0) {
            $('#selected-students-detail').hide();
            return;
        }
        
        students.forEach(student => {
            const studentItem = $(`
                <div class="d-flex align-items-center justify-content-between p-2 border-bottom selected-recipient-item">
                    <div>
                        <i class="ti ti-user text-primary me-2"></i>
                        <span class="fw-medium">${student.name}</span>
                        <br>
                        <small class="text-muted">${student.email}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeSelectedRecipient('${student.id}', 'class_student')">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            `);
            container.append(studentItem);
        });
        
        $('#selected-students-detail').show();
    }
    
    /**
     * Update the selected parents detail list
     */
    function updateSelectedParentsDetail(parents) {
        const container = $('#selected-parents-list');
        container.empty();
        
        if (parents.length === 0) {
            $('#selected-parents-detail').hide();
            return;
        }
        
        parents.forEach(parent => {
            const parentItem = $(`
                <div class="d-flex align-items-center justify-content-between p-2 border-bottom selected-recipient-item">
                    <div>
                        <i class="ti ti-user-check text-warning me-2"></i>
                        <span class="fw-medium">${parent.name}</span>
                        <br>
                        <small class="text-muted">${parent.email}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeSelectedRecipient('${parent.id}', 'class_parent')">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            `);
            container.append(parentItem);
        });
        
        $('#selected-parents-detail').show();
    }
    
    /**
     * Remove a selected recipient
     */
    function removeSelectedRecipient(recipientId, type) {
        // Remove from selectedRecipients array
        selectedRecipients = selectedRecipients.filter(r => r.id !== recipientId);
        
        // Uncheck the corresponding checkbox
        if (type === 'class_student') {
            $(`.class-student-checkbox[value="${recipientId}"]`).prop('checked', false);
        } else if (type === 'class_parent') {
            $(`.class-parent-checkbox[value="${recipientId}"]`).prop('checked', false);
        }
        
        // Update displays
        updateRecipientsDisplay();
        updateSelectedClassRecipientsDisplay();
        updateSelectAllStudentsCheckboxState();
        updateSelectAllParentsCheckboxState();
    }
    
    /**
     * Update the select all students checkbox state
     */
    function updateSelectAllStudentsCheckboxState() {
        const totalCheckboxes = $('.class-student-checkbox').length;
        const checkedCheckboxes = $('.class-student-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#select-all-students').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#select-all-students').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#select-all-students').prop('indeterminate', true).prop('checked', false);
        }
    }
    
    /**
     * Update the select all parents checkbox state
     */
    function updateSelectAllParentsCheckboxState() {
        const totalCheckboxes = $('.class-parent-checkbox').length;
        const checkedCheckboxes = $('.class-parent-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#select-all-parents').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#select-all-parents').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#select-all-parents').prop('indeterminate', true).prop('checked', false);
        }
    }
    
    /**
     * Initialize Group Tab functionality
     */
    function initGroupTab() {
        console.log('Initializing Group Tab functionality...');
        
        // Load institutions when group tab is clicked
        $('#group-tab').on('click', function() {
            loadInstitutions();
        });
        
        // Handle institution selection
        $('#group-institution-select').on('change', function() {
            const institutionId = $(this).val();
            if (institutionId) {
                $('#group-recipient-container').show();
                $('#group-count-display').hide();
                $('#group-selected-groups').hide();
                // Reset recipient selection
                $('.group-recipient-checkbox').prop('checked', false);
                $('#select-all-groups').prop('checked', false);
                selectedRecipients = [];
                updateRecipientsDisplay();
                updateSelectedGroupsDisplay();
            } else {
                $('#group-recipient-container').hide();
                $('#group-count-display').hide();
                $('#group-selected-groups').hide();
            }
        });
        
        // Handle recipient group selection - now handled by checkbox change events
        // The checkboxes are handled globally above
        
        // Handle Select All checkbox
        $('#select-all-groups').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.group-recipient-checkbox').prop('checked', isChecked);
            
            if (isChecked) {
                // Select all groups
                const institutionId = $('#group-institution-select').val();
                if (institutionId) {
                    // Load all recipient types
                    const recipientTypes = ['all_teachers', 'all_students', 'all_parents', 'all_non_working_staff'];
                    recipientTypes.forEach(type => {
                        if (!selectedRecipients.some(r => r.type === type)) {
                            loadRecipientsByType(institutionId, type, true);
                        }
                    });
                }
            } else {
                // Deselect all groups
                selectedRecipients = [];
                updateRecipientsDisplay();
                updateSelectedGroupsDisplay();
            }
        });
    }
    
    /**
     * Load current institution for group selection
     */
    function loadInstitutions() {
        $.ajax({
            url: '/institution/email-sms/institutions',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#group-institution-select');
                    $select.find('option:not(:first)').remove();
                    
                    if (response.data && response.data.length > 0) {
                        // Auto-select the current institution
                        const currentInstitution = response.data[0];
                        $select.append(`<option value="${currentInstitution.id}" selected>${currentInstitution.name}</option>`);
                        
                        // Trigger change event to load recipients
                        $select.trigger('change');
                    } else {
                        $select.append('<option value="" disabled>No institution available</option>');
                        toastr.warning('No institution found');
                    }
                } else {
                    toastr.error(response.message || 'Error loading institution');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading institution:', { xhr, status, error });
                
                let errorMessage = 'Error loading institution';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
                
                const $select = $('#group-institution-select');
                $select.find('option:not(:first)').remove();
                $select.append('<option value="" disabled>Error loading institution</option>');
            }
        });
    }
    
    /**
     * Load recipients based on institution and type
     */
    function loadRecipientsByType(institutionId, recipientType, appendMode = false) {
        console.log(`loadRecipientsByType called: institutionId=${institutionId}, recipientType=${recipientType}, appendMode=${appendMode}`);
        
        let url = '';
        
        switch (recipientType) {
            case 'all_teachers':
                url = `/institution/email-sms/teachers/${institutionId}`;
                break;
            case 'all_students':
                url = `/institution/email-sms/students/${institutionId}`;
                break;
            case 'all_parents':
                url = `/institution/email-sms/parents/${institutionId}`;
                break;
            case 'all_non_working_staff':
                url = `/institution/email-sms/non-working-staff/${institutionId}`;
                break;
            default:
                return;
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log('AJAX response received:', response);
                
                if (response.success) {
                    const count = response.count || 0;
                    const recipients = response.data || [];
                    
                    console.log(`Processing ${count} recipients for type: ${recipientType}`);
                    console.log('Raw recipients data:', recipients);
                    
                    if (appendMode) {
                        // Append mode: add new recipients to existing ones
                        const newRecipients = recipients.map(r => ({
                            id: r.id,
                            type: recipientType,
                            name: r.first_name ? `${r.first_name} ${r.last_name}` : r.name,
                            email: r.email,
                            phone: r.phone,
                            is_student_contact: r.is_student_contact || false
                        }));
                        
                        console.log('Mapped new recipients:', newRecipients);
                        
                        // Remove existing recipients of this type and add new ones
                        selectedRecipients = selectedRecipients.filter(r => r.type !== recipientType);
                        selectedRecipients = selectedRecipients.concat(newRecipients);
                        
                        console.log('Updated selectedRecipients array:', selectedRecipients);
                        
                        let message = `Added ${count} ${recipientType.replace('all_', '')}`;
                        if (response.message) {
                            message += ` - ${response.message}`;
                        }
                        toastr.success(message);
                    } else {
                        // Replace mode: replace all recipients
                        selectedRecipients = recipients.map(r => ({
                            id: r.id,
                            type: recipientType,
                            name: r.first_name ? `${r.first_name} ${r.last_name}` : r.name,
                            email: r.email,
                            phone: r.phone,
                            is_student_contact: r.is_student_contact || false
                        }));
                        
                        let message = `Loaded ${count} recipients`;
                        if (response.message) {
                            message += ` - ${response.message}`;
                        }
                        toastr.success(message);
                    }
                    
                    // Debug: Log what was loaded
                    // if (appendMode) {
                    //     console.log(`Loaded ${recipientType}:`, newRecipients);
                    // }
                    // ...existing code...
                    if (appendMode) {
                        // Append mode: add new recipients to existing ones
                        const newRecipients = recipients.map(r => ({
                            id: r.id,
                            type: recipientType,
                            name: r.first_name ? `${r.first_name} ${r.last_name}` : r.name,
                            email: r.email,
                            phone: r.phone,
                            is_student_contact: r.is_student_contact || false
                        }));

                        console.log('Mapped new recipients:', newRecipients);

                        // Remove existing recipients of this type and add new ones
                        selectedRecipients = selectedRecipients.filter(r => r.type !== recipientType);
                        selectedRecipients = selectedRecipients.concat(newRecipients);

                        console.log('Updated selectedRecipients array:', selectedRecipients);

                        let message = `Added ${count} ${recipientType.replace('all_', '')}`;
                        if (response.message) {
                            message += ` - ${response.message}`;
                        }
                        toastr.success(message);

                        // Debug: Log what was loaded
                        console.log(`Loaded ${recipientType}:`, newRecipients);
                    } else {
                        // Replace mode: replace all recipients
                        selectedRecipients = recipients.map(r => ({
                            id: r.id,
                            type: recipientType,
                            name: r.first_name ? `${r.first_name} ${r.last_name}` : r.name,
                            email: r.email,
                            phone: r.phone,
                            is_student_contact: r.is_student_contact || false
                        }));

                        let message = `Loaded ${count} recipients`;
                        if (response.message) {
                            message += ` - ${response.message}`;
                        }
                        toastr.success(message);
                    }
                    // ...existing code...
                    console.log('Total selected recipients:', selectedRecipients);
                    
                    // Update displays
                    console.log('Calling updateRecipientsDisplay()...');
                    updateRecipientsDisplay();
                    console.log('Calling updateSelectedGroupsDisplay()...');
                    updateSelectedGroupsDisplay();
                    
                    // Update count display
                    const totalCount = selectedRecipients.length;
                    $('#recipient-count-text').text(`Total recipients: ${totalCount}`);
                    $('#group-count-display').show();
                    
                } else {
                    toastr.error(response.message || 'Error loading recipients');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading recipients:', { xhr, status, error });
                
                let errorMessage = 'Error loading recipients';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMessage = 'Recipient data not found. Please check if the institution has any data.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred while loading recipients.';
                }
                
                toastr.error(errorMessage);
                
                // Uncheck the checkbox that caused the error
                $(`.group-recipient-checkbox[value="${recipientType}"]`).prop('checked', false);
            }
        });
    }
    
    /**
     * Update the selected groups display
     */
    function updateSelectedGroupsDisplay() {
        const container = $('#selected-groups-display');
        container.empty();
        
        if (selectedRecipients.length === 0) {
            $('#group-selected-groups').hide();
            return;
        }
        
        // Group recipients by type
        const groupedRecipients = {};
        selectedRecipients.forEach(recipient => {
            if (recipient.type) {
                if (!groupedRecipients[recipient.type]) {
                    groupedRecipients[recipient.type] = [];
                }
                groupedRecipients[recipient.type].push(recipient);
            }
        });
        
        // Display each group
        Object.keys(groupedRecipients).forEach(type => {
            const count = groupedRecipients[type].length;
            const displayName = type.replace('all_', '').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            
            const groupBadge = $(`<span class="badge badge-soft-primary me-2 mb-2">`)
                .html(`<i class="ti ti-users me-1"></i>${displayName}: ${count}`);
            
            container.append(groupBadge);
        });
        
        $('#group-selected-groups').show();
    }
    
    /**
     * Update the select all checkbox state based on individual checkbox states
     */
    function updateSelectAllCheckboxState() {
        const totalCheckboxes = $('.group-recipient-checkbox').length;
        const checkedCheckboxes = $('.group-recipient-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#select-all-groups').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#select-all-groups').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#select-all-groups').prop('indeterminate', true).prop('checked', false);
        }
    }
    
    /**
     * Initialize Individual Tab functionality
     */
    function initIndividualTab() {
        console.log('Initializing Individual Tab functionality...');
        
        // Load institutions when individual tab is clicked
        $('#individual-tab').on('click', function() {
            loadInstitutionsForIndividual();
        });
        
        // Handle institution selection
        $('#individual-institution-select').on('change', function() {
            const institutionId = $(this).val();
            if (institutionId) {
                $('#individual-role-container').show();
                $('#individual-recipient-container').hide();
                $('#individual-selected-display').hide();
                // Reset role selection
                $('#individual-role-select').val('');
                selectedRecipients = [];
                updateRecipientsDisplay();
            } else {
                $('#individual-role-container').hide();
                $('#individual-recipient-container').hide();
                $('#individual-selected-display').hide();
            }
        });
        
        // Handle role selection
        $('#individual-role-select').on('change', function() {
            const role = $(this).val();
            const institutionId = $('#individual-institution-select').val();
            
            if (role && institutionId) {
                loadIndividualsByRole(institutionId, role);
            } else {
                $('#individual-recipient-container').hide();
                $('#individual-selected-display').hide();
            }
        });
        
        // Handle Select All individuals checkbox
        $('#select-all-individuals').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.individual-recipient-checkbox').prop('checked', isChecked);
            
            if (isChecked) {
                // Select all individuals
                $('.individual-recipient-checkbox:checked').each(function() {
                    const recipientId = $(this).val();
                    const recipientName = $(this).data('name');
                    const recipientEmail = $(this).data('email');
                    const recipientPhone = $(this).data('phone');
                    
                    if (!selectedRecipients.some(r => r.id === recipientId)) {
                        selectedRecipients.push({
                            id: recipientId,
                            name: recipientName,
                            email: recipientEmail,
                            phone: recipientPhone,
                            type: 'individual'
                        });
                    }
                });
            } else {
                // Deselect all individuals
                selectedRecipients = selectedRecipients.filter(r => r.type !== 'individual');
            }
            
            updateRecipientsDisplay();
            updateSelectedIndividualsDisplay();
        });
        
        // Handle individual recipient checkbox changes
        $(document).on('change', '.individual-recipient-checkbox', function() {
            const recipientId = $(this).val();
            const recipientName = $(this).data('name');
            const recipientEmail = $(this).data('email');
            const recipientPhone = $(this).data('phone');
            const isChecked = $(this).is(':checked');
            
            if (isChecked) {
                // Add to selected recipients
                if (!selectedRecipients.some(r => r.id === recipientId)) {
                    selectedRecipients.push({
                        id: recipientId,
                        name: recipientName,
                        email: recipientEmail,
                        phone: recipientPhone,
                        type: 'individual'
                    });
                }
            } else {
                // Remove from selected recipients
                selectedRecipients = selectedRecipients.filter(r => r.id !== recipientId);
            }
            
            updateRecipientsDisplay();
            updateSelectedIndividualsDisplay();
            updateSelectAllIndividualsCheckboxState();
        });
        
        // Handle individual search
        $('#individual-search').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterIndividualRecipients(searchTerm);
        });
    }
    
    /**
     * Load current institution for individual selection
     */
    function loadInstitutionsForIndividual() {
        $.ajax({
            url: '/institution/email-sms/institutions',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#individual-institution-select');
                    $select.find('option:not(:first)').remove();
                    
                    if (response.data && response.data.length > 0) {
                        // Auto-select the current institution
                        const currentInstitution = response.data[0];
                        $select.append(`<option value="${currentInstitution.id}" selected>${currentInstitution.name}</option>`);
                        
                        // Trigger change event to show role selection
                        $select.trigger('change');
                    } else {
                        $select.append('<option value="" disabled>No institution available</option>');
                        toastr.warning('No institution found');
                    }
                } else {
                    toastr.error(response.message || 'Error loading institution');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading institution:', { xhr, status, error });
                
                let errorMessage = 'Error loading institution';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
                
                const $select = $('#individual-institution-select');
                $select.find('option:not(:first)').remove();
                $select.append('<option value="" disabled>Error loading institution</option>');
            }
        });
    }
    
    /**
     * Update the select all individuals checkbox state
     */
    function updateSelectAllIndividualsCheckboxState() {
        const totalCheckboxes = $('.individual-recipient-checkbox').length;
        const checkedCheckboxes = $('.individual-recipient-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#select-all-individuals').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#select-all-individuals').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#select-all-individuals').prop('indeterminate', true).prop('checked', false);
        }
    }
    
    /**
     * Filter individual recipients based on search term
     */
    function filterIndividualRecipients(searchTerm) {
        $('.individual-recipient-checkbox').each(function() {
            const $checkbox = $(this);
            const $label = $checkbox.next('label');
            const text = $label.text().toLowerCase();
            
            if (text.includes(searchTerm)) {
                $checkbox.closest('.form-check').show();
            } else {
                $checkbox.closest('.form-check').hide();
            }
        });
        
        // Update select all checkbox state after filtering
        updateSelectAllIndividualsCheckboxState();
    }
    
    /**
     * Load individuals by role and institution
     */
    function loadIndividualsByRole(institutionId, role) {
        let url = '';
        
        switch (role) {
            case 'teachers':
                url = `/institution/email-sms/teachers/${institutionId}`;
                break;
            case 'students':
                url = `/institution/email-sms/students/${institutionId}`;
                break;
            case 'parents':
                url = `/institution/email-sms/parents/${institutionId}`;
                break;
            case 'non_working_staff':
                url = `/institution/email-sms/non-working-staff/${institutionId}`;
                break;
            default:
                return;
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const recipients = response.data || [];
                    displayIndividualRecipients(recipients, role);
                    $('#individual-recipient-container').show();
                    $('#individual-selected-display').show();
                } else {
                    toastr.error(response.message || 'Error loading individuals');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading individuals:', { xhr, status, error });
                
                let errorMessage = 'Error loading individuals';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
            }
        });
    }
    
        /**
     * Display individual recipients in the list
     */
    function displayIndividualRecipients(recipients, role) {
        const container = $('#individual-recipients-list');
        container.empty();
        
        if (recipients.length === 0) {
            container.html('<div class="alert alert-warning">No individuals found for this role.</div>');
            return;
        }
        
        recipients.forEach(recipient => {
            const name = recipient.first_name ? `${recipient.first_name} ${recipient.last_name}` : recipient.name;
            const email = recipient.email || 'No email';
            const displayText = `${name} (${email})`;
            
            const checkboxDiv = $(`
                <div class="form-check mb-2">
                    <input class="form-check-input individual-recipient-checkbox" type="checkbox" 
                           value="${recipient.id}" id="individual_${recipient.id}"
                           data-name="${name}" data-email="${email}" data-phone="${recipient.phone}">
                    <label class="form-check-label" for="individual_${recipient.id}">
                        ${displayText}
                </label>
                </div>
            `);
            
            container.append(checkboxDiv);
        });
        
        // Reset select all checkbox
        $('#select-all-individuals').prop('checked', false);
    }
    
    /**
     * Update the selected individuals display
     */
    function updateSelectedIndividualsDisplay() {
        const container = $('#selected-individuals-display');
        container.empty();
        
        const individualRecipients = selectedRecipients.filter(r => r.type === 'individual');
        
        if (individualRecipients.length === 0) {
            $('#individual-selected-display').hide();
            return;
        }
        
        individualRecipients.forEach(recipient => {
            const badge = $(`<span class="badge badge-soft-success me-2 mb-2">`)
                .html(`<i class="ti ti-user me-1"></i>${recipient.name}<br><small>${recipient.email}</small>`);
            
            container.append(badge);
        });
        
        $('#individual-selected-display').show();
    }
    
    // Old checkbox-based functions removed - now using dropdown-based selection
    
    // Make removeSelectedRecipient function globally accessible
    window.removeSelectedRecipient = function(recipientId, type) {
        // Remove from selectedRecipients array
        selectedRecipients = selectedRecipients.filter(r => r.id !== recipientId);
        
        // Uncheck the corresponding checkbox
        if (type === 'class_student') {
            $(`.class-student-checkbox[value="${recipientId}"]`).prop('checked', false);
        } else if (type === 'class_parent') {
            $(`.class-parent-checkbox[value="${recipientId}"]`).prop('checked', false);
        }
        
        // Update displays
        updateRecipientsDisplay();
        updateSelectedClassRecipientsDisplay();
        updateSelectAllStudentsCheckboxState();
        updateSelectAllParentsCheckboxState();
    };
});