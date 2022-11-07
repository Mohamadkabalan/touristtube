$(function(){
   $('.inviteAct').click(function(e){
        e.preventDefault();
        var self = this;
        if(confirm("Are you sure you want to invite this user?")){
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                success: function(res){
                    if(res.status === "ok"){
                        $(self).closest('tr').remove();
                    }
                    else{
                        alret("An error occured.");
                    }
                }
            });
        }
    }); 
});