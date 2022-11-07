<?php
$path = "";
$tpopular = 5;
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );

require_once $path . 'vendor/autoload.php';
include_once ( $path . "inc/twigFct.php" );

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem($path. 'twig_templates/');
$twig = new Twig_Environment($loader, array(
    'debug' => false,
));
$twig->addExtension(new Twig_Extension_twigTT());
$template = $twig->loadTemplate('review_new.twig');

tt_global_set('includes', array('css/review.css'));

if (userIsLogged() && userIsChannel()) {
    include($theLink . "twig_parts/_headChannel.php");
} else {
    include($theLink . "twig_parts/_head.php");
}

	global $dbConn;
$query = "select * from airport";
$select = $dbConn->prepare($query);
$res    = $select->execute();
$row = $select->fetchAll();
//$ret = db_query($query);
$airport_list = array();
//while($row = db_fetch_array($ret)){
foreach($row as $row_item){
    $airport_list1 = $row_item["title"];
    $airport_list2 = $row_item["country_name"];
	array_push($airport_list,"$airport_list1,$airport_list2");
	
}

//print_r($airport_list);






$data['userIsLogged'] = userIsLogged();
$data['userIsChannel'] = userIsChannel();
$data['SOCIAL_ENTITY_HOTEL'] = SOCIAL_ENTITY_HOTEL;
$data['SOCIAL_ENTITY_RESTAURANT'] = SOCIAL_ENTITY_RESTAURANT;
$data['SOCIAL_ENTITY_LANDMARK'] = SOCIAL_ENTITY_LANDMARK;
$data['SOCIAL_ENTITY_AIRPORT'] = SOCIAL_ENTITY_AIRPORT;
$data['airport_list']=$airport_list;

include($theLink . "twig_parts/_foot.php");
echo $template->render($data);
?>
<script type="text/javascript">
var data  = [<?php echo '"'.implode('","', $airport_list).'"' ?>];


$(document).ready(
  function () {
    $( "#nameOfPlace" ).autocomplete({
      source: data,
      autoFocus: true ,

    });
  }

);
</script>