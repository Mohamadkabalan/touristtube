<?php
    $path = "";

    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
	
    include_once ( $path . "inc/functions/users.php" );
	
	if(!userIsLogged()){
		printf('<script type="text/javascript">window.location = "";</script>');
		ob_flush();
		header("location:".ReturnLink('notfound'));
	}
	
	$user_ID = userGetID();
	$userInfo= getUserInfo($user_ID);
	$userName= returnUserDisplayName( $userInfo , array('max_length' => 12) );
	$freinds = userGetChatList($user_ID);
	include_once( $path . 'services/lib/chat.inc.php' );
	
	$status = array(CHAT_STATUS_AWAY => 'yellow' , 
					CHAT_STATUS_BUSY => 'red' , 
					CHAT_STATUS_ONLINE => 'green' , 
					CHAT_STATUS_APPEAROFFLINE => 'grey' , 
					CHAT_STATUS_OFFLINE => 'grey'
					);
	
	
	$status_string = array( CHAT_STATUS_AWAY => _('Away'), 
							CHAT_STATUS_BUSY => _('Busy')  , 
							CHAT_STATUS_ONLINE => _('Available') , 
							CHAT_STATUS_OFFLINE => _('Offline'), 
							CHAT_STATUS_APPEAROFFLINE => _('Offline') 
							);
?>

