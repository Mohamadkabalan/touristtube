var pagename = '';
var jscrollpane_apiJ;
var fr_txt = "";
var to_txt = "";

var journals_page = 0;
// Saves a list of ids in the order set by the user.
var journal_items_order = '';
// Saves the privacy category.
var privacyValue = '';
// Saves the custom privacy list.
var privacyArray = new Array();
// An array to get the names from the ids - For resetting the custom-box.
var custom_privacy_names = new Array();
// Prepare the raw data for the yellow boxes inside the custom box.
var add_more_box = '<div class="addmore" style="margin: 0px 3px 3px 0;"><input id="addmoretext_custum_privacy_detailed" class="addmoretext_css addmoretext_custum_privacy_detailed ui-autocomplete-input" type="text" data-id="" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' + $.i18n._("add more") + '" value="' + $.i18n._("add more") + '" name="addmoretext" autocomplete="off"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span></div>';
var followers_box = '<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id="" style="width: 73px;"><div class="peoplesdatainside">' + t("followers") + '</div><div class="peoplesdataclose"></div></div>';
var friends_of_friends_box = '<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id="" style="width: 117px;"><div class="peoplesdatainside">' + t("friends of friends") + '</div><div class="peoplesdataclose"></div></div>';
var friends_box = '<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id="" style="width: 60px;"><div class="peoplesdatainside">' + t("friends") + '</div><div class="peoplesdataclose"></div></div>';
var user_box = '<div class="peoplesdata" data-email="" data-id="%user_id%" style="width: 120px;"><div class="peoplesdatainside">%username%</div><div class="peoplesdataclose"></div></div>';
var journal_selected = '';
var objclicked = '';


