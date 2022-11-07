var reviews_page = 0;
var one_object = 0;
var firstPageView = true;
var shareFadeoutTimeout;
function initReviewsDocuments() {
//    $('.social_data_all').each(function (index, element) {
//        if ($(this).find('.social_not_refreshed').length > 0) {
//            initComments($(this));
//            initLikes($(this));
//            initShares($(this));
//        }
//    });
    $('.hotel_infos_imgbig1Up').each(function (index, element) {
	var $this = $(this);
	$this.mouseover(function () {
	    $(this).find('.clsimg.mediabuttons').show();
	});
	$this.mouseout(function () {
	    $(this).find('.clsimg.mediabuttons').hide();
	});
    });
    $(".mediabuttons").mouseover(function () {
	var $this = $(this).closest('.hotel_infos_imgbig1Up');
	var posxx = $this.offset().left - $('#thotel').offset().left - 122;
	var posyy = $this.offset().top - $('#thotel').offset().top - 21;
	$('.evContainer2Over .ProfileHeaderOverin').html($(this).attr('data-title'));
	$('.evContainer2Over').css('left', posxx + 'px');
	$('.evContainer2Over').css('top', posyy + 'px');
	$('.evContainer2Over').stop().show();
    });
    $(".mediabuttons").mouseout(function () {
	$('.evContainer2Over').hide();
    });
    initReportsLog();
}
function initReportsLog() {
    $(".report_button_log_reason").each(function () {
	var $this = $(this);
	var $parent = $this.closest('.hotelReviewsContainer');
	$this.removeClass('report_button_log_reason');
	initTTLogReportFunctions($this, $parent.find('.social_data_all'));
    });
}
function initscrollPane(obj) {
    obj.jScrollPane();
}
var apiBig2 = null;
function initscrollPaneBig2(obj) {
    obj.jScrollPane();
    apiBig2 = obj.data('jsp');
}
/*function setMap() {
 var id_location = 'mapPart';

 var image = '';
 if (dataType === '28') {
 image = new google.maps.MarkerImage(ReturnLink('/images/pin_hot.png'));
 } else if (dataType === '29') {
 image = new google.maps.MarkerImage(ReturnLink('/images/pin_rest.png'));
 } else if (dataType === '30') {
 image = new google.maps.MarkerImage(ReturnLink('/images/pin_lmk.png'));
 }
 var myStyles = [
 {
 featureType: "poi",
 elementType: "labels",
 stylers: [
 {visibility: "off"}
 ]
 }
 ];
 var mapOptions = {
 center: new google.maps.LatLng(latitudeVal, longitudeVal),
 zoom: 12,
 disableDefaultUI: true,
 mapTypeId: google.maps.MapTypeId.ROADMAP,
 styles: myStyles
 };

 var map = new google.maps.Map(document.getElementById(id_location), mapOptions);

 var marker = new google.maps.Marker({
 position: new google.maps.LatLng(latitudeVal, longitudeVal),
 map: map,
 icon: image
 });
 }*/
function getMoreReviews() {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
	url: ReturnLink('/ajax/ajax_load_reviews.php'),
	data: {
	    page: reviews_page,
	    entity_id: dataId,
	    entity_type: dataType,
	    one_object: one_object
	},
	type: 'post',
	success: function (data) {
	    one_object = 0;
	    $('.upload-overlay-loading-fix').hide();
	    var ret = null;
	    try {
		ret = $.parseJSON(data);
	    } catch (Ex) {
		return;
	    }
	    if (ret.error) {
		TTAlert({
		    msg: ret.error,
		    type: 'alert',
		    btn1: t('ok'),
		    btn2: '',
		    btn2Callback: null
		});
	    } else {
		var myData = ret.data;
		var data_count = ret.count;


		$(".oldReviewsList").append(myData);
		initReviewsDocuments();
		initCommentData();

		if (data_count - reviews_page * 6 > 6) {
		    $(".reviews_more").show();
		} else {
		    $(".reviews_more").hide();
		}

	    }
	}
    });
}
function checkActive() {
    $('.oldReviewsImg a.active').each(function () {
	var curElt = $(this);
	var indexElt = curElt.index();
	if (curElt.is(':last-child')) {
	    $('.img_next').hide();
	} else {
	    $('.img_next').show();
	}
	if (indexElt === 0) {
	    $('.img_prev').hide();
	} else {
	    $('.img_prev').show();
	}
    });
}
/**
 * get the original size of the image and fix it in the middle
 */
