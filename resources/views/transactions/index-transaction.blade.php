@extends('layouts.app')

@section('content')
<div class="page-header">
    <h2>{{ Auth::user()->role === 'admin' ? 'Payment Records' : 'Make a Payment' }}</h2>
</div>

@if(Auth::user()->role === 'customer')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="form-card">
                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Select Dish</label>
                        <select name="dish_id" class="form-control" required>
                            @foreach($dishes as $dish)
                                <option value="{{ $dish->id }}">{{ $dish->name }} - ₱{{ $dish->price }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Amount Paid</label>
                        <input type="number" name="amount_paid" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%">Confirm Payment</button>
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
        <tbody>
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
@endsection