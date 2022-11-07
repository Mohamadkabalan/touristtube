function checkDuplicate(userId, userName, userEmail) {
    var result = false;
    var id = userId;
    var name = userName;
    var email = userEmail;
    $.ajax({
	url: '/corporate/Users/check-duplicate',
	data: { userId: id, yourUserName: name, yourEmail: email},
	type: 'post',
	async: false,
        dataType: "json",
	success: function (response) {
	    //
            result = response.success;
	    if(!response.success)
	    {
                var errMsg = '';
                $.each(response.message, function (key, msg) {
                    errMsg += msg + ' ';
                });

                if(response.corporate == 1){
                    ttModal.confirm(errMsg, function(btn){
                      if(btn == "ok"){
                          isCorporateSendEmail(response.userEmail,response.userName,response.userId);
                          $("#formId")[0].reset();
                      }else if(btn == "cancel"){
                          
                      }
                    }, null, {ok:{value:"Yes"},cancel:{value:"No"}});
//                    ttModal.confirm(errMsg, function (btn) {
//                    if (btn == "delete")
//                        goToLinkNew(generateLangURL('/corporate/admin/account/delete/' + accId, 'empty'));
//                    });
                }
                /*else{
                    if(userName && userName!="") {
                        frmValidator.addValidationMessage("#userName", errMsg);
                    }else{
                        frmValidator.addValidationMessage("#userEmail", errMsg);
                    }
                }*/
	    }
            //
            return result;
	},
	error: function (error) {
	    alert('error; ' + eval(error));
	}
    });
    return result;
}

function isCorporateSendEmail(userEmail,userName,userId) {
    var id = userId;
    var name = userName;
    var email = userEmail;
    $.ajax({
	url: '/corporate/Users/sendEmail',
	data: { userId: id, yourUserName: name, yourEmail: email},
	type: 'post',
	aync: false,
        dataType: "json",
	success: function (response) {
	    if(response.success){
                ttModal.show({title: response.title, content: response.msg});
            }else{
                ttModal.show({title: response.errorTitle, content: response.error});
            }
	}
    });
}

function checkPassLength(pass) {
    var passlength = pass.length;
    if(passlength < 8){
        return false;
    }else{
        return true;
    }
}