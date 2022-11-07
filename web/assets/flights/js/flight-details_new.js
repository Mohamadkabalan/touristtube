$(function () {

    $('#cancel_url').on('click', function (e) {
        var cancel = confirm(Translator.trans('Are you sure to cancel reservation?'));
        var cancel_url = $(this).attr('data-link');
        if (cancel) {
            window.location = cancel_url;
        }
        e.preventDefault();
    });
});
