$(document).ready(function(){
    $(document).on('click', '.forget_pass', function () {
		$(".signincontainer .wrong_credentials").html('');
		$(".signincontainer .wrong_credentials").hide();
		$(".signincontainer").hide();
		$(".forgetpasswordcontainer").show();
    });
    $(document).on('click', '.cancel_forget', function () {
		$(".forgetpasswordcontainer .wrong_credentials").html('');
		$(".forgetpasswordcontainer .wrong_credentials").hide();
		$(".forgetpasswordcontainer").hide();
		$(".signincontainer").show();
    });
    $('.SendForgot').click(function (e) {
	var emailforget = $("#emailforget").val();
	if (emailforget == '' || emailforget == 'Your Email / Username' || !(emailforget.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
	    $('#ForgetPassDiv2').hide();
	    $('.wrong_credentials').html(Translator.trans('Please insert your email or username.'));
	    $('.wrong_credentials').show();
	    $('#emailforget').focus();
	} else {
		showTTOverlay('sign_in_container');
	    $('.form-group-FCB').show();
	    $('.wrong_credentials').hide();
	    $('.wrong_credentialspass').hide();
	    $('.form-group-FCB').show();
	    var dataString2 = 'EmailForgotForm=' + emailforget;
	    var resetPasswordPath = $("#resetPasswordPath").val();
	    $.ajax({
		type: "POST",
		url: resetPasswordPath,
		data: dataString2,
		success: function (jresp) {
		    try {
		    } catch (exception) {
				hideTTOverlay();
				return;
		    }
		    	hideTTOverlay();
		    if (!jresp)
				return;

		    if (jresp.success) {
				$('.form-group-FCB').hide();
				$('.wrong_credentialspass').html(jresp.message);
				$('.wrong_credentialspass').show();
				$(".signincontainer").show();
				$(".forgetpasswordcontainer").hide();
		    } else {
				$('#ForgetPassDiv2').hide();
				$('.wrong_credentials').html(Translator.trans(jresp.message));
				$('.wrong_credentials').show();
		    }
		}
	    });
	}
    });

    //when enter key is pressed
    $(document).bind('keypress', function (e) {
	//when log in container is shown
	if (e.keyCode == 13 && $('.logincontainer').is(':visible')) {
	    $('#LogInSignIn').trigger('click');
	}
	//when forgot password is shown
	if (e.keyCode == 13 && $('.forgot_your_password').is(':visible')) {
	    $('.SendForgot').trigger('click');
	}
    });

    $('#LogInSignIn').click(function () {
	$('.wrong_credentials').html();
	$('.wrong_credentials').hide();

	var username = $("#username").val().trim();
	var password = $("#password").val().trim();

	if (username.length == 0) {
	    $('.wrong_credentials').html(Translator.trans('Please insert username or email.'));
	    $('.wrong_credentials').show();
	    $("#username").focus();
	    return false;
	}

	if (password.length < 8) {
	    $('.wrong_credentials').html(Translator.trans('Please your password should be at least 8 characters long.'));
	    $('.wrong_credentials').show();
	    $("#password").focus();
	    return false;
	}

	$(this).prop('type', 'submit');

    });
    $('#pwd').keydown(function (e) {
	if (e.keyCode == 13)
	    $('#LogInSignIn').click();
    });
    $('#YourCPassword').keydown(function (e) {
	if (e.keyCode == 13)
	    $('#CreateMyAccountNew').click();
    });
    $('#emailforget').keydown(function (e) {
	if (e.keyCode == 13)
	    $('.SendForgot').click();
    });
    $("#CreateMyAccountNew").click(function () {
	if (ValidateRegisterFormNew()) {
	    if ($("#FormResult").html() == '') {
		$("#ClaimEmail").hide();
		showTTOverlay('registerContainer');

		var myData = new Object();
		myData['yourUserName'] = $("#YourUserName").val();
		myData['yourEmail'] = $("#YourEmail").val();
		myData['yourPassword'] = $("#YourPassword").val();
		myData['password'] = $("#YourCPassword").val();
		myData['userId'] = 0;

		var registerPath = $("#registerPath").val();

		$.ajax({
		    url: registerPath,
		    data: myData,
		    type: 'post',
		    success: function (data) {
			console.log(data);
			try {
			} catch (Ex) {
			    hideTTOverlay();
			    return;
			}

			if (data.success) {
			    console.log('success');
			    hideTTOverlay();
			    window.top.location.href = generateLangURL('/register-success');

			} else {
			    hideTTOverlay();
			    $("#FormResult").show();
			    $("#FormResult").html(data.message);
			}
		    }
		});
	    }
	}
    });
});
function ValidateRegisterFormNew() {
    if ($("#YourEmail").val() == '') {
        $("#FormResult").show();
        $("#FormResult").html(Translator.trans('Please insert your email.'));
        $("#YourEmail").focus();
        return false;
    } else if (!validateEmail($("#YourEmail").val())) {
        $("#FormResult").show();
        $("#FormResult").html(Translator.trans('Please insert a correct email.'));
        $("#YourEmail").focus();
        return false;
    } else if ($("#YourPassword").val() == '') {
        $("#FormResult").show();
        $("#FormResult").html(Translator.trans('Please insert your password.'));
        $("#YourPassword").focus();
        return false;
    } else if( ($("#YourPassword").val()).length < 8) {
        $("#FormResult").show();
        $("#FormResult").html(Translator.trans('Please your password should be at least 8 characters long.'));
        $("#YourPassword").focus();
        return false;
    } else if ($("#YourCPassword").val() == '') {
        $("#FormResult").show();
        $("#FormResult").html(Translator.trans('Please confirm your password.'));
        $("#YourCPassword").focus();
        return false;
    } else if ($("#YourCPassword").val() != $("#YourPassword").val()) {
        $("#FormResult").show();
        $("#FormResult").html(Translator.trans('Password does not match.'));
        $("#YourCPassword").focus();
        return false;
    } else {
        $("#FormResult").hide();
        $("#FormResult").html('');
        return true;
    }
}
function ShowClaimdiv(data) {
    $("#FormResult").hide();
    $("#FormResult").html('');
    if (data == 1) {
        show = t('please ')+'<a href="#signIn" class="signin">'+t('sign in')+'</a> '+t('using this email or ')+'<a href="#resetIn" class="signin">'+t('reset your password')+'</a>';
    } else {
        show = data;
    }
    $("#ClaimEmail").show();
    $("#ClaimEmail").html(show);
}