function centerImage() {
    var curnt = $(".review_large_img img");

    /*The content size*/
    var content_height = 530;
    var content_width = 994;

    /*The new image size*/
    var new_height = content_height;
    var new_width = content_width;

    /*The real image size*/
    var real_height = $(".oldReviewsImg a.active").attr('data-height');
    var real_width = $(".oldReviewsImg a.active").attr('data-width');

    /*The ratio*/
    var scale_width = new_width / real_width;
    var scale_height = new_height / real_height;


    if (scale_width < scale_height) {
	new_width = real_width * scale_width;
	new_height = real_height * scale_width;
    } else {
	new_width = real_width * scale_height;
	new_height = real_height * scale_height;
    }

    new_width = Math.round(new_width);
    new_height = Math.round(new_height);

    var left = (content_width - new_width) / 2;
    var top = (content_height - new_height) / 2;

    top = Math.round(top);
    left = Math.round(left);

    var new_style = {
	'top': top,
	//'left':left,
	'width': new_width,
	'height': new_height
    };
    curnt.css(new_style);
//        $style = "width: {$new_width}px; height: {$new_height}px;";
}
$(document).ready(function () {

    initscrollPane($(".scrollpane"));
    initscrollPaneBig2($(".oldReviewsImg_scroll"));
    initSocialActions();
    initReviewsDocuments();
//    setMap();
    checkActive();
    centerImage();
    $(document).on('click', '.addtomychannelBut', function () {
	var chid = $('#channel_code').val();
	if (chid == '') {
	    TTAlert({
		msg: Translator.trans('please select one of your channels...'),
		type: 'alert',
		btn1: Translator.trans('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    return;
	}
	var data_id = $('.hotelsContainer').attr('data-id');
	var data_type = $('.hotelsContainer').attr('data-type');
	$('.upload-overlay-loading-fix').show();
	$.ajax({
	    url: ReturnLink('/ajax/ajax_add_discover_tochannel.php'),
	    data: {
		id: data_id,
		data_type: data_type,
		channel_id: chid
	    },
	    type: 'post',
	    success: function (data) {
		$('.upload-overlay-loading-fix').hide();
		var ret = null;
		try {
		    ret = $.parseJSON(data);
		} catch (Ex) {
		    return;
		}
		if (ret.status == 'error') {
		    TTAlert({
			msg: ret.msg,
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		} else {
		    $('#channel_code').find('#channel_code' + chid).remove();
		}
	    }
	});
    });
    $(document).on('click', '#remove_button_rev', function () {
	var log_obj = $(this).closest('.hotelReviewsContainer');
	TTAlert({
	    msg: Translator.trans('are you sure you want to remove this review'),
	    type: 'action',
	    btn1: Translator.trans('cancel'),
	    btn2: Translator.trans('confirm'),
	    btn2Callback: function (data) {
		if (data) {
		    $.ajax({
			url: ReturnLink('/ajax/ajax_remove_review.php'),
			data: {
			    id: log_obj.attr('data-id'),
			    data_type: $('.hotelsContainer').attr('data-type')
			},
			type: 'post',
			success: function (data) {
			    $('.upload-overlay-loading-fix').hide();
			    var ret = null;
			    try {
				ret = $.parseJSON(data);
			    } catch (Ex) {
				return;
			    }
			    if (ret.status == 'error') {
				TTAlert({
				    msg: ret.msg,
				    type: 'alert',
				    btn1: t('ok'),
				    btn2: '',
				    btn2Callback: null
				});
			    } else {
				log_obj.remove();
				one_object = 1;
				getMoreReviews();
			    }
			}
		    });
		}
	    }
	});
    });
//    var pagedimensions = parent.window.returnIframeDimensions();
    $(".showOnMap").fancybox({
//        width: pagedimensions[0],
//        height: pagedimensions[1],
	width: $(window).width() - 80,
	height: $(window).height() - 80,
	closeBtn: true,
	autoSize: false,
	autoScale: true,
	transitionIn: 'elastic',
	transitionOut: 'fadeOut',
	type: 'iframe',
	padding: 0,
	margin: 0,
	scrolling: 'no',
	helpers: {
	    overlay: {closeClick: true}
	}
    });

    $(document).on('click', ".clsimg", function (e) {
	var $this = $(this).closest('.hotel_infos_imgbig1Up');
	var picid = $(this).attr("data-id");
	TTAlert({
	    msg: Translator.trans('are you sure you want to remove permanently this image?'),
	    type: 'action',
	    btn1: Translator.trans('cancel'),
	    btn2: Translator.trans('confirm'),
	    btn2Callback: function (data) {
		if (data) {
		    $('.upload-overlay-loading-fix').show();
		    TTCallAPI({
			what: 'user/discover/remove_image',
			data: {id: picid, entity_type: $('.hotelsContainer').attr('data-type'), },
			callback: function (ret) {
			    $('.upload-overlay-loading-fix').hide();
			    if (ret.status === 'error') {
				TTAlert({
				    msg: ret.msg,
				    type: 'alert',
				    btn1: t('ok'),
				    btn2: '',
				    btn2Callback: null
				});
				return;
			    }
			    $this.remove();
			    initscrollPane($(".scrollpane"));
			}
		    });
		}
	    }
	});
    });
    $(document).on('click', ".hotel_infos_imgbig1", function (e) {
	e.preventDefault();
	var imgSrc = $(this).attr('data-href');
	$('.hotel_infos_imgbigf1').attr('href', imgSrc);
	$('.hotel_infos_imgbigf1').click();
    });
    $(document).on('click', ".oldReviewsImg a", function (e) {
	e.preventDefault();
	var curElt = $(this);
	var indexElt = curElt.index();
	var imgSrc = curElt.attr('href');

	$(".oldReviewsImg a").removeClass("active");
	$(".oldReviewsImg a .youAreHere").animate({bottom: '-44px'}, 500);
	curElt.addClass("active");
	curElt.find(".youAreHere").stop();
	curElt.find(".youAreHere").animate({bottom: '0px'}, 500);
	$(".review_large_img img").remove();
	$(".review_large_img").prepend('<img alt="" src="' + imgSrc + '" >');
	$(".review_large_img").attr("data-index", indexElt);
	checkActive();
	centerImage();

	var jspPanepr = curElt.closest('.jspPane');
	console.log((curElt.position().top + jspPanepr.position().top));
	if ((curElt.position().top + jspPanepr.position().top) > 300) {
	    var newposition = -(321 - curElt.position().top - 103);
	    apiBig2.scrollToY(newposition, true);
	} else if ((curElt.position().top + jspPanepr.position().top) < 0) {
	    var newposition = curElt.position().top;
	    console.log(newposition);
	    apiBig2.scrollToY(newposition, true);
	}
	/*if( !firstPageView ){
	 $("html, body").animate({ scrollTop: $('.oldReviewsImgCon').offset().top}, 300);
	 }else{
	 firstPageView =false;
	 }*/
    });
    // .img_next onclick
    $(document).on('click', ".img_next", function () {
	var toclick = $('.oldReviewsImg a.active').index() + 1;
	$(".oldReviewsImg a:eq(" + toclick + ")").click();
    });
    $(document).on('click', ".img_prev", function () {
	var toclick = $('.oldReviewsImg a.active').index() - 1;
	$(".oldReviewsImg a:eq(" + toclick + ")").click();
    });
    $(".hotel_infos_imgbig").fancybox({
	helpers: {
	    overlay: {closeClick: true}
	}
    });
    $(".hotel_infos_imgbigf1").fancybox({
	helpers: {
	    overlay: {closeClick: true}
	}
    });
    if (parseInt(user_is_logged) != 0) {
	InitChannelUploaderHome('uploadReview_Img', 'upload_posts_container', 15, 0);
    }
    $(document).on('mouseover', ".GlobRate .eval_options_glob", function () {
	var val_txt = $(this).attr('data-text');
	$('.GlobRate .eval_options_over .eval_options_overtxt').html(val_txt);
	$('.GlobRate .eval_options_over').show();
    });
    $(document).on('mouseout', ".GlobRate .eval_options_glob", function () {
	$('.GlobRate .eval_options_over').hide();
    });
    $(document).on('click', ".GlobRate .eval_options_glob", function () {
	var $index = $(this).index();
	if (parseInt(user_is_logged) === 0) {
	    //$(".TopRegisterLink").click();
	    TTAlert({
		msg: t('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to evaluate.'),
		type: 'action',
		btn1: Translator.trans('cancel'),
		btn2: Translator.trans('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}
	var $this = $(this);
	var data_value = $(this).attr('data-value');
	//$('.hotelBigImgRate').html(data_value);
	TTCallAPI({
	    what: ReturnLink('/ajax/modal_newsfeed_rate.php'),
	    data: {entity_id: $('.hotelsContainer').attr('data-id'), entity_type: $('.hotelsContainer').attr('data-type'), score: data_value, globchannelid: null},
	    ret: 'json',
	    callback: function (resp) {
		if (resp.status == 'error') {
		    TTAlert({
			msg: resp.msg,
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		    return;
		}
		$('.GlobRate .eval_options_glob').removeClass('active');
		$this.addClass('active');
		$(".GlobRate .eval_options_glob").each(function () {
		    var newindex = $(this).index();
		    if (newindex <= $index) {
			$(this).addClass('active');
		    } else {
			$(this).removeClass('active');
		    }
		});
	    }
	});
    });


    $(document).on('mouseover', ".specificRate .eval_options_glob", function () {
	var $this = $(this);
	var val_txt = $this.attr('data-text');
	$this.parent().find('.eval_options_over .eval_options_overtxt').html(val_txt);
	$this.parent().find('.eval_options_over').show();
    });
    $(document).on('mouseout', ".specificRate .eval_options_glob", function () {
	var $this = $(this);
	$this.parent().find('.eval_options_over').hide();
    });
    $(document).on('click', ".specificRate .eval_options_glob", function () {
	var $index = $(this).index();
	if (parseInt(user_is_logged) === 0) {
	    //$(".TopRegisterLink").click();
	    TTAlert({
		msg: t('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to evaluate.'),
		type: 'action',
		btn1: Translator.trans('cancel'),
		btn2: Translator.trans('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}
	var $this = $(this);
	var $parents = $this.closest('.specificRate');
	var data_value = $this.attr('data-value');
	var data_type = $this.closest('.specificRate').attr('data-type');
	TTCallAPI({
	    what: ReturnLink('/ajax/modal_newsfeed_rate.php'),
	    data: {entity_id: $('.hotelsContainer').attr('data-id'), entity_type: data_type, score: data_value, globchannelid: null},
	    ret: 'json',
	    callback: function (resp) {
		if (resp.status == 'error') {
		    TTAlert({
			msg: resp.msg,
			type: 'alert',
			btn1: Translator.trans('ok'),
			btn2: '',
			btn2Callback: null
		    });
		    return;
		}
		$this.siblings().removeClass('active');
		$this.addClass('active');
		$parents.find(".eval_options_glob").each(function () {
		    var newindex = $(this).index();
		    if (newindex <= $index) {
			$(this).addClass('active');
		    } else {
			$(this).removeClass('active');
		    }
		});
	    }
	});
    });


    $(".revThemesOption").click(function () {

	if (parseInt(user_is_logged) === 0) {
	    //$(".TopRegisterLink").click();
	    TTAlert({
		msg: Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to select themes.'),
		type: 'action',
		btn1: Translator.trans('cancel'),
		btn2: Translator.trans('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}

	$(this).toggleClass('active');
    });
    $(document).on('click', ".rev_infocheckbox .uploadinfocheckboxpic", function () {

	if (parseInt(user_is_logged) === 0) {
	    //$(".TopRegisterLink").click();
	    TTAlert({
		msg: Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to select options.'),
		type: 'action',
		btn1: t('cancel'),
		btn2: t('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}

	var curob = $(this).parent();
	if (curob.hasClass('active')) {
	    curob.removeClass('active');
	} else {
	    curob.addClass('active');
	}
    });
    $(document).on('click', ".learnMore", function (e) {
	if (parseInt(user_is_logged) === 0) {
	    //$(".TopRegisterLink").click();
	    TTAlert({
		msg: Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to learn more.'),
		type: 'action',
		btn1: Translator.trans('cancel'),
		btn2: Translator.trans('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}

	e.preventDefault();
	var thisElt = $(this);
	var theParent = thisElt.parent();
	var theVal = theParent.attr('data-val');
	thisElt.hide();
	theParent.append(theVal);
    });
    $(document).on('click', ".opt", function () {
	if (parseInt(user_is_logged) === 0) {
	    //$(".TopRegisterLink").click();
	    TTAlert({
		msg: Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to check options.'),
		type: 'action',
		btn1: Translator.trans('cancel'),
		btn2: Translator.trans('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}

	var curob = $(this);
	if (curob.hasClass('active')) {
	    curob.removeClass('active');
	} else {
	    curob.addClass('active');
	}
    });
    $(".evalOptionContainer").hover(
	    function () {
		$(this).find('.toslide').stop().animate({left: '0px'}, 500);
	    },
	    function () {
		$(this).find('.toslide').stop().animate({left: '-150px'}, 500);
	    }
    );

    $(document).on('click', ".reviews_more", function () {
	reviews_page++;
	getMoreReviews();
    });

    // submitReview onclick
    $(document).on('click', ".submitReview", function () {
	if (parseInt(user_is_logged) === 0) {
	    //$(".TopRegisterLink").click();
	    TTAlert({
		msg: Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to add reviews.'),
		type: 'action',
		btn1: Translator.trans('cancel'),
		btn2: Translator.trans('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}
	var revTxt = $('.rev_description').val();
	var revThemes = new Array();
	var revDate = $('.rev_select').val();
//        var revRates = new Array();
	var revNear = new Array();
	var revChecked = ($(".rev_infocheckbox.active").length == 1);
	if (revTxt === '') {
	    TTAlert({
		msg: t('Your review cannot be empty !'),
		type: 'alert',
		btn1: t('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    return;
	}
	if (!revChecked) {
	    TTAlert({
		msg: Translator.trans('Your have to certify that this review is based on your own experience !'),
		type: 'alert',
		btn1: Translator.trans('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    return;
	}
	$(".revThemesOption.active").each(function (index) {
	    revThemes.push($(this).attr('data-value'));
	});
	var revThemesstr = revThemes.join(',');
	$(".nearHotelOpt .opt.active").each(function (index) {
	    var val = $(this).attr('data-value');

	    revNear.push(val);
	});
	var revNearstr = revNear.join(',');

	$.ajax({
	    url: ReturnLink('/ajax/ajax_add_review.php'),
	    data: {
		id: $('.hotelsContainer').attr('data-id'),
		data_type: $('.hotelsContainer').attr('data-type'),
		txt: revTxt,
		themes: revThemesstr,
		date: revDate,
		near: revNearstr
	    },
	    type: 'post',
	    success: function (data) {
		$('.upload-overlay-loading-fix').hide();
		var ret = null;
		try {
		    ret = $.parseJSON(data);
		} catch (Ex) {
		    return;
		}
		if (ret.status == 'error') {
		    TTAlert({
			msg: ret.msg,
			type: 'alert',
			btn1: Translator.trans('ok'),
			btn2: '',
			btn2Callback: null
		    });
		} else {
		    TTAlert({
			msg: ret.msg,
			type: 'alert',
			btn1: Translator.trans('ok'),
			btn2: '',
			btn2Callback: function (e) {
			    location.reload();
			}
		    });
		}
	    }
	});

    });
//   / submitReview onclick
    $(document).on('click', ".MediaButton_data_share", function () {
	if ($('.share_link_holder').css('display') != "none") {
	    $('.share_link_holder').hide();
	    $('.mediabuttonsOver').removeClass('inactive');
	    $(this).removeClass('active');
	} else {
	    $('.mediabuttonsOver').addClass('inactive');
	    $(this).addClass('active');
	    $('.share_link_holder').show();
	    $('.mediabuttonsOver').hide();

	    shareFadeoutTimeout = setTimeout(function () {
		$('.share_link_holder').hide();
		$('.mediabuttonsOver').removeClass('inactive');
		$('.MediaButton_data_share').removeClass('active');
	    }, 1500);

	    $('.share_link_holder').unbind('mouseenter mouseleave').hover(function () {
		clearTimeout(shareFadeoutTimeout);
		$('.share_link_holder').stop(true, true);
		$('.share_link_holder').show();
	    }, function () {
		shareFadeoutTimeout = setTimeout(function () {
		    $('.share_link_holder').hide();
		    $('.mediabuttonsOver').removeClass('inactive');
		    $('.MediaButton_data_share').removeClass('active');
		}, 500);
	    });
	}
    });

    // rev_select onchange
    $(document).on('change', ".rev_select", function () {
	if (parseInt(user_is_logged) === 0) {
	    //$(".TopRegisterLink").click();
	    TTAlert({
		msg: Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to select travel date.'),
		type: 'action',
		btn1: Translator.trans('cancel'),
		btn2: Translator.trans('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}
    });
//   / rev_select onchange

// uploadReviewImg onclick
    $(document).on('click', ".uploadReviewImg", function () {
	if (parseInt(user_is_logged) === 0) {
	    $(".TopRegisterLink").click();
	    TTAlert({
		msg: Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to add photo.'),
		type: 'action',
		btn1: Translator.trans('cancel'),
		btn2: Translator.trans('register'),
		btn2Callback: function (data) {
		    if (data) {
			window.location.href = ReturnLink('/register');
		    }
		}
	    });
	    return;
	}


    });
//   / uploadReviewImg onclick

//    $('.goToRegister').click(function(event) {
//        event.preventDefault();
//        event.stopImmediatePropagation();
//        $.fancybox({
//            'autoScale': true,
//            'transitionIn': 'elastic',
//            'transitionOut': 'elastic',
//            'speedIn': 500,
//            'speedOut': 300,
//            'autoDimensions': true,
//            'centerOnScroll': true,
//            'padding': 0,
//            'margin': 0,
//            'href': '#requestinvitationcontainer'
//        });
//    });
    $(".hotel_infos_imgbig2.active").click();
});
function updateImage(str, pic_link, _type) {
    if (_type == "uploadReview_Img") {

	TTCallAPI({
	    what: 'user/discover/add_image',
	    data: {entity_type: $('.hotelsContainer').attr('data-type'), item_id: $('.hotelsContainer').attr('data-id'), filename: userGlobalDISName() + '' + pic_link},
	    callback: function (ret) {
		if (ret.status === 'error') {
		    TTAlert({
			msg: ret.msg,
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		    return;
		}
		$('.aReviewImg .jspPane').prepend('<div class="hotel_infos_imgbig1Up"><div class="mediabuttonscontainer"><div data-title="remove" data-id="' + ret.id + '" class="clsimg mediabuttons"></div></div><div data-href="' + ReturnLink('media/discover/large/' + userGlobalDISName() + pic_link) + '" class="hotel_infos_imgbig1">' + str + '</div></div>');
		initscrollPane($(".scrollpane"));
		initReviewsDocuments();
	    }
	});
    }
    closeFancyBox();
}
$(document).keydown(function (ev) {
    if (($('.img_next').length > 0 && $('.img_next').css('display') != 'none') || ($('.img_prev').length > 0 && $('.img_prev').css('display') != 'none')) {
	if (ev.keyCode == 39) {
	    if ($('.img_next').length > 0 && $('.img_next').css('display') != 'none') {
		$('.img_next').click();
	    }
	} else if (ev.keyCode == 37) {
	    if ($('.img_prev').length > 0 && $('.img_prev').css('display') != 'none') {
		$('.img_prev').click();
	    }
	}
    }
});