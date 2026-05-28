@extends('layouts.app')

@section('content')
<div class="page-header">
    <h2>{{ Auth::user()->role === 'admin' ? 'Payment Records' : 'Make a Payment' }}</h2>
</div>

<!-- Success/Error Alert -->
<div id="alertContainer"></div>

@if(Auth::user()->role === 'customer')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="form-card">
                <form id="paymentForm">
                    @csrf
                    <div class="form-group">
                        <label>Select Dish</label>
                        <select name="dish_id" id="dishSelect" class="form-control" required>
                            <option value="">-- Select a dish --</option>
                            @foreach($dishes as $dish)
                                <option value="{{ $dish->id }}" data-price="{{ $dish->price }}">{{ $dish->name }} - ₱{{ $dish->price }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Amount Paid</label>
                        <input type="number" id="amountPaid" name="amount_paid" class="form-control" required min="0" step="0.01">
                        <small id="priceHelper" style="color:#999;"></small>
                    </div>
                    <button type="submit" class="btn-primary" id="submitBtn" style="width:100%">
                        <span id="btnText">Confirm Payment</span>
                        <span id="btnSpinner" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif

<div style="margin-top:30px;">
    <h3>{{ Auth::user()->role === 'admin' ? 'All Transactions' : 'My Payment History' }}</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                @if(Auth::user()->role === 'admin') <th>Customer</th> @endif
                <th>Dish</th>
                <th>Amount Paid</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="transactionsBody">
            @foreach($transactions as $t)
            <tr>
                <td>{{ $t->id }}</td>
                @if(Auth::user()->role === 'admin') <td>{{ $t->user->name }}</td> @endif
                <td>{{ $t->dish->name }}</td>
                <td>₱{{ number_format($t->amount_paid, 2) }}</td>
                <td><span style="color: green;">Paid</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    // Show price helper when dish is selected
    $('#dishSelect').on('change', function() {
        const price = $(this).find('option:selected').data('price');
        if (price) {
            $('#priceHelper').text(`Suggested amount: ₱${parseFloat(price).toFixed(2)}`);
        } else {
            $('#priceHelper').text('');
        }
    });

    // Handle Payment Form Submission
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            dish_id: $('#dishSelect').val(),
            amount_paid: $('#amountPaid').val(),
            _token: $('input[name="_token"]').val()
        };

        // Validate form
        if (!formData.dish_id || !formData.amount_paid) {
            showAlert('Please fill in all fields', 'warning');
            return;
        }

        if (parseFloat(formData.amount_paid) <= 0) {
            showAlert('Amount paid must be greater than 0', 'warning');
            return;
        }

        // Submit via AJAX
        $.ajax({
            url: '{{ route("transactions.store") }}',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#submitBtn').prop('disabled', true);
                $('#btnText').hide();
                $('#btnSpinner').show();
            },
            success: function(response) {
                showAlert(response.message || 'Payment successful!', 'success');
                
                // Reset form
                $('#paymentForm')[0].reset();
                $('#priceHelper').text('');
                
                // Refresh transactions table
                loadTransactions();
                
                // Clear amount after 2 seconds
                setTimeout(function() {
                    $('#amountPaid').val('');
                }, 2000);
            },
            error: function(xhr) {
                let message = 'An error occurred. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('\n');
                }
                
                showAlert(message, 'danger');
                console.error('Payment Error:', xhr);
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false);
                $('#btnText').show();
                $('#btnSpinner').hide();
            }
        });
    });

    // Load Transactions Table
    const loadTransactions = () => {
        $.ajax({
            url: '{{ route("transactions.index") }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let html = '';
                
                if (response.transactions && response.transactions.length > 0) {
                    response.transactions.forEach(transaction => {
                        html += `
                            <tr>
                                <td>${transaction.id}</td>
                                @if(Auth::user()->role === 'admin')
                                    <td>${transaction.user.name}</td>
                                @endif
                                <td>${transaction.dish.name}</td>
                                <td>₱${parseFloat(transaction.amount_paid).toFixed(2)}</td>
                                <td><span style="color: green;">Paid</span></td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="5" style="text-align:center;padding:20px;">No transactions found</td></tr>';
                }
                
                $('#transactionsBody').html(html);
            },
            error: function(xhr) {
                console.error('Error loading transactions:', xhr);
                showAlert('Error loading transactions', 'danger');
            }
        });
    };

    // Show Alert Messages
    const showAlert = (message, type = 'info') => {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('#alertContainer').html(alertHtml);
        
        // Auto-dismiss after 5 seconds (except for errors)
        if (type !== 'danger') {
            setTimeout(function() {
                $('#alertContainer').html('');
            }, 5000);
        }
    };
});
</script>
@endsection
