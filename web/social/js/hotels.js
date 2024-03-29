var reviews_page = 0;
/*THotel' s Javascript*/
function setGuests() {
    var $var = '';
    if ($("#guests_sel").val() === '') {
	$var = $("#guests_sel_rooms").val() + '-' + $("#guests_sel_adults").val() + '-' + $("#guests_sel_children").val();
    } else {
	$var = $("#guests_sel").val();
    }
    $("#guests").val($var);
}
/*
 * get the value from hidden field and set them in different select box
 * used on page load after we set the value of the hidden field using the querystring room: (room-adults-children)
 */
function setGuestsFields() {
    var theValue = $("#guests").val();
    if (theValue == '1-1-0' || theValue == '1-2-0') {
	$(".guestSelectGroup").hide();
	$("#guests_sel").val(theValue);
    } else {
	$(".guestSelectGroup").show();
	$("#guests_sel").val('');
	var res = theValue.split('-');
	$("#guests_sel_rooms").val(res[0]);
	$("#guests_sel_adults").val(res[1]);
	$("#guests_sel_children").val(res[2]);
    }
}
function setCurrency(currencyGroup, var1, var2, var3, curName) {
    $.fancybox.close();
//    $("#hotelBudgetOption1 span").html(var1);
//    $("#hotelBudgetOption2 span").html(var2);
//    $("#hotelBudgetOption3 span").html(var3);
    $(".changeCurrencyBtn").html(curName);
}
$(document).ready(function () {
    var moreopened = false;
    initSocialActions();
    initReviewsDocuments();
    $(".more").click(function () {
	var $this = $(this);
	var prev = $this.prev();
	var stanHeight = prev.find("div.stanTXT").height();
	if (moreopened) {
	    prev.stop().animate({height: 101}, 500, function () {
		$this.html(t('> more'));
	    });
	    moreopened = false;
	} else {
	    prev.stop().animate({height: stanHeight}, 500, function () {
		$this.html(t('< less'));
	    });
	    moreopened = true;
	}
    });
    var moreopened2 = false;
    $(".more2").click(function () {
	var $this = $(this);
	var prev = $this.prev();
	var stanHeight = prev.find("div.stanTXT").height();
	if (moreopened2) {
	    prev.stop().animate({height: 54}, 500, function () {
		$this.html(t('> more'));
	    });
	    moreopened2 = false;
	} else {
	    prev.stop().animate({height: stanHeight}, 500, function () {
		$this.html(t('< less'));
	    });
	    moreopened2 = true;
	}
    });
    $(document).on('click', '.account-btn-cancel', function () {
	$('.fancybox-close').click();
    });
    $(document).on('click', "#saveabout", function () {
	$('.sharePopUpRapper_error').html('');
	var data_id = $('#thotel').attr('data-id');
	var $this = $(this);
	var $parent = $this.parent().parent();
	var invite_msg = getObjectData($parent.find(".sharePopUpTextArea"));
	var inviteArray = new Array();
	$parent.find('.peoplecontainer .peoplesdata').each(function () {
	    var obj = $(this);
	    if (obj.attr('id') == "friendsdata") {
		inviteArray.push({friends: 1});
	    } else if (obj.attr('id') == "followersdata") {
		inviteArray.push({followers: 1});
	    } else if (obj.attr('id') == "friends_of_friends_data") {
		inviteArray.push({friendsandfollowers: 1});
	    } else if (obj.attr('data-email') != '') {
		inviteArray.push({email: obj.attr('data-email')});
	    } else if (parseInt(obj.attr('data-id')) != 0) {
		inviteArray.push({id: obj.attr('data-id')});
	    }
	});
	if (inviteArray.length == 0) {
	    $('.sharePopUpRapper_error').html(Translator.trans('Invalid share data'));
	    return;
	}
	TTCallAPI({
	    what: 'social/share',
	    data: {entity_type: SOCIAL_ENTITY_HOTEL, entity_id: data_id, share_with: inviteArray, share_type: SOCIAL_SHARE_TYPE_SHARE, msg: invite_msg, channel_id: null, addToFeeds: 1},
	    callback: function (ret) {
		$('.fancybox-close').click();
	    }
	});
    });
    //
    $(document).on('click', ".reviews_more", function () {
	reviews_page++;
	getMoreReviews();
    });
    if ($('#addmoretext_privacy').length > 0) {
	addmoreusersautocomplete_custom_journal($('#addmoretext_privacy'));
    }
    $(document).on('click', ".uploadinfocheckbox", function () {
	if ($(this).hasClass('active')) {
	    $(this).removeClass('active');
	} else {
	    $(this).addClass('active');
	}
    });
    // click on submit review for unlogged user
    $(document).on('click', "#reviewFormSubmit0", function () {
	TTAlert({
	    msg: Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to write a review.'),
	    type: 'alert',
	    btn1: Translator.trans('ok'),
	    btn2: '',
	    btn2Callback: null
	});
    });
    //    /   click on submit review for unlogged user
    // click on submit review for logged user
    $(document).on('click', "#reviewFormSubmit1", function () {
	var jsError = '';
	var data_id = $('#thotel').attr('data-id');
	if ($("#reviewVal").val() == '') {
	    jsError = Translator.trans('Your review cannot be empty ! <br />');
	} else if (!$(".reviewCheckbox").hasClass('uploadinfocheckboxpicactive')) {
	    jsError = Translator.trans('Your have to certify that this review is based on your own experience !');
	}
	if (jsError != '') {
	    TTAlert({
		msg: jsError,
		type: 'alert',
		btn1: t('ok'),
		btn2: '',
		btn2Callback: null
	    });
	} else {
	    TTCallAPI({
		what: 'user/reviews/add',
		data: {entity_type: SOCIAL_ENTITY_HOTEL, item_id: data_id, title: '', description: $("#reviewVal").val()},
		callback: function (ret) {
		    if (ret.status === 'error') {
			TTAlert({
			    msg: ret.msg,
			    type: 'alert',
			    btn1: Translator.trans('ok'),
			    btn2: '',
			    btn2Callback: null
			});
			return;
		    }
		    window.location.reload();
		}
	    });
	}
    });
    //   / click on submit review for logged in user
    $(".shareThis").fancybox({
	padding: 0,
	margin: 0,
	beforeLoad: function () {
	    resetSelectedUsers();
	    $('#sharePopUp .peoplecontainer .peoplesdata').each(function () {
		$(this).remove();
	    });
	    $('#sharePopUp .uploadinfocheckbox').removeClass('active');
	}
    });
    autocompleteHotels($('#destinationInput'));
    // changeSearch onclick
    $(document).on('click', ".changeSearch", function () {
	$(".basicSearchPart").show();
    });
    //   / changeSearch onclick
    // hide search part
    $(document).on('click', ".hideSearchPart", function () {
	$(".basicSearchPart").hide();
    });
    //   / hide search part
    $(".reviewCheckbox").click(function () {
	var curob = $(this);
	if (curob.hasClass('uploadinfocheckboxpicactive')) {
	    curob.removeClass('uploadinfocheckboxpicactive');
	} else {
	    curob.addClass('uploadinfocheckboxpicactive');
	}
    });

    // Navigate in thumbs Back and Forward
    $(document).on('click', ".thumbsForwardEnable,.thumbsBackEnable", function () {
	var theHotel = $(this).attr('data-hotel');
	var thePage = $(this).attr('data-page');

	$.post(ReturnLink('/ajax/ajax_thotel_media.php'),
		{hotel: theHotel, to: thePage}, function (data) {
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
		    btn1: Translator.trans('ok'),
		    btn2: '',
		    btn2Callback: null
		});
	    } else {
		$(".hotelListPhotosContainer").html(ret.media);
	    }
	    //$('.upload-overlay-loading-fix').hide();
	});
    });
    //   /  Navigate in thumbs Back and Forward
    $('.searchButton').click(function () {
	var destination2 = $('#destinationInput').val();
	if (destination2 == '') {
	    TTAlert({
		msg: Translator.trans('You have to specify a destination'),
		type: 'alert',
		btn1: Translator.trans('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    return;
	}
	var destination = '';
	var checkinDate = $('#fromtxt').attr('data-cal');
	var checkoutDate = $('#totxt').attr('data-cal');
	var data_hotel = $('#destinationInput').attr('data-hotel');
	var dates = '';
	if (typeof checkinDate !== 'undefined' && checkinDate !== '') {
	    dates += '/from/' + checkinDate;
	    if (typeof checkoutDate !== 'undefined' && checkoutDate !== '') {
		dates += '/to/' + checkoutDate;
	    }
	}
	if ($("#guests").val() != '')
	    destination += '/room/' + $("#guests").val();
	if ($('#destinationInput').attr('data-city') != '')
	    destination += '/C/' + $('#destinationInput').attr('data-city');
	if ($('#destinationInput').attr('data-state-code') != '')
	    destination += '/ST/' + $('#destinationInput').attr('data-state-code');
	if ($('#destinationInput').attr('data-code') != '')
	    destination += '/CO/' + $('#destinationInput').attr('data-code');
	if (data_hotel != "" && parseInt(data_hotel) > 0) {
	    var link = ReturnLink('/thotel/id/' + parseInt(data_hotel) + '/s/' + destination2 + destination + dates);
	    document.location.href = link;
	} else {
	    var link = ReturnLink('/hotel-search/' + destination2 + destination + dates);
	    window.open(link, '_blank');
	}
    });

    $('.hotelRoomGallerySmall').click(function () {
	var index = $(this).attr('data-val');
	var $parent = $(this).closest('.hotelRoomBlock');
	if ($parent.hasClass('hotelRoomBlockClosed')) {
	    $parent.removeClass('hotelRoomBlockClosed');
	    $parent.prev().addClass('hotelRoomBlockHeadActive');
	}
	$(this).parent().next().find('.hotelRoomGalleryBig').attr('src', index);
    });
    $('.evalOptionContainer_hotel').click(function () {
	if (!$(this).hasClass('active')) {
	    var $this = $(this);
	    var data_value = $(this).attr('data-value');
	    //$('.hotelBigImgRate').html(data_value);
	    TTCallAPI({
		what: ReturnLink('/ajax/modal_newsfeed_rate.php'),
		data: {entity_id: $('#thotel').attr('data-id'), entity_type: $('#thotel').attr('data-type'), score: data_value, globchannelid: null},
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
		    $('.evalOptionContainer').removeClass('active');
		    $this.addClass('active');
		}
	    });
	}
    });
    $('.hotelRoomBlockHead').click(function () {
	var $this = $(this);
	if (!$this.hasClass('hotelRoomBlockHeadActive')) {
	    $this.addClass('hotelRoomBlockHeadActive');
	    $this.next().removeClass('hotelRoomBlockClosed');
	} else {
	    $this.removeClass('hotelRoomBlockHeadActive');
	    $this.next().addClass('hotelRoomBlockClosed');
	}
    });
    $(".hotelFacilitiesHeadContainer").click(function () {
	var $this = $(this);
	var $parent = $this.closest('.hotelFacilities');
	if (!$this.hasClass('hotelFacilitiesHeadContainerActive')) {
	    $this.addClass('hotelFacilitiesHeadContainerActive');
	    $parent.find(".hotelFacilitiesBody").show();
	} else {
	    $this.removeClass('hotelFacilitiesHeadContainerActive');
	    $parent.find(".hotelFacilitiesBody").hide();
	}
    });
    $(".hotelPoliciesHeadContainer").click(function () {
	var $this = $(this);
	if (!$this.hasClass('hotelPoliciesHeadContainerActive')) {
	    $this.addClass('hotelPoliciesHeadContainerActive');
	    $(".hotelPoliciesBody").show();
	} else {
	    $this.removeClass('hotelPoliciesHeadContainerActive');
	    $(".hotelPoliciesBody").hide();
	}
    });
    $('.reviewComment').focus(function () {
	$(this).val('');
    });
    $('.reviewComment').blur(function () {
	if ($(this).val() == '') {
	    $(this).val(Translator.trans('Write a comment...'));
	}
    });
    $(document).on('click', ".hotelListPic", function () {
	var index = $(this).attr('data-src');
	$('.hotelBigImg').css('width', $(this).attr('data-w'));
	$('.hotelBigImg').css('height', $(this).attr('data-h'));
	$('.hotelBigImg').attr('src', '');
	$('.hotelBigImg').attr('src', index);
    });
    var pagedimensions = parent.window.returnIframeDimensions();
    $(".showOnMap").fancybox({
	width: pagedimensions[0],
	height: pagedimensions[1],
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
    $("#activetubers").on('mouseover', 'img', function (e) {
	if (!$(this).data('title')) {
	    $(this).data('title', $(this).attr('title'));
	    $(this).attr('title', '');
	}
	var title = $(this).data('title');
	var c = (title !== "") ? "<br/>" + title : "";
	$("body").append("<div id='preview'><img src='" + $(this).attr('data-src') + "' alt='Image preview' style='width: 100px;height: 100px;'/>" + c + "</div>");
	var pos_top = $(this).offset().top;
	var pos_left = $(this).offset().left + 28; //width of thumb is 28
	$("#preview").css("top", pos_top + "px").css("left", pos_left + "px").css({
	    position: 'absolute',
	    border: '1px solid #ccc',
	    background: '#333',
	    'font-size': '12px',
	    padding: '5px',
	    display: 'none',
	    color: '#fff',
	    'z-index': 10000,
	    'text-align': 'center'
	}).fadeIn("fast");
    }).on('mouseout', 'img', function () {
	$("#preview").remove();
    }).mousemove(function (e) {
	//$("#preview").css("top",(e.pageY - yOffset) + "px").css("left",(e.pageX + xOffset) + "px");
    });

    // guests_sel on change
    $(document).on('change', "#guests_sel", function () {
	if ($(this).val() == '') {
	    $(".guestSelectGroup").show();
	} else {
	    $(".guestSelectGroup").hide();
	}
	setGuests();
    });
    //   / guests_sel on change
    setGuestsFields();
    // guests_sel_rooms,guests_sel_adults,guests_sel_children on change
    $(document).on('change', "#guests_sel_rooms,#guests_sel_adults,#guests_sel_children", function () {
	setGuests();
    });
    $('#guests_sel').change();
    //   / guests_sel_rooms,guests_sel_adults,guests_sel_children on change

    InitCalendar();
});
function InitCalendar() {
    Calendar.setup({
	inputField: "fromtxt",
	noScroll: true,
	fixed: true,
	trigger: "fromtxt",
	align: "B",
	onSelect: function () {
	    var date = Calendar.intToDate(this.selection.get());
	    TO_CAL.args.min = date;
	    TO_CAL.redraw();
	    $('#fromtxt').attr('data-cal', Calendar.printDate(date, "%Y-%m-%d"));
	    addCalToEvent(this);
	    this.hide();
	},
	dateFormat: "%d / %m / %Y"
    });
    TO_CAL = Calendar.setup({
	inputField: "totxt",
	noScroll: true,
	fixed: true,
	trigger: "totxt",
	align: "B",
	onSelect: function () {
	    var date = Calendar.intToDate(this.selection.get());
	    $('#totxt').attr('data-cal', Calendar.printDate(date, "%Y-%m-%d"));
	    addCalToEvent(this);
	    this.hide();
	},
	dateFormat: "%d / %m / %Y"
    });
}
function addCalToEvent(cals) {
    if (new Date($('#fromtxt').attr('data-cal')) > new Date($('#totxt').attr('data-cal'))) {
	$('#totxt').attr('data-cal', $('#fromtxt').attr('data-cal'));
	$('#totxt').val($('#fromtxt').val());
    }
}
function getMoreReviews() {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
	url: ReturnLink('/ajax/ajax_load_reviews.php'),
	data: {page: reviews_page, entity_id: $('#thotel').attr('data-id'), entity_type: $('#thotel').attr('data-type')},
	type: 'post',
	success: function (data) {
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
		    btn1: Translator.trans('ok'),
		    btn2: '',
		    btn2Callback: null
		});
	    } else {
		var myData = ret.data;
		var data_count = ret.count;


		$(".hotelReviews").append(myData);
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
function initReviewsDocuments() {
//    $('.social_data_all').each(function(index, element) {
//        if ($(this).find('.social_not_refreshed').length > 0) {
//            initComments($(this));
//            initLikes($(this));
//            initShares($(this));
//        }
//    });
}