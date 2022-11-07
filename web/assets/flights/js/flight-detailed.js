$(function () {

    var hash = location.hash.substring(1);
    if (hash && hash == "cancel") {
        $('.non-travelers').hide('slow');
        $('.travelers').removeClass('firstsection');
    }

    $('#cancel').on('click', function (e) {
        $('.non-travelers').hide('slow');
        $('.travelers').removeClass('firstsection');
        e.preventDefault();
    });

    $('.backtodetail').on('click', function (e) {
        $('.non-travelers').show('slow');
        $('.travelers').addClass('firstsection');
        e.preventDefault();
    })

    $('p.cancellalltext, p.cancelreserv').on('click', function (e) {
        var cancel = confirm(Translator.trans('Are you sure to cancel reservation?'));
        var cancel_url = $('#cancel_url').val();
        if (cancel) {
            window.location = cancel_url;
        }
        e.preventDefault();
    });

    var maxheight = Math.max($("#target1 .greyresume").outerHeight(), $("#target2 .greyresume").outerHeight());
    maxheight = Math.max(maxheight, $("#target3 .greyresume").outerHeight());

    $("#right").height(maxheight + 80);
    $(".multi-destination-container .greyresume").height(maxheight + 80);
    $(".slider").hide();
    $(".slider.active").show();

    $('a.tabslide').click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $other = $target.siblings('.active');
        $('a.tabslide').removeClass('active');
        $(this).addClass('active');
        if (!$target.hasClass('active')) {
            $other.each(function (index, self) {
                var $this = $(this);
                $this.removeClass('active').animate({
                    left: $this.width()
                }, 500);
            });

            $target.addClass('active').show().css({
                left: -($target.width())
            }).animate({
                left: 0
            }, 500);
        }
    });
    if ($('a.tabslide').length > 0) {
        $('a.tabslide').first().click();
    }


});