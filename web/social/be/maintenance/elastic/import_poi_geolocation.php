<?php
include("connection.php");

$type= "poi"; // type name to be used for elastic

$query = "SELECT h.*,h.name as name2, h.name as namePh, wc.name as city, states.state_code, states.state_name, cms_countries.code as country_code, cms_countries.name as country_name, concat(h.latitude, ', ', h.longitude) as geolocation FROM discover_poi h, webgeocities wc, states, cms_countries  WHERE h.city_id =wc.id AND wc.country_code=cms_countries.code AND wc.state_code=states.state_code AND states.country_code=cms_countries.code AND h.published=1";
if($rs = mysqli_query($conn,$query)){
$count = mysqli_num_rows($rs);

if($count < 1)
	die('No data to import');

echo "\n\n$type count:: $count\n";

$query1 = "SELECT *,0 as extra from discover_poi_images";

if($rs1 = mysqli_query($conn, $query1)) {
	while($row1 = mysqli_fetch_assoc($rs1)){
		$imagesArray[$row1['poi_id']][] = $row1;
	}
	
	mysqli_free_result($rs1);
}

$query2 = "SELECT * from discover_poi_categ";

if($rs2 = mysqli_query($conn, $query2)) {
	while($row2 = mysqli_fetch_assoc($rs2)) {
		$poiCatArray[$row2['poi_id']][] = $row2;
	}
	
	mysqli_free_result($rs2);
}

$i = 1;

while($row = mysqli_fetch_assoc($rs)) {
  
$row['images']=array();
$poiID = $row['id']; 
if(!isset($imagesArray[$poiID]))
    $imagesArray[$poiID] = array();

$query4 = "SELECT * from cms_thingstodo_details where entity_id = ".$row['id']." AND entity_type = 30 AND image <> '' AND image IS NOT NULL";
$rs4 = mysqli_query($conn, $query4);

if ($rs4 !== false) {
	if(mysqli_num_rows($rs4)) {
		$sar = array('id' => 0, 'user_id' => 0, 'default_pic' => 100, 'extra' => 1);
		
		$row4 = mysqli_fetch_assoc($rs4);
		
		$sar['filename'] = $row4['image'];
		$sar['poi_id'] = $row4['entity_id']; 
		
		array_unshift($imagesArray[$poiID], $sar);
	}
}

if(isset($imagesArray[$row['id']])) {
    $row['images'] = $imagesArray[$poiID];
}

$row['poiCat'] = array();

if(isset($poiCatArray[$row['id']])){
    $title ='';
    $ids = '';
    foreach($poiCatArray[$row['id']] as $poicat){
        $id = $poicat['categ_id'];
        $ids .= $id;
        $query3 = "SELECT * from `discover_categs` where id=".$poicat['categ_id'];
        if($rs3 = mysqli_query($conn,$query3)){
            $row3 = mysqli_fetch_assoc($rs3);
            $title .= $row3['title'];
        }
        $title .= ',';
        $ids .=  ',';
    }
}
$title=rtrim($title, ",");
$ids=rtrim($ids, ",");
$row['poiCatTitle']=$title;
$row['poiCatIds']=$ids;

$row['titleLocation'] = $row['name'].' '.$row['city'].' '.$row['state_name'].' '.$row['country_name'];
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

