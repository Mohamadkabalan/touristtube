// JavaScript Document
var one_object = 0;
var pagename;
var EventsDetailedCal;
var TO_CAL;
var GettingNext = false;
var GettingPrev = false;
var ORIGINAL_MEDIA_ID = 0;
var DEFAULT_PHOTO_NUMBER = 0;
var DEFAULT_VIDEO_NUMBER = 0;
var ORIGINAL_PHOTO_ID = 0;
var NextPage = 0;
var PrevPage = 0;
var SkipNext = 1;
var SkipPrev = 0;
var NoMorePrev = false;
var NoMoreNext = false;
var min_left = 0;
var objectSelected = "";
var currentpage_like = 0;
var currentpage_rates = 0;
var currentpage_comments = 0;
var currentpage_shares = 0;
var currentpage_sponsors = 0;
var currentpage_join = 0;
var itempressed = "";
var CURRENT_MODE = "pop";
var GLOBAL_TOP, GLOBAL_LEFT;
var shareFadeoutTimeout;
var pagetype="";
// Toggle the disconnect button's message.
var disconnect_notification_toggle = 0;

function retieveNumber(Num) {
    var Num = '' + Num;
    ListNum = Num.split('.');
    if (ListNum[1]) {
        NumLast = parseInt(Num) + 1;
    } else {
        NumLast = parseInt(Num);
    }
    return NumLast;
}

function GoLink(URL) {
    window.top.location.href = URL;
}


