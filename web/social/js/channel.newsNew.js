var currentpage = 0;
var globtxtsrch='';

$(document).ready(function(){
    
//
//function setContainerWW(){
//    var ww = Math.floor($(window).width()/196)*196 - 20;
//    $('#posts').css('width',ww+'px');
//    $('#posts').css('margirn-left','5px');
//}
//  $("#searchbut").click(function(){
//        var txtvalsrch=""+$("#srchtxt").val();
//        globtxtsrch=txtvalsrch;
//        currentpage=0;
//        //$('.MediaList posts').html('');
//        getsearchConnections();
//    });
    $(document).on('click',".discover_a, .prev_pg, .next_pg, .first_pg, .last_pg" ,function(){
        var $this = $(this).closest('li');
        var data_page = parseInt($this.attr('data-page')); 
        currentpage = data_page - 1;
        if(currentpage < 0) currentpage = 0;
        getsearchNews();
    });
//    $(document).on('click','#removeconnections2',function(){
//        var $this=$(this);
//        var $parent=$this.closest('div.post');
//        removeConnectionsChannels($parent);
//        
//    });
});
//function checkSubmitSearch(e){
//    if(e && e.keyCode == 13){
//           $('#searchbut').click();
//           
//    }
// }
// 
// function removeConnectionsChannels($parent){
//    $.post(ReturnLink('/ajax/ajax_removeconnections.php'), {globchannelid:channelGlobalID(),id:$parent.attr('data-connectionsid')},function(data){
//        if(data!=false){
//            $parent.remove();
//            window.location.reload();
//        }
//    });
//}
function getsearchNews(){
//    srch = $('input[name="channelsearchname"]').val();
    $.ajax({
        url: generateLangURL('/ajax/search_news'),
        data: { page:currentpage, globchannelid:channelGlobalID() },
        type: 'post',
        success: function(res){
            $('.nContainer').html(res.channelNews);
            $('.pagerWrapper').html(res.paging);
        }
    });
}