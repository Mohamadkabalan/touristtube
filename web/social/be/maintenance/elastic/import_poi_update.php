<?php

require '../../../vendor/autoload.php';
$params = array();

//$params = array('hosts' => array('192.168.1.200:9200'));
$params1 = array('hosts' => array('192.168.2.30:9200'));
$client = new Elasticsearch\Client($params1);
$index = "tt"; // index name
$type= "poi"; // type name to be used for elastic

$conn = mysqli_connect('192.168.2.5','root','7mq17psb','touristtube');
//$conn	= mysqli_connect('localhost','root','tt','tt');
//$conn = mysqli_connect('172.16.124.204','mysql_root','Mr4+%FINDZm,:AGL','touristtube');
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
	$conditionalStatement = " AND p.last_modified > '$updateTime'";


$rs = null;
$query = "SELECT p.id, p.name, p.address, wc.name as city, st.state_name, co.name as country  FROM discover_poi p LEFT JOIN webgeocities wc ON p.city_id =wc.id  LEFT JOIN states as st ON st.state_code = wc.state_code AND st.country_code = wc.country_code  LEFT JOIN cms_countries as co ON co.code = wc.country_code  WHERE p.published = 1".$conditionalStatement;
if($rs=mysqli_query($conn,$query)){
$count=mysqli_num_rows($rs);

if($count < 1)
	die('No data to import');

$query = "SELECT i.id, i.user_id, i.filename, i.poi_id, i.default_pic FROM discover_poi_images i".($conditionalStatement?" INNER JOIN discover_poi p ON (p.id = i.poi_id$conditionalStatement)":'');

if($rset = mysqli_query($conn, $query)) {
	while($r = mysqli_fetch_assoc($rset)) {
		$imageArray[$r['poi_id']][]	= $r;
	}
	
	mysqli_free_result($rset);
}


// Query to log the last timestamp of update elastic 
$insertQuery = "INSERT INTO elastic_update_log (indexName) VALUES ('$type')";
if(mysqli_query($conn,$insertQuery))
 echo "log updated";
// logging till here


$i=1;
while($row=mysqli_fetch_assoc($rs)){
$row['images'] = array();
if(!empty($imageArray[$row['id']]))
 $row['images'] = $imageArray[$row['id']];

//print_r($row); exit();

 $params['body'][] = array(
        'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )
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

?>
