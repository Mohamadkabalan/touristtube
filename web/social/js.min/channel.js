var category_id=0;
var channel_category_page=0;
var old_channel_category_page=0;

function GetDIVheight(parentDiv){
	var liHeight = parentDiv.find(".MediaList li").height();
	var liLength = parentDiv.find(".MediaList li").length;
	var MediaListSize = retieveNumber( liLength / 4 );
	var MeidaHeight = ( MediaListSize * liHeight ) + 30;
	return MeidaHeight
}
function GetDIVheightHome(parentDiv){
	var liHeight = parentDiv.find(".MediaList li").height();
	var liLength = parentDiv.find(".MediaList li").length;
	var MediaListSize = retieveNumber( liLength / 5 );
	var MeidaHeight = ( MediaListSize * liHeight ) + 30;
	return MeidaHeight
}

function InitDocument(){    
    $('.closeSign.hide').mouseover(function () {
        var $parents = $('.evContainer2Over').parent();
        var posxx = $(this).offset().left - $parents.offset().left - 253;
        var posyy = $(this).offset().top - $parents.offset().top - 23;
        $('.evContainer2Over .ProfileHeaderOverin').html('hide');
        $('.evContainer2Over').css('left', posxx + 'px');
        $('.evContainer2Over').css('top', posyy + 'px');
        $('.evContainer2Over').stop().show();
    });
    $('.closeSign.hide').mouseout(function () {
        $('.evContainer2Over').hide();
    });
	$(".image").each(function(){
            var $this = $(this);
            var hide = $this.find(".hide");
            $this.mouseenter(function(){
                hide.removeClass("hide");
                hide.addClass("show");
            }).mouseleave(function(){
                hide.removeClass("show");
                hide.addClass("hide");
            })
	});
	
	
	$(".plusSign").each(function(){
		var $this = $(this);
		var popUp = $this.parent().find(".popUp");
		
		$this.click(function(){
			
			pagetype = $this.attr('data-pagetype');
			var thisIndex = $this.parent().index();
			
			var popclass = 'popUpLeft';
			var popDisplay = $this.attr('data-display')
			
			if(popDisplay){
				if( ( (thisIndex+1) % 4 ) == 0 || ( (thisIndex+1) % 4 ) == 3 ){
					popclass = 'popUpRight';
				}
			}else{
				if( ( (thisIndex+1) % 5 ) == 0 || ( (thisIndex+1) % 5 ) == 4 ){
					popclass = 'popUpRight';
				}
			}
			popUp.removeClass('popUpLeft');
			popUp.removeClass('popUpRight');
			popUp.addClass(popclass);
			
			$(".popUp").hide();
			$(".plusSign").show();
			
			popUp.show();
			$this.hide();
			objectSelected = $this.parent();
		});
	});
	
	$(".minus").each(function(){
		var $this = $(this);
		var popUp = $this.parent();
		var plusSign = $this.parent().parent().find(".plusSign");
		$this.click(function(){
			objectSelected="";
			popUp.hide();
			plusSign.show();
		});
	});
	initPhotoFancy();
}

