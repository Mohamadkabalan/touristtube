var log_page = 0; // pager for the log part.
var search_order="";
var search_filter="";
var jscrollpane_apiL;

$(document).ready(function(){	
	initSocialFunctions();
	if(parseInt(is_owner)==1){
		InitChannelUploaderHome('photos_posts','posts_container',15,0);
		InitChannelUploaderHome('videos_posts','posts_container',10240,0);		
	}
	
	$(".notifications_feed").jScrollPane();
	$(".notifications_feed .jspVerticalBar").css('right','3px');
	$(".notifications_feed .jspVerticalBar").css('width','5px');
    
    
    $('#privacy_button').mouseover(function() {
        var diffx = $('#ChannelContainer').offset().left+255;
        var diffy = $('#ChannelContainer').offset().top+22;
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
	
	$(document).on('click',".textdiv" ,function(){
		$('.linktextarea').hide();
		$('.texttextarea').show();
	});
	$(document).on('click',".linkdiv" ,function(){
		$('.texttextarea').hide();
		$('.linktextarea').show();		
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
			data: {connectionsValue:connectionsValue, connectionsArray:connectionsArray, connectionsType:PRIVACY_EXTAND_TYPE_LOG, channel_id:channelGlobalID()},
			callback: function(ret){
				initResetIcon($('#privacy_button'));
				$('#privacy_button').addClass('privacy_icon'+connectionsValue);
                                $('#privacy_button').attr('data-title',curob.find('.privacy_select option:selected').text());
                                $('#privacy_close_button').click();
				$('.upload-overlay-loading-fix').hide();				
			}
		});
		
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
		updateprivacylogs(globlinkArraystr,globDataArraystr,true,curob);
	});
	
	
	$(document).on('mouseover',"#img_coverall" ,function(){
		var img_height = $(this).parent().find("#img_thumb").height();
		$dimmer = $(this).parent().find("#img_dim");
		$text = $(this).parent().find("#img_title");
		
		$dimmer.height( img_height );
		$dimmer.show();
		$text.show();
	});
	$(document).on('mouseout',"#img_coverall" ,function(){
		$dimmer = $(this).parent().find("#img_dim");
		$text = $(this).parent().find("#img_title");
		
		$dimmer.hide();
		$text.hide();
	});
	
	$(document).on('mouseover',"#stop_notifications_img" ,function(){
		$(this).parent().find("#stop_notifications_text").show();
	});
	$(document).on('mouseout',"#stop_notifications_img" ,function(){
		$(this).parent().find("#stop_notifications_text").hide();
	});
	$(document).on('click',".postsend" ,function(){
		//$(this).attr("src", "../../images/channel/notifications/notifications_show.png ");
		
		if($('.texttextarea').css('display')!="none")
		{
			var text_post=getObjectData($('.textareastyle'));
			var type_post=SOCIAL_POST_TYPE_TEXT;
			if(text_post=='')
			{
				return;
			}
		}
		else if($('.linktextarea').css('display')!="none")
		{
			var text_post=getObjectData($('.linkareastyle'));
			var type_post=SOCIAL_POST_TYPE_LINK;
			if(text_post=='')
			{
				return;
			}
		}else{
			return;
		}

		TTCallAPI({
			what: '/social/post/add',
			data:  {post_text : text_post , post_type: type_post,channel_id:channelGlobalID() },
			callback: function(resp){
				if( resp.status === 'error' ){
					TTAlert({
						msg: resp.error,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return;
				}
				document.location.reload();
			}
		});
	});
	
	
	/**
	* The privacy-box part.
	**/
	
	if(parseInt(is_owner)==1){
		addmoreusersautocomplete_privacy( $('#addmoretext_log') , channelGlobalID() );
	}
	
	// Change the privacy icon.
	initResetSelectedUsers($('#addmoretext_log'));
	$("#privacy_select").change(function(){
		var selectval=parseInt($(this).val());
		
		if(selectval==5){
			$(this).parent().find('.privacy_picker').removeClass('displaynone');
		}else{
			initResetSelectedUsers($(this).parent().find('.addmore input'));
			$(this).parent().find('.friendscontainer .uploadinfocheckbox').removeClass('active');
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
	
	// Remove a log.
	$(document).on('click','#remove_button_log',function(){
		var log_obj = $(this).parent();
		updateLogData(log_obj, 'remove');
	});
        
	$(document).on('click','#remove_button_logpost',function(){
		var log_obj = $(this).parent();
		updateLogData(log_obj, 'removepost');
	});
	
	// Hide a log.
	$(document).on('click','#hide_button_log',function(){
		var log_obj = $(this).parent();
		updateLogData(log_obj, 'hide');
	});
	// Hide a log viewer.
	$(document).on('click','.hide_button_log_viewer',function(){
		var log_obj = $(this).closest('.log_item_list').remove();		
	});
	
	// unhide a log.
	$(document).on('click','.unhide_log',function(){
		var log_obj = $(this).parent();
		updateLogData(log_obj, 'unhide');
	});
	
	$(document).on('click',".log_top_arrow" ,function(){
		var newsfeed_id = $(this).attr("data-id");
		$('#log_hidden_buttons_' + newsfeed_id).toggle();
	});
	
	$(document).on('click',"#cal_left_arrow" ,function(){
		var selected_year = $("#cal_year_display").html();
		$("#cal_year_display").html(selected_year/1 - 1);
		initLogsPage();
	});


	$(document).on('click',"#cal_right_arrow" ,function(){
		var selected_year = $("#cal_year_display").html();
		$("#cal_year_display").html(selected_year/1 + 1);
		initLogsPage();
	});
	
	$(document).on('click',".cal_month" ,function(){
		var new_selected_month = $(this).attr("id").substr(6);
		$("#cal_selected_month").val(new_selected_month);
		$(".cal_month").css("color", "#000");
		$(".cal_month").css("background-image", "");
		initLogDocument();
		initLogCalendar();
		initLogsPage();
	});
	
	$(document).on('click',"#log_load_more" ,function(){
		// Load the logs items updating instead of replacing.
		initLogsPage(1);
	});
	$(document).on('click','.overdatabutenable_log',function(){
		var $this=$(this);
		var entity_id = $(this).parent().attr('data-entity-id');
		var entity_type = $(this).parent().attr('data-entity-type');
		
		if(String(""+$this.attr('data-status'))=="1"){
			enableSharesComments_log(entity_id, entity_type, 0);
			$this.attr('data-status','0');
			$this.find('.overdatabutntficon').addClass('inactive');
		}else{
			enableSharesComments_log(entity_id, entity_type, 1);
			$this.attr('data-status','1');
			$this.find('.overdatabutntficon').removeClass('inactive');
		}
	});
	
	$(document).on('click',".searchBtn_log" ,function(){
		search_order=$('.selectBox_order').attr('data-value');
		search_filter=$('.selectBox_filter').attr('data-value');
		if(search_filter=="all"){
			search_filter="";	
		}
		initLogsPage();
	});
	initLogDocument();
	initLogCalendar();
	initScrollLog();
	enableSelectBoxesLog();
});
function enableSelectBoxesLog(){
	$('.selectBox_order').each(function(){
		$(this).children('span.selected').html($(this).children('div.selectOptions').children('span.selectOption:first').html());
		$(this).attr('data-value',$(this).children('div.selectOptions').children('span.selectOption:first').attr('data-value'));
		
		$(this).children('span.selected,span.selectArrow').click(function(){
			if($(this).parent().children('div.selectOptions').css('display') == 'none'){
				$(this).parent().children('div.selectOptions').css('display','block');
			}else{
				$(this).parent().children('div.selectOptions').css('display','none');
			}
		});
		
		$(this).find('span.selectOption').click(function(){
			$(this).parent().css('display','none');
			$(this).closest('div.selectBox_log').attr('data-value',$(this).attr('data-value'));
			$(this).parent().siblings('span.selected').html($(this).html());
                        $('.searchBtn_log').click();
		});
	});	
	$('.selectBox_filter').each(function(){
		$(this).children('span.selected').html($(this).children('div.selectOptions').children('span.selectOption:first').html());
		$(this).attr('data-value',$(this).children('div.selectOptions').children('span.selectOption:first').attr('data-value'));
		
		$(this).children('span.selected,span.selectArrow').click(function(){
			if($(this).parent().children('div.selectOptions').css('display') == 'none'){
				$(this).parent().children('div.selectOptions').css('display','block');
			}else{
				$(this).parent().children('div.selectOptions').css('display','none');
			}
		});
		
		$(this).find('span.selectOption').click(function(){
			$(this).parent().css('display','none');
			$(this).closest('div.selectBox_log').attr('data-value',$(this).attr('data-value'));
			$(this).parent().siblings('span.selected').html($(this).html());
                        $('.searchBtn_log').click();
		});
	});	
}
function updateprivacylogs(param1,param2,bool,curob){
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
		}
	});	
}
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
function initLogsPage(update){
	// If the function is called without argument == first load, not "load more"
	if(!update){
		update = 0;
		log_page = 0;
	// Load more case.
	} else {
		log_page++;
	}
	
	// Get the selected date.
	var selected_month = $("#cal_selected_month").val();
	var selected_year = $("#cal_year_display").html();
	
	$.ajax({
		url: ReturnLink('/ajax/ajax_log_page.php'),
		data: {page:log_page, selected_month:selected_month, selected_year:selected_year,globchannelid:channelGlobalID(),search_order:search_order,search_filter:search_filter},
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
			}else{
				var myData = ret.data;
				var data_count = ret.count;
				
			//	$("#channel_log_container").html('');
			//	$("#channel_log_container").html(myData);
				if(update == 0)
					$("#log_data_container").html('');
				
				$("#log_data_container").append(myData);
				initLogDocument();
				initScrollLog();
				
			//	log_page++;
			
				// Toggle the visibility of the "load more" button.
				if(data_count - log_page * 10 > 10){
					$(".buttonmorecontainer").show();
				} else {
					$(".buttonmorecontainer").hide();
				}
			
			}
		}
	});
}
function initScrollLog(){
	//$("#log_scroller").jScrollPane();	
}
// Updates the newsfeed table (for the log sections).
// Handles the actions: Remove, Hide and unhide.
function updateLogData(obj, action){
	var id=obj.attr('data-id');
	//$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_log_update.php'),
		data: {id:obj.attr('data-id'),action:action, globchannelid:channelGlobalID()},
		type: 'post',
		success: function(data){
                        var ret = null;
                        try{
                            ret = $.parseJSON(data);
                        }catch(Ex){
                            return ;
                        }
                        if(ret.result=='ok'){
                            if(action == 'remove' || action == 'removepost'){
                                document.location.reload();
                            } else if(action == 'hide'){
                                obj.closest('.log_item_list').find(".social_data_all").hide();
                                obj.closest('.log_item_list').find("#hidden_header_" + id).css('display', 'block');
                                obj.closest('.log_item_list').find("#hidden_body_" + id).css('display', 'block');
                            } else if(action == 'unhide'){
                                obj.closest('.log_item_list').find(".social_data_all").show();
                                obj.closest('.log_item_list').find("#hidden_header_" + id).css('display', 'none');
                                obj.closest('.log_item_list').find("#hidden_body_" + id).css('display', 'none');
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
			
		//	$('.upload-overlay-loading-fix').hide();
		}
	});	
}
// Initializes the log-section document.
function initLogDocument(){        
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
        if($('.edit_post_social').length>0){
            initPostEdit();
        }
	initCommentData();
        
        $('.postImg_link0').fancybox({
            helpers : {
                overlay : {closeClick:true}
            }
        });
        $(".postImg_link1").fancybox({
		transitionIn: 'none',
		transitionOut: 'none',		
		autoScale: false,
		autoDimensions: false,		
		width: 694,
		minWidth: 694,
		maxWidth: 694,
		height: 442,
		minHeight: 442,
		maxHeight: 442,
		padding	:0,
		margin	:0,
		type: 'iframe',
		scrolling: 'no',
                helpers : {
                    overlay : {closeClick:true}
                }
	});
        $('.event_googlemap').each(function (index, element) {
            var $googlemap_location = $(this).find('.googlemap_location');
            var $id_location = $googlemap_location.attr('id');
            var image = new google.maps.MarkerImage(ReturnLink("/images/map_marker.png"));
            var mapOptions = {
                center: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
                zoom: 8,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var map = new google.maps.Map(document.getElementById($id_location), mapOptions);

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
                map: map,
                icon: image,
                title: ''
            });
        });
        $('.event_googlemap_not').each(function (index, element) {
            var $googlemap_location = $(this).find('.googlemap_location');
            var $id_location = $googlemap_location.attr('id');
            var image = new google.maps.MarkerImage(ReturnLink("/images/map_marker.png"));
            var mapOptions = {
                center: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
                zoom: 8,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var map = new google.maps.Map(document.getElementById($id_location), mapOptions);

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
                map: map,
                icon: image,
                title: ''
            });
        });
	$('.social_data_all').each(function(index, element) {
		if($(this).find('.social_not_refreshed').length>0){
                    if ($(this).find('.btn .rates').length > 0) addRaty($(this));
                    if ($(this).find('.btn .rates').length > 0) initRates($(this));
                    if ($(this).find('.btn .likes').length > 0) initLikes($(this));
                    if (parseInt($(this).attr('data-enable')) == 1 || parseInt(is_owner) == 1) {
                        $(this).find('.btn_enabled').show();
                        if ($(this).find('.btn .comments').length > 0) initComments($(this));
                        if ($(this).find('.btn .shares').length > 0) initShares($(this));
                    } else {
                        $(this).find('.btn_enabled').hide();
                    }
                    if($(this).find('.sponsorsDiv').length>0) initSponsors( $(this) );
                    if($(this).find('.joinsDiv').length>0) initJoins( $(this) );
		}
    });	
        $(".log_media_container .picture").each(function () {
            var $this = $(this).parent().parent();
            $this.on('mouseenter', function () {
                $this.find('.log_item_right').show();
                $this.find('.enlarge').show();
            }).on('mouseleave', function () {
                $this.find('.log_item_right').hide();
                $this.find('.enlarge').hide();
            });
        });
        $(".log_media_container .footer_image").each(function () {
            var $this = $(this).parent();
            $this.on('mouseenter', function () {
                $this.find('.enlarge').show();
            }).on('mouseleave', function () {
                $this.find('.enlarge').hide();
            });
        });
	$(".log_sponsor_container").each(function(){
		var $this = $(this);
		$this.on('mouseenter',function(){
			$this.find('.log_sponsor_right').show();
		}).on('mouseleave',function(){
			$this.find('.log_sponsor_right').hide();
		});
	});
	$(".log_events_container").each(function(){
		var $this = $(this);
		$this.on('mouseenter',function(){
			$this.find('.log_events_right').show();
			$this.find('.enlarge').show();
		}).on('mouseleave',function(){
			$this.find('.log_events_right').hide();
			$this.find('.enlarge').hide();
		});
	});
	$(".log_brochure_container").each(function(){
		var $this = $(this);
		$this.on('mouseenter',function(){
			$this.find('.log_brochure_right').show();
		}).on('mouseleave',function(){
			$this.find('.log_brochure_right').hide();
		});
	});
	initReportsLog();
}
function initReportsLog(){
    $(".report_button_log_reason").each(function(){
        var $this = $(this);
        var $parent = $this.closest('.log_item_list');
        $this.removeClass('report_button_log_reason');
        initLogReportFunctions($this,$parent.find('.social_data_all'));
    });
}
// Initializes the log-section Calendar.
function initLogCalendar(){	
	var selected_month = $("#cal_selected_month").val();
	$('#month_' + selected_month).css("background-image", "url(" + ReturnLink('/images/channel/calpicker/month_selector.png') + ")");
	$('#month_' + selected_month).css("color", "#fff");
}

