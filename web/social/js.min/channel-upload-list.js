var currentpage = 0;
var vtype = "";
var searchdescriptiontext = "for better visibility and online search, please fill here";
$(document).ready(function () {
    
    $(document).on('change', "select[name=country]", function () {
        var parents = $(this).closest('.UploadMediaRightTF');
        parents.find('input[name=citynameaccent]').val('');
        parents.find('input[name=cityname]').val('');
        parents.find('input[name=cityname]').attr('data-id','');
    });
    $(document).on('mouseover', ".openclose", function () {
        $(this).find('.openclose_overtxt').html(t('expand'));
    });
    $(document).on('mouseover', ".closeclose", function () {
        $(this).find('.openclose_overtxt').html(t('collapse'));
    });
    $(document).on('click', "#hide_button", function () {
        var $parent = $(this).closest('li');
        deleteMediaPending($parent.attr('data-filename'), $parent.attr('data-vpath'));
        $parent.remove();
        var menuid = parseInt($(this).attr('id'));
        switch (menuid) {
            case 1:
                vtype = "i";
                break;
            case 2:
                vtype = "v";
                break;
            case 3:
                vtype = "a";
                break;
        }
        if (vtype != "a") {
            if (parseInt($('#uploadlistpagecount').attr('data-cnt')) > 1) {
                displayPenddingMediaRelated();
            }
        } else {
            if (parseInt($('#uploadlistpagecountalbum').attr('data-cnt')) > 1) {
                displayPenddingMediaAlbumRelated();
            } else {
                if ($('#uploadlistalbumContentpics #uploadlistpicsleft ul li').length == 0) {
                    $('#albumlist').find('#' + catalogID()).remove();
                    $('#uploadlistalbumContent #uploadlistalbumContentpics').html('');
                }
            }
        }
    });
    $("#newphototofancy").fancybox({
        "padding": 0,
        "margin": 0,
        "width": 375,
        "height": 146,
        "transitionIn": "none",
        "transitionOut": "none",
        "type": "iframe"
    });
    $("#loglistalbumelectedpicsfancy").fancybox({
        "padding": 0,
        "margin": 0,
        "width": 375,
        "height": 146,
        "transitionIn": "none",
        "transitionOut": "none",
        "type": "iframe"
    });
    var datastr = addanotherappend('newphototo');
    $('#uploadlistpopuppublish').html(datastr);
    addAutoCompleteList('newphototo');

    $(document).on('click', "#uploadlistmenucontainer #uploadlistmenucontent .uploadlistmenu", function () {
        if (!$(this).hasClass('active')) {
            catalogID(0);
            currentpage = 0;
            $('#uploadlistalbumContent').hide();
            $('#uploadlistalbumContent #albumlist').html('');
            $('#uploadlistalbumContent #uploadlistalbumContentpics').html('');
            $('#uploadlistcontainer').html('');

            $('#uploadlistmenucontainer #uploadlistmenucontent .uploadlistmenu').removeClass('active');
            $(this).addClass('active');
            var menuid = parseInt($(this).attr('id'));
            switch (menuid) {
                case 1:
                    vtype = "i";
                    break;
                case 2:
                    vtype = "v";
                    break;
                case 3:
                    vtype = "a";
                    break;
            }
            if (vtype != "a") {
                displayPenddingMediaRelated();
            } else {
                $('.upload-overlay-loading-fix-file').show();
                $.post(ReturnLink('/ajax/ajax_getalbumspending.php'), {globchannelid: globchannelid}, function (data) {
                    if (data != false) {
                        $('#uploadlistalbumContent').show();
                        $('#uploadlistalbumContent #albumlist').html(data);
                        $('.upload-overlay-loading-fix-file').hide();
                    }
                });
            }
        }
    });
    $(document).on('click', ".uploadlistpagecountalbumnext", function () {
        if (!$(this).hasClass('inactive')) {
            currentpage++;
            if (currentpage > parseInt($('#uploadlistpagecountalbum').attr('data-cnt')) - 1) {
                currentpage = parseInt($('#uploadlistpagecountalbum').attr('data-cnt')) - 1;
            }
            displayPenddingMediaAlbumRelated();
        }
    });
    $(document).on('click', ".uploadlistpagecountalbumprev", function () {
        if (!$(this).hasClass('inactive')) {
            currentpage--;
            if (currentpage < 0) {
                currentpage = 0;
            }
            displayPenddingMediaAlbumRelated();
        }
    });
    $(document).on('click', ".uploadlistpagecountnext", function () {
        if (!$(this).hasClass('inactive')) {
            currentpage++;
            if (currentpage > parseInt($('#uploadlistpagecount').attr('data-cnt')) - 1) {
                currentpage = parseInt($('#uploadlistpagecount').attr('data-cnt')) - 1;
            }
            displayPenddingMediaRelated();
        }
    });
    $(document).on('click', ".uploadlistpagecountprev", function () {
        if (!$(this).hasClass('inactive')) {
            currentpage--;
            if (currentpage < 0) {
                currentpage = 0;
            }
            displayPenddingMediaRelated();
        }
    });

    $(document).on('click', "#uploadlistpopuppublishalbum #loglistalbumelected .ClearNewAlbumButtonChannel", function () {
        $('.fancybox-close').click();
    });
    $(document).on('click', "#newphototo .UploadMediaRightTF .LaterButtonChannel, #uploadlistpopuppublishalbumpic .LaterButtonChannel", function () {
        var myObject = $(this).closest('.tableform').parent().parent();
        if (formIsFilled(myObject) || myObject.find(".uploadinfocheckbox1.active").length>0) {
            updatePicDataLater(myObject);
        } else {
            $('.fancybox-close').click();
        }
    });
    $(document).on('click', ".uploadinfocheckbox_upload", function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });
    $(document).on('click', '#newphototo .UploadMediaRightTF .SaveButtonChannel', function () {
        var mycurrobj = 'newphototo';
        if (verifyFormList(mycurrobj)) {
            saveObjectData(mycurrobj, true);
        }
    });
    $(document).on('click', '#uploadlistpopuppublishalbum .UploadMediaRightTF .SaveNewAlbumButtonChannel', function () {
        var mycurrobj = 'uploadlistpopuppublishalbum';
        if (verifyFormList(mycurrobj)) {
            $('.upload-overlay-loading-fix-file').show();
            var cobthis = $(this);
            $.post(ReturnLink('/ajax/ajax_updatealbumupload.php'), {catalog_id: catalogID(), title: getObjectData($("#" + mycurrobj + " input[name=title]")), cityname: $("#" + mycurrobj + " input[name=cityname]").val(), cityid: $("#" + mycurrobj + " input[name=cityname]").attr('data-id'), citynameaccent: $("#" + mycurrobj + " input[name=citynameaccent]").val(), is_public: 2, description: getObjectData($("#" + mycurrobj + " textarea[name=description]")), category: $("#" + mycurrobj + " select[name=category] option:selected").val(), placetakenat: getObjectData($("#" + mycurrobj + " input[name=placetakenat]")), keywords: getObjectData($("#" + mycurrobj + " input[name=keywords]")), country: $("#" + mycurrobj + " select[name=country] option:selected").val(), location: $("#" + mycurrobj + " input[name=location]").val()}, function (data) {
                if (data != false) {
                    $('#uploadlistpopuppublishalbumhidden').html($("#" + mycurrobj + "").html());

                    $('#albumlist').find('#' + catalogID()).html($("#" + mycurrobj + " input[name=title]").val());
                    $('.uploadlistcontainerrighttitle').html( $("#" + mycurrobj + " input[name=title]").val());
                    if ($("#" + mycurrobj + " .uploadinfocheckbox_upload").hasClass('active')) {

                        $.post(ReturnLink('/ajax/ajax_getalbumuploaddatapicslist.php'), {id: catalogID(), globchannelid: globchannelid}, function (data) {
                            if (data != false) {
                                $('#uploadlistfiletosave').html(data);

                                var myformarr = CopyFormAlbumtab2("#" + mycurrobj + "");
                                if (myformarr != "") {
                                    $.ajax({url: ReturnLink("/up_savenew.php"), type: "post", data: {
                                            S: $("#S").val(),
                                            myformarr: myformarr,
                                            title: getObjectData($("#" + mycurrobj + " input[name=title]")),
                                            cityname: $("#" + mycurrobj + " input[name=cityname]").val(),
                                            cityid: $("#" + mycurrobj + " input[name=cityname]").attr('data-id'),
                                            citynameaccent: $("#" + mycurrobj + " input[name=citynameaccent]").val(),
                                            is_public: $("#" + mycurrobj + " div.privacyclass.active").attr("data-value"),
                                            description: getObjectData($("#" + mycurrobj + " textarea[name=description]")),
                                            catalog_id: catalogID(),
                                            category: $("#" + mycurrobj + " select[name=category] option:selected").val(),
                                            placetakenat: getObjectData($("#" + mycurrobj + " input[name=placetakenat]")),
                                            keywords: getObjectData($("#" + mycurrobj + " input[name=keywords]")),
                                            country: $("#" + mycurrobj + " select[name=country] option:selected").val(),
                                            location: $("#" + mycurrobj + " input[name=location]").val(),
                                            globchannelid: globchannelid,
                                            defaultcatalogicon: 0
                                        },
                                        success: function (data) {
                                            $('#albumlist').find('#' + catalogID()).remove();
                                            $('#uploadlistalbumContent #uploadlistalbumContentpics').html('');
                                            $('.fancybox-close').click();
                                            $('.upload-overlay-loading-fix-file').hide();
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {

                                        }
                                    });
                                } else {
                                    $('.upload-overlay-loading-fix-file').hide();
                                }
                            }
                        });

                    } else {
                        $('.fancybox-close').click();
                        $('.upload-overlay-loading-fix-file').hide();
                    }

                }
            });
        }
    });
    $(document).on('click', '#uploadlistpopuppublishalbumpic .UploadMediaRightTF .SaveButtonChannel', function () {
        var mycurrobj = 'loglistalbumelectedpics';
        if ($(this).is('#loglistalbumelectedpicssavein') && $("#" + mycurrobj + " .uploadinfocheckbox1").hasClass('active')) {
            CopyForm2($('#' + mycurrobj));
            saveObjectData(mycurrobj, false);
        } else if (verifyFormList(mycurrobj)) {
            CopyForm($('#' + mycurrobj));
            saveObjectData(mycurrobj, true);
        }
    });
    $('.uploadlistmenu_first').click();
});
function FileUploadSaved(fid) {
    if (vtype != "a") {
        $('#' + $('#uploadlistpopuppublish').attr('data-target')).remove();
        if (parseInt($('#uploadlistpagecount').attr('data-cnt')) > 1) {
            displayPenddingMediaRelated();
        }
    } else {
        $('#' + $('#uploadlistpopuppublishalbumpic').attr('data-target')).remove();
        if (parseInt($('#uploadlistpagecountalbum').attr('data-cnt')) > 1) {
            displayPenddingMediaAlbumRelated();
        } else {
            if ($('#uploadlistalbumContentpics #uploadlistpicsleft ul li').length == 0) {
                $('#albumlist').find('#' + catalogID()).remove();
                $('#uploadlistalbumContent #uploadlistalbumContentpics').html('');
            }
        }
    }
    $('.upload-overlay-loading-fix-file').hide();
}
function SaveError(FID, msg) {

}
var global_catalog_ids = null;
/**
 * gets or sets the global catalog id
 * @param integer catalog_id if set, sets the global catalog id.
 * @return integer if catalog_id parameter is not passed returns the global catalog id
 */
