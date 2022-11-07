/**
 * Created on .
 */
var theAction='';
function CloseAccordion($this) {
    $this.click();
}
function ChangeTo(status, parent) {
    if (status == 'closed') {
        parent.attr('rel', 'openned');
        parent.css({'background-color': '#838383'});
        parent.animate({width: '140px', height: '130px'}, 500, function () {
        });
    } else if (status == 'openned') {
        parent.attr('rel', 'closed');
        parent.animate({width: '20px', height: '20px'}, 500, function () {
            parent.css({'background-color': '#FFFFFF'});
        });
    }
}
function checkSubmitsignInbtn(e) {
    if (e && e.keyCode == 13) {
        $('#signInbtn_settings').click();
    }
}
function clearForm() {
    $("#SEmailField").val('');
    $("#SPasswordField").val('');
    $("#SEmailField").blur();
    $("#SPasswordField").blur();
}

function ChangeCheckBox(element, value) {
    if (value == '1') {
        element.addClass('checkboxActive');
        element.attr("data-value", 1);
    } else {
        element.removeClass('checkboxActive');
        element.attr("data-value", 0);
    }
}
function checkCityData(obj){
    $(obj).attr('data-id','');
}
$(document).ready(function () {
    $('.account-btn-cancel').click(function () {
        var $this = $(this);
        var parents = $this.closest('div.AccountInfo_item');        
        if(parents.length>0){
            if (parents.hasClass('active')){
                parents.find(".AccountTitle").click();
            }
        }else{
            document.location.reload();
        }
    });
    $(document).on('change', ".AccountCountrySelect", function () {
        var parents = $(this).closest('.AccountInfo_item');
        parents.find('input[name=city]').val('');
        parents.find('input[name=city]').attr('data-id','');
    });
    $(document).on('click', '.tellWhy', function () {
        $("#deleteTellContainer").toggle();
    });
    $(document).on('click', '#deleteTellContainer .deleteTellOpt:last,#deleteTellContainer .deleteTellOpt:last .contentPrivacyLabel', function () {
        if ($('#deleteTellContainer .deleteTellOpt:last').attr("data-value") == '1') {
            $("#reportcontainer").show();
        } else {
            $("#reportcontainer").hide();
        }
    });

    $(document).on('click', ".fb_disconnect", function () {
        TTCallAPI({
            what: generateLangURL('user/fb_disconnect'),
            data: {},
            callback: function (ret) {
                if (ret.status === 'error') {
                    TTAlert({
                        msg: ret.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                document.location.href = ReturnLink('/account/sharing');
            }
        });
    });
    $(document).on('click', ".peoplesdataclose", function () {
        var parents = $(this).parent();
        var parents_all = parents.parent().parent();
        parents.remove();
        if (parents.attr('data-id') != '') {
            SelectedUsersDelete(parents.attr('data-id'), parents_all.find('.addmore input'));
        }

        if (parents.attr('id') == 'friendsdata') {
            parents_all.find('.uploadinfocheckbox3').removeClass('active');
        } else if (parents.attr('id') == 'followersdata') {
            parents_all.find('.uploadinfocheckbox4').removeClass('active');
        } else if (parents.attr('id') == 'connectionsdata') {
            parents_all.find('.uploadinfocheckbox5').removeClass('active');
        } else if (parents.attr('id') == 'sponsorssdata') {
            parents_all.find('.uploadinfocheckbox6').removeClass('active');
        } else if (parents.attr('id') == 'friends_of_friends_data') {
            parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
        }
    });
    $(".privacy_select").change(function () {
        var selectval = parseInt($(this).val());
        if (selectval == USER_PRIVACY_SELECTED) {
            $(this).parent().find('.privacy_picker').removeClass('displaynone');
        } else {
            initResetSelectedUsers($(this).parent().find('.addmore input'));
            $(this).parent().find('.uploadinfocheckbox').removeClass('active');
            $(this).parent().find('.addmore input').val('');
            $(this).parent().find('.addmore input').blur();
            $(this).parent().find('.peoplesdata').each(function () {
                var parents = $(this);
                parents.remove();
            });
            $(this).parent().find('.privacy_picker').addClass('displaynone');
        }
        initResetIcon($(this).parent().find('.privacy_icon'));
        $(this).parent().find('.privacy_icon').addClass('privacy_icon' + selectval);
    });
    // Collapse
    $('.AccountTitle').click(function () {
        var $this = $(this);
        var parents = $this.closest('div.AccountInfo_item');
        if (parents.hasClass('active')) {
            parents.find("#privacy_close_button").click();
            parents.removeClass('active');
            parents.find(".privacy-account").hide();
            parents.find(".account_toshow").hide();
        } else {
            parents.addClass('active');
            parents.find(".privacy-account").show();
            parents.find(".account_toshow").show();
        }
    });
    //  / Collapse
    $(".personalCheckbox").click(function () {
        var curob = $(this);
        if (curob.hasClass('checkboxActive')) {
            curob.removeClass('checkboxActive');
            curob.attr("data-value", "0");
        } else {
            curob.addClass('checkboxActive');
            curob.attr("data-value", "1");
        }
    });

    // learnmore fancy box in account settings (manage)
    $(".learnmore").fancybox({
        padding: 0,
        width: '1025px',
        margin: 0
    });
    //   / learnmore fancy box in account settings (manage)
    $('#confirmaccount').fancybox({
        padding: 0,
        width: '347px',
        margin: 0,
        beforeLoad: function () {
            clearForm();
        }
    });
    $('.btn').click(function () {
        var $this = $(this);
        //only clicked yellow button should be on top of grey popup
        $('.btn').parent().parent().css('z-index', 1000);
        $this.parent().parent().css('z-index', 1260);
        /////////////////////
        //close other open buttons
        $('.btn').each(function () {
            var $parent = $(this).parent();
            if ($(this).get(0) != $this.get(0)) {
                $parent.attr('rel', 'closed');
                $parent.animate({width: '20px', height: '20px'}, 500, function () {
                    $parent.css({'background-color': '#FFFFFF'});
                });
            }
        });
        ////////////
        var parent = $this.parent();
        var thisrel = parent.attr('rel');
        //ChangeTo('openned', $('.btn').parent());
        ChangeTo(thisrel, parent);
    });

    $('.list li').click(function () {
        var $this = $(this);
        var myrel = $this.attr('rel')
        var parent = $this.closest('div.accountprivacy');
        var thisrel = parent.attr('rel');
        ChangeTo(thisrel, parent);
        parent.attr('value', myrel);
    });

//////////////////////////  Submit

    //    submit for about me - ajax
    $("#saveabout").click(function () {
        var website_url = $('#website_url').val();
        var small_description = $('#small_description').val();
        $.ajax({
            url: ReturnLink('/ajax/account_info.php'),
            data: {
                website_url: website_url,
                small_description: small_description,
                flag: 'about'
            },
            type: 'post',
            success: function (data) {
                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {
                }
                if (!jres) {
                    return;
                }
                if (jres.status == 'ok') {
                    $("#website_url").val(jres.website_url);
                    $("#small_description").val(jres.small_description);
                    TTAlert({
                        msg: jres.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
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
    //   / submit for about me - ajax

    //    submit for account info - ajax
    $("#savepersonal").click(function () {
        var fname = $('input[name=fname]').val();
        var lname = $('input[name=lname]').val();
        var employment = $('input[name=employment]').val();
        var high_education = $('input[name=high_education]').val();
        var useUsername = $("#useUsername").attr("data-value");
        var YourUserName = $('input[name=YourUserName]').val();
        var intrestedin = $('select[name=intrestedin]').val();
        var email = getObjectData($('#email'));//email
        if (!validateEmail(email)) {
            TTAlert({
                msg: t('Please insert a correct email.'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return false;
        }
        var dob = $('input[name=dob]').attr("data-value");
        var display_age = $('#display_age').attr("data-value");
        var display_yearage = $('#display_yearage').attr("data-value");
        var gender = $('input[name=gender]:checked').val();
        var display_gender = $('#display_gender').attr("data-value");
        var display_interest = $('#display_interest').attr("data-value");
        $.ajax({
            url: ReturnLink('/ajax/account_info.php'),
            data: {
                fname: fname,
                lname: lname,
                employment: employment,
                high_education: high_education,
                useUsername: useUsername,
                YourUserName: YourUserName,
                intrestedin: intrestedin,
                email: email,
                dob: dob,
                display_age: display_age,
                display_yearage:display_yearage,
                gender: gender,
                display_gender: display_gender,
                display_interest: display_interest,
                flag: 'personal'
            },
            type: 'post',
            success: function (data) {
                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {
                }
                if (!jres) {
                    return;
                }
                if (jres.status == 'ok') {
                    $('#fname').val(jres.fname);
                    $('#lname').val(jres.lname);
                    $('#employment').val(jres.employment);
                    $('#high_education').val(jres.high_education);
                    var usval=jres.display_fullname;
                    
                    ChangeCheckBox($('#useUsername'), usval);
                    $('#YourUserName').val(jres.YourUserName);
                    $('#intrestedin').val(jres.intrestedin);
                    $('#email').val(jres.email);
                    $('#dob').val(jres.dob);
                    $('#dob').attr("data-value", jres.dob);
                    ChangeCheckBox($('#display_age'), jres.display_age);
                    ChangeCheckBox($('#display_yearage'), jres.display_yearage);
                    $('.gender').removeAttr("checked");
                    $('.gender[value="' + jres.gender + '"]').attr("checked", "checked");
                    ChangeCheckBox($('#display_gender'), jres.display_gender);
                    ChangeCheckBox($('#display_interest'), jres.display_interest);
                    TTAlert({
                        msg: jres.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
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
    //  /  submit for account info - ajax

    //    submit for location - ajax
    $("#savelocation").click(function () {
        var hometown = $('input[name=hometown]').val();
        var city = $('input[name=city]').val();
        var cityid = $('input[name=city]').attr('data-id');
        var country = $('select[name=country]').val();
        if(cityid=="" && city.length>0){
            TTAlert({
                msg: t('invalid city name'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        $.ajax({
            url: ReturnLink('/ajax/account_info.php'),
            data: {
                hometown: hometown,
                city: city,
                cityid: cityid,
                country: country,
                flag: 'location'
            },
            type: 'post',
            success: function (data) {
                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {
                }
                if (!jres) {
                    return;
                }
                if (jres.status == 'ok') {
                    $("#hometown").val(jres.hometown);
                    $("#city").val(jres.city);
                    $("#country").val(jres.country);
                    TTAlert({
                        msg: jres.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
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
    //  /  submit for location - ajax

    //    submit for deactivate account - ajax
    $('#CloseAccountBtn').click(function () {
        if ($('#deactivate_account').hasClass('checkboxActive')) {
            theAction = 'deactivate';
            $('#confirmaccount').click();
        }
        else {
            var stopemail = $("#stop_emails").attr("data-value");
            if (stopemail == 0) {
                stopemail = 1;
            } else {
                stopemail = 0;
            }
            $.ajax({
                url: ReturnLink('/ajax/account_unsubscribe.php'),
                data: {stopemail: stopemail},
                type: 'post',
                success: function (data) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }
                    if (!ret) {
                        TTAlert({
                            msg: t('Couldnt save please try again later'),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            });
        }
    });
    //  /  submit for deactivate account - ajax
    //    submit for delete account - ajax
    $(document).on('click', "#sendReport", function () {

        if ($('.deleteTellOpt').hasClass('checkboxActive')) {
            var msg = $('#reportdesc').val();
            if ($('#deleteTellContainer .deleteTellOpt:last').hasClass('checkboxActive') && msg == '') {
                TTAlert({
                    msg: t('Please write your Report'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
            var entity_type = SOCIAL_ENTITY_PROFILE_ACCOUNT;
            var reason_array = new Array();
                $('.deleteTellOpt.checkboxActive').each(function(){
                    var $this = $(this);
                    reason_array.push( $this.attr('data-id') );
                });
                var reason = reason_array.join(',');
		TTCallAPI({
                    what: 'user/report/add',
                    data: {entity_type:entity_type, entity_id:report_entity_id, msg: msg , reason:reason},
                    callback: function(ret){
                        $('.upload-overlay-loading-fix').hide();
                        if( ret.status === 'error' ){
                            TTAlert({
                                msg: ret.msg,
                                type: 'alert',
                                btn1: t('ok'),
                                btn2: '',
                                btn2Callback: null
                            });
                            return;
                        }
                        TTAlert({
                                msg: ret.msg,
                                type: 'alert',
                                btn1: t('ok'),
                                btn2: '',
                                btn2Callback: null
                        });
                        $("#deleteTellContainer").hide();
                        $("#tellWhy").hide();
                        $("#confirmDelete,#confirmDeleteBtns").show();
                    }
		});
            
        } else {
            TTAlert({
                msg: t('Please choose one of the above proposed options to Report'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
        }
    });
    $(document).on('click', "#confirmDeleteBtns .accountbut2", function () {
        if($("#confirmDelete").attr('data-value') === '0') {
            TTAlert({
                msg: t('Please check to confirm your action.'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }else{
             TTAlert({
                msg: t('Are you sure you want to delete your account? This action cannot be undone.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('confirm'),
                btn2Callback: function(data){
                    if(data){
                        theAction = 'delete';
                        $('#confirmaccount').click();
                    }
                }
            });
           
        }
    });
    $(document).on('click', "#confirmDeleteBtns .accountbut1", function () {
        $("#tellWhy").show();
        $("#confirmDelete,#confirmDeleteBtns").hide();
    });
    //  /  submit for delete account - ajax

    //    submit for account settings - ajax
    $('#AccountMgmtSave').click(function () {
        var uname = $('input[name=uname]').val();
        var new_pass = $('input[name=new_pass]').val();
        var new_pass2 = $('input[name=new_pass2]').val();
        var old_pass = $('input[name=old_pass]').val();
        $('input[name=uname]').removeClass('InputErr');
        $('input[name=old_pass]').removeClass('InputErr');
        $('input[name=new_pass]').removeClass('InputErr');
        $('input[name=new_pass2]').removeClass('InputErr');
        var min_pswd_length = 5;
        if (old_pass.length == 0) {
            TTAlert({
                msg: t('Please specify old password'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            $('input[name=old_pass]').addClass('InputErr');
            return;
        } else if ((new_pass.length < min_pswd_length)) {
            TTAlert({
                msg: sprintf(t('Password must be minimum %s characters long'), [min_pswd_length]),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            $('input[name=new_pass]').addClass('InputErr');
            return;
        } else if ((new_pass != new_pass2)) {
            TTAlert({
                msg: t('passwords mismatch'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            $('input[name=new_pass]').addClass('InputErr');
            $('input[name=new_pass2]').addClass('InputErr');
            return;
        }
       
        $(this).attr('disabled', 'disabled');
        $.ajax({
            url: ReturnLink('/ajax/account_manage.php'),
            data: {uname: uname, new_pass: new_pass, old_pass: old_pass},
            type: 'post',
            success: function (data) {
                $('#AccountMgmtSave').removeAttr('disabled');
                $('body').css('cursor', '');
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (!ret) {
                    TTAlert({
                        msg: t('Couldnt save please try again later'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                if (ret.status == 'ok') {
                    $('#WelcomeUserName').html(uname);
                    TTAlert({
                        msg: t('Information saved!'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    if (ret.error_no == 0) {
                        $('input[name=uname]').addClass('InputErr');
                    } else if (ret.error_no == 1) {
                        $('input[name=old_pass]').addClass('InputErr');
                    } else if (ret.error_no == 2) {
                        $('input[name=new_pass]').addClass('InputErr');
                    }
                    TTAlert({
                        msg: ret.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
                if (
                        (ret.status == 'ok')
                        ||
                        ((ret.status != 'ok') && (ret.error_no != 0))
                        ) {
                    $('#WelcomeUserName a').html($('input[name=uname]').val());
                }
            }
        });
    });
    //  /  submit for account settings - ajax

    // confirm username - password to deactivate account/ delete account
    $(document).on('click', "#signInbtn_settings", function () {
        var EmailField = getObjectData($("#SEmailField"));
        var PasswordField = getObjectData($("#SPasswordField"));

        var action = theAction;

        $('#SPasswordField, #SEmailField').removeClass('InputErr');
        if (EmailField.length == 0 || !(EmailField.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $('#SEmailField').focus().addClass('InputErr');
            return false;
        } else if (PasswordField.length == 0 || !(PasswordField.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $('#SPasswordField').focus().addClass('InputErr');
            return false;
        } else {
            $('.upload-overlay-loading-fix').show();
            var dataString = 'EmailField=' + EmailField + '&PasswordField=' + PasswordField + '&action=' + action;
            $.ajax({
                type: "POST",
                url: ReturnLink("/ajax/account_check_user.php"),
                data: dataString,
                success: function (html) {
                    var ret = null;
                    try {
                        ret = $.parseJSON(html);
                    } catch (Ex) {
                        $('.upload-overlay-loading-fix').hide();
                        return;
                    }
                    $.fancybox.close();
                    if (!ret) {
                        $('.upload-overlay-loading-fix').hide();
                        TTAlert({
                            msg: t('invalid username or password, please try again'),
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }
                    if (ret.status == 'ok') {
                        document.location.href = ReturnLink('/logout');
                    } else {
                        $('.upload-overlay-loading-fix').hide();
                        TTAlert({
                            msg: ret.msg,
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
    //  / confirm username - password to deactivate account
    // Cancel confirmation
    $(document).on('click', ".signInbtn1", function () {
        closeFancyBox();
    });
    //   / Cancel confirmation
    // Send Invitation
    
    $('#SendInviteBtn').click(function () {        
        var err = false;
        $('input[name=remail]').removeClass('InputErr');        
        if (!emailIsValid($('input[name=remail]').val()) || $('input[name=remail]').val() == '') {
            $('input[name=remail]').addClass('InputErr');
            TTAlert({
                msg: t('Please specify a valid email address'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        if (err)
            return;
        var name = $('input[name=rname]').val();
        var email = $('input[name=remail]').val();
        var defaultName = $('input[name=rname]').attr('data-value');
        var defaultEmail = $('input[name=remail]').attr('data-value');
        $('#SendInviteBtn').attr('disabled', 'disabled');
        
        $.ajax({
            url: ReturnLink('/ajax/account_invite.php'),
            data: {
                name: name,
                email: email
            },
            type: 'post',
            success: function (data) {
                $('#SendInviteBtn').removeAttr('disabled');
                $('body').css('cursor', '');
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (!ret) {
                    TTAlert({
                        msg: t('Couldnt perform operation. please try again later'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                if (ret.status == 'ok') {
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    var Invites = parseInt($('#SentInvites').text());
                    $('#SentInvites').html(Invites + 1);
                    $(".invited").prepend(ret.li);
                    if (ret.last == '1') {
                        $("#e2 .AccountOrder").html("1");
                        $(".AccountInfo_item:first").hide();
                    }
                    $('input[name=rname]').val(defaultName);
                    $('input[name=remail]').val(defaultEmail);
                } else {
                    TTAlert({
                        msg: ret.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            }
        });
    });
    //     /  Send Invitation

    $(document).on('click', "#privacy_picker .uploadinfocheckbox", function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#friendsdata').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#friends_of_friends_data').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#followersdata').remove();
                }
            }
        } else {
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var friendstr = '<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">' + t("friends") + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(friendstr);
                    //$(this).parent().parent().find('#friendsdata').css("width", ($(this).parent().parent().find('#friendsdata .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">' + t("friends of friends") + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                    //$(this).parent().parent().find('#friends_of_friends_data').css("width", ($(this).parent().parent().find('#friends_of_friends_data .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">' + t("followers") + '</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                    //$(this).parent().parent().find('#followersdata').css("width", ($(this).parent().parent().find('#followersdata .peoplesdatainside').width() + 20) + "px");
                }
            }
            $(this).addClass('active');
        }
    });
});
function initResetIcon(obj) {
    obj.removeClass('privacy_icon1');
    obj.removeClass('privacy_icon2');
    obj.removeClass('privacy_icon3');
    obj.removeClass('privacy_icon4');
    obj.removeClass('privacy_icon5');
    obj.removeClass('privacy_icon6');
}
function updateImage(str, pic_link, _type) {
    if (_type == "account_pic") {
        document.location.reload();
        /*$('#AboutMeImageUL .ImageStan').html(str);
        $('.userAvatar #AccountProfileImage').attr('src', ReturnLink('/media/tubers/Profile_' + pic_link));
        $('.userAvatar .userAvatarLink').attr('href', ReturnLink('/media/tubers/' + pic_link));*/
    }
    closeFancyBox();
}
function initResetSelectedUsers(obj) {
    resetSelectedUsers(obj);
}
function initPrivacyBox(obj_box, is_visible) {
    var privacyselcted = parseInt(obj_box.find('#privacy_select').val());
    obj_box.find("#privacy_select").change();
    if (privacyselcted == USER_PRIVACY_SELECTED) {
        obj_box.show();
        obj_box.find('.peoplecontainer .emailcontainer .peoplesdata').each(function () {
            var obj = $(this);
            if (obj.attr('id') == "friendsdata") {
                //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
            } else if (obj.attr('id') == "friends_of_friends_data") {
                //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
            } else if (obj.attr('id') == "followersdata") {
                //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
            } else if (parseInt(obj.attr('data-id')) != 0) {
                if (obj_box.hasClass('privacy_box0')) {
                    SelectedUsersAdd(obj.attr('data-id'), $('#addmoretext_zero'));
                } else if (obj_box.hasClass('privacy_box1')) {
                    SelectedUsersAdd(obj.attr('data-id'), $('#addmoretext_first'));
                } else if (obj_box.hasClass('privacy_box2')) {
                    SelectedUsersAdd(obj.attr('data-id'), $('#addmoretext_second'));
                } else if (obj_box.hasClass('privacy_box3')) {
                    SelectedUsersAdd(obj.attr('data-id'), $('#addmoretext_third'));
                } else if (obj_box.hasClass('privacy_box4')) {
                    SelectedUsersAdd(obj.attr('data-id'), $('#addmoretext_forth'));
                } else {
                    SelectedUsersAdd(obj.attr('data-id'), $('#addmoretext_fifth'));
                }

                //obj.css("width", (obj.find('.peoplesdatainside').width() + 20) + "px");
            }
        });
        if (!is_visible) {
            obj_box.hide();
        }
    }
}
//    Date picker for DOB

function InitCalendar() {
    Calendar.setup({
        inputField: "dob",
        noScroll  	 : true,
        trigger: "dob",
        align: "B",
        onSelect: function (calss) {
            var date = Calendar.intToDate(calss.selection.get());
            var dt = Calendar.printDate(date, "%Y-%m-%d");
            $('#dob').attr('data-value', dt);
            $('#dob').val(dt);
            this.hide();
        },
        dateFormat: "%Y-%m-%d"
    });
}
//  /  Date picker for DOB

function initPersonalInfo() {
    $('.privacy-account').mouseover(function () {
        var diffx = $('#AccountInfo').offset().left + 255;
        var diffy = $('#AccountInfo').offset().top + 22;
        var posxx = $(this).offset().left - diffx;
        var posyy = $(this).offset().top - diffy;

        $('.privacybuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.privacybuttonsOver').css('left', posxx + 'px');
        $('.privacybuttonsOver').css('top', posyy + 'px');
        $('.privacybuttonsOver').stop().show();
    });
    $('.privacy-account').mouseout(function () {
        $('.privacybuttonsOver').hide();
    });
    InitCalendar();    
    $(".privacy_down_arrow").click(function () {
        var $this = $(this);
        var parents = $this.closest('div.AccountInfo_item');
        if (!parents.hasClass('active'))
            parents.find(".AccountTitle").click();
        parents.find("#privacy_box").show();
    });
    $(".privacy_close_button").click(function () {
        var $this = $(this);
        var parents = $this.closest('div.AccountInfo_item');
        parents.find("#privacy_box").hide();
    });
    $(document).on('click', ".private_save_buts", function () {
        var curob = $(this).parent();
        var parents = curob.closest('div.AccountInfo_item');
        var privacyValue = parseInt(curob.find("#privacy_select").val());
        var privacyArray = new Array();
        if (privacyValue == USER_PRIVACY_SELECTED) {
            curob.find('.peoplecontainer .emailcontainer .peoplesdata').each(function () {
                var obj = $(this);
                if (obj.attr('id') == "friendsdata") {
                    privacyArray.push({friends: 1});
                } else if (obj.attr('id') == "friends_of_friends_data") {
                    privacyArray.push({friends_of_friends: 1});
                } else if (obj.attr('id') == "followersdata") {
                    privacyArray.push({followers: 1});
                } else if (parseInt(obj.attr('data-id')) != 0) {
                    privacyArray.push({id: obj.attr('data-id')});
                }
            });
        }
        if ((privacyValue == USER_PRIVACY_SELECTED && privacyArray.length > 0) || privacyValue != USER_PRIVACY_SELECTED) {
            var entity_str = curob.attr('data-type');
            TTCallAPI({
                what: 'user/privacy_extand/add',
                data: {privacyValue: privacyValue, privacyArray: privacyArray, entity_type: entity_str, entity_id: 0},
                callback: function (ret) {
                    if (ret.status === 'error') {
                        TTAlert({
                            msg: ret.error,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }
                    var privacy_account = parents.find('.privacy-account');
                    privacy_account.attr('class', 'privacy-account privacy_icon' + curob.find("#privacy_select").val());
                    privacy_account.attr('data-title', curob.find('#privacy_select option:selected').text());
                    curob.find("#privacy_close_button").click();
                }
            });
        } else {
            TTAlert({
                msg: t('Invalid privacy data'),
                type: 'alert',
                btn1: t('ok')
            });
        }
    });
    addmoreusersautocomplete_custom_journal($('#addmoretext_first'));
    initResetSelectedUsers($('#addmoretext_first'));
    addmoreusersautocomplete_custom_journal($('#addmoretext_second'));
    initResetSelectedUsers($('#addmoretext_second'));
    addmoreusersautocomplete_custom_journal($('#addmoretext_third'));
    initResetSelectedUsers($('#addmoretext_third'));

    initPrivacyBox($('.privacy_box1'), false);
    initPrivacyBox($('.privacy_box2'), false);
    initPrivacyBox($('.privacy_box3'), false);
}
function addAutoCompleteList(which) {
    var $ccity = $("input[name=city]", $('#' + which));
    $ccity.autocomplete({
        appendTo: "#" + which,
        search: function (event, ui) {
            var $country = $('#country');
            //console.log($country);
            var cc = $country.val();
            if (cc == 'ZZ') {
                $country.addClass('err');
                event.preventDefault();
            } else {
                $ccity.autocomplete("option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc));
            }
        },
        select: function (event, ui) {
            $ccity.val(ui.item.value);
            $ccity.attr('data-id',ui.item.id);
            event.preventDefault();
        }
    });
}
function undoTTNotificationsData(obj, action) {
    $('.upload-overlay-loading-fix').show();
    var id = obj.attr('data-id');
    var data_ischannel = parseInt(obj.attr('data-channel'));

    $.ajax({
        url: ReturnLink('/ajax/info_newsfeed_update.php'),
        data: {id: id, action: action, ischannel: data_ischannel},
        type: 'post',
        success: function (data) {
            $('.upload-overlay-loading-fix').hide();
            if (data) {
                if (action == 'unhide' || action == 'unhide_all') {
                    obj.closest('.list_notifications_item').remove();
                }
            }
        }
    });
}
function initAccountNotification() {
    $(document).on('click', '.list_item_undo', function () {
        var log_obj = $(this);
        var action_on = "unhide";
        undoTTNotificationsData(log_obj, action_on);
    });
    $(document).on('click', ".account-btn-save", function () {
        var curob = $(this).closest('.formContainer');
        var globlinkArray = new Array();
        var globDataArray = new Array();
        var globTypeArray = new Array();
        globlinkArray.push(parseInt(curob.find('#select1').val()));
        globDataArray.push(curob.find('#select1').attr('data-value'));
        globTypeArray.push(parseInt(curob.find('#select1').attr('data-type')));
        globlinkArray.push(parseInt(curob.find('#select2').val()));
        globDataArray.push(curob.find('#select2').attr('data-value'));
        globTypeArray.push(parseInt(curob.find('#select2').attr('data-type')));
        curob.find('.uploadinfocheckbox').each(function () {
            var myobjstr = $(this);
            globDataArray.push(myobjstr.attr('data-value'));
            globTypeArray.push(parseInt(myobjstr.attr('data-type')));
            if (myobjstr.hasClass('active')) {
                globlinkArray.push(1);
            } else {
                globlinkArray.push(0);
            }
        });
        var globlinkArraystr = globlinkArray.join('/*/');
        var globDataArraystr = globDataArray.join('/*/');
        var globTypeArraystr = globTypeArray.join('/*/');

        var emailnotifications1 = '' + $('#emailnotifications1').val();

        updateTuberOtherEmail(emailnotifications1);
        updateNotificationsForm(globlinkArraystr, globDataArraystr, globTypeArraystr, false);        
    });
}
function updateNotificationsForm(param1, param2, param3, bool) {
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/info_notification_manage.php'),
        data: {value: param1, data: param2, type: param3},
        type: 'post',
        success: function (data) {
            $('.upload-overlay-loading-fix').hide();
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (!ret) {
                TTAlert({
                    msg: t('couldnt save please try again later'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
            if (bool) {
                if (ret.status == 'ok') {
                    TTAlert({
                        msg: t('information saved!'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });

                } else {
                    TTAlert({
                        msg: ret.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            }
        }
    });
}
function updateTuberOtherEmail(param1) {
    $.ajax({
        url: ReturnLink('/ajax/info_notification_otheremail.php'),
        data: {data: param1},
        type: 'post',
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }

            if (!ret) {
                TTAlert({
                    msg: t('couldnt save please try again later'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }

            if (ret.status == 'ok') {
                TTAlert({
                    msg: t('information saved!'),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            } else {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
        }
    });
}
function initAccountPrivacy() {
    $(document).on('click', '.show_item_undo', function () {
        var log_obj = $(this);
        var action_on = "unhide_all";
        undoTTNotificationsData(log_obj, action_on);
    });
    $(document).on('click', "#AccountStanDiv3 .uploadinfocheckbox", function () {
        var curob = $(this);
        if (curob.hasClass('active')) {
            curob.removeClass('active');
        } else {
            curob.addClass('active');
        }
    });
    $('#search_tuber').autocomplete({
        delay: 5,
        appendTo: "#contact_privacy_container",
        search: function (event, ui) {
            var type = 'u';
            var append = "&t=" + type;
            $('#search_tuber').autocomplete('option', 'source', ReturnLink('/ajax/media-autocomplete.php') + append);
        },
        focus: function (event, ui) {
            //$(this).val(ui.item.right);
            return false;
        },
        select: function (event, ui) {
            var wid = ui.item.id;
            var right = ui.item.value_display;
            $('#search_tuber').val(right);
            $('#search_tuber').attr('data-id', wid);
            $('#search_tuber').attr('data-us', right);
            event.preventDefault();
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }
    $('#profile_block_user').click(function () {
        var wid = $('#search_tuber').attr('data-id');
        var tuber_block = getObjectData($('#search_tuber'));
        if (tuber_block != '' && parseInt(wid) > 0 && $('#search_tuber').attr('data-us') == tuber_block) {
            userBlockTuber($('#search_tuber'));
        } else {
            $('#search_tuber').attr('data-id', '');
            $('#search_tuber').attr('data-us', '');
            TTAlert({
                msg: t('invalid user'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
        }
    });
    $('#ContactSave').click(function () {        
        $(this).attr('disabled', 'disabled');
        savePrivacyObjects($('.privacy_box5'));
        var contact_privacy = $('input[name=contact_privacy]:checked').val();
        var search_engine = $('input[name=search_engine]:checked').val();
        $.ajax({
            url: ReturnLink('/ajax/account_privacy.php'),
            data: {contact_privacy: contact_privacy, search_engine: search_engine, flag: 'contact'},
            type: 'post',
            success: function (data) {
                $('#ContactSave').removeAttr('disabled');
                $('body').css('cursor', '');
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (!ret) {
                    TTAlert({
                        msg: t('couldnt save please try again later'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                if (ret.status == 'ok') {
                    TTAlert({
                        msg: t('information saved!'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    TTAlert({
                        msg: ret.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            }
        });
    });

    $(document).on('click', "#privacySave", function () {
        savePrivacyObjects($('.privacy_box0'));
        savePrivacyObjects($('.privacy_box1'));
        savePrivacyObjects($('.privacy_box2'));
        savePrivacyObjects($('.privacy_box3'));
        savePrivacyObjects($('.privacy_box4'));

        var curob = $(this).closest('#ContentPrivacy').find('#AccountStanDiv3');
        var globlinkArray = new Array();
        var globDataArray = new Array();
        var globTypeArray = new Array();

        curob.find('.uploadinfocheckbox').each(function () {
            var myobjstr = $(this);
            globDataArray.push(myobjstr.attr('data-value'));
            globTypeArray.push(parseInt(myobjstr.attr('data-type')));
            if (myobjstr.hasClass('active')) {
                globlinkArray.push(1);
            } else {
                globlinkArray.push(0);
            }
        });
        var globlinkArraystr = globlinkArray.join('/*/');
        var globDataArraystr = globDataArray.join('/*/');
        var globTypeArraystr = globTypeArray.join('/*/');


        updateNotificationsForm(globlinkArraystr, globDataArraystr, globTypeArraystr, true);
    });

    addmoreusersautocomplete_custom_journal($('#addmoretext_zero'));
    initResetSelectedUsers($('#addmoretext_zero'));
    addmoreusersautocomplete_custom_journal($('#addmoretext_first'));
    initResetSelectedUsers($('#addmoretext_first'));
    addmoreusersautocomplete_custom_journal($('#addmoretext_second'));
    initResetSelectedUsers($('#addmoretext_second'));
    addmoreusersautocomplete_custom_journal($('#addmoretext_third'));
    initResetSelectedUsers($('#addmoretext_third'));
    addmoreusersautocomplete_custom_journal($('#addmoretext_forth'));
    initResetSelectedUsers($('#addmoretext_forth'));
    addmoreusersautocomplete_custom_journal($('#addmoretext_fifth'));
    initResetSelectedUsers($('#addmoretext_fifth'));

    $('.AccountStanDiv2').parent().show();
    initPrivacyBox($('.privacy_box0'), true);
    initPrivacyBox($('.privacy_box1'), true);
    initPrivacyBox($('.privacy_box2'), true);
    initPrivacyBox($('.privacy_box3'), true);
    initPrivacyBox($('.privacy_box4'), true);
    initPrivacyBox($('.privacy_box5'), true);
    $('.AccountStanDiv2').parent().hide();
}
function savePrivacyObjects(parents) {
    var privacyValue = parseInt(parents.find("#privacy_select").val());
    var privacyArray = new Array();
    if (privacyValue == USER_PRIVACY_SELECTED) {
        parents.find('.peoplecontainer .emailcontainer .peoplesdata').each(function () {
            var obj = $(this);
            if (obj.attr('id') == "friendsdata") {
                privacyArray.push({friends: 1});
            } else if (obj.attr('id') == "friends_of_friends_data") {
                privacyArray.push({friends_of_friends: 1});
            } else if (obj.attr('id') == "followersdata") {
                privacyArray.push({followers: 1});
            } else if (parseInt(obj.attr('data-id')) != 0) {
                privacyArray.push({id: obj.attr('data-id')});
            }
        });
    }
    if ((privacyValue == USER_PRIVACY_SELECTED && privacyArray.length > 0) || privacyValue != USER_PRIVACY_SELECTED) {
        var entity_str = parents.attr('data-type');
        TTCallAPI({
            what: 'user/privacy_extand/add',
            data: {privacyValue: privacyValue, privacyArray: privacyArray, entity_type: entity_str, entity_id: 0},
            callback: function (ret) {

            }
        });
    }
}
function userBlockTuber(obj) {
    $('.chat-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/profile_friend.php'),
        data: {fid: obj.attr('data-id'), op: 'block_frnd'},
        type: 'post',
        success: function (data) {
            $('.chat-overlay-loading-fix').hide();
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                        $('.chat-overlay-loading-fix').hide();
                return;
            }

            if (!ret) {
                        $('.chat-overlay-loading-fix').hide();
                TTAlert({
                    msg: t("Couldn't process. please try again later"),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }

            if (ret.status == 'ok') {
                obj.attr('data-id', '');
                obj.val('');
                obj.blur();
            } else {
                TTAlert({
                    msg: ret.msg,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }
                        $('.chat-overlay-loading-fix').hide();
        }
    });
}
function checkSubmitInvite(e){
   if(e && e.keyCode == 13){
      $('#SendInviteBtn').click();
   }
}