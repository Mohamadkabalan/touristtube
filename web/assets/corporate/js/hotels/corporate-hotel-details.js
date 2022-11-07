var shareFadeoutTimeout;
var TO_CAL;
var FROM_CAL;
var TO_CALMin;
var DATE_INFO = {};
var HRS_MAX_ROOMS = 9;
var MAX_NONIDENTICAL_ROOMS = 4;
var myLoadOffers = null;

$(document).ready(function () {
    initShowOnMap();
    loadHotelOffers();
    prepareSliders();
    updateSelectBoxValues();

    init360Thumbs();

    $('.image-thumb-section').removeClass('displaynone');
    $(".hotel_infos_imgbig").fancybox({
	helpers: {
	    overlay: {closeClick: true}
	}
    });

    $(document).ajaxComplete(function () {
	$(".rates-info").each(function () {
	    $(this).parent().find("a[rel~=tooltip]").data('title', $(this).html());
	});

	if (myLoadOffers != null) {
	    clearTimeout(myLoadOffers);
	}

	var interval = HOTEL_CONSTANTS.vendors.amadeus.rates_refresh_timespan * 1000;
	myLoadOffers = setTimeout(loadHotelOffers, interval);
    });

    if ($(window.location.hash)) {
	$(window.location.hash).scrollView();
    }

    $(document).on('change', ".room-count", function (e) {
	updateWideScreenSelectedOffersTotal();
    });

    displayFacilities();
});

function prepareSliders() {
    if ($('.slider-for').length > 0) {
	$('.slider-for').each(function () {
	    var unique = $(this).data('unique');
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
	});
    }
}

function displayFacilities() {
    if ($('#facility-data').length) {
	var facilities = JSON.parse($('#facility-data').html());

	var col = 1;
	var i = 1;
	$.each(facilities, function (key, facility) {
	    col = (col <= 3) ? col : 1;
	    var facilityB = facilityBlock(facility, i);
	    html = '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 facilityitems">' + facilityB + '</div>';
	    $('.facility-container').append(html);
	    col++;
	    i++;
	});
    }
}

function facilityBlock(facility, num) {
    var grey = {
	bg: 'greybackdivslgf',
	head: 'slderheadergrey',
	list: 'ullilistgrey'
    };
    var pink = {
	bg: 'pinkbackdivslgf',
	head: 'slderheadecolored',
	list: 'ullilistcolored'
    };
    var blue = {
	bg: 'bluebackdivslgf',
	head: 'slderheadecolored',
	list: 'ullilistcolored'
    };
    var mauv = {
	bg: 'mauvbackdivslgf',
	head: 'slderheadecolored',
	list: 'ullilistcolored'
    };
    var colors = [
	grey,
	pink,
	blue,
	mauv
    ];
    //var css = colors[Math.floor(Math.random() * colors.length)];
    var css = colors[0];
    if (num == 1) {
	css = colors[1];
    } else if (num == 3) {
	css = colors[3];
    } else if (num == 5) {
	css = colors[2];
    }

    html = '<div class="' + css['bg'] + ' padtenbut column-sub">' + '<h3 class="' + css['head'] + '">' + facility['type'] + '</h3>' + '<ul class="' + css['list'] + '">';
    $.each(facility['names'], function (index, name) {
	if (index >= 5) {
	    html += '<li class="visible-xs">- ' + name + '</li>';
	} else {
	    html += '<li>- ' + name + '</li>';
	}
	if (index == 5) {
	    html += '<li class="fac-view-all hidden-xs"><a rel="tooltip" data-title="- ' + facility['names'].join('<br/>- ') + '" data-html="true" href="#">' + '<img src="/images/helpblueimg.png" alt="' + view_all + '" />' + view_all + '</a></li>';
	    return false;
	}
    });
    html += '</ul>' + '</div>';

    return html;
}