$(document).ready(function () {
    $(document).on('click', "#resetpagebut", function () {
        document.location.reload();
    });
    $(document).on('change', "select[name=country]", function () {
        var parents = $(this).closest('.editDiv');
        parents.find('#edit_city_text').val('');
        parents.find('#edit_city_text_cityname').val('');
        parents.find('#edit_city_text_cityname').attr('data-id','');
    });
    if ($('#fromtxt').length > 0)
        InitCalendar();
    if ($(".body_div").length > 0)
        initscrollPane();
    //initCommentData();
    if ($('.social_data_all').length > 0) {
        initSocialActions();
        if (parseInt(is_owner) == 1)
            addAutoComplete();
        hideScrollBar();
        currentpage_like = 0;
        $('.social_data_all').attr("data-page-like", 0);
        currentpage_comments = 0;
        $('.social_data_all').attr("data-page-comments", 0);
        currentpage_shares = 0;
        $('.social_data_all').attr("data-page-shares", 0);

        if ($("#report_button_log_viewer_journal").length > 0) {
            initReportFunctions($("#report_button_log_viewer_journal"));
        }
    }
    $(document).on('mouseover', '.privacy_icon_glob', function () {
        var diffx = $('#JournalMainDiv').offset().left + 255;
        var diffy = $('#JournalMainDiv').offset().top + 22;
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

    $(document).on('click', ".item_details, .item_img_container, .item_actions", function () {

        // Close any open expand page.
        $("#expand_div").hide();

        // Set the point selector.
        $(".itemList").removeClass("selected");
        $(this).parent().addClass("selected");
        journal_selected = $(this).closest('.itemList');
        // Set the colored border.
        $(".img_overlay_border").hide();
        $(this).parent().find('.img_overlay_border').show();

        // Scroll.
        var pos = $(this).parent().offset().top - $(this).parent().parent().offset().top;
        jscrollpane_apiJ.scrollToY(pos, true);

        // Show or hide the shares and comments sections.
        if ($(this).parent().attr('data-shares-comments') == 1) {
            $(".btn_comments").show();
            $(".btn_txt_comments").show();
            $(".btn_shares").show();
            $(".btn_txt_shares").show();
        } else {
            $(".btn_comments").hide();
            $(".btn_txt_comments").hide();
            $(".btn_shares").hide();
            $(".btn_txt_shares").hide();
        }

        $(".social_data_all").attr('data-id', $(this).parent().attr('data-id'));
        var journal_id = $(".social_data_all").attr('data-id');
        $("#edit_add_photos_link").attr('href', ReturnLink('/upload-journal/id/' + journal_id));

        initLikes($(".social_data_all"));
        initComments($(".social_data_all"));
        initShares($(".social_data_all"));
        //getJournalDetails( $(".social_data_all") );
        $('#description').removeClass('selected');
        //var src = $('#description').attr("src").replace("_stan.gif", ".gif");
        //$('#description').attr("src", src);
        $('#description').click();
    });

    $(document).on('mouseover', ".item_img_container", function () {
        $(this).find(".img_overlay").show();
    });
    $(document).on('mouseout', ".item_img_container", function () {
        $(this).find(".img_overlay").hide();
    });

    $("#add_journal_icon").mouseover(function () {
        $("#add_journal_help").show();
    });
    $("#add_journal_icon").mouseout(function () {
        $("#add_journal_help").hide();
    });

    $(".item_built_img").mouseover(function () {
        var src = $(this).attr('src');
        $(this).attr('src', src.substr(0, src.length - 9) + '_yellow.png');
    });
    $(".item_built_img").mouseout(function () {
        var src = $(this).attr('src');
        $(this).attr('src', src.substr(0, src.length - 11) + '_grey.png');
    });


    $("#privacy_down_arrow").click(function () {
        $("#privacy_text").toggle();
        $("#hide_text").toggle();
    });

    $(document).on('click', ".topArrow", function () {
        if ($('.top_buttons_container').css('display') != "none") {
            $('.top_buttons_container').hide();
        } else {
            $('.top_buttons_container').show();
        }
    });

    $(document).on('click', '.overdatabutenable', function () {
        var $this = $(this);
        var item_id = $(".social_data_all").attr('data-id');

        if (String("" + $this.attr('data-status')) == "1") {
            editJournalDetails($(".social_data_all"), 'sharescomments');
            $this.attr('data-status', '0');
            $this.find('.overdatabutntficon').addClass('inactive');

            $(".btn_comments").hide();
            $(".btn_txt_comments").hide();
            $(".btn_shares").hide();
            $(".btn_txt_shares").hide();

            // Update the shares and comments flag.
            $('#item_' + item_id).attr('data-shares-comments', 0);
        } else {
            editJournalDetails($(".social_data_all"), 'sharescomments');
            $this.attr('data-status', '1');
            $this.find('.overdatabutntficon').removeClass('inactive');

            $(".btn_comments").show();
            $(".btn_txt_comments").show();
            $(".btn_shares").show();
            $(".btn_txt_shares").show();

            // Update the shares and comments flag.
            $('#item_' + item_id).attr('data-shares-comments', 1);
        }
    });

    $(document).on('click', '#hide_button', function () {
        editJournalDetails($(".social_data_all"), 'hide');
    });

    // Variable to return the edit to its original form on cancel.
    backup_html = '';
    $(document).on('click', '#remove_button', function () {
        //$(this).parent().parent().find(".closeDiv").click();
        backup_html = $(".editDiv").html();
        $(".editDiv").html('');
        $(".editDiv").append('<div class="closeDiv"></div>');
        $(".editDiv").append('<div class="delete_message">'+t("are you sure you want to remove<br />permanently this journal?<br /><br />this action cannot be undone.")+'</div>');
        $(".editDiv").append('<div class="edit_buttons" style="margin-top:25px;"><div id="button_delete" class="edit_button">' + t("confirm") + '</div><div class="yellow_separator"></div><div id="button_cancel" class="edit_button">' + t("cancel") + '</div></div>');
        $(".editDiv").append('<div style="height:485px;"></div>');
    });

    $(document).on('click', '#button_delete', function () {
        editJournalDetails($(".social_data_all"), 'delete');
    });

    $(document).on('click', '#button_save', function () {
        var journal_id = $(".social_data_all").attr('data-id');
        resetValue($(this).parent().parent().find('#edit_title'));
        resetValue($(this).parent().parent().find('#edit_description'));
        resetValue($(this).parent().parent().find('#edit_location'));
        resetValue($(this).parent().parent().find('#edit_country'));
        resetValue($(this).parent().parent().find('#edit_city'));
        resetValue($(this).parent().parent().find('#edit_keywords'));

        // Save the journal details (edit).
        if (verifyForm('editDiv')) {
            editJournalDetails($(".social_data_all"), 'save');
        }
    });

    $(document).on('click', '#button_cancel', function () {
        $(this).parent().parent().find(".closeDiv").click();
        if (backup_html != '')
            $(".editDiv").html(backup_html);
    });

    $(document).on('click', '#journal_load_more', function () {
        initJournals();
    });

    $(document).on('click', "#headbutinfo", function () {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active');
            $('#headbutinfocontent').animate({'width': 440 + "px"}, 500);
        }
    });
    $(document).on('click', "#headbutinfocontentclose", function () {
        $('#headbutinfocontent').animate({'width': 0 + "px"}, 500);
        $('#headbutinfo').removeClass('active');
    });


    $(document).on('click', ".item_img_container", function () {
        initExpandPanel($(".social_data_all"));
    });

    $(document).on('click', "#expand_save_btn", function () {
        journal_items_order = '';
        // Run through the images saving their ids in the order set by the user.
        $('.images_container .img_box').each(function (index, element) {
            journal_items_order += $(this).attr('data-id') + ',';
        });
        // Remove the trailing comma.
        journal_items_order = journal_items_order.substr(0, (journal_items_order.length - 1));
        // Save the new order.
        editJournalDetails($(".social_data_all"), 'orderitems');
    });


    $(document).on('click', "#expand_cancel_btn,#expand_container", function () {
        $("#expand_div,#expand_container").hide();
    });

    // A turn-around function because fancybox does not allow $(this).
    var item_id = '';
    $(document).on('click', "#edit_btn", function () {
        item_id = $(this).parent().parent().attr('data-id');
        objclicked = $(this).closest('.img_box');
    });

    $(document).on('click', "#cancel_save_btn", function () {
        $(".fancybox-close").click();
    });

    $(document).on('click', "#image_save_btn", function () {
        resetValue($(this).parent().parent().find('#image_title'));
        resetValue($(this).parent().parent().find('#image_description'));
        resetValue($(this).parent().parent().find('#image_keywords'));
        resetValue($(this).parent().parent().find('#image_location'));
        editJournalItem(item_id, 'edit');
    });

    $(document).on('click', "#delete_btn", function () {
        item_id = $(this).parent().parent().attr('data-id');
        editJournalItem(item_id, 'delete');
    });

    $("#edit_btn").fancybox({
        padding: 0,
        margin: 0,
        beforeLoad: function () {
            $('#fancy_edit_item').html('');
            var src = '<div class="edit_image">';
            src += '<div class="left_side"><div class="edit_image_label">'+t('title')+'</div><input type="text" id="image_title" class="edit_image_textfield" value="' + t("title...") + '" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' + t("title...") + '" /><div class="edit_image_label">' + t("description") + '</div><textarea id="image_description" class="edit_image_textarea" value="' + t("description...") + '" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' + t("description...") + '"></textarea></div>';
            src += '<div class="right_side"><div class="edit_image_label">'+t("keywords")+' <span class="details_span">' + t("e.g. nature, restaurant, food)") + '(</span></div><input type="text" id="image_keywords" class="edit_image_textfield" value="'+t("keywords...")+'" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="'+t("keywords...")+'" /><div class="edit_image_label">'+t("place taken at")+'</div><input type="text" id="image_location" class="edit_image_textfield" value="' + t("place taken at...") + '" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="' + t("place taken at...") + '" /></div>';
            src += '<div class="set_icon_container"><div id="setIconBox" class="uploadinfocheckbox_upload"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt">' + t("set as icon for the journal") + '</div></div></div>';
            src += '<div class="save_icons_container"><div id="image_save_btn" class="save_btn"></div><div id="cancel_save_btn" class="cancel_btn"></div></div>';
            src += '</div>';
            $('#fancy_edit_item').html(src);


            $.ajax({
                url: ReturnLink('/ajax/ajax_journal_item_details.php'),
                data: {item_id: item_id},
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
                        return;
                    } else {
                        var name = ret.name;
                        var journal_id = ret.journal_id;
                        var item_desc = ret.item_desc;
                        var item_order = ret.item_order;
                        var default_pic = ret.default_pic;
                        var location_name = ret.location_name;
                        var keywords = ret.keywords;

                        $("#image_title").val(name);
                        $("#image_description").val(item_desc);
                        $("#image_keywords").val(keywords);
                        $("#image_location").val(location_name);

                        if (default_pic == 1) {
                            $("#setIconBox").addClass('active');
                        }

                        // Blur the items on first-load to allow the default text to show (if empty).
                        $("#image_title").blur();
                        $("#image_description").blur();
                        $("#image_keywords").blur();
                        $("#image_location").blur();

                    }
                }
            });



        },
        afterLoad: function () {

        }

    });

    /**
     * The privacy-box part.
     **/

    // Change the privacy icon.
    initResetSelectedUsers($('#addmoretext_privacy'));
    $("#privacy_select").change(function () {
        var selectval = parseInt($(this).val());

        if (selectval == 5) {
            $(this).parent().find('.privacy_picker').removeClass('displaynone');
        } else {
            initResetSelectedUsers($(this).parent().find('.addmore input'));
            $(this).parent().find('.uploadinfocheckbox_upload').removeClass('active');
            $(this).parent().find('.addmore input').val('');
            $(this).parent().find('.addmore input').blur();
            $(this).parent().find('.peoplesdata').each(function () {
                var parents = $(this);
                parents.remove();
            });
            $(this).parent().find('.privacy_picker').addClass('displaynone');
        }
        initResetIcon($(this).parent().find('.privacy_icon'));
        $(this).parent().find('.privacy_icon').addClass('privacy_icon' + selectval);
    });
    $("#privacy_select").change();
    $("#privacy_close_button").click(function () {
        $("#privacy_box").hide();
    });

    $("#privacy_text").click(function () {
        $("#privacy_box").show();
    });

    /**
     * End of the privacy-box part.
     **/

    $(document).on('click', "#report_journal_close, #report_journal_button_cancel", function () {
        $("#report_journal").hide();
    });

    $(document).on('click', "#report_journal_button_report", function () {
        $("#report_journal_report_message").show();
        $("#report_journal_button_ok").show();
    });

    $(document).on('click', "#report_journal_button_ok", function () {
        $("#report_journal").hide();
    });
    $(document).on('click', "#template1_icon", function () {
        $("#template1_icon").addClass("template1_icon_active");
        $("#template2_icon").removeClass("template2_icon_active");
    });
    $(document).on('click', "#template2_icon", function () {
        $("#template2_icon").addClass("template2_icon_active");
        $("#template1_icon").removeClass("template1_icon_active");
    });
    $(document).on('mouseover', "#template1_icon", function () {
        $("#template1_popup").show();
    });
    $(document).on('mouseout', "#template1_icon", function () {
        $("#template1_popup").hide();
    });
    $(document).on('mouseover', "#template2_icon", function () {
        $("#template2_popup").show();
    });
    $(document).on('mouseout', "#template2_icon", function () {
        $("#template2_popup").hide();
    });

    $(document).on('click', "#hide_text", function () {
        var item_id = $(".social_data_all").attr('data-id');
        var object_selected = $("#item_" + item_id);
        var obj_index = object_selected.index();
        if (obj_index == 0) {
            obj_index = 1;
        } else {
            obj_index = obj_index - 1;
        }

        var myNewObj = $(".body_item_list").eq(obj_index);
        object_selected.remove();
        myNewObj.find('.item_details').click();



        // Select the first item.
        if ($('.item_details'))
            $('.item_details').click();
        else
            $('.item_details').first().click();
    });


    $("#searchCalendarbut").click(function () {
        if ($('#fromtxt').html() != '' || $('#totxt').html() != '') {
            fr_txt = "" + $('#fromtxt').attr('data-cal');
            to_txt = "" + $('#totxt').attr('data-cal');
            currentpage = 0;
            $(".list_container").attr("data-skip", 0);
            $('.list_container').html('');
            initJournals(1);
        }
    });

    $(document).on('click', ".uploadinfocheckbox_upload", function () {
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


    $(document).on('click', ".privacyclass", function () {
        // Save the privacy category.
        privacyValue = $(".privacy_menu").find('.privacyclass.active').attr('data-value');

        $('.privacyclass', $(this).parent().parent()).removeClass('active');
        $(this).addClass('active');
        var which = parseInt($(this).attr('data-value'));
        var $form_table = $(this).parent().parent().parent();
        $('.uploadinfomandatory span', $form_table).html('');

        switch (which) {
            case USER_PRIVACY_PRIVATE:
                initResetSelectedUsers($(this).closest('.editDiv').find('.peoplecontainer_custom'));
                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                break;
            case USER_PRIVACY_SELECTED:
                $('.peoplecontainer_custom').show();
                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');
                // Save the custom privacy.
                saveCustomPrivacy();

                // Resets the users for the autocomplete field in custom privacy.
                resetSelectedUsers($('#addmoretext_custum_privacy_detailed'));
                // Fill the autocomplete "don't show" list with the users' ids.
                $('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function () {
                    if ($(this).attr('data-id') != '')
                        SelectedUsersAdd($(this).attr('data-id'), $('#addmoretext_custum_privacy_detailed'));
                });

                break;
            default:
                initResetSelectedUsers($(this).closest('.editDiv').find('.peoplecontainer_custom'));

                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');
                break;
        }
    });

    $(document).on('click', "#cancel_custom_privacy", function () {
        $('.peoplecontainer_custom').hide();
        resetCustomPrivacy();
    });

    $(document).on('click', "#okeventdata", function () {
        $('.peoplecontainer_custom').hide();
    });

    $(document).on('mouseover', "#item_details", function () {
        $(this).find("#journal_hide_button").show();
    });

    $(document).on('mouseout', "#item_details", function () {
        $(this).find("#journal_hide_button").hide();
    });

    $(document).on('click', "#journal_hide_button", function () {
        $(this).parent().parent().hide();
    });



    // Select the first item.
    $('.item_details').first().click();

    //$('#description').click();
});

