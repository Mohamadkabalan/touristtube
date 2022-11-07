var currentpage = 0;
var fr_txt="";
var to_txt="";
var TO_CAL;
var one_object=0;
var globorderby="";
var privacyValue=0;
var privacyArray=new Array();

$(document).ready(function() {
     $(document).on('click', "#resetpagebut", function () {
        document.location.reload();
    });
	InitCalendar();
	InitDocument();
	if(parseInt(is_owner)==1){
		initUserCalendarEvents();
	}
	
	$(document).on('click',"#hide_button" ,function(){
		updateeventpagedata("is_visible",0,$(this).closest('.itemList'));
	});
	$(document).on('click',".hideText" ,function(){
		updateeventpagedata("is_visible",1,$(this).closest('.itemList'));
	});
	$(document).on('click',".DeleteEventAction" ,function(){
		removeevent($(this).closest('.itemList'));
	});
	$("#load_more_next").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		currentpage++;
		getEventsDataRelated(1);
	});
	$(document).on('click',"#hide_button_viewer" ,function(){
		$(this).closest('.itemList').remove();		
		if($("#load_more_next").css('display')!="none"){
			one_object=1;
			getEventsDataRelated(1);
		}else{
			$(".list_container").attr("data-skip",skipnumber);	
		}
	});
	$("#searchCalendarbut").click(function(){
		if($('#fromtxt').html()!='' || $('#totxt').html()!=''){
			fr_txt=""+$('#fromtxt').attr('data-cal');
			to_txt=""+$('#totxt').attr('data-cal');			
			currentpage=0;
			$(".list_container").attr("data-skip",0);
			$('.list_container').html('');
			getEventsDataRelated(0);
		}
	});
	$("#searchbut").click(function(){
		txt_srch_init=""+$("#srchtxt").val();
		if(txt_srch_init==$("#srchtxt").attr('data-value')){
			txt_srch_init="";
		}
		
		globorderby=$("#sortby").val();
		
		currentpage=0;
		$(".list_container").attr("data-skip",0);
		$('.list_container').html('');
		getEventsDataRelated(0);
	});
});
function initResetIcon(obj){
	obj.removeClass('privacy_icon1');
	obj.removeClass('privacy_icon2');
	obj.removeClass('privacy_icon3');
	obj.removeClass('privacy_icon4');
	obj.removeClass('privacy_icon5');
	obj.removeClass('privacy_icon6');
}
function initResetSelectedUsers(obj){
	resetSelectedUsers(obj);
}
function InitDocument(){	
	$(".event_data").each(function(){
		var $this = $(this);
		var hide = $this.find(".hide");
		$this
			.mouseenter(function(){
				hide.removeClass("hide");
			})
			.mouseleave(function(){
				hide.addClass("hide");
			})
	});
}
function InitCalendar(){
	Calendar.setup({
		inputField : "fromtxt",
                noScroll  	 : true,
            fixed: true,
		trigger    : "frombutcontainer",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());
		    TO_CAL.args.min = date;
		    TO_CAL.redraw();
			$('#fromtxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalToEvent(this);
			this.hide();
		},
		dateFormat : "%d / %m / %Y"
	});
	TO_CAL=Calendar.setup({
		inputField : "totxt",
                noScroll  	 : true,
            fixed: true,
		trigger    : "tobutcontainer",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());
			$('#totxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalToEvent(this);
			this.hide();
		},
		dateFormat : "%d / %m / %Y"
	});
}
function addCalToEvent(cals){
	if(new Date($('#fromtxt').attr('data-cal'))>new Date($('#totxt').attr('data-cal'))){
		$('#totxt').attr('data-cal',$('#fromtxt').attr('data-cal'));
		$('#totxt').val($('#fromtxt').val());
	}
}
function getEventsDataRelated(skip){
	var skipnumber = skip;
	if(skip==1){
		skipnumber=parseInt($(".list_container").attr("data-skip"));
	}
	
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmoreTTevent.php'), {user_id:userGlobalID(),txt_srch_init:txt_srch_init,globorderby:globorderby,currentpage:currentpage,frtxt:fr_txt,totxt:to_txt,one_object:one_object,skip:skipnumber},function(data){
		if(data!=false){
			$('.list_container').append(data);
			var currPageStatus=$('.list_container .currPageStatus');
			
			if((""+currPageStatus.attr('data-value'))=="0"){
				$("#load_more_next").hide();
			}else{
				$("#load_more_next").show();	
			}
			$('.event_text span.yellowbold12').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();			
			InitDocument();
		}else{
			$("#load_more_next").hide();	
		}
		if(skip==1 && one_object>0){
			skipnumber++;
			$(".list_container").attr("data-skip",skipnumber);	
		}
		one_object=0;
		$('.upload-overlay-loading-fix').hide();
	});
}
function removeevent(obj){
	var id=obj.attr('data-id');
	TTAlert({
		msg: t("Canceling this event cannot be undone.<br/>This action deletes it from all joining people events' list."),
		type: 'action',
		btn1: t('cancel'),
		btn2: t('confirm'),
		btn2Callback: function(data){
			if(data){
				updateeventpagedata('remove',-2,obj);
			}
		}
	});
}
function updateeventpagedata(param1,param2,obj){
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_TT_event_update.php'),
		data: {str:param1,data:param2,id:obj.attr('data-id')},
		type: 'post',
		success: function(data){
			if(param1=="is_visible"){
				if(parseInt(param2)==0){
					obj.find('.hideitems').removeClass('displaynone');
					obj.find('.DeleteEventAction').addClass('displaynone');
					obj.find('.EditEventAction').addClass('displaynone');
					obj.addClass('inactive');
				}else{
					obj.find('.hideitems').addClass('displaynone');
					obj.find('.DeleteEventAction').removeClass('displaynone');
					obj.find('.EditEventAction').removeClass('displaynone');
					obj.removeClass('inactive');	
				}
			}else if(param1=="remove"){
				obj.remove();
				one_object=1;	
				getEventsDataRelated(-1);
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});	
}
function initUserCalendarEvents(){
	// Calendar Setup
	var DateIndex=0;
	var currDateIndex=0;
	var calTimer="";
	var timer;
	EventsDetailedCal = Calendar.setup(	{
		
		cont          : "idEventsCalendar_user",
                noScroll  	 : true,
		bottomBar 	 : false,
		selectionType : Calendar.SEL_MULTIPLE,
		disabled: function() { 
			return true; 
		},
		dateInfo : getDateUserInfo
		,
		onChange : function() 
		{
			// set time to do js hover after rendering the calendar
			var DateIndex = '';
			if(calTimer !=""){
				clearTimeout(calTimer);
			}
						
			calTimer=setTimeout(function()
			{
				// check the selected days only
				$(".DynarchCalendar-day-selected.highlight_user, .DynarchCalendar-day-selected.highlight_user2").each(function(index, element) {
                    $(this).parent().mouseenter(function(){						
						// get the date from div attribute
						DateIndex = $(this).find('.DynarchCalendar-day-selected').attr('dyc-date');
						currDateIndex=0;
						var currdata=DATE_USER_INFOInit[DateIndex][0];
						// set the values in tooltip from object and set the position
						$(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalEventImg").attr('src', currdata['imageurl']);
						var link_a=ReturnLink('/events-detailed/'+currdata['id']);
						$(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a").attr('href', link_a);
						$(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalTooltipTitle").html(currdata['title']);
						$(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltipClose").attr('data-DateIndex',DateIndex);
						var TooltipTop = $(this).position().top;
						var TooltipLeft = $(this).position().left;
						$(".ed_CalTooltipContent_user").css('margin-top',( TooltipTop + 63) + 'px').css('margin-left', (TooltipLeft - 33) + 'px').show();
					});	
					// hide the tooltip after leave the mouse on tooltip and date selected
					$(this).parent().mouseleave(function(){						
						$(".ed_CalTooltipContent_user").hide();
						//set time to detect if mouse leave the date and tooltip
						/*timer = setTimeout(function()
						{
							$(".ed_CalTooltipContent_user").hide();
						}, 10);
						$('.ed_CalTooltipContent_user').hover(function()
						{
							// if the mouse hover the tooltip clear time to hide it
							clearTimeout(timer);
						});*/
					});
                });							
				
			}, 500);
		}
	});
	$('.ed_CalTooltipContent_user').mouseenter(function(){
		// if the mouse hover the tooltip clear time to hide it
		$(this).show();
	});
	$('.ed_CalTooltipContent_user').mouseleave(function(){
		// if the mouse hover the tooltip clear time to hide it
		$(this).hide();
	});
	// hide tooltip on close click
	$(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltipClose").click(function(){
		currDateIndex++;
		if(DATE_USER_INFOInit[$(this).attr('data-DateIndex')].length>currDateIndex){
			var currdata=DATE_USER_INFOInit[$(this).attr('data-DateIndex')][currDateIndex];
			// set the values in tooltip from object and set the position
			$(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalEventImg").attr('src', currdata['imageurl']);
			var link_a=ReturnLink('/events-detailed/'+currdata['id']);
			$(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a").attr('href', link_a);
			$(".ed_CalTooltipContent_user > .ed_CalTooltip > .ed_CalTooltip_a > .ed_CalTooltipTitle").html(currdata['title']);						
		}else{
			$(".ed_CalTooltipContent_user").hide();
		}
	});
	// hide tooltip when leave it
	$(".ed_CalTooltipContent_user .ed_CalTooltip").mouseleave(function()
	{
		$(".ed_CalTooltipContent_user").hide();
	});
	// retrive the dates selected in DATE_USER_INFO object
	var arrSelectionSet = new Array(), i=0;
	for (var key in DATE_USER_INFO) 
	{
		arrSelectionSet[i] = key;
		i++;
	}
	// select dates in calendar
	EventsDetailedCal.selection.set(arrSelectionSet);	
}
function getDateUserInfo(date, wantsClassName) {
  	var as_number = Calendar.dateToInt(date);
  
	var myDateArr={};
	if(DATE_USER_INFOInit[as_number]){
		var classArray=new Array();
		for(var i=0;i<DATE_USER_INFOInit[as_number].length;i++){
			var obj=DATE_USER_INFOInit[as_number][i];
			var klassStr=obj.klass;
			var flag=false;
			for(var j=0;j<classArray.length;j++){
				if(classArray[j]==klassStr) flag=true;
			}
			if(!flag){
				classArray.push(klassStr);	
			}		
		}
		var classArraystr='';
		for(var j=0;j<classArray.length;j++){
			var tmp = classArray[j].replace(/"/g,'');
			classArraystr += tmp+' ';
		}		
		myDateArr={klass:classArraystr,tooltip : ""};
	}
  return myDateArr;
};
function checkSubmit(e){
   if(e && e.keyCode == 13){
      $('#searchbut').click();
   }
}