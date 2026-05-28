<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <!-- Alert Container -->
    <div id="profileAlertContainer"></div>

    <form id="update-profile-form" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                {{ __('Name') }}
            </label>
            <input id="name" name="name" type="text"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                value="{{ $user->name }}" required autofocus autocomplete="name" />
            <div id="name_error" class="text-red-600 text-sm mt-2"></div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                {{ __('Email') }}
            </label>
            <input id="email" name="email" type="email"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                value="{{ $user->email }}" required autocomplete="username" />
            <div id="email_error" class="text-red-600 text-sm mt-2"></div>

            <!-- Email Verification Status -->
            <div id="verification-status" style="display:none;">
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" id="send-verification-btn"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                <p id="verification-message" class="mt-2 font-medium text-sm text-green-600" style="display:none;">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" id="profile-submit-btn"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <span id="profile-btn-text">{{ __('Save') }}</span>
                <span id="profile-btn-spinner" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i></span>
            </button>

            <p id="profile-success-msg" class="text-sm text-gray-600" style="display:none;">{{ __('Saved.') }}</p>
        </div>
    </form>
</section>

<script>
    $(document).ready(function() {
        // Check if email verification is needed
        checkEmailVerification();

        // Update profile form submission
        $('#update-profile-form').on('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            $('#name_error').text('');
            $('#email_error').text('');
            $('#profile-success-msg').hide();

            let formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('profile.update') }}',
                method: 'PATCH',
                data: formData,
                beforeSend: function() {
                    $('#profile-submit-btn').prop('disabled', true);
                    $('#profile-btn-text').hide();
                    $('#profile-btn-spinner').show();
                },
                success: function(res) {
                    showProfileAlert('{{ __('Profile updated successfully!') }}',
                        'success');

                    // Show success message
                    $('#profile-success-msg').fadeIn();
                    setTimeout(function() {
                        $('#profile-success-msg').fadeOut();
                    }, 3000);

                    if (res.email_verified === false) {
                        $('#verification-status').show();
                    } else {
                        $('#verification-status').hide();
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;

                        if (errors.name) {
                            $('#name_error').text(errors.name[0]);
                        }
                        if (errors.email) {
                            $('#email_error').text(errors.email[0]);
                        }

                        showProfileAlert('{{ __('Please check the errors below') }}',
                            'danger');
                    } else {
                        let message = xhr.responseJSON?.message ||
                            '{{ __('An error occurred while updating profile') }}';
                        showProfileAlert(message, 'danger');
                    }
                },
                complete: function() {
                    $('#profile-submit-btn').prop('disabled', false);
                    $('#profile-btn-text').show();
                    $('#profile-btn-spinner').hide();
                }
            });
        });

        // Send verification email
        $('#send-verification-btn').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('verification.send') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#send-verification-btn').prop('disabled', true);
                    $('#send-verification-btn').text('{{ __('Sending...') }}');
                },
                success: function(res) {
                    showProfileAlert('{{ __('Verification link sent to your email!') }}',
                        'success');
                    $('#verification-message').fadeIn();

                    setTimeout(function() {
                        $('#verification-message').fadeOut();
                    }, 5000);
                },
                error: function(xhr) {
                    let message =
                        '{{ __('Failed to send verification email. Please try again.') }}';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showProfileAlert(message, 'danger');
                },
                complete: function() {
                    $('#send-verification-btn').prop('disabled', false);
                    $('#send-verification-btn').text(
                        '{{ __('Click here to re-send the verification email.') }}');
                }
            });
        });
    });

    // Check if email verification is needed
    function checkEmailVerification() {
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            $('#verification-status').show();
        @else
            $('#verification-status').hide();
        @endif
    }

    // Show Alert Messages
    const showProfileAlert = (message, type = 'info') => {
        const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

        $('#profileAlertContainer').html(alertHtml);

        // Auto-dismiss non-error alerts after 5 seconds
        if (type !== 'danger') {
            setTimeout(function() {
                $('#profileAlertContainer').html('');
            }, 5000);
        }
    };
</script>
