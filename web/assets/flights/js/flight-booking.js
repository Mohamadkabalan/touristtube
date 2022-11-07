var TO_CAL_FLIGHT;
var FROM_CAL_FLIGHT;
var FROM_CAL_FLIGHT0;
var FROM_CAL_FLIGHT1;
var FROM_CAL_FLIGHT2;
var FROM_CAL_FLIGHT3;
var TO_CAL_FLIGHTMin;
var DATE_INFO = {};
$(document).ready(function () {
    $(function () {
        try {
            var flights = $.parseJSON($.cookie("flight"));
            var urlParams = new URLSearchParams(window.location.search);

            if(!urlParams.has('reservationId')){
             $.each(flights, function (key, data) {

                if (data.name === 'fromDateC' || data.name === 'toDateC' || data.name === 'fromDate' || data.name === 'toDate') {

                    if (data.name === 'fromDate' || data.name === 'toDate') {
                        var expireDateArr = data.value.split(" / ");
                        var expireDate = new Date(expireDateArr[2] + "-" + expireDateArr[1] + "-" + expireDateArr[0]);
                    } else {
                        var expireDate = new Date(data.value);
                    }

                    var todayDate = new Date();

                    if (todayDate > expireDate) {

                        var nowYear = todayDate.getFullYear();
                        var nowMo = todayDate.getMonth() + 1; // for getMonth(), January is 0
                        var nowDay = todayDate.getDate();

                        if (data.name === 'fromDateC')
                            data.value = nowYear + '-' + ((nowMo < 10) ? '0' + nowMo : nowMo) + '-' + ((nowDay < 10) ? '0' + nowDay : nowDay);
                        if (data.name === 'fromDate')
                            data.value = ((nowDay < 10) ? '0' + nowDay : nowDay) + ' / ' + ((nowMo < 10) ? '0' + nowMo : nowMo) + ' / ' + nowYear;

                        if (data.name === 'toDateC')
                            data.value = nowYear + '-' + ((nowMo < 10) ? '0' + nowMo : nowMo) + '-' + (((nowDay < 10) ? '0' + nowDay : nowDay) + 1);
                        if (data.name === 'toDate')
                            data.value = (((nowDay < 10) ? '0' + nowDay : nowDay) + 1) + ' / ' + ((nowMo < 10) ? '0' + nowMo : nowMo) + ' / ' + nowYear;

                    }
                }

                if(!window.SKIP_AUTO_LOAD_COOKIES_SEARCH_FORM)
                	$('input[name="' + data.name + '"]').val(data.value);

                /*
                if (data.name == "fromDateC" || data.name == "toDateC") {
                    $('input[name="' + data.name + '"]').val(data.value);
                }
                */

                if (data.name === 'flexibledate') {
                    $('input[type="checkbox"][name="' + data.name + '"]').attr('checked', 'checked');
                    $('input[type="checkbox"][name="' + data.name + '"]').val(1);
                }else if (data.name === 'oneway' && data.value === "1") {
                    $('.onewayclass').trigger('click');
                } else if (data.name === 'multidestination' && data.value === "1") {
                    $('.multipledestclass').trigger('click');
                }
                $('select[name="' + data.name + '"]').val(data.value);
             });
            }
        } catch (err) {
        	console.error(err);
        }
    });
	
	// current date
    var today = new Date();
	
    resetOtherFlights(0,today);
    resetOtherFlights(1,today);
	
    $('#fromDate').val('');
    $('#toDate').val('');
    $('#fromDate-0').val('');
    $('#fromDate-1').val('');
    $('#flexibledate').val('0');
	
    $("input:text").focus(function(){
        $(this).removeClass("error");
    });
	
    if ($('#departureairport').length > 0) {
        setAutocompleteAirports('departureairport');
        setAutocompleteAirports('arrivalairport');
    }
    if ($('#departureairport-0').length > 0) {
        setAutocompleteAirports('departureairport-0');
        setAutocompleteAirports('arrivalairport-0');
    }
    if ($('#departureairport-1').length > 0) {
        setAutocompleteAirports('departureairport-1');
        setAutocompleteAirports('arrivalairport-1');
    }
    if ($('#departureairport-2').length > 0) {
        setAutocompleteAirports('departureairport-2');
        setAutocompleteAirports('arrivalairport-2');
    }
    if ($('#departureairport-3').length > 0) {
        setAutocompleteAirports('departureairport-3');
        setAutocompleteAirports('arrivalairport-3');
    }

    $('#flexibledate').change(function() {
        if($(this).is(":checked")) {
            $(this).val(1);
        }else{
            $(this).val(0);
        }
    });
	
    $('#airplaneform').submit(function (event) {
		return false;
		$('.error_cnt_corporate').html('');
		$('input').removeClass('srch_error');
        var departureairport = $('#departureairportC').val();
        var _departureairport = $('#departureairport').val();
		var i=0;
		
        if (departureairport == '' || _departureairport == '') {
            event.preventDefault();
			showErrorMsg('Invalid departure airport');
	    	$('#departureairport').addClass('srch_error error');
            i++;
        }
        var arrivalairport = $('#arrivalairportC').val();
        var _arrivalairport = $('#arrivalairport').val();
        if ((arrivalairport == '' || _arrivalairport == '') || arrivalairport == departureairport) {
            event.preventDefault();
            $('#arrivalairport').val('');
            $('#arrivalairportC').val('');
			showErrorMsg('Invalid arrival airport');
	    	$('#arrivalairport').addClass('srch_error error');
            i++;
        }
		
        var fromDate = $('#airplaneform #fromDate').val();
        if (fromDate == '') {
            showErrorMsg('invalid departure date');
            event.preventDefault();
			$('#airplaneform #fromDate').addClass('srch_error error');
            i++;
        }
		
        var toDate = $('#airplaneform #toDate').val();
        if (toDate == '' && $('.roundtripclass').hasClass("active")) {
            showErrorMsg('invalid departure date');
            event.preventDefault();
	   		$('#airplaneform #toDate').addClass('srch_error error');
            i++;
        }
		
        var adultsselect = $('#adultsselect').val();
        var infantsselect = $('#infantsselect').val();
        if (adultsselect < infantsselect) {
            showErrorMsg('invalid departure date');
            event.preventDefault();
	    	$('#adultsselect').addClass('srch_error error');
	    	$('#infantsselect').addClass('srch_error error');
            i++;
        }
		
        if ($('#departureairport-0').val() == '' && ($('#arrivalairport-0').val() != '' || $('#fromDate-0').val() != '')) {
            showErrorMsg('invalid departure airport');
            $('#departureairport-0').addClass("srch_error error");
            event.preventDefault();
            i++;
        }
		
        if ($('#departureairport-0').val() != '' && $('#arrivalairport-0').val() == "") {
			showErrorMsg('invalid arrival airport');
            $('#arrivalairport-0').addClass("srch_error error");
            event.preventDefault();
            i++;
        }
		
        if ($('#departureairport-0').val() != '' && $('#fromDate-0').val() == "") {
            showErrorMsg('invalid departure airport');
            $('#fromDate-0').addClass("srch_error error");
            event.preventDefault();
            i++;
        }
		
        if ($('#departureairport-1').val() == '' && ($('#arrivalairport-1').val() != '' || $('#fromDate-1').val() != '')) {
			showErrorMsg('invalid departure airport');
            $('#departureairport-1').addClass("srch_error error");
            event.preventDefault();
            i++;
        }
		
        if ($('#departureairport-1').val() != '' && $('#arrivalairport-1').val() == "") {
			showErrorMsg('invalid arrival airport');
            $('#arrivalairport-1').addClass("srch_error error");
            event.preventDefault();
            i++;
        }
		
        if ($('#departureairport-1').val() != '' && $('#fromDate-1').val() == "") {
            showErrorMsg('invalid departure airport');
            $('#fromDate-1').addClass("srch_error error");
            event.preventDefault();
            i++;
        }
		
		if(i>0){
		   return false;
		}else{
			var formData = ($(this).serializeArray());
			$.cookie.raw = true;
			$.cookie.json = true;
			$.cookie('flight', JSON.stringify(formData));

			$('.upload-overlay-loading-fix').addClass('flightsLoader');
			$('.upload-overlay-loading-fix').show();
		}
    });
	
    if ($('.onewayclass').hasClass("active")) {
        $('#toContainer').hide();
    }
	
    $('html, body').animate({scrollTop: 0}, 0);
	
});

