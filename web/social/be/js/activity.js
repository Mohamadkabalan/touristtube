var controller = 'user';
$(function(){
//    controller = GetController(window.location.pathname);
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
                url: './' + controller + '/ajax_activities',
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
});