function reactivateAccount(bool) {
    $.ajax({
        url: ReturnLink('/ajax/channel_activate_account.php?no_cach=' + Math.random()),
        data: {globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }

            if (ret.status == 'ok') {
                if (bool) {
                    TTAlert({
                        msg: t('your channel is now reactivated!'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            }
        }
    });
}

function clearForm() {
    $("#SEmailField").val('yourname@email.com');
    $("#SPasswordField").val('Your Password');

    $("#SEmailForgotForm").val('yourname@email.com');
}

function enableSelectBoxes() {
    $('.selectBox').each(function () {
        $(this).children('span.selected').html($(this).children('div.selectOptions').children('span.selectOption:first').html());
        $(this).attr('data-value', $(this).children('div.selectOptions').children('span.selectOption:first').attr('data-value'));

        $(this).children('span.selected,span.selectArrow').click(function () {
            if ($(this).parent().children('div.selectOptions').css('display') == 'none') {
                $(this).parent().children('div.selectOptions').css('display', 'block');
            } else {
                $(this).parent().children('div.selectOptions').css('display', 'none');
            }
        });

        $(this).find('span.selectOption').click(function () {
            $(this).parent().css('display', 'none');
            $(this).closest('div.selectBox').attr('data-value', $(this).attr('data-value'));
            $(this).parent().siblings('span.selected').html($(this).html());
        });
    });
    if (pagename != "") {
        //$(".selectBox_pages").find("#select_"+pagename).addClass('selected');
        $(".selectBox_pages").find("#select_" + pagename).click();
    }
}

function InitCarouselOther() {
    if ($(".caouselother").length > 0) {
        $(".caouselother").jCarouselLite({
            circular: false,
            vertical: false,
            scroll: 1,
            visible: 1,
            auto: 0,
            speed: 750,
            hoverPause: true,
            btnNext: "#OtherChannelsPop .next",
            btnPrev: "#OtherChannelsPop .prev"
        });
    }
}

function InitCarouselSquareCorner() {
    if ($(".otherchannel_carousel").length > 0) {
        $(".otherchannel_carousel").jCarouselLite({
            circular: false,
            vertical: false,
            scroll: 1,
            visible: 1,
            auto: 0,
            speed: 750,
            hoverPause: true,
            btnNext: "#OtherChannelDiv .next_btn",
            btnPrev: "#OtherChannelDiv .prev_btn"
        });
    }
}
function InitCarouselLinks() {
    if ($(".socialcarousel").length > 0 && $(".socialcarousel ul li").length > 0) {
        $(".socialcarousel").jCarouselLite({
            circular: false,
            vertical: false,
            scroll: 1,
            visible: 5,
            auto: 0,
            speed: 250,
            hoverPause: true,
            btnNext: "#SocialMediaBTN .next_btn",
            btnPrev: "#SocialMediaBTN .prev_btn"
        });
    }
}

function GotCountryURL() {
    var country_code = $( '#country_code' ).val();
    if(country_code==''){
        window.location.href = ReturnLink('channels') ;
    }else{
        window.location.href = ReturnLink('channel-search/co/'+country_code) ;
    } 
}

var notFadeoutTimeout=null;
var notifFlag=0;
$(document).ready(function () {
    $(document).on('click',"#request_not_accept_left1" ,function(){
        $('.upload-overlay-loading-fix').show();
        var $parent=$(this).closest('.log_item_list_not');
        var data_id = $parent.attr('data-id');
        TTCallAPI({
            what: 'channel/relation/accept',
            data: {parent_id:channelGlobalID(),channel_id:data_id,relation_type:CHANNEL_RELATION_TYPE_SUB},
            callback: function(ret){  
                $('.upload-overlay-loading-fix').hide();
                if( ret.status === 'error' ){
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                $parent.remove();
            }
        });
    });
    $(document).on('click',"#request_not_accept_left2" ,function(){
        $('.upload-overlay-loading-fix').show();
        var $parent=$(this).closest('.log_item_list_not');
        var data_id = $parent.attr('data-id');
        TTCallAPI({
            what: 'channel/relation/accept',
            data: {parent_id:data_id,channel_id:channelGlobalID(),relation_type:CHANNEL_RELATION_TYPE_PARENT},
            callback: function(ret){  
                $('.upload-overlay-loading-fix').hide();
                if( ret.status === 'error' ){
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                $parent.remove();
            }
        });
    });
    $(document).on('click',"#request_not_ignore_left1" ,function(){
        $('.upload-overlay-loading-fix').show();
        var $parent=$(this).closest('.log_item_list_not');
        var data_id = $parent.attr('data-id');
        TTCallAPI({
            what: 'channel/relation/delete',
            data: {parent_id:channelGlobalID(),channel_id:data_id,relation_type:CHANNEL_RELATION_TYPE_SUB},
            callback: function(ret){  
                $('.upload-overlay-loading-fix').hide();
                if( ret.status === 'error' ){
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                $parent.remove();
            }
        });
    });
    $(document).on('click',"#request_not_ignore_left2" ,function(){
        $('.upload-overlay-loading-fix').show();
        var $parent=$(this).closest('.log_item_list_not');
        var data_id = $parent.attr('data-id');
        TTCallAPI({
            what: 'channel/relation/delete',
            data: {parent_id:data_id,channel_id:channelGlobalID(),relation_type:CHANNEL_RELATION_TYPE_PARENT},
            callback: function(ret){  
                $('.upload-overlay-loading-fix').hide();
                if( ret.status === 'error' ){
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                $parent.remove();
            }
        });
    });
    $(document).on('click', ".not_channel_button .share_cnt", function (e) {
        e.preventDefault();
        if($('.notifications_feedchannel').css('display')!='none'){
            $('.notifications_feedchannel').hide();
        }else{
            if (notifFlag == 0) {
                $('.notifications_feedchannel').show();
                if($('.notifications_feed_inside').height()>=350){
                    initscrollPaneNotifNew($(".notifications_feed"));                    
                }
		
                $("#notifications_counter").html('');
                updateNotificationViewed();
                
                $('.notifications_feedchannel').unbind('mouseenter mouseleave').hover(function () {
                    clearTimeout(notFadeoutTimeout);
                    $('.notifications_feedchannel').stop(true, true);
                    $('.notifications_feedchannel').show();
                    notifFlag=1;
                }, function () {
                    notFadeoutTimeout = setTimeout(function () {
                        $('.notifications_feedchannel').hide();
                        notifFlag=0;
                    }, 500);
                });
                //$('.notifications_feedchannel').mouseenter();
                notFadeoutTimeout = setTimeout(function () {
                        $('.notifications_feedchannel').hide();
                        notifFlag=0;
                    }, 2000);
            }else{
                $('.notifications_feedchannel').hide();
                notifFlag=0;                    
            }
        }
    });
    $(document).on('click',".notification_details_button" ,function(){
        $('.notification_details_up_button.active').click();
        var $this = $(this);
        var data_id = $this.attr("data-id");
        var $parent = $this.closest('.log_item_list_not');
        $parent.find('.log_item_list_details_not').show();
        $(this).hide();
        $parent.find('.notification_details_up_button').addClass('active');
        initscrollPaneNotifNew($(".notifications_feed"));
        var pos = $parent.offset().top-$parent.parent().offset().top;		
        if(jscrollpane_apiL!=null){
            jscrollpane_apiL.scrollToY(pos,true);
        }
    });
    $(document).on('click',".notification_bottom" ,function(){
        var $this = $(this);
        if($this.hasClass('notification_bottom_ch')){
            if(parseInt($this.attr('data-notification'))== 1){
                $this.find('.stop_notifications_text').html(t('undo'));
                $this.find('.stop_notifications_img').addClass('plus');
                $this.attr('data-notification', 0);
                updateNotificationDataNewCH($this, 'stopNotifications');			
            }else{
                $this.find('.stop_notifications_text').html(t('stop notifications'));
                $this.find('.stop_notifications_img').removeClass('plus');
                $this.attr('data-notification', 1);
                updateNotificationDataNewCH($this, 'getNotifications');			
            }
        }else{
            if(parseInt($this.attr('data-notification'))== 0){
                $this.find('.stop_notifications_text').html(t('undo'));
                $this.find('.stop_notifications_img').addClass('plus');
                $this.attr('data-notification', 1);
                updateNotificationDataNew($this, 'stopNotifications');			
            }else{
                $this.find('.stop_notifications_text').html(t('stop notifications'));
                $this.find('.stop_notifications_img').removeClass('plus');
                $this.attr('data-notification', 0);
                updateNotificationDataNew($this, 'getNotifications');			
            }
        }
    });
    $(document).on('click',".notification_details_up_button" ,function(){
            $(this).removeClass('active');
            var $parent = $(this).closest('.log_item_list_not');
            $parent.find('.log_item_list_details_not').hide();
            $parent.find('.notification_details_open').show();
            initscrollPaneNotifNew($(".notifications_feed"));
    });
    if($('.notifications_feedchannel').length>0){
        $.ajax({
            type: "POST",
            url: ReturnLink('ajax/ajax_notleft_channel.php'),
            data: {channel_id: channelGlobalID()},
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
                        $(".not_channel_button .plus").html('+');
                    }
                    if (realval==0) realval='';
                    $("#notifications_counter").html(realval);
                    if(ret.val_not_data!='') $(".notifications_feedchannel").append(ret.val_not_data);
                }
            }
        });
    }
    if($('.notifications_feedchannel').length>0){
        (function nf() {
            if ($.cookie("example", {path: '/'}) != null) {
                $.ajax({
                    type: "POST",
                    url: ReturnLink('ajax/ajax_channelNotifications.php'),
                    data: {channel_id: channelGlobalID()},
                    success: function (data) {
                        var ret = null;
                        try {
                            ret = $.parseJSON(data);
                        } catch (Ex) {
                            return;
                        }
                        if (ret.val_not >= 1) {
                            var realval = ret.val_not;
                            if(realval>20){
                                realval =20;
                                $(".not_channel_button .plus").html('+');
                            }                            
                            $("#notifications_counter").html(realval);
                            if(ret.val_not_data!='') $(".notifications_feed_inside").parent().html(ret.val_not_data);
                        }else{
                            $(".not_channel_button .plus").html('');
                            $("#notifications_counter").html('');
                        }
                    },
                    complete: function () {
                        // Schedule the next request when the current one's complete
                        setTimeout(nf, 60000);
                    }
                });
            }
        })();
    }
    $(document).on('click', ".share_channel_button", function () {
        if ($('.share_link_holder.simplemode').css('display') != "none") {
            $('.share_link_holder.simplemode').hide();
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
            $('.share_link_holder.simplemode').show();
            
            shareFadeoutTimeoutCH = setTimeout(function () {
                $('.share_link_holder.simplemode').hide();
                $('.share_channel_button').removeClass('active');
            }, 1500);

            $('.share_link_holder.simplemode').unbind('mouseenter mouseleave').hover(function () {
                clearTimeout(shareFadeoutTimeoutCH);
                $('.share_link_holder.simplemode').stop(true, true);
                $('.share_link_holder.simplemode').show();
            }, function () {
                shareFadeoutTimeoutCH = setTimeout(function () {
                    $('.share_link_holder.simplemode').hide();
                    $('.share_channel_button').removeClass('active');
                }, 500);
            });
        }
    });
    $('.selectBox_pages').on('mouseleave', function () {
        $('.selectBox_pages .selectOptions').hide();
    });
    $('.selectBox_log').on('mouseleave', function () {
        $('.selectBox_log .selectOptions').hide();
    });
    if($("#ChannelRight_data2 .mustopen .stanTXT").length>0){
        var text=$("#ChannelRight_data2 .mustopen .stanTXT").html();    
        var text1=text.replace(/<br \/>/g, "\n");
        var text2=text1.replace(/<br\/>/g, "\n");
        text=text2.replace(/<br>/g, "\n");
        var aboutstr = "<p>"+text.split("\n").join("</p>\r\n<p>")+"</p>";
        $("#ChannelRight_data2 .mustopen .stanTXT").html(aboutstr);

        $('#ChannelRight_data2 .mustopen .stanTXT p').each(function() {
            if(isRTL($(this).text())){
                $(this).addClass('rtl');
            }
            else{
                 $(this).addClass('ltr');
            }

        });
    }

    $('.butEditChannelRight_data').mouseover(function () {
        var $parents = $('.evContainer2Over').parent();
        var posxx = $(this).offset().left - $parents.offset().left - 253;
        var posyy = $(this).offset().top - $parents.offset().top - 23;
        $('.evContainer2Over .ProfileHeaderOverin').html('edit');
        $('.evContainer2Over').css('left', posxx + 'px');
        $('.evContainer2Over').css('top', posyy + 'px');
        $('.evContainer2Over').stop().show();
    });
    $('.butEditChannelRight_data').mouseout(function () {
        $('.evContainer2Over').hide();
    });
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
    if ($('#connectionContainer').length > 0) {
        $('#connectionContainer').show();
        var connww = $('#connectionContainer span').width() + 10;
        $('#connectionContainer').css('width', connww + 'px');
    }
    $('.embed_channel_button').fancybox({
        padding: 0,
        margin: 0
    });
    var moreopened = false;
    initReportChannelFunctions($("#reportbutton"), $("#reportbutton").attr('data-type'), $("#reportbutton").attr('data-id'));

    $('body').append('<style type="text/css"> .fancyFullDim { height: ' + screen.height + 'px !important; width:100% !important; } </style>');

    $(document).on('click', "#resetpagebut", function () {
        document.location.reload();
    });
    // Show the sent message on send.
    $(document).on('click', ".sendButton_reason", function () {
        $("#sharepopup_error").html('');
        if ($('#disconnectBox').hasClass('active')) {
            $('.sharepopup_butBRCancel').click();
            disonnectuser();
        } else if ($('.uploadinfocheckbox_reason').hasClass('active')) {
            $('.upload-overlay-loading-fix').show();
            var $parent = $(this).closest('.sharepopup_container');
            var entity_id = $parent.attr('data-id');
            var entity_type = $parent.attr('data-type');
            var channel_id = $parent.attr('data-channel');
            var msg = '';
            var reason_array = new Array();
            $parent.find('.uploadinfocheckbox_reason.active').each(function () {
                var $this = $(this);
                reason_array.push($this.attr('data-id'));
            });
            var reason = reason_array.join(',');
            TTCallAPI({
                what: 'user/report/add',
                data: {entity_type: entity_type, entity_id: entity_id, channel_id: channel_id, msg: msg, reason: reason},
                callback: function (ret) {
                    $('.upload-overlay-loading-fix').hide();
                    if (ret.status === 'error') {
                        $parent.find("#sharepopup_error").html(ret.msg);
                        return;
                    }
                    closeFancyBox();
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            });
        } else {
            $("#sharepopup_error").html(t('Please choose one of the above proposed options to Report'));
        }
    });
    $(document).on('click', "#disconnectBox", function () {
        if (!$("#disconnectBox").hasClass('reportsent')) {
            if (disconnect_notification_toggle == 0) {
                $('.sendButton_reason').html(t('send'));
                $("#sharepopup_error").html(t('Disconnecting will stop communicating updates from this channel with you'));
                disconnect_notification_toggle = 1;
            } else {
                $('.sendButton_reason').html(t('report'));
                $("#sharepopup_error").html('');
                disconnect_notification_toggle = 0;
            }
        }
    });
    $("#ChannelRight .more").click(function () {
        var $this = $(this);
        var prev = $this.prev();
        var stanHeight = prev.find("div.stanTXT").height()+20;
        if (moreopened) {
            prev.stop().animate({height: 101}, 500, function () {
                $this.html(t('> more'));
            });
            moreopened = false;
        } else {
            prev.stop().animate({height: stanHeight}, 500, function () {
                $this.html(t('< less'));
            });
            moreopened = true;
        }
    });

    try {
        document.addEventListener("mozfullscreenchange", function () {
            if (!document.mozFullScreen) {
                resetFancy();
                var $f = $(".fancybox-iframe");
                $f[0].contentWindow.showComments();
                $.fancybox.toggle();
            }
        }, false);
    } catch (e) {

    }
    try {
        document.addEventListener("webkitfullscreenchange", function () {
            if (!document.webkitIsFullScreen) {
                var $f = $(".fancybox-iframe");
                resetFancy();
                $f[0].contentWindow.showComments();
                $.fancybox.toggle();
            }
        }, false);
    } catch (e) {

    }


    $(document).on('click', '.searchBtn_pages', function () {
        var srch_url = '';
        switch (("" + $('.selectBox_pages').attr('data-value'))) {
            case 'p':
                srch_url = 'channel-photos';
                break;
            case 'v':
                srch_url = 'channel-videos';
                break;
            case 'a':
                srch_url = 'channel-albums';
                break;
            case 'b':
                srch_url = 'channel-brochures';
                break;
            case 'e':
                srch_url = 'channel-events';
                break;
            case 'n':
                srch_url = 'channel-news';
                break;
        }
        var srch_str = getObjectData($('#searchField_pages'));

        if (srch_str.length != 0) {
            srch_str = 'search/' + srch_str;
        }
        if (srch_url != '') {
            document.location.href = ReturnLink('/' + srch_url + '/' + $(this).attr('data-ch-name') + '/' + srch_str);
        }
    });
    $(document).on('click', 'a.connectBTN', function () {
        var $this = $(this);
        var which_case = $(this).attr('data-case');
        var channel_id = $(this).attr('data-cid');
        var $button = $(this);
        var channel_url = $(this).attr('data-url');
        var isChannel, whichLink, new_text, new_case;

        if ((which_case == 0) || (which_case == 5)) {
            //not logged in
            var msg;
            if (which_case == 0)
                msg = t("you need to have a") + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t("TT account") + '</a> ' + t("in order to connect to a channel");
            else if (which_case == 5)
                msg = t("you need to have a") + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t("TT account") + '</a> ' + ("in order to connect to a channel.<br/>to sponsor a channel, go to relevant page.");
            TTAlert({
                msg: msg,
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        } else if (which_case == 1 || which_case == 3) {
            //connected so disconnect
            isChannel = (which_case == 1) ? 1 : 0;
            whichLink = ReturnLink('/ajax/ajax_disconnect_channel.php');
            new_text = isChannel ? 'sponsor' : 'connect';
            new_case = isChannel ? 2 : 4;

        } else if (which_case == 2 || which_case == 4) {
            //not connected so connect
            isChannel = (which_case == 2) ? 1 : 0;
            whichLink = ReturnLink('/ajax/ajax_connect_channel.php');
            new_text = isChannel ? 'unsponsor' : 'disconnect';
            new_case = isChannel ? 1 : 3;
        }

        if ((which_case == 2) && (channel_url != '')) {
            window.location.href = ReturnLink('/channel/' + channel_url);
            return;
        }

        TTCallAPI({
            what: whichLink,
            data: {channel_id: channel_id, create_ts: 1},
            callback: function (ret) {
                var $parent = $this.parent().parent();
                if (!(which_case == 4 && $parent.parent().parent().parent().attr('id') == "ChannelRight_data3")) {
                    if (typeof ret.error == 'undefined' || ret.error == '') {
                        TTAlert({
                            msg: ret.msg,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                    } else {
                        TTAlert({
                            msg: ret.error,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }
                }
                if (which_case == 4 && $parent.parent().parent().parent().attr('id') == "ChannelRight_data3") {
                    if ( !$this.hasClass('followFriendconnectedtochannel') ) {
                        if ($parent.parent().parent().find('ul li').length >= 21) {
                            getMostActiveChannel();
                        }
                        $parent.remove();
                    }
                } else {
                    $button.html(new_text);
                    $button.attr('data-case', new_case);
                }
            }
        });
    });

    $(document).on('click', ".uploadinfocheckbox", function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#friendsdata').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#followersdata').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox5')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#connectionsdata').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox6')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#sponsorssdata').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox_canjoin')) {
                $("#privacy_canjoin_box").hide();
                $("#privacy_canjoin_box .privacy_select").val('public');
                $("#privacy_canjoin_box .privacy_select").change();
            }
        } else {
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var friendstr = '<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">' + t("friends") + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer').prepend(friendstr);
                    //$(this).parent().parent().find('#friendsdata').css("width", ($(this).parent().parent().find('#friendsdata .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">' + t("followers") + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer').prepend(followerstr);
                    //$(this).parent().parent().find('#followersdata').css("width", ($(this).parent().parent().find('#followersdata .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox5')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var connectionstr = '<div class="peoplesdata formttl13" id="connectionsdata" data-email="" data-id=""><div class="peoplesdatainside">' + t("connections") + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer').prepend(connectionstr);
                    //$(this).parent().parent().find('#connectionsdata').css("width", ($(this).parent().parent().find('#connectionsdata .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox6')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var sponsorstr = '<div class="peoplesdata formttl13" id="sponsorssdata" data-email="" data-id=""><div class="peoplesdatainside">' + t('sponsors') + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer').prepend(sponsorstr);
                    //$(this).parent().parent().find('#sponsorssdata').css("width", ($(this).parent().parent().find('#sponsorssdata .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox_canjoin')) {
                $("#privacy_canjoin_box").show();
            } else if ($(this).attr('id') == 'optionBox_sponsors_hide') {
                $('#optionBox_sponsors_remove').removeClass('active');
                remove_notification_toggle = 0;
            } else if ($(this).attr('id') == 'optionBox_sponsors_remove') {
                hide_notification_toggle = 0;
                $('#optionBox_sponsors_hide').removeClass('active');
            } else if ($(this).attr('id') == 'optionBox_tuber_hide') {
                $('#optionBox_tuber_remove').removeClass('active');
                remove_notification_toggle = 0;
            } else if ($(this).attr('id') == 'optionBox_tuber_remove') {
                hide_notification_toggle = 0;
                $('#optionBox_tuber_hide').removeClass('active');
            }
            $(this).addClass('active');
        }
    });
    $(document).on('click', ".sharepopup_butBRCancel", function () {
        $('.fancybox-close').click();
    });
    $(document).on('click', "#saveButton_add_cancel", function () {
        window.location.reload();
    });
    $(document).on('click', ".peoplesdataclose", function () {
        var parents = $(this).parent();
        var parents_all = parents.parent().parent();
        parents.remove();
        if (parents.attr('data-id') != '') {
            if (parents.attr("data-channel") == 1) {
                SelectedChannelsDelete(parents.attr('data-id'), parents_all.find('.addmore input'));
            } else {
                SelectedUsersDelete(parents.attr('data-id'), parents_all.find('.addmore input'));
            }
        }

        if (parents.attr('id') == 'friendsdata') {
            parents_all.find('.uploadinfocheckbox3').removeClass('active');
        } else if (parents.attr('id') == 'followersdata') {
            parents_all.find('.uploadinfocheckbox4').removeClass('active');
        } else if (parents.attr('id') == 'connectionsdata') {
            parents_all.find('.uploadinfocheckbox5').removeClass('active');
        } else if (parents.attr('id') == 'sponsorssdata') {
            parents_all.find('.uploadinfocheckbox6').removeClass('active');
        }
    });
    $("#sharebutton").fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            var imgsrc = $('#HeaderImage img').attr('src');
            $('#sharepopup').html('');
            var str = '<div class="sharepopup_container"><div class="channelyellow13 formContainer100">' + t("share this channel") + '</div><img class="sharepopup_img margintop7" src="' + imgsrc + '"/><div class="formttl13 formContainer100 margintop26">' + t("write something") + '</div><textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' + t("write something...") + '" style="font-family:Arial, Helvetica, sans-serif; width:401px; height:42px;" type="text" name="invitetext">' + t('write something...') + '</textarea>';
            str += '<div class="formttl13 formContainer100 margintop15">' + t("add people (T tubers, emails)") + '</div>';
            str += '<div class="peoplecontainer formContainer100 margintop2"><div class="emailcontainer"><div class="addmore"><input name="addmoretext" id="addmoretext" type="text" class="" data-value="' + t("add more") + '" value="' + t("add more") + '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>';
            str += '<div class="friendscontainer"><div class="uploadinfocheckbox uploadinfocheckbox3 formttl13 margintop8 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> ' + t("friends") + '<span style="color:#b8b8b8;"></span></div></div><div class="uploadinfocheckbox uploadinfocheckbox4 formttl13 margintop5 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> ' + t("followers") + '<span style="color:#b8b8b8;"></span></div></div></div></div><div class="sharepopup_butcontainer margintop8"><div class="sharepopup_butBRCancel sharepopup_buts">' + t("cancel") + '</div><div class="sharepopup_butseperator"></div><div id="share_popup_but" class="sharepopup_but2 sharepopup_buts">' + t("send") + '</div></div></div>';
            $('#sharepopup').html(str);
            $.ajax({
                type: "POST",
                url: ReturnLink("/ajax/popup_actions_share_invite.php"),
                data: {imgsrc: imgsrc, type: 'share'},
                success: function (data) {
                    if (data) {
                        $('#sharepopup').html(data);
                        resetSelectedUsers();
                        addmoreusersautacomplete();
                    }
                }
            });
        }});
    $("#invitebutton").fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            $('#sharepopup').html('');
            var str = '<div class="sharepopup_container"><div class="channelyellow13 formContainer100">' + t("invite people to connect with this channel") + '</div><div class="formttl13 formContainer100 margintop14">' + t("write something") + '</div><textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' + t('write something...') + '" style="font-family:Arial, Helvetica, sans-serif; width:401px; height:42px;" type="text" name="invitetext">' + t('write something...') + '</textarea>';
            str += '<div class="formttl13 formContainer100 margintop15">' + t("add people (T tubers, emails)") + '</div>';
            str += '<div class="peoplecontainer formContainer100 margintop2"><div class="emailcontainer"><div class="addmore"><input name="addmoretext" id="addmoretext" type="text" class="" data-value="' + t("add more") + '" value="' + t("add more") + '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>';
            str += '<div class="friendscontainer"><div class="uploadinfocheckbox uploadinfocheckbox3 formttl13 margintop8 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> ' + t("friends") + '<span style="color:#b8b8b8;"></span></div></div><div class="uploadinfocheckbox uploadinfocheckbox4 formttl13 margintop5 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> ' + t("followers") + '<span style="color:#b8b8b8;"></span></div></div></div></div><div class="sharepopup_butcontainer margintop8"><div class="sharepopup_butBRCancel sharepopup_buts">' + t("cancel") + '</div><div class="sharepopup_butseperator"></div><div id="invite_popup_but" class="sharepopup_but2 sharepopup_buts">' + t("send") + '</div></div></div>';
            $('#sharepopup').html(str);

            $.ajax({
                type: "POST",
                url: ReturnLink("/ajax/popup_actions_share_invite.php"),
                data: {type: 'invite'},
                success: function (data) {
                    if (data) {
                        $('#sharepopup').html(data);
                        resetSelectedUsers();
                        addmoreusersautacomplete();
                    }
                }
            });
        }});
    $(document).on('click', "#invite_popup_but", function () {
        var $this = $(this);
        var $parent = $this.parent().parent();
        var invite_msg = getObjectData($parent.find("#invitetext"));
        var inviteArray = new Array();
        $parent.find('.peoplecontainer .emailcontainer .peoplesdata').each(function () {
            var obj = $(this);
            if (obj.attr('id') == "friendsdata") {
                inviteArray.push({friends: 1});
            } else if (obj.attr('id') == "followersdata") {
                inviteArray.push({followers: 1});
            } else if (obj.attr('id') == "option_6") {
                inviteArray.push({connections: 1});
            } else if (obj.attr('data-email') != '') {
                inviteArray.push({email: obj.attr('data-email')});
            } else if (parseInt(obj.attr('data-id')) != 0) {
                inviteArray.push({id: obj.attr('data-id')});
            }
        });
        if (inviteArray.length == 0) {
            return;
        }

        TTCallAPI({
            what: 'social/share',
            data: {entity_type: SOCIAL_ENTITY_CHANNEL, entity_id: channelGlobalID(), share_with: inviteArray, share_type: SOCIAL_SHARE_TYPE_INVITE, msg: invite_msg, channel_id: channelGlobalID(),addToFeeds:1},
            callback: function (ret) {
                closeFancyBox();
            }
        });

    });
    $(document).on('click', "#share_popup_but", function () {
        var $this = $(this);
        var $parent = $this.parent().parent();
        var invite_msg = getObjectData($parent.find("#invitetext"));
        var inviteArray = new Array();
        $parent.find('.peoplecontainer .emailcontainer .peoplesdata').each(function () {
            var obj = $(this);
            if (obj.attr('id') == "friendsdata") {
                inviteArray.push({friends: 1});
            } else if (obj.attr('id') == "followersdata") {
                inviteArray.push({followers: 1});
            } else if (obj.attr('id') == "option_6") {
                inviteArray.push({connections: 1});
            } else if (obj.attr('data-email') != '') {
                inviteArray.push({email: obj.attr('data-email')});
            } else if (parseInt(obj.attr('data-id')) != 0) {
                inviteArray.push({id: obj.attr('data-id')});
            }
        });
        if (inviteArray.length == 0) {
            return;
        }

        TTCallAPI({
            what: 'social/share',
            data: {entity_type: SOCIAL_ENTITY_CHANNEL, entity_id: channelGlobalID(), share_with: inviteArray, share_type: SOCIAL_SHARE_TYPE_SHARE, msg: invite_msg, channel_id: channelGlobalID(),addToFeeds:1},
            callback: function (ret) {
                closeFancyBox();
            }
        });
    });
    $(".okButton").click(function () {
        $('.sharepopup_butBRCancel').click();
    });

    enableSelectBoxes();

    InitCarouselLinks();

    $('#SearchField').blur(function () {
        if ($(this).data('typed'))
            return;
        $(this).val('Search Channels...');
    }).focus(function () {
        if ($(this).data('typed'))
            return;
        $(this).val('');
    }).keyup(function () {
        $(this).data('typed', true);
    });



    var menushown = false;
    var oopened = false
    var whileopening = false;
    //var moreshown = false;
    $("#OtherChannels_menu_container").show();
    var ww = -$("#OtherChannels_menu_inside").width();
    $("#OtherChannels_menu_inside").delay(100).animate({left: ww + "px"}, 600, function () {
        $("#OtherChannels_menu_container").hide();
    });

    $("#OtherChannels").click(function () {
        var $this = $(this);
        var ww = -$("#OtherChannels_menu_inside").width();
        if ($("#OtherChannels_menu_container").css('display') != "none") {
            $this.removeClass('active');
            $('#CloseChannelDiv').click();
            $("#OtherChannels_menu_inside").delay(100).animate({left: ww + "px"}, 600, function () {
                $("#OtherChannels_menu_container").hide();
            });
        } else {
            $this.addClass('active');
            $("#OtherChannels_menu_container").show();
            $("#OtherChannels_menu_inside").delay(100).animate({left: 0}, 600);
        }
    });
    addProfileSideMenuAction();

    $("#OtherChannels_menu1").click(function () {
        $('#CloseChannelDiv').click();
        $('#OtherLeftInside').css('width', '800px');
        $('.OtherChannels_menu').removeClass('active');
        $(this).addClass('active');
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/my_channels.php'),
            data: {id: channelGlobalID()},
            type: 'post',
            success: function (data) {
                if (data) {
                    $('#OtherLeft #OtherLeftInside').html(data);
                    $('#OtherLeft').css('height', '290px');
                    $('#OtherLeftInside').css('width', '800px');
                    $(this).addClass('active');
                    whileopening = true;
                    $("#OtherChannelDiv").stop().animate({left: '0px'}, 500, function () {
                        whileopening = false;
                    });
                    oopened = true;
                    InitCarouselSquareCorner();
                }
                $('.upload-overlay-loading-fix').hide();
            }
        });
    });
    $("#OtherChannels_menu2").click(function () {
        $('#CloseChannelDiv').click();
        $('#OtherLeftInside').css('width', '800px');
        $('.OtherChannels_menu').removeClass('active');
        $(this).addClass('active');
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/my_sponsered_channel.php'),
            data: {id: channelGlobalID()},
            type: 'post',
            success: function (data) {
                if (data) {
                    $('#OtherLeft #OtherLeftInside').html(data);
                    $('#OtherLeft').css('height', '290px');
                    $('#OtherLeftInside').css('width', '800px');
                    $(this).addClass('active');
                    whileopening = true;
                    $("#OtherChannelDiv").stop().animate({left: '0px'}, 500, function () {
                        whileopening = false;
                    });
                    oopened = true;
                    InitCarouselSquareCorner();
                }
                $('.upload-overlay-loading-fix').hide();
            }
        });
    });
    $("#OtherChannels_menu4").click(function () {
        $('#CloseChannelDiv').click();
        $('#OtherLeftInside').css('width', '800px');
        $('.OtherChannels_menu').removeClass('active');
        $(this).addClass('active');
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/my_sub_channels.php'),
            data: {id: channelGlobalID()},
            type: 'post',
            success: function (data) {
                if (data) {
                    $('#OtherLeft #OtherLeftInside').html(data);
                    $('#OtherLeft').css('height', '290px');
                    $('#OtherLeftInside').css('width', '800px');
                    $(this).addClass('active');
                    whileopening = true;
                    $("#OtherChannelDiv").stop().animate({left: '0px'}, 500, function () {
                        whileopening = false;
                    });
                    oopened = true;
                    InitCarouselSquareCorner();
                }
                $('.upload-overlay-loading-fix').hide();
            }
        });
    });
    $(document).on('click', "#CloseChannelDiv", function () {
        $(".OtherChannels_menu").removeClass('active');
        if (!whileopening) {
            oopened = false;
            $("#OtherChannelDiv").stop().animate({left: '-800px'}, 500, function () {
                if (!oopened) {
                    $('#OtherLeft').css('height', '60px');
                    $('#OtherLeftInside').css('width', '0px');
                }
            });
        }
    });
    $(document).on('mouseover', "#OtherChannelDiv .channellist li .li", function () {
        var $this = $(this);
        if (!$this.find(".hide_channel_but").hasClass('inactive')) {
            $this.find(".hide_channel_but").show();
        }
    });
    $(document).on('mouseout', "#OtherChannelDiv .channellist li .li", function () {
        var $this = $(this);
        $this.find(".hide_channel_but").hide();
    });
    $(document).on('click', ".hide_channel_but", function () {
        var $this = $(this);
        $this.addClass('inactive');
        $this.parent().parent().find(".hide_layer_other_channel").show();
        var is_sposered_page = ($this.parent().parent().hasClass('sponsor')) ? 1 : 0;
        showHideChannelDisplay($this.parent().parent().attr('data-id'), is_sposered_page, 0);
    });
    $(document).on('click', ".plus_layer_other_channel", function () {
        var $this = $(this);
        $this.parent().find(".hide_channel_but").removeClass('inactive');
        $this.parent().find(".hide_layer_other_channel").hide();
        var is_sposered_page = ($this.parent().hasClass('sponsor')) ? 1 : 0;
        showHideChannelDisplay($this.parent().attr('data-id'), is_sposered_page, 1);
    });
    $(document).on('click', ".remove_sponsored_but", function () {
        var $this = $(this);
        removeSponseredChannel($this);
    });
    $(document).on('mouseover', ".hide_layer_other_channel, .plus_layer_other_channel", function () {
        var $this = $(this);
        $this.parent().find(".plus_layer_other_channel").show();
    });
    $(document).on('mouseout', ".hide_layer_other_channel, .plus_layer_other_channel", function () {
        var $this = $(this);
        $this.parent().find(".plus_layer_other_channel").hide();
    });

