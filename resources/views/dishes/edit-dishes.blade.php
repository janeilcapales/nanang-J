@extends('layouts.app')

@section('content')
<div class="page-header">
    <h2>Edit Dish</h2>
    <p>Update details for {{ $dish->name }}</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-card">
            <form action="{{ route('dishes.update', $dish->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Dish Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $dish->name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ $dish->price }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Current Image</label>
                    @if($dish->image)
                        <img src="{{ asset('storage/'.$dish->image) }}" style="width:100px; display:block; margin-bottom:10px; border-radius:8px;">
                    @endif
                    <input type="file" name="image" class="form-control">
                    <small style="color:gray;">Leave blank if you don't want to change the image.</small>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-warning" style="width: 100%;">Update Dish</button>
                    <a href="{{ route('dishes.index') }}" style="display:block; text-align:center; margin-top:10px; color:#666;">Back to Menu</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection