<?php
try{
//if (!isset($expath)) {
//    $expath = "";
//}
//$path = "../";

ob_start();

$mediaSizes = array(

    'xxx'   => array(   '1920', '1080'),
    'xx'    => array(   '1080', '607'),
    'x'     => array(   '720',  '404'),
    'h'     => array(   '560',  '315'),
    'm'     => array(   '370',  '208'),
    's'     => array(   '290',  '163'),
    'xs'    => array(   '192',  '108'),
    's_xx'  => array(   '270',  '270'),
    's_x'   => array(   '180',  '180'),
    's_h'   => array(   '140',  '140'),
    's_m'   => array(   '92',   '92')

);

//define('ENVIRONMENT', getenv('ENVIRONMENT'));
//require_once($path . "inc/config.php");

//require_once($path . "inc/misc.php");
//require_once($path . "inc/functions/db_mysql.php");
//require_once($path . "inc/functions/videos.php");

$expath    = '';
require_once("heart.php");

$root_path = $CONFIG['server']['root'];
$theSize = '';
//$theSize = xss_sanitize($_GET['size']);
//$reflink = urldecode($_GET['l']);
$theSize = $request->query->get('size','');
$reflink = urldecode($request->query->get('l',''));

//$t_width = intval($_GET['w']);
$t_width = intval($request->query->get('w',''));
//echo '||';
//$t_height = intval($_GET['h']);
$t_height = intval($request->query->get('h',''));
if($theSize != '' && in_array($theSize, array_keys($mediaSizes))){
    $t_width = $mediaSizes[$theSize][0];
    $t_height = $mediaSizes[$theSize][1];
}
//$keep_ratio = isset($_GET['keep_ratio']) && $_GET['keep_ratio'] != '' ? ($_GET['keep_ratio'] == 1) : false;
$keep_ratio = $request->query->get('keep_ratio',TRUE);

$thumbcache = "cache/thumbs/";
$md5_reflink = md5($reflink);
$filename = $md5_reflink . "_" . $t_width . "_" . $t_height . ".jpg";

//if the cache doesn't exist or cachebuster is enabled
$nocache_get = $request->query->get('nocache','');
//if (!file_exists($path . $thumbcache . $filename ) || isset($_GET['nocache'])) {
if (!file_exists($root_path . $thumbcache . $filename ) || $nocache_get) {

//    TTDebug( DEBUG_TYPE_MEDIA , DEBUG_LVL_INFO, "$path . $thumbcache . $filename didnt exist");

    $quality = 70;
    if( $keep_ratio ) $quality = 100;

    $options = array(
        'in_path' => $CONFIG['server']['root'] . $reflink,
        'out_path' => $CONFIG['server']['root'] . $thumbcache . $filename,
        'w' => $t_width,
        'h' => $t_height,
        'keep_ratio' => $keep_ratio,
        'quality' => $quality
    );
    if ( mediaSubsample($options) ) {
        echo $t_width;
    } else {
        echo $t_width;
        echo "ERROR";
        exit;
    }
}
ob_clean();
header("Content-Type: image/jpeg");
readfile($root_path . $thumbcache . $filename);
}
catch(Exception $ex){
    echo $ex->getMessage();
}