var currentpage=0;
var old_currentpage=0;
var fr_txt="";
var to_txt="";
var one_object=0;
var globtxtsrch="";
var globcaractersearch="";
var hide_notification_toggle = 0;
var remove_notification_toggle = 0;

function InitDocument(){
    $('#privacy_button').mouseover(function() {
        var diffx = $('#MainChannelContainer').offset().left+255;
        var diffy = $('#MainChannelContainer').offset().top+22;
        var posxx = $(this).offset().left-diffx;
        var posyy = $(this).offset().top-diffy;

        $('.privacybuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.privacybuttonsOver').css('left', posxx + 'px');
        $('.privacybuttonsOver').css('top', posyy + 'px');
        $('.privacybuttonsOver').stop().show();
    });
    $('#privacy_button').mouseout(function() {
        $('.privacybuttonsOver').hide();
    });
	$(".channel-connection-pic").each(function(){
		var $this = $(this);
		var popUp = $this.parent().parent().find(".popUp");
		
		$this.click(function(){
			if($this.parent().parent().hasClass('noaction')){
				return;
			}
			var thisIndex = $(this).parent().parent().index();
			$('#MediaImage ul li').css('z-index',1);
			$this.parent().parent().css('z-index',2);
			var popclass = 'popUpLeft';
			if( ( (thisIndex+1) % 3 ) == 0){
				popclass = 'popUpRight';
			}
			
			popUp.removeClass('popUpLeft');
			popUp.removeClass('popUpRight');
			popUp.addClass(popclass);
			
			$(".popUp").hide();
			$(".plusSign").show();
			$('.channel-connection-pic').removeClass('active');
			$(this).addClass('active');
			popUp.show();
			$(this).find(".plusSign").hide();
		});
	});
	
	$(".minus").each(function(){
		var $this = $(this);
		var popUp = $this.parent();
		var plusSign = $this.parent().parent().find(".plusSign");
		$this.click(function(){
			popUp.parent().find('.channel-connection-pic').removeClass('active');
			popUp.hide();
			plusSign.show();
		});
	});
	$('.swapimagenew').each(function(){
		$(this).mouseover(function(){
			var posxx=$(this).offset().left-$('#MainChannelContainer').offset().left-254;
			var posyy=$(this).offset().top-$('#MainChannelContainer').offset().top-23;
			$('.objectContainerOver .objectContainerOverin').html($(this).attr('data-title'));
			$('.objectContainerOver').css('left',posxx+'px');
			$('.objectContainerOver').css('top',posyy+'px');
			$('.objectContainerOver').show();
		});
		$(this).mouseout(function(){
			$('.objectContainerOver').hide();
		});
	});
	$(".image").each(function(){
		var $this = $(this);
		var hide = $this.find(".hide");
		$this
			.mouseenter(function(){
				if($this.parent().hasClass('noaction')){
					return;
				}
				hide.removeClass("hide");
				hide.addClass("show");
			})
			.mouseleave(function(){
				hide.removeClass("show");
				hide.addClass("hide");
			})
	});
        
        
        $(".reportSponsorsOwner_reason").each(function(){
            var $this = $(this);
            var $parent = $this.closest('li');
            $this.removeClass('reportSponsorsOwner_reason');
            initReportSponsorsFunctions($this,SOCIAL_ENTITY_CHANNEL,$parent.attr('data-userid'),$parent.attr('data-sponsorid'));
        });
}


