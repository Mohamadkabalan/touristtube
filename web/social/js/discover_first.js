var currentMapSize = 0;

$(document).ready(function(){
    $(".select3").selectbox();
    autocompleteDiscover($('#inputArea'));
    $(document).on('click',".discover_button" ,function(){
        var str = $('#inputArea').val();
        var code = ""+ ($('#inputArea').attr('data-code')).toUpperCase();
        var state_code = ""+ ($('#inputArea').attr('data-state-code'));
        var iscountry = parseInt($('#inputArea').attr('data-iscountry'));
        if(iscountry == 0 && code!="" ){
            str += "/"+code;
            if(parseInt(state_code) != 0 ){
                str += "/"+state_code;
            }
        }
        if(str!=''){
            window.location.href = ReturnLink('/map/'+str);
        }
    });
});
function checkSubmitcatadd(e){
    if(e && e.keyCode == 13){
       $('.discover_button').click();
    }
}
function changeFilter(obj){
    resizeMap(parseInt($(obj).val()));
}
function resizeMap1() {
    $('html,body').css({overflow: 'visible'});
    $('.liveBar').removeClass('full');
    $('#mapLiveCam').removeClass('full');
    $('#mapLiveCam').removeClass('middle');
    $('.liveBar').detach().appendTo($('#MiddleMainLive'));
    $("#mapLiveCam").detach().appendTo($('#MiddleMainLive'));
    $(".footer_container_all").show();
    google.maps.event.trigger(map, 'resize');
    map.setCenter(new google.maps.LatLng(46.227638 , 2.213749));
}
function resizeMap2() {
    $('.liveBar').removeClass('full');
    $('#mapLiveCam').removeClass('full');
    $('#mapLiveCam').addClass('middle');
    $('html,body').css({overflow: 'visible'});
    $('.liveBar').detach().appendTo($('#MiddleMainLive'));
    $("#mapLiveCam").detach().appendTo($('#MiddleMainLive'));
    $(".footer_container_all").show();
    google.maps.event.trigger(map, 'resize');
    map.setCenter(new google.maps.LatLng(46.227638 , 2.213749));
}
function resizeMap3() {
    $(".footer_container_all").hide();
    $('#mapLiveCam').removeClass('middle');
    //$('.liveBar').addClass('full');
    $('#mapLiveCam').addClass('full');
    $('html,body').css({overflow: 'hidden'});
    $('#mapLiveCam').detach().prependTo($('body'));
    //$('.liveBar').detach().prependTo($('body'));
    google.maps.event.trigger(map, 'resize');
    map.setCenter(new google.maps.LatLng(46.227638 , 2.213749));
}
function resizeMap(val) {
    if (val == '1' && currentMapSize != '1') {
        resizeMap1();
        currentMapSize = 1;
    } else if (val == '2' && currentMapSize != '2') {
        resizeMap2();
        currentMapSize = 2;
    } else if (val == '3' && currentMapSize != '3') {
        resizeMap3();
        currentMapSize = 3;
    }
}