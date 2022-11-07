$(document)
        .ready(
                function()
                {

	                $(document).on('click', '.update_search_button', function()
	                {
		                $(".update_search_container").slideToggle('fast');
		                $(".flight_search_input_container").slideToggle('fast');
	                });

	                $(document).on('click', '.change_currency_button', function()
	                {
		                $("html, body").animate({
			                scrollTop : $(document).height()
		                }, "slow");
		                $('.currencyselectbox').trigger('click');
	                });

	                $(document).on('click', '.close_container', function()
	                {
		                $(".flight_search_input_container").slideToggle('fast');
		                $(".update_search_container").slideToggle('fast');
	                });

	                var mySlickSlikder = $('.responsiveSlider');
	                mySlickSlikder.on('init', function(event, click)
	                {
		                $(".responsiveSlider div.slick-slide.slick-current").removeClass("slick-current");
	                });

	                mySlickSlikder =
	                        mySlickSlikder
	                                .slick({
	                                    dots : false,
	                                    infinite : false,
	                                    speed : 300,
	                                    slidesToShow : 4,
	                                    slidesToScroll : 1,
	                                    centerMode : false,
	                                    variableWidth : true,
	                                    focusOnSelect : false,
	                                    prevArrow : '<button class="slick-prev slick-arrow" type="button"><i class="fas fa-angle-left"></i></button>',
	                                    nextArrow : '<button class="slick-next slick-arrow" type="button"><i class="fas fa-angle-right"></i></button>',
	                                    responsive : [ {
	                                        breakpoint : 1024,
	                                        settings : {
	                                            slidesToShow : 3,
	                                            slidesToScroll : 1
	                                        }
	                                    }, {
	                                        breakpoint : 600,
	                                        settings : {
	                                            slidesToShow : 2,
	                                            slidesToScroll : 1
	                                        }
	                                    }, {
	                                        breakpoint : 480,
	                                        settings : {
	                                            slidesToShow : 1,
	                                            slidesToScroll : 1
	                                        }
	                                    } ]
	                                });

	                $(document).on("click", ".slick-slide", function()
	                {
		                var $this = $(this);
		                $('.slick-slide').not($this).removeClass('slick-current-new');
		                $this.toggleClass('slick-current-new');

		                var code = $($(this).find("div.airlines_carousel")[0]).attr('data-code');
		                var input = 'input[data-value="airlines"][value="' + code + '"].form-check-input';
		                var airline = 'input[data-value="airlines"].form-check-input';

		                $(airline).not(input).removeAttr('checked');

		                $('#filterleft ' + input).attr("skip-carousel-action", true).trigger('click');
	                });

	                $(document).on('click', '.book_now', function(e)
	                {
		                e.preventDefault();
		                showFlightOverlay();
		                var formid = $(this).attr('id');
		                flightBookNow(formid);
	                });

	                $(document).on('click', '.updateSelectedSegment', function(e)
	                {
		                e.preventDefault();
		                var counter = $(this).attr('data-counter');
		                updateSelectedFlightSegment(counter);
	                });

	                initFlightDetail();

	                $.sessionTimeout({
	                    message : Translator.trans('Your session is about to expire.'),
	                    keepAliveUrl : generateLangURL('/refresh-session'),
	                    keepAliveAjaxRequestType : 'POST',
	                    redirUrl : generateLangURL(isCorporate ? '/corporate/flight?timedOut=1'
	                            : '/flight-booking?timedOut=1', isCorporate ? 'corporate' : ''),
	                    logoutUrl : generateLangURL(isCorporate ? '/corporate/flight' : '/flight-booking', isCorporate
	                            ? 'corporate' : ''),
	                    warnAfter: 600000, // 10 mins
	                    redirAfter: 900000, // 15 mins,
	                    width : 555
	                });

	                // we load up the sorting from flightCookie if not empty
	                // we load up the sorting from flightCookie if not empty
	                var flightCookie = window.ttUtilsInst.getCookies('flight');

	                if (flightCookie) {
		                var flightCookie = $.parseJSON(flightCookie);
		                var sortValue = flightCookie.sort.name;
		                var sortBy = flightCookie.sort.order;

		                if (sortValue != '') {

			                var _sort = '';
			                if (sortBy == 'asc')
				                _sort = 'desc';
			                else if (sortBy == 'desc')
				                _sort = 'asc';

			                $('.sort_item').removeClass('active');
			                $('.sort_item[data-value="' + sortValue + '"').attr('data-sortby', _sort);
			                $('.sort_item[data-value="' + sortValue + '"').addClass('active');

			                sortItemsByValue(sortValue);
		                }
	                }

                });

