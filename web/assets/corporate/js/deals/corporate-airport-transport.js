var ttoverlay = null;

$(document).ready(function () {
    ttoverlay = new TTOverlay("airportTransport");
    var today = new Date();
    if ($("#arrivalInputContainer").length) {
	$("#arrivalInputContainer").dateRangePicker({
	    autoClose: true,
	    showTopbar: false,
	    startDate: today,
	    singleDate: true,
	    singleMonth: true,
	    setValue: function (s, s1) {
		$('#arrivalInput').val(s1);
	    }
	});
    }
    if ($("#departureInputContainer").length) {
	$("#departureInputContainer").dateRangePicker({
	    autoClose: true,
	    showTopbar: false,
	    startDate: today,
	    singleDate: true,
	    singleMonth: true,
	    setValue: function (s, s1) {
		$('#departureInput').val(s1);
	    }
	});
    }

    /*
     * This is for Step 4 Search Hotels
     * Commeting out for now until we get the autocomplete from elastic team
     */
    /*if ($("#searchdiscover").length) {
     addAutoCompleteListHotels();
     }*/
    /*
     * List of all onchange functions for each step
     */
    $("#destinationCountry").change(function () {
	$('#infoBox').show();
	$('#location_text').html($("#destinationCountry option:selected").text());

	// hide error box
	$('.bookingBoxError').hide();
    });
    $(".step1typetransfer").click(function () {
	switch ($("#typeOfTransfer").val()) {
	    case 'oneWayFromAirport':
		var typeOFTransfer = Translator.trans('From Airport / Station / Port to Dropoff point');
		break;
	    case 'oneWayToAirport':
		var typeOFTransfer = Translator.trans('From Pickup point to Airport / Station / Port');
		break;
	    default:
		var typeOFTransfer = Translator.trans('From Airport / Station / Port to Dropoff point and back');
	}
	$('#type_of_transfer_text').html(typeOFTransfer);
    });
    $("#numOfpassengers").change(function () {
	$('#infoContainer9').show();
	$('#passengers_text').html($("#numOfpassengers").val());
    });


    // For Step 0
    $("#step0next_sts").click(function () {
	ttoverlay.show();
	if ($("#destinationCountry").val() !== "") {
	    $.ajax({
		url: generateLangURL($("#airportCitiesListingPath").val(), 'corporate'),
		data: {country: $("#destinationCountry").val()},
		type: 'post',
		success: function (result) {
		    ttoverlay.hide();
		    //check if there is a city found for that country.
		    if (result.count === 0) {
			alert(Translator.trans("No city found for that country. Please choose another."));
			return false;
		    }
		    $('#step-1_sts_box').html(result.output);
		    toggleShowHideNext(0);
		}
	    });
	} else {
	    alert(Translator.trans("Please select a country."));
	}
    });

    $("#step1next_sts").click(function () {
	if ($("#destinationCity").val() !== "") {
	    $('#savedCity').val($("#destinationCity").val());
	    $('#type_of_transfer_text').html('From Airport / Station / Port to Dropoff point and back');
	    $('#infoContainer2').show();
	    toggleShowHideNext(1);
	} else {
	    alert(Translator.trans("Please select a city."));
	}
    });

    $("#step2next_sts").click(function () {
	ttoverlay.show();
	if ($("#destinationCountry").val() !== "" && $("#destinationCity").val() !== "") {
	    $.ajax({
		url: generateLangURL($("#airportListingPath").val(), 'corporate'),
		data: {country: $("#destinationCountry").val(), city: $("#destinationCity").val()},
		type: 'post',
		success: function (result) {
		    ttoverlay.hide();
		    //check if there is an airport found for that country.
		    if (result.count === 0) {
			alert(Translator.trans("No airports currently available. Please choose another option."));
			return false;
		    }
		    $('#transferAirportName').val(result.firstRecord.name);
		    $('#transferAirportCode').val(result.firstRecord.code);
		    $('#airport_text').html(result.firstRecord.name);
		    $('#infoContainer3').show();
		    $('#step-3_sts_box').html(result.output);
		    toggleShowHideNext(2);
		}
	    });
	} else {
	    alert(Translator.trans("Please select a country."));
	}
    });

    $("#step3next_sts").click(function () {
	if ($("#typeOfTransfer").val() !== "") {
	    toggleShowHideNext(3);
	}
    });

    $("#step4next_sts").click(function () {
	var typeOfTransfer = $("#typeOfTransfer").val();
	var destinationAddress = $("#destinationAddress").val();
	if (typeOfTransfer !== "" && destinationAddress != "") {
	    switch (typeOfTransfer) {
		case 'oneWayFromAirport':
		    $('.departureSection').hide();
		    if ($('.departureSection').is(':hidden')) {
			$('.arrivalSection').show();
		    }
		    break;
		case 'oneWayToAirport':
		    $('.arrivalSection').hide();
		    if ($('.arrivalSection').is(':hidden')) {
			$('.departureSection').show();
		    }
		    break;
		default:
		    $('.arrivalSection').show();
		    $('.departureSection').show();
	    }
	    $('#infoContainer4').show();
	    $('#address_text').html($("#destinationAddress").val());
	    toggleShowHideNext(4);
	} else {
	    alert(Translator.trans("Please fillout address field."));
	}
    });

    $("#step5next_sts").click(function () {
	if ($("#step5Cleared").val() === "Yes") {
	    toggleShowHideNext(5);
	} else {
	    alert(Translator.trans("Please fill out all fields."));
	}
    });

    $("#step6next_sts").click(function () {
	ttoverlay.show();
	if ($("#numOfpassengers").val() !== "") {
	    $.ajax({
		url: generateLangURL($("#airportVehiclesPath").val(), 'corporate'),
		data: {destinationCountry: $("#destinationCountry").val(),
		    destinationCity: $("#destinationCity").val(),
		    airportCode: $("#transferAirportCode").val(),
		    typeOfTransfer: $("#typeOfTransfer").val(),
		    numOfPassengers: $("#numOfpassengers").val(),
		    arrivalInput: $("#arrivalInput").val(),
		    arrivalHour: $("#arrivalHour").val(),
		    arrivalMinute: $("#arrivalMinute").val(),
		    departureInput: $("#departureInput").val(),
		    departureHour: $("#departureHour").val(),
		    departureMinute: $("#departureMinute").val(),
		    selectedCurrency: $('.currency-item.selected').attr('currency-code-data')
		},
		type: 'post',
		success: function (result) {
		    ttoverlay.hide();
		    //check if there is an airport found for that country.
		    if (result.count === 0) {
			alert(Translator.trans("No vehicles currently available on your specifications. Please modify options(country/city/airport/time/date/num of passengers)."));
			return false;
		    }
		    $('#step-7_sts_box').html(result.output);
		    toggleShowHideNext(6);
		}
	    });
	} else {
	    alert(Translator.trans("Please fill out all fields."));
	}
    });

    $(".pinkbutacontainer_airtrans").click(function () {
	$('#step-7_sts').hide();
	if ($('#step-7_sts').is(':hidden')) {
	    $('#step-8_sts').show();
	}
    });
    $(".prevStep").click(function () {
	var id = $(this).attr('id');
	toggleShowHidePrevious(parseInt(id.slice(4, 5)));
    });
});


