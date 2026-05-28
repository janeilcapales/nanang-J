@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h2>Edit Dish</h2>
        <p>Update details for {{ $dish->name }}</p>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-card">
                <form id="edit-dish-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="dish-id" value="{{ $dish->id }}">

                    <div class="form-group">
                        <label class="form-label">Dish Name</label>
                        <input type="text" id="edit-dish-name" name="name" class="form-control"
                            value="{{ $dish->name }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price (₱)</label>
                        <input type="number" id="edit-dish-price" step="0.01" name="price" class="form-control"
                            value="{{ $dish->price }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Current Image</label>
                        @if ($dish->image)
                            <img id="current-image" src="{{ asset('storage/' . $dish->image) }}"
                                style="width:100px; display:block; margin-bottom:10px; border-radius:8px;">
                        @else
                            <p style="color:gray; font-size:13px;">No image uploaded</p>
                        @endif
                        <input type="file" id="edit-dish-image" name="image" class="form-control">
                        <small style="color:gray;">Leave blank if you don't want to change the image.</small>
                    </div>

                    <div style="margin-top: 20px;">
                        <button type="submit" id="submit-btn" class="btn-warning" style="width: 100%;">
                            <span id="btn-text">Update Dish</span>
                            <span id="btn-spinner" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                        <a href="{{ route('dishes.index') }}"
                            style="display:block; text-align:center; margin-top:10px; color:#666;">Back to Menu</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Update dish via AJAX
            $('#edit-dish-form').on('submit', function(e) {
                e.preventDefault();

                let dishId = $('#dish-id').val();
                let formData = new FormData(this);
                formData.append('_method', 'PUT'); // Laravel method spoofing

                $.ajax({
                    url: '{{ route('dishes.update', '') }}/' + dishId,
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
                        showAlert(res.message || 'Dish updated successfully!', 'success');

                        // Update image preview if new image was uploaded
                        if (res.data && res.data.image) {
                            $('#current-image').attr('src', '/storage/' + res.data.image)
                        .show();
                        }

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
