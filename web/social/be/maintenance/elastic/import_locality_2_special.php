<?php
include("connection.php");

$type = "locality"; // type name to be used for elastic

$query="SELECT *,name as autocomplete_destinct,name as namePh, 1 as type FROM `webgeocities` WHERE id IN (420257,919743,944776,1135903)";
$rs = mysqli_query($conn,$query);
$count = mysqli_num_rows($rs);
if($count<1)
	die('No records to import');

//$query1 = "SELECT id,user_id,filename,default_pic,restaurant_id  FROM `discover_restaurants_images`";
// $query1 = "SELECT `states`.*,`states`.state_name as state_namePh,`cms_countries`.*,`cms_countries`.full_name as full_namePh,`cms_countries`.name as namePh,`cms_continents`.`name` as continent_name  from `cms_countries`,`cms_continents`,states WHERE states.country_code=`cms_countries`.code AND `cms_countries`.`continent_code`=`cms_continents`.`code`";

$query1 = "SELECT `states`.*,`states`.state_name as state_namePh, 
cms_countries.id, cms_countries.code, cms_countries.name, cms_countries.full_name, cms_countries.iso3, cms_countries.number, cms_countries.dialing_code, cms_countries.continent_code, cms_countries.latitude, cms_countries.longitude, cms_countries.ioc_code, cms_countries.popularity, cms_countries.capital_city, cms_countries.last_modified, cms_countries.used_by_safe_charge, cms_countries.flag_icon, cms_countries.search_hotels, 
`cms_countries`.full_name as full_namePh,`cms_countries`.name as namePh,`cms_continents`.`name` as continent_name  from `cms_countries`,`cms_continents`,states WHERE states.country_code=`cms_countries`.code AND `cms_countries`.`continent_code`=`cms_continents`.`code`";
$countryArray = array();
if($rs1 = mysqli_query($conn,$query1)){
 while($row1 = mysqli_fetch_assoc($rs1)){
  $countryArray[$row1['country_code'].'-'.$row1['state_code']][]=$row1;
 }
 
 mysqli_free_result($rs1);
}
$i =1;
//$states = array();
//
//$query3="SELECT `states`.state_name AS stateName,states`.state_name AS stateNamePh,`states`.state_name AS autocomplete_destinct, `states`.country_code AS country_code, cms_countries.name AS country_name, `states`.popularity AS popularity, state_code as state_code, 2 AS type FROM `states` , `cms_countries` WHERE states.country_code = `cms_countries`.code";
//$rs3 = mysqli_query($conn,$query3);
//while($row3 = mysqli_fetch_assoc($rs3)){
//    $states[]=$row3;
//}
//mysqli_free_result($rs3);
//
//
//$countriesArray = array();
//$query2="SELECT code as countryCode, name as countryName, name as countryNamePh, name as autocomplete_destinct, full_name as countryFullname, full_name as countryFullnamePh, ioc_code as countryThreeCode,iso3 countryISO3Code, popularity as popularity, 3 as type FROM `cms_countries`";
//$rs2 = mysqli_query($conn,$query2);
//while($row2 = mysqli_fetch_assoc($rs2)){
//    $countriesArray[]=$row2;
//}
//
//mysqli_free_result($rs2);
//
//foreach ($states as $state){
//    
//    $id = $state['type']."-".$state['country_code']."-".$state['state_code'];
//    $params['body'][] = array('index' => array('_id'=> $id, '_index'=>$index,'_type'=>$type ) ); 
//    $params['body'][] = $state;
//}
//
//foreach ($countriesArray as $country){
//    $id = $country['type']."-".$country['countryCode'];   
//    $params['body'][] = array( 'index' => array('_id'=> $id,'_index'=>$index,'_type'=>$type ) ); 
//    $params['body'][] = $country;
//}

while($row = mysqli_fetch_assoc($rs)){
    $id = $row['type']."-".$row['id'];
$params['body'][] = array(
        'index' => array('_id'=> $id,'_index'=>$index,'_type'=>$type )
 );
$row['state'][] = $countryArray[$row['country_code'].'-'.$row['state_code']];
$params['body'][] = json_encode($row);

if ($i%10000==0) { echo "importing every ".$i." records \n";
$responses = $client->bulk($params);
$params = array();
unset($responses);
}

if ($i==$count && !empty($params)) { echo "importing last left records \n";
$responses = $client->bulk($params);
$params = array();
unset($responses);
}
$i++;
}

mysqli_free_result($rs);

echo date('l jS \of F Y h:i:s A').'|||';