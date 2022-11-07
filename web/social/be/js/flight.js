var controller = 'flight';

$(function(){
    
    
    $('#priceAdjust').click(function(){
        $('#myModal').modal(
            {
                'backdrop' : false
            }
        );
    });
        
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        
        var pnr = $('#txtPnr').val().trim();
        var email = $('#txtEmail').val().trim();
        var fname = $('#txtFname').val().trim();
        var sname = $('#txtSname').val().trim();
        var status = $('#txtStatus').val();

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
            data: { pnr: pnr, email: email, fname: fname, sname: sname, status: status}
        });
    });
    
    $('#btnReset').click(function(){
        $('#txtPnr').val('');
        $('#txtEmail').val('');
        $('#txtFname').val('');
        $('#txtSname').val('');
        $('#txtStatus').val('0');

        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        
        var pnr = $('#txtPnr').val().trim();
        var email = $('#txtEmail').val().trim();
        var fname = $('#txtFname').val().trim();
        var sname = $('#txtSname').val().trim();
        var status = $('#txtStatus').val();

        $('#imgLoading').show();
        $.ajax({
            url: './' + controller + '/ajax_list',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { pnr: pnr, email: email, fname: fname, sname: sname, status: status}
        });
    });
    
    $('#saveAddFee').click(function(e){
        
        $('#imgLoading').show();
        
        var pnr_id = $('#pnr_id').val();
        var add_fee = $('#add_fee').val().trim();
        var customer_email = $('#customer_email').val().trim();
        var message_body = $('#message_body').val().trim();
        
        $.ajax({
            url: './' + controller + '/sendCancellationFee',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                location.reload();
            },
            data: { 
                pnr_id: pnr_id, 
                add_fee: add_fee, 
                customer_email: customer_email, 
                message_body: message_body
            }
        });
    });
    
});