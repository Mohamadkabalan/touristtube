var currentpage = 0;
var MAX_LOAD;
var isSingle = false;
$(document).ready(function () {
    
    if( !ttModal ) {
	ttModal = window.getTTModal("myModalZ", {});
    }
    
    if( $('#locationDetails').length>0 )
    {
	addAutoCompleteListCurrentCity('locationDetails');
    }
    
    if( $('#account_pic').length>0 )
    {
	initCropAndUpload();
    }
    
    if( $('input[name=old_pass]').length>0 )
    {
	$('input[name=old_pass]').val('');
    }
    
    $(document).on('click', ".account-btn-cancel", function () 
    {
        document.location.reload();
    });

    $(document).on("click", ".learnmorelink_a", function (e) 
    {
	e.preventDefault();
	var $this = $(this);
	var $selector = $("." + $this.attr('for'));
	var data_h = parseInt($selector.attr('data-h'));
	if($this.hasClass('more')){
	    $selector.data('oHeight', $selector.height()).css('height', 'auto').data('nHeight', $selector.height()).height($selector.data('oHeight')).animate({height: $selector.data('nHeight')}, 400);
	    $this.removeClass('more');
	    if ($selector.data('nHeight') <= data_h) {
		$this.remove();
	    }
	}else{
	    $selector.animate({height: data_h + "px"}, 400);
	    $this.addClass('more');
	}
    });
    
    $(document).on('click', ".account-btn-settings-save", function () {
	var uname = $('input[name=uname]').val();
        var new_pass = $('input[name=new_pass]').val();
        var new_pass2 = $('input[name=new_pass2]').val();
        var old_pass = $('input[name=old_pass]').val();
	var desactemp = ($('.desactemp:checked').length>0)?1:0;
	var deleteemp = ($('.deleteemp:checked').length>0)?1:0;
	var stopemail = ($('.stopemail:checked').length>0)?0:1;
	
	if( desactemp == 1 || deleteemp == 1 )
	{
	    var $action = 'deactivate';
	    var confirmalert = Translator.trans("Are you sure you want to deactivate temporarily your account.");
	    if( deleteemp == 1 )
	    {
		$action = 'delete';
		confirmalert = Translator.trans("Are you sure you want to delete your account.");
	    }
	    if (uname.length == 0) 
	    {
		ttModal.alert(Translator.trans("Please specify your username"), null, null, {ok:{value:"close"}});
		return false;
	    }
	    
	    if (old_pass.length == 0) 
	    {
		ttModal.alert(Translator.trans("Please specify your password"), null, null, {ok:{value:"close"}});
		return false;
	    }
	    ttModal.alert(confirmalert, function (btn) {
		if(btn == "ok"){
		    $('.upload-overlay-loading-fix').show();
		    $.ajax({
			url: generateLangURL('/ajax/account-check-user'),
			data: {
			    uname: uname,
			    old_pass: old_pass,
			    action: $action
			},
			type: 'post',
			success: function (data) {
			    $('.upload-overlay-loading-fix').hide();
			    var jres = null;
			    try {
				jres = data;
				var status = jres.status;
			    } catch (Ex) {
			    }
			    if (!jres) {
				return;
			    }
			    
			    if (jres.status == 'ok') {
				ttModal.alert(jres.msg, function (btn) {
				    document.location.href = ReturnLink('/logout');				    
				}, null, {ok:{value:Translator.trans("ok")}});
			    } else {
				ttModal.alert(jres.msg, null, null, {ok:{value:"close"}});
			    }
			}
		    });
		}
	    }, null, {ok:{value:Translator.trans("confirm")}});
	    
	    return false;
	}
	
	var min_pswd_length = 8;
	
        if (old_pass.length == 0) {
	    ttModal.alert(Translator.trans("Please specify your password"), null, null, {ok:{value:"close"}});
            return false;
        }
	
	if ( new_pass.length>0 && new_pass.length < min_pswd_length ) {
	    ttModal.alert(sprintf(Translator.trans("New password must be minimum %s characters long"), [min_pswd_length]), null, null, {ok:{value:"close"}});
            return false;
        }
	
	if ( new_pass.length>0 && new_pass != new_pass2 ) {
	    ttModal.alert(Translator.trans("Confirm new password mismatch"), null, null, {ok:{value:"close"}});            
            return false;
        }
	
	$('.upload-overlay-loading-fix').show();
	$.ajax({
            url: generateLangURL('/ajax/account-settings'),
            data: {
                uname: uname,
                new_pass: new_pass,
                old_pass: old_pass,
                stopemail: stopemail
            },
            type: 'post',
            success: function (data) {
		$('.upload-overlay-loading-fix').hide();
                var jres = null;
                try {
                    jres = data;
		    var status = jres.status;
                } catch (Ex) {
                }
                if (!jres) {
                    return;
                }
                if (jres.status == 'ok') {
		    ttModal.alert(jres.msg, function (btn) {
			if(btn == "ok"){
			    document.location.reload();
			}
		    }, null, {ok:{value:Translator.trans("ok")}});
                } else {
		    ttModal.alert(jres.msg, null, null, {ok:{value:"close"}});
                }
            }
        });
    });
    
    $(document).on('click', ".account-btn-save", function () {
        var description = $('input[name=small_description]').val();
        var website = $('input[name=website]').val();
        var gender = $('input[name=gender]:checked').val();
        var fname = $('input[name=fname]').val();
        var lname = $('input[name=lname]').val();
        var birthday = $('input[name=birthday]').val();
        var email = $('input[name=email]').val();
        var employment = $('input[name=employment]').val();
        var high_education = $('input[name=education]').val();
        var intrestedin = $('select[name=interested_in]').val();
        var hometown = $('input[name=hometown]').val();
        var country = $('select[name=country]').val();
        var city = $('input[name=city]').val();
        var cityid = $('input[name=city]').attr('data-id');
	if(city=='') cityid=0;
        if (!validateEmail(email)) {
	    ttModal.alert(Translator.trans("Please insert a correct email."), null, null, {ok:{value:"close"}});            
            return false;
        }
	if (!ValidURL(website) && website!='') {
	    ttModal.alert(Translator.trans("Please enter a valid website, http://www."), null, null, {ok:{value:"close"}});
            return false;
        }
	$('.upload-overlay-loading-fix').show();
	$.ajax({
            url: generateLangURL('/ajax/account-info'),
            data: {
                description: description,
                website: website,
                gender: gender,
                fname: fname,
                lname: lname,
                birthday: birthday,
                email: email,
                employment: employment,
                high_education: high_education,
                intrestedin: intrestedin,
                hometown: hometown,
                country:country,
                city: city,
                cityid: cityid
            },
            type: 'post',
            success: function (data) {
		$('.upload-overlay-loading-fix').hide();
                var jres = null;
                try {
                    jres = data;
		    var status = jres.status;
                } catch (Ex) {
                }
                if (!jres) {
                    return;
                }
                if (jres.status == 'ok') {
		    ttModal.alert(jres.msg, function (btn) {
			if(btn == "ok"){
			    document.location.reload();
			}
		    }, null, {ok:{value:Translator.trans("ok")}});
                } else {
		    ttModal.alert(jres.msg, null, null, {ok:{value:"close"}});
                }
            }
        });
    });
    if ($('.account_range_picker').length > 0) {
	if( $('.account_range_picker').hasClass('picker_single') ){
	    isSingle = true;
	}
	var $datePicker = $('.account_range_picker');
	var today = new Date();
	var maxYear = today.getFullYear();
	var minYear = 1910;
	var $date = $datePicker.val();
	$datePicker.daterangepicker({
	    singleDatePicker: isSingle,
	    autoApply: true,
	    showDropdowns: true,
	    autoUpdateInput: false,
	    opens: 'left',
	    maxDate: getTodatDate(),
	    minYear: minYear,
	    maxYear: maxYear,
	    locale: {cancelLabel: 'Clear'}
	});
	if($date!=''){
	    $datePicker.data('daterangepicker').setStartDate( convertmyDate($date) );
	}
	$datePicker.on('apply.daterangepicker', function (ev, picker) {
	    var $this = $(this);
	    var $fromDate = picker.startDate.format('YYYY-MM-DD');
	    $this.val($fromDate);
	    $this.attr('value',$fromDate);
	});
    }
    
    if( $('.current_profile_media').length > 0 )
    {
	MAX_LOAD = $('.current_profile_media').attr('data-pages');
	$(window).scroll(function() 
	{
	    if(currentpage >= MAX_LOAD ) return ;
	    if( $(window).scrollTop() + $(window).height() >= ($(document).height()-100) ) 
	    {
		if($('.upload-overlay-loading-fix').css('display')=='none')
		{
		    currentpage++;
		    getProfileMediaRelated();
		}
	    }
	});
    }
});
function updateImage(str, pic_link, _type) {
    if (_type == "account_pic") {
        document.location.reload();
    }
    closeFancyBox();
}
function getProfileMediaRelated()
{
    var media_type = $('.current_profile_media').attr('data-type');
    var media_usertype = $('.current_profile_media').attr('data-usertype');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: generateLangURL('/ajax/profile_media_data'),
        data: {media_type: media_type,media_usertype:media_usertype,page:currentpage},
        type: 'post',
        dataType: "json",
        cache: false,
        success: function (ret) {
            $('.upload-overlay-loading-fix').hide();
            $('.media_container').append(ret.data);
        }
    });
}