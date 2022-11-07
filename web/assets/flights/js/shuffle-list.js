'use strict';

var Shuffle = window.Shuffle;

var element = document.querySelector('.my-shuffle-container');
var sizer = element.querySelector('.my-sizer-element');

var filterObject = {
    departure_airportss : [],
    departure_times : [],
    arrival_times : [],
    airliness : [],
    others : [],
    prices : [],
    stopss : []
};

var shuffleDemo =
        function(element)
        {
	        this.departure_airportss =
	                Array.from(document
	                        .querySelectorAll('#bottomNavBar input[data-value="departure_airports"].form-check-input'));
	        this.departure_times =
	                Array.from(document
	                        .querySelectorAll('#bottomNavBar input[data-value="departure_time"].form-check-input'));
	        this.arrival_times =
	                Array.from(document
	                        .querySelectorAll('#bottomNavBar input[data-value="arrival_time"].form-check-input'));
	        this.airlines =
	                Array
	                        .from(document
	                                .querySelectorAll('#bottomNavBar input[data-value="airlines"].form-check-input'));
	        this.others =
	                Array.from(document.querySelectorAll('#bottomNavBar input[data-value="other"].form-check-input'));
	        this.prices = [];
	        this.stopss = [];

	        this.shuffle = new Shuffle(element, {
		        sizer : sizer,
	        });

	        this.filters = filterObject;
        };

var myShuffleInstance = new shuffleDemo(element);
var shuffleInstance = myShuffleInstance.shuffle;

/**
 * If any of the arrays in the `filters` property have a length of more than zero, that means there is an active filter.
 * 
 * @return {boolean}
 */
shuffleDemo.prototype.hasActiveFilters = function()
{
	return Object.keys(this.filters).some(function(key)
	{
		return this.filters[key].length > 0;
	}, this);
};
/**
 * Filter shuffle based on the current state of filters.
 */
shuffleDemo.prototype.filter = function()
{
	if (this.hasActiveFilters()) {
		this.shuffle.filter(this.itemPassesFilters.bind(this));
	} else {
		this.shuffle.filter(Shuffle.ALL_ITEMS);
	}
};
/**
 * Determine whether an element passes the current filters.
 * 
 * @param {Element}
 *            element Element to test.
 * @return {boolean} Whether it satisfies all current filters.
 */
shuffleDemo.prototype.itemPassesFilters = function(element)
{
	var departure_airportss = this.filters.departure_airportss;
	var departure_times = this.filters.departure_times;
	var arrival_times = this.filters.arrival_times;
	var airliness = this.filters.airliness;
	var others = this.filters.others;
	var prices = this.filters.prices;
	var stopss = this.filters.stopss;

	var departure_airports = element.getAttribute('data-departure_airports');
	var departure_time = element.getAttribute('data-departure_time');
	var arrival_time = element.getAttribute('data-arrival_time');
	var airlines = element.getAttribute('data-airlines');
	var other = element.getAttribute('data-other');
	var price = element.getAttribute('data-price-raw');
	var stops = element.getAttribute('data-stops');

	// If there are active shape filters and this shape is not in that array.
	if ((departure_airportss.length > 0) && !departure_airportss.includes(departure_airports)) {
		return false;
	}
	if ((arrival_times.length > 0) && !arrival_times.includes(arrival_time)) {
		return false;
	}
	if ((departure_times.length > 0) && !departure_times.includes(departure_time)) {
		return false;
	}
	if ((airliness.length > 0) && !airliness.includes(airlines)) {
		return false;
	}
	if ((others.length > 0) && !others.includes(other)) {
		return false;
	}
	if ((prices.length > 0) && (price < prices[0] || price > prices[prices.length - 1])) {
		return false;
	}
	if ((stopss.length > 0) && !stopss.includes(parseInt(stops))) {
		return false;
	}
	return true;
};

shuffleInstance.on(Shuffle.EventType.LAYOUT, function()
{
	console.log('Things finished moving!');
});

