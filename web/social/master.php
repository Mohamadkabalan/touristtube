<?php
$path = '';
include_once 'vendor/autoload.php';
define('ENVIRONMENT', getenv('ENVIRONMENT'));
include_once ( $path . 'inc/config.php' );
include_once ( $path . "inc/functions/db_mysql.php" );
// connect to teh database
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

// adding Symfony
// 28 08 2014
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Route;

$allowed_lang = "fr|in|cn";
$routes = new RouteCollection();
$routes->add('uapi', new Route('/uapi/{q}', array('lang' => 'en', 'pageName' => 'uapi'), array('lang' => $allowed_lang, 'q' => '.*')));
$routes->add('register02', new Route('/{lang}/register?{q}', array('lang' => 'en', 'pageName' => 'register'), array('lang' => $allowed_lang,'q' => '.*')));
$routes->add('tubers02', new Route('/{lang}/tubers?{q}', array('lang' => 'en', 'pageName' => 'tubers'), array('lang' => $allowed_lang,'q' => '.*')));
$routes->add('register0', new Route('/register?{q}', array( 'pageName' => 'register'), array('q' => '.*')));
$routes->add('tubers0', new Route('/tubers?{q}', array( 'pageName' => 'tubers'), array('q' => '.*')));
//old search
$routes->add('search02', new Route('/{lang}/search?{q}', array('lang' => 'en', 'pageName' => 'search'), array('lang' => $allowed_lang,'q' => '.*')));
$routes->add('search0', new Route('/search?{q}', array( 'pageName' => 'search'), array('q' => '.*')));
//things to do
$routes->add('newroute12', new Route('/{lang}/things-to-do/{q}', array('lang' => 'en', 'pageName' => 'hotel-search'), array('lang' => $allowed_lang, 'q' => '.*')));
$routes->add('review02', new Route('/{lang}/hotel-review/{title}/id-{id}', array('lang' => 'en', 'pageName' => 'hotel-review' ), array('lang' => $allowed_lang, 'title' => '[^/]+'),array('id' => '\d+')));
$routes->add('newroute1', new Route('/things-to-do/{q}', array( 'pageName' => 'hotel-search'), array( 'q' => '.*')));
$routes->add('review0', new Route('/hotel-review/{title}/id-{id}', array( 'pageName' => 'hotel-review' ), array( 'title' => '[^/]+'),array('id' => '\d+')));
//search
$routes->add('search22', new Route('/{lang}/{qr}-{catName}-S{t}_{page}_{c}_{orderby}', array('lang' => 'en', 'pageName' => 'search','qr' => null, 'page'=>1, 'c'=>null, 't'=> null, 'orderby' => null, 'catName'=> null)
        , array('lang' => $allowed_lang,'qr' => '.*','page'=>'\d*', 'c'=>'\d*','t'=> '[a-z]?', 'orderby' => '.*', 'catName'=> '.*')));
$routes->add('search2', new Route('/{qr}-{catName}-S{t}_{page}_{c}_{orderby}', array( 'pageName' => 'search','qr' => null, 'page'=>1, 'c'=>null, 't'=> null, 'orderby' => null, 'catName'=> null)
        , array('qr' => '.*','page'=>'\d*', 'c'=>'\d*','t'=> '[a-z]?', 'orderby' => '.*', 'catName'=> '.*')));
//cuisine search discover
$routes->add('search32', new Route('/{lang}/{catName}-{cuisine}-{qr}-{l}_{np}_C{c}', array('lang' => 'en', 'pageName' => 'search-discover', 'qr' => null, 'np' => 1, 'c' => null, 'l' => null, 'cuisine' => null)
        , array('lang' => $allowed_lang,'qr' => '.*', 'np' => '\d*', 'c' => '\d', 'l' => '[a-z]?', 'cuisine' => '[^-]+')));
$routes->add('search3', new Route('/{catName}-{cuisine}-{qr}-{l}_{np}_C{c}', array( 'pageName' => 'search-discover', 'qr' => null, 'np' => 1, 'c' => null, 'l' => null, 'cuisine' => null)
        , array('qr' => '.*', 'np' => '\d*', 'c' => '\d', 'l' => '[a-z]?', 'cuisine' => '[^-]+')));
//cuisine search discover without cuisine
$routes->add('search12', new Route('/{lang}/{catName}-{qr}-{l}_{np}_C{c}', array('lang' => 'en', 'pageName' => 'search-discover','qr' => null,'np'=>1, 'c'=>null,'l'=> null)
        , array('lang' => $allowed_lang,'qr' => '.*','np'=>'\d*', 'c'=>'\d','l'=> '[a-z]?')));
$routes->add('search1', new Route('/{catName}-{qr}-{l}_{np}_C{c}', array( 'pageName' => 'search-discover','qr' => null,'np'=>1, 'c'=>null,'l'=> null)
        , array('qr' => '.*','np'=>'\d*', 'c'=>'\d','l'=> '[a-z]?')));
//hotel review
$routes->add('reviewH2', new Route('/{lang}/{hotelName}-review-H{id}', array('lang' => 'en', 'pageName' => 'hotel-review','hotelName' => null,'id'=>null)
        , array('lang' => $allowed_lang,'hotelName' => '.*','id'=>'\d*')));
