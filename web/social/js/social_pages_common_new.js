$(document).ready(function () {
    $(".faq_collapsedrow").click(function () {
        if ($(this).hasClass("collapseactiverow")) {
            $(this).removeClass('collapseactiverow');
        } else {
            $(".faq_collapsedrow.collapseactiverow").removeClass("collapseactiverow");
            $(this).addClass('collapseactiverow');
        }

    });
});