<?php

include_once ( $path . "inc/common.php" );

define('ENVIRONMENT', getenv('ENVIRONMENT'));
include_once ( $path . 'inc/config.php' );

//if(php_sapi_name() == 'cli'){
//    include_once ( $path . "inc/functions/cache_hash.php" );
//}else{
//    include_once ( $path . "inc/functions/cache_apc.php" );
//}

//include_once ( $path . "inc/functions/ids_cache.php" );
include_once($path . 'inc/functions/lang.php');
include_once($path . 'inc/UriManager.php');
include_once($path . 'inc/misc.php');
include_once ( $path . "inc/functions/seo.php" );
include_once ( $path . "vendor/autoload.php" );
$hashids = new Hashids\Hashids($CONFIG['hash_salt'], 8);
tt_global_set('hashids', $hashids);
include_once ( $path . "inc/functions/db_mysql.php") ;
function dbConnect ( $dbConfig ){
    try {   
        $connection = 'mysql:host='.$dbConfig[ 'host' ].';dbname='.$dbConfig[ 'name' ];
        //$connection = 'mysql:host='.$dbConfig[ 'host' ].';port='.$dbConfig[ 'port' ].';dbname='.$dbConfig[ 'name' ];
        $username   = $dbConfig[ 'user' ];
        $password   = $dbConfig[ 'pwd' ];
        $conn = new PDO($connection, $username, $password);
        $conn->exec("set names utf8");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }
    return $conn;
}


$dbConn = dbConnect( $CONFIG ['db'] );

include_once ( $path . "inc/functions/emails.php" );
include_once ( $path . "inc/functions/channel.php" );
include_once ( $path . "inc/functions/social.php" );
include_once ( $path . "inc/functions/flash.php" );
include_once ( $path . "inc/functions/journal.php" );
include_once ( $path . "inc/functions/discover.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/webcams.php" );
include_once ( $path . "inc/functions/bag.php" );
include_once ( $path . "inc/functions/smart_resize_image.php" );