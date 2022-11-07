var shareFadeoutTimeout;
var TO_CAL;
var FROM_CAL;
var TO_CALMin;
var DATE_INFO = {};
var HRS_MAX_ROOMS = 9;
var MAX_NONIDENTICAL_ROOMS = 4;
var myLoadOffers = null;
var OFFERS_RATES_REFRESH_TIMESPAN;
var loadOffers = false;

var maxSelectableRoomsCount = {
    single: 0,
    double: 0
};

$(document).ready(function () {
    $(document).on('click', '.flag_lang', function () {
	var urlParams = new URLSearchParams(window.location.search);
	if (urlParams.toString() === "") {
	    var inputData = JSON.parse($('#inputData').val());
	    var input = {};

	    input['hotelSearchRequestId'] = inputData.hotelSearchRequestId;
	    input['hotelNameURL'] = inputData.hotelNameURL;
	    input['hotelCityName'] = inputData.city.name;
	    input['hotelId'] = inputData.hotelId;
	    input['fromDate'] = inputData.fromDate;
	    input['toDate'] = inputData.toDate;
	    input['singleRooms'] = inputData.singleRooms;
	    input['doubleRooms'] = inputData.doubleRooms;
	    input['adultCount'] = inputData.adultCount;
	    input['childCount'] = inputData.childCount;
	    input['childAge'] = JSON.stringify(inputData.childAge);
	    input['hotelKey'] = inputData.hotelKey;
	    input['locationId'] = inputData.locationId;
	    input['childBed'] = JSON.stringify(inputData.childBed);

	    urlParams = new URLSearchParams(input);
	}

	var newUrl = $(this).attr('href') + '?' + urlParams.toString();
	window.location = newUrl;
	return false;
    });

    OFFERS_RATES_REFRESH_TIMESPAN = parseInt($('#offers_rates_refresh_timespan').val());
    initHotelConstants();

    var detailsData = $('.hotel_booking_details_container').data();
    if ($('.update_search_container').length > 0 && (!detailsData.sourcett && detailsData.isactive)) {
	loadOffers = true;
	loadHotelOffers();
    }

    updateSelectBoxValues();
    prepareSliders();

    init360Thumbs();

    $(document).on('click', '.update_search_button', function () {
	$(".update_search_container").slideToggle('fast');
	$(".hotel_search_input_container").slideToggle('fast');
    });
    $(document).on('click', '.close_container', function () {
	$(".update_search_container").slideToggle('fast');
	$(".hotel_search_input_container").slideToggle('fast');
    });
    $(document).on('click', '.bookHotelButton', function () {
	$(".update_search_container").css('display', 'none');
	$(".hotel_search_input_container").slideToggle('fast');
    });

    $("#readmoreHolder").readmore({
	speed: 75,
	maxHeight: 100,
	collapsedHeight: 202,
	moreLink: '<a href="#">More ></a>',
	lessLink: '<a href="#">< Less</a>'
    });
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
	mySlickSlider.slick('slickGoTo', thisIndex);
    });

    $('.image-thumb-section').removeClass('displaynone');
    if ($(".hotel_infos_imgbig").length > 0) {
	$(".hotel_infos_imgbig").fancybox({
	    helpers: {
		overlay: {
		    closeClick: true
		}
	    }
	});
    }

    $(document).ajaxComplete(function () {
	$(".rates-info").each(function () {
	    $(this).parent().find("a[rel~=tooltip]").data('title', $(this).html());
	});

	if (loadOffers) {
	    if (myLoadOffers != null) {
		clearTimeout(myLoadOffers);
	    }

	    var interval = OFFERS_RATES_REFRESH_TIMESPAN * 1000;
	    myLoadOffers = setTimeout(loadHotelOffers, interval);
	}
    });

    if ($(window.location.hash)) {
	$(window.location.hash).scrollView();
    }

    $(document).on('change', ".room-count", function (e) {
	// Apply selected value to all instances .room-count
	// having same name attribute. This is done because
	// we have duplicating .room-count for each screen view
	// as per new UI integration
	var identifier = '[name="' + $(this).attr('name') + '"]';
	var selVal = $(this).val();

	$(identifier).each(function () {
	    var val = $(this).val();
	    if (val !== selVal) {
		$(this).val(selVal);
	    }
	});

	// validate room counts
	var isValidRoomCounts = validateRoomSelections(null, 1);
	if (isValidRoomCounts) {
	    // update counts, etc.
	    updateWideScreenSelectedOffersTotal();

	    $(identifier).each(function () {
		$(this).data('prev_val', selVal);
	    });
	} else {
	    // put back previous value
	    var prev_val = parseInt($(this).data('prev_val'), 10);
	    $(identifier).each(function () {
		$(this).val(prev_val);
	    });
	}
    });

    $(document).on('submit', '#hotel-book', function () {
	showHotelOverlay('', 'offer');
    });

    $('[data-fancybox="gallery"]').fancybox({
	animationEffect: false,
	transitionEffect: 'slide',
	thumbs: {
	    autoStart: true,
	},
    });
});

