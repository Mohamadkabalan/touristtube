var currencyLogo = "$";
var budgetPerDay = 0;

var budget;
var search_name;
var data_state;
var data_country;
var currency;
var departdate;
var nb_days;
var themes;
var stars = [];
var cuisines = [];
var dayNumber;
var placeToGo;
var dateDep;
var displayDay;
var rest_type;

function resetDailyBudget() {
    budgetPerDay = Math.round($(".budgetOutput").attr("data-value") / $(".plannerADaySel").attr("data-value"));
    $(".plannerDescRight").html(budgetPerDay + currencyLogo);
}
$(document).ready(function() {
    $("#budgetSlider").slider({
        from: 0, to: 19500, step: 500, smooth: true, round: 0, dimension: "&nbsp;$", skin: "tube",
        onstatechange: function(e) {
            var pointers = this.o.pointers;
            var value = pointers[0].value.origin;
            var tovalue = pointers[1].value.origin;
            var output = value == tovalue ? value + currencyLogo : value + currencyLogo + ' - ' + tovalue + currencyLogo;
            $('.budgetOutput').html(output)
            $('.budgetOutput').attr('data-value', tovalue);
            resetDailyBudget();
        }
    });
    var toret = '';
    autocompleteDiscover($('#plannerStationSelect'));
    addmoreusersautocomplete_custom_journal($('#addmoretext_privacy'));
    $(".shareMe").fancybox();
    var values = $("#foodTypeSelect").val();
    if (values != null) {
        if (values.length > 0) {
            for(var i = 0;i<values.length;i++){
                    toret += '<div class="foodTypeOptionDisp">' + values[i] + '</div>';
                }
            $(".foodTypeOptionDispCont").html(toret);
        }
    }

    $(document).on('click', '.overdatabutenable', function() {
        var $this = $(this);

        if (String("" + $this.attr('data-status')) == "1") {
            $this.attr('data-status', '0');
            $this.find('.overdatabutntficon').addClass('inactive');
        } else {
            $this.attr('data-status', '1');
            $this.find('.overdatabutntficon').removeClass('inactive');
        }
    });
    $(document).on('click', '.hotelsplus', function() {
        var $this = $(this);
        var $parent = $(this).closest('.stationContainer');
        var id = $parent.attr('data-id');
        var data_bag = $('.search_result_container').attr('data-bag');
        var data_country = "" + ($('#plannerStationSelect').attr('data-code')).toUpperCase();
        var data_state = "" + ($('#plannerStationSelect').attr('data-state-code'));
        var data_city = "" + ($('#plannerStationSelect').attr('data-city'));
        var entity_type = $parent.attr('data-type');
        TTCallAPI({
            what: '/user/bag/add',
            data: {item_id: id, entity_type: entity_type, data_country: data_country, data_state: data_state, data_city: data_city},
            callback: function(resp) {
                if (resp.status === 'error') {
                    TTAlert({
                        msg: resp.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                $('.bag_count').html(resp.count);
                $this.removeClass('hotelsplus');
                $this.addClass('stationBag');
                var mystr = '<div class="stationContainer"><div class="stationImgCon"><img src="' + $parent.find('.stationImg').attr('src') + '" alt="" class="stationImg" /></div><div class="stationTitle">' + $parent.find('.stationTitle').html() + '</div><div class="stationSep"></div></div>';
                $('.ContainermapPartttBag').append(mystr);
            }
        });
    });
    $(".foodTypeOptionDispCont").on('click', '.foodTypeOptionDisp', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var $this = $(this);
        var theVal = $(this).attr("data-value");
        var theText = $(this).html();
        var toret = '';
        if ($("#foodTypeSelect").val() != null) {
            if ($("#foodTypeSelect").val().length > 0) {
                $theArray = $("#foodTypeSelect").val();
                var index = $theArray.indexOf(theVal);
                $theArray.splice(index, 1);
                $("#foodTypeSelect").val($theArray);
                $this.remove();
                toret += '<div class="foodTypeOpt" data-value="' + theVal + '">' + theText + '</div>';
            }
        }
        $(".foodTypeSelect").append(toret);
        return false;
    });
    /*$("#foodTypeSelect").live("change",function(){
     var toret ='';
     if($("#foodTypeSelect").val() != null){
     if($("#foodTypeSelect").val().length > 0){
     $("#foodTypeSelect").val().forEach(function(entry) {
     toret += '<div class="foodTypeOptionDisp">'+entry+'</div>';
     });
     $(".foodTypeOptionDispCont").html(toret);
     }
     }
     });*/
    $(".foodTypeSelect").on("click", ".foodTypeOpt", function() {
        var theArray = $("#foodTypeSelect").val();
        var foodValue = "" + $(this).attr("data-value");
        if (theArray != null) {
            if ($.inArray(foodValue, theArray) !== -1) {
                var index = theArray.indexOf(foodValue);
                theArray.splice(index, 1);
                $("#foodTypeSelect").val(theArray);
            } else {
                theArray.push(foodValue);
                $("#foodTypeSelect").val(theArray);
            }
        }
        else {
            $("#foodTypeSelect").val([foodValue]);
        }
        var toret = '';
        var values = $("#foodTypeSelect").val();
        if (values != null) {
            if (values.length > 0) {
                for(var i = 0;i<values.length;i++){
                    var value = values[i];
                    var text = $("#foodTypeSelect option[value='" + value + "']").text();
                    toret += '<div class="foodTypeOptionDisp" data-value="' + value + '">' + text + '</div>';
                }
            }
        }
        $(".foodTypeOptionDispCont").html(toret);
        $(this).remove();
    });
    $('.FromToStars').each(function(index, element) {
        var $this = $(this);
        //var $parent = $this.closest('.evalOptionContainer');
        $this.raty({
            path: AbsolutePath + '/images',
            hints: ['Very Poor', 'Poor', 'Neutral', 'Good', 'Very Good'],
            starOn: 'rating_1.png',
            starOff: 'rating_0.png',
            score: $('#myrating_score').val()/*,
             click: function(score, evt) {
             $parent.find('.evalOptionNum').html( score );
             }*/
        });
    });
    $(".plannerThemesOption").click(function() {
        $(this).toggleClass('plannerThemesOptionSelected');
    });
    $(".interestCategoryOption").click(function() {
        $(this).remove();
    });
    /*$(".starBlock").click(function(){
     var $this = $(this);
     if($this.hasClass('starBlockSel')){
     $(".starBlock").removeClass('starBlockSel');
     }else{
     $(".starBlock").removeClass('starBlockSel');
     $this.addClass('starBlockSel');
     }
     });*/
    /*$(".starBlock").click(function(){
     var $this = $(this);
     if(! $this.hasClass('starBlockSel')){
     $(".starBlock").removeClass('starBlockSel');
     $this.addClass('starBlockSel');
     }
     });*/
    $(".starBlock").click(function() {
        var $this = $(this);
        if ($this.hasClass('starBlockSel')) {
            $this.removeClass('starBlockSel');
        }
        else {
            $this.addClass('starBlockSel');
        }
    });
    $(".plannerValidateButton").click(function() {
        budget = $('.budgetOutput').attr('data-value');
        search_name = $('#plannerStationSelect').val();
        data_state = $('#plannerStationSelect').attr('data-state-code');
        data_country = $('#plannerStationSelect').attr('data-code');
        currency = "" + $('.myCurrencySelect').val();
        departdate = $('.departdate').attr('data-value');
        nb_days = $('.plannerADay.plannerADaySel').attr('data-value');

        var themes_array = new Array();
        $('.plannerThemesOption.plannerThemesOptionSelected').each(function(index, element) {
            var $value = $(this).attr('data-value');
            themes_array.push($value);
        });
        themes = themes_array.join('/*/');
        var errorToReturn = '';

        stars.length = 0;
        $('.starBlock.starBlockSel').each(function() {
            var $value = $(this).attr('data-value');
            stars.push($value);
        });

        dayNumber = $(".plannerADaySel").attr("data-value");
        dateDep = $("#fromtxt").attr("data-value");
        placeToGo = getObjectData($(".plannerStationSelect"));
        if (placeToGo == '') {
            errorToReturn = t('please, specify your destination');
        }
        if (dateDep == '') {
            if (errorToReturn != '') {
                errorToReturn = t('please, specify your destination and your departure date');
            } else {
                errorToReturn = t('please, specify your departure date');
            }
        }        
        rest_type = $('#foodTypeSelect').val() != null ? $('#foodTypeSelect').val().join(',') : '';
        if (errorToReturn != '') {
            TTAlert({
                msg: errorToReturn,
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        displayDay = 1;
        $(".search_result_container").attr('data-bag', search_name);
        getMapRelated();
    });
    $(".plannerADay").click(function() {
        var $this = $(this);
        if (!$this.hasClass('plannerADaySel')) {
            $(".plannerADay").removeClass('plannerADaySel');
            $this.addClass('plannerADaySel');
            resetDailyBudget();
        }
    });
    $('.plannerAddDay').click(function() {
        $(".plannerNbDaysContainer").animate({top: '-75px'}, 500);
        $(".plannerNbDaysContainer2").animate({top: '0px'}, 500);
    });
    $('.plannerRemDay').click(function() {
        $(".plannerNbDaysContainer2").animate({top: '75px'}, 500);
        $(".plannerNbDaysContainer").animate({top: '0px'}, 500);
    });
    $('.plannerSavedTrips .plannerBottomTrips:even').addClass('clearBoth');
    $('.plannerSuggestedFriendsTrips .plannerBottomTrips:even').addClass('clearBoth');

    $('#dateInput').blur(function() {
        if ($(this).val() == '') {
            $(this).val('dd');
        }
    });
    $('#dateInput2').blur(function() {
        if ($(this).val() == '') {
            $(this).val('mm');
        }
    });
    $('#dateInput3').blur(function() {
        if ($(this).val() == '') {
            $(this).val('yyyy');
        }
    });
    //$(".mapParttripDates,.mapParthotels,.mapPartrestaurants,.mapPartattractions,.mapPartmyPlanner,.mapPartttBag").click(function(){
    $(document).on('click', ".mapPart_buttons", function() {
        var $this = $(this);
        var $index = $this.attr("index");
        if ($this.hasClass('mapPartSel')) {
            $(".mapParttripDates,.mapParthotels,.mapPartrestaurants,.mapPartattractions,.mapPartmyPlanner,.mapPartttBag,.mapPartTuber").removeClass('mapPartSel');
            $(".ContainermapParttripDates,.ContainermapParthotels,.ContainermapPartrestaurants,.ContainermapPartattractions,.ContainermapPartmyPlanner,.ContainermapPartttBag,.ContainermapPartTuber").hide();
            $(".ContainermapPartSchedule").show();
            initscrollPane($(".ContainermapPartSchedule"));
        }
        else {
            $(".mapParttripDates,.mapParthotels,.mapPartrestaurants,.mapPartattractions,.mapPartmyPlanner,.mapPartttBag,.mapPartTuber").removeClass('mapPartSel');
            $(".ContainermapPartSchedule,.ContainermapParttripDates,.ContainermapParthotels,.ContainermapPartrestaurants,.ContainermapPartattractions,.ContainermapPartmyPlanner,.ContainermapPartttBag,.ContainermapPartTuber").hide();
            $(".ContainermapParttripDates,.ContainermapParthotels,.ContainermapPartrestaurants,.ContainermapPartattractions,.ContainermapPartmyPlanner,.ContainermapPartttBag,.ContainermapPartTuber").each(function() {
                if ($(this).attr("index") == $index) {
                    $(this).show();
                    initscrollPane($(this));
                }
            });
            $this.addClass('mapPartSel');
        }
    });
    InitCalendar();
    $(document).on('click', ".uploadinfocheckbox", function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#friendsdata').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#friends_of_friends_data').remove();
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    $(this).parent().parent().find('#followersdata').remove();
                }
            }
        } else {
            $(this).addClass('active');
            if ($(this).hasClass('uploadinfocheckbox3')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var friendstr = '<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(friendstr);
                    //$(this).parent().parent().find('#friendsdata').css("width", ($(this).parent().parent().find('#friendsdata .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox_friends_of_friends')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends of friends")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                    //$(this).parent().parent().find('#friends_of_friends_data').css("width", ($(this).parent().parent().find('#friends_of_friends_data .peoplesdatainside').width() + 20) + "px");
                }
            } else if ($(this).hasClass('uploadinfocheckbox4')) {
                if ($(this).parent().hasClass("friendscontainer")) {
                    var followerstr = '<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("followers")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                    //$(this).parent().parent().find('#followersdata').css("width", ($(this).parent().parent().find('#followersdata .peoplesdatainside').width() + 20) + "px");
                }
            }
        }
    });
    $(document).on('click', ".peoplesdataclose", function() {
        var parents = $(this).parent();
        var parents_all = parents.parent().parent();
        parents.remove();
        if (parents.attr('data-id') != '') {
            SelectedUsersDelete(parents.attr('data-id'), parents_all.find('.addmore input'));
        }
        if (parents.attr('id') == 'friendsdata') {
            parents_all.find('.uploadinfocheckbox3').removeClass('active');
        } else if (parents.attr('id') == 'followersdata') {
            parents_all.find('.uploadinfocheckbox4').removeClass('active');
        } else if (parents.attr('id') == 'connectionsdata') {
            parents_all.find('.uploadinfocheckbox5').removeClass('active');
        } else if (parents.attr('id') == 'sponsorssdata') {
            parents_all.find('.uploadinfocheckbox6').removeClass('active');
        } else if (parents.attr('id') == 'friends_of_friends_data') {
            parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
        }
    });
});
function getSelectionHandler() {
}

