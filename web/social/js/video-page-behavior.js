
var LOAD_START, LOAD_END, DL_SIZE = 4096*8;
var _ViewDetail = 'false';
var _VideoLinks;
var _VideoLinksNumber = 1;
var _VideoFavorite;
var _VideoLike;
var _VideoRating;
var _VideoTitle;
var _VideoDescription;
var _VideoImage;
var _LoadedVideo = -1;
var _ViewSmallest;
var _VideoArray;
var ThumbPath = '';
var _VideoWindowMode = 'opaque';
var _VideoHideButtons = false;

var newregWidth = 694;
var newregHeight = 442;
var newsettingsWidth = 690;
var newsettingsHeight = 388;

function VideoLoaded(vl){
	if( typeof vl != 'undefined'){
		return _LoadedVideo = vl;
	}
	else return _LoadedVideo;
}
function VideoLinks(vl){
	if( typeof vl != 'undefined'){
		return _VideoLinks = vl;
	}
	else return _VideoLinks;
}
function VideoLinksNumber(vl){
	if( typeof vl != 'undefined'){
		return _VideoLinksNumber = vl;
	}
	else return _VideoLinksNumber;
}
function VideoLike(val){
	if( typeof val != 'undefined'){
		return _VideoLike = val;
	}
	else return _VideoLike;
}
function VideoFavorite(val){
	if( typeof val != 'undefined'){
		return _VideoFavorite = val;
	}
	else return _VideoFavorite;
} 
function VideoRating(val){
	if( typeof val != 'undefined'){
		return _VideoRating = val;
	}
	else return _VideoRating;
}
function VideoTitle(val){
	if( typeof val != 'undefined'){
		return _VideoTitle = val;
	}
	else return _VideoTitle;
}
function VideoDescription(val){
	if( typeof val != 'undefined'){
		return _VideoDescription = val;
	}
	else return _VideoDescription;
}
function VideoImage(val){
	if( typeof val != 'undefined'){
		return _VideoImage = val;
	}
	else return _VideoImage;
}
function VideoDetail(val){
	if( typeof val != 'undefined'){
		return _ViewDetail = val;
	}
	else return _ViewDetail;
}
function VideoSmallest(val){
	if( typeof val != 'undefined'){
		return _ViewSmallest = val;
	}
	else return _ViewSmallest;
}
function VideoArray(val){
	if( typeof val != 'undefined'){
		return _VideoArray = val;
	}
	else return _VideoArray;
}

function VideoWindowMode(val){
	if( typeof val != 'undefined'){
		return _VideoWindowMode = val;
	}
	else return _VideoWindowMode;
}
function VideoHideButtons(val){
	if( typeof val != 'undefined'){
		return _VideoHideButtons = val;
	}
	else return _VideoHideButtons;
	
}
var startedPlaying;
function PlayMedia(){
	startedPlaying = false;
	//estimate speed
	var imageAddr = AbsolutePath + "/media/images/speedtest.png?n=" + Math.random();
	var $download = $('<img />').css({'display' : 'none'});
	$download.appendTo('body');
	$download.load(function(){
		if(startedPlaying) return;
		startedPlaying = true;
		
		LOAD_END = (new Date()).getTime();
		LOAD_TIME = (LOAD_END - LOAD_START)/1000.0;
		var WHICH_RES;
		var CON_SPEED;

		if(LOAD_TIME == 0) CON_SPEED = 4096;
		else CON_SPEED = parseInt( DL_SIZE/LOAD_TIME );
		if( CON_SPEED < 256) WHICH_RES = 1;
		else if( CON_SPEED < 512) WHICH_RES = 2;
		else if( CON_SPEED < 1024) WHICH_RES = 3;
		else if( CON_SPEED < 2048) WHICH_RES = 4;
		else WHICH_RES = 5;
		var N_RES = VideoLinksNumber();

		WHICH_RES = Math.min(WHICH_RES,N_RES);
		WHICH_RES = 1; //put it back to 1 for testing
		//console.log(VideoLinks());
		makeCall( VideoLinks() , WHICH_RES, VideoFavorite(), VideoLike() , VideoRating() , VideoTitle() , VideoDescription() , VideoImage(), VideoDetail() , VideoHideButtons() );
	});
	LOAD_START = (new Date()).getTime();
	
	//call a timeout so that if the image is never loaded/takes too long to load - the video will start playing
	setTimeout(function(){
		if(startedPlaying) return;
		startedPlaying = true;
		makeCall( VideoLinks() , 1, VideoFavorite(), VideoLike() , VideoRating() , VideoTitle() , VideoDescription() , VideoImage(), VideoDetail(),VideoHideButtons() );
	},2000);
	
	//start the download to estimate speed
	$download.attr('src',imageAddr);
	
}


