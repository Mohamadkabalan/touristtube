var jscrollpane_apiLN=null;
var notFadeoutTimeout=null;
var notifFlag=0;
$(document).ready(function(e) {
        $(document).on('click', ".ProfileHeaderNotifications", function (e) {
            e.preventDefault();
            if($('.notifications_feedcontainer').css('display')!='none'){
                $('.notifications_feedcontainer').hide();
            }else{
                if (notifFlag == 0) {
                    $('.notifications_feedcontainer').show();
                    if($('.notifications_feed_inside').height()>=350){
                        initscrollPaneNotif($(".notifications_feed"));                    
                    }
                    updateNotificationViewed();
                    $('.notifications_feedcontainer').unbind('mouseenter mouseleave').hover(function () {
                        clearTimeout(notFadeoutTimeout);
                        $('.notifications_feedcontainer').stop(true, true);
                        $('.notifications_feedcontainer').show();
                        notifFlag=1;
                    }, function () {
                        notFadeoutTimeout = setTimeout(function () {
                            $('.notifications_feedcontainer').hide();
                            notifFlag=0;
                        }, 500);
                    });
                    $('.notifications_feedcontainer').mouseenter();
                }else{
                    $('.notifications_feedcontainer').hide();
                    notifFlag=0;                    
                }
            }
        });
        $(document).on('click', "#notifications_open_button", function (e) {
		$("#notifications_feed").toggle();
		$("#notifications_viewall").toggle();
                if($('.notifications_feed_inside').height()>=350){
                    initscrollPaneNotif($(".notifications_feed"));                    
                }
                $("#notifications_counter").html('');
                $(".notifications_box .plus").html('');
                $(".ProfileHeaderNotifications .notCountVal").html('');
                $(".ProfileHeaderNotifications .plus").html('');
		updateNotificationViewed();
	});
        $(document).on('click', ".notification_bottom", function (e) {
		var log_obj = $(this);
		var action_on ="hide";
		if( parseInt(log_obj.attr('data-notification'))==0 ){
			action_on ="unhide";
		}
		updateTTNotificationsData(log_obj, action_on);
	});
        $(document).on('click', ".notification_details_open", function (e) {
		$('.notification_details_up_button.active').click();
		var $this = $(this);
		var data_id = $this.attr("data-id");
		var $parent = $this.closest('.log_item_list_not');
		$parent.find('.log_item_list_details_not').show();
		$(this).hide();
		$parent.find('.notification_details_up_button').addClass('active');                
                if($('.notifications_feed_inside').height()>=350){
                    initscrollPaneNotif($(".notifications_feed"));                    
                }		
		
		var pos = $parent.offset().top-$parent.parent().offset().top;		
		if(jscrollpane_apiLN!=null){
                    jscrollpane_apiLN.scrollToY(pos,true);
		}
	});
        $(document).on('click', ".notification_details_up_button", function (e) {
                var updatescroll=false;
                if($('.notifications_feed_inside').height()>=350){
                    updatescroll=true;                    
                }
		$(this).removeClass('active');
		var $parent = $(this).closest('.log_item_list_not');
		$parent.find('.log_item_list_details_not').hide();
		$parent.find('.notification_details_open').show();
                if($('.notifications_feed_inside').height()>=350 || updatescroll){
                    initscrollPaneNotif($(".notifications_feed"));                    
                }
                if($('.notifications_feed_inside').height()<400){
                    $('.jspPane').css('top','0px');                  
                }
	});
	$('.event_googlemap_not').each(function(index, element) {			
		var $googlemap_location	= $(this).find('.googlemap_location');
		var $id_location = $googlemap_location.attr('id');
		var image = new google.maps.MarkerImage(ReturnLink("/images/map_marker.png"));					
		var mapOptions = {
		  center: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
		  zoom: 7,
          disableDefaultUI: true,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		var map = new google.maps.Map(document.getElementById($id_location),mapOptions);
		
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
			map: map,
			icon: image,
			title: ''
		});
	});
});
// Updates the newsfeed table.
function updateNotificationViewed(){
	$.ajax({
		url: ReturnLink('/ajax/info_not_TT_update.php'),
		data: {},
		type: 'post',
		success: function(data){			
		}
	});	
}
function updateTTNotificationsData(obj, action){
	var id=obj.attr('data-id');
	var data_ischannel=parseInt(obj.attr('data-ischannel'));
	var data_uid=obj.attr('data-uid');
	
	$.ajax({
		url: ReturnLink('/ajax/info_newsfeed_update.php'),
		data: {id:id,action:action,ischannel:data_ischannel,uid:data_uid},
		type: 'post',
		success: function(data){
			// Refresh the list where needed.
			if(data){
				if(action == 'hide'){
					obj.find('.stop_notifications_text').html(t('undo'));
					obj.find('.stop_notifications_img').addClass('plus');
					obj.attr('data-notification',0);
				} else if(action == 'unhide'){
					obj.find('.stop_notifications_img').removeClass('plus');
					obj.find('.stop_notifications_text').html(t('stop notifications'));
					obj.attr('data-notification',1);
				}
			}
		}
	});	
}
function initscrollPaneNotif(obj){
	obj.jScrollPane();
	jscrollpane_apiLN = obj.data('jsp');
}