function scrollToDiv(channel_id){
	$('.upload-overlay-loading-fix').show();
	$('#scrollableChannel').html('');
	category_id=channel_id;
	channel_category_page=0;
	old_channel_category_page=0;
	$(".load_more_channels").hide();
	$.ajax({
		url: ReturnLink('/ajax/morechannelcategory.php'),
		data: {start:0,id:channel_id},
		type: 'post',
		success: function(data){
			if(data!=''){
				$('#scrollableChannel').append(data);
				var currPageStatus=$('#scrollableChannel .currPageStatus');
				if((""+currPageStatus.attr('data-value'))=="0"){
					$(".load_more_channels").hide();
				}else{
					$(".load_more_channels").show();	
				}
				currPageStatus.remove();
				InitDocument();
			}else{
				$(".load_more_channels").show();	
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});
}

function InitContainer(media, container, mediaheight){
	var MediaContLength = media.length;
	var LastHeight = 0;
	if(MediaContLength<=3){
		LastHeight = 700;
	}else{
		LastHeight = MediaContLength * mediaheight;
	}
	
	container.height(LastHeight);
}

function showChannelNumber($this){
	var $thisWidth = $this.width();
	var $aWidth = $this.find('a').width()+5;
	var $channelNumber = $this.find('div.channelnumber');
	var $channelWidth = $channelNumber.width();
	
	$channelNumber.css({
						left:$aWidth
						})
					.show();
}

$(document).ready(function(){
	
	InitDocument();
	
	$(document).on('click',".closeSign" ,function(){
		var $this = $(this);
		var thisLi = $this.closest("li");
		var mainID = $this.attr("data-flag");
		var thisStart = $("#"+mainID).attr("data-number");
		var channelID = $("#"+mainID).attr("data-channelid");
		var catId = $("#"+mainID).attr("data-catId");
		var thisEnd = 1;
		thisLi.remove();
		$('.upload-overlay-loading-fix').show();
                $('.evContainer2Over').hide();
		$.ajax({
			url: ReturnLink('/ajax/morechannel.php'),
			data: {flag:mainID, start:thisStart, limit:thisEnd, channelid:channelID, catId:catId},
			type: 'post',
			success: function(data){
				if(data!=''){
					thisStart++;
					$("#"+mainID).attr("data-number",thisStart)
					$("#"+mainID+" ul").append(data);
					if(parseInt(thisStart + 1)>parseInt($this.attr("data-number"))){
						$('.load_more_channels_category').hide();
					}
					InitDocument();
				}
				$('.upload-overlay-loading-fix').hide();
			}
		});
	});
	
	$(document).on('click',".Expand" ,function(){
		var $this = $(this);
		var parentDiv = $this.parent();
		var liLength = parseInt(parentDiv.attr('data-count'));
		MeidaHeight = GetDIVheight( parentDiv );
		if($this.hasClass('ExpandHome')){
			var liLength = parentDiv.find(".MediaList li").length;
			MeidaHeight = GetDIVheightHome(parentDiv);
			if( liLength > 5 ){
				if( $this.hasClass("Collapse") ){
					parentDiv.stop().animate({height:'150px'},500,function(){
						$this.removeClass("Collapse");
					})
				}else{
					parentDiv.stop().animate({height:MeidaHeight+'px'},500,function(){
						$this.addClass("Collapse");
					})
				}
			}
		}else if( liLength > 4 ){
			$('.MediaCont').each(function(index, element) {
                var obj=$(this);
				if( obj.find('.Expand').hasClass("Collapse") ){
					obj.stop().animate({height:'150px'},500,function(){
						 obj.find('.Expand').removeClass("Collapse");
					})
				}
            });
			if( parentDiv.find('.Expand').hasClass("Collapse") ){
				parentDiv.stop().animate({height:'150px'},500,function(){
					 parentDiv.find('.Expand').removeClass("Collapse");
				})
			}else{
				 if(!$this.hasClass('active')){
					getChannelDataRelated(parentDiv); 
				 }else{
					parentDiv.stop().animate({height:MeidaHeight+'px'},500,function(){
						 parentDiv.find('.Expand').addClass("Collapse");
					});
				 }
			}
		}
	});
	
	
	$(".load_more_channels").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		channel_category_page++;
		getChannelCategoryRelated();
	});
	$(".load_more_channels_category").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		channel_category_page++;
		var catId = $(this).attr('data-id');
		getChannelCategoryItems(catId);
	});
	$(".load_more_channels_recently_added").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		channel_category_page++;
		var catId = $(this).attr('data-id');
		getChannelRecentlyAdded(catId);
	});	
	$(".load_more_channels_search").click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		channel_category_page++;
		getChannelsearched();
	});	
	$(document).on('click',".addNewBut" ,function(){
		var curob=$(this);
		var thisparent=curob.parent().parent();
		var mystr='';
		var formid=parseInt(curob.attr("data-value"));
		
		switch(formid){
			case 1:
				mystr='<div class="formContainer100 margintop10 linksin" data-value="1"><input name="customizedlinks" id="customizedlinks" type="text" class="RegFocus" style="width:176px; float:left;" value="" data-id=""/><div class="removeNewBut"><div class="addNewBut_over"><div class="addNewBut_overtxt">'+t('remove links')+'</div></div></div></div>';				
			break;
			case 2:
				mystr='<div class="formContainer100 margintop10 linksin" data-value="2"><input name="sociallinks" id="sociallinks" type="text" class="RegFocus" style="width:176px; float:left;" value="" data-id=""/><div class="removeNewBut"><div class="addNewBut_over"><div class="addNewBut_overtxt">'+t('remove links')+'</div></div></div></div>';				
			break;
		}
		
		thisparent.append(mystr);		
	});
	$(document).on('click',".removeNewBut" ,function(){
		var curob=$(this).parent();		
		var formid=parseInt(curob.attr("data-value"));
		switch(formid){
			case 1:
				removechannelpagedatalinks(curob.find('#customizedlinks').attr('data-id'),curob);				
			break;
			case 2:
				removechannelpagedatalinks(curob.find('#sociallinks').attr('data-id'),curob);						
			break;
		}
	});
	$(".editChannelRight_data1").click(function(){
		if(!$(this).hasClass('active')){
			$('.butEditChannelRight_data_toreset').removeClass('active');			
			var $this = $(this).parent();
			getchannelpagedata($this);
		}
	});
	$(".editChannelRight_data2").click(function(){
		if(!$(this).hasClass('active')){
			var $this = $(this).parent();
			var $more = $this.find('.more');
			
			if($(".mustopen").height() > 200){
				$more.click();
			}
			getchannelpagedata($this);
		}
	});		
	$(".editChannelRight_data3").click(function(){
		if(!$(this).hasClass('active')){
			$('.butEditChannelRight_data_toreset').removeClass('active');		
			var $this = $(this);
			getchannelpagedata($this);
		}
	});	
	$(".editChannelRight_data4").click(function(){
		if(!$(this).hasClass('active')){
			var $this = $(this);
			getchannelpagedata($this);
		}
	});
	$(document).on('click',".editChannelRight_data1_buts1" ,function(){
		var $this = $(this).parent().parent().parent();
		$this.hide();
		$this.html('');
		$('.editChannelRight_data1').removeClass('active');
	});
	$(document).on('click',".info_close1" ,function(){
		$('.editChannelRight_data1_buts1').click();
	});
	$(document).on('click',".editChannelRight_data3_buts1" ,function(){
		var $this = $(this).parent().parent().parent();
		$this.hide();
		$this.html('');
		$('.editChannelRight_data3').removeClass('active');
	});
	$(document).on('click',".info_close3" ,function(){
		$('.editChannelRight_data3_buts1').click();
	});
	$(document).on('click',".editChannelRight_data4_buts1" ,function(){
		var $this = $(this).parent().parent().parent();
		$this.hide();
		$this.html('');
		$('.editChannelRight_data4').removeClass('active');
	});
	$(document).on('click',".editChannelRight_data1_buts2" ,function(){
		var $this = $(this).parent().parent().parent();                
		var ccategory = ''+$('#edit_data_container1 select[name=ccategory]').val();
		var ccountry = ''+$('#edit_data_container1 select[name=ccountry]').val();
		var ccity = ''+$('#edit_data_container1 input[name=ccity]').val();
		var ccityid = ''+$('#edit_data_container1 input[name=ccity]').attr('data-id');
		var cstreet = ''+$('#edit_data_container1 input[name=cstreet]').val();
		var czip = ''+$('#edit_data_container1 input[name=czip]').val();
		var cphone = ''+$('#edit_data_container1 input[name=cphone]').val();
		var curl = ''+$('#edit_data_container1 input[name=curl]').val();
		var createdon = ''+$('#edit_data_container1 input[name=createdon]').val();
		
		var hidecreatedon= ($this.find('.uploadinfocheckbox1').hasClass('active')) ? 1 : 0 ;
		var hidecreatedby= ($this.find('.uploadinfocheckbox2').hasClass('active')) ? 1 : 0 ;
		var hidelocation= ($this.find('.uploadinfocheckbox3').hasClass('active')) ? 1 : 0 ;
		if(checkChannelInfo(ccategory,ccountry,czip,ccity,curl)){
			$('.upload-overlay-loading-fix').show();
			$.ajax({
				url: ReturnLink('/ajax/info_manage_channel_home.php'),
				data: {globchannelid:globchannelid, ccategory: ccategory ,ccountry : ccountry, ccityid:ccityid , ccity: ccity ,cstreet : cstreet,czip:czip,cphone:cphone,curl:curl,createdon:createdon,hidecreatedon:hidecreatedon,hidecreatedby:hidecreatedby,hidelocation:hidelocation},
				type: 'post',
				success: function(data){
					var ret = null;
					$('.upload-overlay-loading-fix').hide();
					try{
						ret = $.parseJSON(data);
					}catch(Ex){
						return ;
					}
					
					if(!ret){
						TTAlert({
							msg: t('couldnt save please try again later'),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						return ;
					}
					
					if(ret.status == 'ok'){
						$('.ChannelRight_data1_content').html(ret.content);
						$('.editChannelRight_data1_buts1').click();
					}else{
						TTAlert({
							msg: ret.error,
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
	$(document).on('click',".editChannelRight_data3_buts2" ,function(){
		var $this = $(this).parent().parent().parent();
		var cname = ''+$('#edit_data_container1 input[name=cname]').val();
		var customlinkArray=new Array();
		var customlinkArraystr="";
		var linkdata=$this.find('.customizedcontainer .linksin');
		linkdata.each(function(){
			var myobjstr=$(this).find('#customizedlinks').val();
			if(myobjstr.length != 0 && ValidURL(myobjstr)){
				customlinkArray.push(myobjstr);
			}					
		});
		if(customlinkArray.length>0){
			customlinkArraystr=customlinkArray.join('/*/');
		}
		var sociallinkArray=new Array();
		var sociallinkArraystr="";
		var linkdata=$this.find('.socialcontainer .linksin');
		linkdata.each(function(){
			var myobjstr=$(this).find('#sociallinks').val();
			if(myobjstr.length != 0 && ValidURL(myobjstr)){
				sociallinkArray.push(myobjstr);
			}					
		});
		if(sociallinkArray.length>0){
			sociallinkArraystr=sociallinkArray.join('/*/');
		}
		if(checkChannelNameInfo(cname)){
			$('.upload-overlay-loading-fix').show();
			
			$.ajax({
				url: ReturnLink('/ajax/info_link_manage_channel_home.php'),
				data: {globchannelid:globchannelid, cname: cname ,customlinkArraystr : customlinkArraystr , sociallinkArraystr: sociallinkArraystr },
				type: 'post',
				success: function(data){
					$('.upload-overlay-loading-fix').hide();
					var ret = null;
					try{
						ret = $.parseJSON(data);
					}catch(Ex){
						return ;
					}
					
					if(!ret){
						TTAlert({
							msg: t('couldnt save please try again later'),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						return ;
					}
					
					if(ret.status == 'ok'){
						$('#HeaderRight #TitleContainer').html(cname);
						$('#HeaderRight #ChannelSettings').attr('title',cname);
						$('#HeaderRight #LogoContainer img').attr('alt',cname);
						$('#SocialMediaBTN .socialcarousel ul').html(ret.content);
						if($('#SocialMediaBTN .socialcarousel ul li').length>5){
							$('#SocialMediaBTN .next_btn').removeClass('disabled');
						}else{
							$('#SocialMediaBTN .next_btn').addClass('disabled');
						}
						$('#SocialMediaBTN .prev_btn').addClass('disabled');
						InitCarouselLinks();
						$('.editChannelRight_data3_buts1').click();
					}else{
						TTAlert({
							msg: ret.error,
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
	/*$(document).on('click',".uploadinfocheckbox" ,function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
		}else{
			$(this).addClass('active');
		}
	});*/
	$(document).on('click',".editChannelRight_data2_buts1" ,function(){
		var $this = $(this).parent().parent();
		$this.find('#edit_data_container2').hide();
		$this.find('.editChannelRight_button_container').hide();
		$this.find('.butEditChannelRight_data').removeClass('active');
	});
	$(document).on('click',".editChannelRight_data2_buts2" ,function(){
		var $this = $(this).parent().parent();
		if($this.find('#edit_aboutchannel').val()!=''){
                    updatechannelpagedata("aboutchannel",$this.find('#edit_aboutchannel').val(),$this);
		}
	});
	$(document).on('click',".editChannelRight_data4_buts2" ,function(){
            var $this = $(this).parent().parent();
            //if($this.find('#slogan_txt').val()!=''){
            updatechannelpagedata("changeslogan",getObjectData($this.find('#slogan_txt')),$this);
            //}
	});
	
	/*$(".ticker").jCarouselLite({
		circular: true,
                vertical: true,
		scroll: 1,
		visible: 1,
		auto: 5000,
		speed: 500,
		hoverPause: true
	});*/
	
	$('.IndexCategory').each(function(){
		
		var $Category = $(this);
		
		$Category.mouseover(function(){
			
			$(".channelnumber").hide();
			showChannelNumber( $Category );
			
			var pos = $Category.position();
			if( $('a', $Category).hasClass('active') ) return ;
			
			$('#CategoryMarker').show().css({
				top: (pos.top) + 'px',
				left: '-1px'
			});
			
			
		}).mouseout(function(){
			
			$('#CategoryMarker').hide();
			$(".channelnumber").hide();
			
		}).click(function(){
			
			var pos = $Category.position();
			$('a', '.IndexCategory').removeClass("active");
			$('a', $Category).addClass("active");
			$('#CategoryMarker').hide();
			
			$("#Categories .marker").removeClass("selected");
			
			$Category.next('.marker').addClass("selected").css({
				top: (pos.top) + 'px',
				left: '0px'
			});
			
		});
		
	});
});
function getchannelpagedata(curob){
	$('.upload-overlay-loading-fix').show();
	var myid=curob.attr('id');
	$.ajax({
		url: ReturnLink('/ajax/info_page_data_manage_channel_home.php?no_cache='+Math.random()),
		data: {globchannelid:globchannelid,data:myid},
		type: 'post',
                cache:false,
		success: function(e){
			var mydata=""+e;
			switch(myid){
				case "ChannelRight_data1":
					$('#edit_data_container1').html(mydata);
					$('#edit_data_container1').show();
					initCreatedOn();
					addAutoCompleteList("edit_data_container1");
					curob.find('.butEditChannelRight_data').addClass('active');
				break;
				case "ChannelRight_data2":
                                case "AboutContid":   
					curob.find('#edit_data_container2 #edit_aboutchannel').val(mydata);
					curob.find('#edit_data_container2').show();
					curob.find('.editChannelRight_button_container').show();
					curob.find('.butEditChannelRight_data').addClass('active');
				break;
				case "editChannelRight_data3":
					$('#edit_data_container1').html(mydata);
					$('#edit_data_container1').show();
					curob.addClass('active');
				break;
				case "editChannelRight_data4":
					$('#edit_data_container4').html(mydata);
                                        $('#slogan_txt').blur();
					$('#edit_data_container4').show();
					curob.addClass('active');
				break;
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});	
}
function updatechannelpagedata(param1,param2,curob){
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_page_manage_channel.php'),
		data: {globchannelid:globchannelid,data:param2,str:param1},
		type: 'post',
		success: function(data){
			$('.upload-overlay-loading-fix').hide();
			var ret = null;
			try{
				ret = $.parseJSON(data);
			}catch(Ex){
				return ;
			}
			
			if(!ret){
				TTAlert({
					msg: t('Couldnt save please try again later'),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}
			
			if(ret.status == 'ok'){
				switch(param1){
					case "aboutchannel":
						var mydescription=param2;				
						mydescription = mydescription.replace(/\n/g,"<br />");
                                                if( curob.hasClass('AboutCont') ){
                                                    curob.find('.stanTXT').html(mydescription);
                                                }else{
                                                    curob.find('.stanTXT').html('<span class="yellow bold font12 yellowabout">'+t("ABOUT")+'</span><br/> '+mydescription);  
                                                }
						
						if($("#stanTXT").height()<=105){
							$("#more").hide();
						}else{
							$("#more").show();
						}
						curob.find('.editChannelRight_data2_buts1').click();
					break;
					case "profilepicchannel":
					case "coverpicchannel":
                                            document.location.reload();
                                        break;
					case "changeslogan":
                                            $('.Slogan').html(param2);
                                            if(param2!=''){
						curob.find('.editChannelRight_data4_buts1').click();
                                            }
					break;
				}				
			}else{
				TTAlert({
					msg: ret.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
		}
	});	
}

function initCreatedOn(){
	Calendar.setup({
		inputField : "createdon",
                noScroll  	 : true,
		trigger    : "createdon",
		align:"B",
		onSelect   : function(calss) {
			var datessl = calss.selection.get();
			if (datessl) {
				datessl = Calendar.intToDate(datessl);
				$('#createdon').attr('data-cal',Calendar.printDate(datessl, "%Y-%m-%d"));
			}
			this.hide();
		},
		disabled      : function(date) {
				var d = new Date();
				d.setHours(12,30,0,0);
				return (date > d);
		},
		dateFormat : "%d / %m / %Y"
	});
}
function addAutoCompleteList(which){
	var $ccity = $("input[name=ccity]", $('#'+ which));	
	$ccity.autocomplete({
		appendTo: "#edit_data_container1",
		search: function(event, ui) {
			var $country = $('#ccountry');
			var cc = $country.val();
			if(cc == 'ZZ'){
				$country.addClass('err');
				event.preventDefault();
			}else{
				$ccity.autocomplete( "option", "source", ReturnLink('/ajax/uploadGetCities.php?cc=' + cc) );
			}
		},
		select: function(event, ui) {
			$ccity.val(ui.item.value);
                        $ccity.attr('data-id',ui.item.id);
			event.preventDefault();
		}
	});
}
function checkChannelInfo(ccategory,ccountry,czip,ccity,curl){
	$('#edit_data_container1 input').removeClass('InputErr');
	$('#edit_data_container1 select').removeClass('InputErr');
	$('#edit_data_container1 .errorclass').html('');
        var zip_required = 0;
	if($("#czip_ttlogo").css('display') !="none")
		zip_required = 1;
	if(ccategory == 0){
		$('#edit_data_container1 .errorclass').html(t('Please select category'));
		$('#edit_data_container1 select[name=ccategory]').addClass('InputErr');
		return false;
	}else if(ccountry==0){
		$('#edit_data_container1 .errorclass').html(t('Please select country'));
		$('#edit_data_container1 select[name=ccountry]').addClass('InputErr');
		return false;
	}/*else if(czip.length == 0 && zip_required == 1){
		$('#edit_data_container1 .errorclass').html(t('Please enter a zip code'));
		$('#edit_data_container1 input[name=czip]').addClass('InputErr');
		return false;
	}*/else if(ccity.length == 0 && zip_required == 1){
		$('#edit_data_container1 .errorclass').html(t('Please enter a valid zip code'));
		$('#edit_data_container1 input[name=ccity]').addClass('InputErr');
		return false;
	}else if (!ValidURL(curl) && curl!='') {
            $('#edit_data_container1 .errorclass').html(t('Please enter a valid url'));
            $('#edit_data_container1 input[name=curl]').addClass('InputErr');
            return false;
        }
	return true;
}
function checkChannelNameInfo(cname){
	$('#edit_data_container1 input').removeClass('InputErr');
	$('#edit_data_container1 .errorclass').html('');	
	if(cname.length == 0){
		$('#edit_data_container1 .errorclass').html(t('Invalid channel name'));
		$('#edit_data_container1 input[name=cname]').addClass('InputErr');
		return false;
	}
	return true;
}
function removechannelpagedatalinks(id,curob){
	if(id==""){
		curob.remove();
		return;
	}
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/info_page_links_delete_manage_channel.php'),
		data: {globchannelid:globchannelid,id:id},
		type: 'post',
		success: function(data){
			$('.upload-overlay-loading-fix').hide();
			var ret = null;
			try{
				ret = $.parseJSON(data);
			}catch(Ex){
				return ;
			}
			
			if(!ret){
				TTAlert({
					msg: t('couldnt save please try again later'),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}
			
			if(ret.status == 'ok'){
				curob.remove();	
			}else{
				TTAlert({
					msg: ret.error,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
		}
	});	
}
function updateImage(str,curname,_type){
	if(_type=="editChannelRight_data5"){
            var biglink = ReturnLink('/media/channel/'+globchannelid+'/'+curname);
            if($('#HeaderImage .channelAvatarLink').length>0){
		$('#LogoContainer .channelAvatarLink img').remove();
		$('#LogoContainer .channelAvatarLink').append(str);
                $('#LogoContainer .channelAvatarLink').attr('href',biglink);
            }else{
                $('#LogoContainer img').remove();
		$('#LogoContainer').append(str);
            }
		updatechannelpagedata("profilepicchannel",curname);
	}else if(_type=="editChannelRight_data6"){
            var biglink = ReturnLink('/media/channel/'+globchannelid+'/'+curname);
                if($('#HeaderImage .channelAvatarLink').length>0){
                    $('#HeaderImage .channelAvatarLink img').remove();
                    $('#HeaderImage .channelAvatarLink').append(str);
                    $('#HeaderImage .channelAvatarLink').attr('href',biglink);
                }else{
                    $('#HeaderImage img').remove();
                    $('#HeaderImage').append(str);
                }
		updatechannelpagedata("coverpicchannel",curname);
	}
	closeFancyBox();
}
function getMediaDataRelated(){
	var mainID = "";
	if(pagetype=="v"){
		mainID = "MediaVideo";
	}else if(pagetype=="i"){
		mainID = "MediaImage";
	}else{
		mainID = "MediaAlbum";
	}
	
	var thisStart = parseInt($("#"+mainID).attr("data-number"))-1;
	var thisEnd = 1;	
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/morechannel.php'),
		data: {flag:mainID, start:thisStart, limit:thisEnd, channelid:globchannelid},
		type: 'post',
		success: function(data){
			if(data!=''){
				thisStart++;
				$("#"+mainID).attr("data-number",thisStart)
				$("#"+mainID+" ul").append(data);
				InitDocument();
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});
}
function getChannelCategoryRelated(){	
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/morechannelcategory.php'),
		data: {start:channel_category_page,id:category_id},
		type: 'post',
		success: function(data){
			if(data!=''){
				$('#scrollableChannel').append(data);
				var currPageStatus=$('#scrollableChannel .currPageStatus');
				if((""+currPageStatus.attr('data-value'))=="0"){
					$(".load_more_channels").hide();
				}else{
					$(".load_more_channels").show();	
				}
				currPageStatus.remove();
				InitDocument();
			}else{
				$(".load_more_channels").show();	
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});

}
function getChannelDataRelated(obj){
	var catId=obj.attr('id');
	var oldstart=parseInt(obj.find('.ListAll').attr('data-number'));
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/morechanneldata.php'),
		data: {catId:catId,start:oldstart},
		type: 'post',
		success: function(data){
			if(data!=''){
				obj.find('.ListAll ul').append(data);
				
				var newstart=obj.find('.ListAll ul li').length-4+oldstart;
				obj.find('.ListAll').attr('data-number',newstart);
				
				obj.find('.Expand').addClass("active");
				var liLength = obj.find(".MediaList li").length;
				MeidaHeight = GetDIVheight( obj);
				if( liLength > 4 ){
					obj.stop().animate({height:MeidaHeight+'px'},500,function(){
						 obj.find('.Expand').addClass("Collapse");
					})
				}
				InitDocument();
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});
	

}
function getChannelCategoryItems(catId){	
	var oldstart=parseInt($('#ChannelBox .ListAll').attr('data-number'));
	var skip=(oldstart - ( old_channel_category_page + 1 )*32 );
	
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/morechannelitem.php'),
		data: {start:channel_category_page, catId:catId,skip:skip},
		type: 'post',
		success: function(data){
			if(data!=''){
				$('#ChannelBox ul').append(data);
				
				var newstart=$('#ChannelBox .ListAll ul li').length+skip;
				$('#ChannelBox .ListAll').attr('data-number',newstart);
				
				var currPageStatus=$('#ChannelBox .currPageStatus');
				if((""+currPageStatus.attr('data-value'))=="0"){
					$(".load_more_channels_category").hide();
				}else{
					$(".load_more_channels_category").show();	
				}
				currPageStatus.remove();
				InitDocument();
				old_channel_category_page=channel_category_page;
			}else{
				$(".load_more_channels_category").show();	
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});

}
function getChannelRecentlyAdded(catId){	
	var oldstart=parseInt($('#ChannelBox .ListAll').attr('data-number'));
	var skip=(oldstart - ( old_channel_category_page + 1 )*32 );
	
	$('.upload-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/morechannelrecentlyaddeds.php'),
		data: {start:channel_category_page, catId:catId,skip:skip},
		type: 'post',
		success: function(data){
			if(data!=''){
				$('#ChannelBox ul').append(data);
				
				var newstart=$('#ChannelBox .ListAll ul li').length+skip;
				$('#ChannelBox .ListAll').attr('data-number',newstart);
				
				var currPageStatus=$('#ChannelBox .currPageStatus');
				if((""+currPageStatus.attr('data-value'))=="0"){
					$(".load_more_channels_recently_added").hide();
				}else{
					$(".load_more_channels_recently_added").show();	
				}
				currPageStatus.remove();
				InitDocument();
				old_channel_category_page=channel_category_page;
			}else{
				$(".load_more_channels_recently_added").show();	
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});

}

function getChannelsearched(){
	$('.upload-overlay-loading-fix').show();
	var page = $(".load_more_channels_search").attr('data-page');
	var c = $(".load_more_channels_search").attr('data-c');
	var co = $(".load_more_channels_search").attr('data-co');
	var t = $(".load_more_channels_search").attr('data-t');
	//var criteria = $(".load_more_channels_search").attr('data-criteria');
	$.ajax({
		url: ReturnLink('/ajax/morechannelsearchitem.php'),
		data: {page:page,c:c,t:t,co:co},
		type: 'post',
		success: function(data){
			if(data!=''){
				$('#MiddelMainChannel ul').append(data);
				var currPageStatus = $('#MiddelMainChannel .currPageStatus');
				if( ( currPageStatus.attr('data-value') ) == "0" ){
					$(".load_more_channels_search").hide();
				}else{
					page++;
					$(".load_more_channels_search").attr("data-page",page).show();	
				}
				currPageStatus.remove();
				InitDocument();
			}else{
				$(".load_more_channels_search").show();	
			}
			$('.upload-overlay-loading-fix').hide();
		}
	});
}

