

//fixes the middleLayout in case contents are being shown under the floating footer


function setMiddleLayout(){
    //var margin = 46 + 60 + 98; //all footer height
    var margin = 300;
    try{
        margin = FooterGetHeight();
    }catch(e){

    }
    var $middle = $('#MiddleInsideNormal'); //add other selectors that define the "middle" or implement a golbal middle in TopIndex.php and BottomIndex.php

    if( $middle.length === 0 ) return ;
    //$middle.css('padding-bottom', '');
    var current_padding = $middle.innerHeight() - $middle.height();

    var vertical_space_on_bottom_without_padding = $(window).height() - ($middle.offset().top + $middle.height());
    var vertical_space_on_bottom_with_padding = vertical_space_on_bottom_without_padding + current_padding;
    //console.log( "vs1 = " + vertical_space_on_bottom_without_padding );
    //console.log( "vs2 = " + vertical_space_on_bottom_with_padding );
    if( vertical_space_on_bottom_without_padding < margin ){
        $middle.css('padding-bottom', (vertical_space_on_bottom_without_padding + 5) + 'px');
        //$middle.css('padding-bottom','');
    }else{
        $middle.css('padding-bottom','');
    }
}


var ChatWindows = 0;
var varChatZIndex = 10000;
var USUAL_CHAT_WIDTH = 273; //actually 275 - (the inputs 1px border *2)
var MINIMIZED_CHAT_WIDTH = 159;
var MARGIN_RIGHT_CHAT = 5;
var AbsolutePath = '';
var WhichStorage = sessionStorage;//localStorage;//sessionStorage;
var myheightList=0;


//rishav { code to check browser state}

var isActive;

window.onfocus = function () {
    isActive = true;
};

window.onblur = function () {
    isActive = false;
};



function SizeAllTabs(){
    var available_width = $(window).width() - $('#chat-search').width();
    //check if we are running out of space to reduce the size of inactive tabs
    var visible_windows = $('div.ChatWindow:visible').length;
    var min_size_inactive_tab = (available_width - USUAL_CHAT_WIDTH*visible_windows - (ChatWindows+2)*MARGIN_RIGHT_CHAT ) / (ChatWindows-visible_windows);
    var nominal_inactive_tab_width = Math.min(MINIMIZED_CHAT_WIDTH,min_size_inactive_tab);
    /////////////////

    //loop throught the footer chat tabs and see which ones are inactive and resize accordingly
    $('.lower_part_main').each(function(){
        var this_userid = $(this).attr('data-tuber');
        if( $('#ChatWindow' + this_userid).is(':visible') ){
            $(this).width(USUAL_CHAT_WIDTH);
        }else{
            $(this).width(nominal_inactive_tab_width);
        }
    });
}
function checkIfExist(id){
    var exist = false;
    $(".lower_part_main").each(function () {
        if($(this).attr('data-tuber') == id){
            exist = true;
        }
    });
    return exist;
}
function hex2a(hexx) {
    var hex = hexx.toString();//force conversion
    var str = '';
    for (var i = 0; i < hex.length; i += 2)
        str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
    return str;
}

/**
 * append to the chat log
 * @param object $chatLog the jquery object holding the chat log
 * @param string new_string the new string to be appended to the chat log
 */
function ChatLogAppend($chatLog, new_string){
    if(typeof $chatLog === 'undefined')
        return;
    $('.jspPane', $chatLog).append(new_string);
    initScrollPane($chatLog);

}

function ChatLogPrepend($chatLog, new_string){
    if(typeof $chatLog === 'undefined')
        return;
    var beforeHeight = $chatLog.find('.jspPane').height();
    $('.jspPane', $chatLog).prepend(new_string);
    initScrollPaneReverse($chatLog ,beforeHeight);
}

/**
 * gets a timestamp to print to the screen
 * @return string a string timestamp
 */
function getTS( ts_arg ){
    var ts_string, ts,hour,month,day,year,min,sec;

    if( typeof ts_arg != 'undefined'){
        ts = ts_arg;
        year = ts.getFullYear();
        month = ts.getMonth() + 1;
        day = ts.getDate();
        hour = ts.getHours();
        min = ts.getMinutes();
        sec = ts.getSeconds();
        if(hour < 10) hour = '0' + hour;
        if(min < 10) min = '0' + min;
        if(sec < 10) sec = '0' + sec;
        var ts_current = new Date();
        var current_day = ts_current.getDate();
        var current_month = ts_current.getMonth();
        if( (current_month==month) && (current_day==day) ){
            ts_string = hour + ":" + min + ":" + sec;
        }else{
            ts_string = year + '-' + month + '-' + day + ' ' + hour + ":" + min + ":" + sec;
        }
    }else{
        ts = new Date();
        hour = ts.getHours();
        min = ts.getMinutes();
        sec = ts.getSeconds();
        if(hour < 10) hour = '0' + hour;
        if(min < 10) min = '0' + min;
        if(sec < 10) sec = '0' + sec;
        ts_string = hour + ":" + min + ":" + sec;
    }
    return ts_string;
}