// Toggle the enable shares and comments.
function enableSharesComments_log(entity_id, entity_type, new_status){
	//$('.upload-overlay-loading-fix').show();
	var action = 'remove';
	$.ajax({
		url: ReturnLink('/ajax/info_log_updatesharescomments.php'),
		data: {entity_id:entity_id,entity_type:entity_type, new_status:new_status, globchannelid:channelGlobalID()},
		type: 'post',
		success: function(data){
			
			// Refresh the list where needed.
			if(action == 'remove'){
				initLogsPage();
			} else if(action == 'hide'){
				$("#hidden_header_" + id).css('display', 'block');
				$("#hidden_body_" + id).css('display', 'block');
			} else if(action == 'unhide'){
				$("#hidden_header_" + id).css('display', 'none');
				$("#hidden_body_" + id).css('display', 'none');
			}
			
		//	$('.upload-overlay-loading-fix').hide();
		}
	});	
}

function addValue1(obj){
	if($(obj).attr('value') == '') $(obj).attr('value',$(obj).attr('data-value'));
} 
function removeValue1(obj) {
	if($(obj).attr('value') == $(obj).attr('data-value')) $(obj).attr('value','');
}
function updateImage_Add_Posts(curname,filetype){
	var type_post=SOCIAL_POST_TYPE_VIDEO;	
	if(filetype=="photos_posts"){
		type_post=SOCIAL_POST_TYPE_PHOTO;	
	}
	TTCallAPI({
		what: '/social/post/add',
		data:  {post_text : curname , post_type: type_post,channel_id:channelGlobalID() },
		callback: function(resp){
			if( resp.status === 'error' ){
				TTAlert({
					msg: resp.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return;
			}
			document.location.reload();
		}
	});
	closeFancyBox();
}

function initscrollPaneNotif(obj){
	obj.jScrollPane();
	jscrollpane_apiL = obj.data('jsp');
}