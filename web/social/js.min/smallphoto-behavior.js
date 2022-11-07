var $outer_container;
var $preloader;

var $bg_full_img;
var $nextImageBtn;
var $prevImageBtn;
var full_currentpage = 0;
var GettingNext = false;
var GettingPrev = false;
var NoMorePrev = false;
var NoMoreNext = false;
var NextPage = 0;
var PrevPage = 0;
var single_thumb_width;
var min_left = 0;
var slideInterval = 0;
var SkipNext = 0;
var SkipPrev = 0;
var ORIGINAL_PHOTO_ID;



function centerOn(which){
	var n_thumbs = $('#ThumbViewer li').length;
	var max_left = -1 * n_thumbs * single_thumb_width + 5*single_thumb_width;
	
	var new_left = -1 * (which - 2) * single_thumb_width;
	if( new_left < max_left ) new_left = max_left;
	if( new_left > min_left ) new_left = min_left;
	$('#ThumbViewer ul').stop().animate({
		left: new_left
	},500);
}

function LoadNext(){
	if( GettingNext || NoMoreNext) return ;
	GettingNext = true;
	NextPage = NextPage + 1;
	
	var $lastPic = $('#ThumbViewer ul li').last();
	var lastID = $lastPic.attr('data-id');
	$.ajax({
		url: ReturnLink('/ajax/photo_viewer.php'),
		data: {vid: ORIGINAL_PHOTO_ID, min_id : lastID , page : NextPage, skip : SkipNext },
		type: 'post',
		success: function(data){
			
			var ret = null;
			try{
				ret = $.parseJSON( data );
			}catch(ex){

			}
			
			if(!ret) return;
			
			if( ret.length == 0 ){
				GettingNext = false;
				$('#ThumbNext').addClass('inactive');
				NoMoreNext = true;
				return ;
			}
			$('#ThumbNext').removeClass('inactive');
			
			
			$.each(ret['media'],function(i,v){
				//if( $('#ThumbViewer img[rel='+v.id+']').length != 0 ){ return ; }
				var $newli = $('<li class="ThumbViewerListItem"></li>');
				$newli.attr({'data-id':v.id});
				var $newa = $('<a class="ThumbViewer_a"></a>');
				$newa.attr({'href':v.media_uri}).appendTo($newli);
                                var $newImage = $('<div class="enlarge '+v.enlarge_class+'"></div>');
                                $newImage.appendTo($newa);
				var $newimg = $('<img/>');
				$newimg.attr({'data-title':v.title, 'alt':v.title,'data-rel':v.id,'url':v.url ,'comments':v.comment_pages, width:'136' ,height:'76','src': decodeURL(v.thumb_image)}).addClass('ThumbViewerImage').appendTo($newa);
				$newli.append('<span></span>');
				//$newli.css({'margin-left': '2px', 'margin-right': '2px'});
				$newli.appendTo($('#ThumbViewer ul'));
                                
				var $newImage = $('<img/>');
				$newImage.attr({'data-title':v.title , 'alt': v.title , 'data-rel': v.id , 'src': decodeURL(v.big_image) }).css({display: 'none', width: v.width, height: v.height});
				var $newLink = $('<a></a>').attr({title : v.title, href: decodeURL(v.big_image), 'data-rel': 'gallery'});
				$newImage.appendTo($newLink);
				//$newLink.appendTo($('#BigImageHolder'));
				
				
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
			

			
			single_thumb_width =140;// Math.max( $('#ThumbViewer li:first').outerWidth( true ) , $('#ThumbViewer li:last').outerWidth( true ) ) + 3;
		}
	});
}					
$(document).ready(function(){
        $outer_container=$("#bg_full");
        $preloader=$("#preloader");
	single_thumb_width = 140;
	ORIGINAL_PHOTO_ID = $('#BigImageHolder').attr('data-id');
	//centerOn(DEFAULT_PHOTO_NUMBER);

	$("#ThumbViewer li").preloader();
        
     $bg_full_img=$("#bg_full_img");
	$nextImageBtn=$(".nextImageBtn");
	$prevImageBtn=$(".prevImageBtn");
	
	sliderWidth=$outer_container.width();
	var totalContent=0;
	fadeSpeed=200;

	//on window resize scale image and reset thumbnail scroller
	$(window).resize(function() {
		FullScreenBackground("#bg_full_img",$bg_full_img.data("newImageW"),$bg_full_img.data("newImageH"));
		var newWidth=$outer_container.width();
		sliderWidth=newWidth;
	});
	
	//next/prev images buttons
	$nextImageBtn.click(function(event){
		event.preventDefault();
                full_currentpage++;
		var $this=$(this);
		if($this.hasClass('inactive')) return;
		SwitchImage($outer_container.data("nextImage"));
		$bg_full_img.attr('title',$this.attr("data-title"));
		$('.header_txt').html($this.attr("data-title"));
                $('#favbutton').attr('data-id',$this.attr('data-id') );
		GetNextPrevImages(ORIGINAL_PHOTO_ID);
	});
	
	$prevImageBtn.click(function(event){
		event.preventDefault();
                full_currentpage--;
                if(full_currentpage<0 && parseInt($('.glob_container').attr('data-album'))==0) full_currentpage=0;
		var $this=$(this);
		if($this.hasClass('inactive')) return;
		SwitchImage($outer_container.data("prevImage"));
		$bg_full_img.attr('title',$this.attr("data-title"));
		$('.header_txt').html($this.attr("data-title"));
		GetNextPrevImages(ORIGINAL_PHOTO_ID);
	});
	
	
	/* */
	
	$('#favbutton').click(function(){
            var $button = $(this);		
            TTCallAPI({
                what: 'social/favorite',
                data: {entity_id : $button.attr('data-id'), entity_type : SOCIAL_ENTITY_MEDIA, channel_id: channelGlobalID() },
                callback: function(data){
                    if(data.status == 'ok'){
                        if( data.favorite == 0 ) {
                            $button.removeClass('active');
                            $button.attr('data-title', t('add to favorites') );
                        }else {
                            $button.addClass('active');
                            $button.attr('data-title', t('remove from favorites') );
                        }
                        $('#bg_full .mediabuttonsOver .ProfileHeaderOverin').html($button.attr('data-title'));
                        if( $('.glob_container').attr('data-id')==$button.attr('data-id') ){
                            if( data.favorite == 0 ) {
                                $('#favorite').removeClass('active');
                                $('#favorite').attr('data-title', t('add to favorites') );
                            }else {
                                $('#favorite').addClass('active');
                                $('#favorite').attr('data-title', t('remove from favorites') );
                            }
                            
                            $('#media_buttons .mediabuttonsOver .ProfileHeaderOverin').html($('#favorite').attr('data-title'));
                        }
                    }
                }
            });
	});
	$(document).on('mouseover','#fsbutton, #favbutton, #shlkbutton',function(){
            if( !$('#bg_full .mediabuttonsOver').hasClass('inactive') ){
		var posxx=$(this).offset().left-$(this).parent().offset().left-251;
		$('#bg_full .mediabuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
		$('#bg_full .mediabuttonsOver').css('left',posxx+'px');
		$('#bg_full .mediabuttonsOver').css('top','-18px');
		$('#bg_full .mediabuttonsOver').stop().show();
            }else{
                $('#bg_full .mediabuttonsOver').hide();
            }
	});
	$(document).on('mouseout','#fsbutton, #favbutton, #shlkbutton',function(){
            $('#bg_full .mediabuttonsOver').hide();
	});
        $(document).on('click',"#shlkbutton" ,function(){
            if($('.share_link_holder.fullmode').css('display')!="none"){
                $('.share_link_holder.fullmode').hide();
                $('#bg_full .mediabuttonsOver').removeClass('inactive');
                $(this).removeClass('active');
            }else{
                $('#bg_full .mediabuttonsOver').addClass('inactive');
                $(this).addClass('active');
                $('.share_link_holder.fullmode').show();
                $('#bg_full .mediabuttonsOver').hide();
            }
	});
	//next/prev images keyboard arrows
	$(document).keydown(function(ev) {
            if( $(".glob_container").css('display')!='none' ){               
		if(ev.keyCode == 39) { //right arrow
			$nextImageBtn.click();
			return false; // don't execute the default action (scrolling or whatever)
		} else if(ev.keyCode == 37) { //left arrow
			$prevImageBtn.click();
			return false; // don't execute the default action (scrolling or whatever)
		}
                
                
            }
	});
	$(document).on('click',".fulls_div" ,function(){
		$('.glob_container').hide();
		$(document).fullScreen(false);
                $('body').removeClass('ofh');
	});
    $(document).bind("fullscreenchange", function(e) {
       if(!$(document).fullScreen()){
		   $('.glob_container').hide();
                   $('body').removeClass('ofh');
	   }
    });
    
    $(document).on('click',"#fullscreen_pic" ,function(){
            $(document).fullScreen(true);
            setNewImageData();
            $('.glob_container').show();
            $('body').addClass('ofh');
    });
	
	
	//  Add for double tab on image in mobile 
/*------------------  27 March  -----------------------------------------------------------------*/
		
	 var wid =  jQuery(window).width();   /*------------------  30 March  ---------------------------*/
      if(wid<768)
	  {
		  
		  $(document).on('click',"#BigImageHolder, #fullscreen_pic" ,function(){		   
			 $(document).fullScreen(true);
				setNewImageData();
				$('.glob_container').show();
		
			/*---------------- new add ----------------*/
		     if(!$('.mediabuttonsOver').hasClass('active') )
				{
				  $('.mediabuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
				  $('.mediabuttonsOver').hide();
				}
			 /*-------------------------------*/
		  });
			
		   /*------------------  8 june 2015  ---------------------------*/		   
	      $(window).resize(function() {
			//$(".mediabuttonsOver").css({"left":"auto !important","right":"20px !important"});
				$(".mediabuttonsOver").css("left","auto !important");
				$(".mediabuttonsOver").css("right","20px !important");
		  });		
	   	   
	   /* photo swiper*/
	  	 
      $(function() {
			  if ($.isFunction('swipe')) {
				  alert("hi");
					$("#swiper_pic").swipe( {
						
								swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
					  if( direction =='left' )
							  $nextImageBtn.trigger( "click" );
							  
					 else 
					     $prevImageBtn.trigger( "click" );
					     phase:'move';
					    },
						
					threshold:0,
				    fingers:'all',					
				});
	      }
     });

	   }
/*--------------------------------------------------------------------------------------------------------*/		

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
	var fadingIn = false;	
	$('span.ProfileFollow').click(function(){
		
		$.ajax({
			url: ReturnLink('/ajax/photo_viewer_subscribe.php'),
			type: 'post',
			data: {uid: ORIGINAL_USER_ID},
			success: function(response){
				
				var Jresponse;
				try{
					Jresponse = $.parseJSON( response );
				}catch(Ex){
					TTAlert({
						msg: t("Couldn't Process Request. Please try again later."),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}

				if( !Jresponse ){
					TTAlert({
						msg: t("Couldn't Process Request. Please try again later."),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}

				if( Jresponse.status == 'ok'){
					TTAlert({
						msg: Jresponse.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}else{
					TTAlert({
						msg: Jresponse.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
			}
		});
	});
	
	$('span.ProfileFriend').click(function(){
		
		$.ajax({
			url: ReturnLink('/ajax/photo_viewer_addfriend.php'),
			type: 'post',
			data: {uid: ORIGINAL_USER_ID},
			success: function(response){
				
				var Jresponse;
				try{
					Jresponse = $.parseJSON( response );
				}catch(Ex){
					TTAlert({
						msg: t("Couldn't Process Request. Please try again later."),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}

				if( !Jresponse ){
					TTAlert({
						msg: t("Couldn't Process Request. Please try again later."),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}

				if( Jresponse.status == 'ok'){
					TTAlert({
						msg: Jresponse.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}else{
					TTAlert({
						msg: Jresponse.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
			}
		});
	});
	
	
	
});
function setNewImageData(){
    full_currentpage = 0;
    $bg_full_img.attr('src','');
    var the1stImg = new Image();
    the1stImg.onload = CreateDelegate(the1stImg, theNewImg_onload);
    the1stImg.src = $('#BigImageHolder img').attr("data-org");
    $bg_full_img.attr('title',$('#BigImageHolder img').attr("data-title"));
    $('.header_txt').html($('#BigImageHolder img').attr("data-title"));
    	
    $nextImageBtn.addClass('inactive');	
    $prevImageBtn.addClass('inactive');
    if($nextImageBtn.length>0){
        GetNextPrevImages(ORIGINAL_PHOTO_ID);
    }
}
function BackgroundLoad($this,imageWidth,imageHeight,imgSrc){
	$this.fadeOut("fast",function(){
		$this.attr("src", "").attr("src", imgSrc); //change image source
		FullScreenBackground($this,imageWidth,imageHeight); //scale background image
		$preloader.fadeOut("fast",function(){$this.fadeIn("slow");});
		//$('.header_txt').html(imageTitle);		
	});
}

//get next/prev images
function GetNextPrevImages($id){
	$.post(ReturnLink('/ajax/ajax_getNextPrevImages.php'), {id:$id,favorite_data_id:$('#favbutton').attr('data-id'),page:full_currentpage,is_album:$('.glob_container').attr('data-album'),data_id:$('.glob_container').attr('data-id')},function(data){
		var ret = null;
		try{
			ret = $.parseJSON(data);
		}catch(Ex){
			return ;
		}
		if(ret.photo_next_big =='' || ret.next_id==null){			
			$nextImageBtn.addClass('inactive');
		}else{	
			$nextImageBtn.removeClass('inactive');
			$outer_container.data("nextImage", ret.photo_next_big );
			$nextImageBtn.attr('data-id', ret.next_id );
			$nextImageBtn.attr('data-title', ret.next_title );
		}
		if(ret.photo_prev_big =='' || ret.prev_id==null){			
			$prevImageBtn.addClass('inactive');
		}else{	
			$prevImageBtn.removeClass('inactive');
			$outer_container.data("prevImage", ret.photo_prev_big );
			$prevImageBtn.attr('data-id', ret.prev_id );
			$prevImageBtn.attr('data-title', ret.prev_title );
		}
                if( ret.favorite == 0 ) {
                    $('#favbutton').removeClass('active');
                    $('#favbutton').attr('data-title', t('add to favorites') );
                }else {
                    $('#favbutton').addClass('active');
                    $('#favbutton').attr('data-title', t('remove from favorites') );
                }
                $('.share_link_holder.fullmode .share_link_text').val(ret.sh_link);
                $('.share_link_holder.fullmode .sh_lk1').attr('href',"http://www.facebook.com/sharer.php?s=100&amp;p[url]="+ ret.sh_link);
                $('.share_link_holder.fullmode .sh_lk2').attr('href',"https://plus.google.com/share?url="+ ret.sh_link);
                $('.share_link_holder.fullmode .sh_lk3').attr('href',"http://www.twitter.com/intent/tweet?url="+ ret.sh_link);
                incrementObjectsViews(  SOCIAL_ENTITY_MEDIA , ret.inc_id );                
	});
}

//switch image
function SwitchImage(img){
    $preloader.fadeIn("fast"); //show preloader
    var theNewImg = new Image();        
    theNewImg.onload = CreateDelegate(theNewImg, theNewImg_onload);
    theNewImg.src = img;
}

//get new image dimensions
function CreateDelegate(contextObject, delegateMethod){
	return function(){
		return delegateMethod.apply(contextObject, arguments);
	}
}

//new image on load
function theNewImg_onload(){
	$bg_full_img.data("newImageW",this.width).data("newImageH",this.height);
	BackgroundLoad($bg_full_img,this.width,this.height,this.src);
}

//Image scale function
function FullScreenBackground(theItem,imageWidth,imageHeight){
	var winWidth=$(window).width();
	var winHeight=$(window).height()-25;
	
	var picHeight = imageHeight / imageWidth;
	var picWidth = imageWidth / imageHeight;
	
	if ((winHeight / winWidth) > picHeight) {
		$(theItem).attr("width",winWidth);
		$(theItem).attr("height",picHeight*winWidth);
	} else {
		$(theItem).attr("height",winHeight);
		$(theItem).attr("width",picWidth*winHeight);
	};
	
	$(theItem).css("margin-left",(winWidth-$(theItem).width())/2);
	$(theItem).css("margin-top",(winHeight-$(theItem).height())/2 + 25);	
}