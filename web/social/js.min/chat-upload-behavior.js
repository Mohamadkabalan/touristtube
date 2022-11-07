var uploader ;
var containerDiv ;

function initUpload(containerDivv,quickupload)
{
		uploader = new plupload.Uploader({
				runtimes : 'html5,flash',
				autostart : true,
				multipart: true,
				browse_button : quickupload,
				container: "chatContainer",
				max_file_size : '200mb',
				url : ReturnLink('/chat_upload_media.php'),
				///resize : {width : 320, height : 240, quality : 72},
				 //thumb : {width: 100, height: 100, quality: 90},
				flash_swf_url : ReturnLink('/js/plupload/js/plupload.flash.swf'),
				filters : [
					{title : "Image files", extensions : "jpg,gif,png"},
					{title : "Video files", extensions : "mp4,wmv,flv,f4v"}
				],
				init: attachCallbacks
		});
		containerDiv = containerDivv;
/*uploader.bind('Init', function(up, params) {

	
	//$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
});*/
		uploader.init();

/*$('#uploadfiles').click(function() {
	uploader.start();
	return false;
});*/

}
function attachCallbacks(uploader)
{
uploader.bind('FilesAdded', function(up, files) {
	

	
	for (var i in files) {
		if(i < files.length)
		{
			$("#"+containerDiv).parent().find('.ChatLog .jspPane').append('<div class="ChatMyMessage">'+
			'<span class="User">'+t("Me")+'</span>'+
			'<span class="Date">17:25:30</span>'+
			'<span class="Msg"><div class="pending_files" id="' + files[i].id + '">'+
			'<div class="filename"> '+t('Image File')+' <br> '+t('size')+' : ' + plupload.formatSize(files[i].size) + '<b></b></span>'+
			'</div></div>');
			
		}
		initScrollPane($("#"+containerDiv).parent().find('.ChatLog'));
		
	}

   setTimeout(function () { up.start(); }, 500);
	
});


uploader.bind('UploadProgress', function(up, file) {
	
	var str = "<br><span>" + file.percent + "%</span><br>";
	//var str = "<br><span>" + file.percent + "%</span><br>";
	
	str += "<div class='progressContainer'><div class='progresss' style='width:"+file.percent+"%;'></div></div>";
	
	$("#"+file.id).find('b').html(str);
	
	
});

uploader.bind('FileUploaded', function(up, file,ret) {


	var obj = jQuery.parseJSON(ret.response);
	
	//console.log(ret.response);
	
	var imgFileName = "media/chat_media/"+obj.filename
	var fileExt = file.name.substr(-3);
	
	var realImage = imgFileName;
	
	
	/*if(inArray(fileExt,videoArray))
	{
		imgFileName = "imgs/video-icon.png";
	}*/
	
	 $('#' + file.id).delay(1500).prepend('<img src="'+AbsolutePath+'/'+imgFileName+'" height="50" alt="'+realImage+'" width="92">');
	 $('#' + file.id).find('b').html('<a href="'+AbsolutePath+'/'+imgFileName+'" target="_blank">'+t("Download")+' </a>');
	 
	 var toChat = $('#' + file.id).parent().parent().parent().parent().parent().attr('id');
	 toChat = toChat.replace("dataTuber","");
	 
	 
	 
	 var SendChatData = {
		op: 'CHAT',
		to: toChat,
		msg: $('#' + file.id).parent('.Msg').html(),
		timezone: TimeZone
	};
		
	Communicate(SendChatData);

});


uploader.bind('BeforeUpload', function (up, file) {
    up.settings.multipart_params = {'table': '','table_row_id':''}
});
}
