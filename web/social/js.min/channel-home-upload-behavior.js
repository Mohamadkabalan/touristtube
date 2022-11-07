
var uploaderHome = null;
var requirements_met = true;
var upload_started;

function InitChannelUploaderHome(which_uploader, container_uploader, imgsize, can_dragdrop,multi_selection) {
    if (typeof multi_selection != 'undefined') {
        multi_selection = false;
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
            url: ReturnLink('/social/channel-upload-file.php'),
            flash_swf_url: ReturnLink('/js/plupload/plupload.flash.swf'),
            multi_selection: multi_selection,
            multipart_params: {
                channel_id: channelGlobalID(),
                user_id: userGlobalID(),
                discover_nam: userGlobalDISName(),
                which_uploader: which_uploader,
                SID: $('#SID').val()
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
            url: ReturnLink('/social/channel-upload-file.php'),
            flash_swf_url: ReturnLink('/js/plupload/plupload.flash.swf'),
            multi_selection: multi_selection,
            multipart_params: {
                channel_id: channelGlobalID(),
                user_id: userGlobalID(),
                discover_nam: userGlobalDISName(),
                which_uploader: which_uploader,
                SID: $('#SID').val()
            },
            filters: [
                {title: filters_title, extensions: filters_extensions},
            ],
            init: attachCallbacks
        });
    }

    uploaderHome.bind('Init', function (up, params) {
        if ((params.runtime == 'flash') && !swfobject.hasFlashPlayerVersion("10")) {
            requirements_met = false;
            if (which_uploader != "uploadBtnbrochure" && which_uploader != "uploadBtnevent") {
                TTAlert({
                    msg: t("Please update your flash installation to at least version 10"),
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
            } else {
                $('#brochureerror').html(t("Please update your flash installation to at least version 10"));
            }
        }
    });

    uploaderHome.init();

    function attachCallbacks(uploader) {

        uploader.bind('FilesAdded', function (up, files) {
            up.settings.multipart_params = {
                channel_id: channelGlobalID(),
                user_id: userGlobalID(),
                discover_nam: userGlobalDISName(),
                which_uploader: which_uploader,
                SID: $('#SID').val()
            };

            if (!requirements_met) {
                if (which_uploader != "uploadBtnbrochure" && which_uploader != "uploadBtnevent") {
                    TTAlert({
                        msg: t("Please update your flash installation to at least version 10"),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    $('#brochureerror').html(t("Please update your flash installation to at least version 10"));
                }
                return false;
            }

            setTimeout(function () {
                uploader.start()
            }, 100);

            $.each(files, function (i, file) {
                var fid = file.id;
                //actaually only one upload
                if (which_uploader == "photos_posts" || which_uploader == "photos_posts" || which_uploader == "videos_posts" || which_uploader == "uploadReview_Img" || which_uploader == "TT_photos_posts" || which_uploader == "flashPhoto" || which_uploader == "TT_videos_posts" || which_uploader == "postMediaPV" || which_uploader == "CHpostMediaPV" || which_uploader == "TT_uploadBtnevent") {
                    $('.upload-overlay-loading-fix-file').show();
                } else if (which_uploader == "editChannelRight_data6") {
                    $('.upload-overlay-loading-fix').show();
                }
            });
        });

        uploader.bind('UploadFile', function (up, file) {
            var fid = file.id;

            upload_started = 1;
            if (which_uploader == "photos_posts" || which_uploader == "videos_posts" || which_uploader == "TT_photos_posts" || which_uploader == "uploadReview_Img" || which_uploader == "flashPhoto" || which_uploader == "TT_videos_posts" || which_uploader == "postMediaPV" || which_uploader == "CHpostMediaPV" || which_uploader == "TT_uploadBtnevent") {
                $('.upload-overlay-loading-fix-file').show();
            } else if (which_uploader == "editChannelRight_data6") {
                $('.upload-overlay-loading-fix').show();
            }
        });

        uploader.bind('UploadProgress', function (up, file) {

            var fid = file.id;
            //file.percent
            if (which_uploader == "photos_posts" || which_uploader == "videos_posts" || which_uploader == "TT_photos_posts" || which_uploader == "uploadReview_Img" || which_uploader == "flashPhoto" || which_uploader == "TT_videos_posts" || which_uploader == "postMediaPV" || which_uploader == "CHpostMediaPV" || which_uploader == "TT_uploadBtnevent") {
                $('.upload-overlay-loading-fix-file span').html(file.percent + '%');
            } else if (pagename == "event" || pagename == "channel-add-brochure") {
                $('.upload-overlay-loading-fix').show();
            }
        });

        uploader.bind('Error', function (up, err) {
            if (which_uploader == "photos_posts" || which_uploader == "videos_posts" || which_uploader == "TT_photos_posts" || which_uploader == "uploadReview_Img" || which_uploader == "flashPhoto" || which_uploader == "TT_videos_posts" || which_uploader == "postMediaPV" || which_uploader == "CHpostMediaPV" || which_uploader == "TT_uploadBtnevent") {
                $('.upload-overlay-loading-fix-file').hide();
            } else if (pagename == "event" || pagename == "channel-add-brochure") {
                $('.upload-overlay-loading-fix').hide();
            } else if (which_uploader == "editChannelRight_data6") {
                $('.upload-overlay-loading-fix').hide();
            }
            if (which_uploader != "uploadBtnbrochure" && which_uploader != "uploadBtnevent") {
                if (err.code == -600) {
                    TTAlert({
                        msg: err.message + '<br/>maximum size: ' + imgsize + 'mb',
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else if (err.file && err.file.name) {
                    TTAlert({
                        msg: t("Error: ") + err.file.name + ' ' + err.message,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    TTAlert({
                        msg: t("Error: ") + err.message,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
            } else {
                if (err.code == -600) {
                    $('#brochureerror').html(err.message + '<br/>maximum size: ' + imgsize + 'mb');
                } else if (err.file && err.file.name) {
                    $('#brochureerror').html(t("Error: ") + err.file.name + ' ' + err.message);
                } else {
                    $('#brochureerror').html(t("Error: ") + err.message);
                }
            }
            uploader.refresh(); // Reposition Flash/Silverlight
        });

        uploader.bind('FileUploaded', function (up, file, response) {
            if (which_uploader == "photos_posts" || which_uploader == "videos_posts" || which_uploader == "TT_photos_posts" || which_uploader == "uploadReview_Img" || which_uploader == "flashPhoto" || which_uploader == "TT_videos_posts" || which_uploader == "postMediaPV" || which_uploader == "CHpostMediaPV" || which_uploader == "TT_uploadBtnevent") {
                $('.upload-overlay-loading-fix-file').hide();
            } else if (pagename == "event" || pagename == "channel-add-brochure") {
                $('.upload-overlay-loading-fix').hide();
            } else if (which_uploader == "editChannelRight_data6") {
                $('.upload-overlay-loading-fix').hide();
            }
            upload_started = 0;

            var fid = file.id;
            //var item = $('#uploadContainer'+ which_uploader);
            var Jresponse;
            try {
                Jresponse = $.parseJSON(response.response);
            } catch (Ex) {

                return;
            }

            if (Jresponse.status !== 'ok') {
                if (which_uploader != "uploadBtnbrochure" && which_uploader != "uploadBtnevent") {
                    TTAlert({
                        msg: Jresponse.error,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    $('#brochureerror').html(Jresponse.error);
                }
            } else {
                $('#picfancyboxbutton').fancybox({
                    "width": Jresponse.stanwidth,
                    "height": Jresponse.stanheight+30,
                    "transitionIn": "none",
                    "transitionOut": "none",
                    "autoSize": true,
                    "type": "iframe",
                    "padding": 0,
                    "margin": 0,
                    helpers : {
                        overlay : {closeClick:false}
                    }
                });
                if (Jresponse.which_uploader == "videos_posts" || Jresponse.which_uploader == "TT_videos_posts" || (Jresponse.is_video==1 && (Jresponse.which_uploader == "postMediaPV" || Jresponse.which_uploader == "CHpostMediaPV") ) ) {
                    $('#picfancyboxbutton').fancybox({
                        "padding": 0,
                        "margin": 0,
                        "width": 375,
                        "height": 146,
                        "transitionIn": "none",
                        "transitionOut": "none",
                        "autoSize": true,
                        "type": "iframe",
                        helpers : {
                            overlay : {closeClick:false}
                        }
                    });
                    $('#picfancyboxbutton').attr("href", ReturnLink("/ajax/showthumb_post.php?path=" + Jresponse.path + "&code=" + Jresponse.code + "&name=" + Jresponse.name + "&type=" + Jresponse.which_uploader));
                    $('#picfancyboxbutton').click();
                } else if (Jresponse.which_uploader == "postMediaPV" || Jresponse.which_uploader == "CHpostMediaPV" ) {
                    var res = ReturnLink(Jresponse.path+'thumb/'+Jresponse.name);
                    var newstrimage='<img src="'+res+ '?x=' + Math.random() + '" width="86" height="48"/>';
                    parent.window.updateImagePOST(newstrimage,Jresponse.name,Jresponse.path,Jresponse.which_uploader,0);                    
                } else {
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
                    if ( Jresponse.which_uploader == "uploadReview_Img" ){
                        $('#picfancyboxbutton').attr("href", ReturnLink("/ajax/channel-fancy.php?path=" + Jresponse.path + "&code=" + Jresponse.code + "&name=" + Jresponse.name + "&type=" + Jresponse.which_uploader + "&h=" + Jresponse.stanheight + "&discover_nam=" +Jresponse.discover_nam));
                    }else{                        
                        $('#picfancyboxbutton').attr("href", ReturnLink("/ajax/channel-fancy.php?path=" + Jresponse.path + "&code=" + Jresponse.code + "&name=" + Jresponse.name + "&type=" + Jresponse.which_uploader + "&h=" + Jresponse.stanheight ));
                    }
                    $('#picfancyboxbutton').click();
                }
            }
        });
    }
}
