var pagenumber =
	1;
var priceOrder =
	'';
var starsOrder =
	'';

$(
	document).
	ready(
		function () {
		    $(
			    'input:checkbox').
			    removeAttr(
				    'checked');
		    var pageType =
			    $(
				    'input[name="pageType"]').
			    val();
		    var w =
			    $(
				    window).
			    width();

    if (w > 768) {
	var srchH = parseInt($('.srch1').outerHeight() - 26);
    }
    pagenumber = $('.pagerWrapper li.active').attr('data-page');

    $(document).on('click', '.prev_pg', function () {
	var curpage = $('.active a').text();
	if (curpage == 1) {
	    pagenumber = 1;
	} else {
	    pagenumber = Number(curpage) - Number(1);
	}
	if (pageType == 1) {
	    getsearchrelated();
	} else if (pageType == 2) {
	    getsearchrelatedcategory();
	} else if (pageType == 3) {
	    getsearchnearby();
	} else if (pageType == 4) {
	    getsearchHotel();
	}
    });

    $(document).on('click', '.first_pg', function () {
	var curpage = $('.active a').text();
	pagenumber = 1;
	if (pageType == 1) {
	    getsearchrelated();
	} else if (pageType == 2) {
	    getsearchrelatedcategory();
	} else if (pageType == 3) {
	    getsearchnearby();
	} else if (pageType == 4) {
	    getsearchHotel();
	}
    });

    $(document).on('click', '.next_pg', function () {
	var curpage = $('.active a').text();
	var lastpage = $('input[name="totalpage"]').val() - 1;
	if (curpage == lastpage) {
	    pagenumber = lastpage;
	} else {
	    pagenumber = Number(curpage) + Number(1);
	}
	if (pageType == 1) {
	    getsearchrelated();
	} else if (pageType == 2) {
	    getsearchrelatedcategory();
	} else if (pageType == 3) {
	    getsearchnearby();
	} else if (pageType == 4) {
	    getsearchHotel();
	}
    });

    $(document).on('click', '.last_pg', function () {
	pagenumber = $().val() - 1;
	if (pageType == 1) {
	    getsearchrelated();
	} else if (pageType == 2) {
	    getsearchrelatedcategory();
	} else if (pageType == 3) {
	    getsearchnearby();
	} else if (pageType == 4) {
	    getsearchHotel();
	}
    });

    $(document).on('click', ".hotel_stars", function () {
	$('input[name="hotelPrefrences"]').removeAttr('checked');
	$('input[name="propertyType"]').removeAttr('checked');
	pagenumber = 1;
	if (pageType == 1) {
	    getsearchrelated();
	} else if (pageType == 2) {
	    getsearchrelatedcategory();
	} else if (pageType == 3) {
	    getsearchnearby();
	} else if (pageType == 4) {
	    getsearchHotel();
	}
    });

    $(document).on('click', ".hotel_prefrences", function () {
	$('input[name="hotelStar"]').removeAttr('checked');
	$('input[name="propertyType"]').removeAttr('checked');
	pagenumber = 1;
	if (pageType == 1) {
	    getsearchrelated();
	} else if (pageType == 2) {
	    getsearchrelatedcategory();
	} else if (pageType == 3) {
	    getsearchnearby();
	} else if (pageType == 4) {
	    getsearchHotel();
	}
    });

    $(document).on('click', ".propertyType", function () {
	$('input[name="hotelStar"]').removeAttr('checked');
	$('input[name="hotelPrefrences"]').removeAttr('checked');
	pagenumber = 1;
	if (pageType == 1) {
	    getsearchrelated();
	} else if (pageType == 2) {
	    getsearchrelatedcategory();
	} else if (pageType == 3) {
	    getsearchnearby();
	} else if (pageType == 4) {
	    getsearchHotel();
	}
    });

//    $(document).on('click', "#price_sort", function () {
//        $('input[name="hotelStar"]').removeAttr('checked');
//        $('input[name="hotelPrefrences"]').removeAttr('checked');
//        $('input[name="propertyType"]').removeAttr('checked');
//
//        $(".stars_sort").removeClass('up').removeClass('down');
//
//        if ($(this).hasClass('up')) {
//            $(this).addClass('down');
//            $(this).removeClass('up');
//
//            priceOrder = 'desc';
//            starsOrder = '';
//        } else {
//
//            $(this).addClass('up');
//            $(this).removeClass('down');
//
//            priceOrder = 'asc';
//            starsOrder = '';
//        }
//        if (pageType == 1) {
//            getsearchrelated();
//        } else if (pageType == 2) {
//            getsearchrelatedcategory();
//        } else if (pageType == 3) {
//            getsearchnearby();
//        } else if (pageType == 4) {
//            getsearchHotel();
//        }
//    });
//    $(document).on('click', "#stars_sort", function () {
//        $('input[name="hotelStar"]').removeAttr('checked');
//        $('input[name="hotelPrefrences"]').removeAttr('checked');
//        $('input[name="propertyType"]').removeAttr('checked');
//        pagenumber = 1;
//        $(".price_sort").removeClass('up').removeClass('down');
//        if ($(this).hasClass('up')) {
//            $(this).addClass('down');
//            $(this).removeClass('up');
//
//            starsOrder = 'desc';
//            priceOrder = '';
//        } else {
//            $(this).addClass('up');
//            $(this).removeClass('down');
//
//            starsOrder = 'asc';
//            priceOrder = '';
//        }
//        if (pageType == 1) {
//            getsearchrelated();
//        } else if (pageType == 2) {
//            getsearchrelatedcategory();
//        } else if (pageType == 3) {
//            getsearchnearby();
//        } else if (pageType == 4) {
//            getsearchHotel();
//        }
//    });

    $(document).on('click', ".pagerWrapper li[data-page] a", function (e) {
	e.preventDefault();
	$('.pagerWrapper li').removeClass('active');
	$(this).parent().addClass('active');
	pagenumber = $(this).parent().attr('data-page');
	if (pageType == 1) {
	    getsearchrelated();
	} else if (pageType == 2) {
	    getsearchrelatedcategory();
	} else if (pageType == 3) {
	    getsearchnearby();
	} else if (pageType == 4) {
	    getsearchHotel();
	}
    });

    initresizefilters();
    $(window).resize(function () {
	initresizefilters();
    });
    $(document).on('click', '.parentfilter', function () {
	var hh = parseInt($('.foot').height()) - 10;
	if ($('.foot').hasClass('slide-up')) {
	    $('.foot').addClass('slide-down', hh);
	    $('.foot').removeClass('slide-up');
	    $('.validateclass').hide();

	    var hhwin = $(window).height() - 10;
	    $('.slide-down').css('bottom', (-hhwin + 30) + "px");
	} else {
	    $('.foot').removeClass('slide-down');
	    $('.foot').addClass('slide-up', hh);
	    $('.validateclass').show();
	}
    });
});

