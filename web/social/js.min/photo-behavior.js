
function escapeModal(e){
	if( e.keyCode == 27) HideModal();
}

var $modal_bg = null;
var $modal_content = null;
var $modal_content_container = null;
var $modal_image = null;
var $catalog_container = null;
var $modal_data = null;
var $modal_nav = null;
var dir = 'ltr';
var PrevLoader = null;
var NextLoader = null;
var ownerbool = false;


var _shareFreindsArray = [];

/**
 * sets the friends array
 * @param {Array} fa
 * @returns {Array}
 */
function shareFriendsArray(fa){
	if( typeof fa === 'undefined'){
		return _shareFreindsArray;
	}else{
		_shareFreindsArray = fa;
	}
}

var WindowWidth;
var WindowHeight;

var _modalShowedCallback = null;
/**
 * sets the callback on 
 * @param the view to open in
 * @return the default view that must be opened
 */
function modalShowedCallback(callback){
	if(typeof callback != 'undefined'){
		_modalShowedCallback = callback;
	}else{
		if( _modalShowedCallback != null) _modalShowedCallback();
	}
}
var MIN_MODAL_WIDTH = 900;
var MIN_MODAL_HEIGHT = 600;

var ALBUM_MODAL_WIDTH = 1060;
var ALBUM_MODAL_HEIGHT = 550;

function DimensionModal(){
	if( ($modal_bg == null) || ($modal_content_container == null) ) return ;
	var padding = 40;
	var content_width = WindowWidth - padding*2;
	var content_height = WindowHeight - padding*2;
	var cleft, ctop;
	if( CATALOG_ID != '' && PHOTO_ID == ''){
		//album mode fix the dimensions
		content_width = ALBUM_MODAL_WIDTH;
		content_height = ALBUM_MODAL_HEIGHT;
		cleft = Math.abs( (WindowWidth - content_width)/2 );
		ctop = Math.abs( (WindowHeight - content_height)/2 );
		
	}else{
		if( content_width < MIN_MODAL_WIDTH ) content_width = MIN_MODAL_WIDTH;
		if( content_height < MIN_MODAL_HEIGHT) content_height = MIN_MODAL_HEIGHT;
		ctop = padding;
		cleft = padding;
	}
	
	$modal_bg.height( WindowHeight ).width( WindowWidth );
	$modal_content_container.height( WindowHeight ).width( WindowWidth );
	//console.log(WindowHeight+"]["+WindowWidth+"]["+$(window).height()+"]["+$(window).width());
	//$modal_bg.height( $(window).height() ).width( $(window).width() );
	//$modal_content_container.height( $(window).height() ).width( $(window).width() );
	$modal_content.css({
		top: ctop,
		left: cleft,
		width:content_width,
		height:content_height
	});
	
	$modal_nav.width( content_width - $modal_data.width() );
	$modal_image.width( content_width - $modal_data.width() ).css({'top': $modal_nav.height()}).height( content_height - $modal_nav.height() );
	$view_image  = $('img',$modal_image);
	
	var n_images = $view_image.length;
	if( n_images == 1){
		var area_width = $modal_image.width();
		var area_height = $modal_image.height();
		var orig_width = $view_image.attr('orig_width');
		var orig_height = $view_image.attr('orig_height');

		var image_new_width,image_new_height,image_top,image_left;
		var resize_width = false;
		var resize_height = false;
		if( orig_width < area_width && orig_height < area_height ){
			image_new_height = orig_height;
			image_new_width = orig_width;
			resize_width = true;
			resize_height = true;
		}else{
			
			if( orig_width > area_width){
				image_new_width = area_width;
				image_new_height = orig_height*area_width/orig_width;
			}else{
				image_new_height = orig_height;
				image_new_width = orig_width;
			}
			
			if( image_new_height > area_height ){
				image_new_height = area_height;
				image_new_width = orig_width*area_height/orig_height;
				resize_width = true;
			}
		}

		if(resize_width){
			area_width = image_new_width;
			if( area_width < MIN_MODAL_WIDTH -  $modal_data.width() ) area_width = MIN_MODAL_WIDTH -  $modal_data.width();
			$modal_nav.width( area_width );
			$modal_image.width( area_width );
			var new_content_width = parseInt(area_width) + $modal_data.width()
			$modal_content.width( new_content_width );
			var modal_left_pos = ( WindowWidth - area_width - $modal_data.width() )/2;
			$modal_content.css({
				left: modal_left_pos + 'px'
			});
		}
		if(resize_height){
			area_height = image_new_height;
			if( area_height < MIN_MODAL_HEIGHT -  $modal_nav.height() ) area_height = MIN_MODAL_HEIGHT -  $modal_nav.height();
			$modal_image.height( area_height );
			var new_nav_height = parseInt(area_height) + $modal_nav.height();
			//this breaks the layout. keeo it 100%
			//$modal_data.height( new_nav_height );
			$modal_content.height( new_nav_height );
			var modal_top_pos = ( WindowHeight - new_nav_height )/2;
			$modal_content.css({
				top: modal_top_pos + 'px'
			});
		}
		
		image_top = (area_height - image_new_height)/2;
		image_left = (area_width - image_new_width)/2;

		$view_image.css({position: 'absolute', top: image_top, left : image_left, height : image_new_height + 'px', width : image_new_width + 'px'});	
		
		
	}else{
		
		var $view_image_container = $view_image.parent();
		
		var n_rows = 6;// parseInt( $modal_image.height() / ($view_image_container.outerHeight()+4) );
		var n_cols = Math.ceil( n_images/n_rows );
		var catalog_width = n_cols * ($view_image_container.outerWidth()+4);
		var catalog_height = $modal_image.height();
		catalog_width = Math.max(catalog_width, $modal_image.width() );
		$catalog_container.css({width: catalog_width, height: catalog_height});
		
	}
	
}

