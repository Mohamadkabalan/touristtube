var signflag = 0;
var profileFlag = 0;
var staticsearchflag = 0;
var profileFadeoutTimeout;
var popupOffersTimeout;
var staticsearchTimeout;
var selectedItemBag;
var channel = 0;
var tOut;
var ww = $(window).width();
var limit = 767;
var minSearchLength = getMinSearchLength(LANG_CODE);
$(document).ready(function () {
    InitCarouselOther();
    $('.searchlocation').attr('data-id', 0);
    $('.searchlocation').attr('data-city', '');
    $('.searchlocation').attr('data-state', '');
    $('.searchlocation').attr('data-contryC', '');
    $('.searchlocation').attr('data-type', 0);
    $('.searchlocation').val('');
    
    $(document).on('click', ".search-btnClick", function () {
	if ($('.input-search').hasClass('searchforchannel')) {
	    var data_contryc = $('.searchlocationchannel').attr('data-contryc');
	    var data_state = $('.searchlocationchannel').attr('data-state');
	    var data_id = $('.searchlocationchannel').attr('data-id');
	    var data_val = $('.searchforchannel').val();
	    var newlnk = '';
	    if (parseInt(data_id) > 0) {
		if (newlnk != '')
		    newlnk += '_';
		newlnk += "CI_" + data_id;
	    } else if (data_contryc != '' && data_state != '') {
		if (newlnk != '')
		    newlnk += '_';
		newlnk += "S_" + data_state + "_" + data_contryc;
	    } else if (data_contryc != '') {
		if (newlnk != '')
		    newlnk += '_';
		newlnk += "CO_" + data_contryc;
	    }
	    if (newlnk != '' || data_val != '') {
		document.location.href = generateLangURL('/channels-search-' + data_val + '-' + newlnk, 'channels');
	    }
	}
    });
    // for trens scroller
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
    initAddToBag();
    $(document).on('click', ".addtobaginactive0", function () {
		if (parseInt($('.regiser_login_links').attr('data-logged')) == 0) {
			showNeedTTAccountMessage();
		    return;
		}
    });
    $(document).on('click', ".bagdisaable", function (e) {
		e.preventDefault();
		showNeedTTAccountMessage();
    });

    $(document).on('click', ".addtonewbagnameBT", function () {
	var id = $(this).attr('data-id');
	var dtype = $(this).attr('data-type');
	var bagname = $('.addtonewbagname').val();
	if (bagname == '') {
	    TTAlert({
		msg: Translator.trans('invalid bag name'),
		type: 'alert',
		btn1: Translator.trans('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    $('.fancybox-close').click();
	    $.fancybox.close();
	    return;
	}
	
	$.ajax({
	    url: generateLangURL('/ajax/add_item_to_bag'),
	    data: {
		id: id,
		name: bagname, 
		type: dtype
	    },
	    type: 'post',
	    success: function (data) {
		var jres = null;
		try {
		    jres = data;
		    var status = jres.status;
		} catch (Ex) {
		}
		if (!jres) {
		    TTAlert({
			msg: jres.msg,
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		    return;
		}	    
		if (jres.status == 'error') {
		    TTAlert({
			msg: jres.msg,
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		}
	    }
	});
	
	$('.fancybox-close').click();
	$.fancybox.close();
    });
    $(document).on('click', ".addtooldbagnameBT", function () {
	var id = $(this).attr('data-id');
	var bid = $(this).attr('data-bid');
	var dtype = $(this).attr('data-type');
	
	$.ajax({
	    url: generateLangURL('/ajax/add_item_to_old_bag'),
	    data: {
		id: id, 
		bid: bid, 
		type: dtype
	    },
	    type: 'post',
	    success: function (data) {
		var jres = null;
		try {
		    jres = data;
		    var status = jres.status;
		} catch (Ex) {
		}
		if (!jres) {
		    TTAlert({
			msg: jres.msg,
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		    return;
		}	    
		if (jres.status == 'error') {
		    TTAlert({
			msg: jres.msg,
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		}
	    }
	});
	
	$('.fancybox-close').click();
	$.fancybox.close();
    });
    if ($('#TrendsDesc').length > 0)
	$('#TrendsDesc').mscroller(opt);

    // for language functionality
    $(document).on('change', '#languageSelect', function () {
	var optionvalue = $(this).val();
	window.location.href = optionvalue;
    });

    // for menu
    $('.menu-toggle').click(function () {
	$("#mobile_tabsmain").slideToggle(500);
    });
    $('body').click(function () {
	if (staticsearchflag == 1) {
	    clearTimeout(staticsearchTimeout);
	    staticsearchTimeout = setTimeout(function () {
		$(".search-staticup").hide();
		staticsearchflag = 0;
	    }, 700);
	}
    });
    $('.opensubout').click(function (event) {
	event.stopImmediatePropagation();
	event.preventDefault();
	if (profileFlag == 0) {
	    $('#TopProfileDiv').fadeIn();
	    $('#TopProfileDiv').unbind('mouseenter mouseleave').hover(function () {
		clearTimeout(profileFadeoutTimeout);
		$('#TopProfileDiv').stop(true, true);
		$('#TopProfileDiv').fadeIn();
		profileFlag = 1;
	    }, function () {
		profileFadeoutTimeout = setTimeout(function () {
		    $('#TopProfileDiv').fadeOut();
		    profileFlag = 0;
		}, 500);
	    });
	    profileFlag = 1;
	    profileFadeoutTimeout = setTimeout(function () {
		$('#TopProfileDiv').fadeOut();
		profileFlag = 0;
	    }, 1500);
	} else {
	    $('#TopProfileDiv').fadeOut();
	    profileFlag = 0;
	}
    });
    var moreshown = false;
    $(".showChannels").click(function () {
	if (!moreshown) {
	    moreshown = true;
	    $("#OtherChannelsPop").show();
	    InitCarouselOther();
	} else {
	    moreshown = false;
	    $("#OtherChannelsPop").hide();
	}
    });
    $(".search-static-items").click(function () {
	setTimeout(function () {
	    clearTimeout(staticsearchTimeout);
	}, 500);
	clearTimeout(staticsearchTimeout);
	staticsearchflag = 1;
    });
    $(".input-search.searchfor").click(function () {
	setTimeout(function () {
	    clearTimeout(staticsearchTimeout);
	}, 500);
	clearTimeout(staticsearchTimeout);
	staticsearchflag = 1;
    });
    $(".input-search.searchfor").focus(function () {
	setTimeout(function () {
	    clearTimeout(staticsearchTimeout);
	}, 500);
	clearTimeout(staticsearchTimeout);
	staticsearchflag = 1;
	if ($(this).val() == "" && $(".input-search.searchlocation").val() != "" && ($(".input-search.searchlocation").attr('data-id') != 0 || $(".input-search.searchlocation").attr('data-contryc') != '')) {
	    $(".search-staticup").show();
	    var text = $('.searchlocation').attr('data-city');
	    $('.itemstextcontent2').text(text);
	} else {
	    $(".search-staticup").hide();
	}
    });
    $(".input-search.searchfor").blur(function () {
	//$(".search-staticup").hide();
    });
    $(".input-search.searchlocation").focus(function () {
	//$(".search-staticup").hide();
    });
    $(".input-search.searchfor").keyup(function () {
	if ($(this).val() == "" && $(".input-search.searchlocation").val() != "" && ($(".input-search.searchlocation").attr('data-id') != 0 || $(".input-search.searchlocation").attr('data-contryc') != '')) {
	    $(".search-staticup").show();
	    var text = $('.searchlocation').attr('data-city');
	    $('.itemstextcontent2').text(text);
	} else {
	    $(".search-staticup").hide();
	}
    });


    $("#searchfor").focus(function () {
	if ($(this).val() == "" && $(".searchlocation").val() != "" && $(".searchlocation").attr('data-id') != "") {
	    $(".search-static2").show();
	    var text2 = $(".searchlocation").attr('data-city');
	    $('.itemstextcontent3').html(text2);
	} else {
	    //$(".search-static2").hide();
	}
    });
    $("#searchfor").blur(function () {
	//$(".search-static2").hide();
    });
    $(".searchlocation").focus(function () {
	//$(".search-static2").hide();
    });
    $("#searchfor").keyup(function () {
	if ($(this).val() == "" && $(".searchlocation").val() != "" && $(".searchlocation").attr('data-id') != "") {
	    $(".search-static2").show();
	    var text2 = $(".searchlocation").attr('data-city');
	    $('.itemstextcontent3').html(text2);
	} else {
	    //$(".search-static2").hide();
	}
    });

    $(document).on('click', ".TTCookie_close, .TTCookie_buts", function () {
	var $parent = $(this).closest('#TTCookieContainer').parent();
	var d = new Date("2050-01-01");
	$.cookie('use_cookie', 'true',{path: '/',expires:d});
	$parent.remove();
    });
    $(document).on('click', ".i-search-crose", function () {
	var $parent = $(this).closest('.bag-search');
	$parent.find('.input-search').val('');
	$parent.find('.input-search').attr('data-type', 0);
	$parent.find('.input-search').attr('data-id', 0);
	$parent.find('.input-search').attr('data-contryc', '');
	$parent.find('.input-search').attr('data-state', '');
	$parent.find('.input-search').attr('data-city', '');
    });

    initShowOnMap();
    $(window).resize(function () {
	initShowOnMap();
	if ($('.poplar-desti_row').length > 0) {
	    var resW = $(window).width();
	    clearTimeout(tOut);
	    if ((ww > limit && resW < limit) || (ww < limit && resW > limit)) {
		tOut = setTimeout(refreshThePage, 100);
	    }
	}
    });

    $('body').click(function () {
	$('.blueairptab').hide();
	$(".imagarrowblue").hide();
    });
    $("#currencyTab").click(function (event) {
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
    	updateCurrency(null, null, this);
    });
});

function updateCurrency(fromCurrency, toCurrency, elt) {
	
	var fromCurrency = fromCurrency || $('.currency-item.selected').attr('currency-code-data');
	var toCurrency = toCurrency || $(elt).attr('currency-code-data');

	if ($(".price-convert").length > 0) {
	    $('.upload-overlay-loading-fix').show();
	    changeSiteCurrency(fromCurrency, toCurrency, '.price-convert');
	}

	if(elt)
	{
		$('.curtext_hp').html('<span>'+$(elt).attr('currency-symbol-data')+'</span> '+$(elt).attr('currency-code-data'));
		$('#currencyTab .blacklbpbout a').html($(elt).attr('currency-code-data'));
		$('#currencyTab .blacklbpbout a').attr('title', $(elt).attr('currency-code-data'));
	
		$(".currency-item").removeClass('selected');
		$('.currency-item[currency-code-data="' + $(elt).attr('currency-code-data') + '"]').addClass('selected');
	}

	$('.curnewtab').hide();
	$('.blueairptab').hide();
	$(".imagarrowblue").hide();

	$.cookie.raw = true;
	$.cookie('currency', toCurrency);
}

function updateSearchStatic(val, lkname, pid, chlink, thlink, hotellink, restaurantslink, medialink) {
    var $where_ms = sprintf(Translator.trans("Where is %s?"), [val]);
    var str = '<a class="search-static-items" href="'+generateLangURL('/where-is-' + lkname + '-' + pid )+ '"><div class="itemspiccontent"><div class="search_static_pics search_static1"></div></div><div class="itemstextcontent">' + $where_ms + '</div></a>';
    if (hotellink != '' && hotellink > 0) {
	var $h_ms = sprintf(Translator.trans("Hotels in %s"), [val]);
	str += '<a class="search-static-items" href="'+generateLangURL('/hotels-in-' + lkname + '-' + pid) + '"><div class="itemspiccontent itemspiccontent1"><div class="search_static_pics search_static2"></div></div><div class="itemstextcontent">' + $h_ms + '</div></a>';
    }    
    if (thlink != '') {
	var $ttd_ms = sprintf(Translator.trans("Things to do in %s"), [val]);
	str += '<a class="search-static-items" href="'+thlink+ '"><div class="itemspiccontent itemspiccontent3"><div class="search_static_pics search_static4"></div></div><div class="itemstextcontent">' + $ttd_ms + '</div></a>';
    }
    if (medialink != '') {
	var $m_ms = sprintf(Translator.trans("Photos / Videos of %s"), [val]);
	str += '<a class="search-static-items" href="'+medialink+'"><div class="itemspiccontent itemspiccontent4"><div class="search_static_pics search_static5"></div></div><div class="itemstextcontent">' + $m_ms + '</div></a>';
    }
    if (chlink != '') {
	var $ch_ms = sprintf(Translator.trans("Channels about %s"), [val]);
	str += '<a class="search-static-items" href="'+generateLangURL('/channel-search/' + chlink, 'channels')+ '"><div class="itemspiccontent itemspiccontent5"><div class="search_static_pics search_static6"></div></div><div class="itemstextcontent">' + $ch_ms + '</div></a>';
    }
    $('.search-static').html(str);
}
function InitCarouselOther() {
    if ($(".caouselother").length > 0) {
	$(".caouselother").jCarouselLite({
	    circular: false,
	    vertical: false,
	    scroll: 1,
	    visible: 1,
	    auto: 0,
	    speed: 0,
	    hoverPause: true,
	    btnNext: "#OtherChannelsPop .next",
	    btnPrev: "#OtherChannelsPop .prev"
	});
    }
}
function checkSRCHSubmit(e) {
    if (e && e.keyCode == 13) {
	$('.search-btnClick').click();
    }
}
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;

    return true;
}
function refreshThePage() {
    ww = $(window).width();
    var w = ww < limit ? (location.reload(true)) : (ww > limit ? (location.reload(true)) : ww = limit);
}
function alphanumeric(inputtxt) {
    //var letterNumber = /^[0-9a-zA-Z]+$/;
    var letterNumber = /^[A-Za-z].*[0-9]|[0-9].*[A-Za-z]+$/;
    if (inputtxt.match(letterNumber)) {
	return true;
    } else {
	return false;
    }
}
function runModal(){
    $( window ).load(function() {
	$('.modal').show();
    });
    // When the user clicks on <span> (x), close the modal
    $(document).on('click', ".modal .close", function () { 
	$('.modal').hide();	    
    });
    // When the user clicks anywhere outside of the modal, close it
    $(window).on('click',function(event){
	if ($(event.target).hasClass('modal')) {
	    $('.modal').hide();
	}
    });
}