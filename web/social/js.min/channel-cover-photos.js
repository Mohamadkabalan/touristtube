var currentpage = 0;
var fr_txt = "";
var to_txt = "";

$(document).ready(function() {
    InitDocument();
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
            $('.MediaList ul').html('');
            getMediaDataRelated(0);
        }
    });
    $(".loadmoreprofileimages").click(function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        currentpage++;
        getMediaDataRelated(0);
    });
    $(document).on('click', "#MediaVideo.MediaList ul li .clsimg", function() {
        var data_type = $(this).closest('.imgbk_butons').attr('data-type');
        var data_id = $(this).closest('.imgbk_butons').attr('data-id');
        var obj = $(this).closest('li');
        TTAlert({
            msg: t('Are you sure you want to delete your profile image?'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data){
                if(data){
                    $('.upload-overlay-loading-fix-file').show();
                    $.ajax({
                        url: ReturnLink('/ajax/ajax_delete_profileimages.php'),
                        data: {
                            data_type: data_type,
                            id: data_id
                        },
                        type: 'post',
                        success: function (data) {
                            var ret = null;
                            try {
                                ret = $.parseJSON(data);
                            } catch (Ex) {
                                $('.upload-overlay-loading-fix-file').hide();
                                return;
                            }
                            if (ret.error == 1) {
                                $('.upload-overlay-loading-fix-file').hide();
                                TTAlert({
                                    msg: ret.msg,
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                                });
                            } else{
                                obj.remove();
                                getMediaDataRelated(1);
                            }
                        }
                    });
                }
            }
        });
    });
    
    $(document).on('click', "#MediaVideo.MediaList ul li .imgbk .albumimg:not(.albumimgActive)", function() {
        var curbut = $(this).closest('.imgbk_butons');
        $('.upload-overlay-loading-fix-file').show();

        var data_id = curbut.attr("data-id");
        var data_type = curbut.attr('data-type');
        $.post(ReturnLink('/ajax/ajax_updateprofileimage.php'), {data_type: data_type,id: data_id}, function(data) {
            var ret;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix-file').hide();
                return;
            }

            if (ret.error==1) {
                $('.upload-overlay-loading-fix-file').hide();
                TTAlert({
                    msg: ret.msg,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
            document.location.reload();
        });
    });
});

function addCalTo(cals) {
    if (new Date($('#fromtxt').attr('data-cal')) > new Date($('#totxt').attr('data-cal'))) {
        $('#totxt').attr('data-cal', $('#fromtxt').attr('data-cal'));
        $('#totxt').html($('#fromtxt').html());
    }
}
function getMediaDataRelated(one_object) {
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_loadmorecoverimages.php'), {channelid: channelGlobalID(), page: currentpage, frtxt: fr_txt, totxt: to_txt, one_object: one_object}, function(data) {
        $('.MediaList ul').append(data);
        InitDocument();
        $('.upload-overlay-loading-fix').hide();
    });
}
function InitDocument() {
    $(document).on('mouseover', ".imgbk_butons .imgbk_buts:not(.albumimgActive)", function(){
        var diffx = $('#MiddleTop').offset().left + 250;
        var diffy = $('#MiddleTop').offset().top + 22;
        var posxx = $(this).offset().left - diffx;
        var posyy = $(this).offset().top - diffy;
        $('.profileimagesbuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.profileimagesbuttonsOver').css('left', posxx + 'px');
        $('.profileimagesbuttonsOver').css('top', posyy + 'px');
        $('.profileimagesbuttonsOver').stop().show();
    });
    $(".imgbk_butons .imgbk_buts").mouseout(function() {
        $('.profileimagesbuttonsOver').hide();
    });
    $(".profileimageLink").each(function (index, element) {
        var $This = $(this);
            
        var vid = $This.attr('data-id');
        var type = $This.attr('data-type');

        $This.attr("href", ReturnLink('parts/user-viewprofileimage.php?id=' + vid + '&type=' + type));

        $This.fancybox({
            "padding": 0,
            "margin": 0,
            "width": '883',
            "height": '604',
            "transitionIn": "none",
            "transitionOut": "none",
            "autoSize": false,
            "scrolling": 'no',
            "type": "iframe"
        });

    });
}