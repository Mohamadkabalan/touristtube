var currentpage = 0;
var globfromsrch='';
var globtosrch='';
var TO_CAL;
var FROM_CAL;

$(document).ready(function () {
     
    if ($('#fromDate').length > 0) {
        FROM_CAL = Calendar.setup({
            inputField: "fromDate",
            noScroll: true,
            fixed: true,
            trigger: "fromContainer",
            align: "B",
            onSelect: function () {
                var date = Calendar.intToDate(this.selection.get());
                TO_CAL.args.min = date;
                TO_CAL.args.date = date;
                TO_CAL.redraw();
                TO_CAL.moveTo(date);
                $('#fromDateC').val(Calendar.printDate(date, "%Y-%m-%d"));
                addCalTo(this);
                this.hide();
//                $('#toDate').focus();
//                TO_CAL.showAt($('#toDate').offset().left, $('#toDate').offset().top + 45);
            },
            disabled      : function(date) {
                            var d = new Date();
                            d.setHours(12,30,0,0);
                            return (date > d);
            },
            dateFormat: "%d / %m / %Y"
        });
        TO_CAL = Calendar.setup({
            inputField: "toDate",
            noScroll: true,
            fixed: true,
            trigger: "toContainer",
            align: "B",
            onSelect: function () {
                var date = Calendar.intToDate(this.selection.get());
                $('#toDate').val(Calendar.printDate(date, "%d / %m / %Y"));
                $('#toDateC').val(Calendar.printDate(date, "%Y-%m-%d"));
                addCalTo(this);
                this.hide();
            },
            disabled      : function(date) {
                var d = new Date();
                d.setHours(12,30,0,0);
                return (date > d);
            },
            dateFormat: "%d / %m / %Y"
        });
        $('#toDate').click();
        TO_CAL.blur();
        $('.DynarchCalendar-topCont').hide();
    }
   $(document).on('click',".discover_a, .prev_pg, .next_pg, .first_pg, .last_pg" ,function(){
        var $this = $(this).closest('li');
        var data_page = parseInt($this.attr('data-page')); 
        currentpage = data_page - 1;
        if(currentpage < 0) currentpage = 0;
        getsearchEvents();
    });
    
   
    $(document).on('click', ".image-style", function () {       
        var fromDate = $('#fromDateC').val();
        var toDate = $('#toDateC').val();
        if (fromDate == '' && toDate=='') {
            if (fromDate == '') {
                TTAlert({
                    msg: 'invalid departure date',
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
            if (toDate == '') {
                TTAlert({
                    msg: 'invalid return date',
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
        }
        globfromsrch=fromDate;
        globtosrch=toDate;
        currentpage=0;
        $(".eventsContainer").html('');
        getsearchEvents();
    });
});
function addCalTo(cals) {
    if (new Date($('#fromDateC').val()) > new Date($('#toDateC').val())) {
        $('#toDateC').val($('#fromDateC').val());
        $('#toDate').val($('#fromDate').val());
    }
}
function getsearchEvents(){
    $.ajax({
        url: generateLangURL('/ajax/search_events'),
        data: { page:currentpage, globchannelid:channelGlobalID(), fromDate :globfromsrch, toDate :globtosrch },
        type: 'post',
        success: function(res){            
            $('.eventsContainer').html(res.channelEvents);
            $('.pagerWrapper').html(res.paging);
        }
    });
}