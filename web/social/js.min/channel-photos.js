var currentpage = 0;
var old_currentpage = 0;
var fr_txt = "";
var to_txt = "";


function InitDocument() {
    $('.closeSign.hide').mouseover(function () {
        var $parents = $('.evContainer2Over').parent();
        var posxx = $(this).offset().left - $parents.offset().left - 253;
        var posyy = $(this).offset().top - $parents.offset().top - 23;
        $('.evContainer2Over .ProfileHeaderOverin').html('hide');
        $('.evContainer2Over').css('left', posxx + 'px');
        $('.evContainer2Over').css('top', posyy + 'px');
        $('.evContainer2Over').stop().show();
    });
    $('.closeSign.hide').mouseout(function () {
        $('.evContainer2Over').hide();
    });
    $(".image").each(function() {
        var $this = $(this);
        var hide = $this.find(".hide");
        $this
                .mouseenter(function() {
                    hide.removeClass("hide");
                    hide.addClass("show");
                })
                .mouseleave(function() {
                    hide.removeClass("show");
                    hide.addClass("hide");
                })
    });

    $(".plusSign").each(function() {
        var $this = $(this);
        var popUp = $this.parent().find(".popUp");

        $this.click(function() {

            var thisIndex = $this.parent().index();
            var popclass = 'popUpLeft';
            if (((thisIndex + 1) % 5) == 0 || ((thisIndex + 1) % 5) == 4) {
                popclass = 'popUpRight';
            }
            popUp.removeClass('popUpLeft');
            popUp.removeClass('popUpRight');
            popUp.addClass(popclass);

            $(".popUp").hide();
            $(".plusSign").show();

            popUp.show();
            $this.hide();
            objectSelected = $this.parent();
        });
    });

    $(".minus").each(function() {
        var $this = $(this);
        var popUp = $this.parent();
        var plusSign = $this.parent().parent().find(".plusSign");
        $this.click(function() {
            objectSelected = "";
            popUp.hide();
            plusSign.show();
        });
    });
    initPhotoFancy();
}


$(document).ready(function() {
    InitDocument();

    $('.' + pagetype + 'menu').addClass('active');

    var menushown = false;
    var moreshown = false;
    if ($("#fromtxt").length > 0) {
        Calendar.setup({
            inputField: "fromtxt",
                noScroll  	 : true,
            trigger: "frombutcontainer",
            align: "B",
            onSelect: function() {
                var date = Calendar.intToDate(this.selection.get());
                TO_CAL.args.min = date;
                TO_CAL.redraw();
                $('#fromtxt').attr('data-cal', Calendar.printDate(date, "%Y-%m-%d"));

                addCalTo(this);
                this.hide();
            },
            disabled: function(date) {
                var d = new Date();
                d.setHours(12, 30, 0, 0);
                return (date > d);
            },
            dateFormat: "%d / %m / %Y"
        });
        TO_CAL = Calendar.setup({
            inputField: "totxt",
                noScroll  	 : true,
            trigger: "tobutcontainer",
            align: "B",
            onSelect: function() {
                var date = Calendar.intToDate(this.selection.get());
                $('#totxt').attr('data-cal', Calendar.printDate(date, "%Y-%m-%d"));

                addCalTo(this);
                this.hide();
            },
            disabled: function(date) {
                var d = new Date();
                d.setHours(12, 30, 0, 0);
                return (date > d);
            },
            dateFormat: "%d / %m / %Y"
        });

    }
    $("#searchCalendarbut").click(function() {
        if ($('#fromtxt').html() != '' || $('#totxt').html() != '') {
            fr_txt = "" + $('#fromtxt').attr('data-cal');
            to_txt = "" + $('#totxt').attr('data-cal');

            currentpage = 0;
            old_currentpage = 0;
            $('.MediaList ul').html('');
            getMediaDataRelated();
        }
    });
    $(".loadmoremedia").click(function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        currentpage++;
        getMediaDataRelated();
    });
    $(document).on('click', ".closeSign", function() {
        var $this = $(this);
        var thisLi = $this.closest("li");
        thisLi.remove();
        one_object = 1;
        $('.evContainer2Over').hide();
        getMediaDataRelated();
    });
});

function addCalTo(cals) {
    if (new Date($('#fromtxt').attr('data-cal')) > new Date($('#totxt').attr('data-cal'))) {
        $('#totxt').attr('data-cal', $('#fromtxt').attr('data-cal'));
        $('#totxt').html($('#fromtxt').html());
    }
}
function getMediaDataRelated() {
    var oldnumber = parseInt($(".MediaList").attr("data-number"));
    var $skip = (oldnumber - (old_currentpage + 1) * 30);
    if (oldnumber < 30) {
        $skip = 0;
    }
    if (one_object == 1 && oldnumber < 30) {
        one_object = 0;
        $('.upload-overlay-loading-fix').hide();
        return;
    }
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_loadmoremedia.php'), {txt_srch_init: txt_srch_init, globchannelid: globchannelid, currentpage: currentpage, type: pagetype, frtxt: fr_txt, totxt: to_txt, one_object: one_object, skip: $skip}, function(data) {
        if (data != false) {
            $('.MediaList ul').append(data);
            var currPageStatus = $('.MediaList .currPageStatus');
            if (("" + currPageStatus.attr('data-value')) == "0") {
                $(".loadmoremedia").hide();
            } else {
                $(".loadmoremedia").show();
            }
            $('.MediaCont .TTL span').html('(' + currPageStatus.attr('data-count') + ')');
            currPageStatus.remove();
            InitDocument();
            $skip = $skip + one_object;
            $(".MediaList").attr("data-number", ($(".MediaList ul li").length) + $skip);
        } else {
            $(".loadmoremedia").hide();
        }
        one_object = 0;
        old_currentpage = currentpage;
        $('.upload-overlay-loading-fix').hide();
    });
}
