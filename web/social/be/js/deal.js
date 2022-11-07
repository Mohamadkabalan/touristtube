var controller = 'deal';

$(function(){
    
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var dealName = $('#txtName').val().trim();
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
            data: { dn: dealName }
        });
    });
    
    $('#txtName').keydown(function(e){
        if(e.which === 13){
            $('#btnFilter').trigger('click');
        }
    });
    
    $('#btnReset').click(function(){
        $('#txtName').val('');
        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        var dealName = $('#txtName').val().trim();
        $('#imgLoading').show();
        $.ajax({
            url: './' + controller + '/ajax_list',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { dn: dealName }
        });
    });
});
