(function($) {
	$.fn.mscroller = function(opt){
		var _cfg = {
			direction: 'left',
			speed: 25,
			behavior: 'scroll',
			delay: 0,
			//scrollamount: 1,
			mouseover: false,
			mouseout: false,
			onclick: false
		}
		if(opt) $.extend(_cfg,opt);
		var obj = this;
		var html = $(this).find('ul.scroller').html();
		
		
		var fn = {};
		// left / right
		var mainW = 0;
		$(obj).find('ul.scroller li').each(function(){
			mainW += $(this).outerWidth(true);
		});
		var w = $(obj).width();
		var nW = 1;
		if(mainW < w) nW = Math.ceil(w/mainW);
		var circle_horz = -1;
		var alternate_horz = -1;
		var slide_horz = -1;
		var scroll_horz  = -1;
		if(_cfg.direction=='right'){
			$(obj).find('ul.scroller').css({left: -1*mainW});
			slide_horz = 1;
			circle_horz = 1;
			scroll_horz = 1;
		}
		
		var circle_horz_loop = 0;
		this.circle_horz = function(){
			if(circle_horz_loop==0)	circle_horz_loop = mainW;
				
			$(obj).find('ul.scroller').css({'width': circle_horz_loop});
			var sLeft = $(obj).find('ul.scroller').css('left');
			if(sLeft == 'auto') sLeft = 0;
			
			if(Math.abs(parseInt(sLeft)) >= circle_horz_loop - w || parseInt(sLeft) ==0){
				if(circle_horz_loop <= nW*mainW){
					circle_horz_loop += mainW;
					$(obj).find('ul.scroller').css({'width': circle_horz_loop}).append(html);
				}else{
					$(obj).find('ul.scroller').css({left: -1*nW*mainW +(_cfg.direction=='right'?0:w)});
				}
			}else{
				$(obj).find('ul.scroller').css({left: parseInt(sLeft) + circle_horz});
			}
			
		}
		
		this.alternate_horz = function(){
			$(obj).find('ul.scroller').css({'width': mainW });
			var sLeft = $(obj).find('ul.scroller').css('left');
			if(sLeft == 'auto') sLeft = 0;
			if(Math.abs(parseInt(sLeft)) == mainW || parseInt(sLeft) == w){
				
				alternate_horz = -1*alternate_horz;
				$(obj).find('ul.scroller').css({left: parseInt(sLeft) + alternate_horz});
			}else{
				$(obj).find('ul.scroller').css({left: parseInt(sLeft) + alternate_horz});
			}
			
		}

		var slide_horz_loop = 0;
		this.slide_horz = function(){
			$(obj).find('ul.scroller').css({'width': mainW });
			var sLeft = $(obj).find('ul.scroller').css('left');
			if(sLeft == 'auto') sLeft = 0;
			if(Math.abs(parseInt(sLeft)) == mainW - w || parseInt(sLeft) == 0){
				if(slide_horz_loop) return true;
				$(obj).find('ul.scroller').css({left: parseInt(sLeft) + slide_horz});
				slide_horz_loop++;
			}else{
				$(obj).find('ul.scroller').css({left: parseInt(sLeft) + slide_horz});
			}
			
		}


		this.scroll_horz = function(){
			$(obj).find('ul.scroller').css({'width': mainW });
			var sLeft = $(obj).find('ul.scroller').css('left');
			if(sLeft == 'auto') sLeft = 0;
			if(Math.abs(parseInt(sLeft)) == mainW || parseInt(sLeft) == w){
				if(obj.delay(_cfg.delay)){
					//alternate_horz = -1*alternate_horz;
					var nLeft  = parseInt(sLeft) == w?-1*mainW+1:w-1;
					$(obj).find('ul.scroller').css({left: nLeft});
				}
			}else{
				$(obj).find('ul.scroller').css({left: parseInt(sLeft) + scroll_horz});
			}
			
		}

		// up // down
		var mainH = 0;
		$(obj).find('ul.scroller li').each(function(){
			mainH += $(this).outerHeight(true);
		});
		var h = $(obj).height();
		
		
		var nH = 1;
		if(mainH < h) nH = Math.ceil(h/mainH);
		
		
		var circle_vert = -1;
		var alternate_vert = -1;
		var slide_vert = -1;
		var scroll_vert = -1;
		if(_cfg.direction=='down'){
			$(obj).find('ul.scroller').css({top: -1*mainH});
			slide_vert = 1;
			circle_vert =1;
			scroll_vert = 1;
		}
		
		var circle_vert_loop = 0;
		this.circle_vert = function(){
			if(circle_vert_loop==0) circle_vert_loop = mainH;
			$(obj).find('ul.scroller').css({'height': circle_vert_loop });
			var sTop = $(obj).find('ul.scroller').css('top');
			if(sTop == 'auto') sTop = 0;
			if(circle_vert_loop  -  Math.abs(parseInt(sTop)) <= h || parseInt(sTop) ==0){
				if(circle_vert_loop <= nH*mainH){
					circle_vert_loop += mainH;
					$(obj).find('ul.scroller').css({'height': circle_vert_loop}).append(html);
				}else{
					$(obj).find('ul.scroller').css({top: -1*nH*mainH +(_cfg.direction=='down'?0:h)});
				}
			}else{
				
				$(obj).find('ul.scroller').css({top: parseInt(sTop) + circle_vert});
			}
		}


		
		

		this.alternate_vert = function(){
			$(obj).find('ul.scroller').css({'height': mainH });
			var sTop = $(obj).find('ul.scroller').css('top');
			if(sTop == 'auto') sTop = 0;
			if(Math.abs(parseInt(sTop)) == mainH || parseInt(sTop) == h){
				alternate_vert = -1*alternate_vert;
				$(obj).find('ul.scroller').css({top: parseInt(sTop) + alternate_vert});
			}else{
				$(obj).find('ul.scroller').css({top: parseInt(sTop) + alternate_vert});
			}
			
		}
		
		this.scroll_vert = function(){
			$(obj).find('ul.scroller').css({'height': mainH });
			var sTop = $(obj).find('ul.scroller').css('top');
			if(sTop == 'auto') sTop = 0;
			if(Math.abs(parseInt(sTop)) == mainH || parseInt(sTop) == h){
				if(obj.delay(_cfg.delay)){
					var nTop  = parseInt(sTop) == h?-1*mainH+1:h-1;
					$(obj).find('ul.scroller').css({top: nTop});
				}
			}else{
				$(obj).find('ul.scroller').css({top: parseInt(sTop) + scroll_vert});
			}
			
		}
		var slide_vert_loop = 0;
		this.slide_vert = function(){
			$(obj).find('ul.scroller').css({'height': mainH });
			var sTop = $(obj).find('ul.scroller').css('top');
			if(sTop == 'auto') sTop = 0;
			if(Math.abs(parseInt(sTop)) == mainH-h || parseInt(sTop) == 0){
				if(slide_vert_loop) return false;
				$(obj).find('ul.scroller').css({top: parseInt(sTop) + slide_vert});
				slide_vert_loop++;
			}else{
				$(obj).find('ul.scroller').css({top: parseInt(sTop) + slide_vert});
			}
			
		}

		this.stop = function(){
			clearInterval(MyMar);
		}
		
		this.start = function(){
			MyMar=setInterval(_run,_cfg.speed);
		}
		
		var delay = 0;
		this.delay = function(time){
			var t = _cfg.speed>0?Math.ceil(time/_cfg.speed):0;
			if(delay++ <= t) return false;
			delay  = 0;
			return true;
			
		}
		
		function _run(){
			if(_cfg.behavior == 'circle'){
				switch(_cfg.direction){
					case 'up': obj.circle_vert(); break;
					case 'down': obj.circle_vert(); break;
					case 'right': obj.circle_horz(); break;
					default: obj.circle_horz();break;
				}
			}else if(_cfg.behavior == 'alternate'){
				switch(_cfg.direction){
					case 'up': 
					case 'down': obj.alternate_vert(); break;
					case 'right': 
					default: obj.alternate_horz();break;
				}
			}else if(_cfg.behavior == 'slide'){
				switch(_cfg.direction){
					case 'up': 
					case 'down': obj.slide_vert(); break;
					case 'right': 
					default: obj.slide_horz();break;
				}
				
			}else{ // scroll
				switch(_cfg.direction){
					case 'up': 
					case 'down': obj.scroll_vert(); break;
					case 'right': 
					default: obj.scroll_horz();break;
				}
			}
		}
		
		
		var MyMar = setInterval(_run,_cfg.speed);
		
		if(typeof(_cfg.mouseover)=='function'){
			$(obj).mouseover(function(){ _cfg.mouseover(obj);});	
		}
		if(typeof(_cfg.mouseout)=='function'){
			$(obj).mouseout(function(){ _cfg.mouseout(obj);});	
		}
		if(typeof(_cfg.onclick)=='function'){
			$(obj).onclick(function(){ _cfg.onclick(obj);});	
		}

	}
})(jQuery);