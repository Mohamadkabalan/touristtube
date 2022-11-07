<?php
require '../../../vendor/autoload.php';
//require 'vendor/autoload.php';

$params = array();

echo date('l jS \of F Y h:i:s A').'|||';
$params1 = array('hosts' => array('192.168.2.30:9200'));
$client = new Elasticsearch\Client($params1);

$index = "tt"; // index name
$type= "restaurant"; // type name to be used for elastic


//$conn = mysqli_connect('localhost','root','mysql_root','touristtube');
$conn = mysqli_connect('192.168.2.5','root','7mq17psb','touristtube');
//$conn = mysqli_connect('localhost','root','tt','tt');
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
	$conditionalStatement = " AND r.last_modified > '$updateTime'";

$query = "SELECT r.* FROM discover_restaurants r WHERE published = 1".$conditionalStatement;

$rs = mysqli_query($conn,$query);

$count = mysqli_num_rows($rs);

if(!$count)
	die('No records to import');


$imagesArray = array();

$query1 = "SELECT i.* from discover_restaurants_images i".($conditionalStatement?" INNER JOIN discover_restaurants r ON (r.id = i.restaurant_id$conditionalStatement)":'');

if($rs1 = mysqli_query($conn, $query1)) {
	while($row1 = mysqli_fetch_assoc($rs1)) {
		$imagesArray[$row1['restaurant_id']][] = $row1;
	}
	
	mysqli_free_result($rs1);
}

//print_r($imagesArray); exit;


// Query to log the last timestamp of update elastic 
$insertQuery = "INSERT INTO `elastic_update_log`(`id`, `indexName`) VALUES (null,'".$type."')";
if(mysqli_query($conn,$insertQuery))
 echo "log updated";
// logging till here

$i=1;

while($row = mysqli_fetch_assoc($rs)){

$row['images']=array();
if(isset($imagesArray[$row['id']])){
 $resid = $row['id'];
 $row['images']	=	$imagesArray[$resid];
}
print_r($row); exit;
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
