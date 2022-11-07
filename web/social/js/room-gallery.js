$(document).ready(function () {
    $('.slider-for').each(function () {
        var unique = $(this).data('unique');
        var sliderFor = '.slider-for-' + unique;
        var nav = '.slider-nav-' + unique;

        slideglob = $(sliderFor).slick({
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
        }

        $(document).on('click', nav + ' .mediathumb', function (e) {
            var indx = $(this).index();
            slideglob[0].slick.slickGoTo(indx);
            $(nav + ' .mediathumb').removeClass('active');
            $(this).addClass('active');
        });

        if ($('.swiper-container-' + unique).length > 0) {
            $('.swiper-container-' + unique).swiper({
                slidesPerView: 'auto',
                centeredSlides: false,
                spaceBetween: 10
            });
        }
    });
});