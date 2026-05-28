@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h2>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->name }}! 👋</h2>
        <p>Here's what's happening at your restaurant today.</p>
    </div>
    <button type="button" class="btn btn-outline-secondary" id="refreshStatsBtn" title="Refresh Statistics">
        <i class="fa-solid fa-rotate-right"></i> Refresh
    </button>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-5" id="statsContainer">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fa-solid fa-bowl-food"></i></div>
            <div class="stat-number stat-dishes">{{ $dishesCount }}</div>
            <div class="stat-label">Total Dishes on Menu</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon gold"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="stat-number stat-reservations">{{ $reservationsCount }}</div>
            <div class="stat-label">Active Reservations</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-receipt"></i></div>
            <div class="stat-number stat-transactions">{{ $transactionsCount }}</div>
            <div class="stat-label">Total Transactions</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="page-header">
    <h2 style="font-size:20px;">Quick Actions</h2>
</div>

<div class="row g-3" id="quickActionsContainer">
    @if(Auth::user()->role === 'admin')
    <div class="col-md-3">
        <div class="stat-card text-center" style="cursor:pointer;" data-action="dishes">
            <div style="font-size:28px;margin-bottom:10px;">🍜</div>
            <div style="font-size:14px;font-weight:500;color:#1a1008;">Manage Dishes</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center" style="cursor:pointer;" data-action="reservations">
            <div style="font-size:28px;margin-bottom:10px;">📅</div>
            <div style="font-size:14px;font-weight:500;color:#1a1008;">View Reservations</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center" style="cursor:pointer;" data-action="transactions">
            <div style="font-size:28px;margin-bottom:10px;">💳</div>
            <div style="font-size:14px;font-weight:500;color:#1a1008;">Transactions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center" style="cursor:pointer;" data-action="reports">
            <div style="font-size:28px;margin-bottom:10px;">📊</div>
            <div style="font-size:14px;font-weight:500;color:#1a1008;">Reports</div>
        </div>
    </div>
    @else
    <div class="col-md-4">
        <div class="stat-card text-center" style="cursor:pointer;" data-action="reservation-create">
            <div style="font-size:28px;margin-bottom:10px;">➕</div>
            <div style="font-size:14px;font-weight:500;color:#1a1008;">Book a Reservation</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center" style="cursor:pointer;" data-action="reservations">
            <div style="font-size:28px;margin-bottom:10px;">📅</div>
            <div style="font-size:14px;font-weight:500;color:#1a1008;">My Reservations</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center" style="cursor:pointer;" data-action="transactions">
            <div style="font-size:28px;margin-bottom:10px;">💳</div>
            <div style="font-size:14px;font-weight:500;color:#1a1008;">My Transactions</div>
        </div>
    </div>
    @endif
</div>

@endsection

<script>
$(document).ready(function() {
    // Route mapping for AJAX actions
    const actionRoutes = {
        'dishes': '{{ route("dishes.index") }}',
        'reservations': '{{ route("reservations.index") }}',
        'reservation-create': '{{ route("reservations.create") }}',
        'transactions': '{{ route("transactions.index") }}',
        'reports': '{{ route("reports.index") }}'
    };

    // Load content via AJAX into modal
    const loadContentInModal = (url, title = 'Content') => {
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function() {
                // Show loading state
                let modal = $('#contentModal');
                if (modal.length === 0) {
                    modal = $(`
                        <div id="contentModal" class="modal fade" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">${title}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    $('body').append(modal);
                }
                const bootstrapModal = new bootstrap.Modal(modal[0]);
                bootstrapModal.show();
            },
            success: function(data) {
                const modal = $('#contentModal');
                modal.find('.modal-body').html(data);
            },
            error: function(xhr) {
                const modal = $('#contentModal');
                modal.find('.modal-body').html(`
                    <div class="alert alert-danger" role="alert">
                        <strong>Error!</strong> Failed to load content. Please try again.
                    </div>
                `);
                console.error('Error loading content:', xhr);
            }
        });
    };

    // Refresh statistics
    const refreshStats = () => {
        $.ajax({
            url: '{{ route("dashboard.stats") }}',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#refreshStatsBtn').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Loading...');
            },
            success: function(data) {
                // Update stat cards with animation
                $('.stat-dishes').fadeOut(200, function() {
                    $(this).text(data.dishesCount).fadeIn(200);
                });
                $('.stat-reservations').fadeOut(200, function() {
                    $(this).text(data.reservationsCount).fadeIn(200);
                });
                $('.stat-transactions').fadeOut(200, function() {
                    $(this).text(data.transactionsCount).fadeIn(200);
                });
            },
            error: function(xhr) {
                alert('Error refreshing statistics. Please try again.');
                console.error(xhr);
            },
            complete: function() {
                $('#refreshStatsBtn').prop('disabled', false).html('<i class="fa-solid fa-rotate-right"></i> Refresh');
            }
        });
    };

    // Attach event handlers to Quick Action cards
    const attachActionHandlers = () => {
        $(document).off('click', '[data-action]').on('click', '[data-action]', function(e) {
            e.preventDefault();
            const action = $(this).data('action');
            const url = actionRoutes[action];
            
            if (url) {
                // Get the label from the clicked element
                const label = $(this).find('div:last').text();
                loadContentInModal(url, label);
            } else {
                alert('Route not found for this action.');
            }
        });
    };

    // Refresh stats button
    $(document).off('click', '#refreshStatsBtn').on('click', '#refreshStatsBtn', function(e) {
        e.preventDefault();
        refreshStats();
    });

    // Initialize
    attachActionHandlers();

    // Optional: Auto-refresh stats every 30 seconds (remove if not needed)
    // setInterval(refreshStats, 30000);
});
</script>
