var currentpagejoin=0;
var TO_CALNew;
var currentpagesponsor=0;
var currentpageinvited=0;
var currentpagechannelsponsoredevent=0;

$(document).ready(function(){    
	if(parseInt(user_is_logged)!=0){
		initCalendarEvents();
	}
	initItemList();
	initjoiningItemList();
	$(".social_data_all").attr('data-id',$('.ed_EventOptions_data').attr("data-id"));
	initSocialFunctions();
	initReportFunctions($(".ed_UpcomingReport_menu"));
        initReportFunctions($(".ed_UpcomingReport"));
	initLikes( $('.social_data_all') );
	if(parseInt($('.ed_EventOptions_data').attr('data-enable'))==1 || parseInt(is_owner)==1){
		$('.btn_enabled').show();
		initComments( $('.social_data_all') );
		initShares( $('.social_data_all') );
	}else{
		$('.btn_enabled').hide();
	}
	if(parseInt(is_owner)==1){
		InitChannelUploaderHome($('.edit_event_pic').attr('id'),'EventsDetailedLeft',15,0);
		$('#defaultEntry_edit').timeEntry({
			show24Hours: true
		});
		$('#defaultEntry_editTo').timeEntry({
			show24Hours: true
		});
		Calendar.setup({
			inputField : "edit_eventdate",
                        noScroll  	 : true,
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
	}
	
	$(document).on('click',".load_more_sponsored" ,function(){
		currentpagechannelsponsoredevent++;
		getChannnelSponsoredEventDataRelated();
	});
	$(document).on('click',".loadmoreinvited" ,function(){
		currentpageinvited++;
		getInvitedDataRelated($(this).attr('data-id'));
	});
	
	$(document).on('click',".loadmorejoin" ,function(){
		currentpagejoin++;
		getJoiningDataRelated($(this).attr('data-id'));
	});
	
	$(document).on('click',".loadmoresponsor" ,function(){
		currentpagesponsor++;
		getSponsorDataRelated($(this).attr('data-id'));
	});
	
	$(document).on('click',".ed_Invite_deny" ,function(){
		TTAlert({
			msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to invite this event'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('register'),
			btn2Callback: function(data){
				if(data){
					window.location.href = ReturnLink('/register');
				}
			}
		});
	});
	
	
	
	$(document).on('click',".followdetailed1" ,function(){
		TTAlert({
                        msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to follow a tuber'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('register'),
			btn2Callback: function(data){
				if(data){
					window.location.href = ReturnLink('/register');
				}
			}
		});
	});
	
	$(document).on('click',".addFrienddetailed1" ,function(){
		TTAlert({
                        msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to add a tuber as a friend'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('register'),
			btn2Callback: function(data){
				if(data){
					window.location.href = ReturnLink('/register');
				}
			}
		});
	});
	
	
	
	$(document).on('click',".connect_not_sign" ,function(){
		TTAlert({
                        msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to connect this channel'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('create my channel'),
			btn2Callback: function(data){
				if(data){
					window.location.href = ReturnLink('/CreateChannelForm');
				}
			}
		});
	});
	
	$(document).on('click',".sponsor_but_detailed1" ,function(){
		TTAlert({
			msg: t('you need to have a Channel page in order to sponsor this event'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('create my channel'),
			btn2Callback: function(data){
				if(data){
					window.location.href = ReturnLink('/CreateChannelForm');
				}
			}
		});
	});
	
	$(document).on('click',"#li_share_not" ,function(){
		TTAlert({
                        msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to share this event'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('register'),
			btn2Callback: function(data){
				if(data){
					window.location.href = ReturnLink('/register');
				}
			}
		});
	});
	
	$(document).on('click',"#li_report_not" ,function(){
		TTAlert({
                        msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to report this event'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('register'),
			btn2Callback: function(data){
				if(data){
					window.location.href = ReturnLink('/register');
				}
			}
		});
	});
	
	// scroll pane
	$('.scroll-pane').jScrollPane();
	
	// Tuber icons on moveover show the numbers of likes, comments and shares
	$(".ed_TuberIconOver").hover(function()
	{
		$(this).children().show();
	});
	$(".ed_TuberIconOver").mouseout(function()
	{
		$(this).children().hide();
	});
	// Set Default comment on focus and blur
	var DefaultCommentInput = "Write a comment...";
	$("#idCommentInput").focus(function()
	{
		if($(this).val() == DefaultCommentInput)	$(this).val("");
	});
	$("#idCommentInput").blur(function()
	{
		if($(this).val() == "")	$(this).val(DefaultCommentInput);
	});

	// Set Default comment on share textarea on focus and blur
	var DefaultCommentInput = $.i18n._('write something...');
	$("#idShareTextarea").focus(function()
	{
		if($(this).val() == DefaultCommentInput)	$(this).val("");
	});
	$("#idShareTextarea").blur(function()
	{
		if($(this).val() == "")	$(this).val(DefaultCommentInput);
	});
	// channels sponsored and other
	$('.ed_ChannelsView > img').click(function()
	{
		$('.ed_AllSponsoredOtherChannels').toggle();
	});
	$('.ed_ChannelsSponsoredOtherClose').click(function()
	{
		$('.ed_AllSponsoredOtherChannels').hide();
	});
	// right arrow menu
	$('.ed_Arrow').click(function()
	{
		$('.ed_List').toggle();
	});
	
	$(".ed_EventPopupClose").click(function()
	{
		$("#Blanket").hide();
		$(".holder").hide();
	});
	
	$(document).on('click',".remove_event" ,function(){
		var $parent=$(this).closest('.ed_UpcomingEventBox');
		removeEvents($parent);
	});
	$(document).on('click',".ed_Event_cancel" ,function(){
		var $parent=$(this).closest('.ed_RightTop');
		removeEvents($parent);
	});
	$(document).on('click',".remove_event_sponsored" ,function(){
		var $parent=$(this).closest('.ed_UpcomingEventBox');
		removeSponsoringEvents($parent);
	});
	$(document).on('click',".hideDiv_event" ,function(){
		showhideEventsItem($(this),0);
	});
	$(document).on('click',".hideText_event" ,function(){
		var $parent=$(this).closest('.ed_UpcomingEventBox');			
		showhideEventsItem($parent.find('.hideDiv_event'),1);
	});
	$(document).on('click',".hideDiv_sponsoring" ,function(){
		showhideSponsoringItem($(this),0);
	});
	$(document).on('click',".hideText_sponsoring" ,function(){
		var $parent=$(this).closest('.ed_UpcomingEventBox');			
		showhideSponsoringItem($parent.find('.hideDiv_sponsoring'),1);
	});
	
	$(document).on('click',".edit_event" ,function(){
		if($(this)!=""){
			geteventTitle($(this));
		}
	});
	
	
	$(document).on('click',"#edit_eventdata_but" ,function(){
		$(this).addClass('active');		
		$(".edit_eventdate_button_container").show();
		$(".show_edit_event").show();
	});
	
	$(document).on('click',"#canceleventdata" ,function(){
		$("#edit_eventdata_but").removeClass('active');				
		$(".edit_eventdate_button_container").hide();
		$(".show_edit_event").hide();
	});
	$(document).on('click',"#saveeventdata_edit" ,function(){
		var curob=$(this).parent().parent();
		var event_id=$(this).attr('data-id');
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
		var whojoin=curob.find('#whocanjoin_cont_select').val();
		var caninvite= (curob.find('.uploadinfocheckbox_eventedit1').hasClass('active')) ? 1 : 0 ;
		var showguests= (curob.find('.uploadinfocheckbox_eventedit2').hasClass('active'))? 1 : 0 ;
		var showsponsors= (curob.find('.uploadinfocheckbox_eventedit3').hasClass('active'))? 1 : 0 ;
		var allowsponsoring= (curob.find('.uploadinfocheckbox_eventedit4').hasClass('active'))? 1 : 0 ;
		var enablesharecomments= (curob.find('.uploadinfocheckbox_eventedit5').hasClass('active'))? 1 : 0 ;
		if(checkEventInfo_Edit(locationevent,fromdate,todate , defaultEntry, defaultEntryTo , data_location , data_lng , data_lat )){
			$.ajax({
				url: ReturnLink('/ajax/info_event_edit_channel.php'),
				data: {globchannelid:channelGlobalID(),event_id:event_id,locationevent : locationevent , fromdate: fromdate ,fromdatetime : defaultEntry , todatetime : defaultEntryTo, todate: todate ,whojoin:whojoin,guestevent:guestevent,caninvite:caninvite,showguests:showguests,showsponsors:showsponsors,allowsponsoring:allowsponsoring,enablesharecomments:enablesharecomments ,data_lng:data_lng,data_lat:data_lat,data_country:data_country,data_location:data_location },
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
						window.location.reload();
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
	$(document).on('click',"#savetitledesc_edit" ,function(){
		var curob=$(this).parent().parent().parent();
		var event_id=$('.ed_EventOptions_data').attr('data-id');
		
		var ed_EventTitle_txt_edit=getObjectData(curob.find('#ed_EventTitle_txt_edit'));
		var ed_Eventdesc_txt_edit=getObjectData(curob.find('#ed_Eventdesc_txt_edit'));
		
		if(checkEventDesc_Edit(ed_EventTitle_txt_edit,ed_Eventdesc_txt_edit)){
			updateeventpagedata("name",ed_EventTitle_txt_edit,event_id,$('#ed_EventTitle_txt'));
			updateeventpagedata("description",ed_Eventdesc_txt_edit,event_id,$('#ed_EventDescription'));			
		}
	});
	$(document).on('click',"#edit_event_text" ,function(){
		$(this).hide();
		$(".ed_EventTitle").hide();
		$(".ed_EventDescription").hide();
		$(".ed_Event_edit_content").show();
                $(".ed_EventTitle_txt_edit").focus();
	});	
	
	$(document).on('click',"#canceltitledesc" ,function(){
		$(".editChannelRight_data13").show();
		$(".ed_EventTitle").show();
		$(".ed_EventDescription").show();
		$(".ed_Event_edit_content").hide();
	});
		
	$(document).on('click',"input:radio[name=event_join_detailed]" ,function(){
		saveJoinDetailedChange( $(".channelevtImg").attr('data-id'),SOCIAL_ENTITY_EVENTS );
	});
	$(document).on('change',"#join_guests_number_detailed" ,function(){
		saveJoinDetailedChange( $(".channelevtImg").attr('data-id'),SOCIAL_ENTITY_EVENTS );
	});
	
	
		
	$(document).on('click',"input:radio[name=event_join_detailed1], #join_guests_number_detailed1, input:radio[name=event_join_detailed1]" ,function(){
		TTAlert({
                        msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to join this event'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('register'),
			btn2Callback: function(data){
				if(data){
					window.location.href = ReturnLink('/register');
				}
			}
		});
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
			what: 'social/join/delete',
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
			what: 'social/join/undodelete',
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
	
	$(document).on('click',"#s_minus" ,function(){
		var $this=$(this);
		TTCallAPI({
			what: 'social/share/delete',
			data: {id:$this.parent().attr('data-sid')},
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
	
	$(document).on('click',"#s_plus" ,function(){		
		var $this=$(this);
		TTCallAPI({
			what: 'social/share/undodelete',
			data: {id:$this.parent().attr('data-sid')},
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
	
	$("#invitepeoplebutton").fancybox({
		padding	:0,
		margin	:0,
		beforeLoad:function(){	
		$('#sharepopup').html('');
		var event_id = $("#invitepeoplebutton").attr("data-id");
		var str='<div class="sharepopup_container"><div class="channelyellow13 formContainer100">'+t("invite people to join my event")+'</div><div class="formttl13 formContainer100 margintop26">'+$.i18n._("write something")+'</div><textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="'+t("write something...")+'" style="font-family:Arial, Helvetica, sans-serif; width:401px; height:42px;" type="text" name="invitetext">'+$.i18n._(''+$.i18n._('write something...')+'')+'</textarea>';
		str+='<div class="formttl13 formContainer100 margintop15">'+$.i18n._("add people (T tubers, emails)")+'</div>';
		str+='<div class="peoplecontainer formContainer100 margintop2"><div class="emailcontainer"><div class="addmore"><input name="addmoretext" id="addmoretext" type="text" class="" data-value="'+$.i18n._("add more")+'" value="'+$.i18n._("add more")+'" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>';
		str+='<div class="friendscontainer"><div class="uploadinfocheckbox uploadinfocheckbox5 formttl13 margintop8 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> '+t("connections")+'<span style="color:#b8b8b8;"></span></div></div></div></div><div class="sharepopup_butcontainer margintop8"><div class="sharepopup_butBRCancel sharepopup_buts">'+t("cancel")+'</div><div class="sharepopup_butseperator"></div><div id="share_popup_but" class="sharepopup_but2 sharepopup_buts">'+t("send")+'</div></div></div>';
		$('#sharepopup').html(str);
		
		$.ajax({
			type: "POST",
			url: ReturnLink("/ajax/popup_actions_invite_event.php"),
			data: {type:'invite_event',channelid: channelGlobalID(),event_id:event_id},
			success: function(data) {
				if(data){
					$('#sharepopup').html(data);
					resetSelectedUsers();
					addmoreusersautocomplete_connections();
				}
			}
		});
	}});
	$(document).on('click',"#share_popup_invite_event" ,function(){
		var $this=$(this);
		var $parent=$this.parent().parent();
		var invite_msg = getObjectData($parent.find("#invitetext"));
		var inviteArray=new Array();
		$parent.find('.peoplecontainer .peoplesdata').each(function(){
			var obj=$(this);
			if(obj.attr('id')=="connectionsdata"){
				inviteArray.push( {connections : 1} );
			}else if( obj.attr('data-email') != '' ){
				inviteArray.push( {email : obj.attr('data-email') } );
			}else if( parseInt( obj.attr('data-id') ) != 0 ){
				inviteArray.push( {id : obj.attr('data-id') } );
			}
		});
		
		TTCallAPI({
			what: 'social/share',
			data: {entity_type:SOCIAL_ENTITY_EVENTS, entity_id: $this.attr('data-id'), share_with : inviteArray, share_type: SOCIAL_SHARE_TYPE_INVITE , msg:invite_msg, channel_id:channelGlobalID(),addToFeeds:1},
			callback: function(ret){
				window.location.reload();
			}
		});
	});
	
	
	$("#sponsor_but_detailed").fancybox({
		padding	:0,
		margin	:0,
		beforeLoad:function(){
		var imgsrc=$('.channelevtImg img').attr('src');			
		$('#sharepopup').html('');
		var event_id = $("#sponsor_but_detailed").attr("data-eventid");
		var ch_id = $("#sponsor_but_detailed").attr("data-id");
		
		var str='<div class="sharepopup_container"><div class="channelyellow13 formContainer100">'+t('sponsor this event')+'</div><img class="sharepopup_img_detailed margintop7" src="'+imgsrc+'"/><div class="formttl13 formContainer100 margintop26">'+$.i18n._("write something")+'</div><textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="'+$.i18n._('write something...')+'" style="font-family:Arial, Helvetica, sans-serif; width:401px; height:42px;" type="text" name="invitetext">'+$.i18n._('write something...')+'</textarea>';
		
		str+='<div class="formttl13 formContainer100 margintop15">'+$.i18n._("add people (T tubers, emails)")+'</div>';
		str+='<div class="peoplecontainer formContainer100 margintop2"><div class="emailcontainer"><div class="addmore"><input name="addmoretext" id="addmoretext" type="text" class="" data-value="'+$.i18n._("add more")+'" value="'+$.i18n._("add more")+'" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>';
		str+='</div><div class="sharepopup_butcontainer margintop8"><div class="sharepopup_butBRCancel sharepopup_buts">'+t("cancel")+'</div><div class="sharepopup_butseperator"></div><div id="share_popup_but" class="sharepopup_but2 sharepopup_buts">'+t("send")+'</div></div></div>';
		$('#sharepopup').html(str);
		
		$.ajax({
			type: "POST",
			url: ReturnLink("/ajax/popup_actions_invite_event.php"),
			data: {type:'sponsor_event',imgsrc: imgsrc,channelid: ch_id,event_id:event_id},
			success: function(data) {
				if(data){
					$('#sharepopup').html(data);
					resetSelectedUsers();
					$('.peoplecontainersponsor #addmoretext').keydown(function(event){
                                            var code = (event.keyCode ? event.keyCode : event.which);
                                            if(code === 13) { //Enter keycode or tab
                                                if(validateEmail($('.peoplecontainersponsor #addmoretext').val())){
                                                    var friendstr='<div class="peoplesdata formttl" data-id="" data-email="'+$('.peoplecontainersponsor #addmoretext').val()+'"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">'+$('.peoplecontainersponsor #addmoretext').val()+'</div><div class="peoplesdataclose"></div></div>';
                                                    $('.emailcontainer').prepend(friendstr);                    //				
                                                    $('.peoplecontainersponsor #addmoretext').val('');
                                                    $('.peoplecontainersponsor #addmoretext').blur();
                                                    var height = $('#inviteForm .formContainer').height()+74;
                                                    $('#inviteForm').css('height',height+"px");
                                                    event.preventDefault();
                                                }
                                            }
                                        });
				}
			}
		});
	}});
	$(document).on('click',"#share_popup_sponsor_event" ,function(){
		var $this=$(this);
		var $parent=$this.parent().parent();
		var invite_msg = getObjectData($parent.find("#invitetext"));
		var inviteArray=new Array();
		$parent.find('.peoplecontainer .peoplesdata').each(function(){
			var obj=$(this);
			if(obj.attr('id')=="connectionsdata"){
				inviteArray.push( {connections : 1} );
			}else if( obj.attr('data-email') != '' ){
				inviteArray.push( {email : obj.attr('data-email') } );
			}else if( parseInt( obj.attr('data-id') ) != 0 ){
				inviteArray.push( {id : obj.attr('data-id') } );
			}
		});
		
		TTCallAPI({
			what: 'social/sponsor',
			data: {entity_type:SOCIAL_ENTITY_EVENTS, entity_id:$this.attr('data-id'), share_with : inviteArray, share_type: SOCIAL_SHARE_TYPE_SPONSOR , msg:invite_msg, channel_id:channelGlobalID(), sponsor_id: $this.attr('data-channelid')},
			callback: function(ret){
				window.location.reload();
			}
		});
	});
	
	$("#share_event_detailed").fancybox({
		padding	:0,
		margin	:0,
		beforeLoad:function(){
		var imgsrc=$('.channelevtImg img').attr('src');			
		$('#sharepopup').html('');
		var event_id = $("#share_event_detailed").attr("data-id");
		
		var str='<div class="sharepopup_container"><div class="channelyellow13 formContainer100">'+t("share this event")+'</div><img class="sharepopup_img_detailed margintop7" src="'+imgsrc+'"/><div class="formttl13 formContainer100 margintop26">'+$.i18n._("write something")+'</div><textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="'+$.i18n._('write something...')+'" style="font-family:Arial, Helvetica, sans-serif; width:401px; height:42px;" type="text" name="invitetext">'+$.i18n._('write something...')+'</textarea>';
		str+='<div class="formttl13 formContainer100 margintop15">'+$.i18n._("add people (T tubers, emails)")+'</div>';
		str+='<div class="peoplecontainer formContainer100 margintop2"><div class="emailcontainer"><div class="addmore"><input name="addmoretext" id="addmoretext" type="text" class="" data-value="'+$.i18n._("add more")+'" value="'+$.i18n._("add more")+'" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>';
		str+='<div class="friendscontainer"><div class="uploadinfocheckbox uploadinfocheckbox3 formttl13 margintop8 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> '+t("friends")+'<span style="color:#b8b8b8;"></span></div></div><div class="uploadinfocheckbox uploadinfocheckbox4 formttl13 margintop8 marginleft5" data-value="3"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> '+t("followers")+'<span style="color:#b8b8b8;"></span></div></div></div></div><div class="sharepopup_butcontainer margintop8"><div class="sharepopup_butBRCancel sharepopup_buts">'+t("cancel")+'</div><div class="sharepopup_butseperator"></div><div id="share_popup_but" class="sharepopup_but2 sharepopup_buts">'+t("send")+'</div></div></div>';
		
		$('#sharepopup').html(str);
		
		$.ajax({
			type: "POST",
			url: ReturnLink("/ajax/popup_actions_share_invite.php"),
			data: {type:'share_event_user',imgsrc: imgsrc,event_id:event_id},
			success: function(data) {
				if(data){
					$('#sharepopup').html(data);
					resetSelectedUsers();
					addmoreusersautacomplete();
				}
			}
		});
	}});
	$(document).on('click',"#share_event_user_popup_but" ,function(){
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
			data: {entity_type:SOCIAL_ENTITY_EVENTS, entity_id:$this.attr('data-id'), share_with : inviteArray, share_type: SOCIAL_SHARE_TYPE_SHARE , msg:invite_msg, channel_id:channelGlobalID(),addToFeeds:1},
			callback: function(ret){
				window.location.reload();
			}
		});

	});
	
	
	
	
	$("#invitepeoplebutton_user").fancybox({
		padding	:0,
		margin	:0,
		beforeLoad:function(){		
		$('#sharepopup').html('');
		var event_id = $("#invitepeoplebutton_user").attr("data-id");
		
		var str='<div class="sharepopup_container"><div class="channelyellow13 formContainer100">'+t("invite people to join my event")+'</div><div class="formttl13 formContainer100 margintop26">'+$.i18n._("write something")+'</div><textarea id="invitetext" class="ChaFocus margintop5" onblur="addValue2(this)" onfocus="removeValue2(this)" data-value="'+$.i18n._('write something...')+'" style="font-family:Arial, Helvetica, sans-serif; width:401px; height:42px;" type="text" name="invitetext">'+$.i18n._('write something...')+'</textarea>';
		str+='<div class="formttl13 formContainer100 margintop15">'+$.i18n._("add people (T tubers, emails)")+'</div>';
		str+='<div class="peoplecontainer formContainer100 margintop2"><div class="emailcontainer"><div class="addmore"><input name="addmoretext" id="addmoretext" type="text" class="" data-value="'+$.i18n._("add more")+'" value="'+$.i18n._("add more")+'" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>';
		str+='<div class="friendscontainer"><div class="uploadinfocheckbox uploadinfocheckbox3 formttl13 margintop8 marginleft5" data-value="2"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> '+t("friends")+'<span style="color:#b8b8b8;"></span></div></div><div class="uploadinfocheckbox uploadinfocheckbox4 formttl13 margintop8 marginleft5" data-value="3"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt" style="font-size:12px; font-weight:normal; color:#000000;"> '+t("followers")+'<span style="color:#b8b8b8;"></span></div></div></div></div><div class="sharepopup_butcontainer margintop8"><div class="sharepopup_butBRCancel sharepopup_buts">'+t("cancel")+'</div><div class="sharepopup_butseperator"></div><div id="share_popup_but" class="sharepopup_but2 sharepopup_buts">'+t("send")+'</div></div></div>';
		$('#sharepopup').html(str);
		
		$.ajax({
			type: "POST",
			url: ReturnLink("/ajax/popup_actions_share_invite.php"),
			data: {type:'invite_event_user',event_id:event_id},
			success: function(data) {
				if(data){
					$('#sharepopup').html(data);
					resetSelectedUsers();
					addmoreusersautacomplete();
				}
			}
		});
		
	}});
	$(document).on('click',"#invite_event_user_popup_but" ,function(){
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
			data: {entity_type:SOCIAL_ENTITY_EVENTS, entity_id:$this.attr('data-id'), share_with : inviteArray, share_type: SOCIAL_SHARE_TYPE_INVITE , msg:invite_msg, channel_id:channelGlobalID(),addToFeeds:1},
			callback: function(ret){
				window.location.reload();
			}
		});

	});
//	$('.channelAvatarLink').fancybox({
//            helpers : {
//                overlay : {closeClick:true}
//            }
//        });
        $(".channelAvatarLink").each(function (index, element) {
            var $This = $(this);

            var vid = $This.attr('data-id');
            var type = $This.attr('data-type');

            $This.attr("href", ReturnLink('parts/user-viewprofileimage.php?id=' + vid + '&type=' + type));

            $This.fancybox({
                "padding": 0,
                "margin": 0,
                "width": '883',
                "height": '604',
                "transitionIn": "none",
                "transitionOut": "none",
                "autoSize": false,
                "scrolling": 'no',
                "type": "iframe"
            });

        });
});

function initItemList(){
	$(".ed_UpcomingEventBox").each(function(){
		var $this = $(this);
		$this.on('mouseenter',function(){
			$this.find('.itemList_right_buttons').show();
			$this.find('.hideDivClass1').show();
			$this.find('.ed_UpComingClose').show();
			$this.find('.ed_UpcomingReport').show();
		}).on('mouseleave',function(){
			$this.find('.itemList_right_buttons').hide();
			$this.find('.hideDivClass1').hide();
			$this.find('.ed_UpComingClose').hide();
			$this.find('.ed_UpcomingReport').hide();
		});
	});
}

function initjoiningItemList(){
	$(".ed_InvitedFriendBox").each(function(){
		var $this = $(this);
		$this.on('mouseenter',function(){
			$this.find('.ed_InvitedAddFriend').show();
			$this.find('.j_minus_detailed').show();
		}).on('mouseleave',function(){
			$this.find('.ed_InvitedAddFriend').hide();
			$this.find('.j_minus_detailed').hide();
		});
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
				var $parent=obj.parent();
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
				var $parent=obj.parent();
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
						}
						$('.upload-overlay-loading-fix').hide();
					}
				});
			}
		}
	});
}


function updateImage(str,curname,_type){
	if(_type=="edit_event"){
		$('.channelevtImg img').remove();
		$('.channelevtImg').append(str);
		updateeventpagedata("photo",curname,$('.channelevtImg').attr('data-id'),$('.channelevtImg'));
	}
	closeFancyBox();
}
function updateeventpagedata(param1,param2,id,obj){
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_event_update.php'),
		data: {globchannelid:channelGlobalID(),str:param1,data:param2,id:id},
		type: 'post',
		success: function(data){
			if(param1=="name"){
				obj.html(param2);
			}else if(param1=="description"){
				var str = param2.replace(/\\n|\n/g, '<br />');
				obj.html(str);
				$('#canceltitledesc').click();
				$('.upload-overlay-loading-fix').hide();
			}else{
				$('.upload-overlay-loading-fix').hide();
			}
		}
	});	
}

function initscrollPaneInvited(){
	$(".scrollpane_invited").jScrollPane();
}

function initscrollPaneJoining(){
	$(".scrollpane_joining").jScrollPane();
}

function initscrollPaneSponsor(){
	$(".scrollpane_sponsor").jScrollPane();
}

function getInvitedDataRelated(id){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmoreinvitedevent.php'), {event_id:id,globchannelid:channelGlobalID(),currentpage:currentpageinvited},function(data){
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

function getChannnelSponsoredEventDataRelated(){
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_loadmorechannelsponsoredevent.php'), {globchannelid:channelGlobalID(),currentpage:currentpagechannelsponsoredevent},function(data){
        if(data!=false){
            $('#event_sponsored_channel_in').append(data);
            var currPageStatus=$('#event_sponsored_channel_in .currPageStatus');
            if((""+currPageStatus.attr('data-value'))=="0"){
                    $(".load_more_sponsored").hide();
            }else{
                    $(".load_more_sponsored").show();	
            }
            $('#event_sponsored_channel_title span').html('('+currPageStatus.attr('data-count')+')');
            currPageStatus.remove();
            initItemList();
        }else{
            $(".load_more_sponsored").remove();	
        }

        $('.upload-overlay-loading-fix').hide();
    });
}

function getJoiningDataRelated(id){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmorejoiningevent.php'), {event_id:id,globchannelid:channelGlobalID(),currentpage:currentpagejoin},function(data){
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


function addCalTo(cals){
	if(new Date($('#edit_eventdate').attr('data-cal'))>new Date($('#edit_eventdate1').attr('data-cal'))){
		$('#edit_eventdate1').attr('data-cal',$('#edit_eventdate').attr('data-cal'));
		$('#edit_eventdate1').val($('#edit_eventdate').val());
	}
}

function getSponsorDataRelated(id){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmoresponsorevent.php'), {event_id:id,globchannelid:channelGlobalID(),currentpage:currentpagesponsor},function(data){
		if(data!=false){
			$('.scrollpane_sponsor').css('height','225px');
			$('#sponsor_container').append(data);
			var currPageStatus=$('#sponsor_container .currPageStatus');
			if((""+currPageStatus.attr('data-value'))=="0"){
				$(".loadmoresponsor").hide();
			}else{
				$(".loadmoresponsor").show();	
			}
			$('#sponsor_Number span').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();
			initscrollPaneSponsor();
			initjoiningItemList();
		}else{
			$(".loadmoresponsor").hide();	
		}
		
		$('.upload-overlay-loading-fix').hide();
	});
}
function checkEventInfo_Edit(locationevent,fromdate,todate , defaultEntry, defaultEntryTo , data_location , data_lng , data_lat ){
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
	This.parent().find(".ed_InvitedName").addClass('ed_InvitedName_minus');
	
	var $plus = This.parent().find('.j_plus_detailed');
	$plus.show();
};

function showplus(This){
	This.hide();
	This.parent().find(".ed_InvitedName").removeClass('ed_InvitedName_minus');
	var $minus = This.parent().find('.j_minus_detailed');
	$minus.show();
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
		url: ReturnLink('/ajax/ajax_join_save_channel_media.php'),
		data: {join_event:join_event, guests_count:guests_count, media:mediatype, mediaid:mediaid,page:0,globchannelid:channelGlobalID()},
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