var controller = 'ml';
function CreateCategoryEditor(text, id, is_new){
    var html = '<input data-new="' + is_new + '" data-initial="' + text.trim() + '" data-id="' + id + '" value="' + text.trim() + '"> <a id="lSave" href="#">Save</a> | <a id="lCancel" href="#">Cancel</a><img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif">';
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
        $(this).html(CreateCategoryEditor(text, id, 0));
        $(this).find('input').focus();
        currentfedit = id;
    });
    
    $('.container').on('click', '#lCancel', function(e){
        e.preventDefault();
        e.stopPropagation();
        var div = $(this).parent();
        var is_new = div.find('input').attr('data-new');
        if(is_new === "1"){
            div.html('<a class="fadd" href="#">Add</a>');
        }
        else{
            var text = div.find('input').data('initial');
            div.html(text);
        }
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
        var div_class = div.attr('class');
        var url = '';
        if(div_class === 'fedit'){
            url = './' + controller + '/ajax_channelCategory_save/t/'+new Date().getTime();
        }else{
            url = './' + controller + '/ajax_ml_channelCategory_save/t/'+new Date().getTime();
        }
        $('#imgSaving').show();
        $.ajax({
           url: url,
           type: 'post',
           dataType: 'json',
           cache:false,
           success: function (res){
               $('#imgSaving').hide();
               if(res.success){
                    div.html(text);
                    currentfedit = null;
               }
               else{
                   $('#lCancel').trigger('click');
                   alert("An error occured while trying to edit this cuisine.")
               }
           },
           data: { id: id, title: text }
        });
    });
    
    /*
     Elie
     */
    $('.container').on('click', '.fadd', function(e){
        e.preventDefault();
        e.stopPropagation();
        var $parent = $(this).parent();
        var id = $parent.attr('id');
        if(currentfedit && currentfedit === id)
            return;
        if(currentfedit && currentfedit !== id)
            $('#lCancel').trigger('click');
        $parent.html(CreateCategoryEditor('', id, 1));
        $parent.find('input').focus();
        currentfedit = id;
    });
    
    /*
     Mukesh
     */
    $('.in_edit, .fr_edit, .zh_edit, .es_edit, .pt_edit, .it_edit, .de_edit, .tl_edit').click(function(){ 
        var $this = $(this);
        if($this.find('.fadd').length > 0){
            return;
        }
        var id = $this.attr('id');
        if(currentfedit && currentfedit === id)
            return;
        if(currentfedit && currentfedit !== id)
            $('#lCancel').trigger('click');
        var text = $this.html();
        $this.html(CreateCategoryEditor(text, id, 0));
        $this.find('input').focus();
        currentfedit = id;
    });
    
});