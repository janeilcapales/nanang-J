@extends('layouts.app')

@section('page-title', 'Book a Table')

@section('content')

<div class="page-header">
    <h2>Book a Table</h2>
    <p>Reserve your table and choose your favorite dish</p>
</div>

<div class="row g-4">

    <!-- Reservation Form -->
    <div class="col-lg-5">
        <div class="form-card">

            <form id="reservationForm" method="POST">
                @csrf

                <!-- Table Number -->
                <div class="form-group">
                    <label class="form-label">Table Number *</label>

                    <select name="table_number" class="form-control" required>
                        <option value="">Select Table</option>

                        @for($i = 1; $i <= 20; $i++)
                            <option value="{{ $i }}">
                                Table {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Reservation Time -->
                <div class="form-group">
                    <label class="form-label">Reservation Date & Time *</label>

                    <input
                        type="datetime-local"
                        name="reservation_time"
                        class="form-control"
                        required>
                </div>

                <!-- Hidden Dish -->
                <input type="hidden" name="dish_id" id="dish_id">

                <!-- Selected Dish Preview -->
                <div id="selectedDishPreview"
                     style="display:none;background:#FFF7ED;border:1px solid #FED7AA;border-radius:12px;padding:14px;margin-bottom:20px;">

                    <div style="display:flex;align-items:center;gap:12px;">

                        <img id="selectedDishImg"
                             src=""
                             style="width:52px;height:52px;object-fit:cover;border-radius:9px;display:none;">

                        <div>
                            <div id="selectedDishName"
                                 style="font-weight:600;color:#1a1008;font-size:15px;"></div>

                            <div id="selectedDishPrice"
                                 style="color:#F97316;font-size:14px;font-weight:500;"></div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display:flex;gap:12px;">
                    <button type="submit"
                            class="btn-primary"
                            style="flex:1;">

                        <i class="fa-solid fa-check"></i>
                        Confirm Reservation
                    </button>

                    <a href="{{ route('reservations.index') }}"
                       style="padding:11px 20px;border:1.5px solid #e0d5c8;border-radius:9px;font-size:14px;color:#666;text-decoration:none;display:inline-flex;align-items:center;">

                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Dish Selection -->
    <div class="col-lg-7">

        <div style="margin-bottom:16px;">
            <div style="font-family:'Playfair Display',serif;font-size:18px;color:#1a1008;font-weight:600;">
                Choose Your Dish
            </div>

            <p style="font-size:13px;color:#888;margin-top:4px;">
                Click a dish card to select
            </p>
        </div>

        <div class="row g-3">

            @foreach($dishes as $dish)

            <div class="col-md-4 col-6">

                <div class="dish-card dish-selectable"
                     data-id="{{ $dish->id }}"
                     data-name="{{ $dish->name }}"
                     data-price="{{ $dish->price }}"
                     data-image="{{ $dish->image ? asset('storage/'.$dish->image) : '' }}"
                     onclick="selectDish(this)"
                     style="cursor:pointer;">

                    @if($dish->image)

                        <img src="{{ asset('storage/'.$dish->image) }}"
                             alt="{{ $dish->name }}"
                             class="dish-card-img">

                    @else

                        <div class="dish-card-img"
                             style="display:flex;align-items:center;justify-content:center;background:#eee;font-size:36px;">

                            🍽️
                        </div>

                    @endif

                    <div class="dish-card-body">

                        <div class="dish-card-name">
                            {{ $dish->name }}
                        </div>

                        <div class="dish-card-price">
                            ₱{{ number_format($dish->price, 2) }}
                        </div>

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

function selectDish(el)
{
    const id    = el.dataset.id;
    const name  = el.dataset.name;
    const price = el.dataset.price;
    const image = el.dataset.image;

    document.getElementById('dish_id').value = id;

    document.getElementById('selectedDishName').textContent = name;

    document.getElementById('selectedDishPrice').textContent =
        '₱' + parseFloat(price).toFixed(2);

    const img = document.getElementById('selectedDishImg');

    if(image)
    {
        img.src = image;
        img.style.display = 'block';
    }
    else
    {
        img.style.display = 'none';
    }

    document.getElementById('selectedDishPreview').style.display = 'block';

    document.querySelectorAll('.dish-selectable')
        .forEach(card => card.classList.remove('selected'));

    el.classList.add('selected');
}

</script>

@endsection