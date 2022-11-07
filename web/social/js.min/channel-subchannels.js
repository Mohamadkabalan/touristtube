var contwidth = 952;
var spacecontwidth = 243;
var maxwidth =0;
var lastleft = 0;
var theleft = 0;
$(document).ready(function(){
    maxwidth = parseInt($(".subchannel_inside").css("width"));
    lastleft = maxwidth - contwidth;        
    theleft = parseInt($(".subchannel_inside").css("left"));
    initItemList();	
    reintialiseslider();
    initscrollPaneSubChannel($('.subchannel_item0') , false );
    $(document).on('click',".sub_items_not" ,function(){
        var $this = $(this);
        var $parent = $this.closest('.sub_items');
        if(parseInt($this.attr('data-notification'))== 1){
            $this.html(t('get notifications'));
            $this.attr('data-notification', 0);
            updateNotificationDataCH($parent, 'stopNotifications');			
        }else{
            $this.html(t('stop notifications'));
            $this.attr('data-notification', 1);
            updateNotificationDataCH($parent, 'getNotifications');			
        }
    });
    $(document).on('click',".next_media_page" ,function(){
        if($(this).hasClass('inactive')) return;
        maxwidth = parseInt($(".subchannel_inside").css("width"));
        lastleft = maxwidth - contwidth; 
        var theanimate = 0;
        if(theleft <= -lastleft){
            theanimate = -lastleft;
        }
        else if( (theleft-spacecontwidth) <= -lastleft){
            theanimate = -lastleft;
        }
        else{
            theanimate = theleft - spacecontwidth;
        }
        $('.prev_media_page').removeClass('inactive');
        if( (theanimate-contwidth-30)<=-maxwidth){
           theanimate = -lastleft;
            $('.next_media_page').addClass('inactive');
        }
        $('.subchannel_inside').stop().animate({left: theanimate}, "6000");
        theleft = theanimate;
    });  
    $(document).on('click',".prev_media_page" ,function(){
        if($(this).hasClass('inactive')) return;
        maxwidth = parseInt($(".subchannel_inside").css("width"));
        lastleft = maxwidth - contwidth; 
        var theanimate = 0;
        if(theleft >= 0){
            theanimate = 0;
        }
        else if( (theleft+spacecontwidth) >= 0){
            theanimate = 0;
        }
        else{
            theanimate = theleft + spacecontwidth;
        }   
        $('.next_media_page').removeClass('inactive');        
        if(theanimate>=-30){
            theanimate =0;
            $('.prev_media_page').addClass('inactive');
        }
        $('.subchannel_inside').stop().animate({left: theanimate}, "6000");
        theleft = theanimate;
    });
    $(document).on('click',".go_sub_items_but" ,function(){
        var $parent=$(this).closest('.sub_items');
        var data_id = $parent.attr('data-id');
        var data_level = $parent.closest('.subchannel_curent').attr('data-level');
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            type: "POST",
            url: ReturnLink("/ajax/channel_sub_list.php"),
            data: {id:data_id,data_level:data_level,parent_id:channelGlobalID()},
            success: function(html) {
                $('.upload-overlay-loading-fix').hide();
                var ret = null;
                try {
                    ret = $.parseJSON(html);
                } catch (Ex) {
                    return;
                }
                $parent.closest('.subchannel_curent').find('.sub_items').removeClass('active');
                $parent.addClass('active');
                var realdata_level = parseInt(ret.data_level);
                $('.subchannel_curent').each(function(){
                    var $this=$(this);
                    var $thisdata_level = parseInt($this.attr('data-level'));
                    if($thisdata_level>=realdata_level){
                        $this.remove();
                    }
                });                
                $('.subchannel_inside').append(ret.data);
                var ww= ($('.subchannel_levels').length * 243) + 223;
                $('.subchannel_inside').css('width',ww+'px');
                var newleft =0;
                if(ww>952){
                    newleft = -(ww-952);
                    $('.next_media_page').addClass('inactive');
                    $('.prev_media_page').removeClass('inactive');
                    $('.prev_next_container').show();
                }else{
                    $('.prev_next_container').hide();
                }
                $('.subchannel_inside').css('left',newleft+'px');
                theleft = parseInt($(".subchannel_inside").css("left"));
                initscrollPaneSubChannel($('#subchannel_levels_content'+ret.data_level) , false );
            }
        });
    });
    $(document).on('click',".hide_sub_items_but" ,function(){
            var $parent=$(this).closest('.sub_items');
            var data_id = $parent.attr('data-id');
            var alrt = "<b>"+$parent.find('.sub_items_title').attr('title')+"</b>";
            TTAlert({
                msg:  sprintf( t("Are you sure you want to remove the subchannel %s ?")  , [alrt]) ,
                type: 'action',
                btn1: t('cancel'),
                btn2: t('confirm'),
                btn2Callback: function(data) {
                    if (data) {
                        $('.upload-overlay-loading-fix').show();
                        TTCallAPI({
                            what: 'channel/relation/delete',
                            data: {parent_id:channelGlobalID(),channel_id:data_id,relation_type:CHANNEL_RELATION_TYPE_SUB},
                            callback: function(ret){  
                                $('.upload-overlay-loading-fix').hide();
                                if( ret.status === 'error' ){
                                    TTAlert({
                                        msg: ret.msg,
                                        type: 'alert',
                                        btn1: t('ok'),
                                        btn2: '',
                                        btn2Callback: null
                                    });
                                    return;
                                }
                                document.location.reload();
                            }
                        });
                    }
                }
            });            
	});
});
function checkSubmitSearch(e){
   if(e && e.keyCode == 13){
      $('.searchBtn_events').click();
   }
}

function initItemList(){
    $(".itemList").each(function(){
        var $this = $(this);
        $this.on('mouseenter',function(){
            $this.find('.hideDivClass').show();
            $this.find('.itemList_right_buttons').show();
        }).on('mouseleave',function(){
            $this.find('.hideDivClass').hide();
            $this.find('.itemList_right_buttons').hide();
        });
    });
}
function initscrollPaneSubChannel(obj,_flag) {    
    try{
       if( (parseInt(obj.height()) -3) >obj.css('min-height').replace('px', '') ){
           obj.css('max-height',obj.css('min-height'));
            obj.jScrollPane();
            if(_flag){
                var jscrol_api = obj.data('jsp');
                jscrol_api.scrollToBottom(true);            
            }
        }
    }catch(e){
        obj.css('max-height',obj.css('min-height'));
        obj.jScrollPane();
        if(_flag){
            try{
                var jscrol_api = obj.data('jsp');
                jscrol_api.scrollToBottom(true); 
            }catch(e){
                
            }
        }
    }  
}
function updateNotificationDataCH(obj, action){
    var chid=obj.attr('data-id');
    $('.upload-overlay-loading-fix').show();
    $.ajax({
        url: ReturnLink('/ajax/info_not_relation_update.php'),
        data: {action:action,channel_id:chid,parent_id:channelGlobalID()},
        type: 'post',
        success: function(data){
            $('.upload-overlay-loading-fix').hide();
        }
    });	
}