var shoppingcart_button;
var globtxtsrch="";
var currentpage_number =0;
var globcaractersearch="";
var friendsection=true;
$(document).ready(function() {
    $container = $('#container');
    $('#loadmore').click(function(){
        if($(this).data('no_more')) return;
        ProfilePage++;
        RebuildLinkNetwork();
        displayNetworkDataRelates();        
   });
    $('#privacy_icon_glob').mouseover(function() {
        var diffx = $('#searchfriendinput').offset().left+255;
        var diffy = $('#searchfriendinput').offset().top+22;
        var posxx = $(this).offset().left-diffx;
        var posyy = $(this).offset().top-diffy;

        $('.privacybuttonsOver .ProfileHeaderOverin').html($(this).attr('data-title'));
        $('.privacybuttonsOver').css('left', posxx + 'px');
        $('.privacybuttonsOver').css('top', posyy + 'px');
        $('.privacybuttonsOver').stop().show();
    });
    $('#privacy_icon_glob').mouseout(function() {
        $('.privacybuttonsOver').hide();
    });  			
    $(document).on('mouseover', '#searchfriendbutalphabet, #alphabetcnt', function(){
        $("#searchfriendbutalphabetcontainer").stop();
        $("#searchfriendbutalphabetcontainer").animate({'width':275+"px",'height':106+"px"},500);			
    });
    $(document).on('mouseout', "#searchfriendbutalphabet, #alphabetcnt" ,function(){
        $("#searchfriendbutalphabetcontainer").stop();
        $("#searchfriendbutalphabetcontainer").animate({'width':51+"px",'height':20+"px"},500);
    });

    $(document).on('mouseover', '#tubefriendbuttonsublist', function(){
        $("#tubefriendbuttonsublist").stop();
        $("#tubefriendbuttonsublist").animate({'width':252+"px"},500);			
    });
    $(document).on('mouseout', "#tubefriendbuttonsublist" ,function(){
        $("#tubefriendbuttonsublist").stop();
        $("#tubefriendbuttonsublist").animate({'width':22+"px"},500);
    });
	
    $(document).on('click', "#alphabetcnt ul li",function(){
        if(!$(this).hasClass('inactive')){
            $("#alphabetcnt ul li").removeClass('active');

            $("#srchfriendtxt").val('');
            $("#srchfriendtxt").blur();
            globtxtsrch="";
            globcaractersearch=$(this).attr('data-value');
            if(String(""+globcaractersearch)=="all"){
                globcaractersearch='';	
            }
            if(globcaractersearch=="#"){
                globcaractersearch="sharp";
            }
            ProfilePage = 0;
            $container.html('');
            $('#loadmore').hide();
            RebuildLinkNetwork();
            displayNetworkDataRelates();
        }			
    });	
	$("#searchfriendbut").click(function(){
                $('.upload-overlay-loading-fix-file').show();
		$('#search_alpha').val('');
		if($("#srchfriendtxt").val() == $("#srchfriendtxt").attr('data-val')){
			$("#srchfriendtxt").val('');
                        $("#srchfriendtxt").blur();
                        globtxtsrch="";
		}else{
                    globtxtsrch =$("#srchfriendtxt").val();
                }
                globcaractersearch='';
                ProfilePage = 0;
                $container.html('');
                $('#loadmore').hide();
                RebuildLinkNetwork();
                displayNetworkDataRelates();
	});
	
	$('.one-tuber .follow_friend').each(function(){
		$(this).click(function(){
			var id = $(this).attr('rel');
			var $this=  $(this);
			var data_user = $(this).attr('data-user');
			
			$.ajax({
				url: ReturnLink('/ajax/subscribe.php'),
				data: {id: id},
				type: 'post',
				success: function(data){
					var jres = null;
					try{
						jres = $.parseJSON( data );
					}catch(Ex){
						
					}
					if( !jres ){
						TTAlert({
							msg: t('Your session timed out. Please login.'),
							type: 'alert',
							btn1: t('ok'),
							btn2: '',
							btn2Callback: null
						});
						return ;
					}

					TTAlert({
						msg: sprintf( t('you are now following %s') ,[data_user]),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});	
					$this.closest('.footerTuber').find('.footerTuber_seperator').remove();
					$this.remove();		
				}
			});

		});
	});
	$(document).on('click','.one-tuber .add_friend_profile',function(){
		var id = $(this).attr('rel');
		var $this=  $(this);
		var data_user = $(this).attr('data-user');
                var data_status = $(this).attr('data-status');
		
		$.ajax({
			url: ReturnLink('/ajax/tuber_friend_request.php'),
			data: {id: id},
			type: 'post',
			success: function(data){
				var jres = null;
				try{
					jres = $.parseJSON( data );
				}catch(Ex){

				}
				if( !jres ){
					TTAlert({
						msg: t("Couldn't Process Request. Please try again later."),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}
				
				
                                if(data_status!="2"){
                                    TTAlert({
                                            msg: sprintf( t('friendship request sent to %s') , [data_user] ),
                                            type: 'alert',
                                            btn1: t('ok'),
                                            btn2: '',
                                            btn2Callback: null
                                    });
                                }
				$this.closest('.footerTuber').find('.footerTuber_seperator').remove();
				$this.remove();
			}
		});

	});
	
	$("#privacy_down_arrow").click(function(){
		$("#privacy_box").show();
	});
	$("#privacy_close_button").click(function(){
		$("#privacy_box").hide();
	});
	
	// Change the privacy icon.
	initResetSelectedUsers($('#addmoretext_privacy'));
	$("#privacy_select").change(function(){
		var selectval=parseInt($(this).val());
		
		if(selectval==USER_PRIVACY_SELECTED){
			$(this).parent().find('.privacy_picker').removeClass('displaynone');
		}else{
			initResetSelectedUsers($(this).parent().find('.addmore input'));
			$(this).parent().find('.uploadinfocheckbox').removeClass('active');
			$(this).parent().find('.addmore input').val('');
			$(this).parent().find('.addmore input').blur();
			$(this).parent().find('.peoplesdata').each(function() {
				var parents=$(this);
				parents.remove();				
            });
			$(this).parent().find('.privacy_picker').addClass('displaynone');
		}
		initResetIcon($(this).parent().find('.privacy_icon'));
		$(this).parent().find('.privacy_icon').addClass('privacy_icon'+selectval);
	});
	$("#privacy_select").change();
	var privacyselcted=parseInt($('#privacy_select').val());
	
	if(privacyselcted==USER_PRIVACY_SELECTED){
		$("#privacy_box").show();
		$('.peoplecontainer .emailcontainer .peoplesdata').each(function(){
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
		$("#privacy_box").hide();
	}
	
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
			}else if($(this).attr('id') == 'optionBox_tuber_hide' ){
                            $('#optionBox_tuber_remove').removeClass('active');
                            remove_notification_toggle = 0;
			}else if($(this).attr('id') == 'optionBox_tuber_remove' ){
                            hide_notification_toggle = 0;
                            $('#optionBox_tuber_hide').removeClass('active');
			}
			$(this).addClass('active');
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
	$(document).on('click',".private_save_buts" ,function(){
		var curob=$(this).parent();
		privacyValue=parseInt($("#privacy_select").val());
		privacyArray=new Array();
		if(privacyValue==USER_PRIVACY_SELECTED){
			curob.find('.peoplecontainer .emailcontainer .peoplesdata').each(function(){
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
			var entity_str=0;
			if( friend_section =="friends" ){
				entity_str=SOCIAL_ENTITY_PROFILE_FRIENDS;
			}else if( friend_section =="followers" ){
				entity_str=SOCIAL_ENTITY_PROFILE_FOLLOWERS;
			}else if( friend_section =="followings" ){
				entity_str=SOCIAL_ENTITY_PROFILE_FOLLOWINGS;
			}
			TTCallAPI({
				what: 'user/privacy_extand/add',
				data: {privacyValue:privacyValue, privacyArray:privacyArray, entity_type: entity_str , entity_id:0},
				callback: function(ret){
                                    $('#privacy_icon_glob').attr('class','privacy_icon'+$("#privacy_select").val());
                                    $('#privacy_icon_glob').attr('data-title',$('#privacy_select option:selected').text());
                                    $("#privacy_close_button").click();
				}
			});
		}else{
			TTAlert({
				msg: t('Invalid privacy data'),
				type: 'alert',
				btn1: t('ok')
			});	
		}
	});
	
	$(".suggested_friends_list").on('mouseover','img', function(e){
		if( !$(this).data('title') ){
			$(this).data('title', $(this).attr('title') );
			$(this).attr('title', '');
		}
		var title = $(this).data('title');
        var $href= $(this).parent().attr('href');
		var c = (title !== "" ) ? "<br/>" + title : "";
		$("body").append("<div id='preview'><a href='"+$href+"' title='"+title+"'><img src='"+ $(this).attr('data-src') +"' alt='Image preview' style='width: 100px;height: 100px;'/></a>"+ c +"</div>");
		var pos_top = $(this).offset().top;
		var pos_left = $(this).offset().left + 28; //width of thumb is 28
		$("#preview").css("top",pos_top + "px").css("left",pos_left + "px").css({
			position:'absolute',
			border: '1px solid #ccc',
			background: '#333',
			'font-size': '12px',
			padding: '5px',
			display: 'none',
			width:'100px',
			color:'#fff',
			'z-index': 10000,
			'text-align': 'center'
		}).fadeIn("fast");						
	}).on('mouseout','img', function(){
		$("#preview").remove();
	}).mousemove(function(e){
		//$("#preview").css("top",(e.pageY - yOffset) + "px").css("left",(e.pageX + xOffset) + "px");
	});
	
	$(document).on('click','.tubeoverdatabutrmv',function(){
		var curobjclk=$(this);
		var target=curobjclk.parent().parent();
		var myname=""+target.attr('data-name');			
		if(isAlpha(myname)){
			var alpobj=$('#searchfriendbutalphabetcontainer #alphabetcnt ul').find('#'+myname);
		}else{
			var alpobj=$('#searchfriendbutalphabetcontainer #alphabetcnt ul').find('#dyese');	
		}
		var confirmstr= sprintf( t("are you sure you want to remove permanently %s") , [myname] ) ;
		
		TTAlert({
			msg: confirmstr,
			type: 'action',
			btn1: t('cancel'),
			btn2: t('confirm'),
			btn2Callback: function(data){
				if(data){
					contextRejectFriend(target.attr('data-id'));	
				}
			}
		});
	});
	$(document).on('click','.tubeoverdatabutblc',function(){
		var curobjclk=$(this);
		
		var target=curobjclk.parent().parent();
		contextBlockFriend(target);
	});
	$(document).on('click','.tubeoverdatabutufl',function(){
		var curobj=$(this);
		var target=$(this).parent().parent();
		var id = target.attr('data-id');
		var myname=""+target.attr('data-name');	
		
		TTAlert({
			msg: sprintf( t('confirm to unfollow %s') , [myname] ),
			type: 'action',
			btn1: t('cancel'),
			btn2: t('confirm'),
			btn2Callback: function(data){
				if( data){
					$('.upload-overlay-loading-fix-file').show();
					$.ajax({
						url: ReturnLink('/ajax/profile_feed_unfollow.php'),
						data: {feed_user_id: id},
						type: 'post',
						success: function(data){
                                                        $('.upload-overlay-loading-fix-file').hide();
							var jres = null;
							try{
								jres = $.parseJSON( data );
							}catch(Ex){								
							}
							if( !jres ){
								TTAlert({
									msg: t("Couldn't Process Request. Please try again later."),
									type: 'alert',
									btn1: t('ok'),
									btn2: '',
									btn2Callback: null
								});
								return ;
							}
							window.location.reload();
						}
					});
				}
			}
		});
	});
	$(document).on('click','#unblock_button_network',function(){
		var curobjclk=$(this);
		
		var target=curobjclk.closest('.network_top_data_item');
		contextUnBlockFriend(target);
	});
	$(document).on('click','#remove_button_network',function(){
		var curobjclk=$(this);
		var target=curobjclk.closest('.network_top_data_item');
		var myname=""+target.attr('data-name');			
		
		var confirmstr= sprintf( t("are you sure you want to cancel friendship request to %s") , [myname] ) ;
		TTAlert({
			msg: confirmstr,
			type: 'action',
			btn1: t('cancel'),
			btn2: t('confirm'),
			btn2Callback: function(data){
				if(data){
					contextRejectFriend(target.attr('data-id'));	
				}
			}
		});
	});
	$(document).on('click','#add_friend_button_network',function(){
		var $this=$(this);
		var target=$this.closest('.network_top_data_item');
		var data_user=""+target.attr('data-name');
		var id = target.attr('data-id');
			
		$.ajax({
			url: ReturnLink('/ajax/tuber_friend_request.php'),
			data: {id: id},
			type: 'post',
			success: function(data){
				var jres = null;
				try{
					jres = $.parseJSON( data );
				}catch(Ex){

				}
				if( !jres ){
					TTAlert({
						msg: t("Couldn't Process Request. Please try again later."),
						type: 'alert',
						btn1: t('ok'),
						btn2: '',
						btn2Callback: null
					});
					return ;
				}
				
				window.location.reload();
				/*TTAlert({
					msg: sprintf( t('friendship request sent to %s') , [data_user]),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: function(){
						window.location.reload();
					}
				});*/
			}
		});

	});
	$(document).on('click','.tubeoverdatabutntf',function(){
		var curobjclk=$(this);
		
		var target=curobjclk.parent().parent().attr('data-id');
		if(String(""+curobjclk.attr('data-status'))=="1"){
			contextShowHideNotificationsFriend(target,0);
			curobjclk.attr('data-status','0');
			curobjclk.find('.tubeoverdatabutntficon').addClass('inactive');
		}else{
			contextShowHideNotificationsFriend(target,1);
			curobjclk.attr('data-status','1');
			curobjclk.find('.tubeoverdatabutntficon').removeClass('inactive');
		}
	});
	$(document).on('click','.tubeoverdatabutacp',function(){
		var curobjclk=$(this);
		
		var target=curobjclk.parent();
		var myname=""+target.attr('data-name')[0];			
		if(isAlpha(myname)){
			var alpobj=$('#searchfriendbutalphabetcontainer #alphabetcnt ul').find('#'+myname);
		}else{
			var alpobj=$('#searchfriendbutalphabetcontainer #alphabetcnt ul').find('#dyese');	
		}
		contextAcceptIgnored(target.attr('data-id'),alpobj);
	});
	$(document).on('click',"#hide_button" ,function(){
		updatefriendpagedata("is_visible",0,$(this).closest('.element'));
	});
	$(document).on('click',".hideText" ,function(){
		updatefriendpagedata("is_visible",1,$(this).closest('.element'));
	});
	// Show or hide parts of the owner-report popup based on the user's choice of what to report.
	$(document).on('click','input:radio[name=reportTuberCategory]',function(){
		if( $(this).val() == 'tuber'){
			$("#fillerDiv").hide();
			$("#reportContent").hide();
			$("#reportTuber").show();
			$(".sharepopup_butcontainer").show();
		} else {
			$("#fillerDiv").hide();
			$("#reportTuber").hide();
			$("#reportContent").show();
			$(".sharepopup_butcontainer").show();
		}
	});
        $(document).on('click',"#optionBox_tuber_hide" ,function(){
            if( hide_notification_toggle == 0 ){
                $('.sendButton_reason_tuber').html(t('send'));
                hide_notification_toggle = 1;
            } else {
                $('.sendButton_reason_tuber').html(t('report'));
                hide_notification_toggle = 0;
            }
        });
        $(document).on('click',"#optionBox_tuber_remove" ,function(){
            if( remove_notification_toggle == 0 ){
                $('.sendButton_reason_tuber').html(t('send'));
                remove_notification_toggle = 1;
            } else {
                $('.sendButton_reason_tuber').html(t('report'));
                remove_notification_toggle = 0;
            }
        });
	$(document).on('click',".sharepopup_butBRCancel" ,function(){		
		$('.fancybox-close').click();
	});
        $(document).on('click',".sendButton_reason_tuber" ,function(){
            $("#sharepopup_error").html('');                
            if($('#optionBox_tuber_hide').hasClass('active')){
                $('.sharepopup_butBRCancel').click();
                if( $('.profile-friend-pic.active').length>0 ){
                    $('.profile-friend-pic.active').closest('.element').find('#hide_button').click();
                }                
            }else if($('#optionBox_tuber_remove').hasClass('active')){
                $('.sharepopup_butBRCancel').click();
                if( friend_section =="friends" ){
                    $('.tubeoverdatabutrmv').click();
                }else{
                   $('.tubeoverdatabutufl').click(); 
                }
            }else if( $('.uploadinfocheckbox_reason').hasClass('active') ){
		$('.upload-overlay-loading-fix').show();
                var $parent = $(this).closest('.sharepopup_container');
                var entity_id = $parent.attr('data-id');
                var entity_type = $parent.attr('data-type');
                var channel_id = $parent.attr('data-channel');
                var msg = '';
                var reason_array = new Array();
                $parent.find('.uploadinfocheckbox_reason.active').each(function(){
                    var $this = $(this);
                    reason_array.push( $this.attr('data-id') );
                });
                var reason = reason_array.join(',');
		TTCallAPI({
			what: 'user/report/add',
			data: {entity_type:entity_type, entity_id:entity_id, channel_id : channel_id, msg: msg , reason:reason},
			callback: function(ret){
                            $('.upload-overlay-loading-fix').hide();
                            if( ret.status === 'error' ){
                                $parent.find("#sharepopup_error").html(ret.msg);
				return;
                            }
                            closeFancyBox();
                            TTAlert({
                                    msg: ret.msg,
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                            });
			}
		});
            }else{
                $("#sharepopup_error").html(t('Please choose one of the above proposed options to Report'));			 	
            }
        });
	$(document).on('click',".network_top_button" ,function(){
		$(".network_top_arrow").click();
		$('.network_top_button').removeClass('active');
		$(this).addClass('active');
		currentpage_number = 0;
		$('.scrollpane_data_up').html('<div class="scrollpane_data formContainer100"><div class="network_top_data_all" id="network_top_data_container"></div></div>');
		getBlockedDataRelated();
	});
	$(document).on('click',".loadmore_data" ,function(){
		currentpage_number++;
		getBlockedDataRelated();
	});
	InitDocumentNetwork();
});
function getBlockedDataRelated(){
    $.ajaxSetup({
        type: 'POST',
        headers: { "cache-control": "no-cache" }
    });

	var curr_cls = $('.network_top_button.active').attr('id');
	var php_str="";
	var title_str="";
	if(curr_cls == "blocked_button_network" ){
		php_str="/ajax/ajax_loadmoreblocked.php";
		title_str = t("blocked") ;
	}else if(curr_cls == "ignored_button_network" ){
		php_str="/ajax/ajax_loadmoreignored.php";
		title_str = t("ignored");
	}else if(curr_cls == "sentrequests_button_network" ){
		php_str="/ajax/ajax_loadmoresentrequests.php";
		title_str= t("my sent requests");
	}
	
	$('.upload-overlay-loading-fix-file').show();

	$.post( ReturnLink( php_str ) , { userId:userId , currentpage:currentpage_number },
        function(data){
		if(data!=false){
                    console.log('no_cache'+userId)
			$('#network_top_data_container').append(data);
			var currPageStatus=$('#network_top_data_container .currPageStatus');
			if((""+currPageStatus.attr('data-value'))=="0"){
				$(".loadmore_data").addClass('displaynone');
			}else{
				$(".loadmore_data").removeClass('displaynone');	
			}
			$('.network_top_data_title').html(title_str+' <span>('+currPageStatus.attr('data-count')+')</span>');
			currPageStatus.remove();
			$(".scrollpane_data").show();
			initscrollPane_data();
			$(".linestyle_container1").show();	
		}else{
			$(".loadmore_data").hide();	
		}
		
		$('.upload-overlay-loading-fix-file').hide();
	});
}
function initscrollPane_data(){
	$(".scrollpane_data").jScrollPane();
}
function updatefriendpagedata(param1,param2,obj){
	$('.upload-overlay-loading-fix-file').show();
        $('.profile-friend-minus').click();
	var php_str="";
	var reverse_data = 0;
	if( friend_section =="friends" ){
		php_str="/ajax/info_friend_update.php";
	}else if( friend_section =="followers" ){
		 reverse_data = 0;
		 php_str="/ajax/info_followers_update.php";
	}else if( friend_section =="followings" ){
		 reverse_data = 1;
		 php_str="/ajax/info_followers_update.php";
	}
	
	$.ajax({
		url: ReturnLink(php_str),
		data: {str:param1,data:param2,id:obj.find('.Profile').attr('data-id'),reverse_data:reverse_data},
		type: 'post',
		success: function(data){
			if(param1=="is_visible"){
				if(parseInt(param2)==0){
					obj.find('.hideitems').removeClass('displaynone');
				}else{
					obj.find('.hideitems').addClass('displaynone');
				}
			}
			$('.upload-overlay-loading-fix-file').hide();
		}
	});	
}
function addReportFunction(is_owner){
    var $this = $(this);
    var $parent = $(".tubeoverdatabutrpt").closest('#tubeoverdata');
    initReportNetworkFunctions($(".tubeoverdatabutrpt"),SOCIAL_ENTITY_USER,$parent.attr('data-id'),friend_section,is_owner);
}
function initReportNetworkFunctions(obj,data_type,data_id,data_friend_section,show_connections){
    obj.fancybox({
        padding	:0,
        margin	:0,
        beforeLoad:function(){
            var show_disconnect = 0;
            
            $('#sharepopup').html('');                        
            var str='<div class="sharepopup_container" style="margin-left:34px; width:506px; height:382px"></div>';
            $('#sharepopup').html(str);

            hide_notification_toggle = 0;
            remove_notification_toggle = 0;
            $.ajax({
                type: "POST",
                cache:false,
                url: ReturnLink("/ajax/popup_report_data.php?no_cache="+Math.random() ),
                data: {type:data_type, show_disconnect:show_disconnect,data_id:data_id,show_connections:show_connections,channel_id:channelGlobalID(),data_friend_section:data_friend_section},
                success: function(data) {
                    var jres = null;
                    try{
                        jres = $.parseJSON( data );
                    }catch(Ex){
                        closeFancyBox();
                    }
                    if( !jres ){
                        closeFancyBox();
                        return ;
                    }
                    $('#sharepopup').html(jres.data);
                }
            });
        }
    });
}
function initResetIcon(obj){
    obj.removeClass('privacy_icon1');
    obj.removeClass('privacy_icon2');
    obj.removeClass('privacy_icon3');
    obj.removeClass('privacy_icon4');
    obj.removeClass('privacy_icon5');
    obj.removeClass('privacy_icon6');
}

function initResetSelectedUsers(obj){
	resetSelectedUsers(obj);
}
function InitDocumentNetwork(){	
	$(".TubersAction").each(function(){
		var $this = $(this);
		var hide = $this.find(".hide");
		$this
			.mouseenter(function(){
				hide.removeClass("hide");
			})
			.mouseleave(function(){
				hide.addClass("hide");
			})
	});
        
    $(".profile-friend-pic").each(function() {
        var $this = $(this);
        var popUp = $this.parent().parent().find(".popUp");

        $this.click(function() {

            var thisIndex = $this.parent().parent().index();
            var popclass = 'popUpLeft';
            if (((thisIndex + 1) % 3) == 0 ) {
                popclass = 'popUpRight';
            }
            popUp.removeClass('popUpLeft');
            popUp.removeClass('popUpRight');
            popUp.addClass(popclass);

            $(".popUp").hide();
            $(".profile-friend-plus").show();
            $('.profile-friend-pic').removeClass('active');

            popUp.show();
            $this.parent().parent().find(".profile-friend-plus").hide();
            $this.parent().parent().find('.profile-friend-pic').addClass('active');
        });
    });

    $(".profile-friend-minus").each(function() {
        var $this = $(this);
        var popUp = $this.closest('.popUp');
        var plusSign = $this.closest('.popUp').parent().find(".profile-friend-plus");
        $this.click(function() {            
            popUp.hide();
            plusSign.show();
            $('.profile-friend-pic').removeClass('active');
        });
    });
    addReportFunction(is_owner);
}
function contextUnBlockFriend(obj){
	TTAlert({
		msg: sprintf( t('Are you sure you want to unblock %s to connect with each other again on Tourist Tube') ,[obj.attr('data-name')] ) ,
		type: 'action',
		btn1: t('cancel'),
		btn2: t('confirm'),
		btn2Callback: function(data){
			if(data){
				$('.chat-overlay-loading-fix').show();
				$.ajax({
                                    url: ReturnLink('/ajax/profile_friend.php'),
                                    data: {fid : obj.attr('data-id'), op : 'unblock_frnd'},
                                    type: 'post',
                                    success: function(data){
                                        var ret = null;
                                        try{
                                                ret = $.parseJSON(data);
                                        }catch(Ex){
                                                $('.chat-overlay-loading-fix').hide();
                                                return ;
                                        }

                                        if(!ret){
                                                $('.chat-overlay-loading-fix').hide();
                                                TTAlert({
                                                        msg: t("Couldn't process. please try again later"),
                                                        type: 'alert',
                                                        btn1: t('ok'),
                                                        btn2: '',
                                                        btn2Callback: null
                                                });
                                                return ;
                                        }

                                        if(ret.status == 'ok'){						
                                                window.location.reload();
                                                /*obj.remove();
                                                initscrollPane_data();*/
                                        }else{
                                                TTAlert({
                                                        msg: ret.msg,
                                                        type: 'alert',
                                                        btn1: t('ok'),
                                                        btn2: '',
                                                        btn2Callback: null
                                                });
                                        }
                                        $('.chat-overlay-loading-fix').hide();
                                    }
				});	
			}
		}
	});
}
function contextBlockFriend(obj){
	TTAlert({
		msg: sprintf( t('are you sure you want to block %s ? this action will disable you to communicate with each other on Tourist Tube<br/>You can unblock %s again from your blocked list.') ,[obj.attr('data-name'),obj.attr('data-name')] ) ,
		type: 'action',
		btn1: t('cancel'),
		btn2: t('confirm'),
		btn2Callback: function(data){
			if(data){
				$('.chat-overlay-loading-fix').show();
				$.ajax({
					url: ReturnLink('/ajax/profile_friend.php'),
					data: {fid : obj.attr('data-id'), op : 'block_frnd'},
					type: 'post',
					success: function(data){
						var ret = null;
						try{
							ret = $.parseJSON(data);
						}catch(Ex){
                                                    $('.chat-overlay-loading-fix').hide();
							return ;
						}
						
						if(!ret){
                                                    $('.chat-overlay-loading-fix').hide();
							TTAlert({
								msg: t("Couldn't process. please try again later"),
								type: 'alert',
								btn1: t('ok'),
								btn2: '',
								btn2Callback: null
							});
							return ;
						}
			
						if(ret.status == 'ok'){
							window.location.reload();					
						}else{
							TTAlert({
								msg: ret.msg,
								type: 'alert',
								btn1: t('ok'),
								btn2: '',
								btn2Callback: null
							});
						}
                                                $('.chat-overlay-loading-fix').hide();
					}
				});
			}
		}
	});
}
function contextAcceptIgnored(id,alpobj){
	$('.chat-overlay-loading-fix').show();
	$.ajax({
		url: ReturnLink('/ajax/profile_friend.php'),
		data: {fid : id, op : 'accept_frnd'},
		type: 'post',
		success: function(data){
			var ret = null;
			try{
				ret = $.parseJSON(data);
			}catch(Ex){
                        $('.chat-overlay-loading-fix').hide();
				return ;
			}
			
			if(!ret){
                        $('.chat-overlay-loading-fix').hide();
				TTAlert({
					msg: t("Couldn't process. please try again later"),
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
				return ;
			}

			if(ret.status == 'ok'){
				alpobj.removeClass('inactive');
				alpobj.addClass('selectable');
				window.location.reload();					
			}else{
				TTAlert({
					msg: ret.msg,
					type: 'alert',
					btn1: t('ok'),
					btn2: '',
					btn2Callback: null
				});
			}
                        $('.chat-overlay-loading-fix').hide();				
		}
	});
}
function contextShowHideNotificationsFriend(id,val){
	$('.upload-overlay-loading-fix-file').show();
	$.post(ReturnLink('/ajax/ajax_showhidenotificationsprofilefriend.php'), {fid:id,typ:val},function(data){
		$('.upload-overlay-loading-fix-file').hide();
	});
}
function contextRejectFriend(id){
	$('.upload-overlay-loading-fix-file').show();
	$.post(ReturnLink('/ajax/ajax_rejectprofilefriend.php'), {fid:id},function(data){
		window.location.reload();	
	});
}
function addValue1(obj){
	if($(obj).attr('value') == '') $(obj).attr('value',$(obj).attr('data-val'));
} 
function removeValue1(obj) {
	if($(obj).attr('value') == $(obj).attr('data-val')) $(obj).attr('value','');
}
function checkSubmitfriend(e){
   if(e && e.keyCode == 13){
	  $('#searchfriendbut').click();
   }
}
function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	
	return true;
}

function reloadfriendpicrelated(){
    if($("#tubefriendbuttonbut4").hasClass('active')){
            window.location.reload();
    }else{
        $('.upload-overlay-loading-fix-file').hide();	
    }
}
function reloadfriendignoredrelated(){
	if($("#tubefriendbuttonbut2").hasClass('active')){
		window.location.reload();
	}else{
		$('.upload-overlay-loading-fix-file').hide();	
	}
}
function reloadfriendremovedrelated(){
	if($("#tubefriendbuttonbut5").hasClass('active')){
		window.location.reload();
	}else{
		$('.upload-overlay-loading-fix-file').hide();	
	}
}
function refreshfriendbodyheight(){
	var nmroz=Math.floor($('#friendtuberid .one-tuber').length/4);
	if(($('#friendtuberid .one-tuber').length%4)!=0){
		nmroz++;
	}
	$('#friendtuberid').css('height',(nmroz*141)+"px");
	$('#container').css('height',"auto");	
}
function RebuildLinkNetwork(){
    var link = ReturnLink('/parts/profile_friends.php?userId='+userIdOwner+'&ss='+globtxtsrch+'&page='+ProfilePage+'&ssalpha='+globcaractersearch+'&friend_section='+friend_section+'&no_cache='+Math.random() );
    $('#page_nav a').attr('href', link );
}
function displayNetworkDataRelates(){
    $.ajax({
        url: $('#page_nav a').attr('href'),
        type: 'post',
        success: function(data){
            var $newEls = $( data );
            $container.append( $newEls );            
            InitDocumentNetwork();  
            $('.upload-overlay-loading-fix-file').hide();
        }
   });
}