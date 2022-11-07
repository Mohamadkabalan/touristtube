var uploaderHome = null;
var ttModal = null;
var requirements_met = true;

function InitUploaderHome(which_uploader, container_uploader, imgsize, can_dragdrop,multi_selection) {
    if( !ttModal ) {
	ttModal = window.getTTModal("myModalZ", {});
    }
    
    if (typeof(multi_selection) == 'undefined') {
        multi_selection = false;
    }
    var hotelId=0;
    if ($('#uploadHotelsReviewsContainer').length > 0) {
        hotelId = $('#uploadHotelsReviewsContainer').attr('data-id');
    }
    var filters_title = "Image files";
    var filters_extensions = "jpg,gif,png,tiff,tif,bmp,jpeg";
    if (which_uploader == "videos_posts" || which_uploader == "TT_videos_posts") {
        filters_title = "Video files";
        filters_extensions = "wmv,mov,flv,mp4,avi,mts,m4v";
    }
    if ( which_uploader == "postMediaPV" || which_uploader == "CHpostMediaPV" ) {
        filters_title = "Video or Image files";
        filters_extensions = "jpg,gif,png,tiff,tif,bmp,jpeg,wmv,mov,flv,mp4,avi,mts,m4v";
    }
    if (can_dragdrop == 1) {
        uploaderHome = new plupload.Uploader({
            runtimes: 'html5,flash',
            button_browse_hover: true,
            max_file_size: imgsize + 'mb',
            container: container_uploader,
            browse_button: which_uploader,
            drop_element: which_uploader,
            url: generateLangURL('/ajax/home-upload-file'),
            flash_swf_url: ReturnLink('/assets/uploads/js/plupload/plupload.flash.swf'),
            multi_selection: multi_selection,
            multipart_params: {
                channel_id: channelGlobalID(),
                discover_nam: userGlobalDISName(),
                hotel_id: hotelId,
                which_uploader: which_uploader
            },
            filters: [
                {title: filters_title, extensions: filters_extensions},
            ],
            init: attachCallbacks
        });
    } else {
        uploaderHome = new plupload.Uploader({
            runtimes: 'html5,flash',
            button_browse_hover: true,
            max_file_size: imgsize + 'mb',
            container: container_uploader,
            browse_button: which_uploader,
            url: generateLangURL('/ajax/home-upload-file'),
            flash_swf_url: ReturnLink('/assets/uploads/js/plupload/plupload.flash.swf'),
            multi_selection: multi_selection,
            multipart_params: {
                channel_id: channelGlobalID(),
                discover_nam: userGlobalDISName(),
                hotel_id: hotelId,
                which_uploader: which_uploader
            },
            filters: [
                {title: filters_title, extensions: filters_extensions},
            ],
            init: attachCallbacks
        });
    }

    uploaderHome.bind('Init', function (up, params) {
        if ((params.runtime == 'flash') && !swfobject.hasFlashPlayerVersion("10")) 
	{
            requirements_met = false;
            ttModal.alert(Translator.trans("Please update your flash installation to at least version 10"), null, null, {ok:{value:Translator.trans("close")}});
        }
    });

    uploaderHome.init();

    function attachCallbacks(uploader) {

        uploader.bind('FilesAdded', function (up, files) {
            up.settings.multipart_params = {
                channel_id: channelGlobalID(),
                user_id: userGlobalID(),
                discover_nam: userGlobalDISName(),
                hotel_id: hotelId,
                which_uploader: which_uploader
            };

            if (!requirements_met) 
	    {
                ttModal.alert(Translator.trans("Please update your flash installation to at least version 10"), null, null, {ok:{value:Translator.trans("close")}});
                return false;
            }
//            if(up.files.length > 6 || uploader.files.length > 6 || files.length > 6) {
            if(files.length > 6 && multi_selection==true) {
		ttModal.alert(Translator.trans("Only 6 files per upload !"), null, null, {ok:{value:Translator.trans("close")}});
		    
                uploader.stop();
                $.each(files, function (i, file) {
                    uploader.removeFile(file);
                });
                
                return false;
            }
            setTimeout(function () {
                uploader.start()
            }, 100);

            $.each(files, function (i, file) {
                var fid = file.id;
                //actaually only one upload
                $('.upload-overlay-loading-fix').show();
            });
        });

        uploader.bind('UploadFile', function (up, file) {
            var fid = file.id;
	    $('.upload-overlay-loading-fix').show();
        });

        uploader.bind('Error', function (up, err) {
            $('.upload-overlay-loading-fix').hide();
	    
	    if (err.code == -600) 
	    {
		ttModal.alert(err.message + '<br/>maximum size: ' + imgsize + 'mb', null, null, {ok:{value:Translator.trans("close")}});					
	    } 
	    else if (err.file && err.file.name) 
	    {
		ttModal.alert(Translator.trans("Error: ") + err.file.name + ' ' + err.message, null, null, {ok:{value:Translator.trans("close")}});		
	    } 
	    else 
	    {
		ttModal.alert(Translator.trans("Error: ") + err.message, null, null, {ok:{value:Translator.trans("close")}});
	    }
	    
            uploader.refresh(); // Reposition Flash/Silverlight
        });

        uploader.bind('FileUploaded', function (up, file, response) {
            $('.upload-overlay-loading-fix').hide();

            var fid = file.id;
            //var item = $('#uploadContainer'+ which_uploader);
            var Jresponse;
            try {
                Jresponse = $.parseJSON(response.response);
            } catch (Ex) {

                return;
            }

            if (Jresponse.status !== 'ok') 
	    {
		ttModal.alert(Jresponse.msg, null, null, {ok:{value:Translator.trans("close")}});
            } 
	    else 
	    { 
		
                if ( which_uploader == "uploadHotelsReviews")
		{
                    updateImage(Jresponse.name, Jresponse.which_uploader,Jresponse.InsertId);
                } 
		else if ( Jresponse.which_uploader == "uploadReview_Img" )
		{
                    updateImage(Jresponse.name, Jresponse.which_uploader);
                } 
		else 
		{
                    $('#picfancyboxbutton').fancybox({
                        "padding": 0,
                        "margin": 0,
                        "width": Jresponse.stanwidth,
                        "height": Jresponse.stanheight+30,
                        "transitionIn": "none",
                        "transitionOut": "none",
                        "autoSize": true,
                        "type": "iframe",
                        helpers : {
                            overlay : {closeClick:false}
                        }
                    });
                    $('#picfancyboxbutton').attr("href", generateLangURL("/ajax/home-upload-fancy?path=" + Jresponse.path + "&code=" + Jresponse.code + "&name=" + Jresponse.name + "&type=" + Jresponse.which_uploader + "&h=" + Jresponse.stanheight ) );
                    $('#picfancyboxbutton').click();
                }
		
            }
	    
        });
    }
}