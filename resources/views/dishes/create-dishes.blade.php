@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h2>Add New Dish</h2>
        <p>Create a new item for your restaurant menu</p>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-card">
                <form id="dish-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Dish Name</label>
                        <input type="text" id="dish-name" name="name" class="form-control" required
                            placeholder="e.g. Special Adobo">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price (₱)</label>
                        <input type="number" id="dish-price" step="0.01" name="price" class="form-control" required
                            placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dish Image</label>
                        <input type="file" id="dish-image" name="image" class="form-control">
                    </div>

                    <div style="margin-top: 20px;">
                        <button type="submit" id="submit-btn" class="btn-warning" style="width: 100%;">
                            <span id="btn-text">Save Dish</span>
                            <span id="btn-spinner" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                        <a href="{{ route('dishes.index') }}"
                            style="display:block; text-align:center; margin-top:10px; color:#666;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Create dish via AJAX
            $('#dish-form').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('dishes.store') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#submit-btn').prop('disabled', true);
                        $('#btn-text').hide();
                        $('#btn-spinner').show();
                    },
                    success: function(res) {
                        showAlert(res.message || 'Dish created successfully!', 'success');

                        // Clear form
                        $('#dish-form')[0].reset();

                        // Redirect to dishes index after 1.5 seconds
                        setTimeout(function() {
                            window.location.href = '{{ route('dishes.index') }}';
                        }, 1500);
                    },
                    error: function(xhr) {
                        let message = 'An error occurred. Please try again.';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;
                                message = Object.values(errors).flat().join('\n');
                            }
                        }

                        showAlert(message, 'danger');
                    },
                    complete: function() {
                        $('#submit-btn').prop('disabled', false);
                        $('#btn-text').show();
                        $('#btn-spinner').hide();
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

            $('#alertContainer').html(alertHtml);

            // Auto-dismiss non-error alerts after 5 seconds
            if (type !== 'danger') {
                setTimeout(function() {
                    $('#alertContainer').html('');
                }, 5000);
            }
        };
    </script>
@endsection
