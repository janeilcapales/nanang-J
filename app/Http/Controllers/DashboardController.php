function loadDashboardStats() {
    $.ajax({
        url: '/dashboard/stats',
        method: 'GET',
        success: function (res) {
            $('#dishes-count').text(res.data.dishes_count);
            $('#reservations-count').text(res.data.reservations_count);
            $('#transactions-count').text(res.data.transactions_count);
        }
    });
}