function ChangeResolution(URL, Resolution, This){
	$("#listRes li").removeClass('selected');
	This.addClass("selected");
	$("#VidPlay_html5_api").get(0).pause();
	$("#stanRes").html(Resolution);
	$("#VidPlay video").attr("src","https://www.touristtube.com"+URL);
	
	$("#listRes").stop().animate({height:'0px'},500,function(){});
	opened = false;
	$("#VidPlay_html5_api").get(0).play();
}

/**
 * gets the currently displayed video's id
 * @return {Integer} the video's id
 */
function VideoID(){
	//in the modal window we have PHOTO_ID, on the videopage we have videoId
	//return ( typeof VideoId == 'undefined' ) ? PHOTO_ID : VideoId;
        if( typeof VideoId != 'undefined' ) return VideoId;
        else if( typeof PHOTO_ID != 'undefined' ) return PHOTO_ID;
        else if( typeof ORIGINAL_MEDIA_ID != 'undefined' ) return ORIGINAL_MEDIA_ID;        
}

function CopyLink(){
	//$("#linkurl").copy();
	//window.prompt( 'Press ctrl/cmd+c to copy text', $("#linkurl").val() );
}

function FacebookShare(){
	var FullURL = window.location.href;
	window.open( "http://www.facebook.com/sharer.php?s=100&amp;p[url]="+FullURL );
}

function GooglePlusShare(){
	var FullURL = window.location.href;
	window.open( "https://plus.google.com/share?&url="+FullURL );
}

function TwitterShare(){
	var FullURL = window.location.href;
	window.open( "http://www.twitter.com/intent/tweet?url="+FullURL );
}

function YahooShare(){
	var FullURL = window.location.href;
	window.open( "http://api.addthis.com/oexchange/0.8/forward/yahoomail/offer?url="+FullURL+"&title="+VideoTitle()+"&description="+VideoDescription()+"&swfurl="+"&height=250&width=400&screenshot="+VideoImage() );
}

function SendMail(){
	var FullURL = window.location.href;
	viewEmailShareForm(VideoTitle(),FullURL);
}

/**
 * gets the dimensions of the video player given the scale of the browser window
 * @returns Array containing width and height of the videoplayer
 */
function videoScaledDimensions(regWidth, regHeight, settingsWidth, settingsHeight){
	/*var regWidth = 694;
	var regHeight = 442;
	var settingsWidth = 690;
	var settingsHeight = 388;*/
	
	/*var regWidth = 547;
	var regHeight = 359;
	var settingsWidth = 543;
	var settingsHeight = 305;*/
	
	var regWidth = regWidth;
	var regHeight = regHeight;
	var settingsWidth = settingsWidth;
	var settingsHeight = settingsHeight;
	
	newregWidth = regWidth;
	newregHeight = regHeight;
	newsettingsWidth = settingsWidth;
	newsettingsHeight = settingsHeight;
	
	var scale = detectZoom.zoom();
	if(  scale != 1){
		//console.log( regWidth + ' ' + window.devicePixelRatio);
		regWidth *= scale;
		regHeight *= scale;
		regWidth = parseInt(regWidth);
		regHeight = parseInt(regHeight);

		settingsWidth *= scale;
		settingsHeight *= scale;
		settingsWidth = parseInt(settingsWidth);
		settingsHeight = parseInt(settingsHeight);
	}
	
	var RetArr = new Array();
	
	RetArr['width'] = settingsWidth;
	RetArr['height'] = settingsHeight;
	
	return RetArr;
}

