$(document).ready(function () {
    $("#bookNow").click(function () {
// Im trying to reuse this function from deals.
// Trying to add validation for transfers here.
        if (typeof $("#pageName").val() !== 'undefined') {
            var pageName = $("#pageName").val();
        } else {
            var pageName = 'deals';
        }

// validate form fields first
        if (!validateBookingFields(pageName)) {
            return false;
        }
        var em = $("#email").val();
        console.log('email ' + em);
        var yourPassword = $("#YourPassword").val();
        if (typeof yourPassword !== "undefined" && yourPassword !== '' && yourPassword.length >= 8 && alphanumeric(yourPassword)) {
//	    var em = $("#email").val();
//	    console.log('email '+em);
            $.ajax({
                url: generateLangURL('/users/register/addSubmit', 'empty'),
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

function validateBookingFields(pageName) {
    /**
     * fieldname - the name of the input field
     * required - true if you dont want field to be empty
     * numeric - true if you want field to contain only numbers
     * displayName - the name youll display in the error message
     *
     */
    if (pageName === 'transfers') {
        var data = {
            "fields": [
                {"fieldname": "country", "required": "true", "displayName": "Country"},
                {"fieldname": "billingCity", "required": "true", "displayName": "City"},
                {"fieldname": "savedCity", "required": "true", "displayName": "City"},
                {"fieldname": "typeOfTransfer", "required": "true", "displayName": "Type of Transfer"},
                {"fieldname": "transferAirportName", "required": "true", "displayName": "Airport / Port / Train Station"},
                {"fieldname": "address", "required": "true", "displayName": "Hotel or Address"},
                {"fieldname": "numOfpassengers", "required": "true", "displayName": "Number of passengers"},
                {"fieldname": "firstName", "required": "true", "displayName": "First name"},
                {"fieldname": "lastName", "required": "true", "displayName": "Last name"},
                {"fieldname": "email", "required": "true", "displayName": "Email Address", "email": "true"},
                {"fieldname": "ccBillingAddress", "required": "true", "displayName": "Credit card billing address"},
                {"fieldname": "travelerCountry", "required": "true", "displayName": "Country code"},
                {"fieldname": "mobile", "required": "true", "displayName": "Mobile phone number"},
                {"fieldname": "mobile_country_code", "required": "true", "displayName": "Area Code"},
                {"fieldname": "postalCode", "required": "true", "displayName": "Postal code"}
            ]
        };
        switch ($("#typeOfTransfer").val()) {
            case 'oneWayFromAirport':
                data.fields.push({"fieldname": "arrivalCompany", "required": "true", "displayName": "Arrival company or flight number"},
                        {"fieldname": "arrivingFrom", "required": "true", "displayName": "Arriving form"},
                        {"fieldname": "arrivalCompleteAddress", "required": "true", "displayName": "Complete Arrival Destination Address"});
                break;
            case 'oneWayToAirport':
                data.fields.push({"fieldname": "departureCompany", "required": "true", "displayName": "Departure company or flight number"},
                        {"fieldname": "goingTo", "required": "true", "displayName": "Going to"},
                        {"fieldname": "departureCompleteAddress", "required": "true", "displayName": "Complete Departure Destination Address"});
                break;
            default:
                data.fields.push({"fieldname": "arrivalCompany", "required": "true", "displayName": "Arrival company or flight number"},
                        {"fieldname": "arrivingFrom", "required": "true", "displayName": "Arriving form"},
                        {"fieldname": "arrivalCompleteAddress", "required": "true", "displayName": "Complete Arrival Destination Address"},
                        {"fieldname": "departureCompany", "required": "true", "displayName": "Departure company or flight number"},
                        {"fieldname": "goingTo", "required": "true", "displayName": "Going to"},
                        {"fieldname": "departureCompleteAddress", "required": "true", "displayName": "Complete Departure Destination Address"});
        }
    } else {
        var data = {
            "fields": [
                {"fieldname": "title", "required": "true", "displayName": "Name Title"},
                {"fieldname": "firstName", "required": "true", "displayName": "First name"},
                {"fieldname": "lastName", "required": "true", "displayName": "Last name"},
                {"fieldname": "country", "required": "true", "displayName": "Country"},
                {"fieldname": "email", "required": "true", "displayName": "Email Address", "email": "true"},
                {"fieldname": "ccBillingAddress", "required": "true", "displayName": "Credit card billing address"}
            ]
        };
    }
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
                if (typeof val.required !== 'undefined' && val.required && len === 0) {
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
        alert('Invalid values for fields:\r\n' + errorMsg);
        return false;
    }

    return true;
}