$.fn.scrollView = function () {
    return this.each(function () {
	$('html, body').animate({
	    scrollTop: $(this).offset().top
	}, 1000);
    });
};

function hotelTooltip() {
    var targets = $('[rel~=tooltip]');
    var target = false;
    var tooltip = false;
    var tip = false;
    var tooltipContainer = false;

    targets.unbind('mouseenter');
    targets.bind('mouseenter', function (e) {
	$('.hotel-tooltip-container').remove();

	target = $(this);
	tip = target.data('title');

	if (!tip || tip == '') {
	    return false;
	}

	var tooltipWidth = Math.ceil($(window).width() * 0.90);
	if (tooltipWidth > 550) {
	    tooltipWidth = 550;
	}

	tooltip = $('<div id="tooltip" class="hotel-tooltip"></div>').html(tip).css('display', 'inline-block');
	tooltipContainer = $('<div class="hotel-tooltip-container"></div>').css({
	    display: 'block',
	    width: (tooltipWidth + 'px')
	}).html(tooltip).appendTo(target);

	var init_tooltip = function () {

	    pos_left = (($(tooltipContainer).outerWidth() / 2) - ($(target).closest('.cond_tooltip_hldr').outerWidth() - 9)) * -1;
	    pos_top = ($(tooltipContainer).outerHeight() + ($(target).closest('.cond_tooltip_hldr').outerHeight() + 30)) * -1;

	    var css = {};
	    if ($('.hotel-tooltip-container').offset().left + $('.hotel-tooltip-container').outerWidth() > $(window).width()) {
		tooltip.addClass('right');
		pos_right = -12;

		css = {
		    right: pos_right,
		    top: pos_top,
		    padding: '20px 0px 30px 0px'
		};
	    } else {
		css = {
		    left: pos_left,
		    top: pos_top,
		    padding: '20px 0px 30px 0px'
		};

	    }

	    tooltipContainer.css(css).animate({
		opacity: 1
	    }, 50);
	};

	init_tooltip();
    });

    targets.bind('mouseleave', function () {
	$('.hotel-tooltip-container').remove();
    });
}

function initIncludedTaxesAndFees(data) {
    var taxesAndFees = '';
    if (data.includedTaxAndFees) {
	taxesAndFees = [];
	for (var i = 0; i < data.includedTaxAndFees.length; i++) {
	    var itm = data.includedTaxAndFees[i];
	    taxesAndFees.push('- ' + itm.description + ': ' + itm.inclusive);
	}

	taxesAndFees = taxesAndFees.join('<br/>');
    }

    $('.tax_hldr').html(taxesAndFees);
}

