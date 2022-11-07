var controller = 'seo';
function filterFn(){	
	var title = $('#txtName').val().trim();
	var url = $('#txtUrl').val().trim();
	$.ajax({
		url: './' + controller + '/ajax_alias',
		type: 'post',
		success     : function (res)
		{
		   // $('#imgLoading').hide();
			$("#listContainer").html(res);
		},
		data: { ti: title, ur: url }
	});
}
$('#txtName, #txtUrl').keydown(function(e){
	if(e.which === 13){
		filterFn();
	}
});    
function resetFn(){	
	$('#txtName').val('');		
	$('#txtUrl').val('');        
	filterFn();
}
    
    