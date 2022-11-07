var dirty=false;
var TO_CAL;
$(document).ready(function(){
	setImagesChannelUp();
	$('#addmoretext').keydown(function(event){
		var code = (event.keyCode ? event.keyCode : event.which);		
		if(code === 13) { //Enter keycode or tab
			if(validateEmail($('#addmoretext').val())){
				var friendstr='<div class="peoplesdata formttl" data-id="" data-email="'+$('#addmoretext').val()+'"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">'+$('#addmoretext').val()+'</div><div class="peoplesdataclose"></div></div>';
				$('.emailcontainer').prepend(friendstr);
				//$('.emailcontainer .peoplesdata').first().css("width",($('.emailcontainer .peoplesdata').first().find('.peoplesdatainside').width()+38)+"px");
				$('#addmoretext').val('');
				$('#addmoretext').blur();
				
				var height = $('#inviteForm .formContainerglob').height()+81;
				$('#inviteForm').css('height',height+"px");
				event.preventDefault();
			}
		}
	})
	$(document).on('click',".accountbut_invite" ,function(){
		var $this=$(this);
		var $parent=$this.parent().parent();
		var invite_msg = getObjectData($parent.find("#invitetext"));
		var inviteArray=new Array();
		$parent.find('.peoplecontainer .emailcontainer .peoplesdata').each(function(){
			var obj=$(this);
			if( obj.attr('data-email') != '' ){
				inviteArray.push( {email : obj.attr('data-email') } );
			}
		});
		if(inviteArray.length==0){
			return;	
		}
		
		TTCallAPI({
			what: 'social/share',
			data: {entity_type:SOCIAL_ENTITY_CHANNEL, entity_id:channelGlobalID(), share_with : inviteArray, share_type: SOCIAL_SHARE_TYPE_INVITE , msg:invite_msg, channel_id:channelGlobalID(),addToFeeds:0},
			callback: function(ret){
				$parent.find('.accountbut5').click();
                                TTAlert({
                                    msg: t('Invitation sent.'),
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                                });
			}
		});

	});
	$('.DynarchCalendar-topCont').live('keydown',function(e){
		e.preventDefault();
	});
	var initLayout = function() {
		$('#colorSelector').ColorPicker({
			color: '#0000ff',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('.backgroundImageStanInside').attr('data-color',hex);
				$('.backgroundImageStanInside').css('background-color','#' + hex);
				$('.backgroundImageStanInside').html('');
				$('.backgroundImageStanInside').attr('data-value','');
			}
		});
		
	};	
	
	EYE.register(initLayout, 'init');
	
	$('#picfancyboxbutton').fancybox({
		"padding"	:0,
		"margin"	:0,
		//"width"			: 1024,
		//"height"			: 768,
		"transitionIn"		: "none",
		"transitionOut"	: "none",
		"type"				: "iframe"
	});
				
	var throwError = 0;
	var throwChaError = 0;
	
	$(document).on('click',".peoplesdataclose" ,function(){
		var parents=$(this).parent();
		parents.remove();
		if(parents.attr('data-id')!=''){
			SelectedUsersDelete(parents.attr('data-id'));	
		}
		if(parents.attr('id')=='friendsdata'){
			$('.uploadinfocheckbox3').removeClass('active');
		}else if(parents.attr('id')=='followersdata'){
			$('.uploadinfocheckbox4').removeClass('active');
		}
	});
	$(document).on('click',".uploadinfocheckbox" ,function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			if($(this).hasClass('uploadinfocheckbox3')){
				$('#friendsdata').remove();
			}else if($(this).hasClass('uploadinfocheckbox4')){
				$('#followersdata').remove();
			}
		}else{
			if($(this).hasClass('uploadinfocheckbox3')){
				var friendstr='<div class="peoplesdata formttl" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends")+'</div><div class="peoplesdataclose"></div></div>';
				$('.emailcontainer').prepend(friendstr);
				//$('#friendsdata').css("width",($('#friendsdata .peoplesdatainside').width()+20)+"px");
			}else if($(this).hasClass('uploadinfocheckbox4')){
				var followerstr='<div class="peoplesdata formttl" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("followers")+'</div><div class="peoplesdataclose"></div></div>';
				$('.emailcontainer').prepend(followerstr);
				//$('#followersdata').css("width",($('#followersdata .peoplesdatainside').width()+20)+"px");
			}
			$(this).addClass('active');
		}
	});
	$(".regHeader").each(function(){
		var $this = $(this);
		
		var thisparent = $this.parent();
		$this.click(function(){
			var height = thisparent.find('.formContainerglob').height()+81;
			if(thisparent.attr('id')=="channelURLForm"){
				height += 20;
			}
			if(thisparent.hasClass("selected")){
				thisparent.find('.addNewBut').hide();
				$this.find('#arrow1').attr("src", $this.find('#arrow1').attr("src").replace("_up","_down"));
				thisparent.attr('data-height',60);
				thisparent.animate({height:'60px'},500,function(){
					thisparent.removeClass("selected");
					//FixFooter();
				});
			}else{
				$this.find('#arrow1').attr("src", $this.find('#arrow1').attr("src").replace("_down","_up"));
				thisparent.attr('data-height',height);
				thisparent.animate({height:height+'px'},500,function(){
					thisparent.addClass("selected");
					thisparent.find('.addNewBut').show();
				});
			}
		});
	});	
	
	$(document).on('click',".uploadinfocheckboxtellus .uploadinfocheckboxpic" ,function(){
		var curob=$(this).parent();
		if(curob.hasClass('active')){
			curob.removeClass('active');
		}else{
			curob.addClass('active');
		}
	});
	$(document).on('click',".accountbut4" ,function(){
		var curob=$(this).parent().parent();
		curob.find(".uploadinfocheckbox").addClass('active');
		curob.find(".uploadinfocheckbox.uploadinfocheckboxprivate").removeClass('active');
	});
	$(document).on('click',".accountbut3" ,function(){
		var curob=$(this).parent().parent();
		curob.find(".uploadinfocheckbox").removeClass('active');
	});
	$(document).on('click',".accountbut1" ,function(){
		var curob=$(this).parent().parent().parent();
		if(curob.hasClass("selected")){
			curob.find('.regHeader').click();
		}
		if(curob.attr('id')=='inviteForm'){
			$('#inviteForm .uploadinfocheckbox').removeClass('active');
			$('#inviteForm #invitetext').val('');
			$('#inviteForm #invitetext').blur();
			$('#inviteForm #addmoretext').val('');
			$('#inviteForm #addmoretext').blur();
			$('#inviteForm .peoplesdata').each(function() {
				var parents=$(this);
				parents.remove(); 
            });
		}else{
			resetInitData(curob);
		}
	});
	$(document).on('click',".accountbutEVCancel" ,function(){
		var curob=$(this).parent().parent();
		var newaddobj=curob.parent();
		curob.remove();		
		var height = newaddobj.parent().find('.formContainerglob').height()+81;
		newaddobj.parent().animate({height:height+'px'},500);
	});
	$(document).on('click',".accountbutBRCancel" ,function(){
		var curob=$(this).parent().parent();
		var newaddobj=curob.parent();
		curob.remove();		
		var height = newaddobj.parent().find('.formContainerglob').height()+81;
		newaddobj.parent().animate({height:height+'px'},500);
	});
	$(document).on('click',".accountbut5" ,function(){
		var curob=$(this).parent().parent().parent();
		if(curob.hasClass("selected")){
			curob.find('.subRegHeader').click();
		}
	});
	$(document).on('click',".addNewBut" ,function(){
		var curob=$(this);
		var thisparent=curob.parent().parent();
		var newaddobj=curob.parent().find('.formContainersub').first();		
		if(newaddobj.hasClass('formContainersubadd')){
			return;	
		}
		var mystr='';
		var formid=parseInt(curob.attr("data-value"));
		
		switch(formid){
			case 8:
				mystr='<div class="formContainersub margintop5" data-value="8"><input name="customizedlinks" id="customizedlinks" type="text" class="ChaFocus" style="font-family:Arial, Helvetica, sans-serif; width:403px; float:left;" value="" data-value="" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/><div class="removeNewBut"><div class="addNewBut_over"><div class="addNewBut_overtxt">'+t('remove links')+'</div></div></div></div>';				
			break;
			case 9:
				mystr='<div class="formContainersub margintop5" data-value="9"><input name="sociallinks" id="sociallinks" type="text" class="ChaFocus" style="font-family:Arial, Helvetica, sans-serif; width:403px; float:left;" value="" data-value="" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/><div class="removeNewBut"><div class="addNewBut_over"><div class="addNewBut_overtxt">'+t('remove links')+'</div></div></div></div>';				
			break;
		}
		
		curob.parent().append(mystr);
		
		var height = thisparent.find('.formContainerglob').height()+81;
		thisparent.animate({height:height+'px'},500);
			
	});
	$(document).on('click',".removeNewBut, .removeNewButEdit" ,function(){
		var curob=$(this).parent();		
		var formid=parseInt(curob.attr("data-value"));

		switch(formid){
			case 8:
				removechannelpagedatalinks(curob.find('#customizedlinks').attr('data-id'),curob);				
			break;
			case 9:
				removechannelpagedatalinks(curob.find('#sociallinks').attr('data-id'),curob);						
			break;
		}
	});
	$(document).on('click',".accountbutedit1" ,function(){
		var curob=$(this).parent().parent();
		curob.html('');
		var thisparent=curob.parent();
		curob.hide();
		var height = thisparent.parent().parent().find('.formContainerglob').height()+81;
		thisparent.parent().parent().animate({height:height+'px'},500);
	});
	
	$(document).on('click',".accountbut2" ,function(){
		var curob=$(this).parent().parent().parent();
		var curobsub=$(this).parent().parent();
		var formid=""+curob.attr("id");
		switch(formid){
			case "profilePhotoForm":
				if($('#profilePhotoForm .ImageStanInside').attr('data-value')!=''){
					updatechannelpagedata("profilepicchannel",$('#profilePhotoForm .ImageStanInside').attr('data-value'),curob);
				}
				break;
			case "coverPhotoForm":
				if($('#coverPhotoForm .ImageStanInside').attr('data-value')!=''){
					updatechannelpagedata("coverpicchannel",$('#coverPhotoForm .ImageStanInside').attr('data-value'),curob);
				}								
				break;
			case "backgroundPhotoForm":
				if($('#backgroundPhotoForm .ImageStanInside').attr('data-value')!=''){
					updatechannelpagedata("backgroundpicchannel",$('#backgroundPhotoForm .ImageStanInside').attr('data-value'),curob);
				}
				if($('#backgroundPhotoForm .backgroundImageStanInside').attr('data-color')!=''){
					updatechannelpagedata("backgroundcolorpicchannel",$('#backgroundPhotoForm .backgroundImageStanInside').attr('data-color'),curob);
				}
				break;
			case "aboutChannelForm":
				var aboutchannel = getObjectData($('#aboutChannelForm textarea[name=aboutchannel]'));
				if(aboutchannel.length == 0){
					TTAlert({
						msg: t('Invalid description'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					
					$('#aboutChannelForm textarea[name=aboutchannel]').addClass('InputErr');
					return;
				}
				updatechannelpagedata("aboutchannel",aboutchannel,curob);
				break;
			case "sloganForm":
				var changeslogan = getObjectData($('#sloganForm input[name=changeslogan]'));
				if(changeslogan.length == 0){
					TTAlert({
						msg: t('Invalid channel slogan'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					
					$('#sloganForm input[name=changeslogan]').addClass('InputErr');
					return;
				}
				updatechannelpagedata("changeslogan",changeslogan,curob);
				break;
			case "keywordsForm":
				var changekeywords = getObjectData($('#keywordsForm input[name=changekeywords]'));
				if(changekeywords.length == 0){
					TTAlert({
						msg: t('Invalid keywords'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					
					$('#keywordsForm input[name=changekeywords]').addClass('InputErr');
					return;
				}
				updatechannelpagedata("changekeywords",changekeywords,curob);
				break;
			case "channelURLForm":
				var changechannelURL = getObjectData($('#channelURLForm input[name=changechannelurl]'));
				if(changechannelURL.length == 0){
					TTAlert({
						msg: t('Invalid channel URL'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					
					$('#channelURLForm input[name=changechannelurl]').addClass('InputErr');
					return;
				}
				updatechannelpagedata("changechannelURL",changechannelURL,curob);
				break;
			case "customizedLinksForm":
				var globlinkArray=new Array();
				curobsub.find('.formContainersub').each(function(){
					var myobjstr=getObjectData($(this).find('#customizedlinks'));
					if(myobjstr.length != 0){
						globlinkArray.push(myobjstr);
					}					
				});
				if(globlinkArray.length>0){
					var globlinkArraystr=globlinkArray.join('/*/');
					updatechannelpagedatalinks("0",globlinkArraystr,curob);
				}else{
					TTAlert({
						msg: t('Invalid customized links'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
				break;
			case "socialLinksForm":
				var globlinkArray=new Array();
				curobsub.find('.formContainersub').each(function(){
					var myobjstr=getObjectData($(this).find('#sociallinks'));
					if(myobjstr.length != 0){
						globlinkArray.push(myobjstr);
					}					
				});
				if(globlinkArray.length>0){
					var globlinkArraystr=globlinkArray.join('/*/');
					updatechannelpagedatalinks("1",globlinkArraystr,curob);
				}else{
					TTAlert({
						msg: t('Invalid social links'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});	
				}
				break;
		}
	});
	$('input[name=YourPassword]').focus(function(){
		dirty = true;
	});
	$('input[name=YourPassword]').blur(function(){
		if( $(this).val().length == 0 ){
			dirty = false;
		}
	});
	$('#tellusplus').fancybox();
});

function addCalTo(cals){
	if(new Date($('#fromdate').attr('data-cal'))>new Date($('#todate').attr('data-cal'))){
		$('#todate').attr('data-cal',$('#fromdate').attr('data-cal'));
		$('#todate').val($('#fromdate').val());
	}
}
function checkAccountInfo(uname,new_pass,new_pass2,old_pass,new_email,new_email2,old_email){
	$('#BecomeTuberForm input').removeClass('InputErr');
	var min_pswd_length = 6;
	if(uname.length == 0){
		TTAlert({
			msg: t('Invalid owner name'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#BecomeTuberForm input[name=fname]').addClass('InputErr');
		return false;
	}else if(dirty && old_pass.length == 0){
		TTAlert({
			msg: t('Please specify old password'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#BecomeTuberForm input[name=YourPassword]').addClass('InputErr');
		return false;
	}else if(dirty && (new_pass.length < min_pswd_length) ){
		TTAlert({
			msg: sprintf( t('Password must be minimum %s characters long') , [min_pswd_length] ) ,
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#BecomeTuberForm input[name=NewPassword]').addClass('InputErr');
		return false;
	}else if( dirty && (new_pass != new_pass2) ){		
		TTAlert({
			msg: t('passwords mismatch'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#BecomeTuberForm input[name=NewPassword]').addClass('InputErr');
		$('#BecomeTuberForm input[name=ConfirmNewPassword]').addClass('InputErr');
		return false;
	}else if( !validateEmail(old_email) && old_email.length!=0){		
		TTAlert({
			msg: t('Please enter a valid email'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#BecomeTuberForm input[name=YourEmail]').addClass('InputErr');
		return false;
	}else if( (!validateEmail(new_email) || !validateEmail(new_email2) || new_email!=new_email2) && old_email.length!=0 && new_email.length!=0){
		TTAlert({
			msg: t('Invalid emails'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#BecomeTuberForm input[name=NewEmail]').addClass('InputErr');
		$('#BecomeTuberForm input[name=ConfirmNewEmail]').addClass('InputErr');
		return false;
	}
	return true;
}
function checkChannelInfo(cname,ccategory,ccountry){
	$('#CreateChannelForm input').removeClass('InputErr');
	$('#CreateChannelForm select').removeClass('InputErr');	
	if(cname.length == 0){
		TTAlert({
			msg: t('Invalid channel name'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#CreateChannelForm input[name=cname]').addClass('InputErr');
		return false;
	}else if(ccategory == 0){
		TTAlert({
			msg: t('Please select category'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#CreateChannelForm select[name=ccategory]').addClass('InputErr');
		return false;
	}else if(ccountry==0){
		TTAlert({
			msg: t('Please select country'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		
		$('#CreateChannelForm select[name=ccountry]').addClass('InputErr');
		return false;
	}
	return true;
}

function updatechannelpagedata(param1,param2,curob){
	$.ajax({
		url: ReturnLink('/ajax/info_page_manage_channel.php'),
		data: {globchannelid:globchannelid,data:param2,str:param1},
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
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}
			
			if(ret.status == 'ok'){
				curob.find('.regHeader').click();
				if(param1=="changechannelURL"){
					$('.channel_page_but').attr('href',ReturnLink('/channel/'+param2));
				}
			}else{
				TTAlert({
					msg: ret.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
		}
	});	
}
function updatechannelpagedatalinks(param1,param2,curob){
	$.ajax({
		url: ReturnLink('/ajax/info_page_links_manage_channel.php'),
		data: {globchannelid:globchannelid,data:param2,str:param1},
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
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}
			
			if(ret.status == 'ok'){
				curob.find('.regHeader').click();
			}else{
				TTAlert({
					msg: ret.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
		}
	});	
}
function updatechannelpagedatanews(param1,curob){
	$.ajax({
		url: ReturnLink('/ajax/info_page_news_manage_channel.php'),
		data: {globchannelid:globchannelid,data:param1},
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
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}
			
			if(ret.status == 'ok'){
				curob.find('.regHeader').click();
			}else{
				TTAlert({
					msg: ret.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
		}
	});	
}

function removechannelpagedatalinks(id,curob){
	var thisparent=curob.parent().parent();
		
	$.ajax({
		url: ReturnLink('/ajax/info_page_links_delete_manage_channel.php'),
		data: {globchannelid:globchannelid,id:id},
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
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}
			
			if(ret.status == 'ok'){
				curob.remove();	
				var height = thisparent.find('.formContainerglob').height()+81;
				thisparent.animate({height:height+'px'},500);
			}else{
				TTAlert({
					msg: ret.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
		}
	});	
}

function resetInitData(curob){
	var myid=""+curob.attr('id');
	var newstrimage='';
	$.ajax({
		url: ReturnLink('/ajax/info_page_data_manage_channel.php'),
		data: {globchannelid:globchannelid,data:myid},
		type: 'post',
		success: function(e){			
			var mydata=""+e;
			if(myid=="profilePhotoForm"){
				if(mydata==''){
					var mydataurl=ReturnLink('/media/tubers/tuber.jpg');	
				}else{
					mydataurl=ReturnLink('/media/channel/'+globchannelid+'/thumb/'+mydata);
				}
				$('#profilePhotoForm .ImageStanInside').attr('data-value',mydata);
				newstrimage='<img src="'+mydataurl+ '?x=' + Math.random() + '" alt="profile photo"/>';
				$('#profilePhotoForm .ImageStanInside').html(newstrimage);
			}else if(myid=="coverPhotoForm"){
				if(mydata==''){
					var mydataurl=ReturnLink('/images/channel/coverphoto.jpg');						
				}else{
					mydataurl=ReturnLink('/media/channel/'+globchannelid+'/thumb/'+mydata);
				}
				
				newstrimage='<img src="'+mydataurl+ '?x=' + Math.random() + '" alt="cover photo"/>';
				$('#coverPhotoForm .ImageStanInside').attr('data-value',mydata);
				
				$('#coverPhotoForm .ImageStanInside').html(newstrimage);				
			}else if(myid=="backgroundPhotoForm"){
				$('#backgroundPhotoForm .backgroundImageStan').html(mydata);
			}else if(myid=="aboutChannelForm"){
				$('#aboutChannelForm #aboutchannel').val(mydata);
			}else if(myid=="sloganForm"){
				$('#sloganForm #changeslogan').val(mydata);
			}else if(myid=="keywordsForm"){
				$('#keywordsForm #changekeywords').val(mydata);
			}else if(myid=="channelURLForm"){
				$('#channelURLForm #changechannelurl').val(mydata);
			}else if(myid=="customizedLinksForm"){
				$('#customizedLinksForm .formContainersub').each(function() {
					$(this).remove();
				});
				$('#customizedLinksForm .formContainerglob').append(mydata);
			}else if(myid=="socialLinksForm"){
				$('#socialLinksForm .formContainersub').each(function() {
					$(this).remove();
				});
				$('#socialLinksForm .formContainerglob').append(mydata);
			}else if(myid=="newsForm"){
				$('#newsForm .formContainersub').each(function() {
					$(this).remove();
				});
				$('#newsForm .formContainerglob').append(mydata);
			}
		}
	});	
}
function getEventData(curob){
	var thisparent=curob.parent();	
	$.ajax({
		url: ReturnLink('/ajax/info_page_event_data_manage_channel.php'),
		data: {globchannelid:globchannelid,id:curob.attr('data-id')},
		type: 'post',
		success: function(data){
			if(data){
				thisparent.find('.eventsubedit').html(data);
				thisparent.find('.eventsubedit').show();
				var height = thisparent.parent().parent().find('.formContainerglob').height()+81;
				thisparent.parent().parent().animate({height:height+'px'},500);
			}
		}
	});	
}
function getBrochureData(curob){
	var thisparent=curob.parent();	
	$.ajax({
		url: ReturnLink('/ajax/info_page_brochure_data_manage_channel.php'),
		data: {globchannelid:globchannelid,id:curob.attr('data-id')},
		type: 'post',
		success: function(data){
			if(data){
				thisparent.find('.eventsubedit').html(data);
				thisparent.find('.eventsubedit').show();
				var height = thisparent.parent().parent().find('.formContainerglob').height()+81;
				thisparent.parent().parent().animate({height:height+'px'},500);
			}
		}
	});	
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
function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	
	return true;
}