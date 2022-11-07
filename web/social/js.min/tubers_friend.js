// JavaScript Document
var previousTuber = $(".one-tuber");
var t;
$(document).ready(function () {

	$(".one-tuber").mouseover(function (evt) {
			clearInterval(t);
			$('.darkTransparent').show();
			$(this).find('.darkTransparent').hide();	
			previousTuber = $(this);
			 
	  });
	
	$(".one-tuber").mouseout(function () {
		t = setTimeout(function () {$('.darkTransparent').hide();},2000);							
	});
	
	
	$('div.TubersAction').on('click','span',function(ev){
		var which = $(this).attr('class');
		var curbut = $(this);
		
		switch(which){
			case 'accept_frnd':
				dotubactionfn(curbut);
				break;
			case 'rjct_frnd':
				/*if( !confirm(t'Are you sure you want to reject this friend request?')) ){
					return false;
				}*/
				TTAlert({
					msg: t('Are you sure you want to reject this friend request?'),
					type: 'action',
					btn1: t('cancel'),
					btn2: t('confirm'),
					btn2Callback: function(data){
						if(data){							
							dotubactionfn(curbut);
						}else{
							return false;
						}
					}
				});
				
				break;
			case 'block_frnd':
				/*if( !confirm(t('Are you sure you want to block this friend?')) ){
					return false;
				}*/
				
				TTAlert({
					msg: t('Are you sure you want to block this friend?'),
					type: 'action',
					btn1: t('cancel'),
					btn2: t('confirm'),
					btn2Callback: function(data){
						if(data){							
							dotubactionfn(curbut);
						}else{
							return false;
						}
					}
				});
				
				break;
			case 'unblock_frnd':
				dotubactionfn(curbut);
				break;
		}	
		
	});
	
});
function dotubactionfn(obj){
	$('.chat-overlay-loading-fix').show();
	var which = obj.attr('class');
	$.ajax({
		url: ReturnLink('/ajax/profile_friend.php'),
		data: {fid : $(obj).attr('data-fid'), op : which},
		type: 'post',
		success: function(data){
			var ret = null;
			try{
				ret = $.parseJSON(data);
			}catch(Ex){
                                $('.chat-overlay-loading-fix').hide();
				return ;
			}
			
			if(!ret){
                                $('.chat-overlay-loading-fix').hide();
				TTAlert({
					msg: t("Couldn't process. please try again later"),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}

			if(ret.status == 'ok'){
				location.reload();
			}else{
				TTAlert({
					msg: ret.msg,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
                        $('.chat-overlay-loading-fix').hide();
		}
	});
}
function HideActions(){
	$('div.TubersAction').hide();
}

function ShowActions(){
	$('div.TubersAction').show();
}

function initBubble()
{
	$('#contentcontainer .one-tuber img').CreateBubblePopup({
		position : 'left',
		innerHtmlStyle: {
							'background-color' :'#ffffff',
							'color':'#686868',
							'font-family': 'Arial, Helvetica, sans-serif',
							'font-size': '18px', 
							'text-align':'left',
							'width' : '244px',
							'font-family':'Arial',
							'font-size':'12px'
						},
		themeName: 	'black',
		themePath: AbsolutePath + '/images/jquerybubblepopup-theme',
		alwaysVisible: true,
		closingDelay: 0
	});
	$('#contentcontainer .one-tuber img').each(function(){
		
		var insideBubble = $(this).attr('rel');
		var nvideo = $(this).attr('data-nvideo');
		var nphoto = $(this).attr('data-nphoto');
		var nviews = $(this).attr('data-nviews');
		insideBubble += "<br/><br/><table style='width: 300px;'><tr class='yellowinside'><td>"+nvideo+"</td><td>"+nphoto+"</td><td>"+nviews+"</td></tr><tr><td>"+t('Videos')+"</td><td>"+t('Photos')+"</td><td>"+t('Views')+"</td></tr></table>";
												 
		$(this).SetBubblePopupInnerHtml(insideBubble);
	});
}