function sortByTitle(element)
{
	return element.getAttribute('data-title');
}

function sortByMiles(element)
{
	return element.getAttribute('data-miles');
}

function sortByDuration(element)
{
	return element.getAttribute('data-duration');
}

function sortByRecommended(element)
{
	return element.getAttribute('data-recommended');
}

function sortByDeparture(element)
{
	return element.getAttribute('data-departure_time');
}

function sortByArrival(element)
{
	return element.getAttribute('data-arrival_time');
}

function sortByPrice(element)
{
	return element.getAttribute('data-price-raw');
}

function compareItems(a, b, field)
{
	var groupA = parseInt(a.element.getAttribute(field), 10);
	var groupB = parseInt(b.element.getAttribute(field), 10);
	var groupReturn = groupA - groupB;
	return groupReturn;
}

function sortItemsByValue(thisValue)
{
	var options;
	if (thisValue) {
		if (thisValue === 'title') {
			options = {
			    reverse : true,
			    by : sortByTitle,
			};
		} else {

			var reverse = false;

			var sortElem = $(".sort-options").find("label[data-value='" + thisValue + "']");

			if ($(sortElem).attr("data-sortby") == "asc") {
				reverse = true;
				$(sortElem).attr("data-sortby", "desc")
			} else {
				reverse = false;
				$(sortElem).attr("data-sortby", "asc")
			}

			options = {
			    reverse : reverse,
			    compare : function(a, b)
			    {
				    return compareItems(a, b, 'data-' + thisValue);
			    }
			};
		}
	} else {
		options = {};
	}
	shuffleInstance.sort(options);
}

function getAllCheckboxesValues()
{

	var filterObject = {
	    departure_airportss : [],
	    arrival_times : [],
	    departure_times : [],
	    airliness : [],
	    others : [],
	    prices : [],
	    stopss : []
	};

	$('.form-check-input').each(function()
	{
		var $this = $(this);
		var thisDataValue = $this.attr("data-value");
		var thisValue = $this.val();
		if ($this.is(':checked')) {
			if (thisDataValue === 'departure_airports') {
				if (!$.inArray(thisDataValue, filterObject.departure_airportss) !== -1) {
					filterObject.departure_airportss.push(thisValue);
				}
			} else if (thisDataValue === 'arrival_time') {
				if (!$.inArray(thisDataValue, filterObject.arrival_times) !== -1) {
					filterObject.arrival_times.push(thisValue);
				}
			} else if (thisDataValue === 'departure_time') {
				if (!$.inArray(thisDataValue, filterObject.departure_times) !== -1) {
					filterObject.departure_times.push(thisValue);
				}
			} else if (thisDataValue === 'airlines') {
				if (!$.inArray(thisDataValue, filterObject.airliness) !== -1) {
					filterObject.airliness.push(thisValue);
				}
			} else if (thisDataValue === 'other') {
				if (!$.inArray(thisDataValue, filterObject.others) !== -1) {
					filterObject.others.push(thisValue);
				}
			}
		} else {

			if (thisDataValue === 'departure_airports') {
				filterObject.departure_airportss = filterObject.departure_airportss.filter(function(item)
				{
					return item !== thisValue
				})
			} else if (thisDataValue === 'arrival_time') {
				filterObject.arrival_times = filterObject.arrival_times.filter(function(item)
				{
					return item !== thisValue
				})
			} else if (thisDataValue === 'departure_time') {
				filterObject.departure_times = filterObject.departure_times.filter(function(item)
				{
					return item !== thisValue
				})
			} else if (thisDataValue === 'airlines') {
				filterObject.airliness = filterObject.airliness.filter(function(item)
				{
					return item !== thisValue
				})
			} else if (thisDataValue === 'other') {
				filterObject.others = filterObject.others.filter(function(item)
				{
					return item !== thisValue
				})
			}

		}
	});

	filterObject.stopss = myStopsArray;
	filterObject.prices = myPricesArray;

	return filterObject;
}

