var currentpage=0;
var fr_txt="";
var to_txt="";
var TO_CAL;
var itempressed;

var jscrollpane_apiB=null;
function initscrollPane(){
	$(".scrollpane").jScrollPane();
	jscrollpane_apiB = $(".scrollpane").data('jsp');
}

function addCalTo(cals){
	if(new Date($('#fromtxt').attr('data-cal'))>new Date($('#totxt').attr('data-cal'))){
		$('#totxt').attr('data-cal',$('#fromtxt').attr('data-cal'));
		$('#totxt').html($('#fromtxt').html());
	}
}

function InitDocument(){
	$(".channelevtTime").on("click", function(){
		var $this = $(this).parent();
		if( itempressed == $this ){
			return;
		}
		
		$('#commentDiv .shadow').each(function(index, element) {
			if(!$(this).hasClass('hide')){
				$(this).find('.closeDiv').click();	
			}
		});
		
		$('.disabledmessage').addClass('displaynone');
		$(".meStanDiv").addClass('displaynone');
		$(".notsigned").val('');
		$(".notsigned").blur();
	
		currentpage_like = 0;
		$('.social_data_all').attr("data-page-like",0);
		currentpage_comments = 0;
		$('.social_data_all').attr("data-page-comments",0);
		currentpage_shares = 0;
		$('.social_data_all').attr("data-page-shares",0);
		
		if(itempressed!=""){
			itempressed.find('.butEditChannelRight_data').attr('id','edit_brochure_');
			itempressed.find('.butEditChannelRight_data').addClass('displaynone');
			$('.plupload.html5').remove();
			itempressed.find('.edit_brochuretitle_buts1').click();
		}
		$(".social_data_all").attr('data-id',$this.attr('data-id'));
		$(".itemList").removeClass("selected");
		$this.addClass("selected");
		$this.find('.butEditChannelRight_data').removeClass('displaynone');
		if(parseInt(is_owner)==1){
			$this.find('.butEditChannelRight_data').attr('id','edit_brochure_'+$this.attr('data-id'));
			InitChannelUploaderHome($this.find('.butEditChannelRight_data').attr('id'),'brochure_content',15,0);
		}
		var pos = $this.offset().top-$this.parent().offset().top;
		if(jscrollpane_apiB!=null){
			jscrollpane_apiB.scrollToY(pos,true);
		}
		if($this.index()>0){
			showScrollBar();
		}else{
			hideScrollBar();
		}
		
		// Get the details for every click of an item.
		initLikes( $(".social_data_all") );
		if(parseInt($this.attr('data-enable'))==1 || parseInt(is_owner)==1){
			$('.btn_enabled').show();
			initComments( $(".social_data_all") );
			initShares( $(".social_data_all") );
		}else{
			$('.btn_enabled').hide();
		}
		
		var data_enable=parseInt($this.attr('data-enable'));
		if(data_enable==0){
			$('.overdatabutenablebrochure').attr('data-status',0);	
			$('.overdatabutenablebrochure .overdatabutntficon').addClass('inactive');	
		}else{
			$('.overdatabutenablebrochure').attr('data-status',1);	
			$('.overdatabutenablebrochure .overdatabutntficon').removeClass('inactive');			
		}
                $('.top_button').removeClass('active');
		itempressed=$this;
	});	
}

