var dirty=false;
var TO_CAL;

$(document).ready(function(){
	setImagesChannelUp();
        addmoreusersautocomplete_parent( $('#parent_input') , channelGlobalID() );
        addmoreusersautocomplete_parent( $('#sub_input') , channelGlobalID() );
	$(document).on('click',".list_item_undo" ,function(){
            var $this = $(this);
            updateNotificationData($this, 'getNotifications');
	});
        $(document).on('click',".list_item_undo_ch" ,function(){
            var $this = $(this);
            updateNotificationDataCH($this, 'getNotifications');
	});
	$('#addmoretext').keydown(function(event){
		var code = (event.keyCode ? event.keyCode : event.which);
		if(code === 13) { //Enter keycode or tab
			if(validateEmail($('#addmoretext').val())){
				var friendstr='<div class="peoplesdata formttl" data-id="" data-email="'+$('#addmoretext').val()+'"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">'+$('#addmoretext').val()+'</div><div class="peoplesdataclose"></div></div>';
				$('.emailcontainer').prepend(friendstr);
//				$('.emailcontainer .peoplesdata').first().css("width",($('.emailcontainer .peoplesdata').first().find('.peoplesdatainside').width()+38)+"px");
				$('#addmoretext').val('');
				$('#addmoretext').blur();

				var height = $('#inviteForm .formContainer').height()+74;
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
	initPrivacyAuto();

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

	var throwError = 0;
	var throwChaError = 0;

	addAutoCompleteList("CreateChannelForm");

	$(".regHeader").each(function(){
		var $this = $(this);

		var thisparent = $this.parent();
		$this.click(function(){
			//var height = thisparent.find('.formContainerglob').height()+91;

			if(thisparent.hasClass("selected")){
				$this.find('#arrow1').attr("src", $this.find('#arrow1').attr("src").replace("_up","_down"));
				thisparent.find('.formContainerglob').css('height',0);
				thisparent.removeClass("selected");
			}else{
				$this.find('#arrow1').attr("src", $this.find('#arrow1').attr("src").replace("_down","_up"));
				thisparent.find('.formContainerglob').css('height','auto');
				thisparent.addClass("selected");
			}
		});
	})
	$(".subRegHeader").each(function(){
		var $this = $(this);

		var thisparent = $this.parent();
		$this.click(function(){
			var heightinit = parseInt(thisparent.attr('data-value'));
			var height = thisparent.find('.formContainer').height()+74;
			if(thisparent.hasClass("selected")){
				thisparent.find('.addNewBut').hide();

				$this.find('#arrow1').attr("src", $this.find('#arrow1').attr("src").replace("_up","_down"));
				thisparent.animate({height:heightinit+'px'},500,function(){
					thisparent.removeClass("selected");
				});
			}else{
				$this.find('#arrow1').attr("src", $this.find('#arrow1').attr("src").replace("_down","_up"));

				thisparent.animate({height:height+'px'},500,function(){
					thisparent.find('.addNewBut').show();
					thisparent.addClass("selected");
				});
			}
		});
	})

	$(document).on('click',".location_text" ,function(){
		var $parent=$(this).parent().parent();
		if($parent.find('.locationContent').css('display')!="none"){
			$parent.find('.locationContent').hide();
		}else{
			$parent.find('.locationContent').show();
		}
	});
        $(document).on('click',"#parentChannel_remove" ,function(){
            var $parent=$(this).closest('.channel_parents_item');
            var data_id = $parent.attr('data-id');
            var alrt = "<b>"+$parent.find('.channel_parents_item_title').attr('title')+"</b>";
            TTAlert({
                msg: t("Are you sure you want to remove the sub channel to ") +alrt+"?",
                type: 'action',
                btn1: t('cancel'),
                btn2: t('confirm'),
                btn2Callback: function(data) {
                    if (data) {
                        $('.upload-overlay-loading-fix').show();
                        TTCallAPI({
                            what: 'channel/relation/delete',
                            data: {parent_id:data_id,channel_id:channelGlobalID(),relation_type:CHANNEL_RELATION_TYPE_PARENT},
                            callback: function(ret){  
                                $('.upload-overlay-loading-fix').hide();
                                if( ret.status === 'error' ){
                                    TTAlert({
                                        msg: ret.msg,
                                        type: 'alert',
                                        btn1: t('ok'),
                                        btn2: '',
                                        btn2Callback: null
                                    });
                                    return;
                                }
                                SelectedChannelsDelete(data_id, $('#parent_input'))
                                $parent.remove();
                            }
                        });
                    }
                }
            });
	});
        $(document).on('click',"#subChannel_remove" ,function(){
            var $parent=$(this).closest('.channel_parents_item');
            var data_id = $parent.attr('data-id');
            var alrt = "<b>"+$parent.find('.channel_parents_item_title').attr('title')+"</b>";
            TTAlert({
                msg: t("Are you sure you want to remove the sub channel ") +alrt+"?",
                type: 'action',
                btn1: t('cancel'),
                btn2: t('confirm'),
                btn2Callback: function(data) {
                    if (data) {
                        $('.upload-overlay-loading-fix').show();
                        TTCallAPI({
                            what: 'channel/relation/delete',
                            data: {parent_id:channelGlobalID(),channel_id:data_id,relation_type:CHANNEL_RELATION_TYPE_SUB},
                            callback: function(ret){  
                                $('.upload-overlay-loading-fix').hide();
                                if( ret.status === 'error' ){
                                    TTAlert({
                                        msg: ret.msg,
                                        type: 'alert',
                                        btn1: t('ok'),
                                        btn2: '',
                                        btn2Callback: null
                                    });
                                    return;
                                }
                                SelectedChannelsDelete(data_id, $('#sub_input'))
                                $parent.remove();
                            }
                        });
                    }
                }
            });
	});
        $(document).on('click',"#parentChannel_send" ,function(){
            var prcon_parent = $(this).closest('.formContainer');
            var parent_input = getObjectData($('#parent_input'));
            var data_id = $('#parent_input').attr('data-id');
            var data_url = $('#parent_input').attr('data-url');
            var data_logo = $('#parent_input').attr('data-logo');
            var data_str = $('#parent_input').attr('data-str');
            var channel_id = channelGlobalID();
            if(parent_input!='' && parseInt(data_id)>0){
                $('.upload-overlay-loading-fix').show();
                TTCallAPI({
                    what: 'channel/relation/add',
                    data: {parent_id:data_id,channel_id:channel_id,relation_type:CHANNEL_RELATION_TYPE_PARENT},
                    callback: function(ret){  
                        $('.upload-overlay-loading-fix').hide();
                        if( ret.status === 'error' ){
                            TTAlert({
                                msg: ret.msg,
                                type: 'alert',
                                btn1: t('ok'),
                                btn2: '',
                                btn2Callback: null
                            });
                            return;
                        }
                        SelectedChannelsAdd(data_id,$('#parent_input'));
                        var newStr='<div class="channel_parents_item" data-id="'+data_id+'">';
                            newStr +='<a href="'+data_url+'" target="_blank" class="channel_parents_item_link" title="'+parent_input+'"><img width="28" height="28" class="sub_items_pic" src="'+data_logo+'" alt="'+parent_input+'"></a>';
                            newStr +='<a href="'+data_url+'" target="_blank" class="channel_parents_item_title" title="'+parent_input+'">'+data_str+'</a>';
                            newStr +='<div id="parentChannel_remove" class="hide_sub_items_but"><div class="hide_remove_butons_over">'+t("remove")+'</div></div>';
                            newStr +='<div class="sep"></div>';
                        newStr +='</div>';
                        
                        $('#parentChannel .channel_parents_container_second').append(newStr);
                        $('#parent_input').val('');
                        $('#parent_input').blur();
                        $('#parent_input').attr('data-id','');
                        //prcon_parent
                        var height = prcon_parent.find('.channel_parents_container').height()+74+32;
                        $('#parentChannel').animate({height:height+'px'},500,function(){
                            $('#parentChannel').find('.addNewBut').show();
                            $('#parentChannel').addClass("selected");
                        });
                    }
		});
            }
            else{
                TTAlert({
                    msg: t('This channel does not exist'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
	});
        $(document).on('click',".sub_items_not" ,function(){
            var $this = $(this);
            var $parent = $this.closest('.channel_parents_item');
            if(parseInt($this.attr('data-notification'))== 1){
                $this.html(t('get notifications'));
                $this.attr('data-notification', 0);
                updateNotificationDataCH($parent, 'stopNotifications');			
            }else{
                $this.html(t('stop notifications'));
                $this.attr('data-notification', 1);
                updateNotificationDataCH($parent, 'getNotifications');			
            }
        });
        $(document).on('click',"#subChannel_send" ,function(){
            var prcon_parent = $(this).closest('.formContainer');
            var parent_input = getObjectData($('#sub_input'));
            var data_id = $('#sub_input').attr('data-id');
            var data_url = $('#sub_input').attr('data-url');
            var data_logo = $('#sub_input').attr('data-logo');
            var data_str = $('#sub_input').attr('data-str');
            var channel_id = channelGlobalID();
            if(parent_input!='' && parseInt(data_id)>0){
                $('.upload-overlay-loading-fix').show();
                TTCallAPI({
                    what: 'channel/relation/add',
                    data: {parent_id:channel_id,channel_id:data_id,relation_type:CHANNEL_RELATION_TYPE_SUB},
                    callback: function(ret){  
                        $('.upload-overlay-loading-fix').hide();
                        if( ret.status === 'error' ){
                            TTAlert({
                                msg: ret.msg,
                                type: 'alert',
                                btn1: t('ok'),
                                btn2: '',
                                btn2Callback: null
                            });
                            return;
                        }
                        SelectedChannelsAdd(data_id,$('#sub_input'));
                        var newStr='<div class="channel_parents_item" data-id="'+data_id+'">';
                            newStr +='<a href="'+data_url+'" target="_blank" class="channel_parents_item_link" title="'+parent_input+'"><img width="28" height="28" class="sub_items_pic" src="'+data_logo+'" alt="'+parent_input+'"></a>';
                            newStr +='<a href="'+data_url+'" target="_blank" class="channel_parents_item_title" title="'+parent_input+'">'+data_str+'</a>';
                            newStr +='<div id="subChannel_remove" class="hide_sub_items_but"><div class="hide_remove_butons_over">'+t("remove")+'</div></div>';
                            newStr +='<div class="sep"></div>';
                        newStr +='</div>';
                                                
                        $('#subChannel .channel_parents_container_second').append(newStr);
                        $('#sub_input').val('');
                        $('#sub_input').blur();
                        $('#sub_input').attr('data-id','');
                        
                        var height = prcon_parent.find('.channel_parents_container').height()+74+32;
                        $('#subChannel').animate({height:height+'px'},500,function(){
                            $('#subChannel').find('.addNewBut').show();
                            $('#subChannel').addClass("selected");
                        });
                    }
		});
            }
            else{
                TTAlert({
                    msg: t('This sub channel does not exist'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
	});
	$(document).on('click',".signInbtn1" ,function(){
		$('.fancybox-close').click();
	});
	$(document).on('click',"#tellusplus" ,function(){
		$('#tellcontainer').show();
		var height = $('#DeleteChannelForm .formContainerglob').height()+91;
		$('#DeleteChannelForm').animate({height:height+'px'},500);
	});
	$(document).on('click',".uploadinfocheckboxtellus .uploadinfocheckboxpic" ,function(){
            var curob=$(this).parent();
            if(curob.hasClass('active')){
                curob.removeClass('active');
                if(curob.attr('id')=="reportcheckbox"){
                    $('#reportcontainer').hide();				
                    var height = $('#DeleteChannelForm .formContainerglob').height()+91;
                    $('#DeleteChannelForm').animate({height:height+'px'},500);
                    $('#reportsubject').val('');
                    $('#reportdesc').val('');
                }
            }else{
                curob.addClass('active');
                if(curob.attr('id')=="reportcheckbox"){
                    $('#reportcontainer').show();
                    var height = $('#DeleteChannelForm .formContainerglob').height()+91;
                    $('#DeleteChannelForm').animate({height:height+'px'},500);
                }
            }
	});
	$(document).on('click',"#sendReport" ,function(){
            if( $('.uploadinfocheckbox_reason').hasClass('active') ){
		$('.upload-overlay-loading-fix').show();
                var $parent = $(this).closest('.sharepopup_container');
                var entity_id = channelGlobalID();
                var entity_type = SOCIAL_ENTITY_CHANNEL;
                var channel_id = channelGlobalID();
                var msg = $('#reportdesc').val();
                if($('#reportcheckbox').hasClass('active') && msg==''){
                    TTAlert({
                        msg: t('Please write your Report'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });                    
                    return;
                }
                var reason_array = new Array();
                $('.uploadinfocheckbox_reason.active').each(function(){
                    var $this = $(this);
                    reason_array.push( $this.attr('data-id') );
                });
                var reason = reason_array.join(',');
		TTCallAPI({
                    what: 'user/report/add',
                    data: {entity_type:entity_type, entity_id:entity_id, channel_id : channel_id, msg: msg , reason:reason},
                    callback: function(ret){
                        $('.upload-overlay-loading-fix').hide();
                        if( ret.status === 'error' ){
                            TTAlert({
                                msg: ret.msg,
                                type: 'alert',
                                btn1: t('ok'),
                                btn2: '',
                                btn2Callback: null
                            });
                            return;
                        }
                        TTAlert({
                                msg: ret.msg,
                                type: 'alert',
                                btn1: t('ok'),
                                btn2: '',
                                btn2Callback: null
                        });
                        $('#tellcontainer').hide();
                        $('.uploadinfocheckbox_delete').removeClass('displaynone');
                        $('.accountbutcontainer_delete').removeClass('displaynone');
                    }
		});
            }else{
                TTAlert({
                    msg: t('Please choose one of the above proposed options to Report'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });			 	
            }
	});
	$(document).on('click',".eventsub" ,function(){
		var curob=$(this);
		var newaddobj=curob.parent().parent().find('.formContainersub').first();
		if(newaddobj.hasClass('formContainersubadd')){
			newaddobj.remove();
		}
		curob.parent().parent().find('.accountbutedit1').each(function() {
			if(curob.parent().index()!=$(this).parent().parent().parent().index()){
           		$(this).click();
			}
        });
		getEventData(curob);
	});
	$(document).on('click',".accountbutedit1" ,function(){
		var curob=$(this).parent().parent();
		curob.html('');
		var thisparent=curob.parent().parent().parent();
		curob.hide();
		var height = thisparent.find('.formContainer').height()+74;
		thisparent.css('height',height+'px');
		var currparent=thisparent.parent().parent();
		currparent.attr('data-height',currparent.find('.formContainerglob').height()+91);
		currparent.css('height',parseInt(currparent.attr('data-height')));
	});
	$(document).on('click',".accountbutEVCancel" ,function(){
		var curob=$(this).parent().parent();
		var newaddobj=curob.parent();
		curob.remove();
		var height = newaddobj.parent().find('.formContainer').height()+74;
		newaddobj.parent().css('height',height+'px');
		var currparent=newaddobj.parent().parent().parent();
		currparent.attr('data-height',currparent.find('.formContainerglob').height()+91);
		currparent.css('height',parseInt(currparent.attr('data-height')));
	});
	$(document).on('click',".accountbut1" ,function(){
		var curob=$(this).parent().parent().parent();
		if(curob.hasClass("selected")){
			curob.find('.regHeader').click();
		}
		if(curob.attr('id')=="BecomeTuberForm"){
			resetInitDataAccount(curob);
		}else if(curob.attr('id')=="CreateChannelForm"){
			resetInitDataChannelInfo(curob);
		}else if(curob.attr('id')=="privacyForm"){
			resetInitDataPrivacyForm(curob);
		}else if(curob.attr('id')=="EmailForm"){
			resetInitDataEmailForm(curob);
		}
	});
	$(document).on('click',".accountbut5" ,function(){
		var curob=$(this).parent().parent().parent();
		if(curob.hasClass("selected")){
			curob.find('.subRegHeader').click();
		}
		if(parseInt($(this).parent().parent().find('.formContainersub').attr('data-value'))==9){
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
			resetInitDataChannelPage($(this).parent().parent());
		}
	});
	$(document).on('click',".addNewBut" ,function(){
		var curob=$(this);
		var thisparent=curob.parent();
		if(!thisparent.hasClass("selected")){
			thisparent.find('.subRegHeader').click();
		}
		var height = thisparent.find('.formContainer').height()+74;
		var heightinit = parseInt(thisparent.attr('data-value'));
		var mystr='';
		var formid=parseInt(curob.attr("data-value"));
		switch(formid){
			case 7:
				mystr='<div class="formContainersub margintop5" data-value="7"><input name="customizedlinks" id="customizedlinks" type="text" class="ChaFocus" style="font-family:Arial, Helvetica, sans-serif; width:403px; float:left;" value="" data-id=""/><div class="removeNewBut"><div class="addNewBut_over"><div class="addNewBut_overtxt">'+t('remove links')+'</div></div></div></div>';
			break;
			case 8:
				mystr='<div class="formContainersub margintop5" data-value="8"><input name="sociallinks" id="sociallinks" type="text" class="ChaFocus" style="font-family:Arial, Helvetica, sans-serif; width:403px; float:left;" value="" data-id=""/><div class="removeNewBut"><div class="addNewBut_over"><div class="addNewBut_overtxt">'+t('remove links')+'</div></div></div></div>';
			break;
		}


		thisparent.find('.formContainer').append(mystr);
		var newheight = thisparent.find('.formContainer').height()+74;

		thisparent.parent().parent().attr('data-height',parseInt(thisparent.parent().parent().attr('data-height'))+(newheight-heightinit));
		thisparent.parent().parent().attr('data-height',parseInt(thisparent.parent().parent().attr('data-height'))-(height-heightinit));

		thisparent.parent().parent().css('height',parseInt(thisparent.parent().parent().attr('data-height')));
		thisparent.css('height',newheight);
	});
	$(document).on('click',".removeNewBut" ,function(){
		var curob=$(this).parent();
		var formid=parseInt(curob.attr("data-value"));
		switch(formid){
			case 7:
				removechannelpagedatalinks(curob.find('#customizedlinks').attr('data-id'),curob);
			break;
			case 8:
				removechannelpagedatalinks(curob.find('#sociallinks').attr('data-id'),curob);
			break;
		}
	});
	$(document).on('click',".accountbut6" ,function(){
		$('#ChannelPage textarea').removeClass('InputErr');
		$('#ChannelPage input').removeClass('InputErr');

		var globcurob=$(this).parent().parent().parent();
		var curob=globcurob.find('.formContainersub');
		var formid=parseInt(curob.attr("data-value"));
		switch(formid){
			case 1:
				if(curob.find('.ImageStanInside').attr('data-value')!=''){
					updatechannelpagedata("profilepicchannel",curob.find('.ImageStanInside').attr('data-value'),'');
				}
			break;
			case 2:
				if(curob.find('.ImageStanInside').attr('data-value')!=''){
					updatechannelpagedata("coverpicchannel",curob.find('.ImageStanInside').attr('data-value'),globcurob);
				}
			break;
			case 3:
				if(curob.find('.ImageStanInside').attr('data-value')!=''){
					updatechannelpagedata("backgroundpicchannel",curob.find('.ImageStanInside').attr('data-value'),globcurob);
				}
				if(curob.find('.backgroundImageStanInside').attr('data-color')!=''){
					updatechannelpagedata("backgroundcolorpicchannel",curob.find('.backgroundImageStanInside').attr('data-color'),globcurob);
				}
			break;
			case 4:
				var aboutchannel = ''+$('#ChannelPage textarea[name=aboutchannel]').val();
				if(aboutchannel.length == 0){
					TTAlert({
						msg: t('Invalid description'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					$('#ChannelPage textarea[name=aboutchannel]').addClass('InputErr');
					return;
				}
				updatechannelpagedata("aboutchannel",aboutchannel,globcurob);
			break;
			case 5:
				var changeslogan = ''+$('#ChannelPage input[name=changeslogan]').val();
				/*if(changeslogan.length == 0){
					TTAlert({
						msg: t('Invalid channel slogan'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					$('#ChannelPage input[name=changeslogan]').addClass('InputErr');
					return;
				}*/
				updatechannelpagedata("changeslogan",changeslogan,globcurob);

			break;
			case 6:
				var changekeywords = ''+$('#ChannelPage input[name=changekeywords]').val();
				if(changekeywords.length == 0){
					TTAlert({
						msg: t('Invalid keywords'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					$('#ChannelPage input[name=changekeywords]').addClass('InputErr');
					return;
				}
				updatechannelpagedata("changekeywords",changekeywords,globcurob);

			break;
			case 7:
				var globlinkArray=new Array();
				curob.each(function(){
					var myobjstr=$(this).find('#customizedlinks').val();
					if(myobjstr.length != 0 && ValidURL(myobjstr)){
						globlinkArray.push(myobjstr);
					}
				});
				if(globlinkArray.length>0){
					var globlinkArraystr=globlinkArray.join('/*/');
					updatechannelpagedatalinks("0",globlinkArraystr,globcurob);
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
			case 8:
				var globlinkArray=new Array();
				curob.each(function(){
					var myobjstr=$(this).find('#sociallinks').val();
					if(myobjstr.length != 0 && ValidURL(myobjstr)){
						globlinkArray.push(myobjstr);
					}
				});
				if(globlinkArray.length>0){
					var globlinkArraystr=globlinkArray.join('/*/');
					updatechannelpagedatalinks("1",globlinkArraystr,globcurob);
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

	$(document).on('click',".accountbut2" ,function(){
		var curob=$(this).parent().parent().parent();
		var formid=""+curob.attr("id");

		switch(formid){
			case "BecomeTuberForm":
				var uname = ''+$('#BecomeTuberForm input[name=fname]').val();
				var new_pass = ''+$('#BecomeTuberForm input[name=NewPassword]').val();
				var new_pass2 = ''+$('#BecomeTuberForm input[name=ConfirmNewPassword]').val();
				var old_pass = ''+$('#BecomeTuberForm input[name=YourPassword]').val();
				var new_email = ''+$('#BecomeTuberForm input[name=NewEmail]').val();
				var old_email = ''+$('#BecomeTuberForm input[name=YourEmail]').val();
				if(checkAccountInfo(uname,new_pass,new_pass2,old_pass,new_email,old_email)){
					$('#BecomeTuberForm .errorclass').html('');
					$.ajax({
						url: ReturnLink('/ajax/account_manage_channel.php'),
						data: {uname : uname, new_pass: new_pass ,old_pass : old_pass , new_email: new_email ,old_email : old_email},
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
									msg: t('couldnt save please try again later'),
									type: 'alert',
									btn1: t('ok'),
									btn2: '',
									btn2Callback: null
								});
								return ;
							}
							if(ret.error_no != 0 ){
								$('#CreateChannelForm input[name=createdby]').val(uname);
							}
							if(ret.status == 'ok'){
								$('#BecomeTuberForm input[name=NewEmail]').val('');
								$('#BecomeTuberForm input[name=NewPassword]').val('');
								$('#BecomeTuberForm input[name=ConfirmNewPassword]').val('');
								$('#BecomeTuberForm input[name=YourPassword]').val('');
								if(new_email!=''){
									$('#BecomeTuberForm input[name=YourEmail]').val(new_email);
									$('#EmailForm .emailnotifications').html(new_email);
								}
								curob.find('.regHeader').click();
							}else{
								if(ret.error_no == 0 ){
									$('input[name=fname]').addClass('InputErr');
								}else if(ret.error_no == 1){
									$('input[name=YourPassword]').addClass('InputErr');
								}else if(ret.error_no == 2){
									$('input[name=NewPassword]').addClass('InputErr');
								}else if(ret.error_no == 3){
									$('input[name=YourEmail]').addClass('InputErr');
								}else if(ret.error_no == 4){
									$('input[name=NewEmail]').addClass('InputErr');
								}
								$('#BecomeTuberForm .errorclass').html(ret.error);
							}
						}
					});
				}
				break;
			case "CreateChannelForm":
				var cname = ''+$('#CreateChannelForm input[name=cname]').val();
				var ccategory = ''+$('#CreateChannelForm select[name=ccategory]').val();
				var ccountry = ''+$('#CreateChannelForm select[name=ccountry]').val();
				var ccity = ''+$('#CreateChannelForm input[name=ccity]').val();
				var ccityid = ''+$('#CreateChannelForm input[name=ccity]').attr('data-id');
				var cstreet = ''+$('#CreateChannelForm input[name=cstreet]').val();
				var czip = ''+$('#CreateChannelForm input[name=czip]').val();
				var cphone = ''+$('#CreateChannelForm input[name=cphone]').val();
				var curl = ''+$('#CreateChannelForm input[name=curl]').val();
				var createdon = ''+$('#CreateChannelForm input[name=createdon]').val();

				var hidecreatedon= (curob.find('.uploadinfocheckbox_settings1').hasClass('active')) ? 1 : 0 ;
				var hidecreatedby= (curob.find('.uploadinfocheckbox_settings2').hasClass('active')) ? 1 : 0 ;
				var hidelocation= (curob.find('.uploadinfocheckbox_settings3').hasClass('active')) ? 1 : 0 ;
				if(checkChannelInfo(cname,ccategory,ccountry,czip,ccity,curl)){
					$('#CreateChannelForm .errorclass').html('');
					$.ajax({
						url: ReturnLink('/ajax/info_manage_channel.php'),
						data: {globchannelid:channelGlobalID(),cname : cname, ccategory: ccategory ,ccountry : ccountry, ccityid:ccityid , ccity: ccity ,cstreet : cstreet,czip:czip,cphone:cphone,curl:curl,createdon:createdon,hidecreatedon:hidecreatedon,hidecreatedby:hidecreatedby,hidelocation:hidelocation},
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
									msg: t('couldnt save please try again later'),
									type: 'alert',
									btn1: t('ok'),
									btn2: '',
									btn2Callback: null
								});
								return ;
							}

							if(ret.status == 'ok'){
								curob.find('.regHeader').click();
$('#deleteChannelName').html(sprintf( t('Before you delete your channel %s, it is important to read this:'), ['<span style="color:#eac31a">'+cname+'</span>']  ) );
							}else{
								if(ret.error_no == 0 ){
									$('input[name=cname]').addClass('InputErr');
								}

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
				break;
			case "EmailForm":
				var globlinkArray=new Array();
				var globDataArray=new Array();
				globlinkArray.push(curob.find('#select1').val());
				globDataArray.push(curob.find('#select1').attr('data-value'));
				globlinkArray.push(curob.find('#select2').val());
				globDataArray.push(curob.find('#select2').attr('data-value'));
				curob.find('.uploadinfocheckbox').each(function(){
					var myobjstr=$(this);
					globDataArray.push(myobjstr.attr('data-value'));
					if(myobjstr.hasClass('active')){
						globlinkArray.push(1);
					}else{
						globlinkArray.push(0);
					}
				});
				var globlinkArraystr=globlinkArray.join('/*/');
				var globDataArraystr=globDataArray.join('/*/');

				var emailnotifications1 = ''+$('#EmailForm input[name=emailnotifications1]').val();
				if(validateEmail(emailnotifications1) && emailnotifications1.length!=0){
					updateChannelOtherEmail(emailnotifications1);
					updateemailform(globlinkArraystr,globDataArraystr,false,curob);
				}else{
					updateemailform(globlinkArraystr,globDataArraystr,true,curob);
				}
				break;
			case "privacyForm":
				var connectionsValue=curob.find('#privacy_connections_box .privacy_select').val();
				var connectionsArray=new Array();
				if(connectionsValue==PRIVACY_EXTAND_KIND_CUSTOM){
					curob.find('#privacy_connections_box .peoplecontainer .emailcontainer .peoplesdata').each(function(){
						var obj=$(this);
						if(obj.attr('id')=="sponsorssdata"){
							connectionsArray.push( {sponsors : 1} );
						}else if(obj.attr('id')=="connectionsdata"){
							connectionsArray.push( {connections : 1} );
						}else if( parseInt( obj.attr('data-channel') ) == 1 ){
							connectionsArray.push( {channelid : obj.attr('data-id') } );
						}else if( parseInt( obj.attr('data-id') ) != 0 ){
							connectionsArray.push( {id : obj.attr('data-id') } );
						}
					});
				}
				if( (connectionsValue==PRIVACY_EXTAND_KIND_CUSTOM && connectionsArray.length>0) || connectionsValue!=PRIVACY_EXTAND_KIND_CUSTOM){
					TTCallAPI({
						what: 'channel/privacy_extand/add',
						data: {connectionsValue:connectionsValue, connectionsArray:connectionsArray, connectionsType:PRIVACY_EXTAND_TYPE_CONNECTIONS, channel_id:channelGlobalID()},
						callback: function(ret){

						}
					});
				}

				var sponsorsValue=curob.find('#privacy_sponsors_box .privacy_select').val();
				var sponsorsArray=new Array();
				if(sponsorsValue==PRIVACY_EXTAND_KIND_CUSTOM){
					curob.find('#privacy_sponsors_box .peoplecontainer .emailcontainer .peoplesdata').each(function(){
						var obj=$(this);
						if(obj.attr('id')=="sponsorssdata"){
							sponsorsArray.push( {sponsors : 1} );
						}else if(obj.attr('id')=="connectionsdata"){
							sponsorsArray.push( {connections : 1} );
						}else if( parseInt( obj.attr('data-channel') ) == 1 ){
							sponsorsArray.push( {channelid : obj.attr('data-id') } );
						}else if( parseInt( obj.attr('data-id') ) != 0 ){
							sponsorsArray.push( {id : obj.attr('data-id') } );
						}
					});
				}
				if( (sponsorsValue==PRIVACY_EXTAND_KIND_CUSTOM && sponsorsArray.length>0) || sponsorsValue!=PRIVACY_EXTAND_KIND_CUSTOM){
					TTCallAPI({
						what: 'channel/privacy_extand/add',
						data: {connectionsValue:sponsorsValue, connectionsArray:sponsorsArray, connectionsType:PRIVACY_EXTAND_TYPE_SPONSORS, channel_id:channelGlobalID()},
						callback: function(ret){

						}
					});
				}

				var logValue=curob.find('#privacy_log_box .privacy_select').val();
				var logArray=new Array();
				if(logValue==PRIVACY_EXTAND_KIND_CUSTOM){
					curob.find('#privacy_log_box .peoplecontainer .emailcontainer .peoplesdata').each(function(){
						var obj=$(this);
						if(obj.attr('id')=="sponsorssdata"){
							logArray.push( {sponsors : 1} );
						}else if(obj.attr('id')=="connectionsdata"){
							logArray.push( {connections : 1} );
						}else if( parseInt( obj.attr('data-channel') ) == 1 ){
							logArray.push( {channelid : obj.attr('data-id') } );
						}else if( parseInt( obj.attr('data-id') ) != 0 ){
							logArray.push( {id : obj.attr('data-id') } );
						}
					});
				}
				if( (logValue==PRIVACY_EXTAND_KIND_CUSTOM && logArray.length>0) || logValue!=PRIVACY_EXTAND_KIND_CUSTOM){
					TTCallAPI({
						what: 'channel/privacy_extand/add',
						data: {connectionsValue:logValue, connectionsArray:logArray, connectionsType:PRIVACY_EXTAND_TYPE_LOG, channel_id:channelGlobalID()},
						callback: function(ret){

						}
					});
				}

				var canjoinValue=curob.find('#privacy_canjoin_box .privacy_select').val();
				var canjoinArray=new Array();
				if(canjoinValue==PRIVACY_EXTAND_KIND_CUSTOM){
					curob.find('#privacy_canjoin_box .peoplecontainer .emailcontainer .peoplesdata').each(function(){
						var obj=$(this);
						if(obj.attr('id')=="sponsorssdata"){
							canjoinArray.push( {sponsors : 1} );
						}else if(obj.attr('id')=="connectionsdata"){
							canjoinArray.push( {connections : 1} );
						}else if( parseInt( obj.attr('data-channel') ) == 1 ){
							canjoinArray.push( {channelid : obj.attr('data-id') } );
						}else if( parseInt( obj.attr('data-id') ) != 0 ){
							canjoinArray.push( {id : obj.attr('data-id') } );
						}
					});
				}
				if( (canjoinValue==PRIVACY_EXTAND_KIND_CUSTOM && canjoinArray.length>0) || canjoinValue!=PRIVACY_EXTAND_KIND_CUSTOM){
					TTCallAPI({
						what: 'channel/privacy_extand/add',
						data: {connectionsValue:canjoinValue, connectionsArray:canjoinArray, connectionsType:PRIVACY_EXTAND_TYPE_EVENTJOIN, channel_id:channelGlobalID()},
						callback: function(ret){

						}
					});
				}

				var globlinkArray=new Array();
				var globDataArray=new Array();
				curob.find('.uploadinfocheckbox').each(function(){
					var myobjstr=$(this);
					if(myobjstr.attr('data-value')!="" && myobjstr.attr('data-value')!="trans"){
						globDataArray.push(myobjstr.attr('data-value'));
						if(myobjstr.hasClass('active')){
							globlinkArray.push(1);
						}else{
							globlinkArray.push(0);
						}
					}
				});
				var globlinkArraystr=globlinkArray.join('/*/');
				var globDataArraystr=globDataArray.join('/*/');
				updateemailform(globlinkArraystr,globDataArraystr,true,curob);
				break;
			case "DeleteChannelForm":
				var globlinkArray=new Array();
				if(curob.find('.uploadinfocheckbox').hasClass('active')){
					$('#confirmaccount').click();
				}else{
                                    TTAlert({
                                        msg: t('You need to confirm in order to delete your channel page'),
                                        type: 'alert',
                                        btn1: t('ok'),
                                        btn2: '',
                                        btn2Callback: null
                                    });
                                    curob.find('.uploadinfocheckbox_delete').addClass('confirm');
                                }
				break;
			case "uploadContainerevent":
				curob=$(this).parent().parent();
				var nameevents=curob.find('#nameevents').val();
				var descriptionevent=curob.find('#descriptionevent').val();
				var locationevent=getObjectData(curob.find('#locationevent'));
				var fromdate=getObjectData(curob.find('#fromdate'));
				var defaultEntry=curob.find('#defaultEntry').val();
				var todate=getObjectData(curob.find('#todate'));
				var defaultEntryTo=curob.find('#defaultEntryTo').val();
				var themephotoStanInside=curob.find('.themephotoStanInside').attr('data-value');
				var guestevent=curob.find('#guestevent').val();
				var caninvite= (curob.find('.uploadinfocheckbox1').hasClass('active')) ? 1 : 0 ;
				var hideguests= (curob.find('.uploadinfocheckbox2').hasClass('active'))? 1 : 0 ;

				//return;
				if(checkEventInfo(nameevents,descriptionevent,locationevent,fromdate,todate,curob)){
					$.ajax({
						url: ReturnLink('/ajax/info_event_manage_channel.php'),
						data: {globchannelid:channelGlobalID(),nameevents : nameevents, descriptionevent: descriptionevent ,locationevent : locationevent , fromdate: fromdate ,fromdatetime : defaultEntry, todate: todate ,todatetime : defaultEntryTo,whojoin:curob.find('input[name=radio1]:checked').val(),themephotoStanInside:themephotoStanInside,guestevent:guestevent,caninvite:caninvite,hideguests:hideguests},
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

								var mynewstr='<div class="formContainersub margintop1" data-value="12" style="height:auto"><div class="removeNewButEdit"><div class="addNewBut_over"><div class="addNewBut_overtxt">'+t('remove event')+'</div></div></div><div class="formttl margintop5 eventsub" data-id="'+ret.value+'">'+nameevents+'</div><div class="formttl margintop5 eventsubedit"></div></div>';
								var newaddobj=curob.parent();
								curob.remove();
								newaddobj.append(mynewstr);

								var height = newaddobj.parent().find('.formContainer').height()+74;
								newaddobj.parent().css('height',height+'px');
								var currparent=newaddobj.parent().parent().parent();
								currparent.attr('data-height',currparent.find('.formContainerglob').height()+91);
								currparent.css('height',parseInt(currparent.attr('data-height')));

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
	$('.resetemailclass').fancybox();
	$('#confirmaccount').fancybox({
						padding	:0,
						margin	:0,
						beforeLoad:function(){
			clearForm();
	}});
	initCreatedOn();

    $(document).on('click', "#signInbtn_settings", function() {
        var EmailField = getObjectData($("#SEmailField"));
        var PasswordField = getObjectData($("#SPasswordField"));

        $('#SPasswordField, #SEmailField').removeClass('InputErr');

        if (EmailField.length == 0 || !(EmailField.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $('#SEmailField').focus().addClass('InputErr');
            return false;
        } else if (PasswordField.length == 0 || !(PasswordField.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $('#SPasswordField').focus().addClass('InputErr');
            return false;
        } else {
            $('.upload-overlay-loading-fix').show();
            var dataString = 'EmailField=' + EmailField + '&PasswordField=' + PasswordField + '&globchannelid=' + globchannelid;
            $.ajax({
                type: "POST",
                url: ReturnLink("/ajax/channel_check_user.php"),
                data: dataString,
                success: function(html) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(html);
                    } catch (Ex) {
                        $('.upload-overlay-loading-fix').hide();
                        return;
                    }
                    $.fancybox.close();
                    if (!ret) {
                        $('.upload-overlay-loading-fix').hide();
                        TTAlert({
                            msg: t('invalid username or password, please try again'),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }

                    if (ret.status == 'ok') {
                        $('.upload-overlay-loading-fix').hide();
                        clearForm();
                        TTAlert({
                            msg: t("your channel has been deactivated for 15 days and will be permanently deleted after that date.<br/>You can reactivate it within this timeframe by simply clicking on the link sent to you by mail in this regard."),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: function(data) {
                                TTAlert({
                                    msg: sprintf(t("your channel will be deleted on %s <br/> Are you sure you want to continue deletion?" ) , [ret.date_ts] ) ,
                                    type: 'action',
                                    btn1: t('cancel'),
                                    btn2: t('confirm'),
                                    btn2Callback: function(data) {
                                        if (data) {
                                            TTAlert({
                                                msg: t("by deleting your channel, you will permanently delete your entire channel profile, content and uploads and you will not be able to retrieve them again.<br/>In case you changed your mind, you can access the channel page before ") + ret.date_ts + ".",
                                                type: 'alert',
                                                btn1: t('ok'),
                                                btn2: '',
                                                btn2Callback: function(data) {
                                                    document.location.href = "" + ReturnLink('/channels');
                                                }
                                            });
                                        } else {
                                            reactivateAccount(true);
                                        }
                                    }
                                });
                            }
                        });
                    } else {
                        $('.upload-overlay-loading-fix').hide();
                        TTAlert({
                            msg: t('invalid username or password, please try again'),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                    }
                }
            });
        }
    });
	$('#resetbtn').live("click", function(e){

		var EmailForgotForm = $("#SEmailForgotForm").val();
		$('#SEmailForgotForm').removeClass('InputErr');
		if(EmailForgotForm == '' || EmailForgotForm == 'yourname@email.com' || !(EmailForgotForm.match(/([\<])([^\>]{1,})*([\>])/i) == null)){
			$('#SEmailForgotForm').focus().addClass('InputErr');
		}else{

			var dataString2 = 'EmailForgotForm='+ EmailForgotForm;

			$.ajax({
				type: "POST",
				url: ReturnLink('/ajax/passprocess.php'),
				data: dataString2,
				success: function(html2) {
					clearForm();
					var jresp;
					try {
						jresp = $.parseJSON(html2);
					}catch(exception ){
						return ;
					}

					if( !jresp ) return ;

					if( jresp.status == 'ok' ){
						$.fancybox.close();
						ShowClaimdiv("an email is sent to you to reset your password");
					}else{
						TTAlert({
							msg: t('Invalid email, please try again'),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
					}

				}
			});
		}
	});
	reactivateAccount(false);

	if(parseInt(channel_notifications)==1){
            if(!$('#EmailForm').hasClass('selected')){
                $('#EmailForm .regHeader').click();
            }
            $("html, body").animate({ scrollTop: $('#EmailForm').offset().top}, 1);
	}
	if(parseInt(go_subchannel)==1){
            if(!$('#ChannelPage').hasClass('selected')){
                $('#ChannelPage .regHeader').click();
            }
            if(!$('#subChannel').hasClass('selected')){
                $('#subChannel .subRegHeader').click();
            }
            if(!$('#parentChannel').hasClass('selected')){
                $('#parentChannel .subRegHeader').click();
            }
            $("html, body").animate({ scrollTop: $('#subChannel').offset().top}, 1);
	}


	$("#czip").blur(function(){
		var zip_code = $("#czip").val();
		var country_code = $("#ccountry").val();

		$.ajax({
			url: ReturnLink('/ajax/ajax_check_zip.php'),
			data: {zip_code: zip_code, country_code:country_code},
			type: 'post',
			success: function(data){

				var jres = null;
				try{
					jres = $.parseJSON( data );
				}catch(Ex){

				}

				if(jres.error){
					throwError = 1;
					$("#FormResult").html( jres.error );
					$("#"+ThisID).addClass('Error');
					//setTimeout( function(){ $("#"+ThisID).focus();  }, 100 );
				}else{
					var city = jres.city;

					// Zip valid.
					if(city != ''){
						$("#ccity").val(city);
						$("#CFormResult").html('');
					// Zip invalid.
					} else{
						//$("#ccity").val('');
						$("#CFormResult").html( $.i18n._("Zip code is incorrect") );
					}
				}

			}
		});
	});

	$("#ccountry").change(function(){
		var country_code = $("#ccountry").val();

		// Check if the country exists in our zip-codes table.
		$.ajax({
			url: ReturnLink('/ajax/ajax_check_country.php'),
			data: {country_code:country_code},
			type: 'post',
			success: function(data){

				var jres = null;
				try{
					jres = $.parseJSON( data );
				}catch(Ex){

				}

				if(jres.error){
					throwChaError = 1;
				}else{
					throwChaError = 0;

					var country = jres.country;

					// If the country doesn't exist.
					if(country == ''){
                                            $("#czip_ttlogo").hide();
						//$("#ccity").removeAttr("readonly");
					} else {
                                            $("#czip_ttlogo").show();
						//$("#ccity").attr("readonly", "readonly");
					}
				}

			}
		});
	});

});

function textWidth(font) {
  var f = font || '11px',
      o = $('<div>' + this + '</div>')
            .css({'position': 'absolute', 'float': 'left', 'white-space': 'nowrap', 'visibility': 'hidden','font-family': 'Arial, Helvetica, sans-serif', 'font-size': f, 'font-weight': 'bold'})
            .appendTo($('body')),
      w = o.width();

  o.remove();

  return w;
}

function doChannelNotificationsCombo(obj){
	var myval=""+obj.value;
	if(myval=="1"){
		$('#EmailForm .channelcheckbox').addClass('active');
	}else if(myval=="2"){
		$('#EmailForm .channelcheckbox').removeClass('active');
	}
}
function reactivateAccount(bool){
	$.ajax({
		url: ReturnLink('/ajax/channel_activate_account.php'+Math.random()),
		data: {globchannelid:channelGlobalID()},
		type: 'post',
		success: function(data){
			var ret = null;
			try{
				ret = $.parseJSON(data);
			}catch(Ex){
				return ;
			}

			if(ret.status == 'ok'){
				if(bool){
					TTAlert({
						msg: t('your channel is now reactivated!'),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
			}
		}
	});
}
function clearForm(){
	$("#SEmailField").val('');
	$("#SPasswordField").val('');
	$("#SEmailField").blur();
	$("#SPasswordField").blur();
}
function checkEventInfo(nameevents,descriptionevent,locationevent,fromdate,todate,obj){
	obj.find('input').removeClass('InputErr');
	obj.find('textarea').removeClass('InputErr');
	if(nameevents.length == 0){
		TTAlert({
			msg: t('Invalid event name'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});

		obj.find('#nameevents').addClass('InputErr');
		return false;
	}else if(descriptionevent.length == 0){
		TTAlert({
			msg: t('Invalid event description'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});

		obj.find('#descriptionevent').addClass('InputErr');
		return false;
	}else if(locationevent.length == 0){
		TTAlert({
			msg: t('Invalid event location'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});

		obj.find('#locationevent').addClass('InputErr');
		return false;
	}else if(fromdate.length == 0){
		TTAlert({
			msg: t('Invalid event data from'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});

		obj.find('#fromdate').addClass('InputErr');
		return false;
	}else if(todate.length == 0){
		TTAlert({
			msg: t('Invalid event date to'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});

		obj.find('#todate').addClass('InputErr');
		return false;
	}
	return true;
}
function checkAccountInfo(uname,new_pass,new_pass2,old_pass,new_email,old_email){
	$('#BecomeTuberForm input').removeClass('InputErr');
	var min_pswd_length = 6;
	if(uname.length == 0){
		$('#BecomeTuberForm .errorclass').html(t('Invalid owner name'));
		$('#BecomeTuberForm input[name=fname]').addClass('InputErr');
		return false;
	}else if(dirty && old_pass.length == 0){
		$('#BecomeTuberForm .errorclass').html(t('Please specify old password'));
		$('#BecomeTuberForm input[name=YourPassword]').addClass('InputErr');
		return false;
	}else if(new_pass.length!=0 && (new_pass.length < min_pswd_length) ){
		$('#BecomeTuberForm .errorclass').html( sprintf( t('Password must be minimum %s characters long') ,[min_pswd_length])  );
		$('#BecomeTuberForm input[name=NewPassword]').addClass('InputErr');
		return false;
	}else if( dirty && (new_pass != new_pass2) ){
		$('#BecomeTuberForm .errorclass').html(t('Passwords mismatch'));
		$('#BecomeTuberForm input[name=NewPassword]').addClass('InputErr');
		$('#BecomeTuberForm input[name=ConfirmNewPassword]').addClass('InputErr');
		return false;
	}else if( !validateEmail(old_email) && old_email.length!=0){
		$('#BecomeTuberForm .errorclass').html(t('Please enter a valid email'));
		$('#BecomeTuberForm input[name=YourEmail]').addClass('InputErr');
		return false;
	}else if( (!validateEmail(new_email)) && old_email.length!=0 && new_email.length!=0){
		$('#BecomeTuberForm .errorclass').html(t('Invalid emails'));
		$('#BecomeTuberForm input[name=NewEmail]').addClass('InputErr');
		return false;
	}
	return true;
}
function checkChannelInfo(cname,ccategory,ccountry,czip,ccity,curl){
	$('#CreateChannelForm input').removeClass('InputErr');
	$('#CreateChannelForm select').removeClass('InputErr');

	// Variable to tell if the zip code and the city are required.
	var zip_required = 0;
	if($("#czip_ttlogo").css('display') !="none")
		zip_required = 1;

	if(cname.length == 0){//rudy channel
		$('#CreateChannelForm .errorclass').html(t('Invalid channel name'));
		$('#CreateChannelForm input[name=cname]').addClass('InputErr');
		return false;
	}else if(ccategory == 0){
		$('#CreateChannelForm .errorclass').html(t('Please select category'));
		$('#CreateChannelForm select[name=ccategory]').addClass('InputErr');
		return false;
	}else if(ccountry==0){
		$('#CreateChannelForm .errorclass').html(t('Please select country'));
		$('#CreateChannelForm select[name=ccountry]').addClass('InputErr');
		return false;
	}/*else if(czip.length == 0 && zip_required == 1){
		$('#CreateChannelForm .errorclass').html(t('Please enter a zip code'));
		$('#CreateChannelForm input[name=czip]').addClass('InputErr');
		return false;
	}*/else if(ccity.length == 0 && zip_required == 1){
		$('#CreateChannelForm .errorclass').html(t('Please enter a valid zip code'));
		$('#CreateChannelForm input[name=ccity]').addClass('InputErr');
		return false;
	}else if (!ValidURL(curl) && curl!='') {
            $('#CreateChannelForm .errorclass').html(t('Please enter a valid url'));
            $('#CreateChannelForm input[name=curl]').addClass('InputErr');
            return false;
        }
	return true;
}

function updatechannelpagedata(param1,param2,curob){
	$.ajax({
		url: ReturnLink('/ajax/info_page_manage_channel.php'),
		data: {globchannelid:channelGlobalID(),data:param2,str:param1},
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
				if(curob!=""){
					curob.find('.subRegHeader').click();
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
		data: {globchannelid:channelGlobalID(),data:param2,str:param1},
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
					msg: t('couldnt save please try again later'),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}

			if(ret.status == 'ok'){
				curob.find('.subRegHeader').click();
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
		data: {globchannelid:channelGlobalID(),data:param1},
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
					msg: t('couldnt save please try again later'),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}

			if(ret.status == 'ok'){
				curob.find('.subRegHeader').click();
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
function updateemailform(param1,param2,bool,curob){
	$.ajax({
		url: ReturnLink('/ajax/info_emailnotification_manage_channel.php'),
		data: {globchannelid:channelGlobalID(),value:param1,data:param2},
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
					msg: t('couldnt save please try again later'),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}
			if(bool){
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
			}else{
				if(ret.status == 'ok'){
					curob.find('.regHeader').click();
				}
			}
		}
	});
}
function updateChannelOtherEmail(param1){
	$.ajax({
		url: ReturnLink('/ajax/info_otheremail_manage_channel.php'),
		data: {globchannelid:channelGlobalID(),data:param1},
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
					msg: t('couldnt save please try again later'),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}

			if(ret.status == 'ok'){
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
	if(parseInt(id)==0){
		curob.remove();
		var newheight = thisparent.find('.formContainer').height()+74;
		thisparent.css('height',newheight);
		return;
	}

	$.ajax({
		url: ReturnLink('/ajax/info_page_links_delete_manage_channel.php'),
		data: {globchannelid:channelGlobalID(),id:id},
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
					msg: t('couldnt save please try again later'),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}

			if(ret.status == 'ok'){
				curob.remove();
				var newheight = thisparent.find('.formContainer').height()+74;
				thisparent.css('height',newheight);
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
function addAutoCompleteList(which){
	var $ccity = $("input[name=ccity]", $('#'+ which));
	$ccity.autocomplete({
		appendTo: "#CreateChannelForm",
		search: function(event, ui) {
			var $country = $('#ccountry');
			//console.log($country);
			var cc = $country.val();
			if(cc == 'ZZ'){
				$country.addClass('err');
				event.preventDefault();
			}else{
				$ccity.autocomplete( "option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc) );
			}
		},
		select: function(event, ui) {
			$ccity.val(ui.item.value);
                        $ccity.attr('data-id',ui.item.id);
			//$('input[name=cityname]',$ccity.parent()).val(ui.item.value);
			event.preventDefault();
		}
	});
}
function goManageEmail(){
	if(!$('#EmailForm').hasClass('selected')){
		$('#EmailForm .regHeader').click();
	}

	$("html, body").animate({ scrollTop: $('#EmailForm').offset().top}, 1);
	$('.fancybox-close').click();
}
function goAccountInfo(){
	if(!$('#BecomeTuberForm').hasClass('selected')){
		$('#BecomeTuberForm .regHeader').click();
	}

	$("html, body").animate({ scrollTop: $('#BecomeTuberForm').offset().top}, 1);
	$('.fancybox-close').click();
}
function ShowClaimdiv(data){
	/*$("#FormResult").html('');
	if(data == 1){
		show = 'please <a href="#signIn" class="signin">sign in</a> using this email or <a href="#resetIn" class="signin">reset your password</a>';
	}else{
		show = data;
	}*/

	//$("#ClaimEmail").css('visibility','visible');
	//$("#ClaimEmail").html(show);
}

function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;

	return true;
}
function resetInitDataChannelPage(curob){
	var myid=parseInt(curob.find('.formContainersub').attr('data-value'));
	var newstrimage='';
	$.ajax({
		url: ReturnLink('/ajax/info_page_data_manage_channel.php'),
		data: {globchannelid:channelGlobalID(),data:myid},
		type: 'post',
		success: function(e){
			var mydata=""+e;
			if(myid==1){
				if(mydata==''){
					var mydataurl=ReturnLink('/media/tubers/tuber.jpg');
				}else{
					mydataurl=ReturnLink('/media/channel/'+globchannelid+'/thumb/'+mydata);
				}
				curob.find('.ImageStanInside').attr('data-value',mydata);
				newstrimage='<img src="'+mydataurl+ '?x=' + Math.random() + '"/>';
				curob.find('.ImageStanInside').html(newstrimage);
			}else if(myid==2){
				if(mydata==''){
					var mydataurl=ReturnLink('/images/channel/coverphoto.jpg');
				}else{
					mydataurl=ReturnLink('/media/channel/'+globchannelid+'/thumb/'+mydata);
				}

				newstrimage='<img src="'+mydataurl+ '?x=' + Math.random() + '"/>';
				curob.find('.ImageStanInside').attr('data-value',mydata);

				curob.find('.ImageStanInside').html(newstrimage);
			}else if(myid==3){
				curob.find('.backgroundImageStan').html(mydata);
			}else if(myid==4){
				curob.find('#aboutchannel').val(mydata);
			}else if(myid==5){
				curob.find('#changeslogan').val(mydata);
			}else if(myid==6){
				curob.find('#changekeywords').val(mydata);
			}else if(myid==7){
				curob.find('.formContainersub').each(function() {
					$(this).remove();
				});
				curob.append(mydata);
			}else if(myid==8){
				curob.find('.formContainersub').each(function() {
					$(this).remove();
				});
				curob.append(mydata);
			}
		}
	});
}
function resetInitDataAccount(curob){
	$.ajax({
		url: ReturnLink('/ajax/info_account_data_channel.php'),
		type: 'post',
		success: function(e){
			if(e){
				$('#BecomeTuberForm input').removeClass('InputErr');
				$('#BecomeTuberForm input[name=NewEmail]').val('');
				$('#BecomeTuberForm input[name=NewPassword]').val('');
				$('#BecomeTuberForm input[name=ConfirmNewPassword]').val('');
				$('#BecomeTuberForm input[name=YourPassword]').val('');
				$('#BecomeTuberForm .errorclass').html('');
				$('#BecomeTuberForm input[name=fname]').val(e);
			}
		}
	});
}
function resetInitDataChannelInfo(curob){
	$.ajax({
		url: ReturnLink('/ajax/info_data_channel.php'),
		data: {globchannelid:channelGlobalID()},
		type: 'post',
		success: function(e){
			if(e){
				$('#CreateChannelForm .formContainerglob').html(e);
				initCreatedOn();
				addAutoCompleteList("CreateChannelForm");
			}
		}
	});
}
function resetInitDataPrivacyForm(curob){
	$.ajax({
		url: ReturnLink('/ajax/info_privacyForm_data_channel.php'),
		data: {globchannelid:channelGlobalID()},
		type: 'post',
		success: function(e){
			if(e){
				$('#privacyForm .formContainerglob').html(e);
				initPrivacyAuto();
			}
		}
	});
}
function resetInitDataEmailForm(curob){
	$.ajax({
		url: ReturnLink('/ajax/info_emailForm_data_channel.php'),
		data: {globchannelid:channelGlobalID()},
		type: 'post',
		success: function(e){
			if(e){
				$('#EmailForm .formContainerglob').html(e);
			}
		}
	});
}
function getEventData(curob){
	var thisparent=curob.parent();
	var currparent=thisparent.parent().parent();

	$.ajax({
		url: ReturnLink('/ajax/info_page_event_data_manage_channel.php'),
		data: {globchannelid:channelGlobalID(),id:curob.attr('data-id')},
		type: 'post',
		success: function(data){
			if(data){
				thisparent.find('.eventsubedit').html(data);
				thisparent.find('.eventsubedit').show();
				var newheight = currparent.find('.formContainer').height()+74;
				currparent.css('height',newheight+'px');

				currparent.parent().parent().attr('data-height',currparent.parent().parent().find('.formContainerglob').height()+91);
				currparent.parent().parent().css('height',parseInt(currparent.parent().parent().attr('data-height')));
			}
		}
	});
}
function initCreatedOn(){
	Calendar.setup({
		inputField : "createdon",
                noScroll  	 : true,
		trigger    : "createdon",
		align:"B",
		onSelect   : function(calss) {
			var datessl = calss.selection.get();
			if (datessl) {
				datessl = Calendar.intToDate(datessl);
				$('#createdon').attr('data-cal',Calendar.printDate(datessl, "%Y-%m-%d"));
			}
			this.hide();
		},
		disabled      : function(date) {
				var d = new Date();
				d.setHours(12,30,0,0);
				return (date > d);
		},
		dateFormat : "%d / %m / %Y"
	});
}
function checkSubmitsignInbtn(e){
   if(e && e.keyCode == 13){
      $('#signInbtn_settings').click();
   }
}
function initResetSelectedUsers(obj){
	resetSelectedUsers(obj);
	resetSelectedChannels(obj);
}
function initPrivacyAuto(){
	initResetSelectedUsers($('#addmoretext_connections'));
	initResetSelectedUsers($('#addmoretext_sponsors'));
	initResetSelectedUsers($('#addmoretext_log'));
	initResetSelectedUsers($('#addmoretext_canjoin'));

	addmoreusersautocomplete_privacy( $('#addmoretext_connections') , channelGlobalID() );
	addmoreusersautocomplete_privacy( $('#addmoretext_sponsors') , channelGlobalID() );
	addmoreusersautocomplete_privacy( $('#addmoretext_log') , channelGlobalID() );
	addmoreusersautocomplete_privacy_join( $('#addmoretext_canjoin') , channelGlobalID() );
	$(".privacy_select").change(function(){
		var selectval=parseInt($(this).val());
		var selectval_str="public";
		if(selectval==5){
			$(this).parent().find('.privacy_picker').show();
		}else{
			initResetSelectedUsers($(this).parent().find('.addmore input'));
			$(this).parent().find('.uploadinfocheckbox').removeClass('active');
			$(this).parent().find('.addmore input').val('');
			$(this).parent().find('.addmore input').blur();
			$(this).parent().find('.peoplesdata').each(function() {
				var parents=$(this);
				parents.remove();
            });
			$(this).parent().find('.privacy_picker').hide();
		}

		if(selectval==2){
			selectval_str="connections";
		}else if(selectval==3){
			selectval_str="sponsors";
		}else if(selectval==4){
			selectval_str="private";
		}else if(selectval==5){
			selectval_str="custom";
		}
		var mypiclink=ReturnLink("/images/channel/privacy_" + selectval_str + ".png");
		$(this).parent().find('.privacy_icon').css('background', 'url('+mypiclink+')');
	});
}
function updateNotificationData(obj, action){
    var user_id = obj.attr('data-id');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
            url: ReturnLink('/ajax/info_not_update.php'),
            data: {action:action,current_channel_id:channelGlobalID(),user_id:user_id},
            type: 'post',
            success: function(data){
                obj.closest('.list_notifications_item').remove();
                $('.upload-overlay-loading-fix').hide();
            }
    });	
}
function updateNotificationDataCH(obj, action){
    var chid=obj.attr('data-id');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/info_not_relation_update.php'),
        data: {action:action,channel_id:chid,parent_id:channelGlobalID()},
        type: 'post',
        success: function(data){
            if(obj.hasClass('list_item_undo_ch')){
                obj.closest('.list_notifications_item').remove();
            }
            $('.upload-overlay-loading-fix').hide();
        }
    });	
}