<?php
include("connection.php");

$type = "restaurant"; // type name to be used for elastic

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

$conditionalStatement = '';

// $query = "SELECT `id`,`factual_id`,`name`,`address`,`address_extended`,`po_box`,`locality`,`region`,`post_town`,`admin_region`,`postcode`,`country`,`tel`,fax,`latitude`,`longitude`,`neighborhood`,`website`,`email`,`category_ids`,`category_labels`,`chain_name`,`chain_id`,`hours`,`hours_display`,`existence`,`city_id`,`zoom_order`,`published`,`last_modified`,`from_source`,`avg_rating`,`nb_votes`,`avg_price`,`map_image`,`updated`, popularity, country_popularity, concat(latitude, ', ', longitude) as geolocation FROM `global_restaurants` WHERE published = 1".$conditionalStatement;
$query = "SELECT r.*,r.name as namePh, CONCAT(r.latitude, ', ', r.longitude) AS geolocation, c.name AS countryName FROM global_restaurants r LEFT JOIN cms_countries c ON (c.code = UPPER(r.country)) WHERE r.published = 1 AND (r.region LIKE '%(Beijing)' or r.locality LIKE '%(Beijing)') and r.country='cn'".$conditionalStatement;
$rs = mysqli_query($conn, $query);

$count = mysqli_num_rows($rs);

if(!$count)
	die('No records to import');

$imagesArray = array();

$query1 = "SELECT i.* FROM discover_restaurants_images i".($conditionalStatement?" INNER JOIN global_restaurants r ON (r.id = i.restaurant_id$conditionalStatement)":'');

if($rs1 = mysqli_query($conn, $query1)) {
	while($row1 = mysqli_fetch_assoc($rs1)) {
		$imagesArray[$row1['restaurant_id']][] = $row1;
	}
	
	mysqli_free_result($rs1);
}

//print_r($imagesArray); exit;

// Query to log the last timestamp of update elastic 
$insertQuery = "INSERT INTO elastic_update_log (indexName) VALUES ('$type')";

if(mysqli_query($conn, $insertQuery))
	echo "log updated\n";
// logging till here

$i = 1;

while($row = mysqli_fetch_assoc($rs)) {
	
	$row['images'] = array();
	if(isset($imagesArray[$row['id']])) {
		$row['images'] = $imagesArray[$row['id']];
	}
	
	//$category_id = explode(',', $row['category_ids']);
	//$row['category_ids'] = $category_id;
	
	//print_r($row); exit;
	
	$row['titleLocation'] = $row['name'].' '.$row['locality'].' '.$row['region'].' '.$row['admin_region'].' '.$row['countryName'];
	
	$params['body'][] = array('index' => array('_id' => $row['id'], '_index' => $index, '_type' => $type));

	$params['body'][] = json_encode($row);
	
	
	if ($i%10000 == 0) {
		
		echo date("Y-m-d H:i:s")."importing every ".$i." records\n";
		
		$responses = $client->bulk($params);
		
		$params = array();
		
		unset($responses);
	}
	
	if ($i == $count && !empty($params)) {
		
		echo date("Y-m-d H:i:s")."importing last left records\n";
		
		$responses = $client->bulk($params);
		
		$params = array();
		
		unset($responses);
	}
	
	$i++;
}

mysqli_free_result($rs);

echo date('l jS \of F Y h:i:s A')."\n\n";
?>
