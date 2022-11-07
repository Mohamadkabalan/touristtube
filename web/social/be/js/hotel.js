var controller = 'hotel';
function refresh_files(id)
{
    $.get('./' + controller + '/files/'+id)
    .success(function (data){
        $('#files').html(data);
    });
}

function InitFacilitiesSelect(){
    $('#sltFacilities').select2({
        placeholder: "Search for a facility",
        minimumInputLength: 2,
        multiple: true,
        id: function(e){
          return e.id;
        },
        ajax: {
            url: './' + controller + '/ajax_get_features',
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                return 'q=' + term;
            },
            results: function(data, page){
                return { results: data };
            }
        },
        initSelection: function(element, callback){
            var data = element.data('all');
            callback(data);
        },
        formatResult: function(item){
            return item.text;
        },
        formatSelection: function(item){
            return item.text;
        },
        escapeMarkup: function(m){
            return m;
        }
    });
}

$(function(){
//    controller = GetController(window.location.pathname);
    $('#map_image_container').on('click', '#updateMapImage', function(){
        var hotelId = $("#id").val();
        $.ajax({
            url: './' + controller + '/update_map_image/',
            type: 'post',
            success     : function (res)
            {
                $('#map_image_container').html(res);
            },
            data: 'id=' + hotelId
        });
    });
    $('#facilitiesContainer').on('click', '#btnSaveFacilities', function(e){
       e.preventDefault();
       $('#imgSaving').show();
       var ids =$('#sltFacilities').select2("val");
       var hotelId = $("#id").val();
        $('#imgSaving').show();
        $.ajax({
            url: './' + controller + '/ajax_save_features',
            type: 'post',
            success     : function (res)
            {
                $('#imgSaving').hide();
                $("#facilitiesContainer").html(res);
            },
            data: 'id=' + hotelId + '&ids=' + ids
        });
    });
    
    $('#facilitiesContainer').on('click', '#btnCancelFacilities', function(e){
        e.preventDefault();
        $('#sltFacilities').select2("destroy");
        $('#sltFacilities').remove();
        $('#hotelFacilitiesRow').show();
        $('#editFacilitiesRow').hide();
    });
    
    $('#facilitiesContainer').on('click', '#btnEditFacilities', function(e){
        e.preventDefault();
        $('#hotelFacilitiesRow').hide();
        $('#editFacilitiesRow').show();
        var klon = $('#mainSltFacilities');
        klon.clone().attr('id', 'sltFacilities' ).insertAfter( klon );
        InitFacilitiesSelect();
    });
    
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var cityName = $('#txtCity').val().trim();
        var hotelName = $('#txtName').val().trim();
        var countryName = $('#txtCountry').val().trim();
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
            data: { ci: cityName, ho: hotelName, co: countryName, cc: countryCode }
        });
    });
    
    $('#txtCity, #txtName, #txtCountry, #txtCountryCode').keydown(function(e){
        if(e.which === 13){
            $('#btnFilter').trigger('click');
        }
    });
    
    $('#btnReset').click(function(){
        $('#txtCity').val('');
        $('#txtName').val('');
        $('#txtCountry').val('');
        $('#txtCountryCode').val('');
        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        var cityName = $('#txtCity').val().trim();
        var hotelName = $('#txtName').val().trim();
        var countryName = $('#txtCountry').val().trim();
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
            data: { ci: cityName, ho: hotelName, co: countryName, cc: countryCode }
        });
    });
    
    $('#upload_hotel_img').submit(function(e) { 
        e.preventDefault();
        $.ajaxFileUpload({
            url             :'./' + controller + '/ajax_upload/',
            secureuri       :false,
            fileElementId   :'userfile',
            dataType        : 'json',
            data            : {
                'title'     : $('#title').val(),
                'id'        : $('#id').val()
            },
            success : function (data, status)
            { 
                if(data.status !== 'error')
                {
                    $('#files').html('<p>Reloading files...</p>');
                    var id=  $('#id').val();
                    refresh_files(id);
                }
            }
        });
        return false;
    });
    $('#files').on('click', 'a.delete_file_link' , function(e) {
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
});