function getAllCheckboxes()
{
	var filterObject = getAllCheckboxesValues();
	myShuffleInstance.filters = filterObject;
	myShuffleInstance.filter();

	if (typeof (Storage) !== "undefined") {
		localStorage.setItem("flightsFilters", JSON.stringify(filterObject));
	}
}

var myStopsArray = [];
var myPricesArray = [];

function initRangeSlider(myClass, myID, startValue, maxValue, myLow, myHigh, mySTep)
{
	var mySlider = new Slider("#" + myID, {
	    min : startValue,
	    max : maxValue,
	    step : mySTep,
	    value : [ startValue, maxValue ],
	    labelledby : [ myLow, myHigh ]
	});

	var endK = (myClass === 'priceslider') ? 50 : 1;
	maxValue = (myClass === 'priceslider') ? maxValue + endK : maxValue;
	for (var k = startValue; k <= maxValue; k += endK) {
		var mySTR =
		        '<input type="hidden" name="' + myClass + '_' + k + '" data-value="' + myClass + '" value="' + k
		                + '" />';
		$("#" + myID).parent().append(mySTR);
		if (myClass === 'priceslider') {
			if ($.inArray(k, myPricesArray) === '-1') {
				myPricesArray.push(k);
			}
			// myPricesArray.push(k);
		} else if (myClass === 'stopslider') {
			if ($.inArray(k, myStopsArray) === '-1') {
				myStopsArray.push(k);
			}
			// myStopsArray.push(k);
		}
	}

	var windowWidth = $(window).width();
	var myParent = ".left_panel";
	if (windowWidth < 992) {
		myParent = "#bottomNavBar";
	}

	var myListArray = Array.from(document.querySelectorAll(myParent + ' input[data-value="' + myClass + '"]'));

	if (myClass === 'stopsslider') {
		shuffleDemo.stopss = myListArray;
	} else if (myClass === 'priceslider') {
		shuffleDemo.prices = myListArray;
	}

	mySlider.on("slideStop", function(sliderValue)
	{

		var myLowValue = sliderValue[0];
		var myHighValue = sliderValue[1];

		if (myClass === 'stopsslider') {
			myStopsArray = getSlidersValues(myClass, myLowValue, myHighValue);
		} else if (myClass === 'priceslider') {
			myPricesArray = getSlidersValues(myClass, myLowValue, myHighValue);
		}
		getAllCheckboxes();

	});

}

function getSlidersValues(myTarget, myLowValue, myHighValue)
{
	var myArray = [];
	if (myTarget === 'stopsslider') {
		for (var i = myLowValue; i <= myHighValue; i++) {
			myArray.push(i);
		}
	} else if (myTarget === 'priceslider') {
		for (var j = myLowValue; j <= myHighValue; j += 50) {
			myArray.push(j);
		}
	}
	$(document).find('.' + myTarget).val(myLowValue + ',' + myHighValue);
	$(document).find('.' + myTarget).attr('data-value', myLowValue + ',' + myHighValue);
	return myArray;
}

function initPriceSlider()
{
	$(".priceslider").each(function()
	{
		var $this = $(this);
		var myID = $this.attr("id");
		var $low = $this.parent('div').find('.low').attr("id");
		var $high = $this.parent('div').find('.high').attr("id");
		var startValue = parseInt($('#minimum_price').val());
		var maxValue = parseInt($('#maximum_price').val());
		// console.log( 'priceslider ,'+myID+','+startValue+','+maxValue+','+$low+','+ $high );
		initRangeSlider('priceslider', myID, startValue, maxValue, $low, $high, 50);
	});
}

