$(document).ready(function() {
    $(document).on('click',".yellowreset" ,function(){
        var $this=$(this);
        var new_pass = $('input[name=NewPassword]').val();
        var new_pass2 = $('input[name=ConfirmNewPassword]').val();

        if(checkAccountInfo(new_pass,new_pass2)){
                $('.upload-overlay-loading-fix').show();
                var email= $this.attr('data-email');
                var dataString = 'email='+ email+'&new_pass='+new_pass;
                $.ajax({
                        type: "POST",
                        url: ReturnLink(("/ajax/update_forgot_password.php")),
                        data: dataString,
                        success: function(res) {					
                                var Jresponse;
                                try{
                                        Jresponse = $.parseJSON( res );
                                }catch(Ex){


                                }
                                if(Jresponse.status=='ok'){
                                        $('.confirm_container_unsubscribe').hide();
                                        $('#confirm_container_hide').show();
                                }else{
                                        TTAlert({
                                                msg: Jresponse.error,
                                                type: 'alert',
                                                btn1: t('ok'),
                                                btn2: '',
                                                btn2Callback: null
                                        });
                                }
                                $('.upload-overlay-loading-fix').hide();
                        }
                });
        }
    });
});
function checkAccountInfo(new_pass,new_pass2){
    $('.confirm_container_unsubscribe input').removeClass('InputErr');
    $('.confirm_container_unsubscribe .errorclass').html('');
    var min_pswd_length = 8;

    if(new_pass.length==0 || (new_pass.length < min_pswd_length) ){
            $('.confirm_container_unsubscribe .errorclass').html( sprintf( t('Password must be minimum %s characters long') , [min_pswd_length]) );
            $('.confirm_container_unsubscribe input[name=NewPassword]').addClass('InputErr');
            return false;
    }else if( new_pass != new_pass2 ){
            $('.confirm_container_unsubscribe .errorclass').html(t('Passwords mismatch'));
            $('.confirm_container_unsubscribe input[name=NewPassword]').addClass('InputErr');
            $('.confirm_container_unsubscribe input[name=ConfirmNewPassword]').addClass('InputErr');
            return false;
    }
    return true;
}
function checkSubmitConfirm(e){
   if(e && e.keyCode == 13){
      $('.yellowreset').click();
   }
}