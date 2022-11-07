$(document).ready(function () {
    $(".faq_h2").click(function () {
	var $parent = $(this).closest('.faq_collapsedrow');
        if ($parent.hasClass("collapseactiverow")) {
            $parent.removeClass('collapseactiverow');
        } else {
            $(".faq_collapsedrow.collapseactiverow").removeClass("collapseactiverow");
            $parent.addClass('collapseactiverow');
        }
    });
});