
$('.modal-form').on('click', function (e) {
    e.preventDefault();
    var url = $(this).attr('data-url');
    $.ajax({
        method: "POST",
        url: url,
        success: function (data) {
            console.log($(data.html).find('.modal'));
            $('.modales').empty().append(data.html).find('.modal').modal('show');
        }
    });
});

$(document).on('click', '.modal-submit', function (e) {
    e.preventDefault();
    var form = $(this).closest('form');
    var body = form.find('.modal-body');
    var data = form.serialize();
    $(this).remove();
    body.empty().append('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
    $.ajax({
        method: "POST",
        url: form.attr('action'),
        data: data,
        success: function (data) {
            switch (data.status) {
                case "success":
                    body.empty().append('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                    break;
                case "error":
                    body.empty().append('<div class="alert alert-danger" role="alert">' + data.message + '</div>');
                    break;
            }         
        }
    });
});
