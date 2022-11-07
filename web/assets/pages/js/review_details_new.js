$(document).ready(function () {
    prepareSliders();
    ttModal = window.getTTModal("myModalZ", {});
    $(".hotel_infos_imgbig").fancybox({
	helpers: {
	    overlay: {closeClick: true}
	}
    });
    $(document).on('mouseover', ".specificRate .eval_options_glob", function () {
	var val_txt = $(this).attr('data-text');
	var parents = $(this).parent();
	parents.find('.eval_options_over .eval_options_overtxt').html(val_txt);
	parents.find('.eval_options_over').show();
    });
    $(document).on('mouseout', ".specificRate .eval_options_glob", function () {
	var parents = $(this).parent();
	parents.find('.eval_options_over').hide();
    });
    $(document).on('click', ".eval_options_glob", function () {
	var $index = $(this).index();
	if (parseInt($('.review_main_container').attr('data-logged')) === 0) {
	    ttModal.confirm(Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink(('/register')) + '">' + Translator.trans('TT account') + '</a> '+ Translator.trans('in order to evaluate.'), function (btn) {
		if(btn == "ok"){
		    window.location.href = ReturnLink(('/register'));
		}
	    }, null, {ok:{value:Translator.trans("register")},cancel:{value:Translator.trans("cancel")}});
	    return;
	}
	
	var $this = $(this);
	var $parents = $this.closest('.specificRate');
	var data_value = $(this).attr('data-value');
	var activedata_value = $parents.find('.eval_options_glob.active:last').attr('data-value');
	if ($parents.find('.eval_options_glob.active:last').length > 0 && activedata_value == data_value) {
	    data_value = 0;
	    $index = -1;
	}
	TTCallAPI({
	    what: ReturnLink(('/ajax/modal_newsfeed_rate.php')),
	    data: {entity_id: $('.review_main_container').attr('data-id'), entity_type: $parents.attr('data-type'), score: data_value, globchannelid: null},
	    ret: 'json',
	    callback: function (resp) {
		if (resp.status == 'error') {
		    ttModal.alert(ret.msg, null, null, {ok:{value:"close"}});		    
		    return;
		}
		$parents.find('.eval_options_glob').removeClass('active');
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

    if ($('.shares').length > 0) {
	$('.shares').each(function () {
	    var $this = $(this);
	    var mid = $this.attr('data-id');
	    $this.fancybox({
		padding: 0,
		margin: 0
	    });
	});
    }

    if ($('.slider-for').length > 0) {
	var mySlickSlider = $('.simpleSlider').slick({
	    dots: false,
	    infinite: false,
	    speed: 300,
	    slidesToShow: 1,
	    slidesToScroll: 1,
	    centerMode: false,
	    variableWidth: false,
	    arrows: false,
	    lazyLoad: 'ondemand',
	});

	if ($(".img_parent_holder").length) {
	    $(document).on('click', '.img_parent_holder .img_holder', function () {
		var $this = $(this);
		var thisIndex = $this.index();
		$('.img_parent_holder .img_holder').removeClass('active');
		$this.addClass('active');
		mySlickSlider.slick('slickGoTo', thisIndex);
	    });
	}
    }

    //upload photos
    $(document).on('click', "#uploadReview_Img", function () {
	var user_is_logged = $('.review_main_container').attr('data-logged');
	if (parseInt(user_is_logged) === 0) {
	    ttModal.confirm(Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink(('/register')) + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to evaluate.'), function (btn) {
		if(btn == "ok"){
					    window.location.href = ReturnLink(('/register'));
		}
	    }, null, {ok:{value:Translator.trans("register")},cancel:{value:Translator.trans("cancel")}});
	    return;
	}
    });

    // submitReview
    $(document).on('click', ".submitReview", function () {
	var user_is_logged = $('.review_main_container').attr('data-logged');
	if (parseInt(user_is_logged) === 0) {
	    ttModal.confirm(Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink(('/register')) + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to add reviews.'), function (btn) {
		if(btn == "ok"){
					    window.location.href = ReturnLink(('/register'));
		}
	    }, null, {ok:{value:Translator.trans("register")},cancel:{value:Translator.trans("cancel")}});
	    return;
	}
	
	var $parent = $(this).closest('.review_form_container');
	var revTxt = $parent.find('.rev_description').val();
	if (revTxt === '') {
	    ttModal.alert(Translator.trans('Your review cannot be empty !'), null, null, {ok:{value:"ok"}});
	    return;
	}

	if ( !$parent.find('input[name=certifyBox]').attr('checked') ) {
	    ttModal.alert(Translator.trans('Your have to certify that this review is based on your own experience !'), null, null, {ok:{value:"ok"}});
	    return;
	}

	$.ajax({
	    url: ReturnLink('/ajax/ajax_add_review.php'),
	    data: {
		id: $('.review_main_container').attr('data-id'),
		data_type: $('.review_main_container').attr('data-type'),
		txt: revTxt
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
		    ttModal.alert(ret.msg, null, null, {ok:{value:"ok"}});
		} else {
		    $('.rev_description').val('');
		    $('input:checkbox').removeAttr('checked');
		    ttModal.alert(ret.msg, function (btn) {
			if(btn == "ok"){
			    location.reload();
			}
		    }, null, {ok:{value:Translator.trans("ok")}});
		}
	    }
	});
    });

    //photoremove
    if ($(".photoremove").length) {
	$(document).on('click', ".photoremove", function (e) {
	    var $this = $(this).closest('.img_holder');
	    var picid = $this.attr("data-id");
		ttModal.confirm(Translator.trans("are you sure you want to remove permanently this image?"), function (btn) {
                    if(btn == "ok"){
                        $.ajax({
                            url: generateLangURL('/ajax/remove_discover_image.php','ajax'),
			    data: {
				id: picid,
				entity_type: $('.review_main_container').attr('data-type')
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
				    ttModal.alert(ret.msg, null, null, {ok:{value:"close"}});
				} else {
				    location.reload();
				}                                
                            }
                        }); 
                    }
                }, null, {ok:{value:Translator.trans("confirm")},cancel:{value:Translator.trans("cancel")}});
	});
    }

    //remove review
    if ($(".remove_button_rev").length) {
	$(document).on('click', '.remove_button_rev', function () {
	    var log_obj = $(this).closest('.social_data_all');
		    ttModal.confirm(Translator.trans("Are you sure you want to remove this review"), function (btn) {
                    if(btn == "ok"){
                        $.ajax({
                            url: generateLangURL('/ajax/ajax_remove_review.php','ajax'),
			    data: {
				id: log_obj.attr('data-id'),
				data_type: $('.review_main_container').attr('data-type')
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
				    ttModal.alert(ret.msg, null, null, {ok:{value:"close"}});
				} else {
				    log_obj.remove();
				}
                                
                            }
                        }); 
                    }
                }, null, {ok:{value:Translator.trans("confirm")},cancel:{value:Translator.trans("cancel")}});
	});
    }

    if ($("#uploadReview_Img").length && parseInt($('.review_main_container').attr('data-logged')) != 0) {
	InitChannelUploaderHome('uploadReview_Img', 'uploadPhotoContainer', 15, 0, true);
    }

    initReviewsGalleryPOPS();
    $(window).resize(function () {
	initReviewsGalleryPOPS();
    });
});

