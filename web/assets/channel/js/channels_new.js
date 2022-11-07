$( document ).ready(function(){
    ttModal = window.getTTModal("myModalZ", {});
    $(document).on('click', '.connect-channel', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink(('/ajax/ajax_connect_channel.php')),
            data: {channel_id: id},
            type: 'post',
            success: function (data) {
                $('.upload-overlay-loading-fix').hide();
                var jres = null;
                try {
                    jres = $.parseJSON(data);
                } catch (Ex) {

                }
                if (!jres) 
		{
		    ttModal.alert(Translator.trans("Your session timed out. Please login."), null, null, {ok:{value:"close"}});                    
                    return;
                }
                if (jres.error != '') 
		{
		    ttModal.alert(jres.error, null, null, {ok:{value:"close"}});                    
                } else {                    
                    $this.remove();
                }
            }
        });
    });    
});