$(document).ready(function () {
    ttModal = window.getTTModal("myModalZ", {});
    $(document).on('click', ".yellowreset", function () {
	var $this = $(this);
	var new_pass = $('input[name=NewPassword]').val();
	var new_pass2 = $('input[name=ConfirmNewPassword]').val();

	if (checkAccountInfo(new_pass, new_pass2)) {
	    $('.upload-overlay-loading-fix').show();
	    var email = $this.attr('data-email');
	    $.ajax({
		type: "POST",
		url: generateLangURL('/corporate/password/update-forgot','corporate'),
		data: {userId: email, newPass: new_pass},
		success: function (Jresponse) {
		    try {
			console.log(Jresponse);
		    } catch (Ex) {
			return;
		    }
		    if (Jresponse.success) {
			$('.confirm_container_unsubscribe').hide();
			$('#confirm_container_hide').show();
		    } else {
			ttModal.alert( Jresponse.message, null, null, {ok:{value:"close"}});			
		    }
		    $('.upload-overlay-loading-fix').hide();
		}
	    });
	}
    });
});
function checkAccountInfo(new_pass,new_pass2){
    $('.confirm_container_unsubscribe .errorclass').html('');
    var min_pswd_length = 8;

    if(new_pass.length==0 || (new_pass.length < min_pswd_length) ){
            $('.confirm_container_unsubscribe .errorclass').html( sprintf( Translator.trans('Password must be minimum %s characters long') , [min_pswd_length]) );
            return false;
    }else if( new_pass != new_pass2 ){
            $('.confirm_container_unsubscribe .errorclass').html(Translator.trans('Confirm new password mismatch'));
            return false;
    }
    return true;
}
function checkSubmitConfirm(e){
   if(e && e.keyCode == 13){
      $('.yellowreset').click();
   }
}
