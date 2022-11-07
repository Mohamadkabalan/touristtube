var currentpage=0;
var fr_txt="";
var to_txt="";
var TO_CAL;

function retieveNumber( Num ){
	var Num = ''+Num;
	ListNum = Num.split('.');
	if(ListNum[1]) {
		NumLast = parseInt(Num) + 1;
	}else{
		NumLast = parseInt(Num);
	}
	return NumLast;
}

function addCalTo(cals){
	if(new Date($('#fromtxt').attr('data-cal'))>new Date($('#totxt').attr('data-cal'))){
		$('#totxt').attr('data-cal',$('#fromtxt').attr('data-cal'));
		$('#totxt').html($('#fromtxt').html());
	}
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

var jscrollpane_apiN;
function initscrollPane(){
	$(".scrollpane").jScrollPane();
	jscrollpane_apiN = $(".scrollpane").data('jsp');
	//jscrollpane_apiN.scrollToY(pos,true);
}
function hideScrollBar()
{
	/*if($("#news_content .itemList").size() <= 7)
	{
		$("#news_content .jspVerticalBar").hide();	
	}*/	
}
function showScrollBar(){
	if($("#news_content .itemList").size() <= 7)
	{
		$("#news_content .jspVerticalBar").show();	
	}	
}
function InitMore(){
	$(".more").toggle(function(){
		$(this).text("< less").siblings(".teaser").hide().siblings(".complete").show();
		initscrollPane();
	}, function(){
		$(this).text("> more").siblings(".teaser").show().siblings(".complete").hide();
		initscrollPane();
	});	
}
$(document).ready(function(){
	
	InitCalendar();
	InitMore();
	
	initscrollPane();
	hideScrollBar();
	initSocialFunctions();
	initReportFunctions($("#report_button"));
	
	$("#searchCalendarbut").click(function(){
		if($('#fromtxt').html()!='' || $('#totxt').html()!=''){
			fr_txt=""+$('#fromtxt').attr('data-cal');
			to_txt=""+$('#totxt').attr('data-cal');
			
			currentpage=0;
			itempressed="";
			$('.newsList .newsAll .newsAll_inside').html('');
			getNewsDataRelated();
		}
	});
	$(document).on('click',"#hide_buttonviewer" ,function(){
		if(itempressed!=""){
			itempressed.remove();
			$(".newsAll_inside .itemList").first().find('.newsDescription').click();
			if($('.loadmorenews').css('display')!="none"){
				$('.loadmorenews').click();
			}else{
				initscrollPane();
			}
		}
	});
	$(document).on('click','.overdatabutenablenews',function(){
		var $this=$(this);
		if(String(""+$this.attr('data-status'))=="1"){
			if(itempressed!=""){
				updatenewspagedata("enable_share_comment",0,itempressed.attr('data-id'),itempressed);
			}
			$this.attr('data-status','0');
			$this.find('.overdatabutntficon').addClass('inactive');
		}else{
			if(itempressed!=""){
				updatenewspagedata("enable_share_comment",1,itempressed.attr('data-id'),itempressed);
			}
			$this.attr('data-status','1');
			$this.find('.overdatabutntficon').removeClass('inactive');
		}
	});
	
	$(document).on('click',"#hide_button" ,function(){
		if(itempressed!=""){			
			updatenewspagedata("is_visible",0,itempressed.attr('data-id'),itempressed);
		}
	});
	$(document).on('click',".hideText" ,function(){
		var $parent=$(this).closest('.itemList');			
		updatenewspagedata("is_visible",1,$parent.attr('data-id'),$parent);
	});
	$(document).on('click',"#remove_button" ,function(){
		if(itempressed!=""){
			removenews(itempressed);
		}
	});
	
	$(document).on('click',".itemList .newsDescription" ,function(){	
		var $this = $(this).parent();
		if($this.hasClass('selected')){
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
		
		$(".itemList").removeClass("selected");
		$this.addClass("selected");
		
		var pos = $this.offset().top-$this.parent().offset().top;
		if(jscrollpane_apiN!=null){
			jscrollpane_apiN.scrollToY(pos,true);
		}
		if($this.index()>0){
			showScrollBar();
		}else{
			hideScrollBar();
		}
		if(itempressed!=""){			
			itempressed.find('.edit_newstitle_buts1').click();
		}
		$(".social_data_all").attr('data-id',$this.attr('data-id'));
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
			$('.overdatabutenablenews').attr('data-status',0);	
			$('.overdatabutenablenews .overdatabutntficon').addClass('inactive');	
		}else{
			$('.overdatabutenablenews').attr('data-status',1);	
			$('.overdatabutenablenews .overdatabutntficon').removeClass('inactive');			
		}
		itempressed=$this;
	});
	$('.newsList .itemList').first().find('.newsDescription').click();	
	
	$(document).on('click',".topArrow" ,function(){
		if($('.top_buttons_container').css('display')!="none"){
			$('.top_buttons_container').hide();	
		}else{
			$('.top_buttons_container').show();
		}
	});
	$(document).on('click',".loadmorenews" ,function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		currentpage++;
		getNewsDataRelated();
	});
	$(document).on('click',"#edit_button" ,function(){
		if(itempressed!=""){
                    $(this).addClass('active');
			if(itempressed.find('.edit_newstitle_container').length>0){
				itempressed.find('.edit_newstitle_container').remove();
			}
			getNewsPageData(itempressed);
		}
	});
	$(document).on('click',".edit_newstitle_buts1" ,function(){
		$(this).parent().parent().remove();
                $('.top_button').removeClass('active');
	});
	$(document).on('click',".edit_newstitle_buts2" ,function(){
		var $this=$(this).parent().parent();
		var mydescription=$this.find('#edit_newstitle_txt').val();
		if(!mydescription.length){
			return;
		}
		var $parent=$this.parent();
		updatenewspagedata("description",mydescription,$parent.attr('data-id'),$parent);
	});
});
function updatenewspagedata(param1,param2,id,obj){
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_news_update.php'),
		data: {globchannelid:channelGlobalID(),str:param1,data:param2,id:id},
		type: 'post',
		success: function(data){
			var ret = null;
			try{
				ret = $.parseJSON(data);
			}catch(Ex){
				return ;
			}
			if(ret.status == 'ok'){
				if(param1=="description"){
					var str =(""+ret.news_desc_small);
					str = str.replace(/\\n|\n/g, '<br />');
					obj.find('.newsDescription .teaser').html(str);
					
					var mydescriptionbig =ret.news_desc;
					mydescriptionbig = mydescriptionbig.replace(/\\n|\n/g, '<br />');
					obj.find('.newsDescription .complete').html(mydescriptionbig);
					
					if(mydescriptionbig.length>str.length && obj.find('.newsDescription .more').length==0){
						obj.find('.newsDescription').append('<span class="more">&gt; more</span>');
						InitMore();
					}else if(mydescriptionbig.length==str.length){
						obj.find('.newsDescription .more').remove();
					}
					
					obj.find('.edit_newstitle_buts1').click();
				}else if(param1=="enable_share_comment"){
					obj.attr('data-enable',param2);
				}else if(param1=="is_visible"){
					if(parseInt(param2)==0){
						obj.find('.hideitems').removeClass('displaynone');
						obj.find('.newsDescriptionInside').addClass('displaynone');
						obj.addClass('inactive');
						obj.find('.edit_newstitle_buts1').click();
					}else{
						obj.find('.hideitems').addClass('displaynone');
						obj.find('.newsDescriptionInside').removeClass('displaynone');
						obj.removeClass('inactive');	
					}
				}
			}			
			$('.upload-overlay-loading-fix').hide();
		}
	});	
}
function getNewsPageData(obj){
	$('.upload-overlay-loading-fix').show();
	var myid=obj.attr('data-id');
	$.ajax({
		url: ReturnLink('/ajax/info_news_description.php'),
		data: {globchannelid:channelGlobalID(),data:myid,textHeight:obj.find('.newsDescription').height()-8},
		type: 'post',
		success: function(e){
			if(e){
				obj.append(e);
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});
}
function getNewsDataRelated(){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_loadmorenews.php'), {txt_srch_init:txt_srch_init,globchannelid:channelGlobalID(),currentpage:currentpage,frtxt:fr_txt,totxt:to_txt},function(data){
		if(data!=false){
                    $('.newsList .newsAll .newsAll_inside').append(data);
                    var currPageStatus=$('.newsList .currPageStatus');
                    var data_value = ""+currPageStatus.attr('data-value');			
                    var data_count = ""+currPageStatus.attr('data-count');
                    if( data_value=="0" ){
                        $(".loadmorenews").hide();
                    }else{
                        $(".loadmorenews").show();
                    }
                    if( data_count=="0" ){
                        $(".social_data_all").hide();
                        $(".newsverticalline").hide();
                    }else{
                        $(".social_data_all").show();
                        $(".newsverticalline").show();
                    }
                    $('#newsContainer .ttl span.channelyellowevtttl').html('('+currPageStatus.attr('data-count')+')');
                    if( data_count!="0" ){
                        if(itempressed==""){
                            $('.newsList .itemList').first().find('.newsDescription').click();
                        }
                    }
                    currPageStatus.remove();
                    initscrollPane();
		}else{
			$(".loadmorenews").hide();	
		}
		
		$('.upload-overlay-loading-fix').hide();
	});
}

function removenews(obj){
	var id=obj.attr('data-id');
	TTAlert({
		msg: t('confirm to remove selected news'),
		type: 'action',
		btn1: t('cancel'),
		btn2: t('confirm'),
		btn2Callback: function(data){
			if(data){
				$('.upload-overlay-loading-fix').show();
				$.ajax({
					url: ReturnLink('/ajax/info_page_news_delete_manage_channel.php'),
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
							myobj.find('.newsDescription').click();
						}
						$('.upload-overlay-loading-fix').hide();
					}
				});	
			}
		}
	});
}