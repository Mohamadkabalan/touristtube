var shareFadeoutTimeout;
var mySwiper;
$(document).ready(function () {
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
                var media_object = $('#sharedata_popupid').find('#addmoretext_media').parent().parent().parent();
                resetSharesBoxMedia(media_object);
            }
        });
    });
    $('.socialButtons.invites').each(function(){
        var $this = $(this);
        var mid = $this.attr('data-id');
        $this.fancybox({
            padding	:0,
            margin	:0,
            beforeLoad:function(){
                var media_object = $('#invitedata_popupid').find('#addmoretext_invite').parent().parent().parent();
                resetInvitesBoxMedia(media_object);
            }
        });
    });
    $(document).on('click', "#share_boxed_send", function () {
        var $this = $(this);
        var media_object = $this.parent();
        var channelid = $this.attr('data-id');
        var media_type = $this.closest('#sharedata_popupid').attr('data-type');
        if($this.hasClass('invite')) media_type = $this.closest('#invitedata_popupid').attr('data-type');
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
        var share_type=SOCIAL_SHARE_TYPE_SHARE;
        if($this.hasClass('invite')) share_type=SOCIAL_SHARE_TYPE_INVITE;
        TTCallAPI({
            what: 'social/share',
            data: {entity_type: media_type, entity_id: channelid, share_with: inviteArray, share_type: share_type, msg: invite_msg, channel_id: channelGlobalID(),addToFeeds:1},
            callback: function (ret) {
                $('.upload-overlay-loading-fix').hide();
                $('.fancybox-close').click();
                resetSharesBoxMedia(media_object);
            }
        });
    });
    $('#addmoretext_media, #addmoretext_invite').keydown(function(event){
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

  
});
function closeFancyReload(is_album) {
    if (parseInt(is_album) == 0) {
        $('.fancybox-close').click();
        document.location.reload();
    }
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
            var data_type = auto_object.closest('.sharedata_popup').attr('data-type');
            auto_object.autocomplete('option', 'source', ReturnLink(('/ajax/channel-autocomplete-share.php') + "&ds=" + su.join(',') + "&data_type=" + data_type+ '&cid=' + current_channel_id));
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
function resetInvitesBoxMedia(media_object) {
    media_object.find("#invitetext").val('');
    media_object.find("#share_with_select").val(0);

    media_object.find(".emailcontainer_boxed_share .addmorecontainer").html('<input type="text" data-id="" value="" placeholder="' + $.i18n._("add more") + '" class="addmoretext_css" id="addmoretext_invite" name="addmoretext" >');
    resetSelectedUsers();
    addshareuserauto(media_object.find('#addmoretext_invite'));
}s
