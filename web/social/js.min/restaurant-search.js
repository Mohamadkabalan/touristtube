var restaurant_page = 0;
var TO_CAL;
var destination;
var country_code;
var state_code;
var time_val;
var date_val;
var persons_val;
var city_code;
var resto_code;
/*var from;
var to;
var restaurantClass;

var accomType;
var restaurantPref;*/
var tuberEvals;
var priceRange;
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
function getCurrentTime(){
	var thisdate = new Date();
	var hours = thisdate.getHours();
	if(hours<10){
		hours="0"+hours;
	}
	var minutes = thisdate.getMinutes();
	if(minutes<10){
		minutes="0"+minutes;
	}
	var time = hours+":"+minutes;
	return 	time;
}
function navigatePhotos(restaurant, from, to){
    if(to !=''){
        $.post(ReturnLink('/ajax/ajax_restaurant_search_media.php'),
            { restaurant:restaurant, from:from, to:to },function(data){
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
                $(".smallImgListCont[data-value='" + restaurant + "']").html(ret.media);
                $(".smallImgPrev[data-value='" + restaurant + "']").attr('data-page',to);
                $(".smallImgPrev[data-value='" + restaurant + "']").attr('data-to',ret.mediaPrev);
                $(".smallImgNext[data-value='" + restaurant + "']").attr('data-page',to);
                $(".smallImgNext[data-value='" + restaurant + "']").attr('data-to',ret.mediaNext);
            }
            //$('.upload-overlay-loading-fix').hide();
       });
    }
}

function redirectToTRestaurant(id,d,t,persons){
    var link = ReturnLink('/trestaurant/id/' + parseInt(id));
    if(d != ''){
        link += '/d/'+d;
    }
    if(t != ''){
        link += '/t/'+t;
    }
    if(persons != ''){
        link += '/persons/'+persons;
    }
    document.location.href = link;
}

function setCurrency(currencyGroup,hotelBudgetOption1,hotelBudgetOption2,hotelBudgetOption3,currName){
    $.fancybox.close();
    $(".budgetCheckboxGroup").html(currencyGroup);
    $(".changeCurrencyBtn").html(currName);
    filterSearch();
}
function filterSearch(){
    destination = $(".destinationInput").val();
    country_code = $(".destinationInput").attr('data-code');
    state_code = $(".destinationInput").attr('data-state-code');
    city_code = $(".destinationInput").attr('data-city');
    if(typeof $(".destinationInput").attr('data-hotel') !== 'undefined' && $(".destinationInput").attr('data-hotel') != 'false')
        resto_code = $(".destinationInput").attr('data-hotel');
    else
        resto_code = '';
    date_val = $("#fromtxt").attr("data-cal");
    time_val = $("#defaultEntry").val();
    persons_val = $("#nbOfPersons_sel").val();
    priceRange = checkboxVal('budgetCheckbox');
    if(resto_code != '' && resto_code != 'undefined'){
        redirectToTRestaurant(resto_code,date_val,time_val,persons_val);
    }else{
        initRestaurantSearch(false);
    }
}
function initRestaurantSearch(update){
    if(!update){
        update = 0;
        restaurant_page = 0;
        $(".allHotelsContainer").html('');
    // Load more case.
    } else {
        restaurant_page++;
    }
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_restaurant_search.php'),
    { orderBy:orderBy,direction:direction,restaurant_page:restaurant_page,destination:destination,country_code:country_code,state_code:state_code, persons:persons_val, d:date_val, t:time_val,city_code:city_code,tuberEvals:tuberEvals },function(data){
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
            $(".allHotelsContainer").append(ret.restaurants);
            $(".hotelSearchHeadRightDesc").html(ret.restaurantSearchHeadRightDesc);
            $(".globDescContainerLeft").html(ret.destinationDesc);
            $(".globDescContainerRightPrecent").html(ret.percent);
            if(ret.restaurantsCount > (9*(restaurant_page+1)) ){
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
            var image = new google.maps.MarkerImage( ReturnLink('/images/pin_rest.png'));
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

$(document).ready(function(){
    autocompleteRestaurants($('#destinationInput'));
    filterSearch();
    $(".searchButton").click(function(){
        filterSearch();
    });
    if($("#defaultEntry").val() === '')
        $("#defaultEntry").val( getCurrentTime() );
    $('#defaultEntry').timeEntry({
        show24Hours: true
    });
    $(".sortElt").click(function(){
        orderBy = $(this).attr("data-value");
        direction = $(this).attr("data-direction");
        filterSearch();
    });
    $(document).on('click',".cuisineMoreLess",function(){
        var $this = $(this);
        var theText = '';
        var theHtml = '';
        var tochange = '';
        theText = $(this).attr("data-value");
        theHtml = $(this).html();
        tochange = $(this).attr("data-other");
        if(theText == 'more'){
            $(".cuisineCheckboxSmallGroup").hide();
            $(".cuisineCheckboxFullGroup").show();
            $this.html(tochange);
            $this.attr("data-value",'less');
            $this.attr("data-other",theHtml);
        }else{
            $(".cuisineCheckboxFullGroup").hide();
            $(".cuisineCheckboxSmallGroup").show();
            $this.html(tochange);
            $this.attr("data-value",'more');
            $this.attr("data-other",theHtml);
        }
    });
    $(document).on('click',"#hotel_load_more" ,function(event){
        event.preventDefault();
        initRestaurantSearch(true);
    });
    $(document).on('click',".smallImgPrev,.smallImgNext" ,function(){
        var from = '';
        var to = '';
        var restaurant = 0;
        from = $(this).attr("data-page");
        to = $(this).attr("data-to");
        restaurant = $(this).attr("data-value");
        navigatePhotos(restaurant, from, to);
    });
    $(".cuisineCheckbox").click(function(){
        var cuisineArr = new Array();
        var curob=$(this);
        var aval = curob.attr("data-value");
        if(curob.hasClass('checkboxActive')){
            $(".cuisineCheckbox[data-value='" + aval + "']").removeClass('checkboxActive');
            cuisineArr = checkboxVal("cuisineCheckbox");
            $(".checkboxActive").removeClass('checkboxActive');
            cuisineArr.forEach(function(entry) {
                $(".cuisineCheckbox[data-value='" + entry + "']").addClass('checkboxActive');
            });
        }else{
            curob.addClass('checkboxActive');
            cuisineArr = checkboxVal("cuisineCheckbox");
            $(".checkboxActive").removeClass('checkboxActive');
            cuisineArr.forEach(function(entry) {
                $(".cuisineCheckbox[data-value='" + entry + "']").addClass('checkboxActive');
            });
        }
    });
    $(".offerCheckbox,.menuCheckbox,.serviceCheckbox,.cardCheckbox,.withCheckbox,.restrictionCheckbox,.mealCheckbox,.occasionCheckbox,.tuberCheckbox").click(function(){
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
            //TO_CAL.args.min = date;
            //TO_CAL.redraw();
            $('#fromtxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
            //addCalToEvent(this);
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