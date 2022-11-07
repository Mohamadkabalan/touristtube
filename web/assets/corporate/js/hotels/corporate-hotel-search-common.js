var TO_CAL_Hotel;
var FROM_CAL_Hotel;
var TO_CAL_HotelMin;
var DATE_INFO = {};
var MAX_ROOM_COUNT = 9;
var MAX_CHILD_COUNT = 6;
var MORE_OPTIONS = 10;
var MAX_NIGHTS_COUNT = 30;
$(document).ready(function () {
    // For testing geolocation search
//    $("#searchdiscover").change(addAutoCompleteListHotels);

    if ($("#searchdiscover").length) {
	addAutoCompleteListHotels2();
    }

    if ($('.slider-nav-main').length > 0) {
	$('.slider-nav-main').show();
    }

    var today = new Date();
    $('#fromContainerH').dateRangePicker({
	autoClose: true,
	showTopbar: false,
	startDate: today,
	getValue: function () {
	    if ($('#fromDateH').val() && $('#toDateH').val())
		return $('#fromDateH').val() + ' to ' + $('#toDateH').val();
	    else
		return '';
	},
	setValue: function (s, s1, s2) {
	    $('#fromDateH').val(s1);
	    $('#toDateH').val(s2);
	}
    }).bind('datepicker-change', function (event, obj) {
	calculateNightsCount();
    });
    $('#toContainerH').dateRangePicker({
	autoClose: true,
	showTopbar: false,
	startDate: today,
	singleDate: true,
	getValue: function () {
	    if ($('#fromDateH').val() && $('#toDateH').val())
		return $('#fromDateH').val() + ' to ' + $('#toDateH').val();
	    else
		return '';
	},
	setValue: function (s, s1, s2) {
	    if (s2 && s2 != 'Invalid date') {
		$('#toDateH').val(s2);
	    } else {
		$('#toDateH').val(s);
	    }
	}
    }).bind('datepicker-change', function (event, obj) {
	calculateNightsCount();
    });

    //On change of number of single rooms, compute adult count and fill it
    $('#singleRooms').change(calculateAdultCount);

    //On change of number of double rooms, hide/show children option then compute adult count and fill it
    $('#doubleRooms,#counter-2').change(function (event, initFlag) {
	var count = parseInt($(this).val()) || 0;
	//hide child age dropdown fields
	if (count == 0) {
	    $('#guest-option').hide();
	}

	if (initFlag !== false) {
	    calculateAdultCount();
	    if (count == 0) {
		$('#childCount').val(0);
	    }
	}
    });

    //On change of autocalculated adult count based on room selections
    $('#adultCount').change(function () {
	var single = parseInt($('#singleRooms').val()) || 0;
	var double = parseInt($('#doubleRooms').val()) || 0;
	var adultCount = parseInt($('#adultCount').val());
	var maxAdultCount = single + (double * 2);
	var maxAdultWithExtraBed = single + (double * 4)

	if (adultCount > maxAdultCount && (adultCount - maxAdultCount) == 1) {
	    TTAlert({
		msg: Translator.trans('The number of adult guests does not correspond to the number of rooms. Are you sure you want to add extra bed(s)?'),
		type: 'action',
		btn1: Translator.trans('No'),
		btn2: Translator.trans('Yes'),
		btn2Callback: function (data) {
		    if (data == false) {
			$('#adultCount').val(maxAdultCount);
		    }
		}
	    });
	}
    });

    //On change of Children
    $('#childCount').change(function () {
	updateChildCount();
    });

    //On load page, trigger on change event for guests/doubleroom
    init();

    //Onclick Search button
    $(".hotelform").submit(function () {
	var valid = validate();
	var hotelId = $('#hotelId').val();
	var detailspage = $('#detailspage').val();
	var name = $('#hotelCityName').val();

	if (!valid) {
	    return false;
	} else if (hotelId > 0 && detailspage == 1) {
	    var hotelNameURL = getCleanTitle(name);
	    var formPath = generateLangURL('./corporate/hotels/details-' + hotelNameURL + '-' + hotelId, 'corporate');
	    $(this).attr('action', formPath);
	    $(this).attr('method', 'post');
	    return true;
	} else {
	    return true;
	}
    });

    $('#increment-1').on('click', function (e) {
	var myobj = $('#counter-1');
	if ($('#singleRooms').length > 0) {
	    myobj = $('#singleRooms');
	}
	increment1(myobj, 1);
	myobj.trigger('change', true);
    });
    $('#increment-2').on('click', function (e) {
	var myobj = $('#counter-2');
	if ($('#doubleRooms').length > 0) {
	    myobj = $('#doubleRooms');
	}
	increment1(myobj, 2);
	myobj.trigger('change', true);
    });
    $('#increment-3').on('click', function (e) {
	var myobj = $('#counter-3');
	if ($('#adultCount').length > 0) {
	    myobj = $('#adultCount');
	}
	increment1(myobj, 3);
    });
    $('#increment-4').on('click', function (e) {
	var myobj = $('#counter-4');
	if ($('#childCount').length > 0) {
	    myobj = $('#childCount');
	}
	increment1(myobj, 4);
	updateChildCount();
    });
    $('#decrement-1').on('click', function (e) {
	var myobj = $('#counter-1');
	if ($('#singleRooms').length > 0) {
	    myobj = $('#singleRooms');
	}
	decrement1(myobj, 1);
	myobj.trigger('change', true);
    });
    $('#decrement-2').on('click', function (e) {
	var myobj = $('#counter-2');
	if ($('#doubleRooms').length > 0) {
	    myobj = $('#doubleRooms');
	}
	decrement1(myobj, 2);
	myobj.trigger('change', true);
    });
    $('#decrement-3').on('click', function (e) {
	var myobj = $('#counter-3');
	if ($('#adultCount').length > 0) {
	    myobj = $('#adultCount');
	}
	decrement1(myobj, 3);
    });
    $('#decrement-4').on('click', function (e) {
	var myobj = $('#counter-4');
	if ($('#childCount').length > 0) {
	    myobj = $('#childCount');
	}
	decrement1(myobj, 4);
	updateChildCount();
    });
});

