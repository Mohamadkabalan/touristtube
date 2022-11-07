/**
 * the web socket handle
 */
var chatIsOn = true;
var ws = null;
var lastFavTab = "ALL";
var docTitle = document.title;

var myheightList=0;

var docDelay;

function ChatEnabled(val){
	if( typeof val != 'undefined') chatIsOn = val;
	else return chatIsOn;
}
	
/**
 * Sends a message
 */
function SendMsgTo(to, msg){
	var SendData = {
		op: 'CHAT',
		to: to,
		msg: msg,
		timezone: TimeZone
	};
		
	Communicate(SendData);
}

function Communicate(options){
	if( ws != null){
		options.client_ts = getClientTS();
		var send_string = JSON.stringify(options, null, 2);
		try{
			ws.send( send_string );
		}catch(Ex){
				
		}
			
	}
}
	
function Receive(json_msg){
	if(json_msg.from.length == 0){
		//console.log('empty from');
	}else if( chatListLoaded() ){
		if( json_msg.from == CHAT_USER_ID )
			AddTuberChat(json_msg.to, false);
				
		else
			AddTuberChat(json_msg.from, false);
		AppendToLog(json_msg);
	}else{
		setTimeout(function(){
			Receive(json_msg);
		},1000);
	}
	

}

function titleChange(tuber,tuberID)
{
	if(!($("#chatInput"+tuberID).is(":focus")))
	{
		
		if(document.title == docTitle)
		
		document.title = tuber + " says ...";
		
		else document.title = docTitle;
		
		docDelay = setTimeout(function () {titleChange(tuber,tuberID);},1200);
	}
		
}
	
var SocketOpened = false;
function connect(){
	if( !ChatEnabled() ) return;
	SocketOpened = false;
	ws = $.gracefulWebSocket(CHAT_SERVER);
	//this check is for firefox
	if(ws == null){
		
		setTimeout(function(){
			connect();
		},500);
		return;
	}
	
	//this onclose is for chrome in case the server is not started when the page loads
	ws.onclose = function(event){		
		/*setTimeout(function(){                    
                    if( $('#chatList').css('display') !="none" ) $('#openChatList').click();
                    $('#openChatList').hide();
                    $("#ttsearch").hide()
                    $("#onlineTubersNb").show().html("offline chat server");
                    connect();
		},1000);*/
		
	}
	
	ws.onopen = function(event){
		SocketOpened = true;
                $('#openChatList').show();
                $("#onlineTubersNb").html( t("online chat server") ); 
	}
	
	
	
}
var ConnectInterval = null;
	
/**
 * @param uid which user
 * @param new_status 0 => offline, 1=> online, 2=> away, busy
 */
 
function getOnlineUserNB()
{
	var onlineTubersCount = 0;
	$(".one-chat-rec").each(function(){
		
		var currentChat = $(this);
		
		if(currentChat.attr('data-chat-status') == "available") onlineTubersCount++;
		
	});
	
	return onlineTubersCount;
}