function hideScrollBar()
{
    if ($("#body_div .itemList").size() <= 8)
    {
        $("#body_div .jspVerticalBar").hide();
    }
}
function showScrollBar() {
    if ($("#body_div .itemList").size() <= 8)
    {
        $("#body_div .jspVerticalBar").show();
    }
}


// Fill the privacy for the "custom" privacy setting.
function saveCustomPrivacy() {
    // Reset the custom privacy array.
    privacyArray = new Array();
    // Fill the custom privacy array.
    if (privacyValue == USER_PRIVACY_SELECTED) {
        $(".privacy_menu").find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function () {
            var obj = $(this);
            if (obj.attr('id') == "friendsdata") {
                privacyArray.push({friends: 1});
            } else if (obj.attr('id') == "friends_of_friends_data") {
                privacyArray.push({friends_of_friends: 1});
            } else if (obj.attr('id') == "followersdata") {
                privacyArray.push({followers: 1});
            } else if (parseInt(obj.attr('data-id')) != 0) {
                privacyArray.push({id: obj.attr('data-id')});
                // Save the name in case the user resets the custom box (cancels changes).
                custom_privacy_names[ obj.attr('data-id') ] = $(this).find(".peoplesdatainside").html();
            }
        });
    }
}

function resetCustomPrivacy() {
    // Reset the custom box choices.
    $(".emailcontainer").html(add_more_box);
    addmoreusersautocomplete_custom_journal($('#addmoretext_custum_privacy_detailed'));
    $(".uploadinfocheckbox4").removeClass('active');
    $(".uploadinfocheckbox_friends_of_friends").removeClass('active');
    $(".uploadinfocheckbox3").removeClass('active');

    // Loop through the custom items.
    for (i = 0; i < privacyArray.length; i++) {
        // Case of "followers".
        if (privacyArray[i]['followers'] && privacyArray[i]['followers'] == 1) {
            $(".uploadinfocheckbox4").addClass('active');
            $(".emailcontainer").append(followers_box);
        }
        // Case of "friends_of_friends".
        else if (privacyArray[i]['friends_of_friends'] && privacyArray[i]['friends_of_friends'] == 1) {
            $(".uploadinfocheckbox_friends_of_friends").addClass('active');
            $(".emailcontainer").append(friends_of_friends_box);
        }
        // Case of "friends".
        else if (privacyArray[i]['friends'] && privacyArray[i]['friends'] == 1) {
            $(".uploadinfocheckbox3").addClass('active');
            $(".emailcontainer").append(friends_box);
        }
        // Case of individual names.
        else if (privacyArray[i]['id']) {
            var tmp_box_item = user_box.replace('%user_id%', privacyArray[i]['id']);
            var tmp_box_item = tmp_box_item.replace('%username%', custom_privacy_names[ privacyArray[i]['id'] ]);

            $(".emailcontainer").append(tmp_box_item);
        }

    }
}