function getCleanTitle(title) {
    // Algorithm taken from Utils.php -> cleanTitleData()

    title = title.replace(/'/g, " ");
    title = title.replace(/\r?\n|\r/g, " ");
    title = title.trim();
    title = title.replace(/"/g, " ");
    title = title.replace(/,/g, "+");
    title = title.replace(/\(/g, "+");
    title = title.replace(/\)/g, "+");
    title = title.replace(/\?/g, "+");
    title = title.replace(/#/g, "");
    title = title.replace(/!/g, "+");
    title = title.replace(/\}/g, "+");
    title = title.replace(/\./g, "+");
    title = title.replace(/\//g, "+");
    title = title.replace(/ & /g, '+');
    title = title.replace(/&/g, '+and+');
    title = title.replace(/>/g, "+");
    title = title.replace(/</g, "+");
    title = title.replace(/ /g, '+');
    title = title.replace(/-/g, '+');
    title = title.replace(/%\+/g, "+");
    title = title.replace(/%-/g, "-");
    title = title.replace(/100%/g, "100");
    title = title.replace(/%/g, "+");
    return title;
}

function updateChildCount() {
    var childCount = $('#childCount').val();

    if (childCount == "0") {
	$('#guest-option').hide();
    } else if (parseInt(childCount) > 6) {
	showErrorMsg(Translator.trans("No more than 6 children per reservation is allowed."));
	$('#childCount').val(0);
	$('#guest-option').hide();
    } else {
	$('#guest-option').show();
	for (i = 1; i <= 6; i++) {
	    if (i <= childCount) {
		$('.form-child' + i).show();
	    } else {
		$('.form-child' + i).hide();
	    }
	}
    }
}

function addCalToHotel(cals) {
    if (new Date($('#fromDateHC').val()) >= new Date($('#toDateHC').val())) {
	var tomorrow = new Date((new Date($('#fromDateHC').val())).valueOf() + 24 * 60 * 60 * 1000);
	var toDateH = ('00' + tomorrow.getDate()).slice(-2) + ' / ' + ('00' + (tomorrow.getMonth() + 1)).slice(-2) + ' / ' + tomorrow.getFullYear();
	var toDateHC = tomorrow.getFullYear() + '-' + ('00' + (tomorrow.getMonth() + 1)).slice(-2) + '-' + ('00' + tomorrow.getDate()).slice(-2);
	$('#toDateH').val(toDateH);
	$('#toDateHC').val(toDateHC);
    }
}

function getDateInfoHotel(date, wantsClassName) {
    var as_number = Calendar.dateToInt(date);
    return DATE_INFO[as_number];
}

function onFocusFunHotel() {
    if (TO_CAL_HotelMin) {
	var $selectedminelement = Number(Calendar.printDate(TO_CAL_Hotel.args.min, "%Y%m%d"));
	DATE_INFO = {};
	DATE_INFO[$selectedminelement] = {klass: "firstDayActive"};
	TO_CAL_Hotel.redraw();
    }
}

function addAutoCompleteListHotels() {
    var destination = $("#searchdiscover").val();
    $('#hotelCityName').val(destination);

    if ($.isNumeric(destination)) {
	$('#hotelCityCode').val('');
	$('#hotelId').val(destination);
	$('#latitude').val(0);
	$('#longitude').val(0);
    } else if (destination == 'geolocation') {
	$('#hotelCityCode').val('');
	$('#hotelId').val(0);
	$('#latitude').val('51.682270');
	$('#longitude').val('0.020760');
    } else {
	$('#hotelCityCode').val(destination);
	$('#hotelId').val(0);
	$('#latitude').val(0);
	$('#longitude').val(0);
    }
}

function addAutoCompleteListHotels2(which) {
    var $ccity = $("#searchdiscover");
    $ccity.autocomplete({
	minLength: minSearchLength,
	appendTo: "#searchhrscontainer",
	search: function (event, ui) {
	    $ccity.autocomplete("option", "source", generateLangURL('/ajax/Hotel_search_for_amadeus', 'corporate'));
	},
	select: function (event, ui) {
	    $ccity.val(ui.item.name);
	    $ccity.parent().find('#hotelCityCode').val(ui.item.hotelCityCode);
	    $ccity.parent().find('#cityId').val(ui.item.cityId);
	    $ccity.parent().find('#hotelCityName').val(ui.item.name);
	    $ccity.parent().find('#hotelId').val(ui.item.hotelId);
	    $ccity.parent().find('#entityType').val(ui.item.entityType);
	    $ccity.parent().find('#longitude').val(ui.item.longitude);
	    $ccity.parent().find('#latitude').val(ui.item.latitude);
	    $ccity.parent().find('#country').val(ui.item.country);
	    $ccity.parent().find('#stars').val(0);
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

function calculateAdultCount() {
    var single = parseInt($('#singleRooms').val()) || 0;
    var double = parseInt($('#doubleRooms').val()) || 0;
    var adultCount = single + (double * 2);
    $('#adultCount').val(adultCount);
}

function calculateNightsCount() {
    var dsplit = $('#fromDateH').val().split("-");
    var from = new Date(dsplit[0], dsplit[1] - 1, dsplit[2]);

    dsplit = $('#toDateH').val().split("-");
    var to = new Date(dsplit[0], dsplit[1] - 1, dsplit[2]);

    var nightsCount = Math.round((to - from) / (1000 * 60 * 60 * 24));

    if (nightsCount > MAX_NIGHTS_COUNT) {
	showErrorMsg(Translator.trans('Reservations longer than ' + MAX_NIGHTS_COUNT + ' nights are not possible.'));
    }
    return nightsCount;
}

function init() {
    // On initial booking search, this won't really affect anything
    // But this is critical on result page and other succeeding pages so the hidden fields will be shown on page load
    // We don't really need the adult count value to be changed on load
    $('#searchdiscover').focus();

    //More options and children
    $('#doubleRooms,#counter-2').trigger('change', false);
    if (parseInt($('#childCount').val()) > 0) {
	$('#guest-option').show();
    } else {
	$('#guest-option').hide;
    }
    $('#childCount').trigger('change');
}

function validate() {
    var valid = true;
    var destination = $("#searchdiscover").val();
    var validDestination = $('#hotelCityName').val();
    if (destination == '') {
	showErrorMsg(Translator.trans('Please select your destination.'));
	valid = false;
    } else if (validDestination == '') {
	showErrorMsg(Translator.trans('Please select a City/Hotel that best match your destination.'));
	valid = false;
    }

    var dateFormat = 'YYYY-MM-DD';
    var fromDateH = $('#fromDateH').val();
    var validFromDate = moment(fromDateH, dateFormat, true);
    if (fromDateH == '' || !validFromDate.isValid()) {
	showErrorMsg(Translator.trans('Invalid Check-In date.'));
	valid = false;
    }

    var toDateH = $('#toDateH').val();
    var validToDate = moment(toDateH, dateFormat, true);
    if (toDateH == '' || !validToDate.isValid()) {
	showErrorMsg(Translator.trans('Invalid Check-Out date.'));
	valid = false;
    }

    var currentDate = moment(0, "HH");
    if (valid && (validToDate.isSame(validFromDate) || validToDate < validFromDate || validFromDate < currentDate || validToDate <= currentDate)) {
	showErrorMsg(Translator.trans('Invalid Check-In/Check-Out date.'));
	valid = false;
    }

    var nightsCount = calculateNightsCount();
    if (nightsCount > MAX_NIGHTS_COUNT) {
	valid = false;
    }

    var single = parseInt($('#singleRooms').val()) || 0;
    var double = parseInt($('#doubleRooms').val()) || 0;
    var adultCount = parseInt($('#adultCount').val());
    var childCount = parseInt($('#childCount').val());

    if (single == 0 && double == 0) {
	showErrorMsg(Translator.trans('Please input the number of rooms.'));
	valid = false;
    } else if ((single + double) > MAX_ROOM_COUNT) {
	showErrorMsg(Translator.trans('No more than 9 rooms per reservation is allowed.'));
	valid = false;
    } else {
	if ((adultCount + childCount) < (single + double)) {
	    showErrorMsg(Translator.trans('The number of guests does not correspond to the number of rooms.'));
	    valid = false;
	} else if ((adultCount + childCount) > (single + (double * 4))) {
	    showErrorMsg(Translator.trans('The number of guests does not correspond to the number of rooms.'));
	    valid = false;
	}

	if (childCount > (double * 2)) {
	    showErrorMsg(Translator.trans('No more than 2 children per double room is allowed.'));
	    valid = false;
	}
    }

    if (valid) {
//        $('.upload-overlay-loading-fix').addClass('hotelsLoader');
//        $('.upload-overlay-loading-fix').show();
    }

    return valid;
}

function increment1(myobj, wich) {
    var maxval = 9;
    if (wich == 3) {
	maxval = 36; // max of 9 rooms, assumming 9 double rooms with max 2 extra adults each
    } else if (wich == 4) {
	maxval = 6;
    }
    myobj.val(function (i, oldval) {
	return (++oldval < maxval) ? oldval : maxval;
    });
}

function decrement1(myobj, wich) {
    var minval = 0;
    if (wich == 3)
	minval = 1;
    myobj.val(function (i, oldval) {
	return (--oldval > minval) ? oldval : minval;
    });
}

function showErrorMsg(msg) {
    TTAlert({
	msg: msg,
	type: 'alert',
	btn1: Translator.trans('ok'),
	btn2: '',
	btn2Callback: null
    });
}
