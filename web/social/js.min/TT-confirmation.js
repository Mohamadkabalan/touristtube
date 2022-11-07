
$(document).ready(function() {
	$(document).on('click',"#confirm_but_reset_password" ,function(){
		var $this=$(this);
		var new_pass = getObjectData($('.confirm_container_unsubscribe input[name=NewPassword]'));
		var new_pass2 = getObjectData($('.confirm_container_unsubscribe input[name=ConfirmNewPassword]'));
		
		if(checkAccountInfo(new_pass,new_pass2)){
			$('.upload-overlay-loading-fix').show();
			var email= $this.attr('data-email');
			var dataString = 'email='+ email+'&new_pass='+new_pass;
			$.ajax({
				type: "POST",
				url: ReturnLink("/ajax/update_forgot_password.php"),
				data: dataString,
				success: function(res) {					
					var Jresponse;
					try{
						Jresponse = $.parseJSON( res );
					}catch(Ex){
						
						
					}
					if(Jresponse.status=='ok'){
						$('.confirm_container_unsubscribe').hide();
						$('#confirm_container_hide').show();
					}else{
						TTAlert({
							msg: Jresponse.error,
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
					}
					$('.upload-overlay-loading-fix').hide();
				}
			});
		}
	});
	$(document).on('click',".reactivate_notifications" ,function(){
		var $this=$(this);
		var email= $this.attr('data-email');
		var dataString = 'email='+ email;
		$.ajax({
			type: "POST",
			url: ReturnLink("/ajax/reactivate_notifications.php"),
			data: dataString,
			success: function(res) {					
				var Jresponse;
				try{
					Jresponse = $.parseJSON( res );
				}catch(Ex){
					
					
				}
				TTAlert({
					msg: Jresponse.msg,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
		});
	});
	$(document).on('click',"#confirm_but_delete_channel" ,function(){
		var $this=$(this);
		var channel= $this.attr('data-channel');
		var dataString = 'channel='+ channel;
		$.ajax({
			type: "POST",
			url: ReturnLink("/ajax/channel_unsubscribe_delete.php"),
			data: dataString,
			success: function(res) {					
				var Jresponse;
				try{
					Jresponse = $.parseJSON( res );
				}catch(Ex){
					
					
				}
				if(Jresponse.error=="error"){
					TTAlert({
						msg: Jresponse.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}else{
					$('#confirm_text_title').hide();
					$('#confirm_but_delete_channel').hide();
					$('.confirm_text_confirm').show();	
				}
			}
		});
	});
	$(document).on('click',"#confirm_but_delete_user" ,function(){
		var $this=$(this);
		var $code= $this.attr('data-code');
		var dataString = 'code='+ $code;
		$.ajax({
			type: "POST",
			url: ReturnLink("/ajax/user_unsubscribe_delete.php"),
			data: dataString,
			success: function(res) {					
				var Jresponse;
				try{
					Jresponse = $.parseJSON( res );
				}catch(Ex){
					
					
				}
				if(Jresponse.error=="error"){
					TTAlert({
						msg: Jresponse.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}else{
					$('#confirm_text_title').hide();
					$('#confirm_but_delete_user').hide();
					$('.confirm_text_confirm').show();	
				}
			}
		});
	});
	$(document).on('click',"#confirm_but_accept" ,function(){
            $('.confirm_container .requestinvitation_wrong_credentials').html('');
            $(".confirm_container .confirm_input").focus().removeClass('InputErr');
            var rpassword = getObjectData($(".confirm_container #pass"));
            var crpassword = getObjectData($(".confirm_container #cpass"));
            var remail = $(".confirm_container").attr('data-val');
            if(rpassword.length == 0 || !(rpassword.match(/([\<])([^\>]{1,})*([\>])/i) == null)){
                $(".confirm_container #pass").focus().addClass('InputErr');
                $('.confirm_container .requestinvitation_wrong_credentials').html(t('please enter your password.'));
                return false;
            }else if(rpassword.length <6 ){
                $(".confirm_container #pass").focus().addClass('InputErr');
                $('.confirm_container .requestinvitation_wrong_credentials').html(t('please your password should be at least 6 characters long.'));
                return false;
            }else if(crpassword.length == 0 || !(crpassword.match(/([\<])([^\>]{1,})*([\>])/i) == null)){
                $(".confirm_container #cpass").focus().addClass('InputErr');
                $('.confirm_container .requestinvitation_wrong_credentials').html(t('please confirm your password.'));
                return false;
            }else if(crpassword != rpassword ){
                $(".confirm_container #cpass").focus().addClass('InputErr');
                $('.confirm_container .requestinvitation_wrong_credentials').html(t('password does not match.'));
                return false;
            }
            var rusername = getObjectData($(".confirm_container #username"));
            
            $.ajax({
                url: ReturnLink('/ajax/ajax_register_accept.php'),
                data: {YourEmail: remail, YourPassword: rpassword, YourCPassword: crpassword,rusername:rusername},
                type: 'post',
                success: function(data) {
                    var jres = null;
                    try {
                        jres = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }
                    if (jres.error) {              
                        if (jres.YourUserName){
                            $("#YourUserName").val(jres.YourUserName);
                        }
                        $('.confirm_container .requestinvitation_wrong_credentials').html(jres.error);
                    } else {
                        window.top.location.href = ReturnLink('/touristtube-video');
                    }
                }
            });
        });
	$(document).on('click',"#confirm_but1" ,function(){	
		var EmailField = getObjectData($("#confirm_email"));
		var PasswordField = getObjectData($("#confirm_pass"));
		
		$('#confirm_pass, #confirm_email').removeClass('InputErr');
		
		if(EmailField.length == 0 || !(EmailField.match(/([\<])([^\>]{1,})*([\>])/i) == null)){
			$('#confirm_email').focus().addClass('InputErr');
			return false;
		}else if(PasswordField.length == 0 || !(PasswordField.match(/([\<])([^\>]{1,})*([\>])/i) == null)){
			$('#confirm_pass').focus().addClass('InputErr');
			return false;
		}else{
			var dataString = 'EmailField='+ EmailField + '&PasswordField=' + PasswordField;
			$.ajax({
				type: "POST",
				url: ReturnLink("/ajax/process.php"),
				data: dataString,
				success: function(data) {
                                    var Jresponse;
                                    try{
                                        Jresponse = $.parseJSON(data);
                                    }catch(Ex){						
                                            return;
                                    }
                                    if(Jresponse.status == 'ok'){
                                       window.location.reload();					
                                    }else{
                                        TTAlert({
                                            msg: t("Invalid credentials"),
                                            type: 'alert',
                                            btn1: t('ok'),
                                            btn2: '',
                                            btn2Callback: null
                                        });
                                    }
				}
			});
		}
	});
	
        
    $(".signin").fancybox({
        padding: 0,
        margin: 0,
        onComplete: clearForm()
    });
    $('#signInbtn').live("click", function() {

        var EmailField = getObjectData($("#SEmailField"));
        var PasswordField = getObjectData($("#SPasswordField"));

        $('#SPasswordField, #SEmailField').removeClass('InputErr');

        if (EmailField == '' || EmailField == 'yourname@email.com' || !(EmailField.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $('#SEmailField').focus().addClass('InputErr');
            return false;
        } else if (PasswordField == '' || PasswordField == 'Your Password' || !(PasswordField.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $('#SPasswordField').focus().addClass('InputErr');
            return false;
        } else {

            var dataString = 'EmailField=' + EmailField + '&PasswordField=' + PasswordField;

            $.ajax({
                type: "POST",
                url: ReturnLink("/ajax/process.php"),
                data: dataString,
                success: function(data) {
                    CloseFancyBox();
                    var Jresponse;
                    try {
                        Jresponse = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }

                    var selctedID = $("#InsideNormal .selected").attr("id");

                    clearForm();
                    if (Jresponse.status == 'ok') {
                        $.fancybox.close();
                        window.top.location.href = ReturnLink('register/' + selctedID);
                    }else{
                        TTAlert({
                            msg: t("Invalid credentials"),
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
    });
    $('#resetbtn').live("click", function(e) {

        var EmailForgotForm = getObjectData($("#SEmailForgotForm"));
        $('#SEmailForgotForm').removeClass('InputErr');
        if (EmailForgotForm == '' || EmailForgotForm == 'yourname@email.com' || !(EmailForgotForm.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $('#SEmailForgotForm').focus().addClass('InputErr');
        } else {

            var dataString2 = 'EmailForgotForm=' + EmailForgotForm;

            $.ajax({
                type: "POST",
                url: ReturnLink('/ajax/passprocess.php'),
                data: dataString2,
                success: function(html2) {
                    clearForm();
                    var jresp;
                    try {
                        jresp = $.parseJSON(html2);
                    } catch (exception) {
                        return;
                    }

                    if (!jresp)
                        return;

                    if (jresp.status == 'ok') {
                        $.fancybox.close();
                        ShowClaimdiv(t("an email is sent to you to reset your password"));
                    } else {
                        TTAlert({
                            msg: t("Invalid email, please try again"),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                    }

                }
            });
        }
    });
});
function clearForm() {
    $("#SEmailField").val(t('yourname@email.com'));
    $("#SPasswordField").val(t('Your Password'));

    $("#SEmailForgotForm").val(t('yourname@email.com'));
}
function checkSubmitConfirm(e){
   if(e && e.keyCode == 13){
      $('#confirm_but1').click();
   }
}
function checkSubmitAccept(e){
   if(e && e.keyCode == 13){
      $('#confirm_but_accept').click();
   }
}
function checkAccountInfo(new_pass,new_pass2){
	$('.confirm_container_unsubscribe input').removeClass('InputErr');
	$('.confirm_container_unsubscribe .errorclass').html('');
	var min_pswd_length = 6;
	
	if(new_pass.length==0 || (new_pass.length < min_pswd_length) ){
		$('.confirm_container_unsubscribe .errorclass').html( sprintf( t('Password must be minimum %s characters long') , [min_pswd_length]) );
		$('.confirm_container_unsubscribe input[name=NewPassword]').addClass('InputErr');
		return false;
	}else if( new_pass != new_pass2 ){
		$('.confirm_container_unsubscribe .errorclass').html(t('Passwords mismatch'));
		$('.confirm_container_unsubscribe input[name=NewPassword]').addClass('InputErr');
		$('.confirm_container_unsubscribe input[name=ConfirmNewPassword]').addClass('InputErr');
		return false;
	}
	return true;
}
function ShowClaimdiv(data) {
    $('.confirm_container .requestinvitation_wrong_credentials').html('');
    if (data == 1) {
        show = t('please ')+'<a href="#signIn" class="signin">'+t('sign in')+'</a> '+t('using this email or ')+' <a href="#resetIn" class="signin">'+t('reset your password')+'</a>';
    } else {
        show = data;
    }
    $("#ClaimEmail").css('visibility', 'visible');
    $("#ClaimEmail").html(show);
}