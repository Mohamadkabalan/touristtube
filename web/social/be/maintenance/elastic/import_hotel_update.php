<?php

require '../../../vendor/autoload.php';
$params = array();

$params1 = array('hosts' => array('89.249.212.8:9200'));
//$params = array('hosts' => array('192.168.1.200:9200'));
//$params1 = array('hosts' => array('192.168.2.30:9200'));
$client = new Elasticsearch\Client($params1);
$index = "tt"; // index name
$type= "hotel"; // type name to be used for elastic

$conn = mysqli_connect('localhost','root','mysql_root','touristtube');
//$conn = mysqli_connect('192.168.2.5','root','7mq17psb','touristtube');
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
	$conditionalStatement = " AND h.last_modified > '$updateTime'";

//$query = "SELECT ch.id, ch.channel_name, ch.logo, ch.small_description, ca.title as catname, ch.category as catid , concat (wc.accent, ' ', wc.name) as city_name_accent, ch.channel_url, ch.nb_comments, ch.up_votes,(nb_comments+ up_votes) AS social_weight, country, ch.create_ts, ch.header  FROM cms_channel_category ca , cms_channel ch LEFT JOIN webgeocities wc ON wc.id=ch.city_id WHERE ch.category=ca.id AND ch.published = '1'";
$rs = null;
$query = "SELECT h.id, h.HotelName, wc.name as city, avg_rating, nb_values, states.state_name, cms_countries.name as country FROM discover_hotels h, webgeocities wc, states, cms_countries  WHERE h.published = 1$conditionalStatement AND h.city_id = wc.id AND wc.country_code = cms_countries.code AND wc.state_code = states.state_code AND states.country_code = cms_countries.code";
if($rs = mysqli_query($conn, $query)) {
	$count = mysqli_num_rows($rs);

if(!$count)
	die('No data to import');


$imagesArray = array();

$query = "SELECT i.id, i.user_id, i.filename, i.hotel_id, i.default_pic FROM discover_hotels_images i".($conditionalStatement?" INNER JOIN discover_hotels h ON (h.id = i.hotel_id$conditionalStatement)":'');
if($rset = mysqli_query($conn, $query)) {
	while($r = mysqli_fetch_assoc($rset)){
		$imagesArray[$r['hotel_id']][] = $r;
	}
	
	mysqli_free_result($rset);
}


// Query to log the last timestamp of update elastic 
$insertQuery = "INSERT INTO `elastic_update_log`(`id`, `indexName`) VALUES (null,'".$type."')";
if(mysqli_query($conn,$insertQuery))
 echo "log updated";
// logging till here


$i=1;
while($row = mysqli_fetch_assoc($rs)){
if(!empty($imagesArray[$row['id']]))
 $row['images'] = $imagesArray[$row['id']];

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
