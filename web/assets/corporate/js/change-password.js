var frmValidator;
$(document).ready(
        function()
        {
	        frmValidator = new TTFormValidator("#chgPassformId", {
		        msgPosition : "bottom"
	        });

	        $("#submit").click(
	                function()
	                {
		                $('.changepasswoerdpopuocontainer .error_hints').html('');
		                var uname = $('input[name=uname]').val();
		                var new_pass = $('input[name=NewPassword]').val();
		                var new_pass2 = $('input[name=ConfirmNewPassword]').val();
		                var old_pass = $('input[name=OldPassword]').val();
		                var min_pswd_length = 8;

		                if (frmValidator.validate()) {

			                if (old_pass.length == 0) {
				                $('.changepasswoerdpopuocontainer .error_hints').html(
				                        Translator.trans("Please specify your password"));
				                return false;
			                }

			                if (new_pass.length > 0 && new_pass.length < min_pswd_length) {
				                $('.changepasswoerdpopuocontainer .error_hints').html(
				                        sprintf(Translator.trans("New password must be minimum %s characters long"),
				                                [ min_pswd_length ]));
				                return false;
			                }

			                if (new_pass.length > 0 && new_pass != new_pass2) {
				                $('.changepasswoerdpopuocontainer .error_hints').html(
				                        Translator.trans("Confirm new password mismatch"));
				                return false;
			                }

			                var myData = new Object();
			                myData['NewPassword'] = new_pass;
			                myData['OldPassword'] = old_pass;

			                $('.upload-overlay-loading-fix').show();
			                $.ajax({
			                    url : generateLangURL('/ajax/account-settings'),
			                    data : {
			                        uname : uname,
			                        new_pass : new_pass,
			                        old_pass : old_pass
			                    },
			                    type : 'post',
			                    success : function(data)
			                    {
				                    $('.upload-overlay-loading-fix').hide();
				                    var jres = null;
				                    try {
					                    jres = data;
					                    var status = jres.status;
				                    } catch (Ex) {
				                    }
				                    if (!jres) {
					                    return;
				                    }
				                    if (jres.status == 'ok') {
					                    closeModalChangePassword(jres.msg);
				                    } else {
					                    $('.changepasswoerdpopuocontainer .error_hints').html(jres.msg);
				                    }
			                    }
			                });
		                }
	                });
	        $(".ConfirmNewPassword").keydown(function(e)
	        {
		        if (e.keyCode == 13) {
			        $("#submit").click();
		        }
	        });
        });