function Htmlplayer(where, vid, regWidth, regHeight, settingsWidth, settingsHeight, videorelated_Arr){
    var v = document.createElement('video');
    if( v.canPlayType && v.canPlayType('video/mp4') ){
            var whichRes = 4;
            var VideoArr = VideoArray();
            var VideoRat = VideoRating();
            var VidLike = VideoLike();
            var VideoFav = VideoFavorite();
            var FullURL = window.location.href;

            var ResolutionArray = new Array('240 p','360 p','480 p','HD 720 p','HD 1080 p');

            var ul = '<ul>';
            for(var i=0; i<VideoArr.length;i++){
                    ul += '<li onclick=\'ChangeResolution("'+VideoArr[i]+'","'+ResolutionArray[i]+'",$(this))\'>'+ResolutionArray[i]+'</li>';
            }
            ul += '</ul>';

            $('#'+where).html('<video id="VidPlay" data-vid="'+vid+'" class="video-js vjs-default-skin" data-setup="{}" controls="controls" autoplay="autoplay" width="694" height="400" preload="auto" poster="'+VideoImage()+'"><source src="' + VideoSmallest()+'" type="video/mp4"></video><div id="PlayerButtons"><div class="resolution" id="resolution"><div class="listRes" id="listRes">'+ul+'</div><div class="stanRes" id="stanRes">'+ResolutionArray[whichRes]+'</div></div><div class="ratingcontainer rating'+VideoRat+'"><ul><li rel="1"></li><li rel="2"></li><li rel="3"></li><li rel="4"></li><li rel="5"></li></ul></div><div class="favorites up" onClick=\'\'></div><div class="share MediaButton_data_share" id="share_link"></div><div class="sharepopup" id="sharepopup"><div class="sharecontainer"><div class="buttonslist"><ul><li onClick="FacebookShare();"><img src="'+ReturnLink('media/images/f_icon_player.gif')+'"></li><li onClick="GooglePlusShare();"><img src="'+ReturnLink('media/images/g_icon_player.gif')+'"></li><li onClick="TwitterShare();"><img src="'+ReturnLink('media/images/t_icon_player.gif')+'"></li><li onClick="YahooShare();"><img src="'+ReturnLink('media/images/y_icon_player.gif')+'"></li><li onClick="SendMail();"><img src="'+ReturnLink('media/images/m_icon_player.gif')+'"></li></ul></div><div class="formlist">'+t("GET THE LINK:")+'<br><input type="text" name="linkurl" id="linkurl" value="'+FullURL+'" readonly="readonly"></div><div class="copylink" onClick="CopyLink()">'+t("copy")+'</div></div></div></div>');

            var StanSelected = $("#stanRes").html();
            var liStan = $("#listRes li");

            liStan.each(function(){
                    var $this = $(this);
                    var thisTXT = $this.html();
                    if(thisTXT == StanSelected){
                            $("#listRes li").removeClass('selected');
                            $this.addClass("selected");
                    }
            });

            if(VidLike == "-1"){
                    $(".dislike").removeClass("up").addClass("over");
            }else if(VidLike == "+1"){
                    $(".like").removeClass("up").addClass("over");
            }

            if(VideoFav == 'no'){
                    $(".favorites").addClass("up");
                    $(".favorites").attr("onClick", "favoriteFunc(false);")
            }else{
                    $(".favorites").addClass("over");
                    $(".favorites").attr("onClick", "favoriteFunc(true);")
            }
        }else{
            //this doesnt run. it just embeds the getflash link
            flashembed(where, {
                    version: 10,
                    expressInstall: true,
                    src: ReturnLink('/videoPlayer.swf'),
                    wmode: 'transparent',//VideoWindowMode(),
                    play: 'true',
                    loop: 'true',
                    name: 'videoPlayer',
                    id: 'videoPlayer',
                    bgcolor:  '#000000',
                    allowFullScreen: 'true',
                    allowScriptAccess: 'always'
            }, {
                    settingsPath: AbsolutePath + '/xml/settings.xml'
            });
    //$('#'+where).html('<h1>No Flash detected.</h1>');
    }
}
function EmbedFlashJW(where, res_list, regWidth, regHeight, ThumbPath , videorelated_id,show_related,is_post,auto_start){
    var res_names = new Array('240 p','360 p','480 p','HD 720 p','HD 1080 p');
    var res_listarr = res_list.split('/*/');
    var res_real=new Array();
    var mobj;
    for(var i=0; i<res_listarr.length; i++){
        mobj = {"file":ReturnLink("/"+res_listarr[i],1) , "label":res_names[i]};
        res_real.push(mobj);
    }
    if(videorelated_id==0){
        jwplayer(where).setup({
            sources: res_real,
            image: ThumbPath,
            ga: {} ,
            stretching: "bestfit",
            autostart: auto_start,                
            logo: {
                file: '',//ReturnLink('/media/images/Logo.png'),
                link: 'https://www.touristtube.com/'
            },
            skin: {
                name: "glow"
            },
            startparam: "ec_seek",
            height: regHeight,
            width: regWidth,
            mode:"html",
            hlshtml:true,
            primary:"html5",
            controlbar:"bottom"
        });
    }else if(show_related==1){
        jwplayer(where).setup({ 
            sources: res_real,
            image: ThumbPath,
            ga: {} ,
            stretching: "bestfit",
            autostart: auto_start,                
            logo: {
                file: '',//ReturnLink('/media/images/logovideo.png'),
                link: 'https://www.touristtube.com/'
            },
            skin: {
                name: "glow"
            },
            related: {
                file: ReturnLink("/ajax/getVideoRelatedXML?id="+videorelated_id),
                onclick: "link"
            },
            tracks: [{ 
                file: ReturnLink("/ajax/getThumbRelatedVTT.php?no_cach=" +Math.random()+"&id="+videorelated_id+"&is_post="+is_post), 
                kind: "thumbnails"
            }],
            //startparam: "start",
            startparam: "ec_seek",
            height: regHeight,
            width: regWidth,
            mode:"html",
            hlshtml:true,
            primary:"html5",
            controlbar:"bottom"
        });
    }else{
        jwplayer(where).setup({
            sources: res_real,
            image: ThumbPath,
            ga: {} ,
            stretching: "bestfit",
            autostart: auto_start,                
            logo: {
                file: '',//ReturnLink('/media/images/Logo.png'),
                link: 'https://www.touristtube.com/'
            },
            skin: {
                name: "glow"
            },           
            tracks: [{ 
                file: ReturnLink("/ajax/getThumbRelatedVTT.php?no_cach=" +Math.random()+"&id="+videorelated_id+"&is_post="+is_post), 
                kind: "thumbnails"
            }],
            //startparam: "start",
            startparam: "ec_seek",
            height: regHeight,
            width: regWidth,
            mode:"html",
            hlshtml:true,
            primary:"html5",
            controlbar:"bottom"
        });
    }
}
function EmbedFlash(where, vid, regWidth, regHeight, settingsWidth, settingsHeight, videorelated_Arr){
//	Htmlplayer(where, vid, regWidth, regHeight, settingsWidth, settingsHeight, videorelated_Arr);
//        return;
	if( !jQuery.browser.flash || (window.location.href.indexOf('html5') !== -1) ){
	//if( false ){
		var v = document.createElement('video');
		if( v.canPlayType && v.canPlayType('video/mp4') ){
			var whichRes = 4;
			var VideoArr = VideoArray();
			var VideoRat = VideoRating();
			var VidLike = VideoLike();
			var VideoFav = VideoFavorite();
			var FullURL = window.location.href;
			
			var ResolutionArray = new Array('240 p','360 p','480 p','HD 720 p','HD 1080 p');
			
			var ul = '<ul>';
			for(var i=0; i<VideoArr.length;i++){
				ul += '<li onclick=\'ChangeResolution("'+VideoArr[i]+'","'+ResolutionArray[i]+'",$(this))\'>'+ResolutionArray[i]+'</li>';
			}
			ul += '</ul>';
			//https://www.touristtube.com
			$('#'+where).html('<video id="VidPlay" data-vid="'+vid+'" class="video-js vjs-default-skin" data-setup="{}" controls="controls" autoplay="autoplay" width="694" height="400" preload="auto" poster="'+VideoImage()+'"><source src="' + VideoSmallest()+'" type="video/mp4"></video><div id="PlayerButtons"><div class="resolution" id="resolution"><div class="listRes" id="listRes">'+ul+'</div><div class="stanRes" id="stanRes">'+ResolutionArray[whichRes]+'</div></div><div class="ratingcontainer rating'+VideoRat+'"><ul><li rel="1"></li><li rel="2"></li><li rel="3"></li><li rel="4"></li><li rel="5"></li></ul></div><div class="favorites up" onClick=\'\'></div><div class="share MediaButton_data_share" id="share_link"></div><div class="sharepopup" id="sharepopup"><div class="sharecontainer"><div class="buttonslist"><ul><li onClick="FacebookShare();"><img src="'+ReturnLink('media/images/f_icon_player.gif')+'"></li><li onClick="GooglePlusShare();"><img src="'+ReturnLink('media/images/g_icon_player.gif')+'"></li><li onClick="TwitterShare();"><img src="'+ReturnLink('media/images/t_icon_player.gif')+'"></li><li onClick="YahooShare();"><img src="'+ReturnLink('media/images/y_icon_player.gif')+'"></li><li onClick="SendMail();"><img src="'+ReturnLink('media/images/m_icon_player.gif')+'"></li></ul></div><div class="formlist">'+t("GET THE LINK:")+'<br><input type="text" name="linkurl" id="linkurl" value="'+FullURL+'" readonly="readonly"></div><div class="copylink" onClick="CopyLink()">'+t("copy")+'</div></div></div></div>');
			
			var StanSelected = $("#stanRes").html();
			var liStan = $("#listRes li");
			
			liStan.each(function(){
				var $this = $(this);
				var thisTXT = $this.html();
				if(thisTXT == StanSelected){
					$("#listRes li").removeClass('selected');
					$this.addClass("selected");
				}
			});
			
			if(VidLike == "-1"){
				$(".dislike").removeClass("up").addClass("over");
			}else if(VidLike == "+1"){
				$(".like").removeClass("up").addClass("over");
			}
			
			if(VideoFav == 'no'){
				$(".favorites").addClass("up");
				$(".favorites").attr("onClick", "favoriteFunc(false);")
			}else{
				$(".favorites").addClass("over");
				$(".favorites").attr("onClick", "favoriteFunc(true);")
			}
			
		}else{
			//this doesnt run. it just embeds the getflash link
			flashembed(where, {
				version: 10,
				expressInstall: true,
				src: ReturnLink('videoPlayer.swf'),
				wmode: 'transparent',//VideoWindowMode(),
				play: 'true',
				loop: 'true',
				name: 'videoPlayer',
				id: 'videoPlayer',
				bgcolor:  '#000000',
				allowFullScreen: 'true',
				allowScriptAccess: 'always'
			}, {
				settingsPath: AbsolutePath + '/xml/settings.xml'
			});
		//$('#'+where).html('<h1>No Flash detected.</h1>');
		}
	}else{	
		var Dims = videoScaledDimensions(regWidth, regHeight, settingsWidth, settingsHeight);
                
		flashembed(where, {
			src: ReturnLink('/videoPlayer.swf'),
			wmode: 'transparent',//VideoWindowMode(),
			play: 'true',
			loop: 'true',
			name: 'videoPlayer',
			id: 'videoPlayer',
			allowFullScreen: 'true',
			allowScriptAccess: 'always'
		}, {
			settingsPath: AbsolutePath + '/xml/settings.xml',
			settingsWidth: Dims['width'],
			settingsHeight: Dims['height'],
			videorelated_Arr:videorelated_Arr
		});
	}
}