function InitCalendar(){
    if( $('#fromtxt').length>0){
	Calendar.setup({
		inputField : "fromtxt",
                noScroll  	 : true,
		trigger    : "frombutcontainer",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());
		    TO_CAL.args.min = date;
		    TO_CAL.redraw();
			$('#fromtxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalTo(this);
			this.hide();
		},
		disabled      : function(date) {
				var d = new Date();
				d.setHours(12,30,0,0);
				return (date > d);
		},
		dateFormat : "%d / %m / %Y"
	});
	TO_CAL=Calendar.setup({
		inputField : "totxt",
                noScroll  	 : true,
		trigger    : "tobutcontainer",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());		   
			$('#totxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalTo(this);
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
}

function hideScrollBar()
{
	/*if($("#brochure_content .itemList").size() <= 7)
	{
		$("#brochure_content .jspVerticalBar").hide();	
	}*/	
}
function showScrollBar(){
	if($("#brochure_content .itemList").size() <= 7)
	{
		$("#brochure_content .jspVerticalBar").show();	
	}	
}

$(document).ready(function(){
	
	InitCalendar();
	InitDocument();
	initscrollPane();
	hideScrollBar();
	initSocialFunctions();
	initReportFunctions($("#report_button"));
        
	$("#searchCalendarbut").click(function(){
		if($('#fromtxt').html()!='' || $('#totxt').html()!=''){
			fr_txt=""+$('#fromtxt').attr('data-cal');
			to_txt=""+$('#totxt').attr('data-cal');
			
			currentpage=0;
			$('.newsList .newsAll .newsAll_inside').html('');
			getBrochuresDataRelated();
		}
	});
	
	$('.newsList .itemList').first().find('.channelevtTime').click();
	
	$(".more").toggle(function(){
		$(this).text("< less").siblings(".teaser").hide().siblings(".complete").show();
		initscrollPane();
	}, function(){
		$(this).text("> more").siblings(".teaser").show().siblings(".complete").hide();
		initscrollPane();
	});
	
	
	$(".loadmorebrochure").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		currentpage++;
		getBrochuresDataRelated();
	});
	
	
	$(document).on('click',".topArrow" ,function(){
		if($('.top_buttons_container').css('display')!="none"){
			$('.top_buttons_container').hide();	
		}else{
			$('.top_buttons_container').show();
		}
	});
	
	$(document).on('click',"#hide_button" ,function(){
		if(itempressed!=""){			
			updatebrochurepagedata("is_visible",0,itempressed.attr('data-id'),itempressed);
		}
	});
	
	$(document).on('click',"#hide_buttonviewer" ,function(){
		if(itempressed!=""){
			itempressed.remove();
			$(".newsAll_inside .itemList").first().find('.channelevtTime').click();
			if($('.loadmorebrochure').css('display')!="none"){
				$('.loadmorebrochure').click();
			}else{
				initscrollPane();
			}
		}
	});
	
	$(document).on('click',"#edit_button" ,function(){
		if(itempressed!=""){
                    $(this).addClass('active');
			getbrochurepagedata(itempressed);
		}
	});
	$(document).on('click',"#remove_button" ,function(){
		if(itempressed!=""){
			removebrochure(itempressed);
		}
	});
	
	$(document).on('click',".edit_brochuretitle_buts1" ,function(){            
                $('.top_button').removeClass('active');
		$(this).parent().parent().hide();
	});
	$(document).on('click',".edit_brochuretitle_buts2" ,function(){
		var $this=$(this).parent().parent();
		var edit_brochuretitle_txt=$this.find('#edit_brochuretitle_txt').val();
		if(!edit_brochuretitle_txt.length){
			return;
		}
		var mydescription = edit_brochuretitle_txt.replace(/\n/g," ");
		var $parent=$this.parent();
		updatebrochurepagedata("name",mydescription,$parent.attr('data-id'),$parent);
	});
	
	$(document).on('click',".hideText" ,function(){
		var $parent=$(this).parent().parent();			
		updatebrochurepagedata("is_visible",1,$parent.attr('data-id'),$parent);
	});
	
	$(document).on('click','.overdatabutenablebrochure',function(){
		var $this=$(this);
		if(String(""+$this.attr('data-status'))=="1"){
			if(itempressed!=""){
				enableSharesComments_brochure(itempressed,0);
			}
			$this.attr('data-status','0');
			$this.find('.overdatabutntficon').addClass('inactive');
		}else{
			if(itempressed!=""){
				enableSharesComments_brochure(itempressed,1);
			}
			$this.attr('data-status','1');
			$this.find('.overdatabutntficon').removeClass('inactive');
		}
	});
});

