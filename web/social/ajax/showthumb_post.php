<?php
$path    = "../";

$bootOptions = array ( "loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" ); 


include_once ( $path . "inc/functions/videos.php" );

$submit_post_get = array_merge($request->query->all(),$request->request->all());
//$path_video = $_REQUEST['path'];
//$code = $_REQUEST['code'];
//$name = $_REQUEST['name'];
//$type = $_REQUEST['type'];
$path_video = $submit_post_get['path'];
$code = $submit_post_get['code'];
$name = $submit_post_get['name'];
$type = $submit_post_get['type'];


$videoarray = getVideoThumbnail_Posts("postThumb".$code,'../' . $path_video, 1 );
$thumbURL = $request->request->get('thumburl', '');;
//if(isset($_POST['thumburl'])){
if($thumbURL){
	
	for($i=0; $i< count( $videoarray ); $i++){
		
		$thumbname = str_replace( $path_video, '', $videoarray[$i] );
		
//		if($thumbname != $_POST['thumburl']){
		if($thumbname != $thumbURL){
			
			unlink($videoarray[$i]);
			$large = $path . $path_video . 'large_' . str_replace("../","",$thumbname);
			$small = $path . $path_video . 'small_' . str_replace("../","",$thumbname);
			TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, 'unlink ' . $large);
			TTDebug(DEBUG_TYPE_MEDIA, DEBUG_LVL_INFO, 'unlink ' . $small);
			@unlink($large);
			@unlink($small);
		}
		
	}	
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
	var curname='<?php echo $name;?>';
	var filetype='<?php echo $type;?>';
	var pathto='<?php echo $path_video;?>';
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
                        var res = $('.videothumbs li.selected img').attr('src');
                        var newstrimage='<img src="'+res+ '?x=' + Math.random() + '" width="86" height="48"/>';
			parent.window.updateImagePOST(newstrimage,curname,pathto,filetype,1);
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
                        $thumbname = str_replace( $path_video, '', $videoarray[$i] );
                        
                        if( $i == 0 ){
                            $Selected = ' class="selected"';
                            $Value = $thumbname;
                        }
                        
                        echo '<li onClick="ThumbSelected(\''.$thumbname.'\');"'.$Selected.'><img src="'.$videoarray[$i].'" width="86" height="48"></li>';
                        
                    }
                
                ?>
                </ul>
            </div>
            <div id="videothumbscontainerbut" name="savethumb" value="post"><?php print _("post");?></div>            
        </div>
        <input type="hidden" name="thumburl" id="thumburl" value="<?php echo $Value; ?>">
    </form>
<?php include($path."closing-footer.php");?>