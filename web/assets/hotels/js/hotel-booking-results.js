'use strict';

var filterObject = {
    price_per_nights: [],
    budget_range: [],
    hotel_class: [],
    districts: [],
    distances: [],
    groups: [],
    maxDistance: 4,
    maxPrice: 800,
    sortOrder: '',
    sortBy: '',
    currentPage: 1,
    sliders: {
	distances: [],
	price_per_nights: []
    }
};

var otherArray = [];
var myStopsArray = [];
var myPricesArray = [];

$(document).ready(function () {
    initHotelConstants();

    if ($('[name=fromDate]').val() == '' || $('[name=toDate]').val() == '') {
	showErrorMsg(Translator.trans('Select check-in and check-out dates.'));
    }

    $(document).on('change', '.form-check-input', function () {
	initFormCheckInput(this);
	getAllCheckboxes();
	loadHotels(false, 1);
    });

    var toCurrency = $('.currency-item.selected').attr('currency-code-data');
    if (toCurrency !== 'USD') {
	changeSiteCurrency('USD', toCurrency, '.filter-options .price-convert');
    }

    initSortEvents();
    if ($('.error-label').html() == '') {
	loadHotels(true, 1);
    } else {
	$('.error-label').show();
	hideHotelOverlay();
    }

    changeCurrency();

    $(document).on('click', '.update_search_button', function () {
	$(".update_search_container").slideToggle('fast');
	$(".hotel_search_input_container").slideToggle('fast');
    });
    $(document).on('click', '.close_container', function () {
	$(".update_search_container").slideToggle('fast');
	$(".hotel_search_input_container").slideToggle('fast');

    });

    $(document).on('click', '.flag_lang', function () {
	var urlParams = new URLSearchParams(window.location.search);
	var newUrl = $(this).attr('href')+'?'+urlParams.toString();
	window.location = newUrl;
	return false;
    });
});

function changeCurrency() {
    $(".currency-item").click(function () {
	var currentMax = filterObject.maxPrice;

	var checkExist = setInterval(function () {
	    if ($('.price-slider-maxPrice').parent('.price-convert').attr('data-price') != currentMax) {
		clearInterval(checkExist);

		var newMaxPrice = Math.ceil($('.price-slider-maxPrice').parent('.price-convert').attr('data-price'));
		$('.price-slider-maxPrice').parent('.price-convert').attr('data-price', newMaxPrice);
		$('.price-slider-maxPrice').html(newMaxPrice);
		filterObject.maxPrice = newMaxPrice;

		var value = [];
		if (filterObject.price_per_nights.length) {
		    value = [
			filterObject.price_per_nights[0],
			filterObject.price_per_nights[(filterObject.price_per_nights.length - 1)]
		    ];
		}

		var newMinValue = Math.ceil($('.price-slider-minValue').parent('.price-convert').attr('data-price'));
		$('.price-slider-minValue').parent('.price-convert').attr('data-price', newMinValue);
		$('.price-slider-minValue').html(newMinValue);

		if (currentMax == value[1]) {
		    myPricesArray = [newMinValue, newMaxPrice];
		} else {
		    var newMaxValue = Math.ceil($('.price-slider-maxValue').parent('.price-convert').attr('data-price'));
		    myPricesArray = [newMinValue, newMaxValue];
		}

		updatePriceFilterValue(myPricesArray);
		filterObject.price_per_nights = myPricesArray;

		initBudgetSlider();
	    }

	    $('.price-convert').each(function () {
		var price = parseFloat($(this).attr('data-price'));
		var floorPrice = Math.floor(price);
		$('.price-convert-text', this).html(floorPrice.toLocaleString('en-US', {
		    maximumFractionDigits: 0
		}));
	    });
	}, 100); // check every 100ms
    });
}

function getAllCheckboxes() {
    filterObject = getAllCheckboxesValues();
}