function initFlightDetail()
{
	$('.nonstop').each(function(index, elt)
	{
		var $this = $(this);
		var fc = $(this).attr('data-id');
		var segIndx = $(this).attr('data-segmentIndex');
		var flightIndx = $(this).parents("figure").attr('data-idx');
		flightIndx--;
		var flightSegmentIndx = $(this).attr('data-segmentSequence');
		var refundable = $(this).attr('data-refundable');
		var seats = $(this).attr('data-seats');

		$this.fancybox({
		    'padding' : 0,
		    'margin' : 0,
		    'width' : '85%',
		    'height' : '75%',
		    'autoScale' : false,
		    'autoDimensions' : false,
		    'beforeLoad' : function()
		    {
			    renderTemplate(fc, segIndx, flightIndx, flightSegmentIndx, refundable, seats);
		    }
		});
	});
}

function convertMinsToHrsMins(minutes)
{
	var h = Math.floor(minutes / 60);
	var m = minutes % 60;
	var r = '';
	if (h > 0) {
		r += window.ttUtilsInst.leftPad(h, 2, "0") + 'h ';
	}
	if (m > 0) {
		m = window.ttUtilsInst.leftPad(m, 2, "0"); // < 10 ? '0' + m : m;
		r += m + 'm';
	} else {
		r += window.ttUtilsInst.leftPad(0, 2, "0") + 'm';
	}
	return r;
}


