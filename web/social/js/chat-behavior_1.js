/**
 * the web socket handle
 */
var chatIsOn = true;
var ws = null;

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
		{
			AddTuberChat(json_msg.to, false);
					alert("ok");
		}
				
		else
		{
				alert("ok");
			AddTuberChat(json_msg.from, false);
		}
		AppendToLog(json_msg);
	}else{
		alert("ok");
		setTimeout(function(){
			Receive(json_msg);
		},1000);
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
		
		setTimeout(function(){
			connect();
		});
		
	}
	
	ws.onopen = function(event){
		SocketOpened = true;
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
 
function replaceTuberSearch()
{

	if($.cookie("openchat") == "false")
	{
		var onlineTubers = getOnlineUserNB();
		
		if(onlineTubers == 1 ) onlineTubers = t("1 tuber");
		else onlineTubers = onlineTubers+ " "+t("tubers");
		
		$("#ttsearch").hide();
		$("#onlineTubersNb").show().html( t("You have %s online" , onlineTubers)  );	
	}else{
		$("#onlineTubersNb").hide().html('');
		$("#ttsearch").show();	
	}
}
 
function DisplayChangeStatus(uid, new_status){
		
	switch(new_status){
		case CHAT_STATUS_OFFLINE:
			$('#ChatListItem' + uid + ' div:first').removeClass().addClass('one-chat-img offline');
			//$('#ChatListItem' + uid + ' div.one-chat-name p').addClass('grey').removeClass('green red yellow').html('Offline');
			$('#ChatWindow' + uid + ' .ChatTitle').addClass('leftgrey2').removeClass('leftgreen2 leftred2 leftyellow2');
			//$('#ChatListItem' + uid ).attr('data-chat-status','offline');
			break;
		case CHAT_STATUS_ONLINE:
			$('#ChatListItem' + uid + ' div:first').removeClass().addClass('one-chat-img available');
			//$('#ChatListItem' + uid + ' div.one-chat-name p').addClass('green').removeClass('red').removeClass('grey').html('Available');
			$('#ChatWindow' + uid + ' .ChatTitle').addClass('leftgreen2').removeClass('leftyellow2 leftred2 leftgrey2');
			//$('#ChatListItem' + uid ).attr('data-chat-status','available');
			break;
		case CHAT_STATUS_AWAY:
			$('#ChatListItem' + uid + ' div:first').removeClass().addClass('one-chat-img away');
			//$('#ChatListItem' + uid + ' div.one-chat-name p').addClass('yellow').removeClass('green red grey').html('Away');
			$('#ChatWindow' + uid + ' .ChatTitle').addClass('leftyellow2').removeClass('leftgreen2 leftred2 leftgrey2');
			//$('#ChatListItem' + uid ).attr('data-chat-status','away');
			break;
		case CHAT_STATUS_BUSY:
			$('#ChatListItem' + uid + ' div:first').removeClass().addClass('one-chat-img busy');
			//$('#ChatListItem' + uid + ' div.one-chat-name p').addClass('red').removeClass('yellow green grey').html('Busy');
			$('#ChatWindow' + uid + ' .ChatTitle').addClass('leftred2').removeClass('leftyellow2 leftgreen2 leftgrey2');
			//$('#ChatListItem' + uid ).attr('data-chat-status','busy');
			break;
	}
	replaceTuberSearch();
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
		connect();
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
	$("#switchatOn").show().fadeIn(10,function () {
		$("#switchatOn").click(function () {
			$("#switchatOn").hide();
			$("#chatList").show();
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


$(document).ready(function () {
		
	/*connect();
	ConnectInterval = setInterval(function(){
		PostConnect();
	}, 500);*/
	
	
	$("#historychat").click(function () {
		var documentWidth = $(document).width();
		var loadLink = ReturnLink("/get_chat_history.php?to=22");
		$("#chathistorydiv").css('left',(documentWidth-700)/2).show().load(loadLink); 
		
			
	});
	
	
	turnOnChat();
	
	$("#ttsearch").focus(function () {
		$(this).val("");
	});
	
	$("#ttsearch").blur(function () {
		$(this).val("Search for a friend to chat with...");
	});
	
	$("#openChatList").click(function () {
		$("#chatList").toggle();
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
	
	loadKeyEvents();
	
	//$("#openChatList").click();
	
	if($.cookie("openchat")!="false"){
		$("#chatList").toggle();
		if($("#chatList").css("display") !="none"){
			$.cookie("openchat", "true",{path: '/'});
			loadChatList();
		}
	}
});

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
var USUAL_CHAT_WIDTH = 257; //actually 275 - (the inputs 1px border *2) - 16 padding pixels of chatDiv
var MINIMIZED_CHAT_WIDTH = 112;

/**
 * sets the proper size on all tab sizes in the chat footer
 */
function SizeAllTabs(){
	var available_width = $(window).width() - $('#chat-search').width();
	
	if( ChatWindows > Math.floor(available_width/MINIMIZED_CHAT_WIDTH)){
		var margin = parseInt( $("div.one-chatLeftList").css('margin-right').replace('px','') );
		var $vis = $('div.ChatWindow:visible');
		var active_width = 0;
		var SingleLength = 0;
		if($vis.length != 0){
			active_width = $vis.width();
			SingleLength = (available_width - active_width - (ChatWindows+2)*margin ) / (ChatWindows-1); //parseInt( 
		}else{
			SingleLength = (available_width - (ChatWindows+2)*margin ) / ChatWindows; //parseInt( 
		}
		
		$("div.one-chatLeftList").width( SingleLength );
	}else{
		$("div.one-chatLeftList").width( MINIMIZED_CHAT_WIDTH );
	}
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
	$('#chatInput').hide();
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
		obj.find('span').html('&nbsp;'+num);	
		WhichStorage.setItem('log_' + whoid,num);
	}
}

/**
 * returns a tubers chat log message number
 * @param string who which tuber's chat log
 * @return string|null
 */
function ChatLogGetMsgNumber(whoid){
	//$.cookie(tuberName);
	if(typeof WhichStorage != 'undefined') return WhichStorage.getItem('log_' + whoid);
	return null;
}

/**
 * the active chat log. (inside the chat window)
 */
var $ActiveChatLog = null;
/**
 * the activbe chat tab. (inside the chat footer)
 */
var $ActiveChatTab = null;

var d = new Date()
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
		var $newdiv = $("<div class='one-chatLeftList left"+statusClass+"' data-tuber='"+uid+"' title='"+tuberName+"'>"+tuberName+"<span></span></div>");
		$("#chat-leftList").append($newdiv);
		
		
		
		//the $chatDiv is the chat window where the chat menu and chat log will appear 
		var $chatDiv = $('<div class="ChatWindow" id="ChatWindow'+uid+'"></div>').css({'z-index': varChatZIndex});
		$('body').append($chatDiv);
		$('.ChatWindow').hide();

		
		var $chatSettings = $("<div class='one-chat-settings'><div class='one-chat-send-files'>"+t('send files')+"</div></div>");
		//$chatSettings.appendTo($chatDiv);
		var $chatTitle = $("<div class='ChatTitle left"+statusClass+"2'>"+tuberName+"</div>");
		
		var $chatClose = $('<div class="ChatCloseImg" title="Close"></div>');
		var $chatMinimize = $('<div class="ChatMinimize" title="Minimize"></div>');
		var $chatSet = $('<div class="ChatSet" title="Settings"></div>');
		
		$chatTitle.append($chatClose).append($chatSet).append($chatMinimize).appendTo($chatDiv);
		var chtold= ChatLogGet(tuberName);
		if(chtold==null){
			chtold="";	
		}
		
		var $chatLog = $("<div class='ChatLog'  id='dataTuber"+uid+"'>"+chtold+"</div>");
		$chatLog.appendTo($chatDiv);
		
		$chatLog.scrollTop( TotalInnerHeight($chatLog) );
		
		ChatWindows++;

		//$chatDiv.show();
		$chatDiv.hide();
		
		//SizeAllTabs();
		
		//var pos = $newdiv.offset();
		//$chatDiv.css({top: pos.top - $chatDiv.height() , left: pos.left });
		//$newdiv.outerWidth( $chatDiv.width() );
		
		$chatInput = $('#chatInput');
		
		if($chatInput.length == 0)
		{
			$chatInput = $('<input type="text" id="chatInput" value="type something..."/>').css({
				'position':'absolute' ,
				'width' : '244px',
				'z-index' : varChatZIndex,
				'display' : 'none',
				border : '1px solid #c0c0c2'}).outerHeight($newdiv.outerHeight()).appendTo('body').keyup(function(event){
				if(event.keyCode == 13){
					var msg = $(this).val();
					if(msg == '') return ;
					SendMsgTo(ToWho , msg);
					$(this).val('');
					$ActiveChatLog.append('<div class="ChatMyMessage"><span class="User">'+t("Me")+'</span><span class="Date">' + getTS() + '</span><br/><span class="Msg">' + EmoticonTextReplace(msg) + '</span></div>');
					$ActiveChatLog.scrollTop( TotalInnerHeight($ActiveChatLog) );
					ChatLogSave($ActiveChatLog.parent().find('.ChatTitle').text() , $ActiveChatLog.html() );
				}
			});
		}
		
		$quickupload = $('#quickupload');
		
		$quickupload = $('<img src="'+AbsolutePath+'/media/images/quickupload.jpg" id="quickupload">').css({
				'position':'absolute',
				'display':'none',
				'z-index' : varChatZIndex + 1,
				cursor: 'pointer'}).appendTo('body');
		
		initUpload($chatLog.attr('id'));
		/*$emoticon = $('#emoticonSelect');
		if($emoticon.length == 0)
		{
			var dim = 17;
			$emoticon = $('<img src="'+AbsolutePath+'/media/images/emoticon.jpg" id="emoticonSelect">').css({
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
						$emoticon_image = $('<div style="width: 60px;height: 30px; display: inline-block; text-align: center; cursor: pointer;"><img src="'+AbsolutePath+'/media/images/Emoticons/'+v+'.png" /></div>');
					else
						$emoticon_image = $('<div style="width: 118px;height: 30px; display: inline-block; text-align: center; cursor: pointer;"><img src="'+AbsolutePath+'/media/images/Emoticons/'+v+'.png" /></div>');
						
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
		
		$(window).scroll(function(){
			if( $chatInput.is(':visible') ){
				$('.ChatMinimize').click();
			}
		}).resize(function(){
			if( $chatInput.is(':visible') ){
				$('.ChatMinimize').click();				
			}
			SizeAllTabs();
		});
		
		
		
		
		$chatClose.click(function(){
			ChatWindows--;
			$newdiv.remove();
			$chatDiv.remove();
			$chatInput.hide();
			//$emoticon.hide();
			//$('#emoticonList').remove();
			$ActiveChatTab = null;
		});
		
		$chatMinimize.click(function(){
			$newdiv.removeClass('activeChat');
			$chatDiv.hide();
			$chatInput.hide();
			//$emoticon.hide();
			//$('#emoticonList').remove();
			SizeAllTabs();
			$ActiveChatTab = null;
		});
		
		//clicking on the $newdiv (footer tab) should bring up the $chatDiv and $chatInput and $emoticon repositioning them
		$newdiv.click(function(){
			
			ToWho = uid;
			WhichStorage.setItem('log_' + ToWho + "msgnum",0);
			$newdiv.find('span').html('');
			
			//$('#emoticonList').remove();
			
			if( $chatDiv.is(':visible') ){
				//no active chat windows
				$('.ChatWindow').hide();
				$chatDiv.hide();
				$ActiveChatLog = null;
				$ActiveChatTab = null;
			}else{
				//a new active chat window
				$('.ChatWindow').hide();
				$chatDiv.show();
				$ActiveChatLog = $chatLog;
				$ActiveChatTab = $newdiv;
			}
			//resize all tabs
			SizeAllTabs();
			
			//the active $ActiveChatTab will be wider than the inactive ones
			
			$newdiv.removeClass('activeChat');
			
			//this is constant but keep it here in case css changes
			var newdiv_padding = $newdiv.css('padding-left');
			newdiv_padding = parseInt(newdiv_padding.replace('px',''));
			
			//if the window is visible position everything
			if( $chatDiv.is(':visible') ){
				$newdiv.width( USUAL_CHAT_WIDTH );//$chatDiv.width() - newdiv_padding
				var pos = $newdiv.offset();
				$chatDiv.css({top: pos.top - $chatDiv.outerHeight(true) , left: pos.left });
				$chatInput.val('').css({top : pos.top, left : pos.left }).show().focus();
				$quickupload.css({top : pos.top, left : pos.left + 5 }).show();
				//$chatInput.width( $chatDiv.innerWidth() ); //put this is css?
				//$emoticon.css({top : pos.top + 6, left : pos.left + ($chatInput.width() - $emoticon.width())}).show();
				$ActiveChatLog.scrollTop( TotalInnerHeight($ActiveChatLog) );
			}
		});
		
		if(active){
			$newdiv.click();
			//$newdiv.click(); used to need triple click for chrome bug.
			//$newdiv.click();
			$ActiveChatLog.scrollTop( TotalInnerHeight($ActiveChatLog) );
		}
	}
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
	if( ($ActiveChatTab != null) && ($ActiveChatTab.attr('tt:id') == from_id) ){
		return ;
	}
	$("#chat-leftList").find('div.one-chatLeftList').each(function(){
		/*if( ($(this).attr('tt:uid') == from_id) && !$(this).hasClass('activeChat') ){
			$(this).addClass('activeChat');
		}*/
		if( ($(this).attr('data-tuber') == from_id)){
			if(!$('#ChatWindow' + from_id).is(':visible')){
				ChatLogSaveMsgNumber(from_id+"msgnum",$(this));
			}
			if(!$(this).hasClass('activeChat') ){
				$(this).addClass('activeChat');
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
	
	if( from == CHAT_USER_ID ){
		ActivateTab(to);
		var all_msg;
		var tuberName = 'Me';
		var tuberNameTo = $('#ChatListItem'+to).find(".one-chat-name span").text();
		all_msg = '<div class="ChatMyMessage">'+ '<span class="User">' + tuberName + '</span><span class="Date">' + ts + '</span><br/><span class="Msg">' + EmoticonTextReplace(msg) + '</span></div>';
		var $chatLog = $('#ChatWindow' + to).find('div.ChatLog');
		$chatLog.append(all_msg);
		$chatLog.scrollTop( TotalInnerHeight($chatLog) );
		ChatLogSave(tuberNameTo, $chatLog.html() );
	}else{
		ActivateTab(from);
		var all_msg;
		var tuberName = $('#ChatListItem'+from).find(".one-chat-name span").text();
		all_msg = '<div class="ChatHisMessage">'+ '<span class="User">' + tuberName + '</span><span class="Date">' + ts + '</span><br/><span class="Msg">' + EmoticonTextReplace(msg) + '</span></div>';
		var $chatLog = $('#ChatWindow' + from).find('div.ChatLog');
		$chatLog.append(all_msg);
		$chatLog.scrollTop( TotalInnerHeight($chatLog) );
		ChatLogSave(tuberName, $chatLog.html() );
		
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
	
}

function showChatSettings()
{
	$("#chatList").unbind('click');
	$("#chatSetButton").css('background','url(/media/images/settings-for-chat-hover.jpg)');
	$("#chatSettingsList").fadeIn(10,function () {

		$("#turnOffChat").click(function () {turnOffChat();});
		
		$("#chatList").click(function () {hideChatSettings();});		
	});	
}

function hideChatSettings()
{
	
	$("#chatSetButton").css('background','url(/media/images/settings-for-chat.jpg)');
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
