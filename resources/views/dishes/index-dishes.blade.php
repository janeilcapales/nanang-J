@extends('layouts.app')

@section('page-title', 'Manage Menu')

@section('content')

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <h2>Menu Management</h2>
            <p>Update, add, or remove dishes from your menu</p>
        </div>
        <a href="{{ route('dishes.create') }}" class="btn-warning">
            <i class="fa-solid fa-plus"></i> Add New Dish
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Dish Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dishes-tbody">
                <tr>
                    <td colspan="5" style="text-align:center;padding:30px;">
                        <i class="fa-solid fa-spinner fa-spin"></i> Loading dishes...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            loadDishes();
        });

        function loadDishes() {
            $.ajax({
                url: '{{ route('dishes.index') }}',
                method: 'GET',
                beforeSend: function() {
                    $('#dishes-tbody').html(
                        '<tr><td colspan="5" style="text-align:center;padding:30px;"><i class="fa-solid fa-spinner fa-spin"></i> Loading dishes...</td></tr>'
                        );
                },
                success: function(res) {
                    let rows = '';

                    if (res.dishes && res.dishes.length > 0) {
                        res.dishes.forEach(function(dish, index) {
                            let imageHtml = '';
                            if (dish.image) {
                                imageHtml =
                                    `<img src="/storage/${dish.image}" style="width:55px;height:55px;object-fit:cover;border-radius:10px;">`;
                            } else {
                                imageHtml =
                                    `<div style="width:55px;height:55px;background:#f5ede0;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;">🍽️</div>`;
                            }

                            rows += `
                        <tr id="dish-row-${dish.id}">
                            <td style="color:#aaa;font-size:13px;">${dish.id}</td>
                            <td>${imageHtml}</td>
                            <td style="font-weight:600;">${dish.name}</td>
                            <td style="color:#F97316; font-weight:700;">₱${parseFloat(dish.price).toFixed(2)}</td>
                            <td>
                                <div style="display:flex;gap:8px;">
                                    <a href="{{ route('dishes.edit', '') }}/${dish.id}" class="btn-sm-edit">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                    <button onclick="deleteDish(${dish.id})" class="btn-danger" style="border:none; cursor:pointer;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`;
                        });
                    } else {
                        rows =
                            '<tr><td colspan="5" style="text-align:center;padding:50px;color:#aaa;">No dishes found.</td></tr>';
                    }

                    $('#dishes-tbody').html(rows);
                },
                error: function() {
                    $('#dishes-tbody').html(
                        '<tr><td colspan="5" style="text-align:center;padding:50px;color:#aaa;">Failed to load dishes.</td></tr>'
                        );
                }
            });
        }

        function deleteDish(id) {
            if (!confirm('Delete this dish?')) {
                return;
            }

            $.ajax({
                url: '{{ route('dishes.destroy', '') }}/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $(`#dish-row-${id}`).css('opacity', '0.5').css('pointer-events', 'none');
                },
                success: function(res) {
                    showAlert(res.message || 'Dish deleted successfully!', 'success');
                    $(`#dish-row-${id}`).fadeOut(300, function() {
                        $(this).remove();
                        // Reload if table is empty
                        if ($('#dishes-tbody tr').length === 0) {
                            loadDishes();
                        }
                    });
                },
                error: function(xhr) {
                    let message = 'Failed to delete dish.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showAlert(message, 'danger');
                    $(`#dish-row-${id}`).css('opacity', '1').css('pointer-events', 'auto');
                }
            });
        }

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
