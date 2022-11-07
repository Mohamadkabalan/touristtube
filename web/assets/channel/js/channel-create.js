$(document).ready(function () 
{
    if( !ttModal ) {
	ttModal = window.getTTModal("myModalZ", {});
    }
    channelGlobalID($('.channel-container').attr('data-id'));
    
    if( $('#channel_profile').length>0 )
    {
//	InitUploaderHome('channel_profile', 'uploadContainer1', 15,0);
	initCropAndUpload();
    }
    
//    if( $('#channel_cover').length>0 )
//    {
//	InitUploaderHome('channel_cover', 'uploadContainer2', 15,0);
//    }
    
    if( $('#locationDetails').length>0 )
    {
	addAutoCompleteListCurrentCity('locationDetails');
    }
    
    $(document).on('click',".info_save_changes" ,function()
    {
	var about = $('textarea[name=about]').val();
	var slogan = $('input[name=slogan]').val();
	var keywords = $('input[name=keywords]').val();
	
	if (about.length == 0) {
	    ttModal.alert(Translator.trans('Please fill in the "about" with an appropriate description'), null, null, {ok:{value:Translator.trans("close")}});
            return false;
        }
	
	if ( slogan.length > 110 ) {
	    ttModal.alert(sprintf(Translator.trans("Slogan must be maximum %s characters long"), [110]), null, null, {ok:{value:Translator.trans("close")}});
            return false;
        }
	
	if( $('input[name=channel_url]').length>0 )
	{
	    var channel_url = $('input[name=channel_url]').val();
	    if ( channel_url.length == 0 ) {
		ttModal.alert(Translator.trans('Please fill in your "TT Channel URL"'), null, null, {ok:{value:Translator.trans("close")}});
		return false;
	    }

	    if ( channel_url.length > 60 ) {
		ttModal.alert(sprintf(Translator.trans("TT Channel URL must be maximum %s characters long"), [60]), null, null, {ok:{value:Translator.trans("close")}});
		return false;
	    }
	}
	
	var update_list = {};
	
	update_list['infoId'] = about;
	
	if( $('input[name=channel_url]').length>0 )
	{
	    update_list['channelUrl'] = channel_url;
	}
	
	if ( slogan.length != 0 ) 
	{
	    update_list['sloganId'] = slogan;
        }
	
	if ( keywords.length != 0 ) 
	{
	    update_list['keywords'] = keywords;
        }
	
	var valid_data= true;
	var custom_list = [];
	$('.social_container1 .social_input').each(function(index, element) {
	    var $this= $(this);
	    var input_val = $this.val();
	    var $data_id = $this.attr('data-id');
	    if( input_val.length != 0 ){
		if ( input_val.trim()!='' && !ValidURL( input_val.trim() ) )
		{
		    ttModal.alert(Translator.trans("Please insert a valid customized links."), null, null, {ok:{value:Translator.trans("close")}});
		    $this.val('');
		    valid_data= false;
		    return false;
		}
		var innerObj = {'id':$data_id, 'link':input_val.trim(), 'isSocial':0};
		custom_list.push(innerObj);	
	    }
	});
	
	$('.social_container2 .social_input').each(function(index, element) {
	    var $this= $(this);
	    var input_val = $this.val();
	    var $data_id = $this.attr('data-id');
	    if( input_val.length != 0 ){		
		if ( input_val.trim()!='' && !ValidURL( input_val.trim() ) )
		{
		    ttModal.alert(Translator.trans("Please insert a valid social links."), null, null, {ok:{value:Translator.trans("close")}});
		    $this.val('');
		    valid_data= false;
		    return false;
		}
		var innerObj = {'id':$data_id, 'link':input_val.trim(), 'isSocial':1};
		custom_list.push(innerObj);	
	    }
	});
	
	if( valid_data )
	{
	    var $channel_id = $('.channel-container').attr('data-id');
	    
	    if( custom_list.length >0 )
	    {
		var jsonLinksString = JSON.stringify(custom_list);
		updateListOfChannelLinksData( $channel_id, jsonLinksString );
	    }
	    
	    var jsonString = JSON.stringify(update_list);
	    updateChannelPageData( $channel_id, jsonString );	    
	}
    });
    
    $(document).on('click',".settings_save_changes" ,function()
    {
	var channel_name = $('input[name=channel_name]').val().trim();
	var category = $('select[name=category]').val().trim();
	var country = $('select[name=country]').val().trim();
	var city = $('input[name=city]').val().trim();
	var city_id = $('input[name=city]').attr('data-id');
	var street = $('input[name=street]').val().trim();
	var default_link = $('input[name=default_link]').val().trim();
	var zip_code = $('input[name=zip_code]').val().trim();
	var phone = $('input[name=phone]').val().trim();
	
	if ( channel_name.length == 0 ) 
	{
	    ttModal.alert(Translator.trans('Please fill in the "channel name" with an appropriate name'), null, null, {ok:{value:Translator.trans("close")}});
            return false;
        }
	
	if ( category.length == 0 || category==0 )
	{
	    ttModal.alert(Translator.trans('Please select channel category'), null, null, {ok:{value:Translator.trans("close")}});
            return false;
        }
	
	if ( country.length == 0 ) 
	{
	    ttModal.alert(Translator.trans('Please choose a country'), null, null, {ok:{value:Translator.trans("close")}});
            return false;
        }
	
	if ( default_link.length > 0  && !ValidURL( default_link ) )
	{
	    ttModal.alert(Translator.trans("Please insert a valid url."), null, null, {ok:{value:Translator.trans("close")}});
	    return false;
	}
	
	var update_list = {};
	
	update_list['channelName'] = channel_name;
	update_list['category'] = category;
	update_list['country'] = country;
	
	if( city.length>0 )
	{
	    update_list['city'] = city;
	    update_list['cityId'] = city_id;
	}
	
	if( street.length>0 )
	{
	    update_list['street'] = street;
	}
	
	if( default_link.length>0 )
	{
	    update_list['defaultLink'] = default_link;
	}
	
	if( zip_code.length>0 )
	{
	    update_list['zipCode'] = zip_code;
	}
	
	if( phone.length>0 )
	{
	    update_list['phone'] = phone;
	}
	
	var valid_data= true;
	
	if( valid_data )
	{
	    var $channel_id = $('.channel-container').attr('data-id');
	    
	    var jsonString = JSON.stringify(update_list);
	    updateChannelPageData( $channel_id, jsonString );	    
	}
    });
    
    $('select[name=country]').change(function(){
	$('input[name=city]').val('');
	$('input[name=city]').attr('data-id','')
    });
    
    $(document).on('change', ".report_issue_but", function () {
	if( $(this).prop("checked") ) {
	    $('textarea[name=issue]').val('');
	    $('.report_issue').show();
	}else{
	    $('textarea[name=issue]').val('');
	    $('.report_issue').hide();
	}
    });

    $(document).on('click',".tell_us" ,function()
    {
	$('.report_container').show();
    });

    $(document).on('click',".send_report_but" ,function()
    {
	var $channel_id = $('.channel-container').attr('data-id');
	var $issue = $('textarea[name=issue]').val().trim();
	var report_check_list = [];
	
	$('.report_check').each(function()
	{
	    if( $(this).prop("checked") ) 
	    {
		report_check_list.push($(this).attr('data-id'));
	    }
	});
	
	if( $('.report_issue_but').prop("checked") && $issue.length == 0 )
	{
	    ttModal.alert(Translator.trans('Please write your Report'), null, null, {ok:{value:Translator.trans("close")}});
	    return false;
	} 
	else if( !$('.report_issue_but').prop("checked") )
	{
	    $('textarea[name=issue]').val('');
	    $issue = '';
	}
	
	if( report_check_list.length==0 )
	{
	    ttModal.alert(Translator.trans('Please choose one of the above proposed options to Report'), null, null, {ok:{value:Translator.trans("close")}});
	    return false;
	}
	
	var entity_type = SOCIAL_ENTITY_CHANNEL;
	var reason = report_check_list.join(',');
	
	$('.upload-overlay-loading-fix').show();
	$.ajax({
	    url: generateLangURL( '/ajax/user/report/add', 'empty' ),
	    data: {entity_type:entity_type, entity_id:$channel_id, channel_id : $channel_id, msg: $issue , reason:reason},
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
		    ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
		    return;
		}	    
		ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
		if (jres.status == 'ok') {
		    $('.report_check').prop("checked",false);
		    $('textarea[name=issue]').val('');
		    $('.report_issue').hide();
		    $('.report_container').hide();
		    $('.delete_container').show();
		}
	    }
	});
    });

    $(document).on('click',".info_delete_channel" ,function()
    {
	if( !$('.delete_check').prop("checked") )
	{
	    ttModal.alert(Translator.trans('You need to confirm in order to delete your channel page'), null, null, {ok:{value:Translator.trans("close")}});
	    return false;
	}
	var $link = '/user/confirm/login';
	var ttModal1 = window.getTTModal("myModalZ", {url: {href: $link}, width: 350, title: Translator.trans('Sign In')});
	ttModal1.show();
    });

    $(document).on('click',".user_confirm_password" ,function()
    {
	var $channel_id = $('.channel-container').attr('data-id');
	var $uname = $('input[name=uname]').val().trim();
	var $password = $('input[name=password]').val().trim();
	$('.confirmpasswordpopuocontainer .error_hints').html('');

	if ($uname.length == 0) 
	{
	    $('.confirmpasswordpopuocontainer .error_hints').html( Translator.trans("Please specify your email address / username") );
	    return false;
	}
	
	if ($password.length == 0) 
	{
	    $('.confirmpasswordpopuocontainer .error_hints').html( Translator.trans("Please specify your password") );
	    return false;
	}
	
	$('.upload-overlay-loading-fix').show();
	$.ajax({
	    url: generateLangURL( '/ajax/channel_delete', 'empty' ),
	    data: {uname:$uname, password:$password, channel_id : $channel_id},
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
		    ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
		    return;
		}	    
		ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
		if (jres.status == 'ok') {
		    setTimeout(function() {
			document.location.href = ReturnLink('/channels');
		    }, 1500);
		}
	    }
	});
    });
    $(document).on('keydown', "input[name=password]", function (e) 
    {
	if (e.keyCode == 13) {
	    $(".user_confirm_password").click();
	}
    });
    
    $(document).on('click',".btn_add_cch" ,function()
    {
	var $this= $(this);
	var $parent= $this.closest('.input_chanel_inp_app');
	var $social_container = $parent.find('.social_container');
	var $placeholder = $social_container.attr('placeholder');
	
	var $newLink = '<div class="col-xs-12 nopad social_item"><div class="row no-margin"><div class="col-sm-6 col-xs-12 nopad"><input class="social_input" autocomplete="off" type="text" placeholder="'+$placeholder+'" data-id="0" value=""/><button class="btn btn-remove-add_cch btn_remove_cch" type="button">-</button></div></div></div>';
	
	$social_container.append($newLink);
    });
    
    $(document).on('click',".btn_remove_cch" ,function()
    {
	var $this= $(this);
	var $parent= $this.closest('.social_container');
	var $social_item = $this.closest('.social_item');
	var $social_input = $social_item.find('.social_input');
	var $data_id = $social_input.attr('data-id');
	
	if ( $data_id !=0 && $data_id !='')
	{
	    var update_list = {};
	    update_list['published'] = -2;
	    var jsonString = JSON.stringify(update_list);
	    var $channel_id = $('.channel-container').attr('data-id');
	    setChannelDatalink( $channel_id, $data_id, jsonString, $social_item );
	}
	else
	{
	    $social_item.remove();
	    if( $parent.find('.social_item').length==0 )
	    {
		$parent.closest('.input_chanel_inp_app').find('.btn_add_cch').click();
	    }
	}
    });
});

