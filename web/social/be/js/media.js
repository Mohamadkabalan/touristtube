function LoadData(){
    var value = $( "#ownerType option:selected" ).attr('value');
    var mediaStatus = $( "#mediaStatus option:selected" ).attr('value');
    switch(value){
        case "1":
            $.ajax({
                url: 'media/tubers_albums',
                type: 'post',
                success: function(res){
                    $("#albumsContainer").show();
                    $("#otherContainer").html('');
                    $("#albumsContainer").html(res);
//                    var firstVal = $('#albumsList').find('option').first().val();
                    var firstVal = $( "#albumsList option:selected" ).attr('value');
                    $('#albumsList').val(firstVal);
                    $('#albumsList').trigger('change');
                }
            });
            break;
        case "2":
            $.ajax({
                url: 'media/channels_albums',
                type: 'post',
                success: function(res){
                    $("#albumsContainer").show();
                    $("#otherContainer").html('');
                    $("#albumsContainer").html(res);
//                    var firstVal = $('#albumsList').find('option').first().val();
                    var firstVal = $( "#albumsList option:selected" ).attr('value');
                    $('#albumsList').val(firstVal);
                    $('#albumsList').trigger('change');
                }
            });
            break;
        case "3":
            var url = '';
            if(mediaStatus === '1')
                url = 'media/tubers_media';
            else if(mediaStatus === '2')
                url = 'media/accepted_tubers_media';
            else
                url = 'media/deleted_tubers_media';
            $.ajax({
                url: url,
                type: 'post',
                success: function(res){
                    $("#albumsContainer").hide();
                    $("#otherContainer").show();
                    $("#otherContainer").html(res);
                    $("#albumMediaContainer").html('');
                }
            });
            break;
        case "4":
            var url = '';
            if(mediaStatus === '1')
                url = 'media/channels_media';
            else if(mediaStatus === '2')
                url = 'media/accepted_channels_media';
            else
                url = 'media/deleted_channels_media';
            $.ajax({
                url: url,
                type: 'post',
                success: function(res){
                    $("#albumsContainer").hide();
                    $("#otherContainer").show();
                    $("#otherContainer").html(res);
                    $("#albumMediaContainer").html('');
                }
            });
            break;
    }
}

$(function(){
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var url = './' + $(this).attr('href');
        $('#imgLoading').show();
        $.ajax({
            url: url,
            type: 'post',
            success: function (res)
            {
                $('#imgLoading').hide();
                $("#otherContainer").html(res);
            }
        });
    });
    
    $('.container').on('change', '#albumsList', function(e){
        var value = $( "#albumsList option:selected" ).attr('value');
        var mediaStatus = $( "#mediaStatus option:selected" ).attr('value');
        
        var url = '';
        if(mediaStatus === '1')
            url = 'media/album_media';
        else if(mediaStatus === '2')
            url = 'media/accepted_album_media';
        else
            url = 'media/deleted_album_media';
        $.ajax({
           url: url,
           type: 'post',
           success: function(res){
               $('#albumMediaContainer').html(res);
           },
           data: { albumId: value }
        });
    });
    
    $('.container').on('click', '.acceptAct', function(e){
        e.preventDefault();
        var self = this;
        if(confirm("Are you sure you want to change the owner of this Photo/Video to user india2?")){
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
    
    $('.container').on('click', '.acceptMAct', function(e){
        e.preventDefault();
        var self = this;
        if(confirm("Are you sure you want to change the owner of this Photo/Video to user Michel?")){
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
    
    $('#mediaStatus').change(function(){
        var value = $( "#ownerType option:selected" ).attr('value');
        var mediaStatus = $( "#mediaStatus option:selected" ).attr('value');
        switch(value){
            case "1":
            case "2":
                $('#albumsList').trigger('change');
                break;
            case "3":
                var url = '';
                if(mediaStatus === '1')
                    url = 'media/tubers_media';
                else if(mediaStatus === '2')
                    url = 'media/accepted_tubers_media';
                else
                    url = 'media/deleted_tubers_media';
                $.ajax({
                    url: url,
                    type: 'post',
                    success: function(res){
                        $("#albumsContainer").hide();
                        $("#otherContainer").show();
                        $("#otherContainer").html(res);
                        $("#albumMediaContainer").html('');
                    }
                });
                break;
            case "4":
                var url = '';
                if(mediaStatus === '1')
                    url = 'media/channels_media';
                else if(mediaStatus === '2')
                    url = 'media/accepted_channels_media';
                else
                    url = 'media/deleted_channels_media';
                $.ajax({
                    url: url,
                    type: 'post',
                    success: function(res){
                        $("#albumsContainer").hide();
                        $("#otherContainer").show();
                        $("#otherContainer").html(res);
                        $("#albumMediaContainer").html('');
                    }
                });
                break;
        }
    });
    
    $('#ownerType').change(function(){
        LoadData();
    });
});