$(document).ready(function(){
	
	InitDocument();
	if(parseInt(is_owner)==1 && $('#addmoretext_privacy').length>0){
		addmoreusersautocomplete_privacy( $('#addmoretext_privacy') , channelGlobalID() );
	}
	$('.sponsorsmenu').addClass('active');	
	
	$(document).on('click',".closeSign0" ,function(){
		var $this = $(this);
		var thisLi = $this.closest("li");
		thisLi.remove();
		one_object=1;
		getSponsorsDataRelated();
	});
	$(document).on('click',".closeSign1" ,function(){
		var $this = $(this).parent().parent().parent();
		$this.find('.hide_img').removeClass('displaynone');
		$(this).removeClass("show");
		$(this).addClass("hide");
		$this.addClass('noaction');
		$this.find('.minus').click();
		$this.find('.hide_connection_content').removeClass('displaynone');
		showHideSponsorsDisplay($this.attr('data-sponsorid'),0);
	});
	$(document).on('click',".unhide_connection_content" ,function(){
		var $this = $(this).parent();
		var $parent = $this.parent().parent().parent();
		$this.addClass('displaynone');
		$parent.removeClass('noaction');
		$parent.find('.hide_img').addClass('displaynone');
		showHideSponsorsDisplay($parent.attr('data-sponsorid'),1);
	});
	$("#searchbut").click(function(){
		var txtvalsrch=""+$("#srchtxt").val();
		if(txtvalsrch==$("#srchtxt").attr('data-val')){
			txtvalsrch=null;
		}
		$('.upload-overlay-loading-fix').show();
		
		globtxtsrch=txtvalsrch;
		globcaractersearch="";
		currentpage=0;
		old_currentpage=0;
		$('.MediaList ul').html('');
		getSponsorsDataRelated();
	});
	$(document).on('mouseover', '#searchbutalphabet, #alphabetcnt', function(){
		$("#searchbutalphabetcontainer").stop();
		$("#searchbutalphabetcontainer").animate({'width':275+"px",'height':106+"px"},500);			
	});
	$(document).on('mouseout', "#searchbutalphabet, #alphabetcnt" ,function(){
		$("#searchbutalphabetcontainer").stop();
		$("#searchbutalphabetcontainer").animate({'width':51+"px",'height':20+"px"},500);
	});
	$(document).on('click', "#alphabetcnt ul li",function(){
		if(!$(this).hasClass('inactive')){
						
			$("#alphabetcnt ul li").removeClass('active');
			$(this).addClass('active');
			currentpage=0;
			old_currentpage=0;
			$("#srchtxt").val($("#srchtxt").attr('data-val'));
			globtxtsrch="";
			globcaractersearch=$(this).attr('data-value');
			if(String(""+globcaractersearch)=="all"){
				globcaractersearch=null;	
			}
			$('.MediaList ul').html('');
			getSponsorsDataRelated();
		}			
	});	
	
	var menushown = false;
	var moreshown = false;
	
	$(".loadmoremedia").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		currentpage++;
		getSponsorsDataRelated();
	});
	
	$(document).on('click','#removeSponsor',function(){
		var $this=$(this);
		var $parent=$this.parent().parent().parent().parent();
		removeSponsorChannels($parent);
	});

	$(document).on('click',".okButton" ,function(){
		$('.sharepopup_butBRCancel').click();
	});
        $(document).on('click',".sendButton_reason_sponsors" ,function(){
            $("#sharepopup_error").html('');                
            if($('#optionBox_sponsors_hide').hasClass('active')){
                $('.sharepopup_butBRCancel').click();
               showHideSponsorsDisplay_Report($('#sharepopup .sharepopup_container').attr('data-sponsorid'),0);
            }else if($('#optionBox_sponsors_remove').hasClass('active')){
               $('.sharepopup_butBRCancel').click();
               removeSponsorChannels_Report($('#sharepopup .sharepopup_container').attr('data-sponsorid'));
            }else if( $('.uploadinfocheckbox_reason').hasClass('active') ){
		$('.upload-overlay-loading-fix').show();
                var $parent = $(this).closest('.sharepopup_container');
                var entity_id = $parent.attr('data-id');
                var entity_type = $parent.attr('data-type');
                var channel_id = $parent.attr('data-channel');
                var msg = '';
                var reason_array = new Array();
                $parent.find('.uploadinfocheckbox_reason.active').each(function(){
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
                                $parent.find("#sharepopup_error").html(ret.msg);
				return;
                            }
                            closeFancyBox();
                            TTAlert({
                                    msg: ret.msg,
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                            });
			}
		});
            }else{
                $("#sharepopup_error").html(t('Please choose one of the above proposed options to Report'));
            }
        });
        $(document).on('click',"#optionBox_sponsors_hide" ,function(){
            if( hide_notification_toggle == 0 ){
                $('.sendButton_reason_sponsors').html(t('send'));
                hide_notification_toggle = 1;
            } else {
                $('.sendButton_reason_sponsors').html(t('report'));
                hide_notification_toggle = 0;
            }
        });
        $(document).on('click',"#optionBox_sponsors_remove" ,function(){
            if( remove_notification_toggle == 0 ){
                $('.sendButton_reason_sponsors').html(t('send'));
                remove_notification_toggle = 1;
            } else {
                $('.sendButton_reason_sponsors').html(t('report'));
                remove_notification_toggle = 0;
            }
        });

	$(document).on('click',".private_save_buts" ,function(){
		var curob=$(this).parent();
		var connectionsValue=curob.find('.privacy_select').val();
		var connectionsArray=new Array();
		if(connectionsValue==PRIVACY_EXTAND_KIND_CUSTOM){
			curob.find('.peoplecontainer .emailcontainer .peoplesdata').each(function(){
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
		if(connectionsValue==PRIVACY_EXTAND_KIND_CUSTOM && connectionsArray.length==0){
			TTAlert({
				msg: t('Invalid data, please try again'),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});
			return;
		}
		$('.upload-overlay-loading-fix').show();
		TTCallAPI({
			what: 'channel/privacy_extand/add',
			data: {connectionsValue:connectionsValue, connectionsArray:connectionsArray, connectionsType:PRIVACY_EXTAND_TYPE_SPONSORS, channel_id:channelGlobalID()},
			callback: function(ret){
				initResetIcon(curob.parent().find('#privacy_button'));
				curob.parent().find('#privacy_button').addClass('privacy_icon'+connectionsValue);
                                curob.parent().find('#privacy_button').attr('data-title',curob.find('.privacy_select option:selected').text());
				$('.upload-overlay-loading-fix').hide();				
			}
		});
	});

	/**
	* The privacy-box part.
	**/
	
	// Change the privacy icon.
	initResetSelectedUsers($('#addmoretext_privacy'));
	$("#privacy_select").change(function(){
		var selectval=parseInt($(this).val());
		
		if(selectval==5){
			$(this).parent().find('.privacy_picker').removeClass('displaynone');
		}else{
			initResetSelectedUsers($(this).parent().find('.addmore input'));
			$(this).parent().find('.uploadinfocheckbox').removeClass('active');
			$(this).parent().find('.addmore input').val('');
			$(this).parent().find('.addmore input').blur();
			$(this).parent().find('.peoplesdata').each(function() {
				var parents=$(this);
				parents.remove();				
            });
			$(this).parent().find('.privacy_picker').addClass('displaynone');
		}
		initResetIcon($(this).parent().find('.privacy_icon'));
		$(this).parent().find('.privacy_icon').addClass('privacy_icon'+selectval);
	});
	$("#privacy_select").change();
	$("#privacy_close_button").click(function(){
		$("#privacy_box").hide();
	});
	
	$("#privacy_open").click(function(){
		$("#privacy_box").show();
	});
	
	/**
	* End of the privacy-box part.
	**/

});
function initResetIcon(obj){
	obj.removeClass('privacy_icon1');
	obj.removeClass('privacy_icon2');
	obj.removeClass('privacy_icon3');
	obj.removeClass('privacy_icon4');
	obj.removeClass('privacy_icon5');
}
function initResetSelectedUsers(obj){
	resetSelectedUsers(obj);
	resetSelectedChannels(obj);
}
function getSponsorsDataRelated(){
	var oldnumber=parseInt($(".MediaList").attr("data-number"));
	var $skip=parseInt(oldnumber - ( old_currentpage + 1 )*18 );
	if($skip<0) $skip=0;
	if((""+$skip)=="NaN") $skip=0;
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmoresponsors.php'), {globchannelid:channelGlobalID(),currentpage:currentpage,one_object:one_object,globtxtsrch:globtxtsrch,globcaractersearch:globcaractersearch,skip:$skip},function(data){
		if(data!=false){
			$('.MediaList ul').append(data);
			var currPageStatus=$('.MediaList .currPageStatus');
			if((""+currPageStatus.attr('data-value'))=="0"){
				$(".loadmoremedia").hide();
			}else{
				$(".loadmoremedia").show();
				
				
			}
			$('.MediaCont .mediaConnectionsDataCount').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();
			InitDocument();
			$skip=$skip+one_object;
			$(".MediaList").attr("data-number",($(".MediaList ul li").length)+ $skip );
		}else{
			$(".loadmoremedia").hide();	
		}
		one_object=0;
		old_currentpage=currentpage;
		$('.upload-overlay-loading-fix').hide();
	});
}

