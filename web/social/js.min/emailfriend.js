var canPost = true;
$(document).ready(function (){	
	var mailTText = "multiple emails? use commas";
	var mailFromText = "your email address...";
	var mailNoteText = "please write your note";
	
	var mailFill = "please fill your friend/s mail/s";
	var mailCorrect = "please enter a correct mail";
	var urmailCorrect = "please correct your mail address";
	
	$("#email_send").click(function(){
		var mailsStr = $("#email_inputTo").val();
		var fromStr = $("#email_inputFrom").val();
		var noteStr = $("#email_inputNote").val();
		
	    //alert(senderVtitle+"   "+senderVlink);
		if(mailsStr == mailTText || mailsStr == ""){
			alertError($("#email_inputTo"), mailFill);
			return;
		}else if(!validateEmail(mailsStr)){
			alertError($("#email_inputTo"), mailCorrect);
			return;
			//$("#email_inputTo").val("please fill the mail input");
		}else{
			$("#email_inputTo").css('border','none');
			document.getElementById("statusText").innerHTML= "";			
			//$("#email_inputFrom").css('border','none');
			document.getElementById("statusText").innerHTML= "Mail sent";
			setInterval(function(){ document.getElementById("statusText").innerHTML= ""; },1500);
			canPost = true;
		}
		
		/*if(fromStr == mailFromText || fromStr == ""){
			alertError($("#email_inputFrom"), mailFill);
			return;
		}else if(!validateEmail(fromStr)){
			alertError($("#email_inputFrom"), urmailCorrect);
			return;
		}else{
			$("#email_inputFrom").css('border','none');
			document.getElementById("statusText").innerHTML= "Mail sent";
			setInterval(function(){ document.getElementById("statusText").innerHTML= ""; },1500);
			canPost = true;
		}*/
	
		if(canPost){
			$.ajax({			
			    type: "POST",
			    //url: "send.php",
				url: ReturnLink('/ajax/share_email_media.php'),
				data:{'email_inputTo':mailsStr,'email_inputFrom':fromStr, 'email_inputNote':noteStr, 'vidTitle':senderVtitle, 'vidLink':senderVlink},
				success: function(){
					//$("#email_inputTo,#email_inputFrom, #email_inputNote").attr('value','');
					$("#email_inputTo").val(mailTText);
					$("#email_inputFrom").val(mailFromText);
					$("#email_inputNote").val(mailNoteText);
			   }
			});
		}
	});
});

function alertError(inputText, textStr){
	inputText.focus();
	inputText.css({'border':'1px solid','border-color':'#e7ba20'});
	document.getElementById("statusText").innerHTML= textStr;
	canPost = false;
}

function checkmail(field){ 
    var regex=/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i; 
    return (regex.test(field)) ? true : false; 
} 

function validateEmail(value){ 
    var result = value.split(","); 

    for(var i = 0;i < result.length;i++) 

    if(!checkmail(result[i]))  
     return false;     
     return true; 
} 

function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	
	return true;
}

