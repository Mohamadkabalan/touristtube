var currentPage=0;
var one_object=0;
var globorderby="id";
var globtxtsrch="";

$(document).ready(function() {
	
	$("#load_more_next").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		currentPage++;
		displaypicrelated();
	});
	
	$("#searchbut").click(function(){
		var txtvalsrch=""+$("#srchtxt").val();
		if(txtvalsrch=="search"){
			txtvalsrch="";
		}
		globorderby=$("#sortby").val();
		globtxtsrch=txtvalsrch;
		currentPage=0;
		$('#favoritesimagecontainer ul').html('');
		displaypicrelated();
	});
	
	$(document).on('click',"#favoritesimagecontainer ul li .clsimg",function(){
            
            
String.prototype.format = function() {
    var formatted = this;
    for( var arg in arguments ) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};

		var curbut=	$(this).closest('li');
		var typemedia="photo";
		if(curbut.attr('data-type')=="v"){
			typemedia="video";
		}
		TTAlert({
			msg: sprintf( t("confirm to delete selected %s from favorites") , [typemedia] ) ,
			type: 'action',
			btn1: t('cancel'),
			btn2: t('confirm'),
			btn2Callback: function(data){
				if(data){
					$('.upload-overlay-loading-fix').show();					
					var target = curbut.attr("id");
                                        var data_val = curbut.attr("data-val");
					$.post(ReturnLink('/ajax/profile_del_fav.php'), {vid:target,entity_type:data_val},function(data){
						if(data){
							curbut.remove();                                                        
							one_object =1;                                                      
                                                        //currentPage=0;
                                                        //$('#favoritesimagecontainer ul').html('');
							displaypicrelated();	
						}else{
							$('.upload-overlay-loading-fix').hide();	
						}
					});
				}
			}
		});	
	});	
	$(document).on('mouseover',"#favoritesimagecontainer ul li .selectitem",function(){
		$(this).css('opacity',1);
	});
	$(document).on('mouseout',"#favoritesimagecontainer ul li .selectitem",function(){
		$(this).css('opacity',0);
	});
	initDocumentFavorites();
});
function initDocumentFavorites(){
	$(".imgbk_butons .imgbk_buts").mouseover(function(){		
		var posxx=$(this).offset().left-$('#ProfileHeaderInternal').offset().left-252;
		var posyy=$(this).offset().top-$('#ProfileHeaderInternal').offset().top-21;
		$('.ProfileHeaderOver .ProfileHeaderOverin').html($(this).attr('data-title'));
		$('.ProfileHeaderOver').css('left',posxx+'px');
		$('.ProfileHeaderOver').css('top',posyy+'px');
		$('.ProfileHeaderOver').stop().show();
	});
	$(".imgbk_butons .imgbk_buts").mouseout(function(){
		$('.ProfileHeaderOver').hide();
	});
}

function addValue1(obj){
	if($(obj).attr('value') == '') $(obj).attr('value',$(obj).attr('data-value'));
} 
function removeValue1(obj) {
	if($(obj).attr('value') == $(obj).attr('data-value')) $(obj).attr('value','');
}
function checkSubmitfavoritespagevalue(e){
   if(e && e.keyCode == 13){
      $('#favoritespagego').click();
   }else{
		return isNumberKey(e);   
   }
}
function checkSubmit(e){
   if(e && e.keyCode == 13){
      $('#searchbut').click();
   }
}
function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	
	return true;
}
function displaypicrelated(){
	$('.upload-overlay-loading-fix').show();
	$.post(ReturnLink('/ajax/ajax_getpicfavorites.php'), {currentPage:currentPage,txtsrch:globtxtsrch,globorderby:globorderby,one_object:one_object},function(data){
		if(data!=false){
			one_object = 0;
			$('#favoritesimagecontainer ul').append(data);
			var currPageStatus=$('#favoritesimagecontainer .currPageStatus');
			
			if((""+currPageStatus.attr('data-value'))=="0"){
				$("#load_more_next").hide();
			}else{
				$("#load_more_next").show();	
			}
			$('.data_head_text span.yellowbold12').html('('+currPageStatus.attr('data-count')+')');
			currPageStatus.remove();			
			initDocumentFavorites();
		}else{
			$("#load_more_next").hide();	
		}
		
		$('.upload-overlay-loading-fix').hide();
	});
}