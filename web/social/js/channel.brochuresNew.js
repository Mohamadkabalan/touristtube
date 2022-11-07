var currentpage = 0;
var globtxtsrch='';

$(document).ready(function(){
    $(document).on('click',".discover_a, .prev_pg, .next_pg, .first_pg, .last_pg" ,function(){
        var $this = $(this).closest('li');
        var data_page = parseInt($this.attr('data-page')); 
        currentpage = data_page - 1;
        if(currentpage < 0) currentpage = 0;
        getsearchBrochures();
    });
});
function getsearchBrochures(){
    $.ajax({
        url: generateLangURL('/ajax/search_brochures'),
        data: { page:currentpage, globchannelid:channelGlobalID() },
        type: 'post',
        success: function(res){
            $('.bContainer').html(res.channelBrochures);
            $('.pagerWrapper').html(res.paging);
        }
    });
}