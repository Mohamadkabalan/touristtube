<?php
$path    = "../";

$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" ); 

$userId = userGetID();

include_once ( $path . "inc/functions/videos.php" );

$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$videoid = $_REQUEST['videoid'];
//$fileid = $_REQUEST['fileid'];
$videoid = $submit_post_get['videoid'];
$fileid = $submit_post_get['fileid'];

$videoInfo = getVideoInfo ( $videoid );

//make sure the requester/uploader is the logged in user
if( intval($videoInfo['userid']) != intval($userId) ){
	return ;
}

$videoarray = getVideoThumbnail( $videoid, '../' . $videoInfo['fullpath'], 1 );
$thumbURL=$request->request->get('thumburl', '');
//if(isset($_POST['thumburl'])){
if($thumbURL){
	
	for($i=0; $i< count( $videoarray ); $i++){
		
		$thumbname = str_replace( $videoInfo['fullpath'], '', $videoarray[$i] );
		
//		if($thumbname != $_POST['thumburl']){
		if($thumbname != $thumbURL){
			
			unlink($videoarray[$i]);
			$large = $path . $videoInfo['fullpath'] . 'large_' . str_replace("../","",$thumbname);
			$small = $path . $videoInfo['fullpath'] . 'small_' . str_replace("../","",$thumbname);
			$xsmall = $path . $videoInfo['fullpath'] . 'xsmall_' . str_replace("../","",$thumbname);
			$thumb = $path . $videoInfo['fullpath'] . 'thumb_' . str_replace("../","",$thumbname);
			TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, 'unlink ' . $large);
			TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, 'unlink ' . $small);
			TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, 'unlink ' . $xsmall);
			TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, 'unlink ' . $thumb);
			@unlink($large);
			@unlink($small);
			@unlink($xsmall);
			@unlink($thumb);
			
			echo '<script type="text/javascript" language="javascript">
					
					parent.selectThumb(\''.$fileid.'\', \''.$videoInfo['fullpath'].'/'.$thumbURL.'\');
					
				</script>';
			
		}
		//parent.window.selectThumb(\''.$fileid.'\', \''.$videoInfo['fullpath'].'/'.$_POST['thumburl'].'\');
	}
	
	//$uploadPath = $videoInfo['fullpath'];
	
	//convertVideo ( $CONFIG [ 'video' ] [ 'videoCoverter' ], $uploadPath . $videoInfo['name'], $uploadPath , $videoInfo['code'] );
	
}


?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Show Thumbnail</title>
<script type="text/javascript" src="/assets/vendor/jquery/dist/jquery-1.9.1.min.js"></script>
<link href="<?php GetLink("css/upload.css") ?>" rel="stylesheet" type="text/css">

<script type="text/javascript">
	
	function ThumbSelected(HereImage){
		$('#thumburl').val(HereImage);
	}
	
	//document.domain = "www.touristtube.com";
	
	$(document).ready(function(){
		
		$('.videothumbs li').click(function(){
			$('.videothumbs li').removeClass('selected');
			$(this).addClass('selected');
		});		
		$('#videothumbscontainerbut').click(function(){
			$('#thumbselector').submit();
			$('.fancybox-close').click();
		});	
		$('.videothumbs li:first').click();
	});

</script>

</head>

<body style="margin:0; padding:0; overflow:hidden !important;">
	<form id="thumbselector" name="thumbselector" method="post">
    <!--input type="submit" name="savethumb" id="savethumb" value="OK"-->
    

        <div id="videothumbscontainer">
            <div id="videothumbscontainertitle"><?php print _("choose one of the thumbnails to make as video icon");?></div>
            <div id="videothumbscontainerimg">
                <ul class="videothumbs">
                <?php 
                    
                    for($i=0; $i< count( $videoarray ); $i++){
                        
                        $Selected = '';
                        $Value = '';
                        $thumbname = str_replace( $videoInfo['fullpath'], '', $videoarray[$i] );
                        
                        if( $i == 0 ){
                            $Selected = ' class="selected"';
                            $Value = $thumbname;
                        }
                        
                        echo '<li onClick="ThumbSelected(\''.$thumbname.'\');"'.$Selected.'><img src="'.$videoarray[$i].'" width="86" height="48"></li>';
                        
                    }
                
                ?>
                </ul>
            </div>
            <div id="videothumbscontainerbut" name="savethumb" value="ok"><?php print _("ok");?></div>            
        </div>
        <input type="hidden" name="thumburl" id="thumburl" value="<?php echo $Value; ?>">
    </form>
<?php include($path."closing-footer.php");?>