function InitCalendar() {
    Calendar.setup({
        inputField: "fromtxt",
                noScroll  	 : true,
        trigger: "frombutcontainer",
        align: "B",
        onSelect: function () {
            var date = Calendar.intToDate(this.selection.get());
            TO_CAL.args.min = date;
            TO_CAL.redraw();
            $('#fromtxt').attr('data-cal', Calendar.printDate(date, "%Y-%m-%d"));

            addCalToEvent(this);
            this.hide();
        },
        dateFormat: "%d / %m / %Y"
    });
    TO_CAL = Calendar.setup({
        inputField: "totxt",
                noScroll  	 : true,
        trigger: "tobutcontainer",
        align: "B",
        onSelect: function () {
            var date = Calendar.intToDate(this.selection.get());
            $('#totxt').attr('data-cal', Calendar.printDate(date, "%Y-%m-%d"));

            addCalToEvent(this);
            this.hide();
        },
        dateFormat: "%d / %m / %Y"
    });
}

function addCalToEvent(cals) {
    if (new Date($('#fromtxt').attr('data-cal')) > new Date($('#totxt').attr('data-cal'))) {
        $('#totxt').attr('data-cal', $('#fromtxt').attr('data-cal'));
        $('#totxt').val($('#fromtxt').val());
    }
}


