// Login
$('#login-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: '/login',
        method: 'POST',
        data: {
            email:    $('#email').val(),
            password: $('#password').val(),
            remember: $('#remember').is(':checked') ? 1 : 0,
        },
        success: function (res) {
            window.location.href = res.redirect_url;
        },
        error: function (xhr) {
            let errors = xhr.responseJSON.errors;
            $('#email-error').text(errors.email ? errors.email[0] : '');
        }
    });
});
 
// Logout
$('#logout-btn').on('click', function () {
    $.ajax({
        url: '/logout',
        method: 'POST',
        success: function (res) {
            window.location.href = res.redirect_url;
        }
    });
});