function getAllCheckboxesValues() {
    filterObject.price_per_nights = [];
    filterObject.budget_range = [];
    filterObject.hotel_class = [];
    filterObject.districts = [];
    filterObject.distances = [];
    filterObject.groups = [];

    otherArray = [];

    $('.form-check-input').each(function () {
	var $this = $(this);
	var thisDataValue = $this.attr("data-value");
	var thisValue = $this.val();
	if ($this.is(':checked')) {
	    if (thisDataValue === 'hotel_class') {
		thisValue = parseInt(thisValue);
		if (filterObject.hotel_class.indexOf(thisValue) == -1) {
		    filterObject.hotel_class.push(thisValue);
		}
	    } else if (thisDataValue === 'district') {
		if (filterObject.districts.indexOf(thisValue) == -1) {
		    filterObject.districts.push(thisValue);
		}
	    } else if (thisDataValue === 'other') {
		if (filterObject.groups.indexOf(thisValue) == -1) {
		    filterObject.groups.push(thisValue);
		}
	    } else if (thisDataValue === 'budget_range') {
		if (filterObject.budget_range.indexOf(thisValue) == -1) {
		    filterObject.budget_range.push(thisValue);
		}
	    }
	}
    });

    filterObject.distances = myStopsArray;
    filterObject.price_per_nights = myPricesArray;

    return filterObject;
}

function getSlidersValues(myTarget, myLowValue, myHighValue) {
    var myArray = [];
    if (myTarget === 'distanceslider') {
	for (var i = myLowValue; i <= myHighValue; i++) {
	    myArray.push(parseInt(i));
	}
    } else if (myTarget === 'budgetslider') {
	for (var j = myLowValue; j <= myHighValue; j += 50) {
	    myArray.push(parseInt(j));
	}
    }
    $(document).find('.' + myTarget).val(myLowValue + ',' + myHighValue);
    $(document).find('.' + myTarget).attr('data-value', myLowValue + ',' + myHighValue);
    return myArray;
}

function initBudgetSlider() {
    $('.filter-price_range').removeClass('hidden');

    // destroy existing sliders
    for (var item in filterObject.sliders.price_per_nights) {
	var slider = filterObject.sliders.price_per_nights[item];
	try {
	    slider.destroy();
	} catch (ex) {
	}
    }

    filterObject.sliders.price_per_nights = [];

    $(".budgetslider").each(function () {
	var $this = $(this);
	var myID = $this.attr("id");
	var $low = $this.parent('div').find('.low').attr("id");
	var $high = $this.parent('div').find('.high').attr("id");

	var min = 0;
	var max = filterObject.maxPrice;
	var start = min;
	var end = max;

	if (filterObject.price_per_nights.length) {
	    start = filterObject.price_per_nights[0];
	    end = filterObject.price_per_nights[(filterObject.price_per_nights.length - 1)];
	}

	initRangeSlider('budgetslider', myID, min, max, start, end, $low, $high, 1);
    });
}

function initFormCheckInput(s) {
    var $this = $(s);
    var thisDataValue = $this.attr("data-value");
    var thisValue = $this.val();

    var slctr = '';
    switch (thisDataValue) {
	case 'hotel_class':
	    slctr = '.filter-hotel-star-' + thisValue;
	    break;
	case 'district':
	    slctr = '.filter-hotel-district-' + thisValue.replace(' ', '');
	    break;
	case 'budget_range':
	    slctr = '.filter-hotel-budget-' + thisValue.replace('+', 'plus');
	    break;
	case 'other':
	    switch (thisValue) {
		case 'has_360':
		    slctr = '.filter-hotel-360';
		    break;
		case 'free_cancelation':
		    slctr = '.filter-hotel-cancelable';
		    break;
		case 'breakfast_included':
		    slctr = '.filter-hotel-breakfast';
		    break;
	    }
	    break;
    }

    $(slctr).each(function () {
	this.checked = s.checked;
    });
}

