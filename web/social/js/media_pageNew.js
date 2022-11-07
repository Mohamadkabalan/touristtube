var shareFadeoutTimeout;
var mySwiper;
$(document).ready(function () {
    incrementObjectsViewsMedia(SOCIAL_ENTITY_MEDIA, $('.containerAllMedia').attr('data-id') );
    $(document).on('change', "#share_with_select", function () {
        var media_object = $(this).parent().parent().parent();
        var value = media_object.find("#share_with_select").val();
        var text = media_object.find('#share_with_select option:selected').attr("data-text");

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
            media_object.find('.emailcontainer_boxed_share .addmorecontainer').append(selected_item);
        }
    });
    $('.socialButtons.shares').each(function(){
        var $this = $(this);
        var mid = $this.attr('data-id');
        $this.fancybox({
            padding	:0,
            margin	:0,
            beforeLoad:function(){
                var media_object = $('#addmoretext_media').parent().parent().parent();
                resetSharesBoxMedia(media_object);
                if( $('#addmoretext_media').length>0 ) addshareuserauto($('#addmoretext_media'));
            }
        });
    });
    $(document).on('click', "#share_boxed_send", function () {
        var $this = $(this);
        var media_object = $this.parent();
        var mediaid = $this.attr('data-id');
        var media_type = SOCIAL_ENTITY_MEDIA;
        var invite_msg = media_object.find("#invitetext").val();
        var inviteArray = new Array();
        media_object.find('.addmorecontainer .peoplesdata').each(function () {
            var obj = $(this);
            var inviteType = 0;
            if (obj.attr('id') == "option_1") {
                inviteArray.push({friends: 1});
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
        $('.upload-overlay-loading-fix').show();

        TTCallAPI({
            what: 'social/share',
            data: {entity_type: media_type, entity_id: mediaid, share_with: inviteArray, share_type: SOCIAL_SHARE_TYPE_SHARE, msg: invite_msg, channel_id: channelGlobalID(),addToFeeds:1},
            callback: function (ret) {
                $('.upload-overlay-loading-fix').hide();
                $('.fancybox-close').click();
                resetSharesBoxMedia(media_object);
            }
        });
    });
    $('#addmoretext_media').keydown(function(event){
        var thisobj =$(this);
        var email_container_privacy = thisobj.parent();
        var code = (event.keyCode ? event.keyCode : event.which);
        if(code === 13) { //Enter keycode or tab
            if(validateEmail(thisobj.val())){
                var friendstr='<div class="peoplesdata formttl" data-id="" data-email="'+thisobj.val()+'"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">'+thisobj.val()+'</div><div class="peoplesdataclose"></div></div>';
                email_container_privacy.append(friendstr);
                thisobj.val('');
                event.preventDefault();
            }
        }
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
        } else if (parents.attr('id') == 'connectionsdata') {
            parents_all.find('.uploadinfocheckbox5').removeClass('active');
        } else if (parents.attr('id') == 'sponsorssdata') {
            parents_all.find('.uploadinfocheckbox6').removeClass('active');
        }
    });
    $('.editbutton').each(function (index, element) {
        var $This = $(this);

        var vid = $This.attr('data-id');
        var channelid = $This.attr('data-channelid');

        $This.attr("href", ReturnLink('/parts/user-viewphoto.php?id=' + vid + '&channelid=' + channelid));

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
    $(document).on('click', ".socialButtons.favorites", function (e) {
        e.preventDefault();
        if( $('.containerAllMedia').attr('data-log') == 0 ){
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ',
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
        var $button = $(this);		
        TTCallAPI({
            what: 'social/favorite',
            data: {entity_id : $button.attr('data-id'), entity_type : SOCIAL_ENTITY_MEDIA, channel_id: channelGlobalID() },
            callback: function(data){
                if(data.status == 'ok'){
                    if( data.favorite == 0 ) {
                        $button.removeClass('active');
                        $button.attr('title', t('watch later') );
                    }else {
                        $button.addClass('active');
                        $button.attr('title', t('remove from watch list') );
                    }
                }
            }
        });
    });
    $(document).on('click', ".socialButtons.likes", function (e) {
        e.preventDefault();
        if( $('.containerAllMedia').attr('data-log') == 0 ){
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ',
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
        var media_object = $(this);
        var mediaid = media_object.attr("data-id");
        var media_type = SOCIAL_ENTITY_MEDIA;
        if ( !media_object.hasClass('active') ) {
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
                        TTAlert({
                            msg: ret.error,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }
                    media_object.addClass('active');
                    media_object.attr('title', t('unlike') );
                }
            });
        } else {
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
                    media_object.removeClass('active');
                    media_object.attr('title', t('like') );
                }
            });
        }
    });
    $(document).on('click', ".share-link", function (e) {
        e.preventDefault();
        if ($('.share_link_holder').css('display') != "none") {
            $('.share_link_holder').hide();
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
            $('.share_link_holder').show();
            
            shareFadeoutTimeout = setTimeout(function () {
                $('.share_link_holder').hide();
                $('.share-link').removeClass('active');
            }, 1500);

            $('.share_link_holder').unbind('mouseenter mouseleave').hover(function () {
                clearTimeout(shareFadeoutTimeout);
                $('.share_link_holder').stop(true, true);
                $('.share_link_holder').show();
            }, function () {
                shareFadeoutTimeout = setTimeout(function () {
                    $('.share_link_holder').hide();
                    $('.share-link').removeClass('active');
                }, 500);
            });
        }
    });
    //$('.swiper-wrapper').width( $('.swiper-slide').length*294 );
    mySwiper = new Swiper('.swiper-container', {
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        slidesPerView: 'auto',
        spaceBetween: 10,
        slidesPerGroup:Math.floor($('.swipe_videocontainer').width()/294),
        onSlideChangeEnd: function (swiper) {            
            if($(window).width()<=767){
                var $obj = $('.swiper-wrapper').children().eq(swiper.activeIndex);
                incrementObjectsViewsMedia(SOCIAL_ENTITY_MEDIA, $obj.attr('data-id') );
                initializeVideoPlayer($obj);
            }
        }
    });
    $(document).on('click', ".imgcontainerswipe,.video_desc", function (e) {
        e.preventDefault();
        document.location.href = $(this).attr('href');
    });
    $(window).resize(function () {
        setRelatedContainerWW(); 
        resizeMediaPage();
    });
    setTimeout(setRelatedContainerWW, 200);
});
function initializeVideoPlayer($obj){
    var flashmediaclassesOther = $obj.find('.flashmediaclassesOther');
    if(flashmediaclassesOther.length>0){        
        var $this = flashmediaclassesOther;
        var datareslist = $this.attr('data-reslist');
        var datareslistimg = $this.attr('data-reslistimg');
        var datamediaid = $this.attr('data-mediaid');
        if(datareslist!=''){
            regWidth = $this.closest('.swiper-slide').find('.video_detimgswiper').width();                     
            regHeight = $this.closest('.swiper-slide').find('.video_detimgswiper').height();
            EmbedFlashJW($this.attr('id'), datareslist, regWidth, regHeight, datareslistimg , datamediaid,0,0,false);
            setTimeout(function () {
                $obj.find('.imgcontainerswipe').hide(); 
            }, 300);        
            $this.closest('.swiper-slide').on('load', function() { 
                 refreshVideosSwiper( $this.attr('id') );
            });
            jwplayer($this.attr('id')).onPlay(function(event) {
                var thisplay = $(this);
                $('.jwplayer').each(function(){
                    var $thisjw = $(this);
                    if( thisplay[0].id != $thisjw.attr('id') )jwplayer($thisjw.attr('id')).stop();
                });
            })
        }
    }
}
function resizeMediaPage(){       
    var resW = $(window).width();
    clearTimeout(tOut);
    if ((ww > limit && resW < limit) || (ww < limit && resW > limit)) {
        tOut = setTimeout(refreshThePage, 100);
    }
    initMediaPOPS();
}
function initMediaPOPS() {
    if ($('.imagefirstbut').length > 0) {
        var pagedimensions = parent.window.returnIframeDimensions();
        $(".imagefirstbut").unbind('click');        
        $(".imagefirstbut").bind("click",function(event) {
            event.preventDefault();
            var $href = $(this).attr('href');
//            if(pagedimensions[0]>1148) pagedimensions[0]=1148;
//            if(pagedimensions[0]<768) pagedimensions[0]='100%';
	    
            $.fancybox({
                width: '100%',
                height: $(window).height()+'px',
                href: $href,
                closeBtn: true,
                autoSize: false,
                autoScale: true,
                autoHeight: true,
                transitionIn: 'elastic',
                transitionOut: 'fadeOut',
                type: 'iframe',
                padding: 0,
                margin: 0,
                scrolling: 'no',
                helpers: {
                    overlay: {closeClick: true}
                }
            });
        });
    }
}
function closeFancyReload(is_album) {
    if (parseInt(is_album) == 0) {
        $('.fancybox-close').click();
        document.location.reload();
    }
}
function setRelatedContainerWW(){
    var ww = $('.swiper-slide').width() + 10;
    var pdn = ($('.swipe_videocontainer').width() - Math.floor($('.swipe_videocontainer').width()/ww)*ww)/2;
    var pdnl = Math.floor(pdn);
    var pdnr = Math.ceil(pdn);
    $('.swipe_video').css('padding-left',pdnl+'px');
    $('.swipe_video').css('padding-right',pdnr+'px');
    mySwiper.params.slidesPerGroup = Math.floor($('.swipe_videocontainer').width()/ww);
    resizeMediaPage();
    //mySwiper.init();
}
function addshareuserauto(auto_object) {
    if (auto_object.length == 0) return;
    current_channel_id = channelGlobalID();
    var email_container_privacy = auto_object.parent().parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            var su = SelectedUsers();
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/channel-autocomplete-share.php') + "&ds=" + su.join(',') + '&cid=' + current_channel_id);
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var selectedUserID1 = ui.item.user_id;
            SelectedUsersAdd(selectedUserID1);
            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selectedUserID1 + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            email_container_privacy.find('.addmorecontainer').append(friendstr);
            auto_object.val('');
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) {
            if (validateEmail(auto_object.val())) {
                var friendstr = '<div class="peoplesdata formttl" data-id="" data-email="' + auto_object.val() + '"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">' + auto_object.val() + '</div><div class="peoplesdataclose"></div></div>';
                email_container_privacy.find('.addmorecontainer').append(friendstr);
                auto_object.val('');
                event.preventDefault();
            }
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }
}
function resetSharesBoxMedia(media_object) {
    media_object.find("#invitetext").val('');
    media_object.find("#share_with_select").val(0);

    media_object.find(".emailcontainer_boxed_share .addmorecontainer").html('<input type="text" data-id="" value="" placeholder="' + $.i18n._("add more") + '" class="addmoretext_css" id="addmoretext_media" name="addmoretext" >');
    resetSelectedUsers();

    addshareuserauto(media_object.find('#addmoretext_media'));
}
function incrementObjectsViewsMedia(entity_type,data_id) {
    $.ajax({
        url: ReturnLink('/ajax/increment_objects_views.php?no_cach=' + Math.random()),
        data: {entity_type: entity_type,id:data_id},
        type: 'post'
    });
}
function closeFancyExportVideo(link_media) {
    closeFancy();
    TTAlert({
        msg: sprintf(t('your video has been updated. %s please bear us some time to be converted.'), ['<br/>']),
        type: 'alert',
        btn1: t('ok'),
        btn2: '',
        btn2Callback: function () {
            if(link_media){
                document.location.href = link_media;
            }
            else{
                document.location.reload();
            }
        }
    });
}
function closeFancy() {
    $('.fancybox-close').click();
}