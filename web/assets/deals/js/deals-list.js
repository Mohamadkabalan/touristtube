var mySlider = [];
var filterObject = {
    sliders: {
	price_slider: []
    }
};

var fixFilterAfterCurrencyChange;
$(document).ready(function () {
    $("[data-toggle='tooltip']").tooltip();

    if ($("#deal_search").length) {
	addAutoCompleteListDeals();
    }

    $('.loadmore_dat').click(function () {
	$("input[name='loadMoreChange']").val(1);
	loadSearchResults();
    });

    //if checkbox is unchecked or checked
    $("input[type='checkbox']").change(function () {
	$("input[name='loadMoreChange']").val(0);
	loadSearchResults();
    });

    $('.form-check-label').click(function () {
	var checkbox = $(this).prev('input[type=checkbox]');
	if (checkbox.is(':checked')) {
	    checkbox.prop('checked', false);
	} else {
	    checkbox.prop('checked', true);
	}
	$("input[name='loadMoreChange']").val(0);
	loadSearchResults();
    });

    initBudgetSlider();
    changeCurrency();

    fixFilterAfterCurrencyChange = function () {
	initBudgetSlider();
    };
});

function changeCurrency() {
    $(".currency-item").click(function () {
	var fromCurrency = $('.currency-item.selected').attr('currency-code-data');
	$('.currency-convert-text').html(fromCurrency);
	initBudgetSlider();
    });
}
// destroy existing sliders
function destroyingExistingSlider() {
    for (var sliderType in filterObject.sliders) {
	for (var item in filterObject.sliders[sliderType]) {
	    var slider = filterObject.sliders[sliderType][item];
	    try {
		slider.destroy();
	    } catch (ex) {
	    }
	}
    }
}

//initializing the budget slider
function initBudgetSlider() {
    // destroy existing sliders
    destroyingExistingSlider();

    $(".budgetslider").each(function () {
	var $this = $(this);
	var myID = $this.attr("id");
	var $low = $this.parent('div').find('.low').attr("id");
	var $high = $this.parent('div').find('.high').attr("id");

	var min = parseInt($("input[name='minPrice']").val());
	var max = parseInt($("input[name='maxPrice']").val());
	var start = min;
	var end = max;

	var pricesList = [];
	if ($(".pbpValue").length) {
	    $(".pbpValue").each(function (index) {
		pricesList[index] = parseInt($(this).attr('data-price'));
	    });
	    start = Math.min.apply(Math, pricesList);
	    end = Math.max.apply(Math, pricesList);
	}
	initRangeSlider($this, myID, min, max, start, end, $low, $high);
    });
}

//initializing the range slider
function initRangeSlider(myClass, myID, minValue, maxValue, startValue, endValue, myLow, myHigh) {
    mySlider = new Slider("#" + myID, {
	min: minValue,
	max: maxValue,
	value: [startValue, endValue],
	labelledby: [myLow, myHigh]
    });

    mySlider.on("slideStop", function (sliderValue) {
	$("input[name='newMinPrice']").val(sliderValue[0]);
	$("input[name='newMaxPrice']").val(sliderValue[1]);
	$("input[name='loadMoreChange']").val(0);
	loadSearchResults();
    });

    var fromCurrency = $('.currency-item.selected').attr('currency-code-data');
    $('.currency-convert-text').html(fromCurrency);

    filterObject.sliders.price_slider.push(mySlider);

}
//this handles the search results
function loadSearchResults() {

    var inputNames = ['dealType', 'location', 'dealNameSearch', 'startDate',
	'endDate', 'minPrice', 'maxPrice', 'priority', 'langCode',
	'hasSearch', 'cityName', 'startDate', 'endDate', 'dealName',
	'cityId', 'minPrice', 'maxPrice', 'categoryIds'];

    var myData = new Object();
    var categoryIds = [];
    $.each(inputNames, function (key, val) {
	if ($("input[name='" + val + "']").length && $("input[name='" + val + "']").val().length) {
	    myData[val] = $("input[name='" + val + "']").val();
	}
	if (val == 'categoryIds') {
	    $(':checkbox:checked').each(function (i) {
		categoryIds[i] = $(this).val();
	    });
	    myData[val] = categoryIds;
	}
    });

    var limit = 20;
    if ($("input[name='loadMoreChange']").val() == 1) {
	if ($("input[name='offset']").length) {
	    var offset = parseInt($("input[name='offset']").val()) + limit;
	    myData['offSet'] = offset;
	    $("input[name='offset']").val(offset);
	}
    }

    myData['selectedCurrency'] = $('.currency-item.selected').attr('currency-code-data');
    myData['minPrice'] = $("input[name='newMinPrice']").val();
    myData['maxPrice'] = $("input[name='newMaxPrice']").val();

    var loadMorepath = $("input[name='getLoadMoreURL']").val();
    showDealsOverlay("dealListResults");
    $.ajax({
	url: loadMorepath,
	type: 'POST',
	data: myData,
	success: function (result) {
	    hideDealsOverlay();
	    if (result.numRowCnt > 0) {
		if ($("input[name='loadMoreChange']").val() == 1) {
		    $("#searchResults").append(result.twigResults);
		} else {
		    $("#searchResults").html('');
		    $("#searchResults").html(result.twigResults);
		}
		initBudgetSlider();
	    }

	    if (result.numRowCnt == 0 || result.numRowCnt < limit) {
		if ($("#loadMoreBtn").is(":visible")) {
		    $("#loadMoreBtn").hide();
		}
	    } else {
		if ($("#loadMoreBtn").is(":hidden")) {
		    $("#loadMoreBtn").show();
		}
	    }
	},
	error: function (error) {
	    hideDealsOverlay();
	    alert('error; ' + eval(error));
	}
    });
}
//autocomplete
function addAutoCompleteListDeals(which) {
    var $ccity = $("#deal_search");
    $ccity.autocomplete({
	minLength: minSearchLength,
	appendTo: "#dealsform",
	search: function (event, ui) {
	    $ccity.autocomplete("option", "source", generateLangURL('/ajax/deal_search_for'));
	},
	select: function (event, ui) {
	    $ccity.val(ui.item.name);
	    $ccity.parent().find('#attractionName').val(ui.item.attractionName);
	    $ccity.parent().find('#cityName').val(ui.item.cityName);
	    $ccity.parent().find('#dealId').val(ui.item.dealId);
	    $ccity.parent().find('#cityId').val(ui.item.cityId);
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
}