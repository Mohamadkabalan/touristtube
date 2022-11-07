var log_page = 0;
var from_filter = "";
var activities_filter = "";

$(document).ready(function () {
    initSocialActions();
    initLogDocument();
    initLogCalendar();
    $(document).on('click', ".tt_otherlink_span", function () {
        var obj = $(this).closest('.log_item_list');
        $('#picfancyboxbutton').fancybox({            
            padding: 0,
            margin: 0,
            type: "iframe",
            width:300,
            height:340
        });
        $('#picfancyboxbutton').attr("href", ReturnLink("/ajax/popup_others_data.php?id=" + obj.find('.log_top_arrow').attr('data-id')+"&data_not=0" ) );
        $('#picfancyboxbutton').click();
    });
    $(document).on('click', ".log_top_arrow", function () {
        var newsfeed_id = $(this).attr("data-id");
        $('#log_hidden_buttons_' + newsfeed_id).toggle();
    });
    $(document).on('click', ".log_post_left_text_echo b", function () {
        var hash = $(this).html();
        hash = hash.substr(1);
        window.location.href = ReturnLink('/echoes/tag/' + hash);
    });
    $("#searchbut").click(function () {
        var lastobj = $('#left_container_newsfeed .log_item_list').last();
        lastobj.find('.log_top_arrow').attr('data-group','');
        from_filter = $("#from_filter").val();
        activities_filter = $("#activities_filter").val();
        log_page = 0;
        initLogsTTPage(0);
    });
    $(document).on('mouseover', '.privacy_icon_glob', function () {
        var diffx = $('#container').offset().left + 255;
        var diffy = $('#container').offset().top + 22;
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
    // Hide a log viewer.
    $(document).on('click', '.hide_button_log_viewer', function () {
        var log_obj = $(this).closest('.log_item_list').remove();
    });
    $(document).on('click', '#hide_all_button_log', function () {
        var log_obj = $(this);
        var action_on = "hide_all";
        if (parseInt(log_obj.attr('data-hide')) == 1) {
            action_on = "unhide_all";
        }
        updateTTNewsfeedData(log_obj, action_on);
    });
    $(document).on('click', "#log_load_more", function () {
        initLogsTTPage(1);
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

    $(document).on('click', ".cal_month", function () {
        var new_selected_month = $(this).attr("id").substr(6);
        $("#cal_selected_month").val(new_selected_month);
        $(".cal_month").css("color", "#000");
        $(".cal_month").css("background-image", "");
        initLogDocument();
        initLogCalendar();
        initLogsTTPage();
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
    $(".suggested_friends_list, .news_people_know_content_tuber").on('mouseover', 'img', function (e) {
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
            width:'100px',
            color: '#fff',
            'z-index': 10000,
            'text-align': 'center'
        }).fadeIn("fast");
    }).on('mouseout', 'img', function () {
        $("#preview").remove();
    }).mousemove(function (e) {
        //$("#preview").css("top",(e.pageY - yOffset) + "px").css("left",(e.pageX + xOffset) + "px");
    });

    //var selected_month = $("#cal_selected_month").val();
    //$('#month_' + selected_month).click();

    

});
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
        var image = new google.maps.MarkerImage(ReturnLink("/images/map_marker.png"));
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
            if ($(this).find('#rates').length > 0) addRaty($(this));
            if ($(this).find('#rates').length > 0) initRates($(this));
            if ($(this).find('#likes').length > 0) initLikes($(this));
            if (parseInt($(this).attr('data-enable')) == 1 || parseInt(is_owner) == 1) {
                $(this).find('.btn_enabled').show();
                if ($(this).find('#comments').length > 0) initComments($(this));
                if ($(this).find('#shares').length > 0) initShares($(this));
            } else {
                $(this).find('.btn_enabled').hide();
            }
            if ($(this).find('#userjoins').length > 0) initJoins($(this));
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
    initReportsLog();
}
function initReportsLog() {
    $(".report_button_log_reason").each(function () {
        var $this = $(this);
        var $parent = $this.closest('.log_item_list');
        $this.removeClass('report_button_log_reason');
        initTTLogReportFunctions($this, $parent.find('.social_data_all'));
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
    $('.upload-overlay-loading-fix').show();
    var selected_month = $("#cal_selected_month").val();
    var selected_year = $("#cal_year_display").html();
    var lastobj = $('#left_container_newsfeed .log_item_list').last();
    var entity_goup = lastobj.find('.log_top_arrow').attr('data-group');
    var entity_id = lastobj.find('.log_top_arrow').attr('data-id');
    $.ajax({
        url: ReturnLink('/ajax/ajax_newsfeed_TTpage.php?no_cache=' + Math.random()),
        cache: false,
        data: {page: log_page, entity_goup:entity_goup, entity_id:entity_id, selected_month: selected_month, selected_year: selected_year, data_profile: userGlobalID(), activities_filter: activities_filter, from_filter: from_filter},
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
                if ( data_count  >= 1) {
                    $(".buttonmorecontainer").show();
                } else {
                    $(".buttonmorecontainer").hide();
                }

            }
        }
    });
}
function initLogCalendar() {
    /*var selected_month = $("#cal_selected_month").val();
    $('#month_' + selected_month).css("background-image", "url(" + ReturnLink('/images/channel/calpicker/month_selector.png') + ")");
    $('#month_' + selected_month).css("color", "#fff");*/
}
function updateTTNewsfeedData(obj, action) {
    var id = obj.attr('data-id');
    var data_ischannel = parseInt(obj.attr('data-ischannel'));
    var data_uid = obj.attr('data-uid');

    $.ajax({
        url: ReturnLink('/ajax/info_newsfeed_update.php'),
        data: {id: id, action: action, ischannel: data_ischannel, uid: data_uid},
        type: 'post',
        success: function (data) {
            // Refresh the list where needed.
            if (data) {
                var hide_on = 'Ttuber';
                if (data_ischannel == 1) {
                    hide_on = 'channel';
                }
                if (action == 'hide_all') {
                    obj.html($.i18n._('unhide all from ') + hide_on);
                    obj.attr('data-hide', 1);
                } else if (action == 'unhide_all') {
                    obj.html($.i18n._('hide all from ') + hide_on);
                    obj.attr('data-hide', 0);
                }
            }
        }
    });
}