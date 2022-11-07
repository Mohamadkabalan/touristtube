var controller = "hotel";
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
           data: { id: id, title: text }
        });
    });
});