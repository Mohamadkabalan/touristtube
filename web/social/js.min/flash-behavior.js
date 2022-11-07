var LoadedPages = 0;
var uploader;
var requirements_met = true;
var jscrollpane_apiFE;
var myStyles = [
    {
        featureType: "poi",
        elementType: "labels",
        stylers: [
            {visibility: "off"}
        ]
    }
];

function initscrollPane() {
    $(".echoesList").jScrollPane({
        autoReinitialise: true
    });
    jscrollpane_apiFE = $(".echoesList").data('jsp');
}
function addValue(obj, val) {
    if ($(obj).attr('value') == '')
        $(obj).attr('value', val);
}
function removeValue(obj, value) {
    if ($(obj).attr('value') == value)
        $(obj).attr('value', '');
}
$.fn.searchEvent = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            if (ev.which == 13) {
                fnc.call(this, ev);
            }
        })
        $('.searchEchoIco').click(function (ev) {
            fnc.call(this, ev);
        })
    })
}
$(document).ready(function () {
    initSocialActions();
    $(".selectecho").selectbox();
    $(".selectecho").change(function(){
        var valfilter= $(this).val();
        window.location.href = ReturnLink('/echoes/filter/' + valfilter );
    });
    $('#loadmore').css('width', ($('#loadmore span').width() + 3) + "px");
    if (!$('#flashPhoto').hasClass('inactive')) {
        InitChannelUploaderHome('flashPhoto', 'flashTextField_container', 15, 0);
    }
    var datenow = new Date();
    if(day_search!=''){
        var datenow = new Date(day_search);   
    }
    var cal1=Calendar.setup({
        cont: "archiveCal",
        noScroll: true,
        date: datenow,
        onSelect: function (calss) {
            if(day_search!=''){
                day_search ='';
            }else{                
                var date = Calendar.intToDate(calss.selection.get());
                var dt = Calendar.printDate(date, "%Y-%m-%d");
                window.location.href = ReturnLink('/echoes/day/' + dt);
            }
        },
        showTime: 12,
        dateFormat: "%Y-%m-%d %I:%M %p"
    });
    if(day_search!=''){
        var as_number = Calendar.dateToInt(datenow);
        cal1.selection.set(as_number);        
    }
    initscrollPane();
    $(".searchEchoField").searchEvent(function () {
        var $searchVal = getObjectData($(".searchEchoField"));
        if($searchVal!=''){
            window.location.href = ReturnLink('/echoes/search/' + $searchVal);
        }
    });
    $(".aCloudItem").click(function () {
        var $link = $(this).html();
        $link = $link.trim();
        $link = ReturnLink('/echoes/tag/' + $link);
        window.location.href = $link;
    });
    $(".echoText").keyup(function () {
        var $this = $(this);
        var $inputVal = $this.val();
        var patt1 = /#(\w*\W)|(#(\w*)) /g;
        var result = $inputVal.match(patt1);
        if (result !== null) {
            result.forEach(function (value, index, ar) {
                var regex = /(\W)$/;

                if (value.match(regex)) {
                    value = value.replace(regex, "");
                }
                $inputVal = $inputVal.replace(value, '<b>' + value + '</b>');
            })
        }
        $(".toshow").html($inputVal);
        var left = FLASH_MAX_LENGTH - $this.val().length;
        $('.flashRemaining').html(left);
    });
    $(".echoText").on('keyup keypress blur change click scroll keydown focus blur', function () {
        //$(".toshow").css('top', -$(this).scrollTop());
        var hh = $(".toshow").height() + 38;
        $(".echoText").css('height', hh+"px");
    });
    $(document).on('click', ".anEchoInside p b", function () {
        var hash = $(this).html();
        hash = hash.substr(1);
        window.location.href = ReturnLink('/echoes/tag/' + hash);
    });
    $(document).on('click', ".clsimg", function () {
        $('#filename').val('');
        $('#vpath').val('');
        $('.ImageStan_flash').html('');
    });
    
    $(".flashTextField, #flashPhoto").click(function () {
        if ($(this).hasClass('inactive')) {
        TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to echo.'),
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
    });
    $(".echoSubmit").click(function () {
        if ($(this).hasClass('inactive')) {
        TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to echo.'),
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
        var textFlash = '';
        var linkFlash = '';
        var locationFlash = '';
        
        var filename = $('#filename').val();
        var vpath = $('#vpath').val();
        
        var type_txt = '';
        
        textFlash = getObjectData($('.echoText'));
        if(textFlash!=''){
            $('.echoText').val(textFlash+' ');
            $('.echoText').keyup();            
            if (textFlash != '') {
                textFlash = $('.toshow').html();
            }
        }
        linkFlash = getObjectData($('.echoLink'));        
        if (!ValidURL(linkFlash) && linkFlash!='') {
            TTAlert({
                msg: t('invalid link'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        
        locationFlash = getObjectData($('.echoLocation'));
        if(locationFlash!=''){
            $('#FlashLong').val($('#echoLocation').attr('data-lng'));
            $('#FlashLat').val($('#echoLocation').attr('data-lat'));
            $('#label').val($('#echoLocation').attr('data-location'));
        }
        if (textFlash == "" && linkFlash == "" && locationFlash == "" && filename == "" && vpath == "") {
            TTAlert({
                msg: t('invalid data'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        var FlashLong = $('#FlashLong').val();
        var FlashLat = $('#FlashLat').val();
        var label = $('#label').val();
        if ((FlashLong == "" || FlashLat == "" || label == "") && locationFlash!='' ) {
            TTAlert({
                msg: t('invalid location'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }

        var ReplyTo = $('#ReplyTo').val();
        var city = $('#city').val();
        var cc = $('#cc').val();
        var loc_id = $('#loc_id').val();
        var rtype = $('#rtype').val();

        
        var data = {
            FlashText: textFlash,
            flash_link: linkFlash,
            flash_location: locationFlash,
            ReplyTo: ReplyTo,
            FlashLong: FlashLong,
            FlashLat: FlashLat,
            vpath: vpath,
            city: city,
            cc: cc,
            loc_id: loc_id,
            rtype: rtype,
            label: label,
            filename: filename};
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/flash_post.php'),
            data: data,
            type: 'post',
            success: function (response) {
                var Jresponse;
                try {
                    Jresponse = $.parseJSON(response);
                } catch (Ex) {
                    $('.upload-overlay-loading-fix').hide();
                    TTAlert({
                        msg: t("Couldn't Process Request. Please try again later."),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }

                if (Jresponse.status != 'ok') {
                    $('.upload-overlay-loading-fix').hide();
                    if (Jresponse.type == 'session') {
                        TTAlert({
                            msg: t('Please login to complete this task.'),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                    } else if (Jresponse.type == 'save') {
                        TTAlert({
                            msg: t("Couldn't add the echo. Please try again later."),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                    }
                    return;
                }

                var cur_flashes = parseInt($('#n_flashes').text());
                $('#n_flashes').html(cur_flashes + 1);
                document.location.href= ReturnLink('/echoes');
                /*$('.echoesContent').load(ReturnLink('/parts/flash_list.php'), function () {
                    $('.echoesContent .anEcho').first().click();
                    refreshFlashData();
                });*/
                $('.toshow').html('');
                $('.echoText').val('');
                $('.echoLink').val('');
                $('.echoLink').blur();
                $('.echoLocation').val('');
                $('.echoLocation').blur();
                $('#FlashLong').val('');
                $('#FlashLat').val('');
                $('#filename').val('');
                $('#vpath').val('');
                $('.ImageStan_flash').html('');
                $('.flashRemaining').html(FLASH_MAX_LENGTH);
                if ($('.flashType.active').attr('id') != 'flashPhoto') {
                    $('.flashType.active').click();
                } else {
                    $('.flashType').removeClass('active');
                    $('#flashText').click();
                }
                $('.upload-overlay-loading-fix').hide();
            }
        });
    });

    // anEcho onclick
    $(document).on('click', ".anEcho", function () {
        if ($(this).length > 0) {
            var $this = $(this);
            var pos = $this.offset().top - $this.parent().offset().top;
            var dataval = $this.attr('data-val');

            $(".social_data_all").attr('data-id', dataval);
            initLikes($(".social_data_all"));
            initComments($(".social_data_all"));
            initReechoes($(".social_data_all"));
            $('.echoe_report_top_button').attr('data-id', dataval);
            jscrollpane_apiFE.scrollToY(pos, true);
            $(".anEcho").removeClass('selected');
            $this.addClass('selected');
        }
    });
    $('#likes').parent().click();
    var input = document.getElementById('echoLocation');
    var searchBox = new google.maps.places.SearchBox(input);

    // Listen for the event fired when the user selects an item from the
    // pick list. Retrieve the matching places for that item.
    google.maps.event.addListener(searchBox, 'places_changed', function () {
        var places = searchBox.getPlaces();
        place = places[0];
        $('#echoLocation').attr('data-location', place.formatted_address);
        $('#echoLocation').attr('data-lat', place.geometry.location.lat());
        $('#echoLocation').attr('data-lng', place.geometry.location.lng());
    });
    $(document).on('click', ".flashType", function () {
        var $this = $(this);        
        var data_value = parseInt($this.attr('data-value'));        
        switch (data_value) {
            case 0:
                break;
            case ECHO_TYPE_TEXT:
                $('.echoText').show();
                $('.toshowCont').show();
                $('.flashRemaining').show();
                $('.echoText').blur();
                break;
            case ECHO_TYPE_LINK:
                $('.echoLink').show();
                $('.echoLink').blur();
                break;
            case ECHO_TYPE_LOCATION:
                $('.echoLocation').show();
                $('.echoLocation').blur();
                break;
            default:
                $('.echoText').show();
                $('.toshowCont').show();
                $('.flashRemaining').show();
                $('.echoText').blur();
                break;
        }
    });
    $(document).on('click', "#loadmore", function () {
        getEchoesDataRelated(0);
    });
    $(document).on('click', ".removeEcho", function () {
        var current = $(this);
        var currentEcho = current.closest(".anEcho");

        var theId = currentEcho.attr('data-val');
        $.ajax({
            url: ReturnLink('/ajax/ajax_remove_echo.php'),
            data: {id: parseInt(theId)},
            type: 'post',
            success: function (data) {
                if (data.trim() == '1') {
                    if (currentEcho.siblings.length > 0) {
                        if (currentEcho.prev().length > 0) {
                            currentEcho.prev().click();
                        }
                        else if (currentEcho.next().length > 0) {
                            currentEcho.next().click();
                        }
                    } else {
                        $('#commentDiv[data-id="' + theId + '"]').hide();
                    }
                    currentEcho.remove();
                    getEchoesDataRelated(1);
                }
            }
        });
    });

    $('.echoesContent .anEcho').first().click();
    refreshFlashData();
});
function resetAllItems() {
    $('.echoLocation').hide();
    $('.echoLink').hide();
    $('.echoText').hide();
    $('.toshowCont').hide();
    $('.echoText').val('');
    $('.echoLink').val('');
    $('#FlashLong').val('');
    $('#FlashLat').val('');
    $('.echoLocation').val('');
    $('.toshowCont .toshow').html('');
    $('#echoLocation').attr('data-location', '');
    $('#echoLocation').attr('data-lat', '');
    $('#echoLocation').attr('data-lng', '');
}
function updateImage(str, pic_link, pathto, _type) {
    if (_type == "flashPhoto") {
        $('#filename').val(pic_link);
        $('#vpath').val(pathto);
        $('.ImageStan_flash').html('<div class="clsimg mediabuttons" data-title="remove"></div>'+str);
        $('.ImageStan_flash').each(function (index, element) {
            var $this=$(this);
            $this.mouseover(function(){
                $(this).find('.clsimg.mediabuttons').show();
            });
            $this.mouseout(function(){
                $(this).find('.clsimg.mediabuttons').hide();
            });
        });
        $(".mediabuttons").mouseover(function(){
            var $this=$(this).closest('.ImageStan_flash');
            var posxx=$this.offset().left-$('.flashBlock1').offset().left-184;
            var posyy=$this.offset().top-$('.flashBlock1').offset().top-21;
            $('.evContainer2Over .ProfileHeaderOverin').html('remove');
            $('.evContainer2Over').css('left',posxx+'px');
            $('.evContainer2Over').css('top',posyy+'px');
            $('.evContainer2Over').stop().show();
        });
        $(".mediabuttons").mouseout(function(){
            $('.evContainer2Over').hide();
        });
    }
    closeFancyBox();
}
function refreshFlashData() {
    $(".echoImg_link").fancybox({
            helpers : {
                overlay : {closeClick:true}
            }
        });
    $('.echoe_googlemap').each(function (index, element) {
        var $googlemap_location = $(this).find('.googlemap_location');
        var $id_location = $googlemap_location.attr('id');
        var image = new google.maps.MarkerImage(ReturnLink("/images/map_marker.png"));
        var mapOptions = {
            center: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
            zoom: 8,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: myStyles
        };

        var map = new google.maps.Map(document.getElementById($id_location), mapOptions);

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
            map: map,
            icon: image,
            title: ''
        });
    });
}
function getEchoesDataRelated(oneobject) {
    $('.upload-overlay-loading-fix').show();
    LoadedPages++;
    var urls = $('#loadmore').attr('rel')+"&oneobject="+oneobject;    
    $.ajax({
        url: urls,
        //url: ReturnLink('/parts/flash_list.php?page=' + LoadedPages),
        success: function (data) {
            $('.echoesContent').append(data);
            refreshFlashData();
            if(oneobject==0){
                setTimeout(function () {
                    jscrollpane_apiFE.scrollToBottom(true);
                }, 500);
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });
}