function initPaginationEvents(pageCount) {
    $(document).off('click', '.pagination-controls li');
    $(document).on('click', '.pagination-controls li', function () {
	var page = parseInt(filterObject.currentPage);

	if ($(this).hasClass('first_pg')) {
	    page = 1;
	} else if ($(this).hasClass('prev_pg')) {
	    page = (page > 1) ? (page - 1) : 1;
	} else if ($(this).hasClass('next_pg')) {
	    page = (page < pageCount) ? (page + 1) : pageCount;
	} else if ($(this).hasClass('last_pg')) {
	    page = pageCount;
	} else {
	    page = $(this).data('page');
	}

	loadHotels(false, page);
    });
}

function initRangeSlider(myClass, myID, minValue, maxValue, startValue, endValue, myLow, myHigh, mySTep) {
    var mySlider = new Slider("#" + myID, {
	min: minValue,
	max: maxValue,
	step: mySTep,
	value: [startValue, endValue],
	labelledby: [myLow, myHigh]
    });

    var windowWidth = $(window).width();
    var myParent = ".left_panel";
    if (windowWidth < 992) {
	myParent = "#bottomNavBar";
    }

    mySlider.on("slideStop", function (sliderValue) {

	var myLowValue = sliderValue[0];
	var myHighValue = sliderValue[1];

	if (myClass === 'distanceslider') {
	    myStopsArray = [myLowValue, myHighValue];
	} else if (myClass === 'budgetslider') {
	    myPricesArray = [myLowValue, myHighValue];
	}

	getAllCheckboxes();
	loadHotels(false, 1);
    });

    if (myClass === 'distanceslider') {
	filterObject.sliders.distances.push(mySlider);
    } else if (myClass === 'budgetslider') {
	filterObject.sliders.price_per_nights.push(mySlider);
    }
}

function initSliders() {
    $('.filter-distance_range').removeClass('hidden');

    // destroy existing sliders
    for (var sliderType in filterObject.sliders) {
	for (var item in filterObject.sliders[sliderType]) {
	    var slider = filterObject.sliders[sliderType][item];
	    try {
		slider.destroy();
	    } catch (ex) {
	    }
	}
    }

    filterObject.sliders.distances = [];

    // create sliders
    $(".distanceslider").each(function () {
	var $this = $(this);
	var myID = $this.attr("id");
	var $low = $this.parent('div').find('.low').attr("id");
	var $high = $this.parent('div').find('.high').attr("id");

	var min = 0;
	var max = filterObject.maxDistance;
	var start = min;
	var end = max;

	if (filterObject.distances.length) {
	    start = filterObject.distances[0];
	    end = filterObject.distances[(filterObject.distances.length - 1)];
	}

	initRangeSlider('distanceslider', myID, min, max, start, end, $low, $high, 1);
    });

    initBudgetSlider();
}

function initSortEvents() {
    $(document).off('click', '.sort_item');
    $(document).on('click', '.sort_item', function () {
	var $this = $(this);
	var sort_item = $this.attr('data-value');

	var clickedMenu = (filterObject.clickedMenu) ? filterObject.clickedMenu.attr('data-value') : null;
	if (clickedMenu == sort_item) {
	    filterObject.clickedMenu = null;
	    return;
	}

	// collapse all
	$('.sort_item').each(function () {
	    var $loop_item = $(this);
	    var loop_sort_item = $loop_item.attr('data-value');
	    var loop_submenu = $loop_item.find('div.sort_submenu');
	    var sort_item_default_text = $loop_item.attr('data-text');

	    if (sort_item != loop_sort_item) {
		$loop_item.removeClass('active');
		$(loop_submenu).addClass('hidden');
		$loop_item.find('.sort_text_hldr').html(sort_item_default_text);
	    }
	});

	// expand/collapse selected sort submenu
	var submenu = $this.find('div.sort_submenu');
	var isCollapsed = $(submenu).hasClass('hidden');

	if (isCollapsed) {
	    $this.addClass('active');
	    $(submenu).removeClass('hidden');
	} else {
	    $this.removeClass('active');
	    $(submenu).addClass('hidden');
	}
    });

    $(document).off('click', '.sort_submenu li');
    $(document).on('click', '.sort_submenu li', function () {
	var $this = $(this);

	filterObject.sortBy = $this.attr('data-sortBy');
	filterObject.sortOrder = $this.attr('data-sortOrder');

	var sort_item = $this.parent().parent().parent();
	var sort_text_hldr = $(sort_item).find('.sort_text_hldr');
	$(sort_text_hldr).html($this.html());

	// collapse all
	$('.sort_item').removeClass('active');
	$('.sort_submenu').addClass('hidden');

	loadHotels(false, 1);
	filterObject.clickedMenu = sort_item;
    });
}

