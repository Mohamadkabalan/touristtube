<?php
include("connection.php");


$type= "airport"; // type name to be used for elastic
$query = "SELECT a.id, a.name, a.city, a.country, st.state_name 
FROM airport a 
LEFT JOIN states as st ON (st.state_code = a.state_code AND st.country_code = a.country) 
WHERE a.published = 1 AND NOT (a.name LIKE 'Bus %' OR a.name LIKE '% Bus %' OR a.name LIKE '%Bus')";

if($rs=mysqli_query($conn,$query)){
$count = mysqli_num_rows($rs);

if($count < 1)
	die('No data to import');

$country_names = array();

$query5 = "SELECT code, name FROM cms_countries";

if($rs5 = mysqli_query($conn, $query5))
{
	while($row5 = mysqli_fetch_assoc($rs5))
	{
		// $countriesArray[$row5['code']]=$row5;
		$country_names[$row5['code']] = $row5['name'];
	}
	
	mysqli_free_result($rs5);
}

$i=1;
while($row=mysqli_fetch_assoc($rs)){
	
/*
// block commented out because we don't need all those variables
$country = $countriesArray[strtoupper($row['country'])];
    
if(isset($country)){
$countryName = $country['name'];
 $row['countryName'] = $countryName;
}
*/

$country_code = strtoupper($row['country']);

if (isset($country_names[$country_code]))
	$row['countryName'] = $country_names[$country_code];

 $params['body'][] = array(
        'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )
	); 
    $params['body'][] = json_encode($row);

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

?>

