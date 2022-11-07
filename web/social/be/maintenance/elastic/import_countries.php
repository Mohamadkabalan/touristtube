<?php
include("connection.php");


$type  = "countries"; // type name to be used for elastic
$query = "SELECT name,code from `cms_countries`";
if ($rs    = mysqli_query($conn, $query)) {
    $count = mysqli_num_rows($rs);

    if ($count < 1) die('No data to import');

    echo "\n\n$type count:: $count\n";


    $i   = 1;
    while ($row = mysqli_fetch_assoc($rs)) {
        $query5 = "SELECT name,lang_code,code from `ml_countries` where code = '".$row['code']."'";
        if ($rs5    = mysqli_query($conn, $query5)) {
            while ($row5 = mysqli_fetch_assoc($rs5)) {
                if ($row5['lang_code'] = 'fr') {
                    $row['name_fr'] = '';
                } elseif ($row5['lang_code'] = 'in') {
                    
                } elseif ($row5['lang_code'] = 'cn') {
                    
                }
            }

            mysqli_free_result($rs5);
        }


        $row['titleLocation'] = $row['city_name'].(isset($row['country_name']) ? ' '.$row['state'] : '');
        $params['body'][]     = array(
            'index' => array('_id' => $row['id'], '_index' => $index, '_type' => $type)
        );
        $params['body'][]     = json_encode($row);

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

