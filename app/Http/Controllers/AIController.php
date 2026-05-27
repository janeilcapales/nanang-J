$('#taste-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: '/ai/process',
        method: 'POST',
        data: {
            taste: $('#taste').val(),
        },
        success: function (res) {
            $('#suggestion-result').text('We suggest: ' + res.suggestion).fadeIn();
        },
        error: function (xhr) {
            showValidationErrors(xhr.responseJSON.errors);
        }
    });
});