var controller = "hotels";
function CreateAmenityEditor(text, id){
    var html = '<input data-initial="' + text + '" data-id="' + id + '" value="' + text + '"> <a id="lSave" href="#">Save</a> | <a id="lCancel" href="#">Cancel</a><img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif">';
    return html;
}

var currentfedit = null;
$(function(){
    $('#has_count').click(function(){
       if($(this).prop('checked')){
           $(this).val("1");
       }
       else{
           $(this).val("0");
       }
    });
//    controller = GetController(window.location.pathname);
    $('.fedit').click(function(){ 
        var id = $(this).attr('id');
        if(currentfedit && currentfedit === id)
            return;
        if(currentfedit && currentfedit !== id)
            $('#lCancel').trigger('click');
        var text = $(this).html();
        $(this).html(CreateAmenityEditor(text, id));
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
        var url = './' + controller + '/ajax_amenity_save';
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
    $('.container').on('click', '#Hc_Cancel', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('div.check_box').hide();
        $('.hcedit span.check_text').show();
    });
    
     $('.hcedit span').click(function(){
        var id = $(this).attr('id');
        $('.hc_check').each(function (){
            if($(this).hide())
                $(this).show();
        });
        $('.check_box').hide();
        $("span#"+id).css("display", "none");
        $("div#"+id).css("display", "block");
        var has_count = $("span#"+id).attr("data-hascount");
//        $("input[type=checkbox]#"+id).val(has_count);
        if(has_count === "1"){
            $("input[type=checkbox]#"+id).prop('checked', 'checked');
        }
    });
    
    $('.container').on('click', '#Hc_Save', function(e){ 
        e.preventDefault();
        e.stopPropagation();
        
        var div = $(this).parent();
        var id = div.attr('id');
        var value = 0;
        if($("input[type=checkbox]#"+id).prop('checked')){
            value = 1;
        }
        var old_value = $('.ftedit span#'+id).attr('data-hascount');
        var url = './' + controller + '/ajax_has_count_save';
        $('#imgSaving').show();
        $.ajax({
           url: url,
           type: 'post',
           dataType: 'json',
           success: function (res){ 
                    $('#imgSaving').hide();
                    $('div.check_box').hide();
               if(res.success){
                    $('.hcedit span#'+id).html(value ? 'Yes' : 'No');
                    $('.hcedit span#'+id).attr('data-hascount', value);
                    $('.hcedit span.check_text').show();
                    currentfedit = null;
               }
               else{
                   $('#Type_Cancel').trigger('click');
                   $("input[type=checkbox]#"+id).prop('checked', old_value === "1" ? 'checked' : '');
                   alert("An error occured while trying to edit this amenity.");
               }
           },
           data: { id: id, has_count: value }
        });
    });
    
});