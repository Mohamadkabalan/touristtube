$(document).ready(function(){

	var mapOptions = {
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableAutoPan: false,
		navigationControl: false,
		streetViewControl: false,
		mapTypeControl: false/*,
		center: new google.maps.LatLng(41.06000,28.98700)*/
	};

	var map = new google.maps.Map(document.getElementById("BigMap"),mapOptions);
	
	var success = false;
	
	function RetryGetPos(){
		
		if( success ) return ;
		
		// Try HTML5 geolocation
		if(navigator.geolocation) {
			
			success = true;

			navigator.geolocation.getCurrentPosition(function(position) {
				var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				
				//my position
				map.setCenter(pos);
				
				var MyLocationImage = new google.maps.MarkerImage(
					AbsolutePath + '/images/mylocation.png',
					new google.maps.Size(45,45),
					new google.maps.Point(0,0),
					new google.maps.Point(22,45)
					);
	
				var MyLocation = new google.maps.Marker({
					position: pos,
					clickable: false,
					icon: MyLocationImage,
					map: map,
					title: 'Me!'
				});
				
				////////////////////////////
				
				//other markers
				$.ajax({
					url: AbsolutePath + '/ajax/setLocation.php',
					data: {latitude: position.coords.latitude, longitude : position.coords.longitude },
					type: 'post',
					success: function(data){
						$('body').css('cursor','');
						var ret = null;
						try{
							ret = $.parseJSON( data );
						}catch(ex){
									
						}
						if(!ret) return;
						$.each(ret, function(index,element){
							
							var imagePath = '';
							var mw = 45;
							var mh = 45;
							if(element.image != ''){
								imagePath = AbsolutePath + '/media/tubers/'+element.image;
							}else{
								if(element.type=='tuber'){
									imagePath = AbsolutePath + '/images/tuber_marker.gif';
									mw = 35;
								}
								else
									imagePath = AbsolutePath + '/images/'+element.type+'.gif';
							}
							var markerImage = new google.maps.MarkerImage(
								imagePath,
								new google.maps.Size(mw,mh),
								new google.maps.Point(0,0),
								new google.maps.Point(mw/2,45)
								);
							var mpos = new google.maps.LatLng(element.latitude, element.longitude);
							var title = element.name;
							if(title == ''){
								switch(element.type){
									case 'tuber':
										title = 'Another Tuber!';
										break;
									default:
										break;
								}
							}
							var marker = new google.maps.Marker({
								position: mpos,
								//clickable: true,
								icon: markerImage,
								map: map,
								title: title
							});
							google.maps.event.addListener(marker, "click", function() {
								//alert('here');
							});
						});
					}
				});
				

			}, function() {
				handleNoGeolocation(true);
			});

		} else {
			// Browser doesn't support Geolocation
			handleNoGeolocation(false);
		}
	}
	
	RetryGetPos();
	setTimeout( RetryGetPos, 3000);

	function handleNoGeolocation(errorFlag) {
		if (errorFlag) {
			var content = 'Error: The Geolocation service failed.';
		} else {
			var content = 'Error: Your browser doesn\'t support geolocation.';
		}	
	}

	var geocoder = new google.maps.Geocoder();  

	$(function() {
		$(".citylist").autocomplete({
         
			source: function(request, response) {

				if (geocoder == null){
					geocoder = new google.maps.Geocoder();
				}
				geocoder.geocode( {
					'address': request.term
				}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {

						var searchLoc = results[0].geometry.location;
						var lat = results[0].geometry.location.lat();
						var lng = results[0].geometry.location.lng();
						var latlng = new google.maps.LatLng(lat, lng);
						var bounds = results[0].geometry.bounds;

						geocoder.geocode({
							'latLng': latlng
						}, function(results1, status1) {
							if (status1 == google.maps.GeocoderStatus.OK) {
								if (results1[1]) {
									response($.map(results1, function(loc) {
										return {
											label  : loc.formatted_address,
											value  : loc.formatted_address,
											bounds   : loc.geometry.bounds
										}
									}));
								}
							}
						});
					}
				});
			},
			select: function(event,ui){
				var pos = ui.item.position;
				var lct = ui.item.locType;
				var bounds = ui.item.bounds;
				
				if (bounds){
				
					map.fitBounds(bounds);
				
					var MyLocationImage = new google.maps.MarkerImage(
						AbsolutePath + '/images/mylocation.png',
						new google.maps.Size(45,45),
						new google.maps.Point(0,0),
						new google.maps.Point(22,45)
						);
				
					var MyLocation = new google.maps.Marker({
						position: map.getCenter(),
						clickable: false,
						icon: MyLocationImage,
						map: map,
						title: 'Me!'
					});
				
				}
			}
		});
	});   
});