function initRoomGallery(target) {
    var gallery = $('.room-gallery', $(target).parent());
    var template = $('#room-gallery-template').html();
    var gallery_data = $(gallery).data();

    // validate if we have a gallery is clickable
    var isClickable = false;
    if (gallery_data.hasOwnProperty(('isClickable'))) {
	isClickable = gallery_data.isClickable;
    }

    // we only populate room's gallery popup html the first time
    if (gallery.html() == '' && isClickable) {
	template = ttUtilsInst.template(template, gallery_data);
	template = $(template);

	if (gallery_data.hasOwnProperty('images')) {
	    if (gallery_data.images) {
		$('.no_image_hldr', template).remove();
		var img_preview_template = $('.images_preview_hldr', template).html();
		var img_thumbs_template = $('.images_thumb_hldr', template).html();

		var img_preview = '';
		var imag_thumbs = '';

		for (var i = 0; i < gallery_data.images.length; i++) {
		    var galleryImages = gallery_data.images[i];
		    var imgObj = {
			'image0': galleryImages[0],
			'image1': galleryImages[1]
		    };

		    img_preview += ttUtilsInst.template(img_preview_template, imgObj);
		    imag_thumbs += ttUtilsInst.template(img_thumbs_template, imgObj);
		    delete i, galleryImages, imgObj;
		}

		$('.images_preview_hldr', template).html(img_preview);
		$('.images_thumb_hldr', template).html(imag_thumbs);

		delete img_preview_template, img_thumbs_template, img_preview, imag_thumbs;
	    } else {
		$('.with_image_hldr', template).remove();
	    }
	}

	if (gallery_data.hasOwnProperty('facilities')) {
	    if (gallery_data.facilities == '') {
		$('.facilities_hldr', template).remove();
	    }
	}

	gallery.html(template);

	if ($('.slider-for', gallery).length > 0) {
	    $('.slider-for', gallery).each(function () {
		prepareSlider(this);
	    });

	    $('#hotel-images-slider').css({
		'opacity': 1,
		'height': ''
	    });
	    $('#hotel-images-thumb').css({
		'opacity': 1,
		'height': ''
	    });
	}

	roomGalleryEvent();
    }

    delete gallery, template, gallery_data;
    return isClickable;
}

function initRoomGalleryData(data) {
    if (data.hasOwnProperty('roomOffers')) {
	for (var key in data.roomOffers) {
	    var offers = data.roomOffers[key];
	    for (var key1 in offers) {
		if (key1 == 'header') {
		    continue;
		}

		var rooms = offers[key1];
		for (var key2 in rooms) {
		    var room = rooms[key2];

		    var gallery = room;
		    var galleryObj = {};

		    if (room.hasOwnProperty('gallery')) {
			gallery = room.gallery;
		    }

		    galleryObj = {
			'counter': (gallery.hasOwnProperty('counter')) ? gallery.counter : '',
			'name': (gallery.hasOwnProperty('name')) ? gallery.name : '',
			'images': (gallery.hasOwnProperty('images')) ? gallery.images : [],
			'facilities': (gallery.hasOwnProperty('facilities')) ? gallery.facilities : '',
			'isClickable': true
		    };

		    if (galleryObj.images.length == 0 && galleryObj.facilities == '') {
			galleryObj.isClickable = false;
		    }

		    var gallerySelector = '.gallery-' + key + '-' + key1 + '-' + key2;
		    $(gallerySelector).data(galleryObj);

		    var roomElmParent = $(gallerySelector).parent();
		    $('.room-name', roomElmParent).data('isClickable', galleryObj.isClickable);
		    $('.room-name', roomElmParent).data('imglength', galleryObj.images.length);

		    // remove link on room name if we don't have gallery data
		    if (!galleryObj.isClickable) {
			var roomElm = $('.room-name', roomElmParent);

			$(roomElm).css({'cursor': 'default', 'text-decoration': 'none'});
			delete roomElmParent, roomElm;
		    }

		    delete key2, room, gallerySelector, galleryObj;
		}
		delete key1, rooms;
	    }

	    delete key, offers;
	}
    }
    delete data;
}

