$(document).ready(function(){$(document).on("click",".update_search_button",function(){$(".update_search_container").slideToggle("fast");$(".flight_search_input_container").slideToggle("fast")});$(document).on("click",".change_currency_button",function(){$("html, body").animate({scrollTop:$(document).height()},"slow");$(".currencyselectbox").trigger("click")});$(document).on("click",".close_container",function(){$(".flight_search_input_container").slideToggle("fast");$(".update_search_container").slideToggle("fast")});var mySlickSlikder=$(".responsiveSlider");mySlickSlikder.on("init",function(event,click){$(".responsiveSlider div.slick-slide.slick-current").removeClass("slick-current")});mySlickSlikder=mySlickSlikder.slick({dots:false,infinite:false,speed:300,slidesToShow:4,slidesToScroll:1,centerMode:false,variableWidth:true,focusOnSelect:false,prevArrow:'<button class="slick-prev slick-arrow" type="button"><i class="fas fa-angle-left"></i></button>',nextArrow:'<button class="slick-next slick-arrow" type="button"><i class="fas fa-angle-right"></i></button>',responsive:[{breakpoint:1024,settings:{slidesToShow:3,slidesToScroll:1}},{breakpoint:600,settings:{slidesToShow:2,slidesToScroll:1}},{breakpoint:480,settings:{slidesToShow:1,slidesToScroll:1}}]});$(document).on("click",".slick-slide",function(){var $this=$(this);$(".slick-slide").not($this).removeClass("slick-current-new");$this.toggleClass("slick-current-new");var code=$($(this).find("div.airlines_carousel")[0]).attr("data-code");var input='input[data-value="airlines"][value="'+code+'"].form-check-input';var airline='input[data-value="airlines"].form-check-input';$(airline).not(input).removeAttr("checked");$("#filterleft "+input).attr("skip-carousel-action",true).trigger("click")});$(document).on("click",".book_now",function(e){e.preventDefault();showFlightOverlay();var formid=$(this).attr("id");flightBookNow(formid)});$(document).on("click",".updateSelectedSegment",function(e){e.preventDefault();var counter=$(this).attr("data-counter");updateSelectedFlightSegment(counter)});initFlightDetail();$.sessionTimeout({message:Translator.trans("Your session is about to expire."),keepAliveUrl:generateLangURL("/refresh-session"),keepAliveAjaxRequestType:"POST",redirUrl:generateLangURL(isCorporate?"/corporate/flight?timedOut=1":"/flight-booking?timedOut=1",isCorporate?"corporate":""),logoutUrl:generateLangURL(isCorporate?"/corporate/flight":"/flight-booking",isCorporate?"corporate":""),warnAfter:6e5,redirAfter:9e5,width:555});var flightCookie=window.ttUtilsInst.getCookies("flight");if(flightCookie){var flightCookie=$.parseJSON(flightCookie);var sortValue=flightCookie.sort.name;var sortBy=flightCookie.sort.order;if(sortValue!=""){var _sort="";if(sortBy=="asc")_sort="desc";else if(sortBy=="desc")_sort="asc";$(".sort_item").removeClass("active");$('.sort_item[data-value="'+sortValue+'"').attr("data-sortby",_sort);$('.sort_item[data-value="'+sortValue+'"').addClass("active");sortItemsByValue(sortValue)}}});function initFlightDetail(){$(".nonstop").each(function(){var $this=$(this);var fc=$(this).attr("data-id");var segIndx=$(this).attr("data-segmentIndex");$this.fancybox({padding:0,margin:0,width:"85%",height:"75%",autoScale:false,autoDimensions:false,beforeLoad:function(){renderTemplate(fc,segIndx)}})})}function convertMinsToHrsMins(minutes){var h=Math.floor(minutes/60);var m=minutes%60;var r="";if(h>0){r+=window.ttUtilsInst.leftPad(h,2,"0")+"h "}if(m>0){m=window.ttUtilsInst.leftPad(m,2,"0");r+=m+"m"}else{r+=window.ttUtilsInst.leftPad(0,2,"0")+"m"}return r}function renderTemplate(fc,segmentIndex){var tmpl=$("#flight_details_tmpl").html();$(".flight_segments").empty();var stop={};var map={};if(segmentIndex!=null&&segmentIndex!=""){segmentIndex=parseInt(segmentIndex);var flightrequestMainInput=$("input[name=flightrequestMain]");var flightrequestMain=JSON.parse(flightrequestMainInput.val());var selectedSegments=flightrequestMain.segments;if(selectedSegments&&selectedSegments.length>=segmentIndex){var firstSeg=selectedSegments[segmentIndex];var nbStops=parseInt(firstSeg["total_flight_segments"]);var loopLen=segmentIndex+nbStops;for(var i=segmentIndex;i<loopLen;i++){var segment=selectedSegments[i];var departureDate=$.datepicker.formatDate("D. M d",new Date(segment["departure_date_time"]));var airline_code=segment["airline_code"];var deptTime=new Date(segment["departure_date_time"]);var arrTime=new Date(segment["arrival_date_time"]);var departureTime=window.ttUtilsInst.leftPad(deptTime.getHours(),2,"0")+":"+window.ttUtilsInst.leftPad(deptTime.getMinutes(),2,"0");var arrivalTime=window.ttUtilsInst.leftPad(arrTime.getHours(),2,"0")+":"+window.ttUtilsInst.leftPad(arrTime.getMinutes(),2,"0");var total_duration=segment["flight_duration"];var timediff=arrTime.getTime()-deptTime.getTime();var diff=new Date(timediff)/6e4;var duration=total_duration;var stop_label=Translator.trans("Non-stop");var popupTitle=Translator.trans("Departure");var days_difference="";var baggageInfoADT=flightrequestMain.main.leaving_baggage_info_ADT||"";var baggageInfoCNN=flightrequestMain.main.leaving_baggage_info_CNN||"";var baggageInfoINF=flightrequestMain.main.leaving_baggage_info_INF||"";var baggageInfoADTLabel=baggageInfoADT&&baggageInfoADT!=""?"Adult Baggage":"";var baggageInfoCNNLabel=baggageInfoCNN&&baggageInfoCNN!=""?"Child Baggage":"";var baggageInfoINFLabel=baggageInfoINF&&baggageInfoINF!=""?"Infant Baggage":"";var dayCountDiff=arrTime.getDate()-deptTime.getDate();var dayLabel="day";if(dayCountDiff>0){if(dayCountDiff>1)dayLabel+="s";days_difference="+"+dayCountDiff+Translator.trans(dayLabel)}var stop_indicator=segment["stop_indicator"];if(nbStops==1){duration=convertMinsToHrsMins(diff);stop_label=Translator.trans("1 Stop")}if(duration==""||i==segmentIndex){duration=convertMinsToHrsMins(diff)}var departure_airport_city=segment["origin_location_city"];var departure_airport_code=segment["origin_location"];var departure_airport_name=segment["origin_location_airport"];var final_arrival_airport_code=selectedSegments[loopLen-1]["destination_location"];var final_arrival_airport_city=selectedSegments[loopLen-1]["destination_location_city"];var arrival_airport_city=segment["destination_location_city"];var arrival_airport_code=segment["destination_location"];var arrival_airport_name=segment["destination_location_airport"];var depterminal_id=segment["departure_terminal_id"];var arrterminal_id=segment["arrival_terminal_id"];var departure_terminal=depterminal_id!=""||depterminal_id>0?Translator.trans("Terminal ")+depterminal_id:"";var arrival_terminal=arrterminal_id!=""||arrterminal_id>0?Translator.trans("Terminal ")+arrterminal_id:"";var flightNumber=segment["flight_number"];var cabin=segment["cabin"];var aircraftType=segment["aircraft_type"];var stopDuration=segment["stop_duration"];map={popupTitle:popupTitle,departure_date:departureDate,origin_airport:departure_airport_name,origin_code:departure_airport_code,departure_terminal:departure_terminal,departure_time:departureTime,duration:duration,flight_number:airline_code+flightNumber,airline_logo:"/media/images/airline-logos/"+airline_code.toUpperCase()+".jpg",cabin:cabin,aircraft_type:aircraftType,departure_airport_city:departure_airport_city,departure_airport_code:departure_airport_code,arrival_time:arrivalTime,arrival_airport_city:arrival_airport_city,arrival_airport:arrival_airport_name,final_arrival_airport_city:final_arrival_airport_city,final_arrival_airport_code:final_arrival_airport_code,arrival_code:arrival_airport_code,total_duration:total_duration,stop_label:stop_label,days_difference:days_difference,baggageInfoADTLabel:baggageInfoADTLabel,baggageInfoCNNLabel:baggageInfoCNNLabel,baggageInfoINFLabel:baggageInfoINFLabel,baggageInfoADT:baggageInfoADT,baggageInfoCNN:baggageInfoCNN,baggageInfoINF:baggageInfoINF};if(stop_indicator>0){stop={stop_indicator:"",stop_duration:stopDuration,stop_city:departure_airport_city,has_stop:"hidden"}}else{stop={stop_indicator:"hidden",has_stop:""}}var res=$.extend(map,stop);var data=window.ttUtilsInst.template(tmpl,res);$(data).appendTo(".flight_segments")}}}else if(fc&&fc!=""){segments=$(fc).find(".segment_number");var segmentIndex=$(fc).find(".segment_index").val();if(segments.length>0){var flightrequestMainInput=$("input[name=flightrequestMain]");var flightrequestMain=JSON.parse(flightrequestMainInput.val());var selectedSegments=flightrequestMain.segments;var loopIdx=0;segments.each(function(){var idx=$(this).val();var departureDate=$.datepicker.formatDate("D. M d",new Date($("#departure_date_time-"+idx).val()));var airline_code=$("#airline_code-"+idx).val();var deptTime=new Date($("#departure_date_time-"+idx).val());var arrTime=new Date($("#arrival_date_time-"+idx).val());var departureTime=window.ttUtilsInst.leftPad(deptTime.getHours(),2,"0")+":"+window.ttUtilsInst.leftPad(deptTime.getMinutes(),2,"0");var arrivalTime=window.ttUtilsInst.leftPad(arrTime.getHours(),2,"0")+":"+window.ttUtilsInst.leftPad(arrTime.getMinutes(),2,"0");var total_duration=$("#flight_duration-"+idx).val();var timediff=arrTime.getTime()-deptTime.getTime();var diff=new Date(timediff)/6e4;var duration=total_duration;var stop_label=Translator.trans("Non-stop");var popupTitle=$("#isbooking").val()=="1"&&(selectedSegments&&selectedSegments.length>0)?"Return":"Departure";popupTitle=Translator.trans(popupTitle);if($($("#leaving_baggage_info_ADT_"+segmentIndex).get(loopIdx)).length==0)loopIdx=0;var baggageInfoADT=$($("#leaving_baggage_info_ADT_"+segmentIndex).get(loopIdx)).val()||"";var baggageInfoCNN=$($("#leaving_baggage_info_CNN_"+segmentIndex).get(loopIdx)).val()||"";var baggageInfoINF=$($("#leaving_baggage_info_INF_"+segmentIndex).get(loopIdx)).val()||"";var baggageInfoADTLabel=baggageInfoADT&&baggageInfoADT!=""?"Adult Baggage":"";var baggageInfoCNNLabel=baggageInfoCNN&&baggageInfoCNN!=""?"Child Baggage":"";var baggageInfoINFLabel=baggageInfoINF&&baggageInfoINF!=""?"Infant Baggage":"";var days_difference="";var dayCountDiff=arrTime.getDate()-deptTime.getDate();if(dayCountDiff==1){days_difference="+1 "+Translator.trans("day")}else if(dayCountDiff>1){days_difference="+"+dayCountDiff+" "+Translator.trans("days")}var stop_indicator=$("#stop_indicator-"+idx).val();var next_stop=$("#stop_indicator-"+(parseInt(idx)+1));if(next_stop.length>0&&next_stop.val()==1){duration=convertMinsToHrsMins(diff);stop_label=Translator.trans("1 Stop")}if(duration==""){duration=convertMinsToHrsMins(diff)}var departure_airport_city=$("#origin_location_city-"+idx).val();var departure_airport_code=$("#origin_location-"+idx).val();var arrival_airport_city=$("#destination_location_city-"+idx).val();var arrival_airport_code=$("#destination_location-"+idx).val();if($("#prev_seg_arrival_airport-"+idx).length>0){arrival_airport_city=$("#prev_seg_arrival_airport-"+idx).val()}if($("#prev_seg_arrival_airport_code-"+idx).length>0){arrival_airport_code=$("#prev_seg_arrival_airport_code-"+idx).val()}if($("#arrivalcity").length>0&&$("#arrivalcode").length>0){arrival_airport_city=$("#arrivalcity").val();arrival_airport_code=$("#arrivalcode").val()}var depterminal_id=$("#departure_terminal_id-"+idx).val();var arrterminal_id=$("#arrival_terminal_id-"+idx).val();var departure_terminal=depterminal_id!=""||depterminal_id>0?Translator.trans("Terminal ")+depterminal_id:"";var arrival_terminal=arrterminal_id!=""||arrterminal_id>0?Translator.trans("Terminal ")+arrterminal_id:"";map={popupTitle:popupTitle,departure_date:departureDate,origin_airport:$("#origin_location_airport-"+idx).val(),origin_code:$("#origin_location-"+idx).val(),arrival_airport:$("#destination_location_airport-"+idx).val(),arrival_code:$("#destination_location-"+idx).val(),departure_terminal:departure_terminal,departure_time:departureTime,arrival_time:arrivalTime,duration:duration,flight_number:airline_code+$("#flight_number-"+idx).val(),airline_logo:$("#airline_logo-"+idx).val(),cabin:$("#cabin-"+idx).val(),aircraft_type:$("#aircraft_type-"+idx).val(),departure_airport_city:departure_airport_city,departure_airport_code:departure_airport_code,arrival_airport_city:arrival_airport_city,arrival_airport_code:arrival_airport_code,final_arrival_airport_city:arrival_airport_city,final_arrival_airport_code:arrival_airport_code,total_duration:total_duration,stop_label:stop_label,days_difference:days_difference,baggageInfoADTLabel:baggageInfoADTLabel,baggageInfoCNNLabel:baggageInfoCNNLabel,baggageInfoINFLabel:baggageInfoINFLabel,baggageInfoADT:baggageInfoADT,baggageInfoCNN:baggageInfoCNN,baggageInfoINF:baggageInfoINF};if(stop_indicator>0){stop={stop_indicator:"",stop_duration:$("#stop_duration-"+idx).val(),stop_city:$("#origin_location_city-"+idx).val(),has_stop:"hidden"}}else{loopIdx++;stop={stop_indicator:"hidden",has_stop:""}}var res=$.extend(map,stop);var data=window.ttUtilsInst.template(tmpl,res);$(data).appendTo(".flight_segments")})}}}function flightBookNow(id){var formid="form#"+id;var jqForm=$(formid);var flightRequest={};var flightrequestMainInput=$("input[name=flightrequestMain]");var flightrequestInput=jqForm.find("input[name=flightrequest]");var mainSerializerInputs=jqForm.find("div.main_serializer :input");var mainSerializerJSON=window.ttUtilsInst.formToJsonObject(mainSerializerInputs);var segmentsInbound=$("#segmentsInbound").val();var formJSON=window.ttUtilsInst.formToJsonObject(formid);flightRequest=JSON.parse(flightrequestMainInput.val());delete formJSON.flightrequest;if(!flightRequest.main){flightRequest={main:flightRequest}}flightRequest.main=window.ttUtilsInst.extendObject(flightRequest.main,mainSerializerJSON,true);if(flightRequest.main.oneway=="")flightRequest.main.oneway="0";if(flightRequest.main.multidestination=="")flightRequest.main.multidestination="0";var jqFormSerializers=jqForm.find("div.seg_serializer");if(null==flightRequest.main.totalOriginalPrice||flightRequest.main.totalOriginalPrice=="")flightRequest.main.totalOriginalPrice=flightRequest.main.totalPriceAttr=flightRequest.main.totalOriginalBaseFare=flightRequest.main.totalOriginalTaxes=flightRequest.main.totalBaseFare=flightRequest.main.totalTaxes=0;flightRequest.segments=flightRequest.segments||[];if(!flightRequest.flightIndex)flightRequest.flightIndex=[];flightRequest.flightIndex.push(flightRequest.segments.length);jqFormSerializers.each(function(index){var formSerializeJSON=window.ttUtilsInst.formToJsonObject($(this).find(":input"));if(segmentsInbound&&segmentsInbound!="")flightRequest.segmentsInbound=segmentsInbound;flightRequest.main.sec_token=formSerializeJSON["sec_token"];if(formSerializeJSON["stop_indicator"]!=1&&formSerializeJSON["stop_indicator"]!="1"){if(mainSerializerJSON["price_attr"]>0){flightRequest.main.totalPriceAttr+=parseFloat(mainSerializerJSON["price_attr"])}if(mainSerializerJSON["base_fare_attr"]>0){flightRequest.main.totalBaseFare+=parseFloat(mainSerializerJSON["base_fare_attr"])}if(mainSerializerJSON["taxes_attr"]>0){flightRequest.main.totalTaxes+=parseFloat(mainSerializerJSON["taxes_attr"])}if(mainSerializerJSON["provider_price"]>0){flightRequest.main.totalProviderPrice+=parseFloat(mainSerializerJSON["provider_price"])}if(mainSerializerJSON["original_price"]>0){flightRequest.main.totalOriginalPrice+=parseFloat(mainSerializerJSON["original_price"])}if(mainSerializerJSON["original_base_fare"]>0){flightRequest.main.totalOriginalBaseFare+=parseFloat(mainSerializerJSON["original_base_fare"])}if(mainSerializerJSON["original_taxes"]>0){flightRequest.main.totalOriginalTaxes+=parseFloat(mainSerializerJSON["original_taxes"])}formSerializeJSON.segmentAmounts={price_attr:parseFloat(mainSerializerJSON["price_attr"]),base_fare_attr:parseFloat(mainSerializerJSON["base_fare_attr"]),taxes_attr:parseFloat(mainSerializerJSON["taxes_attr"]),original_price:parseFloat(mainSerializerJSON["original_price"]),original_base_fare:parseFloat(mainSerializerJSON["original_base_fare"]),original_taxes:parseFloat(mainSerializerJSON["original_taxes"])}}if(formSerializeJSON.oneway=="")formSerializeJSON.oneway="0";if(formSerializeJSON.multidestination=="")formSerializeJSON.multidestination="0";flightRequest.segments.push(formSerializeJSON)});if(!flightRequest.nbStops)flightRequest.nbStops=0;flightRequest.nbStops+=jqFormSerializers.length-1;var expectedSegments=flightRequest.segments.length-flightRequest.nbStops;if(expectedSegments<flightRequest.main.arrivalairportC.length){flightRequest.nextSegment=expectedSegments}else delete flightRequest.nextSegment;flightrequestInput.val(JSON.stringify(flightRequest));localStorage.removeItem("flightsFilters");if(jqForm.length>0){jqForm.submit()}}function updateSelectedFlightSegment(counter){if(null==counter||counter==""||isNaN(counter))counter=0;var jqForm=formid=null;if(counter==0){formid="airplaneform";jqForm=$("#"+formid)}else{formid="updateSelectedSegments_"+counter;jqForm=$("#"+formid);var flightrequestInput=jqForm.find("input[name=flightrequest]");var flightrequestMainInput=$("input[name=flightrequestMain]");var flightRequest=JSON.parse(flightrequestMainInput.val());var selectedSegments=flightRequest.segments;var segmentsIndex=flightRequest.flightIndex;var startIndex=segmentsIndex[counter];var removedSegments=selectedSegments.splice(startIndex);flightRequest.segments=selectedSegments;flightRequest.flightIndex.splice(counter);for(var i=0;i<removedSegments.length;i++){var seg=removedSegments[i];var segmentAmounts=seg.segmentAmounts;if(seg["stop_indicator"]!=1&&seg["stop_indicator"]!="1"){flightRequest.main.totalPriceAttr-=parseFloat(segmentAmounts["price_attr"]);flightRequest.main.totalBaseFare-=parseFloat(segmentAmounts["base_fare_attr"]);flightRequest.main.totalTaxes-=parseFloat(segmentAmounts["taxes_attr"]);flightRequest.main.totalOriginalPrice-=parseFloat(segmentAmounts["original_price"]);flightRequest.main.totalOriginalBaseFare-=parseFloat(segmentAmounts["original_base_fare"]);flightRequest.main.totalOriginalTaxes-=parseFloat(segmentAmounts["original_taxes"])}}flightRequest.nextSegment=flightRequest.flightIndex.length;flightrequestInput.val(JSON.stringify(flightRequest))}if(jqForm.length>0){jqForm.submit()}}