<?php
//ini_set("error_reporting", E_ALL);
//ini_set("display_errors", 1);

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function endsWith($haystack, $needle)
//{
//    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

$path = '../';
$bootOptions = array("loadDb" => 1, 'requireLogin' => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );

$term_get = $request->query->get('term','');
//if (!isset($_GET['term']))
if (!$term_get)
    die('');
//$term = db_sanitize(strtolower($_GET['term']));
$term = strtolower($term_get);
$term = remove_accents($term);
$term = trim($term);
//$t = db_sanitize($_GET['t']);
$t = $request->query->get('t','');

$ret = array();
$i = 0;
$ci_limit = 10;

//require('../vendor/autoload.php');
global $CONFIG;
$config = $CONFIG['solr_config'];

//$searchString ='+type:m +title_t1:('.$term.')';
$client = new Solarium\Client($config);
$client->setAdapter('Solarium\Core\Client\Adapter\Http');
$k=0;
if($t == 'u'){
    $query = $client->createSelect();
    $user_id = userGetID();
//    $searchString = '+((title_t1:'.$term.'* AND display_fullname:true) OR (user_name:'.$term.'* AND display_fullname:false)) +type:u +(allowed_users:"|-1||" OR allowed_users:"" OR allowed_users:*|'.$user_id.'|*)';
//    $searchString = '+(((first_name:'.$term.'* OR last_name:'.$term.'*) AND display_fullname:true) OR (user_name:'.$term.'* AND display_fullname:false)) +type:u +(allowed_users:"|-1||" OR allowed_users:"" OR allowed_users:*|'.$user_id.'|*)';
//    $searchString = 'type:u AND (allowed_users:"|-1||" OR allowed_users:"" OR allowed_users:*|'.$user_id.'|*)';
//    $searchString = '(((first_name:'.$term.'* OR last_name:'.$term.'*) AND display_fullname:true) OR (user_name:'.$term.'* AND display_fullname:false)) AND type:u AND (allowed_users:"|-1||" OR allowed_users:"" OR allowed_users:*|'.$user_id.'|*)';
//    $query->setQuery($searchString);
    $edismax = $query->getEDisMax();
//    $edismax->setQueryFields('first_name^40 user_name^40 last_name^10');
    $term = trim($term);
    $edismax->setBoostQuery('(((first_name:'.$term.'*^40 OR last_name:'.$term.'*^10) AND display_fullname:true) OR (user_name:'.$term.'*^40 AND display_fullname:false)) AND type:u AND (allowed_users:"|-1||" OR allowed_users:"" OR allowed_users:*|'.$user_id.'|*)');
//    $edismax->setBoostQuery('((first_name:'.$term.'*^40 OR last_name:'.$term.'*^10) AND display_fullname:true) OR (user_name:'.$term.'*^40 AND display_fullname:false)');
//    $edismax->setQueryFields('first_name^100 last_name^50 user_name^100');

//    $query->addSort('title_t1 asc');
    $resultset = $client->select($query);
//    $total = $resultset->getNumFound();
//    mail('elie@paravision.org', 'total', $total);
//    $res = array();
    $main_res = array();
    $sub_res = array();
    foreach($resultset as $document){
        if($document->user_name == '')
            continue;
        $id=$document->id;
        $user_name=$document->user_name;
        $title_t1 = $document->display_fullname ? strtolower($document->title_t1) : strtolower($document->user_name);
        $profile_pic = $document->profile_pic;
        if($profile_pic==''){
            $profile_pic = 'tuber.jpg';
        }
        if(startsWith($title_t1, $term))
            $main_res[] = array($title_t1,$id,$profile_pic,$user_name);
        else
            $sub_res[] = array($title_t1,$id,$profile_pic,$user_name);
//        $res[] = array($title_t1,$id,$profile_pic,$user_name);        
    }    
    sort($main_res);
    sort($sub_res);
    $res = array_merge($main_res, $sub_res);
    //sort($res);
    foreach($res as $item_data){
        $id= $item_data[1];
        $item= $item_data[0];
        $profile_pic = $item_data[2];
        $user_name = $item_data[3];        
        $uslnk= ReturnLink('profile/' . $user_name);
        $item_dis=$item;
        if(strlen($item_dis) > 33){
            $item_dis = substr($item_dis,0,30).' ...';
         }
        $dkey = '<img src="'. ReturnLink('media/tubers/xsmall_' . $profile_pic) .'" alt="'.$item.'" class="autocompletImage"/><span class="search_suggestion_text">'.$item_dis.'</span>';
        $ret[$k]['value_display'] = htmlEntityDecode($item);
        $ret[$k]['value'] = $item;
        $ret[$k]['us_link'] = $uslnk;
        $ret[$k]['id'] = $id;
        $ret[$k]['label'] = $dkey;
        $k++;
    }
//    foreach($resultset as $document){
//        $ret[$k]['value_display'] = $document->title_t1;
//        $ret[$k]['value'] = $document->title_t1;
//        $k++;
//    }
}
else{
//    $query = $client->createSuggester();
//    $query->setHandler('searchsuggest');
//    $query->addParam('spellcheck.q', $term);
//    $suggesterResults = $client->suggester($query);
//    $resultset = $suggesterResults->getResults();
//    foreach($resultset as $key => $termResult){
//        foreach($termResult as $result){
//            $ret[$k]['value_display'] = $result;
//            $ret[$k]['value'] = $result;
//            $k++;
//        }
//    }
//    foreach($resultset as $document){
//        $ret[$k]['value_display'] = $document->search_suggest;
//        $ret[$k]['value'] = $document->search_suggest;
//        $k++;
//    }
    
    $locationQuery = $client->createSelect();
    $locationString = "(type:co OR type:ct) AND (title_t1:$term*) AND media_count:[1 TO *]";
    $locationEdismax = $locationQuery->getEDisMax();
    $location_query_string = "type:ct^2 OR type:co^1";
    $locationQuery->setQuery($locationString);
    $locationEdismax->setBoostQuery($location_query_string);
    $locationQuery->addSort('media_count', $query::SORT_DESC);
    $locationQuery->setRows(4);
    $locationResultSet = $client->select($locationQuery);
//    $locationRet = array();
    $pos = 0;
    $ctRet = array();
    $coRet = array();
    foreach($locationResultSet as $document){
        if($document->media_count == 0) { continue; }
        $country = $document->type == 'ct' ? $document->country_t1 : '';
        $locationRet[] = array($document->title_t1, $country);
        if($document->type == 'ct'){
            $ctRet[] = array($document->title_t1, $document->country_t1);
        }
        else{
            $coRet[] = array($document->title_t1, '');
        }
        $pos++;
    }
    sort($ctRet);
    sort($coRet);
    $locationRet = array_merge($ctRet, $coRet);
//    sort($locationRet);
    $ind = 0;
    foreach($locationRet as $item){
        $final = $item[0];
//        $final = !empty($item[1]) ? $item[0].', '.$item[1] : $item[0];
        $ret[$ind]['value_group'] = $final;
        $ret[$ind]['value_display'] = htmlEntityDecode($final);
        $ret[$ind]['value'] = $final;
        $ind++;
    }
    $k = $pos;
    $lang = LanguageGet();
    $langstring = "(+(lang:$lang lang:xx) -(+lang:xx +$lang:1)) AND ";
    $privacyString = "$langstring is_public:2 AND ";
    if(userIsLogged()){
        $userId = userGetID();
        $privacyString = "$langstring (is_public:2 OR allowed_users:*|$userId|*) AND ";
    }
    
    $select = array(
        'query' => $privacyString."(title_t1: *$term*) AND type:m",
        'fields' => array('title_t1, search_suggest')
    );
    $query = $client->createSelect($select);
    
    $groupComponent = $query->getGrouping();
    $groupComponent->addField('search_suggest');
    $groupComponent->setLimit(1);
    $groupComponent->setNumberOfGroups(true);
    
    $resultset = $client->select($query);
    
    $groups = $resultset->getGrouping();
    
    foreach($groups AS $groupKey => $fieldGroup) {
        foreach($fieldGroup AS $valueGroup) {
            foreach($valueGroup AS $document) {
                if($k == 10) break;
                $ret[$k]['value_group'] = $valueGroup->getValue();
                $ret[$k]['value_display'] = htmlEntityDecode($document->title_t1);
                $ret[$k]['value'] = $document->title_t1;
                $k++;
            }
            
        }
    }
}
echo json_encode($ret);