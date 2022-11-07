function initCropAndUpload(){
	var cropperUpload = $(document).find(".cropperUpload");
	if(cropperUpload.length>0){
		cropperUpload.each(function(){
			var myCropper = $(this);
			var title = myCropper.attr('data-title');
			var image_url = myCropper.attr('data-image-url');
			var popup_title = myCropper.attr('data-popup-title');
			var cancel_label = myCropper.attr('data-cancel-label');
			var save_label = myCropper.attr('data-save-label');
			var aspect_ratio = myCropper.attr('data-aspect-ratio');
			var image_size = myCropper.attr('data-image-size');
			var extra_form_data = $.parseJSON( myCropper.attr('data-extra-form-data') );			
			var submit_url = myCropper.attr("data-submit-url");
			var myWidth = myCropper.attr("data-width");
			var myHeight = myCropper.attr("data-height");
			
			var mystr = '<div class="row no-margin">'+
	'<div class="col-xs-12 nopad">'+
		'<label class="label" data-toggle="tooltip" title="'+title+'">';
		    if( title != '' ){
			mystr += '<div class="col-xs-12 nopad cropper_input_label_txt">'+title+'</div><br/>';
		    }
		  mystr += '<img class="rounded" id="avatar" src="'+image_url+'" alt="'+title+'">'+
		  '<input type="file" class="sr-only" id="input" name="image" accept="image/*">'+
		'</label>'+
		'<div class="progress">'+
		  '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>'+
		'</div>'+
	'</div>'+
	'<div class="col-xs-12 nopad">'+
		'<div class="alert" role="alert"></div>'+
		'<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">'+
		  '<div class="modal-dialog" role="document">'+
			'<div class="modal-content">'+
			  '<div class="modal-header">'+
				'<h5 class="modal-title" id="modalLabel">'+popup_title+'</h5>'+
			  '</div>'+
			  '<div class="modal-body">'+
				'<div class="img-container">'+
				  '<img id="image" src="'+image_url+'">'+
				'</div>'+
			  '</div>'+
			  '<div class="modal-footer">'+
				'<button type="button" class="btn btn-secondary" data-dismiss="modal">'+cancel_label+'</button>'+
				'<button type="button" class="btn btn-primary" id="crop">'+save_label+'</button>'+
			  '</div>'+
			'</div>'+
		  '</div>'+
		'</div>'+
	'</div>'+
'</div>'+
'<style type="text/css">'+
	'.label{cursor: pointer;}'+
	'.progress{display: none;width:230px;margin: .2em .6em .3em;}'+
	'.alert{display: none;}'+
	'.img-container img{max-width: 100%;}'+
	'.modal-dialog{max-width: 1000px !important;}'+
'</style>';
			myCropper.append(mystr);
			singleCropAndUpload(myCropper,myWidth,myHeight,aspect_ratio,image_size,extra_form_data,submit_url);
		}); 
	}
}

function singleCropAndUpload(myHolder,myWidth,myHeight,aspect_ratio,image_size,extra_form_data,submit_url){
	
	window.addEventListener('DOMContentLoaded', function (){
	  var avatar = myHolder.find('#avatar');//document.getElementById('avatar');
	  var image = myHolder.find('#image');//document.getElementById('image');
	  var input = myHolder.find('#input');//document.getElementById('input');
	  var crop = myHolder.find('#crop');//document.getElementById('crop');
	  avatar = avatar[0];
	  image = image[0];
	  input = input[0];
	  crop = crop[0];
	  var $progress = myHolder.find('.progress');
	  var $progressBar = myHolder.find('.progress-bar');
	  var $alert = myHolder.find('.alert');
	  var $modal = myHolder.find('#modal');
	  var cropper;
	
	  input.addEventListener('change', function (e) {
		  var files = e.target.files;
		  var done = function (url) {
			  input.value = '';
			  image.src = url;
			  $alert.hide();
			  $modal.modal('show');
		  };

		  var reader;
		  var file;
		  var url;

		  if (files && files.length > 0) {
			  file = files[0];

			  if (URL) {
				  done(URL.createObjectURL(file));
			  } else if (FileReader) {
				  reader = new FileReader();
				  reader.onload = function (e) {
					done(reader.result);
				  };
				  reader.readAsDataURL(file);
			  }
		  }
	  });

	$modal.on('shown.bs.modal', function () {
		cropper = new Cropper(image, {
			aspectRatio: aspect_ratio,
			viewMode: 3,
			minCropBoxWidth:myWidth,
			minCropBoxHeight:myHeight,
			minCanvasWidth: myWidth,
			minCanvasHeight: myHeight,
			minContainerWidth: myWidth,
			minContainerHeight: myHeight
			
		});
	}).on('hidden.bs.modal', function () {
		cropper.destroy();
		cropper = null;
	});


	crop.addEventListener('click', function () {
		var initialAvatarURL;
		var canvas;

		$modal.modal('hide');
		
		if (cropper) {
			canvas = cropper.getCroppedCanvas({width: myWidth, height: myHeight});

			initialAvatarURL = avatar.src;
			var imageData = canvas.toDataURL();
			avatar.src = imageData;
			$progress.show();
			$alert.removeClass('alert-success alert-warning');
			
			canvas.toBlob(function (blob) {
				var formData1 = new FormData();
				formData1.append('file', blob);
				var formData = extra_form_data;

				var dataStr='';
				$.each(formData, function (index, value) {
				    if( dataStr!= '' ) dataStr +='&';
				    dataStr += index+"="+value;
				});
				
				$.ajax(submit_url+'?'+dataStr, {
					method: 'POST',
					data: formData1,
					processData: false,
					contentType: false,
					
					xhr: function () {
						var xhr = new XMLHttpRequest();
						xhr.upload.onprogress = function (e) {
							var percent = '0';
							var percentage = '0%';
							if (e.lengthComputable) {
								percent = Math.round((e.loaded / e.total) * 100);
								percentage = percent + '%';
								$progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
							}
						};
						return xhr;
					},

					success: function (data) {					    
					    var jres = null;
					    try {
						jres = data;
						var status = jres.status;
					    } catch (Ex) {
					    }
					    if (!jres) {
						$alert.show().addClass('alert-success').text(Translator.trans("Couldn't save please try again later"));
						return;
					    }
					    if (jres.status == 'ok') {
						location.reload();
						$alert.show().addClass('alert-success').text(Translator.trans('Upload success'));
					    } else {
						$progress.hide();
						ttModal.alert(jres.msg, null, null, {ok:{value:Translator.trans("close")}});
					    }
					},

					error: function () {
					    avatar.src = initialAvatarURL;
					    $alert.show().addClass('alert-warning').text(Translator.trans('Upload error'));
					},

					complete: function () {
					    $progress.hide();
					},
				});
			});
		}
	});
  });
}