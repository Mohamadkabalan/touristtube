var currentpage_like = 0;
var currentpage_rates = 0;
var currentpage_comments = 0;
var currentpage_replies = 0;
var currentpage_reechoes = 0;
var currentpage_shares = 0;
var currentpage_sponsors = 0;
var currentpage_userjoin = 0;

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

function initSocialActions() {
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
            msg: $.i18n._('you have to sign in, in order to access a tuber page'),
            type: 'action',
            btn1: t('cancel'),
            btn2: 'register',
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
    $(document).on('click', "#sharepopup .uploadinfocheckbox, .social_data_all .uploadinfocheckbox", function () {
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
            }
            $(this).addClass('active');
        }
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
            } else if (obj.attr('id') == "option_6") {
                inviteArray.push({connections: 1});
            } else if (obj.attr('id') == "option_2") {
                inviteArray.push({friendsandfollowers: 1});
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
        removeRate($(this));
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
    $(document).on('click', ".reechoe_but", function () {
        var media_object = $(this).closest('.social_data_all');
        var mediaid = media_object.attr("data-id");
        add_Reechoes_Item(media_object, mediaid);
    });

    $(document).on('click', ".likesDiv .showMore_like", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');

        currentpage_like = parseInt($(this).closest('.social_data_all').attr("data-page-like"));
        currentpage_like++;
        $(this).closest('.social_data_all').attr("data-page-like", currentpage_like);

        getItemsLikesRelated($(this).closest('.social_data_all'));
    });
    $(document).on('click', ".reechoesDiv .showMore_reechoes", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');
        currentpage_reechoes = parseInt($(this).closest('.social_data_all').attr("data-page-reechoes"));
        currentpage_reechoes++;
        $(this).closest('.social_data_all').attr("data-page-reechoes", currentpage_reechoes);
        getItemsReechoesRelated($(this).closest('.social_data_all'));
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
    $(document).on('click', ".userjoinsDiv .showMore_joins", function () {
        var media_type = $(this).closest('.social_data_all').attr('data-type');

        currentpage_userjoin = parseInt($(this).closest('.social_data_all').attr("data-page-userjoin"));
        currentpage_userjoin++;
        $(this).closest('.social_data_all').attr("data-page-userjoin", currentpage_userjoin);

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
        $('#commentDiv .shadow').each(function (index, element) {
            if (!$(this).hasClass('hide')) {
                $(this).find('.closeDiv').click();
            }
        });
        $('#comment_reportDiv').hide();
        $('#album_reportDiv').show();
    });
    $(document).on('click', ".report_button_comment_popup_view", function () {
        $('#comment_reportDiv').attr('data-id', $(this).attr('data-id'));
        $('#commentDiv .shadow').each(function (index, element) {
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
            ret: 'json',
            callback: function (data) {
                var lkcount = parseInt(parseInt($this.attr('data-count')) + 1);
                $this.attr('data-count', lkcount);
                var likestr = " "+ t('likes');
                if(lkcount == 1){
                    likestr = " "+ t(' like');
                }
                $this.parent().find('.cmts_likeDivyellowTxt').html( lkcount + likestr );
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
        $(this).closest('.userjoinsDiv').find("#joins_login_error_msg").removeClass('displaynone');
    });


    $(document).on('click', '.comments_unlike_but', function () {
        var $this = $(this);
        var comment_id = $(this).attr('data-id');

        TTCallAPI({
            what: 'social/like', // Destination address (uapi_inc/social/like.php).
            data: {entity_id: comment_id, entity_type: SOCIAL_ENTITY_COMMENT, like_value: 0},
            ret: 'json',
            callback: function (data) {
                var lkcount = parseInt(parseInt($this.attr('data-count')) - 1);
                $this.attr('data-count', lkcount);
                var likestr = " "+ t('likes');
                if(lkcount == 1){
                    likestr = " "+ t(' like');
                }
                $this.parent().find('.cmts_likeDivyellowTxt').html( lkcount + likestr );
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
        if ($(this).hasClass('opacitynone')) {
            return;
        }
        $(this).prev().click();
    });
    $(document).on('click', ".btn", function () {
        var ThisImage = $(this).find("div").first();
        if ($(this).hasClass('opacitynone')) {
            return;
        }

        $('#comment_reportDiv').hide();
        $('#album_reportDiv').hide();
        if (!ThisImage.hasClass('selected')) {
            $('#commentDiv .shadow').each(function (index, element) {
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
            }else if( ThisImage.attr('id')=="edit" ){
                thisID = "edit";
            }
            if ($("." + thisID + "Div"))
                $(this).closest('.social_data_all').find("." + thisID + "Div").removeClass("hide");
            if (pagename == "channel_log") {
                $('.log_item_list').css('z-index', 0);
                $(this).closest('.log_item_list').css('z-index', 20);
            }
            ThisImage.addClass('selected');
            if (thisID == "description") initscrollPaneSocialDesc($(this).closest('.social_data_all').find(".scrollpane_description"), false);
            // Init the shares autocomplete for the icon image "shares".
            if (thisID == "shares" && (parseInt(user_Is_channel) == 0 || parseInt(is_owner) == 1)) {
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
            // Special conditions for the "userjoins" section..
            else if (thisID == "userjoins") {
                resetJoinsItems($(this).closest('.social_data_all'));
                setJoinsItems($(this));
                getJoinDetails($(this).closest('.social_data_all'));
            }
            // Special conditions for the "description" section..
            else if (thisID == "description" && pagename == "journal") {
                getJournalDetails($(this).closest('.social_data_all'));
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

    $(document).on('click', ".sharepopup_butBRCancel", function () {
        $('.fancybox-close').click();
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
        } else if (parents.attr('id') == 'friends_of_friends_data') {
            parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
        }
    });

    initCommentData();


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
    $('.followFriend').live('click', function () {
        var $this = $(this);
        var $name = $(this).parent().parent().find('.event_InvitedName').html();
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
                    TTAlert({
                        msg: jres.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    if (data_status == "2") {
                        //Most active tuber
                        var $parent = $this.parent().parent();
                        if ($parent.parent().parent().find('ul li').length >= 21) {
                            getMoreActiveTuber();
                        }
                        $parent.remove();
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

} // End initSocialActions.

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
                $(".sortlikes").html(divNum);

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
                initscrollPaneSocial(media_object.find(".scrollpane_likes"), true);
                initLikersData();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
function getItemsReechoesRelated(media_object) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_reechoes_echoe.php?no_cache'+ Math.random() ),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: media_object.attr("data-page-reechoes")},
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
                var divNum = parseInt(ret.count);

                media_object.find('.reechoesDiv .containerDiv .commentsAll .commentsAll_inside').append(myData);
                media_object.find(".reechoesNumber").html(divNum);

                if (divNum <= 7) {
                    media_object.find(".reechoesDiv .showMore_reechoes").hide();
                } else {
                    currentpage_reechoes = parseInt(media_object.attr("data-page-reechoes"));
                    if ((currentpage_reechoes + 1) * 7 >= divNum) {
                        media_object.find(".reechoesDiv .showMore_reechoes").hide();
                    } else {
                        media_object.find(".reechoesDiv .showMore_reechoes").show();
                    }
                }
                media_object.find(".reechoesDiv .commentsAll").addClass("scrollpane_reechoes");
                initscrollPaneSocial(media_object.find(".scrollpane_reechoes"), true);
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
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: media_object.attr("data-page-rates"), globchannelid: channelGlobalID(),
            cache: Math.random()},
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
                $(".sortrating").html(divNum);
                
                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_r').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_r').html(divNum + " " + t('ratings'));
                    } else {
                        item_list.find('.news_count_r').html(divNum + " " + t('rating'));
                    }
                }

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
                initscrollPaneSocial(media_object.find(".scrollpane_rates"), true);
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
                $(".sortshares").html(divNum);
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
                initscrollPaneSocial(media_object.find(".scrollpane_shares"), true);
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
                    initscrollPaneSocial(media_object.find(".scrollpane_sponsors"), true);
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
        url: ReturnLink('/ajax/ajax_all_userjoins_media.php'),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: media_object.attr("data-page-userjoin")},
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

                media_object.find('.userjoinsDiv .containerDiv .commentsAll .commentsAll_inside').append(myData);
                media_object.find(".joinsNumber").html(divNum);

                var mylimit = 6;
                if (parseInt(user_Is_channel) == 0 && parseInt(user_is_logged) == 1) {
                    mylimit = 5;
                }

                if (divNum <= mylimit) {
                    media_object.find(".userjoinsDiv .showMore_joins").hide();
                } else {
                    currentpage_userjoin = parseInt(media_object.attr("data-page-userjoin"));
                    //console.log(currentpage_userjoin+"]["+mylimit);
                    if ((currentpage_userjoin + 1) * mylimit >= divNum) {
                        media_object.find(".userjoinsDiv .showMore_joins").hide();
                    } else {
                        media_object.find(".userjoinsDiv .showMore_joins").show();
                    }
                    media_object.find(".userjoinsDiv .commentsAll").addClass("scrollpane_userjoin");
                    initscrollPaneSocial(media_object.find(".scrollpane_userjoin"), true);
                }
                initLikersDataUserJoins();
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
function add_Reechoes_Item(media_object, data_id) {
    $('.upload-overlay-loading-fix').show();
    TTCallAPI({
        what: '/echoe/reechoe/add',
        data: {entity_id: data_id},
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
            media_object.find('.reechoesDiv .containerDiv .commentsAll .commentsAll_inside').html('');
            currentpage_reechoes = 0;
            media_object.attr("data-page-reechoes", 0);
            getItemsReechoesRelated(media_object);
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

    media_object.find(".sharesDiv .emailcontainer_boxed_share").html('<div class="addmore"><input name="addmoretext_brochure" id="addmoretext_brochure" type="text" class="addmoretext_css" data-value="' + $.i18n._("add more") + '" value="' + $.i18n._("add more") + '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div>');
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

    media_object.find(".sponsorsDiv .emailcontainer_boxed_share").html('<div class="addmore"><input name="addmoretext_sponsors" id="addmoretext_sponsors" type="text" class="addmoretext_css" data-value="' + $.i18n._("add more") + '" value="' + $.i18n._("add more") + '" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div>');
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

function initscrollPaneSocial(obj, _flag) {
    //if( obj.find('.jspContainer').length==0 ){
        obj.jScrollPane();
        if (_flag) {
            try {                
                var jscrol_api = obj.data('jsp');
                jscrol_api.scrollToBottom(true);
            } catch (e) {

            }
        }
    //}
}
function initscrollPaneSocialDesc(obj, _flag) {
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
        what: 'user/join/edit',
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
                    obj.find('.hide_remove_butons_over').html('add');
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
    var ret;
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
        url: ReturnLink('/ajax/ajax_remove_userjoin_event.php'),
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
                    $('.upload-overlay-loading-fix').hide();
                }
            }
        }
    });
}
function addJoinItem(obj, media_type) {
    var ret;
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_add_userjoin_event.php'),
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
function removeRate(obj) {
    $('.upload-overlay-loading-fix').show();
    TTCallAPI({
        what: 'social/rate/delete',
        data: {id: obj.attr('data-id')},
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
            var divNum = resp.count;
            obj.closest('.social_data_all').find(".ratesNumber").html(divNum);
            $(".sortrating").html(divNum);
            if (item_list.find('.news_count_r').length > 0) {                
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
            obj.parent().parent().parent().remove();
            socialObj.find('#myrating_score').val(0);
            addRaty(socialObj);
            if (socialObj.find('.showMore_rates').css('display') != "none") {
                socialObj.find('.showMore_rates').click();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
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
                $(".sortlikes").html(divNum);
                
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
                media_object.find(".likesDiv .commentsAll").addClass("scrollpane_likes");

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_l').length > 0) {
                    var nb_likes = divNum;
                    if (nb_likes > 1 || nb_likes == 0) {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('likes'));
                    } else {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('like'));
                    }
                }

                initLikersData();
            }
        }
    });
}
function initReechoes(media_object) {
    currentpage_reechoes = 0;
    media_object.attr("data-page-reechoes", 0);
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_reechoes_echoe.php'),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0},
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
                var is_liked = ret.is_liked;
                var divOwner = parseInt(ret.is_owner);
                if (divOwner == 0) {
                    $('.echoe_report_top_button').removeClass('displaynone');
                } else {
                    $('.echoe_report_top_button').addClass('displaynone');
                }
                
                media_object.find('.reechoesDiv .containerDiv').html('');
                media_object.find('.reechoesDiv .containerDiv').html('<div class="commentsAll max_height412"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".reechoesNumber").html(divNum);

                if (divNum <= 7) {
                    media_object.find(".reechoesDiv .showMore_reechoes").hide();
                } else {
                    media_object.find(".reechoesDiv .showMore_reechoes").show();
                }
                media_object.find(".reechoesDiv .commentsAll").addClass("scrollpane_reechoes");
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
                var rated_value = ret.rated_value;
                media_object.find('.popup_view_rate').attr('id', 'popup_view_rate' + rated_value);

                media_object.find('.ratesDiv .containerDiv').html('');
                media_object.find('.ratesDiv .containerDiv').html('<div class="commentsAll max_height412"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".ratesNumber").html(divNum);
                $(".sortrating").html(divNum);
                
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
                $(".sortcomments").html(divNum);
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
    currentpage_shares = 0;
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
                media_object.find('.sharesDiv .containerDiv')
                        .html('<div class="commentsAll ' + shares_height + '"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".sharesNumber").html(divNum);
                $(".sortshares").html(divNum);
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
    currentpage_userjoin = 0;
    media_object.attr("data-page-userjoin", 0);
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_userjoins_media.php'),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0},
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
                media_object.find('.userjoinsDiv .containerDiv').html('');
                media_object.find('.userjoinsDiv .containerDiv').html('<div class="commentsAll ' + mdheight + '"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".joinsNumber").html(divNum);

                if (divNum <= mylimit) {
                    media_object.find(".userjoinsDiv .showMore_joins").hide();
                } else {
                    media_object.find(".userjoinsDiv .showMore_joins").show();
                    media_object.find(".userjoinsDiv .commentsAll").addClass("scrollpane_userjoin");
                }
                initLikersDataUserJoins();
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
        obj.parent().find('.mentions_dup').html(obj.parent().find('.mentions div').text() + " ");
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

            if (pagename == "media_page") {
                try {
                    ReloadComments();
                } catch (e) {

                }
            }
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
                $(".sortcomments").html(divNum);

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_c').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_c').html(divNum + " " + t('comments'));
                    } else {
                        item_list.find('.news_count_c').html(divNum + " " + t('comment'));
                    }
                }
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

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_j').length > 0) {
                    var divNum = divNum;
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_j').html(divNum + " " + t('joining guests'));
                    } else {
                        item_list.find('.news_count_j').html(divNum + " " + t('joining guest'));
                    }
                }

                media_object.find(".commentsDiv .commentsAll").addClass("scrollpane_comments");
                initscrollPaneSocial(media_object.find(".scrollpane_comments"), true);
                initLikersDataComments();
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
}

function initLikersDataUserJoins() {
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
}


// Resets the "userjoins" fields to their initial states before they are filled.
function resetJoinsItems(media_object) {
    media_object.find("#event_join_yes").attr('checked', false);
    media_object.find("#event_join_no").attr('checked', false);
    media_object.find("#side_panel_join_text").html("");
    media_object.find("#join_guests").hide();
    media_object.find("#join_guests_number").val(0);
}

// Set the texts for the "userjoins" section depending on the event's category (past, current or upcoming).
function setJoinsItems(obj, event_category) {
    event_category = obj.closest('.social_data_all').attr('data-category').substring(0, 11);

    if (event_category == 'past_events') {
        obj.closest('.social_data_all').find("#side_panel_join_text").html(t("did you join?"));
    } else {
        obj.closest('.social_data_all').find("#side_panel_join_text").html(t("would you like to join?"));
        obj.closest('.social_data_all').find("#join_guests").show();
    }
}

// Set the details if the user already joined that event.
function getJoinDetails(media_object) {
    $.ajax({
        url: ReturnLink('/ajax/ajax_userjoin_media.php'),
        data: {media: media_object.attr('data-type'), mediaid: media_object.attr('data-id'), page: 0},
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
        url: ReturnLink('/ajax/ajax_userjoin_save_media.php'),
        data: {join_event: join_event, guests_count: guests_count, media: mediatype, mediaid: mediaid, page: 0},
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
function share_selectDisabled(obj) {
    var $parent = $(obj).parent();
    $parent.find('.disabledmessage').removeClass('displaynone');
}
function initReportFunctions(obj) {
    obj.fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            var data_type = $('.social_data_all').attr('data-type');
            var data_id = $('.social_data_all').attr('data-id');

            $('#sharepopup').html('');
            var str = '<div class="sharepopup_container" style="margin-left:34px; width:506px; height:312px"></div>';
            $('#sharepopup').html(str);

            disconnect_notification_toggle = 0;
            $.ajax({
                type: "POST",
                cache: false,
                url: ReturnLink("/ajax/popup_report_data.php?no_cache=" + Math.random()),
                data: {type: data_type, show_disconnect: 0, data_id: data_id, show_sponsors: 0, channel_id: channelGlobalID(), data_friend_section: ''},
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
$(document).ready(function () {// Show or hide parts of the owner-report popup based on the user's choice of what to report.
    if ($("#report_button_log_viewer_cam").length > 0) {
        initReportFunctions($("#report_button_log_viewer_cam"));
    }
    $(document).on('click', 'input:radio[name=reportTuberCategory]', function () {
        if ($(this).val() == 'tuber') {
            $("#fillerDiv").hide();
            $("#reportContent").hide();
            $("#reportTuber").show();
            $(".sharepopup_butcontainer").show();
        } else {
            $("#fillerDiv").hide();
            $("#reportTuber").hide();
            $("#reportContent").show();
            $(".sharepopup_butcontainer").show();
        }
    });
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
});
function initTTLogReportFunctions(obj, $parent) {
    obj.fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            var data_type = $parent.attr('data-type');
            var data_id = $parent.attr('data-id');
            var data_channel = $parent.attr('data-channel');
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
                data: {type: data_type, show_disconnect: show_disconnect, data_id: data_id, show_sponsors: 0, channel_id: data_channel, data_friend_section: ''},
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