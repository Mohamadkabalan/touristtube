$(document).ready(function () {
    $(document).on('click', '.sort-options a', function () {
        $(".sort-options a").removeClass('active');
        $(this).addClass('active');
    });
});