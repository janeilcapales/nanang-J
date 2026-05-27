function loadReport() {
    $.ajax({
        url: '/reports',
        method: 'GET',
        beforeSend: function () {
            $('#report-tbody').html('<tr><td colspan="5">Loading...</td></tr>');
        },
        success: function (res) {
            let rows = '';
            res.data.forEach(function (t) {
                rows += `
                    <tr>
                        <td>${t.id}</td>
                        <td>${t.dish ? t.dish.name : ''}</td>
                        <td>₱${parseFloat(t.amount_paid).toFixed(2)}</td>
                        <td>₱${parseFloat(t.change).toFixed(2)}</td>
                        <td>${t.created_at}</td>
                    </tr>`;
            });
            $('#report-tbody').html(rows);
        },
        error: function () {
            $('#report-tbody').html('<tr><td colspan="5">Failed to load report.</td></tr>');
        }
    });
}