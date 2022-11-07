<?php
require '../../../vendor/autoload.php';
include_once '../../../inc/config.php';

echo date('l jS \of F Y h:i:s A') . '|||';

$params1 = array(
    'hosts' => array(
        $CONFIG['elastic']['ip']
    )
);

$client = new Elasticsearch\Client($params1);

$index = $CONFIG['elastic']['index'];

$conn = mysqli_connect($CONFIG['db']['host'], $CONFIG['db']['user'], $CONFIG['db']['pwd'], $CONFIG['db']['name']);
$conn->set_charset("utf8");

$params = array();
?>