function prepareSliders() {
    if ($('.slider-for').length > 0) {
	$('.slider-for').each(function () {
	    prepareSlider(this);
	});
    }
}
function prepareSlider(target) {
    var unique = $(target).data('unique');
    var sliderFor = '.slider-for-' + unique;
    var nav = '.slider-nav-' + unique;

    if (!$(sliderFor).hasClass('slick-initialized')) {
	var slideglob = $(sliderFor).slick({
	    lazyLoad: 'ondemand',
	    slidesToShow: 1,
	    slidesToScroll: 1,
	    arrows: true,
	    fade: true,
	    centerMode: true
	});

	$(sliderFor).removeClass('opacity0');

	if ($(nav + ' .mediathumb').length > 0) {

	    $(nav + ' .mediathumb').eq(0).addClass('active');
	    $(sliderFor).on('afterChange', function (event, slick, index) {
		$(nav + ' .mediathumb').removeClass('active');
		$(nav + ' .mediathumb').eq(index).addClass('active');
	    });

	    $(nav + ' .mediathumb').click(function (e) {
		var indx = $(this).index();
		slideglob[0].slick.slickGoTo(indx);
		$(nav + ' .mediathumb').removeClass('active');
		$(this).addClass('active');
	    });
	}

	if ($('.swiper-container-' + unique).length > 0) {
	    $('.swiper-container-' + unique).swiper({
		slidesPerView: 'auto',
		centeredSlides: false,
		spaceBetween: 10
	    });
	}
    }
}
function initReviewsGalleryPOPS() {
    if ($('.imagefirstbut').length > 0) {
	var pagedimensions = parent.window.returnIframeDimensions();
	$(".imagefirstbut").unbind('click');
	$(".imagefirstbut").bind("click", function (event) {
	    event.preventDefault();
	    var $href = $(this).attr('href');
//            if(pagedimensions[0]>1148) pagedimensions[0]=1148;
//            if(pagedimensions[0]<768) pagedimensions[0]='100%';
	    $.fancybox({
		width: '100%',
		height: $(window).height() + 'px',
		href: $href,
		closeBtn: true,
		autoSize: false,
		autoScale: true,
		autoHeight: true,
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
	});
    }
}
function updateImage(pic_link, _type) {
    if (_type == "uploadReview_Img") {
	$.ajax({
	    url: ReturnLink(('/ajax/add_discover_image.php')),
	    data: {
		item_id: $('.review_main_container').attr('data-id'),
		filename: pic_link,
		entity_type: $('.review_main_container').attr('data-type')
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
		if (ret.status === 'error') {
		    ttModal.alert(ret.msg, null, null, {ok:{value:"close"}});		    
		    return;
		}
		location.reload();
		//$('.yourPhotosContainer').prepend('<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3 photoitems" data-id="' + ret.id + '"><div class="photoremove">X remove</div><img src="' + ReturnLink('media/discover/thumb/' + pic_link) + '" class="width100"/></div>');
	    }
	});
    }
    closeFancyBox();
}