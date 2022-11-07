var pagenumber=1;
var priceOrder = '';
var starsOrder = '';
$(document).ready(function() {
    $('input:checkbox').removeAttr('checked');
    var pageType =$('input[name="pageType"]').val();
    pagenumber= $('#paging .page li.active').attr('data-page');
    $(document).on('click','.prev_pg',function(){
        var curpage =$('.active a').text();
        if(curpage == 1){
            pagenumber = 1;
        }else{
            pagenumber = Number(curpage) - Number(1);
        }
        if(pageType == 3){
            getPoiNearby();
        }else{
            getsearchrelatedcategory();
        }
    });
    $(document).on('click','.first_pg',function(){
        var curpage =$('.active a').text();
        pagenumber = 1;
        
        if(pageType == 3){
            getPoiNearby();
        }else{
            getsearchrelatedcategory();
        }
    });
    $(document).on('click','.next_pg',function(e){
        var curpage =$('.active a').text();
        var lastpage = $('input[name="totalpage"]').val() - 1;
        if(curpage == lastpage){
            pagenumber = lastpage;
        }else{
            pagenumber = Number(curpage) + Number(1);
        }
        
        if(pageType == 3){
            getPoiNearby();
        }else{
            getsearchrelatedcategory();
        }
    });
    $(document).on('click','.last_pg',function(){
        pagenumber = $('input[name="totalpage"]').val() - 1;
        
        if(pageType == 3){
            getPoiNearby();
        }else{
            getsearchrelatedcategory();
        }
    });  
    $(document).on('click',".poiPrefrences" ,function(){
        
        if(pageType == 3){
            getPoiNearby();
        }else{
            getsearchrelatedcategory();
        }
    });
    $(document).on('click',".pagerWrapper li[data-page] a" ,function(e){
        e.preventDefault();
        $('.pagerWrapper li').removeClass('active');
        $(this).parent().addClass('active');
        pagenumber= $(this).parent().attr('data-page');
        
        if(pageType == 3){
            getPoiNearby();
        }else{
            getsearchrelatedcategory();
        }
    });
});


function getsearchrelatedcategory(){
    $('.upload-overlay-loading-fix').show();
    var city =$('input[name="citys"]').val();
    var dest =$('input[name="dest"]').val();
    var poiPrefrences =  new Array();
    $('input[name="poiPrefrences"]:checked').each(function() {
        poiPrefrences.push(this.value);
    });
    $.ajax({
        url: generateLangURL('/ajax/search_prefix_Poi'),
        data: { city:city, poiPrefrences : poiPrefrences, page:pagenumber, dest:dest},
        type: 'post',
        success: function(res){
            $('#search_hotel_in').html(res.poi);
            $('.pagerWrapper .fr').html(res.paging);
            $('.srch_total_num').html(res.total);
            $('.upload-overlay-loading-fix').hide();
        }
    });
}

function getPoiNearby(){
    $('.upload-overlay-loading-fix').show();
    var city =$('input[name="citys"]').val();
    var dest =$('input[name="dest"]').val();
    var poiPrefrences =  new Array();
    $('input[name="poiPrefrences"]:checked').each(function() {
        poiPrefrences.push(this.value);
    });
    $.ajax({
        url: generateLangURL('/ajax/attractions_near_by'),
        data: { city:city, poiPrefrences : poiPrefrences, page:pagenumber, dest:dest},
        type: 'post',
        success: function(res){
            $('#search_hotel_in').html(res.poi);
            $('.pagerWrapper .fr').html(res.paging);
            $('.srch_total_num2').html(res.total);
            $('.upload-overlay-loading-fix').hide();
        }
    });
}