// Function to reset the value if both the data-value and the value are the same (the default text is not changed).
function resetValue(obj) {
    if (obj.attr('data-value') == obj.val())
        obj.val('');
}


function initResetIcon(obj) {
    obj.removeClass('privacy_icon1');
    obj.removeClass('privacy_icon2');
    obj.removeClass('privacy_icon3');
    obj.removeClass('privacy_icon4');
    obj.removeClass('privacy_icon5');
}

function initResetSelectedUsers(obj) {
    resetSelectedUsers(obj);
    resetSelectedChannels(obj);
}




function initscrollPane() {
    $(".body_div").jScrollPane();
    jscrollpane_apiJ = $(".body_div").data('jsp');
}

// Gets the details of a journal.
function getJournalDetails(obj) {
    var journal_id = obj.attr('data-id');
    $.ajax({
        url: ReturnLink('/ajax/ajax_journal_details.php'),
        data: {journal_id: journal_id},
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
                var journal_name = ret.journal_name;
                var journal_desc = ret.journal_desc;
                var journal_desc_init = ret.journal_desc_init;
                var formatted_date = ret.formatted_date;
                var location_name = ret.location_name;
                var is_visible = ret.is_visible;
                var country = ret.country;
                var city = ret.city;
                var city_hidden_value = ret.city_hidden_value;
                var city_hidden_valueid = ret.city_hidden_valueid;
                var keywords = ret.keywords;
                var enable_share_comment = ret.enable_share_comment;
                var privacy_kind_type = ret.privacy_kind_type;
                var privacy_kind_icon = privacy_kind_type;
                try {
                    var privacy_kind_type_arr = privacy_kind_type.split(","); // An array of the options in case of "custom".
                    if (privacy_kind_type_arr.length > 1)
                        privacy_kind_icon = USER_PRIVACY_SELECTED;
                } catch (e) {
                    var privacy_kind_type_arr = privacy_kind_type;
                }
                var privacy_users = ret.privacy_users;
                var privacy_users_arr = ret.custom_privacy_users;
                // If the privacy kind includes more than 1 class, make it 'custom'.
                //			if(privacy_kind_type.indexOf(',') != -1)
                //				privacy_kind_type = 4;

                // Fill the description fields.
                $("#desc_timestamp").html(t("uploaded ") + formatted_date);
                $("#desc_journal_name").html(journal_name);
                $("#desc_journal_location").html(location_name);
                $(".description_journal").html('<div class="scrollpane_description_journal" style="max-height:405px; float: left; width:255px;"><div class="albumdesc albumdesc_journal">' + journal_desc + '</div></div>');

                var privacy_iconDiv = $(".privacy_iconDiv");
                privacy_iconDiv.removeClass('privacy_icon0');
                privacy_iconDiv.removeClass('privacy_icon1'); // Remove all classes.
                privacy_iconDiv.removeClass('privacy_icon2');
                privacy_iconDiv.removeClass('privacy_icon3');
                privacy_iconDiv.removeClass('privacy_icon4');
                privacy_iconDiv.removeClass('privacy_icon5');
                privacy_iconDiv.attr('data-title', ret.privacy_kind_str);
                privacy_iconDiv.addClass('privacy_icon' + privacy_kind_icon); // Add the specific privacy class.
                // End description fields.


                // Fill the edit fields.
                $("#edit_title").attr("value", '');
                $("#edit_description").attr("value", '');
                $("#edit_location").attr("value", '');

                $("#edit_title").attr("value", journal_name);
                $("#edit_description").attr("value", journal_desc_init);
                $("#edit_location").attr("value", location_name);
                $("#edit_country").attr("value", country);
                $("#edit_city_text").attr("value", city);
                $("#edit_city_text_cityname").attr("value", city_hidden_value);
                $("#edit_city_text_cityname").attr("data-id", city_hidden_valueid);
                $("#edit_keywords").attr("value", keywords);

                $("#edit_title").blur();
                $("#edit_description").blur();
                $("#edit_location").blur();

                // The privacy details.
                // Reset the privacy boxes selection.
                $("#privacyclass_user0").removeClass('active');
                $("#privacyclass_user1").removeClass('active');
                $("#privacyclass_user2").removeClass('active');
                $("#privacyclass_user3").removeClass('active');
                $("#privacyclass_user4").removeClass('active');
                $("#privacyclass_user5").removeClass('active');


                // Reset the custom box choices.
                if (parseInt(is_owner) == 1) {
                    $(".emailcontainer").html(add_more_box);
                    addmoreusersautocomplete_custom_journal($('#addmoretext_custum_privacy_detailed'));
                    $(".uploadinfocheckbox4").removeClass('active');
                    $(".uploadinfocheckbox_friends_of_friends").removeClass('active');
                    $(".uploadinfocheckbox3").removeClass('active');
                }

                // Custom privacy.
                if (parseInt(is_owner) == 1 && (privacy_kind_type.length > 1 || privacy_users.length > 0)) {
                    // Select the "custom" icon.
                    $("#privacyclass_user4").addClass('active');
                    
                    // Reset the custom box adding the "add more" box.
                    $(".emailcontainer").html(add_more_box);
                    addmoreusersautocomplete_custom_journal($('#addmoretext_custum_privacy_detailed'));

                    // Select the predefined categories.
                    if (privacy_kind_type_arr.indexOf('5') != -1) {
                        $(".uploadinfocheckbox4").addClass('active');
                        $(".emailcontainer").append(followers_box);
                    }
                    if (privacy_kind_type_arr.indexOf('3') != -1) {
                        $(".uploadinfocheckbox_friends_of_friends").addClass('active');
                        $(".emailcontainer").append(friends_of_friends_box);
                    }
                    if (privacy_kind_type_arr.indexOf('1') != -1) {
                        $(".uploadinfocheckbox3").addClass('active');
                        $(".emailcontainer").append(friends_box);
                    }

                    // Show the users entered manually in the custom box.
                    if (privacy_users != '' && privacy_users != 'undefined') {
                        for (i = 0; i < privacy_users_arr.length; i++) {
                            var tmp_box_item = user_box.replace('%user_id%', privacy_users_arr[i]['id']);
                            var tmp_box_item = tmp_box_item.replace('%username%', privacy_users_arr[i]['FullName']);

                            $(".emailcontainer").append(tmp_box_item);

                            // Save the names in case the user cancels the changes they made to the custom box.
                            custom_privacy_names[ privacy_users_arr[i]['id'] ] = privacy_users_arr[i]['FullName'];
                        }
                    }

                }
                // Other privacies.
                else {
                    $("#privacyclass_user" + privacy_kind_type).addClass('active');
                }


                if (is_visible == 1)
                    $("#hide_button").html(t("hide"));
                else
                    $("#hide_button").html(t("unhide"));

                if (enable_share_comment == 1) {
                    $(".overdatabutenable").attr('data-status', '1');
                    $('.overdatabutntficon').removeClass('inactive');
                } else {
                    $(".overdatabutenable").attr('data-status', '0');
                    $('.overdatabutntficon').addClass('inactive');
                }

                if ($(".albumdesc_journal").height() > 405) {
                    initscrollPaneSocial($(".scrollpane_description_journal"), false);
                }
                    $('.privacyclass.active').click();
                // End edit fields.
            }
        }
    });
}

