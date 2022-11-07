<?php
/*! \file
 * 
 * \brief This api for searching any media
 * 
 * 
 * @param S session id
 * @param search search value
 * @param limit number of records to return
 * @param page page number (starting from 0)
 * @param type type of search
 * @param orderby the order by field
 * @param order the order either asc or desc
 * @param longitude longitude
 * @param latitude latitude
 * @param radius radius
 * @param category_id category id
 * @param noCache true/false determins if image should be retrieved from cach
 * @param KeepRatio 1/0 not used
 * 
 * @return list with the following keys:
 * @return <pre> 
 * @return       <b>categories</b> categories
 * @return       <b>numFound</b> number of records
 * @return       <b>n_videos</b> number of videos
 * @return       <b>n_images</b> number of images
 * @return       <b>corrections</b> List of corrections (array)
 * @return             <b>0</b> 
 * @return       <b>totalPages</b> number of pages
 * @return       <b>media</b> List of information of the media (array)
 * @return             <b>id</b> media id
 * @return             <b>title</b> media title
 * @return             <b>image_video</b> media type (image or video)
 * @return             <b>location</b> media location
 * @return             <b>nViews</b> media number of views
 * @return             <b>pdate</b> media upload date
 * @return             <b>up_vote</b> media number of likes
 * @return             <b>nb_comments</b>  media number of comment
 * @return             <b>description</b>  media description
 * @return             <b>rating</b>  media average rating
 * @return             <b>name</b>  media name
 * @return             <b>fullpath</b>  media full path
 * @return             <b>relativepath</b>  media relative path
 * @return             <b>category</b>  media category
 * @return             <b>country</b>  media country
 * @return             <b>cityname</b>  media cityname
 * @return             <b>placetakenat</b>  media place taken at
 * @return             <b>nb_rating</b>  media number of ratings
 * @return             <b>video_url</b>  <u>only for videos</u>  video url
 * @return             <b>videolink</b>  <u>only for videos</u>  video link
 * @return             <b>duration</b>   <u>only for videos</u>  video duration
 * @return             <b>fulllink</b>   <u>only for thumb</u>  thumb full link
 * @return             <b>duration</b>   media thumb Link
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */



