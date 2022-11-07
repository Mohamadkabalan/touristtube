var currentPageAlbum=0;
var current_object_selected = '';
var privacyValue=-1;
var privacyArray=new Array();

$( document ).ready(function(){
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
	$(document).on('click',"#canceleventdata_remove" ,function(){
		$('.remove_media_container').hide();
		$('.remove_media_container .error_valid').html('');
		$('.remove_media_container .uploadinfocheckbox').removeClass('active');
	});
	$(document).on('click',".clsimg" ,function(){
		$('#saveeventdata_edit_remove').attr('data-id',$(this).closest('li').attr('data-id'));
		current_object_selected = $(this).closest('li');
		$('.remove_media_container').show();
	});
        $(document).on('click',".albumimg" ,function(){
		//if(confirm("Confirm to set album icon")){
		var curbut=$(this).closest('li');
		var target = curbut.attr("data-id");
		var cat_id= $('.all_container').attr('data-id');
		$.post(ReturnLink('/ajax/ajax_addalbumicon.php'), {catid:cat_id,id:target},function(data){
			$(".albumimg").removeClass('active');
			curbut.find('.albumimg').addClass('active');
		});
	});
	$(document).on('click',"#add_but_album" ,function(){
		parent.window.addMediaAlbumUpload($('.all_container').attr('data-upuri'));
	});
	$(document).on('click',"#saveeventdata_edit_remove" ,function(){
		if($('.remove_media_container .uploadinfocheckbox.active').length==0){
			$('.remove_media_container .error_valid').html(t('invalid data'));
		}else{
			var curob=$(this);
			var mediacontents=1;
			if($('.remove_media_container .uploadinfocheckbox_album').hasClass('active')){
				mediacontents=0;
			}else if($('.remove_media_container .uploadinfocheckbox_remove').hasClass('active')){
				mediacontents=1;
			}else{
				return;	
			}
			
			var target = curob.attr("data-id");
			var currobjselected=current_object_selected;
			
			var cat_id= $('.all_container').attr('data-id');
			
			var idsarr=new Array();
			idsarr.push(target);
			
			
			$.post(ReturnLink('/ajax/ajax_deletepiccatalog.php'), {catid:cat_id,idarr:idsarr.join('/*/'),mediacontents:mediacontents},function(data){
				document.location.reload();				
			});
		}
	});
	$(document).on('click',"#remove_button" ,function(){
		parent.window.addMediaRemove($('.all_container').attr('data-link'));
	});
	$(document).on('click',"#cancel_custom_privacy" ,function(){
		$('.error_valid_privacy').html('');
		$('.peoplecontainer_custom').addClass('displaynone');
		$('.info_pop_container').show();
	});
	$(document).on('click', ".next_div",function(){
		if(!$(this).hasClass('inactive')){
			currentPageAlbum++;
			if(currentPageAlbum>parseInt($('.next_prev_album').attr('data-cnt'))-1){
				currentPageAlbum=parseInt($('.next_prev_album').attr('data-cnt'))-1;
			}
			displayalbumrelated();
		}
	});		
	$(document).on('click', ".prev_div",function(){
		if(!$(this).hasClass('inactive')){
			currentPageAlbum--;
			if(currentPageAlbum<0){
				currentPageAlbum=0;	
			}
			displayalbumrelated();
		}
	});
	
	$(document).on('click', ".photoimg",function(){
		var $This = $(this);
		var dId = $This.attr('data-id');
		var channelid = $This.attr('channelid');
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
			}else if($(this).hasClass('uploadinfocheckbox_album')){
				$('.uploadinfocheckbox_remove').removeClass('active');				
			}else if($(this).hasClass('uploadinfocheckbox_remove')){
				$('.uploadinfocheckbox_album').removeClass('active');				
			}
			$(this).addClass('active');
		}
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
			
			$.post(ReturnLink('/ajax/ajax_updatealbumupload.php'), {privacyValue:privacyValue, privacyArray:privacyArray,catalog_id:$('.all_container').attr('data-id'),title:getObjectData($("#edit_title")),cityname: $("#edit_cityname").val(),cityid: $("#edit_cityname").attr('data-id'),citynameaccent: $("#edit_citynameaccent").val(),is_public: $(".privacyclass.active").attr("data-value") , description: getObjectData($("#edit_description")) ,category: $("#edit_category").val(),placetakenat: getObjectData($("#edit_placetakenat")),keywords: getObjectData($("#edit_keywords")),country: $("#edit_country").val(),location: '' },function(data){
				if(data!=false){
					parent.window.closeFancyReload(0);
				}
			});
		}			
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
	initDocumentAlbum();
});
function verifyFormList(which){
	var ok = true;
	var $currobjselected=$('#'+ which);
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
	
	}
	
	if(!ok){
		
		return false;
	}else{
		return true;
	}
}

function initDocumentAlbum(){
	$(".mediabuttons").mouseover(function(){		
		var posxx=$(this).offset().left-$('body').offset().left-252;
		var posyy=$(this).offset().top-$('body').offset().top-21;
		$('.ProfileHeaderOver .ProfileHeaderOverin').html($(this).attr('data-title'));
		$('.ProfileHeaderOver').css('left',posxx+'px');
		$('.ProfileHeaderOver').css('top',posyy+'px');
		$('.ProfileHeaderOver').stop().show();
	});
	$(".mediabuttons").mouseout(function(){
		$('.ProfileHeaderOver').hide();
	});	
}
function displayalbumrelated(){
	$('.upload-overlay-loading-fix').show();
	$('#canceleventdata_remove').click();
	$.post(ReturnLink('/ajax/ajax_loadmore_user_viewalbum.php'), {catid:$('.image_cont_album').attr('data-id'),channelid:$('.image_cont_album').attr('data-channelid'),currentPage:currentPageAlbum},function(data){
		$('.image_cont_album ul').html(data);
		var currPageStatus=$('.image_cont_album .currPageStatus');
		$('.next_prev_album').html(currPageStatus.html());
		$('.header_txt span').html('('+currPageStatus.attr('data-count')+')');
		currPageStatus.remove();
		initDocumentAlbum();
		$('.upload-overlay-loading-fix').hide();
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