//    $(".showChannels").click(function () {
//        if (!moreshown) {
//            moreshown = true;
//            $("#OtherChannelsPop").show();
//            InitCarouselOther();
//        } else {
//            moreshown = false;
//            $("#OtherChannelsPop").hide();
//        }
//    });

    $("#ArrowDowm").click(function () {
        if (menushown) {
            $("#MenuRight").stop().animate({height: '0px'}, 500, function () {
            });
            menushown = false;
        } else {
            $("#MenuRight").stop().animate({height: '69px'}, 500, function () {
            });
            menushown = true;
        }
    });

    $("#MenuRight").mouseleave(function () {
        setTimeout(function () {
            $("#MenuRight").stop().animate({height: '0px'}, 500, function () {
            });
        }, 750)
        menushown = false;
    });

    refreshMostActiveTuberOver();

    $(document).click(function () {
        $("#ChannelRight .list li .popUp").hide();
    });
    InitCarouselSquareCorner();
    if ($(".ticker").length > 0) {
        $(".ticker").jCarouselLite({
            circular: true,
            vertical: true,
            scroll: 1,
            visible: 1,
            auto: 3000,
            speed: 500,
            hoverPause: true
        });
    }


    $(".registerpopUp input").focus(function () {
        $(this).val('');
    });

    $('.addFriend').live('click', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var data_status = "" + $this.attr('data-status');
        if ((String("" + user_Is_channel) == "1") || parseInt(user_is_logged) == 0) {
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to add someone as a friend.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }
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
                        msg: t('Your session timed out. Please login.'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                TTAlert({
                    msg: jres.msg,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                if(jres.status=='error') return;
                if (data_status == "remove") {
                    $this.parent().find('.seperator_button').remove();
                    $this.remove();
                } else {
                    $this.html(t('unfriend'));
                    $this.removeClass('addFriend');
                    $this.addClass('removeFriend');
                }
            }
        });

    });

    $('.removeFriend').live('click', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var data_status = "" + $this.attr('data-status');
        var selected_type = "friend";
        if (String("" + user_Is_channel) == "1") {
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to unfriend someone.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }

        TTAlert({
            msg: sprintf(t('confirm to remove permanently selected %s'), [selected_type]),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function (data) {
                if (data) {
                    $.post(ReturnLink('/ajax/ajax_rejectprofilefriend.php'), {fid: id}, function (data) {
                        $this.html(t('add as a friend'));
                        $this.removeClass('removeFriend');
                        $this.addClass('addFriend');
                    });
                }
            }
        });

    });

    $('.followFriend').live('click', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var data_status = "" + $this.attr('data-status');
        var data_userIschannel = "" + $this.attr('data-userIschannel');
        if (String("" + user_Is_channel) == "1") {
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to follow someone.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }
        if ((data_status == "2" && data_userIschannel == "1") || parseInt(user_is_logged) == 0) {
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to follow someone.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }
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
                        msg: t('Your session timed out. Please login.'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                if (data_status == "remove") {
                    $this.parent().find('.seperator_button').remove();
                    $this.remove();
                } else {                    
                    if (data_status == "2") {
                        //Most active tuber
                        if ( !$this.hasClass('followFriendconnectedtochannel') ) {
                            var $parent = $this.parent().parent();
                            if ($parent.parent().parent().find('ul li').length >= 21) {
                                getMoreActiveTuber();
                            }
                            $parent.remove();
                        }else{
                            $this.remove();
                        }
                    } else {
                        $this.html(t('unfollow'));
                    }
                    if (data_status != "2") {
                        $this.removeClass('followFriend');
                        $this.addClass('unfollowFriend');
                    }
                }
            }
        });

    });

    $('.unfollowFriend').live('click', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var data_status = "" + $this.attr('data-status');
        if (String("" + user_Is_channel) == "1") {
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to unfollow someone.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }
        if (data_status == "1") {
            var myname = $this.parent().attr('data-name');
        } else {
            var myname = $this.parent().parent().find(".mynameDiv").html();
        }
        TTAlert({
            msg: sprintf(t('confirm to unfollow %s'), [myname]),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function (data) {
                if (data) {
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
                            $this.html(t('follow'));
                            $this.removeClass('unfollowFriend');
                            $this.addClass('followFriend');
                        }
                    });
                }
            }
        });
    });
    $('.connectChannel').live('click', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var data_status = "" + $this.attr('data-status');
        if (data_status == "1" && String("" + user_Is_channel) == "1") {
            return;
        }
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/ajax_connect_channel.php'),
            data: {channel_id: id},
            type: 'post',
            success: function (data) {
                $('.upload-overlay-loading-fix').hide();
                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {

                }
                if (!jres) {
                    TTAlert({
                        msg: t('Your session timed out. Please login.'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                if (jres.error != '') {
                    TTAlert({
                        msg: jres.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    if (data_status == "1") {
                        TTAlert({
                            msg: jres.msg,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        $this.attr('data-title', 'disconnect');
                        $('.objectContainerOver .objectContainerOverin').html($this.attr('data-title'));
                    } else if (data_status == "11") {
                        TTAlert({
                            msg: jres.msg,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        $this.addClass('disconnectChannel');
                        $this.removeClass('connectChannel');
                        $this.html(t('disconnect'));
                    } else {
                        //$this.attr('id','disConnectBTN');
                        window.location.reload();
                        //refreshMenuProfileSide(1);
                    }
                    $this.removeClass('connectChannel');
                    $this.addClass('disconnectChannel');
                }
            }
        });

    });
    $('.disconnectChannel').live('click', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var data_status = "" + $this.attr('data-status');
        if (data_status == "1" && String("" + user_Is_channel) == "1") {
            return;
        }
        if (data_status == "1") {
            var myname = $this.parent().attr('data-name');
        } else {
            myname = $this.attr('data-name');
        }
        TTAlert({
            msg: t('you have been disconnected from this channel, you will not receive updates anymore.'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function (data) {
                if (data) {
                    $.ajax({
                        url: ReturnLink('/ajax/ajax_disconnect_channel.php'),
                        data: {channel_id: id, create_ts: 1},
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
                            if (data_status == "1") {
                                $this.attr('data-title', 'connect');
                                $('.objectContainerOver .objectContainerOverin').html($this.attr('data-title'));
                            } else if (data_status == "11") {
                                $this.removeClass('disconnectChannel');
                                $this.addClass('connectChannel');
                                $this.html(t('connect'));
                            } else {
                                //$this.attr('id','ConnectBTN');

                                //refreshMenuProfileSide(0);
                            }
                            $this.removeClass('disconnectChannel');
                            $this.addClass('connectChannel');
                            window.location.reload();
                        }
                    });
                }
            }
        });        
    });
    $('.sponsoring_channel_tuber').live('click', function () {
        var case_val = parseInt($(this).attr('data-case'));
        var msg = "";
        var button2 = "";
        var strlink = "";
        switch (case_val) {
            case 0:
                msg = t("you need to have a channel page in order to sponsor this channel");
                button2 = "register";
                strlink = ReturnLink('/register');
                break;
            case 3:
                msg = t("you need to have a channel page in order to sponsor this channel");
                button2 = t("create my channel");
                strlink = ReturnLink('/CreateChannelForm');
                break;
        }
        TTAlert({
            msg: msg,
            type: 'action',
            btn1: t('cancel'),
            btn2: button2,
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = strlink;
                }
            }
        });
    });

    $("#sponsoring_channel").fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            var imgsrc = $('#HeaderImage img').attr('src');
            var channel_id = $("#sponsoring_channel").attr("data-id");
            $('#sharepopup').html('');
            var str = '<div class="sharepopup_container"><div class="channelyellow13 formContainer100">' + t("sponsor this channel") + '</div><img class="sharepopup_img margintop7" src="' + imgsrc + '"/><div class="formttl13 formContainer100 margintop26">' + t("write something") + '</div><textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' + t('write something...') + '" style="font-family:Arial, Helvetica, sans-serif; width:401px; height:42px;" type="text" name="invitetext">' + t('write something...') + '</textarea>';
            str += '<div class="formttl13 formContainer100 margintop15">' + t("add people (emails)") + '</div>';
            str += '<div class="peoplecontainer peoplecontainersponsor formContainer100 margintop2"><div class="emailcontainer"><div class="addmore"><input name="addmoretext" id="addmoretext" type="text" class="" data-value="' + t("add more") + '" value="' + t("add more") + '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>';
            str += '</div><div class="sharepopup_butcontainer margintop8"><div class="sharepopup_butBRCancel sharepopup_buts">' + t("cancel") + '</div><div class="sharepopup_butseperator"></div><div class="sharepopup_but2 sharepopup_buts">' + t("send") + '</div></div></div>';
            $('#sharepopup').html(str);
            $.ajax({
                type: "POST",
                url: ReturnLink("/ajax/popup_actions_sponsor.php"),
                data: {imgsrc: imgsrc, channelid: channel_id},
                success: function (data) {
                    if (data) {
                        $('#sharepopup').html(data);
                        //resetSelectedUsers();
                        $('.peoplecontainersponsor #addmoretext').keydown(function(event){
                            var code = (event.keyCode ? event.keyCode : event.which);
                            if(code === 13) { //Enter keycode or tab
                                if(validateEmail($('.peoplecontainersponsor #addmoretext').val())){
                                    var friendstr='<div class="peoplesdata formttl" data-id="" data-email="'+$('.peoplecontainersponsor #addmoretext').val()+'"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">'+$('.peoplecontainersponsor #addmoretext').val()+'</div><div class="peoplesdataclose"></div></div>';
                                    $('.emailcontainer').prepend(friendstr);
    //				
                                    $('.peoplecontainersponsor #addmoretext').val('');
                                    $('.peoplecontainersponsor #addmoretext').blur();

                                    var height = $('#inviteForm .formContainer').height()+74;
                                    $('#inviteForm').css('height',height+"px");
                                    event.preventDefault();
                                }
                            }
                        });
                    }
                }
            });
        }});
    $(document).on('click', "#sharepopup_buts_send", function () {
        var $this = $(this);
        var $parent = $this.parent().parent();
        var invite_msg = getObjectData($parent.find("#invitetext"));
        var inviteArray = new Array();
        $parent.find('.peoplecontainer .peoplesdata').each(function () {
            var obj = $(this);
            if (obj.attr('id') == "connectionsdata") {
                inviteArray.push({connections: 1});
            } else if (obj.attr('id') == "option_6") {
                inviteArray.push({connections: 1});
            } else if (obj.attr('data-email') != '') {
                inviteArray.push({email: obj.attr('data-email')});
            } else if (parseInt(obj.attr('data-id')) != 0) {
                inviteArray.push({id: obj.attr('data-id')});
            }
        });

        TTCallAPI({
            what: 'social/sponsor',
            data: {entity_type: SOCIAL_ENTITY_CHANNEL, entity_id: channelGlobalID(), share_with: inviteArray, share_type: SOCIAL_SHARE_TYPE_SPONSOR, msg: invite_msg, channel_id: channelGlobalID(), sponsor_id: $(this).attr('data-id')},
            callback: function (ret) {
                window.location.reload();
            }
        });
    });
    $(document).on('click', '.overdatabutenable', function () {
        var $this = $(this);
        var $parent = $this.parent().parent().parent().parent();
        var target = $parent.attr('data-vid');
        if (String("" + $this.attr('data-status')) == "1") {
            enableSharesComments(target, 0);
            $this.attr('data-status', '0');
            $this.find('.overdatabutntficon').addClass('inactive');
        } else {
            enableSharesComments(target, 1);
            $this.attr('data-status', '1');
            $this.find('.overdatabutntficon').removeClass('inactive');
        }
    });
    $(document).on('click', ".overdatabutcallmodal", function () {
        var $this = $(this);
        var $parent = $this.parent().parent().parent().parent();
        var $val = "";
        if($this.hasClass('overdatabutedit')){
            $val = "edit";
        }else if($this.hasClass('overdatabutlike')){
            $val = "like";
        }else if($this.hasClass('overdatabutrate')){
            $val = "rate";
        }else if($this.hasClass('overdatabutshare')){
            $val = "share";
        }else if($this.hasClass('overdatabutcomment')){
            $val = "comment";
        }else if($this.hasClass('overdatabutreport')){
            $val = "report";
        }
        if (parseInt(user_is_logged) == 0) {
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + sprintf(t('in order to %s .'), [$val]),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }
        if (parseInt(user_Is_channel) == 1 && parseInt(is_owner) == 0) {
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + sprintf(t('in order to %s .'), [$val]),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }
    });
    $(document).on('click', ".overdatabutremove", function () {
        var curitem = $(this).parent().parent().parent().parent();
        var typemedia = "album";
        if (pagetype == "v") {
            typemedia = "video";
        } else if (pagetype == "i") {
            typemedia = "photo";
        }
        TTAlert({
            msg: sprintf(t('confirm to remove permanently selected %s'), [typemedia]),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function (data) {
                if (data) {
                    $('.upload-overlay-loading-fix').show();

                    var $id = curitem.attr('data-vid');

                    if (typemedia == "album") {
                        $.post(ReturnLink('/ajax/ajax_catalogdelete.php'), {id: $id, albumcontents: 1, channel_id: -2}, function (data) {
                            var num_medias = parseInt($('.' + pagetype + 'mediaDataCount').attr('data-number')) - 1;
                            $('.' + pagetype + 'mediaDataCount').attr('data-number', num_medias);
                            $('.' + pagetype + 'mediaDataCount').html("(" + num_medias + ")");
                            $('.' + pagetype + 'menu').parent().find('.channelyellow').html(num_medias);

                            curitem.remove();
                            one_object = 1;
                            getMediaDataRelated();
                        });
                    } else {
                        var idsarr = new Array();
                        var target = $id;
                        idsarr.push(target);

                        $.post(ReturnLink('/ajax/ajax_deletepicDB.php'), {idarr: idsarr.join('/*/')}, function (data) {
                            var num_medias = parseInt($('.' + pagetype + 'mediaDataCount').attr('data-number')) - 1;
                            $('.' + pagetype + 'mediaDataCount').attr('data-number', num_medias);
                            $('.' + pagetype + 'mediaDataCount').html("(" + num_medias + ")");
                            $('.' + pagetype + 'menu').parent().find('.channelyellow').html(num_medias);

                            curitem.remove();
                            one_object = 1;
                            getMediaDataRelated();
                        });
                    }
                }
            }
        });
    });

    $(document).on('click', "#saveButton_Add_Brochure", function () {
        var return_link = $("#saveButton_Add_Brochure").attr("data-return-link");
        var curob = $(this).parent().parent();
        var namebrochure = getObjectData(curob.find('#edit_brochuretitle_txt'));
        var brochurephotoStanInside = curob.find('.brochurephotoStanInside').attr('data-value');
        var pdf = curob.find('#uploadContainerPDF').attr('data-pdf');
        var namebrochure = namebrochure.replace(/\n/g, " ");
        if (checkBrochureInfo_Add(namebrochure, pdf, curob)) {
            $.ajax({
                url: ReturnLink('/ajax/info_add_brochure_channel.php'),
                data: {globchannelid: channelGlobalID(), namebrochure: namebrochure, brochurephotoStanInside: brochurephotoStanInside, pdf: pdf},
                type: 'post',
                success: function (data) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }

                    if (!ret) {
                        curob.find('#brochureerror').html(t('Couldnt save please try again later'));
                        return;
                    }
                    if (ret.status == 'ok') {
                        //window.location.reload();
                        window.open(return_link, '_self');
                    } else {
                        curob.find('#brochureerror').html(ret.error);
                    }
                }
            });
        }
    });
    $('#addbrochure_buttonbk').fancybox();

    $(document).on('click', "#saveButton_Add_News", function () {
        var obj = $(this).parent().parent();
        var news_text = getObjectData(obj.find('#edit_newstitle_txt'));
        if (news_text.length == 0) {
            obj.find('#brochureerror').html(t('Invalid news'));
            obj.find('#edit_newstitle_txt').addClass('InputErr');
            obj.find('#edit_newstitle_txt').focus();
            return false;
        } else {
            $.ajax({
                url: ReturnLink('/ajax/info_add_news_channel.php'),
                data: {globchannelid: channelGlobalID(), news_text: news_text},
                type: 'post',
                success: function (data) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }

                    if (!ret) {
                        obj.find('#newserror').html(t('Couldnt save please try again later'));
                        return;
                    }
                    if (ret.status == 'ok') {
                        var return_url = $("#saveButton_Add_News").attr('data-return-url');
                        window.open(return_url, "_self");
                        //window.location.reload();
                    } else {
                        obj.find('#newserror').html(ret.error);
                    }
                }
            });
        }
    });

    $(document).on('click', "#saveButton_Add_Events", function () {
        // The URL to return to after save.
        var return_url = $("#saveButton_Add_Events").attr('data-return-url');

        var curob = $(this).parent().parent();
        var nameevents = getObjectData(curob.find('#nameevents'));
        var descriptionevent = getObjectData(curob.find('#descriptionevent'));
        var locationevent = getObjectData(curob.find('#locationevent'));
        var data_location = curob.find('#locationevent').attr('data-location');
        var data_country = curob.find('#locationevent').attr('data-country');
        var data_lng = curob.find('#locationevent').attr('data-lng');
        var data_lat = curob.find('#locationevent').attr('data-lat');

        var fromdate = getObjectData(curob.find('#fromdate'));
        var defaultEntry = curob.find('#defaultEntry').val();
        var todate = getObjectData(curob.find('#todate'));
        var defaultEntryTo = curob.find('#defaultEntryTo').val();
        var themephotoStanInside = curob.find('.themephotoStanInside').attr('data-value');
        var guestevent = curob.find('#guestevent').val();
        var caninvite = (curob.find('.uploadinfocheckbox_event1').hasClass('active')) ? 1 : 0;
        var showguests = (curob.find('.uploadinfocheckbox_event2').hasClass('active')) ? 1 : 0;
        var showsponsors = (curob.find('.uploadinfocheckbox_event3').hasClass('active')) ? 1 : 0;
        var allowsponsoring = (curob.find('.uploadinfocheckbox_event4').hasClass('active')) ? 1 : 0;
        var enablesharecomments = (curob.find('.uploadinfocheckbox_event5').hasClass('active')) ? 1 : 0;

        if (checkEventInfo_Add(nameevents, descriptionevent, locationevent, fromdate, todate, defaultEntry, defaultEntryTo, data_location, data_lng, data_lat, curob)) {
            $.ajax({
                url: ReturnLink('/ajax/info_event_manage_channel.php'),
                data: {globchannelid: channelGlobalID(), nameevents: nameevents, descriptionevent: descriptionevent, data_country: data_country, locationevent: locationevent, fromdate: fromdate, fromdatetime: defaultEntry, todate: todate, todatetime: defaultEntryTo, whojoin: curob.find('input[name=radio1]:checked').val(), themephotoStanInside: themephotoStanInside, guestevent: guestevent, caninvite: caninvite, showguests: showguests, showsponsors: showsponsors, allowsponsoring: allowsponsoring, enablesharecomments: enablesharecomments, data_lng: data_lng, data_lat: data_lat, data_location: data_location},
                type: 'post',
                success: function (data) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }

                    if (!ret) {
                        curob.find('#brochureerror').html(t('Couldnt save please try again later'));
                        return;
                    }
                    if (ret.status == 'ok') {
                        //window.location.reload();
                        window.open(return_url+'/'+ret.value, "_self");
                    } else {
                        curob.find('#brochureerror').html(ret.error);
                    }
                }
            });
        }

    });

    $('.videoend_subconatiner').each(function (index, element) {
        var $This = $(this);

        $This.on('mouseenter', function () {
            $This.find('.videoend_overlay').show();
        }).on('mouseleave', function () {
            $This.find('.videoend_overlay').hide();
        });
    });    
