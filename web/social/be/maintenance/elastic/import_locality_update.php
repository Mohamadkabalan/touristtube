<?php
include("connection.php");

$type= "locality"; // type name to be used for elastic

$type_info = 2;

if($type_info == 1){
    $city_id="920114";
    $id_to_change = $type_info."-".$city_id;
    $query="SELECT *, 1 as type FROM `webgeocities` WHERE id=".$city_id;
}elseif($type_info == 2){
    $state_code   = "CO";
    $country_code = "US";
    $id_to_change = $type_info."-".$country_code."-".$state_code;
    $query="SELECT `states`.state_name AS stateName, `states`.country_code AS country_code, cms_countries.name AS country_name, `states`.popularity AS popularity, state_code as state_code, 2 AS type FROM `states` , `cms_countries` WHERE states.country_code = `cms_countries`.code AND states.country_code ='".$country_code."' AND states.state_code ='".$state_code."' ";
}elseif ($type_info == 3) {
    $country_code = "FR";
    $id_to_change = $type_info."-".$country_code;
    $query="SELECT code as countryCode, name as countryName, full_name as countryFullname, ioc_code as countryThreeCode,iso3 countryISO3Code, popularity as popularity, 3 as type FROM `cms_countries` WHERE code = '".$country_code."' ";
}


if($rs=mysqli_query($conn,$query)){
$count=mysqli_num_rows($rs);

if($count<1)
	die('No data to import');

$i=1;
while($row=mysqli_fetch_assoc($rs)){
if($type_info == 1){
    $query1 = "SELECT `states`.state_code,`states`.state_name,`cms_countries`.code as country_code,`cms_countries`.name as name,`cms_countries`.full_name as full_name,`cms_countries`.iso3 as iso3,`cms_countries`.ioc_code as ioc_code from `cms_countries`,states WHERE states.country_code=`cms_countries`.code AND states.state_code = '".$row['state_code']."' AND states.country_code = '".$row['country_code']."' ";
    $rs1=mysqli_query($conn,$query1);
    $row1=mysqli_fetch_assoc($rs1);
    $row['state'][] = $row1;
	
	mysqli_free_result($rs1);
}
    

 $params['body'][] = array(
        'index' => array('_id' =>$id_to_change,'_index'=>$index,'_type'=>$type )
	); 
    $params['body'][] = $row;

 if ($i % 10000==0) { echo "importing every ".$i." records \n";
        $responses = $client->bulk($params);
        // erase the old bulk request
        $params = array();
        // unset the bulk response when you are done to save memory
        unset($responses);
    }
 if ($i==$count && !empty($params)) { echo "importing last left records";
	$responses = $client->bulk($params);
	$params = array();
	unset($responses);
 }
$i++;
}

mysqli_free_result($rs);
}
echo "updated"

?>