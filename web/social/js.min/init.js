// JavaScript Document

var flagme = 1;
var signflag = 0;
var forgetflag = 0;
var profileFlag = 0;
var AC_FL_RunContent = 1;
var bottom1_h, bottom2_h, bottomt_h;//the size of the footer depends on the logged state

var columnReadyCounter = 0;
var $allContentBoxes = $(".content-box"),
        $allTabs = $(".tabs li a"),
        $el, $colOne, $colTwo, $colThree,
        hrefSelector = "",
        speedOne = 1000,
        speedTwo = 2000,
        speedThree = 1500;
var which_tab = -1;
var n_tabs = 6;
var slideT;
var SearchCateFadeoutTimeout;
var signInFromFooter=false;



// This is the callback function for the "hiding" animations
// it gets called for each animation (since we don't know which is slowest)
// the third time it's called, then it resets the column positions
function ifReadyThenReset() {
    columnReadyCounter++;
    if (columnReadyCounter == 3) {
        //$(".col").not(".current .col").css("top", 298);
        $(".col").not(".current .col").css({top: '298px'})
        columnReadyCounter = 0;
    }
}
;
function upperSlideTab()
{
    slideT = setInterval(function () {
        if (flagme == 0) {
            which_tab++;
            if (which_tab == n_tabs)
                which_tab = 0;
            $('#tabs a').eq(which_tab).click();
        }
    }, 5500);
}
function fixChatBar() {
    var top = $(document).scrollTop();
    var pos = $('#BottomContainer').offset();
    var chat_not_fixed_at = pos.top - $(window).height();
}

function getDocHeight() {
    var D = document;
    return Math.max(
            Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
            Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
            Math.max(D.body.clientHeight, D.documentElement.clientHeight)
            );
}

function FooterGetHeight() {
    bottomt_h = $('#BottomContainer2').is(':visible') ? bottom1_h + bottom2_h : bottom1_h;

    return bottomt_h;
}

function FixFooter() {
    FooterGetHeight();

    var _bottom2_h = $('#BottomContainer2').is(':visible') ? bottom2_h : 0;

    var body_height = getDocHeight();
    var wh = $(window).height();
    if ($.browser.msie)
        wh += 4;

    if ($('.BottomFooterButton').hasClass('BottomFooterButtonCollapse')) {
        if($('.footer_style_blank').attr('data-case')=="1"){
            $('.footer_style_blank').css('height', '230px');
            //$('#chatContainer').css('bottom', '230px');            
        }else{
            $('.footer_style_blank').css('height', '208px');
        }        
    } else {
        if($('.footer_style_blank').attr('data-case')=="1"){
            $('.footer_style_blank').css('height', '108px');
            //$('#chatContainer').css('bottom', '60px');
        }else{
            $('.footer_style_blank').css('height', '60px');
        }        
    }
    if ($(this).scrollTop() > 150) {
        $('#BackToTop').show();
    } else {
        $('#BackToTop').hide();
    }
}


function initSlotMachineTabs() {
    $(".current").show();
    $("#slot-machine-tabs").delegate(".tabs a", "click", function (event) {

        which_tab = $(this).parent().index();
        event.stopImmediatePropagation();
        $el = $(this);

        if ((!$el.hasClass("current")) && ($(":animated").length == 0)) {

            // current tab correctly set
            $(".tabs li a").removeClass("current");
            $el.addClass("current");

            // optional... random speeds each time.
            speedOne = /*Math.floor(Math.random()*1000) +*/ 500;
            speedTwo = /*Math.floor(Math.random()*1000) +*/ 750;
            speedThree = /*Math.floor(Math.random()*1000) +*/ 1000;

            // each column is animated upwards to hide
            // kind of annoyingly redudundant code
            $colOne = $(".box-wrapper .current .col-one");
            $colOne.animate({
                "top": -$colOne.height()
            }, speedOne);

            $colTwo = $(".box-wrapper .current .col-two");
            $colTwo.animate({
                "top": -320//-$colTwo.height()
            }, speedTwo);

            $colThree = $(".box-wrapper .current .col-three");
            $colThree.animate({
                "top": -281//-$colThree.height()
            }, speedThree);

            // new content box is marked as current
            $(".content-box").removeClass("current");
            if ($el.hasClass('tabsone'))
                hrefSelector = 'one';
            else if ($el.hasClass('tabstwo'))
                hrefSelector = 'two';
            else if ($el.hasClass('tabsthree'))
                hrefSelector = 'three';
            else if ($el.hasClass('tabsfour'))
                hrefSelector = 'four';
            else if ($el.hasClass('tabsfive'))
                hrefSelector = 'five';
            else if ($el.hasClass('tabssix'))
                hrefSelector = 'six';
            hrefSelector = '#' + hrefSelector;
            $(hrefSelector).addClass("current");

            // columns from new content area are moved up from the bottom
            // also annoying redundant and triple callback seems weird
            $(".box-wrapper .current .col-one").animate({
                "top": 0
            }, speedOne, function () {
                ifReadyThenReset();
            });

            $(".box-wrapper .current .col-two").animate({
                "top": 0
            }, speedTwo, function () {
                ifReadyThenReset();
            });

            $(".box-wrapper .current .col-three").animate({
                "top": 0
            }, speedThree, function () {
                ifReadyThenReset();
            });

            clearInterval(slideT);
            upperSlideTab();
        }

    });
}

function appendfancy(id, data) {
    if (data == '') {
        SaveError(id, t('Save Error. Please try again later.'));
        return false;
    }
    $('#' + id + ' input[name=vid]').val(data);
    $("#" + id + "fancy").attr("href", ReturnLink("/ajax/showthumb.php?videoid=" + data + "&fileid=" + id));

    $("#" + id + "fancy").click();
}

function selectThumb(FileID, ImageURL) {
    $('#' + FileID + ' .stanleft').html('<img src="' + AbsolutePath + "/" + ImageURL + '" width="86" height="48">');
    CloseFancyBox();
    $('#' + FileID + ' .closeclose').click();
}

function selectDirectThumb(FileID, data) {
    if (data == '') {
        SaveError(FileID, t('Save Error. Please try again later.'));
        return false;
    }
    var returnData = data.split('|');
    var ImageURL = returnData[0];
    var media_id = returnData[1];
    $('#' + FileID + ' input[name=vid]').val(media_id);
    //$('#'+FileID+' .stanleft').html('<img src="'+AbsolutePath + "/" + ImageURL+'" width="86" height="48">');
    //$('#'+FileID+' .closeclose').click();
}

function CloseFancyBox() {
    $('#fancybox-close').click();
    $('.fancybox-close').click();
}

function tbTest_focus(e, o) {
    if (o.firstTime) {
        return
    }
    ;
    o.firstTime = true;
    o.value = "";
}

function deletefile(IdFile) {
    /*var FileValue = $('#'+IdFile).find('.FileNameDiv').text();
     var FileId = $('#'+IdFile).find('.FileIdDiv').text();
     $('.deleteddiv').load('deleteuploaded.php?file='+FileValue+'&S='+$("#S").val());*/
    var FileValue = $('input[name=vName]', $('#' + IdFile)).val();
    var FilePath = $('input[name=vPath]', $('#' + IdFile)).val();
    $.ajax({
        url: ReturnLink('/deleteuploaded.php'),
        type: 'post',
        data: {
            fname: FileValue,
            dir: FilePath
        },
        success: function (ret) {

        }
    });
}
function deleteMediaPending(FileValue, FilePath) {
    $.ajax({
        url: ReturnLink('/deleteuploaded.php'),
        type: 'post',
        data: {
            fname: FileValue,
            dir: FilePath
        },
        success: function (ret) {

        }
    });
}

