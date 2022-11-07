var currentpage_photo = 0;
var currentpage_video = 0;
$(document).ready(function() {        
    if( $('.visited_places_part2_hotels').length>0 ){
        var len = $('.visited_places_part2_hotels .visited_places_part2_item').length;
        $('.visited_places_part2_hotels .visited_places_part2_content').css('width', (len*340)+'px');
        if(len>3) $('.visited_places_part2_hotels .next_btn_discover').removeClass('inactive');
    }
    if( $('.visited_places_part2_restaurants').length>0 ){
        var len = $('.visited_places_part2_restaurants .visited_places_part2_item').length;
        $('.visited_places_part2_restaurants .visited_places_part2_content').css('width', (len*340)+'px');
        if(len>3) $('.visited_places_part2_restaurants .next_btn_discover').removeClass('inactive');
    }
    if( $('.visited_places_part2_pois').length>0 ){
        var len = $('.visited_places_part2_pois .visited_places_part2_item').length;
        $('.visited_places_part2_pois .visited_places_part2_content').css('width', (len*340)+'px');
        if(len>3) $('.visited_places_part2_pois .next_btn_discover').removeClass('inactive');
    }
    var contwidth = 956+64;
    $(document).on('click',".next_btn_discover" ,function(){
        if( $(this).hasClass('inactive') ) return;
        var parents = $(this).closest('.visited_places_part2');
        var maxwidth = parseInt(parents.find('.visited_places_part2_content').css("width"));
        var lastleft = maxwidth - contwidth;
        var theanimate = 0;
        var theleft = parseInt(parents.attr('data-left')); 
        if(theleft <= -lastleft){
            theanimate = -lastleft;
        }
        else if( (theleft-contwidth) <= -lastleft){
            theanimate = -lastleft;
        }
        else{
            theanimate = theleft - contwidth;
        }
        parents.find('.prev_btn_discover').removeClass('inactive');
        if( (theanimate-contwidth-100)<=-maxwidth){
           theanimate = -lastleft;
            $(this).addClass('inactive');
        }
        parents.find('.visited_places_part2_content').stop().animate({left: theanimate}, "2000");
        parents.attr('data-left',theanimate);
    });
    $(document).on('click',".prev_btn_discover" ,function(){
        if( $(this).hasClass('inactive') ) return;
        var parents = $(this).closest('.visited_places_part2');
        var maxwidth = parseInt(parents.find('.visited_places_part2_content').css("width"));
        var lastleft = maxwidth - contwidth;
        var theanimate = 0;
        var theleft = parseInt(parents.attr('data-left')); 
        if(theleft >= 0){
            theanimate = 0;
        }
        else if( (theleft+contwidth) >= 0){
            theanimate = 0;
        }
        else{
            theanimate = theleft + contwidth;
        }   
        parents.find('.next_btn_discover').removeClass('inactive');        
        if(theanimate>=-100){
            theanimate =0;
            $(this).addClass('inactive');
        }
        parents.find('.visited_places_part2_content').stop().animate({left: theanimate}, "2000");
        parents.attr('data-left',theanimate);
    });
    $(document).on('click',".next_btn_photo" ,function(){
        if( $(this).hasClass('inactive') ) return;
        currentpage_photo++;
        $('.prev_btn_photo').removeClass('inactive');
        displayPhotoData($(this),'i',currentpage_photo);
    });
    $(document).on('click',".prev_btn_photo" ,function(){
        if( $(this).hasClass('inactive') ) return;
        currentpage_photo--;
        if( currentpage_photo==0 ){
            $(this).addClass('inactive');
        }
        $('.next_btn_photo').removeClass('inactive');
        displayPhotoData($(this),'i',currentpage_photo);
    });
    $(document).on('click',".next_btn_video" ,function(){
        if( $(this).hasClass('inactive') ) return;
        currentpage_video++;
        $('.prev_btn_video').removeClass('inactive');
        displayPhotoData($(this),'v',currentpage_video);
    });
    $(document).on('click',".prev_btn_video" ,function(){
        if( $(this).hasClass('inactive') ) return;
        currentpage_video--;
        if( currentpage_video==0 ){
            $(this).addClass('inactive');
        }
        $('.next_btn_video').removeClass('inactive');
        displayPhotoData($(this),'v',currentpage_video);
    });
    $(document).on('click',".peoplesdataclose" ,function(){
        var parents=$(this).parent();
        var parents_all=parents.parent().parent();
        parents.remove();
        if (parents.attr('data-id') != '') {
            SelectedUsersDelete(parents.attr('data-id'), parents_all.find('.addmore input'));
        }

        if(parents.attr('id')=='friendsdata'){
            parents_all.find('.uploadinfocheckbox3').removeClass('active');
        }else if(parents.attr('id')=='followersdata'){
            parents_all.find('.uploadinfocheckbox4').removeClass('active');
        }else if (parents.attr('id') == 'friends_of_friends_data') {
            parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
        }
    });
    $(document).on('click',".sharepopup_butBRCancel" ,function(){		
            $('.fancybox-close').click();
    });
    $(document).on('click',"#share_popup_but" ,function(){
            var $this=$(this);
            var $parent=$this.parent().parent();
            var invite_msg = getObjectData($parent.find("#invitetext"));
            var inviteArray=new Array();
            $parent.find('.peoplecontainer .emailcontainer .peoplesdata').each(function(){
                var obj=$(this);
                if(obj.attr('id')=="friendsdata"){
                        inviteArray.push( {friends : 1} );
                }else if(obj.attr('id')=="followersdata"){
                        inviteArray.push( {followers : 1} );
                }else if(obj.attr('id')=="option_6"){
                        inviteArray.push( {connections : 1} );
                }else if( obj.attr('data-email') != '' ){
                        inviteArray.push( {email : obj.attr('data-email') } );
                }else if( parseInt( obj.attr('data-id') ) != 0 ){
                        inviteArray.push( {id : obj.attr('data-id') } );
                }
            });
            if(inviteArray.length==0){
                    return;	
            }
            var data_real = $('#right_container').attr('data-real');
            if(data_real=='') window.location.reload();
            TTCallAPI({
                    what: 'social/share',
                    data: {entity_type:SOCIAL_ENTITY_VISITED_PLACES,real_user:data_real, entity_id:$parent.attr('data-id'), share_with : inviteArray, share_type: SOCIAL_SHARE_TYPE_SHARE , msg:invite_msg, channel_id:null,addToFeeds:1},
                    callback: function(ret){
                            closeFancyBox();
                    }
            });
    });
    $(document).on('click',".uploadinfocheckbox" ,function(){
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });
    
});

