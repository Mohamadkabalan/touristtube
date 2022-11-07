<?php

include("connection.php");

$type= "hotelSearch"; 
$query = "SELECT hs.* FROM `cms_hotel_search` AS hs WHERE published=1";
if($rs = mysqli_query($conn,$query)) {
    $count = mysqli_num_rows($rs);
    
    if(!$count) die('No data to import');
    echo "\n\n$type count:: $count\n";
    $i = 1;
    while($row=mysqli_fetch_assoc($rs)) {
        $row['results'] = array();
        $query2 = "SELECT hsd.* FROM `cms_hotel_search_details` AS hsd WHERE hsd.`hotel_booking_id` = ".$row['id']." AND published=1 ORDER BY `popularity`";
        if($rs2 = mysqli_query($conn, $query2)) {
            while($row2 = mysqli_fetch_assoc($rs2)) {
                $row['results'][] = $row2;
            }
            mysqli_free_result($rs2);
        }
        if( sizeof($row['results'])>0){
            $params['body'][] = array(
                'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )
            ); 
            $params['body'][] = json_encode($row);

            if ($i % 10000==0) { echo "importing every ".$i." records \n";
                   $responses = $client->bulk($params);
                   // erase the old bulk request
    //               $params = array();
                   unset($params);
                   // unset the bulk response when you are done to save memory
                   unset($responses);
               }
            if ($i==$count && !empty($params)) { echo "importing last left records";
                   $responses = $client->bulk($params);
    //               $params = array();
                   unset($params);
                   unset($responses);
            }
        }
        $i++;
    }
	
	mysqli_free_result($rs);
}

?>
