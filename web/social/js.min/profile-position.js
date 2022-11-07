var Running = false;
var geocoder = new google.maps.Geocoder();  
var Interval = 0;

function ProfileSetPosition(longitude,latitude){
	
	//console.log('here 2 ' + latitude + ' ' + longitude);
	
	clearInterval(Interval);
	
	$.ajax({
		url: ReturnLink('/ajax/setPosition.php'),
		data: {latitude: latitude, longitude : longitude},
		type: 'post'
	});
}

function ProfileRetryGetPos(){
	//console.log('trying');
	
	if( Running ) return ;

	//console.log('trying Inside');
	
	// Try HTML5 geolocation
	if(navigator.geolocation) {
		
		Running = true;
		
		navigator.geolocation.getCurrentPosition(function(position) {
			//console.log('got position');
			Running = false;
			ProfileSetPosition(position.coords.longitude, position.coords.latitude);
			
		}, function() {
			//console.log('didnt get position');
			Running = false;
			ProfilehandleNoGeolocation();
		},{timeout:5000});

	} else {
		// Browser doesn't support Geolocation
		ProfilehandleNoGeolocation();
	}
}

function ProfilehandleNoGeolocation() {
	
	Running = true;
	
	geocoder.geocode( {
		'address': GEO_COUNTRY
	}, function(results, status) {
		Running = false;
		if (status == google.maps.GeocoderStatus.OK && results.length > 0) {

			var searchLoc = results[0].geometry.location;
			var lat = results[0].geometry.location.lat();
			var lng = results[0].geometry.location.lng();
		
			ProfileSetPosition(lng, lat);
		}
	});
	
}

$(document).ready(function(){
	ProfileRetryGetPos();
	Interval = setInterval( ProfileRetryGetPos, 2000);
});
