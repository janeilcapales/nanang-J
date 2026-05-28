@extends('layouts.app')

@section('content')
<!-- Alert Container -->
<div id="alertContainer"></div>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h2>{{ Auth::user()->role === 'admin' ? 'All Reservations' : 'My Reservations' }}</h2>
        <p>{{ Auth::user()->role === 'admin' ? 'Manage and view all customer bookings' : 'View and manage your table bookings' }}</p>
    </div>
    @if(Auth::user()->role === 'customer')
        <button type="button" class="btn-warning" id="bookTableBtn">
            <i class="fa-solid fa-plus"></i> Book a Table
        </button>
    @endif
</div>

<div style="overflow-x:auto;">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                @if(Auth::user()->role === 'admin') <th>Customer</th> @endif
                <th>Table</th>
                <th>Date & Time</th>
                <th>Dish</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="reservationsTableBody">
            @forelse($reservations as $res)
            <tr>
                <td>{{ $res->id }}</td>
                @if(Auth::user()->role === 'admin') <td>{{ $res->user->name }}</td> @endif
                <td><span class="badge">Table {{ $res->table_number }}</span></td>
                <td>{{ \Carbon\Carbon::parse($res->reservation_time)->format('M d, Y - h:i A') }}</td>
                <td>{{ $res->dish->name ?? 'None' }}</td>
                <td>
                    <div style="display:flex; gap:5px;">
                        <button type="button" class="btn-sm-edit editReservationBtn" data-res-id="{{ $res->id }}">Edit</button>
                        <button type="button" class="btn-danger deleteReservationBtn" data-res-id="{{ $res->id }}" data-res-table="{{ $res->table_number }}">Delete</button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;">No reservations found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    // Open Reservation Modal (Create/Edit)
    const openReservationModal = (reservationId = null) => {
        const url = reservationId 
            ? `/reservations/${reservationId}/edit` 
            : '{{ route("reservations.create") }}';
        
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function() {
                let modal = $('#reservationModal');
                if (modal.length === 0) {
                    modal = $('<div id="reservationModal" class="modal fade" tabindex="-1"></div>');
                    $('body').append(modal);
                }
                
                modal.html(`
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${reservationId ? 'Edit Reservation' : 'Book a Table'}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                
                const bootstrapModal = new bootstrap.Modal(modal[0]);
                bootstrapModal.show();
            },
            success: function(data) {
                const modal = $('#reservationModal');
                modal.find('.modal-body').html(data);
                
                // Handle form submission via AJAX
                const form = modal.find('form');
                form.off('submit').on('submit', function(e) {
                    e.preventDefault();
                    submitReservationForm(form, reservationId);
                });
            },
            error: function(xhr) {
                showAlert('Error loading reservation form. Please try again.', 'danger');
                console.error(xhr);
            }
        });
    };

    // Submit Reservation Form
    const submitReservationForm = (form, reservationId) => {
        const formData = form.serialize();
        const url = reservationId 
            ? `/reservations/${reservationId}` 
            : '{{ route("reservations.store") }}';
        const method = reservationId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                showAlert(response.message || 'Reservation saved successfully!', 'success');
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('reservationModal'));
                if (modal) modal.hide();
                
                // Reload reservations
                setTimeout(() => loadReservations(), 500);
            },
            error: function(xhr) {
                let message = 'Error saving reservation. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('<br>');
                }
                
                showAlert(message, 'danger');
                console.error(xhr);
                form.find('button[type="submit"]').prop('disabled', false);
            }
        });
    };

    // Load Reservations Table
    const loadReservations = () => {
        $.ajax({
            url: '{{ route("reservations.index") }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let html = '';
                const userRole = '{{ Auth::user()->role }}';
                
                if (response.reservations && response.reservations.length > 0) {
                    response.reservations.forEach(res => {
                        const reservationDate = new Date(res.reservation_time).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        
                        html += `
                            <tr>
                                <td>${res.id}</td>
                                ${userRole === 'admin' ? `<td>${res.user.name}</td>` : ''}
                                <td><span class="badge">Table ${res.table_number}</span></td>
                                <td>${reservationDate}</td>
                                <td>${res.dish ? res.dish.name : 'None'}</td>
                                <td>
                                    <div style="display:flex; gap:5px;">
                                        <button type="button" class="btn-sm-edit editReservationBtn" data-res-id="${res.id}">Edit</button>
                                        <button type="button" class="btn-danger deleteReservationBtn" data-res-id="${res.id}" data-res-table="${res.table_number}">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    const colSpan = userRole === 'admin' ? 6 : 5;
                    html = `<tr><td colspan="${colSpan}" style="text-align:center;">No reservations found.</td></tr>`;
                }
                
                $('#reservationsTableBody').html(html);
                attachReservationEventHandlers();
            },
            error: function(xhr) {
                console.error('Error loading reservations:', xhr);
                showAlert('Error loading reservations', 'danger');
            }
        });
    };

    // Attach Event Handlers
    const attachReservationEventHandlers = () => {
        // Edit button
        $(document).off('click', '.editReservationBtn').on('click', '.editReservationBtn', function(e) {
            e.preventDefault();
            const resId = $(this).data('res-id');
            openReservationModal(resId);
        });

        // Delete button
        $(document).off('click', '.deleteReservationBtn').on('click', '.deleteReservationBtn', function(e) {
            e.preventDefault();
            const resId = $(this).data('res-id');
            const tableNum = $(this).data('res-table');
            
            if (confirm(`Cancel reservation for Table ${tableNum}?`)) {
                $.ajax({
                    url: `/reservations/${resId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showAlert(response.message || 'Reservation cancelled successfully!', 'success');
                        loadReservations();
                    },
                    error: function(xhr) {
                        showAlert('Error cancelling reservation. Please try again.', 'danger');
                        console.error(xhr);
                    }
                });
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
        
        // Auto-dismiss non-error alerts after 5 seconds
        if (type !== 'danger') {
            setTimeout(() => {
                $('#alertContainer').html('');
            }, 5000);
        }
    };

    // Book Table Button
    $(document).off('click', '#bookTableBtn').on('click', '#bookTableBtn', function(e) {
        e.preventDefault();
        openReservationModal();
    });

    // Initialize event handlers
    attachReservationEventHandlers();
});
</script>
@endsection
