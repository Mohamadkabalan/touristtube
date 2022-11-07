var dateRangePicker = null;

$(document).ready(function () {
    initDatePicker();
    //This will convert the currency loaded over it's converted value
    //changePriceCurrency();

    $("#searchButton").click(function () {
		filterMyBookings();
    });

    $("input[name='allCheckBox']").click(function () {
		if ($(this).is(':checked')) {
			if ($("input[name='attractionsCheckBox']").is(':checked')) {
				$("input[name='attractionsCheckBox']").removeAttr('checked');
			}
			if ($("input[name='hotelsCheckBox']").is(':checked')) {
				$("input[name='hotelsCheckBox']").removeAttr('checked');
			}
			if ($("input[name='flightsCheckBox']").is(':checked')) {
				$("input[name='flightsCheckBox']").removeAttr('checked');
			}
		}
    });

    $("input[name='attractionsCheckBox']").click(function () {

		if ($(this).is(':checked')) {
			if ($("input[name='allCheckBox']").is(':checked')) {
			$("input[name='allCheckBox']").removeAttr('checked');
			}
		}
    });

    $("input[name='hotelsCheckBox']").click(function () {

		if ($(this).is(':checked')) {
			if ($("input[name='allCheckBox']").is(':checked')) {
			$("input[name='allCheckBox']").removeAttr('checked');
			}
		}
    });

    $("input[name='flightsCheckBox']").click(function () {

		if ($(this).is(':checked')) {
			if ($("input[name='allCheckBox']").is(':checked')) {
			$("input[name='allCheckBox']").removeAttr('checked');
			}
		}
    });

    $(document).on('click', ".approval_pagination, .prev_pg, .next_pg, .first_pg, .last_pg", function () {
		var $this = $(this).closest('li');
		var $rowsect = $this.closest('.row-sect');
		var data_page = $this.attr('data-page');
		if (data_page == 0)
			return;
		var page = data_page;
		$("input[name=start_pg]").val(page);


		filterMyBookings();
		$('html,body').scrollTop(0);
    });
	
	var windowWidth = $(window).width();
	setTimeout(function(){ adjustColumnHeight(windowWidth); },500);
	
});

$(window).resize(function() {
	var windowWidth = $(window).width();
	setTimeout(function(){ adjustColumnHeight(windowWidth); },500);
});

function adjustColumnHeight(windowWidth){
	var myRows = $(document).find('div.copy_image_height');
	myRows.each(function(){
		var my_row = $(this);
		var my_image_height = Math.round( my_row.find('div.copy_height').find('img').height() );
		var my_image_half_height = Math.round(my_image_height/2) - 5;
		
		my_row.find('div.copy_height').css({'height':my_image_height+'px'});
		my_row.find('div.clone_height').css({'min-height': my_image_height+'px'});
		if(windowWidth<768){
		   my_row.find('div.custom_form').css({'min-height': '0px'});
		}
		my_row.find('div.clone_height').find('div.checkin_checkout_container').css({'height':my_image_half_height+'px'});
		my_row.find('div.clone_height').find('div.checkin_checkout_container').eq(1).css({'position': 'absolute','bottom': '0px','left': '5px','right': '5px'});
	});
}

function changePriceCurrency() {
    var toCurrency = $('.currency-item.selected').attr('currency-code-data');
    if (toCurrency !== 'USD') {
	changeSiteCurrency('USD', toCurrency, '.button_price_container .price-convert');
    }
}

function filterMyBookings() {

    showTTOverlay('my-bookings-results');
    var myData = new Object();
    myData['fromDate'] = $("input[name=fromDateC]").val();
    myData['toDate'] = $("input[name=toDateC]").val();
    myData['userId'] = $("input[name=userId]").val();
    myData['start'] = $("input[name=start_pg]").val();

    if ($("input[name='flightsCheckBox']").is(':checked')) {
	myData['flights'] = 1;
    }
    if ($("input[name='hotelsCheckBox']").is(':checked')) {
	myData['hotels'] = 1;
    }
    if ($("input[name='attractionsCheckBox']").is(':checked')) {
	myData['attractions'] = 1;
    }
	if ($("input[name='statusradio']").is(':checked')) {
		myData['status'] = $("input[name='statusradio']:checked").val();
	}
    var path = $("input[name='filterMyBookingUrl']").val();

    console.log(JSON.stringify(myData));

    $.ajax({
	url: path,
	type: 'POST',
	data: myData,
	success: function (result) {
		console.log(result);
	    hideTTOverlay();
	    $("#totalCount").html('');
	    $("#totalCount").html(result.totalCount);
	    $("#my-bookings-results").html('');
	    $("#paginationPages").html('');
	    if (result.totalCount > 0) {
		$("#paginationPages").html(result.pagination);
		$("#my-bookings-results").html(result.twigResponse);
	    } else {
		$("#my-bookings-results").html(Translator.trans('No Results found.'));
	    }
	    changePriceCurrency();
	},
	error: function (error) {
	    hideTTOverlay();
	}
    });
}

function initDatePicker() {
    var $datePicker = $('.datepicker');
    $datePicker.daterangepicker({
	singleDatePicker: false,
	autoApply: true,
	autoUpdateInput: false,
	opens: 'center',
	locale: {cancelLabel: 'Clear'}
    });

    //when changes are applied
    $datePicker.on('apply.daterangepicker', function (ev, picker) {
	var $this = $(this);
	var $fromDate = picker.startDate.format('YYYY-MM-DD');
	var $toDate = picker.endDate.format('YYYY-MM-DD');

	var fromDateInput = $('input[name=fromDateC]');
	fromDateInput.attr('value', $fromDate);
	var toDateInput = $('input[name=toDateC]');
	toDateInput.attr('value', $toDate);
    });
}