function HideModal(){
	$(document).unbind('keyup',escapeModal);
	$('html').css({'overflow': ''});
	$(window).css({'overflow': ''});
	$('body').css({'overflow': ''});
	$modal_bg.remove();
	
	//if video mode return the flash player
	if( $('#flashcontent2').length != 0 ){
		var regWidth = 694;
		var regHeight = 442;
		var settingsWidth = 690;
		var settingsHeight = 388;
		EmbedFlash('flashcontent', null, regWidth, regHeight, settingsWidth, settingsHeight);
		setTimeout(function(){
			PlayMedia();
		},1000);
	}
	//if we are on the video page only show the "View Detail" button if not modal
	if( typeof VideoDetail != 'undefined' ){
		VideoDetail('false');
	}
	
	$modal_content_container.remove();
	$modal_bg = null;
	$modal_content_container = null;
}
$(document).ready(function(){
	$(window).resize(function(){
		setTimeout(function(){
			WindowWidth = Math.max($('body').width(), $(window).width());
			WindowHeight =  window.innerHeight ? window.innerHeight : $(window).height();
			
			DimensionModal();
			$('div.cropRegion').hide();
		},100);
	});
	//$('.ModalFollowFriendBtn').mouseover(function(){
	$(document).on('mouseover', ".ModalFollowBtn",function(){
		var posxx=16;
		
		$('.mediabuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
		$('.mediabuttonsOver').css('left',posxx+'px');
		$('.mediabuttonsOver').css('top','-16px');
		$('.mediabuttonsOver').stop().show();
	});
	$(document).on('mouseover', ".ModalAddFriendBtn",function(){
		var posxx=40;
		
		$('.mediabuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
		$('.mediabuttonsOver').css('left',posxx+'px');
		$('.mediabuttonsOver').css('top','-16px');
		$('.mediabuttonsOver').stop().show();
	});
	//$('.ModalFollowFriendBtn').mouseout(function(){
	$(document).on('mouseout', ".ModalFollowBtn, .ModalAddFriendBtn",function(){
		$('.mediabuttonsOver').hide();
	});
	$('#media_buttons').mouseover(function(){
		$(this).css({
			'opacity': 1
		});
	}).mouseout(function(){
		$(this).css({
			'opacity': 0.5
		});
	});
	
});
