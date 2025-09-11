// Teacher Settings JavaScript

// Profile form handling
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Ensure the file input is included in FormData
    const fileInput = document.getElementById('profile-image-input');
    if (fileInput.files.length > 0) {
        formData.append('profile_image', fileInput.files[0]);
    }
    
    fetch(profileUpdateUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            // Update profile image if changed
            if (data.data.profile_image) {
                document.getElementById('profile-image-preview').src = data.data.profile_image;
            }
        } else {
            toastr.error(data.message);
            if (data.errors) {
                Object.values(data.errors).forEach(error => {
                    toastr.error(error[0]);
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while updating profile');
    });
});

// Password form handling
document.getElementById('password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(passwordUpdateUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            this.reset();
        } else {
            toastr.error(data.message);
            if (data.errors) {
                Object.values(data.errors).forEach(error => {
                    toastr.error(error[0]);
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while changing password');
    });
});

// Profile image preview
document.getElementById('profile-image-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-image-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Delete profile image
function deleteProfileImage() {
    if (confirm('Are you sure you want to delete the profile image?')) {
        fetch(deleteImageUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                document.getElementById('profile-image-preview').src = defaultImageUrl;
                document.getElementById('profile-image-input').value = '';
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while deleting profile image');
        });
    }
}

// Reset forms
function resetProfileForm() {
    document.getElementById('profile-form').reset();
    location.reload();
}

function resetPasswordForm() {
    document.getElementById('password-form').reset();
}
