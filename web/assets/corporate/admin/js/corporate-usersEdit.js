var frmValidator;
var comboGridCity;
$(document).ready(function() {    
    frmValidator = new TTFormValidator("#formId", {
	    msgPosition : "bottom"
    }); 
    ttModal = window.getTTModal("myModalZ", {});

    var ctryOpts = {
		url: generateLangURL("/corporate/SearchCountry", "ajax"),
		searchButton: false,
		resetButton: false,
		colModel: [	{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': false, 'hidden': true},
			{'dbName' : 'p.code' ,'columnName':'code','width':'80px','label':'code','isIdProperty': true},
			{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
		    ]
    };
    var comboGridCountry = new AutoCompleteComboGrid("country", ctryOpts);
    var comboGridUserCountry = new AutoCompleteComboGrid("userCountry", ctryOpts);
    
    var cityOpts = {
        parameter: "countryCode",
        url: generateLangURL('/corporate/SearchCity','ajax'),
        searchButton: false,
        resetButton: false,
        colModel: [ 
            { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
            {'dbName' : 'p.fullname' ,'columnName':'fullname','width':'250px','label':'fullname','isProperty': true}
        ]
    };
    var comboGridCity = new AutoCompleteComboGrid("cityName", cityOpts);

    var currencyOpts = {
        url: generateLangURL('/corporate/SearchCurrency','ajax'),
        searchButton: false,
        resetButton: false,
        colModel: [ { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
	            { 'dbName' : 'p.code' ,'columnName':'code','width':'80px','label':'code', 'isIdProperty': true, 'hidden': true},
	            {'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
            ]
    }; 
    var comboGridCurrency = new AutoCompleteComboGrid("currency", currencyOpts);
    var comboGridPreferredCurrency = new AutoCompleteComboGrid("preferredCurrency", currencyOpts);

    var accOpts = {
		url: generateLangURL('/corporate/account/searchAccount','ajax'),
		searchButton: false,
		resetButton: false,
		colModel: [	{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
			{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
		    ]
    };
    var comboGridAccount = new AutoCompleteComboGrid("account", accOpts);

    var userProfileOpts = {
    	parameter: "userProfileLevel",
		url: generateLangURL('/corporate/users/searchUserProfile','ajax'),
		searchButton: false,
		resetButton: false,
		colModel: [	{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
			{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
		    ]
    };
    //var comboGridUserProfile = new AutoCompleteComboGrid("userProfile", userProfileOpts);
    
    if ($('.account_range_picker').length > 0) {
	    if ($('.account_range_picker').hasClass('picker_single')) {
		    isSingle = true;
	    }
	    var $datePicker = $('.account_range_picker');
	    var today = new Date();
	    var maxYear = today.getFullYear() - 18;
	    var minYear = 1910;
	    var $date = $datePicker.val();
	    $datePicker.daterangepicker({
		singleDatePicker : isSingle,
		autoApply : true,
		showDropdowns : true,
		autoUpdateInput : false,
		opens : 'left',
		startDate : getMaxDateUsers(),
		maxDate : getMaxDateUsers(),
		minYear : minYear,
		maxYear : maxYear,
		locale : {
			cancelLabel : 'Clear'
		}
	    });
	    if ($date != '') {
		    $datePicker.data('daterangepicker').setStartDate(convertmyDate($date));
	    }
	    $datePicker.on('apply.daterangepicker', function(ev, picker)
	    {
		    var $this = $(this);
		    var $fromDate = picker.startDate.format('YYYY-MM-DD');
		    $this.val($fromDate);
		    $this.attr('value', $fromDate);
	    });
    }

    //
    frmValidator.addCustomRule("#userEmail", "checkUserEmailDuplicate", Translator
	    .trans("Email already exists"), function(value)
    {
	    var result = checkDuplicate($("input[name='id']").val(), null, value);
	    if (!result && $('input[name=yourUserName]').val() == value)
		    $('input[name=yourUserName]').val("");
	    else if (result && $("input[name='yourUserName']").val() == '') {
		    var emailValue = $("input[name='yourEmail']").val();
		    $('input[name=yourUserName]').val(emailValue);
	    }
	    return result;
    });

    frmValidator.addCustomRule("#userName", "checkUserNameDuplicate", Translator
	    .trans("Username already exists"), function(value)
    {
	    return checkDuplicate($("input[name='id']").val(), value, null);
    });

    frmValidator.addCustomRule("#yourPassword", "checkPasswordLength", Translator
	    .trans("Password is too short (must be at least 8 characters)"), function(value)
    {
	    return checkPassLength($("input[name='yourPassword']").val());
    });

    $("#setPassword").click(function(ev)
    {
	    $('.pwArea').toggleClass('hidePassword');
    });

    $("#generatePassword").click(function(ev)
    {
	    var passwordGenrated = $("input[name='passwordGenrated']").val();
	    if (passwordGenrated) {
		    $("#yourPassword").prop("type", "text");
		    $("#confirmPassword").prop("type", "text");
	    }
	    $('#yourPassword').val(passwordGenrated);
	    $('#confirmPassword').val(passwordGenrated);
    });

    $("#submitBtn").click(function(ev)
    {
        if(frmValidator.validate()){
            var action = $('input[name="yourEmail"]').length;
            if ( action > 0 ) {
                ttModal.show({
                    title : Translator.trans("Email Notification"),
                    content : Translator.trans("We will send you an email notification")
                });
            }
            $("#formId").submit();
        }
    });

    $("#resetPassword").click(function(ev)
    {
	    ttModal.confirm(Translator.trans("Are you sure you want to reset the password"), function(btn)
	    {
		    if (btn == "ok") {
			    var userId = $("input[name='id']").val();
			    $.ajax({
				url : generateLangURL('/corporate/users/resetPassword', 'ajax'),
				data : {
					userId : userId
				},
				type : 'post',
				success : function(res)
				{
					ttModal.alert(res.msg, null, null, {
						ok : {
							value : "close"
						}
					});
				}
			    });
		    }
	    }, null, {
		ok : {
			value : Translator.trans("Yes")
		},
		cancel : {
			value : Translator.trans("No")
		}
	    });
    });
});

function getMaxDateUsers()
{
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1; // January is 0!
	var yyyy = today.getFullYear() - 18;
	if (dd < 10) {
		dd = '0' + dd;
	}
	if (mm < 10) {
		mm = '0' + mm;
	}
	var today = mm + '/' + dd + '/' + yyyy;
	return today;
}

function checkDuplicate(userId, userName, userEmail)
{
	var result = false;
	var id = userId;
	var name = userName;
	var email = userEmail;
	$.ajax({
	    url : '/corporate/Users/check-duplicate',
	    data : {
	        userId : id,
	        yourUserName : name,
	        yourEmail : email
	    },
	    type : 'post',
	    async : false,
	    dataType : "json",
	    success : function(response)
	    {
		    //
		    result = response.success;
		    if (!response.success) {
			    var errMsg = '';
			    $.each(response.message, function(key, msg)
			    {
				    errMsg += msg + ' ';
			    });

			    if (response.corporate == 1) {
				    ttModal.confirm(errMsg, function(btn)
				    {
					    if (btn == "ok") {
						    isCorporateSendEmail(response.userEmail, response.userName, response.userId);
						    $("#formId")[0].reset();
					    } else if (btn == "cancel") {

					    }
				    }, null, {
				        ok : {
					        value : "Yes"
				        },
				        cancel : {
					        value : "No"
				        }
				    });
			    }
		    }
		    //
		    return result;
	    },
	    error : function(error)
	    {
		    alert('error; ' + eval(error));
	    }
	});
	return result;
}

function isCorporateSendEmail(userEmail, userName, userId)
{
	var id = userId;
	var name = userName;
	var email = userEmail;
	$.ajax({
	    url : '/corporate/Users/sendEmail',
	    data : {
	        userId : id,
	        yourUserName : name,
	        yourEmail : email
	    },
	    type : 'post',
	    aync : false,
	    dataType : "json",
	    success : function(response)
	    {
		    if (response.success) {
			    ttModal.show({
			        title : response.title,
			        content : response.msg
			    });
		    } else {
			    ttModal.show({
			        title : response.errorTitle,
			        content : response.error
			    });
		    }
	    }
	});
}

function checkPassLength(pass)
{
	var passlength = pass.length;
	if (passlength < 8) {
		return false;
	} else {
		return true;
	}
}

function onCountrySelected(selPropId, selPropVal, drpInpt)
{
    if($("#cityName").prop("disabled")){
        $("#cityName").removeAttr("disabled");
    }    

    window.countryCode = selPropId;
    //
    if(comboGridCity) comboGridCity.setValue({id: "", value: ""});
}

