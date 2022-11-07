<?php

if( !isset($bootOptions) ){
	$path    = "../";

	$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1 );
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" ); 

	include_once ( $path . "inc/functions/videos.php" );
	include_once ( $path . "inc/functions/webcams.php" );
	include_once ( $path . "inc/functions/users.php" );	
}


if( !userIsLogged() ){
	ob_clean();
	header('Location: ' . ReturnLink('register') );
	exit;
}

$userId = userGetID();

$userscount=0;
$userscounthide='false';

$limit = 10;
//$page = isset($_GET['page']) ? intval($_GET['page']) - 1 : 0;
$page = $request->query->get('page','') ? intval($request->query->get('page','')) - 1 : 0;

$options = array ( 'published' => 1, 'limit' => $limit, 'page' => $page , 'type' => 'a' , 'userid' => $userId , 'favorite' => true, 'order' => 'd' );

$userVideos = mediaSearch( $options );

$options = array ( 'limit' => $limit, 'page' => $page , 'userid' => $userId , 'favorite' => true ,'order' => 'd' );
$userWebcams = webcamSearch( $options );

$pcount = count ( $userVideos );
	
	if(($pcount%$limit)!=0){
		$pagcount++;
	}
	
	if($pagcount==($page+1)){
		$userscounthide='true';
	}
	
	echo '<div class="userscounthide" data-value="'.$userscounthide.'"></div>';	

for ( $i=0; $i< count ( $userVideos ) ; $i++ ):
	$url = ( strstr($userVideos[$i]['type'],'image') == null ) ? ReturnVideoUriHashed($userVideos[$i]) : ReturnPhotoUri($userVideos[$i]);
	$thumb = ( strstr($userVideos[$i]['type'],'image') == null ) ? videoReturnThumbSrc($userVideos[$i]) : photoReturnThumbSrc($userVideos[$i]);
        $description_db = htmlEntityDecode($userVideos[$i]['description']);
?>
<div class="element">
      
	<div class="imgGrid" id="video_<?php echo $userVideos[$i]['id']; ?>">
		<a href="<?php echo $url ?>" title="<?php echo htmlEntityDecode($userVideos[$i]['title']); ?>"><img src="<?php echo $thumb; ?>" alt="<?php echo htmlEntityDecode($userVideos[$i]['title']); ?>" style="width: <?php echo MEDIA_PROFILE_WIDTH ?>px;height:<?php echo MEDIA_PROFILE_HEIGHT ?>px"/></a>
		<div class="hiddenimgGrid">
			<div class="insidehiddenimg">
				<div class="mediainfottl sorttilte"><?php echo htmlEntityDecode($userVideos[$i]['title'])/*.' '.$userVideos[$i]['id']*/; ?></div>
				<div class="mediainfodate goldText sortdate"><?php echo date ( 'd-m-Y',  strtotime( $userVideos[$i]['pdate'] )); ?></div>
				<div class="mediainfodesc sortcomments" style="height:95px;"><?php echo $description_db; ?></div>
				<div class="mediainfomorePopup">
					<ul>
						<li class="mediainfomorePopupItem mediainfomorePopupItem1"><span class="mediaInfoYellow sortlikes"><?php echo $userVideos[$i]['like_value']; ?></span><br><?php echo _('Likes') ?></li>
						<li class="mediainfomorePopupItem mediainfomorePopupItem2"><span class="mediaInfoYellow sortcomments"><?php echo $userVideos[$i]['nb_comments']; ?></span><br><?php echo _('Comments') ?></li>
						<li class="mediainfomorePopupItem mediainfomorePopupItem3"><span class="mediaInfoYellow sortviews"><?php echo $userVideos[$i]['nb_views']; ?></span><br><?php echo _('Views') ?></li>
						<li class="mediainfomorePopupItem mediainfomorePopupItem4">
							<div class="listVideoRating insidevideolistrating<?php echo (int)$userVideos[$i]['rating'] ?>3"></div>
							<?php echo _('Rating') ?> <span class="mediaInfoYellow">(<span class="sortrating"><?php echo $userVideos[$i]['nb_ratings']; ?></span>)</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="mediaInfo" id="cvideo_<?php echo $userVideos[$i]['id']; ?>">
		<div class="mediainfottl sorttilte"><?php $val_db = htmlEntityDecode($userVideos[$i]['title']); echo cut_sentence_length($val_db,50); ?></div>
		<div class="mediainfodate goldText sortdate"><?php echo date ( 'd-m-Y',  strtotime( $userVideos[$i]['pdate'] )); ?></div>
		<div class="mediainfodesc sortcomments"><?php echo $description_db; ?></div>
		<div class="mediainfomore">
			<ul>
				<li><span class="mediaInfoYellow sortlikes"><?php echo $userVideos[$i]['like_value']; ?></span><br><?php echo _('Likes') ?></li>
				<li><span class="mediaInfoYellow sortcomments"><?php echo $userVideos[$i]['nb_comments']; ?></span><br><?php echo _('Comments') ?></li>
				<li style="background:none; padding:0; width:70px;"><span class="mediaInfoYellow sortviews"><?php echo $userVideos[$i]['nb_views']; ?></span><br><?php echo _('Plays') ?></li>
				<li>
					<div class="listVideoRating insidevideolistrating<?php echo (int)$userVideos[$i]['rating'] ?>3"></div><br/>
					<?php echo _('Rating') ?> <span class="mediaInfoYellow">(<span class="sortrating"><?php echo $userVideos[$i]['nb_ratings']; ?></span>)</span>
				</li>
			</ul>
		</div>
	</div>
	<div class="ProfileUserButton DeleteFavAction" rel="<?php echo $userVideos[$i]['id']; ?>" >
		<div class="ProfileUserDeleteButtonMid">
			<div class="ProfileUserDeleteButtonText"><?php echo _('remove'); ?></div>
			<div class="ProfileUserDeleteButtonImage"></div>
		</div>
	</div>

