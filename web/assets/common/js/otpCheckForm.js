
$(document).ready(function () {
    $('#form_Check').click(function () {
		var code = ($("#form_Code").val()).trim();
		if (code == '' || code.length == 0) {
		    showErrorMsg(Translator.trans('Please fill the Code input.'));
		} else {
		    showTTOverlay("checkFormContainer");
		    setTimeout(function () {
		    	$(".otpForm").submit();
		    }, 500);
		}
    });

    $('#resendCode').click(function () {
		showTTOverlay("checkFormContainer");
		setTimeout(function () {
		    window.location = $('#resendCode').attr('href');
		}, 500);
    });
});
