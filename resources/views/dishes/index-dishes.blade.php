@extends('layouts.app')

@section('page-title', 'Manage Menu')

@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <h2>Menu Management</h2>
        <p>Update, add, or remove dishes from your menu</p>
    </div>
    <a href="{{ route('dishes.create') }}" class="btn-warning">
        <i class="fa-solid fa-plus"></i> Add New Dish
    </a>
</div> 

<div style="overflow-x:auto;">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Dish Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dishes as $dish)
            <tr>
                <td style="color:#aaa;font-size:13px;">{{ $dish->id }}</td>
                <td>
                    @if($dish->image)
                        <img src="{{ asset('storage/'.$dish->image) }}"
                             style="width:55px;height:55px;object-fit:cover;border-radius:10px;">
                    @else
                        <div style="width:55px;height:55px;background:#f5ede0;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;">🍽️</div>
                    @endif
                </td>
                <td style="font-weight:600;">{{ $dish->name }}</td>
                <td style="color:#F97316; font-weight:700;">₱{{ number_format($dish->price, 2) }}</td>
                <td>
                    <div style="display:flex;gap:8px;">
                        {{-- Dito sa loob ng loop, kilala ni Laravel si $dish --}}
                        <a href="{{ route('dishes.edit', $dish->id) }}" class="btn-sm-edit">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        
                        <form action="{{ route('dishes.destroy', $dish->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" style="border:none; cursor:pointer;" onclick="return confirm('Delete this dish?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:50px;color:#aaa;">No dishes found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection