
function ShowClaimdiv(data) {
    $("#FormResult").html('');
    if (data == 1) {
        show = t('please ')+'<a href="#signIn" class="signin">'+t('sign in')+'</a> '+t('using this email or ')+'<a href="#resetIn" class="signin">'+t('reset your password')+'</a>';
    } else {
        show = data;
    }
    $("#ClaimEmail").css('visibility', 'visible');
    $("#ClaimEmail").html(show);
}

function ShowChClaimdiv(data) {
    $("#CFormResult").html('');
    if (data == 1) {
        show = t('please  ')+'<a href="#signIn" class="signin">'+t('sign in ')+'</a> '+t('using this email or  ')+'<a href="#resetIn" class="signin">'+t('reset your password ')+'</a>';
    } else {
        show = data;
    }
    $("#CClaimEmail").css('visibility', 'visible');
    $("#CClaimEmail").html(show);
}

function openSelected(page) {
    if (page != 0) {

        var thisparent = $("#" + page);
        var height = thisparent.find(".regHeader").attr("rel");

        $("#BecomeTuberForm").removeClass("selected").css({'height': '60px'});
        $("#CreateChannelForm").removeClass("selected").css({'height': '60px'});

        thisparent.css({height: height + 'px'});
        thisparent.addClass("selected");

    }
}

/* Validate Register Form */
function ValidateRegisterForm() {
    if ($("#fname").val() == '' || $("#fname").val() == '...') {
        $("#FormResult").html( $.i18n.t('Please insert your first name.') );
        $("#fname").focus();
        return false;
    } else if ($("#lname").val() == '' || $("#lname").val() == '...') {
        $("#FormResult").html( t('Please insert your last name.' ));
        $("#lname").focus();
        return false;
    } else if ($("#YourEmail").val() == '') {
        $("#FormResult").html(t('Please insert your email.'));
        $("#YourEmail").focus();
        return false;
    } else if (!validateEmail($("#YourEmail").val())) {
        $("#FormResult").html(t('Please insert a correct email.'));
        $("#YourEmail").focus();
        return false;
    } else if ($("#YourUserName").val() == '') {
        $("#FormResult").html(t('Please insert your username.'));
        $("#YourUserName").focus();
        return false;
    } else if ($("#YourPassword").val() == '') {
        $("#FormResult").html(t('Please insert your password.'));
        $("#YourPassword").focus();
        return false;
    } else if( ($("#YourPassword").val()).length < 6) {
        $("#FormResult").html(t('Please your password should be at least 6 characters long.'));
        $("#YourPassword").focus();
        return false;
    } else if ($("#YourCPassword").val() == '') {
        $("#FormResult").html(t('Please confirm your password.'));
        $("#YourCPassword").focus();
        return false;
    } else if ($("#YourCPassword").val() != $("#YourPassword").val()) {
        $("#FormResult").html(t('Password does not match.'));
        $("#YourCPassword").focus();
        return false;
    } else if ($("#YourBday").val() == '') {
        $("#FormResult").html(t('Please insert your date of birth.'));
        $("#YourBday").focus();
        return false;
    }else if( !isDateCheck($('#YourBday').val()) || ($('#YourBday').val()).length<10 ){
        $("#FormResult").html(t('Please check date format.'));
        $("#YourBday").focus();
    }
    else if ($(".genderM").is(':not(:checked)') && $(".genderF").is(':not(:checked)')) {
        $("#FormResult").html(t('Please indicate your gender.'));
        return false;
    }
    else {
        $("#FormResult").html('');
        return true;
    }
}
function ValidateRegisterFormNew() {
    if ($("#YourEmail").val() == '') {
        $("#FormResult").html(t('Please insert your email.'));
        $("#YourEmail").focus();
        return false;
    } else if (!validateEmail($("#YourEmail").val())) {
        $("#FormResult").html(t('Please insert a correct email.'));
        $("#YourEmail").focus();
        return false;
    } else if ($("#YourPassword").val() == '') {
        $("#FormResult").html(t('Please insert your password.'));
        $("#YourPassword").focus();
        return false;
    } else if( ($("#YourPassword").val()).length < 6) {
        $("#FormResult").html(t('Please your password should be at least 6 characters long.'));
        $("#YourPassword").focus();
        return false;
    } else if ($("#YourCPassword").val() == '') {
        $("#FormResult").html(t('Please confirm your password.'));
        $("#YourCPassword").focus();
        return false;
    } else if ($("#YourCPassword").val() != $("#YourPassword").val()) {
        $("#FormResult").html(t('Password does not match.'));
        $("#YourCPassword").focus();
        return false;
    } else {
        $("#FormResult").html('');
        return true;
    }
}
/* Validate Channel Form */
function ValidateCahnnelForm() {
    if ($("#oname").val() == '' || $("#oname").val() == '...') {
        $("#CFormResult").html( $.i18n.t('Please insert the owner name.') );
        $("#oname").focus();
        return false;
    } else if ($("#cname").val() == '' || $("#cname").val() == '...') {
        $("#CFormResult").html(t('Please insert the channel name.'));
        $("#cname").focus();
        return false;
    } else if ($("#cemail").val() == '') {
        $("#CFormResult").html(t('Please insert your email.'));
        $("#cemail").focus();
        return false;
    } else if (!validateEmail($("#cemail").val()) && $("#cemail").length>0) {
        $("#CFormResult").html(t('Please insert a correct email.'));
        $("#cpassword").focus();
        return false;
    } else if ($("#cpassword").val() == '') {
        $("#CFormResult").html(t('Please insert your password.'));
        $("#cpassword").focus();
        return false;
    } else if ($("#cpassword").val() < 6) {
        $("#CFormResult").html(t('Please your password should be at least 6 characters long.'));
        $("#cpassword").focus();
        return false;
    } else if ($("#ccpassword").val() == '') {
        $("#CFormResult").html(t('Please confirm your password.'));
        $("#ccpassword").focus();
        return false;
    } else if ($("#ccpassword").val() != $("#cpassword").val()) {
        $("#CFormResult").html(t('Password does not match.'));
        $("#ccpassword").focus();
        return false;
    } else if ($("#ccategory").val() == '0') {
        $("#CFormResult").html(t('Please choose a category.'));
        $("#ccategory").focus();
        return false;
    } else if ($("#ccountry").val() == '0') {
        $("#CFormResult").html(t('Please choose a country.'));
        $("#ccountry").focus();
        return false;
    } /*else if ( ($("#czip").val() == '' || $("#czip").hasClass('Error') ) && $("#czip").parent().css('display') !="none" ) {
        $("#CFormResult").html(t('Please insert a zip code.'));
        $("#czip").focus();
        return false;
    }*/else if (!ValidURL($("#curl").val()) && $("#curl").val()!='') {
        $("#curl").html(t('Please insert a valid url.'));
        $("#curl").focus();
        return false;
    }
    else {
        $("#FormResult").html('');
        return true;
    }
}

function checkvalue($this) {
    var ThisID = $this.attr("id");
    var ThisName = $this.attr("name");
    var ThisValue = $("#" + ThisID).val();

    $.ajax({
        url: ReturnLink('/ajax/ajax_ch_register.php'),
        data: {ThisName: ThisName, ThisValue: ThisValue, flag: 'focus'},
        type: 'post',
        success: function(data) {

            var jres = null;
            try {
                jres = $.parseJSON(data);
            } catch (Ex) {

            }

            if (jres.error) {
                throwChaError = 1;
                $("#CFormResult").html(jres.error);
                $("#" + ThisID).addClass('Error');
            } else {
                throwChaError = 0;
                $("#CFormResult").html('');
                $("#" + ThisID).removeClass('Error');
                $("#" + ThisID).removeClass('Focus');
            }

        }
    });

    // Check if the country exists in our zip-codes table.
    $.ajax({
        url: ReturnLink('/ajax/ajax_check_country.php'),
        data: {country_code: ThisValue},
        type: 'post',
        success: function(data) {

            var jres = null;
            try {
                jres = $.parseJSON(data);
            } catch (Ex) {

            }

            if (jres.error) {
                throwChaError = 1;
                $("#CFormResult").html(jres.error);
                $("#" + ThisID).addClass('Error');
            } else {
                throwChaError = 0;
                $("#CFormResult").html('');
                $("#" + ThisID).removeClass('Error');
                $("#" + ThisID).removeClass('Focus');

                var country = jres.country;
                // If the country doesn't exist.
                if (country == '') {
                    $("#czip").parent().hide();
                    $("#czip_ttlogo").hide();
                } else {
                    $("#czip").parent().show();
                    $("#czip_ttlogo").show();
                }
            }

        }
    });

}