function goToLink() {
    window.location.href = $(this).attr('data-href');
}

function bindToVideoThumbs() {
    //$('.iconinslider').unbind('click').click(goToLink);
    $('.IndexRecentLink').unbind('click');
}
function addValueInput(obj) {
    if ($(obj).attr('value') == '')
        $(obj).attr('value', $(obj).attr('data-value'));
}
function removeValueInput(obj) {
    if ($(obj).attr('value') == $(obj).attr('data-value'))
        $(obj).attr('value', '');
}

function unbindToVideoThumbs() {
    //$('.iconinslider').unbind('click');
    $('.IndexRecentLink').bind('click', function (event) {
        event.preventDefault();
        evet.stopImmediatePropagation();
    });
}

function reintialiseslider() {

    var $scroller = $('.sliderBar');
    var $scrollbar = $('.sliderWrap');
    var $content = $('.items', $('.sliderGallery'));
    var $content_holder = $('.sliderGallery');
    $scroller.disableSelection();

    /////////////////////////////////
    //scrollbar hammer
    var ev_old = 0;
    var dir_old = 0;
    var dragging_element = false;
    function positionHScroller(percentege) {
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
    var dragging_element_2 = false;
    /*var hammer_scroll_content = Hammer($content_holder.get(0), {
     drag_vertical: false,
     drag_horizontal: true,
     drag_min_distance: 5,
     prevent_default: true,
     css_hacks: false,
     transform: false,
     tap: false,
     tap_double: false,
     hold: false
     }).on("dragstart", function(ev) {
     if (inBounds(ev.gesture.center, $content)) {
     unbindToVideoThumbs();
     dragging_element_2 = true;
     $('body').disableSelection();
     } else {
     dragging_element_2 = false;
     }
     }).on("dragend", function(ev) {
     setTimeout(function() {
     bindToVideoThumbs()
     }, 300);
     dragging_element_2 = false;
     ev_old = 0;
     $('body').enableSelection();
     });*/
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
            $('body').disableSelection();
        } else {
            dragging_element = false;
        }
    }).on("dragend", function (ev) {
        dragging_element = false;
        ev_old = 0;
        $('body').enableSelection();
    });

    var hammer_body = Hammer($('body').get(0), {
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

            //if( RootElement.width() >= $content.width() ) cur = min;

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

            //if( RootElement.width() >= $content.width() ) cur = min;
            var per = cur / min;
            //console.log( $content_holder.outerWidth(true) / $content.outerWidth(true) );
            //console.log( $scrollbar.outerWidth(true) / $scroller.outerWidth(true) );
            //content_per = per * ( $content_holder.outerWidth(true) / $content.outerWidth(true) ) * ($scrollbar.outerWidth(true) / $scroller.outerWidth(true));
            $scroller.css({position: 'absolute', left: per * ($scrollbar.outerWidth(true) - $scroller.outerWidth(true)) + 'px'});
            $content.css({'left': cur + 'px'});//per * ( $content_holder.width() - $content.width() )});

        }
    });

    /*.on('drag',function(ev){
     if( !dragging_element_2 ) return ;
     var gesture = ev.gesture;
     var cur = $scroller.css('left');
     if( !inBounds2( ev.gesture.center, $content_holder) ) return ;
     cur = parseInt( cur.substring(0, cur.length - 2) );
     if( isNaN(cur) ) cur = 0;
     var diff;
     if(gesture.direction == 'left'){
     if(dir_old != 1) ev_old = 0;
     dir_old = 1;
     diff = gesture.distance - ev_old;
     cur += diff;
     ev_old = gesture.distance;
     }
     if(gesture.direction == 'right') {
     if(dir_old != -1) ev_old = 0;
     dir_old = -1;
     diff = gesture.distance - ev_old;
     cur -= diff;
     ev_old = gesture.distance;
     }
     //horizontal
     var min = 0;
     var max = $scrollbar.outerWidth(true) - $scroller.outerWidth(true) - 2;
     
     if( cur < min) cur = min;
     if( cur >  max ) cur = max;
     
     //if( RootElement.width() >= $content.width() ) cur = min;
     var per = 0;
     per = cur / max;
     //console.log( $content_holder.outerWidth(true) / $content.outerWidth(true) );
     //console.log( $scrollbar.outerWidth(true) / $scroller.outerWidth(true) );
     per = per * ( $content_holder.outerWidth(true) / $content.outerWidth(true) ) / ($scrollbar.outerWidth(true) / $scroller.outerWidth(true));
     $scroller.css({'position': 'absolute', 'left': cur + 'px'});
     $content.css({'left': per * ( $content_holder.width() - $content.width() )});
     });
     */
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
        });//.css({'left': per * ( $content_holder.width() - $content.width() ) });
    });
}
function HideHere() {
    $('#SignInDiv').fadeOut();
    $('#ForgetPassDiv').fadeOut();
    signflag = 0;
}

/**
 * the timeout handle for the top profile div
 * @type timeout handle
 */
var profileFadeoutTimeout;

/**
 * the timeout handle for the signin fadeout
 * @type type
 */
var SignInTO;

/**
 * called by the iframe when its been clicked
 * @returns {undefined}
 */