function getBrochuresDataRelated(){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmorebrochure.php'), {txt_srch_init:txt_srch_init,globchannelid:channelGlobalID(),currentpage:currentpage,frtxt:fr_txt,totxt:to_txt},function(data){
		if(data!=false){
			$('.newsList .newsAll .newsAll_inside').append(data);
			var currPageStatus=$('.newsList .currPageStatus');			
                        var data_value = ""+currPageStatus.attr('data-value');			
                        var data_count = ""+currPageStatus.attr('data-count');
                        if( data_value=="0" ){
                            $(".loadmorebrochure").hide();
                        }else{
                            $(".loadmorebrochure").show();
                        }
                        if( data_count=="0" ){
                            $(".social_data_all").hide();
                            $(".newsverticalline").hide();
                        }else{
                            $(".social_data_all").show();
                            $(".newsverticalline").show();
                        }
                        
			$('#newsContainer .ttl span.channelyellowevtttl').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();
			initscrollPane();
			InitDocument();
		}else{
			$(".loadmorebrochure").hide();	
		}
		
		$('.upload-overlay-loading-fix').hide();
	});
}
function updateImage(str,curname,_type){
	if(_type=="editChannelRight_data10"){
		if(itempressed!=""){
			itempressed.find('.channelevtImg img').remove();
			itempressed.find('.channelevtImg').append(str);
			updatebrochurepagedata("photo",curname,itempressed.attr('data-id'),itempressed);
		}
	}
	closeFancyBox();
}
function updatebrochurepagedata(param1,param2,id,obj){
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_brochure_update.php'),
		data: {globchannelid:channelGlobalID(),str:param1,data:param2,id:id},
		type: 'post',
		success: function(data){
			if(param1=="name"){
				obj.find('.brochureName').html(param2);
				obj.find('.edit_brochuretitle_buts1').click();
			}else if(param1=="is_visible"){
				if(parseInt(param2)==0){
					obj.find('.hideitems').removeClass('displaynone');
					obj.addClass('inactive');
					obj.find('.edit_brochuretitle_buts1').click();
				}else{
					obj.find('.hideitems').addClass('displaynone');
					obj.removeClass('inactive');	
				}
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});	
}
function getbrochurepagedata(curob){
	$('.upload-overlay-loading-fix').show();
	var myid=curob.attr('data-id');
	$.ajax({
		url: ReturnLink('/ajax/info_brochure_title.php'),
		data: {globchannelid:channelGlobalID(),data:myid},
		type: 'post',
		success: function(e){
			if(e){
				curob.find('#edit_brochuretitle_container #edit_brochuretitle_txt').val(e);
				curob.find('#edit_brochuretitle_container').show();
				curob.addClass('inactive');
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});
}
function removebrochure(obj){
	var id=obj.attr('data-id');
	TTAlert({
		msg: t('confirm to remove selected brochure'),
		type: 'action',
		btn1: t('cancel'),
		btn2: t('confirm'),
		btn2Callback: function(data){
			if(data){
				$('.upload-overlay-loading-fix').show();
				$.ajax({
					url: ReturnLink('/ajax/info_brochure_remove.php'),
					data: {globchannelid:channelGlobalID(),id:id},
					type: 'post',
					success: function(e){
						if(e){
							var obj_index=obj.index();
							if(obj_index==0){
								obj_index=1;	
							}else{
								obj_index=obj_index-1;	
							}
							
							var myobj=$(".newsAll_inside .itemList").eq(obj_index);
							obj.remove();
							initscrollPane();
							myobj.find('.channelevtTime').click();
						}
						$('.upload-overlay-loading-fix').hide();
					}
				});	
			}
		}
	});
}
function enableSharesComments_brochure(obj,val){
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_brochure_update.php'),
		data: {globchannelid:channelGlobalID(),str:'enable_share_comment',data:val,id:obj.attr('data-id')},
		type: 'post',
		success: function(data){
			obj.attr('data-enable',val);
			$('.upload-overlay-loading-fix').hide();
		}
	});
}

