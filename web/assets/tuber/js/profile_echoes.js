var currentpage = 0;
var fr_txt="";
var to_txt="";
var jscrollpane_apiECH;
var TO_CAL;
var myStyles = [
    {
        featureType: "poi",
        elementType: "labels",
        stylers: [
            {visibility: "off"}
        ]
    }
];
function initscrollPane() {
    $(".echoesList").jScrollPane({
        autoReinitialise: true
    });
    jscrollpane_apiECH = $(".echoesList").data('jsp');
}
$(document).ready(function () {
    initSocialActions();
    $('#loadmore').css('width', ($('#loadmore span').width() + 3) + "px");
    
    initscrollPane();
    InitCalendar();
    $(document).on('click', "#resetpagebut", function () {
        document.location.reload();
    });
    $(document).on('click', ".anEcho", function () {
        if ($(this).length > 0) {
            var $this = $(this);
            var pos = $this.offset().top - $this.parent().offset().top;
            var dataval = $this.attr('data-val');

            $(".social_data_all").attr('data-id', dataval);
//            initLikes($(".social_data_all"));
//            initComments($(".social_data_all"));
//            initReechoes($(".social_data_all"));
            $('.echoe_report_top_button').attr('data-id', dataval);
            jscrollpane_apiECH.scrollToY(pos, true);
            $(".anEcho").removeClass('selected');
            $this.addClass('selected');
        }
    });
    $(document).on('click', ".removeEcho", function () {
        var current = $(this);
        var currentEcho = current.closest(".anEcho");
        $('.upload-overlay-loading-fix').show();
        var theId = currentEcho.attr('data-val');
        $.ajax({
            url: ReturnLink('/ajax/ajax_remove_echo.php'),
            data: {id: parseInt(theId)},
            type: 'post',
            success: function (data) {
                if (data.trim() == '1') {                    
                    if (currentEcho.siblings.length > 0) {
                        if (currentEcho.prev().length > 0) {
                            currentEcho.prev().click();
                        }
                        else if (currentEcho.next().length > 0) {
                            currentEcho.next().click();
                        }
                    } else {
                        $('#commentDiv[data-id="' + theId + '"]').hide();
                    }
                    currentEcho.remove();
                    getEchoesDataRelated(1,0);
                }else{
                    $('.upload-overlay-loading-fix').hide();
                }
            }
        });
    });
    $(document).on('click', "#loadmore", function () {
        currentpage++;
        getEchoesDataRelated(0,0);        
    });
    $("#searchCalendarbut").click(function(){
        if($('#fromtxt').html()!='' || $('#totxt').html()!=''){
            fr_txt=""+$('#fromtxt').attr('data-cal');
            to_txt=""+$('#totxt').attr('data-cal');			
            currentpage=0;
            $('.echoesContent').html('');
            getEchoesDataRelated(0,1);
        }
    });
    $('#likes').parent().click();
    $('.echoesContent .anEcho').first().click();
    refreshFlashData();
});
function getEchoesDataRelated(oneobject,firstclick){    
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/profile_flash_list.php'), {user_id:userGlobalID(),page:currentpage,frtxt:fr_txt,totxt:to_txt,oneobject:oneobject},function(data){
            $('.echoesContent').append(data);
            refreshFlashData();
            if(oneobject==0 && firstclick==0){
                setTimeout(function () {
                    jscrollpane_apiECH.scrollToBottom(true);
                }, 500);
            }
            if(firstclick==1){
                $('.echoesContent .anEcho').first().click();
            }
            $('.upload-overlay-loading-fix').hide();
    });
}
function refreshFlashData() {
    $(".echoImg_link").fancybox({
            helpers : {
                overlay : {closeClick:true}
            }
        });
    $('.echoe_googlemap').each(function (index, element) {
        var $googlemap_location = $(this).find('.googlemap_location');
        var $id_location = $googlemap_location.attr('id');
        var image = new google.maps.MarkerImage(ReturnLink("/media/images/map_marker.png"));
        var mapOptions = {
            center: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
            zoom: 8,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: myStyles
        };

        var map = new google.maps.Map(document.getElementById($id_location), mapOptions);

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng($googlemap_location.attr('data-lat'), $googlemap_location.attr('data-lng')),
            map: map,
            icon: image,
            title: ''
        });
    });
}
function InitCalendar(){
    Calendar.setup({
            inputField : "fromtxt",
            noScroll  	 : true,
            fixed: true,
            trigger    : "frombutcontainer",
            align:"B",
            onSelect   : function() {
                    var date = Calendar.intToDate(this.selection.get());
                TO_CAL.args.min = date;
                TO_CAL.redraw();
                    $('#fromtxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));

                    addCalToEvent(this);
                    this.hide();
            },
            dateFormat : "%d / %m / %Y"
    });
    TO_CAL=Calendar.setup({
            inputField : "totxt",
            noScroll  	 : true,
            fixed: true,
            trigger    : "tobutcontainer",
            align:"B",
            onSelect   : function() {
                    var date = Calendar.intToDate(this.selection.get());
                    $('#totxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));

                    addCalToEvent(this);
                    this.hide();
            },
            dateFormat : "%d / %m / %Y"
    });
}
function addCalToEvent(cals){
    if(new Date($('#fromtxt').attr('data-cal'))>new Date($('#totxt').attr('data-cal'))){
        $('#totxt').attr('data-cal','');
        $('#totxt').html('');
    }
}