$routes->add('reviewH', new Route('/{hotelName}-review-H{id}', array( 'pageName' => 'hotel-review','hotelName' => null,'id'=>null)
        , array('hotelName' => '.*','id'=>'\d*')));
//restaurant review
$routes->add('reviewR2', new Route('/{lang}/{restaurantName}-review-R{id}', array('lang' => 'en', 'pageName' => 'restaurant-review','restaurantName' => null,'id'=>null)
        , array('lang' => $allowed_lang,'restaurantName' => '.*','id'=>'\d*')));
$routes->add('reviewR', new Route('/{restaurantName}-review-R{id}', array( 'pageName' => 'restaurant-review','restaurantName' => null,'id'=>null)
        , array('restaurantName' => '.*','id'=>'\d*')));
//airport review
$routes->add('reviewA2', new Route('/{lang}/{airportName}-review-A{id}', array('lang' => 'en', 'pageName' => 'airport-review','airportName' => null,'id'=>null)
        , array('lang' => $allowed_lang,'airportName' => '.*','id'=>'\d*')));
$routes->add('reviewA', new Route('/{airportName}-review-A{id}', array( 'pageName' => 'airport-review','airportName' => null,'id'=>null)
        , array('airportName' => '.*','id'=>'\d*')));
//poi review
$routes->add('reviewT2', new Route('/{lang}/{things2doName}-review-T{id}', array('lang' => 'en', 'pageName' => 'things2do-review','things2doName' => null,'id'=>null)
        , array('lang' => $allowed_lang,'things2doName' => '.*','id'=>'\d*')));
$routes->add('reviewT', new Route('/{things2doName}-review-T{id}', array( 'pageName' => 'things2do-review','things2doName' => null,'id'=>null)
        , array('things2doName' => '.*','id'=>'\d*')));

//home page
$routes->add('page02', new Route('/{lang}/{pageName}', array('lang' => 'en', 'pageName' => 'index'), array('lang' => $allowed_lang)));
$routes->add('page0', new Route('/{pageName}', array( 'pageName' => 'index')));
//home page
$routes->add('page012', new Route('/{lang}/{pageName}/{q}', array('lang' => 'en', 'pageName' => 'index'), array('lang' => $allowed_lang, 'q' => '.*')));
$routes->add('page01', new Route('/{pageName}/{q}', array( 'pageName' => 'index'), array( 'q' => '.*')));

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $route = $matcher->match($request->getRequestUri());
 //   print_r($route);exit;
    if (isset($route['q']) && $route['q'] <> '')
        $request->query->set('q', $route['q']);
//    if(isset($route['lang'])) $_GET['lang'] = $route['lang']; else  $_GET['lang'] = 'en';
//    if (isset($route['q']) && $route['q'] <> '')
//        $_SERVER['QUERY_STRING'] = 'q=' . $route['q'];
//    $page = (!isset($route['page']) || $route['page'] == '') ? 'index' : $route['page'];
    global $dbConn;
    $query = "SELECT * FROM alias WHERE alias = :alias";
    $statement = $dbConn->prepare($query);
    $explode = explode('?', $route['pageName']);
    $pageName = $explode[0];
    $statement->bindParam(':alias', $pageName);
    $res = $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if($result) {
        $page= $result['entity_type'];
        $request->query->set('q', $result['entity_id']);
   } else {
        $page = $route['pageName'];
    }
//    foreach($route as $key=>$val)  $request->query->set($key, $val);
    if ($page == 'search' || $page == 'tubers') {
        if($route['_route'] == 'search2') {
            $request->query->set('qr', $route['qr']);
            $request->query->set('page', $route['page']);
            $request->query->set('c', $route['c']);
            $request->query->set('t', $route['t']);
            $request->query->set('orderby', $route['orderby']);
            $request->query->set('catName', $route['catName']);
        } else {
            $str = explode('&', $route['q']);
            foreach ($str as $mystr) {
                list ($key, $val) = explode('=', $mystr);
    //            $_GET[$key] = $val;
                $request->query->set($key, $val);
            }
        }
    }
    else if($page == 'hotel-review'){
        $request->query->set('id', $route['id']);
        $request->query->set('hotelName', $route['hotelName']);
    }
    else if($page == 'restaurant-review'){
        $request->query->set('id', $route['id']);
        $request->query->set('restaurantName', $route['hotelName']);
    }
    else if($page == 'airport-review'){
        $request->query->set('id', $route['id']);
        $request->query->set('airportName', $route['hotelName']);
    }
    else if($page == 'things2do-review'){
        $request->query->set('id', $route['id']);
        $request->query->set('things2doName', $route['hotelName']);
    }
//    else if( $page == 'hotel-review'){
//        $str = explode('/', $route['q']);
//        $ss = $str[2]."/".$str[0]."/".$str[1];
//        $route['q'] =$ss;
//        $route['_route'] = 'review0';
//        $request->query->set('q', $ss);
//    }

    if (!file_exists($page . '.php')) {
        $request->server->set('SCRIPT_FILENAME', 'notfound.php') ;
        include('notfound.php');
    } else {
        $request->server->set('SCRIPT_FILENAME', $page . '.php') ;
        include($page . '.php');
    }
} catch (ResourceNotFoundException $e) {
    print_r($e);
}