function centerOn(which){
	var n_thumbs = $('#ThumbViewer li').length;
	var max_left = -1 * n_thumbs * single_thumb_width + 4*single_thumb_width;
	
	var new_left = -1 * (which - 2) * single_thumb_width;
	if( new_left < max_left ) new_left = max_left;
	if( new_left > min_left ) new_left = min_left;
	$('#ThumbViewer ul').stop().animate({
		left: new_left
	},500);
}

function LoadNext(){
	if( GettingNext || NoMoreNext) return ;
        NextPage = NextPage + 1;
	GettingNext = true;
	
	var $lastPic = $('#ThumbViewer ul li:last img');
	var lastID = $lastPic.attr('rel');
	$.ajax({
		url: ReturnLink('/ajax/video_viewer.php'),
		data: {vid: ORIGINAL_PHOTO_ID, min_id : lastID , page : NextPage , skip: SkipNext},
		type: 'post',
		success: function(data){
			$('body').css('cursor','');
			$('#ThumbNext').css('cursor','');
			var ret = null;
			try{
				ret = $.parseJSON( data );
			}catch(ex){

			}
			if(!ret) return;
			if( ret.length == 0 ){
				GettingNext = false;
				NoMoreNext = true;
				return ;
			}

			$.each(ret['media'],function(i,v){				
                                var $newli = $('<li></li>');
				$newli.attr({'data-id':v.id});
				var $newa = $('<a class="ThumbViewer_a"></a>');
				$newa.attr({'href':v.media_uri}).appendTo($newli);
                                var $newImage = $('<div class="enlarge '+v.enlarge_class+'"></div>');
                                $newImage.appendTo($newa);
				var $newimg = $('<img/>');
				$newimg.attr({'data-title':v.title, 'alt':v.title,'rel':v.id,'url':v.video_url, width:'136' ,height:'76' ,'comments':v.comment_pages,'src': v.thumb_image}).addClass('ThumbViewerImage').appendTo($newa);
				$newli.append('<span></span>');
				//$newli.css({'margin-left': '2px', 'margin-right': '2px'});
				$newli.appendTo($('#ThumbViewer ul'));
			});
                        if( parseInt(ret['total']) <= (NextPage+1)*5 ){
                            GettingNext = false;
                            $('#ThumbNext').addClass('inactive');
                            NoMoreNext = true;
                        }
			$("#ThumbViewer li").preloader();
                        var n_thumbs = $('#ThumbViewer li').length;
                        var cur_left = $('#ThumbViewer ul').offset().left - $('#ThumbViewer').offset().left;		
                        var new_left = cur_left - single_thumb_width*5;
                        if( (new_left%single_thumb_width)!=0 ){
                            new_left = new_left + Math.abs(new_left%single_thumb_width);				
                        }
                        $('#ThumbViewer ul').stop(false,true).css({position : 'relative'}).animate({
                                left: new_left
                        },500);
                        
                        $('#ThumbViewer ul').css('width',n_thumbs*single_thumb_width+'px');
			GettingNext = false;
			single_thumb_width =140;
		}
	});
}	
$(document).ready(function(){

	single_thumb_width = 140;//$('#ThumbViewer li:first').outerWidth( true ) ;
	
	$("#ThumbViewer li").preloader();
	
	centerOn(DEFAULT_VIDEO_NUMBER);
	
	$('#ThumbNext').click(function(){
		var n_thumbs = $('#ThumbViewer li').length;
		var cur_left = $('#ThumbViewer ul').offset().left - $('#ThumbViewer').offset().left;
		if( n_thumbs < 5 || $(this).hasClass('inactive')) return;
		var max_left = -1 * n_thumbs * single_thumb_width + 5*single_thumb_width;		
		var new_left = cur_left - single_thumb_width*5;
		if( new_left <= max_left && !NoMoreNext ){
			new_left = max_left;
			LoadNext();			
		}
		$('#ThumbPrev').removeClass('inactive');
		if( (new_left%single_thumb_width)!=0 ){
                    new_left = new_left + Math.abs(new_left%single_thumb_width);				
		}
		if(NoMoreNext && (new_left-(5*single_thumb_width) + ( $('#ThumbViewer ul li').size()*single_thumb_width ) )<=0){
			$('#ThumbNext').addClass('inactive');
		}
		
		$('#ThumbViewer ul').stop(false,true).css({position : 'relative'}).animate({
			left: new_left
		},500);
	});			
	$('#ThumbPrev').click(function(){
		var n_thumbs = $('#ThumbViewer li').length;
		var cur_left = $('#ThumbViewer ul').offset().left - $('#ThumbViewer').offset().left;
		if(cur_left>=0){
                    cur_left = 0;
                    $(this).addClass('inactive');
		}
		if( $(this).hasClass('inactive') ) return;
		
		var new_left = cur_left + single_thumb_width*5;
		
		$('#ThumbNext').removeClass('inactive');
            	if( (new_left%single_thumb_width)!=0 ){
                    new_left = new_left + Math.abs(new_left%single_thumb_width);	
		}
		if(new_left>=0){
                    new_left = 0;
                    $(this).addClass('inactive');
		}
		
		$('#ThumbViewer ul').width($('#ThumbViewer ul li').size()*single_thumb_width);
		$('#ThumbViewer ul').stop(false,true).css({position : 'relative'}).animate({
			left: new_left
		},500);
	});

	$('#ThumbViewer').on('click','img.ThumbViewerImage',function(){
		var url = $(this).attr('url');
		window.location = ReturnLink('/video/' + url);
	});
});