function initresizefilters() {
    var hh =
	    $(
		    window).
	    height();
    var w =
	    $(
		    window).
	    width();
    initFilterTriger();
    if (w <= 767) {
	$('.foot').height(hh);
	$('.foot').css('bottom', (-hh + 40) + "px");
	$('.slide-down').css('bottom', (-hh + 60) + "px");
	$('.foot .hiddendiv').height((hh - 67));
    } else {
	$('.foot .hiddendiv').height('auto');
	$('.foot').height('auto');
	$('.foot').css('bottom', "0px");
	$('.foot').removeClass('slide-down');
	$('.foot').removeClass('slide-up');
    }
}

function getsearchrelated() {
    $('.upload-overlay-loading-fix').show();
    var type = $('input[name="types"]').val();
    var city = $('input[name="citys"]').val();
    var dest = $('input[name="dest"]').val();
    var hotelStars = new Array();
    $('input[name="hotelStar"]:checked').each(function () {
	hotelStars.push(this.value);
    });

    var hotelPrefrences = new Array();
    $('input[name="hotelPrefrences"]:checked').each(function () {
	hotelPrefrences.push(this.value);
    });

    var hotelPropertyType = new Array();
    $('input[name="propertyType"]:checked').each(function () {
	hotelPropertyType.push(this.value);
    });
    $.ajax({
	url: '/ajax/search_Hotel',
	data: {type: type, city: city, hotelStars: hotelStars, hotelPrefrences: hotelPrefrences, hotelPropertyType: hotelPropertyType, page: pagenumber, dest: dest, priceOrder: priceOrder, starsOrder: starsOrder},
	type: 'post',
	success: function (res) {
	    $('.srch1').html(res.hotel);
	    $('.pagerWrapper .fr').html(res.paging);
	    $('.srch_total_num').html("(" + res.total + ")");
	    $('.upload-overlay-loading-fix').hide();
	    initAddToBag();
	}
    });
}

