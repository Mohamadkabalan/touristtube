$(document).ready(function(){
        $('.SignInput').each(function(){
            var $this = $(this);
            $this.focus(function(){
                if( $(this).data('focused') ) return;
                $(this).data('focused',true);
                $(this).addClass('focused').val('');
            });
        });	
	$('#LogInSignIn').click(function(){
		
		var EmailField = $("#EmailField").val();
		var PasswordField = $("#PasswordField").val();
		
		$('#PasswordField,#EmailField').removeClass('InputErr');
		if(EmailField == '' || EmailField == $("#EmailField").attr('data-value') || !(EmailField.match(/([\<])([^\>]{1,})*([\>])/i) == null)){
			$('#EmailField').focus().addClass('InputErr');
			return false;
		}else if(PasswordField == '' || PasswordField ==  $("#PasswordField").attr('data-value') || !(PasswordField.match(/([\<])([^\>]{1,})*([\>])/i) == null)){
			$('#PasswordField').focus().addClass('InputErr');
			return false;
		}else{
			$("#signin_loader").show();
			
			var dataString = 'EmailField='+ EmailField + '&PasswordField=' + PasswordField;
			
			var keep_me_logged =0;
			if($('#keepmelogged').is(':checked')){
				keep_me_logged =1;
			}
			dataString  += "&keep_me_logged=" + keep_me_logged;
			
			var latitude=0;
                        var longitude=0;
                        try{
                            latitude = google.loader.ClientLocation.latitude;
                            longitude = google.loader.ClientLocation.longitude;
                        }catch(e){
                        }
			
			dataString  += "&longitude=" + longitude + "&latitude=" + latitude;
			$.ajax({
				type: "POST",
				url: ReturnLink("/ajax/process.php"),
				data: dataString,
				success: function(data) {
					
					var Jresponse;
					try{
						Jresponse = $.parseJSON(data);
					}catch(Ex){						
						$("#signin_loader").hide();
						return ;
					}
					
					if(Jresponse.status == 'error'){
                                            if(Jresponse.reactivate == '1'){
                                                window.top.location.href = ReturnLink('/reactivate');
                                            }
                                            $('#wrong_credentials').css('display', 'inline-block');						
					}else{
                                            window.top.location.reload();
					}
					$("#signin_loader").hide();
				}
			});
			return true;
			
		}
	});
	
});