function clearForm() {
    $("#SEmailField").val('yourname@email.com');
    $("#SPasswordField").val('Your Password');

    $("#SEmailForgotForm").val('yourname@email.com');
}

function clearallfields(formID) {
    $("#" + formID + " input").each(function() {
        if ($(this).attr('name') != 'gender') {
            $(this).val("");
        }
    });
    $("#" + formID + " select").val("");
}

function checkDate($this) {

    var ThisID = $this;
    var ThisName = $this.attr("name");
    var ThisValue = $this.val();
    $.ajax({
        url: ReturnLink('/ajax/ajax_register.php'),
        data: {ThisName: ThisName, ThisValue: ThisValue, flag: 'focus'},
        type: 'post',
        success: function(data) {

            var jres = null;
            try {
                jres = $.parseJSON(data);
            } catch (Ex) {

            }

            if (jres.error) {
                throwError = 1;
                $("#FormResult").html(jres.error);
                ThisID.addClass('Error');
                //setTimeout( function(){ $("#"+ThisID).focus();  }, 100 );
            } else {
                throwError = 0;
                ThisID.removeClass('Error');
                ThisID.removeClass('Focus');
                $("#FormResult").html('');
            }

        }
    });
}

function addAutoCompleteList(which) {
    var $ccity = $("input[name=ccity]", $('#' + which));
    $ccity.autocomplete({
        appendTo: "#CreateChannelForm",
        search: function(event, ui) {
            var $country = $('#ccountry');
            //console.log($country);
            var cc = $country.val();
            if (cc == 'ZZ') {
                $country.addClass('err');
                event.preventDefault();
            } else {
                $ccity.autocomplete("option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc));
            }
        },
        select: function(event, ui) {
            $ccity.val(ui.item.value);
            $ccity.attr('data-id',ui.item.id);
            event.preventDefault();
        }
    });
}

$(document).ready(function() {

    var throwError = 0;
    var throwChaError = 0;

    $(".signin").fancybox({
        padding: 0,
        margin: 0,
        onComplete: clearForm()
    });
    
    addAutoCompleteList("CreateChannelForm");
    
    $('#submitmailreq').click(function() { 
        var emlstr = getObjectData($('#req_email'));
        if (!emailIsValid(emlstr) || emlstr=='' ) {
            TTAlert({
                msg: t('Please specify valid email address'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        $.ajax({
            url: ReturnLink('/ajax/account_invite_request.php'),
            data: {email: emlstr, name: ''},
            type: "post",
            success: function(resp) {
                var Jresponse;
                try {
                    Jresponse = $.parseJSON(resp);
                    $('#req_email').val('');
                    $('#req_email').blur();
                } catch (Ex) {
                    return;
                }

                if (!Jresponse)
                    return;

                if (Jresponse.status == 'ok') {                    
                    TTAlert({
                        msg: Jresponse.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    TTAlert({
                        msg: Jresponse.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            }
        });
    });
    /*$(".regHeader").click(function() {
        var $this = $(this);
        var height = $this.attr("rel");
        var thisparent = $this.parent();


        if (thisparent.hasClass("selected")) {

            thisparent.find(".formContainer").slideUp();
            thisparent.removeClass("selected");

        } else {

            $("#InsideNormal .selected").find(".regHeader").trigger('click');
            thisparent.find(".formContainer").slideDown();
            thisparent.addClass("selected");
        }

    });*/
    if ($('#YourBday').length > 0) {
        Calendar.setup({
            inputField: "YourBday",
                noScroll  	 : true,
            trigger: "cal_icon",
            align: "B",
            onSelect: function(calss) {
                this.hide();
                checkDate($("#YourBday"));
            },
            dateFormat: "%d/%m/%Y"
        });
    }
    $(".bdate").focus(function() {
        $(this).addClass('Focus');
        $(this).removeClass('Error');
    });

    $('.RegFocus').each(function() {
        $(this).focus(function() {
            $(this).addClass('Focus');
            $(this).removeClass('Error');
        });
//        $(this).blur(function() {
//
//            //if(throwError == 0){
//
//            var $this = $(this);
//            var ThisID = $this.attr("id");
//            var ThisName = $this.attr("name");
//            var ThisValue = $("#" + ThisID).val();
//
//            $.ajax({
//                url: ReturnLink('/ajax/ajax_register.php'),
//                data: {ThisName: ThisName, ThisValue: ThisValue, flag: 'focus'},
//                type: 'post',
//                success: function(data) {
//
//                    var jres = null;
//                    try {
//                        jres = $.parseJSON(data);
//                    } catch (Ex) {
//
//                    }
//
//                    if (jres.error) {
//                        throwError = 1;
//                        $("#FormResult").html(jres.error);
//                        $("#" + ThisID).addClass('Error');
//                        //setTimeout( function(){ $("#"+ThisID).focus();  }, 100 );
//                    } else {
//                        throwError = 0;
//                        $("#" + ThisID).removeClass('Error');
//                        $("#" + ThisID).removeClass('Focus');
//                        $("#FormResult").html('');
//                    }
//
//                }
//            });
//
//            //}
//
//        });
    });

    $('.RegUsernameBlur').blur(function() {
        var $this = $(this);
        var YourUserName = $("#YourUserName").val();        
        $this.removeClass('Error');
        if(YourUserName=='') return;
        $.ajax({
            url: ReturnLink('/ajax/ajax_check_user.php'),
            data: {username: YourUserName},
            type: 'post',
            success: function(data) {

                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {

                }

                if (jres.data=='error') {
                    $("#YourUserName").addClass('Error');
                    TTAlert({
                        msg: jres.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            }
        });

    });

//    $('.ChaFocus').each(function() {
//        $(this).focus(function() {
//            $(this).addClass('Focus');
//            $(this).removeClass('Error');
//        });
//        $(this).blur(function() {
//
//            //if(throwChaError == 0){
//
//            var $this = $(this);
//            var ThisID = $this.attr("id");
//            var ThisName = $this.attr("name");
//            var ThisValue = $("#" + ThisID).val();
//
//            $.ajax({
//                url: ReturnLink('/ajax/ajax_ch_register.php'),
//                data: {ThisName: ThisName, ThisValue: ThisValue, flag: 'focus'},
//                type: 'post',
//                success: function(data) {
//
//                    var jres = null;
//                    try {
//                        jres = $.parseJSON(data);
//                    } catch (Ex) {
//
//                    }
//
//                    if (jres.error) {
//                        throwChaError = 1;
//                        $("#CFormResult").html(jres.error);
//                        $("#" + ThisID).addClass('Error');
//                    } else {
//                        throwChaError = 0;
//                        $("#" + ThisID).removeClass('Error');
//                        $("#" + ThisID).removeClass('Focus');
//                        $("#CFormResult").html('');
//                    }
//
//                }
//            });
//
//            //}
//
//        });
//    });

    $("#CreateMyChannel").click(function() {
        if (ValidateCahnnelForm()) {
            $("#ClaimChEmail").css('visibility', 'hidden');
            $('.upload-overlay-loading-fix').show();
            $.ajax({
                url: ReturnLink('/ajax/ajax_ch_register.php'),
                data: {oname: $("#ChRegisterF #oname").val(), cname: $("#ChRegisterF #cname").val(), cemail: $("#ChRegisterF #cemail").val(), cpassword: $("#ChRegisterF #cpassword").val(), ccpassword: $("#ChRegisterF #ccpassword").val(), ccategory: $("#ChRegisterF #ccategory").val(), ccountry: $("#ChRegisterF #ccountry").val(), czip: $("#ChRegisterF #czip").val(), ccity: $("#ChRegisterF #ccity").val(), ccityid: $("#ChRegisterF #ccity").attr('data-id'), cstreet: $("#ChRegisterF #cstreet").val(), cphone: $("#ChRegisterF #cphone").val(), curl: $("#ChRegisterF #curl").val()},
                type: 'post',
                success: function(data) {

                    var jres = null;
                    try {
                        jres = $.parseJSON(data);
                    } catch (Ex) {
                        $('.upload-overlay-loading-fix').hide();
                        return;
                    }
                    if (jres.error) {
                        $('.upload-overlay-loading-fix').hide();
                        $("#CFormResult").html(jres.error);
                        var fieldsArray = jres.fields;
                        for (var i = 0; i < fieldsArray.length; i++) {
                            $("#" + fieldsArray[i]).addClass('Error');
                        }
                    } else {
                        $('.upload-overlay-loading-fix').hide();
//                        ShowChClaimdiv(jres.message);
//                        clearallfields("ChRegisterF");
                        window.top.location.href = ReturnLink('register-success/channel');
                    }

                }
            });
        }

    });

    $("#CreateMyAccount").click(function() {
        if (ValidateRegisterForm()) {
            if ($("#FormResult").html() == '') {
                $("#ClaimEmail").css('visibility', 'hidden');
                $('.upload-overlay-loading-fix').show();
                $.ajax({
                    url: ReturnLink('/ajax/ajax_register.php'),
                    data: {fname: $("#RegisterF #fname").val(), lname: $("#RegisterF #lname").val(), YourEmail: $("#RegisterF #YourEmail").val(), YourUserName: $("#RegisterF #YourUserName").val(), YourPassword: $("#RegisterF #YourPassword").val(), YourCPassword: $("#RegisterF #YourCPassword").val(), YourBday: $("#RegisterF #YourBday").val(), gender: $('input[name=gender]:checked').val()},
                    type: 'post',
                    success: function(data) {
                        var jres = null;
                        try {
                            jres = $.parseJSON(data);
                        } catch (Ex) {
                            $('.upload-overlay-loading-fix').hide();
                            return;
                        }
                        if (jres.error) {
                            $('.upload-overlay-loading-fix').hide();
                            $("#FormResult").html(jres.error);
                            if (jres.YourUserName)
                                $("#YourUserName").val(jres.YourUserName);
                            var fieldsArray = jres.fields;
                            for (var i = 0; i < fieldsArray.length; i++) {
                                $("#" + fieldsArray[i]).addClass('Error');
                            }
                        } else {
                            $('.upload-overlay-loading-fix').hide();
//                            ShowClaimdiv(jres.message);
//                            clearallfields("RegisterF");
                            window.top.location.href = ReturnLink('register-success');
                        }
                    }
                });
            }
        }
    });
    $("#CreateMyAccountNew").click(function() {
        if (ValidateRegisterFormNew()) {
            if ($("#FormResult").html() == '') {
                $("#ClaimEmail").css('visibility', 'hidden');
                $('.upload-overlay-loading-fix').show();
                $.ajax({
                    url: ReturnLink('/ajax/ajax_register.php'),
                    data: {fname: $("#RegisterF #YourUserName").val(), lname: '', YourEmail: $("#RegisterF #YourEmail").val(), YourUserName: $("#RegisterF #YourUserName").val(), YourPassword: $("#RegisterF #YourPassword").val(), YourCPassword: $("#RegisterF #YourCPassword").val(), YourBday: '0000-00-00', gender: 'O', register_type: 'popup'},                    
                    type: 'post',
                    success: function(data) {
                        var jres = null;
                        try {
                            jres = $.parseJSON(data);
                        } catch (Ex) {
                            $('.upload-overlay-loading-fix').hide();
                            return;
                        }
                        if (jres.error) {
                            $('.upload-overlay-loading-fix').hide();
                            $("#FormResult").html(jres.error);
                            if (jres.YourUserName)
                                $("#YourUserName").val(jres.YourUserName);
                            var fieldsArray = jres.fields;
                            for (var i = 0; i < fieldsArray.length; i++) {
                                $("#" + fieldsArray[i]).addClass('Error');
                            }
                        } else {
                            $('.upload-overlay-loading-fix').hide();
//                            ShowClaimdiv(jres.message);
//                            clearallfields("RegisterF");
                            window.top.location.href = ReturnLink('register-success');
                        }
                    }
                });
            }
        }
    });

    $(".registerpopUp input").focus(function() {
        $(this).val('');
    });

    $('#signInbtn').live("click", function() {

        var EmailField = $("#SEmailField").val();
        var PasswordField = $("#SPasswordField").val();

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
                    } else {
                        TTAlert({
                            msg: t("Invalid login, please try again"),
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

        var EmailForgotForm = $("#SEmailForgotForm").val();
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
    
    $("#ccountry").change(function() {
        var country_code = $("#ccountry").val();
        $.ajax({
            url: ReturnLink('/ajax/ajax_check_country.php'),
            data: {country_code: country_code},
            type: 'post',
            success: function(data) {

                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {

                }

                if (jres.error) {
                    throwChaError = 1;
                    $("#CFormResult").html(jres.error);
                } else {
                    throwChaError = 0;
                    $("#CFormResult").html('');

                    var country = jres.country;
                    $("#czip").removeClass('Error');
                    // If the country doesn't exist.
                    if (country == '') {
                        $("#czip").parent().hide();
                        $("#czip_ttlogo").hide();
                    } else {
                        $("#czip").parent().show();
                        $("#czip_ttlogo").show();
                    }
                }

            }
        });
    });

    $("#czip").blur(function() {
        var zip_code = $("#czip").val();
        var country_code = $("#ccountry").val();
        if(zip_code=='' || country_code=='0') return;
        $.ajax({
            url: ReturnLink('/ajax/ajax_check_zip.php'),
            data: {zip_code: zip_code, country_code: country_code},
            type: 'post',
            success: function(data) {

                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {

                }

                if (jres.error) {
                    throwError = 1;
                    $("#FormResult").html(jres.error);
                    $("#czip").addClass('Error');
                    //setTimeout( function(){ $("#"+ThisID).focus();  }, 100 );
                } else {
                    var city = jres.city;

                    // Zip valid.
                    if (city != '') {
                        $("#czip").removeClass('Error');
                        $("#ccity").val(city);
                        $("#CFormResult").html('');
                        // Zip invalid.
                    } else {
                        $("#czip").addClass('Error');
                        $("#ccity").val('');
                        //$("#CFormResult").html("zip code is incorrect");
                        TTAlert({
                            msg: $.i18n._("Zip code is incorrect") ,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                    }
                }

            }
        });
    });


});


function checkSubmitsignInbtn(e) {
    if (e && e.keyCode == 13) {
        $('#signInbtn').click();
    }
}
function checkCalValue(e){
    if(!isNumberWithSeperator(e)){
        return false;
    }
    return true;
}
function isNumberWithSeperator(e){
    var charCode = (e.which) ? e.which : e.keyCode;
    if ( charCode ==47 || charCode ==45 )
       return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
       return false;	
    return true;
}
function isDateCheck(value) {
  var dateFormat;
  if (toString.call(value) === '[object Date]') {
    return true;
  }
  if (typeof value.replace === 'function') {
    value.replace(/^\s+|\s+$/gm, '');
  }
  dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
  return dateFormat.test(value);
}
function checkSubmitRegister(e) {
    if (e && e.keyCode == 13) {
        $('#submitmailreq').click();
    }
}