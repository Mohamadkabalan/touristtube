$(document).ready(function(){
    $(document).on('click',".peoplesdataclose" ,function(){
        var parents=$(this).parent();
        var parents_all=parents.parent().parent();
        parents.remove();
        if (parents.attr('data-id') != '') {
            SelectedUsersDelete(parents.attr('data-id'), parents_all.find('.addmore input'));
        }

        if(parents.attr('id')=='friendsdata'){
            parents_all.find('.uploadinfocheckbox3').removeClass('active');
        }else if(parents.attr('id')=='followersdata'){
            parents_all.find('.uploadinfocheckbox4').removeClass('active');
        }else if (parents.attr('id') == 'friends_of_friends_data') {
            parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
        }
    });
    $(document).on('click',".sharepopup_butBRCancel" ,function(){		
            $('.fancybox-close').click();
    });
    $('.visited_places_item_share').each(function (index, element) {
        var $this = $(this);
        var $parent = $this.closest('.visited_places_item');
        var data_id = $parent.attr('data-id');
        var data_country = $parent.attr('data-country');
        var data_state = $parent.attr('data-state');
        var data_city = $parent.attr('data-city');
        var data_privacy = parseInt($parent.attr('data-privacy'));
        $this.fancybox({
            padding: 0,
            margin: 0,
            beforeLoad: function () {
                $('#sharepopup').html('');
                var str = '<div class="sharepopup_container_icon"></div><div class="sharepopup_container" data-id="' + data_id + '" data-type="' + data_privacy + '"><div class="channelyellow13 formContainer100">' + t("share location") + '</div>';
                str += '<div class="font12boldBlack formContainer100 margintop15">' + data_city + '</div>';
                if (data_state != '') {
                    str += '<div class="font11boldGray formContainer100">' + data_state + '</div>';
                }
                str += '<div class="font11boldGray formContainer100">' + data_country + '</div>';
                str += '<div class="formttl13 formContainer100 margintop26">' + $.i18n._("write something") + '</div><textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' + $.i18n._('write something...') + '" style="font-family:Arial, Helvetica, sans-serif; width:401px; height:42px;" type="text" name="invitetext">' + $.i18n._('write something...') + '</textarea>';
                str += '<div class="formttl13 formContainer100 margintop15">' + t("add people (T tubers)") + '</div>';
                str += '<div class="peoplecontainer formContainer100 margintop2"><div class="emailcontainer"><div class="addmore"><input name="addmoretext" id="addmoretext" type="text" class="" data-value="' + $.i18n._("add more") + '" value="' + $.i18n._("add more") + '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>';
                str += '<div class="friendscontainer"><div class="uploadinfocheckbox uploadinfocheckbox3 formttl13 margintop8 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> ' + t("friends") + '<span style="color:#b8b8b8;"></span></div></div>';
                if (data_privacy == USER_PRIVACY_PUBLIC) {
                    str += '<div class="uploadinfocheckbox uploadinfocheckbox_friends_of_friends formttl13 margintop5 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> ' + t("friends of friends") + '<span style="color:#b8b8b8;"></span></div></div>';
                    str += '<div class="uploadinfocheckbox uploadinfocheckbox4 formttl13 margintop5 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> ' + t("followers") + '<span style="color:#b8b8b8;"></span></div></div>';
                }
                str += '</div></div><div class="sharepopup_butcontainer margintop8"><div class="sharepopup_butBRCancel sharepopup_buts">' + t("cancel") + '</div><div class="sharepopup_butseperator"></div><div id="share_popup_but" class="sharepopup_but2 sharepopup_buts">' + t("send") + '</div></div></div>';
                $('#sharepopup').html(str);
                resetSelectedUsers();
                addmoreusersautocomplete_share_places($('#addmoretext'));
            }});
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
        var data_real = $('#right_container').attr('data-real');
        if (data_real == '')
            window.location.reload();
        TTCallAPI({
            what: 'social/share',
            data: {entity_type: SOCIAL_ENTITY_VISITED_PLACES, real_user: data_real, entity_id: $parent.attr('data-id'), share_with: inviteArray, share_type: SOCIAL_SHARE_TYPE_SHARE, msg: invite_msg, channel_id: null,addToFeeds:1},
            callback: function (ret) {
                closeFancyBox();
            }
        });
    });
    $(document).on('click',".uploadinfocheckbox" ,function(){
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#friendsdata').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#friends_of_friends_data').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#followersdata').remove();
                }
            }
        } else {
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var friendstr = '<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer').prepend(friendstr);
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends of friends")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer').prepend(followerstr);
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("followers")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer').prepend(followerstr);
                }
            }
            $(this).addClass('active');
        }
    });
});


