var controller = "hotel";
function CreateFeatureEditor(text, id){
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
        $(this).html(CreateFeatureEditor(text, id));
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
        var url = './' + controller + '/ajax_feature_save';
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
                   alert("An error occured while trying to edit this feature.")
               }
           },
           data: { id: id, title: text }
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
        $('div.select_box').hide();
        $('.ftedit span.dropdown_text').show();
    });
    
     $('.ftedit span').click(function(){
        var id = $(this).attr('id');
        $('.dropdown_text').each(function (){
            if($(this).hide())
                $(this).show();
        });
        $('.select_box').hide();
        $("span#"+id).css("display", "none");
        $("div#"+id).css("display", "block");
    });
    
    $('.container').on('click', '#Type_Save', function(e){ 
        e.preventDefault();
        e.stopPropagation();
        var div = $(this).parent();
        var text = div.find('select option:selected').val();
        var select_text = div.find('select option:selected').text();
        var id = div.attr('id');
        var url = './' + controller + '/ajax_feature_type_save';
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
                   alert("An error occured while trying to edit this feature.")
               }
           },
           data: { id: id, feature_type: text }
        });
    });
    
});