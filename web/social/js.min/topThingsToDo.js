var currentcategory = "";
var currentPage=0;
$(document).ready(function(){
    autocompleteDiscover($('#citySearch'));
    $(document).on('click',".search-button" ,function(){
        var str = $('#citySearch').val();
        if(str=='') return;
        var code = ""+ ($('#citySearch').attr('data-code')).toUpperCase();
        var iscountry = parseInt($('#citySearch').attr('data-iscountry'));
        var cid = parseInt($('#citySearch').attr('data-id'));
        if( cid!=0 && cid!='' ){
            str = 'C/'+cid;            
        }
        if(iscountry == 0 && code!="" ){
            str += "/CO/"+code;
        }else if(iscountry == 1 && code!="" ){
            str = "CO/"+code;
        }
        if(str!=''){
            window.location.href = ReturnLink('/things-to-do-search/'+str);
        }
    });   
    $(document).on('click',".bagcontainer_a0" ,function(e){
        event.preventDefault();
        TTAlert({
            msg: $.i18n._('you have to sign in, in order to access a tuber page'),
            type: 'action',
            btn1: t('cancel'),
            btn2: 'register',
            btn2Callback: function (data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
    $(document).on('click', ".hotelsplus", function() {
	var $this = $(this);
        if($this.hasClass('stationBagAct')) return;
        if($this.hasClass('inactive')){
            TTAlert({
                msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to add items to your bag.'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        var $parent = $this.closest('.topThingsToDo_all');
        var id = $parent.attr('data-id');
        var data_city = ""+ ($parent.attr('data-city'));
        var data_country = ""+ ($parent.attr('data-country'));
        var entity_type = $parent.attr('data-type');
            TTCallAPI({
                what: '/user/bag/add',
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
                    var cnt = parseInt($('.bag_count').attr('data-count'))+1;
                    $('.bag_count').attr('data-count',cnt);
                    if(cnt>99) cnt="99+";
                    $('.bag_count').html(cnt);
                    $this.removeClass('hotelsplus');
                    $this.addClass('stationBagAct');
                }
            });
    });
    $(document).on('click', ".thingsToDoStepplus", function() {
	var $this = $(this);
        if($this.hasClass('stationBag')) return;
        if($this.hasClass('inactive')){
            TTAlert({
                msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to add items to your bag.'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        var $parent = $this.closest('.thingsToDoStep_poi_info');
        var id = $parent.attr('data-id');
        var data_city = ""+ ($('.thingsToDohead').attr('data-city'));
        var data_country = ""+ ($('.thingsToDohead').attr('data-country'));
        var entity_type = $parent.attr('data-type');
        TTCallAPI({
            what: '/user/bag/add',
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
                var cnt = parseInt($('.bag_count').attr('data-count'))+1;
                $('.bag_count').attr('data-count',cnt);
                if(cnt>99) cnt="99+";
                $('.bag_count').html(cnt);
                $this.removeClass('thingsToDoStepplus');
                $this.addClass('stationBag');
            }
        });
    });
    $("#loadmore").click(function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        currentPage++;
        displaydatarelated();
    });
    $(document).on('click', ".thingsToDoStep_category", function(){
        if($(this).hasClass('active')) return;
        currentPage=0;
        $('.thingsToDoStep_category').removeClass('active');
        $(this).addClass('active');
        currentcategory = $(this).attr('data-label');
        $('.thingsToDoStep_right_part').html('');
        displaydatarelated();
    });
});
function checkSubmitcatadd(e){
    if(e && e.keyCode == 13){
       $('.discover_button').click();
    }
}
function displaydatarelated() {
    $('.upload-overlay-loading-fix-file').show();
    var data_city = ""+ ($('.thingsToDohead').attr('data-city'));
    var data_country = ""+ ($('.thingsToDohead').attr('data-country'));
    $.post(ReturnLink('/ajax/ajax_getthingstodosearch.php'), {data_city:data_city,data_country:data_country,page: currentPage,currentcategory:currentcategory}, function(data) {
        var jres = null;
        try {
            jres = $.parseJSON(data);
        } catch (Ex) {
        }
        if (!jres) {
            return;
        }
        $('.thingsToDoStep_right_part').append(jres.data);
        if(jres.tohideLoad==1){
            $("#loadmore").addClass('displaynone');
        }else{
            $("#loadmore").removeClass('displaynone');
        }
        $('.upload-overlay-loading-fix-file').hide();
    });
}