$(document).ready(function () {
    setMiddleLayout();

    $(window).resize(function(){
        setMiddleLayout();
    }).scroll(function(){
        setMiddleLayout();
    });
    myheightList=$(window).height();
    if(myheightList<730){
        myheightList=myheightList-54;
    }else{
        myheightList=568;
    }


    $("#chatList").css("height",myheightList-49+"px");

    $('#openChatList').show();

    $("#openChatList").click(function( e ) {
        if($("#chatList").css("visibility") === "hidden"){
            $("#chatList").css("visibility", "visible");
            $("#chatList").hide();
        }
        $("#chatList").slideToggle(500, function(){
            refreshChatWindow();
        });
    });
    $("#onlineTubersNb").html( "online chat server" );

    //refreshChatWindow();
    $("#chatList #chat-container").css("height", (myheightList-169) + "px");
    $("#chatList #chat-container-inside").css("height", (myheightList-169) + "px");


    $(document).on('click','.one-chat-name',function (){
        var curuobjs = $(this).parent();
        var uid = curuobjs.attr('data-tuber');
        if(isStarted && callreceiverId == uid)
            return;
        if (curuobjs.attr('data-blocked') == 0) {
            var userstatus =  curuobjs.attr('data-chat-status');
            AddTuberChat(uid,'',userstatus);
            curuobjs.attr('data-blocked',-1);
        }
        if (curuobjs.attr('data-blocked') == -1) {
            //$(".one-chatLeftList[data-tuber="+uid+"]").removeClass('minimized');
            var uid = curuobjs.attr('data-tuber');
            $(".maximize_me"+uid).hide();
            $("#ChatWindow"+uid).show();
            $("#chatInput"+uid).show();
            SizeAllTabs();
        }
    });
    $(window).resize(function () {
        refreshChatWindow();
    });

    initChatScrollPane();
    //$("#chatList").toggle(500);
    loadKeyEvents();

    // search user list
    $('#ttsearch').focus(function(){
        $(this).val('');
    });
    $('#ttsearch').blur(function(){
        $(this).val('Search for a friend to chat with...');
    });
    // search ends here

    initFavorites(); // initiate for clickable contgrols like all / offline and favorites
});



function refreshChatWindow(){
    myheightList=$(window).height();
    if(myheightList<730){
        myheightList=myheightList-54;
    }else{
        myheightList=568;
    }
    $("#chatList").css("height",myheightList-49+"px");
    $("#chatList #chat-container").css("height",(myheightList-169)+"px");
    $("#chatList #chat-container-inside").css("height",(myheightList-169)+"px");
    initChatScrollPane();
}
function ChatStatusChange(newStatus){
    var connection_data = JSON.stringify({
        op: 'CH_STAT',
        status: newStatus
    }, null, 2);

    try{
        ws.send(connection_data);
    }catch(Ex){

    }

}
function ChatLogGet(who){
    //$.cookie(tuberName);
    if(typeof WhichStorage != 'undefined') return WhichStorage.getItem('log_' + who);
    return null;
}

function reinitChatSCrollPane(){

    $('.chat_box_inner').data('jsp').destroy();
    $('#chat-container-inside').data('jsp').destroy();
    initChatScrollPane();

}

function initChatScrollPane(){
    $('.scroll-panenew').jScrollPane();
    $('.scroll-panenew .jspVerticalBar').css('right',0+"px").css('width','7px');
    $('.scroll-panenew .jspVerticalBar').css('opacity',0.43);

    $('.chat_box_inner.scroll-panenew .jspVerticalBar').css('right',5+"px").css('width','7px');
    $('.chat_box_inner.scroll-panenew .jspDrag').css('background',"#f5f5f5");
    $('.jspPane').css('top','0px');
}

function blockuserdata(id) {
    var curobj = $("#ChatListItem" + id);
    curobj.find(".one-chat-overlay-grey").show();
    curobj.attr('data-blocked', 1);
    contextUnBlock(curobj);
}
function unblockuserdata(id) {
    var curobj = $("#ChatListItem" + id);
    curobj.find(".one-chat-overlay-grey").hide();
    curobj.attr('data-blocked', 1);
}


function setvalcmbselected(val) {
    //$.cookie('chatStatus', val, {expires: 365, path: '/'});
    ChatStatusChange(val);
}

function manageExtraLists(){
    var uid = $("#extraList .oneExtraItem:last").attr("data-tuber");
    var newdivshow = $(".lower_part_main[data-tuber='"+uid+"']");
    var tuberID = uid;
    var tuberName = newdivshow.attr('title');

    newdivshow.show().css('width',USUAL_CHAT_WIDTH+'px');

    $(".oneExtraItem[data-tuber = "+tuberID+"]").remove();
    calculateChatWindowOffsets();

}

