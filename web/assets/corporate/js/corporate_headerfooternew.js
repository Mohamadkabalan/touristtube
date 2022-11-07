var fadeInFlag = 0;
var fadeOutTimeout1;
var fadeOutTimeout2;
var fadeOutTimeout3;
var fadeOutTimeout4;

$(document).ready(function () {

    $(".change_theme_but").on("click", function () {
	$('.change_theme_but').removeClass('active');
	$(this).addClass('active');
	var d = new Date(new Date().setFullYear(new Date().getFullYear() + 5));
	$.cookie("corporate_theme", $(this).attr('data-theme'), {path: '/', expires: d});
	window.location.reload();
    });

    $(".change_theme_but_back").on("click", function () {
	$('.change_theme_but_back').removeClass('active');
	$(this).addClass('active');
	var d = new Date(new Date().setFullYear(new Date().getFullYear() + 5));
	$.cookie("corporate_background_theme", $(this).attr('data-theme'), {path: '/', expires: d});
	window.location.reload();
    });

    initFancyButtons();

    //Currency converter
    $('body').click(function () {
	$('.blueairptab').hide();
	$(".imagarrowblue").hide();
    });

    $(".currencyselectbox").click(function (event) {
	event.stopPropagation();
	$(".blueairptab").toggle();
	$(".imagarrowblue").toggle();
    });

    $("#currencyTab2").click(function (event) {
	event.stopPropagation();
	$(".blueairptab").toggle();
	$(".imagarrowblue").toggle();
    });

    $(".blueairptab").click(function (event) {
	event.stopPropagation();
    });

    $(".currency-item").on("click", function () {
	var fromCurrency = $('.currency-item.selected').attr('currency-code-data');
	var toCurrency = $(this).attr('currency-code-data');

	if ($(".price-convert").length > 0) {
	    $('.upload-overlay-loading-fix').show();
	    changeSiteCurrency(fromCurrency, toCurrency, '.price-convert');
	}

	$('.curtext_hp .currency_val').html('<span>' + $(this).attr('currency-symbol-data') + '</span> ' + $(this).attr('currency-code-data'));
	$('#currencyTab .blacklbpbout a').html($(this).attr('currency-code-data'));
	$('#currencyTab .blacklbpbout a').attr('title', $(this).attr('currency-code-data'));

	$(".currency-item").removeClass('selected');
	$('.currency-item[currency-code-data="' + $(this).attr('currency-code-data') + '"]').addClass('selected');

	$('.curnewtab').hide();
	$('.blueairptab').hide();
	$(".imagarrowblue").hide();

	$.cookie.raw = true;
	$.cookie('currency', toCurrency);
    });
});

function changeSiteCurrency(fromCurrency, toCurrency, targetNode, callback) {
    $.ajax({
	url: generateLangURL('/api/convert/currency'),
	data: {from: fromCurrency, to: toCurrency},
	type: 'post',
	dataType: "json",
	cache: false,
	success: function (response) {
	    if (response.status == "200") {
		var conversion_rate = response.conversion_rate;
		$(targetNode).each(function (index) {
		    var mydataprice = $(this).attr('data-price');
		    if (mydataprice > 0) {
			var newmydataprice = mydataprice / conversion_rate;
			$(this).attr('data-price', newmydataprice);
			if ($(this).children('.price-convert-text').length > 0) {
			    if ($('.round-down-price').length > 0) {
				$(this).children('.price-convert-text').html(Math.floor(newmydataprice)).number(true);
			    } else {
				$(this).children('.price-convert-text').html(newmydataprice).number(true, 2);
			    }
			} else if ($(this).children('.price-convert-val').length > 0) {
			    $(this).children('.price-convert-val').val(newmydataprice);
			}
		    }
		    $(this).children('.currency-convert-text').html(toCurrency);
		    $(this).children('.currency-convert-val').val(toCurrency);
		});
		if ($('#price-range').length > 0) {
		    var minPrice = $('#price-range').attr('data-min-price');
		    var maxPrice = $('#price-range').attr('data-max-price');
		    var min = parseInt(Math.floor(minPrice / conversion_rate));
		    var max = parseInt(Math.ceil(maxPrice / conversion_rate));
		    try {
			fixFilterAfterCurrencyChange(min, max);
		    } catch (err) {
			console.log(err);
		    }
		}
		if ($('#ex8').length > 0) {
		    var minPrice = $('#ex8').attr('data-slider-min');
		    var maxPrice = $('#ex8').attr('data-slider-max');
		    var dataValue = $('#ex8').attr('data-value').split(',');
		    var dvMinPrice = dataValue[0];
		    var dvMaxPrice = dataValue[1];

		    var min = parseInt(Math.floor(minPrice / conversion_rate));
		    var max = parseInt(Math.ceil(maxPrice / conversion_rate));
		    var dvMin = parseInt(Math.floor(dvMinPrice / conversion_rate));
		    var dvMax = parseInt(Math.ceil(dvMaxPrice / conversion_rate));

		    try {
			fixFilterAfterCurrencyChange(min, max, dvMin, dvMax);
		    } catch (err) {
			console.log(err);
		    }
		}
		if (callback !== undefined) {
		    callback();
		}
	    }
	    $('.upload-overlay-loading-fix').hide();
	}
    });
}

function goToLinkNew(link) {
    window.location.href = link;
}

function initFancyButtons() {
    $('.buttonsAddTripFancy').each(function () {
	var $this = $(this);
	$this.removeClass('buttonsAddTripFancy');
	$this.fancybox({
	    padding: 0,
	    margin: 0,
	    beforeLoad: function () {
		//$('#popupAddSubAccount').html('');
//		$.post(ReturnLink('/ajax/add_to_bag'), {id: mid, type: mtype}, function (data) {
//		    var ret = null;
//		    try {
//			ret = $.parseJSON(data);
//		    } catch (Ex) {
//			return;
//		    }
//		    if (ret.status == 'error') {
//			TTAlert({
//			    msg: ret.error,
//			    type: 'alert',
//			    btn1: t('ok'),
//			    btn2: '',
//			    btn2Callback: null
//			});
//			$('.fancybox-close').click();
//		    } else {
//			$('#popup_creatid').html(ret.data);
//		    }
//		});
	    }
	});
    });
}