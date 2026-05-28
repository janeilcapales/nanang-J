@extends('layouts.app')

@section('page-title', 'Manage Dishes')

@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <h2>Manage Dishes</h2>
        <p>Add, edit, or remove dishes from your menu</p>
    </div>
    <button type="button" class="btn-warning" id="addDishBtn">
        <i class="fa-solid fa-plus"></i> Add New Dish
    </button>
</div>

<!-- Dish Cards Grid -->
@if(isset($dishes) && $dishes->count())
<div class="row g-4">
    @foreach($dishes as $dish)
    <div class="col-md-3 col-sm-6">
        <div class="dish-card">
            @if($dish->image)
                <img src="{{ asset('storage/'.$dish->image) }}"
                     alt="{{ $dish->name }}"
                     class="dish-card-img">
            @else
                <div class="dish-card-img">🍽️</div>
            @endif

            <div class="dish-card-body">
                <div class="dish-card-name">{{ $dish->name }}</div>
                <div class="dish-card-price">₱{{ number_format($dish->price, 2) }}</div>

                <div style="display:flex;gap:8px;margin-top:14px;">
                    <button type="button" class="btn-sm-edit editDishBtn" style="flex:1;justify-content:center;" data-dish-id="{{ $dish->id }}">
                        <i class="fa-solid fa-pen"></i> Edit
                    </button>
                    <button type="button" class="btn-danger deleteDishBtn" style="flex:1;" data-dish-id="{{ $dish->id }}" data-dish-name="{{ $dish->name }}">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div style="text-align:center;padding:80px 0;color:#aaa;">
    <div style="font-size:56px;margin-bottom:16px;">🍽️</div>
    <div style="font-family:'Playfair Display',serif;font-size:22px;color:#555;margin-bottom:8px;">No dishes yet</div>
    <p style="font-size:14px;">Start by adding your first dish to the menu.</p>
    <a href="#" class="btn-warning" style="margin-top:20px;display:inline-flex;" id="addDishBtnEmpty">
        <i class="fa-solid fa-plus"></i> Add First Dish
    </a>
</div>
@endif

@endsection

<script>
$(document).ready(function() {
    // Add/Edit Dish Modal
    const openDishModal = (dishId = null) => {
        const url = dishId ? `/dishes/${dishId}/edit` : '{{ route("dishes.create") }}';
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                // Create modal if not exists
                let modal = $('#dishModal');
                if (modal.length === 0) {
                    modal = $('<div id="dishModal" class="modal fade" tabindex="-1"></div>');
                    $('body').append(modal);
                }
                
                modal.html(data);
                const bootstrapModal = new bootstrap.Modal(modal[0]);
                bootstrapModal.show();
                
                // Reload dishes after successful form submission
                modal.on('hidden.bs.modal', function() {
                    loadDishes();
                });
            },
            error: function(xhr) {
                alert('Error loading form. Please try again.');
                console.error(xhr);
            }
        });
    };

    // Load Dishes List
    const loadDishes = () => {
        $.ajax({
            url: '{{ route("dishes.index") }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const dishesContainer = $('.row.g-4');
                
                if (response.dishes.length === 0) {
                    dishesContainer.closest('.row, div').html(`
                        <div style="text-align:center;padding:80px 0;color:#aaa;width:100%;">
                            <div style="font-size:56px;margin-bottom:16px;">🍽️</div>
                            <div style="font-family:'Playfair Display',serif;font-size:22px;color:#555;margin-bottom:8px;">No dishes yet</div>
                            <p style="font-size:14px;">Start by adding your first dish to the menu.</p>
                            <button type="button" class="btn-warning" style="margin-top:20px;display:inline-flex;" id="addDishBtnEmpty">
                                <i class="fa-solid fa-plus"></i> Add First Dish
                            </button>
                        </div>
                    `);
                } else {
                    let html = '';
                    response.dishes.forEach(dish => {
                        html += `
                            <div class="col-md-3 col-sm-6">
                                <div class="dish-card">
                                    ${dish.image ? 
                                        `<img src="/storage/${dish.image}" alt="${dish.name}" class="dish-card-img">` :
                                        `<div class="dish-card-img">🍽️</div>`
                                    }
                                    <div class="dish-card-body">
                                        <div class="dish-card-name">${dish.name}</div>
                                        <div class="dish-card-price">₱${parseFloat(dish.price).toFixed(2)}</div>
                                        <div style="display:flex;gap:8px;margin-top:14px;">
                                            <button type="button" class="btn-sm-edit editDishBtn" style="flex:1;justify-content:center;" data-dish-id="${dish.id}">
                                                <i class="fa-solid fa-pen"></i> Edit
                                            </button>
                                            <button type="button" class="btn-danger deleteDishBtn" style="flex:1;" data-dish-id="${dish.id}" data-dish-name="${dish.name}">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    dishesContainer.html(html);
                    attachDishEventHandlers();
                }
            },
            error: function(xhr) {
                console.error('Error loading dishes:', xhr);
            }
        });
    };

    // Attach Event Handlers
    const attachDishEventHandlers = () => {
        // Edit button
        $(document).off('click', '.editDishBtn').on('click', '.editDishBtn', function(e) {
            e.preventDefault();
            const dishId = $(this).data('dish-id');
            openDishModal(dishId);
        });

        // Delete button
        $(document).off('click', '.deleteDishBtn').on('click', '.deleteDishBtn', function(e) {
            e.preventDefault();
            const dishId = $(this).data('dish-id');
            const dishName = $(this).data('dish-name');
            
            if (confirm(`Remove "${dishName}" from the menu?`)) {
                $.ajax({
                    url: `/dishes/${dishId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message || 'Dish deleted successfully!');
                        loadDishes();
                    },
                    error: function(xhr) {
                        alert('Error deleting dish. Please try again.');
                        console.error(xhr);
                    }
                });
            }
        });
    };

    // Add New Dish Button
    $(document).on('click', '#addDishBtn, #addDishBtnEmpty', function(e) {
        e.preventDefault();
        openDishModal();
    });

    // Initial setup
    attachDishEventHandlers();
});
</script>
