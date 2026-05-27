function loadReservations() {
    $.ajax({
        url: '/reservations',
        method: 'GET',
        success: function (res) {
            let rows = '';
            res.data.forEach(function (r) {
                rows += `
                    <tr id="reservation-row-${r.id}">
                        <td>${r.table_number}</td>
                        <td>${r.reservation_time}</td>
                        <td>${r.dish ? r.dish.name : ''}</td>
                        <td>${r.user ? r.user.name : ''}</td>
                        <td>
                            <button onclick="editReservation(${r.id})">Edit</button>
                            <button onclick="deleteReservation(${r.id})">Delete</button>
                        </td>
                    </tr>`;
            });
            $('#reservations-tbody').html(rows);
        }
    });
}
 
// Load dishes into create form
function loadReservationForm() {
    $.ajax({
        url: '/reservations/create',
        method: 'GET',
        success: function (res) {
            let options = '<option value="">Select dish</option>';
            res.data.forEach(function (dish) {
                options += `<option value="${dish.id}">${dish.name}</option>`;
            });
            $('#reservation-dish-id').html(options);
        }
    });
}
 
// Create reservation
$('#reservation-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: '/reservations',
        method: 'POST',
        data: {
            table_number:     $('#reservation-table').val(),
            reservation_time: $('#reservation-time').val(),
            dish_id:          $('#reservation-dish-id').val(),
        },
        success: function (res) {
            loadReservations();
            $('#reservation-form')[0].reset();
            showAlert(res.message, 'success');
        },
        error: function (xhr) {
            showValidationErrors(xhr.responseJSON.errors);
        }
    });
});
 
// Load reservation into edit form
function editReservation(id) {
    $.ajax({
        url: '/reservations/' + id + '/edit',
        method: 'GET',
        success: function (res) {
            let r      = res.data.reservation;
            let dishes = res.data.dishes;
 
            $('#edit-reservation-id').val(r.id);
            $('#edit-reservation-table').val(r.table_number);
            $('#edit-reservation-time').val(r.reservation_time);
 
            let options = '';
            dishes.forEach(function (dish) {
                options += `<option value="${dish.id}" ${dish.id == r.dish_id ? 'selected' : ''}>${dish.name}</option>`;
            });
            $('#edit-reservation-dish-id').html(options);
            $('#edit-reservation-modal').show();
        },
        error: function (xhr) {
            if (xhr.status === 403) showAlert('Not authorized.', 'error');
        }
    });
}
 
// Update reservation
$('#reservation-edit-form').on('submit', function (e) {
    e.preventDefault();
    let id = $('#edit-reservation-id').val();
    $.ajax({
        url: '/reservations/' + id,
        method: 'PUT',
        data: {
            table_number:     $('#edit-reservation-table').val(),
            reservation_time: $('#edit-reservation-time').val(),
            dish_id:          $('#edit-reservation-dish-id').val(),
        },
        success: function (res) {
            loadReservations();
            $('#edit-reservation-modal').hide();
            showAlert(res.message, 'success');
        },
        error: function (xhr) {
            showValidationErrors(xhr.responseJSON.errors);
        }
    });
});
 
// Delete reservation
function deleteReservation(id) {
    if (!confirm('Delete this reservation?')) return;
    $.ajax({
        url: '/reservations/' + id,
        method: 'DELETE',
        success: function (res) {
            $('#reservation-row-' + id).fadeOut(300, function () { $(this).remove(); });
            showAlert(res.message, 'success');
        },
        error: function (xhr) {
            if (xhr.status === 403) showAlert('Not authorized.', 'error');
        }
    });
}