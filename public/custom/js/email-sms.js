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
        const container = $('#selected-recipients-display');
        container.empty();
        
        if (selectedRecipients.length === 0) {
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
                // Handle object recipients (from group tab)
                if (!groupedRecipients[recipient.type]) {
                    groupedRecipients[recipient.type] = [];
                }
                groupedRecipients[recipient.type].push(recipient);
            }
        });
        
        // Display grouped recipients
        Object.keys(groupedRecipients).forEach(type => {
            const recipients = groupedRecipients[type];
            const count = recipients.length;
            
            if (type === 'individual') {
                // Display individual recipients
                recipients.forEach(recipient => {
                    const iconElement = $('<div class="d-inline-block me-2 mb-2">')
                        .html(`<i class="ti ti-user text-success"></i><br><small>${recipient.name}<br><span class="text-muted">${recipient.email}</span></small>`);
                    container.append(iconElement);
                });
            } else {
                // Display group recipients with count
                const displayName = type.replace('all_', '').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                
                // Check if this group contains student contacts (for parents)
                const hasStudentContacts = recipients.some(r => r.is_student_contact);
                let icon = 'ti ti-users text-primary';
                let tooltip = '';
                
                if (type === 'all_parents' && hasStudentContacts) {
                    icon = 'ti ti-user-check text-warning';
                    tooltip = 'title="Using student contact information as parent contacts"';
                }
                
                const groupElement = $('<div class="d-inline-block me-2 mb-2">')
                    .attr(tooltip)
                    .html(`<i class="${icon}"></i><br><small>${displayName}<br><strong>${count}</strong></small>`);
                container.append(groupElement);
            }
        });
    }

    // Initialize group tab functionality
    initGroupTab();
    
    // Initialize individual tab functionality
    initIndividualTab();
    
    // Handle group recipient checkbox changes
    $(document).on('change', '.group-recipient-checkbox', function() {
        const value = $(this).val();
        const isChecked = $(this).is(':checked');
        
        if (isChecked) {
            // Add to selected recipients if not already present
            if (!selectedRecipients.some(r => r.type === value)) {
                const institutionId = $('#group-institution-select').val();
                if (institutionId) {
                    loadRecipientsByType(institutionId, value, true); // true = append mode
                }
            }
        } else {
            // Remove from selected recipients
            selectedRecipients = selectedRecipients.filter(r => r.type !== value);
            updateRecipientsDisplay();
            updateSelectedGroupsDisplay();
        }
        
        // Update select all checkbox state
        updateSelectAllCheckboxState();
    });

    // Handle class selection
    $('#class-select').on('change', function() {
        const value = $(this).val();
        if (value) {
            selectedRecipients = [value];
            updateRecipientsDisplay();
            // Load sections for the selected class
            loadSections(value);
        }
    });

    // Load sections for a class
    function loadSections(classId) {
        // This would typically make an AJAX call to load sections
        $('#section-container').show();
    }

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
            url: '/admin/email-sms/store',
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
            url: `/admin/email-sms/${messageId}/status`,
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
        $('#deleteForm').attr('action', `/admin/email-sms/delete/${messageId}`);
        $('#delete_modal').modal('show');
    });

    // Handle edit message
    $('.edit-message').on('click', function() {
        const messageId = $(this).data('message-id');
        
        $.ajax({
            url: `/admin/email-sms/edit/${messageId}`,
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

    // Load classes for class selection
    function loadClasses() {
        $.ajax({
            url: '/admin/classes/list',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#class-select');
                    $select.find('option:not(:first)').remove();
                    
                    response.data.forEach(cls => {
                        $select.append(`<option value="${cls.id}">${cls.name}</option>`);
                    });
                }
            }
        });
    }

    // Load sections for a class
    function loadSections(classId) {
        $.ajax({
            url: `/admin/academic/sections-by-class/${classId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#section-select');
                    $select.find('option:not(:first)').remove();
                    
                    response.data.forEach(section => {
                        $select.append(`<option value="${section.id}">${section.name}</option>`);
                    });
                    
                    $('#section-container').show();
                }
            }
        });
    }

    // Initialize
    loadClasses();
    
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
     * Load institutions for group selection
     */
    function loadInstitutions() {
        $.ajax({
            url: '/admin/email-sms/institutions',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#group-institution-select');
                    $select.find('option:not(:first)').remove();
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(institution => {
                            $select.append(`<option value="${institution.id}">${institution.name}</option>`);
                        });
                    } else {
                        $select.append('<option value="" disabled>No institutions available</option>');
                        toastr.warning('No active institutions found');
                    }
                } else {
                    toastr.error(response.message || 'Error loading institutions');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading institutions:', { xhr, status, error });
                
                let errorMessage = 'Error loading institutions';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
                
                const $select = $('#group-institution-select');
                $select.find('option:not(:first)').remove();
                $select.append('<option value="" disabled>Error loading institutions</option>');
            }
        });
    }
    
    /**
     * Load recipients based on institution and type
     */
    function loadRecipientsByType(institutionId, recipientType, appendMode = false) {
        let url = '';
        
        switch (recipientType) {
            case 'all_teachers':
                url = `/admin/email-sms/teachers/${institutionId}`;
                break;
            case 'all_students':
                url = `/admin/email-sms/students/${institutionId}`;
                break;
            case 'all_parents':
                url = `/admin/email-sms/parents/${institutionId}`;
                break;
            case 'all_non_working_staff':
                url = `/admin/email-sms/non-working-staff/${institutionId}`;
                break;
            default:
                return;
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const count = response.count || 0;
                    const recipients = response.data || [];
                    
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
                        
                        // Remove existing recipients of this type and add new ones
                        selectedRecipients = selectedRecipients.filter(r => r.type !== recipientType);
                        selectedRecipients = selectedRecipients.concat(newRecipients);
                        
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
                    
                    // Update displays
                    updateRecipientsDisplay();
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
                    
                    if (!selectedRecipients.some(r => r.id === recipientId)) {
                        selectedRecipients.push({
                            id: recipientId,
                            name: recipientName,
                            email: recipientEmail,
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
            const isChecked = $(this).is(':checked');
            
            if (isChecked) {
                // Add to selected recipients
                if (!selectedRecipients.some(r => r.id === recipientId)) {
                    selectedRecipients.push({
                        id: recipientId,
                        name: recipientName,
                        email: recipientEmail,
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
     * Load institutions for individual selection
     */
    function loadInstitutionsForIndividual() {
        $.ajax({
            url: '/admin/email-sms/institutions',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const $select = $('#individual-institution-select');
                    $select.find('option:not(:first)').remove();
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(institution => {
                            $select.append(`<option value="${institution.id}">${institution.name}</option>`);
                        });
                    } else {
                        $select.append('<option value="" disabled>No institutions available</option>');
                        toastr.warning('No active institutions found');
                    }
                } else {
                    toastr.error(response.message || 'Error loading institutions');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading institutions:', { xhr, status, error });
                
                let errorMessage = 'Error loading institutions';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
                
                const $select = $('#individual-institution-select');
                $select.find('option:not(:first)').remove();
                $select.append('<option value="" disabled>Error loading institutions</option>');
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
                url = `/admin/email-sms/teachers/${institutionId}`;
                break;
            case 'students':
                url = `/admin/email-sms/students/${institutionId}`;
                break;
            case 'parents':
                url = `/admin/email-sms/parents/${institutionId}`;
                break;
            case 'non_working_staff':
                url = `/admin/email-sms/non-working-staff/${institutionId}`;
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
                           data-name="${name}" data-email="${email}">
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
});
