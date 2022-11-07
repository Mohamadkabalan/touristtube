var jsp_api = null;

$(document).ready(function () {

    if (jQuery.isFunction(jQuery.fn.selectbox))
        $(".select2").selectbox();

    $(document).on('click', "#EmailOptions .uploadinfocheckbox", function () {
        var curob = $(this);
        if (curob.hasClass('active')) {
            curob.removeClass('active');
        } else {
            curob.addClass('active');
        }
    });

    $(document).on('mouseover', '.ProfileHeader', function () {
        var posxx = $(this).offset().left - $('#ProfileHeaderInternal').offset().left - 245;
        $('.ProfileHeaderOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.ProfileHeaderOver').css('left', posxx + 'px');
        $('.ProfileHeaderOver').css('top', '34px');
        $('.ProfileHeaderOver').stop().show();
    });
    $(document).on('mouseout', '.ProfileHeader', function () {
        $('.ProfileHeaderOver').hide();
    });
    $(document).on('mouseover', '.fr_request_data .fr_request_data_but', function () {
        var posxx = $(this).offset().left - $('#ProfileHeaderInternal').offset().left - 254;
        var posyy = $(this).offset().top - $('#ProfileHeaderInternal').offset().top - 27;
        $('.ProfileFriendOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.ProfileFriendOver').css('left', posxx + 'px');
        $('.ProfileFriendOver').css('top', posyy + 'px');
        $('.ProfileFriendOver').show();
    });
    $(document).on('mouseout', '.fr_request_data .fr_request_data_but', function () {
        $('.ProfileFriendOver').hide();
    });
    $(document).on('click', ".fr_request_data_but1", function () {
        var target = $(this).parent();
        var myname = "" + target.attr('data-name')[0];
        if (isAlpha(myname)) {
            var alpobj = $('#searchfriendbutalphabetcontainer #alphabetcnt ul').find('#' + myname);
        } else {
            var alpobj = $('#searchfriendbutalphabetcontainer #alphabetcnt ul').find('#dyese');
        }
        contextAccept(target, alpobj);
    });

    $(document).on('click', ".fr_request_data_but2", function () {
        var target = $(this).parent();
        contextIgnore(target);
    });

    $(document).on('click', ".fr_request_data_but3", function () {
        var target = $(this).parent();
        contextReject(target);
    });

    $(document).on('mouseover', ".PofileHeaderPopUnderFriends", function () {
        $('.PorifleFrndReqClass').hide();
    });
    $(document).on('mouseout', ".PofileHeaderPopUnderFriends", function () {
        $('.PorifleFrndReqClass').show();
    });
    $(document).on('mouseover', "#ProfileFriends", function () {
        if (jsp_api != null) {
            jsp_api.reinitialise();
            //$('.scroll-panefriend .jspVerticalBar').css('opacity',0);
        } else {
            jsp_api = $('.scroll-panefriend').jScrollPane().data('jsp');
            //$('.scroll-panefriend .jspVerticalBar').css('opacity',0);		
        }
        $('.scroll-panefriend .jspDrag').css('background', '#5f5f5f');
    });

    $('#profile_search_string').focus(function () {
        if ($(this).data('focused'))
            return;
        $(this).data('focused', true)
        $(this).val('');
    }).keydown(function (event) {
        if (event.keyCode == 13) {
            $('#profile_friend_search').click();
        }
    });

    $('#profile_friend_search').click(function () {
        $('input[name=SearchCategory]').removeAttr('checked');
        $('#SearchCategory4').click();
        $('#SearchField').val($('#profile_search_string').val());
        $('.SearchSubmit').click();
    });

    $('#newsfeed_search_button').click(function () {
        $('#profile_header_srch_form').submit();
    });

    $('#profile_friend_add').click(function () {
        if ($('#profile_search_string').val() == '') {
            TTAlert({
                msg: t("Please specify a friend's name."),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        $.ajax({
            url: ReturnLink('/ajax/tuber_friend_autoadd.php'),
            data: {
                ss: $('#profile_search_string').val()
            },
            type: 'post',
            success: function (data) {
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.status == 'ok') {
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    location.reload();
                } else {
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            }
        });
    });

    $('.PofileHeaderPopUnder').hover(function () {
        $(this).prev().css('background-color', '#e3b721');
    }, function () {
        $(this).prev().css('background-color', '');
    });

    $('#profile_order').click(function () {
        //alert('here');
        $('#sort-by').toggle();
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
    if($(".notifications_feedtuber").length>0){
        $.ajax({
            url: ReturnLink('ajax/ajax_notleft_TTpage.php'),
            success: function (data) {
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }            
                if (ret.val_not_data!='') {
                    var realval = ret.val_not;
                    if(realval>20){
                        realval =20;
                        $(".ProfileHeaderNotifications .plus").html('+');
                        $(".notifications_box .plus").html('+');
                    }
                    if( realval>0 ) $(".ProfileHeaderNotifications .notCountVal").html(realval);
                    $("#notifications_counter").html(realval);
                    if(ret.val_not_data!='') $(".notifications_feedtuber").append(ret.val_not_data);
                }
            }
        });
    }
    
    /*(function nf() {
        if ($.cookie("example", {path: '/'}) != null) {
            $.ajax({
                url: ReturnLink('ajax/ajax_getnewnewsfeed.php'),
                success: function (data) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }
                    if (ret.error) {

                    } else {
                        if (ret.val >= 1) {
                            var realval = ret.val;
                            if(realval>20){
                                realval =20;
                                $("#ProfileNewsFeed .plus").html('+');
                            }
                            $("#ProfileNewsFeed .PorifleFrndReq .notCountVal").html(realval);
                            $("#ProfileNewsFeed .PorifleFrndReq").removeClass("hideMe");
                        }else{
                            $("#ProfileNewsFeed .plus").html('');
                            $("#ProfileNewsFeed .PorifleFrndReq").addClass("hideMe");
                        }
                    }
                    if (ret.val_not >= 1) {
                        var realval = ret.val_not;
                        if(realval>20){
                            realval =20;
                            $(".ProfileHeaderNotifications .plus").html('+');
                            $(".notifications_box .plus").html('+');
                        }
                        $(".ProfileHeaderNotifications .notCountVal").html(realval);
                        $("#notifications_counter").html(realval);
                        if(ret.val_not_data!='') $(".notifications_feed_inside").parent().html(ret.val_not_data);
                    }else{
                        $(".ProfileHeaderNotifications .notCountVal").html('');
                        $(".ProfileHeaderNotifications .plus").html('');
                        $("#notifications_counter").html('');
                        $(".notifications_box .plus").html('');
                    }
                },
                complete: function () {
                    // Schedule the next request when the current one's complete
                    setTimeout(nf, 60000);
                }
            });
        }
    })();*/
});

function updaterequestnumber(obj) {
    obj.remove();
    var nwcnt = $('#PofileHeaderPopUnderTopFriendsLength').attr('data-count') - 1;
    if (nwcnt != 0) {
        $('#PofileHeaderPopUnderTopFriendsLength').attr('data-count', nwcnt);
        $('#PofileHeaderPopUnderTopFriendsLength').html($.i18n._('FRIEND REQUESTS') + ' (' + nwcnt + ')');
        $('.PorifleFrndReqClass div').html(nwcnt);
        if (jsp_api != null) {
            jsp_api.reinitialise();
        }
        if (nwcnt > 6) {
            $('.scroll-panefriend .jspContainer').css('height', "216px");
        } else {
            $('.scroll-panefriend .jspContainer').css('height', (nwcnt * 36) + "px");
        }
        $('.scroll-panefriend .jspDrag').css('background', '#5f5f5f');
    } else {
        $('.PorifleFrndReqClass').remove();
        $('.PofileHeaderPopUnderBodyFriends').remove();
    }
    if ($('#searchfriendbut').length > 0) {
        $('#searchfriendbut').click();
    }
    $('.ProfileFriendOver').hide();
}
function isAlpha(xStr) {
    var regEx = /^[a-zA-Z\-]+$/;
    return xStr.match(regEx);
}