@extends('layouts.app')

@section('page-title', 'Edit Reservation')

@section('content')

<div class="page-header">
    <h2>Edit Reservation</h2>
    <p>Update the reservation details below</p>
</div>

<div class="row g-4">
    <!-- Form -->
    <div class="col-lg-5">
        <div class="form-card">
            <form method="POST" action="{{ route('reservations.update', $reservation->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Table Number *</label>
                    <input type="text" name="table_number" class="form-control"
                           value="{{ $reservation->table_number }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Reservation Date & Time *</label>
                    <input type="datetime-local" name="reservation_time" class="form-control"
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
                    <button type="submit" class="btn-primary" style="flex:1;">
                        <i class="fa-solid fa-check"></i> Update Reservation
                    </button>
                    <a href="{{ route('reservations.index') }}" style="padding:11px 20px;border:1.5px solid #e0d5c8;border-radius:9px;font-size:14px;color:#666;text-decoration:none;display:inline-flex;align-items:center;">
                        Cancel
                    </a>
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
         onclick="selectDish(this)"
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
@endsection
<script>
function selectDish(el) {
    const id    = el.dataset.id;
    const name  = el.dataset.name;
    const price = el.dataset.price;
    const image = el.dataset.image;

    document.getElementById('dishSelect').value = id;
    document.getElementById('selectedDishName').textContent = name;
    document.getElementById('selectedDishPrice').textContent = '₱' + parseFloat(price).toFixed(2);

    const imgEl = document.getElementById('selectedDishImg');
    if (image) {
        imgEl.src = image;
        imgEl.style.display = 'block';
    } else {
        imgEl.style.display = 'none';
    }
    document.getElementById('selectedDishPreview').style.display = 'block';

    document.querySelectorAll('.dish-selectable').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
}
</script>