// Edits the details of a journal.
function editJournalDetails(obj, command) {
    var journal_id = obj.attr('data-id');

    // Reset all the variables.
    var shares_and_comments = '';
    var hide = '';
    var edit_title = '';
    var edit_location = '';
    var edit_description = '';
    var edit_country = '';
    var edit_city = '';
    var edit_cityid = '';
    var edit_keywords = '';
    var edit_privacy = '';
    var journalTemplate = '';
    var journalPdf = '0';
    var journalFlash3d = '0';
    custom_privacy_kinds = ''; // The categories in a custom privacy (friends, friends of friends...).
    custom_privacy_users = ''; // The users' ids in a custom privacy.

    // Fill the variables according to the command given.
    if (command == 'sharescomments') {
        shares_and_comments = $(".overdatabutenable").attr("data-status");
    }
    else if (command == 'hide') {
        if ($("#hide_button").html() == 'hide')
            hide = 0;
        else
            hide = 1;
    }
    else if (command == 'save') {
        edit_title = $("#edit_title").attr("value");
        edit_location = $("#edit_location").attr("value");
        edit_description = $("#edit_description").attr("value");
        edit_country = $("#edit_country").attr("value");
        edit_city = $("#edit_city_text_cityname").attr("value");
        edit_cityid = $("#edit_city_text_cityname").attr("data-id");
        edit_keywords = $("#edit_keywords").attr("value");
        edit_privacy = $(".privacy_menu").find('.privacyclass.active').attr('data-value'); // The privacy value.

        // ** Custom privacy.
        // Run through the selected items in a custom box.
        if (edit_privacy == 4) {
            $('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function () {
                // Case of an individual id.
                if ($(this).attr('data-id') != '') {
                    custom_privacy_users += $(this).attr('data-id') + ',';
                }
                // Followers (5).
                else if ($(this).attr('id') == 'followersdata') {
                    custom_privacy_kinds += '5,';
                }
                // Friends of friends (3).
                else if ($(this).attr('id') == 'friends_of_friends_data') {
                    custom_privacy_kinds += '3,';
                }
                // Friends (1).
                else if ($(this).attr('id') == 'friendsdata') {
                    custom_privacy_kinds += '1,';
                }
            });
            // Trim the trailing commas.
            if (custom_privacy_kinds.indexOf(',') != -1)
                custom_privacy_kinds = custom_privacy_kinds.substr(0, custom_privacy_kinds.length - 1);
            if (custom_privacy_users.indexOf(',') != -1)
                custom_privacy_users = custom_privacy_users.substr(0, custom_privacy_users.length - 1);

            // If the user picked "custom" but only filled one category and no users, change their selection to that category alone.
            if (custom_privacy_kinds != '' && custom_privacy_kinds.indexOf(',') == -1 && custom_privacy_users == '') {
                edit_privacy = custom_privacy_kinds;
                custom_privacy_kinds = '';
            }
        }
        // ** End of the custom privacy.


    }
    else if (command == 'delete') {
    }
    else if (command == 'orderitems') {
        if ($("#template2_icon").hasClass("template2_icon_active")) {
            journalTemplate = '1';
        } else {
            journalTemplate = '0';
        }
        if ($("#savepdf").hasClass("active")) {
            journalPdf = '1';
        }
        if ($("#publish3d").hasClass("active")) {
            journalFlash3d = '1';
        }
    }
    if (command != '') {
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/ajax_journal_edit.php'),
            data: {journal_id: journal_id, command: command, shares_and_comments: shares_and_comments, hide: hide, edit_title: edit_title, edit_location: edit_location, edit_description: edit_description, journal_items_order: journal_items_order, edit_country: edit_country,edit_cityid:edit_cityid, edit_city: edit_city, edit_keywords: edit_keywords, edit_privacy: edit_privacy, cpk: custom_privacy_kinds, cpu: custom_privacy_users, journalTemplate: journalTemplate, journalPdf: journalPdf, journalFlash3d: journalFlash3d},
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
                    $('.upload-overlay-loading-fix').hide();
                    TTAlert({
                        msg: ret.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                } else {
                    $('.upload-overlay-loading-fix').hide();
                    // On delete, reload the page.
                    if (command == 'delete' || command == 'orderitems'){
                        location.reload();
                    // On re-order, close the expand window.
                    }else if (command == 'orderitems'){
                        $("#expand_cancel_btn").click();
                    }
                    // Refresh the details.
                    getJournalDetails($(".social_data_all"));
                }
            }
        });
    }

}

