var currentalbumpagei = 0;
var currentalbumpagev = 0;
var shareFadeoutTimeout;
$(document).ready(function () {
    currentalbumpagei=$('#ThumbViewerContainer').attr('data-pageI');
    currentalbumpagev=$('#ThumbViewerContainer').attr('data-pageV');
    if($(".commentsAll .albumdesc").length>0){
        var text=$(".commentsAll .albumdesc").html();
        var text1=text.replace(/<br \/>/g, "\n");
        var text2=text1.replace(/<br\/>/g, "\n");
        text=text2.replace(/<br>/g, "\n");
        var aboutstr = "<p>"+text.split("\n").join("</p>\r\n<p>")+"</p>";
        $(".commentsAll .albumdesc").html(aboutstr);
    }
    $('.commentsAll .albumdesc p').each(function() {
        if(isRTL($(this).text())){
            $(this).addClass('rtl');
        } else{
             $(this).addClass('ltr');
        }
    });
    $(".social_data_all").attr('data-id', $('.image_cont').attr("data-id"));
    incrementObjectsViews(  $(".social_data_all").attr('data-type') , $(".social_data_all").attr("data-id") );
//    if ($(".social_data_all").find('#rates').length > 0) addRaty($(".social_data_all"));
    initSocialActions();
//    if ($(".social_data_all").find('#likes').length > 0) initLikes($(".social_data_all"));
//    if ($(".social_data_all").find('#rates').length > 0) initRates($(".social_data_all"));
    if (parseInt($('.social_data_all').attr('data-enable')) == 1 || parseInt(is_owner) == 1) {
        $('.btn_enabled').show();
//        if ($(".social_data_all").find('#comments').length > 0) initComments($(".social_data_all"));
//        if ($(".social_data_all").find('#shares').length > 0) initShares($(".social_data_all"));
    } else {
        $('.btn_enabled').hide();
    }
    if ($('#ThumbViewer li').length < 5) {
        $('#ThumbNext').addClass('inactive');
        $('#ThumbPrev').addClass('inactive');
    }
    $(document).on('click', ".embed_link", function () {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
            $('.embed_content').show();
        } else {
            $(this).removeClass('active');
            $('.embed_content').hide();
        }
    });
    $(document).on('click', '.add_friend_profile', function () {
        var id = $(this).attr('data-rel');
        var $this = $(this);
        var data_user = $(this).attr('data-user');
        var data_status = $(this).attr('data-status');

        $.ajax({
            url: ReturnLink('/ajax/tuber_friend_request.php'),
            data: {id: id},
            type: 'post',
            success: function (data) {
                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {

                }
                if (!jres) {
                    TTAlert({
                        msg: t("Couldn't Process Request. Please try again later."),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }


                if(data_status!="2"){
                    TTAlert({
                        msg: sprintf(t('friendship request sent to %s'), [data_user]),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
                $(".add_friend_profile").removeClass('displayMe');
                $(".buttonTuber_seperator").hide();
//                $(".remove_friend_profile").addClass('displayMe');
            }
        });

    });
    $('.follow_friend').each(function () {
        $(this).click(function () {
            var id = $(this).attr('data-rel');
            var $this = $(this);
            var data_user = $(this).attr('data-user');

            $.ajax({
                url: ReturnLink('/ajax/subscribe.php'),
                data: {id: id},
                type: 'post',
                success: function (data) {
                    var jres = null;
                    try {
                        jres = $.parseJSON(data);
                    } catch (Ex) {

                    }
                    if (!jres) {
                        TTAlert({
                            msg: t("Your session timed out. Please login."),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }

                    TTAlert({
                        msg: sprintf( t('you are now following %s'), [data_user]),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    $(".follow_friend").removeClass('displayMe');
                    $(".unfollow_friend").addClass('displayMe');
                }
            });

        });
    });
    $('.privacy_icon_glob').mouseover(function () {
        var diffx = $('#InsideContainer').offset().left + 255;
        var diffy = $('#InsideContainer').offset().top + 22;
        var posxx = $(this).offset().left - diffx;
        var posyy = $(this).offset().top - diffy;

        $('.privacybuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.privacybuttonsOver').css('left', posxx + 'px');
        $('.privacybuttonsOver').css('top', posyy + 'px');
        $('.privacybuttonsOver').stop().show();
    });
    $('.privacy_icon_glob').mouseout(function () {
        $('.privacybuttonsOver').hide();
    });
    
    $(document).on('click', ".MediaButton_data_share", function () {
        if ($('.share_link_holder.simplemode').css('display') != "none") {
            $('.share_link_holder.simplemode').hide();
            $('.mediabuttonsOver').removeClass('inactive');
            $(this).removeClass('active');
        } else {
            $('.mediabuttonsOver').addClass('inactive');
            $(this).addClass('active');
            $('.share_link_holder.simplemode').show();
            $('.mediabuttonsOver').hide();
            
            shareFadeoutTimeout = setTimeout(function () {
                $('.share_link_holder.simplemode').hide();
                $('.mediabuttonsOver').removeClass('inactive');
                $('.MediaButton_data_share').removeClass('active');
            }, 1500);

            $('.share_link_holder.simplemode').unbind('mouseenter mouseleave').hover(function () {
                clearTimeout(shareFadeoutTimeout);
                $('.share_link_holder.simplemode').stop(true, true);
                $('.share_link_holder.simplemode').show();
            }, function () {
                shareFadeoutTimeout = setTimeout(function () {
                    $('.share_link_holder.simplemode').hide();
                    $('.mediabuttonsOver').removeClass('inactive');
                    $('.MediaButton_data_share').removeClass('active');
                }, 500);
            });
        }
    });
    
    $(document).on('click', "#ThumbNext_albumI", function () {
        if (!$(this).hasClass('inactive')) {
            currentalbumpagei++;
            loadMediaAlbumRelated($(this),currentalbumpagei,'i');
        }
    });
    $(document).on('click', "#ThumbPrev_albumI", function () {
        if (!$(this).hasClass('inactive')) {
            currentalbumpagei--;
            loadMediaAlbumRelated($(this),currentalbumpagei,'i');
        }
    });
    $(document).on('click', "#ThumbNext_albumV", function () {
        if (!$(this).hasClass('inactive')) {
            currentalbumpagev++;
            loadMediaAlbumRelated($(this),currentalbumpagev,'v');
        }
    });
    $(document).on('click', "#ThumbPrev_albumV", function () {
        if (!$(this).hasClass('inactive')) {
            currentalbumpagev--;
            loadMediaAlbumRelated($(this),currentalbumpagev,'v');
        }
    });

    $(document).on('click', "#Commentsmore", function () {
        if (parseInt($('.social_data_all').attr('data-enable')) == 1 || parseInt(is_owner) == 1) {
            $('#comments').click();
            $("html, body").animate({scrollTop: $('#comments').offset().top}, 1);
        }
    });
    if ($("#report_button_log_viewer_album").length > 0) {
        initReportFunctions($("#report_button_log_viewer_album"));
    }
    if ($("#report_button_log_viewer_photo").length > 0) {
        initReportFunctions($("#report_button_log_viewer_photo"));
    }
    if ($("#report_button_log_viewer_video").length > 0) {
        initReportFunctions($("#report_button_log_viewer_video"));
    }
    $('.edit_media_social').each(function (index, element) {
        var $This = $(this);

        var vid = $This.attr('data-id');
        var channelid = $This.attr('data-channelid');

        $This.attr("href", ReturnLink('parts/user-viewphoto.php?id=' + vid + '&channelid=' + channelid));

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
    $('.edit_media_social_album').each(function (index, element) {
        var $This = $(this);

        var vid = $This.attr('data-id');
        var channelid = $This.attr('data-channelid');

        $This.attr("href", ReturnLink('parts/user-viewalbum.php?id=' + vid + '&channelid=' + channelid));

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

    $('#description').click();
    setTimeout(function () {
        if (parseInt(txt_edit_init) == 1) {
            $('#edit_media_buts').click();
        } else if (parseInt(txt_like_init) == 1) {
            $('#likes').click();
        } else if (parseInt(txt_comment_init) == 1) {
            $('#comments').click();
        } else if (parseInt(txt_share_init) == 1) {
            $('#shares').click();
        } else if (parseInt(txt_rate_init) == 1) {
            $('#rates').click();
        } else if (parseInt(txt_report_init) == 1) {
            $('.log_top_button').click();
        } else {
            $('#description').click();
        }
    }, 500);
    /*unfollow button*/
    $(document).on('click', '.unfollow_friend', function () {
        var curobj = $(this);
        var target = curobj;
        var id = target.attr('data-rel');
        var myname = "" + target.attr('data-name');

        TTAlert({
            msg: sprintf(t('confirm to unfollow %s'), [myname]),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function (data) {
                if (data) {
                    $('.chat-overlay-loading-fix').show();
                    $.ajax({
                        url: ReturnLink('/ajax/profile_feed_unfollow.php'),
                        data: {feed_user_id: id},
                        type: 'post',
                        success: function (data) {
                            var jres = null;
                            try {
                                jres = $.parseJSON(data);
                            } catch (Ex) {

                            }
                            if (!jres) {
                                TTAlert({
                                    msg: t("Couldn't Process Request. Please try again later."),
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                                });
                                return;
                            }
                            $(".unfollow_friend").removeClass('displayMe');
                            $(".follow_friend").addClass('displayMe');
                        }
                    });
                }
            }
        });
    });
    /*unfollow button*/
    /*unfriend button*/
    $(document).on('click', '.remove_friend_profile', function () {
        var curobjclk = $(this);
        var target = curobjclk;
        var myname = "" + target.attr('data-user');
        var confirmstr = sprintf(t("are you sure you want to remove permanently %s"), [myname]);

        TTAlert({
            msg: confirmstr,
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function (data) {
                if (data) {
                    contextRejectFriend(target.attr('data-rel'));
                }
            }
        });
    });
    $(".userAvatarLink").each(function (index, element) {
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
});
function contextRejectFriend(id) {
    $('.chat-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_rejectprofilefriend.php'), {fid: id}, function (data) {
        $('.chat-overlay-loading-fix').hide();
        $(".remove_friend_profile").removeClass('displayMe');
        $(".add_friend_profile").addClass('displayMe');
    });
}
function ReloadComments() {
    $.ajax({
        url: ReturnLink('/parts/video_comments.php?type=' + COMMENT_TYPE),
        type: 'post',
        data: {current_id: ORIGINAL_MEDIA_ID, entity_type: ORIGINAL_ENTITY_TYPE, page: 0},
        success: function (data) {
            $('#CommentList').html(data);
        }
    });
}
function loadMediaAlbumRelated(obj,_page,_type) {
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/album_pic_viewer.php'), {page: _page, id: $('#ThumbViewerContainer').attr('data-id'),type:_type}, function (data) {
        var ret = null;
        try {
            ret = $.parseJSON(data);
        } catch (Ex) {
            $('.upload-overlay-loading-fix').hide();
            return;
        }
        if(_type=='i'){
            $('.album_subcontainer1 .album_content').html(ret.data1);
            if ( parseInt(ret.count) == 0 ) {
                $('#ThumbNext_albumI').addClass('inactive');
            }else{
                $('#ThumbNext_albumI').removeClass('inactive');
            }
            if(_page>0){
                 $('#ThumbPrev_albumI').removeClass('inactive');
            }else{
                 $('#ThumbPrev_albumI').addClass('inactive');
            }
        }else{
            $('.album_subcontainer2 .album_content').html(ret.data2);
            if ( parseInt(ret.count) == 0 ) {
                $('#ThumbNext_albumV').addClass('inactive');
            }else{
                $('#ThumbNext_albumV').removeClass('inactive');
            }
            if(_page>0){
                 $('#ThumbPrev_albumV').removeClass('inactive');
            }else{
                 $('#ThumbPrev_albumV').addClass('inactive');
            }
        }        
        $('.upload-overlay-loading-fix').hide();
    });
}
function closeFancy() {
    $('.fancybox-close').click();
}
function addMediaUpload(link_up) {
    closeFancy();
    document.location.href = link_up;
}
function addMediaAlbumUpload(link_up) {
    closeFancy();
    document.location.href = link_up;
}
function addMediaRemove(link_media) {
    closeFancy();
    document.location.href = link_media;
}

$(document).keydown(function(ev) { 
    if($('.photo_next_big').length>0 || $('.photo_prev_big').length>0){
        if( $(".glob_container").css('display')=='none' ){        
            if(ev.keyCode == 39) {
                if($('.photo_next_big').length>0){
                    var mhref = $('.photo_next_big').parent().attr('href');
                    document.location.href = mhref;
                }
            } else if(ev.keyCode == 37) {
                if($('.photo_prev_big').length>0){
                    var mhref = $('.photo_prev_big').parent().attr('href');
                    document.location.href = mhref;
                }
            }
        }
    }
});