$(document).ready(function()
{

	$(document).on('click', '.sort_item', function()
	{
		var $this = $(this);
		$('.sort_item').removeClass('active');
		$this.addClass('active');
		var thisValue = $this.attr('data-value');
		sortItemsByValue(thisValue);

		if ($('#popup').length > 0) {
			$('#popup').hide();
		}

		//we update sorting from flightCookie
		var flightCookie = $.parseJSON(window.ttUtilsInst.getCookies('flight'));
		flightCookie.sort.name = thisValue;
		flightCookie.sort.order = $this.attr('data-sortby');
		window.ttUtilsInst.setCookies('flight', JSON.stringify(flightCookie));
	});

	$(document).on('change', '.form-check-input', function()
	{
		getAllCheckboxes();
		if ($('#popup').length > 0) {
			$('#popup').hide();
		}
		//
		if ($(this).data("value") == "airlines") {
			if ($(this).attr("skip-carousel-action")) {
				$(this).removeAttr("skip-carousel-action");
			} else
				$('.slick-slide').removeClass('slick-current');
		}
	});

	$(".stopsslider").each(function()
	{
		var $this = $(this);
		var myID = $this.attr("id");
		var $low = $this.parent('div').find('.low').attr("id");
		var $high = $this.parent('div').find('.high').attr("id");
		initRangeSlider('stopsslider', myID, 0, 2, $low, $high, 1);
	});
	initPriceSlider();

	if (typeof (Storage) !== "undefined") {

		// reset localStorage.flightsFilters
		$(document).on('click', '.search_hpage', function()
		{
			localStorage.removeItem('flightsFilters');
		});

		if (localStorage.hasOwnProperty("flightsFilters")) {
			var flightsFilters = JSON.parse(localStorage.getItem("flightsFilters"));

			myShuffleInstance.filters = flightsFilters;
			myShuffleInstance.filter();

			// departure_airportss check
			flightsFilters.departure_airportss.forEach(function(s)
			{
				$('input[type=checkbox][data-value=departure_airports][value="' + s + '"]').attr('checked', 'checked')
			});

			// arrival_times check
			flightsFilters.arrival_times.forEach(function(s)
			{
				$('input[type=checkbox][data-value=arrival_time][value="' + s + '"]').attr('checked', 'checked')
			});

			// departure_times check
			flightsFilters.departure_times.forEach(function(s)
			{
				$('input[type=checkbox][data-value=departure_time][value="' + s + '"]').attr('checked', 'checked')
			});

			// airlines check
			flightsFilters.airliness.forEach(function(s)
			{
				$('input[type=checkbox][data-value=airlines][value="' + s + '"]').attr('checked', 'checked')
			});

			// others check
			flightsFilters.others.forEach(function(s)
			{
				$('input[type=checkbox][data-value=other][value="' + s + '"]').attr('checked', 'checked')
			});

		}
	}

	if (typeof (Storage) !== "undefined") {

		// reset localStorage.flightsFilters
		$(document).on('click', '.search_hpage', function()
		{
			localStorage.removeItem('flightsFilters');
		});

		if (localStorage.hasOwnProperty("flightsFilters")) {
			var flightsFilters = JSON.parse(localStorage.getItem("flightsFilters"));

			myShuffleInstance.filters = flightsFilters;
			myShuffleInstance.filter();

			// departure_airportss check
			flightsFilters.departure_airportss.forEach(function(s)
			{
				$('input[type=checkbox][data-value=departure_airports][value="' + s + '"]').attr('checked', 'checked')
			});

			// arrival_times check
			flightsFilters.arrival_times.forEach(function(s)
			{
				$('input[type=checkbox][data-value=arrival_time][value="' + s + '"]').attr('checked', 'checked')
			});

			// departure_times check
			flightsFilters.departure_times.forEach(function(s)
			{
				$('input[type=checkbox][data-value=departure_time][value="' + s + '"]').attr('checked', 'checked')
			});

			// airlines check
			flightsFilters.airliness.forEach(function(s)
			{
				$('input[type=checkbox][data-value=airlines][value="' + s + '"]').attr('checked', 'checked')
			});

			// others check
			flightsFilters.others.forEach(function(s)
			{
				$('input[type=checkbox][data-value=other][value="' + s + '"]').attr('checked', 'checked')
			});

		}
	}

});
