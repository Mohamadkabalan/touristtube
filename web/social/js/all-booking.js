var currentPage = 0;
var bookingType = '';
var showMore = 1;
var showMoreFlight = 1;
var query = 'both';

$(document).ready(function () {
    $(document).on('click', ".showmore_expand_allbook", function () {
	$('.othethiddendivs').show();
	$('#showmore_albook').hide();
	$('.paginationsection').show();
	showMore = 0
	return false;
    });
    $(document).on('click', ".paginationsectionflight .discover_a,.paginationsectionflight .prev_pg,.paginationsectionflight .next_pg,.paginationsectionflight .first_pg,.paginationsectionflight .last_pg, #flightShowMoreBtn", function () {
	var $this = $(this).closest('li');
	var data_page = $this.attr('data-page');
	var bookingType = $('#my-booking-filter').val();

	if (data_page == 0)
	    return;
	showMoreFlight = 0;
	query = 'flight';

	$.ajax({
	    url: generateLangURL('/my-bookings-search'),
	    data: {page: data_page, bookingType: bookingType, showMore: showMore, showMoreFlight: showMoreFlight, type: query},
	    type: 'post',
	    
	    success: function (res) {
		//          console.log(res);
		$('#flightWrap').html(res.flightBookings);

		if ($('.showmore_expand_allbook_flight').length <= 0) {
		    $('.paginationsectionflight .pagerWrapperflight').html(res.flightPaging);
		}
	    }
	});
	/*$.ajax({
	 url: '/api/flight/bookings',
	 data: {page: data_page, bookingType: bookingType},
	 type: 'post',
	 success: function (res) {
	 $('.paginationsectionflight .pagerWrapperflight').html(res.paging);
	 $('#flightWrap').html(res.data);
	 }
	 });*/
    });

    $(document).on('click', ".paginationsectionhotel .discover_a,.paginationsectionhotel .prev_pg,.paginationsectionhotel .next_pg,.paginationsectionhotel .first_pg,.paginationsectionhotel .last_pg", function () {
	var $this = $(this).closest('li');
	var data_page = parseInt($this.attr('data-page'));
	currentPage = data_page - 1;
	if (currentPage < 0)
	    currentPage = 0;
	query = 'hotel';
	getMyBookings();
    });

    $(document).on('change', '#my-booking-filter', function () {
	bookingType = $(this).val();
	currentPage = 0;
	query = 'both';
	getMyBookings();
    });

    if ($(".dealBookingList").length) {
	toggleDealBookingList();
    }

    $(document).on('click', '#showMoreInvoice', function () {
	var invoiceDate_from = $('#invoiceDate_from').val();
	var invoiceDate_to = $('#invoiceDate_to').val();
	getMyInvoices();
    })
    if ($('#invoiceDate_from').length > 0) {
	Calendar.setup({
	    trigger: "invoiceDate_from",
	    inputField: "invoiceDate_from",
	    dateFormat: "%d / %m / %Y",
	    onSelect: function () {
		this.hide()
	    }
	});

	Calendar.setup({
	    trigger: "invoiceDate_to",
	    inputField: "invoiceDate_to",
	    dateFormat: "%d / %m / %Y",
	    onSelect: function () {
		this.hide()
	    }
	});
    }
});

function getMyBookings() {
    $('.upload-overlay-loading-fix').show();

    var path = $("#myBookingsPath").val();
    $.ajax({
	url: generateLangURL(path),
	data: {page: currentPage, bookingType: bookingType, showMore: showMore, showMoreFlight: showMoreFlight, type: query},
	type: 'post',
	success: function (res) {
	    $('#my-bookings-hotels').html(res.hotelBookings);
	    $('.paginationsectionhotel .pagerWrapper').html(res.paging);
	    $('#flightWrap').html(res.flightBookings);
	    if ($("#my-deals-bookings").length) {
		$('#my-deals-bookings').html(res.dealBookings);
		toggleDealBookingList();
	    }

	    if ($('.showmore_expand_allbook_flight').length <= 0) {
		$('.paginationsectionflight .pagerWrapperflight').html(res.flightPaging);
	    }

	    $('.upload-overlay-loading-fix').hide();
	}
    });
}

function getMyInvoices() {
    $('.upload-overlay-loading-fix').show();
    var invoiceDate_from = $('#invoiceDate_from').val();
    var invoiceDate_to = $('#invoiceDate_to').val();
    $.ajax({
	url: generateLangURL(urlPrefix + '/my-invoices-search'),
	data: {page: currentPage, showMoreFlight: showMoreFlight, type: query, invoiceDate_from: invoiceDate_from, invoiceDate_to: invoiceDate_to},
	type: 'post',
	success: function (res) {
//            console.log(res);
	    //$('#my-bookings-hotels').html(res.hotelBookings);
	    //$('.paginationsectionhotel .pagerWrapper').html(res.paging);
	    $('#flightWrap').html(res.flightBookings);

	    if ($('.showmore_expand_allbook_flight').length <= 0) {
		$('.paginationsectionflight .pagerWrapperflight').html(res.flightPaging);
	    }

	    $('.upload-overlay-loading-fix').hide();
	}
    });
}

function toggleDealBookingList() {
    listCnt = $(".dealBookingList").size();
    if (listCnt <= 3) {
	start = listCnt;
	$("#dealsShowMoreBtn").hide();
    } else {
	start = 3;
    }

    $(".dealBookingList").slice(0, start).show();
    $('#dealsShowMoreBtn').click(function () {
	start = (start + 3 <= listCnt) ? start + 3 : listCnt;
	$(".dealBookingList").slice(0, start).show();
	if (start == listCnt) {
	    $("#dealsShowMoreBtn").hide();
	}
    });
}