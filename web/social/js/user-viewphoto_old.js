var privacyValue=-1;
var privacyArray=new Array();
var $modal_image = null;
var $modal_content_container = null;
$(document).ready(function(e) {
    $modal_content_container = $("#imgInside").parent();
    $modal_image = $("#imgInside");
    $('.exportVideo').on('click',function(){
        if(checkArray(SelectionArray) != 0){
            $('.save-thumb').hide();
            $('.delThumbs').hide();
            $('.save-thumb-separator').hide();
            $('.save-thumb-message').show();
            $.post(ReturnLink('/ajax/ajax_edit_video.php'),{ frames:SelectionArray, allf:AllFrames, id:$('.all_container').attr('data-id') }, function(data){
                SelectionArray = [];
                parent.window.closeFancyExportVideo($('.all_container').attr('data-export'));
            });
        }
    });    
    $(document).on('click',".imageitem" ,function(){
        $this = $(this).find('.thumb-pic-img');
        var ThisRel = $this.attr('rel');
        var ThisIndex = ThisRel - 1;
        if(! $this.hasClass('selected')){
            $(this).find('.yellowborder').addClass("active");
            $this.addClass('selected');
            if(jQuery.inArray(ThisRel, SelectionArray) == -1){
                SelectionArray[ThisIndex] = ThisRel;
            }
            $('.delThumbs').addClass("active");
        }else{
            $(this).find('.yellowborder').removeClass("active");
            $this.removeClass('selected');
            SelectionArray[ThisIndex] = '';
            var up_delete=true;
            $('.yellowborder.active').each(function(){
                if($(this).closest('.imageitem').css('display')!='none'){
                    up_delete=false;                    
                }
            });
            if(up_delete){
                $('.delThumbs').removeClass("active");
            }else{
               $('.delThumbs').addClass("active"); 
            }
        }
    });
    /*$(document).on('click',".yellowborder.active" ,function(){
        $this = $(this);
        var ThisRel = $this.prev().attr('rel');
        var ThisIndex = ThisRel -1;
        if($this.prev().hasClass('selected')){
            $this.removeClass("active");
            $this.prev().removeClass('selected');
            SelectionArray[ThisIndex] = '';
        }
    });*/
    $('.delThumbs').on('click', function(){
            DeletedArray = [];
            $(".imageitem .thumb-pic-img").each(function(){
                    var ThisAttr  = $(this).attr('rel');
                    if(jQuery.inArray(ThisAttr, SelectionArray) != -1){
                            $(this).parent().css('display','none');
                            DeletedArray.push(ThisAttr);
                    }
            });
            if(DeletedArray.length!=0){
                    $('.undoThumbs').css('display','block');
            }
            $('.delThumbs').removeClass("active");
    });

    $('.undoThumbs').on('click',function(){
            SelectionArray = [];
            $(".imageitem .thumb-pic-img").each(function(){
                    var ThisAttr  = $(this).attr('rel');
                    if(jQuery.inArray(ThisAttr, DeletedArray) != -1){
                            $(this).parent().css('display','block');
                            $(this).next().removeClass("active");

                    }
            });
            $(".imageitem .thumb-pic-img").removeClass('selected');
    });
    $('.edit_input').blur();
	addAutoCompleteList();
	if(parseInt(channelid)==0){
		addmoreusersautocomplete_custom_journal( $('#addmoretext_custum_privacy_detailed') );
	}
	$(document).on('click',".top_arrow" ,function(){
		$('.hidden_buttons').toggle();
	});
	$(document).on('click','.overdatabutenable',function(){
		var $this=$(this);
		var entity_id = $('.all_container').attr('data-id');
		var entity_type = $('.all_container').attr('data-type');
		
		if(String(""+$this.attr('data-status'))=="1"){
			enableSharesComments_log(entity_id, entity_type, 0);
			$this.attr('data-status','0');
			$this.find('.overdatabutntficon').addClass('inactive');
		}else{
			enableSharesComments_log(entity_id, entity_type, 1);
			$this.attr('data-status','1');
			$this.find('.overdatabutntficon').removeClass('inactive');
		}
	});
	$(document).on('click',"#canceleventdata" ,function(){
		$('.error_valid_privacy').html('');
		parent.window.closeFancy();
	});
	$(document).on('click',"#add_but" ,function(){
		parent.window.addMediaUpload($('.all_container').attr('data-upuri'));
	});
	$(document).on('click',"#remove_button" ,function(){
		parent.window.addMediaRemove($('.all_container').attr('data-link'));
	});
	$(document).on('click',"#cancel_custom_privacy" ,function(){
		$('.error_valid_privacy').html('');
		$('.peoplecontainer_custom').addClass('displaynone');
		$('.info_pop_container').show();
	});
	
	$(document).on('click','#saveeventdata_edit' ,function(){
		
		var mycurrobj='right_container';	
			
		if(verifyFormList(mycurrobj)){	
                    var curob = $(this).closest('#right_container');
                    if(parseInt(channelid)==0){
                        privacyValue=parseInt($(".privacyclass.active").attr('data-value'));
                    }else{
                        privacyValue = USER_PRIVACY_PUBLIC;
                    }
                    
                    if( (privacyValue==USER_PRIVACY_SELECTED && privacyArray.length==0)){
                        $('.error_valid').html(t('Invalid privacy data'));
                        return;
                    }                    
                    var cobthis=$(this);
                    $.post(ReturnLink('/ajax/profile_media_save.php'), {privacyValue:privacyValue, privacyArray:privacyArray,vid:$('.all_container').attr('data-id'),title:getObjectData($("#edit_title")),cityname: $("#edit_cityname").val(),cityid: $("#edit_cityname").attr('data-id'),citynameaccent: $("#edit_citynameaccent").val(),is_public: $(".privacyclass.active").attr("data-value") ,description: getObjectData($("#edit_description")) ,category: $("#edit_category").val(),placetakenat: getObjectData($("#edit_placetakenat")),keywords: getObjectData($("#edit_keywords")),country: $("#edit_country").val(),location: ''},function(data){
                        if(data!=false){
                           parent.window.closeFancyReload(is_album);
                        }
                    });
		}			
	});
        $(document).on('click','#photo_but' ,function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $("#info_but").removeClass("active");
                $("#edit_info_part").hide();
                $("#edit_photo_part").show();
            }
        });
        $(document).on('click','#info_but' ,function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $("#photo_but").removeClass("active");
                $("#edit_info_part").show();
                $("#edit_photo_part").hide();
            }
        });
        $(document).on('click','#save_photo_edit' ,function(){
            
            var left = $('#imgPhoto').css('left');
            var top = $('#imgPhoto').css('top');
//            console.log(left+'\n'+top);
            left = parseInt( left.substring(0, left.length - 2) );
            top = parseInt( top.substring(0, top.length - 2) );
//            console.log(left+'\n'+top);
            left = parseInt( left *  $('#imgPhoto').data('ow') / $('#imgPhoto').width() );
            top = parseInt( top * $('#imgPhoto').data('oh') / $('#imgPhoto').height() );
//            console.log(left+'\n'+top+'\n'+$('#imgPhoto').data('ow')+'\n'+$('#imgPhoto').data('oh')+'\n'+$('#imgPhoto').width()+'\n'+$('#imgPhoto').height());
            ImageEditor.processImage("action=save&x="+Math.abs(left)+"&y="+Math.abs(top));
            setTimeout(function(){
                parent.window.closeFancyReload(is_album);
            }, 1000);
			
            //window.location.href = window.location.href+"&photo=photo";
           //location.reload();
	   /* var left = $('#imgPhoto').css('left');
            var top = $('#imgPhoto').css('top');
            left = parseInt( left.substring(0, left.length - 2) );
            top = parseInt( top.substring(0, top.length - 2) );
            left = parseInt( left *  $('#imgPhoto').data('ow') / $('#imgPhoto').width() );
            top = parseInt( top * $('#imgPhoto').data('oh') / $('#imgPhoto').height() );
            $.ajax({
                url: ReturnLink('/ajax/profile_image_set.php'),
                type: 'post',
                data: {left: left, top: top},
                success: function(response){
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
                        $('#AccountProfileImage').attr('src',Jresponse.path);
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
            });*/			
	});
	$(document).on('click',"#okeventdata" ,function(){
		var curob=$(this).parent().parent();
		$('.error_valid_privacy').html('');
		privacyValue=parseInt($(".privacyclass.active").attr('data-value'));
		privacyArray=new Array();
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
		
		if( (privacyValue==USER_PRIVACY_SELECTED && privacyArray.length>0) || privacyValue!=USER_PRIVACY_SELECTED){
			$('.peoplecontainer_custom').addClass('displaynone');
			$('.info_pop_container').show();
		}else{
			$('.error_valid_privacy').html(t('Invalid privacy data'));	
		}
	});
	$(document).on('click',".privacyclass" ,function(){
		$('.privacyclass', $(this).parent().parent()).removeClass('active');
		$(this).addClass('active');
		var which = parseInt( $(this).attr('data-value') );
		var $form_table =$('#right_container');
		$('.uploadinfomandatory span', $form_table ).html('');
		
		switch(which){
			case USER_PRIVACY_PRIVATE:
				initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));
				$('.peoplecontainer_custom').addClass('displaynone');
				$('.info_pop_container').show();
				$('.uploadinfomandatory', $form_table ).addClass('inactive');
				$('.uploadinfomandatorytitle', $form_table ).removeClass('inactive');
				break;
			case USER_PRIVACY_SELECTED:
				$(this).closest('.tableform').find('.peoplecontainer_custom').show();
				$('.peoplecontainer_custom').removeClass('displaynone');
				$('.info_pop_container').hide();
				$('.uploadinfomandatory', $form_table ).addClass('inactive');
				$('.uploadinfomandatorytitle', $form_table ).removeClass('inactive');				
				$('.uploadinfomandatorycategory', $form_table ).removeClass('inactive');
				$('.uploadinfomandatorycountry', $form_table ).removeClass('inactive');
				$('.uploadinfomandatorycitynameaccent', $form_table ).removeClass('inactive');
				break;
			default:
				initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));
				$('.peoplecontainer_custom').addClass('displaynone');
				$('.info_pop_container').show();
								
				$('.uploadinfomandatory', $form_table ).addClass('inactive');
				$('.uploadinfomandatorytitle', $form_table ).removeClass('inactive');				
				$('.uploadinfomandatorycategory', $form_table ).removeClass('inactive');
				$('.uploadinfomandatorycountry', $form_table ).removeClass('inactive');
				$('.uploadinfomandatorycitynameaccent', $form_table ).removeClass('inactive');
				break;
		}
	});
	$(document).on('click',".peoplesdataclose" ,function(){
		var parents=$(this).parent();
		var parents_all=parents.parent().parent();
		parents.remove();
		if(parents.attr('data-id')!=''){
			SelectedUsersDelete(parents.attr('data-id'),parents_all.find('.addmore input'));			
		}
		
		if(parents.attr('id')=='friendsdata'){
			parents_all.find('.uploadinfocheckbox3').removeClass('active');
		}else if(parents.attr('id')=='friends_of_friends_data'){
			parents_all.find('.uploadinfocheckbox_friends_of_friends').removeClass('active');
		}else if(parents.attr('id')=='followersdata'){
			parents_all.find('.uploadinfocheckbox4').removeClass('active');
		}
	});
	$(document).on('click',".uploadinfocheckbox" ,function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
		}else{
			$(this).addClass('active');
		}
	});
        $(document).on('click',"#chk-constrain" ,function(){
            if($("#chk-constrain").hasClass("active")){
                $(this).removeClass("active");
                $(this).parent().attr('title','Activate Constraint');
            }else{
                $(this).addClass("active");
                $(this).parent().attr('title','Deactivate Constraint');
            }
        });
        $(document).on('click',"#edit_photo_rotate_left" ,function(){
            ImageEditor.rotate(90);
        });
        $(document).on('click',"#edit_photo_rotate_right" ,function(){
           ImageEditor.rotate(270); 
        });
        $(document).on('click',"#edit_photo_undo" ,function(){
            ImageEditor.undo();
        });
        $(document).on('click',"#edit_photo_redo" ,function(){
            ImageEditor.redo();
        });
        $(document).on('change',"#edit_photo_effect" ,function(){
            var val = $("#edit_photo_effect").val();
                if (val === 'grayscale'){
                    ImageEditor.grayscale();
                }
                else if (val === 'sepia'){
                    ImageEditor.sepia();
                }
                else if (val === 'pencil'){
                    ImageEditor.pencil();
                }
                else if (val === 'emboss'){
                    ImageEditor.emboss();
                }
                else if (val === 'sblur'){
                    ImageEditor.sblur();
                }
                else if (val === 'smooth'){
                    ImageEditor.smooth();
                }
                else if (val === 'invert'){
                    ImageEditor.invert();
                }
                else if (val === 'brighten'){
                    ImageEditor.brighten();
                }
                else if (val === 'darken'){
                    ImageEditor.darken();
                }
        });
        $(document).on('click',"#edit_photo_reset" ,function(){
            ImageEditor.viewOriginal();
            $("#edit_photo_effect").val('');
        });
        $(document).on('change',"#edit_photo_width" ,(function(){
            ImageEditor.txtWidthKeyup();
            ImageEditor.resize();
        }));
        $(document).on('change',"#edit_photo_height" ,function(){
            ImageEditor.txtHeightKeyup();
            ImageEditor.resize();
        });
	
	var privacyselcted=parseInt($('#right_container').attr('data-value'));
	$('#privacyclass_user'+privacyselcted).click();
	if(privacyselcted==USER_PRIVACY_SELECTED){
		$('#right_container').find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function(){
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
	
	if(parseInt(channelid)==0){
		privacyValue=parseInt($(".privacyclass.active").attr('data-value'));
	}else{
            privacyValue = USER_PRIVACY_PUBLIC;
            var $form_table = $('#right_container');
            $('.peoplecontainer_custom').addClass('displaynone');
            $('.info_pop_container').show();

            $('.uploadinfomandatory', $form_table ).addClass('inactive');
            $('.uploadinfomandatorytitle', $form_table ).removeClass('inactive');				
            $('.uploadinfomandatorycategory', $form_table ).removeClass('inactive');
            $('.uploadinfomandatorycountry', $form_table ).removeClass('inactive');
            $('.uploadinfomandatorycitynameaccent', $form_table ).removeClass('inactive');
                
	}
	privacyArray=new Array();	
	if(privacyValue==USER_PRIVACY_SELECTED){
		$('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function(){
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
});
function verifyFormList(which){
	var ok = true;
	var $currobjselected=$('#'+which).find('#edit_info_part');
        
	$('.uploadinfomandatory span', $currobjselected ).html('');
	$('input,select,textarea',$currobjselected ).removeClass('err').each(function(){
		var name = $(this).attr('name');
		var $parenttarget=$(this).parent();
		if( (name == 'location') || (name=='location_name') || (name=='placetakenat') || (name=='cityname') || (name=='status') || (name=='vid') || $(this).hasClass('filevalue') || (name=='filename') || (name=='addmoretext') || (name=='vpath') ){
			
		}else{
                    if(($('.uploadinfomandatory'+name, $parenttarget ).css('display')!='none' && getObjectData($(this)) == '') || (name=="category" && !$('.uploadinfomandatorycategory', $parenttarget ).hasClass('inactive') && $(this).val() == '0') || (name=="country" && !$('.uploadinfomandatorycountry', $parenttarget ).hasClass('inactive') && $(this).val() == '0')){
                        $('.uploadinfomandatory'+name+' span', $parenttarget ).html(t('please fill this field correctly'));
                        ok = false;
                    }
		}
	});
	
	var $parenttarget=$('input[name=cityname]',$currobjselected).parent();
	
	if( getObjectData($('input[name=cityname]',$currobjselected)) == '' && !$('.uploadinfomandatorycitynameaccent',$parenttarget).hasClass('inactive') && $('input[name=cityname]',$currobjselected).length>0){
		$('.uploadinfomandatorycitynameaccent span',$parenttarget).html(t('please fill this field correctly'));
		ok = false;
//                        console.log(5);
	}
	if(!ok){
		
		return false;
	}else{
		return true;
	}
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
// Toggle the enable shares and comments.
function enableSharesComments_log(entity_id, entity_type, new_status){
	
	$.ajax({
		url: ReturnLink('/ajax/info_log_updatesharescomments.php'),
		data: {entity_id:entity_id,entity_type:entity_type, new_status:new_status, globchannelid:channelGlobalID()},
		type: 'post',
		success: function(data){
			
		}
	});	
}
function addAutoCompleteList(){
	var $citynameaccent = $("#edit_citynameaccent");	
	$citynameaccent.autocomplete({
		appendTo: "#right_container",
		search: function(event, ui) {
			var $country = $('select[name=country]', $citynameaccent.parent() ).removeClass('err');
			var cc = $( 'option:selected', $country ).val();
			if(cc == 'ZZ'){
				$country.addClass('err');
				event.preventDefault();
			}else{
				$citynameaccent.autocomplete( "option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc) );
			}
		},
		select: function(event, ui) {
			$citynameaccent.val(ui.item.value);
			$('input[name=cityname]',$citynameaccent.parent()).val(ui.item.value);
                        $('input[name=cityname]',$citynameaccent.parent()).attr('data-id',ui.item.id);
			event.preventDefault();
		}
	});
	
	var $placetakenat = $("#edit_placetakenat");
	$placetakenat.autocomplete({
		appendTo: "#right_container",
		search: function(event, ui){
			var $country = $('select[name=country]', $citynameaccent.parent() ).removeClass('err');
			var cc = $( 'option:selected', $country ).val();
			if(cc == 'ZZ'){
				$country.addClass('err');
				event.preventDefault();
			}else{
				$placetakenat.autocomplete( "option", "source", ReturnLink('/ajax/uploadGetLocations.php?cc=' + cc) );
			}
		},
		select: function(event, ui){
			$placetakenat.val(ui.item.label);
			$('input[name=location]',$placetakenat.parent()).val(ui.item.id);
			$('input[name=location_name]',$placetakenat.parent()).val(ui.item.value);
			event.preventDefault();
		}
	});
}