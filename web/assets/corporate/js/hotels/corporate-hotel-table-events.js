var priceSlider = '';
var distanceSlider = '';
var booking_offerspopTimeout;

$(document).ready(function () {
    if ($('[name=fromDate]').val() == '' || $('[name=toDate]').val() == '') {
	showErrorMsg(Translator.trans('Select check-in and check-out dates.'));
    }

    sortHotels();

    var toCurrency = $('.currency-item.selected').attr('currency-code-data');
    if (toCurrency !== 'USD') {
	changeSiteCurrency('USD', toCurrency, '#budget-filter-block .price-convert');
    }

    if ($('.error_cnt_corporate').html() == '') {
	loadHotels(true, 1);
    } else {
	$('.error_cnt_corporate').show();
	$('.hotel-overlay-loading-fix').addClass('hidden');
    }

    $('.downarrow_filter').click(function () {
	setTimeout(function () {
	    if (typeof priceSlider === 'object') {
		priceSlider.relayout();
	    }

	    if (typeof distanceSlider === 'object') {
		distanceSlider.relayout();
	    }
	}, 100);
    });

    changeCurrency();
});

function loadHotels(newAjaxSearch, page) {
    var input = {};
    //input['newAjaxSearch'] = 0;

    if (newAjaxSearch === true) {
	resetPage();
	//input['newAjaxSearch'] = 1;
    }

    $('.hotel-overlay-loading-fix').removeClass('hidden');
    $('.error-block').addClass('hidden');
    $('#hotel-list').html('');
    $('.hotel-paginationdata').html('');
    $('#total-hotel').html(0);

    // Ajax call to load main results
    $(".hotelform .inputData").each(function () {
	input[$(this).attr("name")] = $(this).val();
    });

    input['budgetRange'] = "";
    if ($('.filter-hotel-budget:checked').length) {
	input['budgetRange'] = $('.filter-hotel-budget:checked').map(function () {
	    return $(this).val();
	}).get();
    }

    input['nbrStars'] = "";
    if ($('.filter-hotel-star:checked').length) {
	input['nbrStars'] = $('.filter-hotel-star:checked').map(function () {
	    return parseInt($(this).val());
	}).get();
    }

    input['district'] = "";
    if ($('.filter-hotel-district:checked').length) {
	input['district'] = $('.filter-hotel-district:checked').map(function () {
	    return $(this).val();
	}).get();
    }

    input['isCancelable'] = $('.filter-hotel-cancelable:checked').length;
    input['hasBreakfast'] = $('.filter-hotel-breakfast:checked').length;

    input['priceRange'] = "";
    if (typeof priceSlider === 'object') {
	input['priceRange'] = priceSlider.getValue();
    }

    input['distanceRange'] = "";
    if (typeof distanceSlider === 'object') {
	input['distanceRange'] = distanceSlider.getValue();
    }

    input['maxDistance'] = parseInt($('#maxDistance').html());
    input['maxPrice'] = parseInt($('#maxPrice').html());
    input['sortBy'] = $('#sortBy').val();
    input['sortOrder'] = $('#sortOrder').val();
    input['page'] = page;
    input['selectedCurrency'] = $('.currency-item.selected').attr('currency-code-data');

    $.ajax({
	url: generateLangURL('./corporate/hotels/avail', 'corporate'),
	data: input,
	type: 'post',
	dataType: "json",
	success: function (jsonData) {
	    if (jsonData.error != null) {
		var msg = jsonData.error;
		$('.error_cnt_corporate').html(msg);
		$('.error_cnt_corporate').show();

	    } else {
		$('.error_cnt_corporate').html('');
		$('#hotel-list').html(jsonData.mainLoop);
		$('.hotel-paginationdata').html(jsonData.pagination);
		$('#total-hotel').html(jsonData.hotelCount);

		updatePaginationControls(jsonData.selectedPage, jsonData.pageCount, jsonData.maxPageToDisplay);
		paginateHotels(jsonData.pageCount);

		var maxPrice = Math.ceil(jsonData.maxPrice);
		var maxDistance = Math.ceil(jsonData.maxDistance);

		if (input['maxDistance'] == 0) {
		    $('#maxDistance').html(maxDistance);
		}

		if (newAjaxSearch === true || jsonData.searchRequestId == 0) {
		    resetPage();
		    filterHotels(maxPrice, maxDistance);

		    if (maxDistance > 0) {
			$('.sort-hotel-distance').removeClass('hidden');
		    }

		    var checkExist = setInterval(function () {
			if ($('#price-slider .price-convert-text').length == 3) {
			    clearInterval(checkExist);
			    $('#maxPrice').parent('.price-convert').attr('data-price', maxPrice);
			    $('#maxPrice').html(maxPrice);

			    if (typeof priceSlider === 'object') {
				value = priceSlider.getValue();
				updatePriceFilterValue(value);
			    }
			}
		    }, 100); // check every 100ms
		}
		initShowOnMap();
		initAddToBag();
	    }

	    if ($(".booking_offerspop").length > 0) {
		booking_offerspopTimeout = setTimeout(function () {
		    clearTimeout(booking_offerspopTimeout);
		    $(".booking_offerspop").click();
		    $(".booking_offerspop").remove();
		}, 1000);
	    }
	},
	error: function (XMLHttpRequest, textStatus, errorThrown) {
	    $('.error_cnt_corporate').html(errorThrown);
	    $('.error_cnt_corporate').show();
	}
    });

    $(document).ajaxStop(function () {
	$('.hotel-overlay-loading-fix').addClass('hidden');
    });
}

