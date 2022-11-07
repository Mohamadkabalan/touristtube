$(document).ready(function () {
    $(document).on('click', '#destination_select_step_next_button', function () {
        $("#destination_city_select_step").show();
        $("#destination_select_step").hide();
    });
    $(document).on('click', '#destination_city_select_prev_button', function () {
        $("#destination_city_select_step").hide();
        $("#destination_select_step").show();
    });
    $(document).on('click', '#destination_city_select_step_next_button', function () {
        $("#transfer_type_step").show();
        $("#destination_city_select_step").hide();
    });
    $(document).on('click', '#transfer_type_step_prev_button', function () {
        $("#transfer_type_step").hide();
        $("#destination_city_select_step").show();
    });
    $(document).on('click', '#transfer_type_step_next_button', function () {
        $("#transfer_type_step").hide();
        $("#airport_select_step").show();
    });
    $(document).on('click', '#airport_select_step_prev_button', function () {
        $("#transfer_type_step").show();
        $("#airport_select_step").hide();
    });
    $(document).on('click', '#airport_select_step_next_button', function () {
        $("#hotel_detination_step").show();
        $("#airport_select_step").hide();
    });
    $(document).on('click', '#hotel_detination_step_prev_button', function () {
        $("#hotel_detination_step").hide();
        $("#airport_select_step").show();
    });
    $(document).on('click', '#hotel_detination_step_next_button', function () {
        $("#hotel_detination_step").hide();
        $("#schedule_select_step").show();
    });
    $(document).on('click', '#schedule_select_step_prev_button', function () {
        $("#hotel_detination_step").show();
        $("#schedule_select_step").hide();
    });
    $(document).on('click', '#schedule_select_step_next_button', function () {
        $("#schedule_select_step").hide();
        $("#passengers_numbers_select_step").show();
    });
    $(document).on('click', '#passengers_numbers_select_step_prev_button', function () {
        $("#schedule_select_step").show();
        $("#passengers_numbers_select_step").hide();
    });
    $(document).on('click', '#passengers_numbers_select_step_next_button', function () {
        $("#passengers_numbers_select_step").hide();
        $("#transport_type_step").show();
    });
    $(document).on('click', '#transport_type_step_prev_button', function () {
        $("#passengers_numbers_select_step").show();
        $("#transport_type_step").hide();
    });
    $(document).on('click', '#transport_type_step_next_button', function () {
        $("#transport_type_step").hide();
        $("#secure_booking_step").show();
    });
    $(document).on('click', '#secure_booking_step_prev_button', function () {
        $("#secure_booking_step").hide();
        $("#transport_type_step").show();
    });
});