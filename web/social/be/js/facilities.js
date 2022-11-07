var controller = "hotels";
function CreateFacilityEditor(text, id){
    var html = '<input data-initial="' + text + '" data-id="' + id + '" value="' + text + '"> <a id="lSave" href="#">Save</a> | <a id="lCancel" href="#">Cancel</a><img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif">';
    return html;
}

var currentfedit = null;
$(function(){
//    controller = GetController(window.location.pathname);
    $('.fedit').click(function(){ 
        var id = $(this).attr('id');
        if(currentfedit && currentfedit === id)
            return;
        if(currentfedit && currentfedit !== id)
            $('#lCancel').trigger('click');
        var text = $(this).html();
        $(this).html(CreateFacilityEditor(text, id));
        $(this).find('input').focus();
        currentfedit = id;
    });
    
    $('.container').on('click', '#lCancel', function(e){
        e.preventDefault();
        e.stopPropagation();
        var div = $(this).parent();
        var text = div.find('input').data('initial');
        div.html(text);
        currentfedit = null;
    });
    
    $('.container').on('click', '#lSave', function(e){
        e.preventDefault();
        e.stopPropagation();
        var div = $(this).parent();
        var text = div.find('input').val();
        if(!text || text.trim() === ""){
            alert("Name cannot be empty.");
            var input = div.find('input');
            var initial = input.data('initial');
            input.val(initial);
            input.focus();
            return;
        }
        text = text.trim();
        var id = div.attr('id');
        var url = './' + controller + '/ajax_facility_save';
        $('#imgSaving').show();
        $.ajax({
           url: url,
           type: 'post',
           dataType: 'json',
           success: function (res){
               console.log(res);
               $('#imgSaving').hide();
               if(res.success){
                    div.html(text);
                    currentfedit = null;
                    
               }
               else{
                   $('#lCancel').trigger('click');
                   alert("An error occured while trying to edit this facility.")
               }
           },
           data: { id: id, name: text }
        });
    }); 
    
    /*
	function name: For Feature type
	Author: Mukesh
	Created: 18 dec 2014
    */
    $('.container').on('click', '#Type_Cancel', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('.ftedit div.select_box').hide();
        $('.ftedit span.dropdown_text').show();
    });
    
    $('.container').on('click', '#Level_Cancel', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('.fledit div.select_box').hide();
        $('.fledit span.dropdown_text').show();
    });
    
    $('.fledit span').click(function(){
        var id = $(this).attr('id');
        $('.fledit .dropdown_text').each(function (){
            if($(this).hide())
                $(this).show();
        });
        $('.fledit .select_box').hide();
        $(".fledit span#"+id).css("display", "none");
        $(".fledit div#"+id).css("display", "block");
    });
    
    $('.ftedit span').click(function(){
        var id = $(this).attr('id');
        $('.ftedit .dropdown_text').each(function (){
            if($(this).hide())
                $(this).show();
        });
        $('.ftedit .select_box').hide();
        $(".ftedit span#"+id).css("display", "none");
        $(".ftedit div#"+id).css("display", "block");
    });
    
    $('.container').on('click', '#Level_Save', function(e){ 
        e.preventDefault();
        e.stopPropagation();
        var div = $(this).parent();
        var text = div.find('select option:selected').val();
        var select_text = div.find('select option:selected').text();
        var id = div.attr('id');
        var url = './' + controller + '/ajax_level_save';
        $('#imgSaving').show();
        $.ajax({
           url: url,
           type: 'post',
           dataType: 'json',
           success: function (res){ 
                    $('div#imgSaving').hide();
                    $('.fledit div.select_box').hide();
               if(res.success){
                    $('.fledit span#'+id).html(select_text);
                    $('.fledit span.dropdown_text').show();
                    currentfedit = null;
               }
               else{
                   $('#Level_Cancel').trigger('click');
                   alert("An error occured while trying to edit this facility.")
               }
           },
           data: { id: id, level: text }
        });
    });
    
    $('.container').on('click', '#Type_Save', function(e){ 
        e.preventDefault();
        e.stopPropagation();
        var div = $(this).parent();
        var text = div.find('select option:selected').val();
        var select_text = div.find('select option:selected').text();
        var id = div.attr('id');
        var url = './' + controller + '/ajax_facility_type_save';
        $('#imgSaving').show();
        $.ajax({
           url: url,
           type: 'post',
           dataType: 'json',
           success: function (res){ 
                    $('div#imgSaving').hide();
                    $('div.select_box').hide();
               if(res.success){
                    $('.ftedit span#'+id).html(select_text);
                    $('.ftedit span.dropdown_text').show();
                    currentfedit = null;
               }
               else{
                   $('#Type_Cancel').trigger('click');
                   alert("An error occured while trying to edit this facility.")
               }
           },
           data: { id: id, type_id: text }
        });
    });
    
});