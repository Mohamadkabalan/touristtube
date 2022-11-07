var currentPage = 0;

$(document).ready(function() {
    $('.loadmore_container_up').css('width', $('.loadmore_container_up').attr('data-wd') + "px");
    $('#loadmore').css('width', ($('#loadmore span').width() + 3) + "px");
    $("#loadmore").click(function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        currentPage++;
        displaypicrelated(0);
    });
    $(document).on('click', "#albumimagecontainer ul li .clsimg", function() {
        var data_type = $(this).closest('.imgbk_butons').attr('data-type');
        var data_id = $(this).closest('.imgbk_butons').attr('data-id');
        var obj = $(this).closest('li');
        TTAlert({
            msg: t('Are you sure you want to delete your profile image?'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data){
                if(data){
                    $('.upload-overlay-loading-fix-file').show();
                    $.ajax({
                        url: ReturnLink('/ajax/ajax_delete_profileimages.php'),
                        data: {
                            data_type: data_type,
                            id: data_id
                        },
                        type: 'post',
                        success: function (data) {
                            var ret = null;
                            try {
                                ret = $.parseJSON(data);
                            } catch (Ex) {
                                $('.upload-overlay-loading-fix-file').hide();
                                return;
                            }
                            if (ret.error == 1) {
                                $('.upload-overlay-loading-fix-file').hide();
                                TTAlert({
                                    msg: ret.msg,
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                                });
                            } else{
                                obj.remove();
                                displaypicrelated(1);
                            }
                        }
                    });
                }
            }
        });
    });
    $(document).on('click', "#albumimagecontainer ul li .imgbk .albumimg:not(.albumimgActive)", function() {
        var curbut = $(this).closest('.imgbk_butons');
        $('.upload-overlay-loading-fix-file').show();

        var data_id = curbut.attr("data-id");
        var data_type = curbut.attr('data-type');
        $.post(ReturnLink('/ajax/ajax_updateprofileimage.php'), {data_type: data_type,id: data_id}, function(data) {
            var ret;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                $('.upload-overlay-loading-fix-file').hide();
                return;
            }

            if (ret.error==1) {
                $('.upload-overlay-loading-fix-file').hide();
                TTAlert({
                    msg: ret.msg,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            }
            document.location.reload();
        });
    });
    initDocumentProfileImages();
});
function initDocumentProfileImages() {
    $(document).on('mouseover', ".imgbk_butons .imgbk_buts:not(.albumimgActive)", function(){
        var posxx = $(this).offset().left - $('#ProfileHeaderInternal').offset().left - 252;
        var posyy = $(this).offset().top - $('#ProfileHeaderInternal').offset().top - 21;
        $('.ProfileHeaderOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.ProfileHeaderOver').css('left', posxx + 'px');
        $('.ProfileHeaderOver').css('top', posyy + 'px');
        $('.ProfileHeaderOver').stop().show();
    });
    $(".imgbk_butons .imgbk_buts").mouseout(function() {
        $('.ProfileHeaderOver').hide();
    });
    $(".profileimageLink").each(function (index, element) {
        var $This = $(this);
            
        var vid = $This.attr('data-id');
        var type = $This.attr('data-type');

        $This.attr("href", ReturnLink('parts/user-viewprofileimage.php?id=' + vid + '&type=' + type));

        $This.fancybox({
            "padding": 0,
            "margin": 0,
            "width": '883',
            "height": '604',
            "transitionIn": "none",
            "transitionOut": "none",
            "autoSize": false,
            "scrolling": 'no',
            "type": "iframe"
        });

    });
}
function displaypicrelated(one_object) {
    $('.upload-overlay-loading-fix-file').show();
    $.post(ReturnLink('/ajax/ajax_getprofileimages.php'), {user_id:userGlobalID(),page: currentPage,one_object: one_object}, function(data) {        
        $('#albumimagecontainer ul').append(data);
        initDocumentProfileImages();
        $('.upload-overlay-loading-fix-file').hide();
    });
}