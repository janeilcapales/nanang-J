@extends('layouts.app')

@section('page-title', 'Manage Dishes')

@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <h2>Manage Dishes</h2>
        <p>Add, edit, or remove dishes from your menu</p>
    </div>
    <a href="{{ route('dishes.create') }}" class="btn-warning">
        <i class="fa-solid fa-plus"></i> Add New Dish
    </a>
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
                    <a href="{{ route('dishes.edit', $dish->id) }}" class="btn-sm-edit" style="flex:1;justify-content:center;">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <form action="{{ route('dishes.destroy', $dish->id) }}" method="POST" style="flex:1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="width:100%;"
                            onclick="return confirm('Remove this dish?')">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
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
    <a href="{{ route('dishes.create') }}" class="btn-warning" style="margin-top:20px;display:inline-flex;">
        <i class="fa-solid fa-plus"></i> Add First Dish
    </a>
</div>
@endif

@endsection