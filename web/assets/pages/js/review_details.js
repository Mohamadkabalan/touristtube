$(document).ready(function () {
    prepareSliders();
    if( !ttModal ) {
	ttModal = window.getTTModal("myModalZ", {});
    }
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
		showNeedTTAccountMessage();
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
	$('.upload-overlay-loading-fix').show();
	$.ajax({
	    url: generateLangURL('/ajax/add_rate'),
	    data: {
		entity_id: $('.review_main_container').attr('data-id'),
		entity_type: $parents.attr('data-type'), 
		score: data_value
	    },
	    type: 'post',
	    success: function (data) {
		$('.upload-overlay-loading-fix').hide();
		var jres = null;
		try {
		    jres = data;
		    var status = jres.status;
		} catch (Ex) {
		}
		if (!jres) {
		    ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
		    return;
		}	    
		
		if (jres.status == 'ok') {
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
		} else {
		    ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
		}		
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
			showNeedTTAccountMessage();
		    return;
		}
    });

    // submitReview
    $(document).on('click', ".submitReview", function () {
	var user_is_logged = $('.review_main_container').attr('data-logged');
	if (parseInt(user_is_logged) === 0) {
		showNeedTTAccountMessage();
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
	var $hide_user = 0;
	if( $parent.find('input[name=hide_user]').attr('checked') )
	{
	    $hide_user = 1;
	}
	
	$.ajax({
	    url: generateLangURL('/ajax/add_review'),
	    data: {
		id: $('.review_main_container').attr('data-id'),
		data_type: $('.review_main_container').attr('data-type'),
		txt: revTxt,
		hideUser: $hide_user
	    },
	    type: 'post',
	    success: function (data) {
		$('.upload-overlay-loading-fix').hide();
		var jres = null;
		try {
		    jres = data;
		    var status = jres.status;
		} catch (Ex) {
		}
		if (!jres) {
		    ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
		    return;
		}	    

		if (jres.status == 'ok') {
		    $('.rev_description').val('');
		    $('input:checkbox').removeAttr('checked');
		    ttModal.alert(jres.msg, function (btn) {
			if(btn == "ok"){
			    location.reload();
			}
		    }, null, {ok:{value:Translator.trans("ok")}});
		} else {
		    ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
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
                            url: generateLangURL('/ajax/remove_review_page_image'),
			    data: {
				id: picid,
				entity_type: $('.review_main_container').attr('data-type')
			    },
			    type: 'post',
			    success: function (data) {
				$('.upload-overlay-loading-fix').hide();
				var jres = null;
				try {
				    jres = data;
				    var status = jres.status;
				} catch (Ex) {
				}
				if (!jres) {
				    ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
				    return;
				}	    
				ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
				if (jres.status == 'ok') {
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
			url: generateLangURL('/ajax/remove_review'),
			data: {
			    id: log_obj.attr('data-id'),
			    data_type: $('.review_main_container').attr('data-type')
			},
			type: 'post',
			success: function (data) {
			    $('.upload-overlay-loading-fix').hide();
			    var jres = null;
			    try {
				jres = data;
				var status = jres.status;
			    } catch (Ex) {
			    }
			    if (!jres) {
				ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
				return;
			    }	    
			    ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
			    if (jres.status == 'ok') {
				log_obj.remove();
			    }
			}
		    }); 
		}
	    }, null, {ok:{value:Translator.trans("confirm")},cancel:{value:Translator.trans("cancel")}});
	});
    }

    if ($("#uploadReview_Img").length && parseInt($('.review_main_container').attr('data-logged')) != 0) {
	InitUploaderHome('uploadReview_Img', 'uploadPhotoContainer', 15, 0, false);
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
	    centerMode: true,
	    prevArrow: '<button class="slick-prev slick-arrow" type="button"><i class="fas fa-angle-left"></i></button>',
	    nextArrow: '<button class="slick-next slick-arrow" type="button"><i class="fas fa-angle-right"></i></button>'
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
	    url: generateLangURL('/ajax/add_review_page_image'),
	    data: {
		item_id: $('.review_main_container').attr('data-id'),
		filename: pic_link,
		entity_type: $('.review_main_container').attr('data-type')
	    },
	    type: 'post',
	    success: function (data) {
		$('.upload-overlay-loading-fix').hide();
		var jres = null;
		try {
		    jres = data;
		    var status = jres.status;
		} catch (Ex) {
		}
		if (!jres) {
		    ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
		    return;
		}
		location.reload();
	    }
	});
    }
    closeFancyBox();
}