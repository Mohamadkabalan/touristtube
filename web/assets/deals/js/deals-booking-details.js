$(document).ready(function () {
    $(".cancel_booking").click(function () {
	showDealsOverlay('dealsContainer');
    });
});

function cancelBooking(x) {
    var a = confirm(Translator.trans('Do you really want to Cancel your reservation?'));
    if (a) {
	$(x).closest("form").submit();
    }
}