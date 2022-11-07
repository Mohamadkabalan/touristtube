$(document).ready(function(){
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
    
    
    $(document).on('click',".uploadinfocheckbox" ,function(){
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });
});


