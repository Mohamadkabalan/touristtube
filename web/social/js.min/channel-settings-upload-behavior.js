
var uploader = null;
var requirements_met = true;
var upload_started;

function InitChannelUploader(which_uploader,imgsize){
	
	//if( uploader !== null ) return ;
	
	uploader = new plupload.Uploader({
		runtimes : 'html5,flash',
		button_browse_hover : true,
		max_file_size : imgsize + 'mb',
		container: 'uploadContainer' + which_uploader,
		browse_button : 'uploadBtn' + which_uploader,
		drop_element: 'uploadDropDiv' + which_uploader,
		url :  ReturnLink('/social/channel-upload-file.php'),
		flash_swf_url : ReturnLink('/js/plupload/plupload.flash.swf'),
		multi_selection: false,
		multipart_params : {
            channel_id: globchannelid,
			which_uploader: which_uploader,
			SID : $('#SID').val()
        },
		filters : [
			{title : "Image files", extensions : "jpg,gif,png,tiff,tif,bmp,jpeg"},
		],
		init: attachCallbacks
	});
	
	uploader.bind('Init', function(up, params) {
		
		if( (params.runtime == 'flash') && !swfobject.hasFlashPlayerVersion("10") ){
			requirements_met = false;
			TTAlert({
				msg: t("Please update your flash installation to at least version 10"),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});			
		}
	});
	
	uploader.init();
	
	function attachCallbacks(uploader){

		uploader.bind('FilesAdded', function(up, files) {
			up.settings.multipart_params = {
									channel_id: globchannelid,
									which_uploader: which_uploader,
									SID : $('#SID').val()
								};
			
			if(!requirements_met){
				TTAlert({
					msg: t("Please update your flash installation to at least version 10"),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return false;
			}

			setTimeout(function(){
				uploader.start()
			}, 100);

			$.each(files, function(i, file) {
				var fid = file.id;
				//actaually only one upload
				$('.upload-overlay-loading-fix').show();
			});
		});

		uploader.bind('UploadFile', function(up, file) {
			var fid = file.id;

			upload_started = 1;
			$('.upload-overlay-loading-fix').show();
		});

		uploader.bind('UploadProgress', function(up, file) {

			var fid = file.id;
			//file.percent
			//$('.upload-overlay-loading-fix span').html(file.percent+'%');
		});

		uploader.bind('Error', function(up, err) {
			$('.upload-overlay-loading-fix').hide();
			if(err.code==-600){
				TTAlert({
					msg: err.message+'<br/>maximum size: '+imgsize+'mb',
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}else if( err.file && err.file.name ){
				TTAlert({
					msg: t("Error: ") + err.file.name + ' ' + err.message,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}else{
				TTAlert({
					msg: t("Error: ") + err.message,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
			uploader.refresh(); // Reposition Flash/Silverlight
		});

		uploader.bind('FileUploaded', function(up, file,response) {
			$('.upload-overlay-loading-fix').hide();
			upload_started = 0;
			
			var fid = file.id;
			var Jresponse;
			try{
				Jresponse = $.parseJSON( response.response );
			}catch(Ex){
				
				return ;
			}

			if(Jresponse.status !== 'ok' ){
				TTAlert({
					msg: Jresponse.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}else{	
				//open fancybox
				if(parseInt(Jresponse.which_uploader)!=3){
					$('#picfancyboxbutton').fancybox({
						"padding"	:0,
						"margin"	:0,
						"width"			: Jresponse.stanwidth,
						"height"			: Jresponse.stanheight,
						"transitionIn"		: "none",
						"transitionOut"	: "none",
						"autoSize"		: true,
						"type"				: "iframe"
					});
					$('#picfancyboxbutton').attr("href", ReturnLink("/ajax/channel-fancy.php?path="+Jresponse.path+"&name="+Jresponse.name+"&type="+Jresponse.which_uploader+"&h="+Jresponse.stanheight) );
					$('#picfancyboxbutton').click();
				}else{
					var ww=400/Jresponse.width;
					var hh=225/Jresponse.height;
					var diff=ww;
					if(hh<ww){
						diff=hh;	
					}
					var newx=(400- Math.round(diff*Jresponse.width))/2;
					var newy=(225- Math.round(diff*Jresponse.height))/2;
					
					$("#uploadDropDiv"+Jresponse.which_uploader+" .ImageStanInside").attr('data-color','');
					$("#uploadDropDiv"+Jresponse.which_uploader+" .ImageStanInside").css('background-color','#ffffff');
					
					var curpath=ReturnLink('/'+Jresponse.path+""+Jresponse.name);
					var newstrimage='<img src="'+curpath+ '?x=' + Math.random() + '" style="top:'+newy+'px; left:'+newx+'px;"/>';
					updateImage("#uploadDropDiv"+Jresponse.which_uploader+" .ImageStanInside",newstrimage,Jresponse.name);
				}
			}
		});
	}
}
function setImagesChannelUp(){
	InitChannelUploader(1,15);
	InitChannelUploader(2,15);
	InitChannelUploader(3,15);	
}