<script type="text/javascript">
	var gmenu;
	$(document).ready(function(){
		//refreshChatWindow();
		$("#chatList #chat-container").css("height",(myheightList - 120)+"px");
		$("#chatList #chat-container-inside").css("height",(myheightList - 120)+"px");
	
		var myselectedarray=Array(false,false,false,false);
		if( $.cookie('chatStatus')== <?php echo CHAT_STATUS_ONLINE ?>){
			myselectedarray[0]=true;
		}
		if( $.cookie('chatStatus')== <?php echo CHAT_STATUS_AWAY ?>){
			myselectedarray[1]=true;
		}
		if( $.cookie('chatStatus')== <?php echo CHAT_STATUS_BUSY ?>){
			myselectedarray[2]=true;
		}
		if( $.cookie('chatStatus')== <?php echo CHAT_STATUS_APPEAROFFLINE ?>){
			myselectedarray[3]=true;
		}
		var ddData = [
			{
				text: "&nbsp;<?php echo _('Available') ?>",
				value: <?php echo CHAT_STATUS_ONLINE ?>,
				selected: myselectedarray[0],			
				imageSrc: "<?php GetLink('media/images/chat-online.png');?>"
			},
			{
				text: "&nbsp;<?php echo _('Away') ?>",
				value: <?php echo CHAT_STATUS_AWAY ?>,
				selected: myselectedarray[1],
				imageSrc: "<?php GetLink('media/images/chat-away.png');?>"
			},
			{
				text: "&nbsp;<?php echo _('Busy') ?>",
				value: <?php echo CHAT_STATUS_BUSY ?>,
				selected: myselectedarray[2],
				imageSrc: "<?php GetLink('media/images/chat-busy.png');?>"
			},
			{
				text: "&nbsp;<?php echo _('invisible') ?>",
				value: <?php echo CHAT_STATUS_APPEAROFFLINE ?>,
				selected: myselectedarray[3],
				imageSrc: "<?php GetLink('media/images/chat-offline.png');?>"
			}
		];
		
		$('#demoBasic').ddslick({
			data: ddData,
			width: 88,
			imagePosition: "left",
			selectText: "",
			onSelected: function (data) {
				//console.log(data);
			}
		});
		
		$(".one-chat-name").click(function () {
			var curuobjs=$(this).parent();
			if( curuobjs.attr('data-blocked') == 0 ){
				var uid = curuobjs.attr('data-tuber');
				AddTuberChat(uid);
			}
		});
		$(window).resize(function(){
			refreshChatWindow();
		});
	});
	function blockuserdata(id){
			var curobj=$("#ChatListItem"+id);
			
			curobj.find(".one-chat-overlay-grey").show();
			
			curobj.attr('data-blocked',1);
			contextUnBlock(curobj);	
	}
	function unblockuserdata(id){
			var curobj=$("#ChatListItem"+id);
			
			curobj.find(".one-chat-overlay-grey").hide();
			
			curobj.attr('data-blocked',1);
			contextBlock(curobj);
				
	}
	
	function setvalcmbselected(val){
		$.cookie('chatStatus', val, { expires: 365 ,path: '/'});
		ChatStatusChange(val);
	}
	function contextUnBlock(curuobj){
		$('table.conmn-menu'+curuobj.attr('data-tuber')).remove();
		var menu1 = [{'VIEW PROFILE':function () { 
					
					var currentUsername = $(this).find(".one-chat-name span").html();
					var usreName = $(this).find(".one-chat-name").attr('data-uname');
					document.location = ReturnLink("/profile/"+usreName);
			
			} } , {'allow chat':function(menuItem,menu) {
			
						$('.chat-overlay-loading-fix').show();
						gmenu = menu;
						$.ajax({
						url: ReturnLink('/ajax/chat_user_op.php'),
						data: {fid : curuobj.attr('data-tuber'), op : 'unblock_frnd'},
						type: 'post',
						success: function(data){
							var ret = null;
							try{
								ret = $.parseJSON(data);
							}catch(Ex){
								return ;
							}
							
							if(!ret){
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
							
							 	curuobj.find(".one-chat-overlay-grey").hide();
												
								curuobj.attr('data-blocked',0);
								gmenu.menu.remove();
								contextBlock(curuobj);
								//friendsection is defined in parts/chat_user_ops.php. we only want to call this function for that page
								
								/*if(typeof friendsection != 'undefined'){
									unblockuserdatafriend(curuobj.attr('data-tuber'));
								}*/
								
							}else{
								TTAlert({
									msg: ret.msg,
									type: 'alert',
									btn1: 'ok',
									btn2: '',
									btn2Callback: null
								});
							}
							$('.chat-overlay-loading-fix').hide();
						}
						})			
		} },{'REMOVE':function () { 
					
				removeFromChatList(curuobj.attr('data-tuber'),0);	
			
		} } ]; 
		curuobj.contextMenu(menu1,{theme:'vista',curid:curuobj.attr('data-tuber')});	
	}
	function contextBlock(curobj){
		
		var tuberName = curobj.attr('data-tuber'); 
	
		$('table.conmn-menu'+curobj.attr('data-tuber')).remove();
		var menu1 = [{'VIEW PROFILE':function () { 
					
					var currentUsername = $(this).find(".one-chat-name span").html();
					var usreName = $(this).find(".one-chat-name").attr('data-uname');
					document.location = ReturnLink("/profile/"+usreName);
			
			} } , {'ADD TO FAVORITES':function () { 
					
				addToFavorites(curobj.attr('data-tuber'));
				
			} } ,   {'STOP CHAT ':function(menuItem,menu) {
			
				$('.chat-overlay-loading-fix').show();
				gmenu = menu;	
				$.ajax({
				url: ReturnLink('/ajax/chat_user_op.php'),
				data: {fid : curobj.attr('data-tuber'), op : 'block_frnd'},
				type: 'post',
				success: function(data){
					var ret = null;
					try{
						ret = $.parseJSON(data);
					}catch(Ex){
						return ;
					}
					
					if(!ret){
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
						
						curobj.find(".one-chat-overlay-grey").show();
												
						
						curobj.attr('data-blocked',1);
						gmenu.menu.remove();
						contextUnBlock(curobj);
						
						//friendsection is defined in parts/chat_user_ops.php. we only want to call this function for that page
						/*if(typeof friendsection != 'undefined'){
							blockuserdatafriend(curobj.attr('data-tuber'));	
						}*/
					}else{
						TTAlert({
							msg: ret.msg,
							type: 'alert',
							btn1: 'ok',
							btn2: '',
							btn2Callback: null
						});
					}
					$('.chat-overlay-loading-fix').hide();
				}
			})					
		} },{'REMOVE':function () { 
					
				removeFromChatList(curobj.attr('data-tuber'),0);	
			
		} } ];
		curobj.contextMenu(menu1,{theme:'vista',curid:curobj.attr('data-tuber')});
	}