function iframeClicked() {
    clearTimeout(SignInTO);
    $('#SignInDiv').unbind('mouseleave');
}
function initResetIconPost(obj){
    obj.removeClass('privacy_icon1');
    obj.removeClass('privacy_icon2');
    obj.removeClass('privacy_icon3');
    obj.removeClass('privacy_icon4');
    obj.removeClass('privacy_icon5');
    obj.removeClass('privacy_icon6');
}
function updateImagePOST(str, pic_link, pathto, _type, isvideo) {
    if (_type == "postMediaPV" || _type == "CHpostMediaPV") {
        $('#filename').val(pic_link);
        $('#filename').attr('data-isvideo',isvideo);
        $('#vpath').val(pathto);
        $('.ImageStan_post').html('<div class="clsimg mediabuttons" data-title="remove"></div>'+str);
        $('.ImageStan_post').each(function (index, element) {
            var $this=$(this);
            $this.mouseover(function(){
                $(this).find('.clsimg.mediabuttons').show();
            });
            $this.mouseout(function(){
                $(this).find('.clsimg.mediabuttons').hide();
            });
        });
        $(".mediabuttons").mouseover(function(){
            var $this=$(this).closest('.ImageStan_post');
            var posxx=$this.offset().left-$('.headpost_container').offset().left-184;
            var posyy=$this.offset().top-$('.headpost_container').offset().top-21;
            $('.headpost_container .evContainer2Over .ProfileHeaderOverin').html('remove');
            $('.headpost_container .evContainer2Over').css('left',posxx+'px');
            $('.headpost_container .evContainer2Over').css('top',posyy+'px');
            $('.headpost_container .evContainer2Over').stop().show();
        });
        $(".mediabuttons").mouseout(function(){
            $('.headpost_container .evContainer2Over').hide();
        });
    }
    closeFancyBox();
}
$(document).ready(function () {
    if($(".showOnMapSH").length>0){
        var pagedimensions = parent.window.returnIframeDimensions();
        $(".showOnMapSH").fancybox({
            width: pagedimensions[0],
            height: pagedimensions[1],
            closeBtn: true,
            autoSize: false,
            autoScale: true,
            transitionIn: 'elastic',
            transitionOut: 'fadeOut',
            type: 'iframe',
            padding: 0,
            margin: 0,
            scrolling: 'no',
            helpers : {
                overlay : {closeClick:true}
            }
        });
    }
    if($('.privacy_picker_post').length>0){
        addmoreusersautocomplete_custom_journal($('.privacy_picker_post #addmoretext_privacy'));
    }
    if ($('#postMediaPV').length > 0) {
        InitChannelUploaderHome('postMediaPV', 'postAddNewID', 10240,0);
    }
    if ($('#CHpostMediaPV').length > 0) {
        InitChannelUploaderHome('CHpostMediaPV', 'postAddNewID', 10240,0);
    }
    $(document).on('click', ".postAddNew .clsimg", function () {
        $('#filename').val('');
        $('#vpath').val('');
        $('.ImageStan_post').html('');
        $('#filename').attr('data-isvideo','0');
        $('.headpost_container .evContainer2Over').hide();
    });
    $(document).on('click', ".postSubmit", function () {
        var textPost = '';
        var linkPost = '';
        var locationPost = '';
        var filename = $('#filename').val();
        var data_isvideo = $('#filename').attr('data-isvideo');
        var vpath = $('#vpath').val();
        
        var type_txt = '';
        
        textPost = getObjectData($('.postIPText'));        
        linkPost = getObjectData($('.postLink'));        
        if (!ValidURL(linkPost) && linkPost!='') {
            TTAlert({
                msg: t('invalid link'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        
        locationPost = $('.postLocation').attr('data-location');
        if(locationPost!=''){
            $('#postLong').val($('.postLocation').attr('data-lng'));
            $('#postLat').val($('.postLocation').attr('data-lat'));
        }
        if (textPost == "" && linkPost == "" && locationPost == "" && filename == "" && vpath == "") {
            TTAlert({
                msg: t('invalid data'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        var postLong = $('#postLong').val();
        var postLat = $('#postLat').val();
        if ((postLong == "" || postLat == "") && locationPost!='' ) {
            TTAlert({
                msg: t('invalid location'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        var curob=$(this).parent().parent();
        if($(".privacy_select_post").length>0){
            var privacyValue=parseInt($(".privacy_select_post").val());
        }else{
            var privacyValue=USER_PRIVACY_PUBLIC;
        }        
        var privacyArray=new Array();
        if(privacyValue==USER_PRIVACY_SELECTED){
            curob.find('.peoplecontainer .emailcontainer .peoplesdata').each(function(){
                var obj=$(this);
                if(obj.attr('id')=="friendsdata"){
                    privacyArray.push( {friends : 1} );
                }else if(obj.attr('id')=="friends_of_friends_data"){
                    privacyArray.push( {friends_of_friends : 1} );
                }else if(obj.attr('id')=="followersdata"){
                    privacyArray.push( {followers : 1} );
                }else if( parseInt( obj.attr('data-id') ) != 0 ){
                    privacyArray.push( {id : obj.attr('data-id') } );
                }
            });
        }
        if( privacyValue==USER_PRIVACY_SELECTED && privacyArray.length==0){
            TTAlert({
                msg: t('Invalid privacy data'),
                type: 'alert',
                btn1: t('ok')
            });
            return;
        }
        TTCallAPI({
            what: '/social/post/add',
            data: {post_text: textPost, post_type: 1,channel_id:channelGlobalID() , privacyValue:privacyValue , privacyArray:privacyArray , longitude:postLong , lattitude:postLat, locationPost:locationPost , linkPost:linkPost , data_isvideo:data_isvideo , filename:filename , data_profile: userGlobalID()},
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
    $(document).on('click', ".postType", function () {
        var $this = $(this);        
        var data_value = parseInt($this.attr('data-value'));        
        switch (data_value) {
            case -1:
                $('.postPRVNew').removeClass('displaynone');
                break;
            case SOCIAL_POST_TYPE_LINK:
                $('.postLink').show();
                $('.postLink').blur();
                break;
            case SOCIAL_POST_TYPE_LOCATION:
                $('.postLocation').show();
                $('.postLocation').blur();
                break;
        }
    });
    $(".privacy_select_post").change(function(){
        var selectval=parseInt($(this).val());
        if(selectval==USER_PRIVACY_SELECTED){
            $(this).parent().find('.privacy_picker_post').removeClass('displaynone');
        }else{
            resetSelectedUsers($(this).parent().find('.addmore input'));
            $(this).parent().find('.uploadinfocheckbox').removeClass('active');
            $(this).parent().find('.addmore input').val('');
            $(this).parent().find('.addmore input').blur();
            $(this).parent().find('.peoplesdata').each(function() {
                var parents=$(this);
                parents.remove();				
            });
            $(this).parent().find('.privacy_picker_post').addClass('displaynone');
        }
        initResetIconPost($('.privacy_post_icon'));
        $('.privacy_post_icon').addClass('privacy_icon'+selectval);
    });
    $(document).on('click', ".uploadinfocheckbox_post", function() {
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
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends of friends")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);                    
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("followers")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                }
            }
        }
    });
    
    if ($("#slot-machine-tabs").length > 0)
        launchSlotMachine();
    if ($(".SearchField #SearchField").length > 0)
        launchSearchHome();
    if ($(".footer_container_all").length > 0)
        launchFooterFN();
    $(document).on('click', ".insideli_switch", function () {
        var $this = $(this).parent();
        TTCallAPI({
            what: 'channel/user_switch_channel',
            data: {id: $this.attr('data-id')},
            callback: function (resp) {
                if (resp.status === 'error') {
                    return;
                }
                document.location.reload();
            }
        });
    });
    // add alert actions
    //click on recent video thumb
    bindToVideoThumbs();

    /*$('.ttmenuclass').mouseover(function () {
        $('.BottomContainer2Over .ProfileHeaderOverin').html($(this).attr('data-title'));
        var posxx = $(this).offset().left - $('#BottomContainer2').offset().left -250;
        var posyy = $(this).offset().top - $('#BottomContainer2').offset().top - 21;
        $('.BottomContainer2Over').css('left', posxx + 'px');
        $('.BottomContainer2Over').css('top', posyy + 'px');
        $('.BottomContainer2Over').stop().show();
    });
    $('.ttmenuclass').mouseout(function () {
        $('.BottomContainer2Over').hide();
    });*/
    $('.BottomFooterButton').mouseover(function () {
        $('.FooterCollaspeExpandOver').css('right', '3px');
        $('.FooterCollaspeExpandOver').css('top', '-29px');
        $('.FooterCollaspeExpandOver').stop().show();
    });
    $('.BottomFooterButton').mouseout(function () {
        $('.FooterCollaspeExpandOver').hide();
    });

    //request invitation
    RequestifyLink();

    ////////////////////////////////////
    //#ThumbViewer, #BottomProfileList
    $("#MostViewedDiv, #sliderContent, .BottomProfileImage").preloader();

    $('#footer_sign_in').click(function () {
        $('#BackToTop').click();
        $('#SignInBtn').click();
        signInFromFooter=true;
    });

    //////////////////////////////////
    //if an image failed to load reset its src
    /*$("img").error(function() {
     if (!$(this).data('retry')) {
     $(this).data('retry', 1)
     }
     var max_retries = 4;
     var retries = parseInt($(this).data('retry'));
     if (retries == max_retries)
     return;
     $(this).data('retry', retries + 1);
     $(this).attr('src', $(this).attr('src') + '&x=' + Math.random());
     });*/

    ///////////////////////////////////
    //the invitations
    $('#IndexInviteSubmit').click(function () {
        var emlstr = getObjectData($('#IndexInviteInput'));
        if (!emailIsValid(emlstr) || emlstr == '') {
            TTAlert({
                msg: t('Please specify valid email address'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }


        $.ajax({
            url: ReturnLink('/ajax/account_invite.php'),
            data: {email: $('#IndexInviteInput').val(), name: ''},
            type: "post",
            success: function (resp) {
                var Jresponse;
                try {
                    Jresponse = $.parseJSON(resp);
                    $('#IndexInviteInput').val('');
                    $('#IndexInviteInput').blur();
                } catch (Ex) {
                    return;
                }

                if (!Jresponse)
                    return;

                if (Jresponse.status == 'ok') {
                    var cur = parseInt($('#invites_left').html());
                    $('#invites_left').html(cur - 1);
                    TTAlert({
                        msg: Jresponse.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    TTAlert({
                        msg: Jresponse.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            }
        });
    });

    $('#IndexInviteInput').keydown(function (event) {
        if (event.keyCode == 13) {
            $('#IndexInviteSubmit').click();
        }
    })

    //////////////////////////
    //the hover over the tuber
    var xOffset = 10;
    var yOffset = 30;
    /*
     //bug in IE10
     $("#TouristTubersList img").hover(function(e){
     this.t = this.title;
     this.title = "";
     var c = (this.t != "") ? "<br/>" + this.t : "";
     $("body").append("<p id='preview'><img src='"+ $(this).attr('src') +"' alt='Image preview' style='width: 100px;height: 100px;'/>"+ c +"</p>");
     $("#preview").css("top",(e.pageY - yOffset) + "px").css("left",(e.pageX + xOffset) + "px").css({
     position:'absolute',
     border: '1px solid #ccc',
     background: '#333',
     'font-size': '12px',
     padding: '5px',
     display: 'none',
     color:'#fff',
     'z-index': 10000,
     'text-align': 'center'
     }).fadeIn("fast");
     },
     function(){
     this.title = this.t;
     $("#preview").remove();
     }).mousemove(function(e){
     $("#preview").css("top",(e.pageY - yOffset) + "px").css("left",(e.pageX + xOffset) + "px");
     });
     */
    //////////////////////////////////////////////////////////////
    
    
//console.log(navigator.userAgent);
    
    if( ! /Android|webOS|iPhone|iPad|iPod|pocket|psp|kindle|avantgo|blazer|midori|Tablet|Palm|maemo|plucker|phone|BlackBerry|symbian|IEMobile|mobile|ZuneWP7|Windows Phone|Opera Mini/i.test(navigator.userAgent) ){
    $("#TouristTubersList").on('mouseover', 'img', function (e) {
        if (!$(this).data('title')) {
            $(this).data('title', $(this).attr('title'));
            $(this).attr('title', '');
        }
        var title = $(this).data('title');
        var $href = $(this).parent().attr('href');
        var c = (title !== "") ? "<br/>" + title : "";
        $("body").append('<div id="preview"><a href="' + $href + '" title="' + title + '"><img src="' + $(this).attr('data-src') + '" alt="Image preview" style="width: 100px;height: 100px;"/></a>' + c + '</div>');
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
    
}
    
    

    /** fix curved corners for < IE8 **/
    //$('.sliderWrap').corner('round 10px');
    //$('#TopSearchArea').corner('round 10px');
    //$('.sliderWrap').corner();
    //$('#TopSearchArea').corner();
    var BV = parseInt($.browser.version);
    if ($.browser.msie && (BV <= 8)) {
        var CurvySettings = {
            tl: {radius: 10},
            tr: {radius: 10},
            bl: {radius: 10},
            br: {radius: 10},
            antiAlias: true
        }
        try {
            if ($('#slidercontainer .sliderWrap').length != 0)
                curvyCorners(CurvySettings, '#slidercontainer .sliderWrap');
            if ($('#TopSearchArea').length != 0)
                curvyCorners(CurvySettings, '#TopSearchArea');
        } catch (e) {

        }

    }
    ////////////////////////////////////

    $('#BackToTop').click(function (event) {
        $('html, body').animate({scrollTop: 0}, 'slow');
        event.stopImmediatePropagation();
        event.preventDefault();
    });

    $('#sort-by li').click(function () {
        $(this).children('a').click();
    });

    $('#filterlist').click(function () {
        $('.listing').animate({
            height: 'toggle'
        }, 500);
    });

    $('.listing li a').each(function () {
        $(this).parent('li').mouseover(function () {
            $(this).addClass('over');
        });
        $(this).parent('li').mouseout(function () {
            $(this).removeClass('over');
        });
        $(this).click(function () {
            var $this = $(this);
            $('.listing').animate({
                height: 'toggle'
            }, 250, function () {
                $('#filtervalue').html($this.text());
            });
        });
    });

    $('.imgover').each(function () {
        $(this).mouseover(function () {
            $(this).attr("src", $(this).attr("src").replace("_up", "_over"));
            return false;
        });
        $(this).mouseout(function () {
            if ($(this).data('active'))
                return;
            $(this).attr("src", $(this).attr("src").replace("_over", "_up"));
            return false;
        });
    });

    $('#UploadOneVideo').css({
        display: 'none'
    })
    $('#UploadOneVideo1').css({
        display: 'none'
    })

    $('#BrowseFile').click(function () {
        $('#UploadOneVideo').click();
    });

    $('#MoreVideos').click(function () {
        var Rel = decodeURL($(this).attr('rel'));
        $('#AllVideoList').load(Rel);
    });
    $('#MorePhotos').click(function () {
        var Rel = decodeURL($(this).attr('rel'));
        $('#AllPhotoList').load(Rel);
    });

    $('#MoreVideosInside,#MorePhotosInside,#MoreAlbumsInside').click(function () {
        var RelInside = decodeURL($(this).attr('rel'));
        var anim_speed = 500;
        $.ajax({
            url: RelInside,
            success: function (ret) {
                $('#BottomProfileList').fadeOut(anim_speed, function () {
                    $('#BottomProfileList').html(ret).fadeIn(anim_speed, function () {

                    });
                    setTimeout(function () {
                        MediaPageFix()
                    }, anim_speed / 5);
                });
            }
        });
    });
    $('#ForgetPassDiv').hide();

    var LiArray = new Array('image1', 'image2', 'image3', 'image4');
    var imageclicked = 0;

    $('#ArrowMiddleTop').click(function () {
        if (flagme == 0) {
            flagme = 1;
            topvalue = '-277px';
            $('.SlotSep').switchClass('SlotSep', 'SlotSepVisited', 200);
            $('#MiddleTop').animate({
                'margin-top': topvalue
            }, 500, function () {
                // Animation complete.
                $('.onclose').hide();
                $('#ArrowMiddleTop').removeClass('ArrowMiddleTopExpanded');
                $('#tabsmain').animate({
                    left: '180px'
                }, 500);
                $(window).resize();
            });
        } else {
            if (!$(".box-wrapper .content-box").length) {
                $('.upload-overlay-loading-fix').show();
                $.post(ReturnLink('/ajax/ajax_slot_machine.php'), {type: 'load'}, function (data) {
                    if (data !== '') {
                        $(".box-wrapper").html(data);
                        $('.upload-overlay-loading-fix').hide();
                        flagme = 0;
                        topvalue = '-26px';
                        $('.onclose').show();
                        $('#MiddleTop').animate({
                            'margin-top': topvalue
                        }, 500, function () {
                            // Animation complete.
                            $('#ArrowMiddleTop').addClass('ArrowMiddleTopExpanded');
                            $('#tabsmain').animate({
                                left: '50px'
                            }, 500);
                            $('.SlotSepVisited').switchClass('SlotSepVisited', 'SlotSep', 600);
                            $(window).resize();
                        });
                    }
                });
            } else {
                flagme = 0;
                topvalue = '-26px';
                $('.onclose').show();
                $('#MiddleTop').animate({
                    'margin-top': topvalue
                }, 500, function () {
                    // Animation complete.
                    $('#ArrowMiddleTop').addClass('ArrowMiddleTopExpanded');
                    $('#tabsmain').animate({
                        left: '50px'
                    }, 500);
                    $('.SlotSepVisited').switchClass('SlotSepVisited', 'SlotSep', 600);
                    $(window).resize();
                });
            }
        }
    });
//

//
    $('#SignOutBtn').click(function () {
        //window.location = ReturnLink('/logout');
        //return ;
        if (profileFlag == 0) {
            $('#TopProfileDiv').fadeIn();
            profileFlag = 1;
            profileFadeoutTimeout = setTimeout(function () {
                $('#TopProfileDiv').fadeOut();
                profileFlag = 0;
            }, 1500);

            $('#TopProfileDiv').unbind('mouseenter mouseleave').hover(function () {
                clearTimeout(profileFadeoutTimeout);
                $('#TopProfileDiv').stop(true, true);
                $('#TopProfileDiv').fadeIn();
                profileFlag = 1;
            }, function () {
                profileFadeoutTimeout = setTimeout(function () {
                    $('#TopProfileDiv').fadeOut();
                    profileFlag = 0;
                }, 500);
            });
            $('#TopProfileDiv').mouseenter();
        } else {
            $('#TopProfileDiv').fadeOut();
            profileFlag = 0;
        }
    });

    $('#SignInBtn').click(function (event){
        event.stopImmediatePropagation();
        event.preventDefault();
        if (signflag == 0) {
            $('#SignInDiv').fadeIn(); 
            signflag = 1;           
        }
    });
    $('#SignInDiv').mouseenter(function (event){
        signflag = 0;
    });
    $('#SignInDiv').mouseleave(function (event){
        signflag = 1;
    });

    $("body").on("swipe",function(){
        if (signflag == 1) $('#SignInDiv').fadeOut();
        signflag = 0;
    });

    //in case the body is clicked and the SignInDiv is shown hide it
    $('body').click(function () {
        if(signInFromFooter){
            signInFromFooter=false;
            return false;
        }
        if (signflag == 1) $('#SignInDiv').fadeOut();
        signflag = 0;
    });

    /////////////////////////////////////////////////////////
    //most recently added video effect
    var selector = '#sliderContent div.imageitem';
    var anim_speed = 300;
    $(selector).mouseenter(function () {

        var $jobj = $(this);
        var $box = $('a.iconinslider', $jobj);

        var $data = $('.VideoViewPane', $box);
        $data.width($(this).width() - 16);//.height(0);
        var $img = $box.children('.VideoViewIconLarge');

        $data.show();
        $data.stop(true);
        $img.stop(true);
        $box.stop(true).css({
            border: '8px solid #eac31a',
            width: ($box.parent().width() - 16) + 'px',
            height: ($box.parent().height() - 16) + 'px'
        }).animate({
            opacity: 1
        }, anim_speed / 2, function () {
            $data.stop(true).animate({
                height: 30
            }, anim_speed);
            $img.stop(true).animate({
                top: 38
            }, anim_speed); //display : 'block'
        });

    }).mouseleave(function () {

        var $jobj = $(this);

        var $box = $jobj.children('a.iconinslider');

        var $data = $box.children('.VideoViewPane');
        var $img = $box.children('.VideoViewIconLarge');
        $box.stop(true);
        $img.stop(true).animate({
            top: -38
        }, anim_speed);
        $data.stop(true).animate({
            height: 0
        }, anim_speed, function () {
            $data.hide();
            $box.stop(true).animate({
                opacity: 0
            }, anim_speed);
        });

    });

    //end of most viewed video effect
    /////////////////////////////////////////////////////

    ////////////////////////////////////
    //most viewed video and photo effect on the index page
    $('#MostViewedDiv').on("mouseenter", '.videolistimage', function (event) {
        var $box = $(this).find('.insidevideolistimage');
        var $img = $(this).find('.VideoViewIcon');
        $img.stop(true).animate({
            top: 25
        }, anim_speed);
        $box.css({
            width: (136 - 4),
            height: (76 - 4),
            border: '2px solid #eac31a'
        }).stop(true).fadeIn(anim_speed, function () {
            $box.css({
                'opacity': 1
            });
        });
        var $rating = $(this).find('.insidevideo');
        $rating.stop(true).fadeIn(anim_speed, function () {
            $rating.css({
                'opacity': 1
            });
        })
    }).on("mouseleave", '.videolistimage', function (event) {
        var $box = $(this).find('.insidevideolistimage');
        var $img = $(this).find('.VideoViewIcon');
        $img.stop(true).animate({
            top: -25
        }, anim_speed);
        $box.stop(true).fadeOut(anim_speed, function () {

        });
        var $rating = $(this).find('.insidevideo');
        $rating.stop(true).fadeOut(anim_speed, function () {

        });
    });
    //end of most viewed video and photo effect
    //////////////////////////////////

    ////////////////////////////////////
    //most viewed video and photo effect on the live page
    $('#cameraList').on("mouseenter", '.camlistimage', function (event) {
        var $box = $(this).find('.insidevideolistimage');
        var $img = $(this).find('.CamViewIcon');
        $img.stop(true).animate({
            top: 40
        }, anim_speed);
        $box.css({
            width: (136 - 4),
            height: (102 - 4),
            border: '2px solid #eac31a'
        }).stop(true).fadeIn(anim_speed, function () {
            $box.css({
                'opacity': 1
            });
        });
        var $rating = $(this).find('.insidevideoBarRate');
        $rating.stop(true).fadeIn(anim_speed, function () {
            $rating.css({
                'opacity': 1
            });
        });
        var $duration = $(this).find('.insidevideolistduration');
        $duration.stop(true).fadeIn(anim_speed, function () {
            $duration.css({
                'opacity': 1
            });
        });

    }).on("mouseleave", '.camlistimage', function (event) {
        var $box = $(this).find('.insidevideolistimage');
        var $img = $(this).find('.CamViewIcon');
        $img.stop(true).animate({
            top: -27
        }, anim_speed);
        $box.stop(true).fadeOut(anim_speed, function () {
            //$box.css({display : 'none', opacity : 0});
        });
        var $rating = $(this).find('.insidevideoBarRate');
        $rating.stop(true).fadeOut(anim_speed, function () {
            //$rating.css({display : 'none',opacity : 0});
        });
        var $duration = $(this).find('.insidevideolistduration');
        $duration.stop(true).fadeOut(anim_speed, function () {
            //$duration.css({display : 'none',opacity : 0});
        });
    });
    //end of most viewed video and photo effect
    //////////////////////////////////
    ////////////////////////////////////
    //most viewed video and photo effect morevideoinside
    $('#BottomProfileList').on("mouseenter", '.BottomProfileImage', function (event) {
        var $box = $(this).find('.insidevideolistimage_more');
        var $img = $(this).find('.VideoViewIcon_more');
        $img.stop(true).animate({
            top: 18
        }, anim_speed);
        $box.css({
            left: 0,
            width: (120 - 4),
            height: (67 - 4),
            border: '2px solid #eac31a'
        }).stop(true).fadeIn(anim_speed, function () {
            $box.css({
                'opacity': 1
            });
        });
        var $rating = $(this).find('.insidevideomore');
        $rating.stop(true).fadeIn(anim_speed, function () {
            $rating.css({
                'opacity': 1
            });
        });
    }).on("mouseleave", '.BottomProfileImage', function (event) {
        var $box = $(this).find('.insidevideolistimage_more');
        var $img = $(this).find('.VideoViewIcon_more');
        $img.stop(true).animate({
            top: -25
        }, anim_speed);
        $box.stop(true).fadeOut(anim_speed, function () {
            //$box.css({display : 'none', opacity : 0});
        });
        var $rating = $(this).find('.insidevideomore');
        $rating.stop(true).fadeOut(anim_speed, function () {
            //$rating.css({display : 'none',opacity : 0});
        });
    }).on('click', '.BottomProfileImage', function () {
        //hack for non ie browsers
        if (!$.browser.msie) {
            var $a = $(this).find('a');
            window.document.location = $a.attr('href');
        }
    });
    //end of most viewed video and photo effect
    //////////////////////////////////
    //suggested live cams
    $('#BottomProfileList').on("mouseenter", '.suggCams', function (event) {
        var $box = $(this).find('.insidevideolistimage_more');
        var $img = $(this).find('.VideoViewIcon_more');
        $img.stop(true).animate({
            top: 35
        }, anim_speed);
        $box.css({
            left: 0,
            width: (120 - 4),
            height: (67 - 4),
            border: '2px solid #eac31a'
        }).stop(true).fadeIn(anim_speed, function () {
            $box.css({
                'opacity': 1
            });
        });
        var $rating = $(this).find('.insidevideomore');
        $rating.stop(true).fadeIn(anim_speed, function () {
            $rating.css({
                'opacity': 1
            });
        });
    }).on("mouseleave", '.suggCams', function (event) {
        var $box = $(this).find('.insidevideolistimage_more');
        var $img = $(this).find('.VideoViewIcon_more');
        $img.stop(true).animate({
            top: -25
        }, anim_speed);
        $box.stop(true).fadeOut(anim_speed, function () {
            //$box.css({display : 'none', opacity : 0});
        });
        var $rating = $(this).find('.insidevideomore');
        $rating.stop(true).fadeOut(anim_speed, function () {
            //$rating.css({display : 'none',opacity : 0});
        });
    }).on('click', '.suggCams', function () {
        //hack for non ie browsers
        if (!$.browser.msie) {
            var $a = $(this).find('a');
            window.document.location = $a.attr('href');
        }
    });
    //end of suggested live cams
    //////////////////////////////////
    ////////////////////////////////////
    //viewed video and photo effect on search page
    $('#container').on("mouseenter", '.SearchResultPic', function (event) {
        var $box = $(this).find('.SearchHoverBox');
        var $img = $(this).find('.SearchHoverIcon');
        $img.stop(true).animate({
            top: 45
        }, anim_speed);
        $box.css({
            width: (189 - 6),
            height: (106 - 6),
            border: '3px solid #CEA113'
        }).stop(true).fadeIn(anim_speed, function () {
            $box.css({
                'opacity': 1
            });
        });

    }).on("mouseleave", '.SearchResultPic', function (event) {
        var $box = $(this).find('.SearchHoverBox');
        var $img = $(this).find('.SearchHoverIcon');
        $img.stop(true).animate({
            top: -25
        }, anim_speed);
        $box.stop(true).fadeOut(anim_speed, function () {
            //$box.css({display : 'none', opacity : 0});
        });
    }).on('click', '.BottomProfileImage', function () {
        //hack for non ie browsers
        //if( !$.browser.msie ){
        //	var $a = $(this).find('a');
        //	window.document.location = $a.attr('href');
        //}
    });
    //end of most viewed video and photo effect
    //////////////////////////////////

    $('.splitimage').each(function () {
        $(this).mouseover(function () {
            $(this).attr("src", $(this).attr("src").replace("_up", "_over"));
            return false;
        });
        $(this).mouseout(function () {
            $(this).attr("src", $(this).attr("src").replace("_over", "_up"));
            return false;
        });
    });

    $('#RightTopMenuInside a').each(function () {
        $(this).mouseover(function () {
            var src = $(this).find('img').attr("src");
            if (src != undefined) {
                src = src.replace("_up", "_over");
                $(this).find('img').attr("src", src);
            }
            return false;
        });
        $(this).mouseout(function () {
            var src = $(this).find('img').attr("src");
            if (src != undefined) {
                src = src.replace("_over", "_up");
                $(this).find('img').attr("src", src);
            }
            return false;
        });
    });

    $('#MapSearchSubmit').click(function () {
        if ($('#SearchFieldMap').val().length < 3 || $('#SearchFieldMap').val() == 'Enter Location' || !($('#SearchFieldMap').val().match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            TTAlert({
                msg: t('Please specify a location !'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });

            $('#SearchFieldMap').focus();
            return false;
        } else {
            $('#MapSearchForm').submit();
        }
    });
    var moreshown = false;
    $(".showChannels").click(function () {
        if (!moreshown) {
            moreshown = true;
            $("#OtherChannelsPop").show();
            InitCarouselOther();
        } else {
            moreshown = false;
            $("#OtherChannelsPop").hide();
        }
    });
    if ($("#changePP").length > 0) {
        InitChannelUploaderHome('changePP', 'userAvatar_id', 15,0);
    }
    $('#viewAllPI').mouseover(function () {
        var diffx = $('#MiddleTop').offset().left + 250;
        var diffy = $('#MiddleTop').offset().top + 22;
        var posxx = $(this).offset().left - diffx;
        var posyy = $(this).offset().top - diffy;
        $('.profileimagesbuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.profileimagesbuttonsOver').css('left', posxx + 'px');
        $('.profileimagesbuttonsOver').css('top', posyy + 'px');
        $('.profileimagesbuttonsOver').stop().show();
    });
    $('#viewAllPI').mouseout(function () {
        $('.profileimagesbuttonsOver').hide();
    });
    $('#viewAllCP').mouseover(function () {
        var diffx = $('#MiddleTop').offset().left + 250;
        var diffy = $('#MiddleTop').offset().top + 22;
        var posxx = $(this).offset().left - diffx;
        var posyy = $(this).offset().top - diffy;
        $('.profileimagesbuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.profileimagesbuttonsOver').css('left', posxx + 'px');
        $('.profileimagesbuttonsOver').css('top', posyy + 'px');
        $('.profileimagesbuttonsOver').stop().show();
    });
    $('#viewAllCP').mouseout(function () {
        $('.profileimagesbuttonsOver').hide();
    });    
});

function updatePP(pic_link) {
    /*if($("#AboutMeImageUL #AccountOverviewImage").length >0){
        $("#AboutMeImageUL #AccountOverviewImage").attr('src', ReturnLink('/media/tubers/Profile_'+pic_link) );
    }
    $('.userAvatar #AccountProfileImage').attr('src', ReturnLink('/media/tubers/Profile_'+pic_link) );
    $('.userAvatarLink').attr('href', ReturnLink('/media/tubers/'+pic_link) );*/
    document.location.reload();
    closeFancyBox();
}

function emailIsValid(email) {
    var re = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
    return re.test(email);
}

new function ($) {
    $.fn.setCursorPosition = function (pos) {
        if ($(this).get(0).setSelectionRange) {
            $(this).get(0).setSelectionRange(pos, pos);
        } else if ($(this).get(0).createTextRange) {
            var range = $(this).get(0).createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    }
}(jQuery);
/**
 * fixes a google maps firefox bug
 */
function FixFireFoxMapScroll(map) {

    // Define a ScrollInterceptOverlay function
    var ScrollInterceptOverlay = function (gmap) {
        if (!(this instanceof ScrollInterceptOverlay))
            return;

        var $div;
        var $mapDiv;

        var initialize = function () {
            $div = $('<div />').css({
                position: 'absolute',
                top: 0,
                left: 0,
                display: 'inline-block'
            });

            var div = $div[0];
            if (div && div.addEventListener) {
                // Internet Explorer, Opera, Google Chrome and Safari
                div.addEventListener("mousewheel", mouseScrollStop);
                // Firefox
                div.addEventListener("DOMMouseScroll", mouseScrollStop);
                div.addEventListener("MozMousePixelScroll", mouseScrollStop);
            }
            else if (div && div.attachEvent) { // IE before version 9
                div.attachEvent("onmousewheel", mouseScrollStop);
            }

            this.setMap(gmap);
        };

        var mouseScrollStop = function (e) {
            if (e && e.preventDefault)
                e.preventDefault();
        };

        this.onAdd = function () {
            $div.prependTo(this.getPanes().overlayMouseTarget);
        };

        this.onRemove = function () {

            var div = $div[0];
            if (div && div.addEventListener) {
                // Internet Explorer, Opera, Google Chrome and Safari
                div.addEventListener("mousewheel", mouseScrollStop);
                // Firefox
                div.addEventListener("DOMMouseScroll", mouseScrollStop);
                div.addEventListener("MozMousePixelScroll", mouseScrollStop);
            }
            else if (div && div.attachEvent) { // IE before version 9
                div.attachEvent("onmousewheel", mouseScrollStop);
            }

            $div.detach();
        };

        this.draw = function () {
            if ($mapDiv && $mapDiv.length === 1) {
                $div.css({
                    width: $mapDiv.outerWidth(),
                    height: $mapDiv.outerHeight()
                });
            }
        };

        var base_setMap = this.setMap;
        this.setMap = function (map) {
            $mapDiv = $(map.getDiv());
            base_setMap.call(this, map);
        };

        initialize.call(this);
    };
    // Setup prototype as OverlayView object
    ScrollInterceptOverlay.prototype = new google.maps.OverlayView();

    // Now create a new ScrollInterceptOverlay OverlayView object:
    var mapScrollInterceptor = new ScrollInterceptOverlay(map);
}

/////////////////
//checks if the element has a scrollbar
(function ($) {
    $.fn.hasScrollBar = function () {
        return this.get(0).scrollHeight > this.innerHeight();
    }
})(jQuery);

function checkBodyVerticalScrollBar() {
    var hContent = $("body").height(); // get the height of your content
    var hWindow = $(window).height(); // get the height of the visitor's browser window

    if (hContent > hWindow) { // if the height of your content is bigger than the height of the browser window, we have a scroll bar
        return true;
    }
    return false;
}

function hasVerticalScroll(node) {
    if (typeof node === 'undefined') {
        if (window.innerHeight) {
            return document.body.offsetHeight > innerHeight;
        }
        else {
            return  document.documentElement.scrollHeight >
                    document.documentElement.offsetHeight ||
                    document.body.scrollHeight > document.body.offsetHeight;
        }
    }
    else {
        return node.scrollHeight > node.offsetHeight;
    }
}
function getObjectData(obj) {
    var mystr = "" + obj.val();
    if (mystr == obj.attr('data-value')) {
        mystr = "";
    }
    return mystr;
}
function launchSlotMachine() {
    initSlotMachineTabs();
    upperSlideTab();
    // first tab and first content box are default current
    $(".tabs li:first-child a, .content-box:first").addClass("current");
    $(".box-wrapper .current .col").css({top: '0px'});
}

/**
 * which search page we are on
 * @type String
 */
var SEARCH_PAGE = '';

/**
 * which search category
 * @type String
 */
var SEARCH_CATEGORY;

/**
 * which global category are we in
 * @type integer
 */
var GLOBAL_CATEGORY = -1;

/**
 * which search page (pages to skip)
 * @type Number|Number
 */
var SearchPage = 0;

/**
 * what type of search to perform (a)ll,(v)ideo,(i)mages
 * @type String
 */
var SearchType;

/**
 * the search string
 * @type String
 */
var SearchString = '';

/**
 * if the search type popup is displayed
 * @type Number
 */
var searchflag = 0;

//////////////////////////////////////////

function GetSuggestLink() {
    return ReturnLink('/ajax/suggest.php?category=' + SEARCH_CATEGORY);
}

/**
 * sets the search page we are on
 * @param {string} sp
 */
function SearchPageSet(sp) {
    SEARCH_PAGE = sp;
}

/**
 * gets which search page wer are on
 * @returns {string}
 */
function SearchPageGet() {
    return SEARCH_PAGE;
}

/**
 * sets the search page we are on
 * @param {string} sp
 */
function SearchCategorySet(sp) {
    SEARCH_CATEGORY = sp;
}

/**
 * gets which search page wer are on
 * @returns {string}
 */
function SearchCategoryGet() {
    return SEARCH_CATEGORY;
}

/**
 * check if the event was for a alphanumeric (and space) keycode
 * @param {event} e
 * @returns {boolean}
 */
function checkChar(e) {
    var key;
    if (e.keyCode)
        key = e.keyCode;
    else if (e.which)
        key = e.which;
    if ((key == 8) || (key == 46)) {
        //backspace or delete buttons
        return true;
    }
    if (/[^A-Za-z0-9 ]/.test(String.fromCharCode(key))) {
        return false;
    } else {
        return true;
    }
}

/**
 * global search results array
 */
var _TTSearchResults = new Array();

/**
 * resest the global search result arrays
 */
function SearchResultsReset() {
    _TTSearchResults = new Array();
}

/**
 * appends to the global search results array
 * @param search_string string
 */
function SearchResultsAppend(search_string) {
    var search_strings = search_string.split(' ');
    $.each(search_strings, function (i, v) {
        var vl = v.toLowerCase();
        if ($.inArray(vl, _TTSearchResults) === -1) {
            _TTSearchResults.push(vl);
        }
    });
}

/**
 * attempts to complete the search results array
 */
function SearchResultsComplete(search_string) {
    var final_res = '';
    var search_strings = search_string.toLowerCase().split(' ');
    $.each(search_strings, function (i, v1) {
        $.each(_TTSearchResults, function (i, v2) {
            if (v1 == v2) {
                if (final_res != '')
                    final_res += ' ';
                final_res += v1;
                return false;
            } else if ((v1.length != 0) && (v2.indexOf(v1) != -1)) {
                if (final_res != '')
                    final_res += ' ';
                final_res += v2;
                return false;
            }
        });
    });
    return final_res;
}

/**
 * the ajax event
 * @type @exp;$@call;ajax
 */
var ajaxObject = null;

function AjaxCancelSearch() {
    if (ajaxObject != null) {
        ajaxObject.abort();
        ajaxObject = null;
    }
}

function InitSorting() {
    //return false;
    var $optionSet = $('#sort-by'),
            $optionLinks = $optionSet.find('a');

    $('#sort-by').find('a').click(function () {
        $('#sort-by').hide();
    });

    $optionLinks.click(function () {

        var $this = $(this);

        if ($this.parent().parent().attr('id') == 'sort-by')
            $('#search_order').html($this.html());

        // don't proceed if already selected
        if ($this.hasClass('selected')) {
            return false;
        }

        $this.parent().parent().find('.selected').removeClass('selected');
        $this.addClass('selected');

        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
                key = $this.parent().parent().attr('data-option-key'), /*go up to the ul*/
                value = $this.attr('data-option-value');
        $('#orderby').val(value);
        $('.SearchSubmit').click();
    });
}

var serach_press_timeout = null;

function launchSearchHome() {
    $('#SearchCategoryList').hide();
    InitSorting();
    autocompleteMedia($('#SearchField'));
    $('#SearchCategoryList li').click(function () {
        SEARCH_CATEGORY = $(this).attr('data-type');
        $('.SearchCategoryBtn').html($(this).html() + '<span class="SearchCategoryIcon"></span>');
        $('#t').val(SEARCH_CATEGORY);
        if (SEARCH_CATEGORY == 'u') {
            $('#SearchForm').attr('action', ReturnLink('/tubers'));
        }
        else {
            $('#SearchForm').attr('action', ReturnLink('/search'));
        }
        $('#SearchCategoryList').hide();
        searchflag = 0;
    });

    $('.SearchSubmit').click(function () {
        var tosearch =$('#SearchField').val();
        if ($("#t").val() == 'u') {
            if( tosearch=='' || tosearch.length<=2){    
                return;
            }
            var page_links = $('#SearchForm').attr('action');
            var sht =$('.SearchField #t').val();
            var shorderby =$('.SearchField #orderby').val();
            var shc =$('.SearchField #c').val();
            if( tosearch!='' ){
                page_links +='/qr/'+tosearch;
            }
            if( sht!='' ){
                page_links +='/t/'+sht;
            }
            if( shorderby!='' ){
                page_links +='/orderby/'+shorderby;
            }
            if( shc!='' ){
                page_links +='/c/'+shc;
            }
        }else{
            if( !$(this).hasClass('keepcategory') ){
                $("#c").val('');
                $('#SearchCategoryList').hide();
            }
            var page_links = ReturnLink('/');
            var sht =$('.SearchField #t').val();
            var category_id = $('#SearchField').attr('data-id');
            var category_name = $('#SearchField').attr('data-name');
            var shorderby =$('.SearchField #orderby').val();
            var shc =$('.SearchField #c').val();
            if( tosearch !=''){
                page_links += tosearch+"-"+category_name+"-S";
            }else{
                page_links += "-"+category_name+"-S";
            }
            if( sht!='' ){
                page_links += sht+"_1_";
            }
            if( category_id!='' ){
                page_links += category_id;
            }
            if( shorderby!='' ){
                page_links +='_'+shorderby;
            }
        }
        document.location.href = page_links;
        //$('#SearchForm').submit();
    });

    $('.SearchCategoryBtn').click(function () {
        if (searchflag == 0) {
            $('#SearchCategoryList').show();
            searchflag = 1;
            SearchCateFadeoutTimeout = setTimeout(function () {
                $('#SearchCategoryList').hide();
                searchflag = 0;
            }, 1500);

            $('#SearchCategoryList').unbind('mouseenter mouseleave').hover(function () {
                clearTimeout(SearchCateFadeoutTimeout);
                $('#SearchCategoryList').show();
                searchflag = 1;
            }, function () {
                SearchCateFadeoutTimeout = setTimeout(function () {
                    $('#SearchCategoryList').hide();
                    searchflag = 0;
                }, 500);
            });
        } else {
            $('#SearchCategoryList').hide();
            searchflag = 0;
        }
    });
    
    $('.videosFilter').on('mouseleave', function () {
        $('.SearchHeaderOrderDropdown').hide();
    });
    
    $('#SearchForm').submit(function (event) {
        event.preventDefault();
    });
    var auto_submit = 1500;
    if ((typeof SEARCH_PAGE != 'undefined') && (SEARCH_PAGE == 'index'))
        auto_submit = 2500;
}
function checkSubmitSearchHome(e){
   if(e && e.keyCode == 13){
      $('.SearchSubmit').click();
   }
}
function launchFooterFN() {
    bottom1_h = 60;
    bottom2_h = USER_IS_LOGGED ? 124 : 148;//the size of the footer depends on the logged state
    bottomt_h = bottom1_h + bottom2_h;
    $('#FooterCollaspeExpand').click(function (event) {
        event.stopImmediatePropagation();
        event.preventDefault();
        if ($('.BottomFooterButton').hasClass('BottomFooterButtonCollapse')) {
            $('.BottomFooterButton').removeClass('BottomFooterButtonCollapse').addClass('BottomFooterButtonExpand');
            $('.FooterCollaspeExpandOver .ProfileHeaderOverin').html(t('open menu'));
            $('#BottomContainer2').animate({
                height: 0 + 'px'
            },
            {
                complete: function () {
                    $('#BottomContainer2').hide();
                    FixFooter();
                },
                step: function (now) {
                    $("html, body").animate({scrollTop: $(document).height()}, 1);
                }
            });
        } else {
            $('.BottomFooterButton').removeClass('BottomFooterButtonExpand').addClass('BottomFooterButtonCollapse');
            $('.FooterCollaspeExpandOver .ProfileHeaderOverin').html(t('close menu'));
            $('#BottomContainer2').show().animate({
                height: bottom2_h + 'px'
            }, {
                complete: function () {
                    FixFooter();
                },
                step: function (now) {
                    $("html, body").animate({scrollTop: $(document).height()}, 1);
                }
            });
        }
        return false;
    });
    FixFooter();

    $(window).scroll(function () {
        FixFooter();
    }).resize(function () {
        FixFooter();
    });
}

function InitCarouselOther() {
    if ($(".caouselother").length > 0) {
        $(".caouselother").jCarouselLite({
            circular: false,
            vertical: false,
            scroll: 1,
            visible: 1,
            auto: 0,
            speed: 750,
            hoverPause: true,
            btnNext: "#OtherChannelsPop .next",
            btnPrev: "#OtherChannelsPop .prev"
        });
    }
}

function closeFancyReload(is_album) {
    if (parseInt(is_album) == 0) {
        $('.fancybox-close').click();
        document.location.reload();
    }
}

function closeFancyExportVideo(link_media) {
    closeFancy();
    TTAlert({
        msg: sprintf(t('your video has been updated. %s please bear us some time to be converted.'), ['<br/>']),
        type: 'alert',
        btn1: t('ok'),
        btn2: '',
        btn2Callback: function () {
            if(link_media){
                document.location.href = link_media;
            }
            else{
                document.location.reload();
            }
        }
    });
}