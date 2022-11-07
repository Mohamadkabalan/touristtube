<?php
$expath = "../";
header('Content-type: application/json');
include($expath."heart.php");
$submit_post_get = array_merge($request->query->all(),$request->request->all());
$user_id = mobileIsLogged($submit_post_get['S']);

$cid = intval($submit_post_get['album_id']);
$ret = array();

if( $user_id != userCatalogOwner($cid) ){
        $ret['status'] = 'error';
}else{
    $srch_options = array(
        'userid' => $user_id,
        'catalog_id' => $cid,
        'search_strict' => 0,
        'limit' => null,
        'type' => 'a'
    );

    $ret = mediaSearch($srch_options);	
    $i=0;
    $len=count($ret);

    if ( ($id = userCatalogDelete($cid))  ){
        for($i=0;$i<$len;$i++){
            $mydata=$ret[$i];			
            videoDelete($mydata['id']);
        }
        $ret['status'] = 'ok';
    }else{
        $ret['status'] = 'error';
    }
}

echo json_encode($ret);