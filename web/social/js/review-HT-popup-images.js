var slideglob;
$(document).ready(function () {
    slideglob = $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 7,
        arrows: false,
        fade: true,
        centerMode: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 7,
        slidesToScroll: 7,
        //appendArrows:'.slider-for',
        asNavFor: '.slider-for',
        dots: false,
        centerMode: true,
        focusOnSelect: true,
		accessibility: true
    });
    $('.slider-nav').on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
        var i = (currentSlide ? currentSlide : 0) + 1;
        if($('.custom_paging').length==0){            
           $('.slider-nav').prepend('<div class="custom_paging"></div>');
           $('.imagescontainer').append('<div class="custom_paging1 visible-xs"></div>');
        }
        $('.custom_paging').html(i + '/' + slick.slideCount);
        $('.custom_paging1').html(i + '/' + slick.slideCount);
    });
    if($('.mediathumbinsideActive').length>0){
        var indx = $('.mediathumbinsideActive').closest('.mediathumb').index();
        $('.mediathumbinsideActive').closest('.mediathumb').click();
    }
});
/*
$(document).keydown(function(ev) {
    if(ev.keyCode == 39) {
        if($('.slick-next').length>0){
            $('.slick-next').click();
        }
    } else if(ev.keyCode == 37) {
        if($('.slick-prev').length>0){
            $('.slick-prev').click();
        }
    } else if(ev.keyCode == 27) {
        parent.closeFancyBox();
    }
});
*/