var currentpage=0;
var currentsponsoringpage=0;
var fr_txt="";
var to_txt="";
var TO_CAL;

$(document).ready(function(){
	InitCalendar();
	initItemList();
	initCalendarEvents();
	
	$(".loadmoreupcommingevents").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		currentpage++;
		getUpcommingEventsDataRelated();
	});
	$(document).on('click',".hideDiv_event" ,function(){
		showhideEventsItem($(this),0);
	});
	$(document).on('click',".hideText_event" ,function(){
		var $parent=$(this).closest('.itemList');			
		showhideEventsItem($parent.find('.hideDiv_event'),1);
	});
	$(document).on('click',".itemList_right_remove" ,function(){
		var $parent=$(this).closest('.itemList');
		removeEvents($parent);		
	});
	$(".loadmoresponsoringevents").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		currentsponsoringpage++;
		getSponsoringEventsDataRelated();
	});
	$(document).on('click',".hideDiv_sponsoring" ,function(){
		showhideSponsoringItem($(this),0);
	});
	$(document).on('click',".hideText_sponsoring" ,function(){
		var $parent=$(this).closest('.itemList');			
		showhideSponsoringItem($parent.find('.hideDiv_sponsoring'),1);
	});
	$(document).on('click',".itemList_right_remove_sponsoring" ,function(){
		var $parent=$(this).closest('.itemList');
		removeSponsoringEvents($parent);		
	});
	$(document).on('click',".searchBtn_events" ,function(){
		var searchString=getObjectData($('.searchField'));
		fr_txt=""+$('#fromtxt').attr('data-cal');
		to_txt=""+$('#totxt').attr('data-cal');
		var strlink="";
		if(searchString.length!=0){
			strlink += "search-string/"+searchString;
		}
		if(fr_txt!=""){
			if(strlink.length!=0){
				strlink += "/";
			}
			strlink += "from/"+fr_txt;	
		}
		if(to_txt!=""){
			if(strlink.length!=0){
				strlink += "/";
			}
			strlink += "to/"+to_txt;	
		}
		if(strlink.length!=0){			
			window.location.href = ReturnLink('/channel-events-search/'+strlink);
		}
	});
});
function checkSubmitSearch(e){
   if(e && e.keyCode == 13){
      $('.searchBtn_events').click();
   }
}
function InitCalendar(){
	
	Calendar.setup({
		inputField : "fromtxt",
                noScroll  	 : true,
		trigger    : "frombutcontainer",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());
			
		    TO_CAL.args.min = date;
		    TO_CAL.redraw();
			$('#fromtxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalTo(this);
			this.hide();
		},
		dateFormat : "%d / %m / %Y"
	});
	TO_CAL=Calendar.setup({
		inputField : "totxt",
                noScroll  	 : true,
		trigger    : "tobutcontainer",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());		   
			$('#totxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalTo(this);
			this.hide();
		},
		dateFormat : "%d / %m / %Y"
	});
	
}
function addCalTo(cals){
	if(new Date($('#fromtxt').attr('data-cal'))>new Date($('#totxt').attr('data-cal'))){
		$('#totxt').attr('data-cal',$('#fromtxt').attr('data-cal'));
		$('#totxt').html($('#fromtxt').html());
	}
}
function initItemList(){
	$(".itemList").each(function(){
		var $this = $(this);
		$this.on('mouseenter',function(){
			$this.find('.hideDivClass').show();
			$this.find('.itemList_right_buttons').show();
		}).on('mouseleave',function(){
			$this.find('.hideDivClass').hide();
			$this.find('.itemList_right_buttons').hide();
		});
	});
}
function getSponsoringEventsDataRelated(){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmoresponsoringevents.php'), {currentpage:currentsponsoringpage,one_object:one_object},function(data){
		one_object=0;
		if(data!=false){
			$('.sponsoringcontainer .upcommingcontent').append(data);
			var currPageStatus=$('.sponsoringcontainer .currPageStatus');
			if((""+currPageStatus.attr('data-value'))=="0"){
				$(".loadmoresponsoringevents").hide();
			}else{
				$(".loadmoresponsoringevents").show();	
			}
			$('.sponsoringcontainer .sponsoringcontainer_title span.channelyellowevtttl').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();
			initItemList();
			
		}else{
			$(".loadmoreupcommingevents").hide();	
		}
		
		$('.upload-overlay-loading-fix').hide();
	});
}
function getUpcommingEventsDataRelated(){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmoreupcommingevents.php'), {currentpage:currentpage,one_object:one_object},function(data){
		one_object=0;
		if(data!=false){
			$('.upcommingcontainer .upcommingcontent').append(data);
			var currPageStatus=$('.upcommingcontainer .currPageStatus');
			if((""+currPageStatus.attr('data-value'))=="0"){
				$(".loadmoreupcommingevents").hide();
			}else{
				$(".loadmoreupcommingevents").show();	
			}
			$('.upcommingcontainer .upcommingcontainer_title span.channelyellowevtttl').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();
			initItemList();
			
		}else{
			$(".loadmoreupcommingevents").hide();	
		}
		
		$('.upload-overlay-loading-fix').hide();
	});
}
function showhideEventsItem(obj,val){
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/ajax_show_hide_events.php'),
		data: {id:obj.attr('data-id'),channel_id:obj.attr('data-channelid'),data:val},
		type: 'post',
		success: function(data){			
			if(data){				
				var $parent=obj.parent().parent();
				if(val==0){
					$parent.find('.hideitems').removeClass('displaynone');
				}else{
					$parent.find('.hideitems').addClass('displaynone');
				}
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});
}
function showhideSponsoringItem(obj,val){
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/ajax_show_hide_sponsoring_events.php'),
		data: {id:obj.attr('data-id'),channel_id:obj.attr('data-channelid'),data:val},
		type: 'post',
		success: function(data){			
			if(data){				
				var $parent=obj.parent().parent();
				if(val==0){
					$parent.find('.hideitems').removeClass('displaynone');
				}else{
					$parent.find('.hideitems').addClass('displaynone');
				}
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});
}
function removeEvents(obj){
	TTAlert({
		msg: t("Canceling this event cannot be undone.<br/>This action deletes it from all joining people's event's list."),
		type: 'action',
		btn1: t('cancel'),
		btn2: t('confirm'),
		btn2Callback: function(data){
			if(data){
				$('.upload-overlay-loading-fix').show();
				$.ajax({
					url: ReturnLink('/ajax/info_page_event_delete_manage_channel.php'),
					data: {globchannelid:obj.attr('data-channelid'),id:obj.attr('data-id')},
					type: 'post',
					success: function(e){
						if(e){
							obj.remove();
							if($(".loadmoreupcommingevents").css('display')!="none"){
								one_object=1;
								getUpcommingEventsDataRelated();
							}
						}
						$('.upload-overlay-loading-fix').hide();
					}
				});	
			}
		}
	});
}
function removeSponsoringEvents(obj){
	TTAlert({
		msg: t('are you sure you want to stop sponsoring this event?'),
		type: 'action',
		btn1: t('cancel'),
		btn2: t('confirm'),
		btn2Callback: function(data){
			if(data){
				$('.upload-overlay-loading-fix').show();
				$.ajax({
					url: ReturnLink('/ajax/info_page_sponsoring_event_delete.php'),
					data: {globchannelid:obj.attr('data-channelid'),id:obj.attr('data-id')},
					type: 'post',
					success: function(e){
						if(e){
							obj.remove();
							if($(".loadmoresponsoringevents").css('display')!="none"){
								one_object=1;
								getSponsoringEventsDataRelated();
							}
						}
						$('.upload-overlay-loading-fix').hide();
					}
				});
			}
		}
	});
}