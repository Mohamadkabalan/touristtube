$(document).ready(function(){
	$(document).on('click',"#uploadheadbar .uploadinsidemenu" ,function(){
		var swich=""+$(this).attr('id');
		switch(swich){
			case 'uploadinsidemenu1':
				document.location.href=""+ReturnLink('/upload');
			break;
			case 'uploadinsidemenu2':
				document.location.href=""+ReturnLink('/upload-album');
			break;
			case 'uploadinsidemenu3':
				document.location.href=""+ReturnLink('/upload-list');
			break;
		}		
	});
});