function setCalanderTrip(selected_date) {
    Calendar.setup({
        cont: "tripDatesCalendarContainer",
                noScroll  	 : true,
        fdow: 1,
        selectionType: Calendar.SEL_SINGLE,
        onSelect: getSelectionHandler(),
        selection: Calendar.dateToInt(selected_date),
        date: selected_date,
        disabled: function(date) {
            var dats = new Date();
            var currentdate = new Date(dats.getFullYear(), dats.getMonth(), dats.getDate(), 0, 0, 0, 0);
            return (date < currentdate);
        }
    });
}
function InitCalendar() {
    Calendar.setup({
        inputField: "fromtxt",
                noScroll  	 : true,
        trigger: "fromtxt",
        align: "B",
        onSelect: function(calss) {
            var date = Calendar.intToDate(calss.selection.get());
            $('#fromtxt').attr('data-value', Calendar.printDate(date, "%Y-%m-%d"));
            this.hide();
        },
        dateFormat: "%d / %m / %Y",
        disabled: function(date) {
            var dats = new Date();
            var currentdate = new Date(dats.getFullYear(), dats.getMonth(), dats.getDate(), 0, 0, 0, 0);
            return (date < currentdate);
        }
    });
}
var directionsDisplay;
var directionsService;
var poly;
var map;
var ib;
var boxText;
var flightPlanCoordinates = [];