function loadHotelOffers() {
    if ($('#fromDateH').val() != '' && $('#toDateH').val() != '') {
	$('.offer-overlay-loading-fix').removeClass('hidden');
	var input = JSON.parse($('#inputData').val());

	$.ajax({
	    url: generateLangURL('./corporate/hotels/offers-' + input['hotelNameURL'] + '-' + input['hotelId'], 'corporate'),
	    data: input,
	    type: 'post',
	    dataType: "json",
	    success: function (jsonData) {
		$('#hotel-offers').html(jsonData.offersLoop);
		$('#hotel-distances').html(jsonData.hotelDistances);
		$('#hotel-credit-cards').html(jsonData.hotelCreditCards);
		$('#main-image').attr('src', jsonData.hotelDetails.mainImage);
		$('#main-image-big').attr('href', jsonData.hotelDetails.mainImageBig);

		if ($('.facilityitems').length === 0) {
		    $('#hotel-amenities').html(jsonData.hotelAmenities);
		    $('#hotel-facilities').html(jsonData.hotelFacilities);
		    displayFacilities();
		}

		/*
		 // commenting this since image is populated on the twig
		 if (jsonData.hotelDetails.images) {
		 var namealt = $('.hotel_infos_imgbig').attr('title');
		 var slider = '';
		 var thumb = '';

		 $.each(jsonData.hotelDetails.images, function (key, images) {
		 $.each(images, function (cat, image) {
		 slider += '<div><img data-lazy="' + image[0] + '" style="height:100%; color:#f2f2f2;" alt="' + namealt + '"/></div>';
		 thumb += '<div class="mediathumb"><div class="mediathumbcontainer"><img src="' + image[1] + '" alt="' + namealt + '"/></div><div class="mediathumbbk"></div></div>'
		 });
		 });
		 $('#hotel-images-slider').html(slider).addClass('slider-for');
		 $('#hotel-images-thumb').html(thumb);
		 }*/

		if (jsonData.hotelDetails.checkInEarliest) {
		    $('#check-in-earliest').html(jsonData.hotelDetails.checkInEarliest);
		}
		if (jsonData.hotelDetails.checkOutLatest) {
		    $('#check-out-latest').html(jsonData.hotelDetails.checkOutLatest);
		}

		if (jsonData.hotelDetails.description) {
		    $('#hotel-description').html(jsonData.hotelDetails.description);
		    $('#hotel-description-block').removeClass('hidden');
		}

		$(window).scroll(function () {
		    setScrollPosition();
		});

		$(window).resize(function () {
		    setScrollPosition();
		    setHightlightsHeight();
		});

		setScrollPosition();
		setHightlightsHeight();
		hotelTooltip();

		$('#reserve').removeAttr('disabled');
		$('#hotel-book').submit(validateRoomSelections);
		$('.offer-overlay-loading-fix').addClass('hidden');

		if ($(window.location.hash)) {
		    $(window.location.hash).scrollView();
		}

		prepareSliders();
		roomGalleryEvent();

		if (jsonData.error) {
		    showErrorMsg(jsonData.error);
		}
	    }
	});
    } else {
	showErrorMsg(Translator.trans('Select check-in and check-out dates.'));

	$('.offer-overlay-loading-fix').addClass('hidden');
	$('.hotel-offer-message').html(Translator.trans('Enter your check-in and check-out dates to view the available room rates.'));
    }
}

function validateRoomSelections() {
    var valid = true;
    var count = 0;

    $('.room-count').each(function () {
	count += parseInt($(this).val());
    });

    if (count <= 0) {
	showErrorMsg(Translator.trans("Please select a room to continue to reservation."));
	valid = false;
    } else if (count > HRS_MAX_ROOMS) {
	var contactLink = "<a href='/contact' title='Contact Us' target='_blank'>" + Translator.trans('contact us') + "</a>";
	showErrorMsg(Translator.trans("Maximum of ") + HRS_MAX_ROOMS + Translator.trans(" rooms are allowed per reservation. For group reservations, please don't hesitate to ") + contactLink + ".");
	valid = false;
    } else {
	var offerSelected = false;
	var gds = $('#gds').html();
	var maxSelectableRooms = $('#maxSelectableRooms').data('value');

	$.each(maxSelectableRooms, function (index, value) {
	    var selected = 0;
	    $('.room-' + index).each(function () {
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
	    });

	    if (selected > value) {
		showErrorMsg(Translator.trans("The selected number of rooms exceeds your search criteria."));
		valid = false;
		return false;
	    }
	});
    }

    return valid;
}