function loadHotelOffers() {
    showHotelOverlay('hotel-offers', 'offer');
    if ($('[name=fromDate]').val() != '' && $('[name=toDate]').val() != '') {
	var input = JSON.parse($('#inputData').val());

	var url = generateLangURL(window.hotel_route_path_hotel_offers).replace('{name}', input['hotelNameURL']).replace('{id}', input['hotelId']);
	$.ajax({
	    url: url,
	    data: input,
	    type: 'post',
	    dataType: "json",
	    success: function (jsonData) {
		maxSelectableRoomsCount.single = jsonData.maxSingleRoomsCount;
		maxSelectableRoomsCount.double = jsonData.maxDoubleRoomsCount;

		$('#hotel-offers').html(jsonData.offersLoop);
		$('#hotel-credit-cards').html(jsonData.hotelCreditCards);

		var showStayInTheHeartSection = false;

		$('.hotel-distances').html(jsonData.hotelDistances);
		if (jsonData.hotelDistances) {
		    showStayInTheHeartSection = true;
		}

		if (jsonData.hasOwnProperty('hotelReviewHighlights')) {
		    $('.hotel-review-highlights').html(jsonData.hotelReviewHighlights);

		    if (!jsonData.hotelDistances && jsonData.hotelReviewHighlights) {
			showStayInTheHeartSection = true;
		    }
		}

		if (showStayInTheHeartSection) {
		    // show stay in the heart section
		    $('.hotel-city-name').removeClass('hidden');
		}

		if (jsonData.hasOwnProperty('hotelDetails')) {
		    if (jsonData.hotelDetails.hasOwnProperty('checkInEarliest')) {
			$('#check-in-earliest').html(jsonData.hotelDetails.checkInEarliest);
		    }
		    if (jsonData.hotelDetails.hasOwnProperty('checkOutLatest')) {
			$('#check-out-latest').html(jsonData.hotelDetails.checkOutLatest);
		    }

		    if (jsonData.hotelDetails.hasOwnProperty('mainImage')) {
			$('#main-image').attr('src', jsonData.hotelDetails.mainImage);
		    }

		    if (jsonData.hotelDetails.hasOwnProperty('mainImageBig')) {
			$('#main-image-big').attr('href', jsonData.hotelDetails.mainImageBig);
		    }

		    if (jsonData.hotelDetails.hasOwnProperty('description')) {
			$('.hotel-description').html(jsonData.hotelDetails.description);
		    }
		}

		if ($('.facility_new_row').length === 0) {
		    if (jsonData.hasOwnProperty('hotelAmenities')) {
			$('.hotel-amenities').html(jsonData.hotelAmenities);
		    }

		    if (jsonData.hasOwnProperty('hotelFacilities')) {
			$('.hotel-facilities').html(jsonData.hotelFacilities);
		    }
		}

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

		$('.left_panel .accordion').first().addClass('active');
		$('.left_panel .accordion').first().next('div.panel').slideToggle();

		$('.right_panel .accordion').first().addClass('active');
		$('.right_panel .accordion').first().next('div.panel').slideToggle();

		if ($('.accordion').length === 2) {
		    $('.accordion').css('cursor', 'default');
		}

		$(window).scroll(function () {
		    setScrollPosition();
		});

		$(window).resize(function () {
		    setScrollPosition();

		});

		setScrollPosition();

		hotelTooltip();

		if (jsonData.hasOwnProperty('roomOffers') && $(jsonData.roomOffers).length > 0) {
		    $('.reserve').removeAttr('disabled');
		    $('.reserve').removeClass('reserveDisabled');
		    $('#hotel-book').unbind('submit').submit(validateRoomSelections);
		}

		if ($(window.location.hash)) {
		    $(window.location.hash).scrollView();
		}

		initIncludedTaxesAndFees(jsonData);
		initRoomGalleryData(jsonData);
		roomGalleryEvent();

		hideHotelOverlay();
		if (jsonData.error) {
		    showErrorMsg(jsonData.error);
		}
	    },
	    error: function (XMLHttpRequest, textStatus, errorThrown) {
		hideHotelOverlay();
		$('.hotel-offer-message').html(Translator.trans(Translator.trans('A problem occured when listing room offers. Please reload the page.')));
	    }
	});

    } else {
	showErrorMsg(Translator.trans('Select check-in and check-out dates.'));

	hideHotelOverlay();
	$('.hotel-offer-message').html(Translator.trans('Enter your check-in and check-out dates to view the available room rates.'));

    }
}

