$(document).ready(function () {

    $(document).on('click', '.update_search_button', function () {
        $(".update_search_container").slideToggle('fast');
        $(".flight_search_input_container").slideToggle('fast');
    });

    $(document).on('click', '.close_container', function () {
        $(".flight_search_input_container").slideToggle('fast');
        $(".update_search_container").slideToggle('fast');
    });

    $(document).on('click', '.flight_details_clicker', function () {
        $(this).closest('.flight_row').find('.flight_details_container').slideToggle('fast');
        $(this).find('i').toggleClass('fa-angle-double-up');
    });

    $('#passengerNameRecord_save').click(function () {
//        showFlightOverlay(".flight_review_trip_container");
        showFlightOverlay();
        setTimeout(function () {
            $("#bookAirTicket").submit();
        }, 500);
    });

    $('.nopad > .search_hpage').click(function () {
//        showFlightOverlay(".flight_search_input_container");
        showFlightOverlay();
        setTimeout(function () {
            $("#airplaneform").submit();
        }, 500);
    });

});