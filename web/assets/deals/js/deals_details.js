var shareFadeoutTimeout;
var TO_CAL;
var FROM_CAL;
var TO_CALMin;
var DATE_INFO = {};
var HRS_MAX_ROOMS = 9;

$(document).ready(function () {
    updateSelectBoxValues();
    prepareSliders();
    $(".btnpopup").fancybox();
    init360Thumbs();

    if ($("#readmoreHolder").length > 0) {
	$("#readmoreHolder").readmore({
	    speed: 75,
	    maxHeight: 100,
	    collapsedHeight: 210,
	    moreLink: '<a href="#">More ></a>',
	    lessLink: '<a href="#">< Less</a>'
	});
    }
    $(document).on('click', '.btn-group label', function () {
	$(".btn-group label").removeClass('active');
	$(this).addClass('active');
    });
    $('.accordion').each(function () {
	var $this = $(this);
	$this.unbind('click');
	$this.click(function () {
	    if ($this.hasClass('active') === false) {
		$('.accordion').removeClass('active');
		$('.panel').hide();
		$this.addClass('active');
		$this.next('div.panel').slideToggle();
	    }
	});
    });

    if ($('.accordion').length === 2) {
	$('.accordion').css('cursor', 'default');
    }

    var mySlickSlider = $('.simpleSlider').slick({
	dots: false,
	infinite: false,
	speed: 300,
	slidesToShow: 1,
	slidesToScroll: 1,
	centerMode: false,
	variableWidth: false,
	arrows: false,
	lazyLoad: 'ondemand',
    });
    $(document).on('click', '.img_parent_holder .img_holder', function () {
	var $this = $(this);
	var thisIndex = $this.index();
	$('.img_parent_holder .img_holder').removeClass('active');
	$this.addClass('active');
	//console.log(thisIndex);
	//mySlickSlider.slickGoTo(thisIndex);
	mySlickSlider.slick('slickGoTo', thisIndex);
    });

    $('.image-thumb-section').removeClass('displaynone');
    if ($(".hotel_infos_imgbig").length > 0) {
	$(".hotel_infos_imgbig").fancybox({
	    helpers: {
		overlay: {closeClick: true}
	    }
	});
    }

    $(document).ajaxComplete(function () {
	$(".rates-info").each(function () {
	    $(this).parent().find("a[rel~=tooltip]").data('title', $(this).html());
	});
    });

    //booking date
    $('#bookingDate').daterangepicker({
	singleDatePicker: true,
	autoApply: true,
	autoUpdateInput: false,
	opens: 'left',
	minDate: getTodatDate(),
	locale: {cancelLabel: 'Clear'}
    });

    //when changes are applied
    $('#bookingDate').on('apply.daterangepicker', function (ev, picker) {
	var bookingDate = picker.startDate.format('YYYY-MM-DD');
	$(this).val(bookingDate);
	getBookingBox();
    });

    // for maps
    $('.cMapsLabel').click(function () {
	var cMapId = $(this).attr('id');

	$('.cMaps').hide();
	$('#' + cMapId + 'Map').show();
	$('#' + cMapId + 'Text').show();
	$('.cMapsLabel').removeClass('active_check');
	$(this).addClass('active_check');
    });

    // for schedules(web) section
    $('.swTime').click(function () {
	var swTimeId = $(this).attr('id');
	var swTmpVar = swTimeId.split('_');

	$('.swTimeBox').hide();
	$('#swTimeBox_' + swTmpVar[1]).show();
	$('.swTime').removeClass('active_date');
	$('#swTime_' + swTmpVar[1]).addClass('active_date');
    });

    // for schedules(popup) section
    $('.spTime').click(function () {
	var spTimeId = $(this).attr('id');
	var spTmpVar = spTimeId.split('_');

	$('.spTimeBox').hide();
	$('.spTime').removeClass('active_date');

	//using class on this cause there seems to be an issue of not being able to see the first popup if you use id attr
	$('.spTimeBox_' + spTmpVar[1]).show();
	$('.spTime_' + spTmpVar[1]).addClass('active_date');
    });

    // for price options
    $('#priceOptionSelect').change(function () {
	$('.priceOptions').hide();
	$('#option_' + $(this).val()).show();
    });

    // for reviews section
    if ($(".reviewList").length) {
	toggleReviewList();
    }
    if ($("#reviewSorting").length) {
	$('#reviewSorting').on('change', function () {
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
		    $('#reviewContainer').html('');
		    var apnd = '';
		    $.each(result, function (i, item) {
			apnd += '<div class="col-xs-12 nopad margin_bottom_20 reviewList">';
			apnd += '<div class="greycontainer_reviews">';
			apnd += '<div class="col-xs-12 nopad">';
			apnd += '<div class="imagecircletxtcont_reviews">';
			apnd += '<p class="textingreycircle_reviews">' + item.rating + '</p>';
			apnd += '</div>';
			apnd += '<p class="nextcircletext_reviews">' + item.owner + ' <span class="wherefromgreytext_reviews">' + Translator.trans('From ') + item.country + ', ' + item.date + '</span></p>';
			apnd += '<div class="col-xs-12 nopad">';
			apnd += '<p class="ingreytext_reviews">' + item.comment + '</p>';
			apnd += '</div>';
			apnd += '</div>';
			apnd += '</div>';
			apnd += '</div>';
		    });
		    apnd += '<div id="loadMoreBtn" class="col-xs-12 nopad padtop20">';
		    apnd += '<div class="loadmore_dat"> load more </div>';
		    apnd += '</div>';
		    $('#reviewContainer').append(apnd);
		    toggleReviewList();
		},
		error: function (error) {
		    alert(Translator.trans('error; ') + eval(error));
		}
	    });
	});
    }
    $(document).on('click', '#bbBuyNowButton', function () {
	showDealsOverlay('dealsContainer');
    });
});