function setScrollPosition() {
    if ($('.containerPrices').length) {
	$('.containerPrices').attr('data-ww', $('.containerPrices').width());
	var scrolly = $(window).scrollTop();
	var posy = $('.containerPrices').offset().top;
	var newpoy = -(scrolly - posy);
	if (newpoy <= 0) {
	    var containerPricesContent = 0;

	    if ($('.containerPricesContent').length > 0) {
		containerPricesContent = $('.containerPricesContent').height();
	    }

	    var newpoy1 = parseInt(newpoy) - $('.containerPricesReserve').height() + containerPricesContent;
	    $('.containerPricesHead').css('position', 'fixed');
	    $('.containerPricesHead').css('width', $('.containerPrices').attr('data-ww') + 'px');

	    if (newpoy1 >= 0) {
		$('.containerPricesHead').css('top', '0');
	    } else {
		$('.containerPricesHead').css('top', newpoy1 + "px");
	    }
	} else {
	    $('.containerPricesHead').css('position', 'relative');
	    $('.containerPricesHead').css('top', 'initial');
	    $('.containerPricesHead').css('width', '100%');
	}
    }
}

function prepareSliders() {
    if ($('.slider-for').length > 0) {
	$('.slider-for').each(function () {
	    prepareSlider(this);
	});

	$('#hotel-images-slider').css({
	    'opacity': 1,
	    'height': ''
	});
	$('#hotel-images-thumb').css({
	    'opacity': 1,
	    'height': ''
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
	    centerMode: true,
	    prevArrow: '<button class="slick-prev slick-arrow" type="button"><i class="fas fa-angle-left"></i></button>',
	    nextArrow: '<button class="slick-next slick-arrow" type="button"><i class="fas fa-angle-right"></i></button>',
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

function roomGalleryEvent() {
    $('.room-gallery .room-gallery-container').unbind('click');
    $('.room-gallery .room-gallery-container').bind('click', function (e) {
	e.stopPropagation();
    });

    $('.room-gallery').unbind('click');
    $('.room-gallery').bind('click', function () {
	$(this).addClass('sliderHidden');
    });

//    $('.room-name').unbind('click');
//    $('.room-name').bind('click', function () {
//	var isShown = initRoomGallery(this);
//	if (isShown) {
//	    $(this).next('.room-gallery').removeClass('sliderHidden');
//	}
//    });

    $('.room-name').each(function () {
	var $this = $(this);
	if ($this.data('isClickable')) {
	    $this.fancybox(
		    {
			padding: 0,
			margin: 0,
			beforeLoad: function () {
			    $('.upload-overlay-loading-fix').show();
			    $('#popup_room_gallery').html('');
			    initFancyRoomGallery($this);
			    $('.upload-overlay-loading-fix').hide();
			},
			afterLoad: function () {
			    $('.slider-for', $('#popup_room_gallery')).each(function () {
				prepareSlider(this);
			    });
			    $('#hotel-images-slider').css({
				'opacity': 1,
				'height': ''
			    });
			    $('#hotel-images-thumb').css({
				'opacity': 1,
				'height': ''
			    });
			}
		    });
	}
    });

    $('.closebut-room').unbind('click');
    $('.closebut-room').bind('click', function () {
	$(this).closest('.room-gallery').addClass('sliderHidden');
    });
}

function initFancyRoomGallery(target) {
    var gallery = $('.room-gallery', $(target).parent());
    var template = $('#room-gallery-template').html();
    var gallery_data = $(gallery).data();

    // validate if we have a gallery is clickable
    var isClickable = false;
    if (gallery_data.hasOwnProperty(('isClickable'))) {
	isClickable = gallery_data.isClickable;
    }

    // we only populate room's gallery popup html the first time
    template = ttUtilsInst.template(template, gallery_data);
    template = $(template);
    $('.no_image_hldr', template).remove();
    $('.ulcontainerroom', template).remove();

    if (gallery_data.hasOwnProperty('images')) {
	if (gallery_data.images) {
	    $('.no_image_hldr', template).remove();
	    var img_preview_template = $('.images_preview_hldr', template).html();
	    var img_thumbs_template = $('.images_thumb_hldr', template).html();

	    var img_preview = '';
	    var imag_thumbs = '';

	    for (var i = 0; i < gallery_data.images.length; i++) {
		var galleryImages = gallery_data.images[i];
		var imgObj = {
		    'image0': galleryImages[0],
		    'image1': galleryImages[1]
		};

		img_preview += ttUtilsInst.template(img_preview_template, imgObj);
		imag_thumbs += ttUtilsInst.template(img_thumbs_template, imgObj);
		delete i, galleryImages, imgObj;
	    }

	    $('.images_preview_hldr', template).html(img_preview);
	    $('.images_thumb_hldr', template).html(imag_thumbs);

	    delete img_preview_template, img_thumbs_template, img_preview, imag_thumbs;
	} else {
	    $('.with_image_hldr', template).remove();
	    $('.facilities_hldr', template).removeClass('col-lg-4');
	    $('.facilities_hldr', template).removeClass('col-md-4');
	    $('.room-gallery-container', template).addClass('max_w_370');
	}
    } else {
	$('.with_image_hldr', template).remove();
	$('.facilities_hldr', template).removeClass('col-lg-4');
	$('.facilities_hldr', template).removeClass('col-md-4');
	$('.room-gallery-container', template).addClass('max_w_370');
    }

    if (gallery_data.hasOwnProperty('facilities')) {
	if (gallery_data.facilities == '') {
	    $('.facilities_hldr', template).remove();
	}
    }

    $('#popup_room_gallery').html(template);

    if ($(target).data().imglength == 0) {
	$('.with_image_hldr', $('#popup_room_gallery')).remove();
	$('.facilities_hldr', $('#popup_room_gallery')).removeClass('col-lg-4');
	$('.facilities_hldr', $('#popup_room_gallery')).removeClass('col-md-4');
	$('.room-gallery-container', $('#popup_room_gallery')).addClass('max_w_370');
    }

    if ($('.facilities_hldr', $('#popup_room_gallery')).length == 0) {
	$('.with_image_hldr', $('#popup_room_gallery')).removeClass('col-lg-8');
	$('.with_image_hldr', $('#popup_room_gallery')).removeClass('col-md-8');
	$('.room-gallery-container', $('#popup_room_gallery')).addClass('max_w_780');
    }

//    if ($('.slider-for', $('#popup_room_gallery')).length > 0) {
//	$('.slider-for', $('#popup_room_gallery')).each(function () {
//	    prepareSlider(this);
//	});
//
//	$('#hotel-images-slider').css({
//	    'opacity': 1,
//	    'height': ''
//	});
//	$('#hotel-images-thumb').css({
//	    'opacity': 1,
//	    'height': ''
//	});
//    }
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
			optionText = val + "   (" + toCurrency + " " + floorPrice.toLocaleString('en-US', {
			    maximumFractionDigits: 0
			}) + ")";
			optionText = optionText.replace(/ /g, '\u00a0');
		    }
		    $(this).text(optionText);
		});
		$(this).attr('data-currency', toCurrency);
	    });
	});
    });
}