function renderTemplate(fc, segmentIndex, flightIndex, flightSegmentIndex, refundable, seats)
{

	if(!flightIndex || flightIndex=="") flightIndex = 0;
	if(!flightSegmentIndex || flightSegmentIndex=="") flightSegmentIndex = 0;
	var tmpl = $("#flight_details_tmpl").html();
	$('.flight_segments').empty();

	// Here you define your variable in a JS object
	// The object keys should be defined in the template HTML with #{key}

	var stop = {};
	var map = {};
	//

	if (segmentIndex != null && segmentIndex != "") {

		segmentIndex = parseInt(segmentIndex);
		//
		var flightrequestMainInput = $("input[name=flightrequestMain]");

		var flightrequestMain = JSON.parse(flightrequestMainInput.val());
		var selectedSegments = flightrequestMain.segments;
		//
		if (selectedSegments && selectedSegments.length >= segmentIndex) {
			var firstSeg = selectedSegments[segmentIndex];
			var nbStops = parseInt(firstSeg['total_flight_segments']);
			var loopLen = segmentIndex + nbStops;

			for (var i = segmentIndex; i < loopLen; i++) {
				var segment = selectedSegments[i];

				var departureDate = $.datepicker.formatDate('D. M d', new Date(segment['departure_date_time']));
				var airline_code = segment['airline_code'];
				var deptTime = new Date(segment['departure_date_time']);
				var arrTime = new Date(segment['arrival_date_time']);
				var departureTime =
				        window.ttUtilsInst.leftPad(deptTime.getHours(), 2, "0") + ':'
				                + window.ttUtilsInst.leftPad(deptTime.getMinutes(), 2, "0");
				var arrivalTime =
				        window.ttUtilsInst.leftPad(arrTime.getHours(), 2, "0") + ':'
				                + window.ttUtilsInst.leftPad(arrTime.getMinutes(), 2, "0");
				var total_duration = segment['flight_duration'];
				var timediff = arrTime.getTime() - deptTime.getTime();
				var diff = new Date(timediff) / 60000;
				var duration = total_duration;
				var stop_label = Translator.trans('Non-stop');
				var popupTitle = Translator.trans('Departure');
				var days_difference = "";

				//
				var baggageInfoADT = flightrequestMain.main.leaving_baggage_info_ADT || "";
				var baggageInfoCNN = flightrequestMain.main.leaving_baggage_info_CNN || "";
				var baggageInfoINF = flightrequestMain.main.leaving_baggage_info_INF || "";
				//
				var baggageInfoADTLabel = (baggageInfoADT && baggageInfoADT != "") ? "Adult Baggage" : "";
				var baggageInfoCNNLabel = (baggageInfoCNN && baggageInfoCNN != "") ? "Child Baggage" : "";
				var baggageInfoINFLabel = (baggageInfoINF && baggageInfoINF != "") ? "Infant Baggage" : "";

				var dayCountDiff = arrTime.getDate() - deptTime.getDate();

				var dayLabel = "day";

				if (dayCountDiff > 0) {
					if (dayCountDiff > 1)
						dayLabel += "s";
					days_difference = "+" + dayCountDiff + Translator.trans(dayLabel);
				}

				var stop_indicator = segment['stop_indicator'];

			/*	if (nbStops == 1) {
					duration = convertMinsToHrsMins(diff);
					stop_label = Translator.trans('1 Stop');
				}

				if (duration == '' || i == segmentIndex) {
					duration = convertMinsToHrsMins(diff);
				}*/

				var elapsedTime =segment['elapsedTime'];
				duration = convertMinsToHrsMins(elapsedTime);

				var departure_airport_city = segment['origin_location_city'];
				var departure_airport_code = segment['origin_location'];
				var departure_airport_name = segment['origin_location_airport'];

				var final_arrival_airport_code = selectedSegments[(loopLen-1)]['destination_location'];
				var final_arrival_airport_city = selectedSegments[(loopLen-1)]['destination_location_city'];

				var arrival_airport_city = segment['destination_location_city'];
				var arrival_airport_code = segment['destination_location'];
				var arrival_airport_name = segment['destination_location_airport'];

				var depterminal_id = segment['departure_terminal_id'];
				var arrterminal_id = segment['arrival_terminal_id'];
				var departure_terminal =
				        (depterminal_id != '' || depterminal_id > 0) ? Translator.trans('Terminal ') + depterminal_id
				                : '';
				var arrival_terminal =
				        (arrterminal_id != '' || arrterminal_id > 0) ? Translator.trans('Terminal ') + arrterminal_id
				                : '';

				var flightNumber = segment['flight_number'];
				var cabin = segment['cabin'];
				var aircraftType = segment['aircraft_type'];
				var stopDuration = segment['stop_duration'];

				map = {
				    'popupTitle' : popupTitle,
				    'departure_date' : departureDate,
				    'origin_airport' : departure_airport_name,
				    'origin_code' : departure_airport_code,
				    'departure_terminal' : departure_terminal,
				    'departure_time' : departureTime,
				    'duration' : duration,
				    'flight_number' : airline_code + flightNumber,
				    'airline_logo' : generateMediaURL('/media/images/airline-logos/' + airline_code.toUpperCase() + '.jpg'),
				    'cabin' : cabin,
				    'aircraft_type' : aircraftType,
				    'departure_airport_city' : departure_airport_city,
				    'departure_airport_code' : departure_airport_code,
				    'arrival_time': arrivalTime,
					'arrival_airport_city': arrival_airport_city,
					'arrival_airport': arrival_airport_name,
				    'final_arrival_airport_city': final_arrival_airport_city,
				    'final_arrival_airport_code': final_arrival_airport_code,
				    'arrival_code': arrival_airport_code,
				    'total_duration' : total_duration,
				    'stop_label' : stop_label,
				    'days_difference' : days_difference,
				    'baggageInfoADTLabel' : baggageInfoADTLabel,
				    'baggageInfoCNNLabel' : baggageInfoCNNLabel,
				    'baggageInfoINFLabel' : baggageInfoINFLabel,
				    'baggageInfoADT' : baggageInfoADT,
				    'baggageInfoCNN' : baggageInfoCNN,
				    'baggageInfoINF' : baggageInfoINF,
				    'refundable': refundable,
				    'seats': seats
				}

				if (stop_indicator > 0) {
					stop = {
					    'stop_indicator' : '',
					    'stop_duration' : stopDuration,
					    'stop_city' : departure_airport_city,
					    'has_stop' : 'hidden'
					}
				} else {
					stop = {
					    'stop_indicator' : 'hidden',
					    'has_stop' : ''
					}
				}
				var res = $.extend(map, stop);

				var data = window.ttUtilsInst.template(tmpl, res);

				$(data).appendTo('.flight_segments');
			};

		}

	}
	else if (fc && fc != "") {
		segments = $(fc).find('.segment_number');
		var segmentIndex = $(fc).find('.segment_index').val();

		if (segments.length > 0) {

			var arrival_airport_city = null;
			var arrival_airport_code = null;

			//skip unwanted segments and start only by the first segment of the selected flight
			var isMultiDestinations = $('#fx_multidestination').val();
			var isRoundTrip = $('#is_round_trip').val()=="1";

			var flightrequestMainInput = $("input[name=flightrequestMain]");
			var currentStop = 0;
			var currentFlight = flightIndex;

			var flightrequestMain = JSON.parse(flightrequestMainInput.val());
			var selectedSegments = flightrequestMain.segments;
			//
			var loopIdx = 0;
			var nbStops = 0;
			//segments.each(function()
			for(var i = flightSegmentIndex ; i < segments.length ; i++)
			        {
						var elt = segments[i]; //this;
				        var idx = $(elt).val();

						//
						//In mutli destinations to show details only flight by flight 
						//Ignore other flights 
						//
						if(currentStop==0) 
						{
							nbStops = $(fc).find('#stops-' + idx).val();
							if(nbStops == "") nbStops = 0;
						}

						if(/*isMultiDestinations == 1 &&*/ ( currentStop > 0 && currentStop > nbStops) )
						{
							var flagIndx = ((isMultiDestinations == 1) ? 1 : 0);
							if( (currentStop >= (nbStops + idx)) || currentFlight >= (flightIndex + flagIndx) ) break;
							else currentStop++;
							//
							currentFlight++
						}else
						{
							currentStop++;
						}

				        var departureDate =
				                $.datepicker.formatDate('D. M d', new Date($('#departure_date_time-' + idx).val()));
				        var airline_code = $('#airline_code-' + idx).val();
				        var deptTime = new Date($('#departure_date_time-' + idx).val());
				        var arrTime = new Date($('#arrival_date_time-' + idx).val());
				        var departureTime =
				                window.ttUtilsInst.leftPad(deptTime.getHours(), 2, "0") + ':'
				                        + window.ttUtilsInst.leftPad(deptTime.getMinutes(), 2, "0");
				        var arrivalTime =
				                window.ttUtilsInst.leftPad(arrTime.getHours(), 2, "0") + ':'
				                        + window.ttUtilsInst.leftPad(arrTime.getMinutes(), 2, "0");
				        var total_duration = $('#flight_duration-' + idx).val();
				        var timediff = arrTime.getTime() - deptTime.getTime();
				        var diff = new Date(timediff) / 60000;
				        var duration = total_duration;
				        var stop_label = Translator.trans('Non-stop');
						
						var popupTitle = ( ($("#isReturn").val() == "1" && (selectedSegments && selectedSegments.length > 0)) ? "Return" : 'Departure');
						popupTitle = Translator.trans(popupTitle);
						
				        if ($($('#leaving_baggage_info_ADT_' + segmentIndex).get(loopIdx)).length == 0)
					        loopIdx = 0;
				        var baggageInfoADT = $($('#leaving_baggage_info_ADT_' + segmentIndex).get(loopIdx)).val() || "";
				        var baggageInfoCNN = $($('#leaving_baggage_info_CNN_' + segmentIndex).get(loopIdx)).val() || "";
				        var baggageInfoINF = $($('#leaving_baggage_info_INF_' + segmentIndex).get(loopIdx)).val() || "";
				        //
				        var baggageInfoADTLabel = (baggageInfoADT && baggageInfoADT != "") ? "Adult Baggage" : "";
				        var baggageInfoCNNLabel = (baggageInfoCNN && baggageInfoCNN != "") ? "Child Baggage" : "";
				        var baggageInfoINFLabel = (baggageInfoINF && baggageInfoINF != "") ? "Infant Baggage" : "";
				        //

				        var days_difference = "";

				        var dayCountDiff = arrTime.getDate() - deptTime.getDate();

				        if (dayCountDiff == 1) {
					        days_difference = "+1 " + Translator.trans("day");

				        } else if (dayCountDiff > 1) {
					        days_difference = "+" + dayCountDiff + " " + Translator.trans("days");

				        }

				        var stop_indicator = $('#stop_indicator-' + idx).val();

				        var next_stop = $('#stop_indicator-' + (parseInt(idx) + 1));
				   /*     if (next_stop.length > 0 && next_stop.val() == 1) {
					        duration = convertMinsToHrsMins(diff);
					        stop_label = Translator.trans('1 Stop');
				        }

				        if (duration == '') {
					        duration = convertMinsToHrsMins(diff);
				        }*/

						var elapsedTime = $('#elapsedTime-' + idx).val();

						duration = convertMinsToHrsMins(elapsedTime);

				        var departure_airport_city = $('#origin_location_city-' + idx).val();
				        var departure_airport_code = $('#origin_location-' + idx).val();


						var arrivalIdx = (/*isMultiDestinations == 1 &&*/ nbStops > 0 ? (parseInt(idx) + parseInt(nbStops)) : idx);
						arrival_airport_city = $('#destination_location_city-' + arrivalIdx).val();
						arrival_airport_code = $('#destination_location-' + arrivalIdx).val();

						if(isMultiDestinations != 1 && !isRoundTrip)
						{
							arrival_airport_city = $('#destination_location_city-' + idx).val();
							arrival_airport_code = $('#destination_location-' + idx).val();
							//
							//
							if ($('#prev_seg_arrival_airport-' + idx).length > 0) {
								arrival_airport_city = $('#prev_seg_arrival_airport-' + idx).val();
							}

							if ($('#prev_seg_arrival_airport_code-' + idx).length > 0) {
								arrival_airport_code = $('#prev_seg_arrival_airport_code-' + idx).val();
							}
							if ($('#arrivalcity').length > 0 && $('#arrivalcode').length > 0) {
								arrival_airport_city = $('#arrivalcity').val();
								arrival_airport_code = $('#arrivalcode').val();
							}
						}

				        var depterminal_id = $('#departure_terminal_id-' + idx).val();

				        var arrterminal_id = $('#arrival_terminal_id-' + idx).val();
				        var departure_terminal =
				                (depterminal_id != '' || depterminal_id > 0) ? Translator.trans('Terminal ')
				                        + depterminal_id : '';
				        var arrival_terminal =
				                (arrterminal_id != '' || arrterminal_id > 0) ? Translator.trans('Terminal ')
				                        + arrterminal_id : '';

				        var operating_airline = $('#operating_airline_name-' + idx).val();
				        var operating_airline_code = $('#operating_airline_code-' + idx).val();

				        map = {
				            'popupTitle' : popupTitle,
				            'departure_date' : departureDate,
				            'origin_airport' : $('#origin_location_airport-' + idx).val(),
				            'origin_code' : $('#origin_location-' + idx).val(),
				            'arrival_airport' : $('#destination_location_airport-' + idx).val(),
				            'arrival_code' : $('#destination_location-' + idx).val(),
				            'departure_terminal' : departure_terminal,
				            'departure_time' : departureTime,
				            'arrival_time' : arrivalTime,
				            'duration' : duration,
				            'flight_number' : airline_code + $('#flight_number-' + idx).val(),
				            'airline_logo' : $('#airline_logo-' + idx).val(),
				            'cabin' : $('#cabin-' + idx).val(),
				            'aircraft_type' : $('#aircraft_type-' + idx).val(),
				            'departure_airport_city' : departure_airport_city,
				            'departure_airport_code' : departure_airport_code,
				            'arrival_airport_city' : arrival_airport_city,
				            'arrival_airport_code' : $('#fx_arrival_code-' + segmentIndex).val(),
				            'total_duration' : total_duration,
				            'stop_label' : stop_label,
				            'days_difference' : days_difference,
				            'baggageInfoADTLabel' : baggageInfoADTLabel,
				            'baggageInfoCNNLabel' : baggageInfoCNNLabel,
				            'baggageInfoINFLabel' : baggageInfoINFLabel,
				            'baggageInfoADT' : baggageInfoADT,
				            'baggageInfoCNN' : baggageInfoCNN,
				            'baggageInfoINF' : baggageInfoINF,
				            'refundable': refundable,
				            'seats': seats,
				            'operating_airline': '' 
				        }

						if(arrival_airport_city)
							map.final_arrival_airport_city = arrival_airport_city;
						if(arrival_airport_code)
							map.final_arrival_airport_code = arrival_airport_code;

						if(operating_airline_code !== airline_code)
							map.operating_airline = Translator.trans('Operated by ') + operating_airline;

				        if (stop_indicator > 0) {
					        stop = {
					            'stop_indicator' : '',
					            'stop_duration' : $('#stop_duration-' + idx).val(),
					            'stop_city' : $('#origin_location_city-' + idx).val(),
					            'has_stop' : 'hidden'
					        }
				        } else {
					        loopIdx++;
					        stop = {
					            'stop_indicator' : 'hidden',
					            'has_stop' : ''
					        }
				        }

				        var res = $.extend(map, stop);
				        var data = window.ttUtilsInst.template(tmpl, res);

				        if(isMultiDestinations != 1 || (currentStop == 0 || currentFlight <= flightIndex) ) 
							$(data).appendTo('.flight_segments');

			        }
		}
	}

}

