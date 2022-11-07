var ttoverlay = null;

$(document).ready(function () {
    var $window = $(window);
    ttoverlay = new TTOverlay("priceOptions");

    $('.slider-for').slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: false,
	fade: true,
	centerMode: true,
	asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
	slidesToShow: 4,
	slidesToScroll: 1,
	appendArrows: '.slider-for',
	asNavFor: '.slider-for',
	dots: false,
	centerMode: true,
	focusOnSelect: true
    });
    $('.slider-for').removeClass('opacity0');
    if ($('.slider-nav .slick-track .mediathumb').length <= 4) {
	$('.slider-nav .slick-track').addClass('positionleft0');
    }
    function checkWidth() {
	var windowsize = $window.width();
	if (windowsize < 991) {
	    $(".collapsablesubtit_dat").click(function () {
		$(this).next('.smxsbordercollapse').toggle();
		$(this).next().next('.smxscollapsable_dat').toggle();
	    });
	}
	;
    }
    checkWidth();

    $(window).resize(checkWidth);

    // for price options
    $('#priceOptionSelect').change(function () {
	$('.priceOptions').hide();
	$('#option_' + $(this).val()).show();
    });

    var today = new Date();
    if ($("#datesDealsContainer").length) {
	$("#datesDealsContainer").dateRangePicker({
	    autoClose: true,
	    showTopbar: false,
	    startDate: today,
	    singleDate: true,
	    singleMonth: true,
	    setValue: function (s, s1) {
		$('#bookingDate').val(s1);
		getBookingBox();
	    }
	});
    }

    if ($(".reviewList").length) {
	toggleReviewList();
    }

    if ($("#reviewSorting").length) {
	$('#reviewSorting').on('change', function () {
	    reviewOverlay = new TTOverlay("reviewContainer");
	    reviewOverlay.show();
	    var myData = new Object();
	    myData['sorting'] = this.value;
	    myData['activityId'] = $('#activityId').val();
	    myData['apiId'] = $('#apiId').val();

	    var path = $('#reviewPath').val();

	    $.ajax({
		url: path,
		type: 'POST',
		data: myData,
		success: function (result) {
		    reviewOverlay.hide();
		    $('#reviewContainer').html('');
		    var apnd = '';
		    $.each(result, function (i, item) {
			apnd += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad padtop20 reviewList">';
			apnd += '<div class="greycontainer_dat">';
			apnd += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad">';
			apnd += '<div class="imagecircletxtcont_dat">';
			apnd += '<img class="greycircle_dat" src="'+generateMediaURL('/media/images/greycircle_hoteldetailed.png')+'" alt="greycircle">';
			apnd += '<p class="textingreycircle_dat">' + item.rating + '</p>';
			apnd += '</div>';
			apnd += '<p class="nextcircletext_dat">' + item.owner + '<span class="wherefromgreytext_dat">' + Translator.trans('From ') + item.country + ', ' + item.date + '</span></p>';
			apnd += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad">';
			apnd += '<p class="ingreytext_dat">' + item.comment + '</p>';
			apnd += '</div></div></div></div>';
		    });
		    apnd += '<div id="loadMoreBtn" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad padtop20">';
		    apnd += '<div class="loadmore_dat"> load more </div>';
		    apnd += '</div>';
		    $('#reviewContainer').append(apnd);
		    toggleReviewList();
		},
		error: function (error) {
		    reviewOverlay.hide();
		    alert(Translator.trans('error; ') + eval(error));
		}
	    });
	});
    }

    $(document).on('click', '#bbBuyNowButton', function () {
	ttoverlay = new TTOverlay("dealsContainer");
	ttoverlay.show();
    });
});

// when bookingDate changes
function getBookingBox() {
    ttoverlay.show();
    var path = $('#priceDetailsPath').val();
    var myData = new Object();
    myData['packageId'] = $('#packageId').val();
    myData['tourCode'] = $('#tourCode').val();
    myData['startDate'] = $('#bookingDate').val();
    myData['currency'] = $('.currency-item.selected').attr('currency-code-data');

    $.ajax({
	url: path,
	type: 'POST',
	data: myData,
	success: function (result) {
	    ttoverlay.hide();
	    $('#bookingBox').html(result);
	}
    });
}