function updateWideScreenSelectedOffersTotal() {
    totalPrice = 0;
    selectedRooms = 0;
    offerTypeSelectedCount = 0;
    cancellation = -1;
    showOfferNote = 0;

    // since we have duplicating room-count per screen view
    // we need to prevent duplicate totals
    var total_keys = [];

    details = '';
    tax = '';
    $(".room-count").each(function () {
	var key = $(this).attr('name');
	if ($.inArray(key, total_keys) === -1) {
	    total_keys.push(key);

	    selected_value = $(this).val();
	    roomPrice = 0;
	    if (selected_value > 0) {
		offerTypeSelectedCount = offerTypeSelectedCount + 1;
		if (offerTypeSelectedCount === 1) {
		    details = $(this).closest('.lightgreudiv').find(".recancel").html();
		    tax = $(this).closest('.lightgreudiv').find(".retax");
		    if (tax.length) {
			tax = tax.html();
		    } else {
			tax = '';
		    }
		}

		roomPrice = $(this).closest('.lightgreudiv').find(".offer-price-text .price_txt .price-convert").attr("data-price");
		totalPrice = totalPrice + Math.floor(roomPrice * parseInt(selected_value, 10));
		selectedRooms = selectedRooms + parseInt(selected_value, 10);

		if (cancellation < 0) {
		    cancellation = $(this).siblings('.offer-cancellation-info').val();
		} else if (cancellation != $(this).siblings('.offer-cancellation-info').val()) {
		    // cancellation not unique for all offers selected
		    showOfferNote = 1;
		}
	    }
	}
    });

    $(".price_txt_total .price-convert").attr("data-price", totalPrice);
    if ($(".price_txt_total .price-convert .price-convert-text").length > 0) {
	$(".price_txt_total .price-convert .price-convert-text").html(totalPrice).number(true);
    }

    roomText = (selectedRooms > 1) ? Translator.trans("rooms") : Translator.trans("room");
    $(".selected_room_count").html(selectedRooms + ' ' + roomText);

    if (totalPrice > 0) {
	$(".price_txt_total").show();
	$(".selected_room_txt").show();
	$(".selected_room_details").show();
	$(".reserve_room_container").addClass("selected_room_color");

	if (offerTypeSelectedCount === 1 && $('.lightgreudiv').length > 1) {
	    $(".selected_room_details .details").html(details);
	    $(".selected_room_details").show();

	    if (tax) {
		$(".selected_room_details .taxInfo").html(tax);
		$(".selected_room_details_taxInfo_hldr").show();
	    } else {
		$(".selected_room_details_taxInfo_hldr").hide();
	    }
	} else {
	    $(".selected_room_details .details").html('');
	    $(".selected_room_details").hide();
	}

	$(".reserve").css("background-color", "#390");
    } else {
	$(".price_txt_total").hide();
	$(".selected_room_txt").hide();
	$(".selected_room_details").hide();
	$(".reserve_room_container").removeClass("selected_room_color");
	$(".reserve").css("background-color", "#db3a69");
    }

    if (showOfferNote === 1) {
	$(".offer_note").show();
    } else {
	$(".offer_note").hide();
    }

    if (window.hotels.pageSrc.hrs) {
	$(".selected_room_details_taxInfo_hldr").hide();
    }
}

