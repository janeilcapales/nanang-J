<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <!-- Alert Container -->
    <div id="passwordAlertContainer"></div>

    <form id="update-password-form" class="mt-6 space-y-6">
        @csrf

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">
                {{ __('Current Password') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                autocomplete="current-password" required />
            <div id="current_password_error" class="text-red-600 text-sm mt-2"></div>
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">
                {{ __('New Password') }}
            </label>
            <input id="update_password_password" name="password" type="password"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                autocomplete="new-password" required />
            <div id="password_error" class="text-red-600 text-sm mt-2"></div>
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">
                {{ __('Confirm Password') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                autocomplete="new-password" required />
            <div id="password_confirmation_error" class="text-red-600 text-sm mt-2"></div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" id="password-submit-btn"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <span id="password-btn-text">{{ __('Save') }}</span>
                <span id="password-btn-spinner" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i></span>
            </button>

            <p id="password-success-msg" class="text-sm text-green-600" style="display:none;">{{ __('Saved.') }}</p>
        </div>
    </form>
</section>

<script>
    $(document).ready(function() {
        $('#update-password-form').on('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            $('#current_password_error').text('');
            $('#password_error').text('');
            $('#password_confirmation_error').text('');
            $('#password-success-msg').hide();

            let formData = {
                current_password: $('#update_password_current_password').val(),
                password: $('#update_password_password').val(),
                password_confirmation: $('#update_password_password_confirmation').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('password.update') }}',
                method: 'PUT',
                data: formData,
                beforeSend: function() {
                    $('#password-submit-btn').prop('disabled', true);
                    $('#password-btn-text').hide();
                    $('#password-btn-spinner').show();
                },
                success: function(res) {
                    showPasswordAlert('{{ __('Password updated successfully!') }}',
                        'success');

                    // Clear form
                    $('#update-password-form')[0].reset();

                    // Show success message
                    $('#password-success-msg').fadeIn();
                    setTimeout(function() {
                        $('#password-success-msg').fadeOut();
                    }, 3000);
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;

                        if (errors.current_password) {
                            $('#current_password_error').text(errors.current_password[0]);
                        }
                        if (errors.password) {
                            $('#password_error').text(errors.password[0]);
                        }
                        if (errors.password_confirmation) {
                            $('#password_confirmation_error').text(errors
                                .password_confirmation[0]);
                        }

                        showPasswordAlert('{{ __('Please check the errors below') }}',
                            'danger');
                    } else {
                        let message = xhr.responseJSON?.message ||
                            '{{ __('An error occurred while updating password') }}';
                        showPasswordAlert(message, 'danger');
                    }
                },
                complete: function() {
                    $('#password-submit-btn').prop('disabled', false);
                    $('#password-btn-text').show();
                    $('#password-btn-spinner').hide();
                }
            });
        });
    });

    // Show Alert Messages
    const showPasswordAlert = (message, type = 'info') => {
        const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

        $('#passwordAlertContainer').html(alertHtml);

        // Auto-dismiss non-error alerts after 5 seconds
        if (type !== 'danger') {
            setTimeout(function() {
                $('#passwordAlertContainer').html('');
            }, 5000);
        }
    };
</script>
