<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <!-- Alert Container -->
    <div id="deleteAlertContainer"></div>

    <button type="button" id="delete-account-btn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
        {{ __('Delete Account') }}
    </button>

    <!-- Delete Account Modal -->
    <div id="confirm-user-deletion" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
        style="display:none;">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <form id="delete-user-form" class="mt-6">
                @csrf
                <div>
                    <label for="password" class="sr-only">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-red-500"
                        placeholder="{{ __('Password') }}" required />
                    <div id="passwordError" class="text-red-600 text-sm mt-2"></div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" id="cancel-btn"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" id="delete-btn"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        <span id="delete-text">{{ __('Delete Account') }}</span>
                        <span id="delete-spinner" style="display:none;"><i
                                class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        const $modal = $('#confirm-user-deletion');
        const $deleteBtn = $('#delete-account-btn');
        const $cancelBtn = $('#cancel-btn');
        const $deleteForm = $('#delete-user-form');
        const $submitBtn = $('#delete-btn');

        // Open modal
        $deleteBtn.on('click', function() {
            $modal.css('display', 'flex');
            $('#password').val('').focus();
            $('#passwordError').text('');
        });

        // Close modal
        $cancelBtn.on('click', function() {
            $modal.css('display', 'none');
            $('#password').val('');
            $('#passwordError').text('');
        });

        // Close modal when clicking outside
        $modal.on('click', function(e) {
            if (e.target === this) {
                $modal.addClass('hidden');
            }
        });

        // Submit delete form
        $deleteForm.on('submit', function(e) {
            e.preventDefault();

            let password = $('#password').val();

            if (!password) {
                $('#passwordError').text('{{ __('Password is required') }}');
                return;
            }

            $.ajax({
                url: '{{ route('profile.destroy') }}',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    password: password
                },
                beforeSend: function() {
                    $submitBtn.prop('disabled', true);
                    $('#delete-text').hide();
                    $('#delete-spinner').show();
                    $('#passwordError').text('');
                },
                success: function(res) {
                    showAlert(
                        '{{ __('Account deleted successfully. You will be redirected shortly.') }}',
                        'success');

                    // Redirect to login after 2 seconds
                    setTimeout(function() {
                        window.location.href = '{{ route('login') }}';
                    }, 2000);
                },
                error: function(xhr) {
                    let message =
                        '{{ __('An error occurred while deleting your account.') }}';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            if (xhr.responseJSON.errors.password) {
                                message = xhr.responseJSON.errors.password[0];
                                $('#passwordError').text(message);
                            } else {
                                const errors = xhr.responseJSON.errors;
                                message = Object.values(errors).flat().join('\n');
                            }
                        }
                    }

                    if (!$('#passwordError').text()) {
                        showAlert(message, 'danger');
                    }
                },
                complete: function() {
                    $submitBtn.prop('disabled', false);
                    $('#delete-text').show();
                    $('#delete-spinner').hide();
                }
            });
        });
    });

    // Show Alert Messages
    const showAlert = (message, type = 'info') => {
        const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

        $('#deleteAlertContainer').html(alertHtml);

        // Auto-dismiss non-error alerts after 5 seconds
        if (type !== 'danger') {
            setTimeout(function() {
                $('#deleteAlertContainer').html('');
            }, 5000);
        }
    };
</script>
