<?php
include("connection.php");


$type= "HotelsCityNew"; // type name to be used for elastic
$query = "SELECT *,h.city_name as name,h.city_name as namePh FROM cms_hotel_city as h";
if($rs=mysqli_query($conn,$query)){
    $count=mysqli_num_rows($rs);
    if(!$count)
        die('No data to import');

    echo "\n\n$type count:: $count\n";
    
    $i=1;
    while($row=mysqli_fetch_assoc($rs)){
        $query2 = "SELECT count(`id`) as hotelcount FROM `cms_hotel_source` WHERE `location_id` =".$row['location_id'];
        
        $rs2 = mysqli_query($conn,$query2);
        if(mysqli_num_rows($rs2)){
            $row2 = mysqli_fetch_assoc($rs2);
            $countHotel = $row2['hotelcount'];
            mysqli_free_result($rs2);
        }
        $row['hotelcount'] = $countHotel;
        
        
        $params['body'][] = array(
                'index' => array('_id' =>$row['id'],'_index'=>$index,'_type'=>$type )
                ); 
        $params['body'][] = json_encode($row);

         if ($i % 10000==0) { 
            echo "importing every ".$i." records \n";
            $responses = $client->bulk($params);
            // erase the old bulk request
            $params = array();
            // unset the bulk response when you are done to save memory
            unset($responses);
        }
        if ($i==$count && !empty($params)) { 
            echo "importing last left records";
            $responses = $client->bulk($params);
            $params = array();
            unset($responses);
        }
        $i++;
    }
    mysqli_free_result($rs);
}

?>