function getWidthHeight() {
    $player = $('embed[name=videoPlayer],object[name=videoPlayer]');
    if( $player.length == 0 || ! $player.get(0).getWidthHeight ) return ;

    //var Dims = videoScaledDimensions();
    var Dims = videoScaledDimensions(newregWidth, newregHeight, newsettingsWidth, newsettingsHeight);

    try {
        $player.get(0).getWidthHeight(Dims['width'],Dims['height']);
    }
    catch(ex){
        setTimeout(function(){
            getWidthHeight();
        }, 1000);
    }
}

$(window).load(function(){
	//trigger the flash to resize
	$(window).resize(function(){
		
		//in the modal popup this function is being triggered before the document.body is ready or the flash is loaded
		//so return right now
		if( ! document.body ) return ;
		
                getWidthHeight();
	});
});
var focused = false;
$(window).bind('focus', function(){
    if(!focused){
        getWidthHeight();
        focused = true;
    }
});

function thisMovie(movieName) {
	var isIE = navigator.appName.indexOf("Microsoft") != -1;
	return (isIE) ? window[movieName] : document[movieName];
}

function replayFn(str){
	$('#endofplay').hide();
}

function videoFinished(str){
}


function playvideoFlash(_VideoRelated, _vidId){
	//alert(_VideoRelated);
	$player = $('embed[name=videoPlayer],object[name=videoPlayer]');
	$.ajax({
        url:  ReturnLink('/ajax/ajax_getflashvideores.php'),
        type: 'POST',
        data: {
            vId: _vidId
        },
        success:function(data){
			//alert(data);
			makeCall( data , 1, VideoFavorite(), VideoLike() , VideoRating() , VideoTitle() , VideoDescription() , VideoImage(), VideoDetail(),VideoHideButtons() );
       }
   });
	
	
	//window.location = ReturnLink('/video/' + str);
	//console.log(url);
	//window.location = url;
}

