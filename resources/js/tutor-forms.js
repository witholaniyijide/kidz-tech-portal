/**
 * Tutor Forms JavaScript Helper
 *
 * Provides client-side enhancements for tutor forms including:
 * - Profile photo preview
 * - DOB to age calculation
 * - Form validation UX
 * - Confirm submission modals
 * - AJAX comment posting
 */

document.addEventListener('DOMContentLoaded', function() {
    // Profile Photo Preview
    initProfilePhotoPreview();

    // DOB to Age calculation
    initDOBCalculator();

    // Form submission confirmation
    initFormConfirmations();

    // AJAX comment posting
    initAjaxComments();

    // Disable submit button while processing
    initFormSubmitProtection();
});

/**
 * Profile photo preview function
 */
function initProfilePhotoPreview() {
    const photoInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('profile_photo_preview');

    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, JPEG, PNG, or WEBP)');
                photoInput.value = '';
                return;
            }

            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must not exceed 2MB');
                photoInput.value = '';
                return;
            }

            // Preview the image
            const reader = new FileReader();
            reader.onload = function(ev) {
                photoPreview.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        });
    }
}

/**
 * DOB to Age calculation
 */
function initDOBCalculator() {
    const dobInput = document.getElementById('date_of_birth');
    const ageDisplay = document.getElementById('age_display');

    if (dobInput && ageDisplay) {
        dobInput.addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            ageDisplay.textContent = age >= 0 ? `${age} years old` : '';
        });
    }
}

/**
 * Form confirmation dialogs
 */
function initFormConfirmations() {
    // Report submission confirmation
    const reportSubmitBtn = document.querySelector('button[name="status"][value="submitted"]');
    if (reportSubmitBtn) {
        reportSubmitBtn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to submit this report? Once submitted, it will be reviewed by the manager.')) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Attendance submission warning
    const attendanceForm = document.querySelector('form[action*="attendance"]');
    if (attendanceForm && !attendanceForm.dataset.confirmed) {
        attendanceForm.addEventListener('submit', function(e) {
            if (!attendanceForm.dataset.submitConfirmed) {
                e.preventDefault();
                if (confirm('Submit this attendance record for manager approval?')) {
                    attendanceForm.dataset.submitConfirmed = 'true';
                    attendanceForm.submit();
                }
            }
        });
    }
}

/**
 * AJAX comment posting
 */
function initAjaxComments() {
    const commentForm = document.querySelector('form[action*="comments"]');
    if (!commentForm) return;

    // Only enable AJAX if user wants it (check for data attribute)
    if (!commentForm.dataset.ajax) return;

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Posting...';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear the comment textarea
                this.querySelector('textarea[name="comment"]').value = '';

                // Show success message
                showNotification('Comment posted successfully!', 'success');

                // Reload page to show new comment
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('Failed to post comment. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        })
        .finally(() => {
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
}

/**
 * Protect forms from double submission
 */
function initFormSubmitProtection() {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButtons = this.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                const originalText = btn.textContent;
                btn.textContent = 'Processing...';

                // Re-enable after 3 seconds (in case of validation errors)
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }, 3000);
            });
        });
    });
}

/**
 * Show notification helper
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    } text-white`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

/**
 * Export for use in other modules
 */
export {
    initProfilePhotoPreview,
    initDOBCalculator,
    initFormConfirmations,
    initAjaxComments,
    initFormSubmitProtection,
    showNotification,
};
