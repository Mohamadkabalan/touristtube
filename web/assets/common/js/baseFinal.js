var MAX_ROOM_COUNT = 9;
var MAX_CHILD_COUNT = 6;
var ttModal=false;
var ttModal1=false;
$(document).ready(function () {    
    var windowWidth = $(window).width();

//    var flight_preloder = new Image();
//    var hotel_preloder = new Image();
//    var default_preloder = new Image();
//    flight_preloder.src = generateMediaURL("/media/images/preloader_flight.gif");
//    hotel_preloder.src = generateMediaURL("/media/images/preloader_hotels.gif");
//    default_preloder.src = "/assets/common/img/preloader-transparent.gif";

    $(document).on('click', 'body', function (e) {
        if (!$(e.target).is('.change_currency_button,.currencyselectbox_top, .currencyselectbox, .currencyselectbox *')) {
            $('.curnewtab').hide();
            $('.curnewtab_top').hide();
        }
	if (!$(e.target).is('.flagbacklangbarBut')) {
            $('.flagbacklangbarBut').removeClass('open');
            $('.dropdown_menu_flag').hide();
        }
    });
    
     $(document).on("click", ".data_media_remove_container .edit_bar_pic", function (e) 
    {
	e.preventDefault();
	var $this = $(this);
	var $parent = $this.closest('.data_media_remove_container');
	var data_id = $parent.attr('data-id');
	var data_type = $parent.attr('data-type');
	var channel_id = channelGlobalID();
	var $url = generateLangURL( '/edit_media_album', 'empty' )+'?id='+data_id+'&data_type='+data_type+'&channel_id='+channel_id;
	image_selected = $parent;
	createEditPopup( $url, Translator.trans("Edit info") );
    });
    
    $(document).on('click', '.flagbacklangbarBut', function (e) {
		e.preventDefault();
		var $this = $(this);
		var $parent = $this.closest('.dropdown');	
        $parent.find(".dropdown_menu_flag").slideToggle('fast', function () {
			if ($parent.hasClass('open')) {
				$parent.removeClass('open');
			} else {
				$parent.addClass('open');
			}
        });
    });

    $(document).on('click', '.currencyselectbox', function (e) {
        $('.curnewtab').toggle();
        e.stopPropagation();
    });
    $(document).on('click', '.currencyselectbox_top', function (e) {
        $('.curnewtab_top').toggle();
        e.stopPropagation();
    });
    
    $(document).on('click', ".data_media_remove", function (e) {
	e.preventDefault();
	if( !ttModal ) {
	    ttModal = window.getTTModal("myModalZ", {});
	}
        var curitem = $(this).closest('.data_media_remove_container');
	var pagetype = curitem.attr('data-type');
	var $id = curitem.attr('data-id');
        var typemedia = "album";
        if (pagetype == "v") {
            typemedia = "video";
        } else if (pagetype == "i") {
            typemedia = "photo";
        }
	
	ttModal.alert( sprintf(Translator.trans("confirm to remove permanently selected %s"), [typemedia]), function (btn) {
	    if(btn == "ok"){
		$('.upload-overlay-loading-fix').show();
		if (typemedia == "album") {
		    $.ajax({
			url: generateLangURL('/ajax/album_delete'),
			data: {id: $id},
			type: 'post',
			success: function (data) {
			    $('.upload-overlay-loading-fix').hide();
			    var jres = null;
			    try {
				jres = data;
				var status = jres.status;
			    } catch (Ex) {
			    }
			    if (!jres) {
				ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
				return;
			    }	    
			    
			    if (jres.status == 'ok') {
				location.reload();
			    } else {
				ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
			    }
			}
		    });
		} else {
		    $.ajax({
			url: generateLangURL('/ajax/media_delete'),
			data: {id: $id},
			type: 'post',
			success: function (data) {
			    $('.upload-overlay-loading-fix').hide();
			    var jres = null;
			    try {
				jres = data;
				var status = jres.status;
			    } catch (Ex) {
			    }
			    if (!jres) {
				ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
				return;
			    }	    
			    
			    if (jres.status == 'ok') {
				location.reload();
			    } else {
				ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
			    }
			}
		    });
		    
		}		
	    }
	}, null, {ok:{value:Translator.trans("confirm")}});
    });

    $('.lazy_image').lazy({
        placeholder: "data:image/gif;base64,R0lGODlhEALAPQAPzl5uLr9Nrl8e7..."
    });

    $(function () {
        try {
            //var flights = $.parseJSON($.cookie("TT-flight"));
            var flights = window.ttUtilsInst.getCookies('flight');
            if (flights) {
                flights = $.parseJSON(flights);
                flights = flights.filter;

                $.each(flights, function (key, data) {
                    if (data.name === "multidestination" && data.value === "1") {
                        $('.multipledestclass').trigger('click');
                        //$('.moreopclick').trigger('click');
                    } else if (data.name === 'oneway' && data.value === "1") {
                        $('.onewayclass').trigger('click');
                        //$('.moreopclick').trigger('click');
                    }

                    if (data.name === "multidestinationC" && data.value !== "") {
                        idx = data.value;
                        ind = 1;
                        if (ind < idx)
                        {
                            do
                            {
                                //$('.add_destination').trigger('click');
                                var next_departure_airport_name = '';
                                var next_departure_airport_code = '';
                                var next_arrival_airport_name = '';
                                var next_arrival_airport_code = '';
                                var next_departure_date = '';
                                var origin = Translator.trans('Origin');
                                var departure = Translator.trans('Departure airport / city');
                                var destination = Translator.trans('Destination');
                                var arrival = Translator.trans('Arrival airport / city');
                                var departing = Translator.trans('Departing');
                                var departureDate = Translator.trans('Departure date');
                                var dispID = ind + 1;
				 
                                var txtDisplay = '<div class="new_destination">' +
                                        '<!--div class="row martopbut5 no-margin"><p class="childclass_hp col-xs-12 nopad">Flight ' + dispID + '</p></div-->' +
                                        '<div class="temp_holder">' +
                                        '<div class="row no-margin">' +
                                        '<div class="col-sm-3 col-xs-12 newmoreopselectpadxs nopad">' +
                                        '<label class="search_input_label">' + origin + '</label>' +
                                        '<input id="departureairport_' + dispID + '" type="text" name="departureairport[]" class="departureairport inputsSearch" placeholder="' + departure + '" class="inputsSearch" value="' + next_departure_airport_name + '">' +
                                        '<input id="departureairportC_' + dispID + '" class="departureairportC" type="hidden" name="departureairportC[]" value="' + next_departure_airport_code + '">' +
                                        '</div>' +
                                        '<div class="col-sm-3 col-xs-12 newmoreopselectpadxs nopad">' +
                                        '<label class="search_input_label">' + destination + '</label>' +
                                        '<input id="arrivalairport_' + dispID + '" type="text" name="arrivalairport[]" class="arrivalairport inputsSearch" placeholder="' + arrival + '" class="inputsSearch" value="' + next_arrival_airport_name + '">' +
                                        '<input id="arrivalairportC_' + dispID + '" class="arrivalairportC" type="hidden" name="arrivalairportC[]" class="arrivalairportC" value="' + next_arrival_airport_code + '">' +
                                        '</div>' +
                                        '<div class="col-sm-3 col-xs-12 newmoreopselectpadxs nopad">' +
                                        '<label class="search_input_label">' + departing + '</label>' +
                                        '<input id="fromDate_' + dispID + '" type="text" name="fromDate[]" class="fromDate range_picker picker_single inputsSearch" placeholder="' + departureDate + '" value="' + next_departure_date + '">' +
                                        '</div>' +
                                        '<div class="col-sm-3 col-xs-12 newmoreopselectpadxs nopad">' +
                                        '<a href="javascript:;" class="remove_row"><i class="fas fa-times"></i></a>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="row martopbut5"></div>' +
                                        '</div>';

                                $("#multiDestinationContainer").append(txtDisplay);
                                destID++;
                                dispID++;
                                ind++;

                            } while (ind < idx);
                        }
                    }
                });

                var customFlight = [];
                if (window.SKIP_AUTO_LOAD_COOKIES_SEARCH_FORM_EXCEPT_FLIGHT_DEPARTURE) {
                    for (var idx in flights) {
                        var input = flights[idx];
                        if (input.id == "departureairport_1" || input.id == "departureairportC_1")
                            customFlight.push(input);
                        //
                        if (customFlight.length > 1) {
                            flights = customFlight;
                            break;
                        }
                    }
                }
				
                window.ttUtilsInst.jsonToForm(flights);
                //
                initAutocompleteAirports("departureairport");
                initAutocompleteAirports("arrivalairport");
            }

        } catch (err) {
            console.log(err);
        }
    });

    if ($(".things_to_do_input").length > 0) {
        initAutocompleteThingsToDo("things_to_do_input");
    }
    ;

    $('.txt_more_a').each(function (index, element) {
        var $this = $(this);
        var $selector = $("." + $this.attr('for'));
        var data_h = parseInt($selector.attr('data-h')) + 10;
        $selector.data('oHeight', $selector.height()).css('height', 'auto').data('nHeight', $selector.height()).height($selector.data('oHeight'));
        if ($selector.data('nHeight') <= data_h) {
            $this.remove();
        } else {
            $this.show();
        }
    });

    $(document).on("click", ".txt_more_a", function (e) {
        e.preventDefault();
        var $this = $(this);
        var $selector = $("." + $this.attr('for'));
        var data_h = parseInt($selector.attr('data-h'));
        if ($this.hasClass('more')) {
            $selector.data('oHeight', $selector.height()).css('height', 'auto').data('nHeight', $selector.height()).height($selector.data('oHeight')).animate({height: $selector.data('nHeight')}, 400);
            $this.html(Translator.trans('less'));
            $this.removeClass('more');
            if ($selector.data('nHeight') <= data_h) {
                $this.remove();
            }
        } else {
            $selector.animate({height: data_h + "px"}, 400);
            $this.addClass('more');
            $this.html(Translator.trans('more'));
        }
    });

//    $(document).on("click", ".moreopclick", function () {
//        return false;
//        var $this = $(this);
//        var $parentform = $this.closest('form');
//        var $thisholder = $this.attr("data-toggle");
//        var i = 0;
//        $parentform.find('.more_' + $thisholder).slideToggle('fast', function () {
//            if (i == 0) {
//                if ($this.hasClass('more')) {
//                    $this.html('<p class="mropclass lessclass">' + Translator.trans('less') + '</p>');
//                    $this.removeClass('more');
//                } else {
//                    $this.addClass('more');
//                    $this.html('<p class="mropclass ">' + Translator.trans('more') + '</p>');
//                }
//            }
//            i++;
//        });
//    });

    $(document).on("click", ".add_destination", function () {
        validateMultipleDestinationRow("#airplaneform");
    });

    $(document).on("click", ".remove_row", function () {
        var $this = $(this);
        $this.closest('div.new_destination').remove();
        destID--;
        if (destID == maxList) {
            $(".add_destination").hide();
        } else {
            $(".add_destination").show();
        }
    });

    $(document).on("click", ".flight_buts", function () {
        var $this = $(this);
        $(".flight_buts").removeClass("active");
        $this.addClass("active");
        //resetAllForms();

        if ($this.hasClass('roundtripclass')) {
            isMultiDestination = false;
            $('.fromDate').removeClass("picker_single");
            //$("#multiDestinationContainer").html("");
            $("#multiDestinationContainer").hide();
            $('.add_destination').hide();
            $('#toContainer').show();
            $('#flexibleDateContainer').show();
            $('#oneway').val(""); //Ask rudy about these values
            $('#multiDestination').val(""); //Ask rudy about these values
            $('#multiDestinationC').val("");
            initDateRangePicker(false);
			$("ul.checkboxul").show();
        } else if ($this.hasClass('onewayclass')) {
            isMultiDestination = false;
            $('.fromDate').addClass("picker_single");
            //$("#multiDestinationContainer").html("");
            $("#multiDestinationContainer").hide();
            $('.add_destination').hide();
            $('#toContainer').hide();
            $('#flexibleDateContainer').show();
            $('#oneway').val(1); //Ask rudy about these values
            $('#multiDestination').val(""); //Ask rudy about these values
            $('#multiDestinationC').val("");
            initDateRangePicker(true);
			$("ul.checkboxul").show();
        } else if ($this.hasClass('multipledestclass')) {
            isMultiDestination = true;
            $('.fromDate').addClass("picker_single");
            $("#multiDestinationContainer").show();
            $('.add_destination').show();
            $('#toContainer').hide();
            $('#flexibleDateContainer').hide();
            $('#oneway').val(""); //Ask rudy about these values
            $('#multiDestination').val(1); //Ask rudy about these values
            initDateRangePicker(true);
			$("ul.checkboxul").hide();
        }
    });

    $('#airplaneform').submit(function (e) {
        var $this = $(this);
        event = e;
        return validateFlightForm($this);
    });

    $('#airplanehotelform').submit(function (e) {
        var $this = $(this);
        event = e;
        return validateFlightForm($this);
    });

    initHotelConstants();
    $("#hotelform").submit(function (e) {
        var $this = $(this);
        event = e;
        return validateHotelsForm($this, true);
    });

    $("#dealsform").submit(function (e) {
        var $this = $(this);
        event = e;
        return validateDealsForm($this);
    });

    if ($('.onewayclass').hasClass("active")) {
        $('#toContainer').hide();
    }

    $(document).on('click', '.incrementCount', function () {
        var $this = $(this);
        var maxval = $this.attr("data-max");
        var relatedField = $this.attr("data-related");
        var myobj = $this.parent().find("input.inputData");
        incrementCount(myobj, maxval);
        myobj.trigger('change', true);
        if ((relatedField) && (relatedField == 'children')) {
            updateChildCount();
        }
    });

    $(document).on('click', '.decrementCount', function () {
        var $this = $(this);
        var minval = $this.attr("data-min");
        var relatedField = $this.attr("data-related");
        var myobj = $this.parent().find("input.inputData");
        decrementCount(myobj, minval);
        myobj.trigger('change', true);
        if (relatedField == 'children') {
            updateChildCount();
        }
    });

    //On change of Children
    $(document).on('change', '#childCount', function () {
        updateChildCount();
    });

    //On change of number of single rooms, compute adult count and fill it
    $(document).on('change', '#singleRooms', function () {
        calculateAdultCount();
    });

    //On change of number of double rooms, bide/show children option then compute adult count and fill it
    $(document).on('change', '#doubleRooms, #counter-2', function (event, initFlag) {
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
    $(document).on('change', '#adultCount, #counter-2', function () {
        $('.error_cnt_corporate').html('');
        var single = parseInt($('#singleRooms').val()) || 0;
        var double = parseInt($('#doubleRooms').val()) || 0;
        var adultCount = parseInt($('#adultCount').val());
        var maxAdultCount = single + (double * 2);
        var maxAdultWithExtraBed = single + (double * 4)
		
        if (adultCount > maxAdultCount && (adultCount - maxAdultCount) == 1) {
            showErrorMsg('The number of adult guests does not correspond to the number of rooms. Are you sure you want to add extra bed(s)?');
        }
    });

    $(document).on("click", ".h3_flight_booking_but", function () {
        var $this = $(this);
        resetFlightForm();
        $('.arrivalairport').val($this.attr('data-name'));
        $('.arrivalairportC').val($this.attr('data-code'));
        window.scrollTo(0, 0);
    });

    $(document).on("click", ".image_searchbox", function () {
        var $this = $(this);
        var thisHolder = $this.attr("data-holder");
        $(".main_holder").hide();
        $("." + thisHolder).show();
        $(".image_searchbox").removeClass("active");
        $this.addClass("active");

        if (thisHolder == 'hotels_container') {
            $("." + thisHolder).find('.fromDate').removeClass("picker_single");
            isSingle = false;
            isMultiDestination = false;
        }

        initDateRangePicker(false);

        initAutocompleteAirports("departureairport");
        initAutocompleteAirports("arrivalairport");
        initAutoCompleteListHotels("searchdiscover");
        addAutoCompleteListDeals("searchattractions");
    });

    setTimeout(function () {
        initDateRangePicker(false);
    }, 500);
    
    if( $('.image_searchbox').length==1 )
    {
	$('.image_searchbox').click();
	$('.absoluteimageconatiner_images').width("123px");
    }
    
    initAutocompleteAirports("departureairport");
    initAutocompleteAirports("arrivalairport");
    initAutoCompleteListHotels("searchdiscover");
    addAutoCompleteListDeals("searchattractions");
    initDisplayRating();
    init();

    inputsSearchBlur();    
    
    if ($("#home_pano_banner").length > 0) {
        setHomePanoBanner();
    }
    
    if( $('.property_add_container').length >0 ) 
    {
	if( !ttModal ) {
	    ttModal = window.getTTModal("myModalZ", {});
	}
	addAutoCompleteListCurrentCity( $('.property_add_container').attr('id') );
    }
	
    $(document).on('click', ".request_form_eval_options", function () {	    
	var $this = $(this);
	var $index = $this.index();
	var $parents = $this.closest('.ratestars_container');
	var data_value = $this.attr('data-value');
	var activedata_value = $parents.find('.request_form_eval_options.active:last').attr('data-value');
	if ($parents.find('.request_form_eval_options.active:last').length > 0 && activedata_value == data_value) {
	    data_value = 0;
	    $index = -1;
	}

	$parents.find('.request_form_eval_options').removeClass('active');
	$this.addClass('active');
	$parents.find(".request_form_eval_options").each(function () {
	    var newindex = $(this).index();
	    if (newindex <= $index) {
		$(this).addClass('active');
	    } else {
		$(this).removeClass('active');
	    }
	});
    });	

    $(document).on('click', ".req_360_send_message_btn", function ()
    {
	if ( validateRequest360Form() ) 
	{
	    $('.upload-overlay-loading-fix').show();
	    var property_info_list = [];
	    $('.property_add_container').each(function()
	    {
		var $this = $(this);
		var $property_name = $this.find("input[name=property_name]").val().trim();
		var $country       = $this.find("select[name=country]").val().trim();		
		var $countryiso3  = $this.find("select[name=country]");		
		var $country_iso3  = $countryiso3.find(":selected").attr('data-code');
		var $city          = $this.find("input[name=city]").val().trim();
		var $city_id       = $this.find("input[name=city]").attr('data-id');
		var $rating_value  = $this.find(".request_form_eval_options.active:last").attr('data-value');
		
		if( $property_name != '' )
		{
		    var $item_list = {};
		    $item_list['property_name'] = $property_name;
		    $item_list['country'] = $country;
		    $item_list['country_iso3'] = $country_iso3;
		    $item_list['city'] = $city;
		    $item_list['city_id'] = $city_id;
		    $item_list['rating_value'] = $rating_value;
		    property_info_list.push( $item_list );
		}
	    });
	    var jsonString = JSON.stringify(property_info_list);

	    var name = $("input[name=contact_name]", $('#get_property_360_form')).val().trim();
	    var email = $("input[name=email]", $('#get_property_360_form')).val().trim();
	    var dialing_code = $("select[name=dialing_code]", $('#get_property_360_form')).val().trim();
	    var dialing_code_iso3 = $("select[name=dialing_code]", $('#get_property_360_form')).find(":selected").attr('data-code').trim();
	    var phone = $("input[name=phone]", $('#get_property_360_form')).val().trim();
	    var msg = $("textarea[name=msg]", $('#get_property_360_form')).val().trim();

	    $.ajax({
		url: generateLangURL( '/ajax/get-your-property-in-360', 'empty' ),
		data: {
		    property_info_list: jsonString,
		    name: name,
		    email: email,
		    dialing_code: dialing_code,
		    dialing_code_iso3: dialing_code_iso3,
		    phone: phone,
		    msg: msg
		},
		type: 'post',
		success: function (data) {
		    $('.upload-overlay-loading-fix').hide();
		    var jres = null;
		    try {
			jres = data;
			var status = jres.status;
		    } catch (Ex) {
		    }
		    if (!jres) {
			return;
		    }
		    ttModal.alert(jres.msg, null, null, {ok:{value:"close"}});
		    document.getElementById('get_property_360_form').reset();
		    $(".request_form_eval_options", $('#get_property_360_form')).removeClass('active');
		}
	    });
	}
    });

    $(document).on('click', ".req_360_add_property_btn", function ()
    {
	$('.upload-overlay-loading-fix').show();
	$("input[name=rating_value]", $('#get_property_360_form')).val($(".property_add_container .request_form_eval_options.active:last").attr('data-value'))
	$("input[name=city_id]", $('#get_property_360_form')).val($(".property_add_container input[name=city]").attr('data-id'))
	document.getElementById('get_property_360_form').submit();
    });
    
});

function validate_alpha(myValue) {
    var regx = /^[A-Za-z][-a-zA-Z ]+$/;
    if (myValue == "") {
        return false;
    } else if (!regx.test(myValue)) {
        return false;
    } else {
        return true;
    }
}

function validate_alphanumeric(myValue) {
    var letters = /^[0-9a-zA-Z]+$/;
    if (myValue.value.match(letters)) {
        return true;
    } else {
        return false;
    }
}

function resetFlightForm() {
    $('input').val('');
    $.cookie('flight', null);
    $("input:text").removeClass("error");
    $("#airplaneform").reset();
    $('.roundtripclass').click();
}

var maxList = 4;
var destID = 1;

function validateMultipleDestinationRow(myHolder, autoAddRow) {

    autoAddRow = (autoAddRow != false);

    var departureairportList = $(myHolder).find("input.departureairport");
    var arrivalairportList = $(myHolder).find("input.arrivalairport");
    var fromDateList = $(myHolder).find("input.fromDate");
    var fromDateListLength = fromDateList.length;
    var lastItem = destID - 1;//fromDateListLength-2;

    var departureAirport = departureairportList.eq(lastItem).val();
    var arrivalAirport = arrivalairportList.eq(lastItem).val();
    var lastDate = fromDateList.eq(lastItem).val();

    if (departureAirport == '') {
        showErrorMsg('Invalid departure airport');
        return false;
    } else if (arrivalAirport == '') {
        showErrorMsg('Invalid arrival airport');
        return false;
    } else if (lastDate == '') {
        showErrorMsg('Invalid departure date');
        return false;
    } else if (autoAddRow) {
        addNewMultiDestination();
    }

}

function addNewMultiDestination() {
    getLastArrivalAirport();
    var id = ($("#airplaneform").find('.departureairport').length) + 1;
    var origin = Translator.trans('Origin');
    var departure = Translator.trans('Departure airport / city');
    var destination = Translator.trans('Destination');
    var arrival = Translator.trans('Arrival airport / city');
    var departing = Translator.trans('Departing');
    var departureDate = Translator.trans('Departure date');
    //
    var txtDisplay = '<div class="new_destination">' +
            '<!--div class="row martopbut5 no-margin"><p class="childclass_hp col-xs-12 nopad">Flight ' + destID + '</p></div-->' +
            '<div class="temp_holder">' +
            '<div class="row no-margin">' +
            '<div class="col-sm-3 col-xs-12 newmoreopselectpadxs nopad">' +
            '<label class="search_input_label">' + origin + '</label>' +
            '<input id="departureairport_' + id + '" type="text" name="departureairport[]" class="departureairport inputsSearch" placeholder="' + departure + '" class="inputsSearch" value="' + next_departure_airport_name + '">' +
            '<input id="departureairportC_' + id + '" class="departureairportC" type="hidden" name="departureairportC[]" value="' + next_departure_airport_code + '">' +
            '</div>' +
            '<div class="col-sm-3 col-xs-12 newmoreopselectpadxs nopad">' +
            '<label class="search_input_label">' + destination + '</label>' +
            '<input id="arrivalairport_' + id + '" type="text" name="arrivalairport[]" class="arrivalairport inputsSearch" placeholder="' + arrival + '" class="inputsSearch" value="">' +
            '<input id="arrivalairportC_' + id + '" class="arrivalairportC" type="hidden" name="arrivalairportC[]" class="arrivalairportC" value="">' +
            '</div>' +
            '<div class="col-sm-3 col-xs-12 newmoreopselectpadxs nopad">' +
            '<label class="search_input_label">' + departing + '</label>' +
            '<input id="fromDate_' + id + '" type="text" name="fromDate[]" class="fromDate range_picker picker_single inputsSearch" placeholder="' + departureDate + '" value="" readonly />' +
            '</div>' +
            '<div class="col-sm-3 col-xs-12 newmoreopselectpadxs nopad">' +
            '<a href="javascript:;" class="remove_row"><i class="fas fa-times"></i></a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="row martopbut5"></div>' +
            '</div>';

    if (destID <= maxList) {
        $("#multiDestinationContainer").append(txtDisplay);
        destID++;
    }
    if (destID == maxList) {
        $(".add_destination").hide();
    } else {
        $(".add_destination").show();
    }

    isMultiDestination = true;
    initStartDate = myMultipleDateStart;
    startDate = getLastSelectedDate("#airplaneform");
    myMultipleDateStart = (startDate) ? startDate : initStartDate;
    initDateRangePicker(true);

    //destroyAutocomplete("departureairport");
    //destroyAutocomplete("arrivalairport");
    initAutocompleteAirports("departureairport");
    initAutocompleteAirports("arrivalairport");
}

function getLastSelectedDate(myHolder) {
    var fromDateList = $(myHolder).find("input.fromDate");
    var fromDateListLength = fromDateList.length;
    var lastItem = fromDateListLength - 2;
    var lastDate = fromDateList.eq(lastItem).val();
    lastDate = (lastDate) ? convertmyDate(lastDate) : '';
    return lastDate;
}

function getDateBeforeCurrentCal($datePicker) {
    var lastCalDate = getTodatDate();
    var returnVal = lastCalDate;
    $("input.fromDate").each(function () {
        var $this = $(this);
        if ($this.attr('id') == $datePicker.attr('id'))
        {
            returnVal = lastCalDate;
        }
        var lastDate = $this.val();
        lastCalDate = (lastDate) ? convertmyDate(lastDate) : lastCalDate;
    });

    return returnVal;
}

function checkBedForAge(childNumber) {
    var age = $('#childAge' + childNumber);
    var bed = $('#childBed' + childNumber);
    if (age.val() > 13) {
        var option = $('<option>').attr("value", "extraBed").text("In extra bed");
        bed.empty().append(option);
    } else {
        var option1 = $('<option>').attr("value", "parentsBed").text("In parents' bed");
        var option2 = $('<option>').attr("value", "extraBed").text("In extra bed");
        bed.empty();
        bed.append(option1);
        bed.append(option2);
    }
}

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

function calculateAdultCount() {
    var single = parseInt($('#singleRooms').val()) || 0;
    var double = parseInt($('#doubleRooms').val()) || 0;
    var adultCount = single + (double * 2);
    $('#adultCount').val(adultCount);
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

function updateChildCount() {
    var childCount = $('#childCount').val();
    if (childCount == "0") {
        $('#guest-option').hide();
    } else if (parseInt(childCount) > 6) {
        showErrorMsg('No more than 6 children per reservation is allowed.');
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

function showErrorMsg(msg) {
    var transMsg = Translator.trans(msg);
    var found = false;
    $("[id*=ttModal_]").each(function () {
        var message = $(this).find("[data-notify='message']").html();
        if (message === transMsg) {
            found = true;
        }
    });
    if (found === false) {
        window.showTTNotify('', transMsg, "error", {placement: {from: "top", align: "right"}});
    }
}

function incrementCount(myobj, maxval) {
    myobj.val(function (i, oldval) {
        return (++oldval < maxval) ? oldval : maxval;
    });
}

function decrementCount(myobj, minval) {
    myobj.val(function (i, oldval) {
        return (--oldval > minval) ? oldval : minval;
    });
}

function validate($this) {
    var valid = true;
    var destination = $this.find('.searchdiscover').val();
    var validDestination = $this.find('.hotelCityName').val();
    if (destination == '') {
        showErrorMsg('Please select your destination.');
        valid = false;
    } else if (validDestination == '') {
        showErrorMsg('Please select a City/Hotel that best match your destination.');
        valid = false;
    }

    var dateFormat = 'YYYY-MM-DD';

    var fromDateH = $this.find('.fromDate').val();
    var validFromDate = moment(fromDateH, dateFormat, true);

    var toDateH = $this.find('.toDate').val();
    var validToDate = moment(toDateH, dateFormat, true);

    if (fromDateH == '' && toDateH == '') {
        showErrorMsg('Select check-in and check-out dates.');
        valid = false;
    } else if (!validFromDate.isValid() || !validToDate.isValid()) {
        showErrorMsg('Invalid Check-In/Check-Out date.');
        valid = false;
    }

    var currentDate = moment(0, "HH");
    if (valid && (validToDate.isSame(validFromDate) || validToDate < validFromDate || validFromDate < currentDate || validToDate <= currentDate)) {
        showErrorMsg('Invalid Check-In/Check-Out date.');
        valid = false;
    }

    var single = parseInt($this.find('#singleRooms').val()) || 0;
    var double = parseInt($this.find('#doubleRooms').val()) || 0;
    var adultCount = parseInt($this.find('#adultCount').val());
    var childCount = parseInt($this.find('#childCount').val());

    if ((single == 0) && (double == 0)) {
        showErrorMsg('Please input the number of rooms.');
        valid = false;
    } else if ((single + double) > MAX_ROOM_COUNT) {
        showErrorMsg('No more than 9 rooms per reservation is allowed.');
        valid = false;
    } else {
        if ((adultCount + childCount) < (single + double)) {
            showErrorMsg('The number of guests does not correspond to the number of rooms.');
            valid = false;
        } else if ((adultCount + childCount) > (single + (double * 4))) {
            showErrorMsg('The number of guests does not correspond to the number of rooms.');
            valid = false;
        }

        if (childCount > (double * 2)) {
            showErrorMsg('No more than 2 children per double room is allowed.');
            valid = false;
        } else if (childCount > double) {
            //review that only one child per double room is in parents bed
            var parentsBed = 0;
            for (var ctr = 1; ctr <= childCount; ctr++) {
                var bed = $('#childBed' + ctr).val();
                if (bed == 'parentsBed') {
                    parentsBed++;
                    if (parentsBed > double) {
                        showErrorMsg('No more than 1 child in parent\'s bed per double room is allowed.');
                        valid = false;
                        break;
                    }
                }
            }
        }
    }
    return valid;
}

function validateDealsForm($this, myValid) {
    var ccity = $this.find('.searchattractions').val();
    if (ccity == '') {
        showErrorMsg('invalid city or destination');
        return false;
    }
    showLoaderOverlay();
    return true;
}
function validateHotelsForm($this, myValid) {
    var valid = (myValid) ? validate($this) : true;
    var hotelId = $this.find('.hotelId').val();
    var detailspage = $this.find('.detailspage').val();
    var name = $this.find('.hotelCityName').val();

    var hotelNameURL = getCleanTitle(name);
    var url = './hotel-details-' + hotelNameURL + '-' + hotelId;
    if(window.hotel_route_path_hotel_details){
	url = window.hotel_route_path_hotel_details.replace('{name}', hotelNameURL).replace('{id}', hotelId);
    }
    
    /*var url = './hotel-details-' + hotelNameURL + '-' + hotelId;

    if (window.hotels.pageSrc.hotels) {
        url = './hotel-detailsTT-' + hotelNameURL + '-' + hotelId;
    }*/
    //console.log(valid);
    //return false;

    if (!valid) {
        //hideHotelOverlay();
        return false;
    } else if (hotelId > 0 && detailspage == 1) {
        showHotelOverlay();
        var formPath = generateLangURL(url);
        $this.attr('action', formPath);
        $this.attr('method', 'post');
        return true;
        /*setTimeout(function(){
         var formPath = generateLangURL(url);
         $this.attr('action', formPath);
         $this.attr('method', 'post');
         return true;
         },500);*/
    } else {
        showHotelOverlay();
        return true;
    }
}

function inputsSearchBlur() {
    //console.log('init inputsSearchBlur()');
    $(document).on('blur', '.inputsSearch', function () {
        var $this = $(this);
        var $thisVal = $this.val();
        var $nextThis = $this.parent().find('input[type="hidden"]');
        if (($nextThis.length > 0) && ($thisVal == '')) {
            $nextThis.val('');
            $nextThis.removeAttr("value");
        }
    });
}

/*validate the date date format YYYY-MM-DD*/
function validateMyDate(myDate) {
    var date_regex = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
    if (date_regex.test(myDate)) {
        return true;
    } else {
        return false;
    }
}

var event;
function validateFlightForm($this) {
    //
    if (validateMultipleDestinationRow("#" + $this.attr("id"), false) == false){
		return false;
	}
	
    $('.error_cnt_corporate').html('');
    $('input').removeClass('srch_error');
    var firstdepartureairport = $this.find('.departureairportC').eq(0);
    var firstdepartureairport_slug = $this.find(".departureairport").eq(0);
    var departureairport_slug = firstdepartureairport_slug.val();
    var departureairport = firstdepartureairport.val();
    var $i = 0;
	
	if ( $('.multipledestclass').hasClass("active") && (destID==1) ) {
		showErrorMsg('please add at least one destination');
		$i++;
	}
	
    if ((departureairport == '') || (departureairport_slug == '')) {
        event.preventDefault();
        showErrorMsg('invalid origin airport');
        firstdepartureairport.addClass('srch_error');
        $i++;
        //return;
    }

    var firstarrivalairport = $this.find('.arrivalairportC').eq(0);
    var firstarrivalairport_slug = $this.find(".arrivalairport").eq(0);
    var arrivalairport_slug = firstarrivalairport_slug.val();
    var arrivalairport = firstarrivalairport.val();
    if ((arrivalairport == '') || (arrivalairport_slug == '') || (arrivalairport == departureairport)) {
        event.preventDefault();
        firstarrivalairport.val('');
        firstarrivalairport_slug.val('');
        showErrorMsg('invalid destination airport');
        firstarrivalairport.addClass('srch_error');
        $i++;
        //return;
    }

    var firstfromDate = $this.find('.fromDate').eq(0);
    var fromDate = firstfromDate.val();
    // console.log(fromDate);
    // console.log(validateMyDate(fromDate));
    if (fromDate == '' || !validateMyDate(fromDate)) {
        showErrorMsg('invalid departure date');
        event.preventDefault();
        firstfromDate.addClass('srch_error');
        $i++;
        //return;
    }

    var firsttoDate = $this.find('.toDate').eq(0);
    var toDate = firsttoDate.val();
    //console.log( toDate );
    //console.log( validateMyDate(toDate) );
    if ($('.roundtripclass').hasClass("active") && (toDate == '' || !validateMyDate(toDate))) {
        showErrorMsg('invalid return date');
        event.preventDefault();
        firsttoDate.addClass('srch_error');
        $i++;
        //return;
    }

    var adultsselect = $this.find('#adultsselect').val();
    var infantsselect = $this.find('#infantsselect').val();
    if (adultsselect || infantsselect) {
        if (adultsselect < infantsselect) {
            showErrorMsg('The number of infants must not be greater than the number of adults!');
            event.preventDefault();
            $('#adultsselect').addClass('srch_error');
            $('#infantsselect').addClass('srch_error');
            $i++;
            //return;
        }
    }

    /*var formData = $this.serializeArray();
     $.cookie.raw = true;
     $.cookie.json = true;
     $.cookie('flight', JSON.stringify(formData));*/

    if ($i > 0) {
        return false;
    } else {
        showFlightOverlay();
        localStorage.removeItem('flightsFilters');
        window.ttUtilsInst.deleteCookies('flight');
        if ($('#multiDestination').val() === "") {
            $("#multiDestinationContainer").html("");
            $('#multiDestinationC').val("");
        } else {
            $('#multiDestinationC').val($('.departureairport').length);
        }

        var flexibledate = $('#flexibledate').is(':checked');

        if (flexibledate) {
            $('#flexibledate').attr('checked', 'checked');
        }

        var formJSON = window.ttUtilsInst.formToJson('#airplaneform');
        var flightJSON = {'filter': formJSON, 'sort': {'name': '', 'order': ''}};
        
        window.ttUtilsInst.setCookies('flight', JSON.stringify(flightJSON));

        //$('.upload-overlay-loading-fix').addClass('flightsLoader');
        //$('.upload-overlay-loading-fix').show();
    }
}

function initDisplayRating() {
    var allStarts = $(document).find("span.star_rating");
    allStarts.each(function () {
        var $this = $(this);
        var $thisRating = ($this.attr("data-rating")) ? $this.attr("data-rating") : 0;
        var $thisColor = $this.attr("data-color");
        var $thisFont = $this.attr("data-font");
        var lastStar = '';

        if ($this.html() === '') {
            /*check if its is decimale*/
            var $myRarting = $thisRating.split(".");
            if ($myRarting[1]) {
                lastStar = '<i class="fas fa-star-half"></i>';
                $thisRating = $myRarting[0];
            }
            for (var i = 0; i < $thisRating; i++) {
                $this.append('<i class="fas fa-star"></i>');
            }
            $this.append(lastStar);
            if ($thisColor) {
                $this.css({'color': $thisColor});
            }
            if ($thisFont) {
                $this.css({'font-size': $thisFont + "px"});
            }
        }
    });
}

function resetAllForms() {
    $(document).find("form").trigger("reset");
}

function resetDisplayTabs() {
    //$(".hide_on_exit").hide();
    //$(".moreopclick").addClass('more').html('<p class="mropclass ">' + Translator.trans('more') + '</p>');
    //resetAllForms();
}

function getTodatDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var today = mm + '/' + dd + '/' + yyyy;
    return today;
}

function convertmyDate(myDate) {
    var today = new Date(myDate);
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var today = mm + '/' + dd + '/' + yyyy;
    return today;
}

function returnSimpleDate(myDate) {
    var mydateArray = myDate.split('-');
    var dateSTR = mydateArray[2] + '/' + mydateArray[1] + '/' + mydateArray[0];
    return dateSTR;
}

function validateEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var result = re.test(email);
    return result;
}

function validateThisForm($this) {
    var myForm = $this;
    var i = 0;
    myForm.find('select.required').each(function () {
        var myInput = $(this);
        var myInputVal = myInput.val();
        var dataAlert = myInput.attr('data-alert');
        if (myInputVal == '' || myInputVal == 0) {
            i++;
            showErrorMsg(dataAlert + ' is required');
        }
    });

    myForm.find('input.required').each(function () {
        var myInput = $(this);
        var myInputVal = myInput.val();
        var myInputType = myInput.attr('data-type');
        var dataAlert = myInput.attr('data-alert');

        if (myInputVal == '') {
            i++;
            showErrorMsg(dataAlert + ' is required');
        } else if (myInputType == 'email') {
            if (validateEmail(myInputVal) !== true) {
                showErrorMsg(dataAlert + ' is invalid');
            }
        }

    });
    if (myForm.find("input.terms").length > 0) {
        if (!$(myForm.find("input.terms").is(':checked'))) {
            showErrorMsg('Please accept our policy.');
        }
        i++;
    }
    if (i > 0) {
        return false;
    }
    return true;

}

var myMultipleDateStart = getTodatDate();
var isMultiDestination = false;
var startDate = getTodatDate();
var isSingle = false;
/*
 function initDateRangePicker(isSingle) {
 if ($(document).find('.range_picker').length > 0) {
 if( $(document).find('.range_picker').hasClass('picker_single') ){
 isSingle = true;
 }
 var $datePicker = $('.range_picker');
 $datePicker.daterangepicker({
 singleDatePicker: isSingle,
 autoApply: true,
 autoUpdateInput: false,
 opens: 'left',
 minDate: (isMultiDestination) ? myMultipleDateStart : getTodatDate(),
 startDate: startDate,
 locale: {cancelLabel: 'Clear'}
 });
 
 $datePicker.on('apply.daterangepicker', function (ev, picker) {
 var $this = $(this);
 var $fromDate = picker.startDate.format('YYYY-MM-DD');
 var $fromDateStan = picker.startDate.format('MM-DD-YYYY');
 var $toDate = picker.endDate.format('YYYY-MM-DD');
 var $toDateStan = picker.endDate.format('MM-DD-YYYY'); 
 
 var $thisBrother = '';
 if ($this.hasClass("fromDate")) {
 $this.val($fromDate);
 $this.attr('value',$fromDate);
 $thisBrother = $this.closest('div.row').find('.toDate');
 $thisBrother.val($toDate);
 $thisBrother.attr('value',$toDate);
 } else if ($this.hasClass("toDate")) {
 $this.val($toDate);
 $this.attr('value',$toDate);
 $thisBrother = $this.closest('div.row').find('.fromDate');
 $thisBrother.val($fromDate);
 $thisBrother.attr('value',$fromDate);
 }
 if(!isMultiDestination) {
 $thisBrother.data('daterangepicker').setStartDate($fromDateStan);
 $thisBrother.data('daterangepicker').setEndDate($toDateStan);
 }
 myMultipleDateStart = $fromDateStan;
 if($this.attr('id') == 'fromDate_1') { 
 startDate = $fromDateStan;
 }
 if(isMultiDestination){
 //initDateRangePicker(true);
 }
 });
 
 $datePicker.each(function(){
 var $this = $(this);
 if($this.hasClass("fromDate")){
 $fromDate = $this.val();
 $thisBrother = $this.closest('div.row').find('.toDate');
 $toDate = $thisBrother.val();
 }else if($this.hasClass("toDate")){
 $toDate = $this.val();
 $thisBrother = $this.closest('div.row').find('.fromDate');
 $fromDate = $thisBrother.val();
 }
 if($fromDate!=''){
 $this.data('daterangepicker').setStartDate( convertmyDate($fromDate) );
 }
 if($toDate!=''){
 $this.data('daterangepicker').setEndDate( convertmyDate($toDate) );
 }
 if($this.hasClass("picker_single")){
 var newStartDate = $this.data('daterangepicker').startDate.format('MM-DD-YYYY');
 $this.data('daterangepicker').setEndDate(newStartDate);
 }
 })
 
 }
 }
 */

function getMaxDate(year) {
    var inNumYears = new Date();
    if (year) {
        inNumYears.setFullYear(inNumYears.getFullYear() + year);
    }
    return (inNumYears.getMonth() + 1) + '/' + inNumYears.getDate() + '/' + inNumYears.getFullYear();
}

function initDateRangePicker(isSingle) {
    //console.log(isSingle);
    if ($(document).find('.range_picker').length > 0) {
        $range_picker = $(document).find('.range_picker');
        $range_picker.each(function () {

            var $datePicker = $(this);
            //console.log($datePicker.attr('id'));
            if ($datePicker.hasClass('picker_single')) {
                //console.log('isSingle');
                isSingle = true;
            } else {
                isSingle = false;
            }
            //console.log(isSingle);

            if (isMultiDestination && isSingle)
            {
                initStartDate = myMultipleDateStart;
                startDate = getDateBeforeCurrentCal($datePicker);
                myMultipleDateStart = (startDate) ? startDate : initStartDate;
            }
            $datePicker.daterangepicker({
                singleDatePicker: isSingle,
                autoApply: true,
                autoUpdateInput: false,
                opens: 'left',
                minDate: (isMultiDestination) ? myMultipleDateStart : getTodatDate(),
                maxDate: getMaxDate(1),
                startDate: startDate,
                locale: {cancelLabel: 'Clear'}
            });

            $datePicker.on('apply.daterangepicker', function (ev, picker) {
                var $this = $(this);
                var $fromDate = picker.startDate.format('YYYY-MM-DD');
                var $fromDateStan = picker.startDate.format('MM-DD-YYYY');
                var $toDate = picker.endDate.format('YYYY-MM-DD');
                var $toDateStan = picker.endDate.format('MM-DD-YYYY');

                var $thisBrother = '';
                if ($this.hasClass("fromDate")) {
                    $this.val($fromDate);
                    $this.attr('value', $fromDate);
                    $thisBrother = $this.closest('div.row').find('.toDate');
                    $thisBrother.val($toDate);
                    $thisBrother.attr('value', $toDate);
                } else if ($this.hasClass("toDate")) {
                    $this.val($toDate);
                    $this.attr('value', $toDate);
                    $thisBrother = $this.closest('div.row').find('.fromDate');
                    $thisBrother.val($fromDate);
                    $thisBrother.attr('value', $fromDate);
                }
                if (!isMultiDestination) {
                    $thisBrother.data('daterangepicker').setStartDate($fromDateStan);
                    $thisBrother.data('daterangepicker').setEndDate($toDateStan);
                }
                myMultipleDateStart = $fromDateStan;
                if ($this.attr('id') == 'fromDate_1') {
                    startDate = $fromDateStan;
                }
                if (isMultiDestination) {
                    //initDateRangePicker(true);
                }
            });

            var $this = $datePicker;
            if ($this.hasClass("fromDate")) {
                $fromDate = $this.val();
                $thisBrother = $this.closest('div.row').find('.toDate');
                $toDate = $thisBrother.val();
            } else if ($this.hasClass("toDate")) {
                $toDate = $this.val();
                $thisBrother = $this.closest('div.row').find('.fromDate');
                $fromDate = $thisBrother.val();
            }
            if ($fromDate != '') {
                $this.data('daterangepicker').setStartDate(convertmyDate($fromDate));
            }
            if ($toDate != '') {
                $this.data('daterangepicker').setEndDate(convertmyDate($toDate));
            }
            if ($this.hasClass("picker_single")) {
                var newStartDate = $this.data('daterangepicker').startDate.format('MM-DD-YYYY');
                $this.data('daterangepicker').setEndDate(newStartDate);
            }
        })

    }
}


function addAutoCompleteListDeals(wich) {
    var $this = $(document).find('.' + wich);
    $this.each(function () {
        var $thisParent = $this.parent();
        var $thisParentID = $thisParent.attr("id");
        $this.autocomplete({
            minLength: minSearchLength,
            //appendTo: "#"+$thisParentID,
            search: function (event, ui) {
                $this.autocomplete("option", "source", generateLangURL('/ajax/deal_search_for'));
            },
            select: function (event, ui) {
                $this.val(ui.item.name);
                $thisParent.find('#attractionName').val(ui.item.attractionName);
                $thisParent.find('#cityName').val(ui.item.cityName);
                $thisParent.find('#dealId').val(ui.item.dealId);
                $thisParent.find('#cityId').val(ui.item.cityId);
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
    });
}
function initAutoCompleteListHotels(wich) {
    var $this = $(document).find('.' + wich);

    $this.each(function () {
        var $thisParent = $this.parent();
        var $thisParentID = $thisParent.attr("id");
        var page_src = $('#search_src').val();
        var url = '/ajax/Hotel_search?page_src=' + page_src;
        /*		if (window.hotels.pageSrc.hotels) {
         var url = '/ajax/Hotel_search';
         }*/

        $this.autocomplete({
            minLength: minSearchLength,
            autoFocus: true,
            //appendTo: "#"+$thisParentID,
            search: function (event, ui) {
                $this.autocomplete("option", "source", generateLangURL(url));
            },
            select: function (event, ui) {
                $this.val(ui.item.name);
                if (!window.hotels.pageSrc.hrs) {
                    $thisParent.find('.hotelCityCode').val(ui.item.hotelCityCode);
                    $thisParent.find('.cityId').val(ui.item.cityId);
                } else {
                    $thisParent.find('input.locationId').val(ui.item.locationId);
                }

                $thisParent.find('input.hotelCityName').val(ui.item.name);
                $thisParent.find('input.cityId').val(ui.item.cityId);
                $thisParent.find('input.hotelId').val(ui.item.hotelId);
                $thisParent.find('input.entityType').val(ui.item.entityType);
                $thisParent.find('input.longitude').val(ui.item.longitude);
                $thisParent.find('input.latitude').val(ui.item.latitude);
                $thisParent.find('input.country').val(ui.item.country);
                $thisParent.find('input.stars').val(0);
                event.preventDefault();
            }
        }).keydown(function (event, ui) {
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
    });
}

function destroyAutocomplete(wich) {
    var myClass = $(document).find('.' + wich);
    if (myClass.length > 0) {
        myClass.each(function () {
            var $this = $(this);
            $this.autocomplete("destroy");
        });
    }
}

function getLastArrivalAirport() {
    var allArrivalAirports = $("#airplaneform").find('input.arrivalairport');
    var arrivalAirportsSize = allArrivalAirports.length;
    var lastArrivalAirport = allArrivalAirports.eq(arrivalAirportsSize - 1);
    next_departure_airport_name = lastArrivalAirport.val();
    next_departure_airport_code = lastArrivalAirport.parent().find('input.arrivalairportC').val();
//	console.log(next_departure_airport_name);
//	console.log(next_departure_airport_code);
}

var next_departure_airport_name = '';
var next_departure_airport_code = ''

function initAutocompleteAirports(wich) {
    var myClass = $(document).find('.' + wich);
    myClass.each(function () {
        var $this = $(this);
        var $thisParent = $this.closest('div.temp_holder');
        //var $thisParentID = $thisParent.attr('id');
        $this.autocomplete({
            minLength: minSearchLength,
            autoFocus: true,
            //appendTo: "#" + $thisParentID,
            search: function (event, ui) {
                $this.autocomplete("option", "source", generateLangURL('/ajax/search_airport_code'));
            },
            select: function (event, ui) {
                if (wich == 'arrivalairport') {
                    if (ui.item.airport_code == $thisParent.find("." + wich + "C").val()) {
                        $this.val('');
                        $thisParent.find("." + wich + "C").val('');
                        showErrorMsg(Translator.trans('invalid arrival airport'));
                        return;
                    }
                    getLastArrivalAirport();
                    next_departure_airport_name = ui.item.value;
                    next_departure_airport_code = ui.item.airport_code;
                }
                $this.val(ui.item.value);
                $thisParent.find("." + wich + "C").val(ui.item.airport_code);
                //$('#' + wich + 'C').val(ui.item.airport_code);
                event.preventDefault();
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class='auto_tuber'>" + item.label + "</a>")
                    .appendTo(ul);
        };
    });
}

function drawmarkers(map, image, title, link_uri, img_uri, la, lo, stars, entity_type, entityId) {
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(la, lo),
        map: map,
        icon: image,
        title: title
    });
    marker_global_array.push(Array(marker, entity_type, entityId))
    var base_url = '';
    var onMarkerClick = function () {
        ib.close();
        var marker = this;
        var content = '<div class="mtooltip"><div class="mtooltipin"><div class="clsgoo" onClick="ib.close();">X</div>';
        if (img_uri != '') {
            if (entity_type == SOCIAL_ENTITY_USER) {
                content += '<img class="mtooltipbig_user" src="' + img_uri + '" alt="" width="72"/>';
            } else if (entity_type == SOCIAL_ENTITY_EVENTS) {
                content += '<img class="mtooltipbig_event" src="' + img_uri + '" alt="" width="72"/>';
            } else {
                content += '<img class="mtooltipbig" src="' + img_uri + '" alt="" width="72"/>';
            }
            content += '<br /><a href="' + link_uri + '" target="_blank">';
        }
        content += '<div class="mtooltip_title">' + title + '</div>';
        if (img_uri != '') {
            content += '</a>';
        }
        if (parseInt(stars) > 0 && parseInt(stars) <= 5) {
            content += '<div class="anchor_star anchor_star' + stars + '"></div>';
        }
        content += '</div>';
        //content += '<div class="anchor_bk"></div>';
        content += '</div>';
        boxText.innerHTML = content;
        ib.open(map, marker);
    };
    google.maps.event.addListener(marker, 'click', onMarkerClick);
}

function showFlightOverlay(eltId) {
    var eltId = (eltId) ? eltId : null;

    window.flightOverlay = new TTOverlay(eltId);
    var custElement = $('<div></div>', {
        "class": "row no-margin loading-flights"
    }).append($('<div></div>'));

    window.flightOverlay.showCustom(custElement, eltId, {
        "size": '300px'
    });
}

function hideFlightOverlay() {
    if (window.flightOverlay)
        window.flightOverlay.hide();
}

function showLoaderOverlay(eltId) {
    var eltId = (eltId) ? eltId : null;

    window.hotelOverlay = new TTOverlay(eltId);
    var custElement = $('<div></div>', {
	"class": "row no-margin loading-hotels"
    }).append($('<div></div>'));

    window.hotelOverlay.showCustom(custElement, ((eltId) ? ('#' + eltId) : ''), {
	"size": '300px'
    });
}

function hideLoaderOverlay() {
    if (window.hotelOverlay) {
        window.hotelOverlay.hide();
    }
}

function showHotelOverlay(eltId, type) {
    var eltId = (eltId) ? eltId : null;
    var type = (type) ? type.trim() : 'listing';
    showLoaderOverlay(eltId);
}

function hideHotelOverlay() {
    hideLoaderOverlay();
}

function initHotelConstants() {
    window.hotels = {};	
    if ($('#hotelform').length) {
	window.hotels.pageSrc = {
	    hrs: false,
	    hotels: false
	};

	var search_src = $('#search_src').val().toUpperCase();
	switch (search_src) {
	    case 'TT':
		window.hotels.pageSrc.hotels = true;
		break;
	    case 'CORPORATE':
		window.hotels.pageSrc.corpo = true;
		break;
	    case 'HRS_DIRECT':
		window.hotels.pageSrc.hrs = true;
		break;
	}
    }
}

function showDealsOverlay(eltId) {
    showLoaderOverlay(eltId);
}

function hideDealsOverlay() {
    hideLoaderOverlay();
}

function showTTOverlay(eltId) {
    showLoaderOverlay(eltId);
}

function hideTTOverlay() {
    hideLoaderOverlay();
}

function showNeedTTAccountMessage() {
    var msg = Translator.trans('You need to have a TouristTube account to use this feature. Click') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('here') + '</a> '+Translator.trans('to register.');
    showErrorMsg(msg);
}


function initAutocompleteThingsToDo(wich) {
    var myClass = $(document).find('.' + wich);
    myClass.each(function () {
        var $this = $(this);
        var $thisParent = $this.closest('div.things_to_do_form');
        //var $thisParentID = $thisParent.attr('id');
        $this.autocomplete({
            minLength: minSearchLength,
            autoFocus: true,
            //appendTo: "#" + $thisParentID,
            search: function (event, ui) {
                $this.autocomplete("option", "source", generateLangURL('/ajax/search_things_to_do'));
            },
            select: function (event, ui) {
                if (ui.item.isSuggestion)
                {
                    //$this.val(ui.item.value);
                    setTimeout(function () {
                        $this.autocomplete("search", ui.item.value);
                    }, 500);
                } else
                {
                    var link_page = null;
                    if (ui.item.links && ui.item.links != undefined) {
                        link_page = "" + ui.item.links;
                    }
                    if (link_page) {
                        document.location.href = link_page;
                    }
                    event.preventDefault();
                }
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class='auto_tuber'>" + item.label + "</a>")
                    .appendTo(ul);
        };
    });
}

function addAutoCompleteListCurrentCity(which) {
    var $this = $("input[name=city]", $('#' + which));
    $this.autocomplete({
        autoFocus: true,
        minLength: minSearchLength,
        appendTo: "#" + which,
        search: function (event, ui) {
            var $country = $('#country', $('#' + which)).val();
            if ($country == '') {
                $('#country').addClass('err');
                event.preventDefault();
            } else {
                $this.autocomplete("option", "source", generateLangURL('/ajax/search_locality?countryCode=' + $country));
            }
        },
        select: function (event, ui) {
            $this.val(ui.item.name);
            $this.attr('data-id', ui.item.id);
            event.preventDefault();
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a class='auto_tuber'>" + item.label + "</a>")
                .appendTo(ul);
    };
}
function closeModalChangePassword(msg)
{
    ttModal.hide();
    $('.upload-overlay-loading-fix').show();
    setTimeout(function() {
	if( !ttModal ) {
	    ttModal = window.getTTModal("myModalZ", {});
	}
	ttModal.alert(msg, null, null, {ok:{value:"close"}});
	$('.upload-overlay-loading-fix').hide();
    }, 2000);    
}
/* Validate Request360 Form */
function validateRequest360Form() 
{
    var send_data_bool = true;
    $('.property_add_container').each(function(index, element)
    {
	if ( !validatePropertyInfoForm( $(this), index ) )
	{
	    send_data_bool = false;
	    return false;
	}
    });
    
    if( send_data_bool )
    {
	if ( $("input[name=contact_name]", $('#get_property_360_form')).val().trim() == '' )
	{
	    showErrorMsg( Translator.trans("Please insert the contact name.") );
	    return false;
	} else if ( $("input[name=contact_name]", $('#get_property_360_form')).val().trim().length > 50 )
	{
	    showErrorMsg( sprintf(Translator.trans("Contact name must be maximum %s characters long"), [50]) );
	    return false;
	} else if ( $("input[name=email]", $('#get_property_360_form')).val().trim() == ''  )
	{
	    showErrorMsg( Translator.trans("Please insert the email address.") );
	    return false;
	} else if ( $("input[name=email]", $('#get_property_360_form')).val().trim().length > 100  )
	{
	    showErrorMsg( sprintf(Translator.trans("Email address must be maximum %s characters long"), [100]) );
	    return false;
	} else if ( !validateEmail($("input[name=email]", $('#get_property_360_form')).val().trim())  )
	{
	    showErrorMsg( Translator.trans("Please insert a correct email.") );
	    return false;
	} else if ( $("select[name=dialing_code]", $('#get_property_360_form')).val().trim() == '') 
	{
	    showErrorMsg( Translator.trans("Please choose the phone country code.") );
	    return false;
	} else if ( $("input[name=phone]", $('#get_property_360_form')).val().trim() == '' )
	{
	    showErrorMsg( Translator.trans("Please insert the phone number.") );
	    return false;
	} else if ( $("input[name=phone]", $('#get_property_360_form')).val().trim().length > 20 )
	{
	    showErrorMsg( sprintf(Translator.trans("Phone number must be maximum %s characters long"), [20]) );
	    return false;
	} else if ( $("textarea[name=msg]", $('#get_property_360_form')).val().trim().length > 300 )
	{
	    showErrorMsg( sprintf(Translator.trans("Queries must be maximum %s characters long"), [300]) );
	    return false;
	} else {
	    return true;
	}
    } else {
	return false;
    }
}
function validatePropertyInfoForm( $this, index ) 
{
    var $property_name = $this.find("input[name=property_name]").val().trim();
    var $country       = $this.find("select[name=country]").val().trim();
    var $city          = $this.find("input[name=city]").val().trim();
    var $city_id       = $this.find("input[name=city]").attr('data-id');
    if( $city_id == '' || parseInt($city_id) == 0 )
    {
	$city = '';
	$this.find("input[name=city]").val('');
    }

    if( $property_name != '' || index == 0 )
    {
	if ( $property_name == ''  )
	{
	    showErrorMsg( Translator.trans("Please insert the official property name.") );
	    return false;
	} 
	else if ( $property_name.length > 100 )
	{
	    showErrorMsg( sprintf(Translator.trans("Property name must be maximum %s characters long"), [100]) );
	    return false;
	} 
	else if ( $country == '' )
	{
	    showErrorMsg( Translator.trans("Please choose a country.") );
	    return false;
	} 
	else if ( $city == '' )
	{
	    showErrorMsg( Translator.trans("Please insert the city.") );
	    return false;
	} 
	else if ( $this.find(".request_form_eval_options.active").length == 0 )
	{
	    showErrorMsg( Translator.trans("Please select the property rate.") );
	    return false;
	}
    }
    return true;
}
function changePropertyCountry(obj) {
    var id = $(obj).val();
    var data_code = $(obj).find(":selected").attr('data-code');
    var option_obj = $("select[name=dialing_code] option[data-code='"+data_code+"']", $('#get_property_360_form'));
    $("select[name=dialing_code]", $('#get_property_360_form')).val(option_obj.val());
    $("select[name=dialing_code]", $('#get_property_360_form')).trigger('change');
}
function createEditPopup(link, $title) {
    ttModal1 = window.getTTModal("myModalZ", {url: {href: link}, width: 500, title: $title});
    ttModal1.show();
}
function closeModalMediaPopup()
{
    ttModal1.hide();    
    $('.upload-overlay-loading-fix').show();
    document.location.reload();    
}