function calculateChatWindowOffsets(){
    var docWidth = $(document).width();
    var chatListWidth = 230;
    var chatWindowsSize = $(".lower_part_main").size();
    var chatMaxWindows = Math.floor( ( docWidth - chatListWidth - 220 ) / 278 );

    if(--chatWindowsSize > chatMaxWindows){
        var differenceBetween = chatWindowsSize - chatMaxWindows;
        //$("#extraWindows").show();
        var differenceBetwweenStr = "+ " + differenceBetween + " tubers";
        if(differenceBetween == 1) differenceBetwweenStr = "+ " + differenceBetween + " tuber";

        $("#extraWindwosNB").show().html(differenceBetwweenStr);
    }else{
        $("#extraWindows").hide();
    }

    //return differenceBetween;
}


function showAllRecords(){
    $(".one-chat-rec").show();
    $(".one-chat-remove-fav").hide();
}

function showJustOnline(){
    $(".one-chat-rec").hide();
    $(".one-chat-remove-fav").hide();
    $(".one-chat-rec").each(function () {
        if(!($(this).attr('data-chat-status') == "offline"))
            $(this).show();
    });
}

function showJustFavorites(){
    $(".one-chat-rec").hide();
    $(".one-chat-rec").each(function () {
        if($(this).attr('data-chat-favorites') == 1){
            $(this).show();
        }

    });
    initRemoveFav();
}

function initFavorites(){
/*$(".oneTabFav").css('cursor','pointer');*/
    $(document).on('click',".oneTabFav",function(){

        $(".oneTabFav").removeClass('selectedFav');
        $(this).addClass('selectedFav');

        var tabID = $(this).attr('id');
        lastFavTab = tabID;
        switch(tabID)
        {
            case "ALL" : showAllRecords();
                break;
            case "ONLINE" : showJustOnline();
                break;
            case "FAVORITES" : showJustFavorites();
                break;

        }
        refreshChatWindow();
    });

}

function initRemoveFav(){
    $(".one-chat-remove-fav").show().css('cursor','pointer').click(function () {
        var favID = $(this).parent().attr('data-tuber');
        removeFavorite(favID);
    });
}

function removeFavorite(favID){
    $.ajax({
        url: ReturnLink('/ajax/chat_favorite_user_del.php'),
        data: {fav_id : favID, op : 'deleteFav_frnd'},
        type: 'post',
        success: function(data){
            var ret = null;

            try{
                ret = $.parseJSON(data);
            }catch(Ex){
                return ;
            }

            if(!ret){
                TTAlert({
                    msg: 'Couldn\'t process. please try again later',
                    type: 'alert',
                    btn1: 'ok',
                    btn2: '',
                    btn2Callback: null
                });
                return ;
            }

            if(ret.status == 'ok'){

                $("#ChatListItem"+favID).attr('data-chat-favorites',0);
                $("#"+lastFavTab).trigger('click');

            }else{
                TTAlert({
                    msg: ret.msg,
                    type: 'alert',
                    btn1: 'ok',
                    btn2: '',
                    btn2Callback: null
                });
            }

            $('.chat-overlay-loading-fix').hide();
        }
    });
}
function replaceURL(inputText) {
    var replacedText, replacePattern1, replacePattern2, replacePattern3;

    inputText   =   inputText.trim();
    inputText   =   inputText.replace(/\r?\n/g, '<br />');

    //URLs starting with http://, https://, or ftp://
    replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    replacedText = inputText.replace(replacePattern1, '<a class="chatLink" href="$1" target="_blank">$1</a>');

    //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
    replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
    replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank" class="chatLink">$2</a>');

    //Change email addresses to mailto:: links.
    replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
    replacedText = replacedText.replace(replacePattern3, '<a class="chatLink" href="mailto:$1">$1</a>');

    return replacedText;
}

function initNotification(){
    console.log('requesting desktop notification...');
    Notification.requestPermission(function(permission){
        if (!('permission' in Notification)) {
            Notification.permission = permission;
        }
    });
}
function closeNotification(notification){
    setTimeout(function(){
        notification.close();
    },2000);
}


function desktopNotification(userName , message , profilePic) {
    if(!window.isActive){
        if (!("Notification" in window))
            console.log('Notification not supported by Browser.');

        else if (Notification.permission === "granted") {
            var options = { body: message, icon: profilePic, dir : "ltr" };
            var notification = new Notification(userName,options);
            closeNotification(notification);
        }
        else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function (permission) {
                if (!('permission' in Notification))
                    Notification.permission = permission;

                if (permission === "granted") {
                    var options = { body: message,icon: profilePic, dir : "ltr" };
                    var notification = new Notification(userName,options);
                    closeNotification(notification);
                }
            });
        }
    }
}