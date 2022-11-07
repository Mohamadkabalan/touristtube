<?php
include("connection.php");


$type  = "DealTopAttractions"; // type name to be used for elastic
$query = "SELECT *,100 as popularity, name as attractions_name FROM deal_top_attractions";
if ($rs    = mysqli_query($conn, $query)) {
    $count = mysqli_num_rows($rs);

    if ($count < 1) die('No data to import');

    echo "\n\n$type count:: $count\n";


    $i   = 1;
    while ($row = mysqli_fetch_assoc($rs)) {
        $params['body'][] = array(
            'index' => array('_id' => $row['id'], '_index' => $index, '_type' => $type)
        );
        $params['body'][] = json_encode($row);

        if ($i % 10000 == 0) {
            echo "importing every ".$i." records \n";
            $responses = $client->bulk($params);
            // erase the old bulk request
            $params    = array();
            // unset the bulk response when you are done to save memory
            unset($responses);
        }
        if ($i == $count && !empty($params)) {
            echo "importing last left records";
            $responses = $client->bulk($params);
            $params    = array();
            unset($responses);
        }
        $i++;
    }

    mysqli_free_result($rs);
}
?>

