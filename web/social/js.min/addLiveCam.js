/**
 * Created on .
 */
function ClearLiveCamForm() {
    $('#liv-cam input').val('');
    $('#liv-cam textarea').val('');
}
function validateURL(textval) {
    var urlregex = new RegExp(
        "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
    return urlregex.test(textval);
}function ValidateLiveCamForm() {

    var lat = $("#latitude").val().toString();
    var lng = $("#longitude").val().toString();

    if ($("#title").val() === '') {
        TTAlert({
            msg: t('Please enter a title of your live camera.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#tite").focus();
        return false;
    } else if ($("#live-url").val() === '') {
        TTAlert({
            msg: t('Please enter a url of your live camera.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#live-url").focus();
        return false;
    }else if (!validateURL($("#live-url").val())){
        TTAlert({
            msg: t('Please enter a valid url of your live camera.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#live-url").focus();
        return false;
    }
    else if (lat === '') {
        TTAlert({
            msg: t('Please enter Geo location of your camera.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#latitude").focus();
        return false;
    }else if (isNaN(lat)) {
        TTAlert({
            msg: t('Please enter valid Geo location of your camera.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#latitude").focus();
        return false;
    } else if (lng === '') {
        TTAlert({
            msg: t('Please enter Geo location of your camera.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#longitude").focus();
        return false;
    }else if (isNaN(lng)) {
        TTAlert({
            msg: t('Please enter valid Geo location of your camera.'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#longitude").focus();
        return false;
    }
    else if ($("#address").val() === '' || $("#address").val() === '...') {
        TTAlert({
            msg: t('Please enter the address'),
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        $("#address").focus();
        return false;
    }
    else {
        $('#liv-cam').submit();
    }
}

$(document).ready(function () {
    $("#Submit").click(function () {
        ValidateLiveCamForm();
    });

    $("#Cancel").click(function () {
        ClearLiveCamForm();
    });
    if(msg !==''){
        TTAlert({
            msg: msg,
            type: 'alert',
            btn1: 'ok',
            btn2: '',
            btn2Callback: null
        });
        msg = '';
    }
});

