/**
 * Created on .
 */
$(document).ready(function() {
	InitChannelUploaderHome('TT_uploadBtnevent','formContainersubaddid',15,1);
	addmoreusersautocomplete_custom_journal( $('#addmoretext') );
	
	
	$('#defaultEntry').timeEntry({
		show24Hours: true
	});
	$("#defaultEntry").val( getCurrentTime() );


	$('#defaultEntryTo').timeEntry({
		show24Hours: true
	});
	$("#defaultEntryTo").val( getCurrentTime() );
	
	Calendar.setup({
		inputField : "fromdate",
                noScroll  	 : true,
		trigger    : "fromdate",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());
		    TO_CAL.args.min = date;
		    TO_CAL.redraw();
			$('#fromdate').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalToEvent(this);
			this.hide();
		},
		dateFormat : "%d / %m / %Y"
	});
	TO_CAL=Calendar.setup({
		inputField : "todate",
                noScroll  	 : true,
		trigger    : "todate",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());
			$('#todate').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalToEvent(this);
			this.hide();
		},
		dateFormat : "%d / %m / %Y"
	});
	$(document).on('click',".uploadinfocheckbox" ,function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			if($(this).hasClass('uploadinfocheckbox3')){
				if($(this).parent().hasClass("friendscontainer")){
					$(this).parent().parent().find('#friendsdata').remove();
				}
			}else if($(this).hasClass('uploadinfocheckbox_friends_of_friends')){
				if($(this).parent().hasClass("friendscontainer")){
					$(this).parent().parent().find('#friends_of_friends_data').remove();
				}
			}else if($(this).hasClass('uploadinfocheckbox4')){
				if($(this).parent().hasClass("friendscontainer")){
					$(this).parent().parent().find('#followersdata').remove();
				}
			}
		}else{
			if($(this).hasClass('uploadinfocheckbox3')){
				if($(this).parent().hasClass("friendscontainer")){
					var friendstr='<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends")+'</div><div class="peoplesdataclose"></div></div>';
					
					$(this).parent().parent().find('.emailcontainer div:eq(0)').after(friendstr);
					//$(this).parent().parent().find('#friendsdata').css("width",($(this).parent().parent().find('#friendsdata .peoplesdatainside').width()+20)+"px");
				}
			}else if($(this).hasClass('uploadinfocheckbox_friends_of_friends')){
				if($(this).parent().hasClass("friendscontainer")){
					var followerstr='<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends of friends")+'</div><div class="peoplesdataclose"></div></div>';
					$(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
					//$(this).parent().parent().find('#friends_of_friends_data').css("width",($(this).parent().parent().find('#friends_of_friends_data .peoplesdatainside').width()+20)+"px");
				}
			}else if($(this).hasClass('uploadinfocheckbox4')){
				if($(this).parent().hasClass("friendscontainer")){
					var followerstr='<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("followers")+'</div><div class="peoplesdataclose"></div></div>';
					$(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
					//$(this).parent().parent().find('#followersdata').css("width",($(this).parent().parent().find('#followersdata .peoplesdatainside').width()+20)+"px");
				}
			}
			$(this).addClass('active');
		}
	});
	$(document).on('click',".privacyclass" ,function(){
		$('.privacyclass').removeClass('active');
		$(this).addClass('active');
		var which = parseInt( $(this).attr('data-value') );
		if(which==USER_PRIVACY_SELECTED){
			$('.peoplecontainer').removeClass('displaynone');
		}else{
			$('.peoplecontainer').addClass('displaynone');	
		}
	});
	$(document).on('click',".peoplesdataclose" ,function(){
		var parents=$(this).parent();
		var parents_all=parents.parent().parent();
		parents.remove();
		if(parents.attr('data-id')!=''){
			SelectedUsersDelete(parents.attr('data-id'),parents_all.find('.addmore input'));			
		}
		
		if(parents.attr('id')=='friendsdata'){
			parents_all.find('.uploadinfocheckbox3').removeClass('active');
		}else if(parents.attr('id')=='friends_of_friends_data'){
			parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
		}else if(parents.attr('id')=='followersdata'){
			parents_all.find('.uploadinfocheckbox4').removeClass('active');
		}
	});
	$(document).on('click',"#event_buts_save" ,function(){
		var curob=$(this).parent().parent();
		var nameevents=getObjectData(curob.find('#nameevents'));
		var descriptionevent=getObjectData(curob.find('#descriptionevent'));
		var locationevent=getObjectData(curob.find('#locationevent'));
		var data_location=curob.find('#locationevent').attr('data-location');
		var data_country=curob.find('#locationevent').attr('data-country');
		var data_lng=curob.find('#locationevent').attr('data-lng');
		var data_lat=curob.find('#locationevent').attr('data-lat');
		
		
		var fromdate=getObjectData(curob.find('#fromdate'));
		var defaultEntry=curob.find('#defaultEntry').val();
		var todate=getObjectData(curob.find('#todate'));
		var defaultEntryTo=curob.find('#defaultEntryTo').val();
		var themephotoStanInside=curob.find('.themephotoStanInside').attr('data-value');
		var guestevent=curob.find('#guestevent').val();
		var themelist=curob.find('#themelist').val();
		var showguests = (curob.find('.uploadinfocheckbox_event1').hasClass('active')) ? 1 : 0 ;
		var caninvite = (curob.find('.uploadinfocheckbox_event2').hasClass('active'))? 1 : 0 ;
		var enablesharecomments = (curob.find('.uploadinfocheckbox_event3').hasClass('active'))? 1 : 0 ;
		
		var privacyValue=curob.find('.privacyclass.active').attr('data-value');
		var privacyArray=new Array();
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
		if( checkEventInfo_Add( nameevents , descriptionevent , locationevent , fromdate , todate , defaultEntry , defaultEntryTo , data_lng , data_lat , data_location , curob ) ){
			if( (privacyValue==USER_PRIVACY_SELECTED && privacyArray.length>0) || privacyValue!=USER_PRIVACY_SELECTED){
				$('.upload-overlay-loading-fix').show();			
				$.ajax({
					url: ReturnLink('/ajax/info_event_manage_user.php'),
					data: {privacyValue:privacyValue, nameevents : nameevents, descriptionevent: descriptionevent ,locationevent : locationevent,data_country:data_country , fromdate: fromdate ,fromdatetime : defaultEntry, todate: todate ,todatetime : defaultEntryTo,themephotoStanInside:themephotoStanInside,guestevent:guestevent,caninvite:caninvite,showguests:showguests,themelist:themelist,enablesharecomments:enablesharecomments,data_lng:data_lng,data_lat:data_lat,data_location:data_location},
					type: 'post',
					success: function(data){
						var ret = null;
						try{
							ret = $.parseJSON(data);
						}catch(Ex){
							$('.upload-overlay-loading-fix').hide();
							return ;
						}
						
						if(!ret){
							$('.upload-overlay-loading-fix').hide();
							curob.find('#brochureerror').html(t('Couldnt save please try again later'));
							return ;
						}
						if(ret.status == 'ok'){
							TTCallAPI({
								what: 'user/privacy_extand/add',
								data: {privacyValue:privacyValue, privacyArray:privacyArray, entity_type:SOCIAL_ENTITY_USER_EVENTS, entity_id:ret.value},
								callback: function(ret){
                                                                    TTAlert({
                                                                        msg: t('Event created'),
                                                                        type: 'alert',
                                                                        btn1: t('ok'),
                                                                        btn2: '',
                                                                        btn2Callback: function(){
                                                                            window.location.reload();
                                                                        }
                                                                    });
								}
							});
							$('.upload-overlay-loading-fix').hide();
						}else{	
							$('.upload-overlay-loading-fix').hide();
							curob.find('#brochureerror').html(ret.error);	
						}
					}
				});
			}
		}
	});
	$(document).on('click',"#event_buts_cancel" ,function(){
		window.location.reload();
	});
});
function checkEventInfo_Add( nameevents , descriptionevent , locationevent , fromdate , todate , defaultEntry , defaultEntryTo , data_lng , data_lat , data_location , obj ){
	obj.find('#brochureerror').html('');
	obj.find('input').removeClass('InputErr');
	obj.find('textarea').removeClass('InputErr');
	if(nameevents.length == 0){
		obj.find('#brochureerror').html(t('Invalid event name'));
		obj.find('#nameevents').addClass('InputErr');
		obj.find('#nameevents').focus();
		return false;
	}else if(descriptionevent.length == 0){	
		obj.find('#brochureerror').html(t('Invalid event description'));
		obj.find('#descriptionevent').addClass('InputErr');
		obj.find('#descriptionevent').focus();
		return false;
	}else if(locationevent.length == 0 || data_lng.length==0 || data_lat.length==0 || data_location.length==0){
		obj.find('#brochureerror').html(t('Invalid event location'));
		obj.find('#locationevent').addClass('InputErr');
		obj.find('#locationevent').focus();
		return false;
	}else if(fromdate.length == 0){
		obj.find('#brochureerror').html(t('Invalid event date from'));
		obj.find('#fromdate').addClass('InputErr');
		obj.find('#fromdate').focus();
		return false;
	}else if(todate.length == 0){
		obj.find('#brochureerror').html(t('Invalid event date to'));
		obj.find('#todate').addClass('InputErr');
		obj.find('#todate').focus();
		return false;
	}else if(defaultEntry.length == 0){
		obj.find('#brochureerror').html(t('Invalid event time'));
		obj.find('#defaultEntry').addClass('InputErr');
		obj.find('#defaultEntry').focus();
		return false;
	}else if(defaultEntryTo.length == 0){
		obj.find('#brochureerror').html(t('Invalid event time'));
		obj.find('#defaultEntryTo').addClass('InputErr');
		obj.find('#defaultEntryTo').focus();
		return false;
	}
	return true;
}
function getObjectData(obj){
	var mystr=""+obj.val();
	if(mystr==obj.attr('data-value')){
		mystr="";	
	}
	return mystr;
}
function updateImage_Add_Brochure(str,curname,_type){
	if(_type=="TT_uploadBtnevent"){
		$('.themephotoStanInside').attr('data-value',curname);
		$('.themephotoStanInside').html(str);		
	}
	closeFancyBox();
}
function getCurrentTime(){
	var thisdate = new Date();
	var hours = thisdate.getHours();
	if(hours<10){
		hours="0"+hours;	
	}
	var minutes = thisdate.getMinutes();
	if(minutes<10){
		minutes="0"+minutes;	
	}
	var time = hours+":"+minutes;
	return 	time;
}
function addCalToEvent(cals){
	if(new Date($('#fromdate').attr('data-cal'))>new Date($('#todate').attr('data-cal'))){
		$('#todate').attr('data-cal',$('#fromdate').attr('data-cal'));
		$('#todate').val($('#fromdate').val());
	}
}

function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	
	return true;
}