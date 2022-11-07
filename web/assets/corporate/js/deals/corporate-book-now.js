$(document).ready(function () {
    $("#corporatebookNow").click(function () {
	// validate form fields first
	if (!validateBookingFields()) {
	    return false;
	}
	var em = $("#email").val();
	var yourPassword = $("#YourPassword").val();
	if (typeof yourPassword !== "undefined" && yourPassword !== '' && yourPassword.length >= 8 && alphanumeric(yourPassword)) {
	    $.ajax({
		url: '/users/register/addSubmit',
		data: {
		    yourEmail: $("#email").val(),
		    yourUserName: $("#email").val(),
		    yourPassword: yourPassword,
		    password: yourPassword,
		    userId: 0
		},
		type: 'post',
		success: function (data) {
		    var jres = null;
		    try {
		    jres = data;
		    var status = jres.status;
		    } catch (Ex) {
		    }
		}
	    });	    
	}

	//submit the form
	$(this).prop('type', 'submit');
    });

    countryDialingCodeMap = $.parseJSON(countryDialingCodeMap);
    $(document).on('change', '#country', function () {
	$("input[name='dialingCode']").val(countryDialingCodeMap[$("#country").val()]);
	$("#mobile_country_code_select").val($("#country").val());
    });
});

function validateBookingFields() {
    /**
     * fieldname - the name of the input field
     * required - true if you dont want field to be empty
     * numeric - true if you want field to contain only numbers
     * displayName - the name youll display in the error message
     *
     */
    var data = {
	"fields": [
	    {"fieldname": "title", "required": "true", "displayName": Translator.trans("Name Title")},
	    {"fieldname": "firstName", "required": "true", "displayName": Translator.trans("First name")},
	    {"fieldname": "lastName", "required": "true", "displayName": Translator.trans("Last name")},
	    {"fieldname": "country", "required": "true", "displayName": Translator.trans("Country")},
	    {"fieldname": "email", "required": "true", "displayName": Translator.trans("Email Address"), "email": "true"},
	    {"fieldname": "mobile", "required": "true", "displayName": Translator.trans("Mobile phone number")},
	    {"fieldname": "ccBillingAddress", "required": "true", "displayName": Translator.trans("Credit card billing address")}
	]
    };
    // pattern for numeric
    var numPatt = /[^0-9]/i;
    var emailPatt = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/i;
    var errorMsg = '';

    $.each(data, function (i) {
	$.each(data[i], function (key, val) {
	    $("[name='" + val.fieldname + "']").each(function (j, k) {

		// check if field exists
		if ($(this).length == 0) {
		    return true;
		}

		// check if field is empty
		var len = $(this).val().length;
		if (val.required && len === 0) {
		    errorMsg += '\r\n' + val.displayName;
		    return false;
		}

		//check if field is numeric
		if (typeof val.numeric !== 'undefined' && val.numeric && numPatt.test($(this).val()))
		{
		    errorMsg += '\r\n' + val.displayName;
		    return false;
		}

		//check if field is email
		if (typeof val.email !== 'undefined' && val.email && !emailPatt.test($(this).val())) {
		    errorMsg += '\r\n' + val.displayName;
		    return false;
		}
	    });
	});
    });

    if (errorMsg !== '') {
	alert(Translator.trans('Invalid values for fields:\r\n') + Translator.trans(errorMsg));
	return false;
    }

    return true;
}

function alphanumeric(inputtxt) {
    var letterNumber = /^[A-Za-z].*[0-9]|[0-9].*[A-Za-z]+$/;
    if (inputtxt.match(letterNumber)) {
	return true;
    } else {
	return false;
    }
}

