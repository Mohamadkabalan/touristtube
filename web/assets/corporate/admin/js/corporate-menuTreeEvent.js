$(document).ready(function () {
    $('.tree').treegrid();

    $('#check_all').click(function(){
        if($(this).attr('checked')) {
            $('.menus').prop('checked', true);
        } else {
            $('.menus').prop('checked', false);
        }
    });

    $('.parent').click(function(){
        var parentId = $(this).attr('id');
        if($(this).attr('checked')) {
            $('.child-' + parentId).prop('checked', true);
        } else {
            $('.child-' + parentId).prop('checked', false);
        }
    });
});