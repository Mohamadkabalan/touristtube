var controller = 'log';
$(function(){
//    controller = GetController(window.location.pathname);

    var activity_startdate = $("#start_date").val();
    var activity_enddate = $("#end_date").val();
    $('#reportrange').daterangepicker(
        {
          ranges: {
             'Today': [moment(), moment()],
             'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
             'This week': [moment().startOf('week').add('days', 1), moment()],
             'Last 7 Days': [moment().subtract('days', 6), moment()],
             'Last 30 Days': [moment().subtract('days', 29), moment()],
             'This Month': [moment().startOf('month'), moment().endOf('month')],
             'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
          },
          startDate: moment().subtract('days', 6),
          endDate: moment()
        },
        function(start, end) {
            $("#start_date").val(start);
            $("#end_date").val(end);
            if(start.format('MMMM D, YYYY') === end.format('MMMM D, YYYY')){
                $('#reportrange span').html(start.format('MMMM D, YYYY'));
            }
            else{
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');
            $('#imgLoading').show();
           
            $.ajax({
                url: './' + controller + '/ajax_searchLogs/',
                type: 'post',
                success: function (res)
                {
                    $('#imgLoading').hide();
                    $("#listContainer").html(res);
                },
                data: { start_date: startDate, end_date: endDate }
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
	
	
    $('#search_type').on('change', function() {

        var sd = new Date($("#start_date").val());
        var day = sd.getDate();
        var month = sd.getMonth() + 1;
        var year = sd.getFullYear();

        var ed = new Date($("#end_date").val());
        var eday = ed.getDate();
        var emonth = ed.getMonth() + 1;
        var eyear = ed.getFullYear();

        var startDate = year+'-'+month+'-'+day;
        var endDate =  eyear+'-'+emonth+'-'+eday;
        $.ajax({
            url: './' + controller + '/ajax_searchLogs/',
            cache: false,
            type: 'post',
            success: function(res){
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            },
            data: { start_date: startDate, end_date: endDate, search_type: $(this).val() }
        });   
    });
	
});



