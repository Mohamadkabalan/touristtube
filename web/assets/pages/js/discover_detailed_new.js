$( document ).ready(function(){
    var swiper = new Swiper('.swiper-container', {
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        slidesPerView: 'auto', 
        centeredSlides: false,
        preventClicksPropagation: true,
        spaceBetween: 10
    });
    $(document).on('click', "a.media_a", function (e) {
        e.preventDefault();
        document.location.href = $(this).attr('href');
    });
    $(".collapse-map").hide();
    //$('.swiper-container1').css('width','100%');
    //setContainerWW();
    //$(window).resize(function () {
    //  setContainerWW();
    //});
    $(document).on('click', '.connect-channel', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink(('/ajax/ajax_connect_channel.php')),
            data: {channel_id: id},
            type: 'post',
            success: function (data) {
                $('.upload-overlay-loading-fix').hide();
                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {

                }
                if (!jres) {
                    TTAlert({
                        msg: t('Your session timed out. Please login.'),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                if (jres.error != '') {
                    TTAlert({
                        msg: jres.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {                    
                    $this.remove();
                }
            }
        });
    });
    $(document).on('click',".expand-map" ,function(){
      if( $('#chatList').css('display') !="none" ) $('#openChatList').click();
      fullscreenmode = true
      $(".expand-map").hide();
      $(".collapse-map").show();
      $('html,body').css({ overflow: 'hidden' });
      $('.container-fluidMaps').css({'position': 'fixed', 'top' : '0px' , 'left' : '0px', width :'100%', height : '100%' , 'z-index': 1500 });
      google.maps.event.trigger(map, 'resize');
      map.setCenter(new google.maps.LatLng( $(".container-fluidMaps").attr('data-lat') , $(".container-fluidMaps").attr('data-log') ) );
    });
    $(document).on('click',".collapse-map" ,function(){
      fullscreenmode = false
      $(".collapse-map").hide();
      $(".expand-map").show();
      $('html,body').css({ overflow:'visible' });
      $('.container-fluidMaps').css({'position': '', top : '' , left : '', height : '' ,width: '','z-index': '' });
      google.maps.event.trigger(map, 'resize');
      map.setCenter(new google.maps.LatLng( $(".container-fluidMaps").attr('data-lat') , $(".container-fluidMaps").attr('data-log') ) );
    });
    $(document).on('change',".slectcateg_select" ,function(){
      var val= $(this).val();
      var search_checkbox_filter = new Array(val);
      for(var j = 0; j<marker_global_array.length; j++){
          var markerdata = marker_global_array[j];
          if( inArray( parseInt(markerdata[1]) , search_checkbox_filter) || inArray( 0 , search_checkbox_filter) ) markerdata[0].setMap(map);
          else markerdata[0].setMap(null);
      }
    });
    $(".slectcateg_select").val('');
});
function updateDiscoverMap(obj){
    var isduplicate=false;
    for(var j = 0; j<marker_global_array.length; j++){
       if( marker_global_array[j][1]==obj.attr('data-type') && marker_global_array[j][2]==obj.attr('data-id')) {
           isduplicate=true;
           break;
       }
    }
    if( isduplicate==false ){
        if( obj.attr('data-type') == SOCIAL_ENTITY_RESTAURANT){
            image = image1;
        }else if( obj.attr('data-type') == SOCIAL_ENTITY_HOTEL ){
            image = image2;
        }else if( obj.attr('data-type') == SOCIAL_ENTITY_LANDMARK ){
            image = image3;
        }else if( obj.attr('data-type') == SOCIAL_ENTITY_EVENTS ){
            image = image5;
        }else if( obj.attr('data-type') == SOCIAL_ENTITY_AIRPORT ){
            image = image6;
        }else{
            image = image0;
        }
        drawmarkers(map, image, obj.attr('data-title') , obj.attr('data-link') , obj.attr('data-img') , obj.attr('data-lat') , obj.attr('data-log') , obj.attr('data-stars') , obj.attr('data-type'), obj.attr('data-id'));
    }
}
function drawmarkers(map, image, title, link_uri, img_uri, la, lo,stars, entity_type,entityId) {
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(la, lo),
        map: map,
        icon: image,
        title: title
    });
    marker_global_array.push(Array(marker, entity_type,entityId))
    var base_url = '';
    var onMarkerClick = function() {
        ib.close();
        var marker = this;
        var content = '<div class="mtooltip"><div class="mtooltipin"><div class="clsgoo" onClick="ib.close();">X</div>';
        if (img_uri != '') {
            if (entity_type == SOCIAL_ENTITY_USER) {
                content += '<img class="mtooltipbig_user" src="' + img_uri + '" alt="" width="72"/>';
            } else if (entity_type == SOCIAL_ENTITY_EVENTS) {
                content += '<img class="mtooltipbig_event" src="' + img_uri + '" alt="" width="72"/>';
            } else {
                content += '<img class="mtooltipbig" src="' + img_uri + '" alt="" width="72"/>';
            }
            content += '<br /><a href="' + link_uri + '" target="_blank">';
        }
        content += '<div class="mtooltip_title">' + title + '</div>';
        if (img_uri != '') {
            content += '</a>';
        }
        if (parseInt(stars) > 0 && parseInt(stars) <= 5) {
            content += '<div class="anchor_star anchor_star' + stars + '"></div>';
        }
        content += '</div>';
        //content += '<div class="anchor_bk"></div>';
        content += '</div>';
        boxText.innerHTML = content;
        ib.open(map, marker);
    };
    google.maps.event.addListener(marker, 'click', onMarkerClick);
}
function setContainerWW(){
    var cnt = $('.swiper-container2 .swiper-slide').length;
    var ww1 = Math.floor($(window).width()- 20);
    var ww = cnt*294;
    if(ww > ww1) ww = ww1;
    $('.swiper-container2').css('width',ww+'px');
};
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}