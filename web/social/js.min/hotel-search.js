var hotel_page = 0;
var TO_CAL;
var destination;
var country_code;
var state_code;
var city_code;
var hotel_code;
var room_val;
var from;
var to;
var hotelClass;
var priceRange;
var accomType;
var hotelPref;
var tuberEvals;

var orderBy = 'id';
var direction = 'DESC';
function returnLink(variab){
    return variab;
}//no sense just for test
function checkboxVal(className){
    var arrayToReturn = new Array();
    $('.'+className).each(function(){
        if($(this).hasClass("checkboxActive")){
            var $value = $(this).attr('data-value');
            arrayToReturn.push($value);
        }
   });
   return arrayToReturn;
}
function setGuests(){
    var $var = '';
    if($("#guests_sel").val()===''){
        $var = $("#guests_sel_rooms").val()+'-'+$("#guests_sel_adults").val()+'-'+$("#guests_sel_children").val();
    }else{
        $var = $("#guests_sel").val();
    }
    $("#guests").val($var);
}
/*
 * get the value from hidden field and set them in different select box
* used on page load after we set the value of the hidden field using the querystring room: (room-adults-children)
*/
function setGuestsFields(){
    var theValue = $("#guests").val();
    if(theValue == '1-1-0' || theValue == '1-2-0'){
        $(".guestSelectGroup").hide();
        $("#guests_sel").val(theValue);
    }else{
        $(".guestSelectGroup").show();
        $("#guests_sel").val('');
        var res = theValue.split('-');
        $("#guests_sel_rooms").val(res[0]);
        $("#guests_sel_adults").val(res[1]);
        $("#guests_sel_children").val(res[2]);
    }
}
function navigatePhotos(hotel, from, to){
    if(to !=''){
        $.post(ReturnLink('/ajax/ajax_hotel_search_media.php'),
            { hotel:hotel, from:from, to:to },function(data){
            var ret = null;
            try{
                ret = $.parseJSON(data);
            }catch(Ex){
                //$('.upload-overlay-loading-fix').hide();
                return ;
            }
            if(ret.error){
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }else{
                $(".smallImgListCont[data-value='" + hotel + "']").html(ret.media);
                $(".smallImgPrev[data-value='" + hotel + "']").attr('data-page',to);
                $(".smallImgPrev[data-value='" + hotel + "']").attr('data-to',ret.mediaPrev);
                $(".smallImgNext[data-value='" + hotel + "']").attr('data-page',to);
                $(".smallImgNext[data-value='" + hotel + "']").attr('data-to',ret.mediaNext);
            }
            //$('.upload-overlay-loading-fix').hide();
       });
    }
}
function redirectToThotel(id,from,to,room,search,country,state,city){
    var link = ReturnLink('/thotel/id/' + parseInt(id));
    if(from != ''){
        link += '/from/'+from;
    }
    if(to != ''){
        link += '/to/'+to;
    }
    if(room != ''){
        link += '/room/'+room;
    }
    if(search != ''){
        link += '/s/'+search;
    }
    if(country != ''){
        link += '/CO/'+country;
    }
    if(state != ''){
        link += '/ST/'+state;
    }
    if(city != ''){
        link += '/C/'+city;
    }
    document.location.href = link;
}
function filterSearch(){
    destination = $(".destinationInput").val();
    country_code = $(".destinationInput").attr('data-code');
    state_code = $(".destinationInput").attr('data-state-code');
    city_code = $(".destinationInput").attr('data-city');
    room_val = $("#guests").val();
    if(typeof $(".destinationInput").attr('data-hotel') !== 'undefined' && $(".destinationInput").attr('data-hotel') != 'false')
        hotel_code = $(".destinationInput").attr('data-hotel');
    else
        hotel_code = '';
    from = $("#fromtxt").attr("data-cal");
    to = $("#totxt").attr("data-cal");
    hotelClass = checkboxVal('hotelStarCheckbox');
    priceRange = checkboxVal('budgetCheckbox');
    accomType = checkboxVal('accomodationCheckbox');
    hotelPref = checkboxVal('hotelPrefCheckbox');
    tuberEvals = checkboxVal('tuberCheckbox');
    if(hotel_code != '' && hotel_code != 'undefined'){
        redirectToThotel(hotel_code,from,to,room_val,destination,country_code,state_code,city_code);
    }else{
        initHotelSearch(false);
    }
}
function initHotelSearch(update){
    if(!update){
        update = 0;
        hotel_page = 0;
        $(".allHotelsContainer").html('');
    // Load more case.
    } else {
        hotel_page++;
    }
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_hotel_search.php'), { orderBy:orderBy,direction:direction,room_val:room_val,hotel_page:hotel_page,destination:destination,country_code:country_code,city_code:city_code,state_code:state_code, from:from, to:to,hotelClass:hotelClass , priceRange:priceRange, accomType:accomType, hotelPref:hotelPref, tuberEvals:tuberEvals },function(data){
        var ret = null;
        try{
            ret = $.parseJSON(data);
        }catch(Ex){
            $('.upload-overlay-loading-fix').hide();
            return ;
        }
        if(ret.error){
            TTAlert({
                msg: ret.error,
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
        }else{
            $(".allHotelsContainer").append(ret.hotels);
            $(".hotelSearchHeadRightDesc").html(ret.hotelSearchHeadRightDesc);
            $(".globDescContainerLeft").html(ret.destinationDesc);
            $(".globDescContainerRightPrecent").html(ret.percent);
            if(ret.hotelsCount > (9*(hotel_page+1)) ){
                $(".buttonmorebigcontainer").show();
            }else{
                $(".buttonmorebigcontainer").hide();
            }
            $(".sortingOpt").removeClass('sortingActive');
            $(".sortElt").removeClass('sortingActive');
            $(".sortingOpt[data-value='" + orderBy + "']").addClass('sortingActive');
            $(".sortElt[data-value='" + orderBy + "'][data-direction='" + direction + "']").addClass('sortingActive');
            var $googlemap_location = $('.leftBlockMap');
            var $id_location = $googlemap_location.attr('id');
            var image = new google.maps.MarkerImage( ReturnLink('/images/pin_hot.png'));
            var myStyles =[
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [
                        { visibility: "off" }
                    ]
                }
            ];
            var mapOptions = {
              center: new google.maps.LatLng( ret.latitude, ret.longitude ),
              zoom: 12,
              disableDefaultUI: true,
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              styles: myStyles
            };

            var map = new google.maps.Map(document.getElementById($id_location),mapOptions);

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng( ret.latitude, ret.longitude ),
                map: map,
                icon: image,
                title: destination
            });
        }
        $('.upload-overlay-loading-fix').hide();
   });
}
function setCurrency(currencyGroup,hotelBudgetOption1,hotelBudgetOption2,hotelBudgetOption3,currName){
    $.fancybox.close();
    $(".budgetCheckboxGroup").html(currencyGroup);
    $(".changeCurrencyBtn").html(currName);
    filterSearch();
}
$(document).ready(function(){
    // guests_sel on change
    $(document).on('change', "#guests_sel", function() {
        if ($(this).val() == '') {
            $(".guestSelectGroup").show();
        } else {
            $(".guestSelectGroup").hide();
        }
        setGuests();
    });
    //   / guests_sel on change
    //
    // guests_sel_rooms,guests_sel_adults,guests_sel_children on change
    $(document).on('change', "#guests_sel_rooms,#guests_sel_adults,#guests_sel_children", function() {
        setGuests();
    });
    //   / guests_sel_rooms,guests_sel_adults,guests_sel_children on change
    setGuestsFields();
    autocompleteHotels($('#destinationInput'));
    filterSearch();
    $(".sortElt").click(function(){
        orderBy = $(this).attr("data-value");
        direction = $(this).attr("data-direction");
        filterSearch();
    });
    $(document).on('click',".smallImgPrev,.smallImgNext" ,function(){
        var from = '';
        var to = '';
        var hotel = 0;
        from = $(this).attr("data-page");
        to = $(this).attr("data-to");
        hotel = $(this).attr("data-value");
        navigatePhotos(hotel, from, to);
    });
    /*$(".destinationInput").change(function(){
        filterSearch();
    });*/
    $(".searchButton").click(function(){
        filterSearch();
    });
    $(document).on('click',"#hotel_load_more" ,function(event){
        event.preventDefault();
        initHotelSearch(true);
    });
    $(".tuberCheckbox,.hotelPrefCheckbox,.accomodationCheckbox,.budgetCheckbox,.hotelStarCheckbox ").click(function(){
        var curob=$(this);
        if(curob.hasClass('checkboxActive')){
            curob.removeClass('checkboxActive');
        }else{
            curob.addClass('checkboxActive');
        }
    });
//    $("body").on('click','.seedetails',function(){
//        $(this).parent().parent().hide()
//            .next().show();
//    });
    $("body").on('click','.openHotelCollapsePart',function(){
        $(this).parent().hide()
            .prev().show();
    });
    $("body").on('click','.smallImg',function(){
        var $parent = $(this).closest(".aHotelContainer");
        var index = $(this).attr('data-value');
        $parent.find('.LargeSizeImg').css('width',$(this).attr('data-w'));
        $parent.find('.LargeSizeImg').css('height',$(this).attr('data-h'));
        $parent.find('.LargeSizeImg').attr('src','');
        $parent.find('.LargeSizeImg').attr('src',index);
    });
    $(".checkOnMap").fancybox({
       width        : 800,
       height       : 600,
       closeBtn     : true,
       autoSize     : false,
       autoScale    : true,
       transitionIn : 'elastic',
       transitionOut: 'fadeOut',
       type         : 'iframe',
       padding      : 0,
       margin       : 0,
       scrolling    : 'no'
    });
    $(".changeCurrency").fancybox({
       width        : 888,
       height       : 650,
       closeBtn     : true,
       autoSize     : false,
       autoScale    : true,
       transitionIn : 'elastic',
       transitionOut: 'fadeOut',
       type         : 'iframe',
       padding      : 0,
       margin       : 0,
       scrolling    : 'no'
    });
    InitCalendar();
});
function InitCalendar(){
	Calendar.setup({
            inputField : "fromtxt",
                noScroll  	 : true,
            trigger    : "fromtxt",
            align:"B",
            onSelect  : function(){
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
            trigger    : "totxt",
            align:"B",
            onSelect   : function(){
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
        $('#totxt').attr('data-cal',$('#fromtxt').attr('data-cal'));
        $('#totxt').html($('#fromtxt').html());
    }
}