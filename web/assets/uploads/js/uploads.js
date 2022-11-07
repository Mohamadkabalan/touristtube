var uploader = null;
var ttModal = null;
var image_selected = null;
var requirements_met = true;
var pausemode = 0;
var MAX_AUTO_RETRY = 3;
var countfileupload = 0;
var countfileuploadnumber = 0;

$(document).ready(function () {
    InitUploader( 'uploads_btn', 'uploads_content', ServerMaxFileSize );
    resetAlbums();
    $(document).on('click',".upload_menu_btn" ,function()
    {
	var $this = $(this);
	$('.upload_menu_btn').removeClass('active');
	$this.addClass('active');
	if( $this.attr('data-value') == 'albums' )
	{
	    $('.create_new_album').show();
	    $('.album_list_data').show();
	} else {
	    $('.create_new_album').hide();
	    $('.album_list_data').hide();
	    resetAlbums();
	}
    });

    $('.stop_all_upload').bind('click', function () {
	ttModal.confirm(Translator.trans("Are you sure you want to cancel pending uploads?"), function (btn) {
	    if(btn == "ok"){
		PauseUpload();
		uploader.splice();
		uploader.refresh();
		ResumeUpload();
		countfileupload = countfileuploadnumber = 0;
		$('.upload-loading-txt-file').hide();
		$('.stop_all_upload').hide();
		$('.upload-loading-sep').hide();
		$('.items_img').each(function(){
		    if( !$(this).hasClass('success') ) $(this).remove();
		});
	    }
	}, null, {ok:{value:Translator.trans("confirm")},cancel:{value:Translator.trans("cancel")}});

    });
});

