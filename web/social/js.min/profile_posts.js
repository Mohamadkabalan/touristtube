var currentpage = 0;
var fr_txt="";
var to_txt="";
var jscrollpane_apiPST;
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
    jscrollpane_apiPST = $(".echoesList").data('jsp');
}
function initEdit(){
    var $This = $('#edit_media_buts');
    var canEdit = $(".social_data_all").attr('data-edit');
    if(canEdit === '1'){
        $This.show();
        var vid = $(".social_data_all").attr('data-id');
        var isMedia = $(".social_data_all").attr('data-ismedia');
        var width = isMedia === '0' ? '269' : '883';
        $This.attr("href", ReturnLink('parts/user-viewpost.php?id=' + vid + '&channelid=0&is_post=1'));

        $This.fancybox({
            "padding": 0,
            "margin": 0,
            "width": width,
            "height": '604',
            "transitionIn": "none",
            "transitionOut": "none",
            "autoSize": false,
            "scrolling": 'no',
            "type": "iframe"
        });
    }
    else{
        $This.hide();
    }
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
            var ismedia = $this.attr('data-ismedia');
            var canedit = $this.attr('data-edit');
            $(".social_data_all").attr('data-id', dataval);
            $(".social_data_all").attr('data-ismedia', ismedia);
            $(".social_data_all").attr('data-edit', canedit);
            initLikes($(".social_data_all"));
            initComments($(".social_data_all"));
            initShares($(".social_data_all"));
            jscrollpane_apiPST.scrollToY(pos, true);
            $(".anEcho").removeClass('selected');
            $this.addClass('selected');
            initEdit();
        }
    });
    $(document).on('click', ".removeEcho", function () {
        var current = $(this);
        var currentEcho = current.closest(".anEcho");
        $('.upload-overlay-loading-fix').show();
        var theId = currentEcho.attr('data-val');
         TTCallAPI({
            what: '/social/post/delete',
            data: {id: theId},
            callback: function (resp) {
                if (resp.status === 'error') {
                    TTAlert({
                        msg: resp.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    $('.upload-overlay-loading-fix').hide();
                    return;
                }
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
                getPostsDataRelated(1,0);
            }
        });
    });
    $(document).on('click', "#loadmore", function () {
        currentpage++;
        getPostsDataRelated(0,0);        
    });
    $("#searchCalendarbut").click(function(){
        if($('#fromtxt').html()!='' || $('#totxt').html()!=''){
            fr_txt=""+$('#fromtxt').attr('data-cal');
            to_txt=""+$('#totxt').attr('data-cal');			
            currentpage=0;
            $('.echoesContent').html('');
            getPostsDataRelated(0,1);
        }
    });
    $('#likes').parent().click();
    $('.echoesContent .anEcho').first().click();
    refreshPostData();
});
function getPostsDataRelated(oneobject,firstclick){    
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/profile_post_list.php'), {user_id:userGlobalID(),page:currentpage,frtxt:fr_txt,totxt:to_txt,oneobject:oneobject},function(data){
            $('.echoesContent').append(data);
            refreshPostData();
            if(oneobject==0 && firstclick==0){
                setTimeout(function () {
                    jscrollpane_apiPST.scrollToBottom(true);
                }, 500);
            }
            if(firstclick==1){
                $('.echoesContent .anEcho').first().click();
            }
            $('.upload-overlay-loading-fix').hide();
    });
}
function refreshPostData() {
    $('.postImglink0').fancybox({
        helpers : {
            overlay : {closeClick:true}
        }
    });
    $(".postImglink1").fancybox({
        transitionIn: 'none',
        transitionOut: 'none',		
        autoScale: false,
        autoDimensions: false,		
        width: 694,
        minWidth: 694,
        maxWidth: 694,
        height: 442,
        minHeight: 442,
        maxHeight: 442,
        padding	:0,
        margin	:0,
        type: 'iframe',
        scrolling: 'no',
        helpers : {
            overlay : {closeClick:true}
        }
    });
    $('.echoe_googlemap').each(function (index, element) {
        var $googlemap_location = $(this).find('.googlemap_location');
        var $id_location = $googlemap_location.attr('id');
        var image = new google.maps.MarkerImage(ReturnLink("/images/map_marker.png"));
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