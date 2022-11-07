var controller = 'poi';
function refresh_poi_files(id)
{
    $.get('./' + controller + '/files/' + id)
    .success(function (data){
        $('#files').html(data);
    });
}

function InitCategoriesSelect(){
    $('#sltCategories').select2({
        closeOnSelect: false
    });
}

function InitCitySelect(){
    $('#txtCityName').select2({
        placeholder: "Search for a city",
        minimumInputLength: 2,
        allowClear: true,
        width: '20%',
        ajax: {
            url: "./city/search",
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                var country = $('#txtCountryCode').select2("val");
                if(country){
                    return {term: term, country: country};
                }
                else{
                    return {term: term};
                }
            },
            results: function(data, page){
                return { results: data };
            }
        },
        initSelection: function(element, callback){
            var id = $(element).val();
            if(id !== "" && id != 0){
                $.ajax("./city/getbyid", {
                    dataType: 'json',
                    type: 'post',
                    data: 'id=' + id
                }).done(function(data) { callback(data); });
            }
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

function InitCountrySelect(){
    $('#txtCountryCode').select2({
        placeholder: "Search for a country",
        minimumInputLength: 2,
        allowClear: true,
        width: '20%',
        ajax: {
            url: "./city/country_search",
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                return 'term=' + term;
            },
            results: function(data, page){
                return { results: data };
            }
        },
        initSelection: function(element, callback){
            var id = $(element).val();
            if(id !== "" && id != 0){
                $.ajax("./city/getbycountrycode", {
                    dataType: 'json',
                    type: 'post',
                    data: 'code=' + id
                }).done(function(data) { callback(data); });
            }
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
    InitCountrySelect();
    InitCitySelect();
//    controller = GetController(window.location.pathname);
    $('#map_image_container').on('click', '#updateMapImage', function(){
        var poiId = $("#id").val();
        $.ajax({
            url: './' + controller + '/update_map_image/',
            type: 'post',
            success     : function (res)
            {
                $('#map_image_container').html(res);
            },
            data: 'id=' + poiId
        });
    });
    $('#categoriesContainer').on('click', '#btnSaveCategories', function(e){
       e.preventDefault();
       $('#imgSaving').show();
       var ids =$('#sltCategories').select2("val");
       var poiId = $("#id").val();
        $('#imgSaving').show();
        $.ajax({
            url: './' + controller + '/ajax_save_categories',
            type: 'post',
            success     : function (res)
            {
                $('#imgSaving').hide();
                $("#categoriesContainer").html(res);
            },
            data: 'id=' + poiId + '&ids=' + ids
        });
    });
    
    $('#categoriesContainer').on('click', '#btnCancelCategories', function(e){
        e.preventDefault();
        $('#sltCategories').select2("destroy");
        $('#sltCategories').remove();
        $('#poiCategoriesRow').show();
        $('#editCategoriesRow').hide();
        $('#mainSltCategories').show();
    });
    
    $('#categoriesContainer').on('click', '#btnEditCategories', function(e){
        e.preventDefault();
        $('#poiCategoriesRow').hide();
        $('#editCategoriesRow').show();
        var klon = $('#mainSltCategories');
        klon.clone().attr('id', 'sltCategories' ).insertAfter( klon );
        InitCategoriesSelect();
        klon.hide();
    });
    
    $('#show_on_map,#hotel_nearby,#restaurant_nearby,#poi_nearby').click(function(){
       if($(this).prop('checked')){
           $(this).val("1");
       }
       else{
           $(this).val("0");
       }
    });
    
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var name = $('#txtName').val().trim();
        var countryCode = $('#txtCountryCode').val().trim();
        var city = $('#txtCityName').val().trim();
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
            data: { cc: countryCode, poi: name, ci: city}
        });
    });
    
    $('#txtName, #txtCountryCode, #txtCityName').keydown(function(e){
        if(e.which === 13){
            $('#btnFilter').trigger('click');
        }
    });
    
    $('#btnReset').click(function(){
        $('#txtName').val('');
//        $('#txtCountryCode').val('');
        $('#txtCountryCode').val('').trigger('change');
        $('#txtCityName').val('').trigger('change');
        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        var name = $('#txtName').val().trim();
        var countryCode = $('#txtCountryCode').val().trim();
        var city = $('#txtCityName').val().trim();
        $('#imgLoading').show();
        $.ajax({
            url: './' + controller + '/ajax_list',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { cc: countryCode, poi: name, ci: city}
        });
    });
    
    $('#upload_poi_img').submit(function(e) {
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
                if(data.status != 'error')
                {
                    $('#files').html('<p>Reloading files...</p>');
                    var id=  $('#id').val();
                    refresh_poi_files(id);
                }
            }
        });
        return false;
    });   

    $('#files').on('click', '.delete_poiimg_link', function(e) {
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