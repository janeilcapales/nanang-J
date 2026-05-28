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
            <tbody id="report-tbody">
                <tr>
                    <td colspan="4" style="text-align:center;padding:20px;color:#aaa;">
                        <i class="fa-solid fa-spinner fa-spin"></i> Loading...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            loadReport();
        });

        function loadReport() {
            $.ajax({
                url: '{{ route('reports.index') }}',
                method: 'GET',
                beforeSend: function() {
                    $('#report-tbody').html(
                        '<tr><td colspan="4" style="text-align:center;padding:20px;"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</td></tr>'
                    );
                },
                success: function(res) {
                    let rows = '';

                    if (res.transactions && res.transactions.length > 0) {
                        res.transactions.forEach(function(t) {
                            const date = new Date(t.created_at).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });

                            rows += `
                        <tr>
                            <td style="font-size:13px;color:#666;">${date}</td>
                            <td style="font-weight:500;">${t.dish ? t.dish.name : 'N/A'}</td>
                            <td>
                                <span style="color:#22c55e;font-weight:600;">₱${parseFloat(t.amount_paid).toFixed(2)}</span>
                            </td>
                            <td>
                                <span style="color:#64748b;">₱${parseFloat(t.change).toFixed(2)}</span>
                            </td>
                        </tr>`;
                        });
                    } else {
                        rows =
                            '<tr><td colspan="4" style="text-align:center;padding:60px;color:#aaa;"><div style="font-size:40px;margin-bottom:12px;">📊</div>No transactions recorded yet.</td></tr>';
                    }

                    $('#report-tbody').html(rows);
                },
                error: function() {
                    $('#report-tbody').html(
                        '<tr><td colspan="4" style="text-align:center;padding:60px;color:#aaa;">Failed to load report.</td></tr>'
                    );
                }
            });
        }
    </script>

@endsection
