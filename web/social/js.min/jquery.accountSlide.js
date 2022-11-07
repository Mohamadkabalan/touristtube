(function( $ ) {
	$.accountSlide = function(options) {
  
		var settings = $.extend({
			clickable : null,
			image_up: '',
			image_down: '',
			time: 1000
		}, options);
		
		if( typeof settings.src == 'undefined' ){
			throw "'src' not defined in options"
		}
		
		if( typeof settings.dest == 'undefined' ){
			throw "'dest' not defined in options"
		}
		
		var max_height = settings.dest.height();
		settings.dest.data('height', max_height).css({'height': 0}).hide();
		
		$.each([settings.src, settings.clickable ], function(i,v){
			if(v != null ){
				v.click(function(event){	
					event.stopPropagation();
					var ResizeInterval = setInterval(function(){
						$(window).resize();
					},100);
					function getHeight(){
						return settings.dest.data('height');
					}
					if( settings.src.attr('src') == settings.image_down){
						settings.src.attr('src', settings.image_up);
						settings.dest.show().animate({height: getHeight() }, settings.time, 'linear', function(){
							//$(window).resize();
							clearInterval(ResizeInterval);
						});
					}else{
						settings.src.attr('src', settings.image_down);
						settings.dest.animate({height: 0}, settings.time, 'linear', function(){
							settings.dest.hide();
							//$(window).resize();
							clearInterval(ResizeInterval);
						});
					}
				});
			}
		});
		
	};
})( jQuery );