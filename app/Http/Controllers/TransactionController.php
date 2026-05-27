function loadTransactions() {
    $.ajax({
        url: '/transactions',
        method: 'GET',
        success: function (res) {
            let dishes = res.data.dishes;
            let transactions = res.data.transactions;
 
            // Populate dishes dropdown
            let options = '<option value="">Select dish</option>';
            dishes.forEach(function (dish) {
                options += `<option value="${dish.id}" data-price="${dish.price}">${dish.name} — ₱${parseFloat(dish.price).toFixed(2)}</option>`;
            });
            $('#transaction-dish-id').html(options);
 
            // Populate transactions table
            let rows = '';
            transactions.forEach(function (t) {
                rows += `
                    <tr>
                        <td>${t.dish ? t.dish.name : ''}</td>
                        <td>₱${parseFloat(t.amount_paid).toFixed(2)}</td>
                        <td>₱${parseFloat(t.change).toFixed(2)}</td>
                        <td>${t.user ? t.user.name : ''}</td>
                    </tr>`;
            });
            $('#transactions-tbody').html(rows);
        }
    });
}
 
// Live change calculator
$(document).on('change', '#transaction-dish-id, #transaction-amount-paid', function () {
    let price      = parseFloat($('#transaction-dish-id option:selected').data('price')) || 0;
    let amountPaid = parseFloat($('#transaction-amount-paid').val()) || 0;
    let change     = amountPaid - price;
    $('#change-display').text('Change: ₱' + (change >= 0 ? change.toFixed(2) : '0.00'));
});
 
// Create transaction
$('#transaction-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: '/transactions',
        method: 'POST',
        data: {
            dish_id:     $('#transaction-dish-id').val(),
            amount_paid: $('#transaction-amount-paid').val(),
        },
        success: function (res) {
            showAlert(res.message + ' Change: ₱' + parseFloat(res.data.change).toFixed(2), 'success');
            loadTransactions();
            $('#transaction-form')[0].reset();
            $('#change-display').text('');
        },
        error: function (xhr) {
            showValidationErrors(xhr.responseJSON.errors);
        }
    });
});