//    $(".channelAvatarLink").fancybox({
//        helpers : {
//            overlay : {closeClick:true}
//        }
//    });
    $(".channelAvatarLink").each(function (index, element) {
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
function updateImage_Add_Brochure(str, curname, _type) {
    if (_type == "uploadBtnbrochure") {
        $('#create_event_container .brochurephotoStanInside').attr('data-value', curname);
        $('#create_event_container .brochurephotoStanInside').html(str);
    } else if (_type == "uploadBtnevent") {
        $('#create_event_container .themephotoStanInside').attr('data-value', curname);
        $('#create_event_container .themephotoStanInside').html(str);

    }
    closeFancyBox();
}
function setNewEvents() {
    if ($('#fromdate').length > 0) {
        $('#defaultEntry').timeEntry({
            show24Hours: true
        });
        $("#defaultEntry").val(getCurrentTime());


        $('#defaultEntryTo').timeEntry({
            show24Hours: true
        });
        $("#defaultEntryTo").val(getCurrentTime());

        Calendar.setup({
            inputField: "fromdate",
                noScroll  	 : true,
            trigger: "fromdate",
            align: "B",
            onSelect: function () {
                var date = Calendar.intToDate(this.selection.get());
                TO_CAL.args.min = date;
                TO_CAL.redraw();
                $('#fromdate').attr('data-cal', Calendar.printDate(date, "%Y-%m-%d"));

                addCalToEvent(this);
                this.hide();
            },
            dateFormat: "%d / %m / %Y"
        });
        TO_CAL = Calendar.setup({
            inputField: "todate",
                noScroll  	 : true,
            trigger: "todate",
            align: "B",
            onSelect: function () {
                var date = Calendar.intToDate(this.selection.get());
                $('#todate').attr('data-cal', Calendar.printDate(date, "%Y-%m-%d"));

                addCalToEvent(this);
                this.hide();
            },
            dateFormat: "%d / %m / %Y"
        });
    }
}
function addCalToEvent(cals) {
    if (new Date($('#fromdate').attr('data-cal')) > new Date($('#todate').attr('data-cal'))) {
        $('#todate').attr('data-cal', $('#fromdate').attr('data-cal'));
        $('#todate').val($('#fromdate').val());
    }
}
function getCurrentTime() {
    var thisdate = new Date();
    var hours = thisdate.getHours();
    if (hours < 10) {
        hours = "0" + hours;
    }
    var minutes = thisdate.getMinutes();
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    var time = hours + ":" + minutes;
    return 	time;
}
function initReportFunctions(obj) {
    obj.fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            var data_type = $('.social_data_all').attr('data-type');
            var data_id = $('.social_data_all').attr('data-id');
            var show_disconnect = 0;
            if ($('.connectstyle').hasClass('disconnectChannel')) {
                show_disconnect = 1;
            }
            $('#sharepopup').html('');
            var str = '<div class="sharepopup_container" style="margin-left:34px; width:506px; height:312px"></div>';
            $('#sharepopup').html(str);

            disconnect_notification_toggle = 0;
            $.ajax({
                type: "POST",
                cache: false,
                url: ReturnLink("/ajax/popup_report_data.php?no_cache=" + Math.random()),
                data: {type: data_type, show_disconnect: show_disconnect, data_id: data_id, show_sponsors: 0, channel_id: channelGlobalID(), data_friend_section: ''},
                success: function (data) {
                    var jres = null;
                    try {
                        jres = $.parseJSON(data);
                    } catch (Ex) {
                        closeFancyBox();
                    }
                    if (!jres) {
                        closeFancyBox();
                        return;
                    }
                    $('#sharepopup').html(jres.data);
                }
            });
        }
    });
}
function initReportChannelFunctions(obj, data_type, data_id) {
    obj.fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            var show_disconnect = 0;
            if ($('.connectstyle').hasClass('disconnectChannel')) {
                show_disconnect = 1;
            }
            $('#sharepopup').html('');
            var str = '<div class="sharepopup_container" style="margin-left:34px; width:506px; height:312px"></div>';
            $('#sharepopup').html(str);

            disconnect_notification_toggle = 0;
            $.ajax({
                type: "POST",
                cache: false,
                url: ReturnLink("/ajax/popup_report_data.php?no_cache=" + Math.random()),
                data: {type: data_type, show_disconnect: show_disconnect, data_id: data_id, show_sponsors: 0, channel_id: channelGlobalID(), data_friend_section: ''},
                success: function (data) {
                    var jres = null;
                    try {
                        jres = $.parseJSON(data);
                    } catch (Ex) {
                        closeFancyBox();
                    }
                    if (!jres) {
                        closeFancyBox();
                        return;
                    }
                    $('#sharepopup').html(jres.data);
                }
            });
        }
    });
}
function initLogReportFunctions(obj, $parent) {
    obj.fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            var data_type = $parent.attr('data-type');
            var data_id = $parent.attr('data-id');
            var show_disconnect = 0;
            if ($('.connectstyle').hasClass('disconnectChannel')) {
                show_disconnect = 1;
            }
            $('#sharepopup').html('');
            var str = '<div class="sharepopup_container" style="margin-left:34px; width:506px; height:312px"></div>';
            $('#sharepopup').html(str);

            disconnect_notification_toggle = 0;
            $.ajax({
                type: "POST",
                cache: false,
                url: ReturnLink("/ajax/popup_report_data.php?no_cache=" + Math.random()),
                data: {type: data_type, show_disconnect: show_disconnect, data_id: data_id, show_sponsors: 0, channel_id: channelGlobalID(), data_friend_section: ''},
                success: function (data) {
                    var jres = null;
                    try {
                        jres = $.parseJSON(data);
                    } catch (Ex) {
                        closeFancyBox();
                    }
                    if (!jres) {
                        closeFancyBox();
                        return;
                    }
                    $('#sharepopup').html(jres.data);
                }
            });
        }
    });
}
function initPostEdit(){
    $('.edit_post_social').each(function (index, element) {
        var $This = $(this);

        var vid = $This.attr('data-id');
        var channelid = $This.attr('data-channelid');
        var isMedia = $This.attr('data-ismedia');
        var width = isMedia === '0' ? '269' : '883';
        $This.attr("href", ReturnLink('parts/user-viewpost.php?id=' + vid + '&channelid=' + channelid + '&is_post=1'));

        $This.fancybox({
            "padding": 0,
            "margin": 0,
            "width": width,
            "height": '604',
            "transitionIn": "none",
            "transitionOut": "none",
            "autoSize": false,
            "scrolling": 'no',
            "type": "iframe"
        });

    });
}
function initSocialFunctions() {

    // Update the share box from the select box.	
    $(document).on('change', "#share_select", function () {
        var media_object = $(this).closest('.social_data_all');
        var $parent = $(this).parent().parent();
        var value = media_object.find("#share_select").val();
        var text = media_object.find('#share_select option:selected').attr("data-text");

        // Remove the previous selection.
        media_object.find("#option_1").remove();
        media_object.find("#option_2").remove();
        media_object.find("#option_3").remove();
        media_object.find("#option_4").remove();
        media_object.find("#option_5").remove();
        media_object.find("#option_6").remove();

        // Add the new selection with exceptions.
        if (value != 4 && value != 5) {
            var selected_item = '<div class="peoplesdata formttl13" id="option_' + value + '" data-email="" data-id=""><div class="peoplesdatainside">' + text + '</div><div class="peoplesdataclose"></div></div>';
            $parent.find('.emailcontainer_boxed_share').prepend(selected_item);
            // Resize the box.
            /*var peoplesdatafirst = $parent.find('.emailcontainer_boxed_share .peoplesdata').first();
            var peoplesdatawidth = peoplesdatafirst.find('.peoplesdatainside').width();
            if (peoplesdatawidth > 70) {
                peoplesdatawidth = peoplesdatawidth + 10;
            }
            peoplesdatafirst.css("width", (peoplesdatawidth + 20) + "px");*/
        }
    });
    $(document).on('click', ".social_link_a_inactive", function (event) {
        event.preventDefault();
        TTAlert({
            msg: t('you have to sign in, in order to access a tuber page'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('register'),
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });

    $(document).on('click', ".likeDiv", function () {
        var media_object = $(this).closest('.social_data_all');
        var mediaid = media_object.attr("data-id");
        var action = $(this).attr('data-action');
        var media_type = media_object.attr('data-type');
        if (action == 'like') {
            $.ajax({
                url: ReturnLink('/ajax/ajax_like_channel_media.php'),
                data: {media: media_type, channelid: channelGlobalID(), mediaid: mediaid},
                type: 'post',
                success: function (data) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }
                    if (ret.error) {
//                        TTAlert({
//                            msg: ret.error,
//                            type: 'alert',
//                            btn1: t('ok'),
//                            btn2: '',
//                            btn2Callback: null
//                        });
                        return;
                    }

                    // Refresh the likes list.
                    initLikes(media_object);
                }
            });
        } else if (action == 'unlike') {
            $.ajax({
                url: ReturnLink('/ajax/ajax_unlike_channel_media.php'),
                data: {media: media_type, channelid: channelGlobalID(), mediaid: mediaid},
                type: 'post',
                success: function (data) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }
                    if (ret.error) {
                        TTAlert({
                            msg: ret.error,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }
                    // Refresh the likes list.
                    initLikes(media_object);
                    // Hide and show the box (IE fix).
                    media_object.find(".likesDiv").addClass("hide");
                    media_object.find(".likesDiv").removeClass("hide");

                }
            });
        }
    });

    $(document).on('click', "#share_boxed_send", function () {
        var media_object = $(this).closest('.social_data_all');
        var mediaid = media_object.attr('data-id');
        var media_type = media_object.attr('data-type');
        var $this = $(this);
        var $parent = $this.parent().parent();
        var invite_msg = getObjectData($parent.find("#invitetext"));
        var inviteArray = new Array();
        $parent.find('.peoplecontainer .peoplesdata').each(function () {
            var obj = $(this);
            var inviteType = 0;
            if (obj.attr('id') == "option_1") {
                inviteArray.push({friends: 1});
            } else if (obj.attr('id') == "option_3") {
                inviteArray.push({followers: 1});
            } else if (obj.attr('id') == "option_2") {
                inviteArray.push({friendsandfollowers: 1});
            } else if (obj.attr('id') == "option_6") {
                inviteArray.push({connections: 1});
            } else if (obj.attr('data-email') != '') {
                inviteArray.push({email: obj.attr('data-email')});
            } else if (parseInt(obj.attr('data-id')) != 0) {
                inviteArray.push({id: obj.attr('data-id')});
            }
        });
        if (inviteArray.length == 0) {
            return;
        }

        TTCallAPI({
            what: 'social/share',
            data: {entity_type: media_type, entity_id: mediaid, share_with: inviteArray, share_type: SOCIAL_SHARE_TYPE_SHARE, msg: invite_msg, channel_id: channelGlobalID(),addToFeeds:1},
            callback: function (ret) {
                // Refresh the shares list.
                initShares(media_object);
                // Reset the share box.
                resetSharesBox(media_object);
            }
        });
    });

    $(document).on('click', "#sponsor_boxed_send", function () {
        var media_object = $(this).closest('.social_data_all');
        var mediaid = media_object.attr('data-id');
        var media_type = media_object.attr('data-type');
        var $this = $(this);
        var $parent = $this.parent().parent();
        var invite_msg = getObjectData($parent.find("#invitetext"));
        var inviteArray = new Array();
        $parent.find('.peoplecontainer .peoplesdata').each(function () {
            var obj = $(this);

            var inviteType = 0;
            if (obj.attr('id') == "option_1") {
                inviteArray.push({friends: 1});
            } else if (obj.attr('id') == "option_3") {
                inviteArray.push({followers: 1});
            } else if (obj.attr('id') == "option_2") {
                inviteArray.push({friendsandfollowers: 1});
            } else if (obj.attr('id') == "option_6") {
                inviteArray.push({connections: 1});
            } else if (obj.attr('data-email') != '') {
                inviteArray.push({email: obj.attr('data-email')});
            } else if (parseInt(obj.attr('data-id')) != 0) {
                inviteArray.push({id: obj.attr('data-id')});
            }
        });

        TTCallAPI({
            what: 'social/sponsor',
            data: {entity_type: media_type, entity_id: mediaid, share_with: inviteArray, share_type: SOCIAL_SHARE_TYPE_SPONSOR, msg: invite_msg, channel_id: channelGlobalID(), sponsor_id: $(this).attr('data-defaultcid')},
            callback: function (ret) {
                // Refresh the shares list.
                initSponsors(media_object);
                // Reset the share box.
                resetSponsorsBox(media_object);
            }
        });
    });

    $(document).on('click', ".hideDiv", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        showhideCommentsItem($(this), 0, media_type);
    });
    $(document).on('click', ".hideDiv_join", function () {
        showhideJoinItem($(this), 0);
    });
    $(document).on('click', ".hideDiv_viewer", function () {
        var $parent = $(this).parent().parent().parent();
        $parent.remove();
        if ($('.showMore_comments').css('display') != "none") {
            $('.showMore_comments').click();
        }
    });

    $(document).on('click', ".removeDiv", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        if ($(this).hasClass('plus')) {
            addCommentsItem($(this), media_type);
        } else {
            removeCommentsItem($(this), media_type);
        }
    });

    $(document).on('click', ".removeDiv_join", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        if ($(this).hasClass('plus')) {
            addJoinItem($(this), media_type);
        } else {
            removeJoinItem($(this), media_type);
        }
    });

    $(document).on('click', ".removeDiv_sponsor", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        if ($(this).hasClass('plus')) {
            addSponsorItem($(this), media_type);
        } else {
            removeSponsorItem($(this), media_type);
        }
    });

    $(document).on('click', ".removeDiv_share", function () {
        removeShare($(this));
    });
    $(document).on('click', ".removeDiv_rate", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        removeRate($(this), media_type);
    });

    $(document).on('keyup', ".notsigned", function (event) {
        var $this = $(this).parent().parent().parent();
        $this.find('.disabledmessage').removeClass('displaynone');
    });
    $(document).on('keydown', "textarea.mention", function (event) {
        var $input = $(this);
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        if (event.keyCode == 13) {
            if ($input.hasClass('mention_edit')) {
                return;
            }
            var comdata = $(this).closest('.social_data_all').find('div.mentions div').html();
            var lilength = $('.mentions-autocomplete-list ul').children().length;
            if ( comdata.length == 0  || comdata=='' ) {
                $(this).val('');
                $('div.mentions div').html('');
                $(this).blur();
                $(this).css('height', '28px');
                return;
            }
            if (lilength == 0) {
                var comments_user_array = new Array();
                $('div.mentions div .comments_user').each(function () {
                    comments_user_array.push($(this).attr('data-id'));
                });

                add_Comments_Item($(this).closest('.social_data_all'), $(this).closest('.social_data_all').find('div.mentions div').html(), comments_user_array);
                $(this).val('');
                $('div.mentions div').html('');
                $(this).blur();
                $(this).css('height', '28px');
            }
        }
    });

    $(document).on('click', ".likesDiv .showMore_like", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');

        currentpage_like = parseInt($(this).closest('.social_data_all').attr("data-page-like"));
        currentpage_like++;
        $(this).closest('.social_data_all').attr("data-page-like", currentpage_like);

        getItemsLikesRelated($(this).closest('.social_data_all'));
    });
    $(document).on('click', ".commentsDiv .showMore_comments", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');

        currentpage_comments = parseInt($(this).closest('.social_data_all').attr("data-page-comments"));
        currentpage_comments++;
        $(this).closest('.social_data_all').attr("data-page-comments", currentpage_comments);

        getItemsCommentsRelated($(this).closest('.social_data_all'));
    });
    $(document).on('click', ".ratesDiv .showMore_rates", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');

        currentpage_rates = parseInt($(this).closest('.social_data_all').attr("data-page-rates"));
        currentpage_rates++;
        $(this).closest('.social_data_all').attr("data-page-rates", currentpage_rates);

        getItemsRatesRelated($(this).closest('.social_data_all'));
    });
    $(document).on('click', ".sharesDiv .showMore_shares", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');

        currentpage_shares = parseInt($(this).closest('.social_data_all').attr("data-page-shares"));
        currentpage_shares++;
        $(this).closest('.social_data_all').attr("data-page-shares", currentpage_shares);

        getItemsSharesRelated($(this).closest('.social_data_all'));
    });
    $(document).on('click', ".sponsorsDiv .showMore_sponsors", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');

        currentpage_sponsors = parseInt($(this).closest('.social_data_all').attr("data-page-sponsors"));
        currentpage_sponsors++;
        $(this).closest('.social_data_all').attr("data-page-sponsors", currentpage_sponsors);

        getItemsSponsorsRelated($(this).closest('.social_data_all'));
    });
    $(document).on('click', ".joinsDiv .showMore_joins", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');

        currentpage_join = parseInt($(this).closest('.social_data_all').attr("data-page-join"));
        currentpage_join++;
        $(this).closest('.social_data_all').attr("data-page-join", currentpage_join);

        getItemsJoinRelated($(this).closest('.social_data_all'));
    });

    $(document).on('click', ".closeDiv", function () {
        var parent = $(this).parent();
        var ThisIMG = $(".btn").find("div.selected");
        if (ThisIMG.length > 0) {
            try {
                resetSharesBox($(this).closest('.social_data_all'));
            } catch (err) {
                //console.log(err.message);
            }
            try {
                resetSponsorsBox($(this).closest('.social_data_all'));
            } catch (err) {
                //console.log(err.message);
            }
            parent.addClass("hide");
            ThisIMG.removeClass("selected");
        }
    });
    $(document).on('click', ".commentBox .comments_user", function () {
        $this = $(this);
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/check_channel_owner.php'),
            data: {id: $this.attr('data-id'), channel_id: channelGlobalID()},
            type: 'post',
            success: function (data) {
                if (data != false) {
                    document.location.href = ReturnLink('/channel/' + data);
                } else {
                    document.location.href = ReturnLink('/profile/' + $this.attr('data-id'));
                }
                $('.upload-overlay-loading-fix').hide();
            }
        });
    });
    $(document).on('click', ".comments_edit", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        var $this = $(this);
        if ($this.hasClass('active')) {
            return;
        }
        var $parent = $this.parent().parent();
        var id = $this.attr('data-id');
        $('.likersData_comments_unit').each(function (index, element) {
            var $this = $(this);
            $this.find('.editcommentsDiv').hide();
            $this.find('.editcommentsDiv').html('<div class="writecommentDiv writecommentDivAuto" style="margin-left:3px;"><div class="examples"><textarea class="mention_edit mention textareaclass" placeholder="' + $this.find('.editcommentsDiv').attr('data-placeholder') + '" style="height: 28px; overflow: hidden;"></textarea></div></div>');
            $this.find('.editcommentsDiv').css('height', '50px');
            $this.find('.commentBox').css('height', 'auto');
            $this.find('.comments_edit').removeClass('active');
        });

        var divheight = $parent.find('.commentBox').height() + 15;
        $parent.find('.editcommentsDiv').css('height', divheight + 'px');
        $parent.find('.editcommentsDiv').attr('data-id', id);
        //$parent.find('.editcommentsDiv').show();
        getItemCommentText(id, $parent, media_type);
    });
    $(document).on('click', "#album_reportbut", function () {
        $('#commentDiv .shadow, .commentDivClass .shadow').each(function (index, element) {
            if (!$(this).hasClass('hide')) {
                $(this).find('.closeDiv').click();
            }
        });
        $('#comment_reportDiv').hide();
        $('#album_reportDiv').show();
    });
    $(document).on('click', ".report_button_comment_popup_view", function () {
        $('#comment_reportDiv').attr('data-id', $(this).attr('data-id'));
        $('#commentDiv .shadow, .commentDivClass .shadow').each(function (index, element) {
            if (!$(this).hasClass('hide')) {
                $(this).find('.closeDiv').click();
            }
        });
        $('#album_reportDiv').hide();
        $('#comment_reportDiv').show();
    });
    $(document).on('click', ".share_popup_view_butBRCancel", function () {
        $(this).closest('.social_data_all').find('.buttons .btn').first().click();
    });

    $(document).on('click', ".sendButton_popup_view", function () {
        if ($('#disconnectBox').hasClass('active')) {
            // do disconnect
            $('.share_popup_view_butBRCancel').click();
            disonnectuser();
        } else if ($('.optionBox1,.optionBox2,.optionBox3,.optionBox4,.optionBox5,.optionBox6,.optionBox7,.optionBox8,.optionBox9,.optionBox10').hasClass('active')) {
            // send report
            $("#disconnectBox").addClass('reportsent');
            $(".radioNotification").hide();
            $(".sentNotification").show();
            $(".okButton").show();
        } else {
            $(".radioNotification").show();
        }
    });

    $(document).on('click', ".hideText_unhide_join span", function () {
        showhideJoinItem($(this), 1);
    });
    $(document).on('click', ".hideText_unhide span", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        showhideCommentsItem($(this), 1, media_type);
    });
    $(document).on('click', '.comments_like_but', function () {
        var $this = $(this);
        var comment_id = $(this).attr('data-id');

        TTCallAPI({
            what: 'social/like', // Destination address (uapi_inc/social/like.php).
            data: {entity_id: comment_id, entity_type: SOCIAL_ENTITY_COMMENT, like_value: 1, channel_id: channelGlobalID()},
            callback: function (data) {
                var lkcount = parseInt(parseInt($this.attr('data-count')) + 1);
                $this.attr('data-count', lkcount);
                var likestr = " "+ t('likes');
                if(lkcount == 1){
                    likestr = " "+ t(' like');
                }
                $this.parent().find('.cmts_likeDivyellowTxt').html(lkcount + likestr );
                $this.removeClass('comments_like_but');
                $this.addClass('comments_unlike_but');

                var item_list = $this.closest('.log_item_list');
                if (item_list.find('.news_count_l').length > 0) {
                    var nb_likes = data.nb_likes;
                    if (nb_likes > 1 || nb_likes == 0) {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('likes'));
                    } else {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('like'));
                    }
                }
            }
        });

    });
    $(document).on('click', ".likepop, .ratepop", function () {
        $(this).parent().find(".meStanDiv").removeClass('displaynone');
    });

    // Show the error message if a channel is required and not found (for sponsors).
    $(document).on('click', '#sponsor_disabled_click_handler', function () {
        $(this).closest('.sponsorsDiv').find("#sponsors_require_channel_error_msg").removeClass('displaynone');
    });

    // Show the error message for the join if the user is not logged in (for events).
    $(document).on('click', '.joins_disabled_radio', function () {
        $(this).closest('.joinsDiv').find("#joins_login_error_msg").removeClass('displaynone');
    });


    $(document).on('click', '.comments_unlike_but', function () {
        var $this = $(this);
        var comment_id = $(this).attr('data-id');

        TTCallAPI({
            what: 'social/like', // Destination address (uapi_inc/social/like.php).
            data: {entity_id: comment_id, entity_type: SOCIAL_ENTITY_COMMENT, like_value: 0},
            callback: function (data) {
                var lkcount = parseInt(parseInt($this.attr('data-count')) - 1);
                $this.attr('data-count', lkcount);
                var likestr = " "+ t('likes');
                if(lkcount == 1){
                    likestr = " "+ t(' like');
                }
                $this.parent().find('.cmts_likeDivyellowTxt').html(lkcount + likestr );
                $this.removeClass('comments_unlike_but');
                $this.addClass('comments_like_but');

                var item_list = $this.closest('.log_item_list');
                if (item_list.find('.news_count_l').length > 0) {
                    var nb_likes = data.nb_likes;
                    if (nb_likes > 1 || nb_likes == 0) {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('likes'));
                    } else {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('like'));
                    }
                }
            }
        });

    });

    $(document).on('click', ".btn_txt", function () {
        if ($(this).hasClass('opacitynone'))
            return;
        $(this).prev().click();
    });
    $(document).on('click', ".btn", function () {
        var ThisImage = $(this).find("div").first();

        $('#comment_reportDiv').hide();
        $('#album_reportDiv').hide();
        if (!ThisImage.hasClass('selected')) {
            $('#commentDiv .shadow, .commentDivClass .shadow').each(function (index, element) {
                if (!$(this).hasClass('hide')) {
                    $(this).find('.closeDiv').click();
                }
            });
            var thisID = ThisImage.attr('id');
            if( ThisImage.hasClass('likes') ){
                thisID = "likes";
            }else if( ThisImage.hasClass('description') ){
                thisID = "description";
            }else if( ThisImage.hasClass('comments') ){
                thisID = "comments";
            }else if( ThisImage.hasClass('shares') ){
                thisID = "shares";
            }else if( ThisImage.hasClass('rates') ){
                thisID = "rates";
            }else if( ThisImage.hasClass('joins') ){
                thisID = "joins";
            }else if( ThisImage.hasClass('sponsors') ){
                thisID = "sponsors";
            }
            if( thisID == "likes" && $(this).closest('.social_data_all').find(".likesDiv .commentsAll .commentsAll_inside").length==0 ){                
                initLikes( $(this).closest('.social_data_all') );
            }else if( thisID == "shares" && $(this).closest('.social_data_all').find(".sharesDiv .commentsAll .commentsAll_inside").length==0 ){
                initShares( $(this).closest('.social_data_all') );
            }else if( thisID == "comments" && $(this).closest('.social_data_all').find(".commentsDiv .commentsAll .commentsAll_inside").length==0 ){
                initComments( $(this).closest('.social_data_all') );
            }else if( thisID == "rates" && $(this).closest('.social_data_all').find(".ratesDiv .commentsAll .commentsAll_inside").length==0 ){
                addRaty( $(this).closest('.social_data_all') );
                initRates( $(this).closest('.social_data_all') );
            }else if( thisID == "joins" && $(this).closest('.social_data_all').find(".joinsDiv .commentsAll .commentsAll_inside").length==0 ){
                initJoins( $(this).closest('.social_data_all') );
            }else if( thisID == "sponsors" && $(this).closest('.social_data_all').find(".sponsorsDiv .commentsAll .commentsAll_inside").length==0 ){
                initSponsors( $(this).closest('.social_data_all') );
            }
            
            if ($("." + thisID + "Div"))
                $(this).closest('.social_data_all').find("." + thisID + "Div").removeClass("hide");
            if (pagename == "channel_log") {
                $('.log_item_list').css('z-index', 0);
                $(this).closest('.log_item_list').css('z-index', 20);
            }

            ThisImage.addClass('selected');
            if (thisID == "description") initscrollPaneSocialChannelDesc($(this).closest('.social_data_all').find(".scrollpane_description"), false);
            
            // Init the shares autocomplete for the icon image "shares".
            if ( thisID == "shares" && (parseInt(user_Is_channel) == 0 || parseInt(is_owner) == 1)) {
                resetSelectedUsers();
                addshareuserautocomplete($(this).closest('.social_data_all').find('#addmoretext_brochure'));
            }
            // Init the sponsor autocomplete for the icon image "sponsors".
            else if (thisID == "sponsors" && parseInt(user_Is_channel) == 1 && parseInt(is_owner) == 0) {
                
                //resetSelectedUsers();
                //addsponsorautocomplete($(this).closest('.social_data_all').find('#addmoretext_sponsors'));
                $(this).closest('.social_data_all').find('#addmoretext_sponsors').keydown(function(event){
                    var thisobj =$(this);
                    var email_container_privacy = thisobj.parent().parent();
                    var code = (event.keyCode ? event.keyCode : event.which);
                    if(code === 13) { //Enter keycode or tab
                        if(validateEmail(thisobj.val())){
                            var friendstr='<div class="peoplesdata formttl" data-id="" data-email="'+thisobj.val()+'"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">'+thisobj.val()+'</div><div class="peoplesdataclose"></div></div>';
                            email_container_privacy.prepend(friendstr);
//				
                            thisobj.val('');
                            thisobj.blur();

                            var height = $('#inviteForm .formContainer').height()+74;
                            $('#inviteForm').css('height',height+"px");
                            event.preventDefault();
                        }
                    }
                });
            }
            // Special conditions for the "joins" section..
            else if (thisID == "joins") {
                resetJoinsItems($(this).closest('.social_data_all'));
                setJoinsItems($(this));
                getJoinDetails($(this).closest('.social_data_all'));
            }
            if (pagename == "channel_log") {
                initScrollLog();
            }
        }
    });

    // ** Save changes to the "join" section on change of any of its fields.
    $(document).on('click', "input:radio[name=event_join]", function () {
        saveJoinChange($(this).closest('.social_data_all').attr('data-id'), $(this).closest('.social_data_all').attr('data-type'), $(this));
    });

    $(document).on('change', "#join_guests_number", function () {
        saveJoinChange($(this).closest('.social_data_all').attr('data-id'), $(this).closest('.social_data_all').attr('data-type'), $(this));
    });
    // ** End: Save join changes.

    initCommentData();
}
function initCommentData() {
    $('textarea.mention').mentionsInput({
        onDataRequest: function (mode, query, callback) {
            $.post(ReturnLink('/ajax/channel-autocomplete-comments.php'), {term: query, channel_id: channelGlobalID(), media: $(this).closest('.social_data_all').attr('data-type'), mediaid: $(this).closest('.social_data_all').attr('data-id')}, function (data) {

                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }

                var searcharray = new Array();
                for (var i = 0; i < ret.length; i++) {
                    var avatar = ret[i].profile_Pic;
                    searcharray.push({'id': ret[i].user_id, 'name': ret[i].username, 'avatar': avatar, 'type': ''});
                }
                var data = searcharray;

                data = _.filter(data, function (item) {
                    return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1
                });

                callback.call(this, data);
            });
        }
    });
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        $('textarea').css('text-indent', '-3px');
    }
}
function initCommentReportFunctions(obj) {
    obj.fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            var data_type = obj.attr('data-type');
            var data_id = obj.attr('data-id');
            var show_disconnect = 0;
            $('#sharepopup').html('');
            var str = '<div class="sharepopup_container" style="margin-left:34px; width:506px; height:312px"></div>';
            $('#sharepopup').html(str);

            disconnect_notification_toggle = 0;
            $.ajax({
                type: "POST",
                cache: false,
                url: ReturnLink("/ajax/popup_report_data.php?no_cache=" + Math.random()),
                data: {type: data_type, show_disconnect: show_disconnect, data_id: data_id, show_sponsors: 0, channel_id: channelGlobalID(), data_friend_section: ''},
                success: function (data) {
                    var jres = null;
                    try {
                        jres = $.parseJSON(data);
                    } catch (Ex) {
                        closeFancyBox();
                    }
                    if (!jres) {
                        closeFancyBox();
                        return;
                    }
                    $('#sharepopup').html(jres.data);
                }
            });
        }
    });
}
function addRaty(obj) {
    obj.find('#myrating').raty({
        path: AbsolutePath + '/images',
        hints: ['Very Poor', 'Poor', 'Neutral', 'Good', 'Very Good'],
        starOn: 'ratingbig_1.png',
        starOff: 'ratingbig_0.png',
        score: obj.find('#myrating_score').val(),
        click: function (score, evt) {
            ////////////////click option

            TTCallAPI({
                what: ReturnLink('/ajax/modal_newsfeed_rate.php'),
                data: {entity_id: $(this).closest('.social_data_all').attr('data-id'), entity_type: $(this).closest('.social_data_all').attr('data-type'), score: score, globchannelid: channelGlobalID()},
                ret: 'json',
                callback: function (resp) {
                    if (resp.status === 'error') {
                        TTAlert({
                            msg: resp.msg,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }
                    initRates($(evt.currentTarget).closest('.social_data_all'));
                    var item_list = obj.closest('.log_item_list');
                    if (item_list.find('.news_count_r').length > 0) {
                        var nb_ratings = resp.nb_ratings;
                        if (nb_ratings > 1 || nb_ratings == 0) {
                            item_list.find('.news_count_r').html(nb_ratings + " " + t('ratings'));
                        } else {
                            item_list.find('.news_count_r').html(nb_ratings + " " + t('rating'));
                        }
                    }
                    if (item_list.find('.log_ratings').length > 0) {
                        item_list.find('.log_ratings').attr('id', 'popup_view_rate' + resp.rating);
                    }
                }
            });

        }
    });
}
function getItemsLikesRelated(media_object) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_like_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: media_object.attr("data-page-like"), globchannelid: channelGlobalID()},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            } else {
                var myData = ret.data;
                var divNum = parseInt(ret.count);
                var is_liked = ret.is_liked;

                media_object.find('.likesDiv .containerDiv .commentsAll .commentsAll_inside').append(myData);
                media_object.find(".likesNumber").html(divNum);

                if (divNum <= 7) {
                    media_object.find(".likesDiv .showMore_like").hide();
                } else {
                    currentpage_like = parseInt(media_object.attr("data-page-like"));
                    if ((currentpage_like + 1) * 7 >= divNum) {
                        media_object.find(".likesDiv .showMore_like").hide();
                    } else {
                        media_object.find(".likesDiv .showMore_like").show();
                    }
                }
                media_object.find(".likesDiv .commentsAll").addClass("scrollpane_likes");
                initscrollPaneSocialChannel(media_object.find(".scrollpane_likes"), true);
                initLikersData();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function getItemsRatesRelated(media_object) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_rate_channel_media.php?no_cache=' + Math.random()),
        data: {
            media: media_object.attr('data-type'),
            mediaid: media_object.attr('data-id'),
            page: media_object.attr("data-page-rates"),
            globchannelid: channelGlobalID(),
            cache: Math.random()
        },
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            } else {
                var myData = ret.data;
                var divNum = parseInt(ret.count);
                var rated_value = ret.rated_value;
                media_object.find('.popup_view_rate').attr('id', 'popup_view_rate' + rated_value);
                media_object.find('.ratesDiv .containerDiv .commentsAll .commentsAll_inside').append(myData);
                media_object.find(".ratesNumber").html(divNum);

                if (divNum <= 7) {
                    media_object.find(".ratesDiv .showMore_rates").hide();
                } else {
                    currentpage_rates = parseInt(media_object.attr("data-page-rates"));
                    if ((currentpage_rates + 1) * 7 >= divNum) {
                        media_object.find(".ratesDiv .showMore_rates").hide();
                    } else {
                        media_object.find(".ratesDiv .showMore_rates").show();
                    }
                }
                media_object.find(".ratesDiv .commentsAll").addClass("scrollpane_rates");
                initscrollPaneSocialChannel(media_object.find(".scrollpane_rates"), true);
                initLikersDataRates();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}