function changeCurrency() {
    $(".currency-item").click(function () {
	currentMax = priceSlider.getAttribute('max');

	var checkExist = setInterval(function () {
	    if ($('#maxPrice').parent('.price-convert').attr('data-price') != currentMax) {
		clearInterval(checkExist);

		newMaxPrice = Math.ceil($('#maxPrice').parent('.price-convert').attr('data-price'));
		$('#maxPrice').parent('.price-convert').attr('data-price', newMaxPrice);
		$('#maxPrice').html(newMaxPrice);

		value = priceSlider.getValue();

		newMinValue = Math.ceil($('#minValue').parent('.price-convert').attr('data-price'));
		$('#minValue').parent('.price-convert').attr('data-price', newMinValue);
		$('#minValue').html(newMinValue);

		if (currentMax == value[1]) {
		    priceSlider.setAttribute('value', [newMinValue, newMaxPrice]);

		    updatePriceFilterValue([newMinValue, newMaxPrice]);
		} else {
		    newMaxValue = Math.ceil($('#maxValue').parent('.price-convert').attr('data-price'));

		    priceSlider.setAttribute('value', [newMinValue, newMaxValue]);

		    updatePriceFilterValue([newMinValue, newMaxValue]);
		}
		priceSlider.setAttribute('max', newMaxPrice);
		priceSlider.refresh();
		priceSlider.relayout();

		addPriceFilterEvent();
	    }
	}, 100); // check every 100ms
    });
}

function updatePriceFilterValue(value) {
    $('#minValue').parent('.price-convert').attr('data-price', value[0]);
    $('#minValue').html(value[0]);

    $('#maxValue').parent('.price-convert').attr('data-price', value[1]);
    $('#maxValue').html(value[1]);
}

function addPriceFilterEvent() {
    priceSlider.on('slideStop', function (ev) {
	value = priceSlider.getValue();
	updatePriceFilterValue(value);
	loadHotels(false, 1);
    });
}

function resetPage() {
    $('#sortBy').val('');
    $('#sortOrder').val('');
    $('.sort-hotel-star-submenu').hide();
    $('.sort-hotel-price-submenu').hide();
    $('.sort-hotel-distance-submenu').hide();
    $('.sort-hotel-distance').addClass('hidden');
    $('.sort-hotel-star').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Stars'));
    $('.sort-hotel-price').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Price'));
    $('.sort-hotel-distance').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Distance'));
    $('.sort-hotel-review').removeClass('sort-icon');

    $('.filter-hotel-budget').attr('checked', false);
    $('.filter-hotel-star').attr('checked', false);
    $('.filter-hotel-cancelable').attr('checked', false);
    $('.filter-hotel-breakfast').attr('checked', false);
    $('.filter-hotel-district').attr('checked', false);

    $('#price-filter-block').addClass('hidden');
    $('#price-slider').find('.slider').remove();
    priceSlider = '';

    $('#distance-filter-block').addClass('hidden');
    $('#distance-slider').find('.slider').remove();
    distanceSlider = '';
}

function sortHotels() {
    // Star Onclick
    $('.sort-hotel-star').on('click', function (e) {
	e.preventDefault();
	$('.sort-hotel-star-submenu').toggle();
	$('.sort-hotel-price-submenu').hide();
	$('.sort-hotel-distance-submenu').hide();
	return false;
    });

    // Star Asc
    $('.sort-hotel-star-asc').on('click', function (e) {
	selectedSort('category', 'asc');
    });

    // Star Desc
    $('.sort-hotel-star-desc').on('click', function (e) {
	selectedSort('category', 'desc');
    });

    // Price Onclick
    $('.sort-hotel-price').on('click', function (e) {
	e.preventDefault();
	$('.sort-hotel-star-submenu').hide();
	$('.sort-hotel-distance-submenu').hide();
	$('.sort-hotel-price-submenu').toggle();
	return false;
    });

    // Price Asc
    $('.sort-hotel-price-asc').on('click', function (e) {
	selectedSort('price', 'asc');
    });

    // Price Desc
    $('.sort-hotel-price-desc').on('click', function (e) {
	selectedSort('price', 'desc');
    });

    // Distance Onclick
    $('.sort-hotel-distance').on('click', function (e) {
	e.preventDefault();
	$('.sort-hotel-star-submenu').hide();
	$('.sort-hotel-price-submenu').hide();
	$('.sort-hotel-distance-submenu').toggle();
	return false;
    });

    // Distance Asc
    $('.sort-hotel-distance-asc').on('click', function (e) {
	selectedSort('distance', 'asc');
    });

    // Distance Desc
    $('.sort-hotel-distance-desc').on('click', function (e) {
	selectedSort('distance', 'desc');
    });

    // Review Onclick
    $('.sort-hotel-review').on('click', function (e) {
	selectedSort('review', '');
	e.preventDefault();
	$('.sort-hotel-star-submenu').hide();
	$('.sort-hotel-price-submenu').hide();
	$('.sort-hotel-distance-submenu').hide();
	return false;
    });
}

