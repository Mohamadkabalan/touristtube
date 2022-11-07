/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    // combine them both, including the parameter for minlength
    $.validator.addClassRules("inputRequired", {required: true});
    $.validator.addClassRules("alphaNumeric", {regex: "^[a-zA-Z0-9 .-]+$"});
    $.validator.addClassRules("alphaNumericRequired", {required: true, regex: "^[a-zA-Z0-9 .-]+$"});
    $.validator.addClassRules("guestEmail", {email: true});
    $.validator.addClassRules("reservationWish", {wish: true});

    $.validator.addMethod("wish", function (value, element, params) {
	var wish = $('#reservationWish').val();
	var regex = new RegExp("^[a-zA-Z0-9\s ]+");
	return this.optional(element) || (regex.test(value) && (wish.length <= 127));
    }, Translator.trans("Special requests can only be alphanumeric and a maximum of 127 characters."));

    $.validator.addMethod("ccvalidity", function (value, element, params) {
	var year = $('#ccExpiryYear').val();
	var month = $('#ccExpiryMonth').val();
	if ((year && !month) || (!year && month)) {
	    return 0;
	} else if (year == params[1] && month < params[0]) {
	    return 0;
	} else {
	    return 1;
	}
    }, Translator.trans("Credit card validity invalid."));

    $.validator.addMethod("regex", function (value, element, regexp) {
	var re = new RegExp(regexp);
	return this.optional(element) || re.test(value);
    }, Translator.trans("Invalid input."));

    // validate form_hotel_book form on keyup and submit
    var validator = $("#form_hotel_book").validate({
	rules: {
	    email: {
		required: true,
		email: true
	    },
	    mobile: {
		required: true,
		digits: true,
		minlength: 6
	    },
	    ccType: {
		required: ccRequired
	    },
	    ccCardHolder: {
		required: ccRequired,
		regex: "^[a-zA-Z0-9 .-]+$"
	    },
	    ccNumber: {
		required: ccRequired,
		creditcard: true
	    },
	    ccExpiryMonth: {
		required: ccRequired,
		ccvalidity: {
		    param: currentMonthYear
		}
	    },
	    ccExpiryYear: {
		required: ccRequired,
		ccvalidity: {
		    param: currentMonthYear
		}
	    },
	    ccCVC: {
		required: ccRequired,
		digits: true,
	    },
	    terms: {
		required: true
	    }
	},
	groups: {
	    cc_validity: "ccExpiryMonth ccExpiryYear"
	},
	errorPlacement: function (error, element) {
	    if (element.attr("name") == "ccExpiryMonth" || element.attr("name") == "ccExpiryYear") {
		error.insertAfter("#ccExpiryMonth");
	    } else if (element.attr("name") == "terms") {
		error.insertAfter("#terms_note");
	    } else {
		error.insertAfter(element);
	    }
	},
	messages: {
	    mobile: {
		minlength: Translator.trans("Minimum length is 6 characters.")
	    },
	    terms: {
		required: Translator.trans("Please accept our policy.")
	    }
	},
	debug: false,
	success: "valid",
	onfocusout: function (element) {
	    $(element).valid();
	},
	errorClass: "error-label",
	focusInvalid: false,
	invalidHandler: function (form, validator) {

	    if (!validator.numberOfInvalids())
		return;

	    $('html, body').animate({
		scrollTop: $(validator.errorList[0].element).offset().top
	    }, 2000);

	},
	submitHandler: function (form) {
	    if ($(form).valid()) {
		form.submit();
	    }
	    return false; // prevent normal form posting
	}
    });
});
