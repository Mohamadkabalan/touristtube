$(document).ready(function () {
    if ($("#searchdiscover").length) {
	addAutoCompleteListHotels();
    }

});


function addAutoCompleteListHotels(which) {
    var $ccity = $("#searchdiscover");
    $ccity.autocomplete({
	minLength: minSearchLength,
	appendTo: "#searchhrscontainer",
	search: function (event, ui) {
	    $ccity.autocomplete("option", "source", generateLangURL('/ajax/Hotel_search_for1'));
	},
	select: function (event, ui) {
	    //$ccity.val(ui.item.name);
	    $ccity.parent().parent().find('#destinationAddress').val(ui.item.name);
	    /*$ccity.parent().find('#locationId').val(ui.item.lid);
	     $ccity.parent().find('#hotelCityName').val(ui.item.name);
	     $ccity.parent().find('#hotelId').val(ui.item.hotelId);
	     $ccity.parent().find('#entityType').val(ui.item.entityType);
	     $ccity.parent().find('#longitude').val(ui.item.longitude);
	     $ccity.parent().find('#latitude').val(ui.item.latitude);
	     $ccity.parent().find('#country').val(ui.item.country);
	     $ccity.parent().find('#stars').val(0);*/
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