function setMapSearch(latitude, longitude, title, flightPlanCoordinates_str, thisday, bagCoordinates_str) {
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();

    var $googlemap_location = $('.googlemap');
    var $id_location = $googlemap_location.attr('id');
    var image = new google.maps.MarkerImage(ReturnLink('/images/pin_empty.png'));
    var myStyles = [
        {
            featureType: "poi",
            elementType: "labels",
            stylers: [
                {visibility: "off"}
            ]
        }
    ];
    var mapOptions = {
        center: new google.maps.LatLng(latitude, longitude),
        zoom: 12,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: myStyles
    };

    map = new google.maps.Map(document.getElementById($id_location), mapOptions);
    /*var marker = new google.maps.Marker({
     position: new google.maps.LatLng( latitude , longitude ),
     map: map,
     icon: image,
     title: title
     });*/

    directionsDisplay.setMap(map);
    directionsDisplay.setOptions({suppressMarkers: true});

    /*var polyOptions = {
     strokeColor: 'red',
     strokeOpacity: 0.9,
     strokeWeight: 8
     };
     poly = new google.maps.Polyline(polyOptions);
     poly.setMap(map);*/

    boxText = document.createElement("div");

    var myOptions = {
        content: boxText
        , disableAutoPan: false
        , maxWidth: 0
        , pixelOffset: new google.maps.Size(-140, 0)
        , zIndex: null
        , boxStyle: {
             opacity: 0.9
            , width: "120px"
            , height: "140px"
        }
        , closeBoxURL: ""
        , infoBoxClearance: new google.maps.Size(1, 1)
        , isHidden: false
        , pane: "floatPane"
        , enableEventPropagation: false
    };

    ib = new InfoBox(myOptions);

    var bagCoordinates_arr = (bagCoordinates_str).split('[*]');
    var image1;
    for (var i = 0; i < bagCoordinates_arr.length; i++) {
        var list = (bagCoordinates_arr[i]).split('/*/');
        if (list[0] == 0 || list[1] == 0)
            continue;
        var loc = new google.maps.LatLng(list[1], list[0]);
        if (parseInt(list[2]) == SOCIAL_ENTITY_RESTAURANT) {
            image1 = new google.maps.MarkerImage(ReturnLink("/images/pin_rest.png"));
        } else if (parseInt(list[2]) == SOCIAL_ENTITY_HOTEL) {
            image1 = new google.maps.MarkerImage(ReturnLink("/images/pin_hot.png"));
        } else if (parseInt(list[2]) == SOCIAL_ENTITY_LANDMARK) {
            image1 = new google.maps.MarkerImage(ReturnLink("/images/pin_lmk.png"));
        }
        drawBagMarker(loc, image1, list[3], list[4], list[5], list[6], 0, '', 0);
    }
    google.maps.event.addListener(map, 'click', function() {
        ib.close();
    });

    drawMapLines(flightPlanCoordinates_str, thisday);
}
function drawMapLines(flightPlanCoordinates_str, thisday) {
    var coordinates_arr = (flightPlanCoordinates_str).split('[*]');
    flightPlanCoordinates = [];
    for (var i = 0; i < coordinates_arr.length; i++) {
        var list = (coordinates_arr[i]).split('/*/');
        if (list[0] == 0 || list[1] == 0)
            continue;
        var loc = new google.maps.LatLng(list[1], list[0]);
        var parray = new Array(loc, list[2], list[3], list[4], list[5], list[6]);
        flightPlanCoordinates.push(parray);
    }
    if (flightPlanCoordinates.length > 1)
        flightPlanCoordinates.push(flightPlanCoordinates[0]);

    if (flightPlanCoordinates.length > 0) {
        var image = new google.maps.MarkerImage(ReturnLink("/images/pin_empty.png"));
        var image_hot = new google.maps.MarkerImage(ReturnLink("/images/pin_hot.png"));
        drawBagMarker(flightPlanCoordinates[0][0], image_hot, flightPlanCoordinates[0][1], flightPlanCoordinates[0][3], flightPlanCoordinates[0][4], flightPlanCoordinates[0][5], 0, '', 0);
        calcRoute(0, image);
    }
}
function addLatLng(j) {
    var obj = flightPlanCoordinates[j][0];
    var path = poly.getPath();
    path.push(obj);

    j++;
    if (j >= flightPlanCoordinates.length)
        return;
    else {
        setTimeout(function() {
            addLatLng(j)
        }, 800)
    }
}

