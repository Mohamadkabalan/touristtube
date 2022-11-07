var privacyValue = -1;
var privacyArray = new Array();
var $modal_image = null;
var $modal_content_container = null;
$(document).ready(function (e) {
    $modal_content_container = $("#imgInside").parent();
    $modal_image = $("#imgInside");
    /**
     * video part
     */
    $('.exportVideo').on('click', function () {
        if (checkArray(SelectionArray) != 0) {
            $('.save-thumb').hide();
            $('.delThumbs').hide();
            $('.save-thumb-separator').hide();
            $('.save-thumb-message').show();
            var is_post = $('.exportVideo').attr('data-post');
            $.post(ReturnLink('/ajax/ajax_edit_video.php'), {frames: SelectionArray, allf: AllFrames, id: $('.all_container').attr('data-id'), is_post: is_post}, function (data) {
                SelectionArray = [];
                if(is_post){
                    parent.window.closeFancyExportVideo();
                }
                else{
                    parent.window.closeFancyExportVideo($('.all_container').attr('data-export'));
                }
            });
        }
    });
    $(document).on('click', ".imageitem", function () {
        $this = $(this).find('.thumb-pic-img');
        var ThisRel = $this.attr('rel');
        var ThisIndex = ThisRel - 1;
        if (!$this.hasClass('selected')) {
            $(this).find('.yellowborder').addClass("active");
            $this.addClass('selected');
            if (jQuery.inArray(ThisRel, SelectionArray) == -1) {
                SelectionArray[ThisIndex] = ThisRel;
            }
            $('.delThumbs').addClass("active");
        } else {
            $(this).find('.yellowborder').removeClass("active");
            $this.removeClass('selected');
            SelectionArray[ThisIndex] = '';
            var up_delete = true;
            $('.yellowborder.active').each(function () {
                if ($(this).closest('.imageitem').css('display') != 'none') {
                    up_delete = false;
                }
            });
            if (up_delete) {
                $('.delThumbs').removeClass("active");
            } else {
                $('.delThumbs').addClass("active");
            }
        }
    });

    $('.delThumbs').on('click', function () {
        DeletedArray = [];
        $(".imageitem .thumb-pic-img").each(function () {
            var ThisAttr = $(this).attr('rel');
            if (jQuery.inArray(ThisAttr, SelectionArray) != -1) {
                $(this).parent().css('display', 'none');
                DeletedArray.push(ThisAttr);
            }
        });
        if (DeletedArray.length != 0) {
            $('.undoThumbs').css('display', 'block');
        }
        $('.delThumbs').removeClass("active");
        updateReintialiseslider();
    });
    $('.undoThumbs').on('click', function () {
        SelectionArray = [];
        $(".imageitem .thumb-pic-img").each(function () {
            var ThisAttr = $(this).attr('rel');
            if (jQuery.inArray(ThisAttr, DeletedArray) != -1) {
                $(this).parent().css('display', 'block');
                $(this).next().removeClass("active");

            }
        });
        $(".imageitem .thumb-pic-img").removeClass('selected');
        updateReintialiseslider();
    });

    /**
     * video and phot part
     */
    $('.edit_input').blur();
    addAutoCompleteList();
    
    if (parseInt(channelid) == 0) {
        addmoreusersautocomplete_custom_journal($('#addmoretext_custum_privacy_detailed'));
    }
    $(document).on('click', ".top_arrow", function () {
        $('.hidden_buttons').toggle();
    });
    $(document).on('click', '.overdatabutenable', function () {
        var $this = $(this);
        var entity_id = $('.all_container').attr('data-id');
        var entity_type = $('.all_container').attr('data-type');

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
    $(document).on('click', "#canceleventdata", function () {
        $('.error_valid_privacy').html('');
        parent.window.closeFancy();
    });
    $(document).on('click', "#add_but", function () {
        parent.window.addMediaUpload($('.all_container').attr('data-upuri'));
    });
    $(document).on('click', "#remove_button", function () {
        parent.window.addMediaRemove($('.all_container').attr('data-link'));
    });
    $(document).on('click', "#cancel_custom_privacy", function () {
        $('.error_valid_privacy').html('');
        $('.peoplecontainer_custom').addClass('displaynone');
        $('.info_pop_container').show();
    });
    
    function temp(which){
            var ok = true;
        var $currobjselected = $('#' + which).find('#edit_info_part');

        $('.uploadinfomandatory span', $currobjselected).html('');
        $('input,select,textarea', $currobjselected).removeClass('err').each(function () {
            var name = $(this).attr('name');
            var $parenttarget = $(this).parent();
            if ((name == 'location') || (name == 'location_name') || (name == 'placetakenat') || (name == 'cityname') || (name == 'status') || (name == 'vid') || $(this).hasClass('filevalue') || (name == 'filename') || (name == 'addmoretext') || (name == 'vpath')) {

            } else {
                if (($('.uploadinfomandatory' + name, $parenttarget).css('display') != 'none' && getObjectData($(this)) == '') || (name == "category" && !$('.uploadinfomandatorycategory', $parenttarget).hasClass('inactive') && $(this).val() == '0') || (name == "country" && !$('.uploadinfomandatorycountry', $parenttarget).hasClass('inactive') && $(this).val() == '0')) {
                    $('.uploadinfomandatory' + name + ' span', $parenttarget).html(t('please fill this field correctly'));
                    ok = false;
                }
            }
        });

        var $parenttarget = $('input[name=cityname]', $currobjselected).parent();

        if (getObjectData($('input[name=cityname]', $currobjselected)) == '' && !$('.uploadinfomandatorycitynameaccent', $parenttarget).hasClass('inactive') && $('input[name=cityname]', $currobjselected).length > 0) {
            $('.uploadinfomandatorycitynameaccent span', $parenttarget).html(t('please fill this field correctly'));
            ok = false;
    //                        console.log(5);
        }
        if (!ok) {

            return false;
        } else {
            return true;
        }
    }
    
    $(document).on('click', '#savepostdata_edit', function () {
        var post_text = $('#edit_post_text').val();
        var post_link = $('#edit_post_link').val();
        var post_location = $('#edit_post_location').attr('data-location');
        var post_longitude = $('#edit_post_location').attr('data-lng');
        var post_latitude = $('#edit_post_location').attr('data-lat');
        
        if(post_text === '' && post_link === '' && post_location === ''){
            $('#edit_post_validate').show();
            return;
        }
        
        
        if (parseInt(channelid) == 0) {
            privacyValue = parseInt($(".privacyclass.active").attr('data-value'));
        } else {
            privacyValue = USER_PRIVACY_PUBLIC;
        }

        if ((privacyValue == USER_PRIVACY_SELECTED && privacyArray.length == 0)) {
            $('.error_valid').html(t('Invalid privacy data'));
            return;
        }

        $.post(
            ReturnLink('/ajax/post_edit.php'), 
            {privacyValue: privacyValue, privacyArray: privacyArray, post_id: $('.all_container').attr('data-id'), 
                post_text: post_text, locationPost: post_location, linkPost: post_link, 
                longitude: post_longitude, latitude: post_latitude, is_post: 1}, 
            function (data) {
                if (data.status === 'ok') {
                    console.log(data);
                    parent.window.closeFancyReload(0);
                }
                else{
                    TTAlert({
                            msg: data.msg,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                    });
                }
            }
        );
        
    });

    $(document).on('click', '#saveeventdata_edit', function () {

        var mycurrobj = 'right_container';

        if (verifyFormList(mycurrobj)) {
            var curob = $(this).closest('#right_container');
            if (parseInt(channelid) == 0) {
                privacyValue = parseInt($(".privacyclass.active").attr('data-value'));
            } else {
                privacyValue = USER_PRIVACY_PUBLIC;
            }

            if ((privacyValue == USER_PRIVACY_SELECTED && privacyArray.length == 0)) {
                $('.error_valid').html(t('Invalid privacy data'));
                return;
            }
            var cobthis = $(this);
            $.post(ReturnLink('/ajax/profile_media_save.php'), {privacyValue: privacyValue, privacyArray: privacyArray, vid: $('.all_container').attr('data-id'), title: getObjectData($("#edit_title")), cityname: $("#edit_cityname").val(), cityid: $("#edit_cityname").attr('data-id'), citynameaccent: $("#edit_citynameaccent").val(), is_public: $(".privacyclass.active").attr("data-value"), description: getObjectData($("#edit_description")), category: $("#edit_category").val(), placetakenat: getObjectData($("#edit_placetakenat")), keywords: getObjectData($("#edit_keywords")), country: $("#edit_country").val(), album: $("#edit_album").val(), location: ''}, function (data) {
                if (data != false) {
                    parent.window.closeFancyReload(is_album);
                }
            });
        }
    });
    $(document).on('click', '#photo_but', function () {
        if (!$(this).hasClass("active")) {
            $(this).addClass("active");
            $("#info_but").removeClass("active");
            $("#edit_info_part").hide();
            $("#edit_photo_part").show();
        }
    });
    $(document).on('click', '#info_but', function () {
        if (!$(this).hasClass("active")) {
            $(this).addClass("active");
            $("#photo_but").removeClass("active");
            $("#edit_info_part").show();
            $("#edit_photo_part").hide();
        }
    });
    $(document).on('click', "#okeventdata", function () {
        var curob = $(this).parent().parent();
        $('.error_valid_privacy').html('');
        privacyValue = parseInt($(".privacyclass.active").attr('data-value'));
        privacyArray = new Array();
        if (privacyValue == USER_PRIVACY_SELECTED) {
            curob.find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function () {
                var obj = $(this);
                if (obj.attr('id') == "friendsdata") {
                    privacyArray.push({friends: 1});
                } else if (obj.attr('id') == "friends_of_friends_data") {
                    privacyArray.push({friends_of_friends: 1});
                } else if (obj.attr('id') == "followersdata") {
                    privacyArray.push({followers: 1});
                } else if (parseInt(obj.attr('data-id')) != 0) {
                    privacyArray.push({id: obj.attr('data-id')});
                }
            });
        }

        if ((privacyValue == USER_PRIVACY_SELECTED && privacyArray.length > 0) || privacyValue != USER_PRIVACY_SELECTED) {
            $('.peoplecontainer_custom').addClass('displaynone');
            $('.info_pop_container').show();
        } else {
            $('.error_valid_privacy').html(t('Invalid privacy data'));
        }
    });
    $(document).on('click', ".privacyclass", function () {
        $('.privacyclass', $(this).parent().parent()).removeClass('active');
        $(this).addClass('active');
        var which = parseInt($(this).attr('data-value'));
        var $form_table = $('#right_container');
        $('.uploadinfomandatory span', $form_table).html('');

        switch (which) {
            case USER_PRIVACY_PRIVATE:
                initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));
                $('.peoplecontainer_custom').addClass('displaynone');
                $('.info_pop_container').show();
                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                break;
            case USER_PRIVACY_SELECTED:
                $(this).closest('.tableform').find('.peoplecontainer_custom').show();
                $('.peoplecontainer_custom').removeClass('displaynone');
                $('.info_pop_container').hide();
                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');
                break;
            default:
                initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));
                $('.peoplecontainer_custom').addClass('displaynone');
                $('.info_pop_container').show();

                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');
                break;
        }
    });
    $(document).on('click', ".peoplesdataclose", function () {
        var parents = $(this).parent();
        var parents_all = parents.parent().parent();
        parents.remove();
        if (parents.attr('data-id') != '') {
            SelectedUsersDelete(parents.attr('data-id'), parents_all.find('.addmore input'));
        }

        if (parents.attr('id') == 'friendsdata') {
            parents_all.find('.uploadinfocheckbox3').removeClass('active');
        } else if (parents.attr('id') == 'friends_of_friends_data') {
            parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
        } else if (parents.attr('id') == 'followersdata') {
            parents_all.find('.uploadinfocheckbox4').removeClass('active');
        }
    });
    $(document).on('click', ".uploadinfocheckbox", function () {
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
                    var friendstr = '<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">' + t("friends") + '</div><div class="peoplesdataclose"></div></div>';

                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(friendstr);
                    //$(this).parent().parent().find('#friendsdata').css("width", ($(this).parent().parent().find('#friendsdata .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">' + t("friends of friends") + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                    //$(this).parent().parent().find('#friends_of_friends_data').css("width", ($(this).parent().parent().find('#friends_of_friends_data .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">' + t("followers") + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                    //$(this).parent().parent().find('#followersdata').css("width", ($(this).parent().parent().find('#followersdata .peoplesdatainside').width() + 20) + "px");
                }
            }
            $(this).addClass('active');
        }
    });
    $(document).on('click', "#chk-constrain", function () {
        if ($("#chk-constrain").hasClass("active")) {
            $(this).removeClass("active");
            $(this).parent().attr('title', 'Activate Constraint');
        } else {
            $(this).addClass("active");
            $(this).parent().attr('title', 'Deactivate Constraint');
        }
    });


    var privacyselcted = parseInt($('#right_container').attr('data-value'));
    $('#privacyclass_user' + privacyselcted).click();
    if (privacyselcted == USER_PRIVACY_SELECTED) {
        $('#right_container').find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function () {
            var obj = $(this);
            if (obj.attr('id') == "friendsdata") {
                //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
            } else if (obj.attr('id') == "friends_of_friends_data") {
                //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
            } else if (obj.attr('id') == "followersdata") {
                //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
            } else if (parseInt(obj.attr('data-id')) != 0) {
                SelectedUsersAdd(obj.attr('data-id'));
                //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
            }
        });
    }

    if (parseInt(channelid) == 0) {
        privacyValue = parseInt($(".privacyclass.active").attr('data-value'));
    } else {
        privacyValue = USER_PRIVACY_PUBLIC;
        var $form_table = $('#right_container');
        $('.peoplecontainer_custom').addClass('displaynone');
        $('.info_pop_container').show();

        $('.uploadinfomandatory', $form_table).addClass('inactive');
        $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
        $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
        $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
        $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');

    }
    privacyArray = new Array();
    if (privacyValue == USER_PRIVACY_SELECTED) {
        $('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function () {
            var obj = $(this);
            if (obj.attr('id') == "friendsdata") {
                privacyArray.push({friends: 1});
            } else if (obj.attr('id') == "friends_of_friends_data") {
                privacyArray.push({friends_of_friends: 1});
            } else if (obj.attr('id') == "followersdata") {
                privacyArray.push({followers: 1});
            } else if (parseInt(obj.attr('data-id')) != 0) {
                privacyArray.push({id: obj.attr('data-id')});
            }
        });
    }
});
function verifyFormList(which) {
    var ok = true;
    var $currobjselected = $('#' + which).find('#edit_info_part');

    $('.uploadinfomandatory span', $currobjselected).html('');
    $('input,select,textarea', $currobjselected).removeClass('err').each(function () {
        var name = $(this).attr('name');
        var $parenttarget = $(this).parent();
        if ((name == 'location') || (name == 'location_name') || (name == 'placetakenat') || (name == 'cityname') || (name == 'status') || (name == 'vid') || $(this).hasClass('filevalue') || (name == 'filename') || (name == 'addmoretext') || (name == 'vpath')) {

        } else {
            if (($('.uploadinfomandatory' + name, $parenttarget).css('display') != 'none' && getObjectData($(this)) == '') || (name == "category" && !$('.uploadinfomandatorycategory', $parenttarget).hasClass('inactive') && $(this).val() == '0') || (name == "country" && !$('.uploadinfomandatorycountry', $parenttarget).hasClass('inactive') && $(this).val() == '0')) {
                $('.uploadinfomandatory' + name + ' span', $parenttarget).html(t('please fill this field correctly'));
                ok = false;
            }
        }
    });

    var $parenttarget = $('input[name=cityname]', $currobjselected).parent();

    if (getObjectData($('input[name=cityname]', $currobjselected)) == '' && !$('.uploadinfomandatorycitynameaccent', $parenttarget).hasClass('inactive') && $('input[name=cityname]', $currobjselected).length > 0) {
        $('.uploadinfomandatorycitynameaccent span', $parenttarget).html(t('please fill this field correctly'));
        ok = false;
//                        console.log(5);
    }
    if (!ok) {

        return false;
    } else {
        return true;
    }
}
function initResetSelectedUsers(obj) {
    obj.hide();
    resetSelectedUsers(obj.find('.addmore input'));
    obj.find('.uploadinfocheckbox').removeClass('active');
    obj.find('.addmore input').val('');
    obj.find('.addmore input').blur();
    obj.find('.peoplesdata').each(function () {
        var parents = $(this);
        parents.remove();
    });
}
// Toggle the enable shares and comments.
function enableSharesComments_log(entity_id, entity_type, new_status) {

    $.ajax({
        url: ReturnLink('/ajax/info_log_updatesharescomments.php'),
        data: {entity_id: entity_id, entity_type: entity_type, new_status: new_status, globchannelid: channelGlobalID()},
        type: 'post',
        success: function (data) {

        }
    });
}
function addAutoCompleteList() {
    var $citynameaccent = $("#edit_citynameaccent");
    $citynameaccent.autocomplete({
        appendTo: "#right_container",
        search: function (event, ui) {
            var $country = $('select[name=country]', $citynameaccent.parent()).removeClass('err');
            var cc = $('option:selected', $country).val();
            if (cc == 'ZZ') {
                $country.addClass('err');
                event.preventDefault();
            } else {
                $citynameaccent.autocomplete("option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc));
            }
        },
        select: function (event, ui) {
            $citynameaccent.val(ui.item.value);
            $('input[name=cityname]', $citynameaccent.parent()).val(ui.item.value);
            $('input[name=cityname]', $citynameaccent.parent()).attr('data-id', ui.item.id);
            event.preventDefault();
        }
    });
}
function updateReintialiseslider() {
    var nbThumb=0;
    var $content = $('#thumb-pic-container .imageitem').each(function () {
        if( $(this).css('display')!='none' ){
            nbThumb++;
        }
    });    
    var widthThumb = 89;
    var margin = 2;
    var widthcontainer = ((widthThumb + margin) * nbThumb);
    $("#thumb-pic-container").css("width", widthcontainer + "px");
}
function positionHScroller(percentege) {
    var $scroller = $('.video-thum-slider-bar');
    var $scrollbar = $('.video-thum-slider-wrap');
    var pp = $scrollbar.width() - $scroller.outerWidth(true);
    $scroller.css({'left': pp * percentege - 2});
}
function inBounds(pos, $element) {
    var el_pos = $element.offset();
    //console.log(el_pos);
    var max_x = el_pos.left + $element.width();
    var max_y = el_pos.top + $element.height();
    //console.log( "[" + pos.left + ":" + pos.top + "] [" + max_x + ":" +max_y + "] [" + el_pos.left + ":" + el_pos.top + "]" );
    if ((pos.pageX < max_x) && (pos.pageY < max_y) && (pos.pageX > el_pos.left) && (pos.pageY > el_pos.top))
        return true;
    else
        return false;
}
function inBounds2(pos, $element) {
    var el_pos = $element.offset();
    var max_x = el_pos.left + $element.width();
    var max_y = el_pos.top + $element.height();
    if ((pos.pageX < max_x) && (pos.pageX > el_pos.left))
        return true;
    else
        return false;
}
function editReintialiseslider($scroller, $scrollbar, $content, $content_holder) {
    /////////////////////////////////
    //scrollbar hammer
    var ev_old = 0;
    var dir_old = 0;
    var dragging_element = false;
    var dragging_element_2 = false;
    
    var hammer_scroller = Hammer($scroller.get(0), {
        drag_vertical: false,
        drag_horizontal: true,
        drag_min_distance: 5,
        prevent_default: true,
        css_hacks: false,
        transform: false,
        tap: false,
        tap_double: false,
        hold: false
    }).on("dragstart", function (ev) {
        if (inBounds(ev.gesture.center, $scrollbar)) {
            dragging_element = true;
            $content_holder.disableSelection();
        } else {
            dragging_element = false;
        }
    }).on("dragend", function (ev) {
        dragging_element = false;
        ev_old = 0;
        $content_holder.enableSelection();
    });
    var hammer_scroll_content = Hammer($content_holder.get(0), {
        drag_vertical: false,
        drag_horizontal: true,
        drag_min_distance: 5,
        prevent_default: true,
        css_hacks: false,
        transform: false,
        tap: false,
        tap_double: false,
        hold: false
    }).on("dragstart", function (ev) {
        if (inBounds(ev.gesture.center, $content)) {
            unbindToVideoThumbs();
            dragging_element_2 = true;
            $content_holder.disableSelection();
        } else {
            dragging_element_2 = false;
        }
    }).on("dragend", function (ev) {
        setTimeout(function () {
            bindToVideoThumbs()
        }, 300);
        dragging_element_2 = false;
        ev_old = 0;
        $content_holder.enableSelection();
    });
    if($('#edit_photo_part').length>0){
        var hammer_body = Hammer($('#edit_photo_part').get(0), {
            drag_vertical: false,
            drag_horizontal: true,
            drag_min_distance: 5,
            prevent_default: false,
            css_hacks: false,
            transform: false,
            tap: false,
            tap_double: false,
            hold: false
        }).on('drag', function (ev) {
            var gesture = ev.gesture;
            if (dragging_element && inBounds2(ev.gesture.center, $scrollbar)) {
                var diff;
                var cur = $scroller.css('left');
                cur = parseInt(cur.substring(0, cur.length - 2));
                if (isNaN(cur))
                    cur = 0;

                if (gesture.direction === Hammer.DIRECTION_LEFT) {
                    if (dir_old !== 1)
                        ev_old = 0;
                    dir_old = 1;
                    diff = gesture.distance - ev_old;
                    cur -= diff;
                    ev_old = gesture.distance;
                }
                if (gesture.direction === Hammer.DIRECTION_RIGHT) {
                    if (dir_old !== -1)
                        ev_old = 0;
                    dir_old = -1;
                    diff = gesture.distance - ev_old;
                    cur += diff;
                    ev_old = gesture.distance;
                }

                //horizontal
                var min = 0;
                var max = $scrollbar.outerWidth(true) - $scroller.outerWidth(true) - 2;

                if (cur < min)
                    cur = min;
                if (cur > max)
                    cur = max;

                var per = 0;
                per = cur / max;
                $scroller.css({'position': 'absolute', 'left': cur + 'px'});
                $content.css({'left': per * ($content_holder.width() - $content.width())});
            } else if (dragging_element_2 && inBounds2(ev.gesture.center, $content_holder)) {
                var diff;
                var cur = $content.css('left');
                cur = parseInt(cur.substring(0, cur.length - 2));
                if (isNaN(cur))
                    cur = 0;

                if (gesture.direction === Hammer.DIRECTION_LEFT) {
                    if (dir_old !== 1)
                        ev_old = 0;
                    dir_old = 1;
                    diff = gesture.distance - ev_old;
                    cur -= diff;
                    ev_old = gesture.distance;
                }
                if (gesture.direction === Hammer.DIRECTION_RIGHT) {
                    if (dir_old !== -1)
                        ev_old = 0;
                    dir_old = -1;
                    diff = gesture.distance - ev_old;
                    cur += diff;
                    ev_old = gesture.distance;
                }

                //horizontal
                var min = $content_holder.outerWidth(true) - $content.outerWidth(true) - 2;
                var max = 0;

                if (cur < min)
                    cur = min;
                if (cur > max)
                    cur = max;
                var per = cur / min;
                $scroller.css({position: 'absolute', left: per * ($scrollbar.outerWidth(true) - $scroller.outerWidth(true)) + 'px'});
                $content.css({'left': cur + 'px'});

            }
        });
    }else{
        var hammer_body = Hammer($('.video-thum-slider-bar').get(0), {
            drag_vertical: false,
            drag_horizontal: true,
            drag_min_distance: 5,
            prevent_default: false,
            css_hacks: false,
            transform: false,
            tap: false,
            tap_double: false,
            hold: false
        }).on('drag', function (ev) {
            var gesture = ev.gesture;
            if (dragging_element && inBounds2(ev.gesture.center, $scrollbar)) {
                var diff;
                var cur = $scroller.css('left');
                cur = parseInt(cur.substring(0, cur.length - 2));
                if (isNaN(cur))
                    cur = 0;

                if (gesture.direction === Hammer.DIRECTION_LEFT) {
                    if (dir_old !== 1)
                        ev_old = 0;
                    dir_old = 1;
                    diff = gesture.distance - ev_old;
                    cur -= diff;
                    ev_old = gesture.distance;
                }
                if (gesture.direction === Hammer.DIRECTION_RIGHT) {
                    if (dir_old !== -1)
                        ev_old = 0;
                    dir_old = -1;
                    diff = gesture.distance - ev_old;
                    cur += diff;
                    ev_old = gesture.distance;
                }

                //horizontal
                var min = 0;
                var max = $scrollbar.outerWidth(true) - $scroller.outerWidth(true) - 2;

                if (cur < min)
                    cur = min;
                if (cur > max)
                    cur = max;

                var per = 0;
                per = cur / max;
                $scroller.css({'position': 'absolute', 'left': cur + 'px'});
                $content.css({'left': per * ($content_holder.width() - $content.width())});
            } else if (dragging_element_2 && inBounds2(ev.gesture.center, $content_holder)) {
                var diff;
                var cur = $content.css('left');
                cur = parseInt(cur.substring(0, cur.length - 2));
                if (isNaN(cur))
                    cur = 0;

                if (gesture.direction === Hammer.DIRECTION_LEFT) {
                    if (dir_old !== 1)
                        ev_old = 0;
                    dir_old = 1;
                    diff = gesture.distance - ev_old;
                    cur -= diff;
                    ev_old = gesture.distance;
                }
                if (gesture.direction === Hammer.DIRECTION_RIGHT) {
                    if (dir_old !== -1)
                        ev_old = 0;
                    dir_old = -1;
                    diff = gesture.distance - ev_old;
                    cur += diff;
                    ev_old = gesture.distance;
                }

                //horizontal
                var min = $content_holder.outerWidth(true) - $content.outerWidth(true) - 2;
                var max = 0;

                if (cur < min)
                    cur = min;
                if (cur > max)
                    cur = max;
                var per = cur / min;
                $scroller.css({position: 'absolute', left: per * ($scrollbar.outerWidth(true) - $scroller.outerWidth(true)) + 'px'});
                $content.css({'left': cur + 'px'});

            }
        });
    }
    $scrollbar.click(function (e) {
        var pos_x = e.pageX - $(this).offset().left;
        var pos_y = e.pageY - $(this).offset().top;

        pos_x = pos_x - $scroller.outerWidth() / 2;
        var min = 0;
        var max = $scrollbar.width() - $scroller.outerWidth(true) - 2;

        if (pos_x < min)
            pos_x = min;
        if (pos_x > max)
            pos_x = max;

        var per = 0;
        per = pos_x / max;
        positionHScroller(per);
        $content.stop().animate({
            left: per * ($content_holder.width() - $content.outerWidth())
        });
    });
}