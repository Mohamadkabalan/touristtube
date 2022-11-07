<?php

require '../vendor/autoload.php';

$params = array();

//$params = array('hosts' => array('192.168.1.200:9200'));
$client = new Elasticsearch\Client();
$index = "tt"; // index name
$type= "media"; // type name to be used for elastic
$conn	= mysqli_connect('localhost','root','tt','tt');
$conn->set_charset("utf8");


$conditionalStatement = '';

$updateTime = null;

$query = "SELECT MAX(lastUpdated) AS last_updated FROM elastic_update_log WHERE indexName = '$type'";

if ($rsUpdate = mysqli_query($conn, $query))
{
	$resultSet = mysqli_fetch_assoc($rsUpdate);
	
	if ($resultSet)
		$updateTime = $resultSet['last_updated'];
	
	mysqli_free_result($rsUpdate);
}

if($updateTime != null)
	$conditionalStatement = " AND v.last_modified > '$updateTime'";


$query = "SELECT v.*, fc_getallowedmediausers(v.id, v.userid, 1) as allowed_users FROM cms_videos where published = 1$conditionalStatement";

$rs = mysqli_query($conn,$query);

$count = mysqli_num_rows($rs);

if(!$count)
	die('No records to import');


$categories = array();
// query to get categories
$q = "SELECT id, title FROM cms_allcategories";

if($result = mysqli_query($conn, $q)) {
	while($data = mysqli_fetch_assoc($result)) {
		$categories[$data['id']] = $data['title'];
	}
	
	mysqli_free_result($result);
}


$languagesArray = array();
// to create the language info Array  of particular videos
$query1 = "SELECT v.* FROM ml_videos v";

if($rs1 = mysqli_query($conn, $query1)){
	while($row1 = mysqli_fetch_assoc($rs1)) {
		$languagesArray[$row1['video_id']]['en'] = array();
		$languagesArray[$row1['video_id']]['hi'] = array();
		$languagesArray[$row1['video_id']]['fr'] = array();
		$languagesArray[$row1['video_id']][$row1['lang_code']] = $row1;
	}
	
	mysqli_free_result($rs1);
}

//print_r($languagesArray); exit;
//till here

$usersArray = array();
//to create the userIfoArray who posted the videos
$query2 = "SELECT u.id, u.FullName, u.gender, u.YourEmail FROM cms_users u LEFT JOIN cms_videos v ON (v.userid = u.id$conditionalStatement) group by u.id ";
if($rs2 = mysqli_query($conn, $query2)) {
	while($row2 = mysqli_fetch_assoc($rs2)) {
		$usersArray[$row2['id']][] = $row2;
	}
	
	mysqli_free_result($rs2);
} // end of 2nd query

//print_r($usersArray);exit;

$citiesArray = array();
$query3 = "SELECT c.id, c.country_code, c.state_code, c.accent, c.name FROM webgeocities c JOIN cms_videos v ON (c.id = v.cityid$conditionalStatement) group by c.id";
if($rs3 = mysqli_query($conn,$query3)) {
	while($row3 = mysqli_fetch_assoc($rs3)) {
		$citiesArray[$row3['id']][] = $row3;
	}
	
	mysqli_free_result($rs3);
}

// Query to log the last timestamp of update elastic 
$insertQuery = "INSERT INTO elastic_update_log(id, indexName) VALUES (null,'".$type."')";
if(mysqli_query($conn,$insertQuery))
 echo "log updated";
// logging till here


$i = 1;
$row = '';

while($row = mysqli_fetch_assoc($rs)) {
//print_r($row);
if(isset($usersArray[$row['userid']])){
 $row['userinfo'] = $usersArray[$row['userid']];
}
if(isset($citiesArray[$row['cityid']])){
 $row['cityInfo'] = $citiesArray[$row['cityid']];
}
if(isset($languagesArray[$row['id']])){ 
 $row['language'] = $languagesArray[$row['id']];
}else{
 //$row['language'] = array('en'=>array(),'hi'=>array(),'fr'=>array());
$row['language'] = $languagesArray[44003];
}
if($row['category'] != 0) {
 $row['category'] = array('catid' => $row['category'], 'categoryName' => $categories[$row['category']]);
}

$params['body'][] = array(

        'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )

 );

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
?>

