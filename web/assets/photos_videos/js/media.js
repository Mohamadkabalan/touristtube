$(document).ready(function () {
    incrementObjectsViewsMedia(SOCIAL_ENTITY_MEDIA, $('.media-container').attr('data-id') );
    var windowWidth = $(window).width();
    var slidesperView = 0;
    if ($(window).width() > 768) {
        slidesperView = 6;
    } else {
        slidesperView = 1;
    }
    if ($('.s1').length) {
        var swiper1 = new Swiper('.s1', {
            pagination: '.swiper-pagination',
            slidesPerView: slidesperView,
            paginationClickable: true,
            spaceBetween: 0,
//            autoplay: 18000,
            nextButton: '.swiper-button-ss-next',
            prevButton: '.swiper-button-ss-prev',
            speed: 1500,
	    onSlideChangeEnd: function (swiper) {            
		if($(window).width()<=767){
		    var $obj = $('.swiper-wrapper').children().eq(swiper.activeIndex);
		    incrementObjectsViewsMedia(SOCIAL_ENTITY_MEDIA, $obj.attr('data-id') );
		    initializeVideoPlayer($obj);
		}
	    }
        });
        $(window).resize(function () {
            if ($(window).width() > 1199) {
                swiper1.params.slidesPerView = 6;
                swiper1.onResize();
            } else if (($(window).width() > 991) && ($(window).width() < 1200)) {
                swiper1.params.slidesPerView = 5;
                swiper1.onResize();
            } else if (($(window).width() > 768) && ($(window).width() < 992)) {
                swiper1.params.slidesPerView = 3;
                swiper1.onResize();
            } else {
                swiper1.params.slidesPerView = 1;
                swiper1.params.speed = 800;
                swiper1.onResize();
            }
        });
    }
    $(window).resize(function () {
	if ( $(window).width() <= 768 ) {
	    console.log($(window).width());
	    location.reload();
	}
		
        if ($(window).width() != windowWidth) {
            move = 0;
            widthDif = $(".successstory-content-wrapper").width() - $(".successstory-content-item").width() - 20;
            $(".successstory-content-wrapper").css("margin-left", 0);
            $(".successstory-content-item").width($(".successstory-content-container").innerWidth() - 20);
            $(".successstory-content-wrapper").each(function () {
                var wrapperWidth = 0;
                $(this).children(".successstory-content-item").each(function () {
                    wrapperWidth += $(this).width() + 20;
                });
                $(this).width(wrapperWidth);
            });
            $(".successstory-content-next").show();
            $(".successstory-content-prev").hide();
            $(".swiper-container-ss.s1 .swiper-slide").first().trigger("click");

            windowWidth = $(window).width();
        }
    });
    $(".swiper-container-ss.s1 .swiper-slide").click(function () {
        var target = $(this).attr('data-target');
        move = 0;
        widthDif = $("[data-rel=" + target + "]").width() - $(".successstory-content-item").width() - 20;
        $(".successstory-content-wrapper").css("margin-left", 0);
        if ($("[data-rel=" + target + "]").children().length <= 1) {
            $(".successstory-content-next").hide();
            $(".successstory-content-prev").hide();
        } else {
            $(".successstory-content-next").show();
            $(".successstory-content-prev").hide();
        }

        $(".successstory-content-wrapper").hide();
        $("[data-rel=" + target + "]").fadeIn();
    });

    $(".expand a.aexpand").click(function () {
        $(this).next(".insidul").slideToggle();
    });
});

function incrementObjectsViewsMedia(entity_type,data_id) {
    $.ajax({
        url: generateLangURL('/ajax/increment_objects_views?no_cach=' + Math.random()),
        data: {entity_type: entity_type,id:data_id},
        type: 'post'
    });
}
function initializeVideoPlayer($obj){
    var flashmediaclassesOther = $obj.find('.flashmediaclassesOther');
    if(flashmediaclassesOther.length>0){        
        var $this = flashmediaclassesOther;
        var datareslist = $this.attr('data-reslist');
        var datareslistimg = $this.attr('data-reslistimg');
        var datamediaid = $this.attr('data-mediaid');
        if(datareslist!=''){
	    $obj.find('.video_detimgswiper').show();
            regWidth = $obj.find('.video_detimgswiper').width();                     
            regHeight = $obj.find('.video_detimgswiper').height();
	    $obj.find('.video_detimgswiper').hide();
            EmbedFlashJW($this.attr('id'), datareslist, regWidth, regHeight, datareslistimg , datamediaid,0,0,false);
            setTimeout(function () {
                $obj.find('.imgcontainerswipe').hide(); 
                $obj.find('.video_detimgswiper').show(); 
            }, 300);        
            $this.closest('.swiper-slide').on('load', function() { 
                 refreshVideosSwiper( $this.attr('id') );
            });
	    jwplayer($this.attr('id')).on('play', function() {
                var thisplay = $(this);
                $('.jwplayer').each(function(){
                    var $thisjw = $(this);
                    if( thisplay[0].id != $thisjw.attr('id') )jwplayer($thisjw.attr('id')).stop();
                });
            })
        }
    }
}
