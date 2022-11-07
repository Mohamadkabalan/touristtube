var my_newval="return";
$(document).ready(function(){
	setCalendar();
	$(document).on('click','.view_all_but' ,function(){
		var $this = $(this);
		var $parent = $this.closest('.ChannelContainer_Items_up');
		var $height=$parent.find('#first_items').height() + 17;
		$this.hide();
		$parent.find('.ChannelContainer_Items').animate({'height':$height+'px'},500,function(){
			$parent.find('.ChannelContainer_Items').css('height','auto');
		});
	});
});
function setCalendar(){
	// Calendar Setup
	var instance_name=null;
	var instance_clicked=null;
	EventsDetailedCal = Calendar.setup(	{
		
		cont          : "ChannelContainer_Calendar",
		bottomBar 	 : false,
                noScroll  	 : true,		
		selectionType : Calendar.SEL_MULTIPLE,
		disabled: function() { 
			return true; 
		},
		dateInfo : getDateEVInfo,
		onChange : function() 
		{
			// set time to do js hover after rendering the calendar
			if(instance_name!=null){
				clearTimeout(instance_name);
			}
			instance_name=setTimeout(function()
			{
				clearTimeout(instance_name);
				// check the selected days only
				
				$(".DynarchCalendar-day-selected.highlight, .DynarchCalendar-day-selected.highlight2").parent().click(function(){
					if($(this).find('.DynarchCalendar-day-selected').hasClass('active')){
						//return;
					}
					if(instance_clicked!=null){
						instance_clicked.removeClass('active');
					}
					instance_clicked=$(this).find('.DynarchCalendar-day-selected');
					$('.view_all_but').hide();
					$('.ChannelContainer_Items').css('height','157px');
					$(this).find('.DynarchCalendar-day-selected').addClass('active');
					// get the date from div attribute
					var DateIndex = $(this).find('.DynarchCalendar-day-selected').attr('dyc-date');
					
					var first_items="";
					var second_items="";
					for(var i=0;i<DATE_INFOInit[DateIndex].length;i++){
						var currdata=DATE_INFOInit[DateIndex][i];
						var link_a=ReturnLink('/channel-events-detailed/'+currdata['id']);
						
						if(parseInt(currdata['_type'])==1){
							first_items+='<div class="items"><a class="div_class" href="'+link_a+'"><img class="items_img" width="86" height="56" src="'+currdata['imageurl']+'"><div class="items_title">'+currdata['title']+'</div><div class="items_event yellowbold11">'+t("view event")+'</div></a><img class="items_close" width="18" height="18" src="'+ReturnLink('/images/eventsdetailed/close-tooltip1.jpg')+'"></div>';	
						}else if(parseInt(currdata['_type'])==2){
							second_items+='<div class="items"><a class="div_class" href="'+link_a+'"><img class="items_img" width="86" height="56" src="'+currdata['imageurl']+'"><div class="items_title">'+currdata['title']+'</div><div class="items_event yellowbold11">'+t("view event")+'</div></a><img class="items_close" width="18" height="18" src="'+ReturnLink('/images/eventsdetailed/close-tooltip1.jpg')+'"></div>';							
						}
					}
					
					$('#channelcontainer_Items1 #first_items').html(first_items);
					$('#channelcontainer_Items1 .div_class_text span').html("("+$('#channelcontainer_Items1 #first_items .items').length+")");
					$('#channelcontainer_Items2 #first_items').html(second_items);
					$('#channelcontainer_Items2 .div_class_text span').html("("+$('#channelcontainer_Items2 #first_items .items').length+")");
					
					if($('#channelcontainer_Items1 #first_items .items').length > 8){
						$('#channelcontainer_Items1').closest('.ChannelContainer_Items_up').find('.view_all_but').show();
					}
					if($('#channelcontainer_Items2 #first_items .items').length > 8){
						$('#channelcontainer_Items2').closest('.ChannelContainer_Items_up').find('.view_all_but').show();
					}
					
					if($('#channelcontainer_Items1 #first_items .items').length>0){
						$('#channelcontainer_Items1').show();	
					}else{
						$('#channelcontainer_Items1').hide();	
					}
					if($('#channelcontainer_Items2 #first_items .items').length>0){
						$('#channelcontainer_Items2').show();	
					}else{
						$('#channelcontainer_Items2').hide();	
					}
					
				});
			}, 500);
		}	
	});
	
	$(document).on('click',".items_close" ,function(){
		var curob=$(this).parent();
		if($(this).closest('.ChannelContainer_Items_up').find('#first_items .items').length <= 9){
			$(this).closest('.ChannelContainer_Items_up').find('.view_all_but').hide();
		}
		curob.remove();
	});
	// retrive the dates selected in DATE_INFO object
	var arrSelectionSet = new Array(), i=0;
	for (var key in DATE_INFO) 
	{
		arrSelectionSet[i] = key;
		i++;
	}
	// select dates in calendar
	EventsDetailedCal.selection.set(arrSelectionSet);	
}
function getDateEVInfo(date, wantsClassName) {
  	var as_number = Calendar.dateToInt(date);
  
	var myDateArr={};
	if(DATE_INFOInit[as_number]){
		var classArray=new Array();
		for(var i=0;i<DATE_INFOInit[as_number].length;i++){
			var obj=DATE_INFOInit[as_number][i];
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
};