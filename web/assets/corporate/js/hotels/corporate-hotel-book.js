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
    $(".payment-list li a").click(function () {
	$(".payment-list li a").removeClass('active');
	$(".image-div").removeClass('active');
	$(this).addClass('active');
	$(this).next('div.image-div').addClass('active');
	var $id = $(this).attr('id');
	var $sub = $('#sub_' + $id);
	$('.contenttabs').hide();
	$sub.show();
    });

    $(".backbut").click(function () {
	$("#section2").hide();
	$("#section1").show();
	$(".sndstep").addClass('blue_bullet');
	$(".sndstep").removeClass('green_bullet');
	$(".sndstep").removeClass('hidden-xs');

	$(".thirdstep").removeClass('blue_bullet');
	$(".thirdstep").addClass('hidden-xs');
    });

    var mySwiper = $('.swiper-container').swiper({
	slidesPerView: 'auto',
	centeredSlides: false,
	spaceBetween: 10
    });

    countryDialingCodeMap = $.parseJSON(countryDialingCodeMap);
    $(document).on('change', '#country', function () {
	$("input[name='mobileCountryCode']").val(countryDialingCodeMap[$("#country").val()]);
	$("#mobileCountryCodeSelect").val($("#country").val());
    });
});
function loadCCForm() {
    $('#btn_continue').click(function (e) {
	e.preventDefault();
	if ($(".formControlYourPassword").hasClass('error-label')) {
	    $(".formControlYourPassword").focus();
	    return false;
	}
	if ($("#form_hotel_book").valid()) {
	    // the form is valid, do something
	    $("#section1").hide();
	    $("#section2").show();
	    $(".sndstep").removeClass('blue_bullet');
	    $(".sndstep").addClass('green_bullet');
	    $(".sndstep").addClass('hidden-xs');

	    $(".thirdstep").addClass('blue_bullet');
	    $(".thirdstep").removeClass('hidden-xs');

	    yourPassword = $(".formControlYourPassword").val();
	    if (typeof yourPassword !== "undefined" && yourPassword != '' && yourPassword.length >= 8 && alphanumeric(yourPassword)) {
		$.ajax({
		    url: ReturnLink('/ajax/ajax_register.php'),
		    data: {fname: $(".contactcontainer #email").val(), lname: '', YourEmail: $(".contactcontainer #email").val(), YourUserName: '', YourPassword: $(".contactcontainer .formControlYourPassword").val(), YourCPassword: $(".contactcontainer .formControlYourPassword").val(), YourBday: '0000-00-00', gender: 'O', register_type: 'popup'},
		    type: 'post',
		    success: function (data) {
			var jres = null;
			try {
			    jres = $.parseJSON(data);
			} catch (Ex) {
			    return;
			}
		    }
		});
	    }
	}
    });
}
