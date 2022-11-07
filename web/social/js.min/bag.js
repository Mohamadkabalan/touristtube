$(document).ready(function(){
    if($('#addmoretext_privacy').length>0){
        addmoreusersautocomplete_custom_journal( $('#addmoretext_privacy') );
    }
    $(document).on('click',".peoplesdataclose" ,function(){
        var parents=$(this).parent();
        var parents_all=parents.parent().parent();
        parents.remove();
        if(parents.attr('data-id')!=''){
            SelectedUsersDelete(parents.attr('data-id'),parents_all.find('.addmore input'));
        }
        if(parents.attr('id')=='friendsdata'){
            parents_all.find('.uploadinfocheckbox3').removeClass('active');
        }else if(parents.attr('id')=='followersdata'){
            parents_all.find('.uploadinfocheckbox4').removeClass('active');
        }else if(parents.attr('id')=='connectionsdata'){
            parents_all.find('.uploadinfocheckbox5').removeClass('active');
        }else if(parents.attr('id')=='sponsorssdata'){
            parents_all.find('.uploadinfocheckbox6').removeClass('active');
        }else if(parents.attr('id')=='friends_of_friends_data'){
            parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
        }
    });
    $(document).on('click',".uploadinfocheckbox" ,function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            if($(this).hasClass('uploadinfocheckbox3')){
                if($(this).parent().hasClass("friendscontainer")){
                    $(this).parent().parent().find('#friendsdata').remove();
                }
            }else if($(this).hasClass('uploadinfocheckbox_friends_of_friends')){
                if($(this).parent().hasClass("friendscontainer")){
                    $(this).parent().parent().find('#friends_of_friends_data').remove();
                }
            }else if($(this).hasClass('uploadinfocheckbox4')){
                if($(this).parent().hasClass("friendscontainer")){
                    $(this).parent().parent().find('#followersdata').remove();
                }
            }
        }else{
            $(this).addClass('active');
            if($(this).hasClass('uploadinfocheckbox3')){
                if($(this).parent().hasClass("friendscontainer")){
                    var friendstr='<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(friendstr);
                }
            }else if($(this).hasClass('uploadinfocheckbox_friends_of_friends')){
                if($(this).parent().hasClass("friendscontainer")){
                    var followerstr='<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends of friends")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                }
            }else if($(this).hasClass('uploadinfocheckbox4')){
                if($(this).parent().hasClass("friendscontainer")){
                    var followerstr='<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("followers")+'</div><div class="peoplesdataclose"></div></div>';
                    $(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
                }
            }
        }
    });
    $(document).on('click',".tt-sprites.i-close" ,function(){
        var $parent = $(this).closest('.section_group_col1');
        var id = $(this).attr('data-id');
        TTCallAPI({
            what: '/user/bag/delete',
            data: {id : id },
            callback: function(resp){
                if( resp.status === 'error' ){
                    TTAlert({
                        msg: resp.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                var userBagItemsCount = parseInt($(".tt-bag-num").attr('data-count'))-1;
                if(userBagItemsCount==0){
                    document.location.reload();
                }else{
                    $(".tt-bag-num").html(userBagItemsCount);
                    $(".tt-bag-num").attr('data-count',userBagItemsCount);
                    $parent.remove();
                }
            }
        });
    });
    $(document).on('click','.account-btn-cancel',function(){
        $('.fancybox-close').click();
    });
    $(document).on('click',"#saveabout" ,function(){
        $('.sharePopUpRapper_error').html('');
        var bagId = $('.section_group_buts').attr('data-id');
        if(bagId === '0'){                
            $('.sharePopUpRapper_error').html(t('You have to select a bag'));
            return;
        }
        var $this=$(this);
        var $parent=$this.parent().parent();
        var invite_msg = $parent.find(".sharePopUpTextArea").val();
        var inviteArray=new Array();
        $parent.find('.peoplecontainer .peoplesdata').each(function(){
                var obj=$(this);
                if(obj.attr('id')=="friendsdata"){
                        inviteArray.push( {friends : 1} );
                }else if(obj.attr('id')=="followersdata"){
                        inviteArray.push( {followers : 1} );
                }else if(obj.attr('id')=="friends_of_friends_data"){
                        inviteArray.push( {friendsandfollowers : 1} );
                }else if( obj.attr('data-email') != '' ){
                        inviteArray.push( {email : obj.attr('data-email') } );
                }else if( parseInt( obj.attr('data-id') ) != 0 ){
                        inviteArray.push( {id : obj.attr('data-id') } );
                }
        });
        if(inviteArray.length==0){
            $('.sharePopUpRapper_error').html(t('Invalid share data'));
            return;
        }
        TTCallAPI({
            what: 'social/share',
            data: {entity_type:SOCIAL_ENTITY_BAG, entity_id: bagId, share_with : inviteArray, share_type: SOCIAL_SHARE_TYPE_SHARE , msg:invite_msg, channel_id:null,addToFeeds:1},
            callback: function(ret){
                 $('.fancybox-close').click();
            }
        });
    });
    $(".share_bagdata").fancybox({
        padding	:0,
        margin	:0,
        beforeLoad:function(){	
            resetSelectedUsers();
            $('#sharePopUp .peoplecontainer .peoplesdata').each(function(){                
                $(this).remove();
            });
            $('#sharePopUp .uploadinfocheckbox').removeClass('active');
        }
    });
    
    var pagedimensions = parent.window.returnIframeDimensions();
    $(".showOnMap").fancybox({
        width: pagedimensions[0],
        height: pagedimensions[1],
        closeBtn: true,
        autoSize: false,
        autoScale: true,
        transitionIn: 'elastic',
        transitionOut: 'fadeOut',
        type: 'iframe',
        padding: 0,
        margin: 0,
        scrolling: 'no',
        helpers : {
            overlay : {closeClick:true}
        }
    });
    $(document).on('click',".remove_bag" ,function(){
        var $value=$(this).closest('.section_group_buts').attr('data-id');
        $.post(ReturnLink('/ajax/ajax_remove_bag.php'),{id:$value}
        ,function(data){
                var ret=null;
                try{
                    ret=$.parseJSON(data);
                }catch(Ex){
                    return;
                }
                if(ret.status=="error"){
                    TTAlert({
                        msg: ret.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }else{
                    document.location.reload();
                }
	   });
    });   
    $(".reset_button0").click(function () {
        document.location.reload();
    });
    $("#search01_filterTitle").change(function(){
        $(".menu-bag").removeClass('active');
        var $value=$(this).val();
        $(".section_group_buts").attr('data-id',$value);
        updateBagSearch($value,0);
    });
    $(document).on('click',".menu-bag" ,function(){
        var $value=$(".section_group_buts").attr('data-id');
        var $a = $(this).attr("data-type");
        if($(this).attr('class') == 'menu-bag active'){
            return '';
        }else{
            $(".menu-bag").removeClass('active');
            $(this).addClass('active');
        }        
        updateBagSearch($value,$a);
    });
    $(".reset_button1").click(function () {
        $(".menu-bag").removeClass('active');
        var $value=$(".section_group_buts").attr('data-id');        
        updateBagSearch($value,0);
    });
    $(".pushtomobile").click(function(){
        var bagId = $(".section_group_buts").attr('data-id');
        if(bagId === '0'){
            TTAlert({
               msg: t('You have to select a bag') ,
               type: 'alert',
               btn1: t('ok'),
               btn2: '',
               btn2Callback: null
            });
            return;
        }
        $.post(ReturnLink('/ajax/ajax_bag_push.php'), { value: bagId }, function(data){
            var ret = null;
            try {
                ret = $.parseJSON(data);
            }catch(Ex){
                return;
            }
            //console.log(ret);
        });
    });
});
function updateBagSearch($value,$a){
    $.post(ReturnLink('/ajax/ajax_bag.php'),
    {value:$value,a:$a},
    function(data){
        var ret=null;
        try{
            ret=$.parseJSON(data);
        }catch(Ex){
            return;
        }
        if(ret.error){
            TTAlert({
                msg: ret.error,
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
        }else{
            $(".section_group_buts").attr('data-id',$value);
            $(".tt-destination-text").html(ret.destination);
            $(".tt-bag-block").html(ret.allInfo);
            $(".share_bagdata").html(ret.sharetext);
            $(".sharePopUpTitle").html(ret.sharetext);
            $(".tt-bag-num").html(ret.userBagItemsCount);
            $(".tt-bag-num").attr('data-count',ret.userBagItemsCount);                    
            $(".showOnMap").attr('href',ReturnLink('/parts/show-on-map.php?type=b&id='+$value));
        }
   });
}