</div>
<?php endfor ?>

<?php
for ( $i=0; $i< count ( $userWebcams ) ; $i++ ):
	$url =ReturnWebcamUri($userWebcams[$i]);
	$thumb = ReturnLink($CONFIG['cam']['uploadPath']. $userWebcams[$i]['url'] . '.jpg');
	$title = htmlEntityDecode($userWebcams[$i]['name']);
	$likes = $userWebcams[$i]['like_value'];
	$views = $userWebcams[$i]['n_views'];
	$rating =  ceil($userWebcams[$i]['rating_value']);
	$nb_rating = $userWebcams[$i]['n_rating'];
	$nb_comments = $userWebcams[$i]['n_comments'];
	$date = date('d-m-Y');
	$desc = $userWebcams[$i]['description'];
        $desc = htmlEntityDecode($desc);
	$id = $userWebcams[$i]['id'];
?>
<div class="element">
      
	<div class="imgGrid" id="video_<?php echo $id; ?>">
		<a href="<?php echo $url ?>" title="<?php echo $title; ?>"><img src="<?php echo $thumb ?>" alt="<?php echo $title; ?>" style="width: <?php echo MEDIA_PROFILE_WIDTH ?>px;height:<?php echo MEDIA_PROFILE_HEIGHT ?>px"/></a>
		<div class="hiddenimgGrid">
			<div class="insidehiddenimg">
				<div class="mediainfottl sorttilte"><?php echo $title; ?></div>
				<div class="mediainfodate goldText sortdate"><?php echo $date; ?></div>
				<div class="mediainfodesc sortcomments" style="height:95px;"><?php echo $desc; ?></div>
				<div class="mediainfomorePopup">
					<ul>
						<li class="mediainfomorePopupItem mediainfomorePopupItem1"><span class="mediaInfoYellow sortlikes"><?php echo $likes; ?></span><br><?php echo _('Likes') ?></li>
						<li class="mediainfomorePopupItem mediainfomorePopupItem2"><span class="mediaInfoYellow sortcomments"><?php echo $nb_comments; ?></span><br><?php echo _('Comments') ?></li>
						<li class="mediainfomorePopupItem mediainfomorePopupItem3"><span class="mediaInfoYellow sortviews"><?php echo $views; ?></span><br><?php echo _('Views') ?></li>
						<li class="mediainfomorePopupItem mediainfomorePopupItem4">
							<div class="listVideoRating insidevideolistrating<?php echo $rating; ?>3"></div><br/>
							<?php echo _('Rating') ?> <span class="mediaInfoYellow">(<span class="sortrating"><?php echo $nb_rating; ?></span>)</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="mediaInfo" id="cvideo_<?php echo $id; ?>">
		<div class="mediainfottl sorttilte"><?php echo cut_sentence_length($title,50); ?></div>
		<div class="mediainfodate goldText sortdate"><?php echo $date; ?></div>
		<div class="mediainfodesc sortcomments"><?php echo $desc; ?></div>
		<div class="mediainfomore">
			<ul>
				<li><span class="mediaInfoYellow sortlikes"><?php echo $likes; ?></span><br/><?php echo _('Likes') ?></li>
				<li><span class="mediaInfoYellow sortcomments"><?php echo $nb_comments; ?></span><br><?php echo _('Comments') ?></li>
				<li style="background:none; padding:0; width:70px;"><span class="mediaInfoYellow sortviews"><?php echo $views; ?></span><br><?php echo _('Plays') ?></li>
				<li>
					<div class="listVideoRating insidevideolistrating<?php echo $rating; ?>3"></div><br/>
					<?php echo _('Rating') ?> <span class="mediaInfoYellow">(<span class="sortrating"><?php echo $nb_rating; ?></span>)</span>
				</li>
			</ul>
		</div>
	</div>
	<div class="ProfileUserButton DeleteFavCamAction" rel="<?php echo $id; ?>" >
		<div class="ProfileUserDeleteButtonMid">
			<div class="ProfileUserDeleteButtonText"><?php echo _('remove'); ?></div>
			<div class="ProfileUserDeleteButtonImage"></div>
		</div>
	</div>
</div>
<?php endfor ?>