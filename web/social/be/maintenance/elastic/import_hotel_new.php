<?php
include("connection.php");


$type= "HotelsNew"; // type name to be used for elastic
$query = "SELECT *,h.city as cityName,h.name as nameNoAuto, h.name as nameAuto, h.name as namePh FROM cms_hotel as h where h.published=1";
if($rs=mysqli_query($conn,$query)){
    $count=mysqli_num_rows($rs);
    if(!$count)
        die('No data to import');

    echo "\n\n$type count:: $count\n";
    
    $i=1;
    while($row=mysqli_fetch_assoc($rs)){
        $translation= array();
        $titlelocation = '';
        $titlelocation = $row['name'];
        $titlelocation .= ' '.$row['city'];
        $countryCode = $row['country_code'];
        $query2 = "SELECT c.name,c.popularity as country_popularity FROM cms_countries as c WHERE c.code ='".$countryCode."'";
        
        $rs2 = mysqli_query($conn,$query2);
        if(mysqli_num_rows($rs2)){
            $row2 = mysqli_fetch_assoc($rs2);
            $countryName = $row2['name'];
            $country_popularity = $row2['country_popularity'];
	    $row['countryName'] = $countryName;
	    $row['country_popularity'] = $country_popularity;
            $titlelocation .= ' '.$countryName;
            mysqli_free_result($rs2);
        }
        $row['titlelocation'] = $titlelocation;
        
        $query3 = "SELECT `location_id` FROM `cms_hotel_source` WHERE `hotel_id` = ".$row['id']." AND source = 'hrs' ";
        
        $rs3 = mysqli_query($conn,$query3);
        if(mysqli_num_rows($rs3)){
            $row3 = mysqli_fetch_assoc($rs3);
            $location_id = $row3['location_id'];
            mysqli_free_result($rs3);
        }
        $row['locationId'] = $location_id;

        $query4 = "SELECT * FROM ml_hotel WHERE hotel_id = ".$row['id'];
        $rs4    = mysqli_query($conn, $query4);
        while ($row4 = mysqli_fetch_assoc($rs4)) {
            $translation['name_'.$row4['lang_code']] = $row4['name'];
            $translation['description_'.$row4['lang_code']] = $row4['name'];
        }
        $translation = array_merge(array('name_en' => $row['name'],'description_en' => $row['description']),$translation);
        $row['translation'] = $translation;
        mysqli_free_result($rs4);
        $row['gis_point'] = '';
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