function roomGalleryEvent() {
    $('.room-gallery .room-gallery-container').click(function (e) {
	e.stopPropagation();
    });

    $('.room-gallery').click(function () {
	$(this).addClass('sliderHidden');
    });

    $('.room-name').click(function () {
	$(this).next('.room-gallery').removeClass('sliderHidden');
    });

    $('.closebut-room').click(function () {
	$(this).closest('.room-gallery').addClass('sliderHidden');
    });
}

function updateWideScreenSelectedOffersTotal() {
    totalPrice = 0;
    selectedRooms = 0;
    offerTypeSelectedCount = 0;
    cancellation = -1;
    showOfferNote = 0;
    $(".room-count").each(function () {
	selected_value = $(this).val();
	roomPrice = 0;
	if (selected_value > 0) {
	    offerTypeSelectedCount = offerTypeSelectedCount + 1;
	    if (offerTypeSelectedCount === 1) {
		details = $(this).closest('.lightgreudiv').find(".recancel").html();
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
	$(".selected_room_bk").addClass("selected_room_color");
	if (offerTypeSelectedCount === 1 && $('.lightgreudiv').length > 1) {
	    $(".selected_room_details ul").html(details);
	    $(".selected_room_details").show();
	} else {
	    $(".selected_room_details ul").html('');
	    $(".selected_room_details").hide();
	}
	$("#reserve").css("background-color", "#390");
    } else {
	$(".price_txt_total").hide();
	$(".selected_room_txt").hide();
	$(".selected_room_details").hide();
	$(".selected_room_bk").removeClass("selected_room_color");
	$("#reserve").css("background-color", "#00304c");
    }
    if (showOfferNote === 1) {
	$(".offer_note").show();
    } else {
	$(".offer_note").hide();
    }
}

function setHightlightsHeight() {
    var hhh1 = $('.lightblue').find('.lightinside').height();
    var hhh2 = $('.darckblue').find('.lightinside').height();
    if (hhh1 < hhh2)
	hhh1 = hhh2;
    hhh1 = parseInt(hhh1) + 15;
    $('.lightblue').css('min-height', hhh1 + 'px');
    $('.darckblue').css('min-height', hhh1 + 'px');
}

function setScrollPosition() {
    if ($('.containerPrices').length) {
	$('.containerPrices').attr('data-ww', $('.containerPrices').width());
	var scrolly = $(window).scrollTop();
	var posy = $('.containerPrices').offset().top;
	var newpoy = -(scrolly - posy);
	if (newpoy <= 0) {
	    var newpoy1 = newpoy - $('.containerPricesReserve').height() + $('.containerPricesContent').height();
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

$.fn.scrollView = function () {
    return this.each(function () {
	$('html, body').animate({
	    scrollTop: $(this).offset().top
	}, 1000);
    });
};

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
    $("div.mediathumbbk_360pg").click(function () {
	renderTourEngine(this);
    });
    //
    $("div.mediathumbbk_360pg[ismain=true]").first().click();
}

function renderTourEngine(imgDom) {
    $(".bigimgslidernew_pg360").attr("src", $(imgDom).attr("main-img"));
    //
    $(imgDom).parent().parent().find("div.active").removeClass("active");
    $(imgDom).parent().addClass("active");
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
	    linkOrigin: ".view_full_tour",
	    openTarget: "popup",
	}, showThumbnail: false, autoload: true, params: {id: hotelId, type: "hotels", name: hotelName, countrycode: ctryCode, group: catgId, groupName: catgName, division: divId, divisionName: divName, subdivision: subDivId}});
}

