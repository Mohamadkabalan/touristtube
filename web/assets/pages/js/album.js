var currentpage = 0;
var MAX_LOAD;

$(document).ready(function ()
{    
    MAX_LOAD = $('.current_album').attr('data-pages');
    $(window).scroll(function() 
    {
        if(currentpage >= MAX_LOAD ) return ;
        if( $(window).scrollTop() + $(window).height() >= ($(document).height()-100) ) 
	{
            if($('.upload-overlay-loading-fix').css('display')=='none')
	    {
                currentpage++;
                getAlbumRelated();
            }
        }
    });
});

function getAlbumRelated()
{
    var media_type = 'a';
    var media_id = $('.current_album').attr('data-id');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: generateLangURL('/ajax/albums_data'),
        data: {media_type: media_type,media_id:media_id,page:currentpage},
        type: 'post',
        dataType: "json",
        cache: false,
        success: function (ret) {
            $('.upload-overlay-loading-fix').hide();
            $('.media_container').append(ret.data);
        }
    });
}