@extends('layouts.app')

@section('content')
<div class="page-header">
    <h2>Add New Dish</h2>
    <p>Create a new item for your restaurant menu</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-card">
            <form action="{{ route('dishes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Dish Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Special Adobo">
                </div>

                <div class="form-group">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00">
                </div>

                <div class="form-group">
                    <label class="form-label">Dish Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-warning" style="width: 100%;">Save Dish</button>
                    <a href="{{ route('dishes.index') }}" style="display:block; text-align:center; margin-top:10px; color:#666;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection