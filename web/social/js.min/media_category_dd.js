// for category show hide

$(document).ready(function(){
    $("#CategoriesTTL, #CategoriesTTL_search, .channelyellowttl").click(function(){
        var wid =   jQuery(window).width();
        if(wid>960)
            return false;        
        $("#Categories").slideToggle();
    });   
});



// for loaing div 
/*
jQuery.noConflict();
$(document).ready(function(){
    $("#SignInBtn").click(function(){
		var wid =   jQuery(window).width();
         if(wid>760)
         return false;  
      	$("#SignInDiv").css({"opacity":"1","background":"red"});
    });
});
*/
  



// for Most Active Tourist Tubers
/*
$(document).ready(function(){
    $("#TouristTubersTTL").click(function(){
        var wid =   jQuery(window).width();
        if(wid>760 )
        return false;   			     
        $("#TouristTubersList").slideToggle();
		
     });
});
*/

	



$(document).ready(function(){
   var wid =   jQuery(window).width();
        if(wid>960 )
     return false;  
  if (navigator.msMaxTouchPoints) {
   $('.sliderGallery').addClass('ms-touch');
   $('.slide-image').on('scroll', function() {
    //$('.slide-image').css('transform','translate3d(-' + (100-$(this).scrollLeft()/6) + 'px)');
	$('.IndexRecentThumb').css('transform','translate3d(-' + (100-$(this).scrollLeft()/6) + 'px)');
  });

} else {
  var slider = {
    el: {
      slider: $(".slide-image"),
      holder: $(".items"),
      imgSlide: $(".IndexRecentThumb")
    },

    slideWidth: $('.imageitem').width(),
    touchstartx: undefined,
    touchmovex: undefined,
    movex: undefined,
    index: 0,
    longTouch: undefined,    
    init: function() {
      this.bindUIEvents();
    },

    bindUIEvents: function() {

      this.el.holder.on("touchstart", function(event) {
        slider.start(event);
      });

      this.el.holder.on("touchmove", function(event) {
        slider.move(event);
      });

      this.el.holder.on("touchend", function(event) {
        slider.end(event);
      });

    },

    start: function(event) {
      // Test for flick.
      this.longTouch = false;
      setTimeout(function() {
        window.slider.longTouch = true;
      }, 100000);

      // Get the original touch position.
      this.touchstartx =  event.originalEvent.touches[0].pageX;

      // The movement gets all janky if there's a transition on the elements.
      $('.animate').removeClass('animate');
    },

    move: function(event) {
      // Continuously return touch position.
      this.touchmovex =  event.originalEvent.touches[0].pageX;
      // Calculate distance to translate holder.
      this.movex = this.index*this.slideWidth + (this.touchstartx - this.touchmovex);
      // Defines the speed the images should move at.
      // var panx = 100-this.movex/6;
	   var panx =this.movex;      
	  //if (this.movex < 600) { // Makes the holder stop moving when there is no more content.
	  if (this.movex < 20) { // Makes the holder stop moving when there is no more content.
      //  this.el.holder.css('transform','translate3d(-' + this.movex + 'px,0,0)');
	   this.el.holder.css('transform','translate3d()');
      }
    //if (panx < 100) { // Corrects an edge-case problem where the background image moves without the container moving.
    //   this.el.imgSlide.css('transform','translate3d(-' + panx + 'px,0,0)');
     if (panx < 10) { // Corrects an edge-case problem where the background image moves without the container moving.
        this.el.imgSlide.css('transform','translate3d()');

      }
    },

    end: function(event) {
      // Calculate the distance swiped.
      //var absMove = Math.abs(this.index*this.slideWidth - this.movex);
	  var absMove = Math.abs(this.index*this.slideWidth);
      // Calculate the index. All other calculations are based on the index.
      if (absMove > this.slideWidth || this.longTouch === false) {
        // if (this.movex > this.index*this.slideWidth && this.index < 8) {
        if (this.movex > this.index*this.slideWidth  ) {
          this.index++;
        //} else if (this.movex < this.index*this.slideWidth && this.index > 0) {
	    } else if (this.movex < this.index*this.slideWidth) {
          this.index--;
        }
      }      
      // Move and animate the elements.
	  this.el.holder.addClass('animate').css('transform', 'translate3d(-' + this.index*this.slideWidth + 'px,0,0)');
      this.el.imgSlide.addClass('animate').css('transform', 'translate3d(-' + 100-this.index*50 + 'px,0,0)');
     // this.el.holder.addClass('animate').css('transform', 'translate3d()');
     // this.el.imgSlide.addClass('animate').css('transform', 'translate3d()');

    }

  };

  slider.init();
}		


// make sliding on click and slide event for mobile / tablet

/*
$(document).ready(function(){
	  	 var wid =   jQuery(window).width();
        if(wid>960 )
        return false;  
		     $("#sliderA").excoloSlider();
		});
   
*/

/*
$(document).ready(function(){
	  var swiper = new Swiper('.swiper-container');
});


$('.sliderGallery').addClass('ms-touch');
  $('.sliderGallery').on('scroll', function() {
	 $('.slide-image').css('transform','translate3d(-' + (10-$(this).scrollLeft()) + 'px,0,0)');
  });
}

 else {
    var slider = {
    el: {
      slider: $(".sliderGallery"),
      holder: $(".items"),
      imgSlide: $(".imageitem")
    },
    slideWidth: $('.sliderGallery').width(),
*/

	
	
/*
 if (navigator.msMaxTouchPoints) {
 var wid =   jQuery(window).width();
      if(wid>960 )
      return false; 	   
 
  $('.sliderGallery').addClass('ms-touch');
  $('.sliderGallery').on('scroll', function() {
	 $('.slide-image').css('transform','translate3d(-' + (10-$(this).scrollLeft()) + 'px,0,0)');
  });
}

 else {
    var slider = {
    el: {
      slider: $(".sliderGallery"),
      holder: $(".items"),
      imgSlide: $(".imageitem")
    },
    slideWidth: $('.sliderGallery').width(),
    touchstartx: undefined,
    touchmovex: undefined,
    movex: undefined,
    index: 0,
    longTouch: undefined,
    
    init: function() {
      this.bindUIEvents();
    },

    bindUIEvents: function() {

      this.el.holder.on("touchstart", function(event) {
        slider.start(event);
      });

      this.el.holder.on("touchmove", function(event) {
        slider.move(event);
      });

      this.el.holder.on("touchend", function(event) {
        slider.end(event);
      });

    },

    start: function(event) {
      // Test for flick.
      this.longTouch = false;
      setTimeout(function() {
        window.slider.longTouch = true;
      }, 1000);

      // Get the original touch position.
      this.touchstartx =  event.originalEvent.touches[0].pageX;

      // The movement gets all janky if there's a transition on the elements.
      $('.animate').removeClass('animate');
    },

    move: function(event) {
      // Continuously return touch position.
      this.touchmovex =  event.originalEvent.touches[0].pageX;
      // Calculate distance to translate holder.
      this.movex = this.index*this.slideWidth + (this.touchstartx - this.touchmovex);
      // Defines the speed the images should move at.
      var panx =10-this.movex;
      if (this.movex <20) { // Makes the holder stop moving when there is no more content.
        this.el.holder.css('transform','translate3d(-' + this.movex + 'px,0,0)');
      }
      if (panx < 10) { // Corrects an edge-case problem where the background image moves without the container moving.
        this.el.imgSlide.css('transform','translate3d(-' + panx + 'px,0,0)');
      }
    },

    end: function(event) {
      // Calculate the distance swiped.
      var absMove = Math.abs(this.index*this.slideWidth - this.movex);
      // Calculate the index. All other calculations are based on the index.
      if (absMove > this.slideWidth || this.longTouch === false) {
        if (this.movex > this.index*this.slideWidth)
		 {
          this.index++;
        } else if (this.movex < this.index*this.slideWidth && this.index > 0) {
          this.index--;
        }
      }      
      // Move and animate the elements.
      this.el.holder.addClass('animate').css('transform', 'translate3d(-' + this.index*this.slideWidth + 'px,0,0)');
      this.el.imgSlide.addClass('animate').css('transform', 'translate3d(-' + 10-this.index*1 + 'px,0,0)');
   }
};
  slider.init();
}*/

});



/*----------------------------------------------------------------------*/