function validateRoomSelections(evt, allowEmptySelections) {
    var valid = true;
    var count = 0;

    if (allowEmptySelections === undefined) {
	allowEmptySelections = 0;
    }

    // we need to track how many rooms are selected per room category (e.g. how many are for: single rooms and double rooms
    // this in turn will be validated on room select change that will corresponse to our search criteria.
    var selectedRoomsByCategory = [];
    var addRoomToACategory = function (elm) {
	var numRooms = parseInt($(elm).val(), 10);
	var category = $(elm).data('roomcategory');
	var categoryKey = (category.includes('single')) ? 'single' : 'double';
	if (!(categoryKey in selectedRoomsByCategory)) {
	    selectedRoomsByCategory[categoryKey] = 0;
	}

	selectedRoomsByCategory[categoryKey] = selectedRoomsByCategory[categoryKey] + numRooms;
    };

    // this will validate room count per each room category as per our search criteria.
    var validateRoomCountPerCategory = function () {
	var isValid = true;
	if ('single' in selectedRoomsByCategory) {
	    if (maxSelectableRoomsCount.single > 0 && selectedRoomsByCategory.single <= maxSelectableRoomsCount.single) {
		isValid = true;
	    } else {
		isValid = false;
	    }
	}

	if (isValid && ('double' in selectedRoomsByCategory)) {
	    if (maxSelectableRoomsCount.double > 0 && selectedRoomsByCategory.double <= maxSelectableRoomsCount.double) {
		isValid = true;
	    } else {
		isValid = false;
	    }
	}

	//console.log('validateRoomCountPerCategory: ' + isValid);
	return isValid;
    };

    var select_keys = [];
    $('.room-count').each(function () {
	var key = $(this).attr('name');
	if ($.inArray(key, select_keys) === -1) {
	    select_keys.push(key);
	    count += parseInt($(this).val());

	    // add room to a category and keeps track the selected room count
	    addRoomToACategory(this);
	}

	// The new UI integration uses two different view population
	// so as to not to overwrite selected offer values with the one
	// same offer of another view that is hidden; we must disable
	// those select boxes
	var hldr = $(this).closest('.room-offer-hldr');
	if (hldr.length) {
	    var display = $(hldr).css('display');
	    if (display.toLowerCase() == 'none') {
		$(this).attr('disabled', 'disabled');
	    } else {
		$(this).removeAttr('disabled');
	    }
	}
    });

    // validate room count per each room category
    var isValidRoomCounts = validateRoomCountPerCategory();

    if (count <= 0 && !allowEmptySelections) {
	showErrorMsg(Translator.trans("Please select a room to continue to reservation."));
	valid = false;
    } else if (count > HRS_MAX_ROOMS) {
	var contactLink = "<a href='/contact' title='Contact Us' target='_blank'>" + Translator.trans('contact us') + "</a>";
	showErrorMsg(Translator.trans("Maximum of ") + HRS_MAX_ROOMS + Translator.trans(" rooms are allowed per reservation. For group reservations, please don't hesitate to ") + contactLink + ".");
	valid = false;
    } else if (!isValidRoomCounts) {
	// we should return all previous selected rooms back
	var ttModal = window.getTTModal("myModalZ", {title: " "});
	ttModal.alert(Translator.trans("You selected more rooms than what was in your initial request. Please refine your search."));

	valid = false;
    } else {
	if (!window.hotels.pageSrc.hrs) {
	    var offerSelected = false;
	    var gds = $('#gds').html();
	    var maxSelectableRooms = $('#maxSelectableRooms').data('value');

	    $.each(maxSelectableRooms, function (index, value) {
		var selected = 0;
		var select_keys = [];

		$('.room-' + index).each(function () {
		    var key = $(this).attr('name');
		    if ($.inArray(key, select_keys) === -1) {
			select_keys.push(key);

			var numRooms = parseInt($(this).val());
			selected += numRooms;
			if (numRooms > 0) {
			    if (offerSelected === true) {
				if (gds == '1') {
				    showErrorMsg(Translator.trans("For non-identical offers, please place a separate reservation for each offer type."));
				    valid = false;
				} else if (selected > MAX_NONIDENTICAL_ROOMS) {
				    showErrorMsg(Translator.trans("When booking multiple non-identical room types, the total number of selected rooms cannot exceed " + MAX_NONIDENTICAL_ROOMS + "."));
				    valid = false;
				}
			    }
			    offerSelected = true;
			}
		    }
		});

		if (selected > value) {
		    showErrorMsg(Translator.trans("The selected number of rooms exceeds your search criteria."));
		    valid = false;
		    return false;
		}
	    });
	}
    }

    return valid;
}