function getItemsSharesRelated(media_object) {
    $('.upload-overlay-loading-fix').show();
    var mylimit = 7;
    if (parseInt(user_Is_channel) == 0 && parseInt(user_is_logged) == 1) {
        mylimit = 4;
    }
    if (media_object.find('.sharesDiv #share_select1').length > 0 || media_object.find('.sharesDiv #share_select').length == 0) {
        mylimit = 7;
    }
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_shares_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: media_object.attr("data-page-shares"), globchannelid: channelGlobalID(), mylimit: mylimit},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;

                media_object.find('.sharesDiv .containerDiv .commentsAll .commentsAll_inside').append(myData);
                media_object.find(".sharesNumber").html(divNum);

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_s').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_s').html(divNum + " " + t('shares'));
                    } else {
                        item_list.find('.news_count_s').html(divNum + " " + t('share'));
                    }
                }

                if (divNum <= mylimit) {
                    media_object.find(".sharesDiv .showMore_shares").hide();
                } else {
                    currentpage_shares = parseInt(media_object.attr("data-page-shares"));
                    if ((currentpage_shares + 1) * mylimit >= divNum) {
                        media_object.find(".sharesDiv .showMore_shares").hide();
                    } else {
                        media_object.find(".sharesDiv .showMore_shares").show();
                    }
                }
                media_object.find(".sharesDiv .commentsAll").addClass("scrollpane_shares");
                initscrollPaneSocialChannel(media_object.find(".scrollpane_shares"), true);
                initLikersDataShares();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}