// when bookingDate changes
function updateBookingChoices(activityPriceId, priceId) {
    //alert('activityPriceId =' + activityPriceId + ' ;priceId =' + priceId);
    //@TODO - check if there are instances of multiple priceID with same data as bookingDate in every activityPriceID
    var myData = {
	"packageId": $('#packageId').val(),
	"tourCode": $('#tourCode').val(),
	"activityPriceId": activityPriceId,
	"priceId": priceId,
	"bookingDate": $('#bookingDate').val(),
	"currency": $('.currency-item.selected').attr('currency-code-data'),
	"units": []
    };

    //Hide error msg first
    if ($("#bookingErrorMsgBox").is(':visible')) {
	$("#bookingErrorMsgBox").hide();
    }

    //Show the correct container first
    if ($("#" + activityPriceId + "_" + priceId + "_numOfTravelerContainer").is(':hidden')) {
	$(".numOfTravelerContainer").hide();
	$("#" + activityPriceId + "_" + priceId + "_numOfTravelerContainer").show();
    }

    //get the value of each unit inside the container
    var apIdChecked = false;
    $("." + activityPriceId + "_" + priceId + "_bbNumOfTraveler").each(function (i) {
	var numOfTraveler = parseInt($(this).val());


	//The idea of this block is to copy the values of unit
	//So that you will have the correct number of units later on in the php call quoteBookingAction
	$(".bbActivityPriceId").each(function (j) {
	    var tmpString = $(this).attr('id');
	    var tmpVar = tmpString.split('_');
	    var tmpActivityPriceId = tmpVar[0];
	    var tmpPriceId = tmpVar[1];

	    //alert(tmpActivityPriceId + ' == ' + tmpPriceId);
	    if (tmpPriceId !== priceId) {
		//alert(tmpActivityPriceId + ' apIdChecked ' + activityPriceId);
		var tmpUnitId = $("input[name='" + tmpActivityPriceId + "_" + tmpPriceId + "_unitId[]']")[i].value;
		$("#" + tmpUnitId).val(numOfTraveler);
	    }
	});

	if (numOfTraveler) {
	    //activityPriceID checker if we have a valid number of travelers
	    apIdChecked = true;
	}
    });

    //set priceId
    $("#priceId").val(priceId);

    //alert('activityPriceId =' + activityPriceId + "_radioButton" + ' ;apIdChecked =' + apIdChecked);
    // Enable radio button
    if (apIdChecked) {
	$("#" + activityPriceId + "_" + priceId + "_radio").prop("checked", true);
    } else {
	$("#" + activityPriceId + "_" + priceId + "_radio").prop("checked", false);
	return false;
    }

    showVerifyButton();
}

function getQuotation(e) {
    ttoverlay.show();
    var activityPriceId = $("input[name='activityPriceId']:checked").val();
    var thisPriceId = $('#priceId').val();
    if (!activityPriceId) {
	$('#bookingErrorMsg').html('Please provide number of traveler(s) and select an option.');
	$('#bookingErrorMsgBox').show();
	return false;
    }

    //Declare this here cause you need to add units
    var myData = {
	"packageId": $('#packageId').val(),
	"tourCode": $('#tourCode').val(),
	"activityPriceId": activityPriceId,
	"priceId": thisPriceId,
	"bookingDate": $('#bookingDate').val(),
	"currency": $('.currency-item.selected').attr('currency-code-data'),
	"units": []
    };

    var requiredUnits = [];
    var adultInBooking = false;
    $("." + activityPriceId + "_" + thisPriceId + "_bbNumOfTraveler").each(function (i) {
	var unitValue = $(this).val();
	var requiredOtherUnits = $("input[name='" + activityPriceId + "_" + thisPriceId + "_requiredOtherUnits[]']")[i].value;
	var unitLabel = $("input[name='" + activityPriceId + "_" + thisPriceId + "_unitLabel[]']")[i].value;

	//Add the units
	var tmpUnitId = $("input[name='" + activityPriceId + "_" + thisPriceId + "_unitId[]']")[i].value;
	myData["units"].push({"unitId": tmpUnitId, "qty": unitValue, "unitLabel": unitLabel});

	//This block will identify if you have correct number of passenger allowed to take the tour
	if (requiredOtherUnits == 'N') {
	    if (unitValue > 0) {
		//meaning there is an adult in the list of travelers
		adultInBooking = true;
	    } else {
		requiredUnits.push(unitLabel);
	    }
	}
    });

    //Show error msg if there is no adult in the booking
    if (!adultInBooking) {
	$('#bookingErrorMsg').html('Must be accompanied by ' + requiredUnits.join(' / '));
	$('#bookingErrorMsgBox').show();
	return false;
    }

    var path = $('#quoteBookingPath').val();
    $.ajax({
	url: path,
	type: 'POST',
	data: myData,
	success: function (result) {
	    ttoverlay.hide();
	    if (typeof result.total !== 'undefined') {
		$('#bookingPrice').html(result.totalFormatted);
		$('#bookingTotalConverted').attr('data-price', result.totalConverted);
		$('#totalPrice').val(result.total);
		$('#numOfPassenger').val(result.numOfPassengers);

		//first remove all appended quoteIds if there is any
		if ($(".hiddenQuoteIds").length) {
		    $(".hiddenQuoteIds").remove();
		}

		//appended new quoteIds
		var quoteIdText = '';
		$.each(result.quoteId, function (key, value) {
		    quoteIdText += '<input type="hidden" name="bookingQuoteId[]" value="' + value + '" class="hiddenQuoteIds" />';
		});
		$('#bbSavedFields').append(quoteIdText);

		$('#bbVerifyPriceButton').hide();
		if ($("#bbVerifyPriceButton").is(':hidden')) {
		    $('#bbBuyNowButton').show();
		}
	    } else {
		$('#bookingErrorMsg').html(result.errorMessage);
		$('#bookingErrorMsgBox').show();
	    }
	}
    });
}

function showVerifyButton() {
    $('#bbBuyNowButton').hide();
    if ($("#bbBuyNowButton").is(':hidden')) {
	$('#bbVerifyPriceButton').show();
    }
}

function toggleReviewList() {
    listCnt = $(".reviewList").size();
    if (listCnt <= 3) {
	start = listCnt;
	$("#loadMoreBtn").hide();
    } else {
	start = 3;
    }

    $(".reviewList").slice(0, start).show();
    $('.loadmore_dat').click(function () {
	start = (start + 3 <= listCnt) ? start + 3 : listCnt;
	$(".reviewList").slice(0, start).show();
	if (start == listCnt) {
	    $("#loadMoreBtn").hide();
	}
    });
}