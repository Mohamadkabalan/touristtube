var currentpagejoin=0;
var TO_CALNew;
var currentpageinvited=0;
var privacyValue=-1;
var privacyArray=new Array();

$( document ).ready(function(){
    if(parseInt(is_owner)==1){
            initUserCalendarEvents();
    }
    initSocialActions();
//    initLikes( $(".social_data_all") );
//    initComments( $(".social_data_all") );
//    initShares( $(".social_data_all") );

    initjoiningItemList();	
    initTTLogReportFunctions($(".event_UpcomingReport_menu"),$('.social_data_all'));
    
    $('.butEditChannelRight_data').mouseover(function () {
        var $parents = $('.evContainer2Over').parent();
        var posxx = $(this).offset().left - $parents.offset().left - 253;
        var posyy = $(this).offset().top - $parents.offset().top - 23;
        $('.evContainer2Over .ProfileHeaderOverin').html('edit');
        $('.evContainer2Over').css('left', posxx + 'px');
        $('.evContainer2Over').css('top', posyy + 'px');
        $('.evContainer2Over').stop().show();
    });
    $('.butEditChannelRight_data').mouseout(function () {
        $('.evContainer2Over').hide();
    });
    
    $('#privacy_icon_viewer').mouseover(function() {
        var diffx = $('#eventDetailedContainer').offset().left+255;
        var diffy = $('#eventDetailedContainer').offset().top+22;
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
	$("#whocanjoin_cont_select").change(function(){
		var selectval=parseInt($(this).val());
		
		if(selectval==USER_PRIVACY_SELECTED){
			$(this).closest('.show_edit_event').find('.peoplecontainer_custom').removeClass('displaynone');
			$('.edit_eventdate_button_container').hide();
			$('#edit_eventdata_but').hide();
		}else{
			$(this).closest('.show_edit_event').find('.peoplecontainer_custom').addClass('displaynone');
			$('.edit_eventdate_button_container').show();
			$('#edit_eventdata_but').show();
		}
		initResetIcon($('#privacy_icon_edit'));
		$('#privacy_icon_edit').addClass('privacy_icon'+selectval);
	});
	$(document).on('click',"#okeventdata" ,function(){
		var curob=$(this).parent().parent();
		privacyValue=parseInt($("#whocanjoin_cont_select").val());
		privacyArray=new Array();
		if(privacyValue==USER_PRIVACY_SELECTED){
			curob.find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function(){
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
		if( (privacyValue==USER_PRIVACY_SELECTED && privacyArray.length>0) || privacyValue!=USER_PRIVACY_SELECTED){
			$("#whocanjoin_cont_select").closest('.show_edit_event').find('.peoplecontainer_custom').addClass('displaynone');
			$('.edit_eventdate_button_container').show();
			$('#edit_eventdata_but').show();
		}else{
			TTAlert({
				msg: t('Invalid privacy data'),
				type: 'alert',
				btn1: t('ok')
			});	
		}
	});
	
	
	$(document).on('click',".sharepopup_butBRCancel" ,function(){
		$('.fancybox-close').click();
	});
	// right arrow menu
	$('.event_Arrow').click(function()
	{
		$('.event_List').toggle();
	});
	$(document).on('click',".event_Event_cancel" ,function(){
		var $parent=$(this).closest('#rightEventContainer');
		removeEvents($parent);
	});
	$(document).on('click',".loadmoreinvited" ,function(){
		currentpageinvited++;
		getInvitedDataRelated($(this).attr('data-id'));
	});
	
	$(document).on('click',".loadmorejoin" ,function(){
		currentpagejoin++;
		getJoiningDataRelated($(this).attr('data-id'));
	});
	$(document).on('change',".themelist_event" ,function(){
		var $parent=$('#rightEventContainer');
		updateeventpagedata('theme_id',$(this).val(),$parent);
	});
	$(document).on('click',"#edit_event_text" ,function(){
		$(this).hide();
		$(".event_EventTitle").hide();
		$(".event_EventDescription").hide();
		$(".event_Event_edit_content").show();
	});	
	$(document).on('click',"#edit_userevent_theme" ,function(){
		$(this).hide();
		$(".leftEventTop_text_name").hide();
		$(".leftEventTop_text_edit").show();
	});	
	
	$(document).on('click',"#canceltitledesc" ,function(){
		$(".editChannelRight_data13").show();
		$(".event_EventTitle").show();
		$(".event_EventDescription").show();
		$(".event_Event_edit_content").hide();
	});
	$(document).on('click',"#savetitledesc_edit" ,function(){
		var curob=$(this).parent().parent().parent();		
		var ed_EventTitle_txt_edit=getObjectData(curob.find('#event_EventTitle_txt_edit'));
		var ed_Eventdesc_txt_edit=getObjectData(curob.find('#event_Eventdesc_txt_edit'));
		
		if(checkEventDesc_Edit(ed_EventTitle_txt_edit,ed_Eventdesc_txt_edit)){
			updateeventpagedata("name",ed_EventTitle_txt_edit,$('#rightEventContainer'));
			updateeventpagedata("description",ed_Eventdesc_txt_edit,$('#rightEventContainer'));			
		}
	});
	$(document).on('click',"input:radio[name=event_join_detailed]" ,function(){
		saveJoinDetailedChange( $("#rightEventContainer").attr('data-id'), SOCIAL_ENTITY_USER_EVENTS );
	});
	$(document).on('change',"#join_guests_number_detailed" ,function(){
		saveJoinDetailedChange( $("#rightEventContainer").attr('data-id'), SOCIAL_ENTITY_USER_EVENTS );
	});
	$(document).on('click',"#edit_eventdata_but" ,function(){
		$(this).addClass('active');		
		/*initResetSelectedUsers($(this).parent().find('.peoplecontainer_custom .addmore input'));
		$(this).parent().find('.peoplecontainer_custom .uploadinfocheckbox').removeClass('active');
		$(this).parent().find('.peoplecontainer_custom .addmore input').val('');
		$(this).parent().find('.peoplecontainer_custom .addmore input').blur();
		$(this).parent().find('.peoplecontainer_custom .peoplesdata').each(function() {
			var parents=$(this);
			parents.remove();				
		});
		$(this).parent().find('.peoplecontainer_custom').addClass('displaynone');
		privacyValue=-1;
		privacyArray=new Array();*/
		$(".edit_eventdate_button_container").show();
		$(".show_edit_event").show();
                $("#whocanjoin_cont_select").change();
	});
	$(document).on('click',".show_edit_event .uploadinfocheckbox" ,function(){
            if($(this).hasClass('active')){
                $(this).removeClass('active');
            }else{
                $(this).addClass('active');
            }
	});
	$(document).on('click',"#canceleventdata" ,function(){
		$("#edit_eventdata_but").removeClass('active');				
		$(".edit_eventdate_button_container").hide();
		$(".show_edit_event").hide();
	});
	$(document).on('click',"#saveeventdata_edit" ,function(){
		var curob=$(this).parent().parent();
		var event_id=$("#rightEventContainer").attr('data-id');
		var locationevent=getObjectData(curob.find('#edit_eventlocation'));
		var data_country=curob.find('#edit_eventlocation').attr('data-country');
		var data_location=curob.find('#edit_eventlocation').attr('data-location');
		var data_lng=curob.find('#edit_eventlocation').attr('data-lng');
		var data_lat=curob.find('#edit_eventlocation').attr('data-lat');
		
		var fromdate=getObjectData(curob.find('#edit_eventdate'));
		var defaultEntry=curob.find('#defaultEntry_edit').val();
		var defaultEntryTo=curob.find('#defaultEntry_editTo').val();
		var todate=getObjectData(curob.find('#edit_eventdate1'));
		var guestevent=curob.find('#edit_eventguests').val();
		
		var caninvite= (curob.find('.uploadinfocheckbox_eventedit1').hasClass('active')) ? 1 : 0 ;
		var showguests= (curob.find('.uploadinfocheckbox_eventedit2').hasClass('active'))? 1 : 0 ;
		var enablesharecomments= (curob.find('.uploadinfocheckbox_eventedit5').hasClass('active'))? 1 : 0 ;
		
		if(checkEventInfo_Edit(locationevent , fromdate , todate , defaultEntry, defaultEntryTo , data_location , data_lng , data_lat )){
			$.ajax({
				url: ReturnLink('/ajax/info_event_edit_user.php'),
				data: { event_id:event_id,locationevent : locationevent , fromdate: fromdate ,fromdatetime : defaultEntry , todatetime : defaultEntryTo , todate: todate , guestevent:guestevent , caninvite:caninvite , showguests:showguests , enablesharecomments:enablesharecomments ,data_lng:data_lng,data_lat:data_lat,data_country:data_country,data_location:data_location },
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
							msg: t('Couldnt save please try again later'),
							type: 'alert',
							btn1: t('ok')
						});
						return ;
					}
					if(ret.status == 'ok'){
						privacyValue=parseInt($("#whocanjoin_cont_select").val());
						if( privacyValue==USER_PRIVACY_SELECTED && privacyArray.length==0){
							window.location.reload();
						}else{
							TTCallAPI({
								what: 'user/privacy_extand/add',
								data: {privacyValue:privacyValue, privacyArray:privacyArray, entity_type:SOCIAL_ENTITY_USER_EVENTS, entity_id:ret.value},
								callback: function(ret){
									window.location.reload();
								}
							});
						}
					}else{	
						TTAlert({
							msg: ret.error,
							type: 'alert',
							btn1: t('ok')
						});	
					}
				}
			});
		}
	});
	
	$(document).on('click',"#i_minus" ,function(){
		var $this=$(this);
		TTCallAPI({
			what: 'social/invited_user/delete',
			data: {inviteid:$this.parent().attr('data-inviteid'),uid:$this.parent().attr('data-userid')},
			callback: function(ret){
				if( ret.status === 'error' ){
					TTAlert({
						msg: ret.error,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return;
				}
				showminus($this);				
			}
		});
	});
	
	$(document).on('click',"#i_plus" ,function(){
		var $this=$(this);
		TTCallAPI({
			what: 'social/invited_user/undodelete',
			data: {inviteid:$this.parent().attr('data-inviteid'),uid:$this.parent().attr('data-userid')},
			callback: function(ret){
				if( ret.status === 'error' ){
					TTAlert({
						msg: ret.error,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return;
				}
				showplus($this);				
			}
		});
	});
	
	$(document).on('click',"#j_minus" ,function(){
		var $this=$(this);
		TTCallAPI({
			what: 'user/join/delete',
			data: {id:$this.parent().attr('data-joinid')},
			callback: function(ret){
				if( ret.status === 'error' ){
					TTAlert({
						msg: ret.error,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return;
				}
				showminus($this);				
			}
		});
	});
	
	$(document).on('click',"#j_plus" ,function(){		
		var $this=$(this);
		TTCallAPI({
			what: 'user/join/undodelete',
			data: {id:$this.parent().attr('data-joinid')},
			callback: function(ret){
				if( ret.status === 'error' ){
					TTAlert({
						msg: ret.error,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return;
				}
				showplus($this);				
			}
		});
	});
	
	$(document).on('click',"#invite_userevent_user_popup_but" ,function(){
		var $this=$(this);
		var $parent=$this.parent().parent();
		var invite_msg = getObjectData($parent.find("#invitetext"));
		var inviteArray=new Array();
		$parent.find('.peoplecontainer .emailcontainer .peoplesdata').each(function(){
			var obj=$(this);
			if(obj.attr('id')=="friendsdata"){
				inviteArray.push( {friends : 1} );
			}else if(obj.attr('id')=="followersdata"){
				inviteArray.push( {followers : 1} );
			}else if( obj.attr('data-email') != '' ){
				inviteArray.push( {email : obj.attr('data-email') } );
			}else if( parseInt( obj.attr('data-id') ) != 0 ){
				inviteArray.push( {id : obj.attr('data-id') } );
			}
		});
		if(inviteArray.length==0){
			return;	
		}
		
		TTCallAPI({
			what: 'social/share',
			data: {entity_type:SOCIAL_ENTITY_USER_EVENTS, entity_id:$this.attr('data-id'), share_with : inviteArray, share_type: SOCIAL_SHARE_TYPE_INVITE , msg:invite_msg,addToFeeds:1 },
			callback: function(ret){
				window.location.reload();
			}
		});

	});
        
        if(parseInt(is_owner)==1){
		
		InitChannelUploaderHome($('.edit_event_pic').attr('id'),'rightEventContainer',15,0);
		
		
		$('#defaultEntry_edit').timeEntry({
			show24Hours: true
		});
                
		$('#defaultEntry_editTo').timeEntry({
			show24Hours: true
		});
                
		
		Calendar.setup({
			inputField : "edit_eventdate",
                noScroll  	 : true,
            fixed: true,
			trigger    : "edit_eventdate",
			align:"B",
			onSelect   : function() {
				var date = Calendar.intToDate(this.selection.get());
				TO_CALNew.args.min = date;
				TO_CALNew.redraw();
				$('#edit_eventdate').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
				addCalTo(this);
				this.hide();
			},
			dateFormat : "%d / %m / %Y"
		});
		TO_CALNew=Calendar.setup({
			inputField : "edit_eventdate1",
                noScroll  	 : true,
            fixed: true,
			trigger    : "edit_eventdate1",
			align:"B",
			onSelect   : function() {
				var date = Calendar.intToDate(this.selection.get());
				$('#edit_eventdate1').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
				
				addCalTo(this);
				this.hide();
			},
			dateFormat : "%d / %m / %Y"
		});
		
		var input = document.getElementById('edit_eventlocation');
		var searchBox = new google.maps.places.SearchBox(input);
		
		// Listen for the event fired when the user selects an item from the
		// pick list. Retrieve the matching places for that item.
		google.maps.event.addListener(searchBox, 'places_changed', function() {
			var places = searchBox.getPlaces();
			place = places[0];                        
                        try{
                           for(var i = 0; i < place.address_components.length; i += 1) {
                                var addressObj = place.address_components[i];
                                for(var j = 0; j < addressObj.types.length; j += 1) {
                                    if (addressObj.types[j] === 'country') {
                                        $('#edit_eventlocation').attr('data-country',addressObj.short_name);
                                    }
                                }
                            } 
                        }catch(e){
                            
                        }                        
			$('#edit_eventlocation').attr('data-location',place.formatted_address);				
			$('#edit_eventlocation').attr('data-lat',place.geometry.location.lat());
			$('#edit_eventlocation').attr('data-lng',place.geometry.location.lng());
		});
            
            var obj_box = $('.show_edit_event');
            var privacyselcted = parseInt(obj_box.find('#whocanjoin_cont_select').val());
            
            if (privacyselcted == USER_PRIVACY_SELECTED) {
                obj_box.show();
                $('.show_edit_event .peoplecontainer').removeClass('displaynone');
                obj_box.find('.peoplecontainer .emailcontainer .peoplesdata').each(function() {
                    var obj = $(this);
                    if (obj.attr('id') == "friendsdata") {
                        //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
                    } else if (obj.attr('id') == "friends_of_friends_data") {
                        //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
                    } else if (obj.attr('id') == "followersdata") {
                        //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
                    } else if (parseInt(obj.attr('data-id')) != 0) {
                        SelectedUsersAdd(obj.attr('data-id'),$('#addmoretext_custum_privacy_detailed'));                        
                        //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
                    }
                });
                obj_box.hide();
                $('.show_edit_event .peoplecontainer').addClass('displaynone');
            }
	}
});
function checkEventDesc_Edit(ed_EventTitle_txt_edit,ed_Eventdesc_txt_edit){
	if(ed_EventTitle_txt_edit.length == 0){
		TTAlert({
			msg: t('Invalid event title'),
			type: 'alert',
			btn1: t('ok')
		});
		return false;
	}else if(ed_Eventdesc_txt_edit.length == 0){
		TTAlert({
			msg: t('Invalid event description'),
			type: 'alert',
			btn1: t('ok')
		});
		return false;
	}
	return true;
}
function showminus(This){
	This.hide();
	This.parent().find(".event_InvitedName").addClass('event_InvitedName_minus');
	
	var $plus = This.parent().find('.j_plus_detailed');
	$plus.show();
};

function showplus(This){
	This.hide();
	This.parent().find(".event_InvitedName").removeClass('event_InvitedName_minus');
	var $minus = This.parent().find('.j_minus_detailed');
	$minus.show();
}
function removeEvents(obj){
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
			if(param1=="theme_id"){
				$('.leftEventTop_pic img').remove();
				$('.leftEventTop_pic').append('');
				$(".leftEventTop_text_name").html('');
				if(parseInt(param2)!=0){
					$('.leftEventTop_pic').append('<img src="'+data+'" width="221px" height="243px"/>');
					$(".leftEventTop_text_name").html( $(".themelist_event option:selected").text() );
				}
				$(".leftEventTop_text_edit").hide();
				
				$("#edit_userevent_theme").show();
				$(".leftEventTop_text_name").show();
			}
			if(param1=="name"){
				obj.find('#event_EventTitle_txt').html(param2);
			}else if(param1=="description"){
				var str = param2.replace(/\\n|\n/g, '<br />');
				obj.find('#event_EventDescription').html(str);
				$('#canceltitledesc').click();
				$('.upload-overlay-loading-fix').hide();
			}else{
				$('.upload-overlay-loading-fix').hide();
			}
		}
	});	
}
function initUserCalendarEvents(){
	// Calendar Setup
	var DateIndex=0;
	var currDateIndex=0;
	var calTimer="";
	var timer;
	EventsDetailedCal = Calendar.setup({
		
		cont          : "idEventsCalendar_user",
                noScroll  	 : true,
            fixed: true,
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
}
function initscrollPaneInvited(){
	$(".scrollpane_invited").jScrollPane();
}

function initscrollPaneJoining(){
	$(".scrollpane_joining").jScrollPane();
}
function getInvitedDataRelated(id){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmoreinvitedUserevent.php'), {event_id:id,currentpage:currentpageinvited},function(data){
		if(data!=false){
			$('.scrollpane_invited').css('height','225px');
			$('#invited_container').append(data);
			var currPageStatus=$('#invited_container .currPageStatus');
			if((""+currPageStatus.attr('data-value'))=="0"){
				$(".loadmoreinvited").hide();
			}else{
				$(".loadmoreinvited").show();	
			}
			$('#invited_Number span').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();
			initscrollPaneInvited();
			initjoiningItemList();
		}else{
			$(".loadmoreinvited").hide();	
		}
		
		$('.upload-overlay-loading-fix').hide();
	});
}


function getJoiningDataRelated(id){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmorejoiningUserevent.php'), {event_id:id,currentpage:currentpagejoin},function(data){
		if(data!=false){
			$('.scrollpane_joining').css('height','225px');
			$('#joining_container').append(data);
			var currPageStatus=$('#joining_container .currPageStatus');
			if((""+currPageStatus.attr('data-value'))=="0"){
				$(".loadmorejoin").hide();
			}else{
				$(".loadmorejoin").show();	
			}
			$('#joining_Number span').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();
			initscrollPaneJoining();
			initjoiningItemList();
		}else{
			$(".loadmorejoin").hide();	
		}
		
		$('.upload-overlay-loading-fix').hide();
	});
}
function initjoiningItemList(){
	$(".event_InvitedFriendBox").each(function(){
		var $this = $(this);
		$this.on('mouseenter',function(){
			$this.find('.event_InvitedAddFriend').show();
			$this.find('.j_minus_detailed').show();
		}).on('mouseleave',function(){
			$this.find('.event_InvitedAddFriend').hide();
			$this.find('.j_minus_detailed').hide();
		});
	});
}
function updateImage(str,curname,_type){
	if(_type=="edit_userevent"){
		$('.rightEventTop_pic img').remove();
		$('.rightEventTop_pic').append(str);
		updateeventpagedata("photo",curname,$('#rightEventContainer'));
	}
	closeFancyBox();
}
// Save a change of join.
function saveJoinDetailedChange( mediaid,mediatype ){
	// Prepare the form variables.
	var join_event = $("input:radio[name=event_join_detailed]:checked").val(); // returns: yes / no
	var guests_count = $("#join_guests_number_detailed").val();
	
	// If the user hasn't specified to join or not, halt the operation.
	if(join_event != 'yes' && join_event != 'no')
		return;
	
	
	$.ajax({
		url: ReturnLink('/ajax/ajax_userjoin_save_media.php'),
		data: {join_event:join_event, guests_count:guests_count, media:mediatype, mediaid:mediaid,page:0},
		type: 'post',
		success: function(data){
			var ret = null;
			try{
				ret = $.parseJSON(data);
			}catch(Ex){
				return ;
			}
			if(ret.error){
				TTAlert({
					msg: ret.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}
			window.location.reload();
		}
	});
}
function addCalTo(cals){
	if(new Date($('#edit_eventdate').attr('data-cal'))>new Date($('#edit_eventdate1').attr('data-cal'))){
		$('#edit_eventdate1').attr('data-cal',$('#edit_eventdate').attr('data-cal'));
		$('#edit_eventdate1').val($('#edit_eventdate').val());
	}
}
function initResetIcon(obj){
	obj.removeClass('privacy_icon0');
	obj.removeClass('privacy_icon1');
	obj.removeClass('privacy_icon2');
	obj.removeClass('privacy_icon3');
	obj.removeClass('privacy_icon4');
	obj.removeClass('privacy_icon5');
}
function initResetSelectedUsers(obj){
	resetSelectedUsers(obj);
}
function checkEventInfo_Edit( locationevent , fromdate , todate , defaultEntry , defaultEntryTo , data_location , data_lng , data_lat ){
	if(fromdate.length == 0){
		TTAlert({
			msg: t('Invalid event date from'),
			type: 'alert',
			btn1: t('ok')
		});
		return false;
	}else if(todate.length == 0){
		TTAlert({
			msg: t('Invalid event date to'),
			type: 'alert',
			btn1: t('ok')
		});
		return false;
	}else if(defaultEntry.length == 0){
		TTAlert({
			msg: t('Invalid event time'),
			type: 'alert',
			btn1: t('ok')
		});
		return false;
	}else if(defaultEntryTo.length == 0){
		TTAlert({
			msg: t('Invalid event time to'),
			type: 'alert',
			btn1: t('ok')
		});
		return false;
	}else if(locationevent.length == 0 || data_location.length==0 || data_lng.length==0 || data_lat.length==0 ){
		TTAlert({
			msg: t('Invalid event location'),
			type: 'alert',
			btn1: t('ok')
		});
		return false;
	}
	return true;
}