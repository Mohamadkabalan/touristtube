<?php
/*! \file
 * 
 * \brief This api returns information of a tuber
 * 
 * \todo <b><i>Change from xml to Json object</i></b>
 * 
 * @param a range of age of tuber
 * @param s gender of tuber
 * @param long longitude of tuber
 * @param lat latitude of tuber
 * @param r radius of tuber
 * @param p page number (starting from 0)
 * @param n country of tuber
 * 
 * @return <b>res</b> xml :
 * @return <pre> 
 * @return         <b>fullname</b> tuber full name
 * @return         <b>gender</b> tuber gender
 * @return         <b>YourCountry</b> tuber Country
 * @return         <b>YourBday</b> tuber date of birth
 * @return         <b>profile_Pic</b> tuber profile Picture path
 * @return         <b>profile_views</b> tuber number of views
 * @return         <b>longitude</b> tuber longitude
 * @return         <b>latitude</b> tuber latitude
 * @return         <b>category_id</b> tuber category id
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */

//age $_REQUEST['a']
// 0 - Any
// 1 - 18~24
// 2 - 25~32
// 3 - 33~45
// 4 - 45~60
// 5 - 60+
//SEX $_REQUEST['s']
// 0 female
// 1 male
// 2 or all do not send
//Page $_REQUEST['p']
// 0 , 1 , 2 , 3 , 4 ........
//lon $_REQUEST['lon'], lat $_REQUEST['lat']
//longitude and latitude
//Nationality $_REQUEST['n']
// LB, US (2 letters code of country UPPER CASE)
//Radius $_REQUEST['r']
//radius 



header("content-type: application/xml; charset=utf-8");
$expath = "../";
include("../heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());

$age = null;
$gender = null;
$country = null;
$radius = 1000;
$lat = null;
$lon = null;
$orderby = 'id';
$order = 'a';
$page = 0;
$limit = 25;


//switch ($_REQUEST['a']) {
switch ($submit_post_get['a']) {
    case 0 : $age = null;
        break;
    case 1 : $age = "18,19,20,21,22,23,24";
        break;
    case 2 : $age = "25,26,27,28,29,30,31,32";
        break;
    case 3 : $age = "33,34,35,36,37,38,39,40,41,42,43,44,45";
        break;
    case 4 : $age = "46,47,48,49,50,51,52,53,54,55,56,57,58,59,60";
        break;
    case 5 : $age = "61,62,63,64,65,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120";
        break;
}
//if (isset($_REQUEST['s'])) {
//    switch ($_REQUEST['s']) {
if (isset($submit_post_get['s'])) {
    switch ($submit_post_get['s']) {
        case 0 : $gender = "F";
            break;
        case 1 : $gender = "M";
            break;
        case 2 : $gender = null;
            break;
    }
}
//if (isset($_REQUEST['lon']) && isset($_REQUEST['lat'])) {
//    $lon = $_REQUEST['lon'];
//    $lat = $_REQUEST['lat'];
//}
//if (isset($_REQUEST['p'])) {
//    $page = $_REQUEST['p'];
//}
//if (isset($_REQUEST['n'])) {
//    $country = strtoupper($_REQUEST['n']);
//}
//if (isset($_REQUEST['r'])) {
//    $radius = $_REQUEST['r'];
//}
if (isset($submit_post_get['lon']) && isset($submit_post_get['lat'])) {
    $lon = $submit_post_get['lon'];
    $lat = $submit_post_get['lat'];
}
if (isset($submit_post_get['p'])) {
    $page = $submit_post_get['p'];
}
if (isset($submit_post_get['n'])) {
    $country = strtoupper($submit_post_get['n']);
}
if (isset($submit_post_get['r'])) {
    $radius = $submit_post_get['r'];
}


$tuberSearch = array(
    'limit' => $limit,
    'page' => $page,
    'orderby' => $orderby,
    'order' => $order,
    'latitude' => $lat,
    'longitude' => $lon,
    'radius' => $radius,
    'country' => $country,
    'gender' => $gender,
    'age' => $age
);
//var_dump($tuberSearch);
$datas = tuberSearch($tuberSearch);

//var_dump($datas);
$res = "<tubers>";
foreach ($datas as $data) {
    $res .= "<tuber>";
    /* $i=0;
      foreach(array_keys($data) as $v)
      {
      //echo " |".$v."-".$i."| ";
      if ($v != $i)
      {
      $res .= "<".$v.">".$data[$v]."</".$v.">";
      }else
      {
      $i = $i + 1;
      }

      }
      $i=0; */

    $res .= "<id>" . $data['id'] . "</id>";
	
	/*code changed by sushma mishra on 30-sep-2015 to get fullname using returnUserDisplayName function starts from here*/
	$userDetail = getUserInfo($data['id']);
    /*if ($data['FullName'] != '')
        $res .= "<fullname>" . safeXML($data['FullName']) . "</fullname>";
    else
        $res .= "<fullname>" . safeXML($data['YourUserName']) . "</fullname>";
	*/	
	$res .= "<fullname>" . safeXML(returnUserDisplayName($userDetail)) . "</fullname>";
	/*code changed by sushma mishra on 30-sep-2015 ends here*/
	
    //$res .= "<fname>".$data['fname']."</fname>";
    //$res .= "<lname>".$data['lname']."</lname>";
    $res .= "<gender>" . $data['gender'] . "</gender>";
    //$res .= "<small_description>".$data['small_description']."</small_description>";


    $res .= "<YourCountry>" . $data['YourCountry'] . "</YourCountry>";
    //$res .= "<hometown>".$data['hometown']."</hometown>";
    //$res .= "<city>".$data['city']."</city>";
    //$res .= "<YourUserName>".$data['YourUserName']."</YourUserName>";
    
    $dobVal = '';
    if ($data['YourBday'] == '0000-00-00' || is_null($data['YourBday'])) {
        $dobVal = '';
    } else {
        $dobVal = intval((time() - strtotime($data['YourBday'])) / (365 * 24 * 3600));
    }
    $res .= "<YourBday>" . $dobVal . "</YourBday>";
    $res .= "<profile_Pic>media/tubers/" . $data['profile_Pic'] . "</profile_Pic>";
    //$res .= "<display_age>".$data['display_age']."</display_age>";
    $res .= "<profile_views>" . $data['profile_views'] . "</profile_views>";
    //$res .= "<published>".$data['published']."</published>";
    $res .= "<longitude>" . $data['longitude'] . "</longitude>";
    $res .= "<latitude>" . $data['latitude'] . "</latitude>";
    $res .= "<category_id>3</category_id>";
    $res .= "</tuber>";
}
$res .= "</tubers>";
echo $res;
