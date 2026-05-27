function loadDishes() {
    $.ajax({
        url: '/dishes',
        method: 'GET',
        success: function (res) {
            let rows = '';
            res.data.forEach(function (dish) {
                rows += `
                    <tr id="dish-row-${dish.id}">
                        <td>${dish.name}</td>
                        <td>₱${parseFloat(dish.price).toFixed(2)}</td>
                        <td>
                            <img src="/storage/${dish.image}" width="60" onerror="this.style.display='none'">
                        </td>
                        <td>
                            <button onclick="editDish(${dish.id})">Edit</button>
                            <button onclick="deleteDish(${dish.id})">Delete</button>
                        </td>
                    </tr>`;
            });
            $('#dishes-tbody').html(rows);
        }
    });
}
 
// Create dish (supports image upload)
$('#dish-form').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData(this);
 
    $.ajax({
        url: '/dishes',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            loadDishes();
            $('#dish-form')[0].reset();
            showAlert(res.message, 'success');
        },
        error: function (xhr) {
            showValidationErrors(xhr.responseJSON.errors);
        }
    });
});
 
// Load dish data into edit form
function editDish(id) {
    $.ajax({
        url: '/dishes/' + id + '/edit',
        method: 'GET',
        success: function (res) {
            let dish = res.data;
            $('#edit-dish-id').val(dish.id);
            $('#edit-dish-name').val(dish.name);
            $('#edit-dish-price').val(dish.price);
            $('#edit-dish-modal').show(); // show your modal/form here
        }
    });
}
 
// Update dish (supports image upload)
$('#dish-edit-form').on('submit', function (e) {
    e.preventDefault();
    let id       = $('#edit-dish-id').val();
    let formData = new FormData(this);
    formData.append('_method', 'PUT'); // Laravel method spoofing
 
    $.ajax({
        url: '/dishes/' + id,
        method: 'POST', // must be POST when sending files with _method spoofing
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            loadDishes();
            $('#edit-dish-modal').hide();
            showAlert(res.message, 'success');
        },
        error: function (xhr) {
            showValidationErrors(xhr.responseJSON.errors);
        }
    });
});
 
// Delete dish
function deleteDish(id) {
    if (!confirm('Delete this dish?')) return;
    $.ajax({
        url: '/dishes/' + id,
        method: 'DELETE',
        success: function (res) {
            $('#dish-row-' + id).fadeOut(300, function () { $(this).remove(); });
            showAlert(res.message, 'success');
        }
    });
}