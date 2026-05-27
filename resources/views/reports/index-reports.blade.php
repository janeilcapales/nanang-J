@extends('layouts.app')

@section('page-title', 'Reports')

@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <h2>Reports</h2>
        <p>Transaction history and financial overview</p>
    </div>
    <button onclick="window.print()" class="btn-warning">
        <i class="fa-solid fa-print"></i> Print Report
    </button>
</div>

<div style="overflow-x:auto;">
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Dish</th>
                <th>Amount Paid</th>
                <th>Change</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td style="font-size:13px;color:#666;">{{ $t->created_at->format('M d, Y') }}</td>
                <td style="font-weight:500;">{{ $t->dish->name }}</td>
                <td>
                    <span style="color:#22c55e;font-weight:600;">₱{{ number_format($t->amount_paid, 2) }}</span>
                </td>
                <td>
                    <span style="color:#64748b;">₱{{ number_format($t->change, 2) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;padding:60px;color:#aaa;">
                    <div style="font-size:40px;margin-bottom:12px;">📊</div>
                    No transactions recorded yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection