$(document).ready(function () {
    var formId = '';
    $(document).on('click', "#cancel_whole_reservation", confirmCancellation);
    $(document).on('click', ".cancel-room-button", confirmRoomCancellation);
    if($('.addtobag').length>0) $('.addtobag').click();
});
function confirmCancellation() {
    TTAlert({
        msg: Translator.trans('Are you sure you want to cancel this reservation?'),
        type: 'action',
        btn1: Translator.trans('No'),
        btn2: Translator.trans('Yes'),
        btn2Callback: function (data) {
            if (data == true) {
                $('#reservation-cancel-submit').submit();
            }
            return false;
        }
    });
    return false;
}
function confirmRoomCancellation() {
    formId = '#' + $(this).parent().attr('id');
    TTAlert({
        msg: Translator.trans('Are you sure you want to cancel this room?'),
        type: 'action',
        btn1: Translator.trans('No'),
        btn2: Translator.trans('Yes'),
        btn2Callback: function (data) {
            if (data == true) {
                if (formId !== '') {
                    $(formId).submit();
                }
            } else {
                formId = '';
            }
            return false;
        }
    });
    return false;
}