function init360Thumbs() {
    $("div.img_holder_360").click(function () {
	renderTourEngine(this);
    });
    //
    $("div.img_holder_360[ismain=true]").first().click();
}

function renderTourEngine(imgDom) {
    $(".slider_big_360").attr("src", $(imgDom).attr("main-img"));
    //
    $(imgDom).parent().find("div.active").removeClass("active");
    $(imgDom).addClass("active");
    //
    //
    var hotelId = $(imgDom).data("hotelid");
    var hotelName = $(imgDom).data("hotelname");
    var ctryCode = $(imgDom).data("ctrycode").toLowerCase();
    var catgId = $(imgDom).data("catgid");
    var catgName = $(imgDom).data("catgname");
    var divId = $(imgDom).data("divid");
    var divName = $(imgDom).data("divname");
    var parentdivid = $(imgDom).data("parentdivid");
    var subDivId = null;
    if (parentdivid && parentdivid != "") {
	subDivId = divId;
	divId = parentdivid;
    }
    var subDivName = '';
    //
    new TTTour("pano", {
	loadPopupLibrary: false,
	fullTour: {
	    active: true,
	    linkOrigin: ".view-full-tour-link",
	    openTarget: "_blank",
	},
	showThumbnail: false,
	autoload: true,
	params: {
	    id: hotelId,
	    type: "hotels",
	    name: hotelName,
	    countrycode: ctryCode,
	    group: catgId,
	    groupName: catgName,
	    division: divId,
	    divisionName: divName,
	    subdivision: subDivId
	}
    });
}
