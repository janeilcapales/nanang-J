$('#profile-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: '/profile',
        method: 'PUT',
        data: $(this).serialize(),
        success: function (res) {
            showAlert(res.message, 'success');
        },
        error: function (xhr) {
            showValidationErrors(xhr.responseJSON.errors);
        }
    });
});
 
$('#delete-account-form').on('submit', function (e) {
    e.preventDefault();
    if (!confirm('Are you sure you want to delete your account? This cannot be undone.')) return;
    $.ajax({
        url: '/profile',
        method: 'DELETE',
        data: $(this).serialize(),
        success: function (res) {
            window.location.href = res.redirect_url;
        },
        error: function (xhr) {
            let errors = xhr.responseJSON?.errors;
            if (errors && errors.password) {
                $('#delete-password-error').text(errors.password[0]);
            }
        }
    });
});