function editJournalItem(item_id, command) {
//var journal_id = obj.attr('data-id');

    // Reset all the variables.
    var name = '';
    var item_desc = '';
    var keywords = '';
    var location_name = '';
    var is_icon = '';

    // Fill the variables according to the command given.
    if (command == 'edit') {
        name = $("#image_title").val();
        item_desc = $("#image_description").val();
        keywords = $("#image_keywords").val();
        location_name = $("#image_location").val();
        // Setup the "set as icon journal" variable.
        if ($("#setIconBox").hasClass("active")) {
            is_icon = 1;
        } else {
            is_icon = 0;
        }
    }
    else if (command == 'delete') {

    }
    // On delete call the same function as edit but with an alert box first.
    if (command == 'delete') {
        TTAlert({
            msg: t('confirm to remove selected photo from journal'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function (data) {
                if (data) {
                    $('.upload-overlay-loading-fix').show();
                    $.ajax({
                        url: ReturnLink('/ajax/ajax_journal_item_edit.php'),
                        data: {command: command, item_id: item_id, name: name, item_desc: item_desc, keywords: keywords, location_name: location_name, is_icon: is_icon},
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
                                $('.upload-overlay-loading-fix').hide();
                                TTAlert({
                                    msg: ret.error,
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                                });
                                return;
                            } else {
                                // Reload the expanded list.
                                initExpandPanel($(".social_data_all"));
                                $('.upload-overlay-loading-fix').hide();

                            }
                        }
                    });
                }// end if.
            }
        });

    } else if (command != '') {
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/ajax_journal_item_edit.php'),
            data: {command: command, item_id: item_id, name: name, item_desc: item_desc, keywords: keywords, location_name: location_name, is_icon: is_icon},
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
                    $('.upload-overlay-loading-fix').hide();
                    TTAlert({
                        msg: ret.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                } else {
                    $('.upload-overlay-loading-fix').hide();

                    if (command == 'edit') {
                        if (objclicked != '' && is_icon == 1 && journal_selected != '') {
                            var src_str = objclicked.find('.img').attr('src');
                            journal_selected.find('.item_image').attr('src', src_str);
                        }
                        $(".fancybox-close").click();
                    }

                    initExpandPanel($(".social_data_all"));

                }
            }
        });
    }
}

function initJournals(reload_results) {
    // Make this variable optional, only for when a force-reload is required.
    if (!reload_results)
        reload_results = 0;

    // If a force-reload is required, reset the paging.
    if (reload_results == 1)
        journals_page = 0;
    else
        journals_page++;
    var uid = $("#hdata").attr("data-uid");

    $.ajax({
        url: ReturnLink('/ajax/ajax_journal_page.php'),
        data: {page: journals_page, uid: uid, from_date: fr_txt, to_date: to_txt},
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
            } else {
                var myData = ret.data;
                var data_count = ret.count;

                // If a force-reload is required, empty the results page first.
                if (reload_results == 1) {
                    $("#body_data").html('');
                }
                if (reload_results == 1 && data_count > 0)
                    $("#body_data").html('<div class="newsverticalline"></div>');

                $("#body_data").append(myData);
                initscrollPane();
                hideScrollBar();

                // Toggle the visibility of the "load more" button.
                if (data_count - journals_page * 8 > 8) {
                    $(".buttonmorecontainer").show();
                } else {
                    $(".buttonmorecontainer").hide();
                }

                // Set the counter data.
                $(".header_journals_count").html('(' + data_count + ')');

                // If a force-reload is required, Select the first item.
                if (reload_results == 1)
                    $('.item_details').first().click();
                if (data_count > 0) {
                    $(".left_panel").show();
                } else {
                    $(".left_panel").hide();
                }
            }
        }
    });

}