function calcRoute(j, image) {
    var count = flightPlanCoordinates.length;
    var start = flightPlanCoordinates[0][0];
    var end = flightPlanCoordinates[j][0];
    var waypts = [];
    var mytitle = '';
    for (var i = 1; i < j; i++) {
        if (i == (j - 1))
            mytitle = "Start point \n" + flightPlanCoordinates[i][1]
        else
            mytitle = flightPlanCoordinates[i][1]
        waypts.push({location: flightPlanCoordinates[i][0], stopover: true});
    }
    var request = {
        origin: start,
        destination: end,
        waypoints: waypts,
        //unitSystem: google.maps.UnitSystem.METRIC,
        //durationInTraffic: true,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.WALKING
    };
    var myDistanceDuration = new Array();
    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);

            var rts = result.routes[0];
            //console.log(rts);
            j++;
            if (j > count - 1) {
                for(var i = 0; i < rts.legs.length; i++){
                    var a = rts.legs[i];
                    myDistanceDuration.push([a.distance.text, a.duration.text, rts.waypoint_order]);
                }
//                rts.legs.forEach(function(a) {
//                    myDistanceDuration.push([a.distance.text, a.duration.text, rts.waypoint_order]);
//                })
                DisplayOnMap(myDistanceDuration, image);

                return;
            } else {
                setTimeout(function() {
                    calcRoute(j, image)
                }, 800)
            }
        }
    });
}