function reloadfromFlash(str){
	//console.log(".... "+str);
	window.location = ReturnLink('/video/' + str);
}
function pauseMyVideo(){
	try{
            jwplayer().pause(true);
		/*var $player = $('embed[name=videoPlayer],object[name=videoPlayer]');
		if($player.length > 0 ){
			$player.get(0).pauseVideo('yes');
		}*/
	}catch(e){
	}
}
function makeCall(str, autovid, fav, like, rate, title, desc,image,viewdetailsBool,buttonsbool) {
	//var tm = thisMovie("videoPlayer");
	//thisMovie("videoPlayer").asFunc(str);
	var $player = $('embed[name=videoPlayer],object[name=videoPlayer]');
	var wait = 300;
	//console.log('here ' + autovid);
        
	if($player.length != 0){
		
		if( typeof $player.get(0).asFunc != 'undefined' && $player.get(0).asFunc){
                    try{
			$player.get(0).asFunc(str, autovid, fav, like, rate, title, desc,image,viewdetailsBool,buttonsbool);
                    }
                    catch(ex){
                        setTimeout(function(){
                            makeCall(str, autovid, fav, like, rate, title, desc,image,viewdetailsBool,buttonsbool);
                        }, wait);
                    }
		}else{
			
			setTimeout(function(){
				makeCall(str, autovid, fav, like, rate, title, desc,image,viewdetailsBool,buttonsbool);
			}, wait );
		}
	}else{
		setTimeout(function(){
			makeCall(str, autovid, fav, like, rate, title, desc,image,viewdetailsBool,buttonsbool);
		}, wait );
	}
}

