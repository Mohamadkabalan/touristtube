var controller = 'thingstodo';
$(function(){
    $('form').submit(function(event){
        var title = $('input[name="title"]').val();
        var id = $('input[name="id"]').val();
       $.ajax({
            async: false,
            dataType: 'json',
            url: './' + controller + '/validate_title',
            type: 'post',
            success     : function (res)
            {
                if(res.status === 'error'){
                    alert(res.msg);
                    event.preventDefault();
                }
            },
            data: { title: title, id: id }
        }); 
    });
});