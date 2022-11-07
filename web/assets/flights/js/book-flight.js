$(document).ready(
    function () {
        $(".traveler_mobile").prop('required', true);
        $(".traveler_email").prop('required', true);


        $('.coupon-code').val('');
        $(function () {
            $.getJSON('https://freegeoip.net/json/', function (data) {
                $('#passengerNameRecord_countryOfResidence option').each(
                    function () {
                        if ($(this).attr('data-country-code') === data.country_code) {
                            $(this).prop("selected", true);
                            $('#passengerNameRecord_mobileCountryCode').find(
                                '[data-country-code="' + data.country_code + '"]').prop("selected", true);
                        }
                    });
            });

            var maxheight =
                Math.max($("#target1 .greyresume").outerHeight(), $("#target2 .greyresume").outerHeight());
            maxheight = Math.max(maxheight, $("#target3 .greyresume").outerHeight());

            $("#right").height(maxheight + 80);
            $(".multi-destination-container .greyresume").height(maxheight + 80);
            $(".slider").hide();
            $(".slider.active").show();
        });
$(".traveler_mobile").blur(
    function() {
        var passenger = $(this).data('passenger');
        if ($("#passengerNameRecord_passengerDetails_"+passenger+"_receiveAlerts").prop('checked')) {
            $('.passengerPhoneContainer_'+passenger+' .tt_validation_message').removeClass("hidden");

        }else{
            $('.passengerPhoneContainer_'+passenger+' .tt_validation_message').addClass("hidden");

        }
    });
        $(".receiveAlerts").click(
            function () {
                var passenger = $(this).data('passenger');
                if ($(this).prop('checked')) {
                    $("#receiveAlertsMessage_" + passenger).addClass("hidden");
                    $('.passengerPhoneContainer_'+passenger+' .tt_validation_message').removeClass("hidden");
                    $('.passengerEmailContainer_'+passenger+' .tt_validation_message').removeClass("hidden");

                    $("#passengerNameRecord_passengerDetails_" + passenger + "_phone").prop('required', true);
                    $("#passengerNameRecord_passengerDetails_" + passenger + "_email").prop('required', true);

                    $("#passengerNameRecord_passengerDetails_" + passenger + "_phone").addClass('required');
                    $("#passengerNameRecord_passengerDetails_" + passenger + "_email").addClass('required');

                } else {
                    $('.passengerPhoneContainer_'+passenger+' .tt_validation_message').addClass("hidden");
                    $('.passengerEmailContainer_'+passenger+' .tt_validation_message').addClass("hidden");
                    $("#receiveAlertsMessage_" + passenger).removeClass("hidden");
                    $("#passengerNameRecord_passengerDetails_" + passenger + "_phone").prop('required', false);
                    $("#passengerNameRecord_passengerDetails_" + passenger + "_email").prop('required', false);
                    $("#passengerNameRecord_passengerDetails_" + passenger + "_phone").removeClass('required');
                    $("#passengerNameRecord_passengerDetails_" + passenger + "_email").removeClass('required');
                }
            });
        $(".usePhoneEmail").click(
            function () {
                var passenger = $(this).data('passenger');
                if ($(this).prop('checked')) {
                    if ($("#passengerNameRecord_mobile").val()) {
                        $("#passengerNameRecord_passengerDetails_" + passenger + "_phone").val($("#passengerNameRecord_mobile").val());
                    }
                    if ($("#passengerNameRecord_email").val()) {
                        $("#passengerNameRecord_passengerDetails_" + passenger + "_email").val($("#passengerNameRecord_email").val());
                    }
                } else {
                    $("#passengerNameRecord_passengerDetails_" + passenger + "_phone").val("");
                    $("#passengerNameRecord_passengerDetails_" + passenger + "_email").val("");
                }

            });
        $("#passengerNameRecord_mobile").blur(function () {
            $('.traveler_mobile').each(function () {
                var passenger = $(this).data("passenger");

                if ($("#passengerNameRecord_mobile").val()) {
                    if ($('#passengerNameRecord_passengerDetails_' + passenger + '_usePhoneEmail').prop('checked')) {
                        $(this).val($("#passengerNameRecord_mobile").val());
                    }
                }
            });
        });
        $("#passengerNameRecord_email").blur(function () {
            $('.traveler_email').each(function () {
                var passenger = $(this).data("passenger");

                if ($("#passengerNameRecord_email").val()) {
                    if ($('#passengerNameRecord_passengerDetails_' + passenger + '_usePhoneEmail').prop('checked')) {
                        $(this).val($("#passengerNameRecord_email").val());
                    }
                }
            });
        });
        $('#passengerNameRecord_countryOfResidence').change(
            function () {
                var selectedOption = $(this).find('option:selected').attr('data-country-code');
                $('#passengerNameRecord_mobileCountryCode')
                    .find('[data-country-code="' + selectedOption + '"]').prop("selected", true);
            });

        $.sessionTimeout({
            message: Translator.trans('Your session is about to expire.'),
            keepAliveUrl: generateLangURL('/refresh-session'),
            keepAliveAjaxRequestType: 'POST',
            redirUrl: generateLangURL(isCorporate ? '/corporate/flight?timedOut=1' : '/flight-booking?timedOut=1',
                isCorporate ? 'corporate' : ''),
            logoutUrl: generateLangURL(isCorporate ? '/corporate/flight' : '/flight-booking', isCorporate
                ? 'corporate' : ''),
            warnAfter: 720000, // 12 mins
            redirAfter: 840000, // 14 mins,
            width: 555,
            access_token: $("input[name=access_token]").val(),
            returnedConversationId: $("input[name=returnedConversationId]").val()
        });

        $('#searchAgain').click(function () {
            // $.ajax({
            // type: 'POST',
            // url: '/close-session',
            // data: {access_token: "John", returnedConversationId: "Boston"}
            // });
            window.location = generateLangURL('/flight-booking');
        });
        /*
         * $("#bookAirTicket").submit(function () { // showFlightOverlay(".right_panel"); showFlightOverlay(); });
         */
        $('a.tabslide').click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')), $other = $target.siblings('.active');
            $('a.tabslide').removeClass('active');
            $(this).addClass('active');
            if (!$target.hasClass('active')) {
                $other.each(function (index, self) {
                    var $this = $(this);
                    $this.removeClass('active').animate({
                        left: $this.width()
                    }, 500);
                });

                $target.addClass('active').show().css({
                    left: -($target.width())
                }).animate({
                    left: 0
                }, 500);
            }
        });
        if ($('a.tabslide').length > 0) {
            $('a.tabslide').first().click();
        }

        $("#passengerNameRecord_passengerDetails_0_firstName").blur(function () {
            if ($(this).validator('isValid') && $("#passengerNameRecord_firstName").val() === '') {
                $("#passengerNameRecord_firstName").val($(this).val());
            }
        });

        $("#passengerNameRecord_passengerDetails_0_surname").blur(function () {
            if ($(this).validator('isValid') && $("#passengerNameRecord_surname").val() === '') {
                $("#passengerNameRecord_surname").val($(this).val());
            }
        });

        var validated = true;
        var frmValidator = null;
        frmValidator = new TTFormValidator("#bookAirTicket");

        $("#bookAirTicket").find('.name').each(
            function (index) {
                var myID = "#" + $(this).attr('id');
                frmValidator.addCustomRule(myID, "traveler_name", Translator
                    .trans("Names should contain only characters"), function (value) {
                    validated = validate_alpha(value);
                    return validated;
                });
            });

        function validate_dateofbirth(containerSelector) {
            var validated = true;
            containerSelector.find("select").each(function (index) {
                if ($(this).val() == "") {
                    validated = false;
                }
            });
            return validated;
        }

        $("#bookAirTicket").find('.traveler_date select').each(
            function (index) {

                var myID = "#" + $(this).attr('id');
                frmValidator.addCustomRule(myID, "traveler_date_of_birth", Translator
                    .trans("Invalid traveller date"), function (value) {
                    var validated = (value != "");
                    // var validated = validate_dateofbirth($("#bookAirTicket").find('.traveler_date'));
                    return validated;
                });

            });

        $("#bookAirTicket").find('.phone_nbr').each(
            function (index) {
                var myID = "#" + $(this).attr('id');
                frmValidator.addCustomRule(myID, "phone_number", Translator
                    .trans("Invalid phone number (Only digits are allowed)"), function (value) {
                    var reg = /[0-9]/;
                    return value.match(reg) && (value.length >= 6) && (value.length <= 15);
                });
            });

        if ($("#bookAirTicket").find('.traveler_id').length > 0) {
            $("#bookAirTicket").find('.traveler_id').each(
                function (index) {
                    var myID = "#" + $(this).attr('id');
                    frmValidator.addCustomRule(myID, "traveler_id_" + index, Translator
                        .trans("Invalid traveller ID"), function (value) {
                        validated = validate_alphanumeric(value);
                        return validated;
                    });
                });
            $("#bookAirTicket").find('.traveler_passport_no').each(
                function (index) {
                    var myID = "#" + $(this).attr('id');
                    frmValidator.addCustomRule(myID, "traveler_passport_no_" + index, Translator
                        .trans("Invalid passport number"), function (value) {
                        validated = validate_alphanumeric(value);
                        return validated;
                    });
                });
        }

        $("#bookAirTicket").submit(
            function (e) {

                var isValid = frmValidator.validate();

                console.log(isValid);
                if (!isValid)
                    return false;
                showFlightOverlay();
                var yourPassword = $("#YourPassword").val();
                if (typeof yourPassword !== "undefined" && yourPassword !== '' && yourPassword.length >= 8
                    && alphanumeric(yourPassword)) {

                    $.ajax({
                        url: generateLangURL('/users/register/addSubmit', 'empty'),
                        data: {
                            yourEmail: $("#passengerNameRecord_email").val(),
                            yourUserName: $("#passengerNameRecord_email").val(),
                            yourPassword: yourPassword,
                            password: yourPassword,
                            userId: 0
                        },
                        type: 'post',
                        success: function (data) {
                            var jres = null;
                            try {
                                jres = data;
                                var status = jres.status;
                            } catch (Ex) {
                            }
                        }
                    });
                }
            });

        $("#couponCode").blur(
            function () {
                if ($(this).val() != '') {
                    $.ajax({
                        url: generateLangURL('/ajax/verify-coupon'),
                        data: {
                            coupon_code: $(this).val(),
                            target_entity_type_id: 76,
                            display_amount: $("#displayAmount").attr("data-price"),
                            display_currency: $("#displayCurrency").text()
                        },
                        type: 'post',
                        success: function (data) {
                            // console.log(data);
                            if (data.status) {
                                $(".coupon-codeverification").removeClass("wrong");
                                $(".coupon-codeverification").addClass("right");

                                if (data.discounted) {
                                    $("#discountedAmount").attr('data-price',
                                        data.discount_details.discount_value);
                                    $("#discountedAmount .price-convert-text").html(
                                        data.discount_details.discount_value).number(true, 2);
                                    // console.log(data.discount_details);
                                    // alert(data.discount_details.amount);
                                    $("#finalPrice").attr('data-price', data.amount);
                                    $("#finalPrice .price-convert-text").html(data.amount).number(true, 2);
                                    $("#discountedAmount").show();
                                    $("#finalPrice").show();
                                }
                            } else {
                                $(".coupon-codeverification").removeClass("right");
                                $(".coupon-codeverification").addClass("wrong");
                            }
                        }
                    });
                }
            });

        var d = new Date(),
            n = d.getMonth();

        $('.dobrow').each(function () {
            var child2 = $(this).children('select:nth-child(2)');
            $(this).children('select:first-child').change(function () {

                if ($(this).children('option:nth-child(2)').is(':selected')) {
                    //display restricted months
                    restrictMonths(n, child2.attr('id'));
                } else {
                    //display full months
                    restrictMonths(0, child2.attr('id'));
                }
            })
        });
    });

function restrictMonths(n, el) {

    var monthArray = new Array();
    monthArray[0] = Translator.trans('Jan');
    monthArray[1] = Translator.trans('Feb');
    monthArray[2] = Translator.trans('Mar');
    monthArray[3] = Translator.trans('Apr');
    monthArray[4] = Translator.trans('May');
    monthArray[5] = Translator.trans('Jun');
    monthArray[6] = Translator.trans('Jul');
    monthArray[7] = Translator.trans('Aug');
    monthArray[8] = Translator.trans('Sep');
    monthArray[9] = Translator.trans('Oct');
    monthArray[10] = Translator.trans('Nov');
    monthArray[11] = Translator.trans('Dec');

    document.getElementById(el).innerHTML = '';

    var optn = document.createElement("OPTION");
    optn.text = Translator.trans('month');
    document.getElementById(el).options.add(optn);

    for (m = n; m < monthArray.length; m++) {
        var optn = document.createElement("OPTION");
        optn.text = monthArray[m];
        // server side month start from one
        optn.value = (m + 1);
        document.getElementById(el).options.add(optn);
    }
}