function showHideSponsorsDisplay(id,_type){
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/show_hide_sponsors_display.php'),
        data: {id:id, _type:_type,globchannelid:channelGlobalID()},
        type: 'post',
        success: function(data){
                $('.upload-overlay-loading-fix').hide();
        }
    });
}
function addValue1(obj){
	if($(obj).attr('value') == '') $(obj).attr('value',$(obj).attr('data-val'));
} 
function removeValue1(obj) {
	if($(obj).attr('value') == $(obj).attr('data-val')) $(obj).attr('value','');
}
function checkSubmitSearch(e){
   if(e && e.keyCode == 13){
	  $('#searchbut').click();
   }
}
function initReportSponsorsFunctions(obj,data_type,data_id,data_sponsorid){
    obj.fancybox({
        padding	:0,
        margin	:0,
        beforeLoad:function(){
            var show_disconnect = 0;
            var show_sponsors = 1;
            
            $('#sharepopup').html('');                        
            var str='<div class="sharepopup_container" style="margin-left:34px; width:506px; height:382px"></div>';
            $('#sharepopup').html(str);

            hide_notification_toggle = 0;
            remove_notification_toggle = 0;
            $.ajax({
                type: "POST",
                cache:false,
                url: ReturnLink("/ajax/popup_report_data.php?no_cache="+Math.random() ),
                data: {type:data_type, show_disconnect:show_disconnect,data_id:data_id,show_sponsors:show_sponsors,channel_id:channelGlobalID(),data_friend_section:''},
                success: function(data) {
                    var jres = null;
                    try{
                        jres = $.parseJSON( data );
                    }catch(Ex){
                        closeFancyBox();
                    }
                    if( !jres ){
                        closeFancyBox();
                        return ;
                    }
                    $('#sharepopup').html(jres.data);
                    $('#sharepopup .sharepopup_container').attr('data-sponsorid',data_sponsorid);
                }
            });
        }
    });
}
function showHideSponsorsDisplay_Report(id,_type){
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/show_hide_sponsors_display.php'),
        data: {id:id, _type:_type,globchannelid:channelGlobalID()},
        type: 'post',
        success: function(data){
            window.location.reload();
        }
    });
}
function removeSponsorChannels_Report(data_sponsorid){
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_removesponsors.php'), {globchannelid:channelGlobalID(),id:data_sponsorid},function(data){
        if(data!=false){
            window.location.reload();
        }else{
            $('.upload-overlay-loading-fix').hide();	
        }
    });
}
function removeSponsorChannels($parent){
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_removesponsors.php'), {globchannelid:channelGlobalID(),id:$parent.attr('data-sponsorid')},function(data){
        if(data!=false){
            $parent.remove();
            var num_connections=parseInt($('.mediaConnectionsDataCount').attr('data-number'))-1;
            $('.mediaConnectionsDataCount').attr('data-number',num_connections);
            $('.mediaConnectionsDataCount').html(num_connections);
            $('.sponsorsmenu').parent().find('.channelyellow').html(num_connections);

            if($(".loadmoremedia").css('display')!="none"){
                    one_object=1;
                    getSponsorsDataRelated();
            }else{
                    $('.upload-overlay-loading-fix').hide();	
            }
        }else{
                $('.upload-overlay-loading-fix').hide();	
        }
    });
}