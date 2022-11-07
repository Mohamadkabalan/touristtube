$(document).ready(function(e) {
    
	$(document).on('click',"#log_not_load_more" ,function(){
		initLogsNotificationsTTPage(1);
	});
    $(document).on('click', ".event_picflash b", function () {
        var hash = $(this).html();
        hash = hash.substr(1);
        window.location.href = ReturnLink('/echoes/tag/' + hash);
    });
	$(document).on('click',".cal_month_NOT" ,function(){
		var new_selected_month = $(this).attr("id").substr(6);
		$("#cal_selected_month").val(new_selected_month);
		$(".cal_month_NOT").css("color", "#000");
		$(".cal_month_NOT").css("background-image", "");
		initLogCalendar();
		initLogsNotificationsTTPage();
	});
	$(document).on('click',"#cal_left_arrow_NOT" ,function(){
		var selected_year = $("#cal_year_display").html();
		$("#cal_year_display").html(selected_year/1 - 1);
		initLogsNotificationsTTPage();
	});


	$(document).on('click',"#cal_right_arrow_NOT" ,function(){
		var selected_year = $("#cal_year_display").html();
		$("#cal_year_display").html(selected_year/1 + 1);
		initLogsNotificationsTTPage();
	});
	
	$(document).on('click','#stop_getnotif_button',function(){
		var log_obj = $(this);
		var action_on ="hide";
		if( parseInt(log_obj.attr('data-notification'))==0 ){
			action_on ="unhide";
		}
		updateTTNotificationsData(log_obj, action_on);		
	});
});
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
				//document.location.reload();
				if(action == 'hide'){
					obj.html( t('undo') );
					obj.attr('data-notification',0);
				} else if(action == 'unhide'){
					obj.html( t('stop notifications') );
					obj.attr('data-notification',1);
				}
			}
		}
	});	
}
function initLogsNotificationsTTPage(update){
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
        var lastobj = $('#log_data_container .log_item_list').last();
        var entity_goup = lastobj.find('.log_top_arrow').attr('data-group');
        var entity_id = lastobj.find('.log_top_arrow').attr('data-id');
	
	$.ajax({
		url: ReturnLink('/ajax/ajax_not_TTpage.php'),
		data: {page:log_page, selected_month:selected_month, entity_goup:entity_goup, entity_id:entity_id, selected_year:selected_year,data_profile:userGlobalID()},
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
					$("#log_data_container").html('');
				
				$("#log_data_container").append(myData);
				initLogDocument();
			
				// Toggle the visibility of the "load more" button.
				if( data_count >= 1 ){
                                    $(".buttonmorecontainer").show();
				} else {
                                    $(".buttonmorecontainer").hide();
				}
			
			}
		}
	});
}