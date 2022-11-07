$(document).ready(function () {
    if (parseInt($('#uploadHotelsReviewsContainer').attr('data-logged')) != 0) {
	InitUploaderHome('uploadHotelsReviews', 'uploadHotelsReviewsContainer', 15, 0, false);
    }

    $(document).on('click', '.close_container', function () {
	$(".hotel_search_input_container").slideToggle('fast');
    });
    $(document).on('click', '.bookHotelButton', function () {
	$(".hotel_search_input_container").slideToggle('fast');
    });

    $(".review_tab_list li a").click(function (event) {
	event.preventDefault();
	$(".review_tab_list li a").removeClass('active');
	$(".review_tab_list li .image-div").removeClass('active');
	$(this).addClass('active');
	$(this).next('div.image-div').addClass('active');
	var $id = $(this).attr('id');
	var $sub = $('#sub_' + $id);
	$('.contenttabs').hide();
	$sub.show();
    });

    $(document).on('click', ".photoremove", function (e) {
	var $this = $(this).closest('.photoitems');
	var picid = $(this).attr("data-id");
	TTAlert({
	    msg: Translator.trans('Are you sure you want to remove permanently this image?'),
	    type: 'action',
	    btn1: Translator.trans('Cancel'),
	    btn2: Translator.trans('Confirm'),
	    btn2Callback: function (data) {
		if (data) {
		    $('.upload-overlay-loading-fix').show();
		    $.ajax({
			url: generateLangURL('/ajax/remove/hotel/images'),
			data: {id: picid},
			type: 'post',
			success: function (res) {
			    $('.upload-overlay-loading-fix').hide();
			    if (res.status == 'error') {
				showErrorMsg(res.msg);
			    } else {
				$this.remove();
			    }
			}
		    });
		}
	    }
	});
    });
    initHotelsReviewMap();
    $(window).resize(function () {
	initHotelsReviewMap();
    });
});

function initHotelsReviewMap() {
    if ($('.reviewratebut').length > 0 && parseInt($('#uploadHotelsReviewsContainer').attr('data-logged')) != 0) {
	var pagedimensions = parent.window.returnIframeDimensions();
	$(".reviewratebut").unbind('click');
	$(".reviewratebut").bind("click", function (event) {
	    event.preventDefault();
	    var $href = $(this).attr('href');
	    if (pagedimensions[0] > 1000) {
		pagedimensions[0] = 1000;
	    }
	    if (pagedimensions[0] < 768) {
		pagedimensions[0] = '100%';
	    }

	    $.fancybox({
		width: pagedimensions[0],
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

    if ($('.imagefirstbut').length > 0) {
	var pagedimensions = parent.window.returnIframeDimensions();
	$(".imagefirstbut").unbind('click');
	$(".imagefirstbut").bind("click", function (event) {
	    event.preventDefault();
	    var $href = $(this).attr('href');
	    if (pagedimensions[0] > 1148) {
		pagedimensions[0] = 1148;
	    }

	    if (pagedimensions[0] < 768) {
		pagedimensions[0] = '100%';
	    }

	    $.fancybox({
		width: pagedimensions[0],
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

function updateImage(pic_link, _type, InsertId) {
    if (_type == "uploadHotelsReviews") {
	$('.galPhotosContainer').prepend('<div class="col-xs-4 new_smal_padding photoitems"><div class="photoremove" data-id="' + InsertId + '">remove</div><a class="imagefirstbut" title="" href="/review-HT-popup-images?id=' + $('#uploadHotelsReviewsContainer').attr('data-id') + '&pic=' + InsertId + '" rel="nofollow"><img src="' + generateMediaURL('/media/hotels/' + $('#uploadHotelsReviewsContainer').attr('data-id') + '/hotels50HS13472_' + pic_link) + '" class="width-100 imagefirstdiv"></a></div>');
	initHotelsReviewMap();
    }
    closeFancyBox();
}

function closeFancyBoxReview() {
    closeFancyBox();
}