function displayPhotoData(obj,_type,_page){
    var parents = obj.closest('.visited_places_media_holder');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/ajax_visited_media.php'),
        data: {page:_page,data_profile:userGlobalID() , city_id:city_id,type:_type},
        type: 'post',
        success: function(data){
            var ret = null;
            try{
                    ret = $.parseJSON(data);
            }catch(Ex){
                $('.upload-overlay-loading-fix').hide();
                    return ;
            }
            if(ret.error){
                $('.upload-overlay-loading-fix').hide();
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            }else{
                var myData = ret.data;
                var data_count = ret.count;
                var firstobj=parents.find('.visited_places_media_content');
                parents.find('.visited_places_media_container').append(myData);
                
                var secondobj=parents.find('.visited_places_media_content_new');
                var seconda=parents.find('.visited_places_media_content_new .visited_places_media_item');
                if( obj.hasClass('next_btn_photo') || obj.hasClass('next_btn_video') ){
                    if( data_count - (_page * 12) > 12 ){
                        obj.removeClass('inactive');
                    }else{
                        obj.addClass('inactive');
                    }
                }
                if(seconda.length>0){
                    var firstobj_left = '-420px';
                    var secondobj_left = '0px';
                    if( obj.hasClass('prev_btn_photo') || obj.hasClass('prev_btn_video') ){
                        firstobj_left = '420px';
                        secondobj.css('left','-420px');
                    }
                    firstobj.animate({'left':firstobj_left},500,function(){
                            firstobj.remove();
                    });
                    secondobj.animate({'left':secondobj_left},500,function(){
                        secondobj.removeClass('visited_places_media_content_new');
                        $('.upload-overlay-loading-fix').hide();
                    });
                }else{
                    obj.addClass('inactive');
                    if(_type=="i"){
                        currentpage_photo--;
                    }else{
                       currentpage_video--; 
                    }                    
                    $('.upload-overlay-loading-fix').hide();
                }
            }
        }
    });
}