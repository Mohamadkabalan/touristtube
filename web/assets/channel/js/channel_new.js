$(document).ready(function () {
    var windowWidth = $(window).width();
    var slidesperView = 0;
    if ($(window).width() > 768) {
        slidesperView = 4;
    } else {
        slidesperView = 1;
    }
    if ($('.s1').length) {
        var swiper1 = new Swiper('.s1', {
            slidesPerView: slidesperView,
            spaceBetween: 0,
//            autoplay: 18000,
        });
        $(window).resize(function () {
            if ($(window).width() > 1199) {
                swiper1.params.slidesPerView = 4;
                swiper1.onResize();
            } else if (($(window).width() > 991) && ($(window).width() < 1200)) {
                swiper1.params.slidesPerView = 3;
                swiper1.onResize();
            } else if (($(window).width() > 768) && ($(window).width() < 992)) {
                swiper1.params.slidesPerView = 2;
                swiper1.onResize();
            } else {
                swiper1.params.slidesPerView = 1;
                swiper1.params.speed = 800;
                swiper1.onResize();
            }
        });
    }
});