function DisplayOnMap(myDistanceDuration, image) {
    var str = '';
    for(var i = 0; i< myDistanceDuration.length; i++){
        var a = myDistanceDuration[i];
        var j = a[2][i];

        if (String("" + j) == "undefined") {
            j = -1;
        }
        if (String("" + j) != "undefined") {
            j = parseInt(j) + 1;
            try {
                if (j == 0) {
                    var image_hot = new google.maps.MarkerImage(ReturnLink("/images/pin_hot.png"));
                    drawBagMarker(flightPlanCoordinates[j][0], image_hot, flightPlanCoordinates[j][1], flightPlanCoordinates[j][3], flightPlanCoordinates[j][4], flightPlanCoordinates[j][5], 0, (a[0] + " - " + a[1]), 0);
                } else {
                    drawBagMarker(flightPlanCoordinates[j][0], image, flightPlanCoordinates[j][1], flightPlanCoordinates[j][3], flightPlanCoordinates[j][4], flightPlanCoordinates[j][5], 0, (a[0] + " - " + a[1]), (i + 1));
                }
            } catch (e) {
            }
        }
        str += "<strong>"+t('Track')+" " + i + " :</strong> " + a[0] + " - " + a[1] + "<br>";
    }
//    var i = 0;
//    myDistanceDuration.forEach(function(a) {
//        var j = a[2][i];
//
//        if (String("" + j) == "undefined") {
//            j = -1;
//        }
//        if (String("" + j) != "undefined") {
//            j = parseInt(j) + 1;
//            try {
//                if (j == 0) {
//                    var image_hot = new google.maps.MarkerImage(ReturnLink("/images/pin_hot.png"));
//                    drawBagMarker(flightPlanCoordinates[j][0], image_hot, flightPlanCoordinates[j][1], flightPlanCoordinates[j][3], flightPlanCoordinates[j][4], flightPlanCoordinates[j][5], 0, (a[0] + " - " + a[1]), 0);
//                } else {
//                    drawBagMarker(flightPlanCoordinates[j][0], image, flightPlanCoordinates[j][1], flightPlanCoordinates[j][3], flightPlanCoordinates[j][4], flightPlanCoordinates[j][5], 0, (a[0] + " - " + a[1]), (i + 1));
//                }
//            } catch (e) {
//            }
//        }
//        i++;
//        str += "<strong>Track " + i + " :</strong> " + a[0] + " - " + a[1] + "<br>";
//    });
    $('.plannerMapTrackInfo').append(str);
}