/*
 * Added this function here cause City is located in another template so it can see any event declared inside the ready
 */
function updateCityOnChange() {
    $('#location_text').html($("#destinationCity").val() + ', ' + $("#destinationCountry option:selected").text());
}


function changeTypeOfTransfer(x, type) {
    $('#step-2_sts .active').removeClass('active');
    $(x).addClass('active');
    $('#typeOfTransfer').val(type);
}

/*
 * Added this function here cause Airport is located in another template so it can see any event declared inside the ready
 */
function updateTransferAirportOnChange(newVal) {
    $('#airport_text').html(newVal);
    $('#infoContainer3').show();
}

function changeTransferAirport(y, airportCode, airportName) {
    $('#step-3_sts .active').removeClass('active');
    $(y).addClass('active');
    $('#transferAirportName').val(airportName);
    $('#transferAirportCode').val(airportCode);
}

/*
 * Added this function to organize the arrival and departure step
 */
function updateArrivalAndDeparture() {
    var typeOfTransfer = $("#typeOfTransfer").val();
    var arrivalInput = $("#arrivalInput").val();
    var arrivalHour = $("#arrivalHour").val();
    var arrivalMinute = $("#arrivalMinute").val();
    var departureInput = $("#departureInput").val();
    var departureHour = $("#departureHour").val();
    var departureMinute = $("#departureMinute").val();
    var proceedNextStep = false;

    switch (typeOfTransfer) {
	case 'oneWayFromAirport':
	    if (arrivalInput !== '') {
		$("#arrival_text").html(Translator.trans('From Pickup point to Airport / Station / Port'));
		$("#arrivalDate_text").html(arrivalInput + ', ' + arrivalHour + ':' + arrivalMinute);
		$("#infoContainer5").show();
		$("#infoContainer6").show();

		proceedNextStep = true;
	    }
	    break;
	case 'oneWayToAirport':
	    if (departureInput !== '') {
		$("#departure_text").html(Translator.trans('From Airport / Station / Port to Dropoff point'));
		$("#departureDate_text").html(departureInput + ', ' + departureHour + ':' + departureMinute);
		$("#infoContainer7").show();
		$("#infoContainer8").show();

		proceedNextStep = true;
	    }
	    break;
	default:
	    if (arrivalInput !== '' && departureInput !== '') {
		$("#arrival_text").html(Translator.trans('From Pickup point to Airport / Station / Port'));
		$("#arrivalDate_text").html(arrivalInput + ', ' + arrivalHour + ':' + arrivalMinute);
		$("#departure_text").html(Translator.trans('From Airport / Station / Port to Dropoff point'));
		$("#departureDate_text").html(departureInput + ', ' + departureHour + ':' + departureMinute);

		$("#infoContainer5").show();
		$("#infoContainer6").show();
		$("#infoContainer7").show();
		$("#infoContainer8").show();

		proceedNextStep = true;
	    }
    }

    if (proceedNextStep) {
	$("#step5Cleared").val('Yes');
    } else {
	$("#step5Cleared").val('No');
    }
}

