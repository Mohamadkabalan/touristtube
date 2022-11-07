$(document).ready(function () 
{
    $(document).on('click',".create_album_but" ,function()
    {
	$('.error_hints').html('');
	if ( validateDataForm() ) 
	{
            $('.upload-overlay-loading-fix').show();
	    var channel_id = channelGlobalID();
	    var country = $('select[name=country]').val().trim();
	    var category = $('select[name=category]').val().trim();
	    var city = $('input[name=city]').val().trim();
	    var city_id = $("input[name=city]").attr('data-id');
	    var name = $('input[name=name]').val().trim();
	    var description = $('textarea[name=description]').val().trim();
	    
	    $.ajax({
		url: generateLangURL( '/ajax/album-add', 'empty' ),
		data: {
		    name: name,
		    category: category,
		    country: country,
		    city: city,
		    city_id: city_id,
		    channel_id: channel_id,
		    description: description
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
			closeModalPopup(jres);			
		    } else {
			$('.error_hints').html(jres.msg);
		    }
		}
	    });
        }
    });

    $(document).on('click',".create_media_but" ,function()
    {
	$('.error_hints').html('');
	if ( validateDataForm() ) 
	{
            $('.upload-overlay-loading-fix').show();
	    var channel_id = channelGlobalID();
	    var country = $('select[name=country]').val().trim();
	    var category = $('select[name=category]').val().trim();
	    var album_id = $('select[name=album_pop]').val().trim();
	    var city = $('input[name=city]').val().trim();
	    var city_id = $("input[name=city]").attr('data-id');
	    var temp_id = $(".add_media_container").attr('data-temp');
	    var size = $(".add_media_container").attr('data-size');
	    var name = $('input[name=name]').val().trim();
	    var description = $('textarea[name=description]').val().trim();
	    
	    $.ajax({
		url: generateLangURL( '/ajax/media-add', 'empty' ),
		data: {
		    name: name,
		    category: category,
		    country: country,
		    city: city,
		    city_id: city_id,
		    channel_id: channel_id,
		    album_id: album_id,
		    temp_id: temp_id,
		    size: size,
		    description: description
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
			closeCreateModalMediaPopup();			
		    } else {
			$('.error_hints').html(jres.msg);
		    }
		}
	    });
        }
    });
    
    $(document).on('click',".edit_media_but" ,function()
    {
	$('.error_hints').html('');
	if ( validateDataForm() ) 
	{
            $('.upload-overlay-loading-fix').show();
	    var channel_id = channelGlobalID();
	    var country = $('select[name=country]').val().trim();
	    var category = $('select[name=category]').val().trim();
	    var city = $('input[name=city]').val().trim();
	    var city_id = $("input[name=city]").attr('data-id');
	    var id = $(".add_media_container").attr('data-id');
	    var type = $(".add_media_container").attr('data-type');
	    var name = $('input[name=name]').val().trim();
	    var description = $('textarea[name=description]').val().trim();
            var album_id=0;
	    if( $('select[name=album_pop]').length>0 ){
                 album_id = $('select[name=album_pop]').val().trim();
             }
	    $.ajax({
		url: generateLangURL( '/ajax/media-edit', 'empty' ),
		data: {
		    name: name,
		    category: category,
		    country: country,
		    city: city,
		    city_id: city_id,
		    channel_id: channel_id,
		    album_id: album_id,
		    id: id,
		    type: type,
		    description: description
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
			closeModalMediaPopup();			
		    } else {
			$('.error_hints').html(jres.msg);
		    }
		}
	    });
        }
    });
});

/* Validate Form */
function validateDataForm() 
{
    if ( $("select[name=country]").val().trim() == '')
    {
        $('.error_hints').html(Translator.trans("Please choose a country."));
        return false;
    } 
    else if ( $("input[name=city]").val().trim() == '' || $("input[name=city]").attr('data-id').trim() == '' || $("input[name=city]").attr('data-id').trim() == '0')
    {
	$("input[name=city]").val('');
	$("input[name=city]").attr('data-id', '');
        $('.error_hints').html(Translator.trans("Please choose a city."));
        return false;
    } 
    else if ( $("select[name=category]").val().trim() == '') 
    {
        $('.error_hints').html(Translator.trans("Please choose a category."));
        return false;
    } 
    else if ( $("input[name=name]").val().trim() == ''  )
    {
	$('.error_hints').html(Translator.trans("Please insert the title."));
        return false;
    } 
    else if ( $("input[name=name]").val().length >100  )
    {
	$('.error_hints').html( Translator.trans("Title must be maximum 100 characters long.") );
        return false;
    } 
    else {
        return true;
    }
}