function InitUploader( which_uploader, container_uploader, imgsize ) {
    if( !ttModal ) {
	ttModal = window.getTTModal("myModalZ", {});
    }

    uploader = new plupload.Uploader({
        runtimes: 'html5,flash', //,silverlight 
	container: container_uploader,
	browse_button: which_uploader,
	drop_element: which_uploader,
        button_browse_hover: true,
        //chunk_size: '1mb',
        max_file_size: imgsize + 'mb',
        url: generateLangURL( '/ajax/upload-file', 'empty' ),
        flash_swf_url: generateLangURL( '/assets/uploads/js/plupload/plupload.flash.swf', 'empty' ),
        silverlight_xap_url: generateLangURL( '/assets/uploads/js/plupload/plupload.silverlight.xap', 'empty' ),
        multipart_params: {
            album_id: CatalogID(),
            channel_id: channelGlobalID()
        },
        filters: [
            {title: "All media files", extensions: "jpg,gif,png,tiff,tif,bmp,jpeg,wmv,mov,flv,mp4,avi,mts,m4v"},
            {title: "Image files", extensions: "jpg,gif,png,tiff,tif,bmp,jpeg"},
            {title: "Video files", extensions: "wmv,mov,flv,mp4,avi,mts,m4v,mpg"}
        ],
        init: attachCallbacks
    });
    
    

    uploader.bind('Init', function (up, params) {
        if ((params.runtime == 'flash') && !swfobject.hasFlashPlayerVersion("10")) 
	{
            requirements_met = false;
            ttModal.alert(Translator.trans("Please update your flash installation to at least version 10"), null, null, {ok:{value:Translator.trans("close")}});
        }
    });

    uploader.init();

    function attachCallbacks(uploader) {

        uploader.bind('FilesAdded', function (up, files) {
	    // Count the files.
            $.each(files, function (i, file) {
                var fid = file.id;
                countfileupload++;
                $('.upload-loading-txt-file').html(Translator.trans('loading') + ' '+ countfileuploadnumber + Translator.trans(' of ') + countfileupload);
            });
            // Update the counter display when a user adds a file to the upload list.
            $('.upload-loading-txt-file').html(Translator.trans('loading') + ' '+ countfileuploadnumber + Translator.trans(' of ') + countfileupload);

            up.settings.multipart_params = {
                album_id: CatalogID(),
		channel_id: channelGlobalID()
            };

            if (!requirements_met) 
	    {
                ttModal.alert(Translator.trans("Please update your flash installation to at least version 10"), null, null, {ok:{value:Translator.trans("close")}});
                return false;
            }
            if( files.length > 6 ) {
		ttModal.alert(Translator.trans("Only 6 files per upload !"), null, null, {ok:{value:Translator.trans("close")}});
		    
                uploader.stop();
                $.each(files, function (i, file) {
                    uploader.removeFile(file);
                });
                countfileupload = countfileuploadnumber = 0;
                return false;
            }

            if (pausemode != 1) {
                setTimeout(function () {
                    ResumeUpload();
                }, 100);
            }
        });

        uploader.bind('UploadFile', function (up, file) {
            var fid = file.id;
            if (fid == $('.upload-loading-txt-file').attr('data-id')) {
                return;
            }

            countfileuploadnumber++;
            $('.upload-loading-txt-file').attr('data-id', fid);
            $('.upload-loading-txt-file').html(t('loading') + ' '+ countfileuploadnumber + t(' of ') + countfileupload);
            $('.upload-loading-txt-file').show();
            $('.stop_all_upload').show();
            $('.upload-loading-sep').show();

            createImage(file, fid);
            SetPageLeave();
//            $('#pic_upload_'+ fid + ' .pause').show();
            $('#pic_upload_'+ fid + ' .upload_perc_txt span').html("0%");
            $('#pic_upload_'+ fid + ' .pink_perc').width("0%");
        });

        uploader.bind('UploadProgress', function (up, file) {

            var fid = file.id;
            $('#pic_upload_'+ fid).find('div.progress').css('width', file.percent + '%');
            $('#pic_upload_'+ fid).find('div.progress').css('background-color', '#f0bf1b');
            $('#pic_upload_'+ fid + ' .upload_perc_txt span').html(file.percent+"%");
            $('#pic_upload_'+ fid + ' .pink_perc').width(file.percent+"%");
        });

        uploader.bind('Error', function (up, err) {
            $('.upload-overlay-loading-fix').hide();
	    
	    if (err.code == -600) 
	    {
		ttModal.alert(err.message + '<br/>maximum size: '+ imgsize + 'mb', null, null, {ok:{value:Translator.trans("close")}});					
	    } 
	    else if (err.code == -601) 
	    {
		ttModal.alert(Translator.trans("File extension error, Please use: jpg,gif,png,tiff,tif,bmp,jpeg,wmv,mov,flv,mp4,avi,mts,m4v"), null, null, {ok:{value:Translator.trans("close")}});					
	    } 
	    else if (err.file && err.file.name) 
	    {
		ttModal.alert(Translator.trans("Error: ") + err.file.name + ' '+ err.message, null, null, {ok:{value:Translator.trans("close")}});		
	    } 
	    else 
	    {
		ttModal.alert(Translator.trans("Error: ") + err.message, null, null, {ok:{value:Translator.trans("close")}});
	    }
	    
	    if (err.file && err.file.name) {
                showError(err.file, Translator.trans("Error uploading file: "+err.file.name+". Please try again later."));
            } else {
                showError(err.file, Translator.trans("Couldn't Process Request. Please try again later."));
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

	    $('#pic_upload_'+ fid).attr('data-path', Jresponse.path );
	    $('#pic_upload_'+ fid).attr('data-name', Jresponse.name );
	    $('#pic_upload_'+ fid).attr('data-id', Jresponse.id );
            if (Jresponse.status !== 'ok') 
	    {
		showError( file, Jresponse.msg );
		ttModal.alert(Jresponse.msg, null, null, {ok:{value:Translator.trans("close")}});
            } 
	    else 
	    {
		$('#pic_upload_'+ fid + ' .edit_bar_pic').show();
                $('#pic_upload_'+ fid).addClass('success');
		var img_str ='<img src="'+Jresponse.img+'">';
		$('#pic_upload_'+ fid+' .grey_upload_bar').remove();
		$('#pic_upload_'+ fid+' .error_container').remove();
		$('#pic_upload_'+ fid+' .table-cell').html(img_str);
		$('#pic_upload_'+ fid+' .absolute_container_add').addClass('text_align_center');
		$('#pic_upload_'+ fid+' .edit_bar').show();
		
                if (countfileupload == countfileuploadnumber)
		{
//                    if (!$('.upload2 #log li').find('.togb').hasClass('closeclose')) {
//                        $('.upload2 #log li').first().find('.togb').click();
//                    }
                    countfileupload = countfileuploadnumber = 0;
                    $('.upload-loading-txt-file').hide();
                    $('.stop_all_upload').hide();
                    $('.upload-loading-sep').hide();
                }		
            }	    
        });
    }
}

/**
 * the global catalog id
 */
var global_catalog_id = null;
/**
 * gets or sets the global catalog id
 * @param integer catalog_id if set, sets the global catalog id.
 * @return integer if catalog_id parameter is not passed returns the global catalog id
 */
function CatalogID(catalog_id) {
    if (typeof(catalog_id) == 'undefined')
        return global_catalog_id;
    else
        global_catalog_id = catalog_id;
}

function ResumeUpload() {
    pausemode = 0;
    uploader.start();
}

function PauseUpload() {
    pausemode = 1;
    uploader.stop();
}

function createImage(file, i)
{
    //for upoad
    var file_ext = file.name.split('.');
    if (file_ext.length < 2)
        return;
    file_ext = file_ext[file_ext.length - 1].toLowerCase();
    var isImage = false;
    if ((file_ext == 'png') || (file_ext == 'jpg') || (file_ext == 'jpeg') || (file_ext == 'bmp') || (file_ext == 'tiff') || (file_ext == 'tif'))
        isImage = true;
    
    var template ='<div id="pic_upload_'+ i + '" data-key="'+ i + '" data-path="" data-name="" data-id="" class="col-lg-3 col-md-4 col-xs-6 small_col_padding xxs_full_width margin_bottom_40 items_img">'+
                        '<div class="row no-margin">'+
                            '<div class="col-xs-12 nopad">'+
                                '<div class="background_grey_container">'+
                                    '<img alt="grey_backgroud_img" src="'+generateMediaURL('/media/images/channel_new/grey_backgroud_img.jpg')+'" class="grey_backgroud_img">'+
                                    '<div class="absolute_container_add">'+
					'<div class="table-wrapper">'+
					    '<span class="table-cell">'+
					    '</span>'+
					'</div>'+
                                        '<div class="grey_upload_bar">'+
                                            '<p class="upload_perc_txt">'+Translator.trans("uploading")+'...<span></span></p>'+
                                            '<span class="pink_perc" style="width: 0%;"></span>'+
                                        '</div>'+
					'<div class="error_container">'+
					    '<div class="pink_close_logo cancel_image">'+
						'<span>x</span>'+
					    '</div>'+
					    '<div class="text_relative_container">'+
						'<p class="upload_error">'+Translator.trans('Error uploading')+'</p>'+
						'<p class="try_again_grey"></p>'+
					    '</div>'+
					'</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="row no-margin min_height_30">'+
			    '<div class="col-xs-12 nopad edit_bar">'+
                                '<div class="edit_bar_pic"><img src="'+generateMediaURL('/media/images/bag_new/blue_edit_logo.png')+'" class="edit_logo"></div>'+
                                '<p class="close_but cancel_image">x</p>'+
                            '</div>'+
			'</div>'+
                    '</div>';
    
    $('.item_upload_container').append(template);
    $('#pic_upload_'+ i + ' .edit_bar_pic').hide();    
    $('#pic_upload_'+ i + ' .edit_bar_pic').bind('click', function () 
    {
	var $this = $(this);
	var $parent = $this.closest('.items_img');
	var data_id = $parent.attr('data-id');
	var $url = generateLangURL( '/add_media', 'empty' )+'?channel_id='+channelGlobalID()+'&album_id='+CatalogID()+'&temp_id='+data_id;
	image_selected = $parent;
	createPopup( $url, Translator.trans("Save info") );
    });
    
    $('#pic_upload_'+ i + ' .cancel_image').bind('click', function () 
    {
	var $this = $(this);
	var $parent = $this.closest('.items_img');
	var $key = $parent.attr('data-key');
	ttModal.confirm(Translator.trans("Are you sure you want to cancel this upload?"), function (btn) {
	    if(btn == "ok"){
		var upload_file = uploader.getFile($key);
		if ( upload_file ) {
		    if (upload_file.status == plupload.UPLOADING) 
		    {
			PauseUpload();
		    }
		    uploader.removeFile(file);
		    countfileupload--;
		    countfileuploadnumber--;
		    if (upload_file.status == plupload.UPLOADING) 
		    {
			setTimeout(function () {
			    ResumeUpload();
			}, 200);
		    }
		}
		
		if (countfileupload <= 0) {
		    countfileupload = countfileuploadnumber = 0;
		    $('.upload-loading-txt-file').hide();
		    $('.stop_all_upload').hide();
		    $('.upload-loading-sep').hide();
		}

		SetPageLeave();
		
		deleteTempFile( $parent );
		$parent.remove();
	    }
	}, null, {ok:{value:Translator.trans("confirm")},cancel:{value:Translator.trans("cancel")}});
    });
}

function deleteTempFile( $this )
{
    var data_path = $this.attr('data-path');
    var data_name = $this.attr('data-name');
    var data_id = $this.attr('data-id');
    
    if( data_name == '' || data_path == '' )
    {
	return true;
    }
    
    $('.upload-overlay-loading-fix').show();
    $.ajax({
	url: generateLangURL( '/ajax/media_temp_delete', 'empty' ),
	data: { name:data_name, id:data_id, path:data_path },
	type: 'post',
	success: function (data) {
	    $('.upload-overlay-loading-fix').hide();
	    var jres = null;
	    try {
		jres = data;
		var status = jres.status;
	    } catch (Ex) {
	    }
	    if (!jres) {
		return;
	    }
	}
    });
}

function SetPageLeave() {
//	if( (uploader.total.queued == 0) && (upload_started == 0) ){
    if ((uploader.total.queued == 0)) {
        setConfirmUnload(false, Translator.trans("Some media files are being processed. Leaving this page will result in loss of data. Press \"Cancel\" to continue uploading."));
    } else {
        setConfirmUnload(true, Translator.trans("Some media files are being processed. Leaving this page will result in loss of data. Press \"Cancel\" to continue uploading."));
    }
}

function showError(file, msg) {
    var fid = file.id;
    var item = $('#pic_upload_'+ fid);
    item.find('.table-wrapper').hide();
    item.find('.grey_upload_bar').hide();
    item.find('.error_container').show();
    item.find('.error_container .try_again_grey').html(msg);

    if (countfileupload == countfileuploadnumber) 
    {
        countfileupload = countfileuploadnumber = 0;
        $('.upload-loading-txt-file').hide();
        $('.stop_all_upload').hide();
        $('.upload-loading-sep').hide();
    }
}

function selectAlbum( $this ) {
    var id = $($this).val();
    if ( id != 0 && id!='' ) {
        CatalogID(id);
	$('.album_title_data span').html( $('select[name=album] option:selected').text() );
        $(".album_title_data").show();
    } else {
	CatalogID(null);
	$('.album_title_data span').html('');
	$(".album_title_data").hide();
    }
}

function resetAlbums() {
    CatalogID(null);
    $('select[name=album]').val($('select[name=album] option:eq(0)').val());
    $('.album_title_data span').html('');
    $(".album_title_data").hide();
}

function closeModalPopup(jres)
{
    ttModal.hide();
    
    var new_album = '<option value="'+jres.id+'">'+jres.name+'</option>';
    $('select[name=album] option:eq(0)').after(new_album);
    $('select[name=album]').val($('select[name=album] option:eq(1)').val());
    $('select[name=album]').change();
    
    $('.upload-overlay-loading-fix').show();
    setTimeout(function() {
	if( !ttModal ) {
	    ttModal = window.getTTModal("myModalZ", {});
	}
	ttModal.alert(jres.msg, null, null, {ok:{value:"close"}});
	$('.upload-overlay-loading-fix').hide();
    }, 2000);    
}

function closeCreateModalMediaPopup()
{
    ttModal.hide();    
    $('.upload-overlay-loading-fix').show();
    image_selected.remove();
    image_selected = null;
    setTimeout(function() {
	if( !ttModal ) {
	    ttModal = window.getTTModal("myModalZ", {});
	}
	$('.upload-overlay-loading-fix').hide();
    }, 2000);    
}