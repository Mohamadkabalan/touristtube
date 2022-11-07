$(document).ready(function() {
    addAutoCompleteList('city_locations');
    google.maps.event.addDomListener(window, 'load', initialize);
    $('#submit').click(function() {
        if(!ValidateAddPoiForm())
            return false;
        else
            $('#add_poi').submit();
    });
    $(document).on('keypress','#pac-input',function(event){
        if(event.keyCode ==13)
            return false;
    });

});
/*$(document).on('keyup','#city_id',function(){
    var min_length = 3; // min characters to display the auto-complete
    var keyword = $('#city_id').val();
    var cc = $('#country').val();
    var lang='en';
    if (keyword.length >= min_length) {
        $.ajax({
            url: ReturnLink('/ajax/addHotel_countryList.php'),
            type: 'POST',
            data: {cc:cc,term:keyword},
            success:function(data){
                if(data==''){
                    $('#country_list_id').hide();
                    return false;
                }
                $('#country_list_id').show();
                $('#country_list_id').html(data);
            }
        });
    } else {
        $('#country_list_id').hide();
    }
});
// set_item : this function will be executed when we select an item
$(document).on('click','.city_list_item',function(){
    var item = $(this).attr('data-name');
    var city_id = $(this).attr('data-id');
    $('#city_id').val(item);
    $('#cid').val(city_id);
    $('#country_list_id').hide();
});*/
function addAutoCompleteList(which) {
    var $ccity = $("input[name=city]", $('#' + which));
    $ccity.autocomplete({
        appendTo: "#" + which,
        search: function (event, ui) {
            var $country = $('#country');
            //console.log($country);
            var cc = $country.val();
            if (cc == 'ZZ') {
                $country.addClass('err');
                event.preventDefault();
            } else {
                $ccity.autocomplete("option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc));
            }
        },
        select: function (event, ui) {
            $ccity.val(ui.item.value);
            $('#cityid').val(ui.item.id);
            event.preventDefault();
        }
    });
}
function initialize() {
    var lat=null;
    var lng=null;

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            console.log('Geo location is not supported by this browser');
        }
    }
    function showPosition(position) {
        lat = position.coords.latitude;
        lng = position.coords.longitude;
        var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(lat, lng),
                new google.maps.LatLng(lat, lng));
        map.fitBounds(defaultBounds);
    }
    var markers = [];
    var geocoder = geocoder = new google.maps.Geocoder();
    var map = new google.maps.Map(document.getElementById('map'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    lat = lat?lat:-33.8902;
    lng = lng?lng:151.1759;

    getLocation();

    var defaultBounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(lat, lng),
            new google.maps.LatLng(lat, lng));
    map.fitBounds(defaultBounds);

    // Create the search box and link it to the UI element.
    var input = /** @type {HTMLInputElement} */(
            document.getElementById('pac-input'));
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var searchBox = new google.maps.places.SearchBox(
            /** @type {HTMLInputElement} */(input));

    // Listen for the event fired when the user selects an item from the
    // pick list. Retrieve the matching places for that item.
    google.maps.event.addListener(searchBox, 'places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }
        for (var i = 0, marker; marker = markers[i]; i++) {
            marker.setMap(null);
        }
        // For each place, get the icon, place name, and location.
        markers = [];
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
            var image = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            var marker = new google.maps.Marker({
                map: map,
                icon: image,
                title: place.name,
                position: place.geometry.location,
                animation: google.maps.Animation.DROP
                //draggable:true
            });
            markers.push(marker);
            bounds.extend(place.geometry.location);

            $('#latitude').val(place.geometry.location.lat());
            $('#longitude').val(place.geometry.location.lng());
        }
        map.fitBounds(bounds);
        google.maps.event.addListener(marker, "dragend", function(e){
            var lat, lng, address;
            geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    lat = marker.getPosition().lat();
                    lng = marker.getPosition().lng();
                    address = results[0].formatted_address;
                   // alert("Latitude: " + lat + "\nLongitude: " + lng + "\nAddress: " + address);
                }
            });
        });
    });
    // Bias the SearchBox results towards places that are within the bounds of the
    // current map's viewport.
    google.maps.event.addListener(map, 'bounds_changed', function() {
        var bounds = map.getBounds();
        searchBox.setBounds(bounds);
    });
}
/**
 * Created by rishav on 22/12/14.
 */
function validateEmail(email) {
    var re = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
    return re.test(email);
}function IsNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
}
function validateURL(textval) {
    var urlregex = new RegExp(
        "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
    return urlregex.test(textval);
}function phonenumber(inputtxt){
    var ph = inputtxt.trim();
    if(!isNaN(ph) && ph>=9)
        return true;
    else
        return false;

}function ValidateAddPoiForm() {

    if ($("#cat").val() == '') {
        TTAlert({
            msg: t('Please Select a Category'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });

        $("#property").focus();
        return false;
    }
    else if ($("#names_poi").val() == '') {
        TTAlert({
            msg: t('Please Enter a title for your Point of interest.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });

        $("#names_poi").focus();
        return false;
    } else if ($("#country").val() == '') {
        TTAlert({
            msg: t('Please select your country.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });

        $("#country").focus();
        return false;
    }else if ($("#city_id").val() == '') {
        TTAlert({
            msg: t('Please select a city.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#city_id").focus();
        return false;
    }else if ($("#pac-input").val() == '') {
        TTAlert({
            msg: t('please select the location of your Point of interest in map.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#pac-input").focus();
        return false;
    }else if ($("#price_poi").val()!='' && !IsNumeric($("#price_poi").val())) {
        TTAlert({
            msg: t('Cost should be a number'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#price_poi").focus();
        return false;
    }else if ($("#address_poi").val() == '') {
        TTAlert({
            msg: t('Please Enter a valid address.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });

        $("#address_poi").focus();
        return false;
    }else if ($("#email").val() == '') {
        TTAlert({
            msg: t('Please insert your email address'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#email").focus();
        return false;
    }else if(! validateEmail($("#email").val())){
        TTAlert({
            msg: t('Please insert a valid email address'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#email").focus();
        return false;
    }else if($("#url_poi").val()!='' && ! validateURL($("#url_poi").val())){
        TTAlert({
            msg: t('Please enter a valid URL'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#url_poi").focus();
        return false;
    }else if(! phonenumber($("#phone_poi").val())){
        TTAlert({
            msg: t('Please enter a valid Phone number'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#phone_poi").focus();
        return false;
    }else
        return true;
}