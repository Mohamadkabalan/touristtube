var controller;
var slideglob;
function InitFacilitiesMultiSelect(){
    $('#sltFacilities').select2({
        closeOnSelect: false
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
            url: './' + controller + '/ajax_get_facilities',
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

function initiSlider(){
    slideglob = $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        centerMode: true
    });
    $('.slider-for').removeClass('opacity0');
    if ($('.mediathumb').length > 0) {
        $('.mediathumb').eq(0).addClass('active');
        $('.slider-for').on('afterChange', function (event, slick, index) {
            $('.mediathumb').removeClass('active');
            $('.mediathumb').eq(index).addClass('active');
        });
    }
}

$(function(){
    if($("#typehotel").val() == 1){
       controller = 'hotelchain';
    }else{
       controller = 'hotels'; 
    }
    
    initiSlider();
    $(document).on('click', '.btn_delete22', function(e){
        e.preventDefault();
        var slider_item = $(this).parent();
        var index = slider_item.attr('data-slick-index');
        console.log(index);
        var image_id = $(this).attr('data-id');
        $.ajax({
           url: generateLangURL('/' + controller + '/ajax_delete_image'),
           type: 'post',
           success : function(res){
               $('.slider-for').slick('unslick');
               $('#slider_container').html(res);
               $('#image_types').html(res);
               initiSlider();
               var newIndex = 0;
               if(index > 0){
                   newIndex = index - 1;
               }
               $('.slider-for').slick('slickGoTo', newIndex);
               if(res.success){

               }
               else{

               }
           },
           data: { image_id : image_id }
        });
    });
    
    $(document).on('click', ".mediathumb", function (e) {
        var indx = $(this).index();
        slideglob[0].slick.slickGoTo(indx);
        $('.mediathumb').removeClass('active');
        $(this).addClass('active');
    });
    
    $('#upload_hotels_img').submit(function(e) { 
        e.preventDefault();
        var select = $("#location").val();
        $.ajaxFileUpload({
            url             :'./' + controller + '/ajax_upload_hotels/',
            secureuri       :false,
            fileElementId   :'userfile',
            dataType        : 'json',
            data            : {
                'title'     : $('#title').val(),
                'id'        : $('#id').val(),
                'select'    : select
            },
            success : function (data, status)
            { 
                window.location.href = './' + controller +'/view/'+$('#id').val();    
            }
        });
        return false;
    });

    $('#facilitiesContainer').on('click', '#btnSaveFacilities', function(e){
       e.preventDefault();
       $('#imgSaving').show();
       var ids =$('#sltFacilities').select2("val");
       var hotelId = $("#id").val();
        $('#imgSaving').show();
        $.ajax({
            url: './' + controller + '/ajax_save_facilities',
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
        $('#sltFacilities').show();
        InitFacilitiesMultiSelect();
//        InitFacilitiesSelect();
    });
    
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var cityName = $('#txtCity').val().trim();
        var hotelName = $('#txtName').val().trim();
        var hotelId = $('#txtId').val().trim();
        var countryName = $('#txtCountry').val().trim();
        var stars = $('#txtStars').val().trim();
//        var countryCode = $('#txtCountryCode').val().trim();
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
            data: { ci: cityName, ho: hotelName, s: stars, co: countryName, hi: hotelId }
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
        $('#txtId').val('');
        $('#txtCountry').val('');
        $('#txtStars').val('');
        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        var cityName = $('#txtCity').val().trim();
        var hotelName = $('#txtName').val().trim();
        var hotelId = $('#txtId').val().trim();
        var countryName = $('#txtCountry').val().trim();
        var stars = $('#txtStars').val().trim();
        $('#imgLoading').show();
        $.ajax({
            url: './' + controller + '/ajax_list',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { ci: cityName, ho: hotelName, s: stars, co: countryName, hi: hotelId }
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
