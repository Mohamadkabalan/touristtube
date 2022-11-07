function getCurrentTime() {
    var thisdate = new Date();
    var hours = thisdate.getHours();
    if (hours < 10) {
        hours = "0" + hours;
    }
    var minutes = thisdate.getMinutes();
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    var time = hours + ":" + minutes;
    return time;
}
function setCurrency(currencyGroup, var1, var2, var3, curName) {
    $.fancybox.close();
    $("#hotelBudgetOption1 span").html(var1);
    $("#hotelBudgetOption2 span").html(var2);
    $("#hotelBudgetOption3 span").html(var3);
    $(".changeCurrencyBtn").html(curName);
}
$(document).ready(function() {
    InitCalendar('checkinDate');
    autocompleteRestaurants($('#hotelTextInput_search'));
    /* Set The Time*/
    $('#restaurantTime').timeEntry({
        show24Hours: true
    });
    $("#restaurantTime").val(getCurrentTime());
    /*  /  Set The Time*/
    $('.starBlock').click(function() {
        $(this).toggleClass('starBlockSel');
    });
    $('.hotelBudgetOption').click(function() {
        $this = $(this);
        if (!$this.hasClass('hotelBudgetOptionSel')) {
            $('.hotelBudgetOption').removeClass('hotelBudgetOptionSel');
            $this.addClass('hotelBudgetOptionSel');
        }
    });
    /*$('.citySelect').click(function() {
        var cityName = $(this).text().toLowerCase();
        var link = ReturnLink('/hotel-search/' + cityName);
        window.open(link, '_blank');
    });*/
    $('.evalHotelsList ul li').mouseenter(function() {
        $(this).find('.hotelpopUp').show();
    });
    $('.evalHotelsList ul li').mouseleave(function() {
        $(this).find('.hotelpopUp').hide();
    });
    $('.searchButton').click(function() {
        var destination2 = $('#hotelTextInput_search').val();
        if (destination2 == '') {
            TTAlert({
                msg: t('You have to specify a destination'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        var destination = '';
        var checkinDate = $('#checkinDate').attr('data-value');
        if (typeof checkinDate !== 'undefined' && checkinDate !== '') {
            destination += '/d/' + checkinDate;
        }
        if ($("#restaurantTime").val() != '') {
            destination += '/t/' + $("#restaurantTime").val();
        }
        if ($("#nbOfPersons_sel").val() != '') {
            destination += '/persons/' + $("#nbOfPersons_sel").val();
        }
        if ($('#hotelTextInput_search').attr('data-city') != '')
            destination += '/C/' + $('#hotelTextInput_search').attr('data-city');
        if ($('#hotelTextInput_search').attr('data-state-code') != '')
            destination += '/ST/' + $('#hotelTextInput_search').attr('data-state-code');
        if ($('#hotelTextInput_search').attr('data-code') != '')
            destination += '/CO/' + $('#hotelTextInput_search').attr('data-code');
        var data_hotel = $('#hotelTextInput_search').attr('data-hotel');
        if (data_hotel != "" && parseInt(data_hotel) > 0) {
            var link = ReturnLink('/trestaurant/id/' + parseInt(data_hotel) + '/s/' + destination2 + destination);
        } else {
            if ($("#foodType_sel").val() != '') {
                destination += '/foodtype/' + $("#foodType_sel").val();
            }
            if ($(".hotelBudgetOption.hotelBudgetOptionSel").length > 0)
                destination += '/budget/' + $(".hotelBudgetOption.hotelBudgetOptionSel").attr("data-value");
            var link = ReturnLink('/restaurant-search/' + destination2 + destination);
        }
        window.open(link, '_blank');
    });

    // advancedSearchBtn onclick
    $(document).on('click', ".advancedSearchBtn", function() {
        $(this).hide();
        $(".basicSearchPart").addClass("advSearchPart");
        $(".basicSearchBtn").show();
        $(".advSearchFieldsCon").show();
    });
    //   / advancedSearchBtn onclick

    // basicSearchBtn onclick
    $(document).on('click', ".basicSearchBtn", function() {
        $(this).hide();
        $(".basicSearchPart").removeClass("advSearchPart");
        $(".advancedSearchBtn").show();
        $(".advSearchFieldsCon").hide();
    });
    //   / basicSearchBtn onclick
});

function InitCalendar(element) {
    Calendar.setup({
        inputField: element,
                noScroll  	 : true,
            fixed: true,
        trigger: element,
        align: "B",
        onSelect: function(calss) {
            var date = Calendar.intToDate(calss.selection.get());
            $('#' + element).attr('data-value', Calendar.printDate(date, "%Y-%m-%d"));
            this.hide();
        },
        dateFormat: "%d / %m / %Y",
        disabled: function(date) {
            var dats = new Date();
            var currentdate = new Date(dats.getFullYear(), dats.getMonth(), dats.getDate(), 0, 0, 0, 0);
            return (date < currentdate);
        }
    });
}