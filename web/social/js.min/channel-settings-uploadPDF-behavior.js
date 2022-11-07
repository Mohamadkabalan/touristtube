
var uploaderPDF = null;
var requirements_met = true;
var upload_started;

function InitChannelUploaderPDF(which_uploader){
	
	//if( uploaderPDF !== null ) return ;
	
	uploaderPDF = new plupload.Uploader({
		runtimes : 'html5,flash',
		button_browse_hover : true,
		max_file_size : 100 + 'mb',
		container: 'uploadContainer' + which_uploader,
		browse_button : 'uploadBtn' + which_uploader,
		url :  ReturnLink('/social/channel-upload-file.php'),
		flash_swf_url : ReturnLink('/js/plupload/plupload.flash.swf'),
		multi_selection: false,
		multipart_params : {
            channel_id: globchannelid,
			which_uploader: which_uploader,
			SID : $('#SID').val()
        },
		filters : [
			{title : "Image files", extensions : "pdf"},
		],
		init: attachCallbacks
	});
	
	uploaderPDF.bind('Init', function(up, params) {
		
		if( (params.runtime == 'flash') && !swfobject.hasFlashPlayerVersion("10") ){
			requirements_met = false;
			if(pagename!="brochure"){		
				TTAlert({
					msg: t("Please update your flash installation to at least version 10"),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}else{
				$('#brochureerror').html(t("Please update your flash installation to at least version 10"));	
			}
		}
	});
	
	uploaderPDF.init();
	
	function attachCallbacks(uploader){

		uploader.bind('FilesAdded', function(up, files) {
			up.settings.multipart_params = {
									channel_id: globchannelid,
									which_uploader: which_uploader,
									SID : $('#SID').val()
								};
			
			if(!requirements_met){
				if(pagename!="brochure"){
					TTAlert({
						msg: t("Please update your flash installation to at least version 10"),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}else{
					$('#brochureerror').html(t("Please update your flash installation to at least version 10"));	
				}
				return false;
			}

			setTimeout(function(){
				uploader.start()
			}, 100);

			$.each(files, function(i, file) {
				var fid = file.id;
				addAction(file,fid,which_uploader);
				//actaually only one upload
				$('#uploadContainer'+ which_uploader+' .filediv').html('<div class="filename"></div><div class="progressbar"><span class="progressvalue"></span><div class="progress"></div></div><span class="status" ></span><span class="cancel" >&nbsp;</span><span class="pause" >&nbsp;</span><span class="resume" >&nbsp;</span><div class="pdf_icon"></div>');
				$('#uploadContainer'+ which_uploader).find('span.progressvalue').text('0%');
			});
		});

		uploader.bind('UploadFile', function(up, file) {
			var fid = file.id;
			
			upload_started = 1;
			$('#uploadContainer'+ which_uploader+' .pause').show();
			$('#uploadContainer'+ which_uploader+' span.status').html( t("Uploading ...") );
			$('#uploadContainer'+ which_uploader).find('span.progressvalue').text('0%');
		});

		uploader.bind('UploadProgress', function(up, file) {

			var fid = file.id;
			//file.percent
			
			$('#uploadContainer'+ which_uploader).find('div.progress').css('width', file.percent+'%');
			$('#uploadContainer'+ which_uploader).find('div.progress').css('background-color','#f0bf1b');
			$('#uploadContainer'+ which_uploader).find('span.progressvalue').text(file.percent+'%');
		});

		uploader.bind('Error', function(up, err) {
			if(pagename!="brochure"){
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
			}else{
				if(err.code==-600){
					$('#brochureerror').html(err.message+'<br/>maximum size: '+imgsize+'mb');
				}else if( err.file && err.file.name ){
					$('#brochureerror').html(t("Error: ") + err.file.name + ' ' + err.message);
				}else{
					$('#brochureerror').html(t("Error: ") + err.message);
				}
			}
			uploader.refresh(); // Reposition Flash/Silverlight
		});

		uploader.bind('FileUploaded', function(up, file,response) {

			upload_started = 0;
			
			var fid = file.id;
			var item = $('#uploadContainer'+ which_uploader);
			var Jresponse;
			try{
				Jresponse = $.parseJSON( response.response );
			}catch(Ex){
				
				return ;
			}

			if(Jresponse.status !== 'ok' ){
				if(pagename!="brochure"){
					TTAlert({
						msg: Jresponse.error,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}else{
					$('#brochureerror').html(Jresponse.error);	
				}
			}else{	
				item.find('div.progress').css({'width': '100%','background-color' : '#73c563','background-image' : 'none'});
				item.find('span.progressvalue').text('100%').css({'color' : '#686868'});
				item.addClass('success').find('span.status').html('<span class="status2">'+t("Upload Complete")+'</span>');
				
				item.find('.pause').hide();
				item.find('.resume').hide();
				item.find('.cancel').hide();
				item.find('.pdf_icon').show();
				item.attr('data-pdf',Jresponse.name);
			}
		});
	}
}
function addAction(file,i,which_uploader){
	
	$(document).on('click',".pause" ,function(){
		var upload_file = uploaderPDF.getFile(i);
		if( !upload_file || upload_file.status != plupload.UPLOADING ){
			return ;
		}
		var curbut=$(this);
				
		curbut.hide();
		$('.resume').show();
		$('span.status').html( t("Paused") );
		$('div.progress').css('width', 0 + '%');
		$('span.progressvalue').text(0+'%');
		PauseUpload();
	});
	
	$(document).on('click',".resume" ,function(){
		var upload_file = uploaderPDF.getFile(i);
		if(  !upload_file || upload_file.status == plupload.UPLOADING ){
			return ;
		}
		$(this).hide();
		$('.pause').show();
                $('span.status').html(t("Uploading"));
		ResumeUpload();
	});
	$(document).on('click',".cancel" ,function(){
		var upload_file = uploaderPDF.getFile(i);
		if( !upload_file ){
			
		}else if(upload_file.status == plupload.UPLOADING ){
			uploaderPDF.stop();
			uploaderPDF.removeFile(file);
		}else{
			uploaderPDF.removeFile(file);
		}
						
		$('#uploadContainer'+ which_uploader+' .filediv').html('');
	});	
}
function PauseUpload(){
	uploaderPDF.stop();
}

function ResumeUpload(){
	uploaderPDF.start();
}
