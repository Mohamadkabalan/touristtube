var currentPage = 0;
var one_object = 0;
var globorderby = "id";
var globtxtsrch = "";
var CATALOG_ID;
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
$(document).ready(function() {
    $('#privacy_icon_viewer').mouseover(function() {
        var diffx = $('#albumcontainer').offset().left+255;
        var diffy = $('#albumcontainer').offset().top+22;
        var posxx = $(this).offset().left-diffx;
        var posyy = $(this).offset().top-diffy;

        $('.privacybuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.privacybuttonsOver').css('left', posxx + 'px');
        $('.privacybuttonsOver').css('top', posyy + 'px');
        $('.privacybuttonsOver').stop().show();
    });
    $('#privacy_icon_viewer').mouseout(function() {
        $('.privacybuttonsOver').hide();
    });

    $("#load_more_next").click(function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        currentPage++;
        displaypicrelated();
    });

    $("#searchbut").click(function() {
        var txtvalsrch = "" + $("#srchtxt").val();
        if (txtvalsrch == "search") {
            txtvalsrch = "";
        }
        globorderby = $("#sortby").val();
        globtxtsrch = txtvalsrch;
        currentPage = 0;
        $('#albumimagecontainer ul').html('');
        displaypicrelated();
    });

    $(document).on('click', ".selectitem", function() {
        $(this).closest('li').find('.EditMediaActionButs').click();
    });
    $(document).on('click', "#albumimagecontainer ul li .clsimg", function() {
        var curbut = $(this).closest('li');
        var typemedia = "photo";
        if (curbut.attr('data-type') == "v") {
            typemedia = "video";
        }

        TTAlert({
            msg: sprintf( t('confirm to remove selected %s from album') , [typemedia] ),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data) {
                if (data) {
                    $('.upload-overlay-loading-fix').show();
                    var target = curbut.attr("id");
                    var idsarr = new Array();
                    idsarr.push(target);

                    $.post(ReturnLink('/ajax/ajax_deletepiccatalog.php'), {catid: CATALOG_ID, idarr: idsarr.join('/*/')}, function(data) {
                        if (data) {
                            curbut.remove();
                            one_object = 1;
                            displaypicrelated();
                        } else {
                            $('.upload-overlay-loading-fix').hide();
                        }
                    });
                }
            }
        });

    });
    $(document).on('mouseover', "#albumimagecontainer ul li .selectitem", function() {
        $(this).css('opacity', 1);
    });
    $(document).on('mouseout', "#albumimagecontainer ul li .selectitem", function() {
        $(this).css('opacity', 0);
    });
    $(document).on('click', "#albumimagecontainer ul li .selectitem", function() {
        var curitem = $(this).parent();
        objectSelected = curitem;

    });
    /*$(document).on('click',"#albumimagecontainer ul li .imgbk .editimg",function(){
     var curitem=$(this).parent().parent();
     objectSelected=curitem;
     if(parseInt(curitem.find('.selectitem').attr('data-type'))==1){
     editpics(curitem);
     }else{
     editvideos(curitem);
     }
     });	*/

    $(document).on('click', "#albumimagecontainer ul li .imgbk .albumimg:not(.albumimgActive)", function() {
        var curbut = $(this).closest('li');

        TTAlert({
            msg: t('confirm to set album icon'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data) {
                if (data) {
                    $('.upload-overlay-loading-fix').show();

                    var target = curbut.attr("id");
                    $.post(ReturnLink('/ajax/ajax_addalbumicon.php'), {catid: CATALOG_ID, id: target}, function(data) {
                        
                        try {
                            ret = $.parseJSON(data);
                        } catch (Ex) {
                            return;
                        }

                        if (!ret) {
                            TTAlert({
                                msg: t('Couldnt set album icon.'),
                                type: 'alert',
                                btn1: t('ok'),
                                btn2: '',
                                btn2Callback: null
                            });
                            return;
                        }
                        
                        if (ret.status == '1') {
                            $("#albumimagecontainer li .albumimg").removeClass("albumimgActive");
                            $("#albumimagecontainer li#"+ret.id+" .albumimg").addClass("albumimgActive");
                        }
                        initDocumentAlbum();
                        $('.upload-overlay-loading-fix').hide();
                    });
                }
            }
        });

        //hideOverPic($(this).parent());
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
                objectSelected.find('.imgtitle').html(jres.title);
                $('.fancybox-close').click();
            });
        }
    });
    $(document).on('click', "#uploadlistpopuppublishalbum #loglistalbumelected .ClearNewAlbumButton", function() {
        $('.fancybox-close').click();
    });
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
    initDocumentAlbum();
});
function initDocumentAlbum() {
    $(document).on('mouseover', ".imgbk_butons .imgbk_buts:not(.albumimgActive)", function(){
        var posxx = $(this).offset().left - $('#ProfileHeaderInternal').offset().left - 252;
        var posyy = $(this).offset().top - $('#ProfileHeaderInternal').offset().top - 21;
        $('.ProfileHeaderOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.ProfileHeaderOver').css('left', posxx + 'px');
        $('.ProfileHeaderOver').css('top', posyy + 'px');
        $('.ProfileHeaderOver').stop().show();
    });
    $(".imgbk_butons .imgbk_buts").mouseout(function() {
        $('.ProfileHeaderOver').hide();
    });
    refreshEditMedia();
}
function refreshEditMedia() {
    $("a.EditMediaActionButs").each(function() {
        var $clicked = $(this);
        var id = $(this).attr('rel');
        $clicked.fancybox({
            padding: 0,
            margin: 0,
            beforeLoad: function() {
                $('#uploadlistpopuppublishalbum .inputuploadformTF').val('');
                $('#uploadlistpopuppublishalbum #logalbumaddcountry, #newphototo #logalbumaddcategory').val('0');
                $('#uploadlistpopuppublishalbum .inputuploaddescriptionTF').val('');
                $('#uploadlistpopuppublishalbum textarea[name=description]').blur();
                $('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
                $('#uploadlistpopuppublishalbum input[name=keywords]').blur();

                objectSelected = $clicked.closest('li');
                MediaEditData(id);
            }
        });
    });
}
function MediaEditData(id) {
    $.post(ReturnLink('/ajax/profile_media_details.php'), {id: id}, function(data) {
        if (data != false) {
            $('#uploadlistpopuppublishalbum').html(data);
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
function addValue1(obj) {
    if ($(obj).attr('value') == '')
        $(obj).attr('value', $(obj).attr('data-value'));
}
function removeValue1(obj) {
    if ($(obj).attr('value') == $(obj).attr('data-value'))
        $(obj).attr('value', '');
}
function checkSubmit(e) {
    if (e && e.keyCode == 13) {
        $('#searchbut').click();
    }
}
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
function displaypicrelated() {
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_getpicalbum.php'), {catid: CATALOG_ID, currentPage: currentPage, txtsrch: globtxtsrch, globorderby: globorderby, one_object: one_object}, function(data) {
        if (data != false) {
            one_object = 0;
            $('#albumimagecontainer ul').append(data);
            var currPageStatus = $('#albumimagecontainer .currPageStatus');

            if (("" + currPageStatus.attr('data-value')) == "0") {
                $("#load_more_next").hide();
            } else {
                $("#load_more_next").show();
            }
            $('.data_head_text span.yellowbold12').html('(' + currPageStatus.attr('data-count') + ')');
            currPageStatus.remove();
            initDocumentAlbum();
        } else {
            $("#load_more_next").hide();
        }

        $('.upload-overlay-loading-fix').hide();
    });
}
function postMediaSave(data) {
    if (objectSelected != "") {
        objectSelected.find('.imgtitle').html(data.title);
    }
}
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