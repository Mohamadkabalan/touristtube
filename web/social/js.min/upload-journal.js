var applyalbumcheckcount=0;
var counterfilepubleshed=0;
var boolloderappear=false;

var countfileupload=0;
var countfileuploadnumber=0;

var defaultheight=452;


var searchdescriptiontext="for better visibility and online search, please fill here";

function verifyForm(which){

	var ok = true;
	var $currobjselected=$("#"+which);
	if((which=="logalbumadd" || which=="logalbumelected")){
		$currobjselected=$('.'+ which);
	}
	$('.uploadinfomandatory span', $currobjselected ).html('');
	$('input,select,textarea',$currobjselected ).removeClass('err').each(function(){
		var name = $(this).attr('name');
		var $parenttarget=$(this).parent();
		if( (name == 'location') || (name=='location_name') || (name=='cityname') || (name=='status') || (name=='vid') || $(this).hasClass('filevalue') || (name=='filename') || (name=='addmoretext') || (name=='vpath') ){
			
		}else{
			if(($('.uploadinfomandatory'+name, $parenttarget ).css('display')!='none' && getObjectData($(this)) == '') || (name=="country" && !$('.uploadinfomandatorycountry', $parenttarget ).hasClass('inactive') && $(this).val() == '0' && $('.uploadinfomandatorycountry', $parenttarget ).length>0)){	
				
				$('.uploadinfomandatory'+name+' span', $parenttarget ).html(t('please fill this field correctly'));
				ok = false;
			}
		}
	});
	
	var $parenttarget=$('input[name=cityname]',$currobjselected).parent();
	
	if( getObjectData($('input[name=cityname]',$currobjselected)) == '' && !$('.uploadinfomandatorycitynameaccent',$parenttarget).hasClass('inactive') && $('input[name=cityname]',$currobjselected).length>0){
		
		$('.uploadinfomandatorycitynameaccent span',$parenttarget).html(t('please fill this field correctly'));
		ok = false;
	}

	
	if(!ok){
		TTAlert({
			msg: t('Please input mandatory fields'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
		return false;
	}else{
		return true;
	}
}

var _AutocompleteCities = null;
/**
 * resets the number of cities
 */
function AutocompleteCitiesReset(){
	_AutocompleteCities = new Array();
}
/**
 * adds a city to the autocomplete records
 * @param {String} CityNameVal
 */
function AutocompleteCitiesAdd(CityNameVal){
	_AutocompleteCities.push(CityNameVal);
}
/**
 * checks if the city exists in the autocomplete array
 * @param {String} CityName
 * @returns {String}
 */
function AutocompleteCitiesExists(CityName){
	if( _AutocompleteCities === null ) return null;
	var CleanCity = removeDiacritics(CityName);
	for(var i = 0; i < _AutocompleteCities.length ; i++){
		
		if( CleanCity.toLowerCase() === _AutocompleteCities[i].value.toLowerCase() ) return _AutocompleteCities[i];
	}
	return null;
}

function addAutoComplete(which){
	var $citynameaccent = $("input[name=citynameaccent]", $('#SWFUpload_2_'+ which));
	if((which=="logalbumadd" || which=="logalbumelected")){
		$citynameaccent = $("#log_2 #"+which+"citynameaccent");
	}
	$citynameaccent.autocomplete({
		appendTo: "#MiddleInsideNormal",
		search: function(event, ui) {
			var $country = $('select[name=country]', $citynameaccent.parent() ).removeClass('err');
			var cc = $( 'option:selected', $country ).val();
			if(cc == 'ZZ'){
				$country.addClass('err');
				event.preventDefault();
			}else{
				$citynameaccent.autocomplete( "option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc) );
			}
			AutocompleteCitiesReset();
		},
		select: function(event, ui) {
			//dont show the city accent. only show the regular city.
			$citynameaccent.val( ucwords(ui.item.value) );
			$('input[name=cityname]',$citynameaccent.parent()).val(ui.item.value);
                        $('input[name=cityname]',$citynameaccent.parent()).attr('data-id',ui.item.id);
			event.preventDefault();
		},
		change: function (event, ui) {
			if(!ui.item){
				// The item selected from the menu, if any. Otherwise the property is null
				//so clear the item for force selection
				var getCity =  AutocompleteCitiesExists($citynameaccent.val());
				if( getCity === null){
					$citynameaccent.val('');
					return ;
				}
				//dont show the city accent. only show the regular city.
				$citynameaccent.val( ucwords(getCity.value) );
				$('input[name=cityname]',$citynameaccent.parent()).val(getCity.value);
			}

		}
	}).keydown(function(event){
		var code = (event.keyCode ? event.keyCode : event.which);
		//the value in the input was changed so the hidden cityaccent is no longer valid
		if( AutocompleteCitiesExists($citynameaccent.val()) === null ){
			$('input[name=cityname]',$citynameaccent.parent()).val('');
			$('input[name=cityname]',$citynameaccent.parent()).attr('data-id','');
		}
		//if enter or tab try to accept the value
		if(code === 13 || code === 9) { //Enter keycode or tab
			if( AutocompleteCitiesExists($citynameaccent.val()) === null ){
				event.preventDefault(); 
				return;
			}
			if(code === 13){
				$(this).blur();
			}
		}
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		AutocompleteCitiesAdd(item);
		//label has accented city name
		//value has the city name
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>"+ ucwords(item.value) + "</a>" )
		.appendTo( ul );
	};
}

function FileUploadCompleted(fid){
	$('#SWFUpload_2_'+fid+' .pause').hide();
	$('#SWFUpload_2_'+fid+' .resume').hide();
}

function FileUploadSaved(){
	$('.upload2 #log li .licorner .uploadinfocheckboxAllcontainer .uploadinfocheckbox3.active').each(function() {
		$(this).parent().parent().parent().remove();
	});
	$('.upload2 #log li').each(function() {
		$(this).find('.licorner .uploadinfocheckboxAllcontainer .uploadinfocheckbox3').hide();
		$(this).find('.licorner .uploadinfocheckboxAllcontainer .uploadinfocheckbox3').removeClass('active');
	});
	$(".upload-overlay-loading-fix-file").hide();
}
function FileUploadIsSaved(fid){
	return ($('#log li#SWFUpload_2_'+fid).find('span.status2').html() == 'Saved!');
}

var upload_started = 0;
function SetPageLeave(){
	if( (uploader.total.queued == 0) && (upload_started == 0) ){
		setConfirmUnload(false,t("Some media files are being processed. Leaving this page will result in loss of data. Press \"Cancel\" to continue uploading.") );
	}else{
		setConfirmUnload(true,t("Some media files are being processed. Leaving this page will result in loss of data. Press \"Cancel\" to continue uploading.") );
	}
}

function ShowError(file,msg){
	var fid = file.id;
	var item = $('#SWFUpload_2_'+fid);
	$('#SWFUpload_2_'+fid+' .pause').hide();
	$('#SWFUpload_2_'+fid+' .resume').hide();
	item.find('div.progress').css({'width': '100%','background-color' : 'red','background-image' : 'none'});
	item.addClass('success').find('span.status').html('<span class="status2">' + msg + '</span>');
	
	if(countfileupload==countfileuploadnumber){
		if(!$('.upload2 #log li').find('.togb').hasClass('closeclose')){
			$('.upload2 #log li').first().find('.togb').click();
		}
		countfileupload=countfileuploadnumber=0;
		$('.upload-loading-txt-file').hide();	
	}
}

function SaveError(FID,msg){
	var item = $('#'+FID);
	$('#'+FID+' .pause').hide();
	$('#'+FID+' .resume').hide();
	item.find('div.progress').css({'width': '100%','background-color' : 'red','background-image' : 'none'});
	item.addClass('success').find('span.status').find('span.status2').html(msg);
	$(".upload-overlay-loading-fix-file").hide();
}

function createImage(file,i){

	//for upoad
	var file_ext = file.name.split('.');
	if( file_ext.length < 2 ) return;
	file_ext = file_ext[file_ext.length - 1].toLowerCase();
	var isImage = false;
	if( (file_ext == 'png') || (file_ext == 'jpg') || (file_ext == 'jpeg') || (file_ext == 'bmp') || (file_ext == 'tiff') || (file_ext == 'tif') ) isImage = true;
	
	var template='<li id="SWFUpload_2_'+i+'" data-id="'+i+'" data-isImage="'+isImage+'">'+
		'<script type="text/javascript">'+
			'$(document).ready(function(){'+
				'$("#SWFUpload_2_'+i+' .preloader").hide();' +
				'$("#SWFUpload_2_'+i+' .preloader")' +
				'.ajaxStart(function(){' +
					'$(".preloader").hide();' +
					'$(this).show();' +
					'$("#SWFUpload_2_'+i+'").css({opacity: 0.4});' +
				'}).ajaxStop(function(){' +
					'$(this).hide();' +
					'$("#SWFUpload_2_'+i+'").css({opacity: 1});' +
				'});'+

				'$("#SWFUpload_2_'+i+'fancy").fancybox({'+
					'"padding"	:0,'+
					'"margin"	:0,'+
					'"width"			: 375,'+
					'"height"			: 146,'+
					'"transitionIn"		: "none",'+
					'"transitionOut"	: "none",'+
					'"type"				: "iframe"'+
				'});'+
			'});'+
		'</script>'+
	'<div class="licorner">'+
	'<div class="uploadinfocheckboxAllcontainer">' +
		'<div class="uploadinfocheckbox uploadinfocheckbox3" style="display:none;"><div class="uploadinfocheckboxpic"></div></div>'+
	'</div>'+
	'<div class="licornericon'+isImage+'"></div><div class="stanleft"><div class="stanleftin"></div></div><div class="togb openclose"><div class="openclose_over"><div class="openclose_overtxt">'+t("expand")+'</div></div></div>'+
	'<div class="filediv">'+
		'<div class="filename" title='+file.name+'>'+file.name+'</div>'+
		'<div class="progressbar"><div class="progress"></div></div>'+
		'<span class="status" >'+t('Processing')+'</span>'+
		'<span class="cancel" >&nbsp;</span>'+
		'<span class="pause" >&nbsp;</span>'+
		'<span class="resume" >&nbsp;</span>'+
	'</div>'+
	'</div>'+
	'<input type="hidden" name="SWFUpload_2_'+i+'" value="" class="filevalue">'+
	
	'</li>';

	$('#log').append(template);
	
	
	
	$('#SWFUpload_2_'+i+' .cancel').bind('click', function(){
		if( FileUploadIsSaved(i) ){
			$('li#SWFUpload_2_'+i).slideUp('fast').remove();
		}else{
			TTAlert({
				msg: t('Are you sure you want to cancel this upload?'),
				type: 'action',
				btn1: t('cancel'),
				btn2: t('confirm'),
				btn2Callback: function(data){
					if(data){
						var upload_file = uploader.getFile(i);
						if( !upload_file ){
							
						}else if(upload_file.status == plupload.UPLOADING ){
							uploader.stop();
							uploader.removeFile(file);
                                                        countfileupload--;
                                                        countfileuploadnumber--;
                                                        if( countfileupload<=0 ){
                                                            $('.upload-loading-txt-file').hide();
                                                        }
							setTimeout(function(){
								uploader.start();
							},100);
						}else{
							if( $('#SWFUpload_2_' + i + ' .resume').css('display')!='none' ){
                                                            $('#SWFUpload_2_' + i + ' .resume').click();
                                                            setTimeout(function() {
                                                                uploader.stop();
                                                                uploader.removeFile(file);
                                                                setTimeout(function() {
                                                                    uploader.start();
                                                                }, 100);
                                                            }, 100);
                                                        }else{
                                                           uploader.removeFile(file); 
                                                        }
                                                        countfileupload--;
                                                        countfileuploadnumber--;
                                                        if( countfileupload<=0 ){
                                                            $('.upload-loading-txt-file').hide();
                                                        }
						}
		
						SetPageLeave();
		
						deletefile('SWFUpload_2_'+i);
						
						$('li#SWFUpload_2_'+i).slideUp('fast').remove();	
					}
				}
			});
		}
	});
	
	$('#SWFUpload_2_'+i+' .pause').bind('click', function(){
		var upload_file = uploader.getFile(i);
		if( !upload_file || upload_file.status != plupload.UPLOADING ){
			
			return ;
		}
		var curbut=$(this);
		curbut.hide();
                $('#SWFUpload_2_'+i+' .resume').show();
                $('#SWFUpload_2_'+i + ' span.status').html( t("Paused") );
                $('#SWFUpload_2_'+i).find('div.progress').css('width', 0 + '%');
                PauseUpload();
	});
	
	$('#SWFUpload_2_'+i+' .resume').bind('click', function(){
		var upload_file = uploader.getFile(i);
		if(  !upload_file || upload_file.status == plupload.UPLOADING ){
			return ;
		}
		$(this).hide();
		$('#SWFUpload_2_'+i+' .pause').show();
		ResumeUpload();
	});
}

function addDescriptionRelated(id,isimage){
	if(!$("#SWFUpload_2_"+id).hasClass('desc')){
		var newdata=addanotherappend("SWFUpload_2_"+id,isimage);
		$("#SWFUpload_2_"+id).append(newdata);
		//addAutoComplete(id);
		$("#SWFUpload_2_"+id).addClass('desc');
		addButtonSave("SWFUpload_2_"+id,isimage);
		$("#SWFUpload_2_"+id).find('.privacyclass').click();		
	}
}
function addButtonSave(myid,isimage){
	var id="#"+myid;
	$(""+id+" .SaveButton_journal").unbind('click');
	$(""+id+" .SaveButton_journal").click(function (e) {
		$(".upload-overlay-loading-fix-file").show();	
		$this=$(this);	
		
		if( !verifyForm(myid) ){ 
			$(".upload-overlay-loading-fix-file").hide(); 
			return 0; 
		}
		
		$.ajax({
			url: ReturnLink('/ajax/journal_item_add.php'),
			data : {						
				title: getObjectData($(id+" input[name=title]")),
				description: getObjectData($(id+" textarea[name=description]")),  
				jid: journalID() ,  
				filename: $(id).attr('data-filename'), 
				vpath: $(id).attr('data-vpath'),  
				lat: getObjectData($(id+" input[name=lattitude]")),
				lng: getObjectData($(id+" input[name=longitude]")),
				label: getObjectData($(id+" input[name=placetakenat]")),
				keywords: getObjectData($(id+" input[name=keywords]")),				
				default:$(id+" .uploadinfocheckbox2").hasClass("active") ? 1 : 0
			},
			type : 'post',
			success: function(response){
				var Jresponse;
				$(".upload-overlay-loading-fix-file").hide();
				try{
					Jresponse = $.parseJSON( response );
				}catch(Ex){
					TTAlert({
						msg: t("Couldn't Process Request. Please try again later."),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}
		
				if( !Jresponse ){
					TTAlert({
						msg: t("Couldn't Process Request. Please try again later."),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}

				if( Jresponse.status == 'ok'){
					
					$this.parent().parent().parent().parent().parent().remove();
					
				}else{
					TTAlert({
						msg: Jresponse.msg,
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
				}
			}//sucess
		});		
	});	
}
function PauseUpload(){
	uploader.stop();
}

function ResumeUpload(){
	uploader.start();
}

var uploader = null;
var requirements_met = true;
$(document).ready(function(){
	defaultheight=412;
	
	$(document).on('click',".upload2 .togb" ,function(){
		var this_button = $(this);
		var that = this_button.parent().parent();
				
		if(this_button.parent().find(".uploadinfocheckboxAllcontainer .uploadinfocheckbox3").hasClass("active") && this_button.parent().find(".uploadinfocheckboxAllcontainer .uploadinfocheckbox3").css("display")!="none")return 0;
		
		if(this_button.hasClass("openclose")){
			var objOpenBool=false;
			$(".upload2 #log li").each(function(){
				var thisObject=$(this);
				if(thisObject.hasClass('desc') && !objOpenBool){
					objOpenBool=true;
					if(formIsFilled(thisObject)){
						TTAlert({
							msg: t("You are about to remove your filled data.<br/>Would you like to proceed?"),
							type: 'action',
							btn1: t('cancel'),
							btn2: t('confirm'),
							btn2Callback: function(data){
								if(data){
									;
									thisObject.removeClass('desc');
									thisObject.find(".formcontainer").remove();
									thisObject.animate({height: "92px"}, 500, function(){
										thisObject.find(".togb").removeClass("closeclose").addClass("openclose");
									});
									addDescriptionRelated(that.attr('data-id'),that.attr('data-isImage'));
									$("div.formcontainer",that).show();
									that.animate({height:defaultheight+"px"}, 500, function(){
										this_button.removeClass("openclose").addClass("closeclose");
										$(window).resize();
									});
								}
							}
						});
					}else{
						;
						thisObject.removeClass('desc');
						thisObject.find(".formcontainer").remove();
						thisObject.animate({height: "92px"}, 500, function(){
							thisObject.find(".togb").removeClass("closeclose").addClass("openclose");
						});
						addDescriptionRelated(that.attr('data-id'),that.attr('data-isImage'));
						$("div.formcontainer",that).show();
						that.animate({height:defaultheight+"px"}, 500, function(){
							this_button.removeClass("openclose").addClass("closeclose");
							$(window).resize();
						});
					}
				}
			});
			if(!objOpenBool){
				addDescriptionRelated(that.attr('data-id'),that.attr('data-isImage'));
				$("div.formcontainer",that).show();
				that.animate({height:defaultheight+"px"}, 500, function(){
					this_button.removeClass("openclose").addClass("closeclose");
					$(window).resize();
				});	
			}
		}else{
			if(formIsFilled(that)){
				TTAlert({
					msg: t("You are about to remove your filled data.<br/>Would you like to proceed?"),
					type: 'action',
					btn1: t('cancel'),
					btn2: t('confirm'),
					btn2Callback: function(data){
						if(data){
							;
							that.removeClass('desc');
							that.find(".formcontainer").remove();
							that.animate({height: "92px"}, 500, function(){
								this_button.removeClass("closeclose").addClass("openclose");
								$(window).resize();
							});
						}
					}
				});
			}else{
				;
				that.removeClass('desc');
				that.find(".formcontainer").remove();
				that.animate({height: "92px"}, 500, function(){
					this_button.removeClass("closeclose").addClass("openclose");
					$(window).resize();
				});
			}
			
		}
	});
	$('.upload-overlay-loading-fix-file .loadingbuttons').bind('click', function(){
		var upload_file = uploader.getFile($(this).attr('data-id'));
		if($(this).hasClass('loadingpause')){
			if( !upload_file || upload_file.status != plupload.UPLOADING ){
				return ;
			}
			$(this).removeClass('loadingpause');
			$(this).addClass('loadingresume');
			PauseUpload();
		}else{
			if(  !upload_file || upload_file.status == plupload.UPLOADING ){
				return ;
			}
			$(this).removeClass('loadingresume');
			$(this).addClass('loadingpause');
			ResumeUpload();
		}
	});
	
	$('#uploadnewalbum').mouseover(function(){
		$('#uploadnewalbumover').show();
	});
	$('#uploadnewalbum').mouseout(function(){
		$('#uploadnewalbumover').hide();
	});
	$(document).on('click',"#uploadnewalbum" ,function(){
		if($('.uploadalbum').css('display')=='none'){
			$('.upload2 #log').html('');
			$('.uploadalbumselected .logalbumelected li').html('');				
			$('.uploadalbumselected').hide();			
			$('#uploadSection').hide();
			
			$('.uploadalbumselected .logalbumelected li').css('height','76px');
			
			$(".UploadMediaRightTF .ClearNewAlbumButton").click();
			$('.uploadalbum').show();
			$('.uploadalbum').animate({'height':'407px'},500);
			$(window).resize();
		}
	});
	$(document).on('click','.'+currentlogalbumaddid+' .UploadMediaRightTF .ClearNewAlbumButton' ,function(){
		$('#privacyclass_user'+USER_PRIVACY_PUBLIC).click();
		$('.'+currentlogalbumaddid+' .inputuploadformTF').val('');
		$('.'+currentlogalbumaddid+' #logalbumaddcountry, .'+currentlogalbumaddid+' #logalbumaddcategory').val('0');
		$('.'+currentlogalbumaddid+' .inputuploaddescriptionTF').val('');
		$('textarea[name=description]').blur();
		$('input[name=placetakenat]').blur();
		$('input[name=keywords]').blur();
	});
	$(document).on('click','.'+currentlogalbumaddid+' .UploadMediaRightTF .SaveNewAlbumButton' ,function(){
		var curob = $(this).closest('.tableform');
		var privacyValue=curob.find('.privacyclass.active').attr('data-value');
		var privacyArray=new Array();
		
		if(privacyValue==USER_PRIVACY_SELECTED){
			curob.find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function(){
				var obj=$(this);
				if(obj.attr('id')=="friendsdata"){
					privacyArray.push( {friends : 1} );
				}else if(obj.attr('id')=="friends_of_friends_data"){
					privacyArray.push( {friends_of_friends : 1} );
				}else if(obj.attr('id')=="followersdata"){
					privacyArray.push( {followers : 1} );
				}else if( parseInt( obj.attr('data-id') ) != 0 ){
					privacyArray.push( {id : obj.attr('data-id') } );
				}
			});
		}	
		if( (privacyValue==USER_PRIVACY_SELECTED && privacyArray.length==0)){
			TTAlert({
				msg: t('Invalid privacy data'),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});
			return;
		}
		if(verifyForm(currentlogalbumaddid)){
			$(".upload-overlay-loading-fix").show();	
			$.ajax({
				url: ReturnLink('/ajax/journal_add.php'),
				data: {title: getObjectData($("."+currentlogalbumaddid+" input[name=title]")),cityname: $("."+currentlogalbumaddid+" input[name=cityname]").val(),cityid: $("."+currentlogalbumaddid+" input[name=cityname]").attr('data-id'),is_public: $("."+currentlogalbumaddid+" div.privacyclass.active").attr("data-value") ,description:getObjectData($("."+currentlogalbumaddid+" textarea[name=description]")),placetakenat: getObjectData($("."+currentlogalbumaddid+" input[name=placetakenat]")),keywords: getObjectData($("."+currentlogalbumaddid+" input[name=keywords]")),country: $("."+currentlogalbumaddid+" select[name=country] option:selected").val(),location: $("."+currentlogalbumaddid+" input[name=location]").val(),date: $("."+currentlogalbumaddid+" input[name=date]").attr('data-cal')},
				type: 'post',
				success: function(response){
					$(".upload-overlay-loading-fix").hide();
					var Jresponse;
					try{
						Jresponse = $.parseJSON( response );
					}catch(Ex){
						TTAlert({
							msg: t("Couldn't Process Request. Please try again later."),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						return ;
					}
					
					if( !Jresponse ){
						TTAlert({
							msg: t("Couldn't Process Request. Please try again later."),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						return ;
					}
	
					if( Jresponse.status == 'ok'){
						TTCallAPI({
							what: 'user/privacy_extand/add',
							data: {privacyValue:privacyValue, privacyArray:privacyArray, entity_type:SOCIAL_ENTITY_JOURNAL, entity_id:Jresponse.id},
							callback: function(ret){
								
							}
						});
						doJournalDisplay(Jresponse.id);
					}else{
						TTAlert({
							msg: Jresponse.msg,
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
					}
				}
			});
		}
	});
	$(document).on('click','.uploadalbumselected .UploadMediaRightTF .SaveNewAlbumButton' ,function(){
		var curob = $(this).closest('.tableform');
		var privacyValue=curob.find('.privacyclass.active').attr('data-value');
		var privacyArray=new Array();
		if(privacyValue==USER_PRIVACY_SELECTED){
			curob.find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function(){
				var obj=$(this);
				if(obj.attr('id')=="friendsdata"){
					privacyArray.push( {friends : 1} );
				}else if(obj.attr('id')=="friends_of_friends_data"){
					privacyArray.push( {friends_of_friends : 1} );
				}else if(obj.attr('id')=="followersdata"){
					privacyArray.push( {followers : 1} );
				}else if( parseInt( obj.attr('data-id') ) != 0 ){
					privacyArray.push( {id : obj.attr('data-id') } );
				}
			});
		}	
		if( (privacyValue==USER_PRIVACY_SELECTED && privacyArray.length==0)){
			TTAlert({
				msg: t('Invalid privacy data'),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});
			return;
		}
		if(verifyForm('logalbumelected')){				
			$(".upload-overlay-loading-fix").show();	
			$.ajax({
				url: ReturnLink('/ajax/journal_update.php'),
				data: {journal_id:journalID(),title:getObjectData($(".logalbumelected input[name=title]")) , cityname: $(".logalbumelected input[name=cityname]").val(),cityid: $(".logalbumelected input[name=cityname]").attr('data-id') ,is_public: $(".logalbumelected div.privacyclass.active").attr("data-value") ,description: getObjectData($(".logalbumelected textarea[name=description]")) ,placetakenat: getObjectData($(".logalbumelected input[name=placetakenat]")) ,keywords: getObjectData($(".logalbumelected input[name=keywords]")) ,country: $(".logalbumelected select[name=country] option:selected").val() , location: $(".logalbumelected input[name=location]").val() ,date: $(".logalbumelected input[name=date]").attr('data-cal') },
				type: 'post',
				success: function(response){
					$(".upload-overlay-loading-fix").hide();
					var Jresponse;
					try{
						Jresponse = $.parseJSON( response );
					}catch(Ex){
						TTAlert({
							msg: t("Couldn't Process Request. Please try again later."),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						return ;
					}
					
					if( !Jresponse ){
						TTAlert({
							msg: t("Couldn't Process Request. Please try again later."),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						return ;
					}
	
					if( Jresponse.status == 'ok'){
						TTCallAPI({
							what: 'user/privacy_extand/add',
							data: {privacyValue:privacyValue, privacyArray:privacyArray, entity_type:SOCIAL_ENTITY_JOURNAL, entity_id:Jresponse.id},
							callback: function(ret){
								
							}
						});
						$('.albumuploaddescription').html($('.uploadalbumselected .logalbumelected li').html());
						$(".albumuploaddescription input[name=title]").val($(".logalbumelected input[name=title]").val());
						$(".albumuploaddescription textarea[name=description]").val($(".logalbumelected textarea[name=description]").val());
						$(".albumuploaddescription input[name=keywords]").val($(".logalbumelected input[name=keywords]").val());
						$(".albumuploaddescription input[name=cityname]").val($(".logalbumelected input[name=cityname]").val());
						$(".albumuploaddescription input[name=cityname]").attr('data-id',$(".logalbumelected input[name=cityname]").attr('data-id'));
						$(".albumuploaddescription input[name=citynameaccent]").val($(".logalbumelected input[name=citynameaccent]").val());
						$(".albumuploaddescription select[name=category]").val($(".logalbumelected select[name=category] option:selected").val());
						$(".albumuploaddescription select[name=country]").val($(".logalbumelected select[name=country] option:selected").val());
						$(".albumuploaddescription input[name=placetakenat]").val($(".logalbumelected input[name=placetakenat]").val());
						$(".albumuploaddescription input[name=location]").val($(".logalbumelected input[name=location]").val());
						
						$('.uploadalbumselected .logalbumelected li .formcontainercreatealbum').attr('data-value',$(".logalbumelected div.privacyclass.active").attr("data-value"));
						$('.upload2 .privacyclass').each(function() {
							$(this).attr('id',$(".logalbumelected div.privacyclass.active").attr("id"));
							$(this).attr('data-value',$(".logalbumelected div.privacyclass.active").attr("data-value"));
							$(this).click();
						});
						//$('#albumlist').find('#'+journalID()).html($(".logalbumelected input[name=title]").val());
						$('.uploadalbumselected .logalbumelected li .formcontainercreatealbum').html(t("ALBUM NAME:")+" "+$(".logalbumelected input[name=title]").val());
					}else{
						TTAlert({
							msg: Jresponse.msg,
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
					}
				}
			});
		}			
	});
	$(document).on('click',".uploadalbumselected .togb",function(){
		var this_button = $(this);
		var that = $(this).parent().parent();
		if(this_button.hasClass("openclose")){
			$("div.formcontainer",$(".uploadalbumselected")).show();
			that.animate({height: "462px"}, 500, function(){
				this_button.removeClass("openclose").addClass("closeclose");
				$(window).resize();
			});
		}else{
			$("div.formcontainer",$(".uploadalbumselected")).hide();
			that.animate({height: "76px"}, 500, function(){
				this_button.removeClass("closeclose").addClass("openclose");
				$(window).resize();
			});
		}
	});
	$(document).on('click',".uploadalbumselected .ClearNewAlbumButton" ,function(){
		var targetlater=$(this).parent().parent().parent().parent().parent().find('.licorner').find('.togb');
		targetlater.click();
	});
	
	$(document).on('mouseover',".openclose" ,function(){	
		$(this).find('.openclose_overtxt').html( t('expand') );
	});
	$(document).on('mouseover',".closeclose" ,function(){	
		$(this).find('.openclose_overtxt').html( t('collapse') );
	});
	
	$(document).on('click',".UploadMediaRightTF .LaterButton" ,function(){
		var myObject=$(this).parent().parent().parent().parent();
		if(formIsFilled(myObject)){
			TTAlert({
				msg: t("You are about to remove your filled data.<br/>Would you like to proceed?"),
				type: 'action',
				btn1: t('cancel'),
				btn2: t('confirm'),
				btn2Callback: function(data){
					if(data){
						myObject.remove();
						;
					}
				}
			});
		}else{
			myObject.remove();
			;
		}		
	});
	
	$(document).on('click',".UploadMediaLeftTF .privacyclass" ,function(){
		$('.privacyclass', $(this).parent().parent()).removeClass('active');
		$(this).addClass('active');
		var which = parseInt( $(this).attr('data-value') );
		var $form_table =$(this).parent().parent().parent();
		$('.uploadinfomandatory span', $form_table ).html('');
		
		switch(which){
			case USER_PRIVACY_PRIVATE:
				initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));
				$('.uploadinfomandatory', $form_table ).addClass('inactive');
				$('.uploadinfomandatorytitle', $form_table ).removeClass('inactive');
				break;
			case USER_PRIVACY_SELECTED:
				$(this).closest('.tableform').find('.peoplecontainer_custom').show();
				$('.uploadinfomandatory', $form_table ).addClass('inactive');
				$('.uploadinfomandatorytitle', $form_table ).removeClass('inactive');				
				$('.uploadinfomandatorycategory', $form_table ).removeClass('inactive');
				$('.uploadinfomandatorycountry', $form_table ).removeClass('inactive');
				$('.uploadinfomandatorycitynameaccent', $form_table ).removeClass('inactive');
				break;
			default:
				initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));
								
				$('.uploadinfomandatory', $form_table ).addClass('inactive');
				$('.uploadinfomandatorytitle', $form_table ).removeClass('inactive');				
				$('.uploadinfomandatorycategory', $form_table ).removeClass('inactive');
				$('.uploadinfomandatorycountry', $form_table ).removeClass('inactive');
				$('.uploadinfomandatorycitynameaccent', $form_table ).removeClass('inactive');
				break;
		}
	});
	
	// Privacy box options add/remove.
	$(document).on('click',".uploadinfocheckbox" ,function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			if($(this).hasClass('uploadinfocheckbox3')){
				if($(this).parent().hasClass("friendscontainer")){
					$(this).parent().parent().find('#friendsdata').remove();
				}
			}else if($(this).hasClass('uploadinfocheckbox_friends_of_friends')){
				if($(this).parent().hasClass("friendscontainer")){
					$(this).parent().parent().find('#friends_of_friends_data').remove();
				}
			}else if($(this).hasClass('uploadinfocheckbox4')){
				if($(this).parent().hasClass("friendscontainer")){
					$(this).parent().parent().find('#followersdata').remove();
				}
			}
		}else{
			if($(this).hasClass('uploadinfocheckbox3')){
				if($(this).parent().hasClass("friendscontainer")){
					var friendstr='<div class="peoplesdata formttl13" id="friendsdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends")+'</div><div class="peoplesdataclose"></div></div>';
					
					$(this).parent().parent().find('.emailcontainer div:eq(0)').after(friendstr);
					//$(this).parent().parent().find('#friendsdata').css("width",($(this).parent().parent().find('#friendsdata .peoplesdatainside').width()+20)+"px");
				}
			}else if($(this).hasClass('uploadinfocheckbox_friends_of_friends')){
				if($(this).parent().hasClass("friendscontainer")){
					var followerstr='<div class="peoplesdata formttl13" id="friends_of_friends_data" data-email="" data-id=""><div class="peoplesdatainside">'+t("friends of friends")+'</div><div class="peoplesdataclose"></div></div>';
					$(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
					//$(this).parent().parent().find('#friends_of_friends_data').css("width",($(this).parent().parent().find('#friends_of_friends_data .peoplesdatainside').width()+20)+"px");
				}
			}else if($(this).hasClass('uploadinfocheckbox4')){
				if($(this).parent().hasClass("friendscontainer")){
					var followerstr='<div class="peoplesdata formttl13" id="followersdata" data-email="" data-id=""><div class="peoplesdatainside">'+t("followers")+'</div><div class="peoplesdataclose"></div></div>';
					$(this).parent().parent().find('.emailcontainer div:eq(0)').after(followerstr);
					//$(this).parent().parent().find('#followersdata').css("width",($(this).parent().parent().find('#followersdata .peoplesdatainside').width()+20)+"px");
				}
			}
			$(this).addClass('active');
		}
	});
	
	$(document).on('click',".peoplesdataclose" ,function(){
		var parents=$(this).parent();
		var parents_all=parents.parent().parent();
		parents.remove();
		if(parents.attr('data-id')!=''){
			if(parents.attr("data-channel") == 1){
				SelectedChannelsDelete(parents.attr('data-id'),parents_all.find('.addmore input'));	
			}else{
				SelectedUsersDelete(parents.attr('data-id'),parents_all.find('.addmore input'));
			}	
		}
		
		if(parents.attr('id')=='friendsdata'){
			parents_all.find('.uploadinfocheckbox3').removeClass('active');
		}else if(parents.attr('id')=='followersdata'){
			parents_all.find('.uploadinfocheckbox4').removeClass('active');
		}else if(parents.attr('id')=='connectionsdata'){
			parents_all.find('.uploadinfocheckbox5').removeClass('active');
		}else if(parents.attr('id')=='sponsorssdata'){
			parents_all.find('.uploadinfocheckbox6').removeClass('active');
		}else if(parents.attr('id')=='friends_of_friends_data'){
			parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
		}
	});
	
	
	if(parseInt(txt_id)!=0 && txt_id!=''){
	 	doJournalDisplay(parseInt(txt_id));
	}
});

function initResetIcon(obj){
	obj.removeClass('privacy_icon1');
	obj.removeClass('privacy_icon2');
	obj.removeClass('privacy_icon3');
	obj.removeClass('privacy_icon4');
	obj.removeClass('privacy_icon5');
}


function initResetSelectedUsers(obj){
	obj.hide();
	resetSelectedUsers(obj.find('.addmore input'));
	obj.find('.uploadinfocheckbox').removeClass('active');
	obj.find('.addmore input').val('');
	obj.find('.addmore input').blur();
	obj.find('.peoplesdata').each(function() {
		var parents=$(this);
		parents.remove();				
	});
}


var MAX_AUTO_RETRY = 3;

function InitUploader(){
	if( uploader !== null ) return ;
	
	uploader = new plupload.Uploader({
		runtimes : 'html5,flash', //,silverlight 
		browse_button : 'button1',
		button_browse_hover : true,
		//chunk_size: '1mb',
		max_file_size : ServerMaxFileSize + 'mb',
		container: 'MiddleInsideNormal',
		drop_element: 'MiddleInsideNormal',
		url :  ReturnLink('/upload-flash'),
		flash_swf_url : ReturnLink('/js/plupload/plupload.flash.swf'),
		silverlight_xap_url : ReturnLink('/js/plupload/plupload.silverlight.xap'),
		multipart_params : {
            "S" : $('#S').val(),
			journal_id: journalID()
        },
		filters : [
			{title : "Image files", extensions : "jpg,gif,png,tiff,tif,bmp,jpeg"}
		],
		init: attachCallbacks
	});
	
	uploader.bind('Init', function(up, params) {
		if( (params.runtime == 'flash') && !swfobject.hasFlashPlayerVersion("10") ){
			requirements_met = false;
			TTAlert({
				msg: t("Please update your flash installation to at least version 10"),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});
			
			$('#flash_err').show();
			$('#InsideNormal').hide();
		}
	});
	
	uploader.init();
	
	function attachCallbacks(uploader){

		uploader.bind('FilesAdded', function(up, files) {
			up.settings.multipart_params = {
									"S" : $('#S').val(),
									journal_id: journalID()
								};
			
			if(!requirements_met){
				TTAlert({
					msg: t("Please update your flash installation to at least version 10"),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return false;
			}

			setTimeout(function(){
				uploader.start();
			}, 100);

			$.each(files, function(i, file) {
				var fid = file.id;
				countfileupload++;
			});
		});

		uploader.bind('UploadFile', function(up, file) {
			var fid = file.id;
			if(fid==$('.upload-loading-txt-file').attr('data-id')){
				return;
			}
			countfileuploadnumber++;
			$('.upload-loading-txt-file').stop();
			$('.upload-loading-txt-file').attr('data-id',fid);
			$('.upload-loading-txt-file').html(t('loading')+' '+countfileuploadnumber+t(' of ')+countfileupload);
			$('.upload-loading-txt-file').show();
			createImage(file,fid);	
			upload_started = 1;
			SetPageLeave();
			$('#SWFUpload_2_'+fid+' .pause').show();
			$('#log li#SWFUpload_2_'+fid + ' span.status').html(t("Uploading ...")+" 0%");
		});

		uploader.bind('UploadProgress', function(up, file) {

			var fid = file.id;
			$('#log li#SWFUpload_2_'+fid).find('div.progress').css('width', file.percent+'%');
			$('#log li#SWFUpload_2_'+fid).find('div.progress').css('background-color','#f0bf1b');
			$('#log li#SWFUpload_2_'+fid + ' span.status').html(t("Uploading ...")+" "+file.percent+"%");
		});

		uploader.bind('Error', function(up, err) {
			
			if( err.file && err.file.name ){
				ShowError(err.file, t("Couldn't Process Request. Please try again later."));
			}else{
				ShowError(err.file, t("Couldn't Process Request. Please try again later."));
			}
			uploader.refresh(); // Reposition Flash/Silverlight
		});

		uploader.bind('FileUploaded', function(up, file,response) {
			
			upload_started = 0;
			SetPageLeave();

			var fid = file.id;
			var item = $('#log li#SWFUpload_2_'+fid);

			var Jresponse;
			try{
				Jresponse = $.parseJSON( response.response );
			}catch(Ex){
				ShowError(file, t("Couldn't Process Request. Please try again later."));
				return ;
			}

			if(Jresponse.status != 'ok' ){
				
				if( !file.failed ){
					file.failed = 1;
				}else{
					file.failed++;
				}

				if(file.failed == MAX_AUTO_RETRY ){
					ShowError(file, Jresponse.msg );
				}else{
					file.status = plupload.QUEUED;
				}
				
			}else{
				//success
				item.find('div.progress').css({'width': '100%','background-color' : '#73c563','background-image' : 'none'});
                                item.addClass('success').find('span.status').html('<span class="status2">' + t("Upload Complete")+" 100%" + '</span>');

				FileUploadCompleted(fid);

				//set thumbnail
				var thumb = $('#SWFUpload_2_'+fid+' input[name=vthumb]').val();
			
				$('#SWFUpload_2_'+fid+' .stanleft .stanleftin').html('<img src="'+AbsolutePath + '/' + Jresponse.fileinfo.vpath + Jresponse.fileinfo.thumb+'" width="86" height="48">');
				$('#SWFUpload_2_'+fid+' .SaveButton_journal').removeAttr('disabled');
				
				$('#SWFUpload_2_'+fid).attr('data-filename',Jresponse.fileinfo.filename);
				$('#SWFUpload_2_'+fid).attr('data-vpath',Jresponse.fileinfo.vpath);
				
				
				$('#SWFUpload_2_'+fid+' .stanleft .stanleftin').css('background','none');
                                $('#SWFUpload_2_' + fid + ' .togb').show();
				if(countfileupload==countfileuploadnumber){
					if(!$('.upload2 #log li').find('.togb').hasClass('closeclose')){
						$('.upload2 #log li').first().find('.togb').click();
					}
					countfileupload=countfileuploadnumber=0;
					$('.upload-loading-txt-file').hide();	
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
 * @param catalog_id if set, sets the global catalog id.
 * @return integer if catalog_id parameter is not passed returns the global catalog id
 */
function journalID(catalog_id){
	if(typeof catalog_id == 'undefined') return global_catalog_id;
	else global_catalog_id = catalog_id;
}
function doJournalDisplay(id){	
	
	if(id!=0){
		$(".upload-overlay-loading-fix").show();
		$('.upload2 #log').html('');
		$('.uploadalbum').hide();
		$('.uploadalbum').css('height','0px');
		$('.'+currentlogalbumaddid+' .UploadMediaRightTF .ClearNewAlbumButton').click();
		$("div.formcontainer",$(".uploadalbumselected")).hide();
		$(".uploadalbumselected .logalbumelected li").animate({height: "76px"}, 500, function(){
			$(".uploadalbumselected .logalbumelected li .togb").removeClass("closeclose").addClass("openclose");
			$(window).resize();
		});
		
		journalID(id);
		
		$.post(ReturnLink('/ajax/ajax_getjournaldata.php'), {id:id},function(data){
			if(data!=false){
				$('.uploadalbumselected .logalbumelected li').html(data);
				$('.uploadalbumselected .logalbumelected li textarea[name=description]').blur();
				$('.uploadalbumselected .logalbumelected li input[name=placetakenat]').blur();
				$('.uploadalbumselected .logalbumelected li input[name=keywords]').blur();
				
				addAutoComplete('logalbumelected');
				var privacyselcted=parseInt($('.uploadalbumselected .logalbumelected li .formcontainercreatealbum').attr('data-value'));
				$('.uploadalbumselected .logalbumelected li #privacyclass_user'+privacyselcted).click();
				
				$('.uploadalbumselected').show();		
				$('#uploadSection').show();
				
				if(privacyselcted==USER_PRIVACY_SELECTED){
					$('.uploadalbumselected .logalbumelected li').find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function(){
						var obj=$(this);
						if(obj.attr('id')=="friendsdata"){
							//obj.css("width",(obj.find('.peoplesdatainside').width()+20)+"px");
						}else if(obj.attr('id')=="friends_of_friends_data"){
							//obj.css("width",(obj.find('.peoplesdatainside').width()+20)+"px");
						}else if(obj.attr('id')=="followersdata"){
							//obj.css("width",(obj.find('.peoplesdatainside').width()+20)+"px");
						}else if( parseInt( obj.attr('data-id') ) != 0 ){
							SelectedUsersAdd(obj.attr('data-id'));
							//obj.css("width",(obj.find('.peoplesdatainside').width()+20)+"px");
						}
					});
				}
				
				$(".upload-overlay-loading-fix").hide();
				
				Calendar.setup({
					inputField : "logalbumelecteddate",
                noScroll  	 : true,
					trigger    : "logalbumelecteddate",
					align:"B",
					onSelect   : function() {
						var date = Calendar.intToDate(this.selection.get());
						$('#logalbumelecteddate').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
						this.hide();
					},
					dateFormat : "%d-%m-%Y"
				});
				
				$('.albumuploaddescription').html($('.uploadalbumselected .logalbumelected li').html());
				
				addmoreusersautocomplete_custom_journal( $('.uploadalbumselected .logalbumelected li #addmoretext_privacy') );
				////////////////////////
				//initalize the uploader
				InitUploader();
				
			}
		});
	}
	
}

function formIsFilled(obj){
	var ok = false;
	if(getObjectData($('input[name=title]',obj))!="" && $('input[name=title]',obj).length>0){
		ok = true;
	}else if(getObjectData($('input[name=citynameaccent]',obj))!="" && $('input[name=citynameaccent]',obj).length>0){
		ok = true;
	}else if(getObjectData($('textarea[name=description]',obj))!="" && $('textarea[name=description]',obj).length>0){
		ok = true;
	}else if(getObjectData($('input[name=placetakenat]',obj))!="" && $('input[name=placetakenat]',obj).length>0){
		ok = true;
	}else if(getObjectData($('input[name=keywords]',obj))!="" && $('input[name=keywords]',obj).length>0){
		ok = true;
	}
	return ok;
}