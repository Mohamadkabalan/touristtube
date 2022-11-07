var controller = 'ml';

$(function(){
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var cityName = $('#txtCity').val().trim();
        var countryCode = $('#txtCountryCode').val().trim();
        var name = $('#txtName').val().trim();
		//code added to get the value of video id by sushma mishra on 28-08-2015 starts from here
		var videoId = $('#txtVideoId').val().trim();
		//code added to get the value of video id by sushma mishra on 28-08-2015 ends here
        var hashId = $('#txtHashId').val().trim();
        var url = './' + $(this).attr('href');
        $('#imgLoading').show();
        $.ajax({
            url: url,
            type: 'post',
            success  : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
			//code to pass the videoid value by sushma mishra on 28-08-2015 starts from here
            data: { ci: cityName, cc: countryCode, m: name,  vid: videoId, hid: hashId}
			//code to pass the videoid value by sushma mishra on 28-08-2015 ends here
        });
    });
    //code added to call the function on videoid keydown by sushma mishra on 28-08-2015 starts from here
    $('#txtCity, #txtName, #txtCountryCode, #txtVideoId, #txtHashId').keydown(function(e){
	 //code added to call the function on videoid keydown by sushma mishra on 28-08-2015 ends here
        if(e.which === 13){
            $('#btnFilter').trigger('click');
        }
    });
    
    $('#btnReset').click(function(){
        $('#txtCity').val('');
        $('#txtName').val('');
        $('#txtCountryCode').val('');
		//code added to reset the value of videoid by sushma mishra on 28-08-2015 starts from here
		$('#txtVideoId').val('');
		 //code added to reset the value of videoid by sushma mishra on 28-08-2015 ends here
        $('#txtHashId').val('');
        $('#btnFilter').trigger('click');
    });
    
    $('#btnFilter').click(function(e){
        var cityName = $('#txtCity').val().trim();
        var name = $('#txtName').val().trim();
        var countryCode = $('#txtCountryCode').val().trim();
		//code added to get the value of video id by sushma mishra on 28-08-2015 starts from here
		var videoId = $('#txtVideoId').val().trim();
		//code added to get the value of video id by sushma mishra on 28-08-2015 ends here
        var hashId = $('#txtHashId').val().trim();
        $('#imgLoading').show();
        $.ajax({
            url: './' + controller + '/ajax_list',
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { ci: cityName, m: name, cc: countryCode,  vid: videoId, hid: hashId }
        });
    });
});