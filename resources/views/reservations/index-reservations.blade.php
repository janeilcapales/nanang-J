@extends('layouts.app')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h2>{{ Auth::user()->role === 'admin' ? 'All Reservations' : 'My Reservations' }}</h2>
        <p>{{ Auth::user()->role === 'admin' ? 'Manage and view all customer bookings' : 'View and manage your table bookings' }}</p>
    </div>
    @if(Auth::user()->role === 'customer')
        <a href="{{ route('reservations.create') }}" class="btn-warning">
            <i class="fa-solid fa-plus"></i> Book a Table
        </a>
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
        <tbody>
            @forelse($reservations as $res)
            <tr>
                <td>{{ $res->id }}</td>
                @if(Auth::user()->role === 'admin') <td>{{ $res->user->name }}</td> @endif
                <td><span class="badge">Table {{ $res->table_number }}</span></td>
                <td>{{ \Carbon\Carbon::parse($res->reservation_time)->format('M d, Y - h:i A') }}</td>
                <td>{{ $res->dish->name ?? 'None' }}</td>
                <td>
                    <div style="display:flex; gap:5px;">
                        <a href="{{ route('reservations.edit', $res->id) }}" class="btn-sm-edit">Edit</a>
                        <form action="{{ route('reservations.destroy', $res->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Cancel this?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;">No reservations found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection