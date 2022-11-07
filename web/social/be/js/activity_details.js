var controller = 'user';
$(function(){
//    controller = GetController(window.location.pathname);

	var activity_startdate = $("#start_date").val();
	var activity_enddate = $("#end_date").val();
    $('#reportrange').daterangepicker(
        {
          ranges: {
             'Today': [moment(), moment()],
             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
             'This week': [moment().startOf('week').add(1, 'days'), moment()],
             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
             'This Month': [moment().startOf('month'), moment().endOf('month')],
             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(6, 'days'),
          endDate: moment()
        },
        function(start, end) {
			activity_startdate = $("#start_date").val(start);
			activity_enddate = $("#end_date").val(end);
            if(start.format('MMMM D, YYYY') === end.format('MMMM D, YYYY')){
                $('#reportrange span').html(start.format('MMMM D, YYYY'));
            }
            else{
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');
            hashId =$("#hash_id").val();//added to get the value of the hash id Anthony Malak 04-07-2015
            cmsvideoId =$("#cmsvideo_id").val();//code added to get the value of the cms videos id by sushma mishra on 26-08-2015
            $('#imgLoading').show();
           
			$.ajax({
				url: './' + controller + '/ajax_user_activities_details/'+$("#user_id").val()+'/'+$("#activity_type").val(),
                type: 'post',
                success: function (res)
                {
                    $('#imgLoading').hide();
                    $("#listContainer").html(res);
                },
                data: { start_date: startDate, end_date: endDate, hash_id: hashId, cmsvideo_id: cmsvideoId }//variable added to check on the cms videos id by sushma mishra on 26-08-2015
				//added to check on the hash id Anthony Malak 04-07-2015
            });
			
			
        }
    );
	
    $('#is_admin').click(function(){
       if($(this).prop('checked')){
           $(this).val("1");
       }
       else{
           $(this).val("0");
       }
    });
	
	
	$('#activity_type').on('change', function() {
	
            var sd = new Date($("#start_date").val());
            var day = sd.getDate();
            var month = sd.getMonth() + 1;
            var year = sd.getFullYear();

            var ed = new Date($("#end_date").val());
            var eday = ed.getDate();
            var emonth = ed.getMonth() + 1;
            var eyear = ed.getFullYear();
		
            startDate = year+'-'+month+'-'+day;
            endDate =  eyear+'-'+emonth+'-'+eday;
            hashId =$("#hash_id").val();//added to get the value of the hash id Anthony Malak 04-07-2015
            cmsvideoId =$("#cmsvideo_id").val();//added to get the value of the cms videos id by Sushma Mishra on 26-08-2015
                $.ajax({
                    url: './' + controller + '/ajax_user_activities_details/'+$("#user_id").val()+'/'+$(this).val(),
                    cache: false,
                    type: 'post',
                    success: function(res){
                            $('#imgLoading').hide();
                            $("#listContainer").html(res);
                    },
                    data: { start_date: startDate, end_date: endDate, hash_id: hashId, cmsvideo_id: cmsvideoId}//added to check cms videos id by Sushma Mishra on 26-08-2015
					//added to check on the hash id Anthony Malak 04-07-2015
                });   
	});
        
//      Added By Anthony Malak 04-07-2015 to fix the filter by the Hash id added by the user
//      <start>        
        $('#hash_id').on('change', function() {
	
		var sd = new Date($("#start_date").val());
		var day = sd.getDate();
                var month = sd.getMonth() + 1;
                var year = sd.getFullYear();
		
		var ed = new Date($("#end_date").val());
		var eday = ed.getDate();
                var emonth = ed.getMonth() + 1;
                var eyear = ed.getFullYear();
		
		startDate = year+'-'+month+'-'+day;
		endDate =  eyear+'-'+emonth+'-'+eday;
		cmsvideoId =$("#cmsvideo_id").val();// code added by sushma mishra on 26-08-2015 to get cms videos id
                hashId =$("#hash_id").val();
                
                $.ajax({
                    url: './' + controller + '/ajax_user_activities_details/'+$("#user_id").val()+'/'+$("#activity_type").val(),
                    cache: false,
                    type: 'post',
                    success: function(res){
						$('#imgLoading').hide();
						$("#listContainer").html(res);
                    },
                    data: { start_date: startDate, end_date: endDate, hash_id: hashId, cmsvideo_id: cmsvideoId }
                });   
	});
//      Added By Anthony Malak 04-07-2015 to fix the filter by the Hash id added by the user
//      <end>

//  Code added By Sushma Mishra on 26-08-2015 to add the filter by cms videos id starts from here  
	$('#cmsvideo_id').on('change', function() {
	var sd = new Date($("#start_date").val());
	var day = sd.getDate();
			var month = sd.getMonth() + 1;
			var year = sd.getFullYear();
	
	var ed = new Date($("#end_date").val());
	var eday = ed.getDate();
			var emonth = ed.getMonth() + 1;
			var eyear = ed.getFullYear();
	
	startDate = year+'-'+month+'-'+day;
	endDate =  eyear+'-'+emonth+'-'+eday;
	hashId =$("#hash_id").val();
	cmsvideoId =$("#cmsvideo_id").val();
	
		$.ajax({
			url: './' + controller + '/ajax_user_activities_details/'+$("#user_id").val()+'/'+$("#activity_type").val(),
			cache: false,
			type: 'post',
			success: function(res){
					$('#imgLoading').hide();
					$("#listContainer").html(res);
			},
			data: { start_date: startDate, end_date: endDate, hash_id: hashId, cmsvideo_id: cmsvideoId}
		});   
	});
//  Code added By Sushma Mishra on 26-08-2015 to add the filter by cms videos id ends here    
});


