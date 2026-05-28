@extends('layouts.app')

@section('page-title', 'Edit Reservation')

@section('content')

<!-- Alert Container -->
<div id="alertContainer"></div>

<div class="page-header">
    <h2>Edit Reservation</h2>
    <p>Update the reservation details below</p>
</div>

<div class="row g-4">
    <!-- Form -->
    <div class="col-lg-5">
        <div class="form-card">
            <form id="editReservationForm">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Table Number *</label>
                    <input type="text" name="table_number" id="tableNumber" class="form-control"
                           value="{{ $reservation->table_number }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Reservation Date & Time *</label>
                    <input type="datetime-local" name="reservation_time" id="reservationTime" class="form-control"
                           value="{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Select Dish *</label>
                    <input type="hidden" name="dish_id" id="dishSelect" value="{{ $reservation->dish_id }}">
                </div>

                <!-- Current dish preview -->
                @if($reservation->dish)
                <div id="selectedDishPreview" style="background:#FFF7ED;border:1px solid #FED7AA;border-radius:12px;padding:14px;margin-bottom:20px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        @if($reservation->dish->image)
                            <img id="selectedDishImg" src="{{ asset('storage/'.$reservation->dish->image) }}"
                                 style="width:52px;height:52px;object-fit:cover;border-radius:9px;">
                        @else
                            <img id="selectedDishImg" src="" style="display:none;">
                        @endif
                        <div>
                            <div id="selectedDishName" style="font-weight:600;color:#1a1008;font-size:15px;">{{ $reservation->dish->name }}</div>
                            <div id="selectedDishPrice" style="color:#F97316;font-size:14px;font-weight:500;">₱{{ number_format($reservation->dish->price, 2) }}</div>
                        </div>
                    </div>
                </div>
                @else
                <div id="selectedDishPreview" style="display:none;background:#FFF7ED;border:1px solid #FED7AA;border-radius:12px;padding:14px;margin-bottom:20px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <img id="selectedDishImg" src="" style="width:52px;height:52px;object-fit:cover;border-radius:9px;">
                        <div>
                            <div id="selectedDishName" style="font-weight:600;color:#1a1008;font-size:15px;"></div>
                            <div id="selectedDishPrice" style="color:#F97316;font-size:14px;font-weight:500;"></div>
                        </div>
                    </div>
                </div>
                @endif

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn-primary" id="submitBtn" style="flex:1;">
                        <span id="btnText"><i class="fa-solid fa-check"></i> Update Reservation</span>
                        <span id="btnSpinner" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i> Updating...</span>
                    </button>
                    <button type="button" id="cancelBtn" style="padding:11px 20px;border:1.5px solid #e0d5c8;border-radius:9px;font-size:14px;color:#666;text-decoration:none;display:inline-flex;align-items:center;background:none;cursor:pointer;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dish Cards -->
    <div class="col-lg-7">
        <div style="margin-bottom:16px;">
            <div style="font-family:'Playfair Display',serif;font-size:18px;color:#1a1008;font-weight:600;">Choose a Different Dish</div>
            <p style="font-size:13px;color:#888;margin-top:4px;">Click a dish card to change your selection</p>
        </div>
        <div class="row g-3">
           @foreach($dishes as $dish)
<div class="col-md-4 col-6">
    <div class="dish-card dish-selectable {{ $reservation->dish_id == $dish->id ? 'selected' : '' }}"
         data-id="{{ $dish->id }}"
         data-name="{{ $dish->name }}"
         data-price="{{ $dish->price }}"
         data-image="{{ $dish->image ? asset('storage/'.$dish->image) : '' }}"
         style="cursor:pointer;">

        @if($dish->image)
            <img src="{{ asset('storage/'.$dish->image) }}" alt="{{ $dish->name }}" class="dish-card-img">
        @else
            <div class="dish-card-img" style="font-size:36px;">🍽️</div>
        @endif

        <div class="dish-card-body">
            <div class="dish-card-name">{{ $dish->name }}</div>
            <div class="dish-card-price">₱{{ number_format($dish->price, 2) }}</div>
        </div>
    </div>
</div>
@endforeach
        </div>
    </div>
</div>

<style>
    .dish-selectable.selected {
        outline: 2.5px solid #F97316;
        box-shadow: 0 0 0 4px rgba(249,115,22,0.15);
    }
</style>

<script>
$(document).ready(function() {
    const reservationId = @json($reservation->id);
    const reservationIndexUrl = '{{ route("reservations.index") }}';

    $(document).on('click', '.dish-selectable', function() {
        const $this = $(this);
        const id = $this.data('id');
        const name = $this.data('name');
        const price = parseFloat($this.data('price')) || 0;
        const image = $this.data('image');

        $('#dishSelect').val(id);
        $('#selectedDishName').text(name);
        $('#selectedDishPrice').text('₱' + price.toFixed(2));

        const imgEl = $('#selectedDishImg');
        if (image) {
            imgEl.attr('src', image).show();
        } else {
            imgEl.hide();
        }

        $('#selectedDishPreview').show();
        $('.dish-selectable').removeClass('selected');
        $this.addClass('selected');
    });

    $('#editReservationForm').on('submit', function(e) {
        e.preventDefault();

        if (!$('#tableNumber').val() || !$('#reservationTime').val() || !$('#dishSelect').val()) {
            showAlert('Please fill in all required fields', 'warning');
            return;
        }

        const formData = {
            table_number: $('#tableNumber').val(),
            reservation_time: $('#reservationTime').val(),
            dish_id: $('#dishSelect').val(),
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            url: `/reservations/${reservationId}`,
            type: 'PUT',
            data: formData,
            beforeSend: function() {
                $('#submitBtn').prop('disabled', true);
                $('#btnText').hide();
                $('#btnSpinner').show();
            },
            success: function(response) {
                showAlert(response.message || 'Reservation updated successfully!', 'success');

                setTimeout(function() {
                    window.location.href = reservationIndexUrl;
                }, 1500);
            },
            error: function(xhr) {
                let message = 'An error occurred. Please try again.';

                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                showAlert(message, 'danger');
                console.error('Update Error:', xhr);
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false);
                $('#btnText').show();
                $('#btnSpinner').hide();
            }
        });
    });

    $('#cancelBtn').on('click', function(e) {
        e.preventDefault();
        window.location.href = reservationIndexUrl;
    });

    function showAlert(message, type = 'info') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        $('#alertContainer').html(alertHtml);

        if (type !== 'danger') {
            setTimeout(function() {
                $('#alertContainer').empty();
            }, 5000);
        }
    }
});
</script>
@endsection