/**
 * SOLR ACTIVE OPTIONS
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>type</b>: what type of media file (v)ideo or (i)mage or (a)ll or (u)ser. default 'v'<br/>
 * <b>search_string</b>: the string to search for. could be space separated. no default<br/>
 * 
 * 
* search for videos given certain options. options include:<br/>
* 
* 
* <b>public</b>: wheather the media file is public or not. default 1<br/>
* <b>userid</b>: the media file's owner's id. default null<br/>
* <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
* <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
* <b>latitude</b>: the latitude of the location to search within<br/>
* <b>longitude</b>: the logitude of the location to search within<br/>
* <b>radius</b>: the radius to search within (in meters)<br/>
* <b>dist_alg</b>: the distance algorithm to use (s)quare [faster], or (c)ircular [slower]. default is 's'<br/>
* <b>search_where</b>: where to search for the string (t)itle, (d)escription, (k)eywords, (a)ll, or a comma separated combination. default is 'a'<br/>
* <b>max_id<b/>: get records less than this one. (implied orderby 'id' and order 'd'),
* <b>min_id<b/>: get records greater than this one. (implied orderby 'id' and order 'a'),
* @param array $srch_options. the search options
* @return array a number of media records
*/
header('Content-type: application/json');
require_once("heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//if(empty($_REQUEST['search']) && !isset($_REQUEST['category_id'])){
if(empty($submit_post_get['search']) && !isset($submit_post_get['category_id'])){
    $res = array("categories"=>array(),"numFound"=>0,"n_videos"=>0,"n_images"=>0,"totalPages"=>0,"media"=>array());
    echo json_encode($res);
    exit();
}
$whatigot = "";
foreach($_REQUEST as $kkk=>$aaa)
{
    $whatigot .= $kkk."=".$aaa."&";
}
file_put_contents("searchsearch.txt",$whatigot);

//header("content-type: application/xml; charset=utf-8"); 



$limit = 20;
//if (isset($_REQUEST['limit']) && intval($_REQUEST['limit'])>0) $limit = $_REQUEST['limit'];
if (isset($submit_post_get['limit']) && intval($submit_post_get['limit'])>0) $limit = $submit_post_get['limit'];


$page = 1;
//if (isset($_REQUEST['page']) && intval($_REQUEST['page'])>0)$page = $_REQUEST['page'];
if (isset($submit_post_get['page']) && intval($submit_post_get['page'])>0)$page = $submit_post_get['page'];

//"uid"
// userid
$uid = null;
$userId = null;
//if (isset($_REQUEST['uid']) && intval($_REQUEST['uid'])>0)
//$userId = mobileIsLogged($_REQUEST['S']);
if (isset($submit_post_get['S']) && $submit_post_get['S'])
	$userId = mobileIsLogged($submit_post_get['S']);

if($userId){
    $uid = $userId;
}

define("TYPE_IMAGE",'i');
define("TYPE_VIDEO",'v');
define("TYPE_USER",'u');
define("TYPE_BOTH",'a');

$type = 'a';
//if (isset($_REQUEST['type']))
//{
//    switch(intval($_REQUEST['type']))
if (isset($submit_post_get['type']))
{
    switch(intval($submit_post_get['type']))
    {
        case 0 :	$type = constant("TYPE_BOTH");
        break;	
        case 1 :	$type = constant("TYPE_VIDEO");
        break;
        case 2 :	$type = constant("TYPE_IMAGE");
        break;
        case 3 :	$type = constant("TYPE_USER");
        break;
    }
}

$orderby = "similarity";
//if (isset($_REQUEST['orderby']))
//{
//    switch(intval($_REQUEST['orderby']))
if (isset($submit_post_get['orderby']))
{
    switch(intval($submit_post_get['orderby']))
    {
        case 1 :  $orderby="id"; break;	
        case 2 :  $orderby="duration"; break;	
        case 3 :  $orderby="rating"; break;	
        case 5 :  $orderby="like_value"; break;	
        case 4 :  $orderby="like_value"; break;	
        case 6 :  $orderby="down_votes"; break;
        case 7 :  $orderby="similarity"; break;
        case 8 :  $orderby="nb_views"; break;
        case 9 :  $orderby="pdate"; break;
        case 10 :  $orderby="title"; break;
    }
}

//"order"
//0 for desc
//1 for asc 
$order = "asc";
//if (isset($_REQUEST['order']) && intval($_REQUEST['order'])==0) $order = 'desc';
if (isset($submit_post_get['order']) && intval($submit_post_get['order'])==0) $order = 'desc';


$long = null;
$lat = null;
$radius = 1000;
//if((isset($_REQUEST['longitude'])) && (isset($_REQUEST['latitude'])))
//{
//$long = doubleval($_REQUEST['longitude']);
//$lat = doubleval($_REQUEST['latitude']);
//$radius = intval($_REQUEST['radius']);
if((isset($submit_post_get['longitude'])) && (isset($submit_post_get['latitude'])))
{
$long = doubleval($submit_post_get['longitude']);
$lat = doubleval($submit_post_get['latitude']);
$radius = intval($submit_post_get['radius']);
}

$search = "";
//if (isset($_REQUEST['search']))
//{
//$search = $_REQUEST['search'];
//}
if (isset($submit_post_get['search']))
{
$search = $submit_post_get['search'];
}
if ($search=="")
{
$search=null;	
}

$catid = null;
//if (isset($_REQUEST['category_id']))
//{
//$catid = $_REQUEST['category_id'];
//}
if (isset($submit_post_get['category_id']))
{
$catid = $submit_post_get['category_id'];
}
$size = 'm';
$noCache = false;
$keepRatio = '1';
//if (isset($_REQUEST['size'])) {
//    $size = xss_sanitize($_REQUEST['size']);
//}
//if (isset($_REQUEST['noCache'])) {
//    $noCache = xss_sanitize($_REQUEST['noCache']);
//}
//if (isset($_REQUEST['keepRatio'])) {
//    $keepRatio = intval(xss_sanitize($_REQUEST['keepRatio'])) ? xss_sanitize($_REQUEST['keepRatio']) : '1';
//}
if (isset($submit_post_get['size'])) {
    $size = $submit_post_get['size'];
}
if (isset($submit_post_get['noCache'])) {
    $noCache = $submit_post_get['noCache'];
}
if (isset($submit_post_get['keepRatio'])) {
    $keepRatio = intval($submit_post_get['keepRatio']) ? $submit_post_get['keepRatio'] : '1';
}

$options = array(
    'q' => $search,
    't' => $type,
    'c' => $catid,
    'limit' => $limit,
    'page' => $page,
    'orderby' => $orderby,
    'order' => $order
);
if($uid){
    $options['userid'] = $uid;
}
//print_r($options);exit();
$data = SolrSearch($options, $size, $noCache, $keepRatio);

// $pager_sql=mediaSearchNew($srch_options);
//$data = SolrSearch($options);
//debug($data);
$data1 = json_encode($data);
echo $data1;

function SolrSearch($srch_options, $size, $noCache, $keepRatio){
    global $path;
    global $CONFIG;
    require($path . 'vendor/autoload.php');
    $userVideos = $ct = array();
    $config = $CONFIG['solr_config'];
    $order = $srch_options['order'];
    $lang = LanguageGet();
    $langstring = "(+(lang:$lang lang:xx) -(+lang:xx +$lang:1)) AND ";
    $client = new Solarium\Client($config);
    $client->setAdapter('Solarium\Core\Client\Adapter\Http');
    $query = $client->createSelect();
    $helper = $query->getHelper();
    $privacyString = "$langstring is_public:2";
    if (isset($srch_options['userid'])) {
        $userId = $srch_options['userid'];
        $privacyString = "$langstring (is_public:2 OR allowed_users:*|$userId|*)";
    }

    $searchString = "$privacyString AND type:m";
    $qq = $srch_options['q'];
    $qq = $helper->escapeTerm($qq);
    search_log($qq, 'W_MEDIA');
    if ($qq <> '')
        $searchString .= " AND( title_t1:'$qq' OR description_t1:'$qq' OR city_name_accent:'$qq' )";
    $catSearchString = $searchString;
    if (isset($srch_options['t']) && $srch_options['t'] <> '' && $srch_options['t'] <> 'a')
        $catSearchString .= " +mtype:" . $srch_options['t'];
    $cat_query = $client->createSelect();
    $cat_query->setQuery($catSearchString);
    $cat_facetSet = $cat_query->getFacetSet();
    $cat_facetSet->createFacetField('cat_id')->setField('cat_id');
    $cat_result = $client->select($cat_query);
    $cat_facet = $cat_result->getFacetSet()->getFacet('cat_id');
    $categories = array();
    foreach ($cat_facet as $value => $count) {
        if ($count > 0) {
            $categories[] = $value;
        }
    }
    $userVideos['categories'] = $categories;
    if (isset($srch_options['c']) && $srch_options['c'] <> '' && $srch_options['c'] <> 0)
        $searchString .= " +cat_id:" . $srch_options['c'];

    try {
        $query = $client->createSelect();
        $query->setQuery($searchString);
        $facetSet = $query->getFacetSet();
        $facetSet->createFacetField('mtype')->setField('mtype');
        $resultset = $client->select($query);
        $userVideos['numFound'] = $resultset->getNumFound();
        $facet = $resultset->getFacetSet()->getFacet('mtype');
        foreach ($facet as $value => $count)
            $ct[$value] = $count;
        $userVideos['n_videos'] = $ct['v'];
        $userVideos['n_images'] = $ct['i'];

        $query = $client->createSelect();
        if (isset($srch_options['t']) && $srch_options['t'] <> '' && $srch_options['t'] <> 'a')
            $searchString .= " AND mtype:" . $srch_options['t'];
        $spellcheck = $query->getSpellcheck();
        $spellcheck->setQuery($qq);
        $spellcheck->setBuild(true);
        $spellcheck->setCollate(true);

        $query->setStart(($srch_options['page'] - 1) * $srch_options['limit']);
        $query->setRows($srch_options['limit']);
        switch ($srch_options['orderby']) {
            case 'id' : $query->addSort("id $order");
                break;
            case 'pdate': $query->addSort("pdate $order");
                break;
            case 'title': $query->addSort("search_suggest $order");
                break;
            case 'like_value': $query->addSort("like_value $order");
                break;
            case 'nb_views': $query->addSort("nb_views $order");
                break;
            case 'rating' : $query->addSort("rating $order");
                break;
            case 'similarity': $searchString = "{!MediaSearch}" . $searchString;
                break;
//            default: $searchString = "{!MediaSearch}" . $searchString;
        }
        $query->setQuery($searchString);
        $resultset = $client->select($query);
        $spellcheckResult = $resultset->getSpellcheck();
        if (isset($spellcheckResult) && !$spellcheckResult->getCorrectlySpelled()) {
            $collation = $spellcheckResult->getCollation();
            $k = 1;
            if (isset($collation)) {
                $userVideos['corrections'][0] = $collation->getQuery();
                if ($k > 0) {
                    $new_term = $userVideos['corrections'][0];
                    $searchString = "$privacyString AND type:m";
                    $searchString .= " AND ( title_t1:'$new_term' OR description_t1:'$new_term' OR city_name_accent:'$new_term' )";
                    $catSearchString = $searchString;
                    if (isset($srch_options['t']) && $srch_options['t'] <> '' && $srch_options['t'] <> 'a')
                        $catSearchString .= " AND mtype:" . $srch_options['t'];
                    $cat_query = $client->createSelect();
                    $cat_query->setQuery($catSearchString);
                    $cat_facetSet = $cat_query->getFacetSet();
                    $cat_facetSet->createFacetField('cat_id')->setField('cat_id');
                    $cat_result = $client->select($cat_query);
                    $cat_facet = $cat_result->getFacetSet()->getFacet('cat_id');
                    $categories = array();
                    foreach ($cat_facet as $value => $count) {
                        if ($count > 0) {
                            $categories[] = $value;
                        }
                    }
                    $userVideos['categories'] = $categories;
                    $query = $client->createSelect();
                    if (isset($srch_options['t']) && $srch_options['t'] <> '' && $srch_options['t'] <> 'a')
                        $searchString .= " AND mtype:" . $srch_options['t'];
                    $query->setStart(($srch_options['page'] - 1) * $srch_options['limit']);
                    $query->setRows($srch_options['limit']);
                    switch ($srch_options['orderby']) {
                        case 'id' : $query->addSort("id $order");
                            break;
                        case 'pdate': $query->addSort("pdate $order");
                            break;
                        case 'title': $query->addSort("search_suggest $order");
                            break;
                        case 'title': $query->addSort("search_suggest $order");
                            break;
                        case 'like_value': $query->addSort("like_value $order");
                            break;
                        case 'nb_views': $query->addSort("nb_views $order");
                            break;
                        case 'rating' : $query->addSort("rating $order");
                            break;
                        case 'similarity': $searchString = "{!MediaSearch}" . $searchString;
                            break;
            //            default: $searchString = "{!MediaSearch}" . $searchString;
                    }
                    $query->setQuery($searchString);
                    $facetSet = $query->getFacetSet();
                    $facetSet->createFacetField('mtype')->setField('mtype');
                    $resultset = $client->select($query);
                    $userVideos['numFound'] = $resultset->getNumFound();
                    $facet = $resultset->getFacetSet()->getFacet('mtype');
                    foreach ($facet as $value => $count)
                        $ct[$value] = $count;
                    $userVideos['n_videos'] = $ct['v'];
                    $userVideos['n_images'] = $ct['i'];
                }
            }
        }
        $userVideos['totalPages'] = ceil($resultset->getNumFound() / $srch_options['limit']);
        $k = 0;
        $userVideos['media'] = array();
        foreach ($resultset as $document) {
            $userVideos['media'][$k]['id'] = $document->id;
            $userVideos['media'][$k]['title'] = $document->title_t1;
            $userVideos['media'][$k]['image_video'] = $document->mtype;
            $userVideos['media'][$k]['location'] = $document->location_t;
            $userVideos['media'][$k]['n_views'] = $document->nb_views . "";
            $userVideos['media'][$k]['pdate'] = str_replace('Z',' ',str_replace('T',' ',$document->pdate));
            $userVideos['media'][$k]['up_vote'] = $document->like_value . "";
            $userVideos['media'][$k]['nb_comments'] = $document->nb_comments . "";
            $userVideos['media'][$k]['description'] = $document->description_t1;
            $userVideos['media'][$k]['rating'] = $document->rating . "";
            $userVideos['media'][$k]['name'] = $document->name_t;
            $userVideos['media'][$k]['code'] = $document->code_m;
            $userVideos['media'][$k]['fullpath'] = $document->fullpath_m;
            $userVideos['media'][$k]['relativepath'] = $document->relativepath_m;
            $userVideos['media'][$k]['category'] = $document->category_t;
            $userVideos['media'][$k]['country'] = $document->country_name;
            $userVideos['media'][$k]['cityname'] = $document->city_name;
            $userVideos['media'][$k]['placetakenat'] = $document->placetakenat ? $document->placetakenat : "";
            $userVideos['media'][$k]['nb_rating'] = $document->nb_ratings . "";
            if($document->mtype == 'v'){
                $thumbLink = substr(getVideoThumbnail($document->id, $path . $document->fullpath_m, 0), strlen($path));
                $userVideos['media'][$k]['video_url'] = $document->url;
                $userVideos['media'][$k]['videolink'] = $thumbLink;
                $userVideos['media'][$k]['duration'] = $document->duration;
                if($userId){
                    //isfriend
                    //isfollowed
                    //isLiked
                    //myrating
                }
            }
            else{
                $thumbLink = $document->fullpath_m . $document->name_t;
                $userVideos['media'][$k]['fulllink'] = $thumbLink;
            }
            $userVideos['media'][$k]['thumbLink'] = resizepic($thumbLink, $size, $noCache, $keepRatio);
            $k++;
        }
        return $userVideos;
    } catch (Exception $exception) {
        echo $exception;
        return $exception;
    }
}
