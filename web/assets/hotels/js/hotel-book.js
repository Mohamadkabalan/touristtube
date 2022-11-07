$(document).ready(function () {
    $('.formControlYourPassword').val('');
    loadCCForm();

    $(".formControlYourPassword").blur(function () {
	if ($(".formControlYourPassword").val() != '' && (($(".formControlYourPassword").val()).length < 8 || !alphanumeric($(".formControlYourPassword").val()))) {
	    $(".formControlYourPassword").addClass('error-label');
	    $(".formControlYourPassword").focus();
	    return false;
	}

	$(".formControlYourPassword").removeClass('error-label');
    });

    $(".backbut").click(function () {
	$("#section2").hide();
	$("#section1").show();
    });

    countryDialingCodeMap = $.parseJSON(countryDialingCodeMap);
    $(document).on('change', '#country', function () {
	$("input[name='mobileCountryCode']").val(countryDialingCodeMap[$("#country").val()]);
	$("#mobileCountryCodeSelect").val($("#country").val());
    });

    $(document).on('submit', '#form_hotel_book', function () {
	showHotelOverlay('', 'booking');
    });
    $(".bookcond").click(function () {
	var $this = $(this);
	var $parent = $this.closest('.bookcond_container');
	var $fas = $this.find('.fas');
        $parent.find(".full_background").slideToggle('fast', function () {
	    if ($fas.hasClass('fa-angle-up')) {
		$fas.removeClass('fa-angle-up');
	    } else {
		$fas.addClass('fa-angle-up');
	    }
        });
    });
});

function loadCCForm() {
    $('#btn_continue').click(function (e) {
	e.preventDefault();
	if ($(".formControlYourPassword").hasClass('error-label')) {
	    $(".formControlYourPassword").focus();
	    return false;
	}

	var isValid = frmValidator.validate();
	if (isValid) {
	    $("#section1").hide();
	    $("#section2").show();

	    var yourPassword = $(".formControlYourPassword").val();
	    if (typeof yourPassword !== "undefined" && yourPassword != '' && yourPassword.length >= 8 && alphanumeric(yourPassword)) {
		$.ajax({
		    url: generateLangURL('/users/register/addSubmit', 'empty'),
		    data: {
			yourEmail: $(".contactcontainer #email").val(),
			yourUserName: $(".contactcontainer #email").val(),
			yourPassword: $(".formControlYourPassword").val(),
			password: $(".formControlYourPassword").val(),
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
	}
    });
}
