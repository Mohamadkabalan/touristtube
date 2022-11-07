<?php
$path = "../../";
include_once ( $path . "inc/service_bootstrap.php" );
include_once($path. "inc/config.php");
require($path . 'vendor/autoload.php');
$solrId = NULL;
$likeValue = NULL;
//print_r($argv);
if(isset($argv) && count($argv) > 0){
    foreach($argv as $param){
        $split = split('=', $param);
        $key = $split[0];
        $value = $split[1];
        switch($key){
            case 'solrId':
                $solrId = $value;
                break;
            case 'likeValue':
                $likeValue = $value;
                break;
        }
    }
    
    $client = new Solarium\Client($config);
    //    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
    global $CONFIG;
    
    $config = $CONFIG['solr_config'];

    $update = $client->createUpdate();
    $doc = $update->createDocument();
    $doc->setKey('solr_id', $solrId);
    $doc->addField('like_value', $likeValue, null, 'inc');
    $update->addDocument($doc);
    $update->addCommit();
    $client->update($update);
}