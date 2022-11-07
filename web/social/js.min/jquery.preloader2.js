(function( $ ){

	$.fn.preloader = function() {  

		var ImagesArr = new Array();
		var checkFlag = new Array();
			
		this.each(function() {

			var $this = $(this);
			
			$this.find('img').each(function(i,v){
				if( $(this).data('preloaded') ) return;
				$(this).data('preloaded',true);
				$(this).css({'visibility': 'hidden', 'opacity' : 0});/*.load(function(){
					$(this).parent().removeClass('preloader');
					$(this).css({'visibility': 'visible', 'opacity' : 1});
				});*/
				if( $(this).parent('a').length == 0 ){
					 $(this).wrap("<a class='preloader added' />");
				}else{
					$(this).parent().addClass('preloader')
				}
				ImagesArr.push($(this));
				checkFlag.push(false);
			});
			
		});
		
		var $icon = jQuery("<img />",{
		
		id : 'loadingicon' ,
		src : ReturnLink('/css/fancybox_loading2.gif'),
                width:'16',
                height:'16'		
		}).hide().appendTo("body");
		var count = 0;
		
		var pi;
		
		var checkLoaded = function(){
			if($icon.get(0).complete!=true) return ;
			
			var stillmore = false;
			for(var i = 0; i < ImagesArr.length; i++ ){

				if( (ImagesArr[i].get(0).complete == true) && (checkFlag[i] == false) ){
					var $IA = ImagesArr[i];
					ImagesArr[i].css("visibility","visible").animate({opacity:1}, 250, function(){ //.delay((i+1)*200)
						if( $(this).parent().hasClass('added') ){
							$(this).unwrap();
						}else{
							$(this).parent().removeClass("preloader");
						}
					});
					checkFlag[i] = true;
				}

				if( checkFlag[i] == false ) stillmore = true;
			}

			if( stillmore == false){
				clearInterval(pi);
				$icon.remove();
			}
		};
		
		pi = setInterval(checkLoaded,500);
		
		var a = 1;

	};
})( jQuery );