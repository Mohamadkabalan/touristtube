$(document).ready(function(){
		
	RequestifyLink();
	
	$(document).on('mouseover','.MediaButton, .MediaButton_data, #fullscreen_pic',function(){
            if( !$('.mediabuttonsOver').hasClass('inactive') ){
		var posxx=$(this).offset().left-$(this).parent().offset().left-251;
		$('.mediabuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
		$('.mediabuttonsOver').css('left',posxx+'px');
		$('.mediabuttonsOver').css('top','-18px');
		$('.mediabuttonsOver').stop().show();
            }else{
                $('.mediabuttonsOver').hide();
            }
	});
	$(document).on('mouseout','.MediaButton, .MediaButton_data, #fullscreen_pic',function(){
            $('.mediabuttonsOver').hide();
	});
        
	//////////////////////////
	//requestinvite
	setTimeout(function(){
		if(userIsLogged == 1) return;
		$('#myrating img').unbind('click');
	},100);
	$('#favorite').click(function(){
		var $button = $(this);
		if( $(this).data('reset') ){
			
		}
		TTCallAPI({
			what: 'social/favorite',
			data: {entity_id : ORIGINAL_MEDIA_ID, entity_type : SOCIAL_ENTITY_MEDIA, channel_id: channelGlobalID() },
			callback: function(data){
				
				$button.removeData('reset');
					
				if(data.status == 'ok'){
					if( data.favorite == 0 ) {
						$('#favorite').removeClass('active');
						$('#favorite').attr('data-title', t('add to favorites') );
					}else {
						$('#favorite').addClass('active');
						$('#favorite').attr('data-title', t('remove from favorites') );
					}
					$('.mediabuttonsOver .ProfileHeaderOverin').html($('#favorite').attr('data-title'));
				}else{
                                        TTAlert({
                                            msg: data.msg,
                                            type: 'action',
                                            btn1: t('sign in'),
                                            btn2: t('register'),
                                            btn2Callback: function(data) {
                                                if (data) {
                                                    window.location.href = ReturnLink('/register');
                                                } else {
                                                    SignInTO = setTimeout(function() {
                                                        $('#SignInDiv').fadeIn();
                                                        signflag = 1;
                                                    }, 300);
                                                }
                                            }
                                        });
				}
			}
		});
	});
	
	
	$('#like').click(function(){
		var $button = $(this);
		
		TTCallAPI({
			what: 'social/like',
			data: {entity_id : VideoID(), entity_type : SOCIAL_ENTITY_MEDIA, like_value: 1, channel_id: channelGlobalID() },
			callback: function(ret){
				
				$button.removeData('reset');
				
				if(ret.status == 'ok'){
					var cur_likes = parseInt( $('#display_likes').text() );
					$('#display_likes').text( cur_likes + 1);
					$('#like').attr('src', ReturnLink('/images/like_button_yellow.png') );
					$('#dislike').attr('src', ReturnLink('/images/dislike_button.png') );
				}else{
					TTAlert({
						msg: ret.error_msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
				
			}
		});

	});
					
	$('#dislike').click(function(){
		var $button = $(this);
		
		TTCallAPI({
			what: 'social/like',
			data: {entity_id : VideoID(), entity_type : SOCIAL_ENTITY_MEDIA, like_value: -1, channel_id: channelGlobalID() },
			callback: function(ret){
				
				$button.removeData('reset');
				
				if(ret.status == 'ok'){
					var cur_likes = parseInt( $('#display_likes').text() );
					$('#display_likes').text( cur_likes - 1);
					$('#dislike').attr('src', ReturnLink('/images/dislike_button_yellow.png') );
					$('#like').attr('src', ReturnLink('/images/like_button.png') );
				}else{
					TTAlert({
						msg: ret.error_msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
				
			}
		});
		
	});

});