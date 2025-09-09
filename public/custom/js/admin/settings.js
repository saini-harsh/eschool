$(document).ready(function() {
    // Profile Image Preview
    $('#profile-image-input').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#profile-image-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });


    // Profile Form Submission
    $('#profile-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const profileImage = $('#profile-image-input')[0].files[0];
        
        if (profileImage) {
            formData.append('logo', profileImage);
        }

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="ti ti-loader ti-spin me-2"></i>Saving...');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: '/admin/settings/profile',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Update sidebar if needed
                    if (response.data.logo) {
                        $('.sidebar-footer .avatar img').attr('src', '/storage/' + response.data.logo);
                    }
                    // Update the image preview with the new uploaded image
                    if (profileImage) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#profile-image-preview').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(profileImage);
                    }
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
                    toastr.error('An error occurred while updating profile');
                }
            },
            complete: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });


    // Password Form Submission
    $('#password-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="ti ti-loader ti-spin me-2"></i>Changing...');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: '/admin/settings/change-password',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    resetPasswordForm();
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
                } else if (xhr.status === 400) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('An error occurred while changing password');
                }
            },
            complete: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });

});

// Delete Profile Image
function deleteProfileImage() {
    if (confirm('Are you sure you want to delete the profile image?')) {
        $.ajax({
            url: '/admin/settings/delete-profile-image',
            type: 'POST',
            data: {
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#profile-image-preview').attr('src', '/adminpanel/img/users/avatar-2.jpg');
                    $('#profile-image-input').val('');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while deleting profile image');
            }
        });
    }
}

// Reset Profile Form
function resetProfileForm() {
    $('#profile-form')[0].reset();
    // Reset image preview to original
    const originalSrc = $('#profile-image-preview').data('original-src') || '/adminpanel/img/users/avatar-2.jpg';
    $('#profile-image-preview').attr('src', originalSrc);
}

// Reset Password Form
function resetPasswordForm() {
    $('#password-form')[0].reset();
}

// Store original image sources for reset functionality
$(document).ready(function() {
    $('#profile-image-preview').data('original-src', $('#profile-image-preview').attr('src'));
});