function updateImage(str, curname, _type)
{
    var $channel_id = $('.channel-container').attr('data-id');
    var param1 = '';
    var update_list = {};
    if (_type == "channel_profile") {
	$('#uploadContainer1 .channel_update_img img').remove();
	$('#uploadContainer1 .channel_update_img').append(str);
	param1 = 'profileId';
    } else if (_type == "channel_cover") {
        $('#uploadContainer2 .channel_update_img img').remove();
	$('#uploadContainer2 .channel_update_img').append(str);
	param1 = 'coverId';
    }
    closeFancyBox();
    
    update_list[param1] = curname;
    var jsonString = JSON.stringify(update_list);
    updateChannelPageData( $channel_id, jsonString );
}

function updateChannelPageData( $channel_id, jsonString ) 
{
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: generateLangURL( '/ajax/channel_info_update', 'empty' ),
        data: {channel_id: $channel_id, update_list: jsonString},
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
		ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
		return;
	    }	    
	    ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
	}
    });
}

function updateListOfChannelLinksData( $channel_id, jsonString ) 
{
    $.ajax({
        url: generateLangURL( '/ajax/channel_list_links_update', 'empty' ),
        data: {channel_id: $channel_id, update_list: jsonString},
        type: 'post',
	success: function (data) {
	    var jres = null;
	    try {
		jres = data;
		var status = jres.status;
	    } catch (Ex) {
	    }
	}
    });
}

function setChannelDatalink( $channel_id, id, jsonString, $social_item )
{
    $.ajax({
	url: generateLangURL( '/ajax/channel_links_update', 'empty' ),
	data: {channel_id: $channel_id, id:id, update_list: jsonString},
	type: 'post',
	success: function(data){
	    $('.upload-overlay-loading-fix').hide();
	    var jres = null;
	    try {
		jres = data;
		var status = jres.status;
	    } catch (Ex) {
	    }
	    if (!jres) {
		ttModal.alert(Translator.trans("Couldn't save please try again later"), null, null, {ok:{value:Translator.trans("close")}});
		return false;
	    }
	    ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
	    if (jres.status == 'ok') {
		var $parent= $social_item.closest('.social_container');
		$social_item.remove();
		if( $parent.find('.social_item').length==0 )
		{
		    $parent.closest('.input_chanel_inp_app').find('.btn_add_cch').click();
		}
	    } else {
		return false;
	    }
	}
    });
}