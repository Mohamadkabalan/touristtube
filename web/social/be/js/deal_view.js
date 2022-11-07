var controller = 'deal';

function refresh_files(id)
{
    $.get('./' + controller + '/files/'+id)
    .success(function (data){
        $('#deal_files').html(data);
    });
}

$(function(){
    
    $('#upload_deal_img').submit(function(e) { 
        e.preventDefault();
        $.ajaxFileUpload({
            url             :'./' + controller + '/ajax_upload/',
            secureuri       :false,
            fileElementId   :'userfile',
            dataType        : 'json',
            data            : 
            {
                'id'        : $('#id').val()
            },
            success : function (data, status)
            { 
                if(data.status !== 'error')
                {
                    $('#deal_files').html('<p>Reloading files...</p>');
                    var id=  $('#id').val();
                    refresh_files(id);
                }
            }
        });
        return false;
    });
    
    $('#deal_files').on('click', 'a.delete_file_link' , function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this images?'))
        {
            var link = $(this);
            $.ajax({
                url         : './' + controller + '/delete_image/' + link.data('file_id') + '/' + $('#id').val(),
                dataType    : 'json',
                success     : function (data)
                {
                    files = $('#files');
                    if (data.status === "success")
                    {
                        link.parents('li').fadeOut('fast', function() {
                            $(this).remove();
                            if (files.find('li').length == 0)
                            {
                                files.html('<p>No Files Uploaded</p>');
                            }
                        });
                    }
                    else
                    {
                        alert(data.msg);
                    }
                }
            });
        }
    });
    
    // reoredr image code
    $('.reorder_link').on('click',function(){
        $("ul.reorder-photos-list").sortable({ tolerance: 'pointer' });
        $('.reorder_link').html('save reordering');
        $('.reorder_link').attr("id","save_reorder");
        $('#reorder-helper').slideDown('slow');
        $('.image_link').attr("href","javascript:void(0);");
        $('.image_link').css("cursor","move");
        $("#save_reorder").click(function( e ){
        if( !$("#save_reorder i").length )
        {
               // $(this).html('').prepend('<img src="../images/refresh-animated.gif"/>');
                $("ul.reorder-photos-list").sortable('destroy');
                $("#reorder-helper").html( "Reordering Photos - This could take a moment. Please don't navigate away from this page." ).removeClass('light_box').addClass('notice notice_error');

                var h = [];
                $("ul.reorder-photos-list li").each(function() {  h.push($(this).attr('id').substr(9));  });
                $.ajax({
                        type: "POST",
                        url: './' + controller + '/updateImageOrder/',
                        data: {ids: " " + h + ""},
                        success: function(html) 
                        {     
                            window.location.reload();
                        }

                });	
                return false;
        }	
            e.preventDefault();		
	});
    });
    
    //upload thumb image
    $('#upload_deal_thumb_img').submit(function(e) { 
        e.preventDefault();
        $.ajaxFileUpload({
            url             :'./' + controller + '/ajax_thumb_upload/',
            secureuri       :false,
            fileElementId   :'thumb',
            dataType        : 'json',
            data            : 
            {
                'id'        : $('#id').val()
            },
            success : function (data, status)
            { 
                if(data.status !== 'error')
                {
                    //var id=  $('#id').val();
                     var image = document.getElementById('thumb_image');
                      image.src = "../social/media/deals/" + data.path;
                       $("#thumb_image").show();
                }
            }
        });
        return false;
    });
    
});

 $(function () {
                $('#datetimepicker1').datetimepicker({
                    format: 'LT'
                });
                
                 $('#datetimepicker2').datetimepicker({
                    format: 'LT'
                });
            });