function getsearchrelatedcategory() {
    $('.upload-overlay-loading-fix').show();
    var search = $('input[name="citys"]').val();
    var dest = $('input[name="dest"]').val();
    var hotelStars = new Array();
    $('input[name="hotelStar"]:checked').each(function () {
	hotelStars.push(this.value);
    });

    var hotelPrefrences = new Array();
    $('input[name="hotelPrefrences"]:checked').each(function () {
	hotelPrefrences.push(this.value);
    });

    var hotelPropertyType = new Array();
    $('input[name="propertyType"]:checked').each(function () {
	hotelPropertyType.push(this.value);
    });
    $.ajax({
	url: '/ajax/search_prefix_Hotel',
	data: {search: search, hotelStars: hotelStars, hotelPrefrences: hotelPrefrences, hotelPropertyType: hotelPropertyType, page: pagenumber, dest: dest, priceOrder: priceOrder, starsOrder: starsOrder},
	type: 'post',
	success: function (res) {
	    $('.srch1').html(res.hotel);
	    $('.pagerWrapper .fr').html(res.paging);
	    $('.srch_total_num').html("(" + res.total + ")");
	    $('.upload-overlay-loading-fix').hide();
	    initAddToBag();
	}
    });
}

function getsearchnearby() {
    $('.upload-overlay-loading-fix').show();
    var search = $('input[name="citys"]').val();
    var dest = $('input[name="dest"]').val();
    var hotelStars = new Array();
    $('input[name="hotelStar"]:checked').each(function () {
	hotelStars.push(this.value);
    });

    var hotelPrefrences = new Array();
    $('input[name="hotelPrefrences"]:checked').each(function () {
	hotelPrefrences.push(this.value);
    });

    var hotelPropertyType = new Array();
    $('input[name="propertyType"]:checked').each(function () {
	hotelPropertyType.push(this.value);
    });
    $.ajax({
	url: '/ajax/search_near_by_Hotel',
	data: {search: search, hotelStars: hotelStars, hotelPrefrences: hotelPrefrences, hotelPropertyType: hotelPropertyType, page: pagenumber, dest: dest, priceOrder: priceOrder, starsOrder: starsOrder},
	type: 'post',
	success: function (res) {
	    $('.srch1').html(res.hotel);
	    $('.pagerWrapper .fr').html(res.paging);
	    $('.srch_total_num').html("(" + res.total + ")");
	    $('.upload-overlay-loading-fix').hide();
	    initAddToBag();
	}
    });
}

function getsearchHotel() {
    $('.upload-overlay-loading-fix').show();
    var search = $('input[name="citys"]').val();
    var dest = $('input[name="dest"]').val();
    var hotelStars = new Array();
    $('input[name="hotelStar"]:checked').each(function () {
	hotelStars.push(this.value);
    });

    var hotelPrefrences = new Array();
    $('input[name="hotelPrefrences"]:checked').each(function () {
	hotelPrefrences.push(this.value);
    });

    var hotelPropertyType = new Array();
    $('input[name="propertyType"]:checked').each(function () {
	hotelPropertyType.push(this.value);
    });
    $.ajax({
	url: '/ajax/search_Hotels',
	data: {search: search, hotelStars: hotelStars, hotelPrefrences: hotelPrefrences, hotelPropertyType: hotelPropertyType, page: pagenumber, dest: dest, priceOrder: priceOrder, starsOrder: starsOrder},
	type: 'post',
	success: function (res) {
	    $('.srch1').html(res.hotel);
	    $('.pagerWrapper .fr').html(res.paging);
	    $('.srch_total_num').html("(" + res.total + ")");
	    $('.upload-overlay-loading-fix').hide();
	    initAddToBag();
	}
    });
}

function initFilterTriger() {
    var w = $(window).width();
    if (w <= 767) {
	$('.filter_results').parent().addClass('parentfilter');
    } else {
	$('.filter_results').parent().removeClass('parentfilter');
    }
}