function addCalToFlight(cals) {
    if (new Date($('#airplaneform #fromDateC').val()) > new Date($('#airplaneform #toDateC').val())) {
        $('#airplaneform #toDateC').val($('#airplaneform #fromDateC').val());
        $('#airplaneform #toDate').val($('#airplaneform #fromDate').val());
    }
}
function resetDatesFlight(objCal, obj1, obj2, date) {
    if ($(obj1).length > 0) {
        objCal.args.min = date;
        objCal.args.date = date;
        objCal.redraw();
        objCal.moveTo(date);
        obj1.val('');
        obj2.val('');
    }
}
function getDateInfoFlight(date, wantsClassName) {
    var as_number = Calendar.dateToInt(date);
    return DATE_INFO[as_number];
}
function onFocusFunFlight() {
    if (TO_CAL_FLIGHTMin) {
        var $selectedminelement = Number(Calendar.printDate(TO_CAL_FLIGHT.args.min, "%Y%m%d"));
        DATE_INFO = {};
        DATE_INFO[$selectedminelement] = {klass: "firstDayActive"};
        TO_CAL_FLIGHT.redraw();
    }
}
function setAutocompleteAirports(wich) {
    var $obj = $('#' + wich);
    var $objparentid = $obj.parent().attr('id');
    $obj.autocomplete({
        minLength: minSearchLength,
        appendTo: "#" + $objparentid,
        search: function (event, ui) {
            $obj.autocomplete("option", "source", generateLangURL('/ajax/search_airport_code'));
        },
        select: function (event, ui) {
            if (wich == 'arrivalairport') {
                if (ui.item.airport_code == $('#departureairportC').val()) {
                    $('#arrivalairport').val('');
                    $('#arrivalairportC').val('');
                    TTAlert({
                        msg: 'invalid arrival airport',
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
            }
            $obj.val(ui.item.value);
            $('#' + wich + 'C').val(ui.item.airport_code);

            var oneway = $('#oneway').val();
            var multidest = $('#multiDestination').val();

            if(oneway == 1 && multidest == 1){
                setMultidesFields(wich, ui.item.value, ui.item.airport_code);
            }

            event.preventDefault();
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a class='auto_tuber'>" + item.label + "</a>")
                .appendTo(ul);
    };
}
function resetForm() {
    $('input').val('');
    $.cookie('flight', null);
    $("input:text").removeClass("error");
    document.getElementById("airplaneform").reset();
    $('.roundtripclass').click();
}

function resetOtherFlights(_id,dates){
    if($('#fromDate-'+_id).val()<dates && $('#fromDate-'+_id).val()!=''){
		$('#fromDate-'+_id).val(dates);
		if(_id==0){
			$('#fromDate-1').val('');
			resetOtherFlights(1,dates);
		}
    }
	
    if($('#fromDate-'+_id).data('dateRangePicker')) $('#fromDate-'+_id).data('dateRangePicker').destroy();
	
    $('#fromDate-'+_id).dateRangePicker({
		autoClose : true,
		showTopbar: false,
		startDate: dates,
		singleDate: true,
		singleMonth: true,
		setValue: function(s) {
			$('#fromDate-'+_id).val(s);
			if(_id==0){
			resetOtherFlights(1,s);
			}
		}
    });
}

function setMultidesFields(selector, airport, airportCode){
    if(selector === "arrivalairport"){
        $('#departureairport-0').val(airport);
        $('#departureairport-0C').val(airportCode);
    }
    if(selector === "arrivalairport-0"){
        $('#departureairport-1').val(airport);
        $('#departureairport-1C').val(airportCode);
    }
}
