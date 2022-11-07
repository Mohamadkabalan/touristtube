$(document).ready(function () {
    $('#confirm_container_hide').hide();
    $("#submit").click(function () {
	var oldPassword = $('#YourOldPassword').val();
	var newPassword = $('#YourPassword').val();
	var confirmPassword = $('#YourCPassword').val();
	var yourUserName = $('#YourUserName').val();
	if ($.trim(newPassword) == '' || $.trim(oldPassword) == '' || $.trim(confirmPassword) == '') {
	    TTAlert({
		msg: Translator.trans("Please insert your password."),
		type: 'alert',
		btn1: Translator.trans('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    return false;
	}
	if (confirmPassword != newPassword) {
	    TTAlert({
		msg: Translator.trans("These passwords don't match."),
		type: 'alert',
		btn1: Translator.trans('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    return false;
	}

	var myData = new Object();
	myData['newPassword'] = newPassword;
	myData['oldPassword'] = oldPassword;
	myData['yourUserName'] = yourUserName;
	myData['userId'] = $('#userId').val();

	var updateUrl = $('#updatePswdUsernamePath').val();
	var username = '';
	$.ajax({
	    type: "POST",
	    url: generateLangURL(updateUrl),
	    data: myData,
	    success: function (jresp) {
		try {
		    console.log(jresp);
		} catch (Ex) {
		    return;
		}
		if (jresp.success) {
		    $('#YourPassword').val('');
		    $('#YourOldPassword').val('');
		    $('#YourCPassword').val('');
		    $('#YourUserName').val('')
		    $('#confirm_container_hide').show();
		    if (jresp.username) {
			username = Translator.trans("Your username has been updated successfully.");
			$('.paddingpasssuccess').append('<p class="resetpasssuccess">' + username + '</p>');
		    }
		} else {
		    TTAlert({
			msg: jresp.message,
			type: 'alert',
			btn1: Translator.trans('ok'),
			btn2: '',
			btn2Callback: null
		    });
		}
		$('.upload-overlay-loading-fix').hide();
	    }
	});

    });
});

