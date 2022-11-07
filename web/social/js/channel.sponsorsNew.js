var currentpage = 0;
var globtxtsrch='';
var masonryoptions = { columnWidth: 196, itemSelector: '.post', isFitWidth: true, isAnimated: true };
$(document).ready(function(){
    $('#posts').masonry(masonryoptions);
    $(document).on('click','#removeSponsor2',function(){
        var $this=$(this);
        var $parent=$this.closest('div.post');
        removeSponsorChannels($parent);
        
    });
    setContainerWW();
    $(window).resize(function () {
        setContainerWW();
    });
    $("#searchbut").click(function(){
        var txtvalsrch=""+$("#srchtxt").val();
        globtxtsrch=txtvalsrch;
        currentpage=0;
        //$('.MediaList posts').html('');
        getsearchSponsors();
    });
    $(document).on('click',".discover_a, .prev_pg, .next_pg, .first_pg, .last_pg" ,function(){
        var $this = $(this).closest('li');
        var data_page = parseInt($this.attr('data-page')); 
        currentpage = data_page - 1;
        if(currentpage < 0) currentpage = 0;
        getsearchSponsors();
    });
    $(window).load(function () {
        $('#posts').masonry('destroy');
        $('#posts').masonry(masonryoptions);
    });
});
function checkSubmitSearch(e){
    if(e && e.keyCode == 13){
           $('#searchbut').click();
           
    }
 }

function setContainerWW(){
    var ww = Math.floor($(window).width()/196)*196 - 20;
    $('#posts').css('width',ww+'px');
    $('#posts').css('margirn-left','5px');
}
function removeSponsorChannels($parent){
    $.post(ReturnLink(('/ajax/ajax_removesponsors.php')), {globchannelid:channelGlobalID(),id:$parent.attr('data-sponsorid')},function(data){
        if(data!=false){
            $parent.remove();
            window.location.reload();
        }
    });
}
function getsearchSponsors(){
    $.ajax({
        url: generateLangURL('/ajax/search_sponsors'),
        data: { page:currentpage, globchannelid:channelGlobalID(), search_string :globtxtsrch },
        type: 'post',
        success: function(res){
            $('.postContainer').html(res.channelSponsor);
            $('.pagerWrapper').html(res.paging);
            $('#posts').masonry('destroy');
            $('#posts').masonry(masonryoptions);
        }
    });
}
