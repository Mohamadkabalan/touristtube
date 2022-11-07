/*var currentPage=0;
var globorderby="id";
var globtxtsrch="";*/

$(document).ready(function() {
	$('#requestinvitationcontainerclose, #requestinvitationcontainerformbut2').click(function(){
		//window.close();
       //closeWindow();
	   $('.fancybox-close').click();
	});
	function closeWindow() {
		//window.open('','_self');
		//window.close();
	}
	$('#requestinvitationcontainerformbut1').click(function(){
		if(verifyFormList('requestinvitationcontainer')){
			var $currobjselected=$('#requestinvitationcontainer');	
			if(!validateEmail($('#inputtxt4',$currobjselected).val())){
				$('#inputtxt4',$currobjselected).parent().addClass('mandatory');
				TTAlert({
					msg: t('Please enter a valid email'),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return;
			}
			$.ajax({
				url: ReturnLink('/ajax/request_invite.php'),
				data: $('input,textarea,select', $('#requestinvitationcontainer')).serialize(),
				type: 'post',
				success: function(resp){
					var Jresponse;
					try{
						Jresponse = $.parseJSON( resp );
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
					if(!Jresponse){
						TTAlert({
							msg: t("Couldn't Process Request. Please try again later."),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						return ;
					}
					TTAlert({
						msg: Jresponse.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					if(Jresponse.status == 'ok') $('.fancybox-close').click();
				}
			});
		}
	});
});

function addValue1(obj){
	if($(obj).attr('value') == '') $(obj).attr('value',$(obj).attr('data-value'));
} 
function removeValue1(obj) {
	if($(obj).attr('value') == $(obj).attr('data-value')) $(obj).attr('value','');
}
function verifyFormList(which){
	var ok = true;
	var $currobjselected=$('#'+ which);	
	$('.requestinvitationcontainerforminput').removeClass('mandatory');
	$('input,select,textarea',$currobjselected ).each(function(){
		if($(this).val() == '' || $(this).val()==$(this).attr('data-value')){
			$(this).parent().addClass('mandatory');
			ok = false;
		}		
	});
	
	if(!ok){
		TTAlert({
			msg: t('All fields are mandatory'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		return false;
	}else{
		return true;
	}
}
function addValue2(obj){
	if($(obj).attr('value') == '') $(obj).attr('value',$(obj).attr('data-value'));
} 
function removeValue2(obj) {
	if($(obj).attr('value') == $(obj).attr('data-value')) $(obj).attr('value','');
}
function  getObjectData(obj){
	var mystr=""+obj.val();
	if(mystr==obj.attr('data-value')){
		mystr="";	
	}
	return mystr;
}