// Fill the "show more" for sponsors.
function getItemsSponsorsRelated(media_object) {
    $('.upload-overlay-loading-fix').show();

    $.ajax({
        url: ReturnLink('/ajax/ajax_all_sponsors_channel_media.php'),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: media_object.attr("data-page-sponsors"), globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;


                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_sp').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_sp').html(divNum + " " + t('sponsors'));
                    } else {
                        item_list.find('.news_count_sp').html(divNum + " " + t('sponsor'));
                    }
                }


                media_object.find('.sponsorsDiv .containerDiv .commentsAll .commentsAll_inside').append(myData);
                media_object.find(".sponsorsNumber").html(divNum);
                var mylimit = 6;
                if (parseInt(user_Is_channel) == 1 && parseInt(is_owner) == 0) {
                    mylimit = 4;
                }

                if (divNum <= mylimit) {
                    media_object.find(".sponsorsDiv .showMore_comments").hide();
                } else {
                    currentpage_sponsors = parseInt(media_object.attr("data-page-sponsors"));
                    if ((currentpage_sponsors + 1) * mylimit >= divNum) {
                        media_object.find(".sponsorsDiv .showMore_sponsors").hide();
                    } else {
                        media_object.find(".sponsorsDiv .showMore_sponsors").show();
                    }
                    media_object.find(".sponsorsDiv .commentsAll").addClass("scrollpane_sponsors");
                    initscrollPaneSocialChannel(media_object.find(".scrollpane_sponsors"), true);
                }
                initLikersDataSponsors();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
// Fill the "show more" for join.
function getItemsJoinRelated(media_object) {
    $('.upload-overlay-loading-fix').show();

    $.ajax({
        url: ReturnLink('/ajax/ajax_all_joins_channel_media.php'),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: media_object.attr("data-page-join"), globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_j').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_j').html(divNum + " " + t('joining guests'));
                    } else {
                        item_list.find('.news_count_j').html(divNum + " " + t('joining guest'));
                    }
                }

                media_object.find('.joinsDiv .containerDiv .commentsAll .commentsAll_inside').append(myData);
                media_object.find(".joinsNumber").html(divNum);

                var mylimit = 6;
                if (parseInt(user_Is_channel) == 0 && parseInt(user_is_logged) == 1) {
                    mylimit = 5;
                }

                if (divNum <= mylimit) {
                    media_object.find(".joinsDiv .showMore_joins").hide();
                } else {
                    currentpage_join = parseInt(media_object.attr("data-page-join"));
                    //console.log(currentpage_join+"]["+mylimit);
                    if ((currentpage_join + 1) * mylimit >= divNum) {
                        media_object.find(".joinsDiv .showMore_joins").hide();
                    } else {
                        media_object.find(".joinsDiv .showMore_joins").show();
                    }
                    media_object.find(".joinsDiv .commentsAll").addClass("scrollpane_join");
                    initscrollPaneSocialChannel(media_object.find(".scrollpane_join"), true);
                }
                initLikersDataJoins();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}

