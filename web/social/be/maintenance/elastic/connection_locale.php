<?php
require '../../../vendor/autoload.php';
$params  = array();
$params1 = array();
echo date('l jS \of F Y h:i:s A').'|||';

//$params1 = array('hosts' => array('192.168.2.104:9200'));
$params1 = array('hosts' => array('localehost:9200'));
$client  = new Elasticsearch\Client($params1);
$index   = "tt1"; // index name
//$conn = mysqli_connect('192.168.2.110','root','7mq17psb','touristtube');
$conn    = mysqli_connect('172.16.124.204', 'mysql_root', 'Mr4+%FINDZm,:AGL', 'touristtube');
$conn->set_charset("utf8");


$type  = "countries"; // type name to be used for elastic
$query = "SELECT name,code from `cms_countries`";
if ($rs    = mysqli_query($conn, $query)) {
    $count = mysqli_num_rows($rs);

    if ($count < 1) die('No data to import');

    echo "\n\n$type count:: $count\n";


    $i   = 1;
    $pp = '';
    while ($row = mysqli_fetch_assoc($rs)) {
        $query5 = "SELECT name,lang_code,code from `ml_countries` where code = '".$row['code']."'";
        if ($rs5    = mysqli_query($conn, $query5)) {
            while ($row5 = mysqli_fetch_assoc($rs5)) {
                $row['name_'.$row5['lang_code']] = $row5['name'];

                $pp .= 'PUT tt/countries/'.$i.'   **   { "name" : "'.$row['name'].'", "code" : "'.$row['code'].'" , "name_'.$row5['lang_code'].'" : "'.$row5['name']. '"}   **   ' ;
            }

            mysqli_free_result($rs5);
        }
    }

    echo($pp);exit;
    mysqli_free_result($rs);
}
?>

