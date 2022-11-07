var EventsDetailedCal;
var month_array=new Array();
var month_birth_array=new Array();


$(document).ready(function(){
	setCalendar();
	$(document).on('click','.view_all_but' ,function(){
		var $this = $(this);
		var $parent = $this.closest('.EventContainer_Items_up');
		var $height=$parent.find('#first_items').height() + 17;
		$this.hide();
		$parent.find('.EventContainer_Items').animate({'height':$height+'px'},500,function(){
			$parent.find('.EventContainer_Items').css('height','auto');
		});
	});
	$(document).on('click','.birth_close' ,function(){
		$(this).closest('.birth_container').remove();		
	});
	$(document).on('click','.birth_next' ,function(){
		var $parent = $(this).closest('.birth_container');	
		$parent.find('.birth_items_content').stop();
		
		var oldleft =  Math.floor( ($parent.find('.birth_items_content').offset().left - $parent.find('.birth_items_holder').offset().left )/340);
		var newleft = oldleft*340  -340;
		if( (newleft - 340 + $parent.find('.birth_items_content').width())<10){
			$('.birth_next').addClass('disable');	
		}
		$('.birth_prev').removeClass('disable');
		
		$parent.find('.birth_items_content').animate({'left':newleft+'px'},500);
	});
	$(document).on('click','.birth_prev' ,function(){
		var $parent = $(this).closest('.birth_container');	
		$parent.find('.birth_items_content').stop();
		
		var oldleft =  Math.floor( ($parent.find('.birth_items_content').offset().left - $parent.find('.birth_items_holder').offset().left )/340);
		var newleft = oldleft*340  + 340;
		if( (newleft) > -10){
			newleft = 0;
			$('.birth_prev').addClass('disable');	
		}
		$('.birth_next').removeClass('disable');
		
		$parent.find('.birth_items_content').animate({'left':newleft+'px'},500);
	});	
	$(document).on('click',".textdiv" ,function(){
		$('.linktextarea').hide();
		$('.texttextarea').show();
	});
	
	$(document).on('click',".linkdiv" ,function(){
		$('.texttextarea').hide();
		$('.linktextarea').show();		
	});
              
	$(document).on('click','.birth_pic_calendat22' ,function(){
		var $this = $(this);
		var as_number = $this.attr('data-id');
		var $parent = $this.parent();		
		$('.birth_container').remove();
		if(DATE_USER_BIRTHInit[as_number]){
					
			var str='<div class="birth_container">';
				str +='<div class="birth_close"></div>';
				str +='<div class="birth_icon2"></div>';
				str +='<div class="birth_text"> '+t('birthdays');
					str +='<span class="yellowbold12">('+DATE_USER_BIRTHInit[as_number].length+')</span>';
				str +='</div>';
				if(DATE_USER_BIRTHInit[as_number].length<5){
					str +='<div class="birth_next disable"></div>';
				}else{
					str +='<div class="birth_next"></div>';
				}
				str +='<div class="birth_prev disable"></div>';				
				str +='<div class="birth_items_holder">';				
					str +='<div class="birth_items_content">';
						for(var i=0;i<DATE_USER_BIRTHInit[as_number].length;i++){
							var obj=DATE_USER_BIRTHInit[as_number][i];
							var imageurl=obj.imageurl;
							var title=obj.title;
							var id=obj.id;
							var lnk=obj.lnk;
							str +='<a class="birth_items" data-id="'+id+'" href="'+lnk+'">';
								str +='<img width="56" height="56" src="'+imageurl+'" class="birth_img">';
								str +='<div class="birth_title">'+title+'</div>';
								str +='<div class="birth_edit"></div>';
							str +='</a>';		
						}
					str +='</div>';
				str +='</div>';         
			str +='</div>';
			$('#EventContainer_Calendar').append(str);
			
			$('.birth_container').css('top', ($parent.offset().top + 70 - $('#EventContainer_Calendar').offset().top )+'px' );
			$('.birth_container').css('left', ($parent.offset().left - 15 - $('#EventContainer_Calendar').offset().left )+'px' );
			$('.birth_items_content').css('width', ($('.birth_items_content .birth_items').length * 85)+'px' );
				
		}
	});
});
function setCalendar(){
	// Calendar Setup
	var instance_name=null;
	var instance_clicked=null;
	EventsDetailedCal = Calendar.setup(	{
		
		cont          : "EventContainer_Calendar",
		bottomBar 	 : false,
                noScroll  	 : true,
		selectionType : Calendar.SEL_MULTIPLE,
		disabled: function() { 
			return true; 
		},
		dateInfo : getDateEVInfo,
		onChange : function(cal, date, anim) 
		{
			// set time to do js hover after rendering the calendar
			if(instance_name!=null){
				clearTimeout(instance_name);
			}
			
			
			instance_name=setTimeout(function()
			{
				clearTimeout(instance_name);
				
				
				for(var i=0; i<month_birth_array.length; i++){
					if(parseInt(month_birth_array[i])!=0){
						var coll=( (i+1)%7) - 1;
						var row = Math.floor( ((i+1)/7) );
						if(coll<0){
							coll = 6;
							row = row -1;
						}
						if(row<0){
							row =0;	
						}
						
						var row_item = $('.DynarchCalendar-bodyTable tr').eq( row );
						var select_item = row_item.find('td').eq(coll);
						select_item.addClass('birth');
						select_item.append('<a rel="nofollow" href="#sharepopup" class="birth_pic_calendat" data-id="'+parseInt(month_birth_array[i])+'"></a>');
					}
				}
				month_array=new Array();
				month_birth_array=new Array();
				
				$(".birth_pic_calendat").each(function(){
                                    var $this = $(this);
                                    var as_number = $this.attr('data-id');
                                    var $parent = $this.parent();
                                    $this.fancybox({
                                        padding: 0,
                                        margin: 0,
                                        beforeLoad: function () {
                                            $('#sharepopup').html('');
                                            var str='<div class="birth_container">';
                                                str +='<div class="birth_icon2"></div>';
                                                str +='<div class="birth_text"> '+t('birthdays');
                                                        str +='<span class="yellowbold12">('+DATE_USER_BIRTHInit[as_number].length+')</span>';
                                                str +='</div>';
                                                if(DATE_USER_BIRTHInit[as_number].length<5){
                                                        str +='<div class="birth_next disable"></div>';
                                                }else{
                                                        str +='<div class="birth_next"></div>';
                                                }
                                                str +='<div class="birth_prev disable"></div>';				
                                                str +='<div class="birth_items_holder">';				
                                                        str +='<div class="birth_items_content">';
                                                                for(var i=0;i<DATE_USER_BIRTHInit[as_number].length;i++){
                                                                        var obj=DATE_USER_BIRTHInit[as_number][i];
                                                                        var imageurl=obj.imageurl;
                                                                        var title=obj.title;
                                                                        var id=obj.id;
                                                                        var lnk=obj.lnk;
                                                                        str +='<a class="birth_items" data-id="'+id+'" href="'+lnk+'">';
                                                                                str +='<img width="56" height="56" src="'+imageurl+'" class="birth_img">';
                                                                                str +='<div class="birth_title">'+title+'</div>';
                                                                                str +='<div class="birth_edit"></div>';
                                                                        str +='</a>';		
                                                                }
                                                        str +='</div>';
                                                str +='</div>';         
                                        str +='</div>';
                                            $('#sharepopup').html(str);                
                                    }});
                                });  
				/*$(".DynarchCalendar-day-selected.highlight_user, .DynarchCalendar-day-selected.highlight_user2").each(function(index, element) {
					$(this).removeAttr('disabled');
					$(this).attr('readOnly', 'readOnly');
					$(this).removeAttr("disabled");
        			$(this).attr("unselectable", "on");
				});*/
				$(".DynarchCalendar-day-selected.highlight_user, .DynarchCalendar-day-selected.highlight_user2").parent().click(function(){
					if($(this).find('.DynarchCalendar-day-selected').hasClass('active')){
						//return;
					}
					if(instance_clicked!=null){
						instance_clicked.removeClass('active');
					}
					$('.view_all_but').hide();
					$('.EventContainer_Items').css('height','157px');
					
					instance_clicked=$(this).find('.DynarchCalendar-day-selected');
					$(this).find('.DynarchCalendar-day-selected').addClass('active');
					// get the date from div attribute
					var DateIndex = $(this).find('.DynarchCalendar-day-selected').attr('dyc-date');
					
					var first_items="";
					var second_items="";
					for(var i=0;i<DATE_USER_INFOInit[DateIndex].length;i++){
						var currdata=DATE_USER_INFOInit[DateIndex][i];
						var link_a=ReturnLink('/events-detailed/'+currdata['id']);
						
						if(parseInt(currdata['_type'])==1){
							first_items+='<div class="items"><a class="div_class" href="'+link_a+'"><img class="items_img" width="86" height="56" src="'+currdata['imageurl']+'"><div class="items_title">'+currdata['title']+'</div><div class="items_event yellowbold11">'+t("view event")+'</div></a><img class="items_close" width="18" height="18" src="'+ReturnLink('/images/eventsdetailed/close-tooltip1.jpg')+'"></div>';	
						}else if(parseInt(currdata['_type'])==2){
							second_items+='<div class="items"><a class="div_class" href="'+link_a+'"><img class="items_img" width="86" height="56" src="'+currdata['imageurl']+'"><div class="items_title">'+currdata['title']+'</div><div class="items_event yellowbold11">'+t("view event")+'</div></a><img class="items_close" width="18" height="18" src="'+ReturnLink('/images/eventsdetailed/close-tooltip1.jpg')+'"></div>';							
						}
					}
					$('#eventcontainer_Items1 #first_items').html(first_items);
					$('#eventcontainer_Items1 .div_class_text span').html("("+$('#eventcontainer_Items1 #first_items .items').length+")");
					$('#eventcontainer_Items2 #first_items').html(second_items);
					$('#eventcontainer_Items2 .div_class_text span').html("("+$('#eventcontainer_Items2 #first_items .items').length+")");
					
					if($('#eventcontainer_Items1 #first_items .items').length > 8){
						$('#eventcontainer_Items1').closest('.EventContainer_Items_up').find('.view_all_but').show();
					}
					if($('#eventcontainer_Items2 #first_items .items').length > 8){
						$('#eventcontainer_Items2').closest('.EventContainer_Items_up').find('.view_all_but').show();
					}
					
					if($('#eventcontainer_Items1 #first_items .items').length>0){
						$('#eventcontainer_Items1').show();	
					}else{
						$('#eventcontainer_Items1').hide();	
					}
					if($('#eventcontainer_Items2 #first_items .items').length>0){
						$('#eventcontainer_Items2').show();	
					}else{
						$('#eventcontainer_Items2').hide();	
					}
					
				});
			}, 500);
		}	
	});
	
	$(document).on('click',".items_close" ,function(){
		var curob=$(this).parent();
		if($(this).closest('.EventContainer_Items_up').find('#first_items .items').length <= 9){
			$(this).closest('.EventContainer_Items_up').find('.view_all_but').hide();
		}
		curob.remove();
	});
	// retrive the dates selected in DATE_USER_INFO object
	var arrSelectionSet = new Array(), i=0;
	for (var key in DATE_USER_INFO) 
	{
		arrSelectionSet[i] = key;
		i++;
	}
	// select dates in calendar
	EventsDetailedCal.selection.set(arrSelectionSet);	
}
function getDateEVInfo(date, wantsClassName) {
  	var as_number = Calendar.dateToInt(date);
  	var as_birth = '1'+Calendar.printDate(date, "%m-%d");
  	var as_birth_arr = String(as_birth).split('-');
  	as_birth = as_birth_arr[0]+""+as_birth_arr[1];
	$('.birth_container').remove();
	
	if(DATE_USER_BIRTHInit[as_birth]){
		if( !inArray(as_number, month_array ) ) {
			month_birth_array.push( parseInt( as_birth ) );
		}
	}else {
		if( !inArray(as_number, month_array ) ) {
			month_birth_array.push(0);	
		}
	}
	month_array.push(as_number);	
	
	var myDateArr={};
	
	if(DATE_USER_INFOInit[as_number]){
		var classArray=new Array();
		for(var i=0;i<DATE_USER_INFOInit[as_number].length;i++){
			var obj=DATE_USER_INFOInit[as_number][i];
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
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
function updateImage_Add_Posts(curname,filetype){	
	closeFancyBox();
}
function getObjectData(obj){
	var mystr=""+obj.val();
	if(mystr==obj.attr('data-value')){
		mystr="";	
	}
	return mystr;
}