function selectVehicleType(arrivalPriceId, departurePriceId, totalPrice, priceCurrency, carModel, serviceType) {
    switch ($("#typeOfTransfer").val()) {
	case 'oneWayFromAirport':
	    $('#bookingArrivalBox').show();
	    break;
	case 'oneWayToAirport':
	    $('#bookingDepartureBox').show();
	    break;
	default:
	    $('#bookingArrivalBox').show();
	    $('#bookingDepartureBox').show();
    }

    $('.transportCity').html($("#destinationCity").val());
    $('.completeAddress').val($('#destinationAddress').val());
    $('#arrivalPriceId').val(arrivalPriceId);
    $('#departurePriceId').val(departurePriceId);
    $('#totalPrice').val(totalPrice);
    $('#currency').val(priceCurrency);
    $('#carModel').val(carModel);
    $('#serviceType').val(serviceType);

    $('#step-7_sts').hide();
    $('#step-8_sts').show();
}

/*
 * Added this function to show booking price text after a vehicle is selected
 */
function showBookNowStartingPrice(x) {
    var convertedPrice = $(x).find(".price-convert").attr('data-price');
    var selectedCurrency = $(x).find(".currency-convert-text").html();
    var formattedPrice = $(x).find(".price-convert-text").html();

    $('#vDataPrice').attr("data-price", convertedPrice);
    $('#vCurrency').html(selectedCurrency);
    $('#vPrice').html(formattedPrice);
    $('#vPriceContainer').show();
}
/*
 * This function will toggle the hide show of previous buttons in every step <firas.boukarroum>
 */
function toggleShowHidePrevious(i) {
    $('#step-' + i + '_sts').hide();
    if ($('#step-' + i + '_sts').is(':hidden')) {
	$('#step-' + (i - 1) + '_sts').show();
    }
}
function toggleShowHideNext(i) {
    $('#step-' + i + '_sts').hide();
    if ($('#step-' + i + '_sts').is(':hidden')) {
	$('#step-' + (i + 1) + '_sts').show();
    }
}

/*
 * This is for Step 4 Search Hotels
 */
function addAutoCompleteListHotels(which) {
    var $ccity = $("#searchdiscover");
    $ccity.autocomplete({
	minLength: minSearchLength,
	appendTo: "#searchhrscontainer",
	search: function (event, ui) {
	    $ccity.autocomplete("option", "source", generateLangURL('/ajax/Hotel_search_for1'));
	},
	select: function (event, ui) {
	    $ccity.val(ui.item.name);
	    $ccity.parent().parent().find('#destinationAddress').val(ui.item.name).focus();
	    $('#infoContainer4').show();
	    $('#address_text').html($("#destinationAddress").val());
	    event.preventDefault();
	}

    }).keydown(function (event) {
	var code = (event.keyCode ? event.keyCode : event.which);
	if (code === 13) {
	    event.preventDefault();
	    return false;
	}

    }).data("ui-autocomplete")._renderItem = function (ul, item) {
	return $("<li></li>")
		.data("item.autocomplete", item)
		.append("<a class='auto_tuber'>" + item.label + "</a>")
		.appendTo(ul);
    };
}