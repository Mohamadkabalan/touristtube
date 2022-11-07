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

$template = $twig->loadTemplate('planner_result.twig'); //specify your template here


$user_id = userGetID();

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}

$fr_txt = "";
$to_txt = "";
$country_code ="";
$state_code ="";
$from_value = "dd / mm / yyyy";
$string_search = xss_sanitize(UriGetArg(0));
$string_search_value = isset($string_search) ? $string_search : '';
$count_day = $tpopular;
if ($string_search_value != '') {
    $string_search_arr =    explode('_',$string_search_value);
    $string_search_value = $string_search_arr[0];
    if ( sizeof($string_search_arr) > 1) {
        $country_code = $string_search_arr[1];
        $country_code = ( $country_code !='' ) ? $country_code : '';
        if ($country_code != '' && sizeof($string_search_arr) > 2) {
            $state_code = $string_search_arr[2];
            $state_code = ( $state_code !='' ) ? $state_code : '';
        }
    }    
    $fr_txt = xss_sanitize(UriGetArg(1));
    $fr_txt = isset($fr_txt) ? $fr_txt : '';
    $from_value = date('d / m / Y', strtotime($fr_txt));
    if ($fr_txt != '') {
        $to_txt = xss_sanitize(UriGetArg(2));
        $to_txt = isset($to_txt) ? $to_txt : '';

        $from_date = date('d/m/Y', strtotime($fr_txt));
        $to_date = date('d/m/Y', strtotime($to_txt));
        $count_day = ( $to_date - $from_date ) + 1;
    }
} else {
    $string_search_value = 'Where to go?';
}
if ($count_day > 14)
    $count_day = 14;

global $dbConn;
$query = "select cms_allcategories.title,cms_bag.user_id,cms_bag.state_code,cms_bag.city_id,cms_bagitem.item_id from cms_bag join cms_bagitem on cms_bag.id=cms_bagitem.bag_id join cms_allcategories on cms_bagitem.type= cms_allcategories.id and cms_bag.user_id=498";
//$ret = db_query($query);
$select = $dbConn->prepare($query);
$res    = $select->execute();
$bag_item = array();
$bag_item = $select->fetchAll();
//while($row = db_fetch_array($ret)){
//    $bag_item[] = $row_item;
//}



$includes = array('css/jslider.css', "js/jscal2.js", "js/jscal2.en.js", 'css/jslider.tube.css', 'js/jshashtable-2.1_src.js', 'js/jquery.numberformatter-1.2.3.js', 'js/tmpl.js', 'js/jquery.dependClass-0.1.js', 'js/draggable-0.1.js', 'js/jquery.slider_new.js', 'js/planner_new.js', 'css/plannerCal_new.css', 'css/planner_new.css', 'css/simple-slider-tt.css', 'js/simple-slider.js', 'css/plannerMap.css');
tt_global_set('includes', $includes);

include("TopIndex.php");

$options = array(
    'type' => array(1),
    'userid' => $user_id,
    'n_results' => true
);

$friend_array_count = userFriendSearch($options);

$options = array(
    'userid' => $user_id,
    'unique' => 0,
    'n_results' => true
);
$friends_of_friends_array_count = userFriendsOfFriendsSearch($options);

$options = array(
    'userid' => $user_id,
    'n_results' => true
);

$continents = array();
$continents[0]['name'] = 'Asia';
$continents[0]['countries'] = array(
    'Bangkok',
    'Beijing',
    'Cambodia',
    'China',
    'Hong Kong',
    'India',
    'Japan',
    'Macau',
    'Malaysia');
$continents[1]['name'] = 'Asia';
$continents[1]['countries'] = array(
    'Bangkok',
    'Beijing',
    'Cambodia',
    'China',
    'Hong Kong',
    'India',
    'Japan',
    'Macau',
    'Malaysia');
$continents[2]['name'] = 'CENTRAL & SOUTH AMERICA';
$continents[2]['countries'] = array(
    'Bangkok',
    'Beijing',
    'Cambodia',
    'China',
    'Hong Kong',
    'India',
    'Japan',
    'Macau',
    'Malaysia');
$continents[3]['name'] = 'Asia';
$continents[3]['countries'] = array(
    'Bangkok',
    'Beijing',
    'Cambodia',
    'China',
    'Hong Kong',
    'India',
    'Japan',
    'Macau',
    'Malaysia');

$mainCountries = array();
$mainCountries[0]['name'] = 'Asia';
$mainCountries[0]['cities'] = array(
    'Bangkok',
    'Beijing',
    'Cambodia',
    'China',
    'Hong Kong',
    'India',
    'Japan',
    'Macau',
    'Malaysia');
$mainCountries[1]['name'] = 'Asia';
$mainCountries[1]['cities'] = array(
    'Bangkok',
    'Beijing',
    'Cambodia',
    'China',
    'Hong Kong',
    'India',
    'Japan',
    'Macau',
    'Malaysia');
$mainCountries[2]['name'] = 'Asia';
$mainCountries[2]['cities'] = array(
    'Bangkok',
    'Beijing',
    'Cambodia',
    'China',
    'Hong Kong',
    'India',
    'Japan',
    'Macau',
    'Malaysia');


$followers_array_count = userSubscriberSearch($options);
$data['continents']	=	$continents;
$data['mainCountries']	=	$mainCountries;
$data['bag_item'] = $bag_item;

echo $template->render($data);
?>

<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript" src="//google-maps-utility-library-v3.googlecode.com/svn-history/r290/trunk/infobox/src/infobox_packed.js"></script>
<script src="//google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.9/src/markerwithlabel.js" type="text/javascript"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js"> </script>
<script>
var myCenter=new google.maps.LatLng(51.508742,-0.120850);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:5,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script type="text/javascript">
 
	    $(document).ready(function(){
        $(".custom-select1").each(function(){
            $(this).wrap("<span class='select-wrapper1'></span>");
            $(this).after("<span class='holder1'></span>");
        });
        $(".custom-select1").change(function(){
            var selectedOption = $(this).find(":selected").text();
            $(this).next(".holder1").text(selectedOption);
			
        }).trigger('change');
    })
	
		$(document).ready(function(){
        $(".custom-select").each(function(){
            $(this).wrap("<span class='select-wrapper'></span>");
            $(this).after("<span class='holder'></span>");
        });
        $(".custom-select").change(function(){
            var selectedOption = $(this).find(":selected").text();
            $(this).next(".holder").text(selectedOption);
        }).trigger('change');
    })
</script>


<?php include("BottomIndex.php");?>