$(document).ready(function () {
    prepareSliders();
    ttModal = window.getTTModal("myModalZ", {});
    if ($(".more_container").length) {
	toggleReviewList();
    }

    if ($('.specificRate .eval_options_glob').length > 0) {
	$(document).on('click', ".specificRate .eval_options_glob", function () {
	    var $index = $(this).index();
	    if (parseInt($('.hotelsContainer').attr('data-logged')) === 0) {
		ttModal.confirm(Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink(('/register')) + '">' + Translator.trans('TT account') + '</a> ' 
			+ Translator.trans('in order to evaluate.'), function (btn) {
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
		data: {entity_id: $('.hotelsContainer').attr('data-id'), entity_type: $parents.attr('data-type'), score: data_value, globchannelid: null},
		ret: 'json',
		callback: function (resp) {
		    if (resp.status == 'error') {
			ttModal.alert(resp.msg, null, null, {ok:{value:"ok"}});
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
    }
    // view reviews links to anchor to reviews list
    if ($('#viewReviews').length > 0) {
	$("#viewReviews").on('click', function (event) {
	    if (this.hash !== "") {
		event.preventDefault();
		var hash = this.hash;

		$('#reviewListSection').animate({
		    scrollTop: $(hash).offset().top
		}, 800, function () {
		    window.location.hash = hash;
		});
	    }
	});
    }

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
    if ($("#uploadReview_Img").length) {
	$(document).on('click', "#uploadReview_Img", function () {
	    var user_is_logged = $('.hotelsContainer').attr('data-logged');
	    if (parseInt(user_is_logged) === 0) {
		ttModal.confirm(Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink(('/register')) + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to evaluate.'), function (btn) {
                    if(btn == "ok"){
						window.location.href = ReturnLink(('/register'));
                    }
                }, null, {ok:{value:Translator.trans("register")},cancel:{value:Translator.trans("cancel")}});
		return;
	    }
	});
    }

    // submitReview
    if ($(".submitReview").length) {
	$(document).on('click', ".submitReview", function () {
	    var user_is_logged = $('.hotelsContainer').attr('data-logged');
	    if (parseInt(user_is_logged) === 0) {
		ttModal.confirm(Translator.trans('You need to have a') + ' <a class="black_link" href="' + ReturnLink(('/register')) + '">' + Translator.trans('TT account') + '</a> ' + Translator.trans('in order to add reviews.'), function (btn) {
                    if(btn == "ok"){
						window.location.href = ReturnLink(('/register'));
                    }
                }, null, {ok:{value:Translator.trans("register")},cancel:{value:Translator.trans("cancel")}});
		return;
	    }
	    var revTxt = $('textarea[name="reviewDescription"]').val();
	    var revThemes = new Array();
	    var revDate = $('.rev_select').val();

	    var revNear = new Array();
	    if (revTxt === '') {
		ttModal.alert(Translator.trans('Your review cannot be empty !'), null, null, {ok:{value:"ok"}});
		return;
	    }

	    if ($('input[name=certifyBox]').attr('checked')) {
	    } else {
		ttModal.alert(Translator.trans('Your have to certify that this review is based on your own experience !'), null, null, {ok:{value:"ok"}});
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
			ttModal.alert(ret.msg, null, null, {ok:{value:"ok"}});
		    } else {
			ttModal.confirm(ret.msg, function (btn) {
			    if(btn == "ok"){
				location.reload();
			    }
			}, null, {ok:{value:Translator.trans("ok")}});
		    }
		}
	    });
	});
    }

    //photoremove
    if ($(".photoremove").length) {
	$(document).on('click', ".photoremove", function (e) {
	    var $this = $(this).closest('.photoitems');
	    var picid = $this.attr("data-id");
		ttModal.confirm(Translator.trans("are you sure you want to remove permanently this image?"), function (btn) {
                    if(btn == "ok"){
                        $.ajax({
                            url: generateLangURL('/ajax/remove_discover_image.php','ajax'),
			    data: {
				id: picid,
				entity_type: $('.hotelsContainer').attr('data-type')
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
				    $this.remove();
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

    if ($("#uploadReview_Img").length && parseInt($('.hotelsContainer').attr('data-logged')) != 0) {
	InitChannelUploaderHome('uploadReview_Img', 'uploadPhotoContainer', 15, 0, true);
    }

    $(document).on('change', ".place", function () {
	var $this = $(this).closest('.radioOpt');
	setSelectedRadio($this);
    });

    $('.place').each(function () {
	var $this = $(this);
	var $id = $this.attr('id');
	if (document.getElementById($id).checked) {
	    var $thispr = $this.closest('.radioOpt');
	    setSelectedRadio($thispr);
	}
    });
    //$('#Hotel.place').change();
    autocompleteReviewsNew($('.nameOfPlace'));

    $("#searchButton").click(function () {
	var link_page = $("#reviewUrl").val();
	document.location.href = link_page;
    });

});
function toggleReviewList() {
    listCnt = $(".reviewList").size();
    if (listCnt <= 3) {
	start = listCnt;
	$(".more_txt").hide();
	$(".less_txt").hide();
    } else {
	start = 3;
    }

    $(".reviewList").slice(0, start).show();
    $('.more_txt').click(function () {
	start = (start + 3 <= listCnt) ? start + 3 : listCnt;
	$(".reviewList").slice(0, start).show();
	if (start == listCnt) {
	    $(".more_txt").hide();
	    $(".less_txt").show();
	}
    });

    $('.less_txt').click(function () {
	end = listCnt;
	start = (listCnt - 3 <= start) ? listCnt - 3 : start;
	$(".reviewList").slice(0, start).hide();
	if (start <= 3) {
	    $(".more_txt").show();
	    $(".less_txt").hide();
	}
    });
}

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

function setSelectedRadio($this) {
    var dataType = $this.attr('data-type');
    var dataStr = $this.attr('data-str');
    $('.nameOfPlace').attr('data-type', dataType);
    $('.searchDesc').attr('placeholder', dataStr);
}
function autocompleteReviewsNew(auto_object) {
    if (auto_object.length == 0)
	return;
    var email_container_privacy = auto_object.parent();
    auto_object.autocomplete({
	minLength: minSearchLength,
	appendTo: email_container_privacy,
	delay: 500,
	search: function (event, ui) {
	    var type = auto_object.attr('data-type');
	    var append = "&t=" + type;
	    if (auto_object.hasClass("nameOfPlace") && auto_object.hasClass("ui-autocomplete-input")) {
		auto_object.autocomplete('option', 'source', generateLangURL('/ajax/review_autocomplete?' + append));
	    } else {
		auto_object.autocomplete('option', 'source', ReturnLink('/ajax/review-autocomplete.php') + append);
	    }
	},
	focus: function (event, ui) {
	    return false;
	},
	select: function (event, ui) {
	    $('.searchDesc').val(ui.item.value_display);
	    $("#reviewUrl").val(ui.item.link);
	    event.preventDefault();
	}
    }).keydown(function (event) {
	var code = (event.keyCode ? event.keyCode : event.which);
	if (code === 13 || code === 9) {
	    if (AutocompleteCitiesExists($citynameaccent.val()) === null) {
		event.preventDefault();
		return;
	    }
	    if (code === 13) {
		$(this).blur();
	    }
	} else {
	    auto_object.attr('data-code', '');
	    auto_object.attr('data-hotel', '');
	    auto_object.attr('data-city', '');
	    auto_object.attr('data-state-code', '');
	}
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
	return $("<li></li>")
		.data("item.autocomplete", item)
		.append("<a>" + item.value + "</a>")
		.appendTo(ul);
    }
}

function updateImage(pic_link, _type) {
    if (_type == "uploadReview_Img") {
	$.ajax({
	    url: ReturnLink(('/ajax/add_discover_image.php')),
	    data: {
		item_id: $('.hotelsContainer').attr('data-id'),
		filename: pic_link,
		entity_type: $('.hotelsContainer').attr('data-type')
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
		    ttModal.alert(ret.msg, null, null, {ok:{value:"ok"}});
		    return;
		}
		$('.yourPhotosContainer').prepend('<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3 photoitems" data-id="' + ret.id + '"><div class="photoremove">X remove</div><img src="' + ReturnLink('media/discover/thumb/' + pic_link) + '" class="width100"/></div>');
	    }
	});
    }
    closeFancyBox();
}
