var not_page = 0; // pager for the log part.

$(document).ready(function(){
	// Initialize the calendar on first-load.
	initLogCalendar();
	
	var gen_obj = $("#markReadData");
	updateNotificationData(gen_obj, 'markAsViewed');
	
    $(document).on('click',"#request_not_ignore1" ,function(){
        $('.upload-overlay-loading-fix').show();
        var $parent=$(this).closest('.log_item_list');
        var data_id = $parent.attr('data-id');
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
                $parent.remove();
            }
        });
    });
    $(document).on('click',"#request_not_ignore2" ,function(){
        $('.upload-overlay-loading-fix').show();
        var $parent=$(this).closest('.log_item_list');
        var data_id = $parent.attr('data-id');
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
                $parent.remove();
            }
        });
    });
    $(document).on('click',"#request_not_accept1" ,function(){
        $('.upload-overlay-loading-fix').show();
        var $parent=$(this).closest('.log_item_list');
        var data_id = $parent.attr('data-id');
        TTCallAPI({
            what: 'channel/relation/accept',
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
                $parent.remove();
            }
        });
    });
    $(document).on('click',"#request_not_accept2" ,function(){
        $('.upload-overlay-loading-fix').show();
        var $parent=$(this).closest('.log_item_list');
        var data_id = $parent.attr('data-id');
        TTCallAPI({
            what: 'channel/relation/accept',
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
                $parent.remove();
            }
        });
    });
	
	$(document).on('click',".topArrow" ,function(){
		var parent = $(this).parent();
		
		if(parent.find('.log_top_buttons_container').css('display') !="none" ){			
			parent.find('.log_top_buttons_container').hide();
		}else{
			parent.find('.log_top_buttons_container').show();
		}
	});
        $(document).on('click',"#stop_getnotif_button_ch" ,function(){
            var $this = $(this);
            var $parent = $this.closest('.log_top_buttons_container');
            if(parseInt($this.attr('data-notification'))== 1){
                $this.html(t('undo'));
                $this.attr('data-notification', 0);
                updateNotificationDataCH($parent, 'stopNotifications');			
            }else{
                $this.html(t('stop notifications'));
                $this.attr('data-notification', 1);
                updateNotificationDataCH($parent, 'getNotifications');			
            }
        });
	$(document).on('click',"#stop_getnotif_button" ,function(){
		var $this = $(this);
		var parent = $this.parent().parent();
		
		if(parseInt($this.attr('data-notification'))== 0){
			$this.html(t('undo'));
			$this.attr('data-notification', 1);
			
			parent.css('background', '#f4f4f4');
			
			var parent = $(this).closest('.log_top_buttons_container');
			updateNotificationData(parent, 'stopNotifications');			
		}else{
			$this.html( t('stop notifications') );
			$this.attr('data-notification', 0);
			
			parent.css('background', '#FFF');
			
			var parent = $(this).closest('.log_top_buttons_container');
			updateNotificationData(parent, 'getNotifications');			
		}
	});
	
	$(document).on('click',"#remove_button" ,function(){
		var parent = $(this).parent();
		
	});
	
	
	/* Calendar part. */
	
	$(document).on('click',"#cal_left_arrow" ,function(){
		var selected_year = $("#cal_year_display").html();
		$("#cal_year_display").html(selected_year/1 - 1);
		initNotificationsPage();
	});

	$(document).on('click',"#cal_right_arrow" ,function(){
		var selected_year = $("#cal_year_display").html();
		$("#cal_year_display").html(selected_year/1 + 1);
		initNotificationsPage();
	});
	
	$(document).on('click',".cal_month" ,function(){
		var new_selected_month = $(this).attr("id").substr(6);
		$("#cal_selected_month").val(new_selected_month);
		$(".cal_month").css("color", "#000");
		$(".cal_month").css("background-image", "");
		initLogCalendar();
		initNotificationsPage();
	});
	
	
	
	$(document).on('click',"#not_load_more" ,function(){
		// Load the logs items updating instead of replacing.
		initNotificationsPage(1);
	});


	/* End of the calendar part. */
	
	
	$(document).on('click',"#remove_button" ,function(){
		var parent = $(this).parent();
		updateNotificationData(parent, 'remove');
	});
	
	initPhotoFancyNotification();
	
});

// Initializes the log-section Calendar.
function initLogCalendar(){	
	/*var selected_month = $("#cal_selected_month").val();
	$('#month_' + selected_month).css("background-image", "url(" + ReturnLink('/images/channel/calpicker/month_selector.png') + ")");
	$('#month_' + selected_month).css("color", "#fff");*/
}

function updateNotificationDataCH(obj, action){
    var chid=obj.attr('data-user-id');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/info_not_relation_update.php'),
        data: {action:action,channel_id:chid,parent_id:channelGlobalID()},
        type: 'post',
        success: function(data){
            $('.upload-overlay-loading-fix').hide();
        }
    });	
}
// Updates the newsfeed table.
function updateNotificationData(obj, action){
	var id=obj.attr('data-not-id');
	var current_channel_id = obj.attr('data-currchannel-id');
	var user_id = obj.attr('data-user-id');
	
	//$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_not_update.php'),
		data: {id:id,action:action,current_channel_id:current_channel_id,user_id:user_id},
		type: 'post',
		success: function(data){
			// Refresh the list where needed.
			if(action == 'remove'){
				obj.closest('.notification_Div').remove();
			}else if(action == 'stopNotifications' || action == 'getNotifications'){                            
                            //location.reload();
                        }
			
		//	$('.upload-overlay-loading-fix').hide();
		}
	});	
}

function initNotificationsPage(update){
	// If the function is called without argument == first load, not "load more"
	if(!update){
		update = 0;
		not_page = 0;
	// Load more case.
	} else {
		not_page++;
	}
	
	// Get the selected date.
	var selected_month = $("#cal_selected_month").val();
	var selected_year = $("#cal_year_display").html();
        var lastobj = $('#not_scroller .log_item_list').last();
        var entity_goup = lastobj.find('.log_top_arrow').attr('data-group');
        var entity_id = lastobj.find('.log_top_arrow').attr('data-id');
	
	$.ajax({
		url: ReturnLink('/ajax/ajax_not_page.php'),
		data: {page:not_page, selected_month:selected_month, entity_goup:entity_goup, entity_id:entity_id, selected_year:selected_year,globchannelid:channelGlobalID()},
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
				
				if(update == 0)
					$("#newsContainer").html('');
				
				$("#newsContainer").append(myData);
				initPhotoFancyNotification();
			
				// Toggle the visibility of the "load more" button.
				//console.log(not_page);
				if( data_count >= 1 ){
					$(".buttonmorecontainer").show();
				} else {
					$(".buttonmorecontainer").hide();
				}
			
			}
		}
	});
}

function initPhotoFancyNotification(){	
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
}