function setGuests() {
    var $var = '';
    if ($("#guests_sel").val() === '') {
        $var = $("#guests_sel_rooms").val() + '-' + $("#guests_sel_adults").val() + '-' + $("#guests_sel_children").val();
    } else {
        $var = $("#guests_sel").val();
    }
    $("#guests").val($var);
}
function getStar() {
    var star = '';
    $(".starBlockSel").each(function() {
        star += $(this).attr("data-value") + ',';
    });
    star = star.substring(0, star.length - 1);
    return star;
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
    InitCalendar('checkoutDate');
    autocompleteHotels($('#hotelTextInput_search'));
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
        var checkoutDate = $('#checkoutDate').attr('data-value');
        var data_hotel = $('#hotelTextInput_search').attr('data-hotel');
        var dates = '';
        if (typeof checkinDate !== 'undefined' && checkinDate !== '') {
            dates += '/from/' + checkinDate;
            if (typeof checkoutDate !== 'undefined' && checkoutDate !== '') {
                dates += '/to/' + checkoutDate;
            }
        }
        if ($("#guests").val() != '')
            destination += '/room/' + $("#guests").val();
        if ($('#hotelTextInput_search').attr('data-city') != '')
            destination += '/C/' + $('#hotelTextInput_search').attr('data-city');
        if ($('#hotelTextInput_search').attr('data-state-code') != '')
            destination += '/ST/' + $('#hotelTextInput_search').attr('data-state-code');
        if ($('#hotelTextInput_search').attr('data-code') != '')
            destination += '/CO/' + $('#hotelTextInput_search').attr('data-code');

        if (data_hotel != "" && parseInt(data_hotel) > 0) {
            var link = ReturnLink('/thotel/id/' + parseInt(data_hotel)+ '/s/' + destination2 + destination+dates);
        } else {
            if (getStar() != '')
                destination += '/star/' + getStar();
            if ($(".hotelBudgetOption.hotelBudgetOptionSel").length > 0)
                destination += '/budget/' + $(".hotelBudgetOption.hotelBudgetOptionSel").attr("data-value");
            var link = ReturnLink('/hotel-search/' + destination2 + destination + dates);
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

    // guests_sel on change
    $(document).on('change', "#guests_sel", function() {
        if ($(this).val() == '') {
            $(".guestSelectGroup").show();
        } else {
            $(".guestSelectGroup").hide();
        }
        setGuests();
    });
    //   / guests_sel on change
    //
    // guests_sel_rooms,guests_sel_adults,guests_sel_children on change
    $(document).on('change', "#guests_sel_rooms,#guests_sel_adults,#guests_sel_children", function() {
        setGuests();
    });
    $('#guests_sel').change();
    //   / guests_sel_rooms,guests_sel_adults,guests_sel_children on change
});

function InitCalendar(element) {
    Calendar.setup({
        inputField: element,
                noScroll  	 : true,
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