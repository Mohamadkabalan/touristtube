var frmValidator = null;

$(function () {
    if (typeof window.hotelFormId === 'undefined' || window.hotelFormId === null) {
	window.hotelFormId = 'form_hotel_book';
    }

    var formExists = $('#' + window.hotelFormId).length;
    if (formExists) {
	console.log(window.hotelFormId + ' form exsits.');
	frmValidator = new TTFormValidator('#' + window.hotelFormId);

	frmValidator.addCustomRule("#reservationWish", "wish", Translator.trans("Special requests can only be alphanumeric and a maximum of 127 characters."), function (val) {
	    var regex = new RegExp("^[a-zA-Z0-9\s ]+");
	    if (val) {
		return (regex.test(val) && (val.length <= 127));
	    }

	    return false;
	});

	frmValidator.addCustomRule(".ccValidOnly", "ccvalidity", Translator.trans("Credit card validity invalid"), function (val) {
	    return ccvalidity();
	});

	frmValidator.addCustomRule(".textOnly", "textOnly", Translator.trans("The name you entered cannot contain numbers and/or special characters"), function (val) {
	    return textOnly(val);
	});

	frmValidator.addCustomRule(".numberOnly", "numberOnly", Translator.trans("The value you entered should only contain numbers"), function (val) {
	    return numberOnly(val);
	});
    }

    function ccvalidity() {
	$('.ccexpirationhldr .tt_validation_message').remove();

	var year = $('#ccExpiryYear').val();
	var month = $('#ccExpiryMonth').val();

	if ((year && !month) || (!year && month)) {
	    return false;
	} else if (year == currentMonthYear[1] && month < currentMonthYear[0]) {
	    return false;
	} else {
	    return true;
	}
    }

    function textOnly(val) {
	var regex = new RegExp("^[^\\d\\_\\(\\)\\!\\@\\#\\$\\%\\^\\&\\*]+$");
	if (val) {
	    return (regex.test(val));
	}

	return false;
    }

    function numberOnly(val) {
	var regex = new RegExp("^[0-9]+$");
	if (val) {
	    return (regex.test(val));
	}

	return false;
    }
});
