$(document).ready(function(){
    setContainerWW();
    $(window).resize(function () {
        setContainerWW();
    });
    
   
  $("span.xstyle").click(function(){
      $("#deletablelocation").hide();
     });
     
     
     $("#removeimg").click(function(){
      $("#first-image").hide();
     });
 });
 
  
 
function setContainerWW(){
    if($(window).width()>=896){
        var ww = $(window).width() - $('.smallcontainer').width() - 30;
        $('.bigcontainerleft').css('width',ww+'px');
    }
}

