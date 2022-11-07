var controller = 'hotels';

$(function(){
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var keyword = $('#txtKeywrod').val().trim();
        var name = $('#txtName').val().trim();
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
             data: { keyword: keyword, name: name }
        });
    });
    
    $('#txtKeyword, #txtName').keydown(function(e){
        if(e.which === 13){
            $('#btnFilter').trigger('click');
        }
    });
    
    $('#btnReset').click(function(){
        $('#txtKeyword').val('');
        $('#txtName').val('');
        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        var keyword = $('#txtKeyword').val().trim();
        var name = $('#txtName').val().trim();
//        var countryCode = $('#txtCountryCode').val().trim();
        $('#imgLoading').show();
        $.ajax({
            url: './' + controller + '/hotel_search_ajax',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { keyword: keyword, name: name }
        });
    });
});