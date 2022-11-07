function addValue(obj, val) {
    if ($(obj).attr('value') == '')
        $(obj).attr('value', val);
}
function removeValue(obj, value) {
    if ($(obj).attr('value') == value)
        $(obj).attr('value', '');
}
var markersArrayData = [];
var map;
var currentMapSize = 1;
var mapOptions;
var ib;
var current_marker;
var myStyles = [
    {
        featureType: "poi",
        elementType: "labels",
        stylers: [
            {visibility: "off"}
        ]
    }
];
var image = new google.maps.MarkerImage(ReturnLink("/images/map_marker.png"));
function drawmarkers(markersArrayData, image, boxText, k) {
    if (markersArrayData instanceof Array) {
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(markersArrayData[4], markersArrayData[5]),
            map: markersArrayData[0],
            icon: image,
            title: markersArrayData[1]
        });

        var marker_new = marker;
        current_marker = marker_new;

        //Setting the onclick marker function
        var base_url = '';
        var onMarkerClick = function() {
            ib.close();
            var marker = this;
            //map.setCenter(new google.maps.LatLng(la, lo));
            //var latLng = marker.getPosition();
            var content = '<div class="mtooltip"><div class="mtooltipin"><div class="clsgoo" onClick="ib.close();">X</div>';
            if (markersArrayData[3] != '') {
                content += '<a href="' + markersArrayData[2] + '" target="_blank"><img class="mtooltipbig" src="' + markersArrayData[3] + '" alt="" width="72" height="39" /></a>';
            }
            //content += '<div class="mtooltip_title">'+title+'</div><br />'+la+'<br />'+lo+'<br />';
            content += '<a href="' + markersArrayData[2] + '" target="_blank"><div class="mtooltip_title">' + markersArrayData[1] + '</div></a>';

            content += '</div><div class="anchor_bk"></div></div>';
            //ib.setContent(content);
            boxText.innerHTML = content;
            ib.open(map, marker);
        };
        google.maps.event.addListener(current_marker, 'click', onMarkerClick);
    }
}
$.fn.enterKey = function(fnc) {
    return this.each(function() {
        $(this).keypress(function(ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                fnc.call(this, ev);
            }
        })
    })
}
function searchLiveCam(value, value2) {
    $.get(ReturnLink('/ajax/ajax_livecam_search.php'), {ss: value, ss2: value2}, function(data) {
        $("#AllCamList").html(data);
    });
    if (currentMapSize == '3') {
        resizeMap1();
    }
    $(".liveCamSearch").val('');
}
function resizeMap1() {
    $('html,body').css({overflow: 'visible'});
    $('.liveBar').removeClass('full');
    $('#mapLiveCam').removeClass('full');
    $('#mapLiveCam').removeClass('middle');
    $('.liveBar').detach().prependTo($('#MiddleMainLive'));
    $("#mapLiveCam").detach().prependTo($('#MiddleMainLive'));
    $(".footer_container_all").show();
    google.maps.event.trigger(map, 'resize');
    map.setCenter(new google.maps.LatLng(10, 10));
}
function resizeMap2() {
    $('.liveBar').removeClass('full');
    $('#mapLiveCam').removeClass('full');
    $('#mapLiveCam').addClass('middle');
    $('html,body').css({overflow: 'visible'});
    $('.liveBar').detach().prependTo($('#MiddleMainLive'));
    $("#mapLiveCam").detach().prependTo($('#MiddleMainLive'));
    $(".footer_container_all").show();
    google.maps.event.trigger(map, 'resize');
    map.setCenter(new google.maps.LatLng(10, 10));
}
function resizeMap3() {
    $(".footer_container_all").hide();
    $('#mapLiveCam').removeClass('middle');
    $('.liveBar').addClass('full');
    $('#mapLiveCam').addClass('full');
    $('html,body').css({overflow: 'hidden'});
    $('#mapLiveCam').detach().prependTo($('body'));
    $('.liveBar').detach().prependTo($('body'));
    google.maps.event.trigger(map, 'resize');
    map.setCenter(new google.maps.LatLng(10, 10));
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
$("#input").enterKey(function() {
    alert('Enter!');
})
$(document).ready(function() {
    $('.liveBarIn').on('mouseleave', function () {
        $('.liveMapOptionsCont').hide();
    });
    if($('.liveCamSearch').length>0) autocompleteLiveCam($('.liveCamSearch'));
    // liveMapSearchIcon onclick or enter press
    $(document).on('click', ".liveMapSearchIcon", function() {
        var value = getObjectData($(".liveCamSearch"));
        if(value!='') document.location.href = ReturnLink('/live/ss/'+value);
    });
    $(".liveCamSearch").enterKey(function() {
        var value = $(".liveCamSearch").val();
        document.location.href = ReturnLink('/live/ss/'+value);
//        searchLiveCam(value, '');
    });
    //   / liveMapSearchIcon onclick or enter press

    // aCloudItem onclick
    $(document).on('click', ".aCloudItem", function() {
        var value = $(this).html();
//        searchLiveCam('', value);
document.location.href = ReturnLink('/live/ss2/'+value);
    });
    //   / aCloudItem onclick
    //
    // liveMapOptions onclick
    $(document).on('click', ".liveMapOptions", function() {
        $(".liveMapOptionsCont").toggle();
    });
    //   / liveMapOptions onclick

    // liveMapOpt onclick
    $(document).on('click', ".liveMapOpt", function() {
        var $this = $(this);
        var mapSizeOpt = $this.attr('data-val');
        var mapSizeOptTxt = $this.html();
        $(".liveMapOptions span").html(mapSizeOptTxt);
        $(".liveMapOptions").attr('data-val', mapSizeOpt);
        resizeMap(mapSizeOpt);
    });
    //   / liveMapOpt onclick
    mapOptions = {
        center: new google.maps.LatLng(10, 10),
        zoom: 3,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: myStyles
    };

    map = new google.maps.Map(document.getElementById("mapLiveCam"), mapOptions);

    $('#loadmore').css('width', ($('#loadmore span').width() + 3) + "px");
    var LoadedPages = 0;
    $(document).on('click', "#loadmore", function() {
        $('.upload-overlay-loading-fix').show();
        LoadedPages++;
        $.ajax({
            url: ReturnLink($('#loadmore').attr('rel')),//ReturnLink('/ajax/ajax_livecam_search.php?page=' + LoadedPages),
            success: function(data) {
                $('#AllCamList').append(data);
                $('.upload-overlay-loading-fix').hide();
            }
        });
    });
});