var pagenumber=1;
var priceOrder = '';
var starsOrder = '';
$(document).ready(function() {
    pagenumber= $('#paging .page li.active').attr('data-page');
    $(document).on('click',".restaurantPrefrences" ,function(){
        getsearchrelated();
    });
    $(document).on('click',"#paging .page li a" ,function(e){
        e.preventDefault();
        $('#paging .page li').removeClass('active');
        $(this).parent().addClass('active');
        pagenumber= $(this).parent().attr('data-page');
        getsearchrelated();
    });
    var pagedimensions = parent.window.returnIframeDimensions();
    $(".showOnMap").fancybox({
        width: pagedimensions[0],
        height: pagedimensions[1],
        closeBtn: true,
        autoSize: false,
        autoScale: true,
        transitionIn: 'elastic',
        transitionOut: 'fadeOut',
        type: 'iframe',
        padding: 0,
        margin: 0,
        scrolling: 'no',
        helpers : {
            overlay : {closeClick:true}
        }
    });
    $(document).on('click', ".addtobag", function() {
	var $this = $(this);
        if($this.hasClass('stationBagAct')) return;
        if($this.hasClass('inactive')){
            TTAlert({
                msg: t('you need to have a')+' <a class="black_link" href="'+'/register'+'">'+t('TT account')+'</a> '+t('in order to add items to your bag.'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        var $parent = $this.closest('.allHotelInfo');
        var id = $parent.attr('data-id');
        var data_city = ""+ ($parent.attr('data-city'));
        var data_country = ""+ ($parent.attr('data-country'));
        var entity_type = $parent.attr('data-type');
            TTCallAPI({
                what: '/social/user/bag/add',
                data:  {item_id : id , entity_type:entity_type,data_city:data_city,data_country:data_country},
                callback: function(resp){
                    if( resp.status === 'error' ){
                        TTAlert({
                            msg: resp.error,
                            type: 'alert',
                            btn1: t('ok'),
                            btn2: '',
                            btn2Callback: null
                        });
                        return;
                    }
//                    var cnt = parseInt($('.bag_count').attr('data-count'))+1;
//                    $('.bag_count').attr('data-count',cnt);
//                    if(cnt>99) cnt="99+";
//                    $('.bag_count').html(cnt);
//                    $this.removeClass('hotelsplus');
//                    $this.addClass('stationBagAct');
                }
            });
    });
});


function getsearchrelated(){
    var type =$('input[name="types"]').val();
    var city =$('input[name="citys"]').val();
    var dest =$('input[name="dest"]').val();
//    var hotelStars = new Array();
//    $('input[name="hotelStar"]:checked').each(function() {
//        hotelStars.push(this.value);
//    });
//    
    var restaurantPrefrences =  new Array();
    $('input[name="restaurantPrefrences"]:checked').each(function() {
        restaurantPrefrences.push(this.value);
    });
    
//    var hotelPropertyType =  new Array();
//    $('input[name="propertyType"]:checked').each(function() {
//        hotelPropertyType.push(this.value);
//    });
    $.ajax({
        url: '/ajax/search_Restaurant',
        data: { type:type, city:city, restaurantPrefrences : restaurantPrefrences, page:pagenumber, dest:dest},
        type: 'post',
        success: function(res){
            $('#search_hotel_in').html(res.restaurant);
            $('#paging').html(res.paging);
            $('#total_hotels').html(res.total);
            return ;
            //$('.upload-overlay-loading-fix').hide();
        }
    });
}