<?php

require '../../../web/vendor/autoload.php';
$params = array();
$params1 = array();
echo date('l jS \of F Y h:i:s A').'|||';

//$params1 = array('hosts' => array('192.168.2.30:9200'));
//$params1 = array('hosts' => array('89.249.212.8:9200'));
$params1 = array('hosts' => array('172.16.124.203:9200'));
//$params = array('hosts' => array('192.168.1.200:9200'));
$client = new Elasticsearch\Client($params1);
$index = "tt10"; // index name
$type= "airport"; // type name to be used for elastic

//$conn = mysqli_connect('192.168.2.5','root','7mq17psb','touristtube');
$conn = mysqli_connect('localhost','root','mysql_root','touristtube');
//$conn	= mysqli_connect('localhost','root','tt','tt');
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
	$conditionalStatement = " AND a.last_modified > '$updateTime'";

$id_to_change = '2501';

$rs = null;
$query = "SELECT * FROM `airport` where ".($id_to_change?"id = $id_to_change AND ":'')."published = 1$conditionalStatement AND NOT (name LIKE 'Bus %' OR name LIKE '% Bus %' OR name LIKE '%Bus')";
//$query = "SELECT p.id, p.name, p.address, wc.name as city, st.state_name, co.name as country  FROM discover_poi p  LEFT JOIN webgeocities wc ON p.city_id =wc.id  LEFT JOIN states as st ON st.state_code = wc.state_code AND st.country_code = wc.country_code  LEFT JOIN cms_countries as co ON co.code = wc.country_code  WHERE ".$queryString." p.published='1'";

if($rs = mysqli_query($conn,$query)){
$count = mysqli_num_rows($rs);

if(!$count)
	die('No data to import');


$imagesArray = array();

$query = "SELECT i.* FROM airport_images i".($conditionalStatement?" INNER JOIN discover_hotels h ON (h.id = i.airport_id$conditionalStatement)":'');

if($rset = mysqli_query($conn, $query)) {
	while($r = mysqli_fetch_assoc($rset)) {
		$imagesArray[$r['airport_id']][]	= $r;
	}
	
	mysqli_free_result($rset);
}

// Query to log the last timestamp of update elastic 
$insertQuery = "INSERT INTO elastic_update_log (indexName) VALUES ('$type')";
if(mysqli_query($conn, $insertQuery))
	echo "log updated";
// logging till here


$i=1;
while($row = mysqli_fetch_assoc($rs)) {
    
    
$row['images'] = array();
if(!empty($imagesArray[$row['id']]))
 $row['images'] = $imagesArray[$row['id']];

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

?>
