$(document).ready(function () {
    var latitude = 0;
    var longitude = 0;
    try {
	latitude = google.loader.ClientLocation.latitude;
	longitude = google.loader.ClientLocation.longitude;
    } catch (e) {
    }
    if ($("#latitude").length) {
	$("#latitude").val(latitude);
    }
    if ($("#longitude").length) {
	$("#longitude").val(longitude);
    }
    $('.forgetbutton').click(function () {
	$('.wrong_credentials').hide();
	$('.wrong_credentialspass').hide();
	$('.logincontainer').hide();
	$('.forgot_your_password').show();
    });
    $('.CancelForgot').click(function () {
	$('.wrong_credentials').hide();
	$('.wrong_credentialspass').hide();
	$('.forgot_your_password').hide();
	$('.logincontainer').show();
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
	$('#loginErr.wrong_credentials').html('');
	$('#loginErr.wrong_credentials').hide();
	$('.login_error_msg').html('');
	$('.login_error_msg').hide();

	var username = $("#username").val().trim();
	var password = $("#password").val().trim();

	if (username.length == 0) {
	    $('#loginErr.wrong_credentials').html(Translator.trans('Please insert username or email.'));
	    $('#loginErr.wrong_credentials').show();
	    $("#username").focus();
	    return false;
	}
	if (password.length < 8) {
	    $('#loginErr.wrong_credentials').html(Translator.trans('Please your password should be at least 8 characters long.'));
	    $('#loginErr.wrong_credentials').show();
	    $("#password").focus();
	    return false;
	}
	$(this).prop('type', 'submit');

    });

    $('.SendForgot').click(function (e) {
	var emailforget = $("#emailforget").val();
	if (emailforget == '' || emailforget == 'Your Email / Username' || !(emailforget.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
	    $('.wrong_credentials').html(Translator.trans('Please insert your email or username.'));
	    $('.wrong_credentials').show();
	    $('#emailforget').focus();
	} else {
	    $('.upload-overlay-loading-fix').show();
	    $('.wrong_credentials').hide();
	    $('.wrong_credentialspass').hide();

	    $.ajax({
		type: "POST",
		url: $("#resetPasswordPath").val(),
		data: {userCredential: emailforget},
		success: function (jresp) {
		    try {
			console.log(jresp);
		    } catch (exception) {
			$('.upload-overlay-loading-fix').hide();
			return;
		    }
		    $('.upload-overlay-loading-fix').hide();
		    if (!jresp)
			return;
		    if (jresp.success) {
			$('.wrong_credentialspass').html(Translator.trans(jresp.message));
			$('.wrong_credentialspass').show();
			$('.forgot_your_password').hide();
			$('.logincontainer').show();
		    } else {
			if (jresp.message) {
			    $('.wrong_credentials').html(Translator.trans(jresp.message));
			} else {
			    $('.wrong_credentials').html(Translator.trans('Incorrect user credentials.'));
			}
			$('.wrong_credentials').show();
		    }
		}
	    });
	}
    });
});

$(document).keypress(function(e) {
    if(e.which == 13) {
        $('#LogInSignIn').click(); 
    }
});
