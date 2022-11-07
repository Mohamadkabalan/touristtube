var ttModal = false;
$(document).ready(function () {
    var formId = '';
    $(document).on('click', "#cancel_whole_reservation", confirmCancellation);
    $(document).on('click', ".cancel-room-button", confirmRoomCancellation);
    if ($('.addtobag').length > 0)
	$('.addtobag').click();

    if (!ttModal) {
	ttModal = window.getTTModal("myModalZ", {});
    }
});

function confirmCancellation() {
    ttModal.alert(Translator.trans('Are you sure you want to cancel this reservation?'), function (btn) {
	if (btn == "ok") {
	    showHotelOverlay('', 'cancellation');
	    $('#reservation-cancel-submit').submit();
	}
	return false;
    }, null, {ok: {value: Translator.trans("Yes")}});

    return false;
}

function confirmRoomCancellation() {
    formId = '#' + $(this).attr('data-formId');

    ttModal.alert(Translator.trans('Are you sure you want to cancel this room?'), function (btn) {
	if (btn == "ok") {
	    if (formId !== '') {
		showHotelOverlay('', 'cancellation');
		$(formId).submit();
	    }
	} else {
	    formId = '';
	}
	return false;
    }, null, {ok: {value: Translator.trans("Yes")}});

    return false;
}