function loadHotels(newAjaxSearch, page) {
    showHotelOverlay('', 'listing');

    $('.error-block').addClass('hidden');
    $('#hotel-list').html('');
    $('.hotel-paginationdata').html('');
    $('.total-hotel').html(0);

    var input = {};

    input['newAjaxSearch'] = 0;
    if (newAjaxSearch === true) {
	// resetPage();
	input['newAjaxSearch'] = 1;
    }

    $("#hotelform .inputData").each(function () {
	input[$(this).attr("name")] = $(this).val();
    });

    // solve issue on our stars filter if stars exist as a url query string
    if (input['fromDate'] && input['toDate']) {
	delete(input['stars']);
    }

    // initialize our filter Object
    getAllCheckboxes();

    input['nbrStars'] = '';
    if (filterObject.hotel_class.length) {
	input['nbrStars'] = filterObject.hotel_class;
    }

    input['district'] = '';
    if (filterObject.districts.length) {
	input['district'] = filterObject.districts;
    }

    input['isCancelable'] = ($('.filter-hotel-cancelable:checked').length) ? 1 : 0;
    input['hasBreakfast'] = ($('.filter-hotel-breakfast:checked').length) ? 1 : 0;
    input['has360'] = ($('.filter-hotel-360:checked').length) ? 1 : 0;

    input['budgetRange'] = "";
    if (filterObject.budget_range.length) {
	input['budgetRange'] = filterObject.budget_range;
    }

    input['priceRange'] = "";
    if (filterObject.price_per_nights.length) {
	input['priceRange'] = [
	    filterObject.price_per_nights[0],
	    filterObject.price_per_nights[(filterObject.price_per_nights.length - 1)]
	];
    }

    input['distanceRange'] = "";
    if (filterObject.distances.length) {
	input['distanceRange'] = [
	    filterObject.distances[0],
	    filterObject.distances[(filterObject.distances.length - 1)]
	];
    }

    input['singleRooms'] = parseInt((input['singleRooms']) ? input['singleRooms'] : 0);
    input['doubleRooms'] = parseInt((input['doubleRooms']) ? input['doubleRooms'] : 0);
    input['childCount'] = parseInt((input['childCount']) ? input['childCount'] : 0);
    input['maxDistance'] = parseInt(($('.filter-max-distance').val()) ? $('.filter-max-distance').val() : 0);
    input['maxPrice'] = parseInt(($('.filter-max-price').val()) ? $('.filter-max-price').val() : 0);

    input['sortBy'] = filterObject.sortBy;
    input['sortOrder'] = filterObject.sortOrder;
    input['page'] = page;
    input['selectedCurrency'] = $('.currency-item.selected').attr('currency-code-data');

    var url = generateLangURL(window.hotel_route_path_hotel_avail);
    $.ajax({
	url: url,
	data: input,
	type: 'post',
	dataType: "json",
	success: function (jsonData) {
	    if (jsonData.error != null) {
		// will check if we have prior results during pagination (for non HRS)
		if (page > 1 && !window.hotels.pageSrc.hrs) {
		    // don't move to next page and just view previous page
		    page -= 1;
		    loadHotels(false, page);
		} else {
		    var msg = jsonData.error;
		    $('.error-block').removeClass('hidden');
		    $('.error-label').html(msg);
		    $('.error-label').show();
		}
	    } else {
		$('.error-block').addClass('hidden');
		$('#hotel-list').html(jsonData.mainLoop);
		$('.hotel-paginationdata').html(jsonData.pagination);
		$('.total-hotel').html(jsonData.hotelCount);

		filterObject.currentPage = jsonData.selectedPage;
		updatePaginationControls(jsonData.selectedPage, jsonData.pageCount, jsonData.maxPageToDisplay);
		initPaginationEvents(jsonData.pageCount);

		var maxPrice = Math.ceil(jsonData.maxPrice);
		var maxDistance = Math.ceil(jsonData.maxDistance);

		filterObject.maxPrice = maxPrice;
		filterObject.maxDistance = maxDistance;

		$('.filter-max-price').val(maxPrice);
		$('.filter-max-distance').val(maxDistance);

		var checkExist = setInterval(function () {
		    if ($('.price-slider .price-convert-text').length >= 3) {
			clearInterval(checkExist);
			$('.price-slider-maxPrice').parent('.price-convert').attr('data-price', maxPrice);
			$('.price-slider-maxPrice').html(maxPrice);
			if (filterObject.price_per_nights.length) {
			    updatePriceFilterValue(input['priceRange']);
			} else {
			    updatePriceFilterValue([0, maxPrice]);
			}
		    }
		}, 100); // check every 100ms

		initDisplayRating();
		initSliders();
		initShowOnMap();
		initAddToBag();
	    }
	},
	error: function (XMLHttpRequest, textStatus, errorThrown) {
	    $('.error-block').removeClass('hidden');
	    $('.error-label').html(errorThrown);
	    $('.error-label').show();
	}
    });

    $(document).ajaxStop(function () {
	hideHotelOverlay();
    });
}

