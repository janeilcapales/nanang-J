@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
    <h2>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->name }}! 👋</h2>
    <p>Here's what's happening at your restaurant today.</p>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fa-solid fa-bowl-food"></i></div>
            <div class="stat-number">{{ $dishesCount }}</div>
            <div class="stat-label">Total Dishes on Menu</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon gold"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="stat-number">{{ $reservationsCount }}</div>
            <div class="stat-label">Active Reservations</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-receipt"></i></div>
            <div class="stat-number">{{ $transactionsCount }}</div>
            <div class="stat-label">Total Transactions</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="page-header">
    <h2 style="font-size:20px;">Quick Actions</h2>
</div>

<div class="row g-3">
    @if(Auth::user()->role === 'admin')
    <div class="col-md-3">
        <a href="{{ route('dishes.index') }}" style="text-decoration:none;">
            <div class="stat-card text-center" style="cursor:pointer;">
                <div style="font-size:28px;margin-bottom:10px;">🍜</div>
                <div style="font-size:14px;font-weight:500;color:#1a1008;">Manage Dishes</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('reservations.index') }}" style="text-decoration:none;">
            <div class="stat-card text-center" style="cursor:pointer;">
                <div style="font-size:28px;margin-bottom:10px;">📅</div>
                <div style="font-size:14px;font-weight:500;color:#1a1008;">View Reservations</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('transactions.index') }}" style="text-decoration:none;">
            <div class="stat-card text-center" style="cursor:pointer;">
                <div style="font-size:28px;margin-bottom:10px;">💳</div>
                <div style="font-size:14px;font-weight:500;color:#1a1008;">Transactions</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('reports.index') }}" style="text-decoration:none;">
            <div class="stat-card text-center" style="cursor:pointer;">
                <div style="font-size:28px;margin-bottom:10px;">📊</div>
                <div style="font-size:14px;font-weight:500;color:#1a1008;">Reports</div>
            </div>
        </a>
    </div>
    @else
    <div class="col-md-4">
        <a href="{{ route('reservations.create') }}" style="text-decoration:none;">
            <div class="stat-card text-center" style="cursor:pointer;">
                <div style="font-size:28px;margin-bottom:10px;">➕</div>
                <div style="font-size:14px;font-weight:500;color:#1a1008;">Book a Reservation</div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('reservations.index') }}" style="text-decoration:none;">
            <div class="stat-card text-center" style="cursor:pointer;">
                <div style="font-size:28px;margin-bottom:10px;">📅</div>
                <div style="font-size:14px;font-weight:500;color:#1a1008;">My Reservations</div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('transactions.index') }}" style="text-decoration:none;">
            <div class="stat-card text-center" style="cursor:pointer;">
                <div style="font-size:28px;margin-bottom:10px;">💳</div>
                <div style="font-size:14px;font-weight:500;color:#1a1008;">My Transactions</div>
            </div>
        </a>
    </div>
    @endif
</div>

@endsection