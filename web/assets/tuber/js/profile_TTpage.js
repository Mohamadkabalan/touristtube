var log_page = 0; // pager for the log part.
var search_order = "";
var search_filter = "";
var activities_filter = "";

$(document).ready(function () {
    initLogDocument();
    initLogCalendar();
    if (no_socials_links != 0) {
        initSocialActions();
    }
    
    if (parseInt(is_owner) == 1) {
        initCalendarEvents();
        initUserCalendarEvents();
    }
    if ($('#posts_container').length > 0) {
        InitChannelUploaderHome('TT_photos_posts', 'posts_container', 15,0);
        InitChannelUploaderHome('TT_videos_posts', 'posts_container', 10240,0);
    }
    
    $(document).on('click', ".tt_otherlink_span", function () {
        var obj = $(this);
        $('#picfancyboxbutton').fancybox({            
            padding: 0,
            margin: 0,
            type: "iframe",
            width:300,
            height:340
        });
        $('#picfancyboxbutton').attr("href", ReturnLink("/ajax/popup_others_data.php?id=" + obj.attr('data-id')+"&data_not=1" ) );
        $('#picfancyboxbutton').click();
    });
    $(document).on('click', ".log_post_left_text_echo b", function () {
        var hash = $(this).html();
        hash = hash.substr(1);
        window.location.href = ReturnLink('/echoes/tag/' + hash);
    });
    $(document).on('click', ".log_post_leftecho b", function () {
        var hash = $(this).html();
        hash = hash.substr(1);
        window.location.href = ReturnLink('/echoes/tag/' + hash);
    });
    $(document).on('mouseover', '.privacy_icon_glob', function () {
        var diffx = $('#right_container').offset().left + 255;
        var diffy = $('#right_container').offset().top + 22;
        var posxx = $(this).offset().left - diffx;
        var posyy = $(this).offset().top - diffy;

        $('.privacybuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.privacybuttonsOver').css('left', posxx + 'px');
        $('.privacybuttonsOver').css('top', posyy + 'px');
        $('.privacybuttonsOver').stop().show();
    });    
    $(document).on('mouseout', '.privacy_icon_glob', function () {
        $('.privacybuttonsOver').hide();
    });
    //
    $(document).on('click', '#tt_event_link0', function () {
        $('.tt_event_link').removeClass('active');
        $(this).addClass('active');
        $('#tt_event_created').hide();
        $('#tt_event_joined').show();
    });
    $(document).on('click', '#tt_event_link1', function () {
        $('.tt_event_link').removeClass('active');
        $(this).addClass('active');
        $('#tt_event_joined').hide();
        $('#tt_event_created').show();
    });
    $(document).on('click', '.add_friend_profile', function () {
        var id = $(this).attr('rel');
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
                if (friend_section == 'friend') {
                    window.location.reload();
                } else {
                    $(".add_friend_profile").removeClass('displayMe');
                    $(".buttonTuber_seperator").hide();
//                    $(".remove_friend_profile").addClass('displayMe');
                }
            }
        });

    });
    $('.follow_friend').each(function () {
        $(this).click(function () {
            var id = $(this).attr('rel');
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
                            msg: t('Your session timed out. Please login.'),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }

                    TTAlert({
                        msg: sprintf(t('you are now following %s'), [data_user]),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    if (friend_section == 'followings') {
                        window.location.reload();
                    } else {
                        $(".follow_friend").removeClass('displayMe');
                        $(".unfollow_friend").addClass('displayMe');
                    }
                }
            });

        });
    });
    $(document).on('click', "#activity_log_but", function () {
        var $this = $(this);
        $('#activity_log_back_up').stop();
        if ($this.hasClass('active')) {
            $('#activity_log_back_up').animate({'height': 0}, 500, function () {
                $this.removeClass('active');
            });
        } else {
            $('#activity_log_back_up').animate({'height': '170px'}, 500, function () {
                $this.addClass('active');
            });
        }
    });
    $('#activity_log_container').on('mouseleave', function () {
        var $this = $('#activity_log_but');
        $('#activity_log_back_up').stop();
        $('#activity_log_back_up').animate({'height': 0}, 500, function () {
            $this.removeClass('active');
        });
    });
    $(document).on('click', "#activity_log_close", function () {
        var $this = $('#activity_log_but');
        if ($this.hasClass('active')) {
            $('#activity_log_back_up').animate({'height': 0}, 500, function () {
                $this.removeClass('active');
            });
        }
    });
    $(document).on('click', ".activity_log_buttons", function () {
        var $this = $(this);
        if (!$this.hasClass('active')) {
            $(".activity_log_buttons").removeClass('active');
            var data_value = $this.attr('data-value');
            $this.addClass('active');
            if (data_value == "all") {
                data_value = "";
            }
            activities_filter = data_value;
            initLogsTTPage();
        }
    });
    $(document).on('click', ".visited_places_item_text", function () {
        var $parent = $(this).closest('.visited_places_item');
        addMarker($parent.attr('data-lat'), $parent.attr('data-lng'));
    });
    $(document).on('click', ".visited_places_item_delete", function () {
        var $parent = $(this).closest('.visited_places_item');
        TTCallAPI({
            what: '/user/visited_places/delete',
            data: {id: $parent.attr('data-id')},
            callback: function (resp) {
                if (resp.status === 'error') {
                    TTAlert({
                        msg: resp.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                marker_array[$parent.attr('data-id')].setMap(null);
                $parent.remove();
                $('#visited_places_title').attr('data-count', (parseInt($('#visited_places_title').attr('data-count')) - 1));
                $('#visited_places_title span').html('(' + $('#visited_places_title').attr('data-count') + ')');
            }
        });
    });
    $(document).on('click', ".add_location_place", function () {
        var add_post = getObjectData($('.add_location_txt'));
        if (add_post == "" || $('.add_location_txt').attr('data-lng') == "" || $('.add_location_txt').attr('data-lat') == "") {
            TTAlert({
                msg: t('invalid location'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }

        TTCallAPI({
            what: '/user/visited_places/add',
            data: {location: add_post, longitude: $('.add_location_txt').attr('data-lng'), lattitude: $('.add_location_txt').attr('data-lat')},
            callback: function (resp) {
                if (resp.status === 'error') {
                    TTAlert({
                        msg: resp.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                var image = new google.maps.MarkerImage(generateMediaURL("/media/images/map_marker.png"));
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng($('.add_location_txt').attr('data-lat'), $('.add_location_txt').attr('data-lng')),
                    map: map,
                    icon: image,
                    title: '' + add_post
                });
                marker_array[resp.id] = marker;
                $('#visited_places_title').attr('data-count', (parseInt($('#visited_places_title').attr('data-count')) + 1));
                $('#visited_places_title span').html('(' + $('#visited_places_title').attr('data-count') + ')');
                var newstr = '<div class="visited_places_item" data-id="' + resp.id + '"  data-lat="' + $('.add_location_txt').attr('data-lat') + '" data-lng="' + $('.add_location_txt').attr('data-lng') + '"><div class="visited_places_item_text">' + add_post + '</div><div class="visited_places_item_delete"></div></div>';
                $('#visited_places_container').prepend(newstr);
                $('.add_location_txt').val('');
                $('.add_location_txt').attr('data-lng', '');
                $('.add_location_txt').attr('data-lat', '');
            }
        });
    });
    $(document).on('click', ".add_location_send", function () {
        var add_post = getObjectData($('.add_location_txt'));
        if (add_post == "" || $('.add_location_txt').attr('data-lng') == "" || $('.add_location_txt').attr('data-lat') == "") {
            TTAlert({
                msg: t('invalid location'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }

        TTCallAPI({
            what: '/social/post/add',
            data: {post_text: add_post, longitude: $('.add_location_txt').attr('data-lng'), lattitude: $('.add_location_txt').attr('data-lat'), post_type: SOCIAL_POST_TYPE_LOCATION, data_profile: userGlobalID()},
            callback: function (resp) {
                if (resp.status === 'error') {
                    TTAlert({
                        msg: resp.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                document.location.reload();
            }
        });
    });
    $(document).on('click', ".postsend", function () {

        if ($('.texttextarea').css('display') != "none")
        {
            var text_post = getObjectData($('.textareastyle'));
            var type_post = SOCIAL_POST_TYPE_TEXT;
            if (text_post == '')
            {
                return;
            }
        }
        else if ($('.linktextarea').css('display') != "none")
        {
            var text_post = getObjectData($('.linkareastyle'));
            var type_post = SOCIAL_POST_TYPE_LINK;
            if (text_post == '')
            {
                return;
            }
        } else {
            return;
        }

        TTCallAPI({
            what: '/social/post/add',
            data: {post_text: text_post, post_type: type_post, data_profile: userGlobalID()},
            callback: function (resp) {
                if (resp.status === 'error') {
                    TTAlert({
                        msg: resp.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                document.location.reload();
            }
        });
    });

    $(document).on('click', ".textdiv", function () {
        $('.linktextarea').hide();
        $('.texttextarea').show();
    });

    $(document).on('click', ".linkdiv", function () {
        $('.texttextarea').hide();
        $('.linktextarea').show();
    });
    // Remove a log.
    $(document).on('click', '#remove_button_log', function () {
        var log_obj = $(this).parent();
        updateTTLogData(log_obj, 'remove');
    });
    $(document).on('click', '#remove_button_logpost', function () {
        var log_obj = $(this).parent();
        updateTTLogData(log_obj, 'removepost');
    });

    // Hide a log.
    $(document).on('click', '#hide_button_log', function () {
        var log_obj = $(this).parent();
        updateTTLogData(log_obj, 'hide');
    });
    // Hide a log viewer.
    $(document).on('click', '.hide_button_log_viewer', function () {
        var log_obj = $(this).closest('.log_item_list').remove();
    });

    // unhide a log.
    $(document).on('click', '.unhide_log', function () {
        var log_obj = $(this).parent();
        updateTTLogData(log_obj, 'unhide');
    });

    $(document).on('click', ".log_top_arrow", function () {
        var newsfeed_id = $(this).attr("data-id");
        $('#log_hidden_buttons_' + newsfeed_id).toggle();
    });
    $(document).on('click', "#cal_left_arrow", function () {
        var selected_year = $("#cal_year_display").html();
        $("#cal_year_display").html(selected_year / 1 - 1);
        initLogsTTPage();
    });


    $(document).on('click', "#cal_right_arrow", function () {
        var selected_year = $("#cal_year_display").html();
        $("#cal_year_display").html(selected_year / 1 + 1);
        initLogsTTPage();
    });

    $(document).on('click', ".cal_month_TT", function () {
        var new_selected_month = $(this).attr("id").substr(6);
        $("#cal_selected_month").val(new_selected_month);
        $(".cal_month_TT").css("color", "#000");
        $(".cal_month_TT").css("background-image", "");
        initLogCalendar();
        initLogsTTPage();
    });
    $(document).on('click', "#log_load_more", function () {
        initLogsTTPage(1);
    });
    $(document).on('click', '.overdatabutenable', function () {
        var $this = $(this);
        var entity_id = $(this).parent().attr('data-entity-id');
        var entity_type = $(this).parent().attr('data-entity-type');

        if (String("" + $this.attr('data-status')) == "1") {
            enableSharesComments_log(entity_id, entity_type, 0);
            $this.attr('data-status', '0');
            $this.find('.overdatabutntficon').addClass('inactive');
        } else {
            enableSharesComments_log(entity_id, entity_type, 1);
            $this.attr('data-status', '1');
            $this.find('.overdatabutntficon').removeClass('inactive');
        }
    });

    $(".buttonlist_info_white_friends_list").on('mouseover', 'img', function (e) {
        if (!$(this).data('title')) {
            $(this).data('title', $(this).attr('title'));
            $(this).attr('title', '');
        }
        var title = $(this).data('title');
        var c = (title !== "") ? "<br/>" + title : "";
        $("body").append("<div id='preview'><img src='" + $(this).attr('data-src') + "' alt='Image preview' style='width: 100px;height: 100px;'/>" + c + "</div>");
        var pos_top = $(this).offset().top;
        var pos_left = $(this).offset().left + 28; //width of thumb is 28
        $("#preview").css("top", pos_top + "px").css("left", pos_left + "px").css({
            position: 'absolute',
            border: '1px solid #ccc',
            background: '#333',
            'font-size': '12px',
            padding: '5px',
            display: 'none',
            color: '#fff',
            'z-index': 10000,
            'text-align': 'center'
        }).fadeIn("fast");
    }).on('mouseout', 'img', function () {
        $("#preview").remove();
    }).mousemove(function (e) {
        //$("#preview").css("top",(e.pageY - yOffset) + "px").css("left",(e.pageX + xOffset) + "px");
    });

    $(".buttonlist_info_white_channel_list .buttonlist_info_white_channel_item").each(function () {
        var $this = $(this);
        var allPop = $(".buttonlist_info_white_channel_list .popUp");
        var thisPopup = $(this).find(".popUp");
        $this.mouseenter(function () {
            allPop.hide();
            thisPopup.show();
        });
        $this.mouseleave(function () {
            thisPopup.hide();
        });
    });


    $(document).on('click', ".buttonlist_link_click", function () {
        var $this = $(this);
        if ($this.hasClass('active')) {
            $('#buttonlist_info_containerup').css('overflow', 'hidden');
            $('#buttonlist_info_containerup').animate({'height': 0}, 500, function () {
                $('#buttonlist_info_containerup').css('overflow', 'hidden');
                $('.ttpage_collaps').removeClass('active');
                $('#buttonlist_content_buttons').show();
            });
        } else {
      /*  Change here 25 May
	  -------------------------------------------**/     
	  var height_value = $('#buttonlist_info_containerup').attr('data-height');					  
		     /* var height_value = 350;
              if (parseInt(is_owner) == 1) {
		   // height_value = '427';
			 }*/
            $('#buttonlist_info_containerup').animate({'height': height_value + 'px'}, 500, function () {
                $('#buttonlist_info_containerup').css('overflow', 'visible');
                $('.ttpage_collaps').addClass('active');
                $('#buttonlist_content_buttons').hide();
            });
        }
    });

    /*unfollow button*/
    $(document).on('click', '.unfollow_friend', function () {
        var curobj = $(this);
        var target = curobj;
        var id = target.attr('data-rel');
        var myname = "" + target.attr('data-user');

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
                            $('.chat-overlay-loading-fix').hide();
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
                            if (friend_section == 'followings') {
                                window.location.reload();
                            } else {
                                $(".unfollow_friend").removeClass('displayMe');
                                $(".follow_friend").addClass('displayMe');
                            }
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
    /*unfriend button*/
});

function contextRejectFriend(id) {
    $('.chat-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_rejectprofilefriend.php'), {fid: id}, function (data) {
        $('.chat-overlay-loading-fix').hide();
        if (friend_section == 'friend') {
            window.location.reload();
        } else {
            $(".remove_friend_profile").removeClass('displayMe');
            $(".add_friend_profile").addClass('displayMe');
        }
    });
}

// Toggle the enable shares and comments.
function enableSharesComments_log(entity_id, entity_type, new_status) {
    //$('.upload-overlay-loading-fix').show();
    var action = 'remove';
    $.ajax({
        url: ReturnLink('/ajax/info_log_updatesharescomments.php'),
        data: {entity_id: entity_id, entity_type: entity_type, new_status: new_status, globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {

            // Refresh the list where needed.
            if (action == 'remove') {
                initLogsTTPage();
            } else if (action == 'hide') {
                $("#hidden_header_" + id).css('display', 'block');
                $("#hidden_body_" + id).css('display', 'block');
            } else if (action == 'unhide') {
                $("#hidden_header_" + id).css('display', 'none');
                $("#hidden_body_" + id).css('display', 'none');
            }

            //	$('.upload-overlay-loading-fix').hide();
        }
    });
}
// Updates the newsfeed table (for the log sections).
// Handles the actions: Remove, Hide and unhide.
function updateTTLogData(obj, action) {
    var id = obj.closest('.log_item_list').find('.log_top_arrow').attr('data-id');

    //$('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/info_log_update.php'),
        data: {id: id, action: action},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.result == 'ok') {
                if(action == 'remove' || action == 'removepost'){
                    document.location.reload();
                } else if (action == 'hide') {
                    obj.closest('.log_item_list').find(".social_data_all").hide();
                    obj.closest('.log_item_list').find("#hidden_header_" + id).css('display', 'block');
                    obj.closest('.log_item_list').find("#hidden_body_" + id).css('display', 'block');
                } else if (action == 'unhide') {
                    if( !obj.closest('.log_item_list').find("#hidden_body_" + id).hasClass('unhide_logcont') ) obj.closest('.log_item_list').find(".social_data_all").show();
                    obj.closest('.log_item_list').find("#hidden_header_" + id).css('display', 'none');
                    obj.closest('.log_item_list').find("#hidden_body_" + id).css('display', 'none');
                }
            } else {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
            //	$('.upload-overlay-loading-fix').hide();
        }
    });
}
function initLogsTTPage(update) {
    // If the function is called without argument == first load, not "load more"
    if (!update) {
        update = 0;
        log_page = 0;
        // Load more case.
    } else {
        log_page++;
    }
    $("#activity_log_close").click();

    // Get the selected date.
    var selected_month = $("#cal_selected_month").val();
    var selected_year = $("#cal_year_display").html();
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        cache: false,
        url: ReturnLink('/ajax/ajax_log_TTpage.php?nocache='+Math.random()),
        data: {page: log_page, selected_month: selected_month, selected_year: selected_year, globchannelid: channelGlobalID(), search_order: search_order, search_filter: search_filter, data_profile: userGlobalID(), activities_filter: activities_filter},
        type: 'post',
        success: function (data) {
            $('.upload-overlay-loading-fix').hide();
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
            } else {
                var myData = ret.data;
                var data_count = ret.count;

                if (update == 0)
                    $("#log_data_container").html('');

                $("#log_data_container").append(myData);
                initLogDocument();
                initCommentData();

                // Toggle the visibility of the "load more" button.
                if (data_count - log_page * 10 > 10) {
                    $(".buttonmorecontainer").show();
                } else {
                    $(".buttonmorecontainer").hide();
                }

            }
        }
    });
}
// Initializes the log-section Calendar.
function initLogCalendar() {
    var selected_month = $("#cal_selected_month").val();
    $('#month_' + selected_month).css("background-image", "url(" + generateMediaURL('/media/images/channel/calpicker/month_selector.png') + ")");
    $('#month_' + selected_month).css("color", "#fff");
}

function initLogDocument() {
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
        if($('.edit_post_social').length>0){
            initPostEdit();
        }
        $('.echoImg_link').fancybox({
            helpers : {
                overlay : {closeClick:true}
            }
        });
        $('.postImg_link0').fancybox({
            helpers : {
                overlay : {closeClick:true}
            }
        });
        $(".postImg_link1").fancybox({
		transitionIn: 'none',
		transitionOut: 'none',		
		autoScale: false,
		autoDimensions: false,		
		width: 694,
		minWidth: 694,
		maxWidth: 694,
		height: 442,
		minHeight: 442,
		maxHeight: 442,
		padding	:0,
		margin	:0,
		type: 'iframe',
		scrolling: 'no',
                helpers : {
                    overlay : {closeClick:true}
                }
	});
    $('.event_googlemap').each(function (index, element) {
        var $googlemap_location = $(this).find('.googlemap_location');
        var $id_location = $googlemap_location.attr('id');
        var image = new google.maps.MarkerImage(generateMediaURL("/media/images/map_marker.png"));
        var mapOptions = {
            center: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById($id_location), mapOptions);
        
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
            map: map,
            icon: image,
            title: ''
        });
    });
    $('.social_data_all').each(function (index, element) {
        if ($(this).find('.social_not_refreshed').length > 0) {
//            if ($(this).find('#rates').length > 0) addRaty($(this));
//            if ($(this).find('#rates').length > 0) initRates($(this));
//            if ($(this).find('#likes').length > 0) initLikes($(this));
            if (parseInt($(this).attr('data-enable')) == 1 || parseInt(is_owner) == 1) {
                $(this).find('.btn_enabled').show();
//                if ($(this).find('#comments').length > 0) initComments($(this));
//                if ($(this).find('#shares').length > 0) initShares($(this));
            } else {
                $(this).find('.btn_enabled').hide();
            }
//            if ($(this).find('#userjoins').length > 0) initJoins($(this));
        }
    });
    $(".log_item_container, .log_events_container, .log_sponsor_container, .log_news_container").each(function () {
        var $this = $(this);
        $this.on('mouseenter', function () {
            $this.find('.log_item_right').show();
            $this.find('.enlarge').show();
        }).on('mouseleave', function () {
            $this.find('.log_item_right').hide();
            $this.find('.enlarge').hide();
        });
    });
    $(".log_media_container .picture").each(function () {
        var $this = $(this).parent().parent();
        $this.on('mouseenter', function () {
            $this.find('.log_item_right').show();
            $this.find('.enlarge').show();
        }).on('mouseleave', function () {
            $this.find('.log_item_right').hide();
            $this.find('.enlarge').hide();
        });
    });
    $(".log_media_container .footer_image").each(function () {
        var $this = $(this).parent();
        $this.on('mouseenter', function () {
            $this.find('.enlarge').show();
        }).on('mouseleave', function () {
            $this.find('.enlarge').hide();
        });
    });
    $('.stanClick_photos_post_log').each(function (index, element) {
        var $This = $(this);
    
        var dId = $This.attr('data-id');
        var channelid = $This.attr('data-channelid');

        $This.attr("href", ReturnLink('parts/user-viewphoto-post.php?dId=' + dId + '&data_profile=' + userGlobalID()));
        $This.fancybox({
            "width": '885',
            "height": '620',
            "transitionIn": "none",
            "transitionOut": "none",
            "autoSize": false,
            "padding": 0,
            "margin": 0,
            "scrolling": 'no',
            "scrollOutside": false,
            "type": "iframe",
            "afterLoad": unscrollIframe
        });

    });
    $('.stanClick_videos_post_log').each(function (index, element) {
        var $This = $(this);

        var vid = $This.attr('data-id');
        var channelid = $This.attr('data-channelid');

        $This.attr("href", ReturnLink('parts/user-viewvideo-post.php?vid=' + vid + '&data_profile=' + userGlobalID()));

        $This.fancybox({
            "width": '885',
            "height": '620',
            "transitionIn": "none",
            "transitionOut": "none",
            "autoSize": false,
            "padding": 0,
            "margin": 0,
            "scrolling": 'no',
            "type": "iframe",
            "afterLoad": unscrollIframe
        });

    });
    initReportsLog();
}
function initReportsLog() {
    $(".report_button_log_reason").each(function () {
        var $this = $(this);
        var $parent = $this.closest('.log_item_list');
        $this.removeClass('report_button_log_reason');
        initTTLogReportFunctions($this, $parent.find('.social_data_all'));
    });
}
function initCalendarEvents() {
    // Calendar Setup
    var DateIndex = 0;
    var currDateIndex = 0;
    var calTimer = "";
    var timer;
    EventsDetailedCal = Calendar.setup({
        cont: "idEventsCalendar",
                noScroll  	 : true,
            fixed: true,
        bottomBar: false,
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

function initUserCalendarEvents() {
    // Calendar Setup
    var DateIndex = 0;
    var currDateIndex = 0;
    var calTimer = "";
    var timer;
    EventsDetailedCal = Calendar.setup({
        cont: "idEventsCalendar_user",
                noScroll  	 : true,
            fixed: true,
        bottomBar: false,
        selectionType: Calendar.SEL_MULTIPLE,
        disabled: function () {
            return true;
        },
        dateInfo: getDateUserInfo
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
                $(".DynarchCalendar-day-selected.highlight_user, .DynarchCalendar-day-selected.highlight_user2").each(function (index, element) {
                    //$(this).removeAttr("disabled");
                    //$(this).attr("unselectable", "on");

                    $(this).parent().mouseenter(function () {
                        // get the date from div attribute
                        DateIndex = $(this).find('.DynarchCalendar-day-selected').attr('dyc-date');
                        currDateIndex = 0;
                        var currdata = DATE_USER_INFOInit[DateIndex][0];
                        // set the values in tooltip from object and set the position
                        $(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalEventImg").attr('src', currdata['imageurl']);
                        var link_a = ReturnLink('/events-detailed/' + currdata['id']);
                        $(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a").attr('href', link_a);
                        $(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalTooltipTitle").html(currdata['title']);
                        $(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltipClose").attr('data-DateIndex', DateIndex);
                        var TooltipTop = $(this).position().top;
                        var TooltipLeft = $(this).position().left;
                        $(".ed_CalTooltipContent_user").css('margin-top', (TooltipTop + 63) + 'px').css('margin-left', (TooltipLeft - 33) + 'px').show();
                    });
                    // hide the tooltip after leave the mouse on tooltip and date selected
                    $(this).parent().mouseleave(function () {
                        $(".ed_CalTooltipContent_user").hide();
                    });
                });

            }, 500);
        }
    });
    $('.ed_CalTooltipContent_user').mouseenter(function () {
        // if the mouse hover the tooltip clear time to hide it
        $(this).show();
    });
    $('.ed_CalTooltipContent_user').mouseleave(function () {
        // if the mouse hover the tooltip clear time to hide it
        $(this).hide();
    });
    // hide tooltip on close click
    $(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltipClose").click(function () {
        currDateIndex++;
        if (DATE_USER_INFOInit[$(this).attr('data-DateIndex')].length > currDateIndex) {
            var currdata = DATE_USER_INFOInit[$(this).attr('data-DateIndex')][currDateIndex];
            // set the values in tooltip from object and set the position
            $(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalEventImg").attr('src', currdata['imageurl']);
            var link_a = ReturnLink('/events-detailed/' + currdata['id']);
            $(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a").attr('href', link_a);
            $(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalTooltipTitle").html(currdata['title']);
        } else {
            $(".ed_CalTooltipContent_user").hide();
        }
    });
    // hide tooltip when leave it
    $(".ed_CalTooltipContent_user .ed_CalTooltip").mouseleave(function ()
    {
        $(".ed_CalTooltipContent_user").hide();
    });
    // retrive the dates selected in DATE_USER_INFO object
    var arrSelectionSet = new Array(), i = 0;
    for (var key in DATE_USER_INFO)
    {
        arrSelectionSet[i] = key;
        i++;
    }
    // select dates in calendar
    EventsDetailedCal.selection.set(arrSelectionSet);
}
function getDateUserInfo(date, wantsClassName) {
    var as_number = Calendar.dateToInt(date);

    var myDateArr = {};
    if (DATE_USER_INFOInit[as_number]) {
        var classArray = new Array();
        for (var i = 0; i < DATE_USER_INFOInit[as_number].length; i++) {
            var obj = DATE_USER_INFOInit[as_number][i];
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

function updateImage_Add_Posts(curname, filetype) {
    var type_post = SOCIAL_POST_TYPE_VIDEO;
    if (filetype == "TT_photos_posts") {
        type_post = SOCIAL_POST_TYPE_PHOTO;
    }
    TTCallAPI({
        what: '/social/post/add',
        data: {post_text: curname, post_type: type_post, data_profile: userGlobalID()},
        callback: function (resp) {
            if (resp.status === 'error') {
                TTAlert({
                    msg: resp.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
            document.location.reload();
        }
    });
    closeFancyBox();
}
function addMarker(la, lo) {
    map.setCenter(new google.maps.LatLng(la, lo));
    map.setZoom(14);
}
function mapShareLoc(obj) {
    var data_link = $(obj).attr('data-link');
    $('#' + data_link).click();
}


/*------------  25 MAy  ------------------------------*/
/* New add */ 
 $(document).ready( function () {
    var wid =  $(window).width();
		var height_value;
	    if (wid<768) 
		   { 
		    	height_value = 900;
		       $('#buttonlist_info_containerup').css({'height': height_value +'px'});
			}
		 else if(wid>768 && wid<960)
		    {
			   	height_value = 780;
			    $('#buttonlist_info_containerup').css({'height': height_value +'px'});		
			 }
		 else{ 
				 height_value = 427;
		    	 $('#buttonlist_info_containerup').css({'height': height_value +'px'});		
			 }
			 
	   $('#buttonlist_info_containerup').attr('data-height',height_value);			    
  });