function resetPage() {
    $('.filter-hotel-star').attr('checked', false);
    $('.filter-hotel-district').attr('checked', false);
    $('.filter-hotel-cancelable').attr('checked', false);
    $('.filter-hotel-breakfast').attr('checked', false);
    $('.filter-hotel-360').attr('checked', false);

    filterObject = {
	price_per_nights: [],
	budget_range: [],
	hotel_class: [],
	districts: [],
	distances: [],
	groups: [],
	maxDistance: 4,
	maxPrice: 800,
	sortOrder: '',
	sortBy: '',
	sliders: {
	    distances: [],
	    price_per_nights: []
	}
    };
}

function updatePaginationControls(page, pageCount, maxPage) {
    page = parseInt(page);
    pageCount = parseInt(pageCount);
    maxPage = parseInt(maxPage);

    // Determine selected page
    var min = 1;
    var max = 1;
    var middlePage = Math.floor(maxPage / 2);
    // Update pagination controls on whether the same page numbers should be displayed or not
    // eg: when selecting page 10, since we only display 5 page numbers, the controls should be 8 9 10 11 12

    if (page <= middlePage || pageCount < maxPage) {
	// If page selected is on the first half or if number of pages is less than the maximum page numbers to be displayed, lets show the first pages
	min = 1;
	max = (pageCount < maxPage) ? pageCount : maxPage;

    } else if (page > middlePage && page < (pageCount - middlePage)) {
	// If page selected is somewhere in the middle, lets make it the center of page options
	min = +page - +middlePage;
	max = +page + +middlePage;

    } else {
	// If page selected is on the last half, show the last pages
	min = +pageCount - +maxPage + 1;
	max = pageCount;
    }

    // Display the appropriate page options
    $('li.page').addClass('hidden');
    for (var i = min; i <= max; i++) {
	$('li.page-' + i).removeClass('hidden');
    }

    $('span.separator').removeClass('hidden');
    $('li.page-' + max + ' span.separator').addClass('hidden');

    // Highlight selected page
    $('.pagination-controls li').removeClass('active');
    $('.page-' + page).addClass('active');
}

function updatePriceFilterValue(value) {
    $('.price-slider-minValue').parent('.price-convert').attr('data-price', value[0]);
    $('.price-slider-minValue').html(value[0]);

    $('.price-slider-maxValue').parent('.price-convert').attr('data-price', value[1]);
    $('.price-slider-maxValue').html(value[1]);
}
