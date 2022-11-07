var controller = 'thingstodo';
$(function(){
    $('#txtId, #txtName').keydown(function(e){
        if(e.which === 13){
            $('#btnFilter').trigger('click');
        }
    });
    
    $('#btnReset').click(function(){
        $('#txtId').val('');
        $('#txtName').val('');
        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        var ttdId = $('#txtId').val().trim();
        var ttdName = $('#txtName').val().trim();
        var id = $('#ttdId').val();
        $('#imgLoading').show();
        $.ajax({
            url: './' + controller + '/view_ajax',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { ttdId: ttdId, ttdName: ttdName, id: id }
        });
    });
});