function catalogID(catalog_id) {
    if (typeof catalog_id == 'undefined')
        return global_catalog_ids;
    else
        global_catalog_ids = catalog_id;
}

function refreshPublishMedia() {
    $("#uploadlistcontainer #uploadlistcontainerpics li a").each(function () {
        var $clicked = $(this);
        $clicked.fancybox({
            padding: 0,
            margin: 0,
            beforeLoad: function () {
                $("#newphototo .uploadinfocheckbox_upload").removeClass('active');
                $('#newphototo .inputuploadformTF').val('');
                $('#newphototo #logalbumaddcountry, #newphototo #logalbumaddcategory').val('0');
                $('#newphototo .inputuploaddescriptionTF').val('');
                $('#newphototo textarea[name=description]').blur();
                $('#newphototo input[name=placetakenat]').blur();
                $('#newphototo input[name=keywords]').blur();

                var target = $clicked.parent().attr('id');
                var id = $clicked.parent().attr('data-id');
                var vpath = $clicked.parent().attr('data-vpath');
                var filename = $clicked.parent().attr('data-filename');
                $('#uploadlistpopuppublish').attr('data-target', target);
                $('#uploadlistpopuppublish').attr('data-id', id);
                $('#uploadlistpopuppublish').attr('data-vpath', vpath);
                $('#uploadlistpopuppublish').attr('data-filename', filename);
                
                $.post(ReturnLink('/ajax/pending_media_details.php'), {id: id,channelid:channelGlobalID()}, function(data) {
                    if (data != false) {
                        $('#uploadlistpopuppublish').html(data);
                        resetSelectedUsers($('#uploadlistpopuppublish #addmoretext_privacy'));
                        $('#uploadlistpopuppublish input[name=title]').blur();
                        $('#uploadlistpopuppublish textarea[name=description]').blur();
                        $('#uploadlistpopuppublish input[name=placetakenat]').blur();
                        $('#uploadlistpopuppublish input[name=keywords]').blur();

                        addAutoCompleteList('uploadlistpopuppublish');                        
                    }
                });
            }
        });
    });
}
function refreshPublishMediaAlbum() {
    $("#uploadlistalbumContentpics #uploadlistpicsleft li a").each(function () {
        var $clicked = $(this);
        $clicked.fancybox({
            padding: 0,
            margin: 0,
            beforeLoad: function () {
                $('#loglistalbumelectedpics .inputuploadformTF').val('');
                $('#loglistalbumelectedpics #logalbumaddcountry, #loglistalbumelectedpics #logalbumaddcategory').val('0');
                $('#loglistalbumelectedpics .inputuploaddescriptionTF').val('');
                $('#loglistalbumelectedpics textarea[name=description]').blur();
                $('#loglistalbumelectedpics input[name=placetakenat]').blur();
                $('#loglistalbumelectedpics input[name=keywords]').blur();
                $('#uploadlistpopuppublishalbumpic .uploadinfocheckbox1').removeClass('active');
                $('#uploadlistpopuppublishalbumpic .uploadinfocheckbox2').removeClass('active');
                $('#loglistalbumelectedpics .catalog_class').val(catalogID());

                var target = $clicked.parent().attr('id');
                var vpath = $clicked.parent().attr('data-vpath');
                var id = $clicked.parent().attr('data-id');
                var filename = $clicked.parent().attr('data-filename');
                $('#uploadlistpopuppublishalbumpic').attr('data-target', target);
                $('#uploadlistpopuppublishalbumpic').attr('data-id', id);
                $('#uploadlistpopuppublishalbumpic').attr('data-vpath', vpath);
                $('#uploadlistpopuppublishalbumpic').attr('data-filename', filename);
                
                $.post(ReturnLink('/ajax/pending_mediaalbum_details.php'), {id: id,privacyselcted:USER_PRIVACY_PUBLIC,channelid:channelGlobalID()}, function(data) {
                    if (data != false) {
                        $('#uploadlistpopuppublishalbumpic').html(data);
                        $('#uploadlistpopuppublishalbumpic input[name=title]').blur();
                        $('#uploadlistpopuppublishalbumpic textarea[name=description]').blur();
                        $('#uploadlistpopuppublishalbumpic input[name=placetakenat]').blur();
                        $('#uploadlistpopuppublishalbumpic input[name=keywords]').blur();
                        addAutoCompleteList('uploadlistpopuppublishalbumpic');
                    }
                });
            }
        });
    });
}
function verifyFormList(which) {
    var ok = true;
    var $currobjselected = $('#' + which);
    $('.uploadinfomandatory span', $currobjselected).html('');
    $('input,select,textarea', $currobjselected).removeClass('err').each(function () {
        var name = $(this).attr('name');
        var $parenttarget = $(this).parent();
        if ((name == 'location') || (name == 'location_name') || (name == 'cityname') || (name == 'status') || (name == 'vid') || $(this).hasClass('filevalue')) {

        } else {
            if ((!$('.uploadinfomandatory' + name, $parenttarget).hasClass('inactive') && $(this).val() == '') || (name == "category" && !$('.uploadinfomandatorycategory', $parenttarget).hasClass('inactive') && $(this).val() == '0') || (name == "country" && !$('.uploadinfomandatorycountry', $parenttarget).hasClass('inactive') && $(this).val() == '0')) {
                $('.uploadinfomandatory' + name + ' span', $parenttarget).html(t('please fill this field correctly'));
                ok = false;
            }
        }
    });

    var $parenttarget = $('input[name=cityname]', $currobjselected).parent();

    if ($('input[name=cityname]', $currobjselected).val() == '' && !$('.uploadinfomandatorycitynameaccent', $parenttarget).hasClass('inactive')) {
        $('.uploadinfomandatorycitynameaccent span', $parenttarget).html(t('please fill this field correctly'));
        ok = false;
    }

    if (!ok) {
        /*TTAlert({
         msg: 'Please input mandatory fields',
         type: 'alert',
         btn1: t('ok'),
         btn2: '',
         btn2Callback: null
         });*/
        return false;
    } else {
        return true;
    }
}
function addAutoCompleteList(which) {
    var $citynameaccent = $("input[name=citynameaccent]", $('#' + which));
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
        },
        select: function (event, ui) {
            $citynameaccent.val(ui.item.value);
            $('input[name=cityname]', $citynameaccent.parent()).val(ui.item.value);
            $('input[name=cityname]', $citynameaccent.parent()).attr('data-id',ui.item.id);
            event.preventDefault();
        }
    });
}
function displayPenddingMediaRelated() {
    $('.upload-overlay-loading-fix-file').show();
    $.post(ReturnLink('/ajax/ajax_getphotosvideospending.php'), {type: vtype, currentpage: currentpage, globchannelid: globchannelid}, function (data) {
        if (data != false) {
            $('#uploadlistcontainer').html(data);
            refreshPublishMedia();
            $('.upload-overlay-loading-fix-file').hide();
        }
    });
}
function displayPenddingMediaAlbumRelated() {
    $('.upload-overlay-loading-fix-file').show();
    $.post(ReturnLink('/ajax/ajax_getphotosvideosalbumpending.php'), {id: catalogID(), currentpage: currentpage, globchannelid: globchannelid}, function (data) {
        if (data != false) {
            $('#uploadlistalbumContent #uploadlistalbumContentpics #uploadlistpicsleft').html(data);
            refreshPublishMediaAlbum();
            $('.upload-overlay-loading-fix-file').hide();
        }
    });
}
function dolistalbumdatadisplay(obj) {
    var id = parseInt($(obj).val());
    var boolloderappear = false;
    if (id != 0) {
        $('.upload-overlay-loading-fix-file').show();

        catalogID(id);
        currentpage = 0;
        /*load data inside album popup*/
        $.post(ReturnLink('/ajax/ajax_getlistalbumuploaddata_channel.php'), {id: id}, function (data) {
            if (data != false) {
                $('#uploadlistpopuppublishalbum').html(data);
                $('#uploadlistpopuppublishalbum textarea[name=description]').blur();
                $('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
                $('#uploadlistpopuppublishalbum input[name=keywords]').blur();

                addAutoCompleteList('uploadlistpopuppublishalbum');

                var datastr = addnewalbumdata('loglistalbumelectedpics');
                $('#uploadlistpopuppublishalbumpic').html(datastr);
                $('#uploadlistpopuppublishalbumpic .privacyclass').click();
                addAutoCompleteList('loglistalbumelectedpics');

                $('#uploadlistpopuppublishalbumhidden').html($('#uploadlistpopuppublishalbum').html());

                if (boolloderappear) {
                    $('.upload-overlay-loading-fix-file').hide();
                }
                boolloderappear = true;
            }
        });
        $.post(ReturnLink('/ajax/ajax_getlistalbumuploaddatapics.php'), {id: id, globchannelid: globchannelid, name: $("#albumlist option:selected").text()}, function (data) {
            if (data != false) {
                $('#uploadlistalbumContent #uploadlistalbumContentpics').html(data);
                $('#uploadlistalbumContent #uploadlistalbumContentpics .uploadlistpicalbum a').fancybox();

                refreshPublishMediaAlbum();
                if (boolloderappear) {
                    $('.upload-overlay-loading-fix-file').hide();
                }
                boolloderappear = true;
            }
        });
    }
}
function saveObjectData(mycurrobj, checkFields) {
    var isImage = false;
    var file_ext = $('#' + mycurrobj + '').parent().attr('data-filename').split('.');
    if (file_ext.length > 1) {
        file_ext = file_ext[file_ext.length - 1].toLowerCase();
        if ((file_ext == 'png') || (file_ext == 'jpg') || (file_ext == 'jpeg') || (file_ext == 'bmp') || (file_ext == 'tiff') || (file_ext == 'tif'))
            isImage = true;
    }
    if ($("#" + mycurrobj + " .uploadinfocheckbox1").hasClass('active')) {
        var myformarr = CopyFormtab2('#' + mycurrobj + '');
    } else {
        var myformarr = CopyFormtabSingle('#' + mycurrobj + '');
    }
    var ct_id = catalogID();
    if ($('#' + mycurrobj + " .catalog_class").length > 0) {
        if($('#' + mycurrobj + " .catalog_class").val()!= 0){
            ct_id = $('#' + mycurrobj + " .catalog_class").val();
        }else{
            ct_id = null;
        }
    }
    var queryString;
    if (checkFields) {
        queryString = {S: $("#S").val(),
            myformarr: myformarr,
            title: getObjectData($("#" + mycurrobj + " input[name=title]")),
            cityname: $("#" + mycurrobj + " input[name=cityname]").val(),
            cityid: $("#" + mycurrobj + " input[name=cityname]").attr('data-id'),
            citynameaccent: $("#" + mycurrobj + " input[name=citynameaccent]").val(),
            is_public: $("#" + mycurrobj + " div.privacyclass.active").attr("data-value"),
            description: getObjectData($("#" + mycurrobj + " textarea[name=description]")),
            catalog_id: ct_id,
            category: $("#" + mycurrobj + " select[name=category] option:selected").val(),
            placetakenat: getObjectData($("#" + mycurrobj + " input[name=placetakenat]")),
            keywords: getObjectData($("#" + mycurrobj + " input[name=keywords]")),
            country: $("#" + mycurrobj + " select[name=country] option:selected").val(),
            location: $("#" + mycurrobj + " input[name=location]").val(),
            globchannelid: globchannelid,
            getFromCatalog: false,
            defaultcatalogicon: $("#" + mycurrobj + " .uploadinfocheckbox2").hasClass("active") ? 1 : 0};
    } else {
        queryString = {S: $("#S").val(),
            myformarr: myformarr,
            catalog_id: ct_id,
            globchannelid: globchannelid,
            defaultcatalogicon: $("#" + mycurrobj + " .uploadinfocheckbox2").hasClass("active") ? 1 : 0,
            getFromCatalog: true
        };
    }
    if (myformarr != "") {
        $('.upload-overlay-loading-fix-file').show();
        $.ajax({url: ReturnLink("/up_savenew.php"), type: "post", data: queryString,
            success: function (data) {
                if ($("#" + mycurrobj + " .uploadinfocheckbox1").hasClass('active')) {
                    FileDeleteSaved();
                } else {
                    $('.fancybox-close').click();
                    ((!isImage) ? appendfancy("" + mycurrobj + "", data) : selectDirectThumb("" + mycurrobj + "", data))
                    FileUploadSaved();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    } else {
        $('.upload-overlay-loading-fix-file').hide();
    }
}
function FileDeleteSaved() {
    if (currentpage == parseInt($('#uploadlistpagecount').attr('data-cnt')) - 1) {
        currentpage = parseInt($('#uploadlistpagecount').attr('data-cnt')) - 2;
    }
    $('#albumlist').find('#' + catalogID()).remove();
    $('#uploadlistalbumContent #uploadlistalbumContentpics').html('');
    displayPenddingMediaRelated();
    $('.fancybox-close').click();
}
function CopyFormtab2(obj) {
    var formarr = new Array();
    var formarrstr = "";
    $('#uploadlistcontainerpics ul li').each(function () {
        var thisobj = "#" + $(this).attr('id');
        var mystr = $(this).attr('data-filename') + '[*]' + $(this).attr('data-vpath') + '[*][*]' + $(obj + " input[name=lattitude]").val() + '[*]' + $(obj + " input[name=longitude]").val() + '[*]' + $(obj + " input[name=vid]").val() + '[*]' + thisobj;
        formarr.push(mystr);
    });
    formarrstr = formarr.join('/*/');

    return formarrstr;
}
function CopyFormAlbumtab2(obj) {
    var formarr = new Array();
    var formarrstr = "";
    $('#uploadlistfiletosave .filetosave li').each(function () {
        var thisobj = "#" + $(this).attr('id');
        var mystr = $(this).attr('data-filename') + '[*]' + $(this).attr('data-vpath') + '[*][*]0[*]0[*][*]' + obj;
        formarr.push(mystr);
    });
    formarrstr = formarr.join('/*/');

    return formarrstr;
}
function CopyFormtabSingle(obj) {
    var formarr = new Array();
    var formarrstr = "";
    var mystr = $(obj).parent().attr('data-filename') + '[*]' + $(obj).parent().attr('data-vpath') + '[*][*]' + $(obj + " input[name=lattitude]").val() + '[*]' + $(obj + " input[name=longitude]").val() + '[*]' + $(obj + " input[name=vid]").val() + '[*]' + obj;
    formarr.push(mystr);
    formarrstr = formarr.join('/*/');

    return formarrstr;
}
function CopyForm(obj) {
    if (vtype == "a") {
        if (obj.find(".uploadinfocheckbox1").hasClass("active")) {
            obj.find('.tableform input[name=title]').val($("#uploadlistpopuppublishalbum input[name=title]").val());
            obj.find('.tableform input[name=cityname]').val($("#uploadlistpopuppublishalbum input[name=cityname]").val());
            obj.find('.tableform input[name=cityname]').attr('data-id',$("#uploadlistpopuppublishalbum input[name=cityname]").attr('data-id'));
            obj.find('.tableform input[name=citynameaccent]').val($("#uploadlistpopuppublishalbum input[name=citynameaccent]").val());
            obj.find('.tableform textarea[name=description]').val($("#uploadlistpopuppublishalbum textarea[name=description]").val());
            obj.find('.tableform input[name=placetakenat]').val($("#uploadlistpopuppublishalbum input[name=placetakenat]").val());
            obj.find('.tableform input[name=keywords]').val($("#uploadlistpopuppublishalbum input[name=keywords]").val());
            obj.find('.tableform input[name=location]').val($("#uploadlistpopuppublishalbum input[name=location]").val());
            obj.find('.tableform select[name=category]').val($("#uploadlistpopuppublishalbum select[name=category] option:selected").val());
            obj.find('.tableform select[name=country]').val($("#uploadlistpopuppublishalbum select[name=country] option:selected").val());
            if ($('#uploadlistpopuppublishalbumhidden .uploadinfocheckbox_upload').hasClass('active')) {
                obj.find('.tableform .showthumblink_album').remove();
            }
            obj.find('.tableform .uploadinfocheckbox1').removeClass('active');
//            obj.find('.tableform .uploadinfocheckbox2').removeClass('active');
        }
    }
}
function CopyForm2(obj) {
    if (vtype == "a") {
        if (obj.find(".uploadinfocheckbox1").hasClass("active")) {
            if ($('#uploadlistpopuppublishalbumhidden .uploadinfocheckbox_upload').hasClass('active')) {
                obj.find('.tableform .showthumblink_album').remove();
            }
            obj.find('.tableform .uploadinfocheckbox1').removeClass('active');
//            obj.find('.tableform .uploadinfocheckbox2').removeClass('active');
        }
    }
}

function formIsFilled(obj) {
    var ok = false;
    if (getObjectData($('input[name=title]', obj)) != "" && $('input[name=title]', obj).length > 0) {
        ok = true;
    } else if (getObjectData($('input[name=citynameaccent]', obj)) != "" && $('input[name=citynameaccent]', obj).length > 0) {
        ok = true;
    } else if (getObjectData($('textarea[name=description]', obj)) != "" && $('textarea[name=description]', obj).length > 0) {
        ok = true;
    } else if (getObjectData($('input[name=placetakenat]', obj)) != "" && $('input[name=placetakenat]', obj).length > 0) {
        ok = true;
    } else if (getObjectData($('input[name=keywords]', obj)) != "" && $('input[name=keywords]', obj).length > 0) {
        ok = true;
    } else if (getObjectData($('select[name=category]', obj)) != "0") {
        ok = true;
    }
    return ok;
}
function updatePicDataLater(myObject){
    var myformarr = CopyFormLater(myObject);
    var curob = myObject.find('.tableform');        
    var privacyValue = USER_PRIVACY_PUBLIC;
    var users_ids = new Array();
    
    var privacyArrayStr = '';
    var ct_id = myObject.find(".catalog_class").val();      
    $('.upload-overlay-loading-fix').show();
    var thisobj = "#" + myObject.attr('id');
    $.post(ReturnLink('/ajax/up_savelater.php'), {myformarr: myformarr, filename: $(thisobj).attr('data-filename'),catalog_id: ct_id,privacyValue: privacyValue,privacyArray: privacyArrayStr},function(data){
        $('.fancybox-close').click();
        $('.upload-overlay-loading-fix').hide();
    });
}
function CopyFormLater(obj) {
    var formarrstr = "";
    if ( obj.find(".uploadinfocheckbox1").length>0 ) {
        if (obj.find(".uploadinfocheckbox1").hasClass("active") && obj.find(".uploadinfocheckbox2").length>0 ) {
            obj.find('.tableform input[name=title]').val($("#uploadlistpopuppublishalbumhidden input[name=title]").val());
            obj.find('.tableform input[name=cityname]').val($("#uploadlistpopuppublishalbumhidden input[name=cityname]").val());
            obj.find('.tableform input[name=cityname]').attr('data-id',$("#uploadlistpopuppublishalbumhidden input[name=cityname]").attr('data-id'));
            obj.find('.tableform input[name=citynameaccent]').val($("#uploadlistpopuppublishalbumhidden input[name=citynameaccent]").val());
            obj.find('.tableform textarea[name=description]').val($("#uploadlistpopuppublishalbumhidden textarea[name=description]").val());
            obj.find('.tableform input[name=placetakenat]').val($("#uploadlistpopuppublishalbumhidden input[name=placetakenat]").val());
            obj.find('.tableform input[name=keywords]').val($("#uploadlistpopuppublishalbumhidden input[name=keywords]").val());
            obj.find('.tableform input[name=location]').val($("#uploadlistpopuppublishalbumhidden input[name=location]").val());
            obj.find('.tableform select[name=category]').val($("#uploadlistpopuppublishalbumhidden select[name=category] option:selected").val());
            obj.find('.tableform select[name=country]').val($("#uploadlistpopuppublishalbumhidden select[name=country] option:selected").val());
        }
    }
    var thisobj = "#" + obj.attr('id');        
    var formarrstr = getObjectData($(thisobj + " input[name=title]")) ;
    formarrstr += '[*]' + getObjectData($(thisobj + " textarea[name=description]"));
    formarrstr += '[*]' + getObjectData($(thisobj + " input[name=keywords]"));
    formarrstr += '[*]' + $(thisobj + " select[name=category] option:selected").val();
    formarrstr += '[*]' + $(thisobj + " select[name=country] option:selected").val();
    formarrstr += '[*]' + $(thisobj + " input[name=cityname]").val();
    formarrstr += '[*]' + $(thisobj + " input[name=citynameaccent]").val();
    formarrstr += '[*]' + getObjectData($(thisobj + " input[name=placetakenat]"))+'[*]';
    formarrstr += ($(thisobj +" .uploadinfocheckbox2").hasClass("active"))? 1 : 0;
    formarrstr += '[*]' + $(thisobj + " input[name=cityname]").attr('data-id');
    
    return formarrstr;
}