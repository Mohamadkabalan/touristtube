$( document ).ready(function(){
    if( !ttModal ) {
	ttModal = window.getTTModal("myModalZ", {});
    }
    addAutoCompleteListCurrentCity('channel-location-form');
    
    $(document).on('click', ".create_channel_but", function () {
        if ( validateCahnnelForm() ) 
	{
            $('.upload-overlay-loading-fix').show();
	    var name = $("input[name=name]", $('.channel-container')).val().trim();
	    var category = $("select[name=category]", $('.channel-container')).val().trim();
	    var country = $("select[name=country]", $('.channel-container')).val().trim();
	    var city = $("input[name=city]", $('.channel-container')).val().trim();
	    var city_id = $("input[name=city]", $('.channel-container')).attr('data-id');
	    var street = $("input[name=street]", $('.channel-container')).val().trim();
	    var phone = $("input[name=phone]", $('.channel-container')).val().trim();
	    var url = $("input[name=url]", $('.channel-container')).val().trim();
	    
	    $.ajax({
		url: generateLangURL( '/ajax/channel-add', 'empty' ),
		data: {
		    name: name,
		    category: category,
		    country: country,
		    city: city,
		    city_id: city_id,
		    street: street,
		    phone: phone,
		    url: url
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
			window.top.location.href = ReturnLink('register-success/channel');
		    } else {
			ttModal.alert(jres.msg, null, null, {ok:{value:"close"}});
		    }
		}
	    });
        }
    });
});
/* Validate Channel Form */
function validateCahnnelForm() {
    if ( $("input[name=name]", $('.channel-container')).val().trim() == ''  )
    {
	ttModal.alert(Translator.trans("Please insert the channel name."), null, null, {ok:{value:Translator.trans("close")}});
        return false;
    } else if ( $("select[name=category]", $('.channel-container')).val().trim() == '') 
    {
        ttModal.alert(Translator.trans("Please choose a category."), null, null, {ok:{value:Translator.trans("close")}});
        return false;
    } else if ( $("select[name=country]", $('.channel-container')).val().trim() == '')
    {
        ttModal.alert(Translator.trans("Please choose a country."), null, null, {ok:{value:Translator.trans("close")}});
        return false;
    } else if ( $("input[name=url]", $('.channel-container')).val().trim()!='' && !ValidURL( $("input[name=url]", $('.channel-container')).val().trim() ) )
    {
        ttModal.alert(Translator.trans("Please insert a valid url."), null, null, {ok:{value:Translator.trans("close")}});
        return false;
    } else {
        return true;
    }
}