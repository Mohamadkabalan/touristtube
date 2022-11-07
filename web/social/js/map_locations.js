function LocationMap(containerID, lat,lng,name ){

	var settings = {
		start_zoom: 15,
		min_zoom: 13,
		max_zoom: 18
	};

	var pos = new google.maps.LatLng(lat,lng);

	var mapOptions = {
		zoom: settings.start_zoom,
		minZoom: settings.min_zoom,
		maxZoom: settings.max_zoom,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableAutoPan: false,
		navigationControl: false,
		streetViewControl: false,
		mapTypeControl: false,
		center: pos
	};

	map = new google.maps.Map(document.getElementById(containerID),mapOptions);

	if($.browser.mozilla) FixFireFoxMapScroll(map);
	
	var MyLocationImage = new google.maps.MarkerImage(
	AbsolutePath + '/media/images/mylocation.png',
	new google.maps.Size(45,45),
	new google.maps.Point(0,0),
	new google.maps.Point(22,22)
	);

	var MyLocation = new google.maps.Marker({
		position: pos,
		clickable: false,
		icon: MyLocationImage,
		map: map,
		title: name
	});
}


function RateLocation(){
	
	$('#rating_dialog').dialog({
		zIndex: 5000,
		autoOpen: true,
		title: "Submit Review",
		modal: true,
		draggable: false,
		minWidth: 350,
		resizable: false,
		open: function(event,ui){
			var $dialog = $('div.ui-dialog-content');
			var $dialog_content = $('<div class="tt_content"></div>');
			var $dialog_stars = $('<div></div>');
			var $dialog_comment = $('<textarea style="width:300px;height: 75px;margin-bottom: 10px;" maxlength="255"></textarea>');
			var $dialog_score = $('<input type="hidden" name="score" value="0"/>');
			var $dialog_commented = $('<input type="hidden" name="comment" value="0"/>');

			$dialog_content.append('<div class="ReviewLabel">'+t('Review')+'</div>').append($dialog_comment).append('<div class="ReviewLabel">'+t('Rating')+'</div>').append($dialog_stars).append($dialog_score).append($dialog_commented);

			$dialog_comment.focus(function(){
				if($dialog_commented.val() == 0){
					$dialog_commented.val(1);
					$(this).val('');
				}
			});

			$('div.tt_content',$dialog).remove();
			$dialog.append($dialog_content);
			

			$.ajax({
				url: ReturnLink('/ajax/location_myreview.php'),
				type: 'post',
				data: {
					uid: LocationId
				},
				success: function(response){
					$('body').css('cursor', '');

					var jres = null;
					try{
						jres = $.parseJSON( response );
					}catch(Ex){

					}
					if( !jres ){
						TTAlert({
							msg: t("Couldn't get rate data. Please try again later."),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						$('#rating_dialog').dialog('close');
						return ;
					}
					//$('#rating_dialog').dialog('close');

					var stars = 0;
					if(jres.status == 'none'){

					}else{
						stars = jres.rating;
						if( jres.review.length != 0 ){
							$dialog_comment.val(jres.review);
							$dialog_commented.val(1);
						}
					}

					/////////////////////////// raty
					$dialog_stars.raty({
						path: AbsolutePath + '/media/images/raty',
						hints   : ['Very Poor', 'Poor', 'Neutral', 'Good', 'Very Good' ],
						starOn  : 'star-on.png',
						starOff : 'star-off.png',
						score : stars,
						click: function(score, evt) {
							////////////////click option
							var comment = $dialog_comment.val();
							if( comment.length == 0 ){
								TTAlert({
									msg: t('Please write a review'),
									type: 'alert',
									btn1: t('ok'),
									btn2: '',
									btn2Callback: null
								});
								return false;
							}
							
							$.ajax({
								url : ReturnLink('/ajax/location_review_set.php'),
								type: 'post',
								data: {
									uid: LocationId,
									score: score,
									review: comment
								},
								success: function(response){
									$('body').css('cursor', '');

									var jres = null;
									try{
										jres = $.parseJSON( response );
									}catch(Ex){

									}
									if( !jres ){
										TTAlert({
											msg: t('Couldnt submit rating. Please try again later'),
											type: 'alert',
											btn1: t('ok'),
											btn2: '',
											btn2Callback: null
										});
										return ;
									}
									if( jres.status != 'ok' ){
										TTAlert({
											msg: jres.msg,
											type: 'alert',
											btn1: t('ok'),
											btn2: '',
											btn2Callback: null
										});
									}
									
									//update rating
									$('#n_review').html( jres.n_review );

									var ratingi = parseInt(jres.rating);
									$('#rating img.rating_star').each(function(i){
										if(i < ratingi){
											$(this).attr('src',AbsolutePath + '/media/images/rating_1.png');
										}else{
											$(this).attr('src',AbsolutePath + '/media/images/rating_0.png');
										}
									});
									
									ReloadComments();

									$('#rating_dialog').dialog('close');

									TTAlert({
										msg: t('Review Submitted!'),
										type: 'alert',
										btn1: t('ok'),
										btn2: '',
										btn2Callback: null
									});
								}
							});
							//////////////////////////click option
						}
					});
					/////////////////////////// raty
				}
				///////////////////////success function
			});


		}
	});

}
					