function removeFromChatList(userID,not)
{
	$.ajax({
			url: ReturnLink('/ajax/chat_user_notifications.php'),
			data: {friend_id : userID, op : 'removeFrnd',notification : not},
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
						msg: t("Couldn't process. please try again later"),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}

				if(ret.status == 'ok'){	
					
					if(not == 0)
					{
						$("#ChatListItem"+userID).animate({'height':'0px'},500,function () {$(this).remove();});
					}else{
						loadChatList();
					}
					$("#"+lastFavTab).trigger('click');					
					
				}else{
					TTAlert({
						msg: ret.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
				
				$('.chat-overlay-loading-fix').hide();
			}
	});		
}

function addToFavorites(favID)
{
		
	$.ajax({
			url: ReturnLink('/ajax/chat_favorite_user_add.php'),
			data: {fav_id : favID, op : 'addFav_frnd'},
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
						msg: t("Couldn't process. please try again later"),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}

				if(ret.status == 'ok'){	
				
					$("#ChatListItem"+favID).attr('data-chat-favorites',1);
					$("#"+lastFavTab).trigger('click');					
					
				}else{
					TTAlert({
						msg: ret.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
				
				$('.chat-overlay-loading-fix').hide();
			}
	});	
		
}
 
function replaceTuberSearch()
{

	if($.cookie("openchat") == "false")
	{
		var onlineTubers = getOnlineUserNB();
		
		if(onlineTubers == 1 ) onlineTubers = t("1 tuber");
		else onlineTubers = onlineTubers+ " "+t("tubers");
		
		$("#ttsearch").hide();
		$("#onlineTubersNb").show().html( t("online tubers") +" (" + onlineTubers + ")");	
	}else{
		$("#onlineTubersNb").hide().html('');
		$("#ttsearch").show();	
	}
}
 
function DisplayChangeStatus(uid, new_status){
		
	switch(new_status){
		
		case CHAT_STATUS_OFFLINE:
			
			$('#ChatListItem' + uid).find('div').eq(1).removeClass('available away busy').addClass('one-chat-img offline');
			//$('#ChatListItem' + uid + ' div.one-chat-name p').addClass('grey').removeClass('green red yellow').html('Offline');
			$('#ChatWindow' + uid + ' .ChatTitle').addClass('leftgrey2').removeClass('leftgreen2 leftred2 leftyellow2');
			//$('#ChatListItem' + uid ).attr('data-chat-status','offline');
			break;
		case CHAT_STATUS_ONLINE:
			
			$('#ChatListItem' + uid ).find('div').eq(1).removeClass('offline away busy').addClass('one-chat-img available');
			//$('#ChatListItem' + uid + ' div.one-chat-name p').addClass('green').removeClass('red').removeClass('grey').html('Available');
			$('#ChatWindow' + uid + ' .ChatTitle').addClass('leftgreen2').removeClass('leftyellow2 leftred2 leftgrey2');
			//$('#ChatListItem' + uid ).attr('data-chat-status','available');
			break;
		case CHAT_STATUS_AWAY:
			$('#ChatListItem' + uid).find('div').eq(1).removeClass('available offline busy').addClass('one-chat-img away');
			//$('#ChatListItem' + uid + ' div.one-chat-name p').addClass('yellow').removeClass('green red grey').html('Away');
			$('#ChatWindow' + uid + ' .ChatTitle').addClass('leftyellow2').removeClass('leftgreen2 leftred2 leftgrey2');
			//$('#ChatListItem' + uid ).attr('data-chat-status','away');
			break;
		case CHAT_STATUS_BUSY:
			$('#ChatListItem' + uid).find('div').eq(1).removeClass('available away offline').addClass('one-chat-img busy');
			//$('#ChatListItem' + uid + ' div.one-chat-name p').addClass('red').removeClass('yellow green grey').html('Busy');
			$('#ChatWindow' + uid + ' .ChatTitle').addClass('leftred2').removeClass('leftyellow2 leftgreen2 leftgrey2');
			//$('#ChatListItem' + uid ).attr('data-chat-status','busy');
			break;
	}
	replaceTuberSearch();
	$("#"+lastFavTab).trigger('click');
}

/**
 * what to do after the websocket connection is established
 */
function PostConnect(){
	
	if( !SocketOpened ) return;	
	replaceTuberSearch();
	
	//connected to stop trying to connect
	clearInterval(ConnectInterval);
	
	ConnectInterval = null;
	destroyContextMenu();
	loadChatList();
	
	//on connect send the chat server the CONNECT command with the needed session id
	var status = ChatStatusGet();
	if(!status) status = CHAT_STATUS_ONLINE;
	var connection_data = JSON.stringify({
		op: 'CONNECT',
		sid: CHAT_SESSION_ID,
		status: status
	}, null, 2);
	
	try{
            ws.send(connection_data);
	}catch(Ex){
            
	}
	
	//if the socket connection is dropped try to reestablish the connection
	ws.onclose = function(evt) {
		setTimeout(function(){
                   connect();
		},2000);
		ConnectInterval = setInterval(function(){
			PostConnect();
		}, 500);
	};

	ws.onmessage = function (event) {
		var srv = null
		try{
			srv = JSON.parse(event.data);
		}catch(Ex){

		}
		srv.from = parseInt(srv.from);
		
		//from == 0 implies a message from the server itself
		if(srv.from == 0){
			if(srv.op == 'dc'){
				DisplayChangeStatus(srv.who,0);
			}else if(srv.op == 'con'){
				DisplayChangeStatus(srv.who,srv.status);
			}else if(srv.op == 'ch_stat'){
				DisplayChangeStatus(srv.who,srv.status);
			}else if(srv.op == 'frnd_req'){
				TTAlert({
					msg: t(" got freind request from ") + srv.who,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
		}else{
			Receive( srv );
			if(srv.from != '') titleChange($(".one-chatLeftList[data-tuber="+srv.from+"]").attr('title'),srv.from);	
			$(".one-chatLeftList[data-tuber="+srv.from+"]").click(function () {
                        document.title = docTitle;				
                        clearInterval(docDelay);
                        });
			
			
			$("#chatInput"+srv.from).focus(function () {
				document.title = docTitle;	
				clearInterval(docDelay);
					
			});
		}
	};
		
}
	
function ChatStatusGet(){
	return $.cookie('chatStatus');
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



function turnOffChat()
{
	ChatEnabled(false);
	var SEND = {op:'CLIENT_DISCONNECT'};
	Communicate(SEND);
	
	$("#chatList").hide().html('');
	
	replaceTuberSearch();
	$("#switchatOn").show().fadeIn(10,function () {
		$("#switchatOn").click(function () {
			$("#switchatOn").hide();
			$("#chatList").slideToggle();
			turnOnChat();
				
		});
	});
	
}


function turnOnChat()
{
	ChatEnabled(true);

		connect();
		ConnectInterval = setInterval(function(){
			PostConnect();
		}, 500);
		
		loadChatList();
		
			
}

function initFavorites()
{
	$(".oneTabFav").css('cursor','pointer').click(function () {
		
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
		reinitChatSCrollPane();	
		});
			
}

function shareTubersToChatWindow(to_user)
{
	var str = "";
	var selected_array = new Array();
	$(".one_tuber_share").each(function() {
		
		var currentTuberShare = $(this).find('.one_tuber_name');
		if(currentTuberShare.hasClass('selected_share'))
		{

		  str  += '<div class="tuber_automatic_share">'+t("i want to share this tube with you")+'</div> <br><a href="'+ReturnLink("/profile/"+currentTuberShare.html())+'" target="_blank"><img src="'+currentTuberShare.parent().attr('data-image') + '" +  class="tuber_search" border="0">  <div class="tuber_search_name">' + currentTuberShare.html() + "</div></a>" ;
			
		}
		
	});
	
	var SendData = {
		op: 'CHAT',
		to: to_user,
		msg: str,
		timezone: TimeZone
	};
		
	Communicate(SendData);
	
	ChatLogAppend($("#dataTuber"+to_user),'<div class="ChatMyMessage"><span class="User">'+t("Me")+'</span><span class="Date">' + getTS() + '</span><br/><span class="Msg">'+str+'</span></div>');
	
	$("#blackTransparentDiv").trigger('click');
	
}

function getRemovedChecked()
{
	var str = "";
	var selected_array = new Array();
	$(".one_tuber_share").each(function() {
		
		var currentTuberID = $(this).attr('data-tuber');
		
		var currentTuberShare = $(this).find('.one_tuber_name');
		if(currentTuberShare.hasClass('selected_share'))
		{

		 	removeFromChatList(currentTuberID,1); 
			
		}
		
	});
	$("#blackTransparentDiv").trigger('click');	
}

function viewTubersShare(to_user)
{
	var documentWidth = $(document).width();
	var loadLink = ReturnLink("/get_my_tubers.php?to_user="+to_user);
	$("#blackTransparentDiv").show();
	$("#chattubers").css('left',(documentWidth-370)/2).show().load(loadLink,function () {
		
		var element = $('#shareTubersInside').jScrollPane({
			animateDuration: 500
		});
		
		jscrollpane_api = element.data('jsp');
			
		$("#shareTubersInside .jspDrag").css({'background-color':'#5f5f5f','width':'6px'});
		
		
		$(".one_tuber_share").click(function () {
			
			var currentName = $(this).find(".one_tuber_name");
			
			if(currentName.hasClass('selected_share'))
			{
				currentName.removeClass('selected_share');	
			}else{
				currentName.addClass('selected_share');
			}
		
		});
		
		$("#blackTransparentDiv").unbind('click');
			$("#blackTransparentDiv").css('cursor','pointer').click(function () {
				$("#chattubers").hide().html("");
				$("#blackTransparentDiv").hide();
			});
		
	});
}

function viewRemovedUsers()
{
	var documentWidth = $(document).width();
	var loadLink = ReturnLink("/get_my_tubers.php?to_user=0&status=notification");
	$("#blackTransparentDiv").show();
	$("#chattubers").css('left',(documentWidth-370)/2).show().load(loadLink,function () {
		
		var element = $('#shareTubersInside').jScrollPane({
			animateDuration: 500
		});
		
		jscrollpane_api = element.data('jsp');
			
		$("#shareTubersInside .jspDrag").css({'background-color':'#5f5f5f','width':'6px'});
		
		
		$(".one_tuber_share").click(function () {
			
			var currentName = $(this).find(".one_tuber_name");
			
			if(currentName.hasClass('selected_share'))
			{
				currentName.removeClass('selected_share');	
			}else{
				currentName.addClass('selected_share');
			}
		
		});
		
		$("#blackTransparentDiv").unbind('click');
			$("#blackTransparentDiv").css('cursor','pointer').click(function () {
				$("#chattubers").hide().html("");
				$("#blackTransparentDiv").hide();
			});
		
	});
}


function viewChatHistory(otherID)
{
	var documentWidth = $(document).width();
		var loadLink = ReturnLink("/get_chat_history.php?to="+otherID);
		$("#blackTransparentDiv").show();
		$("#chathistorydiv").css('left',(documentWidth-700)/2).show().load(loadLink,function () {
			
			//$("#rightChatHistory").jScrollPane({animateDuration: 1000});
			
			var element = $('#rightChatHistory').jScrollPane({
				animateDuration: 500
			});
			showhideTimeFunctions();
			
			
			jscrollpane_api = element.data('jsp');
			
			$("#rightChatHistory .jspDrag").css({'background-color':'#5f5f5f','width':'6px'});
			
			$("#blackTransparentDiv").unbind('click');
			$("#blackTransparentDiv").css('cursor','pointer').click(function () {
				$("#chathistorydiv").hide().html("");
				$("#blackTransparentDiv").hide();
			});
			
			
			$(".chat_time_history").click(function () {
		
				/*if(!($(this).hasClass("yellow")))
				{*/
					$(".chat_time_history").removeClass('yellow').addClass('grey');
					$(this).addClass('yellow');
					
					var currentAttr = $(this).attr('data-date');
					var pos = $("#"+currentAttr).position();
					
					//alert(pos.top);
					jscrollpane_api.scrollToY(pos.top - 65,true);
				/*}*/
	
			});
				
		}); 	
}


$(window).resize(function () {
	
	var docWidth = $(document).width();	
	var chatListWidth = 230;
	var chatWindowsSize = $(".one-chatLeftList").size();
	var chatMaxWindows = Math.floor( ( docWidth - chatListWidth - 220 ) / 278 );
	

	$(".one-chatLeftList").hide();
	
	for($i = 0;$i<chatMaxWindows;$i++)
	{
		$(".one-chatLeftList").eq($i).width(USUAL_CHAT_WIDTH+'px').show();	
	}

	
	if(chatWindowsSize > chatMaxWindows)
	{
		
		var differenceBetween = chatWindowsSize - chatMaxWindows;
		$("#extraWindows").show();
		var differenceBetwweenStr = "+ " + differenceBetween + " tubers";
		if(differenceBetween == 1) differenceBetwweenStr = "+ " + differenceBetween + " tuber";
			
		$("#extraWindwosNB").show().html(differenceBetwweenStr);	
		
	}else{
		
		$("#extraWindows").hide();	
		
	}
	
	
	
});

$(document).ready(function () {
		
	/*connect();
	ConnectInterval = setInterval(function(){
		PostConnect();
	}, 500);*/
	
	myheightList=$(window).height();
	if(myheightList<730){
		myheightList=myheightList-54;
	}else{
		myheightList=568;	
	}
	
	$("#chatList").css("height",myheightList+"px");
		
	
	turnOnChat();
	
	$("#ttsearch").focus(function () {
		$(this).val("");
	});
	
	$("#ttsearch").blur(function () {
		$(this).val("Search for a friend to chat with...");
	});
	
	$("#openChatList").click(function () {
		$("#chatList").slideToggle(500,function () {
			
			if($("#chatList").css("display") !="none"){
			$.cookie("openchat", "true",{path: '/'});
			destroyContextMenu();
			loadChatList();
			}else{
				$.cookie("openchat", "false",{path: '/'});
				destroyContextMenu();
				
			}
			replaceTuberSearch();
				
		});
		
	});
	
	loadKeyEvents();
	
	//$("#openChatList").click();
	if($.cookie("openchat")=="true"){
		$("#chatList").slideToggle();
		if($("#chatList").css("display") !="none"){
			$.cookie("openchat", "true",{path: '/'});
			loadChatList();
		}
	}
});
function refreshChatWindow(){	
	myheightList=$(window).height();
	if(myheightList<730){
		myheightList=myheightList-54;
	}else{
		myheightList=568;	
	}
	
	$("#chatList").css("height",myheightList+"px");
	$("#chatList #chat-container").css("height",(myheightList - 120)+"px");
	$("#chatList #chat-container-inside").css("height",(myheightList - 120)+"px");
	initChatScrollPane();
}

function showAllRecords()
{
	$(".one-chat-rec").show();
	$(".one-chat-remove-fav").hide();	
}

function showJustOnline(){
	
	$(".one-chat-rec").hide();
	$(".one-chat-remove-fav").hide();
	$(".one-chat-rec").each(function () {
		
		if($(this).attr('data-chat-status') == "available")
		{
			$(this).show();	
		}
			
	});
		
}

function showJustFavorites()
{
	$(".one-chat-rec").hide();
	$(".one-chat-rec").each(function () {
		
		if($(this).attr('data-chat-favorites') == 1)
		{
			$(this).show();	
		}
			
	});
	initRemoveFav();	
}


function initRemoveFav()
{
	$(".one-chat-remove-fav").show().css('cursor','pointer').click(function () {
			
			
			var favID = $(this).parent().attr('data-tuber');
			
			removeFavorite(favID);
			
			
	});;	
}

function removeFavorite(favID)
{
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
						msg: t("Couldn't process. please try again later"),
						type: 'alert',
						btn1: t('ok'),
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
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
				
				$('.chat-overlay-loading-fix').hide();
			}
	});	
}


function destroyContextMenu(){
	$('.conmn-menu').remove();
}

function loadKeyEvents()
{
	$("#ttsearch").keypress(function () {
		instantSearch($(this));
	});
	$("#ttsearch").keydown(function () {
		instantSearch($(this));
	});
	$("#ttsearch").keyup(function () {
		instantSearch($(this));
	});	
}

function loadChatList()
{
	$("#chatList").load( ReturnLink('/chatlist.php') ,function () {
		loadChatFunctions();
	});
}
var ChatWindows = 0;
var ChatID = null;
var USUAL_CHAT_WIDTH = 273; //actually 275 - (the inputs 1px border *2)
var MINIMIZED_CHAT_WIDTH = 159;
var MARGIN_RIGHT_CHAT = 5;

/**
 * sets the proper size on all tab sizes in the chat footer
 */
function SizeAllTabs(){
	var available_width = $(window).width() - $('#chat-search').width();
	
	//check if we are running out of space to reduce the size of inactive tabs
	var visible_windows = $('div.ChatWindow:visible').length;
	var min_size_inactive_tab = (available_width - USUAL_CHAT_WIDTH*visible_windows - (ChatWindows+2)*MARGIN_RIGHT_CHAT ) / (ChatWindows-visible_windows);
	var nominal_inactive_tab_width = Math.min(MINIMIZED_CHAT_WIDTH,min_size_inactive_tab);
	/////////////////
	
	//loop throught the footer chat tabs and see which ones are inactive and resize accordingly
	$('.one-chatLeftList').each(function(){
		var this_userid = $(this).attr('data-tuber');
		if( $('#ChatWindow' + this_userid).is(':visible') ){
			$(this).width(USUAL_CHAT_WIDTH);
		}else{
			$(this).width(nominal_inactive_tab_width);
		}
	});
}

/**
 * gets a timestamp to send to the server
 * @return string a string timestamp
 */
function getClientTS(){
	var ts = new Date();
	var year = ts.getFullYear();
	var month = ts.getMonth()+1;
	var day = ts.getDate();
	
	var hour = ts.getHours();
	var min = ts.getMinutes();
	var sec = ts.getSeconds();
	
	if(hour < 10) hour = '0' + hour;
	if(min < 10) min = '0' + min;
	if(sec < 10) sec = '0' + sec;
	
	var ts_string = year + '-' + month + '-' + day + ' ' + hour + ':' + min + ':' + sec;
	return ts_string;
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

/**
 * called to hide the chat
 */
function HideChat(){
	$('.ChatWindow').hide();
	$('#chatList').hide();
}

/**
 * which storage to use for the chat log. could be 'sessionStorage' or 'localStorage'
 */
var WhichStorage = sessionStorage;//localStorage;//sessionStorage;

/**
 * saves a tubers chat log
 * @param string who which tuber's chat log
 * @param string log the tuber's chat log
 */
function ChatLogSave(who,log){
	//$.cookie(""+$ActiveChatLog.parent().find('.ChatTitle').text(), ""+$ActiveChatLog.html(),{ path: '/' });
	if(typeof WhichStorage != 'undefined') WhichStorage.setItem('log_' + who, log);
}

/**
 * returns a tubers chat log
 * @param string who which tuber's chat log
 * @return string|null
 */
function ChatLogGet(who){
	//$.cookie(tuberName);
	if(typeof WhichStorage != 'undefined') return WhichStorage.getItem('log_' + who);
	return null;
}

/**
 * saves a tubers chat log message unreaded
 * @param string who which tuber's chat log
 */
function ChatLogSaveMsgNumber(whoid,obj){
	if(typeof WhichStorage != 'undefined') {
		var num=ChatLogGetMsgNumber(whoid);
		if(num==null){
			num=1;	
		}else{
			num++;	
		}
		obj.find('.LeftTuberName').css('color','#000000');
		obj.find('span.status').html('('+num+')');	
		WhichStorage.setItem('log_' + whoid,num);
		$('.quickuploadcontainer').hide();
	}
}

/**
 * returns a tubers chat log message number
 * @param string whoid who which tuber's chat log
 * @return string|null
 */
function ChatLogGetMsgNumber(whoid){
	//$.cookie(tuberName);
	if(typeof WhichStorage != 'undefined') return WhichStorage.getItem('log_' + whoid);
	return null;
}

/**
 * extracts the chat log to be saved
 * @param object $chatLog the jquery object holding the chat log
 * @return string the chat log
 */
function ChatLogExtract($chatLog){
	return $('.jspPane', $chatLog).html();
}

/**
 * append to the chat log
 * @param object $chatLog the jquery object holding the chat log
 * @param string new_string the new string to be appended to the chat log
 */
function ChatLogAppend($chatLog, new_string){
	$('.jspPane', $chatLog).append(new_string);
	
	initScrollPane($chatLog);
	
}

function initScrollPane($obj){
	var element = $obj.jScrollPane();
	
	$obj.find(".jspDrag").css('background-color','#5f5f5f');
	$obj.find(".jspVerticalBar").css('width','5px');
		
	jscrollpane_api = element.data('jsp');
	jscrollpane_api.scrollToY(TotalInnerHeight($obj.find('.jspPane')) + 1000,true);	

}


var d = new Date();
var TimeZone = d.getTimezoneOffset();
var ToWho = 0;
varChatZIndex = 10000;
function AddTuberChat(uid, active){
	
	if(typeof active == 'undefined') active = true;
	
	//console.log("uid = '" + uid + "'");
	
	if( uid.length ==0 ) return ;
	
	//only add a new chat tab if it doesnt exist
	if( !checkIfExist(uid) )
	{
		var statusClass = $('#ChatListItem'+uid).attr('data-chat-status');
		var tuberName = $('#ChatListItem'+uid).find(".one-chat-name span").text();
		
		if(!tuberName){
			tuberName = 'Tuber';
			statusClass = 'green';
		}
		
		//the $newdiv is a new ChatTab in the footer
		var $newdiv = $("<div class='one-chatLeftList' data-tuber='"+uid+"' title='"+tuberName+"'><div class='LeftChatContainer'></div><div class='LeftTuberName left"+statusClass+"'>"+tuberName+"</div><span class='status'></span></div>");
		
			
		
		var docWidth = $(document).width();	
		var chatListWidth = 230;
		var chatWindowsSize = $(".one-chatLeftList").size() + 1;
		var chatMaxWindows = Math.floor( ( docWidth - chatListWidth - 220 ) / 278 );
		
		
		
		if(chatWindowsSize > chatMaxWindows)
		{
			
			var differenceBetween = chatWindowsSize - chatMaxWindows;
			$("#extraWindows").show();
			
			
			$("#extraWindows").unbind('click');
			$("#extraWindows").css('cursor','pointer').click(function () {
				
				if($("#extraList").is(":visible"))
				{
					$("#extraList").hide();	
				}else{
					$("#extraList").show();		
				}
				
			});
			
			var differenceBetwweenStr = "+ " + differenceBetween + " tubers";
			if(differenceBetween == 1) differenceBetwweenStr = "+ " + differenceBetween + " tuber";
			
			$("#extraWindwosNB").show().html(differenceBetwweenStr);	
			
			var lastName = $(".one-chatLeftList:last").attr('title');
			var lastID = $(".one-chatLeftList:last").attr('data-tuber');
			
			var $newExtraItem = $("<div class='oneExtraItem' data-tuber='"+lastID+"'>"+lastName+"</div>");
			
			$("#extraList").append($newExtraItem);
			
			$newExtraItem.click(function () {
				
				var replaceName = $(".one-chatLeftList:visible:last").attr('title');
				var replaceID = $(".one-chatLeftList:visible:last").attr('data-tuber');
				
				$(".one-chatLeftList:visible:last").hide();
				$(".one-chatLeftList[data-tuber='"+$newExtraItem.attr('data-tuber')+"']").css('width',USUAL_CHAT_WIDTH+'px').show();
				
				$(this).attr('data-tuber',replaceID).html(replaceName);
				
			});
			
			$(".one-chatLeftList:visible:last").hide();	
		}
		
		$("#chat-leftList").append($newdiv);
		
		
		
		
		
		
		//the $chatDiv is the chat window where the chat menu and chat log will appear 
		var $chatDiv = $('<div class="ChatWindow" id="ChatWindow'+uid+'"></div>').css({'z-index': varChatZIndex});
		$('.LeftChatContainer',$newdiv).append($chatDiv).click(function(event){
			event.stopImmediatePropagation();
			//return false;
		});
		
		var $chatSettings = $("<div class='one-chat-settings'>" +
		"<div class='one-chat-settings-row' id='viewChatHistory"+uid+"'>"+t('view chat history')+"</div>"+
		"</div>");
		
		$chatSettings.appendTo($chatDiv);
		
		$chatEmoticons = $("<div class='emoticonslist'></div>");
		$chatEmoticons.appendTo($chatDiv);
		
		var $chatTitle = $("<div class='ChatTitle left"+statusClass+"2'>"+tuberName+"</div>");
		
		var $chatClose = $('<div class="ChatCloseImg" title="Close"></div>');
		var $chatMinimize = $('<div class="ChatMinimize" title="Minimize"></div>');
		var $chatSet = $('<div class="ChatSet" title="Settings"></div>');
		var $chatEmoti = $('<div class="emoticonsIcon" title="Emoticons"></div>');
		
		$chatTitle.append($chatClose).append($chatSet).append($chatMinimize).append($chatEmoti).appendTo($chatDiv);
		var chtold = ChatLogGet(tuberName);
		if(chtold==null){
			chtold="";
		}
		var $chatLog = $("<div class='ChatLog'  id='dataTuber"+uid+"'></div>");
		$chatLog.appendTo($chatDiv);
		$chatLog.html(chtold);
		
		ChatWindows++;

		//$chatDiv.show();
		$chatDiv.hide();
		$chatDiv.parent().find('.quickuploadcontainer').hide();
		
		//SizeAllTabs();
		
		//var pos = $newdiv.offset();
		//$chatDiv.css({top: pos.top - $chatDiv.height() , left: pos.left });
		//$newdiv.outerWidth( $chatDiv.width() );
		
		var $chatInput = $('<input type="text" id="chatInput'+uid+'" class="chatInput" value="type something..."/>').css({
			'position':'absolute' ,
			'width' : '244px',
			'z-index' : varChatZIndex,
			'display' : 'none',
			border : '1px solid #c0c0c2'}).outerHeight($newdiv.outerHeight()).appendTo( $('.LeftChatContainer',$newdiv) ).keyup(function(event){
			if(event.keyCode == 13){
				var msg = $(this).val();
				if(msg == '') return ;
				SendMsgTo(ToWho , msg);
				$(this).val('');
				ChatLogAppend($chatLog,'<div class="ChatMyMessage"><span class="User">'+t("Me")+'</span><span class="Date">' + getTS() + '</span><br/><span class="Msg">' + EmoticonTextReplace(msg) + '</span></div>');
				//$chatLog.scrollTop( TotalInnerHeight($chatLog) );
				ChatLogSave($chatLog.parent().find('.ChatTitle').text() , ChatLogExtract($chatLog) );
				showhideTimeFunctions();
			}
			
			
			
		}).click(function(event){
			event.stopImmediatePropagation();
			return false;
		}).focus(function () {
			if($(this).val() == "type something...")
			{
				$(this).val('');	
			}	
		});
		
		var $quickupload = $('#quickupload');
		
		$quickupload = $('<div class="quickuploadcontainer" id="quickuploadContainer'+uid+'"><img src="'+AbsolutePath+'/images/quickupload.jpg" id="quickupload'+uid+'"></div>').appendTo($('.LeftChatContainer',$newdiv));
		
		
		
		/*$emoticon = $('#emoticonSelect');
		if($emoticon.length == 0)
		{
			var dim = 17;
			$emoticon = $('<img src="'+AbsolutePath+'/images/emoticon.jpg" id="emoticonSelect">').css({
				'position':'absolute' ,
				'z-index' : varChatZIndex + 1,
				'display' : 'none',
				cursor: 'pointer'}).height( dim ).width( dim ).appendTo('body');
			//cursor: 'pointer'}).outerHeight($newdiv.outerHeight() - 2 ).outerWidth( $newdiv.outerHeight() - 2 ).appendTo('body');
			
			$emoticon.click(function(){
				var $emoticonList = $('#emoticonList');
				if( $emoticonList.length != 0 ){
					$emoticonList.remove();
					return;
				}
				var list_width = 200;//$chatInput.outerWidth();
				var list_height = 250;
				var emoticon_pos = $emoticon.offset();
				var left = emoticon_pos.left + $emoticon.outerWidth() - list_width;
				var top = emoticon_pos.top - list_height;
				$emoticonList = $('<div id="emoticonList"></div>').css({
					'z-index': 10002,
					position: 'absolute',
					left: left,
					top: top,
					'background-color':'#FFFFFF'
				}).appendTo($('body'));
				$emoticonList.outerWidth(list_width).outerHeight(list_height);
					
				$.each(Emoticons,function(i,v){
					var $emoticon_image;
					if(v!='touristtube')
						$emoticon_image = $('<div style="width: 60px;height: 30px; display: inline-block; text-align: center; cursor: pointer;"><img src="'+AbsolutePath+'/images/Emoticons/'+v+'.png" /></div>');
					else
						$emoticon_image = $('<div style="width: 118px;height: 30px; display: inline-block; text-align: center; cursor: pointer;"><img src="'+AbsolutePath+'/images/Emoticons/'+v+'.png" /></div>');
						
					$emoticon_image.click(function(){
						var emoticon_text = EmoticonText[v][0];
						var chat_text = $chatInput.val();
						if( (chat_text.length != 0) && (chat_text[chat_text.length - 1] != ' ') ){
							chat_text += ' ';
						}
						$('#emoticonList').remove();
						$chatInput.val( chat_text + emoticon_text ).focus();
					});
					$emoticonList.append($emoticon_image);
				});
					
			});
			$chatLog.jScrollPane();
		}*/
		
		//$chatInput.val('').css({top : pos.top, left : pos.left }).show().focus();
		
		
		$chatClose.click(function(){
			ChatWindows--;
			$newdiv.remove();
			$chatDiv.remove();
			$chatInput.remove();
			//$emoticon.hide();
			//$('#emoticonList').remove();
			SizeAllTabs();
			manageExtraLists();
		});
		
		$chatMinimize.click(function(){
			$chatDiv.hide();
			$chatDiv.parent().find('.quickuploadcontainer').hide();
			$chatInput.hide();
			//$emoticon.hide();
			//$('#emoticonList').remove();
			SizeAllTabs();
		});
		
		//clicking on the $newdiv (footer tab) should bring up the $chatDiv and $chatInput and $emoticon repositioning them
		$newdiv.click(function(){
			
			ToWho = uid;
			WhichStorage.setItem('log_' + ToWho + "msgnum",0);
			$newdiv.find('span.status').html('');
			
			//$('#emoticonList').remove();
			
			if( $chatDiv.is(':visible') ){
				//no active chat windows
				$chatDiv.hide();
				$chatDiv.parent().find('.quickuploadcontainer').hide();
				$chatInput.hide();
			}else{
				//a new active chat window
				$chatDiv.show();
				$chatDiv.parent().find('.quickuploadcontainer').show();
				initScrollPane($chatLog);
				$chatInput.show();
				//if there were unread messages remove them now
				$newdiv.removeClass('activeChat');
				$newdiv.find(".LeftTuberName").css('color','#FFF');
			}
			//resize all tabs
			SizeAllTabs();
			
		});
		
		if(active){
			$newdiv.click();
			//$newdiv.click(); used to need triple click for chrome bug.
			//$newdiv.click();
		}
		
		
		$chatSet.click(function () {
		
			
			var $one_chat_settings = $(this).parent().parent().find(".one-chat-settings");

			if(!($one_chat_settings.is(":visible"))){
				
				$(this).css('background','url('+AbsolutePath+'/images/chatSetBtn-hover.jpg) center center no-repeat');
				$one_chat_settings.show();
				
				$("#viewChatHistory"+uid).click(function () {
					viewChatHistory(uid);
					$(this).parent().parent().find('.ChatSet').trigger('click');	
				});
				
			}else{
				$(this).css('background','url('+AbsolutePath+'/images/chatSetBtn.jpg) center center no-repeat');
				$one_chat_settings.hide();
			}
			
			if($(this).parent().parent().find(".emoticonslist").is(":visible")) $(this).parent().find(".emoticonsIcon").trigger('click');
			
			
			
		});
		
		$chatEmoti.click(function () {
			
			var $one_chat_emolist = $(this).parent().parent().find(".emoticonslist");
			
			if(!($one_chat_emolist.is(":visible"))){
			
				$(this).css('background','url('+AbsolutePath+'/images/emotiIcon-hover.jpg) center center no-repeat');
				$one_chat_emolist.show().load(ReturnLink("/get_emoticons.php"),function () {
					emoticonsFunctions($chatEmoti);	
				});
				
				
			
			}else{
				$one_chat_emolist.hide();
				$(this).css('background','url('+AbsolutePath+'/images/emotiIcon.jpg) center center no-repeat');	
			}
			
		});
		
		$("#shareTuber").click(function () {
				var toID = $(this).parent().parent().attr('id');
				toID = toID.replace("ChatWindow","");
				
				viewTubersShare(toID);
		});
		
		
		
		//$chatLog.jScrollPane();
		//$chatLog.find(".jspDrag").css('background-color','#5f5f5f');
		
		initScrollPane($chatLog);
		initUpload('quickuploadContainer'+uid,'quickupload'+uid);
		
		
		$("#send_media").click(function () {
		
			var quickuploadbtn = $(this).parent().parent().parent().find('.quickuploadcontainer img').attr('id');
			alert(quickuploadbtn);
			$("#"+quickuploadbtn).click();
		
		});
		
		showhideTimeFunctions();
		//calculateChatWindowOffsets();
	}
}

function emoticonsFunctions(obj)
{
	var EMOTI_WIDTH_CONTAINER = 269;
	var EMOTI_WIDTH_ARROW = 38;
	$(".oneEmoTab").css('cursor','pointer').click(function () {
		
		var spanIndex = $(this).index() - 1;
		
		var $emotiContainer = $(this).parent().parent().find(".emoticonsContainer");
		var $emotiArrow = $(this).parent().find(".emotiArrow");

		$emotiArrow.animate({'left':spanIndex*EMOTI_WIDTH_ARROW+'px'},500);
		$emotiContainer.animate({'left':-(spanIndex*EMOTI_WIDTH_CONTAINER)+'px'},500);
		
	});
	
	$(".one_emoticon_div").css('cursor','pointer').click(function () {
		
		var currentChatInput = $(this).parent().parent().parent().parent().parent().find(".chatInput");
		var currentChatInputVal = currentChatInput.val();
		
		if(currentChatInputVal == "type something...") currentChatInputVal = "";
		
		currentChatInputVal += $(this).attr('title');
		
		currentChatInput.val(currentChatInputVal);
		currentChatInput.focus();
		
		obj.trigger('click');
		
	});
	
}

function manageExtraLists()
{
	var newdivshow = $(".one-chatLeftList:hidden:first");
	
	var tuberID = newdivshow.attr('data-tuber');
	var tuberName = newdivshow.attr('title');
	
	newdivshow.show().css('width',USUAL_CHAT_WIDTH+'px');
	
	$(".oneExtraItem[data-tuber = "+tuberID+"]").remove();
	calculateChatWindowOffsets();
		
}
function calculateChatWindowOffsets()
{
	var docWidth = $(document).width();	
	var chatListWidth = 230;
	var chatWindowsSize = $(".one-chatLeftList").size();
	var chatMaxWindows = Math.floor( ( docWidth - chatListWidth - 220 ) / 278 );
	

	
	if(chatWindowsSize > chatMaxWindows)
	{
		
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


function showhideTimeFunctions()
{
	$(".ChatHisMessage,.ChatMyMessage").unbind('mouseover');
	$(".ChatHisMessage,.ChatMyMessage").mouseover(function () {
			$(".ChatHisMessage,.ChatMyMessage").find('.Date').hide();
			$(this).find('.Date').show();	
	});
	$(".ChatHisMessage,.ChatMyMessage").unbind('mouseout');
	$(".ChatHisMessage,.ChatMyMessage").mouseout(function () {
		$(".ChatHisMessage,.ChatMyMessage").find('.Date').hide();
		
	});
}

function TotalInnerHeight($jobj){
	var th = 0;
	$jobj.children().each(function(){
		th += $(this).height();
	});
	return th;
}

function daysInMonth(month,year) {
	var m = [31,28,31,30,31,30,31,31,30,31,30,31];
	if (month != 1) return m[month];
	if (year%4 != 0) return m[1];
	if (year%100 == 0 && year%400 != 0) return m[1];
	return m[1] + 1;
}

function ActivateTab(from_id){
	
	$("#chat-leftList").find('div.one-chatLeftList').each(function(){
		/*if( ($(this).attr('tt:uid') == from_id) && !$(this).hasClass('activeChat') ){
			$(this).addClass('activeChat');
		}*/
		if( ($(this).attr('data-tuber') == from_id)){
			if(!$('#ChatWindow' + from_id).is(':visible')){
				ChatLogSaveMsgNumber(from_id+"msgnum",$(this));
				$(this).addClass('activeChat');
			}else if(!$(this).hasClass('activeChat') ){
				//$(this).addClass('activeChat');
				$(this).find(".LeftTuberName").css('color','#FFF');
			}
		}
	});
}

function AppendToLog(json_msg){
	if(json_msg.from.length == 0 ) return ;
	var from = json_msg.from;
	var to = json_msg.to;
	var msg = json_msg.msg;
	var ts = '';
	if(typeof json_msg.ts != 'undefined'){
		ts = json_msg.ts;
		
		var ts_arr = ts.split('-');
		
		var year = parseInt(ts_arr[0]);
		var month = parseInt(ts_arr[1]) - 1;
		var day = parseInt(ts_arr[2]);
		var hours = parseInt(ts_arr[3]);
		var minutes = parseInt(ts_arr[4]);
		var seconds = parseInt(ts_arr[5]);
		
		minutes -= TimeZone;
		if(minutes < 0){
			minutes+=60;
			hours--;
		}
		if(hours < 0){
			hours += 24;
			day--;
		}
		if(day <= 0){
			month--;
			if(month < 0){
				month = 11;
				year--;
			}
			day = daysInMonth(month,year);
		}
		var ts_js = new Date(year, month, day, hours, minutes, seconds);
		
		ts = getTS(ts_js);
	}else{
		ts = getTS();
	}
	var $chatLog;
	if( from == CHAT_USER_ID ){
		ActivateTab(to);
		var all_msg;
		var tuberName = 'Me';
		var tuberNameTo = $('#ChatListItem'+to).find(".one-chat-name span").text();
		all_msg = '<div class="ChatMyMessage">'+ '<span class="User">' + tuberName + '</span><span class="Date">' + ts + '</span><br/><span class="Msg">' + EmoticonTextReplace(msg) + '</span></div>';
		$chatLog = $('#ChatWindow' + to).find('div.ChatLog');
		ChatLogAppend($chatLog,all_msg);
		//$chatLog.scrollTop( TotalInnerHeight($chatLog) );
		ChatLogSave(tuberNameTo, ChatLogExtract($chatLog) );
	}else{
		ActivateTab(from);
		var all_msg;
		var tuberName = $('#ChatListItem'+from).find(".one-chat-name span").text();
		all_msg = '<div class="ChatHisMessage">'+ '<span class="User">' + tuberName + '</span><span class="Date">' + ts + '</span><br/><span class="Msg">' + EmoticonTextReplace(msg) + '</span></div>';
		$chatLog = $('#ChatWindow' + from).find('div.ChatLog');
		ChatLogAppend($chatLog,all_msg);
		//$chatLog.scrollTop( TotalInnerHeight($chatLog) );
		ChatLogSave(tuberName, ChatLogExtract($chatLog) );
		
	}
	
	
}

function loadChatFunctions()
{
	/*$(".one-chat-rec").click(function () {
		
		var uid = $(this).attr('tt:uid');
		AddTuberChat(uid);
		
	});*/
	$("#chatSetButton").click(function () {
		
		if(!($("#chatSettingsList").is(":visible"))){
			showChatSettings();
			
		}else{
			hideChatSettings();
		}
		
	});
	
	initFavorites();
	
}

function showChatSettings()
{
	$("#chatList").unbind('click');
	$("#chatSetButton").css('background','url('+AbsolutePath+'/images/settings-for-chat-hover.jpg)');
	$("#chatSettingsList").fadeIn(10,function () {

		$("#turnOffChat").click(function () {turnOffChat();});
		
		$("#addRemovedUser").click(function () {viewRemovedUsers();});
		
		$("#chatList").click(function () {hideChatSettings();});		
	});	
}

function hideChatSettings()
{
	
	$("#chatSetButton").css('background','url('+AbsolutePath+'/images/settings-for-chat.jpg)');
	$("#chatSettingsList").hide();	
}

function instantSearch(object)
{
	var tempSearch = object.val();
	if(tempSearch == "") $(".one-chat-rec").show();
	else{
		var chatRecs = $(".one-chat-rec").size();
		
		if(chatRecs == 0)
		{
			$("#chatList").show();
			if($("#chatList").css("display") !="none")
			{
				loadChatList();	
			}
		}
		$("#chatList").show();
		$(".one-chat-rec").each(function () {
			
			var currentChatName = $(this).find(".one-chat-name").html();
			currentChatName = (currentChatName).toLowerCase();
			if(currentChatName.indexOf(tempSearch) != -1) {$(this).show();}
			else {$(this).hide();}
		});	
	}
}

function checkIfExist(id)
{
	var exist = false;
	$(".one-chatLeftList").each(function () {
		if($(this).attr('data-tuber') == id)
		{
			exist = true;	
		}
	});
	return exist;
}

function chatListLoaded()
{
	return ($('#chatList').text() != '');
}

function reinitChatSCrollPane()
{

	$('#chat-container-inside').data('jsp').destroy();
	initChatScrollPane();
	
}	

function initChatScrollPane()
{
	$('.scroll-panenew').jScrollPane();
	$('.scroll-panenew .jspVerticalBar').css('right',0+"px").css('width','7px');
	$('.scroll-panenew .jspVerticalBar').css('opacity',0.43);	
}