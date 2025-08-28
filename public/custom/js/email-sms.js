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
        
        selectedRecipients.forEach(recipient => {
            const icon = $('<div class="d-inline-block me-2 mb-2">')
                .html('<i class="ti ti-mail-check text-success"></i><br><small>TEMPMAIL</small>');
            container.append(icon);
        });
    }

    // Handle group selection
    $('#group-select').on('change', function() {
        const value = $(this).val();
        if (value) {
            selectedRecipients = [value];
            updateRecipientsDisplay();
        }
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
});