function changeDay(obj) {
    displayDay = parseInt($(obj).val());
    getMapRelated();
}
function drawBagMarker(position, image, title, link_uri, img_uri, anchor_star, reviews, real_time, number) {
    if (number != 0) {
        var marker = new MarkerWithLabel({
            position: position,
            map: map,
            draggable: true,
            raiseOnDrag: true,
            labelContent: number,
            icon: image,
            icon: image,
                    labelAnchor: new google.maps.Point(3, 30),
            labelClass: "labels", // the CSS class for the label
            labelInBackground: false
        });
    } else {
        var marker = new google.maps.Marker({
            position: position,
            title: title,
            icon: image,
            map: map
        });
    }

    //real_time = '';
    var base_url = '';
    var onMarkerClick = function() {
        ib.close();
        var marker = this;
        //map.setCenter(new google.maps.LatLng(la, lo));
        //var latLng = marker.getPosition();
        var content = '<div class="mtooltip"><div class="mtooltipin"><div class="clsgoo" onClick="ib.close();">X</div>';
        if (img_uri != '') {
            content += '<img class="mtooltipbig" src="' + img_uri + '" alt="" width="72" height="39" />';
            content += '<br /><a href="' + link_uri + '" target="_blank">';
        }
        //content += '<div class="mtooltip_title">'+title+'</div><br />'+la+'<br />'+lo+'<br />';
        content += '<div class="mtooltip_title">' + title + '</div>';
        if (img_uri != '') {
            content += '</a>';
        }
        if (parseInt(anchor_star) > 0 && parseInt(anchor_star) <= 5) {
            content += '<div class="anchor_star anchor_star' + anchor_star + '"></div>';
        }
        if (parseInt(reviews) != 0) {
            content += '<div class="textreviews"><u>' + reviews + ' ' +t("REVIEWS")+'</u></div>';
        }
        if (real_time != '') {
            content += '<div class="textreviews">' + real_time + '</div>';
        }
        content += '</div><div class="anchor_bk"></div></div>';
        //ib.setContent(content);
        boxText.innerHTML = content;
        ib.open(map, marker);
    };
    google.maps.event.addListener(marker, 'click', onMarkerClick);
}

function getMapRelated() {
    $('.upload-overlay-loading-fix').show();
    $.post(ReturnLink('/ajax/ajax_planer_result.php'), {search_name: search_name, data_state: data_state, data_country: data_country, displayDay: displayDay, currency: currency, budget: budget, departdate: departdate, nb_days: nb_days, themes: themes, stars: stars, rest_type: rest_type}, function(data) {
        var ret = null;
        try {
            ret = $.parseJSON(data);
        } catch (Ex) {
            $('.upload-overlay-loading-fix').hide();
            return;
        }
        if (ret.error) {
            TTAlert({
                msg: ret.error,
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
        } else {
            $(".search_result_container").html(ret.data);
            $(".current_bag .bag_count").html(ret.all_bag_count);
            $(".current_bag").show();

            $('#plannerStationSelect').attr('data-code', ret.data_country);
            $('#plannerStationSelect').attr('data-state-code', ret.data_state);
            $('#plannerStationSelect').attr('data-city', ret.city_id);

            setCalanderTrip(parseInt(ret.departdate_str));
            initscrollPane($(".ContainermapPartSchedule"));
        }
        $('.upload-overlay-loading-fix').hide();
    });
}
function initscrollPane(obj) {
    obj.jScrollPane();
}