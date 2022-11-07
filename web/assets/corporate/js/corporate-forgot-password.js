var frmValidator;
$(document).ready(function () {
    frmValidator = new TTFormValidator("#chgPassformId", {msgPosition: "bottom"});
    //
    $('#confirm_container_hide').hide(); 
        $("#submit").click(function () {
        if(frmValidator.validate()){
            if ($('#NewPassword').val() != $('#ConfirmNewPassword').val()) {
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
            myData['NewPassword'] = $('#NewPassword').val();
            myData['OldPassword'] = $('#OldPassword').val();
            myData['NewPassword'] = $('#NewPassword').val();
            myData['userId'] = $('#userId').val();
            myData['encodedPass'] = $('#encodedPass').val();
            myData['saltPass'] = $('#saltPass').val();

            var updateUrl = $('#updateUrl').val();
            $.ajax({
                type: "POST",
                url: generateLangURL(updateUrl,'corporate'),
                data: myData,
                success: function (jresp) {
                    console.log(jresp);
                    try {
                        console.log(jresp);
                    } catch (Ex) {
                        return;
                    }
                    if (jresp.success) {
                        $('.confirm_container_unsubscribe').hide();
                        $('#NewPassword').val('');
                        $('#OldPassword').val('');
                        $('#ConfirmNewPassword').val('')
                        $('#confirm_container_hide').show();
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
        }
    });
});

