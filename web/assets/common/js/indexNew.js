
$(document).ready(function () {
    var marquee = $('div.marquee');
    var opt = {
	speed: 50,
	behavior: 'circle',
	mouseover: function (o) {
	    o.stop();
	    o.css({cursor: 'pointer'});
	},
	mouseout: function (o) {
	    o.start();
	}
    };
    marquee.each(function () {
	var mar = $(this), indent = mar.width();
	mar.marquee = function () {
	    indent--;
	    mar.css('text-indent', indent);
	    if (indent < -1 * mar.children('div.marquee-text').width()) {
		indent = mar.width();
	    }
	};
	mar.data('interval', setInterval(mar.marquee, 1000 / 60));
    });
    if ($('#TrendsDesc').length > 0)
	$('#TrendsDesc').mscroller(opt);

    $('.boutton-1').on('click', function (e) {
	$('.flightcontent').hide();
	$('.dealcontent').hide();
	$('.boutton-2').removeClass('yellow-back');
	$('.boutton-3').removeClass('yellow-back');
	$('.boutton-1').addClass('yellow-back');
	$('.hotelscontent').show();
    });
    $('.boutton-2').on('click', function (e) {
	$('.hotelscontent').hide();
	$('.dealcontent').hide();
	$('.boutton-1').removeClass('yellow-back');
	$('.boutton-3').removeClass('yellow-back');
	$('.boutton-2').addClass('yellow-back');
	$('.flightcontent').show();
    });
    $('.boutton-3').on('click', function (e) {
	$('.hotelscontent').hide();
	$('.flightcontent').hide();
	$('.boutton-1').removeClass('yellow-back');
	$('.boutton-2').removeClass('yellow-back');
	$('.boutton-3').addClass('yellow-back');
	$('.dealcontent').show();
    });
    if ($('.bouttonSearchTabs').length == 1) {
	$('.bouttonSearchTabs').click();
    } else {
	$('.boutton-1').click();
    }

    $('#mandatoryCancel').on('click', function (e) {
	var returnPath = $("#returnPath").val();
	window.location.href = returnPath;
    });

    $('#mandatoryContinue').on('click', function (e) {
	$('.wrong_credentials').html();
	$('.wrong_credentials').hide();

	var fail = false;
	$("input[name*='mandatory_']").each(function () {
	    var fieldVal = $(this).val().trim();
	    if (fieldVal.length == 0) {
		fail = true
	    }
	});
	if (fail) {
	    if ($("input[name*='mandatory_']").length > 1) {
		$('.wrong_credentials').html(Translator.trans('Mandatory fields are required.'));
	    } else {
		$('.wrong_credentials').html(Translator.trans('Mandatory field is required.'));
	    }
	    $('.wrong_credentials').show();
	} else {
	    $(this).prop('type', 'submit');
	}
    });

    var today = new Date();
    if ($(".datesDealsContainer").length) {
	$(".datesDealsContainer").each(function (k, v) {
	    $(this).dateRangePicker({
		autoClose: true,
		showTopbar: false,
		startDate: today,
		singleDate: true,
		singleMonth: true,
		setValue: function (s, s1) {
		    $(this).find('input').val(s1);
		}
	    });
	});
    }
});
