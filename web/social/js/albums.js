var currentpage = 0;
var masonryoptions = { columnWidth: 294, itemSelector: '.post', isFitWidth: true, isAnimated: true };
$(document).ready(function(){    
    $('#posts').masonry(masonryoptions);
    $(document).on('click',".discover_a, .prev_pg, .next_pg, .first_pg, .last_pg" ,function(){
        var $this = $(this).closest('li');
        var data_page = parseInt($this.attr('data-page')); 
        currentpage = data_page - 1;
        if(currentpage < 0) currentpage = 0;
        getsearchCHAlbums();
    });
    setContainerWW();
    $(window).resize(function () {
        setContainerWW();
    });
    $(window).load(function () {
        $('#posts').masonry('destroy');
        $('#posts').masonry(masonryoptions);
    });
});
function setContainerWW(){
    var ww = Math.floor($(window).width()/294)*294 - 20;
    $('#posts').css('width',ww+'px');
    $('#posts').css('margirn-left','5px');
}
function getsearchCHAlbums(){
    $.ajax({
        url: generateLangURL('/ajax/search_albums_ch'),
        data: { page:currentpage, globchannelid:channelGlobalID() },
        type: 'post',
        success: function(res){
            $('#posts').html(res.channelAlbums);
            $('.pagerWrapper').html(res.paging);
            $('#posts').masonry('destroy');
            $('#posts').masonry(masonryoptions);
        }
    });
}