// JavaScript Document
var previousTuber = $(".one-tuber");
var t;
$(document).ready(function () {
    InitTuberDocument();
    
    $(document).on('click', ".social_link_a_inactive", function(event) {
        event.preventDefault();
        TTAlert({
            msg: $.i18n._('you have to sign in, in order to access a tuber page'),
            type: 'action',
            btn1: t('cancel'),
            btn2: 'register',
            btn2Callback: function(data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                }
            }
        });
    });
    $(document).on('click','#allTubers .one-tuber div.AddFriendButton',function(){
            var id = $(this).attr('rel');
            var curobj=$(this);		
            $.ajax({
                    url: ReturnLink('/ajax/tuber_friend_request.php'),
                    data: {id: id},
                    type: 'post',
                    success: function(data){
                            var jres = null;
                            try{
                                    jres = $.parseJSON( data );
                            }catch(Ex){

                            }
                            if( !jres ){
                                    TTAlert({
                                            msg: t("Couldn't Process Request. Please try again later."),
                                            type: 'alert',
                                            btn1: t('ok'),
                                            btn2: '',
                                            btn2Callback: null
                                    });
                                    return ;
                            }

                            TTAlert({
                                    msg: jres.msg,
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                            });
                            curobj.removeClass('AddFriendButton');
                            curobj.addClass('RemoveFriendButton');
                            curobj.attr('data-title',t('remove friend'));
                            $('.ProfileTuberOver .ProfileHeaderOverin').html(curobj.attr('data-title'));				
                    }
            });

    });
    $(document).on('click','#allTubers .one-tuber div.RemoveFriendButton',function(){
            var id = $(this).attr('rel');
            var curobj=$(this);

            TTAlert({
                    msg: t('confirm to remove permanently selected friend'),
                    type: 'action',
                    btn1: t('cancel'),
                    btn2: t('confirm'),
                    btn2Callback: function(data){
                            if(data){
                                    $.post(ReturnLink('/ajax/ajax_rejectprofilefriend.php'), {fid:id},function(data){
                                            curobj.removeClass('RemoveFriendButton');
                                            curobj.addClass('AddFriendButton');
                                            curobj.attr('data-title',t( 'add as a friend' ));
                                            $('.ProfileTuberOver .ProfileHeaderOverin').html(curobj.attr('data-title'));					
                                    });	
                            }
                    }
            });
    });

    $(document).on('click','#allTubers .one-tuber div.unFollowFriendButton',function(){
            var id = $(this).attr('rel');
            var curobj=$(this);
            var target=$(this).parent().parent();
            var myname=""+target.attr('data-name');	

            TTAlert({
                    msg: sprintf( t( 'confirm to unfollow %s') ,[myname] ),
                    type: 'action',
                    btn1: t('cancel'),
                    btn2: t('confirm'),
                    btn2Callback: function(data){
                            if(data){
                                    $.ajax({
                                            url: ReturnLink('/ajax/profile_feed_unfollow.php'),
                                            data: {feed_user_id: id},
                                            type: 'post',
                                            success: function(data){
                                                    var jres = null;
                                                    try{
                                                            jres = $.parseJSON( data );
                                                    }catch(Ex){

                                                    }
                                                    if( !jres ){
                                                            TTAlert({
                                                                    msg: t("Couldn't Process Request. Please try again later."),
                                                                    type: 'alert',
                                                                    btn1: t('ok'),
                                                                    btn2: '',
                                                                    btn2Callback: null
                                                            });
                                                            return ;
                                                    }
                                                    curobj.removeClass('unFollowFriendButton');
                                                    curobj.addClass('FollowFriendButton');
                                                    curobj.attr('data-title', t('follow') );
                                                    $('.ProfileTuberOver .ProfileHeaderOverin').html(curobj.attr('data-title'));

                                            }
                                    });	
                            }
                    }
            });
    });
    $(document).on('click','#allTubers .one-tuber div.FollowFriendButton',function(){

            var id = $(this).attr('rel');
            var curobj=$(this);
            $.ajax({
                    url: ReturnLink('/ajax/subscribe.php'),
                    data: {id: id},
                    type: 'post',
                    success: function(data){
                            var jres = null;
                            try{
                                    jres = $.parseJSON( data );
                            }catch(Ex){

                            }
                            if( !jres ){
                                    TTAlert({
                                            msg: t("Couldn't Process Request. Please try again later."),
                                            type: 'alert',
                                            btn1: t('ok'),
                                            btn2: '',
                                            btn2Callback: null
                                    });
                                    return ;
                            }						
                            curobj.removeClass('FollowFriendButton');
                            curobj.addClass('unFollowFriendButton');
                            curobj.attr('data-title',t('unfollow'));
                            $('.ProfileTuberOver .ProfileHeaderOverin').html(curobj.attr('data-title'));

                    }
            });
    });
    
    $('#loadmore').click(function(){
        $.ajax({
            url: $('#page_nav a').attr('href'),
            type: 'post',
            success: function(data){
                var $newEls = $( data );
                $('.container_tuber').find('.userscounthide').remove();
                $('.container_tuber').append( $newEls );
                if($('.container_tuber').find('.userscounthide').attr('data-value')=="true"){
                    $('#loadmore').hide();
                }
                TuberPage++;
                $('#page_nav a').attr('href', ReturnLink('/parts/tubers_results.php?ss='+TuberSearchString+'&page='+TuberPage) ); 
                InitTuberDocument();
            }
       });

   });
});
function InitTuberDocument() {
    $('.container_tuber').find('.userscounthide').remove();
    $(".one-tuber .requestinvite").each(function() {
        var $this = $(this);
        var popUp = $this.parent().parent().find(".popUp");
        $this.mouseover(function() { 
            var thisIndex = $this.parent().parent().index();
            var popclass = 'popUpLeft';            
            if (((thisIndex + 1) % 4) == 0 ) {
                popclass = 'popUpRight';
            }
            popUp.removeClass('popUpLeft');
            popUp.removeClass('popUpRight');
            popUp.addClass(popclass);
            $(".popUp").hide();
            popUp.show();
        });
        $this.mouseout(function() {           
            popUp.removeClass('popUpLeft');
            popUp.removeClass('popUpRight');
            $(".popUp").hide();
        });
    });
    $(".imgTuberGrid a").each(function(){
        var $this = $(this);
        $this.mouseover(function(){
            $(".imgTuberGrid a").removeClass("hover");
            $this.addClass("hover");
        });
        $this.mouseout(function(){
            $this.removeClass("hover");
        });
    });
    $('.swapimage').each(function(){
        $(this).mouseover(function(){
            var posxx=$(this).offset().left-$('.ProfileTuberOver').parent().offset().left-254;
            var posyy=$(this).offset().top-$('.ProfileTuberOver').parent().offset().top-23;
            $('.ProfileTuberOver .ProfileHeaderOverin').html($(this).attr('data-title'));
            $('.ProfileTuberOver').css('left',posxx+'px');
            $('.ProfileTuberOver').css('top',posyy+'px');
            $('.ProfileTuberOver').show();
        });
        $(this).mouseout(function(){
            $('.ProfileTuberOver').hide();
        });
    });
}
