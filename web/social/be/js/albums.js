$(function(){
    $('#mediaStatus').change(function(){
        LoadData();
    });

    $('#ownerType').change(function(){
        LoadData();
    });
    
    $('.container').on('click', '.acceptAct', function(e){
        e.preventDefault();
        var self = this;
        if(confirm("Are you sure you want to change the owner of this Album to user india2?")){
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                success: function(res){
                    if(res.success){
                        $(self).closest('tr').remove();
                    }
                    else{
                        alret("An error occured.");
                    }
                }
            })
        }
    });
});

function LoadData(){
    var value = $( "#ownerType option:selected" ).attr('value');
    var mediaStatus = $( "#mediaStatus option:selected" ).attr('value');
    var url = '';
    switch(value){
        case "1":
            url = mediaStatus === '1' ? 'media/tubers_albums_list' : 'media/accepted_tubers_albums_list';
            $.ajax({
                url: url,
                type: 'post',
                success: function(res){
                    $("#albumsContainer").html(res);
                }
            });
            break;
        case "2":
            url = mediaStatus === '1' ? 'media/channels_albums_list' : 'media/accepted_channels_albums_list';
            $.ajax({
                url: url,
                type: 'post',
                success: function(res){
                    $("#albumsContainer").html(res);
                }
            });
            break;
    }
}