function initExpandPanel(obj) {
    var journal_id = obj.attr('data-id');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_journal_expand.php'),
        data: {journal_id: journal_id},
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
                $('.upload-overlay-loading-fix').hide();
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                $('.upload-overlay-loading-fix').hide();
                var myData = ret.data;
                var data_count = ret.count;

                $("#images_container").html('');
                $("#images_container").html(myData);
                $("#expand_div,#expand_container").show();
                $(".images_container").sortable({cursor: "move"});

                $("#edit_add_photo_link").attr('href', ReturnLink('/upload-journal/id/' + journal_id))


                $(".expand_div .body").jScrollPane();
            }
        }
    });
}

var _AutocompleteCities = null;
/**
 * resets the number of cities
 */
function AutocompleteCitiesReset() {
    _AutocompleteCities = new Array();
}
/**
 * adds a city to the autocomplete records
 * @param {String} CityName
 */
function AutocompleteCitiesAdd(CityNameVal) {
    _AutocompleteCities.push(CityNameVal);
}
/**
 * checks if the city exists in the autocomplete array
 * @param {String} CityName
 * @returns {String}
 */
function AutocompleteCitiesExists(CityName) {
    if (_AutocompleteCities === null)
        return null;
    var CleanCity = removeDiacritics(CityName);
    for (var i = 0; i < _AutocompleteCities.length; i++) {

        if (CleanCity.toLowerCase() === _AutocompleteCities[i].value.toLowerCase())
            return _AutocompleteCities[i];
    }
    return null;
}
function addAutoComplete() {
    var $citynameaccent = $("#edit_city_text");

    $citynameaccent.autocomplete({
        appendTo: "#MiddleInsideNormal",
        search: function (event, ui) {
            var $country = $('select[name=country]', $citynameaccent.parent()).removeClass('err');
            var cc = $('option:selected', $country).val();
            if (cc == 'ZZ') {
                $country.addClass('err');
                event.preventDefault();
            } else {
                $citynameaccent.autocomplete("option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc));
            }
            AutocompleteCitiesReset();
        },
        select: function (event, ui) {
            //dont show the city accent. only show the regular city.
            $citynameaccent.val(ucwords(ui.item.value));
            $('input[name=cityname]', $citynameaccent.parent()).val(ui.item.value);
            $('input[name=cityname]', $citynameaccent.parent()).attr('data-id',ui.item.id);
            event.preventDefault();
        },
        change: function (event, ui) {
            if (!ui.item) {
                // The item selected from the menu, if any. Otherwise the property is null
                //so clear the item for force selection
                var getCity = AutocompleteCitiesExists($citynameaccent.val());
                if (getCity === null) {
                    $citynameaccent.val('');
                    return;
                }
                //dont show the city accent. only show the regular city.
                $citynameaccent.val(ucwords(getCity.value));
                $('input[name=cityname]', $citynameaccent.parent()).val(getCity.value);
            }

        }
    }).keydown(function (event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        //the value in the input was changed so the hidden cityaccent is no longer valid
        if (AutocompleteCitiesExists($citynameaccent.val()) === null) {
            $('input[name=cityname]', $citynameaccent.parent()).val('');
            $('input[name=cityname]', $citynameaccent.parent()).attr('data-id');
        }
        //if enter or tab try to accept the value
        if (code === 13 || code === 9) { //Enter keycode or tab
            if (AutocompleteCitiesExists($citynameaccent.val()) === null) {
                event.preventDefault();
                return;
            }
            if (code === 13) {
                $(this).blur();
            }
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        AutocompleteCitiesAdd(item);
        //label has accented city name
        //value has the city name
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + ucwords(item.value) + "</a>")
                .appendTo(ul);
    }
}
function verifyForm(which) {

    var ok = true;
    var $currobjselected = $("#" + which);
    $('.uploadinfomandatory span', $currobjselected).html('');
    $('input,select,textarea', $currobjselected).removeClass('err').each(function () {
        var name = $(this).attr('name');
        var $parenttarget = $(this).parent();
        if ((name == 'location') || (name == 'location_name') || (name == 'cityname') || (name == 'status') || (name == 'vid') || $(this).hasClass('filevalue') || (name == 'filename') || (name == 'addmoretext') || (name == 'vpath')) {

        } else {
            if (($('.uploadinfomandatory' + name, $parenttarget).css('display') != 'none' && getObjectData($(this)) == '') || (name == "country" && !$('.uploadinfomandatorycountry', $parenttarget).hasClass('inactive') && $(this).val() == '0' && $('.uploadinfomandatorycountry', $parenttarget).length > 0)) {
                $('.uploadinfomandatory' + name + ' span', $parenttarget).html(t('please fill this field correctly'));
                ok = false;
            }
        }
    });

    var $parenttarget = $('input[name=cityname]', $currobjselected).parent();

    if (getObjectData($('input[name=cityname]', $currobjselected)) == '' && !$('.uploadinfomandatorycitynameaccent', $parenttarget).hasClass('inactive') && $('input[name=cityname]', $currobjselected).length > 0) {
        $('.uploadinfomandatorycitynameaccent span', $parenttarget).html(t('please fill this field correctly'));
        ok = false;
    }


    if (!ok) {
        TTAlert({
            msg: t('Please input mandatory fields'),
            type: 'alert',
            btn1: t('ok'),
            btn2: '',
            btn2Callback: null
        });
        return false;
    } else {
        return true;
    }
}