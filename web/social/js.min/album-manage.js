var keypressed=0;
var indexpressed=-1;
var dragbool=false;
var frsposx=0;
var frsposy=0;
var catalogpressed="";
var currentPage=0;
var globtxtsrch="";
var globkeysrch="";
var caltocreated=false;
var curdate=new Date();
var TO_CAL;
var mediatype="a";
var ct_id="";
var frtxt="";
var totxt="";
$(document).ready(function() {
	$('#headbutinfocontent').css('width','1500px');
	var realwidth = $('#headbutinfocontenttxt').width() + 51 ;
	$('#headbutinfocontent').css('width','0px');
	$('#headbutinfocontent').attr('data-width',realwidth);
	$('#headbutinfocontent').removeClass('opacityzero');	
	
	$(document).on('click',".uploadinfocheckbox" ,function(){
		var curob=$(this);
		if(curob.hasClass('uploadinfocheckbox_remove1')){
			curob.addClass('active');
			$('.uploadinfocheckbox_remove2').removeClass('active');	
		}else if(curob.hasClass('uploadinfocheckbox_remove2')){
			curob.addClass('active');
			$('.uploadinfocheckbox_remove1').removeClass('active');	
		}else if(curob.hasClass('uploadinfocheckbox_remove3')){
			curob.addClass('active');
			$('.uploadinfocheckbox_remove4').removeClass('active');	
		}else if(curob.hasClass('uploadinfocheckbox_remove4')){
			curob.addClass('active');
			$('.uploadinfocheckbox_remove3').removeClass('active');	
		}else if($(this).hasClass('active')){
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
			$(this).addClass('active');
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
	$(document).on('click',".popup_delete_ok" ,function(){
		var curob=$(this).parent();
		if((""+curob.attr('id')=="popup_delete_album")){
			// 0 delete album only --   1 delete album with content
			var albumcontents=1;
			if($('.uploadinfocheckbox_remove1').hasClass('active')){
				albumcontents=0;
			}else if($('.uploadinfocheckbox_remove2').hasClass('active')){
				albumcontents=1;
			}else{
				return;	
			}
			$('.overlay-loading-fix').show();
			var target = curob.attr("data-id");
			var curbut=$('#catmenucontent').find('#'+target);
			$.post(ReturnLink('/ajax/ajax_catalogdelete.php'), {id:target,albumcontents:albumcontents},function(data){
				$('.slectcateg_select').find('#'+target).remove();
				if(curbut.find('.txtMN').hasClass('active') || albumcontents==1){
					$("#catAllmenu").click();
				}else{
					refreshMediaCount();
					refreshMediaCatalogs();
				}
				curbut.remove();				
			});
		}else if((""+curob.attr('id')=="popup_delete_album_pic")){
			
			// 0 delete media from album only --   1 delete media permanently
			var mediacontents=1;
			if($('.uploadinfocheckbox_remove3').hasClass('active')){
				mediacontents=0;
			}else if($('.uploadinfocheckbox_remove4').hasClass('active')){
				mediacontents=1;
			}else{
				return;	
			}
			$('.overlay-loading-fix').show();
			var target = curob.attr("data-id");
			var currobjselected=$('#catimagecontainer').find('#'+target);
			
			var cat_id= catalogpressed.parent().attr("id");
			
			var idsarr=new Array();
			idsarr.push(target);
			
			
			$.post(ReturnLink('/ajax/ajax_deletepiccatalog.php'), {catid:cat_id,idarr:idsarr.join('/*/'),mediacontents:mediacontents},function(data){
				refreshMediaCount();
				refreshMediaCatalogs();
				getMediaImages();					
			});
			
			if(parseInt($('#catselected').attr('data-val'))==2){
				$('#catselected').click();
			}			
			removeButSelectedMenu();
		}
		$('.fancybox-close').click();
	});
	$('.scroll-panemenu').jScrollPane({showArrows: true});
	$('.scroll-panemenu .jspVerticalBar').css('right',0+"px");
	//$('.scroll-panemenu .jspDrag').css('background','#5f5f5f');
	
	$(".select3").selectbox();
	$(".select2").selectbox();		
	$("#catAllplus").fancybox();
	
	$(".cataddselected").fancybox({
			padding	:0,
			margin	:0,
		afterShow:function(){
			//$('#slectcateg').html($('#slectcategnone').html());
			enableSelectBoxes();
	}});
	refreshEditCat();
	refreshEditImg();
	$(document).on('click',"#headbutinfo",function(){
		 if(!$(this).hasClass('active')){
			 $(this).addClass('active');
			 var realwidth = $('#headbutinfocontent').attr('data-width');
			 $('#headbutinfocontent').animate({'width':realwidth+"px"},500);
		 }
	});	
	$(document).on('click',"#headbutinfocontentclose",function(){
		 $('#headbutinfocontent').animate({'width':0+"px"},500);
		 $('#headbutinfo').removeClass('active');
	});
	
	addListenerClose();				
	$(document).on('click',"#cataddbut",function(){
		var txtcatadd=""+$("#cataddtxt").val();
		if(txtcatadd!="" && txtcatadd!="name album..."){
			$('.overlay-loading-fix').show();
						
			$.post(ReturnLink('/ajax/ajax_addcatalog.php'), {txtcatadd:txtcatadd},function(data){
				if(data!=false){
					$("#cataddtxt").val('name album...');
					$('#catmenucontent ul').prepend(data);
                                        var arr = new Array(""+$('#catmenucontent ul li').length);
					$("#catAllmenu").html( sprintf(t('MY ALBUMS (%s)'), arr ) );
					$(".fancybox-close").click();
					calldroppableslectitem();
					
					var nstr='<option value="" selected="selected">...</option>';
					$('#catmenucontent ul li').each(function(){
						var mycurrtarget=$(this);
						var newcatid=mycurrtarget.attr('id');
						nstr+='<option value="'+newcatid+'" id="'+newcatid+'">'+mycurrtarget.find('.txtMN').html()+'</option>';
					});
					$('.slectcateg_select').html(nstr);
					
					$('.scroll-panemenu').jScrollPane({showArrows: true});
					$('.scroll-panemenu .jspVerticalBar').css('right',0+"px");
					//$('.scroll-panemenu .jspDrag').css('background','#5f5f5f');
					
					$('.overlay-loading-fix').hide();
					refreshEditCat();
				}
			});				
		}
	});	
	$(document).on('click',"#cataddbut1, #cataddtobut1, #cateditbut1, #cateditimgbut1, .popup_delete_cancel",function(){
		$('.fancybox-close').click();
	});
	$(document).on('click',"#cataddtobut",function(){
		if(!$(this).hasClass('inactive')){
			
			var cmbcatadd=""+$(".slectcateg_select").val();
			var txtcatadd=""+$("#cataddtotxt").val();
			if(txtcatadd!="" && txtcatadd!="create album..."){
				$('.overlay-loading-fix').show();
				$.post(ReturnLink('/ajax/ajax_addcatalog.php'), {txtcatadd:txtcatadd},function(data){
					if(data!=false){
						$("#cataddtotxt").val('create album...');
						$('#catmenucontent ul').prepend(data);
						$(".fancybox-close").click();
						calldroppableslectitem();
						
						
						var nstr='<option value="" selected="selected">...</option>';
						$('#catmenucontent ul li').each(function(){
							var mycurrtarget=$(this);
							var newcatid=mycurrtarget.attr('id');
							nstr+='<option value="'+newcatid+'" id="'+newcatid+'">'+mycurrtarget.find('.txtMN').html()+'</option>';
						});
						$('.slectcateg_select').html(nstr);
						mycurrtarget=$('#catmenucontent ul li').first();
						var newcatid=mycurrtarget.attr('id');
						
						$('.scroll-panemenu').jScrollPane({showArrows: true});
						$('.scroll-panemenu .jspVerticalBar').css('right',0+"px");
						//$('.scroll-panemenu .jspDrag').css('background','#5f5f5f');
						
						refreshEditCat();
						var newln = $('#catimagecontainer ul li.active').length;
						
						var idsarr=new Array();
						$('#catimagecontainer ul li.active').each(function(){
							var target = $(this).attr("id");
							idsarr.push(target);								
						});
						
						$.ajax({
							url: ReturnLink('/ajax/ajax_addpiccatalog.php'),
							type: 'post',
							data : {catid:newcatid ,idarr:idsarr.join('/*/')},
							success: function(data){
								document.location.href=ReturnLink("/album-manage/"+ct_id);
								/*refreshMediaCount();
								getMediaImages();
								$.post(ReturnLink('/ajax/ajax_catalogmenucount.php'), {catid:newcatid},function(data){
									mycurrtarget.find('.descMN').html(data);
									$('.overlay-loading-fix').hide();
								});*/
							},
							error: function (xhr, ajaxOptions, thrownError) {
								
							}
						});
					}
				});			
			}else if(cmbcatadd!=""){
				$('.overlay-loading-fix').show();
				
				var newcatid=parseInt(cmbcatadd);
				var mycurrtarget=$('#catmenucontent ul').find('#'+newcatid);
				var newln = $('#catimagecontainer ul li.active').length;
				
				var idsarr=new Array();
				$('#catimagecontainer ul li.active').each(function(){
					var target = $(this).attr("id");
					idsarr.push(target);								
				});
				$.ajax({
					url: ReturnLink('/ajax/ajax_addpiccatalog.php'),
					type: 'post',
					data: {catid:newcatid ,idarr:idsarr.join('/*/')},
					success: function(data){
						document.location.href=ReturnLink("/album-manage/"+ct_id);
						/*refreshMediaCount();
						getMediaImages();
						$.post(ReturnLink('/ajax/ajax_catalogmenucount.php'), {catid:newcatid},function(data){
							mycurrtarget.find('.descMN').html(data);
							$("#cataddtotxt").val('create album...');
							$(".fancybox-close").click();
							$('.overlay-loading-fix').hide();
						});*/
					},
					error: function (xhr, ajaxOptions, thrownError) {
						
					}
				});		
			}
		}
	});
	
	$("#searchbut").click(function(){
		var txtvalsrch=""+$("#srchtxt").val();
		removeButSelectedMenu();
		if( txtvalsrch!="" && ( parseInt($("#filterby").val())==1 || parseInt($("#filterby").val())==0 ) ){
			$('.overlay-loading-fix').show();
			
			globtxtsrch=txtvalsrch;
			currentPage=0;
			ct_id="";
			if(catalogpressed!=""){
				ct_id=catalogpressed.parent().attr('id');
			}
			globkeysrch=""+$("#filterby").val();
			frtxt="";
			totxt="";
			getMediaImages();		
		}else if(parseInt($("#filterby").val())==2 && ($('#fromtxt').html()!='' || $('#totxt').html()!='')){
			$('.overlay-loading-fix').show();
			
			frtxt=""+$('#fromtxt').attr('data-cal');
			totxt=""+$('#totxt').attr('data-cal');
			
			txtvalsrch="";
			globtxtsrch=txtvalsrch;
			currentPage=0;
			ct_id="";
			if(catalogpressed!=""){
				ct_id=catalogpressed.parent().attr('id');
			}
			globkeysrch=""+$("#filterby").val();
			
			getMediaImages();					
		}else if(parseInt($("#filterby").val())==3 && txtvalsrch!=""){
			$('.overlay-loading-fix').show();
		
			globtxtsrch="";				
			$.post(ReturnLink('/ajax/ajax_getsearchcatalog.php'), {txtsrch:txtvalsrch},function(data){
				if(catalogpressed!=""){
					catalogpressed="";
				}
				$("#catmenucontent ul").html(data);
				$('.scroll-panemenu').jScrollPane({showArrows: true});
				$('.scroll-panemenu .jspVerticalBar').css('right',0+"px");
				//$('.scroll-panemenu .jspDrag').css('background','#5f5f5f');
				calldroppableslectitem()
				refreshEditCat();
				$('.overlay-loading-fix').hide();
			});
		}
	});
	$("#catAllmenu, #catAllnum").click(function(){
		$('.overlay-loading-fix').show();
		
		if(parseInt($('#catselected').attr('data-val'))==2){
			$('#catselected').click();
		}
		ct_id="";
		removeButSelectedMenu();
		$("#catmenucontent ul li .txtMN").removeClass("active");
		$("#catmenucontent ul li .txtMN").removeClass("active1");
		catalogpressed="";
		
		mediatype='a';
		$("#sortcmb").html('<select name="mediafilter" id="mediafilter" class="select2" style="width:136px;" onchange="doMediaFilter(this);"><option value="a" selected="selected">'+t("all")+'</option><option value="i">'+t("photos")+'</option><option value="v">'+t("videos")+'</option></select>');
		$(".select2").selectbox();
		
		$("#srchtxt").val('');
		$("#fromtxt").html('');
		$("#fromtxt").attr('data-cal','');
		$("#totxt").html('');
		$("#totxt").attr('data-cal','');
		
		globtxtsrch="";
		globkeysrch="";
		currentPage=0;
		$('#catimagecontainer').css('opacity',0);
		$.post(ReturnLink('/ajax/ajax_getpiccatalog.php'), {catid:'',currentPage:currentPage,txtsrch:globtxtsrch,globkeysrch:globkeysrch,mediatype:mediatype},function(data){
			$('#catsubcontainer').html(data);
			refreshEditImg();
			refreshMediaCount();
			initDocument();
			refreshMediaCatalogs();
		});			
	});
	$(document).on('click',"#catpagego" ,function(){
		var myvl=$('#catpagevaluetxt').val();
		if(myvl!=""){
			var myvllen=parseInt($('#catPagecount').attr('data-count'));
			myvl=parseInt(myvl);
			if(myvl>myvllen){
				myvl=myvllen;
			}
			if(myvl<=0){
				myvl=1;
			}
			$('.overlay-loading-fix').show();
			
			if(parseInt($('#catselected').attr('data-val'))==2){
				$('#catselected').click();
			}
			ct_id="";
			if(catalogpressed!=""){
				ct_id=catalogpressed.parent().attr('id');
			}
			removeButSelectedMenu();
			currentPage=myvl-1;
			$('#catimagecontainer').css('opacity',0);
			getMediaImages();
		}
	});
	$(document).on('click',"#catmenucontent ul li .clickMN" ,function(){
		$('.overlay-loading-fix').show();
		
		if(parseInt($('#catselected').attr('data-val'))==2){
			$('#catselected').click();
		}
		removeButSelectedMenu();
		$("#srchtxt").val('');
		$("#fromtxt").html('');
		$("#fromtxt").attr('data-cal','');
		$("#totxt").html('');
		$("#totxt").attr('data-cal','');
	
		$("#catmenucontent ul li .txtMN").removeClass("active");
		$("#catmenucontent ul li .txtMN").removeClass("active1");
		catalogpressed=$(this);
		$(this).addClass("active");
		ct_id= $(this).parent().attr("id");
		currentPage=0;
		globtxtsrch="";
		globkeysrch="";
		$('#catimagecontainer').css('opacity',0);
		frtxt="";
		totxt="";
		
		mediatype='a';
		$("#sortcmb").html('<select name="mediafilter" id="mediafilter" class="select2" style="width:136px;" onchange="doMediaFilter(this);"><option value="a" selected="selected">'+t("all")+'</option><option value="i">'+t("photos")+'</option><option value="v">'+t("videos")+'</option></select>');
		$(".select2").selectbox();
		
		getMediaImages();		
	});
		
	Calendar.setup({
		inputField : "fromtxt",
                noScroll  	 : true,
		trigger    : "frombut",
		align:"B",
		onSelect   : function() {
			var date = Calendar.intToDate(this.selection.get());
		    TO_CAL.args.min = date;
		    TO_CAL.redraw();
			$('#fromtxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
						
			addCalTo(this);
			this.hide();
		},
		dateFormat : "%d-%m-%Y"
	});
	TO_CAL=Calendar.setup({
		inputField : "totxt",
                noScroll  	 : true,
		trigger    : "tobut",
		align:"B",
		onSelect   : function(calsss) { 
			var date = Calendar.intToDate(this.selection.get());
			$('#totxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalTo(this);
			this.hide();
		},
		dateFormat : "%d-%m-%Y"
	});
	
	$("#catselectall").click(function(){
		$('#catimagecontainer ul li').addClass('active');
		addButSelectedMenu();
                $('#catselected').html(t('Show Selected'));
                $('#catselected').attr('data-val',1);
                $('#catselected').addClass('inactive');
	});
	$("#catselected").click(function(){
		if(!$(this).hasClass('inactive')){
			var vals = parseInt($(this).attr('data-val'));
			if(vals==1){
                            $('#catselectall').addClass('inactive');
				$(this).attr('data-val',2);
				$('#catselected').html(t('show all'));
				$('#catimagecontainer ul li').each(function(){
					if(!$(this).hasClass('active')){
						$(this).hide();
					}
				});
			}else{
                            $('#catselectall').removeClass('inactive');
				$('#catselected').html(t('show selected'));
				$(this).attr('data-val',1);
				$('#catimagecontainer ul li').show();	
			}
		}
	});
	$("#catunselected").click(function(){
		if(!$(this).hasClass('inactive')){
			if(parseInt($('#catselected').attr('data-val'))==2){
				$('#catselected').click();
			}			
			removeButSelectedMenu();
			$("#catimagecontainer ul li").removeClass("active");
			$("#catimagecontainer ul li").find('.clsimg').hide();
			$("#catimagecontainer ul li").find('.albumimg').hide();
			//$("#catimagecontainer ul li").find('.ovrbkimg').hide();
		}
	});
	$(document).on('mouseover', "#catmenucontent ul li .txtMN" ,function(){
		$(this).addClass("active1");
		//albumMenuOver
		var posxx=$(this).offset().left-$('#catmenucontent').offset().left+17;
		var posyy=$(this).offset().top-$('#catmenucontent').offset().top+30;
		$('.albumMenuOver .albumMenuOverin').html($(this).find('span').html());
		$('.albumMenuOver').css('left',posxx+'px');
		$('.albumMenuOver').css('top',posyy+'px');
		$('.albumMenuOver').stop().show();
	});
	$(document).on('mouseout',"#catmenucontent ul li .txtMN" ,function(){
		$(this).removeClass("active1");
		$('.albumMenuOver').hide();
	});
	$(document).on('mouseover',".albumMenuOver" ,function(){
		$('.albumMenuOver').show();
	});
	$(document).on('mouseout',".albumMenuOver" ,function(){
		$('.albumMenuOver').hide();
	});
	$(document).on('mouseover',"#catAllplus" ,function(){
		$('#catAllplusover').show();
	});
	$(document).on('mouseout',"#catAllplus" ,function(){
		$('#catAllplusover').hide();
	});
	$(document).on('mouseover',"#catmenucontent ul li .editMN" ,function(){
		$('#editMNover').css('top',($(this).parent().offset().top-$('#catmenu').offset().top+36)+'px');
		$('#editMNover').show();
	});
	$(document).on('mouseout',"#catmenucontent ul li .editMN" ,function(){
		$('#editMNover').hide();
	});
	$(document).on('mouseover',"#catmenucontent ul li .clsMN" ,function(){
		$('#clsMNover').css('top',($(this).parent().offset().top-$('#catmenu').offset().top+33)+'px');
		$('#clsMNover').show();
	});
	$(document).on('mouseout',"#catmenucontent ul li .clsMN" ,function(){
		$('#clsMNover').hide();
	});
	$(document).on('click',"#catmenucontent ul li .clsMN" ,function(){
		$this = $(this);
		TTAlert({
			msg: t('are you sure you want to remove permanently this album?<br/>this action cannot be undone.'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('confirm'),
			btn2Callback: function(data){
				if(data){
					$('.overlay-loading-fix').show();
					var albumcontents =1;
					var curbut=$this.closest('li');
					var target = curbut.attr("id");
					$.post(ReturnLink('/ajax/ajax_catalogdelete.php'), {id:target,albumcontents:albumcontents},function(data){
						$('.slectcateg_select').find('#'+target).remove();
						if(curbut.find('.txtMN').hasClass('active') || albumcontents==1){
							$("#catAllmenu").click();
						}else{
							refreshMediaCount();
							refreshMediaCatalogs();
						}
						curbut.remove();				
					});
				}
			}
		});
	});
	$(document).on('click', ".UploadMediaLeftTF .privacyclass", function() {
            $('.privacyclass', $(this).parent().parent()).removeClass('active');
            $(this).addClass('active');
            var which = parseInt($(this).attr('data-value'));
            var $form_table = $(this).parent().parent().parent();
            $('.uploadinfomandatory span', $form_table).html('');

            switch (which) {
                case USER_PRIVACY_PRIVATE:
                    initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));
                    $('.uploadinfomandatory', $form_table).addClass('inactive');
                    $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                    break;
                case USER_PRIVACY_SELECTED:
                    $(this).closest('.tableform').find('.peoplecontainer_custom').show();
                    $('.uploadinfomandatory', $form_table).addClass('inactive');
                    $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                    $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
                    $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
                    $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');
                    break;
                default:
                    initResetSelectedUsers($(this).closest('.tableform').find('.peoplecontainer_custom'));

                    $('.uploadinfomandatory', $form_table).addClass('inactive');
                    $('.uploadinfomandatorytitle', $form_table).removeClass('inactive');
                    $('.uploadinfomandatorycategory', $form_table).removeClass('inactive');
                    $('.uploadinfomandatorycountry', $form_table).removeClass('inactive');
                    $('.uploadinfomandatorycitynameaccent', $form_table).removeClass('inactive');
                    break;
            }
        });
	$(document).on('click',"#uploadlistpopuppublishalbum #loglistalbumelected .ClearNewAlbumButton" ,function(){
		$('.fancybox-close').click();
	});
	$(document).on('click','#uploadlistpopuppublishalbum .UploadMediaRightTF #log_save_media' ,function(){
		var mycurrobj='uploadlistpopuppublishalbum';		
		if(verifyFormList(mycurrobj)){
			//$('.upload-overlay-loading-fix').show();
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
				curob.find('.error_valid').html(t('Invalid privacy data'));
				return;
			}
			var cobthis=$(this);		
												
			$.post(ReturnLink('/ajax/profile_media_save.php'), {privacyValue:privacyValue, privacyArray:privacyArray,vid:$('#uploadlistpopuppublishalbum').attr('data-id'),title:getObjectData($("#"+mycurrobj+" input[name=title]")),cityname: $("#"+mycurrobj+" input[name=cityname]").val(), cityid: $("#"+mycurrobj+" input[name=cityname]").attr('data-id'),citynameaccent: $("#"+mycurrobj+" input[name=citynameaccent]").val(),is_public: $("#"+mycurrobj+" div.privacyclass.active").attr("data-value") ,description: getObjectData($("#"+mycurrobj+" textarea[name=description]")) ,category: $("#"+mycurrobj+" select[name=category] option:selected").val(),placetakenat: getObjectData($("#"+mycurrobj+" input[name=placetakenat]")),keywords: getObjectData($("#"+mycurrobj+" input[name=keywords]")),country: $("#"+mycurrobj+" select[name=country] option:selected").val(),location: $("#"+mycurrobj+" input[name=location]").val()},function(data){
				if(data!=false){
					$('.fancybox-close').click();
				}
			});
		}			
	});
	$(document).on('click','#uploadlistpopuppublishalbum .UploadMediaRightTF #loglistalbumelectedsave' ,function(){
		var mycurrobj='uploadlistpopuppublishalbum';		
		if(verifyFormList(mycurrobj)){
			//$('.upload-overlay-loading-fix').show();
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
				curob.find('.error_valid').html(t('Invalid privacy data'));
				return;
			}
			var cobthis=$(this);									
			$.post(ReturnLink('/ajax/ajax_updatealbumupload.php'), {privacyValue:privacyValue, privacyArray:privacyArray,catalog_id:$('#uploadlistpopuppublishalbum').attr('data-id'),title:getObjectData($("#"+mycurrobj+" input[name=title]")),cityname: $("#"+mycurrobj+" input[name=cityname]").val(),cityid: $("#"+mycurrobj+" input[name=cityname]").attr('data-id'),citynameaccent: $("#"+mycurrobj+" input[name=citynameaccent]").val(),is_public: $("#"+mycurrobj+" div.privacyclass.active").attr("data-value") ,description: getObjectData($("#"+mycurrobj+" textarea[name=description]")) ,category: $("#"+mycurrobj+" select[name=category] option:selected").val(),placetakenat: getObjectData($("#"+mycurrobj+" input[name=placetakenat]")),keywords: getObjectData($("#"+mycurrobj+" input[name=keywords]")),country: $("#"+mycurrobj+" select[name=country] option:selected").val(),location: $("#"+mycurrobj+" input[name=location]").val()},function(data){
				if(data!=false){
					$('#catmenucontent ul').find('#'+$('#uploadlistpopuppublishalbum').attr('data-id')).find('.txtMN span').html(getObjectData($("#"+mycurrobj+" input[name=title]")));
					$('.slectcateg_select').find('#'+$('#uploadlistpopuppublishalbum').attr('data-id')).html(getObjectData($("#"+mycurrobj+" input[name=title]")));
					/*albumclicked.find('.sorttilte').html($("#"+mycurrobj+" input[name=title]").val());
					var des_str=getObjectData($("#"+mycurrobj+" textarea[name=description]"));
					var str = des_str.replace(/\\n|\n/g, '<br />');
					var result1 = str.substring(1,100);
					var result2 = str.substring(1,80);
					if(str.length>100){
						result1 +='...';	
					}
					if(str.length>80){
						result2 +='...';	
					}
					albumclicked.find('.mediainfodesc1').html(result1);
					albumclicked.find('.mediainfodesc').html(result2);*/
					$('.fancybox-close').click();
				}
			});
		}			
	});
	
	calldragslectitem();
	initDocument();
});
function initDocument(){
	if($('#paging_content').length>0){	
		$('#paging_content').css('width',(($('#paging_content').height()/17)*288)+'px');	
		$('#paging_content').css('width',( $('#paging_content ul li').last().offset().left - $('#paging_content ul').offset().left + $('#paging_content ul li').last().width() + 3 )+'px');
		$('#paging_content ul').css('height','17px');	
		
		$('#paging_content ul').css('left', $('#catsubcontainer').attr('data-left') +'px');
		
		var diffview = ( $('.paging_content_item.active').offset().left /*- $('#paging_content').offset().left*/ - $('#paging_content ul').offset().left + parseInt($('#catsubcontainer').attr('data-left'))  + $('.paging_content_item.active').width() + 3 );
		
		if(  diffview > 290 ){
			var newdiff = ( $('.paging_content_item.active').offset().left - $('#paging_content ul').offset().left  + $('.paging_content_item.active').width() + 3 );
			var newleft = 286 - newdiff;
			
			if(newleft>0){
				newleft = 0;	
			}
			$('#paging_content ul').css('left', newleft+'px');
			$('#catsubcontainer').attr('data-left',newleft);
		}else if( diffview <16 ){
			
			var newleft = -( $('.paging_content_item.active').offset().left - $('#paging_content ul').offset().left );
			if(newleft>0){
				newleft = 0;	
			}
			$('#paging_content ul').css('left', newleft+'px');
			$('#catsubcontainer').attr('data-left',newleft);
		}
	}
}
function refreshWindow(){
	var mywidth=1220;
	/*var mywidth=$(window).width();
	if(mywidth<1220){
		mywidth=1220;
	}*/
	var cntww=mywidth-490;
	var numcnt=Math.floor(cntww/146);
	$('#catsubcontainer').css('width',(numcnt*146)+'px');
	$('#catHeader').css('width',( (numcnt*146) - 1 )+'px');
	$('#catcontainer').css('width',(numcnt*146+260)+'px');
	
	//$('#catmenucontent').css('height',($('#catmenu').height()-40)+'px');
	$('.scroll-panemenu').jScrollPane({showArrows: true});
	$('.scroll-panemenu .jspVerticalBar').css('right',0+"px");
	//$('.scroll-panemenu .jspDrag').css('background','#5f5f5f');
	
	$('#catcontainer').css('opacity',1);
}
$(window).keydown(function(event) {
  keypressed=event.keyCode;
});
$(window).keyup(function(event) {
  keypressed=0;
});
function calldragslectitem(){
	refreshWindow();
	$("#catimagecontainer ul li .selectitem").click(function(){
		$(this).off('mousemove');
		$(this).unbind('mousemove');
		if(dragbool){
			dragbool=false;
			return;
		}
		if(keypressed==16){
			if(indexpressed!=-1){
				var pi=indexpressed;
				var ln=$(this).parent().index();
				if(pi>ln){
					pi=ln;
					ln=indexpressed;
				}
				for(var i=pi;i<=ln;i++){
					var myobj=$("#catimagecontainer ul li").eq(i);
					$(myobj).addClass("active");					
				}
				//showOverPic($(this).parent());
			}else{
				indexpressed=$(this).parent().index();
				$(this).parent().addClass("active");
				//showOverPic($(this).parent());
			}
		}else{
			if($(this).parent().hasClass('active')){
				$(this).parent().removeClass("active");	
				//hideOverPic($(this).parent());		
			}else{
				$(this).parent().addClass("active");
				//showOverPic($(this).parent());
			}
			indexpressed=$(this).parent().index();
			addButSelectedMenu();
			if($(this).parent().parent().find('.active').length==0){
				indexpressed=-1;				
				if(parseInt($('#catselected').attr('data-val'))==2){
					$('#catselected').click();
				}
				removeButSelectedMenu();
			}
		}
		
	});
	$("#catimagecontainer ul li .selectitem").mouseover(function(){
		showOverPic($(this).parent());
	});
	$("#catimagecontainer ul li .clsimg, #catimagecontainer ul li .mediabuttons").mouseover(function(){
		showOverPic($(this).parent().parent());
		var posxx=$(this).offset().left-$('#ProfileHeaderInternal').offset().left-252;
		var posyy=$(this).offset().top-$('#ProfileHeaderInternal').offset().top-21;
		$('.ProfileHeaderOver .ProfileHeaderOverin').html($(this).attr('data-title'));
		$('.ProfileHeaderOver').css('left',posxx+'px');
		$('.ProfileHeaderOver').css('top',posyy+'px');
		$('.ProfileHeaderOver').stop().show();
	});
	$("#catimagecontainer ul li .selectitem, #catimagecontainer ul li .clsimg, #catimagecontainer ul li .mediabuttons").mouseout(function(){
		hideOverPic($(this).parent().parent());
		$('.ProfileHeaderOver').hide();
	});
	$("#catimagecontainer ul li .selectitem").mousedown(function(){
		frsposx=$(this).offset().left;
		frsposy=$(this).offset().top;
		$(this).bind('mousemove',movehid);
	});
	$("#catimagecontainer ul li .selectitem").mouseup(function(){
		dragbool=false;
		$(this).unbind('mousemove',movehid);
		$("#catimagecontainer ul li .hiddenimg").css("opacity",0);	
	});
	
	
	$('#catimagecontainer ul li .hiddenimg').draggable( {
      containment: "window",
      stack: '#catimagecontainer',
      cursor: 'move',
	  stop:dostopdrag,
      revert: true
    } );
	calldroppableslectitem();	
}
function movehid(e){
	var $thisObj = $(e.currentTarget);
	
	if(Math.abs($thisObj.offset().left-frsposx)>50 || Math.abs($thisObj.offset().top-frsposy)>50){
		if($thisObj.parent().hasClass('active')){
			$thisObj.parent().find('.hiddenimg .dragiconimg').html($('#catimagecontainer ul li.active').length);
			$thisObj.parent().find('.hiddenimg').css("opacity",1);
			dragbool=true;
		}
	}
}
function dostopdrag(){
	$("#catimagecontainer ul li .selectitem").unbind('mousemove',movehid);
	$("#catimagecontainer ul li .hiddenimg").css("opacity",0);
}
function calldroppableslectitem(){
	$('#catmenucontent ul li .txtMN').droppable( {
      accept: '#catimagecontainer ul li .hiddenimg',
      hoverClass: 'active1',
      drop: handleCardDrop
    } );	
}
function addCalTo(cals){ 
	if(new Date($('#fromtxt').attr('data-cal'))>new Date($('#totxt').attr('data-cal'))){
		$('#totxt').attr('data-cal',$('#fromtxt').attr('data-cal'));
		$('#totxt').html($('#fromtxt').html());
	}
}
function changeFilter(obj){
	$("#srchtxt").val('');
	$("#fromtxt").html('');
	$("#fromtxt").attr('data-cal','');
	$("#totxt").html('');
	$("#totxt").attr('data-cal','');
	if(parseInt($(obj).val())==2){
		$("#searchinput").hide();
		$(".calbut").show();
		$(".caltxt").show();
	}else{
		$(".calbut").hide();
		$(".caltxt").hide();
		$("#searchinput").show();
	}
}
function showOverPic(obj){
	if(!dragbool){
		obj.find('.mediabuttonsspace').show();
		obj.find('.mediabuttons').show();
	}
}
function hideOverPic(obj){
	obj.find('.mediabuttonsspace').hide();
	obj.find('.mediabuttons').hide();
}
function addButSelectedMenu(){
	$('#catselected').removeClass('inactive');
	$('#catunselected').removeClass('inactive');
	$('#cataddselected').removeClass('inactive');
	$('#cataddselected').addClass('cataddselected');	
}
function removeButSelectedMenu(){
	$('#catselected').addClass('inactive');
	$('#catunselected').addClass('inactive');
	$('#cataddselected').addClass('inactive');
	$('#cataddselected').removeClass('cataddselected');	
}
function addValue(obj,val){
	 if($(obj).attr('value') == '') $(obj).attr('value',val);
}
function removeValue(obj,value) {
	if($(obj).attr('value') == value) $(obj).attr('value','');
}
function addValue1(obj){
	if($(obj).attr('value') == '') $(obj).attr('value',$(obj).attr('data-val'));
} 
function removeValue1(obj) {
	if($(obj).attr('value') == $(obj).attr('data-val')) $(obj).attr('value','');
}
function checkSubmit(e){
   if(e && e.keyCode == 13){
      $('#searchbut').click();
   }
}
function checkSubmitcatadd(e){
   if(e && e.keyCode == 13){
      $('#cataddbut').click();
   }
}
function checkSubmitcataddto(e){
   if(e && e.keyCode == 13){
      $('#cataddtobut').click();
   }
}
function checkSubmitcatedit(e){
   if(e && e.keyCode == 13){
      $('#cateditbut').click();
   }
}
function checkSubmitcateditimg(e){
   if(e && e.keyCode == 13){
      $('#cateditimgbut').click();
   }
}
function checkSubmitcatpagevalue(e){
   if(e && e.keyCode == 13){
      $('#catpagego').click();
   }else{
		return isNumberKey(e);   
   }
}
function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	
	return true;
}
function enableSelectBoxes(){
	/*$('#slectcateg').each(function(){
		$(this).children('span.selected').html($(this).children('div.selectOptions').children('span.selectOption:first').html());
		$(this).attr('value',$(this).children('div.selectOptions').children('span.selectOption:first').attr('value'));
		
		$(this).children('span.selected,span.selectArrow').click(function(){
			if($(this).parent().children('div.selectOptions').css('display') == 'none'){
				$(this).parent().children('div.selectOptions').css('display','block');
			}else{
				$(this).parent().children('div.selectOptions').css('display','none');
			}
		});
		
		$(this).find('span.selectOption').click(function(){
			$(this).parent().css('display','none');
			$(this).closest('div.selectBox').attr('value',$(this).attr('value'));
			$(this).parent().siblings('span.selected').html($(this).html());
		});
	});*/
}
function enableSelectBoxes2(midd){
	/*$('#slectcateditimg').each(function(){
		$(this).children('span.selected').html($(this).children('div.selectOptions').children('span.selectOption').eq(midd).html());
		$(this).attr('value',$(this).children('div.selectOptions').children('span.selectOption').eq(midd).attr('value'));
		
		$(this).children('span.selected,span.selectArrow').click(function(){
			if($(this).parent().children('div.selectOptions').css('display') == 'none'){
				$(this).parent().children('div.selectOptions').css('display','block');
			}else{
				$(this).parent().children('div.selectOptions').css('display','none');
			}
		});
		
		$(this).find('span.selectOption').click(function(){
			$(this).parent().css('display','none');
			$(this).closest('div.selectBox').attr('value',$(this).attr('value'));
			$(this).parent().siblings('span.selected').html($(this).html());
		});
	});*/
}
function handleCardDrop( event, ui ) {
	dragbool=true;
	$('.overlay-loading-fix').show();
	
	var mycurrtarget=$(this).parent();
	var slotNumber = mycurrtarget.attr('id');
	var cardNumber = ui.draggable.parent().attr('id');
	ui.draggable.parent().find('.hiddenimg').css("opacity",0);
	var newln = $('#catimagecontainer ul li.active').length;
	
	var idsarr=new Array();
	$('#catimagecontainer ul li.active').each(function(){
		var target = $(this).attr("id");
		idsarr.push(target);								
	});
	
	$.ajax({
		url: ReturnLink('/ajax/ajax_addpiccatalog.php'),
		type: 'post',
		data: {catid:slotNumber,idarr:idsarr.join('/*/')},
		success: function(data){
			document.location.href=ReturnLink("/album-manage/"+ct_id);
			/*refreshMediaCount();
			getMediaImages();
			$.post(ReturnLink('/ajax/ajax_catalogmenucount.php'), {catid:slotNumber},function(data){
				mycurrtarget.find('.descMN').html(data);
				dragbool=false;
				$('.overlay-loading-fix').hide();						
			});*/
		},
		error: function (xhr, ajaxOptions, thrownError) {
			
		}
	});	
}
function addListenerClose(){
	$("#catimagecontainer ul li .clsimg").click(function(){
		var currobjselected=$(this).parent().parent();
		var typemedia="photo";
		if(currobjselected.find('.photovideoicon').attr('data-type')=="v"){
			typemedia="video";
		}
		var arr = new Array(typemedia,'<br/>');
		TTAlert({
			msg: sprintf( t('are you sure you want to remove permanently this %s ? %s this action cannot be undone.') , arr ) ,
			type: 'action',
			btn1: t('cancel'),
			btn2: t('confirm'),
			btn2Callback: function(data){
				if(data){
					$('.overlay-loading-fix').show();
					//var mylens=$('#catimagecontainer ul li.active').length;
					
					var idsarr=new Array();
					var target = currobjselected.attr("id");
					idsarr.push(target);
					
					
					$.post(ReturnLink('/ajax/ajax_deletepicDB.php'), {idarr:idsarr.join('/*/')},function(data){
						ct_id="";
						refreshMediaCount();
						getMediaImages();
					});
				}
			}
		});
					
		hideOverPic($(this).parent());			
	});
	$("#catimagecontainer ul li .albumimg").click(function(){
		//if(confirm("Confirm to set album icon")){
		var curbut=$(this).parent().parent();
		
		TTAlert({
			msg: t('confirm to set album icon'),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('confirm'),
			btn2Callback: function(data){
				if(data){
					$('.overlay-loading-fix').show();
				
					var target = curbut.attr("id");
					var cat_id= catalogpressed.parent().attr("id");
					$.post(ReturnLink('/ajax/ajax_addalbumicon.php'), {catid:cat_id,id:target},function(data){
						$('.overlay-loading-fix').hide();					
					});
				}
			}
		});
		
		hideOverPic($(this).parent());
	});		
	
	$(".paging_content_item").click(function(){
		var myvl = $(this).attr('data-id');
		var myvllen=parseInt($('#catPagecount').attr('data-count'));
		myvl=parseInt(myvl);
		if(myvl>myvllen){
			myvl=myvllen;
		}
		if(myvl<=0){
			myvl=1;
		}
		$('.overlay-loading-fix').show();
		
		if(parseInt($('#catselected').attr('data-val'))==2){
			$('#catselected').click();
		}
		ct_id="";
		if(catalogpressed!=""){
			ct_id=catalogpressed.parent().attr('id');
		}
		removeButSelectedMenu();
		currentPage=myvl-1;
		$('#catimagecontainer').css('opacity',0);
		getMediaImages();
	});	
	$("#paging_container_last").click(function(){
		if(currentPage>=parseInt($('#catPagecount').attr('data-count'))-1){
			return;	
		}
		var myvl = $('#catPagecount').attr('data-count');
		myvl=parseInt(myvl);		
		if(myvl<=0){
			myvl=1;
		}
		$('.overlay-loading-fix').show();
		
		if(parseInt($('#catselected').attr('data-val'))==2){
			$('#catselected').click();
		}
		ct_id="";
		if(catalogpressed!=""){
			ct_id=catalogpressed.parent().attr('id');
		}
		removeButSelectedMenu();
		currentPage=myvl-1;
		$('#catimagecontainer').css('opacity',0);
		getMediaImages();
	});	
	$("#paging_container_first").click(function(){
		if(parseInt($('#catPagecount').attr('data-page'))<=0){
			return;	
		}
		var myvl = 1;
		myvl=parseInt(myvl);		
		if(myvl<=0){
			myvl=1;
		}
		$('.overlay-loading-fix').show();
		
		if(parseInt($('#catselected').attr('data-val'))==2){
			$('#catselected').click();
		}
		ct_id="";
		if(catalogpressed!=""){
			ct_id=catalogpressed.parent().attr('id');
		}
		removeButSelectedMenu();
		currentPage=myvl-1;
		$('#catimagecontainer').css('opacity',0);
		getMediaImages();
	});	
	$("#paging_container_next").click(function(){
		if(!$(this).hasClass('inactive') && currentPage<( parseInt($('#catPagecount').attr('data-count')) - 1 ) ){
			$('.overlay-loading-fix').show();
			
			if(parseInt($('#catselected').attr('data-val'))==2){
				$('#catselected').click();
			}
			removeButSelectedMenu();
			currentPage++;
			if(currentPage>parseInt($('#catPagecount').attr('data-count'))-1){
				currentPage=parseInt($('#catPagecount').attr('data-count'))-1;
			}
			ct_id="";
			if(catalogpressed!=""){
				ct_id=catalogpressed.parent().attr('id');
			}
			$('#catimagecontainer').css('opacity',0);
			getMediaImages();
		}
	});	
	$("#paging_container_prev").click(function(){
		if(!$(this).hasClass('inactive') && currentPage>0){
			$('.overlay-loading-fix').show();
			
			if(parseInt($('#catselected').attr('data-val'))==2){
				$('#catselected').click();
			}
			removeButSelectedMenu();
			currentPage--;
			if(currentPage<0){
				currentPage=0;	
			}
			ct_id="";
			if(catalogpressed!=""){
				ct_id=catalogpressed.parent().attr('id');
			}
			$('#catimagecontainer').css('opacity',0);				
			getMediaImages();
		}
	});
}

function refreshEditCat(){	
	/*$(".editMN").each(function(){
		var $clicked = $(this);
		$clicked.fancybox({
			padding	:0,
			margin	:0,
			beforeLoad:function(){
				$('#catedit #catedittxt').val($('#catedit #catedittxt').attr('data-val'));	
				$('#catedit #catedittxt1').val($('#catedit #catedittxt1').attr('data-val'));				
				var target = $clicked.parent().attr('id');
				$.post(ReturnLink('/ajax/ajax_getcatalog.php'), {id:target},function(data){
					$('#catedit').html(data);
					editAlbumText();
				});	
			}
		});
	});*/
	
	$(".editMN").each(function(){
		var $clicked = $(this);
		var id = $(this).closest('li').attr('id');
		$clicked.fancybox({
		padding	:0,
		margin	:0,
			beforeLoad:function(){
				$('#uploadlistpopuppublishalbum').html('');
				$('#uploadlistpopuppublishalbum .inputuploadformTF').val('');
				$('#uploadlistpopuppublishalbum #logalbumaddcountry, #newphototo #logalbumaddcategory').val('0');
				$('#uploadlistpopuppublishalbum .inputuploaddescriptionTF').val('');
				$('#uploadlistpopuppublishalbum textarea[name=description]').blur();
				$('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
				$('#uploadlistpopuppublishalbum input[name=keywords]').blur();
				
				albumclicked=$clicked.closest('li');
				CatalogEdit(id);
			}
		});
	});	
	
	$(".clsimg_album").each(function(){
		var $clicked = $(this);
		$clicked.fancybox({
			padding	:0,
			margin	:0,
			beforeLoad:function(){
				$("#popup_delete_album_pic").attr('data-id',$clicked.parent().parent().attr('id'));
				$("#popup_delete_album_pic .uploadinfocheckbox_remove3").removeClass('active');
				$("#popup_delete_album_pic .uploadinfocheckbox_remove4").removeClass('active');
			}
		});
	});
}
function editAlbumText(){
	$("#catedit #cateditbut").click(function(){
		 var catedit=$(this).attr('data-id');
		
		 if(catedit!=""){				
			if($('#catedittxt').val()!="" && $('#catedittxt').val()!="edit title..."){
				$('.overlay-loading-fix').show();
				
				var txedt=$('#catedittxt').val();
				var txedt1="";
				if($('#catedittxt1').val()!="" && $('#catedittxt1').val()!="edit location..."){
					txedt1=$('#catedittxt1').val();
				}
				
				$.post(ReturnLink('/ajax/ajax_editcatalog.php'), {id:catedit,txedt:txedt,txedt1:txedt1},function(data){
					$('#catmenucontent ul').find('#'+catedit).find('.txtMN span').html(txedt);
					$(".fancybox-close").click();						
					$('.slectcateg_select').find('#'+catedit).html(txedt);	
					$('.overlay-loading-fix').hide();					
				});
			}		
		}
	});		
}
function editImageText(){
	$("#cateditimg #cateditimgbut").click(function(){
		 var catedit=$(this).attr('data-id');
		 if(catedit!=""){
			 var bool=0;
			 if($('#cateditimgtxt').val()=="" || $('#cateditimgtxt').val()=="title of the photo"){
				 bool=1;
				 $('#cateditimginput').addClass('err');
			 }else{
				 $('#cateditimginput').removeClass('err');
			 }
			 if($('#cateditimgtxt1').val()=="" || $('#cateditimgtxt1').val()=="add description..."){
				 bool=1;
				 $('#cateditimginput1').addClass('err');
			 }else{
				 $('#cateditimginput1').removeClass('err');
			 }
			 if($('#slectcateditimg').val()==""){
				 bool=1;
				 $('#slectcateditimg').addClass('err');
			 }else{
				 $('#slectcateditimg').removeClass('err');
			 }
			 if($('#cateditimgtxt2').val()=="" || $('#cateditimgtxt2').val()=="edit location..."){
				 bool=1;
				 $('#cateditimginput2').addClass('err');
			 }else{
				 $('#cateditimginput2').removeClass('err');
			 }
			 if($('#cateditimgtxt3').val()=="" || $('#cateditimgtxt3').val()=="add keywords..."){
				 bool=1;
				 $('#cateditimginput3').addClass('err');
			 }else{
				 $('#cateditimginput3').removeClass('err');
			 }
			 if(bool==1){
				 return;
			 }
			 $('.overlay-loading-fix').show();
			 var plcstr=$('#cateditimgtxt2').val();
			 if(plcstr==$('#cateditimgtxt2').attr('data-val')){
				plcstr=""; 
			 }
			 $.post(ReturnLink('/ajax/ajax_editimage.php'), {id:catedit,title:$('#cateditimgtxt').val(),desc:$('#cateditimgtxt1').val(),cat:$('#slectcateditimg').val(),place:plcstr,keywords:$('#cateditimgtxt3').val()},function(data){
				$(".fancybox-close").click();						
				$('.overlay-loading-fix').hide();					
			 });					
		}
	});
}
function refreshEditImg(){
	$(".vidimg").fancybox({
		transitionIn: 'none',
		transitionOut: 'none',		
		autoScale: false,
		autoDimensions: false,		
		width: 694,
		minWidth: 694,
		maxWidth: 694,
		height: 442,
		minHeight: 442,
		maxHeight: 442,
		padding	:0,
		margin	:0,
		type: 'iframe',
		scrolling: 'no'
	});
	$(".photoimg").fancybox();
	
	$(".editimg").each(function(){
		var $clicked = $(this);
		var id = $(this).closest('li').attr('id');
		$clicked.fancybox({
		padding	:0,
		margin	:0,
			beforeLoad:function(){
				$('#uploadlistpopuppublishalbum').html('');
				$('#uploadlistpopuppublishalbum .inputuploadformTF').val('');
				$('#uploadlistpopuppublishalbum #logalbumaddcountry, #newphototo #logalbumaddcategory').val('0');
				$('#uploadlistpopuppublishalbum .inputuploaddescriptionTF').val('');
				$('#uploadlistpopuppublishalbum textarea[name=description]').blur();
				$('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
				$('#uploadlistpopuppublishalbum input[name=keywords]').blur();
				
				albumclicked=$clicked.closest('li');
				MediaEditData(id);
			}
		});
	});
}

function CatalogEdit(id){	
	$.post(ReturnLink('/ajax/ajax_getlistalbumuploaddata.php'), {id:id},function(data){
		if(data!=false){
			$('#uploadlistpopuppublishalbum').html(data);
			resetSelectedUsers($('#uploadlistpopuppublishalbum #addmoretext_privacy'));
			$('#uploadlistpopuppublishalbum input[name=title]').blur();
			$('#uploadlistpopuppublishalbum textarea[name=description]').blur();
			$('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
			$('#uploadlistpopuppublishalbum input[name=keywords]').blur();
			
			$('#uploadlistpopuppublishalbum .uploadinfocheckboxcontainer').remove();
			
			addAutoCompleteList('uploadlistpopuppublishalbum');				
			$('#uploadlistpopuppublishalbum').attr('data-id',id);
			var privacyselcted=parseInt($('#uploadlistpopuppublishalbum .formcontainer').attr('data-value'));
			$('#uploadlistpopuppublishalbum #privacyclass_user'+privacyselcted).click();
			if(privacyselcted==USER_PRIVACY_SELECTED){
				$('#uploadlistpopuppublishalbum').find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function(){
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
			addmoreusersautocomplete_custom_journal( $('#uploadlistpopuppublishalbum #addmoretext_privacy') );			
		}
	});
}
function MediaEditData(id){	
	$.post(ReturnLink('/ajax/profile_media_details.php'), {id:id},function(data){
		if(data!=false){
			$('#uploadlistpopuppublishalbum').html(data);
			resetSelectedUsers($('#uploadlistpopuppublishalbum #addmoretext_privacy'));
			$('#uploadlistpopuppublishalbum input[name=title]').blur();
			$('#uploadlistpopuppublishalbum textarea[name=description]').blur();
			$('#uploadlistpopuppublishalbum input[name=placetakenat]').blur();
			$('#uploadlistpopuppublishalbum input[name=keywords]').blur();
			
			$('#uploadlistpopuppublishalbum .uploadinfocheckboxcontainer').remove();
			
			addAutoCompleteList('uploadlistpopuppublishalbum');				
			$('#uploadlistpopuppublishalbum').attr('data-id',id);
			var privacyselcted=parseInt($('#uploadlistpopuppublishalbum .formcontainer').attr('data-value'));
			$('#uploadlistpopuppublishalbum #privacyclass_user'+privacyselcted).click();
			if(privacyselcted==USER_PRIVACY_SELECTED){
				$('#uploadlistpopuppublishalbum').find('.peoplecontainer_custom .emailcontainer .peoplesdata').each(function(){
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
			addmoreusersautocomplete_custom_journal( $('#uploadlistpopuppublishalbum #addmoretext_privacy') );			
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
function verifyFormList(which){
	var ok = true;
	var $currobjselected=$('#'+ which);	
	$('.uploadinfomandatory span', $currobjselected ).html('');
	$('input,select,textarea',$currobjselected ).removeClass('err').each(function(){
		var name = $(this).attr('name');
		var $parenttarget=$(this).parent();
		if( (name == 'location') || (name=='location_name') || (name=='cityname') || (name=='status') || (name=='vid') || $(this).hasClass('filevalue') || (name=='filename') || (name=='addmoretext') || (name=='vpath') ){
			
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
function addAutoCompleteList(which){
	var $citynameaccent = $("input[name=citynameaccent]", $('#'+ which));	
	$citynameaccent.autocomplete({
		appendTo: "#contentcontainer",
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
function doMediaFilter(obj){
	$('.overlay-loading-fix').show();
	mediatype=$(obj).val();	
	
	if(parseInt($("#filterby").val())==3){
		$("#srchtxt").val('');
	}
	globtxtsrch=""+$("#srchtxt").val();
	currentPage=0;
	ct_id="";
	if(catalogpressed!=""){
		ct_id=catalogpressed.parent().attr('id');
	}
	frtxt="";
	totxt="";
	if(parseInt($("#filterby").val())==2){
		frtxt=$('#fromtxt').html();
		totxt=$('#totxt').html();
	}
	globkeysrch=""+$("#filterby").val();
	getMediaImages();
}
function getMediaImages(){	
	$.post(ReturnLink('/ajax/ajax_getpiccatalog.php'), {catid:ct_id,currentPage:currentPage,txtsrch:globtxtsrch,globkeysrch:globkeysrch,mediatype:mediatype,frtxt:frtxt,totxt:totxt},function(data){
		$('#catsubcontainer').html(data);
		currentPage = $('#catPagecount').attr('data-page');
		calldragslectitem();
		addListenerClose();
		refreshEditCat();
		initDocument();
		$('.overlay-loading-fix').hide();
		refreshEditImg();
	});
}
function refreshMediaCount(){
	$.post(ReturnLink('/ajax/ajax_imagecount.php'), {},function(data){
		var arr = new Array(''+data);
		$("#catAllnum").html(sprintf(t('MEDIA (%s)'),arr));
	});
}
function refreshMediaCatalogs(){
	/*if(catalogpressed!=""){
		catalogpressed="";
	}*/
	$.post(ReturnLink('/ajax/ajax_getsearchcatalog.php'), {txtsrch:""},function(data){		
		$("#catmenucontent ul").html(data);
		$("#catAllmenu").html('MY ALBUMS ('+$('#catmenucontent ul li').length+')');
		$('.scroll-panemenu').jScrollPane({showArrows: true});
		$('.scroll-panemenu .jspVerticalBar').css('right',0+"px");				
		//$('.scroll-panemenu .jspDrag').css('background','#5f5f5f');
		if(catalogpressed!=""){
			$("#catmenucontent ul").find('#'+catalogpressed.parent().attr('id')).find('.clickMN').addClass("active");
			catalogpressed=$("#catmenucontent ul").find('#'+catalogpressed.parent().attr('id')).find('.clickMN');
		}
		calldragslectitem();
		addListenerClose();
		refreshEditCat();
		$('.overlay-loading-fix').hide();
	});		
}