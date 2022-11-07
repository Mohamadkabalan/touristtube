var controller = 'restaurant';
function refresh_retaurant_files(id)
{
    $.get('./' + controller + '/files/'+id)
    .success(function (data){
        $('#files').html(data);
    });
}

function InitCuisinesSelect(){
    $('#sltCuisines').select2({
        closeOnSelect: false
    });
}

$(function(){
//    controller = GetController(window.location.pathname);
    $('#map_image_container').on('click', '#updateMapImage', function(){
        var restaurantId = $("#id").val();
        $.ajax({
            url: './' + controller + '/update_map_image/',
            type: 'post',
            success     : function (res)
            {
                $('#map_image_container').html(res);
            },
            data: 'id=' + restaurantId
        });
    });
    
    $('#cuisinesContainer').on('click', '#btnSaveCuisines', function(e){
       e.preventDefault();
       $('#imgSaving').show();
       var ids =$('#sltCuisines').select2("val");
       var restId = $("#id").val();
        $('#imgSaving').show();
        $.ajax({
            url: './' + controller + '/ajax_save_cuisines',
            type: 'post',
            success     : function (res)
            {
                $('#imgSaving').hide();
                $("#cuisinesContainer").html(res);
            },
            data: 'id=' + restId + '&ids=' + ids
        });
    });
    
    $('#cuisinesContainer').on('click', '#btnCancelCuisines', function(e){
        e.preventDefault();
        $('#sltCuisines').select2("destroy");
        $('#sltCuisines').remove();
        $('#restaurantCuisinesRow').show();
        $('#editCuisinesRow').hide();
        $('#mainSltCuisines').show();
    });
    
    $('#cuisinesContainer').on('click', '#btnEditCuisines', function(e){
        e.preventDefault();
        $('#restaurantCuisinesRow').hide();
        $('#editCuisinesRow').show();
        var klon = $('#mainSltCuisines');
        klon.clone().attr('id', 'sltCuisines' ).insertAfter( klon );
        InitCuisinesSelect();
        klon.hide();
    });
    
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var cityName = $('#txtCity').val().trim();
        var name = $('#txtName').val().trim();
        var countryCode = $('#txtCountryCode').val().trim();
        var url = './' + $(this).attr('href');
        $('#imgLoading').show();
        $.ajax({
            url: url,
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { ci: cityName, re: name, cc: countryCode}
        });
    });
    
    $('#txtCity, #txtName, #txtCountryCode').keydown(function(e){
        if(e.which === 13){
            $('#btnFilter').trigger('click');
        }
    });
    
    $('#btnReset').click(function(){
        $('#txtCity').val('');
        $('#txtName').val('');
        $('#txtCountryCode').val('');
        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        var cityName = $('#txtCity').val().trim();
        var name = $('#txtName').val().trim();
        var countryCode = $('#txtCountryCode').val().trim();
        $('#imgLoading').show();
        $.ajax({
            url: './' + controller + '/ajax_list',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { ci: cityName, re: name, cc: countryCode}
        });
    });
    
    $('#upload_restaurant_img').submit(function(e) { 
        e.preventDefault();
        $.ajaxFileUpload({
            url             :'./' + controller + '/ajax_upload/',
            secureuri       : false,
            fileElementId   :'userfile',
            dataType        : 'json',
            data            : {
                'title'     : $('#title').val(),
                'id'        : $('#id').val()
            },
            success : function (data, status)
            {
                if(data.status != 'error')
                {
                    $('#files').html('<p>Reloading files...</p>');
                    var id=  $('#id').val();
                    refresh_retaurant_files(id);
                }
            }
        });
        return false;
    });
    
    $('#files').on('click', '.delete_restimg_link', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this file?'))
        {
            var link = $(this);
            $.ajax({
                url         : './' + controller + '/delete_file/' + link.data('file_id') + '/' + $('#id').val(),
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
})