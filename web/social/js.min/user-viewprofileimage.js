 $( document ).ready(function(){
    if( $('.btn_likes').length>0 || $('.btn_comments').length>0 ){
        initSocialActions();    
        if( $('.btn_likes').length>0 ){
            initLikes( $(".social_data_all") );
        }
        if( $('.btn_comments').length>0 ){
            initComments( $(".social_data_all") );
        }
        if( $('.btn_likes').length>0 ){
           $('#likes').click(); 
        }else if( $('.btn_comments').length>0 ){
           $('#comments').click(); 
        }
    }
    //
    $(document).on('click', ".viewPicsBut", function() {
        var $This = $(this);            
        var data_href = $This.attr('data-href');
        parent.document.location.href = data_href;
    });
    $(document).keydown(function(ev) {            
        if(ev.keyCode == 39) {
            if( !$('.next_div').hasClass('inactive') ){
                var mlk=$('.next_div').parent().attr('href');
                document.location.href = mlk;
            }
            return false; 
        } else if(ev.keyCode == 37) {
            if( !$('.prev_div').hasClass('inactive') ){
                var mlk=$('.prev_div').parent().attr('href');
                document.location.href = mlk;
            }
            return false;
        }
    });
});