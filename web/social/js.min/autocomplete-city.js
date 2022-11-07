var success = false;
var CurrentLocation = new Object();
CurrentLocation.latitude = 0;
CurrentLocation.longitude = 0;
CurrentLocation.address = '';
CurrentLocation.country = 'ZZ';

function RetryGetPos(){
	
	if( success ) return ;

	// Try HTML5 geolocation
	if(navigator.geolocation) {
		
		success = true;

		navigator.geolocation.getCurrentPosition(function(position){

			var Mylatitude = position.coords.latitude;
			var Mylongitude = position.coords.longitude;
			
			CurrentLocation.latitude = Mylatitude;
			CurrentLocation.longitude = Mylongitude;
					
			var geocoder = new google.maps.Geocoder();  

			var latlng = new google.maps.LatLng(Mylatitude, Mylongitude);

			geocoder.geocode({
				'latLng': latlng
			}, function(results, status){
				
				if (status == google.maps.GeocoderStatus.OK) {
					
					if( !results || results.length == 0 ) return;
					
					$.each(results[0].address_components,function(index,element){
						if(element.types[0] == 'country'){
							CurrentLocation.country = element.short_name;
						}
					});
					
					$.each(results[0].address_components,function(index,element){
						if(element.types[0] == 'administrative_area_level_1'){
							CurrentLocation.address = element.short_name;
						}
					});
					
					$.each(results[0].address_components,function(index,element){
						if(element.types[0] == 'administrative_area_level_2'){
							if( CurrentLocation.address.indexOf(element.short_name) == -1){
								CurrentLocation.address += ', ' + element.short_name;
							}
						}
					});
					
					$.each(results[0].address_components,function(index,element){
						if(element.types[0] == 'administrative_area_level_3'){
							if( CurrentLocation.address.indexOf(element.short_name) == -1){
								CurrentLocation.address += ', ' + element.short_name;
							}
						}
					});
					
					/*$('input[name=placetakenat]').each(function(){
						if($(this).val() == ''){
							$(this).val( CurrentLocation.address );
						}
					});*/
					
					$('input[name=lattitude]').each(function(){
						if( ($(this).val() == '') || ($(this).val() == '0') ){
							$(this).val( CurrentLocation.latitude );
						}
					});
					$('input[name=longitude]').each(function(){
						if( ($(this).val() == '') || ($(this).val() == '0') ){
							$(this).val( CurrentLocation.longitude );
						}
					});
					
				}

			});

		}, function(){
			handleNoGeolocation(true);	
		});

	} else {
		// Browser doesn't support Geolocation
		handleNoGeolocation(false);
	}
}

function handleNoGeolocation(errorFlag) {
	if (errorFlag) {
		
	} else {
		success = true;
	}	
}

$(document).ready(function(){
	RetryGetPos();
});
