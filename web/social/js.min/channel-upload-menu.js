$(document).ready(function(){
	$(document).on('click',"#uploadheadbar .uploadinsidemenu" ,function(){
		var swich=""+$(this).attr('id');
		switch(swich){
			case 'uploadinsidemenu1':
				document.location.href=""+ReturnLink('/channel-upload/'+globchannelid);
			break;
			case 'uploadinsidemenu2':
				document.location.href=""+ReturnLink('/channel-upload-album/'+globchannelid);
			break;
			case 'uploadinsidemenu3':
				document.location.href=""+ReturnLink('/channel-upload-list/'+globchannelid);
			break;
		}		
	});
});