function add_Comments_Item(media_object, val, arr) {
    $('.upload-overlay-loading-fix').show();
    TTCallAPI({
        what: '/social/comment/add',
        data: {entity_id: media_object.attr('data-id'), entity_type: media_object.attr('data-type'), comment: val, comments_user_array: arr, channel_id: channelGlobalID()},
        callback: function (resp) {
            if (resp.status === 'error') {
                TTAlert({
                    msg: resp.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            media_object.find('.commentsDiv .containerDiv .commentsAll .commentsAll_inside').html('');
            currentpage_comments = 0;
            media_object.attr("data-page-comments", 0);

            getItemsCommentsRelated(media_object);
            if (pagename == "media_page") {
                try {
                    ReloadComments();
                } catch (e) {
                }
            }            
        }
    });
}
function resetSharesBox(media_object) {
    if (media_object.find('.sharesDiv').length == 0) {
        return;
    }
    media_object.find(".sharesDiv #invitetext").val('');
    media_object.find(".sharesDiv #invitetext").blur();
    media_object.find(".sharesDiv #share_select").val(0);

    media_object.find(".sharesDiv .emailcontainer_boxed_share").html('<div class="addmore"><input name="addmoretext_brochure" id="addmoretext_brochure" type="text" class="addmoretext_css" data-value="' + t("add more") + '" value="' + t("add more") + '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div>');
    resetSelectedUsers();

    addshareuserautocomplete(media_object.find('#addmoretext_brochure'));
}
function resetSponsorsBox(media_object) {
    if (media_object.find('.sponsorsDiv').length == 0) {
        return;
    }
    media_object.find(".sponsorsDiv #invitetext").val('');
    media_object.find(".sponsorsDiv #invitetext").blur();
    media_object.find(".sponsorsDiv #share_select").val(0);

    media_object.find(".sponsorsDiv .emailcontainer_boxed_share").html('<div class="addmore"><input name="addmoretext_sponsors" id="addmoretext_sponsors" type="text" class="addmoretext_css" data-value="' + t("add more") + '" value="' + t("add more") + '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div>');
    //resetSelectedUsers();
    //addsponsorautocomplete(media_object.find('#addmoretext_sponsors'));
    media_object.find('#addmoretext_sponsors').keydown(function(event){
        var thisobj =$(this);
        var email_container_privacy = thisobj.parent().parent();
        var code = (event.keyCode ? event.keyCode : event.which);
        if(code === 13) { //Enter keycode or tab
            if(validateEmail(thisobj.val())){
                var friendstr='<div class="peoplesdata formttl" data-id="" data-email="'+thisobj.val()+'"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">'+thisobj.val()+'</div><div class="peoplesdataclose"></div></div>';
                email_container_privacy.prepend(friendstr);
//				
                thisobj.val('');
                thisobj.blur();

                var height = $('#inviteForm .formContainer').height()+74;
                $('#inviteForm').css('height',height+"px");
                event.preventDefault();
            }
        }
    });
}
function initscrollPaneSocialChannel(obj, _flag) {
   if( !obj.hasClass('jspScrollable') ){
        obj.jScrollPane({ autoReinitialise: true });
        if (_flag) {
            try {
                var jscrol_api = obj.data('jsp');
                jscrol_api.scrollToBottom(true);
            } catch (e) {

            }
        }
    }
}
function initscrollPaneSocialChannelDesc(obj, _flag) {
    try {
        if (obj.height() >= obj.css('max-height').replace('px', '')) {
            obj.jScrollPane();
            if (_flag) {
                var jscrol_api = obj.data('jsp');
                jscrol_api.scrollToBottom(true);
            }
        }
    } catch (e) {
        obj.jScrollPane();
        if (_flag) {
            try {
                var jscrol_api = obj.data('jsp');
                jscrol_api.scrollToBottom(true);
            } catch (e) {

            }
        }
    }
}
function showhideCommentsItem(obj, val, media_type) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_show_hide_comment_brochure.php'),
        data: {id: obj.attr('data-id'), data: val, media_type: media_type},
        type: 'post',
        success: function (data) {
            if (data != 0) {
                var $parent = obj.parent().parent().parent();
                if (val == 0) {
                    $parent.find('.likersData_comments').addClass('displaynone');
                    $parent.find('.likersData_comments_unhide').removeClass('displaynone');
                } else {
                    $parent.find('.likersData_comments_unhide').addClass('displaynone');
                    $parent.find('.likersData_comments').removeClass('displaynone');
                }
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function showhideJoinItem(obj, val) {
    $('.upload-overlay-loading-fix').show();
    TTCallAPI({
        what: 'social/join/edit',
        data: {id: obj.attr('data-id'), val: val, data: 'is_visible'},
        callback: function (resp) {
            if (resp.status === 'error') {
                $('.upload-overlay-loading-fix').hide();
                TTAlert({
                    msg: resp.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
            var $parent = obj.parent().parent().parent();
            if (val == 0) {
                $parent.find('.likersData_join').addClass('displaynone');
                $parent.find('.likersData_join_unhide').removeClass('displaynone');
            } else {
                $parent.find('.likersData_join_unhide').addClass('displaynone');
                $parent.find('.likersData_join').removeClass('displaynone');
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function removeCommentsItem(obj, media_type) {
    var ret;
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_remove_comment_brochure.php'),
        data: {id: obj.attr('data-id'), media_type: media_type},
        type: 'post',
        success: function (data) {
            if (data) {
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.done == 1) {
                    obj.addClass('plus');
                    obj.find('.hide_remove_butons_over').html(t('add'));
                    obj.parent().parent().parent().find('.removeDiv_trans').show();
                }
                $('.upload-overlay-loading-fix').hide();
                var item_list = obj.closest('.log_item_list');
                if (item_list.find('.news_count_c').length > 0) {
                    var divNum = ret.count;
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_c').html(divNum + " " + t('comments'));
                    } else {
                        item_list.find('.news_count_c').html(divNum + " " + t('comment'));
                    }
                }
            }
        }
    });
}
function addCommentsItem(obj, media_type) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_add_comment_brochure.php'),
        data: {id: obj.attr('data-id'), media_type: media_type},
        type: 'post',
        success: function (data) {
            if (data) {
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.done == 1) {
                    obj.removeClass('plus');
                    obj.find('.hide_remove_butons_over').html(t('remove'));
                    obj.parent().parent().parent().find('.removeDiv_trans').hide();
                }
                $('.upload-overlay-loading-fix').hide();
                var item_list = obj.closest('.log_item_list');
                if (item_list.find('.news_count_c').length > 0) {
                    var divNum = ret.count;
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_c').html(divNum + " " + t('comments'));
                    } else {
                        item_list.find('.news_count_c').html(divNum + " " + t('comment'));
                    }
                }
            }
        }
    });
}
function removeJoinItem(obj, media_type) {
    var ret;
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_remove_join_event.php'),
        data: {id: obj.attr('data-id'), media_type: media_type},
        type: 'post',
        success: function (data) {
            if (data) {
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.done == 1) {
                    obj.addClass('plus');
                    obj.find('.hide_remove_butons_over').html(t('add'));
                    obj.parent().parent().parent().find('.removeDiv_trans').show();
                    var item_list = obj.closest('.log_item_list');
                    if (item_list.find('.news_count_j').length > 0) {
                        var divNum = ret.count;
                        if (divNum > 1 || divNum == 0) {
                            item_list.find('.news_count_j').html(divNum + " " + t('joining guests'));
                        } else {
                            item_list.find('.news_count_j').html(divNum + " " + t('joining guest'));
                        }
                    }
                }
                $('.upload-overlay-loading-fix').hide();
            }
        }
    });
}
function addJoinItem(obj, media_type) {
    var ret;
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_add_join_event.php'),
        data: {id: obj.attr('data-id'), media_type: media_type},
        type: 'post',
        success: function (data) {
            if (data) {
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.done == 1) {
                    obj.removeClass('plus');
                    obj.find('.hide_remove_butons_over').html(t('remove'));
                    obj.parent().parent().parent().find('.removeDiv_trans').hide();
                }
                $('.upload-overlay-loading-fix').hide();
                var item_list = obj.closest('.log_item_list');
                if (item_list.find('.news_count_j').length > 0) {
                    var divNum = ret.count;
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_j').html(divNum + " " + t('joining guests'));
                    } else {
                        item_list.find('.news_count_j').html(divNum + " " + t('joining guest'));
                    }
                }
            }
        }
    });
}
function removeSponsorItem(obj, media_type) {
    var ret;
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_remove_sponsor_event.php'),
        data: {id: obj.attr('data-id'), media_type: media_type},
        type: 'post',
        success: function (data) {
            if (data) {
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.done == 1) {
                    obj.addClass('plus');
                    obj.find('.hide_remove_butons_over').html(t('add'));
                    obj.parent().parent().parent().find('.removeDiv_trans').show();

                    var item_list = obj.closest('.log_item_list');
                    if (item_list.find('.news_count_s').length > 0) {
                        var divNum = ret.count;
                        if (divNum > 1 || divNum == 0) {
                            item_list.find('.news_count_s').html(divNum + " " + t('sponsors'));
                        } else {
                            item_list.find('.news_count_s').html(divNum + " " + t('sponsor'));
                        }
                    }
                }
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function addSponsorItem(obj, media_type) {
    var ret;
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_add_sponsor_event.php'),
        data: {id: obj.attr('data-id'), media_type: media_type},
        type: 'post',
        success: function (data) {
            if (data) {
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.done == 1) {
                    obj.removeClass('plus');
                    obj.find('.hide_remove_butons_over').html(t('remove'));
                    obj.parent().parent().parent().find('.removeDiv_trans').hide();
                    var item_list = obj.closest('.log_item_list');
                    if (item_list.find('.news_count_s').length > 0) {
                        var divNum = ret.count;
                        if (divNum > 1 || divNum == 0) {
                            item_list.find('.news_count_s').html(divNum + " " + t('sponsors'));
                        } else {
                            item_list.find('.news_count_s').html(divNum + " " + t('sponsor'));
                        }
                    }
                }
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function removeShare(obj) {
    var ret;
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_remove_share_brochure.php'),
        data: {id: obj.attr('data-id')},
        type: 'post',
        success: function (data) {
            if (data) {
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.done == 1) {
                    var item_list = obj.closest('.log_item_list');
                    obj.parent().parent().parent().remove();
                    if (obj.closest('.social_data_all').find('.showMore_shares').css('display') != "none") {
                        obj.closest('.social_data_all').find('.showMore_shares').click();
                        $('.upload-overlay-loading-fix').hide();
                    }
                    if (item_list.find('.news_count_s').length > 0) {
                        var divNum = ret.count;
                        if (divNum > 1 || divNum == 0) {
                            item_list.find('.news_count_s').html(divNum + " " + t('shares'));
                        } else {
                            item_list.find('.news_count_s').html(divNum + " " + t('share'));
                        }
                    }
                }
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function removeRate(obj, media_type) {
    $('.upload-overlay-loading-fix').show();
    TTCallAPI({
        what: 'social/rate/delete',
        data: {id: obj.attr('data-id'), media_type: media_type},
        callback: function (resp) {
            if (resp.status === 'error') {
                $('.upload-overlay-loading-fix').hide();
                TTAlert({
                    msg: resp.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
            var item_list = obj.closest('.log_item_list');
            if (item_list.find('.news_count_r').length > 0) {
                var divNum = resp.count;
                if (divNum > 1 || divNum == 0) {
                    item_list.find('.news_count_r').html(divNum + " " + t('ratings'));
                } else {
                    item_list.find('.news_count_r').html(divNum + " " + t('rating'));
                }
            }
            if (item_list.find('.log_ratings').length > 0) {
                item_list.find('.log_ratings').attr('id', 'popup_view_rate' + resp.rated_val);
            }
            var socialObj = obj.closest('.social_data_all');
            addRaty(socialObj);
            obj.parent().parent().parent().remove();
            socialObj.find('#myrating_score').val(0);
            if (socialObj.find('.showMore_rates').css('display') != "none") {
                socialObj.find('.showMore_rates').click();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function share_selectDisabled(obj) {
    var $parent = $(obj).parent();
    $parent.find('.disabledmessage').removeClass('displaynone');
}
function enableSharesComments(id, val) {
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_enable_shares_comments.php'), {fid: id, val: val, pagetype: pagetype}, function (data) {
        $('.upload-overlay-loading-fix').hide();
    });
}
function postMediaSave(data) {
    if (objectSelected != "") {
        var mydescription = data.description;
        if (mydescription != "") {
            objectSelected.find('.popUp .more0').removeClass('displaynone');
        }
        mydescription = mydescription.replace(/\n/g, "<br />").substr(0, 130) + '...';
        objectSelected.find('.popUp .popUpDivDescription').html(mydescription);
        objectSelected.find('.medias_title').html(data.title);
    }
}
function showHideChannelDisplay(id, is_sposered_page, _type) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/show_hide_channel_display.php'),
        data: {id: id, _type: _type, is_sposered_page: is_sposered_page},
        type: 'post',
        success: function (data) {
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function getMoreActiveTuber() {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/moreactivetuber.php'),
        data: {id: channelGlobalID()},
        type: 'post',
        success: function (data) {
            if (data) {
                $('.ChannelRight_data4 .list ul').append(data);
                refreshMostActiveTuberOver();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function getMostActiveChannel() {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/mostactivechannel.php'),
        type: 'post',
        success: function (data) {
            if (data) {
                $('#ChannelRight_data3 .list ul').append(data);
                refreshMostActiveTuberOver();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function removeSponseredChannel(obj) {
    $parent = obj.parent().parent();
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/remove_sponsered_channel.php'),
        data: {id: $parent.attr('data-id'), globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {
            if (data != false) {
                $parent.remove();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function refreshMostActiveTuberOver() {
    $("#ChannelRight .list li").each(function () {
        var $this = $(this);
        var allPop = $("#ChannelRight .list li .popUp");
        var thisPopup = $(this).find(".popUp");
        $this.mouseenter(function () {
            allPop.hide();
            thisPopup.show();
        });
        $this.mouseleave(function () {
            thisPopup.hide();
        });
    });
}
function refreshMenuProfileSide(val) {
    var str = "";
    switch (val) {
        case 0:
            str = '<li><a href="javascript:;" id="sharebutton_connect">' + t('share') + '</a></li><li><a href="javascript:;" id="invitebutton_connect">' + t("invite") + '</a></li><li><a href="javascript:;" id="reportbutton_connect">' + t('report') + '</a></li>';
            break;
        case 1:
            str = '<li><a href="#sharepopup" rel="nofollow" id="sharebutton">' + t('share') + '</a></li><li><a href="#sharepopup" rel="nofollow" id="invitebutton">' + t('invite') + '</a></li><li><a href="#sharepopup" rel="nofollow" id="reportbutton">' + t('report') + '</a></li>';
            break;
    }
    $('#HeaderRight #MenuRight ul').html(str);
    addProfileSideMenuAction();
}
function addProfileSideMenuAction() {
    $("#sharebutton_inactive").click(function () {
        TTAlert({
            msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to share this channel.'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('register'),
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
    $("#sharebutton_connect").click(function () {
        TTAlert({
            msg: t('you need to connect this channel in order to share this channel'),
            type: 'alert',
            btn1: t('ok'),
            btn2: '',
            btn2Callback: null
        });
    });
    $("#sharebutton_channel").click(function () {
        TTAlert({
            msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + sprintf(t('in order to share this channel. %s you can only sponsor this channel.'), ['<br/>']),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('register'),
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
    $("#invitebutton_inactive").click(function () {
        TTAlert({
            msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to invite people to connect with this channel.'),
            type: 'atcion',
            btn1: t('cancel'),
            btn2: t('register'),
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
    $("#invitebutton_connect").click(function () {
        TTAlert({
            msg: t('you need to connect this channel in order to invite people to connect with this channel'),
            type: 'alert',
            btn1: t('ok'),
            btn2: '',
            btn2Callback: null
        });
    });
    $("#invitebutton_channel").click(function () {
        TTAlert({
            msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + sprintf(t('in order to invite people to connect with this channel. %s you can only sponsor this channel.'), ['<br/>']),
            type: 'atcion',
            btn1: t('cancel'),
            btn2: t('register'),
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
    $(document).on('click', ".reportbutton_channel", function () {
        if (pagename == "popup_view") {
            return;
        }
        var data_status = $(this).attr('data-status');

        var msg = t("you need to have a") + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t("TT account") + '</a> ' + t("in order to report this channel.<br/>you can only sponsor this channel.");
        if (data_status == "disable_comment") {
            msg = t("you need to have a") + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t("TT account") + '</a> ' + t("in order to report a comment.<br/>you can only sponsor this channel.");
        } else if (data_status == "disable_brochure") {
            msg = t("you need to have a") + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t("TT account") + '</a> ' + t("in order to report a brochure.<br/>you can only sponsor this channel.");
        }
        TTAlert({
            msg: msg,
            type: 'atcion',
            btn1: t('cancel'),
            btn2: t('register'),
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
    $(document).on('click', "#reportbutton_connect", function () {
        var data_status = $(this).attr('data-status');
        var msg = "you need to connect this channel in order to report this channel";
        if (data_status == "disable_comment") {
            msg = "you need to connect this channel in order to report a comment";
        } else if (data_status == "disable_brochure") {
            msg = "you need to connect this channel in order to report a brochure";
        }
        TTAlert({
            msg: msg,
            type: 'alert',
            btn1: t('ok'),
            btn2: '',
            btn2Callback: null
        });
    });
    $(document).on('click', "#reportbutton_inactive", function () {
        var data_status = $(this).attr('data-status');
        var msg = t("you need to have a") + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t("TT account") + '</a> ' + ("in order to report this channel");
        if (data_status == "disable_comment") {
            msg = t("you need to have a") + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t("TT account") + '</a> ' + t("in order to report a comment");
        } else if (data_status == "disable_brochure") {
            msg = t("you need to have a") + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t("TT account") + '</a> ' + t("in order to report a brochure");
        }
        TTAlert({
            msg: msg,
            type: 'action',
            btn1: t('cancel'),
            btn2: t('register'),
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
}
function disonnectuser() {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_disconnect_channel.php'),
        data: {channel_id: globchannelid, create_ts: 1},
        type: 'post',
        success: function (data) {
            var jres = null;
            try {
                jres = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            if (!jres) {
                TTAlert({
                    msg: t("Couldn't Process Request. Please try again later."),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            window.location.reload();
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function checkBrochureInfo_Add(namebrochure, pdf, obj) {
    obj.find('#brochureerror').html('');
    obj.find('input').removeClass('InputErr');
    obj.find('textarea').removeClass('InputErr');
    if (namebrochure.length == 0) {
        obj.find('#brochureerror').html(t('Invalid brochure name'));
        obj.find('#edit_brochuretitle_txt').addClass('InputErr');
        obj.find('#edit_brochuretitle_txt').focus();
        return false;
    } else if (pdf.length == 0) {
        obj.find('#brochureerror').html(t('Invalid PDF file'));
        return false;
    }
    return true;
}
function checkEventInfo_Add(nameevents, descriptionevent, locationevent, fromdate, todate, defaultEntry, defaultEntryTo, data_location, data_lng, data_lat, obj) {
    obj.find('#brochureerror').html('');
    obj.find('input').removeClass('InputErr');
    obj.find('textarea').removeClass('InputErr');
    if (nameevents.length == 0) {
        obj.find('#brochureerror').html(t('Invalid event name'));
        obj.find('#nameevents').addClass('InputErr');
        obj.find('#nameevents').focus();
        return false;
    } else if (descriptionevent.length == 0) {
        obj.find('#brochureerror').html(t('Invalid event description'));
        obj.find('#descriptionevent').addClass('InputErr');
        obj.find('#descriptionevent').focus();
        return false;
    } else if (locationevent.length == 0 || data_location.length == 0 || data_lng.length == 0 || data_lat.length == 0) {
        obj.find('#brochureerror').html(t('Invalid event location'));
        obj.find('#locationevent').addClass('InputErr');
        obj.find('#locationevent').focus();
        return false;
    } else if (fromdate.length == 0) {
        obj.find('#brochureerror').html(t('Invalid event date from'));

        obj.find('#fromdate').focus();
        return false;
    } else if (todate.length == 0) {
        obj.find('#brochureerror').html(t('Invalid event date to'));

        obj.find('#todate').focus();
        return false;
    } else if (defaultEntry.length == 0) {
        obj.find('#brochureerror').html(t('Invalid event time'));
        obj.find('#defaultEntry').addClass('InputErr');
        obj.find('#defaultEntry').focus();
        return false;
    } else if (defaultEntryTo.length == 0) {
        obj.find('#brochureerror').html(t('Invalid event time'));
        obj.find('#defaultEntryTo').addClass('InputErr');
        obj.find('#defaultEntryTo').focus();
        return false;
    }
    return true;
}
function initLikes(media_object) {
    currentpage_like = 0;
    media_object.attr("data-page-like", 0);
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_like_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0, globchannelid: channelGlobalID()},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = parseInt(ret.count);
                var is_liked = ret.is_liked;

                media_object.find('.likesDiv .containerDiv').html('');
                media_object.find('.likesDiv .containerDiv').html('<div class="commentsAll max_height412"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".likesNumber").html(divNum);

                // Set the "like" / "unlike" button.
                if (is_liked == 1) {
                    media_object.find('.likeDiv').attr('data-action', 'unlike');
                    media_object.find('.likeDiv').html('<span> ' + t("unlike") + '</span>');
                    media_object.find('.likeDiv').addClass('active');
                } else {
                    media_object.find('.likeDiv').attr('data-action', 'like');
                    media_object.find('.likeDiv').html('<span> ' + t("like") + '</span>');
                    media_object.find('.likeDiv').removeClass('active');
                }
                if (divNum <= 7) {
                    media_object.find(".likesDiv .showMore_like").hide();
                } else {
                    media_object.find(".likesDiv .showMore_like").show();
                }

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_l').length > 0) {
                    var nb_likes = divNum;
                    if (nb_likes > 1 || nb_likes == 0) {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('likes'));
                    } else {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('like'));
                    }
                }

                media_object.find(".likesDiv .commentsAll").addClass("scrollpane_likes");
                initLikersData();
            }
        }
    });
}
function initRates(media_object) {
    currentpage_rates = 0;
    media_object.attr("data-page-rates", 0);

    $.ajax({
        url: ReturnLink('/ajax/ajax_all_rate_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0, globchannelid: channelGlobalID(),
            cache: Math.random()},
        cache: false,
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = parseInt(ret.count);
                var rated_value = ret.rated_value;
                media_object.find('.popup_view_rate').attr('id', 'popup_view_rate' + rated_value);

                media_object.find('.ratesDiv .containerDiv').html('');
                media_object.find('.ratesDiv .containerDiv').html('<div class="commentsAll max_height412"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".ratesNumber").html(divNum);

                if (divNum <= 7) {
                    media_object.find(".ratesDiv .showMore_rates").hide();
                } else {
                    media_object.find(".ratesDiv .showMore_rates").show();
                }
                media_object.find(".ratesDiv .commentsAll").addClass("scrollpane_rates");
                initLikersDataRates();
            }
        }
    });
}
function initComments(media_object) {
    currentpage_comments = 0;
    media_object.attr("data-page-comments", 0);
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_comments_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0, globchannelid: channelGlobalID(), pagename: pagename},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;

                media_object.find('.commentsDiv .containerDiv').html('');
                media_object.find('.commentsDiv .containerDiv').html('<div class="commentsAll max_height412"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".commentsNumber").html(divNum);

                if (divNum <= 5) {
                    media_object.find(".commentsDiv .showMore_comments").hide();
                } else {
                    media_object.find(".commentsDiv .showMore_comments").show();
                }
                media_object.find(".commentsDiv .commentsAll").addClass("scrollpane_comments");
                initLikersDataComments();
            }
        }
    });
}

function initShares(media_object) {
    currentpage_shares = 0;
    var shares_height = 'max_height412';
    var mylimit = 7;
    if (parseInt(user_Is_channel) == 0 && parseInt(user_is_logged) == 1) {
        shares_height = 'max_height221';
        mylimit = 4;
    }
    if (media_object.find('.sharesDiv #share_select1').length > 0 || media_object.find('.sharesDiv #share_select').length == 0) {
        shares_height = 'max_height412';
        mylimit = 7;
    }
    media_object.attr("data-page-shares", 0);
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_shares_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0, globchannelid: channelGlobalID(), mylimit: mylimit},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;

                media_object.find('.sharesDiv .containerDiv').html('');
                media_object.find('.sharesDiv .containerDiv').html('<div class="commentsAll ' + shares_height + '"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".sharesNumber").html(divNum);

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_s').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_s').html(divNum + " " + t('shares'));
                    } else {
                        item_list.find('.news_count_s').html(divNum + " " + t('share'));
                    }
                }

                if (divNum <= mylimit) {
                    media_object.find(".sharesDiv .showMore_shares").hide();
                } else {
                    currentpage_shares = parseInt(media_object.attr("data-page-shares"));
                    if ((currentpage_shares + 1) * mylimit >= divNum) {
                        media_object.find(".sharesDiv .showMore_shares").hide();
                    } else {
                        media_object.find(".sharesDiv .showMore_shares").show();
                    }
                }
                media_object.find(".sharesDiv .commentsAll").addClass("scrollpane_shares");
                initLikersDataShares();
            }
        }
    });
}

function initJoins(media_object) {
    currentpage_join = 0;
    media_object.attr("data-page-join", 0);
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_joins_channel_media.php'),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0, globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;

                var mylimit = 6;
                var mdheight = 'max_height375';
                if (parseInt(user_Is_channel) == 0 && parseInt(user_is_logged) == 1) {
                    mylimit = 5;
                    mdheight = 'max_height312';
                }
                media_object.find('.joinsDiv .containerDiv').html('');
                media_object.find('.joinsDiv .containerDiv').html('<div class="commentsAll ' + mdheight + '"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".joinsNumber").html(divNum);


                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_j').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_j').html(divNum + " " + t('joining guests'));
                    } else {
                        item_list.find('.news_count_j').html(divNum + " " + t('joining guest'));
                    }
                }

                if (divNum <= mylimit) {
                    media_object.find(".joinsDiv .showMore_joins").hide();
                } else {
                    media_object.find(".joinsDiv .showMore_joins").show();
                    media_object.find(".joinsDiv .commentsAll").addClass("scrollpane_join");
                }
                initLikersDataJoins();
            }
        }
    });
}

function initSponsors(media_object) {
    currentpage_sponsors = 0;
    media_object.attr("data-page-sponsors", 0);

    $.ajax({
        url: ReturnLink('/ajax/ajax_all_sponsors_channel_media.php'),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0, globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;


                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_sp').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_sp').html(divNum + " " + t('sponsors'));
                    } else {
                        item_list.find('.news_count_sp').html(divNum + " " + t('sponsor'));
                    }
                }

                media_object.find('.sponsorsDiv .containerDiv').html('');
                media_object.find('.sponsorsDiv .containerDiv').html('<div class="commentsAll max_height412"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".sponsorsNumber").html(divNum);

                var mylimit = 6;
                if (parseInt(user_Is_channel) == 1 && parseInt(is_owner) == 0) {
                    mylimit = 4;
                }
                if (divNum <= mylimit) {
                    media_object.find(".sponsorsDiv .showMore_sponsors").hide();
                } else {
                    media_object.find(".sponsorsDiv .showMore_sponsors").show();
                    media_object.find(".sponsorsDiv .commentsAll").addClass("scrollpane_comments");
                }
                // Initiate the buttons that show on mouse-over.
                initLikersDataSponsors();
            }
        }
    });
}

function getItemCommentText(id, obj, media_type) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_text_comment_brochure.php'),
        data: {id: id},
        type: 'post',
        success: function (data) {
            if (data) {
                obj.find('.editcommentsDiv textarea').val();
                obj.find('.comments_edit').addClass('active');
                obj.find('.editcommentsDiv').show();
                initEditComment(obj.find('.editcommentsDiv textarea'), data, media_type);
                obj.addClass('disable');
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function initEditComment(obj, data, media_type) {
    obj.parent().append("<div class='mentions_text_class' style='display:none;'></div>");
    obj.parent().find('.mentions_text_class').html(data);
    var men_arr = [];
    obj.parent().find('.mentions_text_class .comments_user').each(function () {
        men_arr.push({'id': $(this).attr('data-id'), 'name': $(this).html(), 'avatar': "", 'type': "", 'value': $(this).html()});
    });
    obj.parent().find('.mentions_text_class').remove();

    obj.mentionsInput({
        addedItems: men_arr,
        onDataRequest: function (mode, query, callback) {
            $.post(ReturnLink('/ajax/channel-autocomplete-comments.php'), {term: query, channel_id: channelGlobalID(), media: media_type, mediaid: obj.closest('.social_data_all').attr('data-id')}, function (data) {

                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }

                var searcharray = new Array();
                for (var i = 0; i < ret.length; i++) {
                    var avatar = ret[i].profile_Pic;
                    searcharray.push({'id': ret[i].user_id, 'name': ret[i].username, 'avatar': avatar, 'type': ''});
                }
                var data = searcharray;

                data = _.filter(data, function (item) {
                    return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1
                });
                callback.call(this, data);
            });
        }
    });
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        $('textarea').css('text-indent', '-3px');
    }
    obj.parent().find('.mentions div').html(data);
    obj.val(obj.parent().find('.mentions div').text());
    obj.css('height', (obj.parent().find('.mentions div').height() + 14) + "px");
    var $parent = obj.parent().parent().parent().parent().parent();
    $parent.find('.commentBox').css('height', obj.height() + "px");

    setTimeout(function () {
        obj.parent().find('.mentions div').html(data);
        obj.val(obj.parent().find('.mentions div').text());
        obj.parent().find('.mentions_dup').html(obj.parent().find('.mentions div').text());
        obj.css('height', (obj.parent().find('.mentions div').height() + 14) + "px");
        var $parent = obj.parent().parent().parent().parent().parent();
        $parent.find('.commentBox').css('height', obj.height() + "px");
    }, 200);
    obj.keypress(function (event) {
        var $parent = $(this).parent().parent().parent().parent().parent();
        $parent.find('.commentBox').css('height', $(this).height() + "px");
    });

    obj.keydown(function (event) {
        var $input = $(this);
        var $parent = $(this).parent();

        if (event.keyCode == 13) {
            var lilength = $('.mentions-autocomplete-list ul').children().length;
            var comment_str = "" + $parent.find('div.mentions div').html();
            if (lilength == 0 && comment_str != "") {
                var comments_user_array = new Array();
                $parent.find('div.mentions div .comments_user').each(function () {
                    comments_user_array.push($(this).attr('data-id'));
                });
                update_Comments_Item($parent.parent().parent().parent().attr('data-id'), $parent.find('div.mentions div').html(), comments_user_array, $input.closest('.social_data_all'));
            }
        }
    });
}
function update_Comments_Item(id, val, arr, media_object) {
    $('.upload-overlay-loading-fix').show();
    TTCallAPI({
        what: '/social/comment/update',
        data: {comment: val, id: id, comments_user_array: arr},
        callback: function (resp) {
            if (resp.status === 'error') {
                TTAlert({
                    msg: resp.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            media_object.find('.commentsDiv .containerDiv .commentsAll .commentsAll_inside').html('');
            currentpage_comments = 0;
            media_object.attr("data-page-comments", 0);

            getItemsCommentsRelated(media_object);
        }
    });
}
function getItemsCommentsRelated(media_object) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_comments_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: media_object.attr("data-page-comments"), globchannelid: channelGlobalID(), pagename: pagename},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix').hide();
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                $('.upload-overlay-loading-fix').hide();
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;


                media_object.find('.commentsDiv .containerDiv .commentsAll .commentsAll_inside').append(myData);
                media_object.find(".commentsNumber").html(divNum);

                if (divNum <= 5) {
                    media_object.find(".commentsDiv .showMore_comments").hide();
                } else {
                    currentpage_comments = parseInt(media_object.attr("data-page-comments"));
                    if ((currentpage_comments + 1) * 5 >= divNum) {
                        media_object.find(".commentsDiv .showMore_comments").hide();
                    } else {
                        media_object.find(".commentsDiv .showMore_comments").show();
                    }
                }
                media_object.find(".commentsDiv .commentsAll").addClass("scrollpane_comments");
                initscrollPaneSocialChannel(media_object.find(".scrollpane_comments"), true);
                initLikersDataComments();

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_c').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_c').html(divNum + " " + t('comments'));
                    } else {
                        item_list.find('.news_count_c').html(divNum + " " + t('comment'));
                    }
                }
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function initLikersData() {
    $(".likersData").each(function () {
        var $this = $(this);
        $this.on('mouseenter', function () {
            $this.find('.bottomLikes').show();
        }).on('mouseleave', function () {
            $this.find('.bottomLikes').hide();
        });
    });
    if (pagename == "channel_log") {
        initScrollLog();
    }
}
function initLikersDataRates() {
    $(".likersData_rates").each(function () {
        var $this = $(this);
        $this.on('mouseenter', function () {
            $this.find('.bottomLikes').show();
            $this.find('.removeDiv_rate').show();
        }).on('mouseleave', function () {
            $this.find('.bottomLikes').hide();
            $this.find('.removeDiv_rate').hide();
        });
    });
    if (pagename == "channel_log") {
        initScrollLog();
    }
}

function initLikersDataComments() {
    $(".likersData_comments").each(function () {
        var $this = $(this);
        $this.on('mouseenter', function () {
            if ($this.hasClass('disable')) {
                return;
            }
            $this.find('.hideDiv').show();
            $this.find('.hideDiv_viewer').show();
            $this.find('.removeDiv').show();
            $this.find('.bottomLikes').show();
        }).on('mouseleave', function () {
            $this.find('.bottomLikes').hide();
            $this.find('.hideDiv').hide();
            $this.find('.hideDiv_viewer').hide();
            $this.find('.removeDiv').hide();
        });
    });
    if (pagename == "channel_log") {
        initScrollLog();
    }
    $(".report_button_comment_reason").each(function () {
        var $this = $(this);
        $this.removeClass('report_button_comment_reason');
        initCommentReportFunctions($this);
    });
}
function initLikersDataShares() {
    $(".likersData_share").each(function () {
        var $this = $(this);
        $this.on('mouseenter', function () {
            $this.find('.removeDiv_share').show();
            $this.find('.bottomLikes').show();
        }).on('mouseleave', function () {
            $this.find('.bottomLikes').hide();
            $this.find('.removeDiv_share').hide();
        });
    });
    if (pagename == "channel_log") {
        initScrollLog();
    }
}

function initLikersDataJoins() {
    $(".likersData_join").each(function () {
        var $this = $(this);
        $this.on('mouseenter', function () {
            if ($this.hasClass('disable')) {
                return;
            }
            $this.find('.hideDiv_join').show();
            $this.find('.hideDiv_viewer').show();
            $this.find('.removeDiv_join').show();
            $this.find('.bottomLikes').show();
        }).on('mouseleave', function () {
            $this.find('.bottomLikes').hide();
            $this.find('.hideDiv_join').hide();
            $this.find('.hideDiv_viewer').hide();
            $this.find('.removeDiv_join').hide();
        });
    });
    if (pagename == "channel_log") {
        initScrollLog();
    }
}

function initLikersDataSponsors() {
    $(".likersData_sponsor").each(function () {
        var $this = $(this);
        $this.on('mouseenter', function () {
            if ($this.hasClass('disable')) {
                return;
            }
            $this.find('.hideDiv_sponsor').show();
            $this.find('.hideDiv_viewer').show();
            $this.find('.removeDiv_sponsor').show();
            $this.find('.bottomLikes').show();
        }).on('mouseleave', function () {
            $this.find('.bottomLikes').hide();
            $this.find('.hideDiv_sponsor').hide();
            $this.find('.hideDiv_viewer').hide();
            $this.find('.removeDiv_sponsor').hide();
        });
    });
    if (pagename == "channel_log") {
        initScrollLog();
    }
}


// Resets the "joins" fields to their initial states before they are filled.
function resetJoinsItems(media_object) {
    media_object.find("#event_join_yes").attr('checked', false);
    media_object.find("#event_join_no").attr('checked', false);
    media_object.find("#side_panel_join_text").html("");
    media_object.find("#join_guests").hide();
    media_object.find("#join_guests_number").val(0);
}

// Set the texts for the "joins" section depending on the event's category (past, current or upcoming).
function setJoinsItems(obj, event_category) {
    event_category = obj.closest('.social_data_all').attr('data-category').substring(0, 11);

    if (event_category == 'past_events') {
        obj.closest('.social_data_all').find("#side_panel_join_text").html("did you join?");
    } else {
        obj.closest('.social_data_all').find("#side_panel_join_text").html("would you like to join?");
        obj.closest('.social_data_all').find("#join_guests").show();
    }
}

// Set the details if the user already joined that event.
function getJoinDetails(media_object) {
    $.ajax({
        url: ReturnLink('/ajax/ajax_join_channel_media.php'),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: 0, globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var attending = ret.attending;
                var guests_count = ret.guests_count;

                // If the user is attending, check the "yes" box and fill the guests number.
                if (attending == 1) {
                    media_object.find("#event_join_yes").attr("checked", true);
                    media_object.find("#join_guests_number").val(guests_count);
                }
            }
        }
    });
}

// Save a change of join.
function saveJoinChange(mediaid, mediatype, obj) {
    // Prepare the form variables.
    var join_event = obj.closest('.social_data_all').find("input:radio[name=event_join]:checked").val(); // returns: yes / no
    var guests_count = obj.closest('.social_data_all').find("#join_guests_number").val();

    // If the user hasn't specified to join or not, halt the operation.
    if (join_event != 'yes' && join_event != 'no')
        return;


    $.ajax({
        url: ReturnLink('/ajax/ajax_join_save_channel_media.php'),
        data: {join_event: join_event, guests_count: guests_count, media: mediatype, mediaid: mediaid, page: 0, globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                //resetJoinsItems( obj.closest('.social_data_all') );
                //setJoinsItems(obj);
                getJoinDetails(obj.closest('.social_data_all'));

                initJoins(obj.closest('.social_data_all'));
            }
        }
    });
}


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
function initCalendarEvents() {
    if ($('#idEventsCalendar').length > 0) {
        // Calendar Setup
        var DateIndex = 0;
        var currDateIndex = 0;
        var calTimer = "";
        var timer;
        EventsDetailedCal = Calendar.setup({
            cont: "idEventsCalendar",
            bottomBar: false,
                noScroll  	 : true,
            selectionType: Calendar.SEL_MULTIPLE,
            disabled: function () {
                return true;
            },
            dateInfo: getDateInfo
            ,
            onChange: function ()
            {
                // set time to do js hover after rendering the calendar
                var DateIndex = '';
                if (calTimer != "") {
                    clearTimeout(calTimer);
                }

                calTimer = setTimeout(function ()
                {
                    // check the selected days only
                    $(".DynarchCalendar-day-selected.highlight, .DynarchCalendar-day-selected.highlight2").each(function (index, element) {
                        $(this).parent().mouseenter(function () {
                            // get the date from div attribute
                            DateIndex = $(this).find('.DynarchCalendar-day-selected').attr('dyc-date');
                            currDateIndex = 0;
                            var currdata = DATE_INFOInit[DateIndex][0];
                            // set the values in tooltip from object and set the position
                            $(".ed_CalTooltipContent > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalEventImg").attr('src', currdata['imageurl']);
                            var link_a = ReturnLink('/channel-events-detailed/' + currdata['id']);
                            $(".ed_CalTooltipContent > .ed_CalTooltip > .ed_CalTooltip_a").attr('href', link_a);
                            $(".ed_CalTooltipContent > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalTooltipTitle").html(currdata['title']);
                            $(".ed_CalTooltipContent > .ed_CalTooltip > .ed_CalTooltipClose").attr('data-DateIndex', DateIndex);
                            var TooltipTop = $(this).position().top;
                            var TooltipLeft = $(this).position().left;
                            $(".ed_CalTooltipContent").css('margin-top', (TooltipTop + 63) + 'px').css('margin-left', (TooltipLeft - 33) + 'px').show();
                        });
                        // hide the tooltip after leave the mouse on tooltip and date selected
                        $(this).parent().mouseleave(function () {
                            $(".ed_CalTooltipContent").hide();
                        });
                    });

                }, 500);
            }
        });
        $('.ed_CalTooltipContent').mouseenter(function () {
            // if the mouse hover the tooltip clear time to hide it
            $(this).show();
        });
        $('.ed_CalTooltipContent').mouseleave(function () {
            // if the mouse hover the tooltip clear time to hide it
            $(this).hide();
        });
        // hide tooltip on close click
        $(".ed_CalTooltipContent > .ed_CalTooltip > .ed_CalTooltipClose").click(function () {
            currDateIndex++;
            if (DATE_INFOInit[$(this).attr('data-DateIndex')].length > currDateIndex) {
                var currdata = DATE_INFOInit[$(this).attr('data-DateIndex')][currDateIndex];
                // set the values in tooltip from object and set the position
                $(".ed_CalTooltipContent > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalEventImg").attr('src', currdata['imageurl']);
                var link_a = ReturnLink('/channel-events-detailed/' + currdata['id']);
                $(".ed_CalTooltipContent > .ed_CalTooltip > .ed_CalTooltip_a").attr('href', link_a);
                $(".ed_CalTooltipContent > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalTooltipTitle").html(currdata['title']);
            } else {
                $(".ed_CalTooltipContent").hide();
            }
        });
        // hide tooltip when leave it
        $(".ed_CalTooltip").mouseleave(function ()
        {
            $(".ed_CalTooltipContent").hide();
        });
        // retrive the dates selected in DATE_INFO object
        var arrSelectionSet = new Array(), i = 0;
        for (var key in DATE_INFO)
        {
            arrSelectionSet[i] = key;
            i++;
        }
        // select dates in calendar
        EventsDetailedCal.selection.set(arrSelectionSet);
    }
}
function getDateInfo(date, wantsClassName) {
    var as_number = Calendar.dateToInt(date);

    var myDateArr = {};
    if (DATE_INFOInit[as_number]) {
        var classArray = new Array();
        for (var i = 0; i < DATE_INFOInit[as_number].length; i++) {
            var obj = DATE_INFOInit[as_number][i];
            var klassStr = obj.klass;
            var flag = false;
            for (var j = 0; j < classArray.length; j++) {
                if (classArray[j] == klassStr)
                    flag = true;
            }
            if (!flag) {
                classArray.push(klassStr);
            }
        }
        var classArraystr = '';
        for (var j = 0; j < classArray.length; j++) {
            var tmp = classArray[j].replace(/"/g, '');
            classArraystr += tmp + ' ';
        }
        myDateArr = {klass: classArraystr, tooltip: ""};
    }
    return myDateArr;
}
;
function initPhotoFancy() {
    initChannelFancyPhotos("885", "620");
}

function initChannelFancyPhotos(w, h)
{
    $('.stanClick_photos').fancybox({
        minWidth: w,
        minHeight: h,
        prevEffect: 'none',
        nextEffect: 'none',
        transitionIn: "none",
        transitionOut: "none",
        autoSize: false,
        padding: 0,
        margin: 0,
        scrolling: 'no',
        scrollOutside: false,
        type: "iframe",
        beforeShow: function () {
            if (window.parent.getCurrentMode() == "full") {
                fancyFullMode();
            }


        },
        afterShow: function () {

            if ($.browser.msi) {
                $('.fancybox-inner iframe').contents().find("#fsbutton").remove();
            } else {
                if (window.parent.getCurrentMode() == "full") {
                    fancyFullMode();
                }

                //showComments();


                $('.fancybox-inner iframe').contents().find("#fsbutton").on('click', function () {
                    var $f = $(".fancybox-iframe");
                    if (getCurrentMode() == "pop")
                    {
                        /*var $n = $('.fancybox-inner iframe').contents().find(".imgInside");
                         $n.addClass('tofullscreenImage');*/

                        $('.fancybox-overlay').css('opacity', '1').toggleFullScreen();
                        $f[0].contentWindow.hideComments();
                        setCurrentMode('full');
                        fancyFullMode();
                        $.fancybox.toggle();
                    } else {

                        $('.fancybox-overlay').fullScreen(false);
                        $f[0].contentWindow.showComments();
                        resetFancy();
                        $.fancybox.toggle();
                    }
                });

            }
        },
        afterLoad: unscrollIframe,
        afterClose: function () {
            resetFancy();
            $('.fancybox-overlay').fullScreen(false);
            $.fancybox.toggle();
            setCurrentMode('pop');
        },
        onCancel: function () {
            resetFancy();
            var $f = $(".fancybox-iframe");
            $f[0].contentWindow.showComments();
            $.fancybox.toggle();
        }
    });
}

function fancyFullMode()
{
    var tmpH = screen.height;

    GLOBAL_TOP = $(".fancybox-wrap").css('top');
    GLOBAL_LEFT = $(".fancybox-wrap").css('left');
    $(".fancybox-wrap").css({'top': '0px', 'left': '0px'});
    $(".fancybox-wrap,.fancybox-inner").addClass('fancyFullDim').css({'overflow': 'hidden'});

    $('.fancybox-lock .fancybox-overlay').css('overflow', 'hidden');

}

function resetFancy()
{
    setCurrentMode('pop');

    $(".fancybox-wrap,.fancybox-inner").removeClass('fancyFullDim');
    $(".fancybox-wrap").css({'top': GLOBAL_TOP + 'px', 'left': GLOBAL_LEFT + 'px'});
}

function getCurrentMode()
{
    return CURRENT_MODE;
}
function setCurrentMode(mode)
{
    CURRENT_MODE = mode;
}

function checkSubmit_Search_pages(e) {
    if (e && e.keyCode == 13) {
        $('.searchBtn_pages').click();
    }
}
function checkSizeEmbed(obj) {
    var id = parseInt($(obj).val());
    resetLogoEmbededView();
    $('.embed_logo_view').addClass('embed_logo_view' + id);
    var ww = 103;
    var hh = 38;
    if (id == 2) {
        ww = 72;
        hh = 27;
    } else if (id == 3) {
        ww = 46;
        hh = 18;
    } else if (id == 4) {
        ww = 23;
        hh = 31;
    }
    if (id == 0) {
        $('.popup_embed_channel_container .embed_content').val('');
    } else {
        $('.popup_embed_channel_container .embed_content').val('<iframe src="' + $('.popup_embed_channel_container').attr('data-uri') + '/' + id + '" style="width: ' + ww + 'px; height: ' + hh + 'px; border:none;"></iframe>');
    }
}
function resetLogoEmbededView() {
    for (var i = 0; i < 5; i++) {
        $('.embed_logo_view').removeClass('embed_logo_view' + i);
    }
}

function ReloadComments(){	
    $.ajax({
        url: ReturnLink('/parts/video_comments.php?type=' + COMMENT_TYPE),
        type : 'post',
        data: {current_id : VideoId, page : commentsPage , entity_type:ORIGINAL_ENTITY_TYPE , sort : commentsSort, sortby :  commentsSortBy},
        success: function(data){
            $('#CommentList').html(data);
            ShowHidePager();
        }
    });
}
function updateNotificationViewed(){
    $.ajax({
        url: ReturnLink('/ajax/info_not_CH_update.php'),
        data: {channel_id:channelGlobalID()},
        type: 'post',
        success: function(data){
        }
    });	
}
function initscrollPaneNotifNew(obj){
	obj.jScrollPane();
	jscrollpane_apiL = obj.data('jsp');
}
function updateNotificationDataNewCH(obj, action){
    var chid=obj.attr('data-user-id');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/info_not_relation_update.php'),
        data: {action:action,channel_id:chid,parent_id:channelGlobalID()},
        type: 'post',
        success: function(data){
            $('.upload-overlay-loading-fix').hide();
        }
    });	
}
// Updates the newsfeed table.
function updateNotificationDataNew(obj, action){
	var id=obj.attr('data-not-id');
	var current_channel_id = obj.attr('data-currchannel-id');
	var user_id = obj.attr('data-user-id');
	
	//$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_not_update.php'),
		data: {id:id,action:action,current_channel_id:current_channel_id,user_id:user_id},
		type: 'post',
		success: function(data){
			// Refresh the list where needed.
			if(action == 'remove'){
				obj.closest('.notification_Div').remove();
			}else if(action == 'stopNotifications' || action == 'getNotifications'){                            
                            //location.reload();
                        }
			
		//	$('.upload-overlay-loading-fix').hide();
		}
	});	
}