function selectedSort(sortBy, sortOrder) {
    switch (sortBy) {
	case 'category':
	    var sort = $('.sort-hotel-star');
	    sort.addClass('sort-icon');
	    if (sortOrder == 'asc') {
		sort.find('.sort-title').html($('.sort-hotel-star-asc').html());
	    } else {
		sort.find('.sort-title').html($('.sort-hotel-star-desc').html());
	    }

	    $('.sort-hotel-price').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Price'));
	    $('.sort-hotel-distance').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Distance'));
	    $('.sort-hotel-review').removeClass('sort-icon');
	    break;
	case 'price':
	    var sort = $('.sort-hotel-price');
	    sort.addClass('sort-icon');
	    if (sortOrder == 'asc') {
		sort.find('.sort-title').html($('.sort-hotel-price-asc').html());
	    } else {
		sort.find('.sort-title').html($('.sort-hotel-price-desc').html());
	    }

	    $('.sort-hotel-star').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Stars'));
	    $('.sort-hotel-distance').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Distance'));
	    $('.sort-hotel-review').removeClass('sort-icon');
	    break;
	case 'distance':
	    var sort = $('.sort-hotel-distance');
	    sort.addClass('sort-icon');
	    if (sortOrder == 'asc') {
		sort.find('.sort-title').html($('.sort-hotel-distance-asc').html());
	    } else {
		sort.find('.sort-title').html($('.sort-hotel-distance-desc').html());
	    }

	    $('.sort-hotel-star').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Stars'));
	    $('.sort-hotel-price').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Price'));
	    $('.sort-hotel-review').removeClass('sort-icon');
	    break;
	case 'review':
	    $('.sort-hotel-review').addClass('sort-icon');
	    $('.sort-hotel-star').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Stars'));
	    $('.sort-hotel-price').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Price'));
	    $('.sort-hotel-distance').removeClass('sort-icon').find('.sort-title').html(Translator.trans('Distance'));
	    break;
    }

    $('#sortBy').val(sortBy);
    $('#sortOrder').val(sortOrder);
    loadHotels(false, 1);
}

function filterHotels(maxPrice, maxDistance) {
    $('.filter-hotel-budget').change(function () {
	loadHotels(false, 1);
    });

    $('.filter-hotel-star').change(function () {
	loadHotels(false, 1);
    });

    $('.filter-hotel-cancelable').change(function () {
	loadHotels(false, 1);
    });

    $('.filter-hotel-breakfast').change(function () {
	loadHotels(false, 1);
    });

    $('.filter-hotel-district').change(function () {
	loadHotels(false, 1);
    });

    maxPrice = Math.ceil(maxPrice);
    maxDistance = Math.ceil(maxDistance);

    if (maxPrice > 0) {
	priceSlider = new Slider('#priceRange', {
	    'min': 0,
	    'max': maxPrice,
	    'value': [0, maxPrice],
	    'range': true,
	    'tooltip': 'always',
	    'tooltip_split': true
	});

	addPriceFilterEvent();

	setTimeout(function () {
	    priceSlider.relayout();
	}, 100);

	$('#price-filter-block').removeClass('hidden');
    }

    if (maxDistance > 0) {
	distanceSlider = new Slider('#distanceRange', {
	    'min': 0,
	    'max': maxDistance,
	    'value': [0, maxDistance],
	    'range': true,
	    'tooltip': 'always',
	    'tooltip_split': true
	});

	distanceSlider.on('slideStop', function (ev) {
	    loadHotels(false, 1);
	});

	setTimeout(function () {
	    distanceSlider.relayout();
	}, 100);

	$('#distance-filter-block').removeClass('hidden');
    }
}

function updatePaginationControls(page, pageCount, maxPage) {
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

function paginateHotels(pageCount) {
    $('.pagination-controls li').click(function () {
	var page = $('.pageNumbers').find('.active').data('page');

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
