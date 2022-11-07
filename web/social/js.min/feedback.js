function ClearFeedbackForm() {
        $('#FeedbackForm input').val('...');
        $('#FeedbackForm textarea').val('...');
    }
    function validateEmail(elementValue){
        var emailPattern = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
        return emailPattern.test(elementValue);
    }
    function ValidateFeedbackForm() {
        if ($("#fname").val() == '' || $("#fname").val() == '...') {
            TTAlert({
                msg: t('Please insert your first name.'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            $("#fname").focus();
            return false;
        } else if ($("#lname").val() == '' || $("#lname").val() == '...') {
            TTAlert({
                msg: t('Please insert your last name'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });

            $("#lname").focus();
            return false;
        } else if ($("#email").val() == '' || $("#email").val() == '...') {
            TTAlert({
                msg: t('Please insert your email address'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            $("#email").focus();
            return false;
        }else if(! validateEmail($("#email").val())){
            TTAlert({
                msg: t('Please enter a valid email'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            $("#email").focus();
            return false;
        }
        else if ($("#fmessage").val() == '' || $("#fmessage").val() == '...') {
            TTAlert({
                msg: t('Please insert your message'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            $("#fmessage").focus();
            return false;
        }
        else {
            $.ajax({
                url: ReturnLink('/ajax/sendfeeback.php'),
                data: {
                    fname: $("#fname").val(),
                    lname: $("#lname").val(),
                    phone: $("#phone").val(),
                    email: $("#email").val(),
                    message: $("#fmessage").val()
                },
                type: 'post',
                success: function(result) {
                    ret = $.parseJSON(result);
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    ClearFeedbackForm();
                }
            });

        }
    }

    $(document).ready(function() {

        $("#SubmitFeedback").click(function() {
            ValidateFeedbackForm();
        });

        $("#CancelFeedback").click(function() {
            ClearFeedbackForm();
        });

        $(".feedbackinput, .feedbacktextarea").focus(function() {
            if($(this).val() === "..."){
                $(this).val('');
            }
        });

    });