</script>
<div id="chatSetButton"></div>
<div id="chatSettingsList">
	<div id="chatSettingsPointer"></div>
    <div class="onelistChatSettings" id="turnOffChat"><?php echo _('Switch off chat');?></div>
</div>
<div class="chat-overlay-loading-fix"><div></div></div>
<div class="one-chat-myimg"><img src="<?php GetLink('media/tubers/' . userCroppedPhoto($userInfo['profile_Pic']) )?>" alt="<?php echo $userName; ?>"></div>
<div class="one-chat-username"><?php echo $userName; ?></div>
<div id="my_bottom_border"></div>
<div class="one-chat-checkbox">
    <div id="demoBasic" class="dd-container" style="width: 115px;"></div>
</div>
<div id="onlineFav">
	<div class="oneTabFav selectedFav" id="ALL"><?php echo _('ALL');?></div>
	<div class="oneTabFav" id="ONLINE"><?php echo _('ONLINE');?></div>
	<div class="oneTabFav" id="FAVORITES"><?php echo _('FAVORITES');?></div>        
</div>
<br/><br/>
<!--<div id="chat-type"></div>-->
<div id="chat-container">
	<div id="chat-container-inside" class="scroll-panenew">
		<?php foreach($freinds as $freind): 
		$status_id = chatGetUserStatus($freind['id']);
		$favoritesClass = "";
		
		$isFavorite = userFavoriteUserAdded($user_ID,$freind['id']);
		
		if($isFavorite) $isFavorite = "1";
		else $isFavorite = "0";
		
		?>
        <div class="one-chat-rec" data-tuber="<?php echo $freind['id'] ?>" id="ChatListItem<?php echo $freind['id'] ?>" data-blocked="<?php echo $freind['blocked']; ?>" data-chat-status = "<?php echo strtolower($status_string[$status_id]); ?>" data-chat-favorites = "<?php echo $isFavorite; ?>">
        	<div class="one-chat-overlay-grey"></div>
            <div class="one-chat-img <?php echo strtolower($status_string[$status_id]); ?>"><img border="0" src="<?php GetLink('media/tubers/' . userCroppedPhoto($freind['profile_Pic']) )?>" alt="<?php echo returnUserDisplayName($freind,array('max_length'=>18)); ?>"></div>
            <div class="one-chat-name" data-uname="<?php echo $freind['YourUserName'] ?>"><span><?php echo returnUserDisplayName($freind,array('max_length'=>18)); ?></span> <?php /*?><p class="<?php  echo $status[$status_id]; ?>"><?php echo $status_string[$status_id]; ?></p><?php */?></div>
<!--            <div class="one-chat-linetop"></div>-->
           <!-- <div class="one-chat-linebottom"></div>-->
             <div class="one-chat-remove-fav" title="remove"></div>
            <div class="one-chat-block"></div>
            <div class="one-chat-video"></div>
            <!--<div class="one-chat-unblock"></div>-->
           
            <div class="one-chat-msg"><span></span></div>
           <?php /*?> <div class="one-chat-status-<?php echo strtolower($status_string[$status_id]); ?>"></div><?php */?>
            <script type="text/javascript">				
			  $(function() { 
			  	var myobjname=$("#ChatListItem<?php echo $freind['id'] ?>");
			
				<?php if($freind['blocked']==1){?>
					contextUnBlock(myobjname);
			  	<?php } else{?>
					contextBlock(myobjname);
				<?php } ?>
			  });
			</script>
            <?php if($freind['blocked']==1){?>
            	<script>
					var myobj=$("#ChatListItem<?php echo $freind['id'] ?>");
                	/*myobj.css("background","#ec7676");
					myobj.find('.one-chat-video').hide();
					myobj.find('.one-chat-block').show();
					myobj.find('.one-chat-unblock').hide();*/
					
					myobj.find('.one-chat-overlay-grey').show();
                </script>
            <?php } ?>
        </div>
        <?php endforeach; ?>
       
    </div>
</div>
<script type="text/javascript">
	
	$(document).ready(function(){
		initChatScrollPane();
	});
</script>