<?php
$path = "";

$bootOptions = array("loadDb" => 0, 'loadLocation' => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/webcams.php" );

$includes = array('js/jquery.selectBox.js','css/jquery.selectBox.css');

tt_global_set('includes', $includes);

include("TopIndex.php");
$uricurserver = currentServerURL();
?>
	
	<div style="width:920px; margin-left:auto; margin-right: auto;">
		
		<div style="width:710px; float:left;">
		
			<div class="LiveTitle">	360 videos	</div>
		
			
			<div class="LiveBigPicture">

			<iframe src="<?php echo $uricurserver.'/360/PARAVISION.html';?>" width="580" height="380" >360/PARAVISION.html</iframe>
			</div>
			
			<div style="clear:both"></div>

   
		</div>
	
		
		
	</div>
	
	<div style="clear:both; height: 100px;"></div>

<?php include("BottomIndex.php"); ?>