var searchdescriptiontext = "for better visibility and online search, please fill here";
var albumclicked = "";
$(document).ready(function() {
    /*$('#container').on('click','div.EditAction',function(){
     var id = $(this).attr('rel');
     MediaEdit(id);
     })*/
    $('#container').on('click', 'div.DeleteAction', function() {
        var id = $(this).attr('rel');
        var currDelMedia = t('are you sure you want to remove permanently this media?<br/>this action cannot be undone.');
        if (pagename == "videos") {
            currDelMedia = 'are you sure you want to remove permanently this video?<br/>this action cannot be undone.';
        } else if (pagename == "photos") {
            currDelMedia = 'are you sure you want to remove permanently this photo?<br/>this action cannot be undone.';
        }
        var $parent = $(this).closest('.element');
        TTAlert({
            msg: t(currDelMedia),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data) {
                if (data) {
                    MediaDelete(id,$parent);
                }
            }
        });
    }).on('click', 'div.DeleteFavAction', function() {
        var id = $(this).attr('rel');
        TTAlert({
            msg: t('Really Delete Favorite?'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data) {
                if (data) {
                    FavoriteDelete(id);
                }
            }
        });
    }).on('click', 'div.DeleteFavCamAction', function() {
        var id = $(this).attr('rel');
        TTAlert({
            msg: t('Really Delete Favorite?'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data) {
                if (data) {
                    FavoriteCamDelete(id);
                }
            }
        });
    }).on('click', 'div.DeleteCatalogAction', function() {
        var id = $(this).attr('rel');
        var $parent = $(this).closest('.element_album');
        TTAlert({
            msg: t('are you sure you want to remove permanently this album?<br/>this action cannot be undone.'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data) {
                if (data) {
                    CatalogDelete(id,$parent);
                }
            }
        });

    })

    refreshEditMedia();
    refreshEditAlbums();
    $(document).on('click', ".UploadMediaLeftTF .privacyclass", function() {
        $('.privacyclass', $(this).parent().parent()).removeClass('active');
        $(this).addClass('active');
        var which = parseInt($(this).attr('data-value'));
        var $form_table = $(this).parent().parent().parent();
        $('.uploadinfomandatory span', $form_table).html('');

        switch (which) {
            case USER_PRIVACY_PRIVATE:
                initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));
                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                break;
            case USER_PRIVACY_SELECTED:
                $(this).closest('.tableform').find('.peoplecontainer_custom').show();
                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');
                break;
            default:
                initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));

                $('.uploadinfomandatory', $form_table).addClass('inactive');
                $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
                $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');
                break;
        }
    });
    $(document).on('click', "#uploadlistpopuppublishalbum #loglistalbumelected .ClearNewAlbumButton", function() {
        $('.fancybox-close').click();
    });
    $(document).on('click', '#uploadlistpopuppublishalbum .UploadMediaRightTF #loglistalbumelectedsave', function() {
        var mycurrobj = 'uploadlistpopuppublishalbum';
        if (verifyFormList(mycurrobj)) {
            //$('.upload-overlay-loading-fix').show();
            var curob = $(this).closest('.tableform');
            var privacyValue = curob.find('.privacyclass.active').attr('data-value');
            var privacyArray = new Array();

            if (privacyValue == USER_PRIVACY_SELECTED) {
                curob.find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function() {
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
            if ((privacyValue == USER_PRIVACY_SELECTED && privacyArray.length == 0)) {
                curob.find('.error_valid').html(t('Invalid privacy data'));
                return;
            }
            var cobthis = $(this);
            $.post(ReturnLink('/ajax/ajax_updatealbumupload.php'), {privacyValue: privacyValue, privacyArray: privacyArray, catalog_id: $('#uploadlistpopuppublishalbum').attr('data-id'), title: getObjectData($("#" + mycurrobj + " input[name=title]")), cityname: $("#" + mycurrobj + " input[name=cityname]").val(),cityid: $("#" + mycurrobj + " input[name=cityname]").attr('data-id'), citynameaccent: $("#" + mycurrobj + " input[name=citynameaccent]").val(), is_public: $("#" + mycurrobj + " div.privacyclass.active").attr("data-value"), description: getObjectData($("#" + mycurrobj + " textarea[name=description]")), category: $("#" + mycurrobj + " select[name=category] option:selected").val(), placetakenat: getObjectData($("#" + mycurrobj + " input[name=placetakenat]")), keywords: getObjectData($("#" + mycurrobj + " input[name=keywords]")), country: $("#" + mycurrobj + " select[name=country] option:selected").val(), location: $("#" + mycurrobj + " input[name=location]").val()}, function(data) {
                if (data != false) {
                    albumclicked.find('.sorttilte').html($("#" + mycurrobj + " input[name=title]").val());
                    var des_str = getObjectData($("#" + mycurrobj + " textarea[name=description]"));
                    var str = des_str.replace(/\\n|\n/g, '<br />');
                    var result1 = str.substring(0, 100);
                    var result2 = str.substring(0, 80);
                    if (str.length > 100) {
                        result1 += '...';
                    }
                    if (str.length > 80) {
                        result2 += '...';
                    }
                    albumclicked.find('.mediainfodesc1').html(result1);
                    albumclicked.find('.mediainfodesc').html(result2);
                    $('.fancybox-close').click();
                }
            });
        }
    });
    $(document).on('click', '#uploadlistpopuppublishalbum .UploadMediaRightTF #log_save_media', function() {
        var mycurrobj = 'uploadlistpopuppublishalbum';
        if (verifyFormList(mycurrobj)) {
            //$('.upload-overlay-loading-fix').show();
            var curob = $(this).closest('.tableform');
            var privacyValue = curob.find('.privacyclass.active').attr('data-value');         
            var privacyArray = new Array();

            if (privacyValue == USER_PRIVACY_SELECTED) {
                curob.find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function() {
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
            if ((privacyValue == USER_PRIVACY_SELECTED && privacyArray.length == 0)) {
                curob.find('.error_valid').html(t('Invalid privacy data'));
                return;
            }
            var cobthis = $(this);

            $.post(ReturnLink('/ajax/profile_media_save.php'), {privacyValue: privacyValue, privacyArray: privacyArray, vid: $('#uploadlistpopuppublishalbum').attr('data-id'), title: getObjectData($("#" + mycurrobj + " input[name=title]")), cityname: $("#" + mycurrobj + " input[name=cityname]").val(),cityid: $("#"+mycurrobj+" input[name=cityname]").attr('data-id'), citynameaccent: $("#" + mycurrobj + " input[name=citynameaccent]").val(), is_public: $("#" + mycurrobj + " div.privacyclass.active").attr("data-value"), description: getObjectData($("#" + mycurrobj + " textarea[name=description]")), category: $("#" + mycurrobj + " select[name=category] option:selected").val(), placetakenat: getObjectData($("#" + mycurrobj + " input[name=placetakenat]")), keywords: getObjectData($("#" + mycurrobj + " input[name=keywords]")), country: $("#" + mycurrobj + " select[name=country] option:selected").val(), location: $("#" + mycurrobj + " input[name=location]").val()}, function(data) {
                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {
                    $('.fancybox-close').click();
                }
                if (!jres) {
                    $('.fancybox-close').click();
                    return;
                }                
                if (jres.status=='error') {
                    TTAlert({
                        msg: jres.msg,
                        type: 'alert',
                        btn1:'',
                        btn2: t('ok')
                    });
                    $('.fancybox-close').click();
                    return;
                }                
                albumclicked.find('.sorttilte').html(jres.title);
                var des_str = jres.description;
                var str = des_str.replace(/\\n|\n/g, '<br />');
                var result1 = str.substring(0, 100);
                var result2 = str.substring(0, 80);
                if (str.length > 100) {
                    result1 += '...';
                }
                if (str.length > 80) {
                    result2 += '...';
                }
                albumclicked.find('.mediainfodesc1').html(result1);
                albumclicked.find('.mediainfodesc').html(result2);
                $('.fancybox-close').click();
            });
        }
    });

    $(document).on('click', ".uploadinfocheckbox", function() {

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
            $(this).addClass('active');
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var friendstr = '<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends")+'</div><div class="peoplesdataclose"></div></div>';

                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(friendstr);
                    //$(this).parent().parent().find('#friendsdata').css("width", ($(this).parent().parent().find('#friendsdata .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends of friends")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                    //$(this).parent().parent().find('#friends_of_friends_data').css("width", ($(this).parent().parent().find('#friends_of_friends_data .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("followers")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                    //$(this).parent().parent().find('#followersdata').css("width", ($(this).parent().parent().find('#followersdata .peoplesdatainside').width() + 20) + "px");
                }
            }
        }
    });
    $(document).on('click', ".peoplesdataclose", function() {
        var parents = $(this).parent();
        var parents_all = parents.parent().parent();
        parents.remove();
        if (parents.attr('data-id') != '') {
            SelectedUsersDelete(parents.attr('data-id'), parents_all.find('.addmore input'));
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
});
function initResetSelectedUsers(obj) {
    obj.hide();
    resetSelectedUsers(obj.find('.addmore input'));
    obj.find('.uploadinfocheckbox').removeClass('active');
    obj.find('.addmore input').val('');
    obj.find('.addmore input').blur();
    obj.find('.peoplesdata').each(function() {
        var parents = $(this);
        parents.remove();
    });
}
function refreshEditAlbums() {
    $("a.EditCatalogActionButs").each(function() {
        var $clicked = $(this);
        var id = $(this).attr('rel');
        $clicked.fancybox({
            padding: 0,
            margin: 0,
            beforeLoad: function() {
                $('#uploadlistpopuppublishalbum').html('');
                $('#uploadlistpopuppublishalbum .inputuploadformTF').val('');
                $('#uploadlistpopuppublishalbum #logalbumaddcountry, #newphototo #logalbumaddcategory').val('0');
                $('#uploadlistpopuppublishalbum .inputuploaddescriptionTF').val('');
                $('#uploadlistpopuppublishalbum textarea[name=description]').blur();
                $('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
                $('#uploadlistpopuppublishalbum input[name=keywords]').blur();

                albumclicked = $clicked.parent();
                CatalogEdit(id);
            }
        });
    });
}
function refreshEditMedia() {
    $("a.EditMediaActionButs").each(function() {
        var $clicked = $(this);
        var id = $(this).attr('rel');
        $clicked.fancybox({
            padding: 0,
            margin: 0,
            beforeLoad: function() {
                $('#uploadlistpopuppublishalbum').html('');
                $('#uploadlistpopuppublishalbum .inputuploadformTF').val('');
                $('#uploadlistpopuppublishalbum #logalbumaddcountry, #newphototo #logalbumaddcategory').val('0');
                $('#uploadlistpopuppublishalbum .inputuploaddescriptionTF').val('');
                $('#uploadlistpopuppublishalbum textarea[name=description]').blur();
                $('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
                $('#uploadlistpopuppublishalbum input[name=keywords]').blur();

                albumclicked = $clicked.parent();
                MediaEditData(id);
            }
        });
    });
}
function CatalogDelete(id,obj) {
    
    $.ajax({
        url: ReturnLink('/ajax/catalog_delete.php'),
        data: {
            cid: id
        },
        type: 'post',
        success: function(data) {
            
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }

            if (!ret) {
                TTAlert({
                    msg: t('Couldnt remove from favorites. Please try again later.'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }

            if (ret.status == 'ok') {
                obj.remove();
                one_object =1;
                var section = $('#page_nav a').attr('data-case');
                ProfilePage--;
                RebuildLink(section);
                displayMediaDataRelates();
            } else {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
        }
    });
}
function verifyFormList(which) {
    var ok = true;
    var $currobjselected = $('#' + which);
    $('.uploadinfomandatory span', $currobjselected).html('');
    $('input,select,textarea', $currobjselected).removeClass('err').each(function() {
        var name = $(this).attr('name');
        var $parenttarget = $(this).parent();
        if ((name == 'location') || (name == 'location_name') || (name == 'cityname') || (name == 'status') || (name == 'vid') || $(this).hasClass('filevalue') || (name == 'filename') || (name == 'addmoretext') || (name == 'vpath')) {

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
    }

    if (!ok) {

        return false;
    } else {
        return true;
    }
}
function CatalogEdit(id) {
    $.post(ReturnLink('/ajax/ajax_getlistalbumuploaddata.php'), {id: id}, function(data) {
        if (data != false) {
            $('#uploadlistpopuppublishalbum').html(data);
            resetSelectedUsers($('#uploadlistpopuppublishalbum #addmoretext_privacy'));
            $('#uploadlistpopuppublishalbum input[name=title]').blur();
            $('#uploadlistpopuppublishalbum textarea[name=description]').blur();
            $('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
            $('#uploadlistpopuppublishalbum input[name=keywords]').blur();

            $('#uploadlistpopuppublishalbum .uploadinfocheckboxcontainer').remove();

            addAutoCompleteList('uploadlistpopuppublishalbum');
            $('#uploadlistpopuppublishalbum').attr('data-id', id);
            var privacyselcted = parseInt($('#uploadlistpopuppublishalbum .formcontainer').attr('data-value'));
            $('#uploadlistpopuppublishalbum #privacyclass_user' + privacyselcted).click();
            if (privacyselcted == USER_PRIVACY_SELECTED) {
                $('#uploadlistpopuppublishalbum').find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function() {
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
            addmoreusersautocomplete_custom_journal($('#uploadlistpopuppublishalbum #addmoretext_privacy'));
        }
    });
}
function MediaEditData(id) {
    $.post(ReturnLink('/ajax/profile_media_details.php'), {id: id}, function(data) {
        if (data != false) {
            $('#uploadlistpopuppublishalbum').html(data);
            resetSelectedUsers($('#uploadlistpopuppublishalbum #addmoretext_privacy'));
            $('#uploadlistpopuppublishalbum input[name=title]').blur();
            $('#uploadlistpopuppublishalbum textarea[name=description]').blur();
            $('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
            $('#uploadlistpopuppublishalbum input[name=keywords]').blur();

            $('#uploadlistpopuppublishalbum .uploadinfocheckboxcontainer').remove();

            addAutoCompleteList('uploadlistpopuppublishalbum');
            $('#uploadlistpopuppublishalbum').attr('data-id', id);
            var privacyselcted = parseInt($('#uploadlistpopuppublishalbum .formcontainer').attr('data-value'));
            $('#uploadlistpopuppublishalbum #privacyclass_user' + privacyselcted).click();
            if (privacyselcted == USER_PRIVACY_SELECTED) {
                $('#uploadlistpopuppublishalbum').find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function() {
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
            addmoreusersautocomplete_custom_journal($('#uploadlistpopuppublishalbum #addmoretext_privacy'));
        }
    });
}
function addAutoCompleteList(which) {
    var $citynameaccent = $("input[name=citynameaccent]", $('#' + which));
    $citynameaccent.autocomplete({
        appendTo: "#contentcontainer",
        search: function(event, ui) {
            var $country = $('select[name=country]', $citynameaccent.parent()).removeClass('err');
            var cc = $('option:selected', $country).val();
            if (cc == 'ZZ') {
                $country.addClass('err');
                event.preventDefault();
            } else {
                $citynameaccent.autocomplete("option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc));
            }
        },
        select: function(event, ui) {
            $citynameaccent.val(ui.item.value);
            $('input[name=cityname]', $citynameaccent.parent()).val(ui.item.value);
            $('input[name=cityname]', $citynameaccent.parent()).attr('data-id',ui.item.id);
            event.preventDefault();
        }
    });
}

function FavoriteDelete(id) {
    
    $.ajax({
        url: ReturnLink('/ajax/profile_del_fav.php'),
        data: {
            vid: id
        },
        type: 'post',
        success: function(data) {
            $('body').css('cursor', '');
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }

            if (!ret) {
                TTAlert({
                    msg: t('Couldnt remove from favorites. Please try again later.'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }

            if (ret.status == 'ok') {
                window.location.reload();
            } else {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
        }
    });
}
function FavoriteCamDelete(id) {
    
    $.ajax({
        url: ReturnLink('/ajax/profile_del_fav_cam.php'),
        data: {
            vid: id
        },
        type: 'post',
        success: function(data) {
            $('body').css('cursor', '');
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }

            if (!ret) {
                TTAlert({
                    msg: t('Couldnt remove from favorites. Please try again later.'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }

            if (ret.status == 'ok') {
                window.location.reload();
            } else {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
        }
    });
}
function MediaDelete(id,obj) {
    
    $.ajax({
        url: ReturnLink('/ajax/profile_del_media.php'),
        data: {
            vid: id
        },
        type: 'post',
        success: function(data) {
            $('body').css('cursor', '');
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }

            if (!ret) {
                TTAlert({
                    msg: t('Couldnt delete. Please try again later.'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }

            if (ret.status == 'ok') {                
                obj.remove();
                one_object =1;
                var section = $('#page_nav a').attr('data-case');
                ProfilePage--;
                RebuildLink(section);
                displayMediaDataRelates();
            } else {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
        }
    });
}


function MediaEdit(id) {

    var $dialog = $('#dialog');
    if ($dialog.length == 0) {
        $dialog = $('<div id="dialog"/>');
        $dialog.appendTo($('body'));
    }

    $('body').css('cursor', 'pointer');
    $.ajax({
        url: ReturnLink('/ajax/profile_media_details.php'),
        type: 'post',
        data: {
            vid: id
        },
        success: function(data) {
            $dialog.html(data);

            $('input[name=save]', $dialog).click(function() {

                MediaSave(function(data) {
                    $dialog.dialog('close');
                });

            });

            $dialog.dialog({
                zIndex: 100,
                autoOpen: true,
                title: "Edit media",
                modal: true,
                draggable: false,
                minWidth: 940,
                minHeight: 410,
                resizable: false,
                close: function(ev, ui) {
                    $("input[name=citynameaccent]").autocomplete('close');
                    $("input[name=placetakenat]").autocomplete('close');
                    $("input[name=tripname]").autocomplete('close');
                }
            });
        }
    });
}

function AddAutomplete() {
    var $citynameaccent = $("input[name=citynameaccent]");
    $citynameaccent.autocomplete({
        search: function(event, ui) {
            var $country = $('select[name=country]').removeClass('err');
            var cc = $('option:selected', $country).val();
            if (cc == 'ZZ') {
                $country.addClass('err');
                event.preventDefault();
            } else {
                $citynameaccent.autocomplete("option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc));
            }
        },
        select: function(event, ui) {
            $citynameaccent.val(ui.item.value);
            $('input[name=cityname]').val(ui.item.value);
            $('input[name=cityname]').attr('data-id',ui.item.id);
            event.preventDefault();
        }
    });

    var $tripname = $("input[name=tripname]");
    //userTrips in upload.php
    $tripname.autocomplete({
        source: userTrips,
        minLength: 2
    });
}

function MediaSave(callback) {

    $.ajax({
        url: ReturnLink('/ajax/profile_media_save.php'),
        type: 'post',
        data: {
            vid: $('input[name=vid]').val(),
            title: $('input[name=title]').val(),
            cityname: $('input[name=cityname]').val(),
            cityid: $('input[name=cityname]').attr('data-id'),
            citynameaccent: $('input[name=citynameaccent]').val(),
            is_public: $('input[name=is_public]:checked').val(),
            description: $('textarea[name=description]').val(),
            category: $('select[name=category] option:selected').val(),
            placetakenat: $('input[name=placetakenat]').val(),
            keywords: $('input[name=keywords]').val(),
            country: $('select[name=country] option:selected').val(),
            lattitude: $('input[name=lattitude]').val(),
            longitude: $('input[name=longitude]').val(),
            location: $('input[name=location]').val(),
            tripname: $('input[name=tripname]').val()
        },
        success: function(data) {

            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                TTAlert({
                    msg: t("Couldn't Process Request. Please try again later."),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }

            if (!ret) {
                TTAlert({
                    msg: t("Couldn't Process Request. Please try again later."),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }

            if (ret.status == 'ok') {
                callback(data);

                var title = $('input[name=title]').val();
                var description = $('textarea[name=description]').val();
                var vid = $('input[name=vid]').val();
                $('#cvideo_' + vid + ' strong.sorttilte').html(title);
                $('#cvideo_' + vid + ' div.mediainfodesc').html(description);

                $('#video_' + vid + ' strong.sorttilte').html(title);
                $('#video_' + vid + ' div.mediainfodesc').html(description);

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

}
function RebuildLink(section){
    var link;
    switch(section){
        case 'videos':
            link = ReturnLink('parts/profile_vids.php?userId='+userId+'&page='+ProfilePage+'&ss='+ss);
        break;
        case 'photos':
            link = ReturnLink('parts/profile_photos.php?userId='+userId+'&page='+ProfilePage+'&ss='+ss);
        break;
        case 'albums':
            link = ReturnLink('parts/profile_albums.php?userId='+userId+'&page='+ProfilePage+'&ss='+ss);
        break;
        case 'favorites':
            link = ReturnLink('parts/profile_favorites.php?page='+ProfilePage);
        break;
        case 'newsfeed':
            link = ReturnLink('parts/profile_newsfeed.php?page='+ProfilePage);
        break;
    }  
    $('#page_nav a').attr('href', link );
}
function checkSubmit(e){
   if(e && e.keyCode == 13){
      $('#searchbut').click();
   }
}