function likeFunc(str) {
	
	TTCallAPI({
		what: 'social/like',
		data: {entity_id : VideoID(), entity_type : SOCIAL_ENTITY_MEDIA, like_value: str, channel_id: channelGlobalID() },
		callback: function(data){
			
			var cur_likes = parseInt( $('#display_likes').text() );
				
			if(data.status == 'ok'){
				if( str == "+1" )
					$('#display_likes').text( cur_likes + 1);
				else
					$('#display_likes').text( cur_likes - 1);
			}else{
				TTAlert({
					msg: data.error_msg,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
			
		}
	});
	
	return true;
}
	
function favoriteFunc(str) {
	TTCallAPI({
		what: 'social/favorite',
		data: {entity_id : VideoID(), entity_type : SOCIAL_ENTITY_MEDIA, channel_id: channelGlobalID() },
		callback: function(data){			
                    if(data.status == 'ok'){
                        if( data.favorite == 0 ){
                            $(".favorites").removeClass("over");
                            $(".favorites").addClass("up");
                            $(".favorites").attr("onClick", "favoriteFunc(false);");
                        }else{
                            $(".favorites").removeClass("up");
                            $(".favorites").addClass("over");
                            $(".favorites").attr("onClick", "favoriteFunc(true);");
                        }
                    }else{
                        var $player = $('embed[name=videoPlayer],object[name=videoPlayer]');
                        $player.get(0).getFavoriteResult(0);
                        TTAlert({
                            msg: data.msg,
                            type: 'action',
                            btn1: t('sign in'),
                            btn2: t('register'),
                            btn2Callback: function(data) {
                                if (data) {
                                    window.location.href = ReturnLink('/register');
                                } else {
                                    SignInTO = setTimeout(function() {
                                        $('#SignInDiv').fadeIn();
                                        signflag = 1;
                                    }, 300);
                                }
                            }
                        });
                    }			
		}
	});
	
	return true;
}

function rateFunc(str){
	
	TTCallAPI({
		what: 'social/rate',
		data: {entity_id : VideoID(), entity_type : SOCIAL_ENTITY_MEDIA, score: str, channel_id: channelGlobalID() },
		callback: function(data){
			
			if( data.status == 'ok' ){
				//update rating
				$('#rating_text').html( '(' + data.newrate + ')');

				var ratingi = parseInt(data.rating);
				$('#myrating_score').val(ratingi);
				$('#rating img.rating_star').each(function(i){
					if(i < ratingi){
						$(this).attr('src', AbsolutePath + '/media/images/rating_1.png');
					}else{
						$(this).attr('src', AbsolutePath + '/media/images/rating_0.png');
					}
				});
			}
			
		}
	});
}
		
var Called = false;
var OldValue = -1;
var GettingThumb = false;
function ResetCalling(){
	if( Called ) return true;
	Called = true;
	setTimeout(function(){
		Called = false;
	}, 200);
	return false;
}

function timeFuncHtml(str){
	if(str == -1) return false;
			
	//if( ResetCalling() ) return false;
			
	//if( str == OldValue) return false;
			
	//if(GettingThumb == true) return false;
			
	OldValue = str;
			
	return ThumbPath + '/out' + str + '.jpg';
}

function timeFunc(str) {
	
	if(str == -1) return false;
	
	if( ResetCalling() ) return false;
	
	if( str == OldValue) return false;
	
	if(GettingThumb == true) return false;
	
	OldValue = str;
	
	getimagePathsNew(ThumbPath + '/out' + str + '.jpg');
	
	return true;
}
		
function getimagePathsNew(path){
	//thisMovie("videoPlayer").getimagePaths(path);
	
	var $player = $('embed[name=videoPlayer],object[name=videoPlayer]');
	$player.get(0).getimagePaths(path);
}

/*function videoFinished(str){
	$('#endofplay').show();
}*/


$(document).ready(function(){
	$(document).on('click', '#endOfPlayReplay', function () {
		$('#endofplay').hide();
		//replay
	});
	var opened = false;
	$(document).on('click', '#stanRes', function () {
		if(opened){
			$("#listRes").stop().animate({height:'0px'},500,function(){});
			opened = false;
		}else{
			$("#listRes").stop().animate({height:'100px'},500,function(){});
			opened = true;
		}
	});
	$(document).on('mouseleave', '#resolution', function () {
		$("#listRes").stop().animate({height:'0px'},500,function(){});
		opened = false;
	});
	
	$(document).on('click', '.ratingcontainer li', function () {
		var score = $(this).attr('rel');
		var vid = $("#VidPlay_html5_api").attr("data-vid");
		
		TTCallAPI({
			what: 'social/rate',
			data: {entity_id : vid, entity_type : SOCIAL_ENTITY_MEDIA, score: score, channel_id: channelGlobalID() },
			callback: function(data){
				
				if( data.status == 'ok' ){
					//update rating
					$(".ratingcontainer")
										.removeClass("rating0")
										.removeClass("rating1")
										.removeClass("rating2")
										.removeClass("rating3")
										.removeClass("rating4")
										.removeClass("rating5");
					$(".ratingcontainer").addClass("rating"+data.newrate);
					$('#rating_text').html( '(' + data.newrate + ')');
				}
				
			}
		});

	});
	
	var openshare = false;
	
	$(document).on('click', '.share', function () {
		if(openshare){
			$(this).removeClass("selected");
			$("#sharepopup").hide();
			openshare = false;
		}else{
			$(this).addClass("selected");
			$("#sharepopup").show();
			openshare = true;
		}
	});
	
	$(document).on('click', '.likeUnlike', function () {
		var vote = $(this).attr('rel');
		var vid = $("#VidPlay_html5_api").attr("data-vid");
		
		TTCallAPI({
			what: 'social/like',
			data: {entity_id : VideoID(), entity_type : SOCIAL_ENTITY_MEDIA, like_value: str, channel_id: channelGlobalID() },
			callback: function(data){
				
				var cur_likes = parseInt( $('#display_likes').text() );
					
				if(data.status == 'ok'){
					if( str == "+1" )
						$('#display_likes').text( cur_likes + 1);
					else
						$('#display_likes').text( cur_likes - 1);
				}else{
					TTAlert({
						msg: data.error_msg,
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