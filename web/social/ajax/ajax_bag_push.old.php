<?php
$path="../";
$bootOptions=array("loadDb"=>1,"loadLocation"=>0,"requireLogin"=>0);
include_once($path."inc/common.php");
include_once($path."inc/bootstrap.php");
include_once($path."inc/functions/users.php");
include_once($path."api/iosPush/iospush.php");
include_once($path."inc/functions/bag.php");

$user_id = userGetID();
//$bag_id = intval(@$_POST["value"]);
$bag_id = intval($request->request->get('value', 0));
$bag_info = userBagInfo($user_id,$bag_id);
$country_code = $bag_info['country_code'];
$state_code = $bag_info['state_code'];
$city_id = $bag_info['city_id'];
$bag_name = '';
if($city_id!=0){
    $city_info = worldcitiespopInfo($city_id);
    $bag_name = $city_info['name'];
    $country_code = strtoupper($city_info['country_code']);
    $state_code = $city_info['state_code'];        
    $state_info = worldStateInfo($country_code,$state_code);
    $state_name = $state_info['state_name'];        
    $bag_name .= ' - '.$state_name;        
    $country_info = countryGetInfo($country_code);        
    $country_name = $country_info['name'];
    $bag_name .= ' - '.$country_name;
}else if($state_code!=''){        
    $state_info = worldStateInfo($country_code,$state_code);
    $state_name = $state_info['state_name'];        
    $bag_name = $state_name;        
    $country_info = countryGetInfo($country_code);        
    $country_name = $country_info['name'];
    $bag_name .= ' - '.$country_name;
}else if($country_code!=''){
    $country_info = countryGetInfo($country_code);        
    $country_name = $country_info['name'];
    $bag_name = $country_name;
}
        
$baglist = userBagListByName($user_id, $bag_id);
$bag_id = $baglist[0]['id'];

$res = bagPushToMobile($user_id, $bag_id, $bag_name, "A bag was pushed to your mobile.");
echo json_encode($res);