$(document).ready(function () {
    $(function () {
	var timeoutHandle = window.setTimeout(function () {
	    window.location.href = generateLangURL(redirectURL);
	}, 600000);

	$("#masterPassImg").click(function () {
	    $("#masterPass").submit();
	});
	$("#visaCheckoutImg").click(function () {
	    $("#visaCheckout").submit();
	});
	$("form[name='payment']").validate({
	    rules: {
		card_number: {
		    required: true,
		    number: true,
		    maxlength: 16,
		    minlength: 16
		},
		select: {
		    required: true
		},
		expireMM: {
		    required: true
		},
		expireYY: {
		    required: true
		},
		expiry_date: {
		    required: true,
		    number: true,
		    maxlength: 4,
		    minlength: 4
		},
		card_security_code: {
		    required: true,
		    number: true,
		    maxlength: 3,
		    minlength: 3
		}
	    },
	    messages: {
		card_number: {
		    required: Translator.trans("Required field; cannot be left empty"),
		    number: Translator.trans("Field must only be digits"),
		    minlength: Translator.trans("Your card number must be at least 16 characters long"),
		    maxlength: Translator.trans("Your card number must be maximum 16 characters long")
		},
		expireMM: {
		    required: Translator.trans("Required field; cannot be left empty")
		},
		expireYY: {
		    required: Translator.trans("Required field; cannot be left empty")
		},
		expiry_date: {
		    required: Translator.trans("Required field; cannot be left empty"),
		    number: Translator.trans("Field must only be digits"),
		    minlength: Translator.trans("Expiry date must be at least 4 characters long"),
		    maxlength: Translator.trans("Expiry date must be maximum 4 characters long")
		},
		card_security_code: {
		    required: Translator.trans("Required field; cannot be left empty"),
		    number: Translator.trans("Field must only be digits"),
		    minlength: Translator.trans("Expiry date must be at least 4 characters long"),
		    maxlength: Translator.trans("Expiry date must be maximum 4 characters long")
		}
	    },
	    submitHandler: function (form) {
		window.clearTimeout(timeoutHandle);
		timeoutHandle = window.setTimeout(function () {
		    window.location.href = generateLangURL(redirectURL);
		}, 600000);
		$('#expiry_date').val($('#expireYY').val() + $('#expireMM').val());
		$("#expireMM").prop("disabled", true);
		$("#expireYY").prop("disabled", true);
		$("#device_fingerprint").prop("disabled", true);
		form.submit();
	    }
	});
    });
    $.ajax({
	type: "POST",
	url: '/api/device_fingerprint',
	dataType: 'json',
	data: JSON.stringify({'transaction_id': $.url().param('transaction_id'), 'device_fingerprint': $("#device_fingerprint").val()}),
	success: function (data) {
	    if (data.status === 200) {
		$('.upload-overlay-loading-fix').hide();
	    }
	}
    });
});