function prepareSliders() {
    if ($('.slider-for').length > 0) {
	$('.slider-for').each(function () {
	    prepareSlider(this);
	});
    }
}
function prepareSlider(target) {
    var unique = $(target).data('unique');
    var sliderFor = '.slider-for-' + unique;
    var nav = '.slider-nav-' + unique;

    if (!$(sliderFor).hasClass('slick-initialized')) {
	var slideglob = $(sliderFor).slick({
	    lazyLoad: 'ondemand',
	    slidesToShow: 1,
	    slidesToScroll: 1,
	    arrows: true,
	    fade: true,
	    centerMode: true
	});

	$(sliderFor).removeClass('opacity0');

	if ($(nav + ' .mediathumb').length > 0) {

	    $(nav + ' .mediathumb').eq(0).addClass('active');
	    $(sliderFor).on('afterChange', function (event, slick, index) {
		$(nav + ' .mediathumb').removeClass('active');
		$(nav + ' .mediathumb').eq(index).addClass('active');
	    });

	    $(nav + ' .mediathumb').click(function (e) {
		var indx = $(this).index();
		slideglob[0].slick.slickGoTo(indx);
		$(nav + ' .mediathumb').removeClass('active');
		$(this).addClass('active');
	    });
	}

	if ($('.swiper-container-' + unique).length > 0) {
	    $('.swiper-container-' + unique).swiper({
		slidesPerView: 'auto',
		centeredSlides: false,
		spaceBetween: 10
	    });
	}
    }
}
function updateSelectBoxValues() {
    $(".currency-item").click(function () {
	var fromCurrency = $('.room-count').attr('data-currency');
	var toCurrency = $(this).attr('currency-code-data');
	changeSiteCurrency(fromCurrency, toCurrency, '.room-count', function () {
	    var optionText = '';
	    $('.room-count').each(function () {
		var price = $(this).attr('data-price');
		$(this).children('option').each(function () {
		    var val = parseInt($(this).val());
		    if (val === 0) {
			optionText = '0';
		    } else {
			var floorPrice = (Math.floor(val * price));
			optionText = val + "   (" + toCurrency + " " + floorPrice.toLocaleString('en-US', {maximumFractionDigits: 0}) + ")";
			optionText = optionText.replace(/ /g, '\u00a0');
		    }
		    $(this).text(optionText);
		});
		$(this).attr('data-currency', toCurrency);
	    });
	});
    });
}

function init360Thumbs() {
    $("div.img_holder_360").click(function () {
	renderTourEngine(this);
    });
    //
    $("div.img_holder_360[ismain=true]").first().click();
}

/*
 * Copied old js
 */
// when bookingDate changes
function getBookingBox() {
    showDealsOverlay("priceOptions");
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
	    hideDealsOverlay();
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
    showDealsOverlay("priceOptions");
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
	    hideDealsOverlay();
	    if (typeof result.total !== 'undefined') {
		$('#topBookingPrice').html(result.totalFormatted);
		$('#topBookingTotalConverted').attr('data-price', result.totalConverted);
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