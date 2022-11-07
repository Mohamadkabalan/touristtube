<?php

$ix = 0;
// To Connect to facebook app
include_once ( $path.'vendor/autoload.php' );
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
if( !isset($request) || !is_object($request) )$request = Request::createFromGlobals();
$php_page = basename($request->server->get('PHP_SELF', ''));
if(php_sapi_name() == 'cli'){
	include_once ( $path . "inc/functions/cache_hash.php" );
}else{
	//the xcache abstraction for caching
	//include_once ( $path . "inc/functions/cache_xcache.php" );

	//apc cache abstraction
	include_once ( $path . "inc/functions/cache_apc.php" );
}

//constants definition for css and js versioning . added on 15-April-2015
include_once ( $path . "inc/versions_vars.php" );



//the cache abstraction of intrusion detection and DoS system
//include_once ( $path . "inc/functions/ids_cache.php" );
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', getenv('ENVIRONMENT'));
include_once ( $path . 'inc/config.php' );
include_once ( $path . "inc/functions/db_mysql.php" );



if(session_id() == '') session_start();
ob_start();

// Search Freindly URL
include_once($path . 'inc/UriManager.php');
DecomposeUri();
//Channel
include_once($path . 'inc/functions/channel.php');
//mailer class
include_once($path . 'inc/classes/class.smtp.php');
include_once($path . 'inc/classes/phpmailer.class.php');
//Misc
include_once($path . 'inc/misc.php');
//emails
//locations
include_once($path . 'inc/functions/locations.php');
//all socail functionality
include_once($path . 'inc/functions/social.php');
include_once($path . 'inc/functions/webcams.php');
require_once($path . 'inc/functions/discover.php');
include_once($path . 'inc/functions/flash.php');
include_once ( $path . "inc/functions/smart_resize_image.php" );
//search engine optimization functions
include_once($path . 'inc/functions/seo.php');
//search engine
include_once($path . 'inc/functions/search.php');
//the language support
include_once($path . 'inc/functions/lang.php');
//the remove accent
include_once($path . 'inc/functions/accent.inc.php');
//the ios push functionality
include_once ( $path . "api/iosPush/iospush.php" );

//which abstraction layer?

include_once ( $path . "inc/functions/journal.php" );


//fix lang
//$php_page = basename($_SERVER['SCRIPT_FILENAME']);
$php_page = basename($request->server->get('SCRIPT_FILENAME', ''));

//include_once ( $path . "vendor/autoload.php" );
$hashids = new Hashids\Hashids($CONFIG['hash_salt'], 8);
tt_global_set('hashids', $hashids);

tt_global_set('page',$php_page);

$allowed_lang_array = array('fr', 'in');
$GLOBAL_LANG = 'en';

// $subdomain_lang = array_shift((explode(".",$_SERVER['HTTP_HOST'])));
// $subdomain_lang = array_shift((explode(".",$request->server->get('HTTP_HOST', ''))));
// if(in_array($subdomain_lang, $allowed_lang_array)){
//     $GLOBAL_LANG = $subdomain_lang;
// }
 $allowed_lang_array = array('fr', 'in', 'cn');
$subdomain_lang     = explode("/", $request->server->get('REQUEST_URI', ''));
$i                  = 0;
$GLOBAL_LANG        = '';
while ($i < sizeof($subdomain_lang) && $i < 3) {
    if (in_array($subdomain_lang[$i], $allowed_lang_array)) {
        $GLOBAL_LANG = $subdomain_lang[$i];
        $i           = 3;
    }
    $i++;
}

$slang=LanguageGet();
$js_local = setLangGetText($slang);
  //   Symfony\Component\Translation\Loader\ArrayLoader,
use  Symfony\Component\Translation\Translator,
     Symfony\Component\Translation\Loader\MoFileLoader,
     Symfony\Component\Locale\Locale;
//use  MaxMind\Db\Reader;
  
 
$translator = new Translator($js_local);
//$translator->addLoader('array', new ArrayLoader() );
$translator->addLoader('pofile', new MoFileLoader() );


$filename = $CONFIG ['server']['root']."i18n/{$js_local}/LC_MESSAGES/tt_1.mo";
$mtime = filemtime($filename);
//echo "tt_1_{$mtime}.mo";
$translator->addResource( 'pofile' , "i18n/{$js_local}/LC_MESSAGES/tt_1_{$mtime}.mo", $js_local);



function t( $str, $args=array() ){
    global $translator;
    return $translator->trans( $str , $args );
}

//if( !file_exists($path . 'inc/lang/' .$slang . '.php') )
//{
//    LanguageSet('en');
//    $slang = LanguageGet();
//}

// loading database connection
function dbConnect1 ( $dbConfig ){
    try {
        //$connection = 'mysql:host='.$dbConfig[ 'host' ].';port='.$dbConfig[ 'port' ].';dbname='.$dbConfig[ 'name' ];
        $connection = 'mysql:host='.$dbConfig[ 'host' ].';dbname='.$dbConfig[ 'name' ];
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

if ( !isset($dbConn)  ){    $dbConn = dbConnect1( $CONFIG ['db'] );}

// redirection to the registration page if needed
if ( isset($bootOptions[ 'requireLogin' ]) && $bootOptions[ 'requireLogin' ] )
{
    include_once($path . "inc/functions/users.php");
	
    if(!userIsLogged()) {
        header("Location: ".ReturnLink("register"));
    }
}

$CountryName = $CountryCode = '';
// loading location Info
//if ( isset($bootOptions[ 'loadLocation' ]) && $bootOptions[ 'loadLocation' ] )
//{
////	$CountryIp = $_SERVER["REMOTE_ADDR"];
//	$CountryIp = $request->server->get('REMOTE_ADDR', '');
//        $databaseFile = '/data/utilities/GeoLite2-Country.mmdb';
//        $reader = new Reader($databaseFile);
//        $countryLoc = $reader->get($CountryIp);
//        $reader->close();
//	$CountryName = $countryLoc['country']['names']['en'];
//	$CountryCode = $countryLoc['country']['iso_code'];
//}

include_once($path . 'inc/functions/emails.php');