function flightBookNow(id)
{

	var formid = "form#" + id;
	var jqForm = $(formid);

	// Final result to handle all results
	var flightRequest = {};

	// Get the previous submitted Flight Request data
	var flightrequestMainInput = $("input[name=flightrequestMain]");

	// Flight Request data to be filled before submitting form to next segment
	var flightrequestInput = jqForm.find("input[name=flightrequest]");

	// Get the main common data to be added to all the segments
	var mainSerializerInputs = jqForm.find("div.main_serializer :input");

	//
	// Serialize the main common data to JSON format
	var mainSerializerJSON = window.ttUtilsInst.formToJsonObject(mainSerializerInputs);

	//
	// Get the inbound segments in case of round trip, to be resubmitted to the next step
	var segmentsInbound = $("#segmentsInbound").val();

	// Converting the selected form segment to JSON
	var formJSON = window.ttUtilsInst.formToJsonObject(formid);

	// Retrieve the flightrequest input having the full previous JSON and Convert it to JSON
	flightRequest = JSON.parse(flightrequestMainInput.val());// JSON.parse(formJSON.flightrequest);

	// Remove the flightrequest property from the JSON so we can save the other fields grouped alone under another
	// property
	delete formJSON.flightrequest;

	if (!flightRequest.main) {
		flightRequest = {
			main : flightRequest
		};
	}
	//

	flightRequest.main = window.ttUtilsInst.extendObject(flightRequest.main, mainSerializerJSON, true);
	if (flightRequest.main.oneway == "")
		flightRequest.main.oneway = "0";
	if (flightRequest.main.multidestination == "")
		flightRequest.main.multidestination = "0";
	//
	// Get the available segments in the selected form including the stops
	var jqFormSerializers = jqForm.find("div.seg_serializer");

	//
	//
	if (null == flightRequest.main.totalOriginalPrice || flightRequest.main.totalOriginalPrice == "")
		flightRequest.main.totalOriginalPrice =
		        flightRequest.main.totalPrice =
		        	flightRequest.main.totalPriceAttr =
		                flightRequest.main.totalOriginalBaseFare =
		                        flightRequest.main.totalOriginalTaxes =
		                                flightRequest.main.totalBaseFare = flightRequest.main.totalTaxes = 0;
	//
	//
	// Grouping all form values under segements property
	flightRequest.segments = flightRequest.segments || [];

	//
	// Variable used to define the start index of each flight (group of segments) inside the segments array
	if (!flightRequest.flightIndex)
		flightRequest.flightIndex = [];
	flightRequest.flightIndex.push(flightRequest.segments.length);

	// Looping the segment details including the stops to be extracted as separate segments
	jqFormSerializers.each(function(index)
	{
		var me =$(this);
		var formSerializeJSON = window.ttUtilsInst.formToJsonObject(me.find("input"));

		// MERGE formSerializeJSON -> mainSerializerJSON
		// formSerializeJSON = window.ttUtilsInst.extendObject(formSerializeJSON, mainSerializerJSON, true);

		// Fill the Inbound segments in case of round trip
		if (segmentsInbound && segmentsInbound != "")
			flightRequest.segmentsInbound = segmentsInbound;

		// Keep always the current latest token
		flightRequest.main.sec_token = formSerializeJSON['sec_token'];

		if (formSerializeJSON['stop_indicator'] != 1 && formSerializeJSON['stop_indicator'] != "1") {
			// Displayed Total Amount - 20 USD
			if (mainSerializerJSON['price_attr'] > 0) {
				flightRequest.main.totalPriceAttr += parseFloat(mainSerializerJSON['price_attr']);
			}
			if (mainSerializerJSON['price'] > 0) {
				flightRequest.main.totalPrice += parseFloat(mainSerializerJSON['price']);
			}
			if (mainSerializerJSON['base_fare_attr'] > 0) {
				flightRequest.main.totalBaseFare += parseFloat(mainSerializerJSON['base_fare_attr']);
			}
			if (mainSerializerJSON['taxes_attr'] > 0) {
				flightRequest.main.totalTaxes += parseFloat(mainSerializerJSON['taxes_attr']);
			}
			if (mainSerializerJSON['provider_price'] > 0) {
				flightRequest.main.totalProviderPrice += parseFloat(mainSerializerJSON['provider_price']);
			}
			if (mainSerializerJSON['original_price'] > 0) {
				flightRequest.main.totalOriginalPrice += parseFloat(mainSerializerJSON['original_price']);
			}
			// Original Price In AED for provider
			if (mainSerializerJSON['original_base_fare'] > 0) {
				flightRequest.main.totalOriginalBaseFare += parseFloat(mainSerializerJSON['original_base_fare']);
			}
			if (mainSerializerJSON['original_taxes'] > 0) {
				flightRequest.main.totalOriginalTaxes += parseFloat(mainSerializerJSON['original_taxes']);
			}
			// Filled in segments to be used in update selected segments
			formSerializeJSON.segmentAmounts = {
			    'price_attr' : parseFloat(mainSerializerJSON['price_attr']),
			    'base_fare_attr' : parseFloat(mainSerializerJSON['base_fare_attr']),
			    'taxes_attr' : parseFloat(mainSerializerJSON['taxes_attr']),
			    'original_price' : parseFloat(mainSerializerJSON['original_price']),
			    'original_base_fare' : parseFloat(mainSerializerJSON['original_base_fare']),
			    'original_taxes' : parseFloat(mainSerializerJSON['original_taxes'])
			};

		}
		//
		if (formSerializeJSON.oneway == "")
			formSerializeJSON.oneway = "0";
		if (formSerializeJSON.multidestination == "")
			formSerializeJSON.multidestination = "0";
		//
		flightRequest.segments.push(formSerializeJSON);

	});
	if (!flightRequest.nbStops)
		flightRequest.nbStops = 0;
	flightRequest.nbStops += jqFormSerializers.length - 1;

	// Selecting the next segment as per already selected segments
	// We made - jqFormSerializers.length to remove the stops to be not included in our test
	var expectedSegments = (flightRequest.segments.length - flightRequest.nbStops);
	if (expectedSegments < flightRequest.main.arrivalairportC.length) {
		flightRequest.nextSegment = expectedSegments;

	} else
		delete flightRequest.nextSegment;

	//


	flightRequest=JSON.stringify(flightRequest);


	flightrequestInput.val(flightRequest.replace(/'/g, ''));
	// reset localStorage.flightsFilters
	localStorage.removeItem('flightsFilters');

	//
	//
	if (jqForm.length > 0) {
		jqForm.submit();
	}

}

/**
 * Used when the user choose to update a previous selected segment This method should remove all segments after the
 * selected one and start the process from this step
 * 
 * @param {type}
 *            counter
 * @returns null
 */
function updateSelectedFlightSegment(counter)
{

	if (null == counter || counter == "" || isNaN(counter))
		counter = 0;
	//
	var jqForm = formid = null;
	//
	// If the first segment needs to be updated then refine the search
	if (counter == 0) {
		formid = "airplaneform";
		jqForm = $("#" + formid);
	} else {
		formid = "updateSelectedSegments_" + counter;
		jqForm = $("#" + formid);
		//
		// Flight Request data to be filled before submitting form to next segment
		var flightrequestInput = jqForm.find("input[name=flightrequest]");

		// Initial data with full details and selected segments
		var flightrequestMainInput = $("input[name=flightrequestMain]");
		var flightRequest = JSON.parse(flightrequestMainInput.val());
		var selectedSegments = flightRequest.segments;
		var segmentsIndex = flightRequest.flightIndex;
		var startIndex = segmentsIndex[counter];
		//
		// REMOVE ALL SELECTED SEGMENTS STARTING BY THE SELECTED COUNTER
		var removedSegments = selectedSegments.splice(startIndex);
		flightRequest.segments = selectedSegments;
		// REMOVE SEG INDEXES RELATED TO THE REMOVED SEGMENTS
		flightRequest.flightIndex.splice(counter);

		// UPDATE TOTALs BY REDUCING AMOUNTS FROM REMOVED SELECTED SEGEMENTS
		for (var i = 0; i < removedSegments.length; i++) {
			var seg = removedSegments[i];
			var segmentAmounts = seg.segmentAmounts;
			//
			if (seg['stop_indicator'] != 1 && seg['stop_indicator'] != "1") {
				// Displayed Total Amount - 20 USD
				flightRequest.main.totalPrice -= parseFloat(segmentAmounts['price']);
				flightRequest.main.totalPriceAttr -= parseFloat(segmentAmounts['price_attr']);
				flightRequest.main.totalBaseFare -= parseFloat(segmentAmounts['base_fare_attr']);
				flightRequest.main.totalTaxes -= parseFloat(segmentAmounts['taxes_attr']);

				// Original Price In AED for provider
				flightRequest.main.totalOriginalPrice -= parseFloat(segmentAmounts['original_price']);
				flightRequest.main.totalOriginalBaseFare -= parseFloat(segmentAmounts['original_base_fare']);
				flightRequest.main.totalOriginalTaxes -= parseFloat(segmentAmounts['original_taxes']);
			}
		}
		//
		// UPDATE NEXT SEGMENT FIELD
		flightRequest.nextSegment = flightRequest.flightIndex.length;

		//
		// Fill updated JSON to be submitted to get the next segment

		flightrequestInput.val(JSON.stringify(flightRequest));
	}
	//
	if (jqForm.length > 0) {
		jqForm.submit();
	}
}
