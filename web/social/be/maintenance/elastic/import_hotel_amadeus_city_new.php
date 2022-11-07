<?php
include("connection.php");


$type  = "ttHotelCity"; // type name to be used for elastic
$query = "SELECT w.id, w.country_code, w.state_code, w.name as cityName, w.latitude, w.longitude, w.timezoneid, w.popularity 
          FROM webgeocities as w
          INNER JOIN amadeus_hotel_city as ahc ON ahc.city_id = w.id";
if ($rs    = mysqli_query($conn, $query)) {
    $count = mysqli_num_rows($rs);
    if (!$count) die('No data to import');

    echo "\n".date('c')."\n\n$type count:: $count\n";

    $i   = 1;
    while ($row = mysqli_fetch_assoc($rs)) {
        $params['body'][] = array(
            'index' => array('_id' => $row['id'], '_index' => $index, '_type' => $type)
        );
        $params['body'][] = json_encode($row);

        if ($i % 10000 == 0) {
            echo "\n".date('c')."importing every ".$i." records \n";
            $responses = $client->bulk($params);
            // erase the old bulk request
            $params    = array();
            // unset the bulk response when you are done to save memory
            unset($responses);
        }
        if ($i == $count && !empty($params)) {
            echo "\n".date('c')."importing last left records";
            $responses = $client->bulk($params);
            $params    = array();
            unset($responses);
        }
        $i++;
    }
    echo "\n".date('c')." - Finished importing data";
    mysqli_free_result($rs);
}
?>

